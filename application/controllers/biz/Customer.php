<?php
class Customer extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz', 'Biz_dhn');

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
    }

    public function lists()
    {

		$this->Biz_dhn_model->make_user_group_table($this->member->item('mem_userid')); //고객별 그룹정보 테이블 확인

		// 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

		$view['add'] = $this->input->get("add");
		$skin = "lists";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		$mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만)
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        $sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid')." where length( ifnull(ab_kind,'') ) > 0 ";
        $view['kind'] = $this->db->query($sql)->result();

		//고객 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_ab_". $mem_userid ." ";
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
				, (SELECT COUNT(*) FROM cb_ab_". $mem_userid ."_group b WHERE b.aug_group_id IN (
					SELECT cg_id FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_id AND c.cg_use_yn = 'Y'
				)) AS lv1_cnt /* 그룹1차 고객수 */
				, (SELECT COUNT(*) FROM cb_ab_". $mem_userid ."_group b WHERE a.cg_id = b.aug_group_id) AS lv2_cnt /* 그룹2차 고객수 */
				, (SELECT COUNT(*) FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_parent_id AND c.cg_use_yn = 'Y') AS rowspan /* 라인수 */
			FROM cb_customer_group a
			WHERE cg_mem_id = '". $mem_id ."'
			AND cg_use_yn = 'Y'
		) t
		ORDER BY cg_sort ASC ";
		//log_message("ERROR", "/application/controllers/biz/customer > lists > SQL : ". $sql);
		//echo $sql ."<br>";
		$view['customer_group'] = $this->db->query($sql)->result();

		//미분류 고객수
		$sql = "
		SELECT COUNT(*) AS cnt
		FROM cb_ab_". $mem_userid ."
		WHERE ab_id NOT IN (
			SELECT aug_ab_id FROM cb_ab_". $mem_userid ."_group
		) ";
		//echo $sql ."<br>";
		$view['unclassified_cnt'] = $this->db->query($sql)->row()->cnt;
		//echo "unclassified_cnt : ". $view['unclassified_cnt'] ."<br>";

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/customer',
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

    //고객 현황 데이타
	public function inc_lists() {
		$skin = "inc_lists";
		$add = $this->input->post("add");
		if($add != "") $skin .= $add;
		$mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		$del_ids = $this->input->post('del_ids'); //선택 번호
		$delGid = $this->input->post('delGid'); //선택 번호
        if($del_ids && count($del_ids) > 0 && $del_ids[0]) { //선택 삭제
            if(!empty($del_ids[0]) && $del_ids[0]=='all'){ //전체 고객 삭제
                $this->db->query("delete from cb_ab_".$mem_userid); //고객 목록
                $this->db->query("delete from cb_ab_".$mem_userid."_group"); //그룹별 고객 목록
            }else if(!empty($del_ids[0]) && $del_ids[0]=='group') { //소속그룹 고객 삭제
				//echo "del_ids[0] : ". $del_ids[0] .", delGid : ". $delGid ."<br>";
				if($delGid == "0"){ //미분류 그룹 삭제
					//미분류 고객 정보 삭제
					$sql = "
					DELETE FROM cb_ab_".$mem_userid."
					WHERE ab_id NOT IN (
						SELECT aug_ab_id
						FROM cb_ab_dhn_group
					) ";
					$this->db->query($sql);
				}else if($delGid != ""){ //소속그룹 삭제
					//소속 고객 정보 삭제
					$sql = "
					DELETE FROM cb_ab_".$mem_userid."
					WHERE ab_id IN (
						SELECT aug_ab_id
						FROM cb_ab_".$mem_userid."_group
						WHERE aug_group_id = '". $delGid ."'
						AND aug_ab_id NOT IN (
							SELECT aug_ab_id	from cb_ab_".$mem_userid."_group WHERE aug_ab_id IN (
								SELECT aug_ab_id from cb_ab_".$mem_userid."_group WHERE aug_group_id = '". $delGid ."'
								)
							AND aug_group_id != '". $delGid ."'
						)
					) ";
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", sql : ". $sql);
					$this->db->query($sql);

					//소속 그룹정보 삭제
					$sql = "DELETE FROM cb_ab_".$mem_userid."_group WHERE aug_group_id = '". $delGid ."' ";
					$this->db->query($sql);
				}
			}else{ //선택삭제
                foreach($del_ids as $did) {
                    $this->db->delete("ab_".$mem_userid, array("ab_id"=>$did)); //고객 목록
					$this->db->delete("ab_".$mem_userid."_group", array("aug_ab_id"=>$did)); //그룹별 고객 목록
                }
            }
        }

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['search_type'] = ($this->input->post("search_type")) ? $this->input->post("search_type") : "all"; //상태
        $view['param']['search_for'] = $this->input->post("search_for"); //전화번호
        $view['param']['search_group'] = $this->input->post("search_group"); //친구구분
        $view['param']['search_name'] = $this->input->post("search_name"); //고객명
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지
		$view['glv'] = $this->input->post("glv"); //그룹 레벨
		$view['gid'] = $this->input->post("gid"); //그룹 번호
		$view['add'] = $add;
		//echo "glv : ". $view['glv'] .", gid : ". $view['gid'] ."<br>";

		$gnm1 = "";
		$gnm2 = "";
		if($view['gid'] != ""){
			$sql = "
			SELECT *
			FROM cb_customer_group
			WHERE cg_mem_id = '".$mem_id."'
			AND cg_id = '". $view['gid'] ."'
			AND cg_use_yn = 'Y' ";
			//echo $sql ."<br>";
			$group = $this->db->query($sql)->row();
			$get_parent_id = $group->cg_parent_id;
			$get_level = $group->cg_level;
			$get_gname = $group->cg_gname;
			//echo "gnm : ". $gnm ."<br>";
			if($get_level == "2"){ //2차 그룹
				$gnm2 = $get_gname;
				$sql = "
				SELECT *
				FROM cb_customer_group
				WHERE cg_mem_id = '".$mem_id."'
				AND cg_id = '". $get_parent_id ."'
				AND cg_use_yn = 'Y' ";
				//echo $sql ."<br>";
				$group1 = $this->db->query($sql)->row();
				$gnm1 = $group1->cg_gname;
			}else{ //1차 그룹
				$gnm1 = $get_gname;
				$gnm2 = "";
			}
			//echo "gnm1 : ". $gnm1 .", gnm2 : ". $gnm2 ."<br>";
		}
		$view['gnm1'] = $gnm1;
		$view['gnm2'] = $gnm2;

        $where = " 1=1 "; $param = array();
        if($view['param']['search_type']!="all") { //상태 (1.정상, 0.수신거부)
			$where .= "and ab_status = '".  (($view['param']['search_type']=="reject") ? "0" : "1") ."' ";
		}
        if($view['param']['search_for']) { //전화번호
			//$where .= "and ab_tel like ? "; array_push($param, '%'.$view['param']['search_for'].'%');
			$where .= "and ab_tel like '%". addslashes($view['param']['search_for']) ."%' ";
		}
        //if($view['param']['search_group']) {
        //    if($view['param']['search_group']=='FT') {
        //        $where .= "and exists ( select 1 from cb_friend_list b where b.mem_id = '".$mem_id."' and b.phn = ab_tel) ";
        //    } else if($view['param']['search_group']=='NFT') {
        //        $where .= " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$mem_id."' and b.phn = ab_tel) ";
        //    } else if($view['param']['search_group']=='NONE') {
        //        $where .= "and ab_kind = ? "; array_push($param, '');
        //    } else {
        //        $where .= "and ab_kind = ? "; array_push($param, $view['param']['search_group']);
        //    }
        //}
        if($view['param']['search_name']) { //고객명
			//$where .= "and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%');
			$where .= "and ab_name like '%". addslashes($view['param']['search_name']) ."%' ";
		}
		if($view['glv'] != "" && $view['gid'] != ""){ //그룹별 조회
			if($view['glv'] == 1){ //그룹 1차
				if($view['gid'] == "0"){ //미분류 그룹조회
					$where .= "
					AND ab_id NOT IN (
						SELECT aug_ab_id
						FROM cb_ab_".$mem_userid."_group
					) ";
				}else{ //그룹별 조회
					$where .= "
					AND ab_id IN (
						SELECT aug_ab_id
						FROM cb_ab_".$mem_userid."_group
						WHERE aug_group_id IN (
							SELECT cg_id
							FROM cb_customer_group
							WHERE cg_mem_id = '".$mem_id."'
							AND cg_parent_id = '". $view['gid'] ."'
							AND cg_use_yn = 'Y'
						)
					) ";
				}
				//echo $where ."<br>";
			}else{ //그룹 2차
				$where .= "
				AND ab_id IN (
					SELECT aug_ab_id
					FROM cb_ab_".$mem_userid."_group
					WHERE aug_group_id IN (
						SELECT cg_id
						FROM cb_customer_group
						WHERE cg_mem_id = '".$mem_id."'
						AND cg_id = '". $view['gid'] ."'
						AND cg_use_yn = 'Y'
					)
				) ";
			}
		}

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';

        //고객 전체수
		$sql = "
		select count(*) as cnt
		from cb_ab_".$mem_userid." a
		where ".$where;
		//echo $sql ."<br>";
		$page_cfg['total_rows'] = $this->db->query($sql)->row()->cnt;
		$page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
		//echo "total_rows : ". $page_cfg['total_rows'] ."<br>";

        //고객 리스트
		$sql = "
		SELECT a.*
			, DATE_FORMAT(ab_datetime, '%y.%m.%d') as ab_date
			, (
				SELECT GROUP_CONCAT(CONCAT(cg_gname,'##',cg_id) SEPARATOR '@@')
				FROM cb_customer_group
				WHERE cg_mem_id = '".$mem_id."'
				AND cg_use_yn = 'Y'
				AND cg_id IN (
					SELECT aug_group_id
					FROM cb_ab_".$mem_userid."_group b
					WHERE b.aug_ab_id = a.ab_id
				)
			) AS gname /* 그룹명1##그룹번호1@@그룹명2##그룹번호2 */
		FROM cb_ab_".$mem_userid." a
		WHERE ".$where."
		ORDER BY DATE_FORMAT(ab_datetime,'%Y-%m-%d') DESC, ab_tel ASC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//DATE_FORMAT(ab_datetime,'%Y-%m-%d') DESC, ab_name ASC, ab_tel ASC
        //echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
        $view['list'] = $this->db->query($sql)->result();
        $view['view']['canonical'] = site_url();

		//고객 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_ab_". $mem_userid ." ";
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
			, (CASE WHEN cg_level = 1 THEN cg_gname ELSE (SELECT cg_gname FROM cb_customer_group g WHERE g.cg_id = cg_parent_id AND g.cg_mem_id = '3' AND g.cg_use_yn = 'Y' LIMIT 1) END) AS pname /* 부모그룹명 */
		FROM (
			SELECT
				  a.*
				, (SELECT COUNT(*) FROM cb_ab_". $mem_userid ."_group b WHERE b.aug_group_id IN (
					SELECT cg_id FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_id AND c.cg_use_yn = 'Y'
				)) AS lv1_cnt /* 그룹1차 고객수 */
				, (SELECT COUNT(*) FROM cb_ab_". $mem_userid ."_group b WHERE a.cg_id = b.aug_group_id) AS lv2_cnt /* 그룹2차 고객수 */
				, (SELECT COUNT(*) FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_parent_id AND c.cg_use_yn = 'Y') AS rowspan /* 라인수 */
			FROM cb_customer_group a
			WHERE cg_mem_id = '". $mem_id ."'
			AND cg_use_yn = 'Y'
		) t
		ORDER BY cg_sort ASC ";
		//echo $sql ."<br>";
		$view['customer_group'] = $this->db->query($sql)->result();

		//미분류 고객수
		$sql = "
		SELECT COUNT(*) AS cnt
		FROM cb_ab_". $mem_userid ."
		WHERE ab_id NOT IN (
			SELECT aug_ab_id FROM cb_ab_". $mem_userid ."_group
		) ";
		//echo $sql ."<br>";
		$view['unclassified_cnt'] = $this->db->query($sql)->row()->cnt;
		//echo "unclassified_cnt : ". $view['unclassified_cnt'] ."<br>";

        $this->load->view('biz/customer/'. $skin, $view);
    }

	//고객 목록 > 그룹관리 > 모달 고객 목록
	public function ajax_group_list(){
		$mem_id = $this->member->item('mem_id'); //회원 번호
		$mem_userid = $this->member->item('mem_userid'); //회원 아이디
		//고객 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_ab_". $mem_userid ." ";
		//echo $sql ."<br>";
		$result['count'] = $this->db->query($sql)->row()->cnt;

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
				, (SELECT COUNT(*) FROM cb_ab_". $mem_userid ."_group b WHERE b.aug_group_id IN (
					SELECT cg_id FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_id AND c.cg_use_yn = 'Y'
				)) AS lv1_cnt /* 그룹1차 고객수 */
				, (SELECT COUNT(*) FROM cb_ab_". $mem_userid ."_group b WHERE a.cg_id = b.aug_group_id) AS lv2_cnt /* 그룹2차 고객수 */
				, (SELECT COUNT(*) FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_parent_id AND c.cg_use_yn = 'Y') AS rowspan /* 라인수 */
			FROM cb_customer_group a
			WHERE cg_mem_id = '". $mem_id ."'
			AND cg_use_yn = 'Y'
		) t
		ORDER BY cg_sort ASC ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result['list'] = $this->db->query($sql)->result();
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

    //고객 목록 > 그룹관리 > 그룹 추가/수정
	public function group_save(){
        $glv = $this->input->post('glv'); //레벨
        $gnm = $this->input->post('gnm'); //그룹명
        $pid = $this->input->post('pid'); //부모번호
        $gid = $this->input->post('gid'); //그룹번호
		$gnm = preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>\{\}]/i", "", $gnm); //특수문자 제거
        if($glv == "" or $gnm == "") {
            echo '{"success": false}';
            exit;
        }
        if($gid != ""){ //수정
			//그룹 수정
			$group = array();
			$group['cg_gname'] = $gnm; //그룹명
			$where = array();
			$where['cg_id'] = $gid; //그룹번호
			$group['cg_mem_id'] = $this->member->item('mem_id'); //회원번호
			$this->db->update("cb_customer_group", $group, $where);
		}else{ //신규
			//그룹번호 생성
			$sql = "SELECT IFNULL(MAX(cg_id),0)+1 AS cg_id FROM cb_customer_group ";
			$cg_id = $this->db->query($sql)->row()->cg_id;
			$cg_parent_id = $cg_id; //부모번호

			if($glv == 1){ //그룹 1차
				//기존 정렬번호 증가
				$sql = "UPDATE cb_customer_group SET cg_sort = cg_sort+100 WHERE cg_mem_id = '". $this->member->item('mem_id') ."' ";
				$this->db->query($sql);

				//정렬번호 생성
				//SELECT IFNULL(MAX(cg_sort),0)+100 AS cg_sort
				$sql = "
				SELECT 100 AS cg_sort
				FROM cb_customer_group
				WHERE cg_mem_id = '".$this->member->item('mem_id')."'
				AND cg_level = 1
				and cg_use_yn = 'Y' ";
				$cg_sort = $this->db->query($sql)->row()->cg_sort;
			}else{
				//부모 정렬번호 조회
				$sql = "
				SELECT IFNULL(MAX(cg_sort),100) AS cg_sort
				FROM cb_customer_group
				WHERE cg_mem_id = '". $this->member->item('mem_id') ."'
				AND cg_id = '". $pid ."'
				AND cg_level = 1
				AND cg_use_yn = 'Y' ";
				$cg_parent_sort = $this->db->query($sql)->row()->cg_sort;

				//정렬번호 생성
				$sql = "
				SELECT IFNULL(MAX(cg_sort),". $cg_parent_sort .")+1 AS cg_sort
				FROM cb_customer_group
				WHERE cg_mem_id = '".$this->member->item('mem_id')."'
				AND cg_parent_id = '". $pid ."'
				AND cg_level = 2
				and cg_use_yn = 'Y' ";
				$cg_sort = $this->db->query($sql)->row()->cg_sort;
				$cg_parent_id = $pid; //부모번호
			}

			//그룹 추가
			$group = array();
			$group['cg_id'] = $cg_id; //그룹번호
			$group['cg_mem_id'] = $this->member->item('mem_id'); //회원번호
			$group['cg_parent_id'] = $cg_parent_id; //부모번호
			$group['cg_level'] = $glv; //레벨
			$group['cg_gname'] = $gnm; //그룹명
			$group['cg_sort'] = $cg_sort; //정렬번호
			$this->db->replace("cb_customer_group", $group);
		}

		header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }

    //고객 목록 > 그룹관리 > 그룹 삭제
	public function group_del(){
        $mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}
		$glv = $this->input->post('glv'); //레벨
        $gid = $this->input->post('gid'); //그룹번호
        $gb = $this->input->post('gb'); //구분(G:그룹삭제, ALL:그룹/고객삭제) 2021-02-15
        if($gid == "") {
            echo '{"success": false}';
            exit;
        }

		//검색조건
		$sch = "";

		//소속 고객 삭제 2021-02-15
		if($gb == "ALL"){
			$sql = "
			DELETE FROM cb_ab_".$mem_userid."
			WHERE ab_id IN (
				SELECT aug_ab_id
				FROM cb_ab_dhn_group
				WHERE aug_group_id = '". $gid ."'
				AND aug_ab_id NOT IN (
					SELECT aug_ab_id	from cb_ab_dhn_group WHERE aug_ab_id IN (
						SELECT aug_ab_id from cb_ab_dhn_group WHERE aug_group_id = '". $gid ."'
						)
					AND aug_group_id != '". $gid ."'
				)
			) ";
			$this->db->query($sql);
		}

		//그룹에 속해있는 고객정보 삭제
		if($glv == "1"){ //그룹 1차의 경우
			$sch = "AND cg_parent_id = '". $gid ."' "; //하위그룹까지 모두 삭제
			$sql = "
			DELETE	FROM cb_ab_".$mem_userid."_group
			WHERE aug_group_id IN (
				SELECT cg_id
				FROM cb_customer_group
				WHERE cg_mem_id = '". $this->member->item('mem_id') ."'
				AND cg_parent_id = '". $gid ."'
			) ";
		}else{ //그룹 2차의 경우
			$sch = "AND cg_id = '". $gid ."' "; //해당 그룹만 삭제
			$sql = "DELETE	FROM cb_ab_".$mem_userid."_group WHERE aug_group_id = '". $gid ."' ";
		}
		$this->db->query($sql);

		//그룹정보 삭제
        $sql = "DELETE FROM cb_customer_group WHERE cg_mem_id = '". $this->member->item('mem_id') ."' ". $sch ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 고객 목록 > 그룹관리 > 그룹 삭제 : ". $sql);
		$this->db->query($sql);

		header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }

    public function inc_lists_all() {

        $where = " 1=1 "; $param = array();
        if($view['param']['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($view['param']['search_type']=="reject") ? "0" : "1"); }
        if($view['param']['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$view['param']['search_for'].'%'); }
        // 2019.01.17 이수환 고객구분없음 추가로 수정
        //if($view['param']['search_group']) { $where .= " and ab_kind like ? "; array_push($param, '%'.$view['param']['search_group'].'%'); }
        if($view['param']['search_group']) {
            if($view['param']['search_group'] == 'NONE') {
                $where .= " and ifNull(ab_kind, '') = ? "; array_push($param, '');
            } else {
                $where .= " and ifNull(ab_kind, '') like ? "; array_push($param, '%'.$view['param']['search_group'].'%');
            }
        }

        if($view['param']['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%'); }

        $sql = "
			select a.*
			from cb_ab_".$this->member->item('mem_userid')." a
			where ".$where." order by (case when length(a.ab_kind) > 0 then concat('0',a.ab_kind) else '9' end) ,  a.ab_datetime desc" ;
        //log_message("ERROR", "SQL : ".$sql);
        $all_data = $this->db->query($sql, $param)->result();
        echo json_encode($all_data);
    }

    //고객 목록 > 그룹 > 소속그룹 삭제
	public function cus_group_remove(){
        $mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}
		$abid = $this->input->post('abid'); //고객번호
        $gid = $this->input->post('gid'); //그룹번호
        if($abid == "" or $gid == "") {
            echo '{"success": false}';
            exit;
        }
        $sql = "DELETE FROM cb_ab_".$mem_userid."_group WHERE aug_ab_id = '". $abid ."' AND aug_group_id = '". $gid ."' ";
		$this->db->query($sql);
        header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }

    //고객 목록 > 수정
	public function modify()
    {
        $customer_id = $this->input->post('customer_id');
        $status_box = $this->input->post('status_box');	//:none
        $kind = urldecode($this->input->post('kind'));	//:1111
        $addr = urldecode(trim($this->input->post('addr')));	//주소
        $memo = urldecode(trim($this->input->post('memo')));	//메모
        if(!$customer_id) {
            echo '{"success": false}';
            exit;
        }
        $this->ab_status = ($status_box=='reject') ? '0' : '1';
        //$this->ab_kind = $kind;
        $this->ab_addr = $addr;
        $this->ab_memo = $memo;
        $this->db->update("ab_".$this->member->item('mem_userid'), $this, array("ab_id"=>$customer_id));
        header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }

    //고객 등록 > 연락처 직접 입력
	public function write()
    {
        $view = array();
        $view['view'] = array();
		$view['add'] = $this->input->get("add");
		$skin = "write";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		$tels = $this->input->post('tels'); //전화번호 (배열)
        $names = $this->input->post('names'); //이름 (배열)
        $groups = $this->input->post('groups'); //고객구분 (배열)
        $addrs = $this->input->post('addrs'); //주소 (배열)
        if($tels && count($tels) > 0) {
            $this->save_customer($tels, $names, $groups, $addrs);
            return;
        }

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		//고객그룹 조회
		$sql = "
		SELECT
			  cg_id /* 그룹번호 */
			, cg_parent_id /* 부모번호 */
			, cg_level /* 레벨 */
			, cg_gname /* 그룹명 */
		FROM cb_customer_group
		WHERE cg_mem_id = '". $this->member->item('mem_id') ."'
		AND cg_use_yn = 'Y'
		ORDER BY cg_sort ASC ";
		//echo $sql ."<br>";
		$view['customer_group'] = $this->db->query($sql)->result();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/customer',
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

    //고객 등록 => 연락처 직접 입력 => 데아티 저장
	function save_customer($tels, $names, $groups, $addrs)
    {
        $ok = 0;
        $ok_ins = 0; //등록수
		$ok_upd = 0; //등록수
		$ok_can = 0; //실패수
        $duplicated = array();
        $this->Biz_model->make_customer_book($this->member->item('mem_userid'));
        for($n=0;$n<count($tels);$n++) {
            if(!is_numeric(str_replace("-", "", $tels[$n]))) {
                $ok_can++;
                continue;
            }
			$data = array();
            $data['ab_tel'] = str_replace("-", "", $tels[$n]); //전화번호
            $data['ab_name'] = ($names[$n]) ? $names[$n] : ''; //이름
            $data['ab_addr'] = ($addrs[$n]) ? $addrs[$n] : ''; //주소
            $data['ab_group_id'] = ($groups[$n]) ? $groups[$n] : '0'; //그룹번호
            $data["ab_datetime"] = cdate('Y-m-d H:i:s');
            $dp = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_tel=?", array($data['ab_tel']));
            if($dp > 0) {
                array_push($duplicated, array($data['ab_tel'], $data['ab_name']));
				//고객정보 수정
				$where = array();
				$where["ab_tel"] = $data['ab_tel']; //전화번호
				$this->db->update("cb_ab_".$this->member->item('mem_userid'), $data, $where);
                $ok_upd++;
				//고객번호 조회
				$sql = "
				SELECT ab_id
				FROM cb_ab_".$this->member->item('mem_userid')."
				WHERE ab_tel = '". $data['ab_tel'] ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹별 고객수 Query : ".$sql);
				$ab_id = $this->db->query($sql)->row()->ab_id;
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 수정의 경우 ab_id : ".$ab_id);
            } else {
                $this->db->insert("ab_".$this->member->item('mem_userid'), $data);
				$ab_id = $this->db->insert_id();
                $ok_ins++;
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 추가의 경우 ab_id : ".$ab_id);
                if ($this->db->error()['code'] < 1) { $ok++; }
            }

			if($data['ab_group_id'] != "0"){ //그룹이 선택된 경우
				//그룹별 고객 확인
				$sql = "
				SELECT COUNT(1) AS cnt
				FROM cb_ab_".$this->member->item('mem_userid')."_group
				WHERE aug_ab_id = '". $ab_id ."'
				AND aug_group_id = '". $data['ab_group_id'] ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹별 고객 확인 Query : ".$sql);
				$cnt = $this->db->query($sql)->row()->cnt;
				if($cnt == 0){ //그룹별 고객 정보가 없는 경우
					$group = array();
					$group["aug_ab_id"] = $ab_id; //고객번호
					$group["aug_group_id"] = $data['ab_group_id']; //그룹번호
					$this->db->replace("cb_ab_".$this->member->item('mem_userid')."_group", $group); //그룹별 고객 정보 추가
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹별 고객 정보 추가 OK");
				}
			}
        }
        header('Content-Type: application/json');
        echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.count($duplicated).', "ok_ins": '. $ok_ins .', "ok_upd": '. $ok_upd .', "ok_can": '. $ok_can .'}';
    }

    //고객 등록 => 엑셀 업로드
	function upload()
    {
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 고객 등록 => 엑셀 업로드 시작");
		$mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);

		//업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        //log_message("ERROR", "Excel upload 시작");

        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        //log_message("ERROR",$inputFileType );

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();

        // 쉬트별로 읽기
        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        if($this->input->post('real')) {
            //$tmpFile = '/var/www/html/uploads/'.cdate('YmdHis').'.txt';
            $dir = $this->input->server("DOCUMENT_ROOT") .'/uploads/temp/';

			//오래된 파일 삭제
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오래된 파일 삭제 1");
			if ($handle = opendir($dir)) {
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오래된 파일 삭제 2");
				while (false !== ($file = readdir($handle))) {
					if ((time()-filectime($dir.$file)) < 24*60*60*1) { //1일 넘는 오래된 파일을 삭제
						if (preg_match('/\.txt$/i', $file)) {
							unlink($dir.$file);
							//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 삭제 file : ". $dir.$file);
						}
					}
				}
			}

			//고객 정보 임시파일 생성
			$tmpFile = $dir . cdate('YmdHis') .'.txt';
            $fp = fopen($tmpFile, 'w');

        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sheetsCount : ". $sheetsCount);

        for($i = 0; $i < $sheetsCount; $i++){
            $sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > highestRow : ". $highestRow);

            //$han_field = array("전화번호", "이름", "고객구분", "삭제");
            //$eng_field = array("ab_tel", "ab_name", "ab_kind", "del" );
			$han_field = array("전화번호", "이름", "그룹 1차", "그룹 2차", "주소");
            $eng_field = array("ab_tel", "ab_name", "ab_group_id", "ab_addr", "del");
            $base_pos = array(0, 1, 2, 3);
            $pos = array();

            // 한줄읽기
            for ($row = 1; $row <= $highestRow; $row++){
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                // 첫줄에 필드내용
                if($row==1) {
                    foreach($han_field as $f) {
                        for($n=0;$n<count($rowData[0]);$n++) {
                            if($f==$rowData[0][$n]) {
                                array_push($pos, $n);
                                break;
                            }
                        }
                    }
                    // 찾지못한경우 기본값으로 설정, 찾은경우 다음행으로 이동
                    if(count($han_field) != count($pos)) { $pos = $base_pos; } else { continue; }
                }
                unset($data);
                $data = array();
                // $rowData가 한줄의 데이터를 셀별로 배열처리 됩니다.
                // 엑셀의 날짜가 간혹 41852 이렇게 보일때가 있습니다. 이것은 엑셀서식이기에 우리가 흔히 쓰는 형식으로 변경을 해줘야 할때가 있습니다
                //$date = PHPExcel_Style_NumberFormat::toFormattedString($날짜변수, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $err = false;
                $col = 0;
                $ab_group_id = 0;
                $ab_addr = "";
                foreach($pos as $p) {
                    if($col==0) {
                        if(!is_numeric(preg_replace("/[^0-9]*/s", "", $rowData[0][$p]))) {//; str_replace(" ","",str_replace("-", "", $rowData[0][$p])))) {
                            $err = true;
                            break;
                        } else {
                            $data[$eng_field[$col]] = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]); //str_replace(" ", "", str_replace("-", "", $rowData[0][$p]));
                        }
                    } else {
                        //$data[$eng_field[$col]] = $rowData[0][$p];
                        //$data[$eng_field[$col]] = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $rowData[0][$p]);
                        $data[$eng_field[$col]] = trim(preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>\{\}]/i", "", $rowData[0][$p]));
						if($data[$eng_field[$col]] == "선택사항입니다" || $data[$eng_field[$col]] == "선택사항 입니다" || $data[$eng_field[$col]] == "선택사항 입니다."){
							$data[$eng_field[$col]] = "";
						}
						if($data[$eng_field[$col]] != ""){
							$data[$eng_field[$col]] = preg_replace('/\r\n|\r|\n/','',$data[$eng_field[$col]]); //줄바꿈 제거
						}

						if($col==2) { //그룹 1차
							if($data[$eng_field[$col]] != "") { //그룹 1차가 있는 경우
								//그룹번호 조회
								$sql = "
								SELECT cg_id
								FROM cb_customer_group
								WHERE cg_mem_id = '". $mem_id ."'
								AND cg_gname = '". $data[$eng_field[$col]] ."'
								AND cg_level = 1
								AND cg_use_yn = 'Y' ";
								$cg_id = $this->db->query($sql)->row()->cg_id;
								//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
								if($cg_id == ""){ //등록된 그룹이 없는 경우
									//그룹번호 생성
									$sql = "SELECT IFNULL(MAX(cg_id),0)+1 AS cg_id FROM cb_customer_group ";
									$cg_id = $this->db->query($sql)->row()->cg_id;

									//기존 정렬번호 증가
									$sql = "UPDATE cb_customer_group SET cg_sort = cg_sort+100 WHERE cg_mem_id = '". $mem_id ."' ";
									$this->db->query($sql);

									//정렬번호 생성
									//SELECT IFNULL(MAX(cg_sort),0)+100 AS cg_sort
									$sql = "
									SELECT 100 AS cg_sort
									FROM cb_customer_group
									WHERE cg_mem_id = '".$mem_id."'
									AND cg_level = 1
									and cg_use_yn = 'Y' ";
									$cg_sort = $this->db->query($sql)->row()->cg_sort;

									//그룹 1차 추가
									$group = array();
									$group['cg_id'] = $cg_id; //그룹번호
									$group['cg_mem_id'] = $mem_id; //회원번호
									$group['cg_parent_id'] = $cg_id; //부모번호
									$group['cg_level'] = 1; //레벨
									$group['cg_gname'] = $data[$eng_field[$col]]; //그룹명
									$group['cg_sort'] = $cg_sort; //정렬번호
									$this->db->replace("cb_customer_group", $group);
								}
								$ab_group_id = $cg_id;
							}else{
								$ab_group_id = 0;
							}
						}else if($col==3) { //그룹 2차
							//$data[$eng_field[$col]] = "";
							//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data[". $row ."][". $col ."] ab_group_id : ". $ab_group_id);
							if($ab_group_id != 0 && $data[$eng_field[$col]] != "") { //그룹 2차가 있는 경우
								//그룹번호 조회
								$sql = "
								SELECT cg_id
								FROM cb_customer_group
								WHERE cg_mem_id = '". $mem_id ."'
								AND cg_parent_id = '". $ab_group_id ."'
								AND cg_gname = '". $data[$eng_field[$col]] ."'
								AND cg_level = 2
								AND cg_use_yn = 'Y' ";
								$cg_id = $this->db->query($sql)->row()->cg_id;
								//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
								if($cg_id == ""){ //등록된 그룹이 없는 경우
									//그룹번호 생성
									$sql = "SELECT IFNULL(MAX(cg_id),0)+1 AS cg_id FROM cb_customer_group ";
									$cg_id = $this->db->query($sql)->row()->cg_id;

									//부모 정렬번호 조회
									$sql = "
									SELECT IFNULL(MAX(cg_sort),100) AS cg_sort
									FROM cb_customer_group
									WHERE cg_mem_id = '". $mem_id ."'
									AND cg_id = '". $ab_group_id ."'
									AND cg_level = 1
									AND cg_use_yn = 'Y' ";
									$cg_parent_sort = $this->db->query($sql)->row()->cg_sort;

									//정렬번호 생성
									$sql = "
									SELECT IFNULL(MAX(cg_sort),". $cg_parent_sort .")+1 AS cg_sort
									FROM cb_customer_group
									WHERE cg_mem_id = '".$mem_id."'
									AND cg_parent_id = '". $ab_group_id ."'
									AND cg_level = 2
									and cg_use_yn = 'Y' ";
									$cg_sort = $this->db->query($sql)->row()->cg_sort;

									//그룹 2차 추가
									$group = array();
									$group['cg_id'] = $cg_id; //그룹번호
									$group['cg_mem_id'] = $mem_id; //회원번호
									$group['cg_parent_id'] = $ab_group_id; //부모번호
									$group['cg_level'] = 2; //레벨
									$group['cg_gname'] = $data[$eng_field[$col]]; //그룹명
									$group['cg_sort'] = $cg_sort; //정렬번호
									$this->db->replace("cb_customer_group", $group);
								}
								$ab_group_id = $cg_id;
							}
						}else if($col==4) { //주소
							$ab_addr = $data[$eng_field[$col]];
							//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ab_addr : ". $ab_addr);
						}
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data[". $col ."] : ". $data[$eng_field[$col]]);
                    }
                    $col++;
                }
                if($err) continue;
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ab_group_id[". $p ."] : ". $ab_group_id);
				$data["ab_kind"] = "";
				$data["del"] = "";
				$data["ab_group_id"] = $ab_group_id;
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');
                $data["ab_addr"] = $ab_addr;

                if($this->input->post('real')) {
                    //3,01098790716,고객명1,1222,,2019-02-20 13:24:00.505
                    $outstr = $mem_id.','.$data['ab_tel'].','.$data['ab_name'].','.$data['ab_kind'].','.$data['del'].','.$data['ab_datetime'].','.$data['ab_group_id'].','.$data['ab_addr'].','.chr(13).chr(10);
                    fwrite($fp,$outstr );
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > outstr : ". $outstr);
                } else {
                    $ok++;
                }
            }
        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > aaaaaaaaaaaaaaa");

        if($fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > File Lock 해제 및 닫기 완료");

            $uptempdel = "delete FROM cb_ab_upload_temp WHERE ab_id = '".$mem_id."'";
            $this->db->query($uptempdel);

            $loadstr = "LOAD DATA LOCAL INFILE '".$tmpFile."' ".
                       "INTO TABLE cb_ab_upload_temp
                        CHARACTER SET utf8mb4
                        FIELDS
                        TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                        LINES
                        TERMINATED BY '\n'
                 ";
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > loadstr : ". $loadstr);
			//IGNORE 1 LINES

            $this->db->query($loadstr);
            if($this->db->error()['code'] >0)  {
                //log_message('error', 'DB ERROR = '.$this->db->_error_message());
            } else {
                unlink($tmpFile); //임시 파일 삭제
            }
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 111111111111");

            $delstr = "select count(1) as cnt FROM cb_ab_".$mem_userid." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = '' and t.ab_id = '".$mem_id."')";
            $dup_qty = $this->db->query($delstr)->row()->cnt;
            //$dup_qty= $this->db->affected_rows();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 22222222222");

            $delstr = "delete a FROM cb_ab_".$mem_userid." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = 'D' and t.ab_id = '".$mem_id."')";
            $this->db->query($delstr);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 333333333333");

            //고객 등록
			$insstr = "INSERT INTO cb_ab_".$mem_userid." (ab_name, ab_tel, ab_group_id, ab_datetime)
                        SELECT DISTINCT ab_name, ab_tel, ab_group_id, ab_datetime
                        from cb_ab_upload_temp t
                        WHERE ab_status = ''
                          and ab_id ='".$mem_id."'
                          and not exists ( select 1 from cb_ab_".$mem_userid." a where t.ab_tel = a.ab_tel )";
            //$this->db->query($insstr);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > insstr : ". $insstr);
            //$ok = $this->db->affected_rows();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 444444444444");

			//고객 등록
			//임시 고객 정보 조회
			$sql = "
			SELECT *
			from cb_ab_upload_temp
			WHERE ab_id = '". $mem_id ."' ";
			$temps = $this->db->query($sql)->result();
			foreach($temps as $r){
				$data = array();
				$data["ab_name"] = $r->ab_name; //고객명
				$data["ab_tel"] = $r->ab_tel; //전화번호
				$data["ab_group_id"] = 0; //$r->ab_group_id; //그룹번호
				$data["ab_datetime"] = $r->ab_datetime; //등록일자
				$data["ab_addr"] = $r->ab_addr; //주소
				//고객수
				$sql = "
				SELECT COUNT(1) AS cnt
				FROM cb_ab_".$mem_userid."
				WHERE ab_tel = '". $r->ab_tel ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 고객수 Query : ".$sql);
				$cnt = $this->db->query($sql)->row()->cnt;
				if($cnt == 0){ //그룹별 고객 정보가 없는 경우
					$this->db->replace("cb_ab_".$mem_userid, $data); //그룹별 고객 정보 추가
				}else{
					$where = array();
					$where["ab_tel"] = $data["ab_tel"]; //템플릿번호
					$this->db->update("cb_ab_".$mem_userid, $data, $where);
				}
			}

			//그룹별 고객 추가
			$sql = "
			SELECT
				  a.ab_id /* 고객번호 */
				, b.ab_group_id /* 그룹번호 */
			FROM cb_ab_".$mem_userid." a
			LEFT JOIN cb_ab_upload_temp b ON a.ab_tel = b.ab_tel
			WHERE b.ab_id = '". $mem_id ."'
			AND IFNULL(b.ab_group_id,0) > 0 ";
			$groups = $this->db->query($sql)->result();
			foreach($groups as $r){
				$sql = "
				SELECT COUNT(1) AS cnt
				FROM cb_ab_".$mem_userid."_group
				WHERE aug_ab_id = '". $r->ab_id ."'
				AND aug_group_id = '". $r->ab_group_id ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹별 고객수 Query : ".$sql);
				$cnt = $this->db->query($sql)->row()->cnt;
				if($cnt == 0){ //그룹별 고객 정보가 없는 경우
					$data = array();
					$data["aug_ab_id"] = $r->ab_id; //고객번호
					$data["aug_group_id"] = $r->ab_group_id; //그룹번호
					$this->db->replace("cb_ab_".$mem_userid."_group", $data); //그룹별 고객 정보 추가
				}
			}
        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 555555555");

        header('Content-Type: application/json');
        if($this->input->post('real')) {
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 66666666666");
			echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.$dup_qty.', "deleted":'.$del_qty.', "status": "success"}';
        } else {
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 77777777777");
			echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.', "uploaded": '.$ok.'}';
        }
    }

    //고객 등록 => 엑셀 업로드
	function excel_upload()
    {
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 고객 등록 => 엑셀 업로드 시작");
		$ok = 0; //전체수
		$ok_ins = 0; //등록수
		$ok_upd = 0; //등록수
		$ok_del = 0; //삭제수
        $ok_zero = 0; // 0안붙은 친구들
		$mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
        $group_id = $this->input->post("group_id");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid .", group_id : ". $group_id .", _FILES['file']['tmp_name'] : ". $_FILES['file']['tmp_name']);

		//업로드된 엑셀 파일을 처리함
	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > inputFileType : ". $inputFileType);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
			$sheetsCount = $objPHPExcel->getSheetCount();

			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
				//log_message("ERROR", "Rows : ".$highestRow);
				// 한줄읽기
				for ($row = 2; $row <= $highestRow; $row++){
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

					$data = array();
                    if (substr(trim($rowData[0][0]), 0, 1) != "0"){
                        // $ab_flag = (empty(trim($rowData[0][3]))) ? "" : preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>\{\}]/i", "", trim($rowData[0][3])); //구분 (D.삭제, R.수신거부)
                        // if($ab_flag != "") $ab_flag = strtoupper($ab_flag); //대문자변경
                        // if($ab_flag != "D"){
                            $ok_zero++;
                            continue;
                        // }

                    }
					$data["ab_tel"] = (empty(trim($rowData[0][0]))) ? "" : preg_replace("/[^0-9]*/s", "", trim($rowData[0][0])); //전화번호
					$data["ab_name"] = (empty(trim($rowData[0][1]))) ? "" : preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>\{\}]/i", "", trim($rowData[0][1])); //고객명
					$data["ab_group_id"] = 0; //그룹번호
					$data["ab_datetime"] = cdate('Y-m-d H:i:s'); //등록일자
					$data["ab_addr"] = (empty(trim($rowData[0][2]))) ? "" : preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>\{\}]/i", "", trim($rowData[0][2])); //주소
					$ab_flag = (empty(trim($rowData[0][3]))) ? "" : preg_replace("/[#\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>\{\}]/i", "", trim($rowData[0][3])); //구분 (D.삭제, R.수신거부)
					if($ab_flag != "") $ab_flag = strtoupper($ab_flag); //대문자변경
					if($ab_flag == "R"){ //구분 (D.삭제, R.수신거부)
						$data["ab_status"] = "0"; //상태 (0.수신거부, 1.정상)
					}

					if($data["ab_tel"] != ""){
						//고객번호 조회
						$sql = "
						SELECT ifnull(max(ab_id),0) AS ab_id
						FROM cb_ab_".$mem_userid."
						WHERE ab_tel = '". $data["ab_tel"] ."' ";
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 고객수 Query : ".$sql);
						$ab_id = $this->db->query($sql)->row()->ab_id;
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ab_id(1) : ".$ab_id);
						if($ab_id == "0"){ //고객 정보가 없는 경우
							if($ab_flag != "D"){ //삭제가 아닌 경우
								//고객정보 추가
								$rtn = $this->db->replace("cb_ab_".$mem_userid, $data); //그룹별 고객 정보 추가
								$ab_id = $this->db->insert_id();
								$ok_ins++; //등록수 증가
							}
						}else{
							if($ab_flag != "D"){ //삭제가 아닌 경우
								//고객정보 수정
								$where = array();
								$where["ab_id"] = $ab_id; //고객번호
								$rtn = $this->db->update("cb_ab_".$mem_userid, $data, $where);
								$ok_upd++; //수정수 증가
							}else{
								//그룹정보 삭제
								$where = array();
								$where["aug_ab_id"] = $ab_id; //고객번호
								$rtn = $this->db->delete("cb_ab_".$mem_userid."_group", $where);

								//고객정보 삭제
								$where = array();
								$where["ab_id"] = $ab_id; //고객번호
								$rtn = $this->db->delete("cb_ab_".$mem_userid, $where);
								$ok_del++; //삭제수 증가
							}
						}
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ab_id(2) : ".$ab_id);
						$ok++;

						//그룹별 고객 추가
						if($group_id != "" and $group_id != "0" and $ab_flag != "D"){
							$sql = "
							SELECT COUNT(1) AS cnt
							FROM cb_ab_".$mem_userid."_group
							WHERE aug_ab_id = '". $ab_id ."'
							AND aug_group_id = '". $group_id ."' ";
							//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹별 고객수 Query : ".$sql);
							$cnt = $this->db->query($sql)->row()->cnt;
							if($cnt == 0){ //그룹별 고객 정보가 없는 경우
								$data = array();
								$data["aug_ab_id"] = $ab_id; //고객번호
								$data["aug_group_id"] = $group_id; //그룹번호
								$this->db->replace("cb_ab_".$mem_userid."_group", $data); //그룹별 고객 정보 추가
							}
						}
					}
				}
			}
        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 555555555");
        header('Content-Type: application/json');
		echo '{"status": "success", "ok": '. $ok .', "ok_ins": '. $ok_ins .', "ok_upd": '. $ok_upd .', "ok_del": '. $ok_del .', "ok_zero": '. $ok_zero .'}';
    }

    function uploadbylist()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        //log_message("ERROR", "Excel upload 시작  ");

        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);

        //log_message("ERROR",$inputFileType );

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();

        // 쉬트별로 읽기
        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        if($this->input->post('real')) {
            $tmpFile = '/var/www/html/uploads/'.cdate('YmdHis').'.txt';
            $fp = fopen($tmpFile, 'w');
        }
        for($i = 0; $i < $sheetsCount; $i++)
        {
            $sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $han_field = array("전화번호", "이름", "고객구분", "삭제");
            $eng_field = array("ab_tel", "ab_name", "ab_kind", "del" );
            $base_pos = array(0, 1, 2, 3);
            $pos = array();

            // 한줄읽기
            for ($row = 1; $row <= $highestRow; $row++)
            {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                // 첫줄에 필드내용
                if($row==1) {
                    foreach($han_field as $f) {
                        for($n=0;$n<count($rowData[0]);$n++) {
                            if($f==$rowData[0][$n]) {
                                array_push($pos, $n);
                                break;
                            }
                        }
                    }
                    // 찾지못한경우 기본값으로 설정, 찾은경우 다음행으로 이동
                    if(count($han_field) != count($pos)) { $pos = $base_pos; } else { continue; }
                }
                unset($data);
                $data = array();
                // $rowData가 한줄의 데이터를 셀별로 배열처리 됩니다.
                // 엑셀의 날짜가 간혹 41852 이렇게 보일때가 있습니다. 이것은 엑셀서식이기에 우리가 흔히 쓰는 형식으로 변경을 해줘야 할때가 있습니다
                //$date = PHPExcel_Style_NumberFormat::toFormattedString($날짜변수, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $err = false;
                $col = 0;
                foreach($pos as $p) {
                    if($col==0) {
                        if(!is_numeric(preg_replace("/[^0-9]*/s", "", $rowData[0][$p]))) {//; str_replace(" ","",str_replace("-", "", $rowData[0][$p])))) {
                            $err = true;
                            break;
                        } else {
                            $data[$eng_field[$col]] = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]); //str_replace(" ", "", str_replace("-", "", $rowData[0][$p]));
                        }
                    } else {
                        //$data[$eng_field[$col]] = $rowData[0][$p];
                        $data[$eng_field[$col]] = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $rowData[0][$p]);
                    }
                    $col++;
                }
                if($err) continue;
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');

                if($this->input->post('real')) {
                    //3,01098790716,고객명1,1222,,2019-02-20 13:24:00.505
                    $outstr = $this->input->post('mem_id').','.$data['ab_tel'].','.$data['ab_name'].','.$data['ab_kind'].','.$data['del'].','.$data['ab_datetime'].chr(13).chr(10);
                    fwrite($fp,$outstr );
                } else {
                    $ok++;
                }
            }
        }

        if($fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            //log_message("ERROR", 'File Lock 해제 및 닫기 완료');

            $uptempdel = "delete FROM cb_ab_upload_temp WHERE ab_id = '".$this->input->post('mem_id')."'";
            $this->db->query($uptempdel);

            $loadstr = "LOAD DATA LOCAL INFILE '".$tmpFile."'".
                       "INTO TABLE cb_ab_upload_temp
                        CHARACTER SET utf8mb4
                        FIELDS
                        TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                        LINES
                        TERMINATED BY '\n'
                 ";
            //IGNORE 1 LINES

            $this->db->query($loadstr);
            if($this->db->error()['code'] >0)  {
                //log_message('error', 'DB ERROR = '.$this->db->_error_message());
            } else {
                unlink($tmpFile);
            }

            $delstr = "select count(1) as cnt FROM cb_ab_".$this->input->post('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = '' and t.ab_id = '".$this->input->post('mem_id')."')";
            $dup_qty = $this->db->query($delstr)->row()->cnt;
            //$dup_qty= $this->db->affected_rows();

            $delstr = "delete a FROM cb_ab_".$this->input->post('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = 'D' and t.ab_id = '".$this->input->post('mem_id')."')";
            $this->db->query($delstr);

            $insstr = "INSERT INTO cb_ab_".$this->input->post('mem_userid')."(ab_name, ab_tel, ab_kind, ab_datetime)
                        SELECT DISTINCT ab_name, ab_tel, ab_kind, ab_datetime
                        from cb_ab_upload_temp t
                        WHERE ab_status = ''
                          and ab_id ='".$this->input->post('mem_id')."'
                          and not exists ( select 1 from cb_ab_".$this->input->post('mem_userid')." a where t.ab_tel = a.ab_tel )";
            $this->db->query($insstr);
            $ok = $this->db->affected_rows();
        }

        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"nrow_len": '.$ok.', "dup_list": '.json_encode($duplicated).', "status": "success", "duplicated": '.$dup_qty.', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
    }

    //고객삭제
	function deletebylist()
    {
        $mem_id = $this->input->post('mem_id');
        $mem_userid = $this->input->post('mem_userid');

        $sql = "delete from cb_ab_".$mem_userid;
        $this->db->query($sql);

		$sql = "delete from cb_ab_".$mem_userid."_group";
		$this->db->query($sql); //그룹별 고객 목록
    }

    function upload__()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        //log_message("ERROR", "Excel upload 시작  ");
        /*
         업로드 시 자료 초기화 방지 .
         if($this->input->post('real')) {

         $sql = "DROP TABLE IF EXISTS `cb_ab_".$this->member->item('mem_userid')."`";
         $this->db->query($sql);
         $this->Biz_model->make_customer_book($this->member->item('mem_userid'));
         }
         */

        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();

        // 쉬트별로 읽기
        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        for($i = 0; $i < $sheetsCount; $i++)
        {
            $sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $han_field = array("전화번호", "이름", "고객구분", "삭제");
            $eng_field = array("ab_tel", "ab_name", "ab_kind", "del" );
            $base_pos = array(0, 1, 2, 3);
            $pos = array();

            // 한줄읽기
            for ($row = 1; $row <= $highestRow; $row++)
            {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                // 첫줄에 필드내용
                if($row==1) {
                    foreach($han_field as $f) {
                        for($n=0;$n<count($rowData[0]);$n++) {
                            if($f==$rowData[0][$n]) {
                                array_push($pos, $n);
                                break;
                            }
                        }
                    }
                    // 찾지못한경우 기본값으로 설정, 찾은경우 다음행으로 이동
                    if(count($han_field) != count($pos)) { $pos = $base_pos; } else { continue; }
                }
                unset($data);
                $data = array();
                // $rowData가 한줄의 데이터를 셀별로 배열처리 됩니다.
                // 엑셀의 날짜가 간혹 41852 이렇게 보일때가 있습니다. 이것은 엑셀서식이기에 우리가 흔히 쓰는 형식으로 변경을 해줘야 할때가 있습니다
                //$date = PHPExcel_Style_NumberFormat::toFormattedString($날짜변수, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $err = false;
                $col = 0;
                foreach($pos as $p) {
                    if($col==0) {
                        if(!is_numeric(str_replace("-", "", $rowData[0][$p]))) {
                            $err = true;
                            break;
                        } else {
                            $data[$eng_field[$col]] = str_replace("-", "", $rowData[0][$p]);
                        }
                    } else {
                        $data[$eng_field[$col]] = $rowData[0][$p];
                    }
                    $col++;
                }
                if($err) continue;
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');

                if($this->input->post('real')) {
                    /* 전화번호가 기존에 존재 하는지 확인 */
                    $dp = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_tel=? " /* and ifnull(ab_kind,'')=ifnull(?, '')"*/, array($data['ab_tel']/*, $data['ab_kind']*/));

                    /*
                     * 삭제 필드가 값이 있다면 전화 번호 삭제 시도
                     */
                    if($data['del'] == 'D') {
                        if($dp > 0) {
                            $this->db->query("delete from cb_ab_".$this->member->item('mem_userid')." where ab_tel = '".$data['ab_tel']."'");
                            $del_qty ++;
                        }
                    } else {
                        unset($data['del']);
                        if($dp > 0) {
                            array_push($duplicated, array($data['ab_tel'], $data['ab_name']));
                        } else {
                            $this->db->insert("ab_".$this->member->item('mem_userid'), $data);
                            if ($this->db->error()['code'] < 1) { $ok++; }
                        }
                    }
                } else {
                    $ok++;
                }
            }
        }
        //log_message("ERROR", "Excel upload 끝 ( ".$highestRow." )");
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.count($duplicated).', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
    }

    public function upload_() {


        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }

        $this->load->library("ExcelReader");

        $xlsx = new XlsxReader();

        $inputFileName = $_FILES['file']['tmp_name'];

        //log_message("ERROR", "Excel upload 시작  ");

        $xlsx->init($inputFileName);

        $xlsx->load_sheet(1);

        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;

        $highestRow = $xlsx->rowsize;
        $highestColumn = $xlsx->colsize;

        $han_field = array("전화번호", "이름", "고객구분", "삭제");
        $eng_field = array("ab_tel", "ab_name", "ab_kind", "del" );
        $base_pos = array(0, 1, 2, 3);
        $pos = array();

        // 한줄읽기
        for ($row = 1; $row <= $highestRow; $row++)
        {

            unset($data);

            $data = array();

            $err = false;
            $col = 0;

            $data['ab_tel'] = $xlsx->val($row, 0);
            $data['ab_name'] = $xlsx->val($row, 1);
            $data['ab_kind'] = $xlsx->val($row, 2);
            $data['del'] = $xlsx->val($row, 3);
            $data["ab_datetime"] = cdate('Y-m-d H:i:s');

            if($this->input->post('real')) {
                /* 전화번호가 기존에 존재 하는지 확인 */
                //$dp = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_tel=? " /* and ifnull(ab_kind,'')=ifnull(?, '')"*/, array($data['ab_tel']/*, $data['ab_kind']*/));
                $dp = 0;
                /*
                 * 삭제 필드가 값이 있다면 전화 번호 삭제 시도
                 */
                if($data['del'] == 'D') {
                    if($dp > 0) {
                        $this->db->query("delete from cb_ab_".$this->member->item('mem_userid')." where ab_tel = '".$data['ab_tel']."'");
                        $del_qty ++;
                    }
                } else {
                    unset($data['del']);
                    if($dp > 0) {
                        array_push($duplicated, array($data['ab_tel'], $data['ab_name']));
                    } else {
                        $this->db->insert("ab_".$this->member->item('mem_userid'), $data);
                        if ($this->db->error()['code'] < 1) { $ok++; }
                    }
                }
            } else {
                $ok++;
            }
        }

        $xlsx->close();

        //log_message("ERROR", "Excel upload 끝 ( ".$highestRow." )");

        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.count($duplicated).', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }

    }

    //고객관리 > 엑셀 다운로드
	public function download()
    {
        //log_message("ERROR", "EXCEL 다운로드 시작");
        $post = array();
        $post['search_type'] = $this->input->post("search_type");
        $post['search_for'] = $this->input->post("search_for");
        $post['search_group'] = $this->input->post("search_group");
        $post['search_name'] = $this->input->post("search_name");
        $post['searchGlv'] = $this->input->post("searchGlv"); //그룹레벨
        $post['searchGid'] = $this->input->post("searchGid"); //그룹번호

		$mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}

        //log_message("ERROR", "EXCEL 다운로드 >> ".$this->input->post("search_group"));

		$param = array($this->member->item('mem_id'));
		// 2022-09-07 b.mem_tel_mark_flag, b.mem_parent_tel_mark_flag 필드 추가 및 $mem_tel_mark_flag, $mem_parent_tel_mark_flag변수에 값추가.
		$rs = $this->db->query("select mem_tel_mark_flag, mem_parent_tel_mark_flag from cb_member where mem_id=?", $param)->row();

		$mem_tel_mark_flag = $rs->mem_tel_mark_flag;
		$mem_parent_tel_mark_flag = $rs->mem_parent_tel_mark_flag;

		log_message("ERROR", "Customer TEL Download => mem_tel_mark_flag : ".$mem_tel_mark_flag);
		log_message("ERROR", "Customer TEL Download => mem_parent_tel_mark_flag : ".$mem_parent_tel_mark_flag);

        $where = " 1=1 ";
        $param = array();
        if($post['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($post['search_type']=="reject") ? "0" : "1"); }
        if($post['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$post['search_for'].'%'); }
        //         if($post['search_group']) { $where .= " and ab_kind like ? "; array_push($param, '%'.$post['search_group'].'%'); }
        //         if($post['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$post['search_name'].'%'); }

        if($post['search_group']) {
            if($post['search_group']=='FT') {
                $where .= " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($post['search_group']=='NFT') {
                $where .= " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($post['search_group']=='NONE') {
                $where .= " and ab_kind = ? "; array_push($param, '');
            } else {
                $where .= " and ab_kind = ? "; array_push($param, $view['param']['search_group']);
            }
        }

        if($view['param']['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%'); }

        if($post['searchGlv'] != "" && $post['searchGid'] != ""){ //그룹별 조회
			if($post['searchGlv'] == 1){ //그룹 1차
				$where .= "
				AND ab_id IN (
					SELECT aug_ab_id
					FROM cb_ab_".$mem_userid."_group
					WHERE aug_group_id IN (
						SELECT cg_id
						FROM cb_customer_group
						WHERE cg_mem_id = '". $this->member->item("mem_id") ."'
						AND cg_parent_id = '". $post['searchGid'] ."'
						AND cg_use_yn = 'Y'
					)
				) ";
			}else{ //그룹 2차
				$where .= "
				AND ab_id IN (
					SELECT aug_ab_id
					FROM cb_ab_".$mem_userid."_group
					WHERE aug_group_id IN (
						SELECT cg_id
						FROM cb_customer_group
						WHERE cg_mem_id = '". $this->member->item("mem_id") ."'
						AND cg_id = '". $post['searchGid'] ."'
						AND cg_use_yn = 'Y'
					)
				) ";
			}
		}

        $sql = "
		SELECT a.*
			, (
				SELECT GROUP_CONCAT(cg_gname SEPARATOR ', ')
				FROM cb_customer_group
				WHERE cg_mem_id = '". $this->member->item("mem_id") ."'
				AND cg_use_yn = 'Y'
				AND cg_id IN (
					SELECT aug_group_id
					FROM cb_ab_". $this->member->item("mem_userid") ."_group b
					WHERE b.aug_ab_id = a.ab_id
				)
			) AS gname /* 그룹명1, 그룹명2 */
		FROM cb_ab_".$this->member->item('mem_userid')." a
		WHERE ".$where."
		ORDER BY DATE_FORMAT(ab_datetime,'%Y-%m-%d') DESC, ab_name ASC, ab_tel ASC ";
        //log_message("ERROR", "Query : ".$sql);
        $list = $this->db->query($sql, $param)->result();

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:G1');

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:G3');

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //등록일
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //그룹
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); //전화번호
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); //고객명
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10); //수신여부
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(50); //주소
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(50); //메모

        $this->excel->getActiveSheet()->mergeCells('A1:G1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '고객목록');
        $this->excel->getActiveSheet()->mergeCells('A3:A3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '등록일');
        $this->excel->getActiveSheet()->mergeCells('B3:B3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '그룹');
        $this->excel->getActiveSheet()->mergeCells('C3:C3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '전화번호');
        $this->excel->getActiveSheet()->mergeCells('D3:D3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '고객명');
        $this->excel->getActiveSheet()->mergeCells('E3:E3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '수신여부');
        $this->excel->getActiveSheet()->mergeCells('F3:F3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '주소');
        $this->excel->getActiveSheet()->mergeCells('G3:G3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '메모');

        $row = 4;
        foreach($list as $val) {
            // 수신자 전화번호 국번 별표 처리
            $ab_tel_ori = $val->ab_tel;
            $ab_tel = "";
            $ab_name_ori = $val->ab_name;
            $ab_name = "";

            if (strlen($ab_tel_ori) > 10) {
                $ab_tel = substr($ab_tel_ori, 0, 3)."****".substr($ab_tel_ori, 7);
            } else {
                $ab_tel = substr($ab_tel_ori, 0, 3)."***".substr($ab_tel_ori, 6);
            }

            $ab_name_count = mb_strlen("$ab_name_ori", "UTF-8");

            if ($ab_name_count > 3) {
                //$ab_name = substr($ab_name_ori, 0, 1)."**".substr($ab_name_ori, strlen($ab_name_ori) - 1);
                $tempStr = "";
                for($strCount = 1; $strCount <= $ab_name_count - 2; $strCount++) {
                    $tempStr .= "*";
                }

                $ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8").$tempStr.iconv_substr($ab_name_ori, $ab_name_count - 1, 1, "utf-8");
            } else if ($ab_name_count == 3) {
                //$ab_name = substr($ab_name_ori, 0, 1)."*".substr($ab_name_ori, 2);
                $ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8")."*".iconv_substr($ab_name_ori, 2, 1, "utf-8");
            } else if ($ab_name_count == 2) {
                $ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8")."*";
            } else if ($ab_name_count == 1){
                $ab_name = "*";
            }

            // 2022 03-17 수신자 전화번호 국번 **** 처리구문 변경. 기존: dhnadmin, dhn, TG 계정, (주)지니 계정 보이게 처리, 변경: TG계정, (주)지니 계정 보이게 처리
//             if($this->member->item('mem_id') != '3' && $this->member->item('mem_id') != '6' && $this->member->item('mem_id') != '2' && $this->member->item('mem_id') != '962') {
//                 $login_stack = $this->session->userdata('login_stack');
//                 if($this->session->userdata('login_stack')) {
//                     $login_stack = $this->session->userdata('login_stack');
//                     if($login_stack[0] == '3' || $login_stack[0] == '6' || $login_stack[0] == '2' || $login_stack[0] == '962') {
//                         $ab_tel = $ab_tel_ori;
//                         $ab_name = $ab_name_ori;
//                     }
//                 }
//             } else {
//                 $ab_tel = $ab_tel_ori;
//                 $ab_name = $ab_name_ori;
//             }
//             if($this->member->item('mem_id') != '738' && $this->member->item('mem_id') != '962') {
//                 $login_stack = $this->session->userdata('login_stack');
//                 if($this->session->userdata('login_stack')) {
//                     $login_stack = $this->session->userdata('login_stack');
//                     if($login_stack[0] == '738' || $login_stack[0] == '962') {
//                         $ab_tel = $ab_tel_ori;
//                         $ab_name = $ab_name_ori;
//                     }
//                 }
//             } else {
//                 $ab_tel = $ab_tel_ori;
//                 $ab_name = $ab_name_ori;
//             }


            if($mem_tel_mark_flag != 'Y' && $this->member->item('mem_id') != '738' && $this->member->item('mem_id') != '962') {
                $login_stack = $this->session->userdata('login_stack');
                if($this->session->userdata('login_stack')) {
                    $login_stack = $this->session->userdata('login_stack');
                    if((($login_stack[0] == '3'||$login_stack[0] == '2') && $mem_parent_tel_mark_flag == 'Y') || $login_stack[0] == '738' || $login_stack[0] == '962') {
                        $ab_tel = $ab_tel_ori;
                        $ab_name = $ab_name_ori;
                    }
                } else {
                    if ((($this->member->item('mem_id') == '3'||$this->member->item('mem_id') == '2') && $mem_parent_tel_mark_flag == 'Y')) {
                        $ab_tel = $ab_tel_ori;
                        $ab_name = $ab_name_ori;
                    }
                }
            } else {
                $ab_tel = $ab_tel_ori;
                $ab_name = $ab_name_ori;
            }


            // 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->ab_datetime); //등록일
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->gname); //그룹
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $ab_tel); //전화번호
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $ab_name); //고객명
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($val->ab_status=='1') ? '정상' :'수신거부'); //수신여부
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->ab_addr); //주소
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->ab_memo); //메모
            $row++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="statistics_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    //고객 목록 > 그룹이동
	public function chg_group(){
        $orgGid = $this->input->post('orgGid'); //이전 그룹번호
        $newGid = $this->input->post('newGid'); //변경 그룹번호
        $chkIds = $this->input->post('chkIds'); //선택한 고객번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orgGid : ". $orgGid .", newGid : ". $newGid .", chkIds : ". $chkIds);
		$mem_id = $this->input->post("mem_id");
        $mem_userid = $this->input->post("mem_userid");
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_userid)) {
		    $mem_userid = $this->member->item('mem_userid');
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);
		if($newGid == "" or $chkIds == "") {
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > exit");
			echo '{"status": "error"}';
			exit;
		}
		if($orgGid != ""){ //이전 그룹번호 초기화
			//$sql = "delete from cb_ab_dhn_group where aug_ab_id IN (". $chkIds .") AND aug_group_id = '". $orgGid ."' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹이동 > 이전 그룹번호 초기화 : ". $sql);
			//$this->db->query($sql);
		}
		if($newGid == "0"){ //전체고객목록으로 이동의 경우
			$sql = "delete from cb_ab_". $mem_userid ."_group where aug_ab_id IN (". $chkIds .") AND aug_group_id = '". $orgGid ."' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹이동 > 전체고객목록 : ". $sql);
			$this->db->query($sql);
		}else{
			$chkIds_exp = explode(",", $chkIds);
			if(!empty($chkIds_exp)){ //선택 삭제
				for($ii = 0; $ii < count($chkIds_exp); $ii++){
					//기존 그룹정보 삭제
					if($orgGid != "" && $orgGid != "0"){
						$sql = "delete from cb_ab_". $mem_userid ."_group where aug_ab_id = '". $chkIds_exp[$ii] ."' AND aug_group_id = '". $orgGid ."' ";
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹이동 > 기존 그룹정보 삭제 : ". $sql);
						$this->db->query($sql);
					}
					//그룹정보 확인
					$sql = "SELECT COUNT(*) AS cnt from cb_ab_". $mem_userid ."_group where aug_ab_id = '". $chkIds_exp[$ii] ."' AND aug_group_id = '". $newGid ."' ";
					$cnt = $this->db->query($sql)->row()->cnt;
					if($cnt == 0){ //신규
						$sql = "
						insert into cb_ab_". $mem_userid ."_group (
							aug_ab_id, aug_group_id
						) values (
							'". $chkIds_exp[$ii] ."', '". $newGid ."'
						) ";
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹이동 > 선택이동 (신규) : ". $sql);
						$this->db->query($sql);
					}
				}
			}
		}
		header('Content-Type: application/json');
		echo '{"status": "success"}';
    }

    //고객 목록 > 그룹해제
	public function del_group(){
        $ab_id = $this->input->post('ab_id');
        // $gname = $this->input->post('gname');
        $gid = $this->input->post('gid');
        // $gnm = $this->input->post('gnm');
        $mem_userid = $this->member->item('mem_userid');
        // $gnametext = "";
        // $exp1 = explode("@@", $gname);
        // for($ii = 0; $ii < count($exp1); $ii++){
        //     $plustext = "@@";
        //     if($ii==0||$ii==count($exp1)-1){
        //         $plustext = "";
        //     }
        //     if($exp1[$ii]!=$gnm."##".$gid){
        //         $gnametext .= $exp1[$ii].$plustext;
        //     }
        // }

        // $orgGid = $this->input->post('orgGid'); //이전 그룹번호

		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orgGid : ". $orgGid .", newGid : ". $newGid .", chkIds : ". $chkIds);

		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", mem_userid : ". $mem_userid);

        if($gid != ""){
            $sql = "delete from cb_ab_". $mem_userid ."_group where aug_ab_id = '". $ab_id ."' AND aug_group_id = '". $gid ."'";
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 그룹이동 > 기존 그룹정보 삭제 : ". $sql);
            $this->db->query($sql);
            // if($ab_id != ""){
            //     $sql = "UPDATE cb_customer_group SET cg_sort = cg_sort+100 WHERE cg_mem_id = '". $mem_id ."' ";
            //     $this->db->query($sql);
            // }
        }


		header('Content-Type: application/json');
		echo '{"status": "success"}';
    }

}
?>
