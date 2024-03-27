<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Group class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Smart extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board','Cart');

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
        $this->load->library(array('querystring', 'funn', 'alimtalk_v'));
	}

	//스마트전단 보기
	public function view($code = ""){
	    $add = $this->input->get("add"); //스킨 추가
		$skin = "main";
		if($add != "") $skin .= $add;

		$view = array();
		$view['code'] = $code; //스마트전단 코드
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부

        //홈코드 (뒤로가기 추가) 22-04-07 윤재박
        $view['home'] = ($this->input->get("home")) ? $this->input->get("home") : ""; //홈코드

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
			, b.tem_imgpath /* 템플릿 이미지 경로 */
			, b.tem_bgcolor /* 템플릿 배경색 */
			, b.tem_useyn /* 템플릿 사용여부(Y.사용, N.사용안함, S.직접입력) */
		FROM cb_pop_screen_data a
		LEFT JOIN cb_design_templet b ON a.psd_tem_id = b.tem_id
		WHERE psd_code = '". $view['code'] ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['screen_data'] = $this->db->query($sql)->row();
		$psd_id = $view['screen_data']->psd_id; //전단번호
		$mem_id = $view['screen_data']->psd_mem_id; //회원번호
		$psd_ver_no = $view['screen_data']->psd_ver_no; //버전번호
        $psd_cnt = $view['screen_data']->psd_goods_cnt;

        $view['psd_mode'] = "N";

        $view['psd_mode'] = "N";
        if($psd_cnt==0){
            $sql = "
    		SELECT
    			count(1) as cnt
    		FROM cb_pop_screen_goods
    		WHERE psg_psd_id = '". $psd_id ."' AND psg_mem_id = '".$mem_id."'";
            $psd_cnt = $this->db->query($sql)->row()->cnt;
            if($psd_cnt>1000){
                $view['psd_mode'] = "L";
            }
        }else{
            if($psd_cnt>1000){
                $view['psd_mode'] = "L";
            }
        }

        if($mem_id=="2"||$mem_id=="1789"){
            $skin = "main_test0";
        }

        $in_ip = $_SERVER['REMOTE_ADDR'];
        // if($mem_id=="3"||$mem_id=="2464"){// if($in_ip=="61.75.230.209"){
        //     $skin = "main_t_0";
        //     // $skin = "main_type";
        // }
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		//주문설정 정보 2021-03-25
		$sql = "select * from cb_orders_setting where mem_id = '". $mem_id ."' ";
		$view['os'] = $this->db->query($sql)->row();

		//세션생성
		$_SESSION['shop_mem_id'] = $view['shop']->mem_id;
		$_SESSION['shop_mem_userid'] = $view['shop']->mem_userid;
		if($view['shop']->mem_userid == "dhn"){
			//echo "_SESSION['shop_mem_id'] : ". $_SESSION['shop_mem_id'] ."<br>_SESSION['shop_mem_userid'] : ". $_SESSION['shop_mem_userid'] ."<br>";
		}

        if(!empty($view['screen_data']->psd_goods)){
            //할인&대표상품 등록 조회

            $psd_goods = json_decode($view['screen_data']->psd_goods);


            $view['screen_step1'] = "";
            if($view['screen_data']->psd_step1_yn=="Y"){

                $view['screen_step1'] = (object)$psd_goods->pdata;
            }

    		//코너별 상품등록 조회
            $screen_box_Array = array();


            $screen_conner_Array = array();

            $view['screen_box'] = (object)$psd_goods->data;

    		$view['tit_text_info'] = (object)$psd_goods->tdata;

        }else{
            // if($mem_id=="3"||$mem_id=="2464"){// if($in_ip=="61.75.230.209"){
                //할인&대표상품 등록 조회
        		$sql = "
        		SELECT a.*
        			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
        		FROM cb_pop_screen_goods a
        		WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
        		and psg_psd_id = '". $psd_id ."' /* 전단번호 */
        		and psg_step = '1' /* 스텝 */ and psg_step_no = 0
        		ORDER BY psg_seq ASC ";
            // }else{
            //     //할인&대표상품 등록 조회
        	// 	$sql = "
        	// 	SELECT a.*
        	// 		, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
        	// 	FROM cb_pop_screen_goods a
        	// 	WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
        	// 	and psg_psd_id = '". $psd_id ."' /* 전단번호 */
        	// 	and psg_step = '1' /* 스텝 */
        	// 	ORDER BY psg_seq ASC ";
            // }

    		//echo $sql ."<br>";
    		$view['screen_step1'] = $this->db->query($sql)->result();


    		if($psd_ver_no == 1){ //버전 1의 경우
    			$sort = "psg_step ASC,  psg_step_no ASC, psg_seq ASC";
    			$sort_t = "pst_step ASC,  pst_step_no ASC, pst_seq ASC";
    		}else{
    			$sort = "psg_step_no ASC, psg_seq ASC";
    			$sort_t = "pst_step_no ASC, pst_seq ASC";
    		}

    		$limit500s = "";

    		if($mem_id == "1789"){
    			// $limit500s = " limit 700 ";
                $view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 500;
    		    $view['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
    			$limit500s = " LIMIT ".(($view['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";


                $sql = "
        		SELECT count(1) as cnt
        		FROM cb_pop_screen_goods a
        		WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
        		AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
        		AND psg_step > 1 /* 스텝 */
        		ORDER BY ". $sort ." ";

                $view["total"] = $this->db->query($sql)->row()->cnt;
                $view["total"] = ceil($view["total"] / $view['perpage']);
    		}else{

    		}
            // if($mem_id=="3"||$mem_id=="2464"){// if($in_ip=="61.75.230.209"){
                //코너별 상품등록 조회
        		$sql = "
        		SELECT a.*
        			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_tit_id LIMIT 1) AS tit_imgpath /* 코너별 이미지 */
                    , (SELECT tit_type FROM cb_design_title dt WHERE dt.tit_id = a.psg_tit_id LIMIT 1) AS tit_type
        			, (SELECT COUNT(1) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND b.psg_step_no = a.psg_step_no) AS rownum /* 코너별 등록수 */
        			, (SELECT COUNT(1) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND psg_seq = 0) AS gronum /* 코너수 */
        			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
                    , c.pst_tit_text_info /*바로가기텍스트정보*/
                    , c.pst_tit_bgcolor as tit_bgcolor
                    , c.pst_tit_txcolor as tit_txcolor
                    , c.pst_tit_align as tit_align
                    , c.pst_sub_title as sub_title
                    , c.pst_sub_title_size as sub_title_size
                    , c.pst_sub_title_color as sub_title_color
        		FROM cb_pop_screen_goods a
                left join cb_pop_screen_text c on a.psg_psd_id =c.pst_psd_id  AND a.psg_step_no= c.pst_step_no AND c.pst_mem_id = '". $mem_id ."' AND c.pst_seq = 0
        		WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
        		AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
        		AND psg_box_no > 0 /* 스텝 */
        		ORDER BY ". $sort ." ".$limit500s;
            // }else{
            //     //코너별 상품등록 조회
        	// 	$sql = "
        	// 	SELECT a.*
        	// 		, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_tit_id LIMIT 1) AS tit_imgpath /* 코너별 이미지 */
        	// 		, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND b.psg_step_no = a.psg_step_no) AS rownum /* 코너별 등록수 */
        	// 		, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND psg_seq = 0) AS gronum /* 코너수 */
        	// 		, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
        	// 	FROM cb_pop_screen_goods a
        	// 	WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
        	// 	AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
        	// 	AND psg_step > 1 /* 스텝 */
        	// 	ORDER BY ". $sort ." ".$limit500s;
            // }

    		//echo $sql ."<br>";
    		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
    		$view['screen_box'] = $this->db->query($sql)->result();
            // log_message("error", "sadfsadfsafsadf  : ".$sql);

    		//코너별 바로가기텍스트 조회

    		if($mem_id == "1789"){
    			$sql = "
    			SELECT DISTINCT pst_box_no
    			, pst_tit_text_info /* 코너별 타이틀텍스트 정보 */ from (select * from
    		(SELECT  pst_box_no
    			, pst_tit_text_info /* 코너별 타이틀텍스트 정보 */
    		FROM cb_pop_screen_text
    		WHERE pst_mem_id = '". $mem_id ."' /* 회원번호 */
    		AND pst_psd_id = '". $psd_id ."' /* 전단번호 */
    		AND pst_step > 1 /* 스텝 */
    		ORDER BY  ". $sort_t ." ".$limit500s.") t ) tt ";
    		}else{

                // if($mem_id=="3"||$mem_id=="2464"){// if($in_ip=="61.75.230.209"){
        			$sql = "
        			SELECT DISTINCT pst_box_no
        				, pst_tit_text_info /* 코너별 타이틀텍스트 정보 */
        			FROM cb_pop_screen_text
        			WHERE pst_mem_id = '". $mem_id ."' /* 회원번호 */
        			AND pst_psd_id = '". $psd_id ."' /* 전단번호 */
        			AND pst_step_no > 0 /* 스텝 */
        			ORDER BY ". $sort_t ." ";
                // }else{
                //     $sql = "
        		// 	SELECT DISTINCT pst_box_no
        		// 		, pst_tit_text_info /* 코너별 타이틀텍스트 정보 */
        		// 	FROM cb_pop_screen_text
        		// 	WHERE pst_mem_id = '". $mem_id ."' /* 회원번호 */
        		// 	AND pst_psd_id = '". $psd_id ."' /* 전단번호 */
        		// 	AND pst_step > 1 /* 스텝 */
        		// 	ORDER BY ". $sort_t ." ";
                // }
    		}


    		$view['tit_text_info'] = $this->db->query($sql)->result();
    		// $tit_text_info = $view['screen_box']->tit_text_info;
    		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tit_text_info : ". $sql);
        }

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

	//스마트쿠폰 보기
	public function coupon_bk($code = ""){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "coupon";
		if($add != "") $skin .= $add;

		$view = array();
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
		FROM cb_pop_coupon_data a
		WHERE pcd_code = '". $code ."'
		AND pcd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data'] = $this->db->query($sql)->row();
		$psd_id = $view['data']->psd_id; //전단번호
		$mem_id = $view['data']->psd_mem_id; //회원번호
		$psd_ver_no = $view['data']->psd_ver_no; //버전번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

    //스마트쿠폰 보기
	public function coupon($code = ""){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "coupon";
		if($add != "") $skin .= $add;

		$view = array();
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부


        $sql = "
		SELECT *
		FROM cb_pop_coupon_data a
        left join cb_pop_coupon_data_detail b on a.pcd_id = b.pcdd_parent_id
		WHERE a.pcd_code = '". $code ."'
		AND a.pcd_useyn = 'Y' ";
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data'] = $this->db->query($sql)->result();
        //
        $mem_id = $view['data'][0]->pcd_mem_id; //회원번호


		//스마트전단 데이타
		// $sql = "
		// SELECT
		// 	  a. *
		// FROM cb_pop_coupon_data a
		// WHERE pcd_code = '". $code ."'
		// AND pcd_useyn = 'Y' ";
		// //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		// //echo $sql ."<br>";
		// $view['data'] = $this->db->query($sql)->row();
		// $psd_id = $view['data']->psd_id; //전단번호
		// $psd_ver_no = $view['data']->psd_ver_no; //버전번호
		// echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

    //스마트쿠폰 보기
	public function eveview($code = ""){
	    // $add = $this->input->get("add"); //스킨 추가

		$skin = "eveview";
		// if($add != "") $skin .= $add;

		$view = array();
        $view['code'] = $code;
		// $view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부

        $info_code = $this->input->get("code");

        if(!empty($info_code)&&$info_code!="preview"){
            $sql = "
    		SELECT
    			  *
    		FROM cb_evemaker_send_data
    		WHERE esd_code = '". $info_code ."' ";
            $view['info_data'] = $this->db->query($sql)->row();

            if(!empty($view['info_data'])&&$view['info_data']->esd_state=="0"&&empty($view['info_data']->esd_view_date)){
                $goodsget = array();
                $goodsget['esd_state'] = "1";
                $goodsget['esd_view_date'] = date("Y-m-d H:i:s");

                $where = array();
                $where['esd_code'] = $info_code;
                $where['esd_state'] = '0';
				$this->db->update('cb_evemaker_send_data', $goodsget, $where);
            }
        }

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
		FROM cb_evemaker_data a
		WHERE emd_code = '". $code ."'
		AND emd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data'] = $this->db->query($sql)->row();
		$emd_id = $view['data']->emd_id; //전단번호
		$mem_id = $view['data']->emd_mem_id; //회원번호


        $sql = "
		SELECT
			  a. *
		FROM cb_evemaker_goods a
		WHERE emg_emd_id = '". $emd_id ."' and emg_mem_id = '". $mem_id ."'
		AND emg_useyn = 'Y' ";

        $view['goods_data'] = $this->db->query($sql)->result();
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
        $view['nameArr'] = "";
        $view['subnameArr'] = "";
        $view['urlurlArr'] = "";
        $no = 0;
        if(!empty($view['goods_data'])){
            foreach($view['goods_data'] as $r) {
                $dl_type = $r->emg_type;

                $dl_name = $r->emg_name;
                $dl_sub_name = $r->emg_sub_name;
                $dl_imgurl = $r->emg_imgpath;
                if($dl_type=="N"&&$dl_imgurl==""){
                     $dl_imgurl = '/images/eveview/img_gift_01.png';
                }else if($dl_type=="K"){
                     $dl_imgurl = '/images/eveview/img_bomb_01.png';
                }else if($dl_type=="D"||$dl_type=="P"){
                     $dl_imgurl = '/images/eveview/img_point_01.png';
                }

                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > r->emg_name : ".$r->emg_name." / dl_name : ".$dl_name);

                if(!empty($view['nameArr'])){
                    $view['nameArr'] .= ",'".$dl_name."'";
                }else{
                    $view['nameArr'] .= "'".$dl_name."'";
                }

                if(!empty($view['subnameArr'])){
                    $view['subnameArr'] .= ",'".$dl_sub_name."'";
                }else{
                    $view['subnameArr'] .= "'".$dl_sub_name."'";
                }

                if(!empty($view['urlurlArr'])){
                    $view['urlurlArr'] .= ",'".$dl_imgurl."'";
                }else{
                    $view['urlurlArr'] .= "'".$dl_imgurl."'";
                }

                $no++;
                if($no>=10){
                    break;
                }

            }
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > nameArr : ".$view['nameArr']);
        }



		// $psd_ver_no = $view['data']->psd_ver_no; //버전번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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


	public function evereward($code = ""){
	    // $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "evereward";
		// if($add != "") $skin .= $add;

		$view = array();
        $view['code'] = $code;
		// $view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부
        $emdcode = $this->input->get("emd_code");
        $info_code = $this->input->get("code"); //쿠폰코드

        if(empty($info_code)||$info_code=="preview"){
            $sql = "
    		SELECT
    			  a. *
    		FROM cb_evemaker_data a
    		WHERE emd_code = '". $emdcode ."'
    		AND emd_useyn = 'Y' ";
    		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
    		//echo $sql ."<br>";
    		$view['data'] = $this->db->query($sql)->row();
    		$emd_id = $view['data']->emd_id; //전단번호
    		$mem_id = $view['data']->emd_mem_id; //회원번호

            $sql = "
    		SELECT
    			  a. *
    		FROM cb_evemaker_goods a
    		WHERE emg_emd_id = '". $emd_id ."' and emg_mem_id = '". $mem_id ."' and emg_id = '".$code."'
    		AND emg_useyn = 'Y' ";

            $view['rw_data'] = $this->db->query($sql)->row();

            $view['imgurl'] = $view['rw_data']->emg_imgpath;
            $view['rw_type'] = $view['rw_data']->emg_type;

            if($view['rw_type']=="N"&&$view['imgurl']==""){
                 $view['imgurl'] = '/images/eveview/img_gift_01.png';
            }else if($view['rw_type']=="D"||$view['rw_type']=="P"){
                 $view['imgurl'] = '/images/eveview/img_point_01.png';
            }
        }else{
            $sql = "
    		SELECT
    			  *
    		FROM cb_evemaker_send_data
    		WHERE esd_code = '". $info_code ."'";
            $emsd_data = $this->db->query($sql)->row();

            if(!empty($emsd_data)){

                $view['emsd_data'] = $emsd_data;

                $sql = "
        		SELECT
        			  *
        		FROM cb_evemaker_send_info
        		WHERE esi_idx = '". $emsd_data->esd_esi_idx ."'";
                $emsi_data = $this->db->query($sql)->row();


                $sql = "
        		SELECT
        			  a. *
        		FROM cb_evemaker_data a
        		WHERE emd_code = '". $emsi_data->esi_emd_code ."'
        		AND emd_useyn = 'Y' ";
        		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
        		//echo $sql ."<br>";
        		$view['data'] = $this->db->query($sql)->row();
        		$emd_id = $view['data']->emd_id; //전단번호
        		$mem_id = $view['data']->emd_mem_id; //회원번호

                $sql = "
        		SELECT
        			  a. *
        		FROM cb_evemaker_goods a
        		WHERE emg_emd_id = '". $emd_id ."' and emg_mem_id = '". $mem_id ."' and emg_id = '".$emsd_data->esd_emg_id."'
        		AND emg_useyn = 'Y' ";

                $view['rw_data'] = $this->db->query($sql)->row();

                $view['imgurl'] = $view['rw_data']->emg_imgpath;
                $view['rw_type'] = $view['rw_data']->emg_type;

                if($view['rw_type']=="N"&&$view['imgurl']==""){
                     $view['imgurl'] = '/images/eveview/img_gift_01.png';
                }else if($view['rw_type']=="D"||$view['rw_type']=="P"){
                     $view['imgurl'] = '/images/eveview/img_point_01.png';
                }
            }
        }



        //매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();
		//스마트전단 데이타
		// $sql = "
		// SELECT
		// 	  a. *
		// FROM cb_pop_coupon_data a
		// WHERE pcd_code = '". $code ."'
		// AND pcd_useyn = 'Y' ";
		// //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		// //echo $sql ."<br>";
		// $view['data'] = $this->db->query($sql)->row();
		// $psd_id = $view['data']->psd_id; //전단번호
		// $mem_id = $view['data']->psd_mem_id; //회원번호
		// $psd_ver_no = $view['data']->psd_ver_no; //버전번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		// $sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		// //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		// $view['shop'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

    // public function reward_pick(){
	//     $emd_id = $this->input->post('emd_id');
    //     $emd_user = $this->input->post('emd_user');
    //     $code = $this->input->post('code');
    //
    //     $sql = "SELECT emg_id FROM cb_evemaker_goods WHERE emg_emd_id ='".$emd_id."' and emg_useyn= 'Y'";
    //     $eve_result = $this->db->query($sql)->result();
    //     log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
    //
    //
    //
    //     $result = array();
    //     if(!empty($eve_result)){
    //         $arr_emg_id = array();
    //         foreach($eve_result as $r) {
    //             array_push($arr_emg_id, $r->emg_id);
    //         }
    //
    //
    //         $selected = array_rand($arr_emg_id);
    //         log_message("ERROR", $_SERVER['REQUEST_URI'] ." > emg_id : ".$arr_emg_id[$selected]);
    //         $sql = "SELECT * FROM cb_evemaker_goods WHERE emg_id ='".$arr_emg_id[$selected]."' ";
    //
    //         log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
    //         $eve_result0 = $this->db->query($sql)->row();
    //
    //         $arcnt = count($arr_emg_id);
    //         $picknum = $selected+1;
    //         $divide = 360 / $arcnt;
    //         $maxdivide = ($divide * $picknum - 10)*-1;
    //         $mindivide = ((($maxdivide*-1) - $divide) + 10)*-1;
    //         $ranpick = rand($maxdivide, $mindivide);
    //         log_message("ERROR", $_SERVER['REQUEST_URI'] ." > arcnt : ".$arcnt." / picknum : ".$picknum." / divide : ".$divide." / maxdivide : ".$maxdivide." / mindivide : ".$mindivide." / ranpick : ".$ranpick);
    //
    //         $result["code"] = '0';
    //         $result["imgurl"] = $eve_result0->emg_imgpath;
    //         $result["title"] = $eve_result0->emg_name;
    //         $result["detail"] = $eve_result0->emg_sub_name;
    //         $result["ranpick"] = $ranpick;
    //         $result["type"] = $eve_result0->emg_type;
    //         $result["eid"] = $eve_result0->emg_id;
    //
    //         if(!empty($code)&&$code!="preview"){
    //             $goodsget = array();
	// 			$goodsget['esd_emg_id'] = $result["eid"];
	// 			$goodsget['esd_emg_name'] = $result["title"];
    //             $goodsget['esd_state'] = "2";
    //             $goodsget['esd_eve_date'] = date("Y-m-d H:i:s");
    //
    //             $where = array();
    //             $where['esd_code'] = $code;
    //             $where['esd_state'] = '1';
	// 			$this->db->update('cb_evemaker_send_data', $goodsget, $where);
    //
    //             if($result["type"]!="K"){
    //                 $sql = "SELECT * FROM cb_evemaker_data WHERE emd_id ='".$emd_id."' ";
    //
    //                 log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
    //                 $emd_data = $this->db->query($sql)->row();
    //
    //                 $this->sendat_eve($code, $emd_data->emd_mem_id, $result["title"]." ".$result["detail"]);
    //             }
    //
    //
    //         }
    //
    //     }else{
    //         $result["code"] = '1';
    //     }
    //
	//     $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	//     header('Content-Type: application/json');
	//     echo $json;
	// }

    public function reward_pick(){
	    $emd_id = $this->input->post('emd_id');
        $emd_user = $this->input->post('emd_user');
        $code = $this->input->post('code');



        $sql = "SELECT emg_id, emg_cnt FROM cb_evemaker_goods WHERE emg_emd_id ='".$emd_id."' and emg_useyn= 'Y'";
        $eve_result = $this->db->query($sql)->result();
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);



        $result = array();
        if(!empty($eve_result)){
            $arr_emg_id = array();
            $sql = "SELECT count(1) as cnt FROM cb_evemaker_goods WHERE emg_emd_id ='".$emd_id."' and emg_useyn= 'Y' and emg_type = 'K'";
            $k_cnt = $this->db->query($sql)->row()->cnt;

            $sql = "SELECT sum(emg_cnt) as cnt_sum FROM cb_evemaker_goods WHERE emg_emd_id ='".$emd_id."' and emg_useyn= 'Y' and emg_type <> 'K'";
            $cnt_sum = $this->db->query($sql)->row()->cnt_sum;
            $kallcnt = 0;
            $knncnt = 0;

            $allcnt = count($eve_result);

            if($allcnt>4){
                $kallcnt = 25;
            }else{
                $kallcnt = 30;
            }

            if($k_cnt>1){
                $knncnt = round($kallcnt / $k_cnt, 0);
            }else if($k_cnt==1){
                $knncnt = $kallcnt;
            }

             // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > k_cnt : ".$k_cnt." / cnt_sum : ".$cnt_sum);

            $real_pick = array();

            $cnt = 0;
            $no = 0;
            $kno = 0;
            $firstno = 0;
            // $lastno  = 0;
            foreach($eve_result as $r) {
                array_push($arr_emg_id, $r->emg_id);

                if($r->emg_cnt>0&&$cnt_sum>0&&$code!="preview"){

                    // $firstno = $lastno +1;
                    //
                    // if($firstno==1){
                    //     $firstno=0;
                    // }
                    if($r->emg_type != "K"){
                        // $cnt = sprintf('%d', ($r->emg_cnt / ($cnt_sum+$allcnt))*100);
                        $cnt = round(($r->emg_cnt / ($cnt_sum+$allcnt))*100, 0);
                    }else{
                        $cnt = $knncnt;
                    }

                    // $lastno = $firstno + $r->emg_cnt;
                    $real_pick_temp = array_fill(0,$cnt, "".$no."");
                    // $cnt = $cnt + $r->emg_cnt;
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > firstno : ".$firstno." / lastno : ".$lastno." / cnt : ".$cnt." / no : ".$no);
                    if(empty($real_pick)){
                        $real_pick = $real_pick_temp;
                    }else{
                        $real_pick = array_merge($real_pick, $real_pick_temp);
                    }
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." >  emg_cnt : ".$r->emg_cnt." / no : ".$no);
                }

                if($code!="preview"&&$cnt_sum==0&&$kno==0&&$k_cnt>0){
                    if($r->emg_type == "K"){
                        $kno = $no;
                    }
                }

                $no++;

            }

            if($cnt_sum>0&&$code!="preview"){
                shuffle($real_pick);
                $selectreal = array_rand($real_pick);
                $selected = $real_pick[$selectreal];
                // $selected = $real_pick[0];

                $sql = "UPDATE cb_evemaker_goods SET emg_cnt = emg_cnt - 1 WHERE emg_id ='".$arr_emg_id[$selected]."' ";
                $this->db->query($sql);
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > case 1  / real_pick cnt : ". count($real_pick)." / selected : ".$selected." / real_pick : ". json_encode($real_pick) );
            }else if($code!="preview"&&$cnt_sum==0&&$k_cnt>0){
                $selected = $kno;
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > case 2 "." / kno : ".$kno);
            }else if($code=="preview"){
                $selected = array_rand($arr_emg_id);
            }

            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > emg_id : ".$arr_emg_id[$selected]);
            $sql = "SELECT * FROM cb_evemaker_goods WHERE emg_id ='".$arr_emg_id[$selected]."' ";

            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
            $eve_result0 = $this->db->query($sql)->row();

            $arcnt = count($arr_emg_id);
            $picknum = $selected+1;
            $divide = 360 / $arcnt;
            $maxdivide = ($divide * $picknum - 10)*-1;
            $mindivide = ((($maxdivide*-1) - $divide) + 10)*-1;
            $ranpick = rand($maxdivide, $mindivide);
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > arcnt : ".$arcnt." / picknum : ".$picknum." / divide : ".$divide." / maxdivide : ".$maxdivide." / mindivide : ".$mindivide." / ranpick : ".$ranpick);

            $result["code"] = '0';
            $result["imgurl"] = $eve_result0->emg_imgpath;
            $result["title"] = $eve_result0->emg_name;
            $result["detail"] = $eve_result0->emg_sub_name;
            $result["ranpick"] = $ranpick;
            $result["type"] = $eve_result0->emg_type;
            $result["eid"] = $eve_result0->emg_id;






            if(!empty($code)&&$code!="preview"){
                $goodsget = array();
				$goodsget['esd_emg_id'] = $result["eid"];
				$goodsget['esd_emg_name'] = $result["title"];
                $goodsget['esd_state'] = "2";
                $goodsget['esd_eve_date'] = date("Y-m-d H:i:s");

                $where = array();
                $where['esd_code'] = $code;
                $where['esd_state'] = '1';
				$this->db->update('cb_evemaker_send_data', $goodsget, $where);

                if($result["type"]!="K"){
                    $sql = "SELECT * FROM cb_evemaker_data WHERE emd_id ='".$emd_id."' ";

                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
                    $emd_data = $this->db->query($sql)->row();

                    $this->sendat_eve($code, $emd_data->emd_mem_id, $result["title"]." ".$result["detail"]);
                }


            }

        }else{
            $result["code"] = '1';
        }

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

    public function reward_confirm(){
        $code = $this->input->post('code');
        $result = array();

        if(!empty($code)&&$code!="preview"){
            $goodsget = array();
            $goodsget['esd_state'] = "3";
            $goodsget['esd_goods_date'] = date("Y-m-d H:i:s");

            $where = array();
            $where['esd_code'] = $code;
            $where['esd_state'] = '2';
            $this->db->update('cb_evemaker_send_data', $goodsget, $where);
            $result["code"] = '0';
        }else{
            $result["code"] = '1';
        }

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//고객분포
	public function map(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$mem_id = $this->member->item('mem_id'); //회원번호
		$mem_userid = $this->member->item('mem_userid'); //회원아이디

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		//고객 주소 리스트
		$sql = "
		SELECT *
		FROM cb_ab_". $mem_userid ."
		WHERE ab_addr != '' /* 주소가 있는 것만 가져 온다. */
		ORDER BY ab_id ASC ";
		//echo $sql ."<br>";
		$view['addr_list'] = $this->db->query($sql)->result();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout',
			'skin' => 'map',
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

	//스마트전단 > 장바구니
	public function cart(){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "cart";
		if($add != "") $skin .= $add;
		$view = array();
		$mem_id = $_SESSION['shop_mem_id']; //회원번호
		$view['code'] = $this->input->get("code"); //스마트전단 코드
        //홈코드 (뒤로가기 추가) 22-04-07 윤재박
        $view['home'] = ($this->input->get("home")) ? $this->input->get("home") : ""; //홈코드
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
		FROM cb_pop_screen_data a
		WHERE psd_code = '". $view['code'] ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['screen_data'] = $this->db->query($sql)->row();
		$mem_id = $view['screen_data']->psd_mem_id; //회원번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ."<br>";
        if($view['screen_data']->psd_order_yn=="Y"){
            if($view['screen_data']->psd_order_alltime=="N"){
                if(!empty($view['screen_data']->psd_order_edt)){
                    $nowTime = date("Y-m-d H:i:s"); // 현재 시간
                    $expTime = date($view['screen_data']->psd_order_edt . " " . substr($view['screen_data']->psd_order_et, 0, 2) . ":" . substr($view['screen_data']->psd_order_et, 2, 2) . ":00"); // 종료 시간
                    if($nowTime > $expTime){
                      redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }

          }else{
              redirect($_SERVER['HTTP_REFERER']);
          }
		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		if($view['shop']->mem_stmall_yn != "Y") { //스마트전단 주문하기 사용여부
			$url = "/smart/view/". $view['code'];
			if($view['ScreenShotYn'] != ""){
				$url .= "?ScreenShotYn=". $view['ScreenShotYn'];
			}
			echo "
			<script>
				alert('해당 스마트전단은 판매 중단되었습니다.');
				location.href = '". $url ."';
			</script>";
			exit();
		}

		if($_SESSION['shop_mem_id'] == ""){ //세션이 없는 경우
			//세션생성
			$_SESSION['shop_mem_id'] = $view['shop']->mem_id;
			$_SESSION['shop_mem_userid'] = $view['shop']->mem_userid;
			if($view['shop']->mem_userid == "dhn"){
				//echo "_SESSION['shop_mem_id'] : ". $_SESSION['shop_mem_id'] ."<br>_SESSION['shop_mem_userid'] : ". $_SESSION['shop_mem_userid'] ."<br>";
			}
		}

		//주문설정 정보 2021-03-25
		$sql = "select * from cb_orders_setting where mem_id = '". $mem_id ."' ";
		$view['os'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

	//스마트전단 > 장바구니 추가
	public function addcart(){
	    $mem_id = $this->input->post("shop_mem_id"); //매장 회원번호
	    $psdid = $this->input->post("psdid"); //전단번호
	    $goodsid = $this->input->post("goodsid"); //상품번호
	    $qty = $this->input->post("qty"); //주문갯수

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

	   	//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
		FROM cb_pop_screen_data a
		WHERE psd_id = '". $psdid ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 > sql : ".$sql);
	    $data = $this->db->query($sql)->row();

        $passflag = "Y";

        if (!empty($_SESSION["user_cart"])){
            foreach($_SESSION["user_cart"] as $keys => $r){
                if ($keys == "0"){
                    $check_psd_id = $_SESSION["user_cart"][$keys]['psd_id'];
                    $check_mem_id = $_SESSION["user_cart"][$keys]['mem_id'];
                }
                if ($check_psd_id != $psdid || $check_mem_id != $mem_id){
                    $test = true;
                    $result = array(
                        'code' => '9',
                        'message' => '다른 전단의 상품이 들어있습니다.',
                    );
                    break;
                }
            }

            // if(!empty($_SESSION['user_cart'])) {
                $goods_array_id = array_column($_SESSION["user_cart"],"goodsid");
                if(in_array($goodsid, $goods_array_id)){
                    $passflag = "N";
                }
            // }
        }

        if(!empty($data->psd_goods)){
            //스마트전단 상품정보

            $psd_goods = json_decode($data->psd_goods);

            $goods = array();
            $arr_pdata = array();
            $arr_data = array();


            if($data->psd_step1_yn=="Y"&&$passflag=="Y"){
                foreach ($psd_goods->pdata as $r) {

                    if($goodsid==$r->psg_id){
                        $goods = (object) array(
                                    "psg_id" => $r->psg_id  //회원번호
                                  , "psg_imgpath" => $r->psg_imgpath //이미지경로
                                  , "psg_name" => $r->psg_name //상품명
                                  , "psg_option" => $r->psg_option //옵션명
                                  , "psg_price" => $r->psg_price //정상가
                                  , "psg_dcprice" => $r->psg_dcprice //할인가
                                  , "psg_option2" => $r->psg_option2 //추가란
                                  , "psg_code" => $r->psg_code //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
                                  , "psg_stock" => $r->psg_stock //품절여부 2021-06-17 추가
                              );
                              break;
                    }
                }
            }

            if(count($goods)==0&&$passflag=="Y"){
                foreach ($psd_goods->data as $r) {


                    if($goodsid==$r->psg_id){
                        $goods = (object) array(
                                    "psg_id" => $r->psg_id  //회원번호
                                  , "psg_imgpath" => $r->psg_imgpath //이미지경로
                                  , "psg_name" => $r->psg_name //상품명
                                  , "psg_option" => $r->psg_option //옵션명
                                  , "psg_price" => $r->psg_price //정상가
                                  , "psg_dcprice" => $r->psg_dcprice //할인가
                                  , "psg_option2" => $r->psg_option2 //추가란
                                  , "psg_code" => $r->psg_code //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
                                  , "psg_stock" => $r->psg_stock //품절여부 2021-06-17 추가
                              );
                              break;
                    }
                }
            }


        }else{
            //스마트전단 상품정보
            $sql = "
            SELECT
                  a. *
            FROM cb_pop_screen_goods a
            WHERE psg_id = '". $goodsid ."' ";
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품정보 > sql : ".$sql);
            $goods = $this->db->query($sql)->row();
        }

	   	// //스마트전단 상품정보
		// $sql = "
		// SELECT
		// 	  a. *
		// FROM cb_pop_screen_goods a
		// WHERE psg_id = '". $goodsid ."' ";
		// //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품정보 > sql : ".$sql);
	    // $goods = $this->db->query($sql)->row();

	    // log_message("ERROR", "addcart > BarCode : ".$goods->psg_code);

        if (!empty($_SESSION["user_cart"])){
            foreach($_SESSION["user_cart"] as $keys => $r){
                if ($keys == "0"){
                    $check_psd_id = $_SESSION["user_cart"][$keys]['psd_id'];
                    $check_mem_id = $_SESSION["user_cart"][$keys]['mem_id'];
                }
                if ($check_psd_id != $psdid || $check_mem_id != $mem_id){
                    $test = true;
                    $result = array(
                        'code' => '9',
                        'message' => '다른 전단의 상품이 들어있습니다.',
                    );
                    break;
                }
            }
        }

		if($view['shop']->mem_stmall_yn != "Y") { //스마트전단 주문하기 사용여부
			$result = array(
				'code' => '1',
				'message' => '해당 스마트전단은 판매 중단되었습니다.',
			);
		}else if($view['order_etime_over'] == "Y"){ //주문마감시간 경과
			$result = array(
				'code' => '2',
				'message' => '주문 마감 시간이 경과 되었습니다. <br>(마감시간 '. $order_etime .')',
			);
		}else if($goods->stock < $qty && $goods->stock_flag == 'Y' && !empty($goods->stock_flag)){ //stock_flag : 재고관리 (Y:설정, N:설정안함)
			$result = array(
				'code' => '3',
				'message' => '재고가 부족하여 구매 할 수 없습니다.',
			);
		}else{
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 > 장바구니 추가 Start");
    	    $code = 0;
    	    $message = '등록 성공';
    	    if(!empty($_SESSION['user_cart'])) {
    	        $goods_array_id = array_column($_SESSION["user_cart"],"goodsid");
    	        if(!in_array($goodsid, $goods_array_id)){ //같은 상품이 없느 경우
					$cartindex = count($_SESSION['user_cart']);
					$cart = array(
						  'goodsid' => $goods->psg_id //상품번호
						, 'name' => $goods->psg_name //상품명
						, 'option' => $goods->psg_option //규격
						, 'price' => $this->funn->numbers_only($goods->psg_price) //정상가
						, 'dcprice' => $this->funn->numbers_only($goods->psg_dcprice) //할인가
						, 'imgurl' => $goods->psg_imgpath //이미지경로
						, 'option2' =>$goods->psg_option2 //옵션
						, 'badge' =>$goods->psg_badge //뱃지
						, 'qty'  =>$qty //주문건수
					    , 'bar_code' => $goods->psg_code //상품번호(바코드) 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
                        , 'psd_id' => $psdid
                        , 'mem_id' => $mem_id
					);
					$_SESSION['user_cart'][$cartindex] = $cart;
    	        } else { //같은 상품이 있는 경우 주문건수 증가
    	            for($i =0; $i < count($_SESSION["user_cart"]); $i++) {
    	                if($goodsid === $_SESSION["user_cart"][$i]['goodsid']) {
    	                    //$_SESSION["user_cart"][$i]['qty'] = $_SESSION["user_cart"][$i]['qty'] + $qty;
							if($goods->least_num > ($_SESSION["user_cart"][$i]['qty'] + $qty) && $goods->least_num_flag == 'Y' && !empty($goods->least_num_flag)) { //least_num_flag : 구매 최소 수량 제한 (Y:설정, N:설정안함)
								$code = '4';
								$message = $goods->psg_name .'<br>1회 최대 구매 수량은 '. $goods->least_num .'개 입니다.';
							}else if($goods->limit_num < ($_SESSION["user_cart"][$i]['qty'] + $qty) && $goods->limit_num_flag == 'Y' && !empty($goods->limit_num_flag)) { //limit_num_flag : 구매 최대 수량 제한 (Y:설정, N:설정안함)
								$code = '5';
								$message = $goods->psg_name .'<br>1회 최대 구매 수량은 '. $goods->limit_num .'개 입니다.';
							}else{
								$_SESSION["user_cart"][$i]['qty'] = $_SESSION["user_cart"][$i]['qty'] + $qty;
							}
    	                }
    	            }
    	        }
    	    } else {
					$cart = array(
						  'goodsid' => $goods->psg_id //상품번호
						, 'name' => $goods->psg_name //상품명
						, 'option' => $goods->psg_option //규격
						, 'price' => $this->funn->numbers_only($goods->psg_price) //정상가
						, 'dcprice' => $this->funn->numbers_only($goods->psg_dcprice) //할인가
						, 'imgurl' => $goods->psg_imgpath //이미지경로
						, 'option2' =>$goods->psg_option2 //옵션
						, 'badge' =>$goods->psg_badge //뱃지
						, 'qty'  =>$qty //주문건수
					    , 'bar_code' => $goods->psg_code //상품번호(바코드) 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
                        , 'psd_id' => $psdid
                        , 'mem_id' => $mem_id
					);
					$_SESSION['user_cart'][0] = $cart;
    	    }

			if($code == 0){ //등록 성공
				$amt = 0;
				for($i =0; $i < count($_SESSION["user_cart"]); $i++){
					$dcprice = preg_replace("/[^0-9]*/s", "", $_SESSION["user_cart"][$i]['dcprice']); //할인가 숫자 제거
					$amt = $amt + $dcprice * $_SESSION["user_cart"][$i]['qty']; //주문금액 = 할인가 * 주문건수
				}
				//$cnt = count($_SESSION['user_cart']);
				$cnt = 0;
				for($i =0; $i < count($_SESSION["user_cart"]); $i++){
					$cnt = $cnt + $_SESSION["user_cart"][$i]['qty']; //주문건수
				}
				$result = array(
					  'code' => $code
					, 'message' =>$message
					, 'cartcnt' => $cnt
					, 'amt' => number_format($amt)
				);
			}else{
				$result = array(
					  'code' => $code
					, 'message' => $message
				);
			}
	    }
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    //log_message("ERROR","json:". $json);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//장바구니 상품삭제
	public function delitem(){
	    $goodsid = $this->input->post('goodsid');
	    foreach($_SESSION["user_cart"] as $keys => $r) {
	        //log_message("ERROR", "ID : ".$r['goodsid']." / ".$goodsid);
	        if($r['goodsid'] === $goodsid) {
	            //log_message("ERROR", "삭제 - ID : ".$r['goodsid']." / ".$goodsid);
	            unset($_SESSION["user_cart"][$keys]);
	        }
	    }
	    $_SESSION["user_cart"] = array_values($_SESSION["user_cart"]);
	    $result = array(
	        'code' => '0',
	        'message' => '성공'
	    );
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//장바구니 수량 변경
	public function changeqty(){
	    $goodsid = $this->input->post("goodsid"); //상품번호
	    $qty = $this->input->post("qty"); //주문갯수
		$cnt = 0;
		//log_message("ERROR", "/cart/changeqty > goodsid : ". $goodsid .", qty : ". $qty);

		//스마트전단 상품정보
		$sql = "
		SELECT
			  a. *
		FROM cb_pop_screen_goods a
		WHERE psg_id = '". $goodsid ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품정보 > sql : ".$sql);
	    $goods = $this->db->query($sql)->row();

		if($goods->least_num > $qty && $goods->least_num_flag == 'Y' && !empty($goods->least_num_flag)) { //least_num_flag : 구매 최소 수량 제한 (Y:설정, N:설정안함)
	        $result = array(
	            'code' => '1',
	            'message' => $goods->description .'<br>1회 최소 구매 수량은 '. $goods->least_num .'개 입니다.',
	        );
	    }else if($goods->limit_num < $qty && $goods->limit_num_flag == 'Y' && !empty($goods->limit_num_flag)) { //limit_num_flag : 구매 최대 수량 제한 (Y:설정, N:설정안함)
	        $result = array(
	            'code' => '2',
	            'message' => $goods->description .'<br>1회 최대 구매 수량은 '. $goods->limit_num .'개 입니다.',
	        );
	    }else{
			foreach($_SESSION["user_cart"] as $keys => $r) {
				//log_message("ERROR", "ID : ".$r['goodsid']." / ".$goodsid);
				if($r['goodsid'] === $goodsid) {
					//log_message("ERROR", "삭제 - ID : ".$r['goodsid']." / ".$goodsid);
					$_SESSION["user_cart"][$keys]['qty'] = $qty;
				}
				$cnt = $cnt + $_SESSION["user_cart"][$keys]['qty'];
			}
			$result = array(
				'code' => '0',
				'message' => '성공',
				'cartcnt' => $cnt
			);
		}
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//스마트전단 > 주문하기
	public function orders(){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "orders";
		if($add != "") $skin .= $add;

		if(!empty($_SESSION['user_cart'])){
			$cartqty = $this->Cart_model->cartqty();
		}else{
			$cartqty = 0;
		}
		//$cartqty = 0;
		if($cartqty == 0){
			//alert("주문정보가 없습니다.");
			echo "<script>history.back();</script>";
			exit();
		}

		$view = array();
		$mem_id = $_SESSION['shop_mem_id']; //회원번호
		$view['code'] = $this->input->get("code"); //스마트전단 코드
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부
        //홈코드 (뒤로가기 추가) 22-04-07 윤재박
        $view['home'] = ($this->input->get("home")) ? $this->input->get("home") : ""; //홈코드

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
		FROM cb_pop_screen_data a
		WHERE psd_code = '". $view['code'] ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['screen_data'] = $this->db->query($sql)->row();
		$mem_id = $view['screen_data']->psd_mem_id; //회원번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		if($view['shop']->mem_stmall_yn != "Y") { //스마트전단 주문하기 사용여부
			$url = "/smart/view/". $view['code'];
			if($view['ScreenShotYn'] != ""){
				$url .= "?ScreenShotYn=". $view['ScreenShotYn'];
			}
			echo "
			<script>
				alert('해당 스마트전단은 판매 중단되었습니다.');
				location.href = '". $url ."';
			</script>";
			exit();
		}

		if($_SESSION['shop_mem_id'] == ""){ //세션이 없는 경우
			//세션생성
			$_SESSION['shop_mem_id'] = $view['shop']->mem_id;
			$_SESSION['shop_mem_userid'] = $view['shop']->mem_userid;
			if($view['shop']->mem_userid == "dhn"){
				//echo "_SESSION['shop_mem_id'] : ". $_SESSION['shop_mem_id'] ."<br>_SESSION['shop_mem_userid'] : ". $_SESSION['shop_mem_userid'] ."<br>";
			}
		}

		//주문설정 정보 2021-03-25
		$sql = "select * from cb_orders_setting where mem_id = '". $mem_id ."' ";
		$view['os'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

	//스마트전단 > 개인정보처리방침 전문보기
	public function privacy(){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "privacy";
		if($add != "") $skin .= $add;

		$view = array();
		$mem_id = $_SESSION['shop_mem_id']; //회원번호
		$view['code'] = $this->input->get("code"); //스마트전단 코드
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
		FROM cb_pop_screen_data a
		WHERE psd_code = '". $view['code'] ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['screen_data'] = $this->db->query($sql)->row();
		$mem_id = $view['screen_data']->psd_mem_id; //회원번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['shop'] = $this->db->query($sql)->row();

		if($_SESSION['shop_mem_id'] == ""){ //세션이 없는 경우
			//세션생성
			$_SESSION['shop_mem_id'] = $view['shop']->mem_id;
			$_SESSION['shop_mem_userid'] = $view['shop']->mem_userid;
			if($view['shop']->mem_userid == "dhn"){
				//echo "_SESSION['shop_mem_id'] : ". $_SESSION['shop_mem_id'] ."<br>_SESSION['shop_mem_userid'] : ". $_SESSION['shop_mem_userid'] ."<br>";
			}
		}

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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


	private function getMillisecond()
	{
	    list($microtime,$timestamp) = explode(' ',microtime());
	    $time = date('ymdHis').substr($microtime, 2, 3);

	    return $time;
	}

	//스마트전단 > 주문하기 > 주문하기
	public function ordercomplete(){
		//주문정보
		$order = array();
		$order['mem_id'] = $_SESSION['shop_mem_id']; //회원사번호
		$order['receiver'] = $this->input->post('receiver'); //고객명
		$order['phnno'] = $this->input->post('phnno'); //휴대폰번호
		$order['postcode'] = $this->input->post('postcode'); //우편번호
		$order['addr1']= $this->input->post('addr1'); //주소
		$order['addr2']= $this->input->post('addr2'); //상세주소
		$order['charge_type'] = $this->input->post('charge_type'); //결재수단 (1:현장결제(카드), 2:현장결제(현금))
		$order['deliv_amt'] = $this->input->post('deliv_amt'); //배달비
		$order['point_save_no'] = $this->input->post('point_save_no'); //마트적립번호
		$order['user_req'] = $this->input->post('user_req'); //고객 요청사항
		$order['status'] = 0; //진행상태 (0:주문완료, 1:배달준비중, 2:배달시작, 3:배달완료, 4:주문취소, 9.주문대기)
		$order['orderno'] = $this->getMillisecond();

		//배달정보 저장하기 사용시 쿠키생성
		$deliv_save = $this->input->post('deliv_save'); //배달정보 저장하기
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 주문하기 > deliv_save : ". $deliv_save);
		$coo_time = 60 * 60 * 24 * 30; //쿠키값 설정 30일(24시간 * 30)
		if($deliv_save == "Y"){
			setcookie("cookie_deliv_save", $deliv_save, time() + $coo_time, "/"); //배달정보 저장하기 => 쿠키저장
			setcookie("cookie_deliv_receiver", $this->funn->encrypt($order['receiver']), time() + $coo_time, "/"); //고객명 => 쿠키저장
			setcookie("cookie_deliv_phnno", $this->funn->encrypt($order['phnno']), time() + $coo_time, "/"); //휴대폰번호 => 쿠키저장
			setcookie("cookie_deliv_postcode", $this->funn->encrypt($order['postcode']), time() + $coo_time, "/"); //우편번호 => 쿠키저장
			setcookie("cookie_deliv_addr1", $this->funn->encrypt($order['addr1']), time() + $coo_time, "/"); //주소 => 쿠키저장
			setcookie("cookie_deliv_addr2", $this->funn->encrypt($order['addr2']), time() + $coo_time, "/"); //상세주소 => 쿠키저장
			setcookie("cookie_deliv_charge_type", $order['charge_type'], time() + $coo_time, "/"); //결제방법 => 쿠키저장
			setcookie("cookie_deliv_point_save_no", $this->funn->encrypt($order['point_save_no']), time() + $coo_time, "/"); //마트적립번호 => 쿠키저장
			setcookie("cookie_deliv_user_req", $this->funn->encrypt($order['user_req']), time() + $coo_time, "/"); //요청사항 => 쿠키저장
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 주문하기 > 쿠키저장");
		}else{
			setcookie("cookie_deliv_save", "", time() - $coo_time, "/"); //배달정보 저장하기 => 쿠키제거
			setcookie("cookie_deliv_receiver", "", time() - $coo_time, "/"); //고객명 => 쿠키제거
			setcookie("cookie_deliv_phnno", "", time() - $coo_time, "/"); //휴대폰번호 => 쿠키제거
			setcookie("cookie_deliv_postcode", "", time() - $coo_time, "/"); //우편번호 => 쿠키제거
			setcookie("cookie_deliv_addr1", "", time() - $coo_time, "/"); //주소 => 쿠키제거
			setcookie("cookie_deliv_addr2", "", time() - $coo_time, "/"); //상세주소 => 쿠키제거
			setcookie("cookie_deliv_charge_type", "", time() - $coo_time, "/"); //결제방법 => 쿠키제거
			setcookie("cookie_deliv_point_save_no", "", time() - $coo_time, "/"); //마트적립번호 => 쿠키제거
			setcookie("cookie_deliv_user_req", "", time() - $coo_time, "/"); //요청사항 => 쿠키제거
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 주문하기 > 쿠키제거");
		}

		//주문정보 확인
		if(!empty($_SESSION['user_cart'])){
			$cartqty = $this->Cart_model->cartqty();
		}else{
			$cartqty = 0;
		}
		//$cartqty = 0;
		if($cartqty == 0){ //주문정보가 없는 경우
			$result = array(
				'code' => '1',
				'message' => '장시간 미사용으로 주문정보가 없습니다.'
			);
		}else{
			//주문정보 추가
			$this->db->insert('cb_orders', $order); //주문정보 추가
			$order_id = $this->db->insert_id(); //주문번호
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 주문정보 추가 완료, order_id : ". $order_id);
			foreach($_SESSION["user_cart"] as $keys => $r){
				//null 값 처리
				$od_price = $_SESSION["user_cart"][$keys]['price']; //정상가
				if(empty($od_price)) $od_price = 0;
				$od_dcprice = $_SESSION["user_cart"][$keys]['dcprice']; //판매가
				if(empty($od_dcprice)) $od_dcprice = 0;
				$od_qty = $_SESSION["user_cart"][$keys]['qty']; //판매가
				if(empty($od_qty)) $od_qty = 0;

				//주문 상세정보 추가
				$od = array();
				$od['order_id'] = $order_id; //주문번호
				$od['goodsid'] = $_SESSION["user_cart"][$keys]['goodsid']; //상품번호
				$od['name'] = $_SESSION["user_cart"][$keys]['name']; //상품명
				$od['option'] = $_SESSION["user_cart"][$keys]['option']; //규격
				$od['option2'] = $_SESSION["user_cart"][$keys]['option2']; //옵션
				$od['imgurl'] = $_SESSION["user_cart"][$keys]['imgurl']; //이미지경로
				$od['price'] = $od_price; //정상가
				$od['dcprice'] = $od_dcprice; //판매가
				$od['qty'] = $od_qty; //수량
				$od['amount'] = $od_dcprice * $od_qty; //총상품가격
				$od['barcode'] = $_SESSION["user_cart"][$keys]['bar_code']; //상품번호(바코드) 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				$this->db->insert('cb_order_details', $od);
				unset($od);
				unset($_SESSION["user_cart"][$keys]); //장바구니 비우기
			}

			//알림톡 발송 : 주문완료
			$this->sendat($order_id, $_SESSION['shop_mem_id'], "0");

			$result = array(
				'code' => '0',
				'message' => 'OK',
				'oid' =>$order_id
			);
		}

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 주문내역 조회
	public function orderview(){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "orderview";
		if($add != "") $skin .= $add;

		$view = array();
		$view['oid'] = $this->input->get("oid"); //주문번호
		$view['phn'] = $this->input->get("phn"); //휴대폰번호
		$view['code'] = $this->input->get("code"); //스마트전단 코드
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부
		$view['mng'] = $this->input->get("mng");

		//주문정보
		// $sql = "SELECT * FROM cb_orders  WHERE id = '". $view['oid'] ."' AND phnno = '". $view['phn'] ."' ";
		$sql = "SELECT a.*, b.account_info FROM cb_orders a join cb_member b on a.mem_id = b.mem_id WHERE a.id = '". $view['oid'] ."' AND a.phnno = '". $view['phn'] ."' ";
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data'] = $this->db->query($sql)->row();
		$order_id = $view['data']->id; //주문번호
		$mem_id = $view['data']->mem_id; //회원번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ."<br>";

		//주문 상세정보
		$sql = "select * from cb_order_details where order_id = '". $order_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['list'] = $this->db->query($sql)->result();

		$sql = "select * from cb_orders_setting where mem_id = '".$mem_id."' ";
		$view['orderset'] = $this->db->query($sql)->row();

		//매장정보
		$sql = "select * from cb_member where mem_id = '". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['shop'] = $this->db->query($sql)->row();

		if($_SESSION['shop_mem_id'] == ""){ //세션이 없는 경우
			//세션생성
			$_SESSION['shop_mem_id'] = $view['shop']->mem_id;
			$_SESSION['shop_mem_userid'] = $view['shop']->mem_userid;
			if($view['shop']->mem_userid == "dhn"){
				//echo "_SESSION['shop_mem_id'] : ". $_SESSION['shop_mem_id'] ."<br>_SESSION['shop_mem_userid'] : ". $_SESSION['shop_mem_userid'] ."<br>";
			}
		}

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

	//스마트전단 > 주문취소
	function ordercancel(){
		$oid = $this->input->post("oid"); //주문번호
		$phn = $this->input->post("phn"); //휴대폰번호
		$sql = "select * from cb_orders where id = '". $oid ."' and status = '0' AND phnno = '". $phn ."' /* 진행상태 (0:주문완료, 4:주문취소) */ ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
		$rs = $this->db->query($sql)->row();
		$order_id = $rs->id; //주문번호
		$mem_id = $rs->mem_id; //회원번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > order_id : ".$order_id .", mem_id : ".$mem_id);
		if(!empty($order_id)){
			//주문정보 주문취소 처리
			$sql = "update cb_orders set status = '4', cal_person = 'C' where id = '". $order_id ."'";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
			$this->db->query($sql);

			//알림톡 발송 : 주문취소
			$this->sendat($order_id, $mem_id, "4");

			$result = array(
				'code' => '0',
				'message' => 'OK'
			);
		}else{
			$result = array(
				'code' => '1',
				'message' => '주문정보가 없거나 해당 상품이 배달준비중 입니다.'
			);
		}
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 주문완료 > 알림톡 발송
	//controllers/Smart.php
	//mall/Order.php
	function sendat($oid, $mid, $gb){

	    //주문정보
		$sql = "select * from cb_orders d where d.id = '". $oid ."' ";
	    $odr = $this->db->query($sql)->row();

	    //회원정보 (마트)
        //ib플래그
		$sql = "
		SELECT
			  a.*
			, b.spf_key
		FROM cb_member a
		LEFT JOIN ".config_item('ib_profile')." b ON a.mem_id = b.spf_mem_id AND b.spf_use = 'Y'
		WHERE a.mem_id = '". $mid ."' ";
	    $mem = $this->db->query($sql)->row();

        $sql = "
            select a.mem_voucher_yn, ";
        $sql = $sql."
              (SELECT
                SUM(amt_amount * amt_deduct) amt
                 FROM cb_amt_".$mem->mem_userid." WHERE FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') as voucher_deposit ";
        $sql = $sql."
            from cb_member a
            where a.mem_useyn='Y' and a.mem_id='".$mid."'";

        $voucher = $this->db->query($sql)->row();

        $voucher_flag = "N";

        if($voucher->voucher_deposit>0&&$voucher->mem_voucher_yn=='Y'){
            $voucher_flag = "V";
        }

		//주문 상품정보
		$sql = "
		SELECT
			  CONCAT(d.name, ' ', d.option) AS good_name /* 상품명 */
			, (SELECT COUNT(1) FROM cb_order_details WHERE order_id = d.order_id) AS good_cnt /* 주문수 */
			, (SELECT IFNULL(SUM(amount),0) FROM cb_order_details WHERE order_id = d.order_id) AS good_amt /* 전체상품금액 */
			, o.deliv_amt /* 배달비 */
			, o.charge_type /* 결재수단 */
		FROM cb_order_details d
		LEFT JOIN cb_orders o ON o.id = d.order_id
		WHERE d.order_id =  '". $oid ."'
		AND d.id = (SELECT MIN(id) FROM cb_order_details WHERE order_id = d.order_id) ";
		$goods = $this->db->query($sql)->row();

		$good_name = trim($goods->good_name); //주문 상품명
		$good_cnt = $goods->good_cnt; //주문 건수
		if($good_cnt > 1){ //주문 건수가 2건 이상인 경우
			$good_name = $good_name ." 외 ". ($good_cnt - 1) ."건";
		}
		$good_amt = $goods->good_amt; //전체상품금액
		$deliv_amt = $goods->deliv_amt; //배달비
		$tot_amt = number_format($good_amt + $deliv_amt) ."원";//총결제금액 = 전체상품금액 + 배달비
		$charge_type = $this->funn->get_charge_type($goods->charge_type); //결재수단
		$mem_stmall_alim_phn = $mem->mem_stmall_alim_phn; //주문 알림 수신 번호
		$arr_stmall_alim_phn = explode(',', $mem_stmall_alim_phn);

	    //주문내역 확인  URL
		$url = 'http://'. $_SERVER['HTTP_HOST'] .'/smart/orderview?name='. urlencode($odr->receiver) .'&phn='. $odr->phnno .'&oid='. $oid;

		//주문완료시(고객) > 알림톡 발송
		$param = array();
	    $param['Auth'] = $mem->spf_key; //프로필 키
	    $param['alim_flag'] = '3'; //알림톡발송구분(1.구마트톡, 2.신마트톡, 3.지니)
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            if($gb == "0"){ //주문완료
    			$param['temp'] = '20210928_3'; //템플릿 코드 : 주문완료시(고객)
    		}else if($gb == "2"){ //배송시작
    			$param['temp'] = '20210928_4'; //템플릿 코드 : 주문출발시(고객)
    		}else if($gb == "3"){ //배송완료
    			$param['temp'] = '20210928_5'; //템플릿 코드 : 배송완료시(고객)
    		}else if($gb == "4"){ //주문취소
    			$param['temp'] = '20210928_6'; //템플릿 코드 : 주문취소시(고객)
    		}
        }else{
            if($gb == "0"){ //주문완료
    			$param['temp'] = '2021030301'; //템플릿 코드 : 주문완료시(고객)
    		}else if($gb == "2"){ //배송시작
    			$param['temp'] = '2021030303'; //템플릿 코드 : 주문출발시(고객)
    		}else if($gb == "3"){ //배송완료
    			$param['temp'] = '2021030304'; //템플릿 코드 : 배송완료시(고객)
    		}else if($gb == "4"){ //주문취소
    			$param['temp'] = '2021030302'; //템플릿 코드 : 주문취소시(고객)
    		}
        }
	    $param['sender'] = $mem->mem_phone; //발신자 연락처
	    $param['name'] = $odr->receiver; //성명
	    $param['phn'] = $odr->phnno; //수신자 연락처
	    $param['add1'] = $odr->orderno; //주문번호
	    $param['add2'] = $good_name; //주문상품
	    $param['add3'] = $this->funn->format_phone($mem->mem_phone, "-"); //문의하기
	    $param['add4'] = '';
	    $param['add5'] = '';
	    $param['add6'] = '';
	    $param['add7'] = '';
	    $param['add8'] = '';
	    $param['add9'] = '';
	    $param['add10'] = '';
	    $param['2nd'] = 'Y';
	    $param['m1'] = $url; //주문내역 확인 URL (모바일)
	    $param['pc1'] = $url; //주문내역 확인 URL (PC)
		$param['2nd_btn_add'] = 'Y'; //2차 발송 버튼 링크 추가 여부 2021-03-05
        $param['mid'] = $mid; //mem_id
        $param['voucher'] = $voucher_flag; //바우처 발송여부
	    $rtn = $this->alimtalk_v->send($param);

        $iksanplus = "";
        if($mid=="1896"){
            $iksanplus = "(망성)";
        }
		//주문완료시(관리자) > 알림톡 발송
		for($i=0; $i<count($arr_stmall_alim_phn); $i++){
			$param = array();
			$param['Auth'] = $mem->spf_key; //프로필 키
			$param['alim_flag'] = '3'; //알림톡발송구분(1.구마트톡, 2.신마트톡, 3.지니)
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                if($gb == "0"){ //주문완료
    				$param['temp'] = '20210928_7'; //템플릿 코드 : 주문완료시(관리자)
    			}else if($gb == "4"){ //주문취소
    				$param['temp'] = '20210928_8'; //템플릿 코드 : 주문취소시(관리자)
    			}
            }else{
                if($gb == "0"){ //주문완료
    				$param['temp'] = '2021030305'; //템플릿 코드 : 주문완료시(관리자)
    			}else if($gb == "4"){ //주문취소
    				$param['temp'] = '2021030306'; //템플릿 코드 : 주문취소시(관리자)
    			}
            }
			$param['sender'] = $mem->mem_phone; //발신자 연락처
			$param['name'] = $odr->receiver; //성명
			$param['phn'] = $arr_stmall_alim_phn[$i]; //수신자 연락처
			$param['add1'] = $odr->orderno; //주문번호
			$param['add2'] = $odr->receiver.$iksanplus; //주문자명
			$param['add3'] = $this->funn->format_phone($odr->phnno, "-"); //휴대폰번호
			$param['add4'] = $charge_type; //결제정보
			$param['add5'] = $tot_amt; //총결제금액
			$param['add6'] = '';
			$param['add7'] = '';
			$param['add8'] = '';
			$param['add9'] = '';
			$param['add10'] = '';
			$param['2nd'] = 'Y';
			$param['m1'] = $url ."&mng=Y"; //주문내역 확인 URL (모바일)
			$param['pc1'] = $url ."&mng=Y"; //주문내역 확인 URL (PC)
			$param['2nd_btn_add'] = 'Y'; //2차 발송 버튼 링크 추가 여부 2021-03-05
            $param['mid'] = $mid; //mem_id
            $param['voucher'] = $voucher_flag; //바우처 발송여부
			$rtn = $this->alimtalk_v->send($param);
		}
	    //log_message("ERROR", "주문완료 Alimtalk 결과 : ".$rtn);
	}

    //검색
	public function search_goods(){
	    $psdid = $this->input->post('psdid');
        $word = $this->input->post('word');
        $sql = "SELECT * FROM cb_pop_screen_goods WHERE psg_psd_id ='".$psdid."' AND (psg_name like '%".$word."%' OR psg_option like '%".$word."%')";
        $result = array();

        $result["search"] = $this->db->query($sql)->result();
        if(!empty($result["search"])){
            $result["code"] = '0';
        }else{
            $result["code"] = '1';
        }

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

    //스마트홈 보기
	public function shome($key){

	    $add = $this->input->get("add"); //스킨 추가
		$skin = "shome";
		if($add != "") $skin .= $add;

		$view = array();

        $sql = "
            SELECT
                *
            FROM
                cb_alimtalk_ums chs
            WHERE
                link_type = 'home' AND home_code = '".$key."'
        ";
        $view["link"] = $this->db->query($sql)->row();
        if (empty($view["link"])){
            redirect("404");
        }

        $mem_id = $view['link']->mem_id; //회원번호

        // 20230314 이수환 Kakao Channel Public Id 처리부 시작

		    // $sql = "select spf_public_id, spf_friend, spf_key from cb_wt_send_profile_dhn where spf_mem_id=".$mem_id." and spf_appr_id <> '' and spf_use='Y' and spf_status='A'";
		    // $tmp_result = $this->db->query($sql)->row();
		    // $tmp_public_id = $tmp_result->spf_public_id;
		    // $tmp_uuid = $tmp_result->spf_friend;
		    // $tmp_profile =  $tmp_result->spf_key;
		    // // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_public_id : ". $tmp_public_id);
		    // // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_uuid : ". $tmp_uuid);
		    // if (empty($tmp_public_id)) {   // kakao channel public id 가 없으면
		    //     $channel_public_id = $this->funn->get_kakao_channel_public_id($tmp_uuid);
		    //     // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > channel_public_id : ". $channel_public_id);
		    //     if (!empty($channel_public_id)) {   // kakao channel public id을 가져오면 cb_wt_send_profile_dhn 테이블에 업데이트
		    //         $update_sql = "update cb_wt_send_profile_dhn set spf_public_id='".$channel_public_id."' where spf_mem_id=".$mem_id." and spf_key='".$tmp_profile."' and spf_appr_id<>'' and spf_use='Y' and spf_status='A'";
		    //         // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > update_sql : ". $update_sql);
		    //         $this->db->query($update_sql);
		    //     }
		    // }


		//매장정보
		$sql = "select a.*, ddd.spf_friend, ddd.spf_public_id, ddd.spf_key from cb_member a
            LEFT JOIN
            cb_wt_send_profile_dhn ddd
            ON a.mem_id = ddd.spf_mem_id where a.mem_id='". $mem_id ."' ";
		$view['shop'] = $this->db->query($sql)->row();
        $tmp_public_id = $view['shop']->spf_public_id;
        $tmp_uuid = $view['shop']->spf_friend;
        $tmp_profile =  $view['shop']->spf_key;


        if (empty($tmp_public_id)) {   // kakao channel public id 가 없으면
            $channel_public_id = $this->funn->get_kakao_channel_public_id($tmp_uuid);
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > channel_public_id : ". $channel_public_id);
            if (!empty($channel_public_id)) {   // kakao channel public id을 가져오면 cb_wt_send_profile_dhn 테이블에 업데이트
                $update_sql = "update cb_wt_send_profile_dhn set spf_public_id='".$channel_public_id."' where spf_mem_id=".$this->member->item('mem_id')." and spf_key='".$tmp_profile."' and spf_appr_id<>'' and spf_use='Y' and spf_status='A'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > update_sql : ". $update_sql);
                $this->db->query($update_sql);
                $tmp_public_id = $channel_public_id;
            }
        }

        $view['puid'] = $tmp_public_id;

        $view['key'] = $key;

        if($view['shop']->mem_shop_template != 'A'){
            $skin = "shome_".$view['shop']->mem_shop_template;
        }

		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
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

    function check_purchase_datetime(){
        $result = array();
        $result["code"] = "0";
		$psd_id = $this->input->post("psd_id");
        $sql = "SELECT * FROM cb_pop_screen_data WHERE psd_id = " . $psd_id;
        $data = $this->db->query($sql)->row();

        if ($data->psd_order_yn == "Y"){
            $nowdate = date("Y-m-d");
            $sdt = $data->psd_order_sdt;
            $edt = $data->psd_order_edt;
            $st = $data->psd_order_st;
            $et = $data->psd_order_et;

            if ((strtotime($nowdate) < strtotime(date($sdt))) || (strtotime($nowdate) > strtotime(date($edt)))) {
                $result["code"] = "1";
            }else if ($data->psd_order_alltime == 'N'){
                $nowdate2 = date('Hi');
                if ($st > $nowdate2 || $et < $nowdate2){
                    $result["code"] = "1";
                }
            }
        }

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

}
?>
