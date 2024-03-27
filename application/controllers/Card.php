<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Cart', 'Post', 'Post_link', 'Post_file', 'Post_extra_vars', 'Post_meta', 'Biz');

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
		$this->load->library(array('querystring', 'funn', 'alimtalk', 'accesslevel', 'email', 'notelib', 'point', 'imagelib', 'image_api'));
	}

	public function main(){
		/**
		* 레이아웃을 정의합니다
		*/

        $cnt = $this->db
            ->select("count(*) AS cnt")
            ->from("cb_wt_card_sub")
            ->where("mem_id", $this->member->item("mem_id"))
            ->get()->row()->cnt;

        if ($cnt > 0 ){
            echo "<script type='text/javascript'>alert('이미 신청하셨습니다.');document.location.href='/home';</script>";
            return;
        }
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout_home',
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

	public function save(){
        function up_img($t, $files, $mem_id){
            $rtn = '';
            if (isset($files) && isset($files['name'])) { //이미지 업로드
    			$t->load->library('upload');
    			$upload_path = config_item('uploads_dir') .'/sub/';
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
    			$uploadconfig['allowed_types'] = 'gif|jpg|png';
    			$uploadconfig['max_size'] = 1024 * 1024;
    			$uploadconfig['encrypt_name'] = true;
    			$t->upload->initialize($uploadconfig);
                $t->load->library('image_api');
    			$_FILES['userfile']['name'] = $files['name'];
    			$_FILES['userfile']['type'] = $files['type'];
    			$_FILES['userfile']['tmp_name'] = $files['tmp_name'];
    			$_FILES['userfile']['error'] = $files['error'];
    			$_FILES['userfile']['size'] = $files['size'];
    			if ($t->upload->do_upload()) {
    				$t->load->library('image_lib');
    				$t->image_lib->clear();
    				$imgconfig = array();
    				$filedata = $t->upload->data();
    				$imgconfig['image_library'] = 'gd2';
    				$imgconfig['source_image']	= $filedata['full_path'];
    				//$imgconfig['create_thumb'] = TRUE;
    				$imgconfig['maintain_ratio'] = TRUE;
    				$imgconfig['new_image'] = $filedata['full_path'];
    				$t->image_lib->initialize($imgconfig);
    				if($t->image_lib->resize()) {
    					$rtn = str_replace($_SERVER["DOCUMENT_ROOT"],'',$imgconfig['new_image']); //이미지경로
                        $str_path = str_replace("/var/www/igenie", "", $filedata['full_path']);
                        $t->image_api->api_image($str_path);
    				} else {
    					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Thumb 이미지 저장 실패 : ". $this->upload->display_errors());
    				}
    			} else {
    				$file_error = $t->upload->display_errors();
    				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload 실패 : ".$this->upload->display_errors().'( '. $upload_path.' )');
    			}
    			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload OK");
    		}
            return $rtn;
        }

		$data = array();
        $mem_id = $this->member->item("mem_id");
        $buis_img = up_img($this, $_FILES['img_file1'], $mem_id);
        $bank_img = up_img($this, $_FILES['img_file2'], $mem_id);
        $sub_img = up_img($this, $_FILES['img_file3'], $mem_id);
        $this->db
            ->set('mem_id', $mem_id)
            ->set('name', $this->input->post('user_name'))
            ->set('tel', $this->input->post('tel'))
            ->set('email', $this->input->post('email'))
            ->set('company', $this->input->post('company_name'))
            ->set('buis_img', $buis_img)
            ->set('bank_img', $bank_img)
            ->set('sub_img', $sub_img)
            ->set('cus_memo', $this->input->post('content'))
            ->insert('cb_wt_card_sub');

		if ($this->db->error()['code'] > 0) {
			echo "<script type='text/javascript'>alert('저장 오류입니다.');history.back();</script>";
		} else {
			echo "<script type='text/javascript'>alert('신청되었습니다.');document.location.href='/home';</script>";
		}
	}

    // 카드 신청 목록
	public function lists(){
		//log_message("error", "들옴 ?");
		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['perpage'] = 20;
		//$view['param']['idx'] = $this->input->get("idx"); //소속 ID
		if(!empty($this->input->get("search_type"))){
			if($this->input->get("search_type")=="1"){ //업체명
				$view['search_type'] = "company";
			}else if($this->input->get("search_type")=="2"){ //신청자 이름
				$view['search_type'] = "name";
			}
		}else{
			$view['search_type'] = ""; //검색조건
		}
		$view['param']['search_for'] = $this->input->get("search_for"); //검색내용
		if(!empty($this->input->get("search_for"))){
			$view["search_for"] = $this->input->get("search_for");
		}
		$view['param']['page'] = (!empty($this->input->get("page")))? $this->input->get("page"): 1;

		$where = " where 1=1 ";
		if(!empty($this->input->get("reg_date"))){
			$view["reg_date"] = $this->input->get("reg_date");
			$where .= "and create_datetime between '".$view['reg_date']." 00:00:00' and '".$view['reg_date']." 23:59:59'  ";
		}
		$view['state'] = $this->input->get("state");
		if(!empty($view['state']) && $view['state']!="ALL") {
			$where .= "and status = '".$view['state']."' ";
		}
		if($view['param']['search_for'] && $view['search_type']){
			$where .= "and ".$view['search_type']." like '%".$view['param']['search_for']."%' ";
		}
		//print_r($view['param']);
		//echo "where : ". $where ."<br>";

		$sql = "SELECT * FROM cb_wt_card_sub ".$where."ORDER BY create_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['row'] = $this->db->query($sql)->result();


		$sql = "SELECT count(*) as cnt FROM cb_wt_card_sub ".$where;
		//log_message("ERROR", "sql : ".$sql);
		//echo $sql; exit;
		$view['total_rows'] = $this->db->query($sql)->row()->cnt; //총 count row

		$view['view']['canonical'] = site_url();
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		$this->load->library('pagination'); //페이징 라이브러리
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();


		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
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

    public function set_status(){
        $this->db
            ->set('status', $this->input->post('status'))
            ->where('mem_id', $this->input->post('mem_id'))
            ->update("cb_wt_card_sub");
    }

    public function set_memo(){
        $this->db
            ->set('memo', $this->input->post('memo'))
            ->where('mem_id', $this->input->post('mem_id'))
            ->update("cb_wt_card_sub");
    }

    // 정산메인
	public function settlement(){
        $mem_id = $this->member->item('mem_id');
        if($mem_id != '2'){
            alert('접금불가');
            redirect('/home');
            return;
        }

        $view = array();
        $view['yeardate'] = $this->input->get('yeardate');
        $view['monthdate'] = $this->input->get('monthdate');
        $view['week1'] = $this->input->get('week1');
        $view['week2'] = $this->input->get('week2');
        $view['week3'] = $this->input->get('week3');
        $view['week4'] = $this->input->get('week4');
        $view['week5'] = $this->input->get('week5');

        $where = ' 1 = 1 ';
        $where .= ' AND startYear = "' . $view['yeardate'] . '" ';
        $where .= ' AND startmonth = "' . $view['monthdate'] . '" ';

        $allflag = true;
        $firstflag = true;
        $weekor = ' AND ( ';
        if ($view['week1'] == '1'){
            if ($firstflag){
                $weekor .= ' weeks = 1 ';
                $firstflag = false;
                $allflag = false;
            } else {

            }
        }

        if ($view['week2'] == '1'){
            if ($firstflag){
                $weekor .= ' weeks = 2 ';
                $firstflag = false;
                $allflag = false;
            } else {
                $weekor .= ' OR weeks = 2 ';
            }
        }

        if ($view['week3'] == '1'){
            if ($firstflag){
                $weekor .= ' weeks = 3 ';
                $firstflag = false;
                $allflag = false;
            } else {
                $weekor .= ' OR weeks = 3 ';
            }
        }

        if ($view['week4'] == '1'){
            if ($firstflag){
                $weekor .= ' weeks = 4 ';
                $firstflag = false;
                $allflag = false;
            } else {
                $weekor .= ' OR weeks = 4 ';
            }
        }

        if ($view['week5'] == '1'){
            if ($firstflag){
                $weekor .= ' weeks = 5 ';
                $firstflag = false;
                $allflag = false;
            } else {
                $weekor .= ' OR weeks = 5 ';
            }
        }

        if ($allflag){
            $weekor = '';
        } else {
            $weekor .= ') ';
        }


        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        	    FROM cb_member_register cmr
            	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . $mem_id . " AND cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            SELECT
                a.*
              , b.*
            FROM
                cmem c
            INNER JOIN
                cb_member a ON a.mem_id = c.mem_id AND a.mem_useyn = 'Y'
            INNER JOIN
                cb_wt_nicepay_settle_weekly b ON a.mem_id = b.mem_id
            WHERE
                " . $where . $weekor . "
        ";
        // log_message("error", $sql);
        $view['list'] = $this->db->query($sql)->result();

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'settlement',
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

    public function set_status2(){
        $this->db
            ->set('status', $this->input->post('status'))
            ->where(array(
                'mem_id' => $this->input->post('mem_id')
              , 'startdt' => $this->input->post('startdt')
              , 'mid' => $this->input->post('mid')
              , 'serviceId' => $this->input->post('serviceId')
              , 'subId' => $this->input->post('subId')
            ))
            ->update("cb_wt_nicepay_settle_weekly");
    }

    public function calendar(){
		$view = array();

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'settlecustom',
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

    public function settledetail(){
        $result = array();
        $mem_id = $this->member->item('mem_id');
        $where = array();
        if ($this->input->post('flag') == 'd'){
            $where['a.settlmntDt'] = $this->input->post('dt');
        } else if ($this->input->post('flag') == 'w'){
            $where['a.settlmntDt >='] = explode('/', $this->input->post('dt'))[0];
            $where['a.settlmntDt <='] = explode('/', $this->input->post('dt'))[1];
        }
        // log_message('error', $this->input->post('dt'));
        if($mem_id != '2'){
            $where['a.mem_id'] = $mem_id;
        }
        $list = $this->db
            ->select('
                IFNULL(c.id, d.moid) AS id
              , b.buyername
              , IFNULL(b.create_datetime, d.create_datetime) AS create_datetime
              , SUM(a.trAmt) AS trAmt
              , b.cardname
              , b.cardno
              , ROUND(SUM(a.depositAmt) - ROUND(SUM(a.trAmt) * c.dhn_fee/100, 3), 3) AS amt
              , f.mem_username
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
            ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
            ->join('cb_member as f', 'c.mem_id = f.mem_id', 'left')
            ->where($where)
            ->group_by('id')
            ->order_by('id')
            ->order_by('b.buyername', 'DESC')
            ->order_by('create_datetime')
            ->get()
            ->result();

        $html = '';
        foreach($list as $a){
            if($a->trAmt == "0") continue;
            $html .= '<tr>';
            if($mem_id == '2'){
                $html .= '    <td>' . $a->mem_username . '</td>';
            }
            $html .= '    <td>' . $a->id . '</td>';
            $html .= '    <td>' . $a->cardname . '</td>';
            $html .= '    <td>' . $a->cardno . '</td>';
            $html .= '    <td>' . number_format($a->amt) . '원' . '</td>';
            $html .= '</tr>';
        }

        $result['html'] = $html;


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function settledetail2(){
        $result = array();
        $mem_id = $this->member->item('mem_id');
        $where = array();
        if ($this->input->post('flag') == 'd'){
            $where['a.settlmntDt'] = $this->input->post('dt');
        } else if ($this->input->post('flag') == 'w'){
            $where['a.settlmntDt >='] = explode('/', $this->input->post('dt'))[0];
            $where['a.settlmntDt <='] = explode('/', $this->input->post('dt'))[1];
        }
        // log_message('error', $this->input->post('dt'));
        if($mem_id != '2'){
            $where['a.mem_id'] = $mem_id;
        }

        $where['a.mid'] = config_item('MID');

        $list = $this->db
            ->select('
                IFNULL(b.create_datetime, d.create_datetime) AS create_datetime
              , SUM(a.trAmt) AS trAmt
              , SUM(a.depositAmt) as depositAmt
              , SUM(ROUND(a.trAmt* c.dhn_fee/100))  as dhn_fee
              , ROUND(SUM(a.depositAmt) - SUM(ROUND(a.trAmt* c.dhn_fee/100)), 3) AS amt
              , f.mem_username
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
            ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
            ->join('cb_member as f', 'c.mem_id = f.mem_id', 'left')
            ->where($where)
            // ->group_by('id')
            ->group_by('a.mem_id')
            ->order_by('create_datetime')
            // ->get_compiled_select();
            // log_message('error', $list);
            ->get()
            ->result();

        $html = '';
        foreach($list as $a){
            if($a->trAmt == "0") continue;
            $html .= '<tr>';
            $html .= '    <td>' . $a->mem_username . '</td>';
            $html .= '    <td>' . number_format($a->depositAmt) . '원' . '</td>';
            $html .= '    <td>' . number_format($a->dhn_fee) . '원' . '</td>';
            $html .= '    <td>' . number_format($a->amt) . '원' . '</td>';
            $html .= '</tr>';
        }

        $result['html'] = $html;


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function settledetail3(){
        $result = array();
        $mem_id = $this->member->item('mem_id');
        $where = array();
        $where['a.settlmntDt >='] = explode('/', $this->input->post('dt'))[0];
        $where['a.settlmntDt <='] = explode('/', $this->input->post('dt'))[1];
        // log_message('error', $this->input->post('dt'));
        $where['a.mem_id'] = $this->member->item('mem_id');

        // $where['a.mid'] = config_item('MID');

        $list = $this->db
            ->select('
                a.trAmt
              , a.fee + ROUND(a.trAmt * c.dhn_fee/100) + a.vat AS fee
              , a.trAmt - (a.fee + ROUND(a.trAmt * c.dhn_fee/100) + a.vat) as settleAmt
              , b.cardname
              , b.cardno
              , c.orderno
              , b.create_datetime
              , a.appNo
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
            ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
            ->join('cb_member as f', 'c.mem_id = f.mem_id', 'left')
            ->where($where)
            // ->group_by('id')
            // ->group_by('a.mem_id')
            ->order_by('create_datetime')
            // ->get_compiled_select();
            // log_message('error', $list);
            ->get()
            ->result();

        $html = '';
        foreach($list as $a){
            if($a->trAmt == "0") continue;
            $html .= '<tr>';
            $html .= '    <td>' . $a->orderno . '</td>';
            $html .= '    <td>' . $a->create_datetime . '</td>';
            $html .= '    <td>' . number_format($a->trAmt) . '원' . '</td>';
            $html .= '    <td>' . number_format($a->fee) . '원' . '</td>';
            $html .= '    <td>' . number_format($a->settleAmt) . '원' . '</td>';
            $html .= '    <td>' . $a->cardname . '</td>';
            $html .= '    <td>' . $a->cardno . '</td>';
            $html .= '    <td>' . $a->appNo . '</td>';
            $html .= '</tr>';
        }

        $result['html'] = $html;


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function get_month(){
        $result = array();
        $mem_id = $this->member->item('mem_id');
        $where = array();
        if($mem_id != '2'){
            $where['a.mem_id'] = $mem_id;
        }

        $ym = substr($this->input->post('dt'), 0, 4) . '-' . substr($this->input->post('dt'), 4);
        $sd = date('Ym', strtotime('-1 months', strtotime($ym . '-01 00:00:00')));
        $ed = date('Ym', strtotime('+1 months', strtotime($ym . '-31 23:59:59')));

        $data = $this->db
            ->select("
                settlmntDt
              , (SUM(a.trAmt) - SUM(a.fee) - SUM(a.vat)) AS damt
              , ROUND(SUM(a.trAmt * dhn_fee/100), 3) AS fee
            ")
            ->from('cb_wt_nicepay_settle a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->where($where)
            ->where("a.settlmntDt BETWEEN '".$sd.'01'."' AND '".$ed.'31'."'")
            ->group_by('a.settlmntDt')
            ->order_by('a.settlmntDt', 'ASC')
            // ->get_compiled_select();
            // log_message('error', $data);
            ->get()
            ->result();


        $result["list"] = array();
        if (!empty($data)){
            foreach($data as $a){
                $ymd = substr($a->settlmntDt, 0, 4) . "-" . substr($a->settlmntDt, 4, 2) . "-" . substr($a->settlmntDt, 6, 2);
                array_push($result['list'], array(
                    'vs_id' => $a->settlmntDt
                  , 'vs_title' => number_format(round($a->damt - $a->fee)) . "원"
                  , 'vs_start' => (strtotime("$ymd 00:00:00") . "000")
                  , 'vs_end' => (strtotime("$ymd 23:59:59") . "000")
                ));
            }
        }


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function cdetail(){
		$view = array();

        $view['startDate'] = !empty($this->input->get("startDate")) ? $this->input->get("startDate") : date('Y-m-d', strtotime('-7 days'));
        $view['endDate'] = !empty($this->input->get("endDate")) ? $this->input->get("endDate") : date('Y-m-d');

        $sdt = preg_replace("/[^0-9]*/s", "", $view['startDate']);
        $edt = preg_replace("/[^0-9]*/s", "", $view['endDate']);

        $card_fee = strtotime($a->create_datetime) >= strtotime('2023-09-01 00:00:00') ? 2.75 : 2.75;

        $view['list'] = $this->db
            ->select('
                IFNULL(c.id, d.moid) AS id
              , b.buyername
              , b.goodsname
              , IFNULL(b.create_datetime, d.create_datetime) AS create_datetime
              , a.settlmntDt
              , a.trAmt AS trAmt
              , ROUND(round(a.trAmt * c.dhn_fee/100) + a.fee + a.vat) AS fee
              , a.vat AS vat
              , a.instmntMon AS instmntMon
              , a.ninstFee AS ninstFee
              , b.cardname
              , b.cardno
              , CASE
                    WHEN b.cardcl = \'0\' THEN \'신용\'
                    WHEN b.cardcl = \'1\' THEN \'체크\'
                    WHEN b.cardcl IS NULL THEN \'\'
                END AS cardcl
              , CASE
                    WHEN b.cardtype = \'01\' THEN \'개인\'
                    WHEN b.cardtype = \'02\' THEN \'가능\'
                    WHEN b.cardtype IS NULL THEN \'\'
                    ELSE \'해외\'
                END AS cardtype
              , round(a.trAmt - round(a.trAmt * c.dhn_fee / 100) - a.fee - a.vat, 0) AS payamt
              , a.appNo
            ')
        ->from('cb_wt_nicepay_settle AS a')
        ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
        ->join('cb_orders AS c', 'b.oid = c.id', 'left')
        ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
        ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
        ->where(array(
            'a.mem_id' => $this->member->item('mem_id')
        ))
        ->where("c.creation_date BETWEEN '".$sdt."' AND '".$edt."'")
        ->order_by('c.creation_date', 'DESC')
        ->order_by('id')
        ->order_by('b.buyername', 'DESC')
        ->order_by('create_datetime')
        // ->get_compiled_select();
        // log_message('error', $view['list']);
        ->get()
        ->result();

        $view['total_cnt'] = count($view['list']);

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'settlecustomdetail',
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

    public function cdownload(){

        $sdt = preg_replace("/[^0-9]*/s", "", $this->input->post("startDate"));
        $edt = preg_replace("/[^0-9]*/s", "", $this->input->post("endDate"));

        $list = $this->db
            ->select('
                IFNULL(c.id, d.moid) AS id
              , b.buyername
              , b.goodsname
              , IFNULL(b.create_datetime, d.create_datetime) AS create_datetime
              , a.settlmntDt
              , a.trAmt AS trAmt
              , ROUND(ROUND(a.trAmt * c.dhn_fee/100, 3) + a.fee) AS fee
              , a.vat AS vat
              , a.instmntMon AS instmntMon
              , a.ninstFee AS ninstFee
              , b.cardname
              , b.cardno
              , CASE
                    WHEN b.cardcl = \'0\' THEN \'신용\'
                    WHEN b.cardcl = \'1\' THEN \'체크\'
                    WHEN b.cardcl IS NULL THEN \'\'
                END AS cardcl
              , CASE
                    WHEN b.cardtype = \'01\' THEN \'개인\'
                    WHEN b.cardtype = \'02\' THEN \'가능\'
                    WHEN b.cardtype IS NULL THEN \'\'
                    ELSE \'해외\'
                END AS cardtype
              , ROUND(a.depositAmt - ROUND(a.trAmt * c.dhn_fee/100, 3), 3) AS payamt
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
            ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
            ->where(array(
                'a.mem_id' => $this->member->item('mem_id')
            ))
            ->where("a.settlmntDt BETWEEN '".$sdt."' AND '".$edt."'")
            ->group_by('id')
            ->order_by('id')
            ->order_by('b.buyername', 'DESC')
            ->order_by('create_datetime')
            ->get()
            ->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

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
	    ),	'A1:O1');

	    // $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    // $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "주문번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "구매자명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "상품명");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "결제일");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "정산일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "결제금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "수수료");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "부가가치세");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "할부개월");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "무이자수수료");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "정산금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "카드사");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "카드번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "카드구분");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "카드형태");

	    $cnt = 1;
	    $row = 2;

	    foreach($list as $r) {
            if ($r->trAmt == '0') continue;
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->id);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->buyername);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->goodsname);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->create_datetime);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->settlmntDt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, (!empty($r->trAmt) ? number_format($r->trAmt) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, (!empty($r->fee) ? number_format($r->fee) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, (!empty($r->vat) ? number_format($r->vat) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->instmntMon);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, (!empty($r->ninstFee) ? number_format($r->ninstFee) . '원' : ''));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, (!empty($r->payamt) ? number_format($r->payamt) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->cardname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->cardno);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $r->cardcl);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $r->cardtype);
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function adownload($mem_id){

        $sdt = preg_replace("/[^0-9]*/s", "", $this->input->post("startDate"));
        $edt = preg_replace("/[^0-9]*/s", "", $this->input->post("endDate"));

        $list = $this->db
            ->select('
                IFNULL(c.id, d.moid) AS id
              , b.buyername
              , b.goodsname
              , IFNULL(b.create_datetime, d.create_datetime) AS create_datetime
              , a.settlmntDt
              , SUM(a.trAmt) AS trAmt
              , ROUND(SUM(a.trAmt) * c.dhn_fee/100, 3) + SUM(a.fee) AS fee
              , SUM(a.vat) AS vat
              , a.instmntMon AS instmntMon
              , SUM(a.ninstFee) AS ninstFee
              , b.cardname
              , b.cardno
              , CASE
                    WHEN b.cardcl = \'0\' THEN \'신용\'
                    WHEN b.cardcl = \'1\' THEN \'체크\'
                    WHEN b.cardcl IS NULL THEN \'\'
                END AS cardcl
              , CASE
                    WHEN b.cardtype = \'01\' THEN \'개인\'
                    WHEN b.cardtype = \'02\' THEN \'가능\'
                    WHEN b.cardtype IS NULL THEN \'\'
                    ELSE \'해외\'
                END AS cardtype
              , ROUND(SUM(a.depositAmt) - ROUND(SUM(a.trAmt) * c.dhn_fee/100, 3), 3) AS payamt
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
            ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
            ->where(array(
                'a.mem_id' => $mem_id
            ))
            ->where("a.settlmntDt BETWEEN '".$sdt."' AND '".$edt."'")
            ->group_by('id')
            ->order_by('id')
            ->order_by('b.buyername', 'DESC')
            ->order_by('create_datetime')
            ->get()
            ->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

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
	    ),	'A1:O1');

	    // $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    // $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "주문번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "구매자명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "상품명");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "결제일");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "정산일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "결제금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "수수료");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "부가가치세");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "할부개월");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "무이자수수료");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "정산금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "카드사");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "카드번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "카드구분");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "카드형태");

	    $cnt = 1;
	    $row = 2;

	    foreach($list as $r) {
            if ($r->trAmt == '0') continue;
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->id);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->buyername);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->goodsname);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->create_datetime);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->settlmntDt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, (!empty($r->trAmt) ? number_format($r->trAmt) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, (!empty($r->fee) ? number_format($r->fee) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, (!empty($r->vat) ? number_format($r->vat) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->instmntMon);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, (!empty($r->ninstFee) ? number_format($r->ninstFee) . '원' : ''));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, (!empty($r->payamt) ? number_format($r->payamt) . '원' : ''));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->cardname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->cardno);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $r->cardcl);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $r->cardtype);
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function wdownload(){
        // $mem_id = $this->member->item('mem_id');
        // if($mem_id != '2'){
        //     alert('접금불가');
        //     redirect('/home');
        //     return;
        // }
        //
        // $yeardate = $this->input->post('yeardate');
        // $monthdate = $this->input->post('monthdate');
        //
        // $where = ' 1 = 1 ';
        // $where .= ' AND startYear = "' . $yeardate . '" ';
        // $where .= ' AND startmonth = "' . $monthdate . '" ';
        //
        // $allflag = true;
        // $firstflag = true;
        // $weekor = ' AND ( ';
        // if ($view['week1'] == '1'){
        //     if ($firstflag){
        //         $weekor .= ' weeks = 1 ';
        //         $firstflag = false;
        //         $allflag = false;
        //     } else {
        //
        //     }
        // }
        //
        // if ($view['week2'] == '1'){
        //     if ($firstflag){
        //         $weekor .= ' weeks = 2 ';
        //         $firstflag = false;
        //         $allflag = false;
        //     } else {
        //         $weekor .= ' OR weeks = 2 ';
        //     }
        // }
        //
        // if ($view['week3'] == '1'){
        //     if ($firstflag){
        //         $weekor .= ' weeks = 3 ';
        //         $firstflag = false;
        //         $allflag = false;
        //     } else {
        //         $weekor .= ' OR weeks = 3 ';
        //     }
        // }
        //
        // if ($view['week4'] == '1'){
        //     if ($firstflag){
        //         $weekor .= ' weeks = 4 ';
        //         $firstflag = false;
        //         $allflag = false;
        //     } else {
        //         $weekor .= ' OR weeks = 4 ';
        //     }
        // }
        //
        // if ($view['week5'] == '1'){
        //     if ($firstflag){
        //         $weekor .= ' weeks = 5 ';
        //         $firstflag = false;
        //         $allflag = false;
        //     } else {
        //         $weekor .= ' OR weeks = 5 ';
        //     }
        // }
        //
        // if ($allflag){
        //     $weekor = '';
        // } else {
        //     $weekor .= ') ';
        // }
        //
        //
        // $sql = "
        //     WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
        //     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        // 	    FROM cb_member_register cmr
        //     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //         WHERE cm.mem_id = " . $mem_id . " AND cmr.mem_id <> cm.mem_id
        //         UNION ALL
        //         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //         FROM cb_member_register cmr
        //         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //         JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        //     )
        //     SELECT
        //         a.*
        //       , b.*
        //     FROM
        //         cmem c
        //     INNER JOIN
        //         cb_member a ON a.mem_id = c.mem_id AND a.mem_useyn = 'Y'
        //     INNER JOIN
        //         cb_wt_nicepay_settle_weekly b ON a.mem_id = b.mem_id
        //     WHERE
        //         " . $where . $weekor . "
        // ";
        // // log_message("error", $sql);
        // $list = $this->db->query($sql)->result();

        $where = array();
        $where['a.settlmntDt >='] = explode('/', $this->input->post('dt'))[0];
        $where['a.settlmntDt <='] = explode('/', $this->input->post('dt'))[1];

        $where['a.mid'] = config_item('MID');

        $list = $this->db
            ->select('
                ROUND(SUM(a.depositAmt) - ROUND(SUM(a.trAmt) * c.dhn_fee/100, 3)) AS amt
              , f.mem_username
              , f.mem_shop_pay_inicis_account
              , f.mem_shop_pay_inicis_bank
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->join('cb_orders AS c', 'b.oid = c.id', 'left')
            ->join('cb_wt_nicepay_cancel AS d', 'a.tid = d.tid', 'left')
            ->join('cb_wt_nicepay_result AS e', 'd.res_id = e.id', 'left')
            ->join('cb_member as f', 'c.mem_id = f.mem_id', 'left')
            ->where($where)
            // ->group_by('id')
            ->group_by('a.mem_id')
            // ->get_compiled_select();
            // log_message('error', $list);
            ->get()
            ->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

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
	    ),	'A1:E1');

	    // $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    // $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "은행");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상대계좌");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "입금금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "업체명");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "입금자명");

	    $cnt = 1;
	    $row = 2;

	    foreach($list as $r) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, config_item('account_bank')[$r->mem_shop_pay_inicis_bank]);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->mem_shop_pay_inicis_account);
            $this->excel->getActiveSheet()->setCellValueExplicit('B' . $row, $r->mem_shop_pay_inicis_account, PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->amt);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->mem_username);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '(주)대형네트웍스');
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="week_settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function cardlist(){

        $view['start_date'] = !empty($this->input->get('start_date')) ? $this->input->get('start_date') : date('Y-m-d', strtotime('-1 months'));
        $view['end_date'] = !empty($this->input->get('end_date')) ? $this->input->get('end_date') : date('Y-m-d');
        $view['perpage'] = !empty($this->input->get("per_page")) ? $this->input->get("per_page") : 10; //목록수
		$view['param']['page'] = !empty($this->input->get("page")) ? $this->input->get("page") : 1; //현재 페이지
        $view['sc'] = !empty($this->input->get('sc')) ? $this->input->get('sc') : 1;
        $view['sv'] = !empty($this->input->get('sv')) ? $this->input->get('sv') : '';
        $view['userid'] = !empty($this->input->get('userid')) ? $this->input->get('userid') : 'ALL';
        $view['nameid'] = !empty($this->input->get('nameid')) ? $this->input->get('nameid') : 'ALL';

        $view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
        $view['username'] = $this->db
            ->select('
                mem_id
              , mem_username
            ')
            ->from('cb_member')
            ->where('mem_shop_pay_inicis_flag', 'Y')
            ->get()->result();

        $where = '';
        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where .= ' and e.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }
        if($view['nameid'] != 'ALL'){
            $where .= ' and a.mem_id = ' . $view['nameid'] . ' ';
        }

        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . ($view['userid'] == 'ALL' ? $this->member->item('mem_id') : $view['userid']) . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
                count(*) as cnt
            from
                cb_wt_nicepay_settle a
            left join
                cb_wt_nicepay_result b on a.tid = b.tid
            left join
                cb_wt_nicepay_cancel c on a.tid = c.tid
            left join
                cb_orders d on b.oid = d.id
            left join
                cb_member e on d.mem_id = e.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                d.creation_date between '" . $view['start_date'] . " 00:00:00' and '" . $view['end_date'] . " 23:59:59'
                and
                f.mem_id is not null
                $where
        ";
        // log_message('error', $sql);
        $rs = $this->db->query($sql)->result();
        $view['total_rows'] = $rs[0]->cnt + $rs[1]->cnt;

        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . ($view['userid'] == 'ALL' ? $this->member->item('mem_id') : $view['userid']) . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
            	if(b.id is not null, '1', '2') as pc
              , ifnull(d.id, c.moid) as id
              , ifnull(d.orderno, c.moid) as orderno
              , e.mem_username
              , d.receiver
              , ifnull(b.create_datetime, c.create_datetime) as create_datetime
              , a.settlmntDt
              , a.trAmt
              , a.depositAmt
              , a.fee
              , a.vat
              , a.instmntMon
              , a.ninstFee
              , b.cardname
              , b.cardno
              , CASE
                    WHEN b.cardcl = '0' THEN '신용'
                    WHEN b.cardcl = '1' THEN '체크'
                    WHEN b.cardcl IS NULL THEN ''
                END AS cardcl
              , CASE
                    WHEN b.cardtype = '01' THEN '개인'
                    WHEN b.cardtype = '02' THEN '가능'
                    WHEN b.cardtype IS NULL THEN ''
                    ELSE '해외'
                END AS cardtype
              , d.dhn_fee
              , a.cal_flag
              , a.tid
              , (
                select
                    mem_username
                from
                    cb_member_register y
                left join
                    cb_member z on y.mrg_recommend_mem_id = z.mem_id
                where
                    y.mem_id = a.mem_id
              ) as rec_mem_username
              , d.creation_date
              , b.id as rid
            from
            	cb_wt_nicepay_settle a
            left join
            	cb_wt_nicepay_result b on a.tid = b.tid
            left join
            	cb_wt_nicepay_cancel c on a.tid = c.tid
            left join
            	cb_orders d on (b.oid = d.id) or (c.moid = d.id)
            left join
                cb_member e on d.mem_id = e.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                d.creation_date between '" . $view['start_date'] . " 00:00:00' and '" . $view['end_date'] . " 23:59:59'
                and
                f.mem_id is not null
                $where
            order by
            	creation_date DESC, id, create_datetime
        ";
        // log_message('error', $sql);
        $view['list'] = $this->db->query($sql)->result();

        $this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'cardlist',
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

    public function cardsublist(){

        $view['perpage'] = !empty($this->input->get("per_page")) ? $this->input->get("per_page") : 20; //목록수
		$view['param']['page'] = !empty($this->input->get("page")) ? $this->input->get("page") : 1; //현재 페이지
        $view['sc'] = !empty($this->input->get('sc')) ? $this->input->get('sc') : 1;
        $view['sv'] = !empty($this->input->get('sv')) ? $this->input->get('sv') : '';
        $view['type'] = !empty($this->input->get('type')) ? $this->input->get('type') : 3;

        $where = array();
        if ($view['type'] == '2'){
            $where['mem_shop_pay_offline_flag'] = 'Y';
        } else if ($view['type'] == '3'){
            $where['mem_shop_pay_inicis_flag'] = 'Y';
        } else if ($view['type'] == '4'){
            $where['mem_is_pay'] = 'Y';
        } else if ($view['type'] == '5'){
            $where['mem_is_account'] = 'Y';
        }

        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where['mem_username like'] = '%' . $view['sv'] . '%';
            }
        }

        $view['total_rows'] = $this->db
            ->select('
                count(*) as cnt
            ')
            ->from('cb_member')
            ->where($where)
            ->where_not_in('mem_id', array(2,3))
            ->get()->row()->cnt;


        $view['list'] = $this->db
            ->select('
                mem_username
              , mem_shop_pay_offline_flag
              , mem_shop_pay_inicis_flag
              , mem_is_pay
              , mem_is_account
              , mem_id
            ')
            ->from('cb_member')
            ->where($where)
            ->where_not_in('mem_id', array(2,3))
            ->order_by('mem_username')
            ->limit($view['perpage'], $view['perpage'] * ($view['param']['page'] - 1))
            ->get()->result();

        $this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'cardsublist',
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

    public function dcalendar(){
		$view = array();

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'dcalendar',
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

    public function wcalendar(){
		$view = array();

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'wcalendar',
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

    public function get_month2(){
        $result = array();
        $mem_id = $this->member->item('mem_id');
        $where = array();
        if($mem_id != '2'){
            $where['mem_id'] = $mem_id;
        }

        $ym = substr($this->input->post('dt'), 0, 4) . '-' . substr($this->input->post('dt'), 4);
        $sd = date('Y-m-d H:i:s', strtotime('-1 months', strtotime($ym . '-01 00:00:00')));
        $ed = date('Y-m-d H:i:s', strtotime('+1 months', strtotime($ym . '-31 23:59:59')));

        $data = $this->db
            ->select("
                *
              , sum(round(depositAmt)) as g_depositAmt
              , sum(round(dhn_fee)) as g_dhn_fee
            ")
            ->from('cb_wt_nicepay_settle_weekly')
            ->where(array(
                'create_datetime >=' => $sd
              , 'create_datetime <=' => $ed
            ))
            ->group_by('startdt')
            ->order_by('create_datetime', 'ASC')
            // ->get_compiled_select();
            // log_message('error', $data);
            ->get()
            ->result();


        $result["list"] = array();
        if (!empty($data)){
            foreach($data as $a){
                $ymd = date('Y-m-d', strtotime($a->create_datetime . '-1 days'));
                $ymd2 = date('Y-m-d', strtotime($a->create_datetime . '+6 days'));
                array_push($result['list'], array(
                    'vs_id' => $a->startdt . '/' . $a->enddt
                  , 'vs_title' => number_format($a->g_depositAmt - $a->g_dhn_fee) . "원"
                  , 'vs_start' => (strtotime("$ymd 00:00:00") . "000")
                  , 'vs_end' => (strtotime("$ymd 23:59:59") . "000")
                  , 'vs_start2' => (strtotime("$ymd2 00:00:00") . "000")
                  , 'vs_end2' => (strtotime("$ymd2 23:59:59") . "000")
                ));
            }
        }


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function get_month3(){
        $result = array();
        $mem_id = $this->member->item('mem_id');
        $where = array();
        if($mem_id != '2'){
            $where['mem_id'] = $mem_id;
        }

        $ym = substr($this->input->post('dt'), 0, 4) . '-' . substr($this->input->post('dt'), 4);
        $sd = date('Y-m-d H:i:s', strtotime('-1 months', strtotime($ym . '-01 00:00:00')));
        $ed = date('Y-m-d H:i:s', strtotime('+1 months', strtotime($ym . '-31 23:59:59')));

        $data = $this->db
        ->select("
            *
          , sum(round(depositAmt)) as g_depositAmt
          , sum(round(dhn_fee)) as g_dhn_fee
        ")
        ->from('cb_wt_nicepay_settle_weekly')
        ->where(array(
            'create_datetime >=' => $sd
          , 'create_datetime <=' => $ed
          , 'mem_id' => $this->member->item('mem_id')
        ))
        ->group_by('startdt')
        ->order_by('create_datetime', 'ASC')
        // ->get_compiled_select();
        // log_message('error', $data);
        ->get()
        ->result();


        $result["list"] = array();
        if (!empty($data)){
            foreach($data as $a){
                $ymd = date('Y-m-d', strtotime($a->create_datetime . '+6 days'));
                array_push($result['list'], array(
                    'vs_id' => $a->startdt . '/' . $a->enddt
                  , 'vs_title' => number_format($a->g_depositAmt - $a->g_dhn_fee) . "원"
                  , 'vs_start' => (strtotime("$ymd 00:00:00") . "000")
                  , 'vs_end' => (strtotime("$ymd 23:59:59") . "000")
                ));
            }
        }


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function cldownload(){
        $mem_id = $this->member->item('mem_id');
        if($mem_id != '2'){
            alert('접금불가');
            redirect('/home');
            return;
        }

        function cellColor($excel, $cells, $color){

            $excel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                     'rgb' => $color
                )
            ));
        }

        $sd = $this->input->post('start_date');
        $ed = $this->input->post('end_date');
        $view['sc'] = !empty($this->input->post('sc')) ? $this->input->post('sc') : 1;
        $view['sv'] = !empty($this->input->post('sv')) ? $this->input->post('sv') : '';
        $view['userid'] = !empty($this->input->post('userid')) ? $this->input->post('userid') : 'ALL';
        $view['nameid'] = !empty($this->input->post('nameid')) ? $this->input->post('nameid') : 'ALL';
        $view['arr_rid'] = $this->input->post('arr_rid');
        $rid = implode(',', $view['arr_rid']);

        $where = '';

        if($view['nameid'] != 'ALL'){
            $where .= ' and a.mem_id = ' . $view['nameid'] . ' ';
        }

        $where .=' and b.id in (' . $rid . ')';

        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where2 .= $where . ' and d.mem_username like \'%' . $view['sv'] . '%\' ';
                $where .= ' and e.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }


        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . ($view['userid'] == 'ALL' ? $this->member->item('mem_id') : $view['userid']) . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
            	if(b.id is not null, '1', '2') as pc
              , ifnull(d.id, c.moid) as id
              , ifnull(d.orderno, c.moid) as orderno
              , e.mem_username
              , d.receiver
              , ifnull(b.create_datetime, c.create_datetime) as create_datetime
              , a.settlmntDt
              , a.trAmt
              , a.depositAmt
              , a.fee
              , a.vat
              , a.instmntMon
              , a.ninstFee
              , b.cardname
              , b.cardno
              , CASE
                    WHEN b.cardcl = '0' THEN '신용'
                    WHEN b.cardcl = '1' THEN '체크'
                    WHEN b.cardcl IS NULL THEN ''
                END AS cardcl
              , CASE
                    WHEN b.cardtype = '01' THEN '개인'
                    WHEN b.cardtype = '02' THEN '가능'
                    WHEN b.cardtype IS NULL THEN ''
                    ELSE '해외'
                END AS cardtype
              , d.dhn_fee
              , a.cal_flag
              , a.tid
              , (
                select
                    mem_username
                from
                    cb_member_register y
                left join
                    cb_member z on y.mrg_recommend_mem_id = z.mem_id
                where
                    y.mem_id = a.mem_id
              ) as rec_mem_username
              , d.creation_date
            from
            	cb_wt_nicepay_settle a
            left join
            	cb_wt_nicepay_result b on a.tid = b.tid
            left join
            	cb_wt_nicepay_cancel c on a.tid = c.tid
            left join
            	cb_orders d on (b.oid = d.id) or (c.moid = d.id)
            left join
                cb_member e on d.mem_id = e.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                d.creation_date between '" . $sd . " 00:00:00' and '" . $ed . " 23:59:59'
                and
                f.mem_id is not null
                $where
            order by
            	creation_date DESC, id, create_datetime
        ";

        $list = $this->db->query($sql)->result();

        if($this->member->item('mem_level') >= 150){
            $al = 'O';
        }else if ($this->member->item('mem_level') >= 50){
            $al = 'N';
        }

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

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
	    ),	'A1:' . $al . '1');


	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        if($this->member->item('mem_level') >= 150){
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        }else if ($this->member->item('mem_level') >= 50){
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        }

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "주문번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "구매자명");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "결제 및 부분취소");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "정산일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "결제금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "수수료");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "부가가치세");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "정산받은금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "업체정산금액");
        if($this->member->item('mem_level') >= 150){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "관리자정산");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "수익금액");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "카드사");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "카드번호");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "카드형태");
        }else if ($this->member->item('mem_level') >= 50){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "수익금액");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "카드사");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "카드번호");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "카드형태");
        }

	    $cnt = 1;
	    $row = 2;

        if (!empty($list)){
            $cnt2 = 1;
            $flag2 = false;
            $trAmt = 0;
            $fee = 0;
            $vat = 0;
            $depositAmt = 0;
            $a_trAmt = 0;
            $a_fee = 0;
            $a_vat = 0;
            $a_depositAmt = 0;
            $a_c_depositAmt = 0;
            $a_s_depositAmt = 0;
            $a_rev = 0;
            $a_ex_feevat = 0;
            foreach($list as $key => $a){
                $card_fee = strtotime($a->create_datetime) >= strtotime('2023-09-01 00:00:00') ? 2.75 : 2.64;
                $a_trAmt += $a->trAmt;
                $a_fee += $a->fee;
                $a_vat += $a->vat;
                if ($a->pc != '3'){
                    $a_depositAmt += $a->depositAmt;
                    $a_c_depositAmt += $a->depositAmt - round($a->trAmt / 100 * $a->dhn_fee);
                } else {
                    // $a_ex_feevat += round($a->trAmt / 100 * $card_fee);
                    // $a_depositAmt += $a->trAmt - ($a->trAmt / 100 * $card_fee);
                    // $a_c_depositAmt += round(($a->trAmt - ($a->trAmt / 100 * $card_fee)) - ($a->trAmt / 100 * ($a->dhn_fee)));
                }
                $a_s_depositAmt += round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100);
                if ($a->pc != '3'){
                    $a_rev += round($a->trAmt / 100 * $a->dhn_fee);
                } else {
                    // $a_rev += round(($a->trAmt - ($a->trAmt / 100 * $card_fee)) - (($a->trAmt - ($a->trAmt / 100 * $card_fee)) - ($a->trAmt / 100 * ($a->dhn_fee))));
                }

                if (!empty($a->cardname)){
                    $trAmt = $a->trAmt;
                    $fee = $a->fee;
                    $vat = $a->vat;
                    $depositAmt = $a->depositAmt;
                }

                $flag = false;
                $flag3 = false;
                if ($list[$key-1]->id == $list[$key]->id){
                    $flag = true;
                    $trAmt += $a->trAmt;
                    $fee += $a->fee;
                    $vat += $a->vat;
                    $depositAmt += $a->depositAmt;
                } else if ($list[$key+1]->id == $list[$key]->id){
                    $flag = true;
                    $cnt2++;
                }
                if ($list[$key+1]->id != $list[$key]->id){
                    $flag2 = true;
                }
                if ($list[$key-1]->id != $list[$key]->id){
                    $flag3 = true;
                }
                if ($flag) cellColor($this->excel, 'A'.$row.':N'.$row, 'FFCCCC');
                $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$al.$row)->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ),
                ));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $a->id);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $flag3 ? $a->mem_username : '');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $flag3 ? $a->receiver : '');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $a->create_datetime);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $a->settlmntDt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, !empty($a->trAmt) ? round($a->trAmt) : '');
                if($a->pc != '3'){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, !empty($a->fee) ? round($a->fee) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, !empty($a->vat) ? round($a->vat) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, !empty($a->depositAmt) ? round($a->depositAmt) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $flag ? '' : $a->depositAmt - round($a->trAmt * $a->dhn_fee / 100));
                } else {
                    $this->excel->getActiveSheet()->mergeCells('G' . $row . ':H' . $row);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, round($a->trAmt / 100 * $card_fee));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, !empty($a->depositAmt) ? $a->trAmt - round($a->trAmt / 100 * $card_fee) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $flag ? '' : ($a->trAmt - round($a->trAmt / 100 * $card_fee)) - round($a->trAmt / 100 * ($a->dhn_fee)));
                }
                if($this->member->item('mem_level') >= 150){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $flag ? '' : round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100));
                    if($a->pc != '3'){
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $flag ? '' : round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100));
                    } else {
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $flag ? '' : ($a->trAmt - round($a->trAmt / 100 * $card_fee)) - (($a->trAmt - round($a->trAmt / 100 * $card_fee)) - round($a->trAmt / 100 * ($a->dhn_fee))));
                    }
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a->cardname);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $a->cardno);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $a->cardtype);
                }else if ($this->member->item('mem_level') >= 50){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $flag ? '' : $a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a->cardname);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a->cardno);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $a->cardtype);
                }
                $row++;
                $cnt++;
                if ($cnt2 > 1 && $flag2){
                    $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$al.$row)->applyFromArray(array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                            ),
                        ),
                    ));
                    cellColor($this->excel, 'A'.$row.':N'.$row, 'FFCC99');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $a->id);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $trAmt);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $fee);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $trAmt > 0 ? $vat : '0');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $trAmt > 0 ? $depositAmt : '0');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $trAmt > 0 ? $depositAmt - round($trAmt * $a->dhn_fee) / 100 : '0');
                    if($this->member->item('mem_level') >= 150){
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $trAmt > 0 ? round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) : '0');
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $trAmt > 0 ? round($trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100) : '0');
                    }else if ($this->member->item('mem_level') >= 50){
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $trAmt > 0 ? round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) : '0');
                    }
                    $cnt2 = 1;
                    $flag2 = false;
                    $row++;
                    $cnt++;
                }
            }
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
            $this->excel->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray(
            array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            ));
            cellColor($this->excel, 'A'.$row.':N'.$row, 'CCFFFF');
            $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$al.$row)->applyFromArray(array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
            ));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '-합계-');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $a_trAmt);
            $this->excel->getActiveSheet()->mergeCells('G' . $row . ':H' . $row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $a_fee + $a_vat + $a_ex_feevat);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, number_format());
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $a_depositAmt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $a_c_depositAmt);
            if($this->member->item('mem_level') >= 150){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $a_s_depositAmt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a_rev);
            }else if ($this->member->item('mem_level') >= 50){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $a_s_depositAmt);
            }
            $this->excel->getActiveSheet()->duplicateStyleArray(array(
                'numberformat' => array(
                    'code' => '#,##0'
                )
            ), 'F2:L'.$row);
            $this->excel->getActiveSheet()->setSelectedCellByColumnAndRow(0, 1);
        }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function cldownload3(){
        $mem_id = $this->member->item('mem_id');
        if($mem_id != '2'){
            alert('접금불가');
            redirect('/home');
            return;
        }

        function cellColor($excel, $cells, $color){

            $excel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                     'rgb' => $color
                )
            ));
        }

        $sd = $this->input->post('start_date');
        $ed = $this->input->post('end_date');
        $view['sc'] = !empty($this->input->post('sc')) ? $this->input->post('sc') : 1;
        $view['sv'] = !empty($this->input->post('sv')) ? $this->input->post('sv') : '';
        $view['userid'] = !empty($this->input->post('userid')) ? $this->input->post('userid') : 'ALL';
        $view['nameid'] = !empty($this->input->post('nameid')) ? $this->input->post('nameid') : 'ALL';

        $where = '';

        if($view['nameid'] != 'ALL'){
            $where .= ' and a.mem_id = ' . $view['nameid'] . ' ';
        }


        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where2 .= $where . ' and d.mem_username like \'%' . $view['sv'] . '%\' ';
                $where .= ' and e.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }


        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . ($view['userid'] == 'ALL' ? $this->member->item('mem_id') : $view['userid']) . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
            	if(b.id is not null, '1', '2') as pc
              , ifnull(d.id, c.moid) as id
              , ifnull(d.orderno, c.moid) as orderno
              , e.mem_username
              , d.receiver
              , ifnull(b.create_datetime, c.create_datetime) as create_datetime
              , a.settlmntDt
              , a.trAmt
              , a.depositAmt
              , a.fee
              , a.vat
              , a.instmntMon
              , a.ninstFee
              , b.cardname
              , b.cardno
              , CASE
                    WHEN b.cardcl = '0' THEN '신용'
                    WHEN b.cardcl = '1' THEN '체크'
                    WHEN b.cardcl IS NULL THEN ''
                END AS cardcl
              , CASE
                    WHEN b.cardtype = '01' THEN '개인'
                    WHEN b.cardtype = '02' THEN '가능'
                    WHEN b.cardtype IS NULL THEN ''
                    ELSE '해외'
                END AS cardtype
              , d.dhn_fee
              , a.cal_flag
              , a.tid
              , (
                select
                    mem_username
                from
                    cb_member_register y
                left join
                    cb_member z on y.mrg_recommend_mem_id = z.mem_id
                where
                    y.mem_id = a.mem_id
              ) as rec_mem_username
              , d.creation_date
            from
            	cb_wt_nicepay_settle a
            left join
            	cb_wt_nicepay_result b on a.tid = b.tid
            left join
            	cb_wt_nicepay_cancel c on a.tid = c.tid
            left join
            	cb_orders d on (b.oid = d.id) or (c.moid = d.id)
            left join
                cb_member e on d.mem_id = e.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                d.creation_date between '" . $sd . " 00:00:00' and '" . $ed . " 23:59:59'
                and
                f.mem_id is not null
                $where
            order by
            	creation_date DESC, id, create_datetime
        ";

        $list = $this->db->query($sql)->result();

        if($this->member->item('mem_level') >= 150){
            $al = 'O';
        }else if ($this->member->item('mem_level') >= 50){
            $al = 'N';
        }

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

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
	    ),	'A1:' . $al . '1');


	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        if($this->member->item('mem_level') >= 150){
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        }else if ($this->member->item('mem_level') >= 50){
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        }

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "주문번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "구매자명");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "결제 및 부분취소");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "정산일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "결제금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "수수료");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "부가가치세");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "정산받은금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "업체정산금액");
        if($this->member->item('mem_level') >= 150){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "관리자정산");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "수익금액");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "카드사");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "카드번호");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "카드형태");
        }else if ($this->member->item('mem_level') >= 50){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "수익금액");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "카드사");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "카드번호");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "카드형태");
        }

	    $cnt = 1;
	    $row = 2;

        if (!empty($list)){
            $cnt2 = 1;
            $flag2 = false;
            $trAmt = 0;
            $fee = 0;
            $vat = 0;
            $depositAmt = 0;
            $a_trAmt = 0;
            $a_fee = 0;
            $a_vat = 0;
            $a_depositAmt = 0;
            $a_c_depositAmt = 0;
            $a_s_depositAmt = 0;
            $a_rev = 0;
            $a_ex_feevat = 0;
            foreach($list as $key => $a){
                $card_fee = strtotime($a->create_datetime) >= strtotime('2023-09-01 00:00:00') ? 2.75 : 2.64;
                $a_trAmt += $a->trAmt;
                $a_fee += $a->fee;
                $a_vat += $a->vat;
                if ($a->pc != '3'){
                    $a_depositAmt += $a->depositAmt;
                    $a_c_depositAmt += round($a->depositAmt - $a->trAmt * $a->dhn_fee / 100);
                } else {
                    $a_ex_feevat += round($a->trAmt / 100 * $card_fee);
                    $a_depositAmt += $a->trAmt - ($a->trAmt / 100 * $card_fee);
                    $a_c_depositAmt += round(($a->trAmt - ($a->trAmt / 100 * $card_fee)) - ($a->trAmt / 100 * ($a->dhn_fee)));
                }
                $a_s_depositAmt += round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100);
                if ($a->pc != '3'){
                    $a_rev += round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100);
                } else {
                    $a_rev += round(($a->trAmt - ($a->trAmt / 100 * $card_fee)) - (($a->trAmt - ($a->trAmt / 100 * $card_fee)) - ($a->trAmt / 100 * ($a->dhn_fee))));
                }

                if (!empty($a->cardname)){
                    $trAmt = $a->trAmt;
                    $fee = $a->fee;
                    $vat = $a->vat;
                    $depositAmt = $a->depositAmt;
                }

                $flag = false;
                $flag3 = false;
                if ($list[$key-1]->id == $list[$key]->id){
                    $flag = true;
                    $trAmt += $a->trAmt;
                    $fee += $a->fee;
                    $vat += $a->vat;
                    $depositAmt += $a->depositAmt;
                } else if ($list[$key+1]->id == $list[$key]->id){
                    $flag = true;
                    $cnt2++;
                }
                if ($list[$key+1]->id != $list[$key]->id){
                    $flag2 = true;
                }
                if ($list[$key-1]->id != $list[$key]->id){
                    $flag3 = true;
                }
                if ($flag) cellColor($this->excel, 'A'.$row.':N'.$row, 'FFCCCC');
                $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$al.$row)->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ),
                ));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $a->id);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $flag3 ? $a->mem_username : '');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $flag3 ? $a->receiver : '');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $a->create_datetime);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $a->settlmntDt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, !empty($a->trAmt) ? round($a->trAmt) : '');
                if($a->pc != '3'){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, !empty($a->fee) ? round($a->fee) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, !empty($a->vat) ? round($a->vat) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, !empty($a->depositAmt) ? round($a->depositAmt) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $flag ? '' : $a->depositAmt - round($a->trAmt * $a->dhn_fee / 100));
                } else {
                    $this->excel->getActiveSheet()->mergeCells('G' . $row . ':H' . $row);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, round($a->trAmt / 100 * $card_fee));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, !empty($a->depositAmt) ? $a->trAmt - round($a->trAmt / 100 * $card_fee) : '');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $flag ? '' : ($a->trAmt - round($a->trAmt / 100 * $card_fee)) - round($a->trAmt / 100 * ($a->dhn_fee)));
                }
                if($this->member->item('mem_level') >= 150){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $flag ? '' : round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100));
                    if($a->pc != '3'){
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $flag ? '' : round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100));
                    } else {
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $flag ? '' : ($a->trAmt - round($a->trAmt / 100 * $card_fee)) - (($a->trAmt - round($a->trAmt / 100 * $card_fee)) - round($a->trAmt / 100 * ($a->dhn_fee))));
                    }
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a->cardname);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $a->cardno);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $a->cardtype);
                }else if ($this->member->item('mem_level') >= 50){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $flag ? '' : $a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a->cardname);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a->cardno);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $a->cardtype);
                }
                $row++;
                $cnt++;
                if ($cnt2 > 1 && $flag2){
                    $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$al.$row)->applyFromArray(array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                            ),
                        ),
                    ));
                    cellColor($this->excel, 'A'.$row.':N'.$row, 'FFCC99');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $a->id);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $trAmt);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $fee);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $trAmt > 0 ? $vat : '0');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $trAmt > 0 ? $depositAmt : '0');
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $trAmt > 0 ? $depositAmt - round($trAmt * $a->dhn_fee) / 100 : '0');
                    if($this->member->item('mem_level') >= 150){
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $trAmt > 0 ? round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) : '0');
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $trAmt > 0 ? round($trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100) : '0');
                    }else if ($this->member->item('mem_level') >= 50){
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $trAmt > 0 ? round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) : '0');
                    }
                    $cnt2 = 1;
                    $flag2 = false;
                    $row++;
                    $cnt++;
                }
            }
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
            $this->excel->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray(
            array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            ));
            cellColor($this->excel, 'A'.$row.':N'.$row, 'CCFFFF');
            $this->excel->getActiveSheet()->getStyle('A'.$row.':'.$al.$row)->applyFromArray(array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
            ));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '-합계-');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $a_trAmt);
            $this->excel->getActiveSheet()->mergeCells('G' . $row . ':H' . $row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $a_fee + $a_vat + $a_ex_feevat);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, number_format());
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $a_depositAmt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $a_c_depositAmt);
            if($this->member->item('mem_level') >= 150){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $a_s_depositAmt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a_rev);
            }else if ($this->member->item('mem_level') >= 50){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $a_s_depositAmt);
            }
            $this->excel->getActiveSheet()->duplicateStyleArray(array(
                'numberformat' => array(
                    'code' => '#,##0'
                )
            ), 'F2:L'.$row);
            $this->excel->getActiveSheet()->setSelectedCellByColumnAndRow(0, 1);
        }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function cldownload4(){
        $mem_id = $this->member->item('mem_id');
        if($mem_id != '2'){
            alert('접금불가');
            redirect('/home');
            return;
        }

        $sd = $this->input->post('start_date');
        $ed = $this->input->post('end_date');
        $view['sc'] = !empty($this->input->post('sc')) ? $this->input->post('sc') : 1;
        $view['sv'] = !empty($this->input->post('sv')) ? $this->input->post('sv') : '';
        $view['userid'] = !empty($this->input->post('userid')) ? $this->input->post('userid') : 'ALL';
        $view['nameid'] = !empty($this->input->post('nameid')) ? $this->input->post('nameid') : 'ALL';

        $where = '';
        if($view['nameid'] != 'ALL'){
            $where .= ' and a.mem_id = ' . $view['nameid'] . ' ';
        }


        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where2 .= $where . ' and d.mem_username like \'%' . $view['sv'] . '%\' ';
                $where .= ' and e.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }

        $sql = "
        WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            WHERE cm.mem_id = " . ($view['userid'] == 'ALL' ? $this->member->item('mem_id') : $view['userid']) . " and cmr.mem_id <> cm.mem_id
            UNION ALL
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        )
        select
            d.creation_date
          , e.mem_biz_reg_no
          , e.mem_username
          , e.mem_biz_reg_rep_name
          , concat(e.mem_biz_reg_add1, ' ', mem_biz_reg_add2, ' ', mem_biz_reg_add3) as addr
          , e.mem_biz_reg_biz
          , e.mem_biz_reg_sector
          , e.mem_biz_reg_email1
          , ifnull(d.id, c.moid) as id
          , ifnull(b.create_datetime, c.create_datetime) as create_datetime
          , d.dhn_fee
          , a.trAmt
        from
            cb_wt_nicepay_settle a
        left join
            cb_wt_nicepay_result b on a.tid = b.tid
        left join
            cb_wt_nicepay_cancel c on a.tid = c.tid
        left join
            cb_orders d on (b.oid = d.id) or (c.moid = d.id)
        left join
            cb_member e on d.mem_id = e.mem_id
        left join
            cmem f on a.mem_id = f.mem_id
        where
            d.creation_date between '" . $sd . " 00:00:00' and '" . $ed . " 23:59:59'
            and
            f.mem_id is not null
            $where
        order by
            d.creation_date DESC, id, create_datetime
        ";
        $list = $this->db->query($sql)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"
            , "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ"
            , "BA", "BB", "BC", "BD", "BE", "BF", "BG");
        $AcolWidth = array(20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20
            , 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20
            , 20, 20, 20, 20, 20, 20, 20);
        $AcolHeader = array(
            '전자(세금)계산서 종류' . PHP_EOL . '(01:일반, 02:영세율)'
          , '작성일자'
          , '공급자 등록번호' . PHP_EOL . '("-" 없이 입력)'
          , '공급자' . PHP_EOL . ' 종사업장번호'
          , '공급자 상호'
          , '공급자 성명'
          , '공급자 사업장주소'
          , '공급자 업태'
          , '공급자 종목'
          , '공급자 이메일'
          , '공급받는자 등록번호' . PHP_EOL . '("-" 없이 입력)'
          , '공급받는자' . PHP_EOL . '종사업장번호'
          , '공급받는자 상호'
          , '공급받는자 서명'
          , '공급받는자 사업장주소'
          , '공급받는자 업태'
          , '공급받는자 종목'
          , '공급받는자 이메일1'
          , '공급받는자 이메일2'
          , '공급가액'
          , '세액'
          , '비고'
        );
        for($i=1;$i<5;$i++){
            array_push($AcolHeader, '일자' . $i . PHP_EOL . '(2자리, 작성년월 제외)');
            array_push($AcolHeader, '품목' . $i);
            array_push($AcolHeader, '규격' . $i);
            array_push($AcolHeader, '수량' . $i);
            array_push($AcolHeader, '단가' . $i);
            array_push($AcolHeader, '공급가액' . $i);
            array_push($AcolHeader, '세액' . $i);
            array_push($AcolHeader, '품목비고' . $i);
        }
        array_push($AcolHeader, '현금');
        array_push($AcolHeader, '수표');
        array_push($AcolHeader, '어음');
        array_push($AcolHeader, '외상미수금');
        array_push($AcolHeader, '영수(01),' . PHP_EOL . '청구(02)');

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('Sheet1');

        $this->excel->getActiveSheet()->getStyle('A1:'.$Acol[(count($Acol) - 1)].'200')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        foreach($Acol as $key => $a){
            $this->excel->getActiveSheet()->getColumnDimension($a)->setWidth($AcolWidth[$key]);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($key, 6, $AcolHeader[$key]);
            $this->excel->getActiveSheet()->getStyle($a . '6')->getAlignment()->setWrapText(true);
        }

	    // $cnt = 1;
	    $row = 7;

        if (!empty($list)){
            foreach($list as $key => $a){
                $card_fee = strtotime($a->create_datetime) >= strtotime('2023-09-01 00:00:00') ? 2.75 : 2.64;
                $sp += round($a->trAmt / 100 * ($card_fee + $a->dhn_fee)) / 1.1;
                $ta += round($a->trAmt / 100 * ($card_fee + $a->dhn_fee)) / 1.1 * 0.1;
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '01');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Ymd', strtotime($a->creation_date)));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, '3648800974');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, '주식회사대형네트웍스');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, '송종근');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, '경상남도 창원시 의창구 차룡로48번길 54, 302호(팔용동,경남창원산학융합본부 기업연구관)');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, '정보서비스');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, 'finance@dhncorp.co.kr');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $a->mem_biz_reg_no);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a->mem_username);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $a->mem_biz_reg_rep_name);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $a->addr);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $a->mem_biz_reg_biz);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $a->mem_biz_reg_sector);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $a->mem_biz_reg_email1);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, round($sp));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, round($ta));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, date('d', strtotime($a->creation_date)));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, '온라인판매결재수수료');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, round($sp));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, round($ta));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(58, $row, '01');
                $row++;
            }
            $this->excel->getActiveSheet()->setAutoFilter('A6:'.$Acol[(count($Acol) - 1)].($row-1));

            // $this->excel->getActiveSheet()->duplicateStyleArray(array(
            //     'numberformat' => array(
            //         'code' => '#,##0'
            //     )
            // ), 'F2:N'.$row);
            $this->excel->getActiveSheet()->setSelectedCellByColumnAndRow(0, 1);
        }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function cldownload2(){
        $mem_id = $this->member->item('mem_id');
        if($mem_id != '2'){
            alert('접금불가');
            redirect('/home');
            return;
        }

        function cellColor($excel, $cells, $color){

            $excel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                     'rgb' => $color
                )
            ));
        }

        $sd = $this->input->post('start_date');
        $ed = $this->input->post('end_date');
        $view['sc'] = !empty($this->input->post('sc')) ? $this->input->post('sc') : 1;
        $view['sv'] = !empty($this->input->post('sv')) ? $this->input->post('sv') : '';
        $view['userid'] = !empty($this->input->post('userid')) ? $this->input->post('userid') : 'ALL';
        $view['nameid'] = !empty($this->input->post('nameid')) ? $this->input->post('nameid') : 'ALL';
        $view['arr_rid'] = $this->input->post('arr_rid');
        $rid = implode(',', $view['arr_rid']);

        $where = '';

        if($view['nameid'] != 'ALL'){
            $where .= ' and a.mem_id = ' . $view['nameid'] . ' ';
        }

        $where .=' and b.id in (' . $rid . ')';

        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where2 .= $where . ' and d.mem_username like \'%' . $view['sv'] . '%\' ';
                $where .= ' and e.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }


        $sql = "
            select
                a.mem_username
              , sum(a.trAmt) as trAmt
              , sum(a.depositAmt) as depositAmt
              , sum(a.cdepositAmt) as cdepositAmt
              , sum(fee) as fee
            from(
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    WHERE cm.mem_id = 2 and cmr.mem_id <> cm.mem_id
                    UNION ALL
                    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                )
                select
                    a.trAmt
                  , a.depositAmt
                  , a.depositAmt - truncate(a.trAmt * d.dhn_fee / 100 + 0.5, 0) as cdepositAmt
                  , truncate(a.trAmt * d.dhn_fee / 100 + 0.5, 0) as fee
                  , e.mem_id
                  , e.mem_username
                from
                    cb_wt_nicepay_settle a
                left join
                    cb_wt_nicepay_result b on a.tid = b.tid
                left join
                    cb_wt_nicepay_cancel c on a.tid = c.tid
                left join
                    cb_orders d on (b.oid = d.id) or (c.moid = d.id)
                left join
                    cb_member e on d.mem_id = e.mem_id
                left join
                    cmem f on a.mem_id = f.mem_id
                where
                    d.creation_date between '" . $sd . " 00:00:00' and '" . $ed . " 23:59:59'
                    and
                    f.mem_id is not null
                    $where
            ) a
            group by
            	mem_id
            order by
                mem_username desc
        ";

        $list = $this->db->query($sql)->result();
        log_message('error', $sql);

        $Acol = array("A", "B", "C", "D", "E", "F", "G");

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('Sheet1');
        $this->excel->getDefaultStyle()->getFont()->setName('Malgun gothic');
        $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);

        cellColor($this->excel, 'B4:G4', 'BFBFBF');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, "결제사이트");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, "결제금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, "정산받은금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, "업체정산금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, "수익금액");
        $this->excel->getActiveSheet()->duplicateStyleArray(array(
            'font' => array(
                'bold' => true
            )
          , 'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        ), 'B4:G4');

        $this->excel->getActiveSheet()->mergeCells('B2:G2');
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, '전자결제 업체 수수료 정산(' . str_replace('-', '.', $sd) . '~' . str_replace('-', '.', $ed) . ')');
        $this->excel->getActiveSheet()->duplicateStyleArray(array(
            'font' => array(
                'bold' => true
              , 'size' => 18
            )
          , 'alignment' => array(
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
          )
        ), 'B2');

	    $row = 5;

        if (!empty($list)){
            $a_trAmt = 0;
            $a_depositAmt = 0;
            $a_cdepositAmt = 0;
            $a_fee = 0;
            foreach($list as $a){
                $a_trAmt += $a->trAmt;
                $a_depositAmt += $a->depositAmt;
                $a_cdepositAmt += $a->cdepositAmt;
                $a_fee += $a->fee;
                cellColor($this->excel, 'B'.$row.':G'.$row, 'CCC0DA');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '지니');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $a->mem_username);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $a->trAmt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $a->depositAmt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $a->cdepositAmt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $a->fee);
                $row++;
            }
            cellColor($this->excel, 'B'.$row.':G'.$row, 'FFFF00');
            $this->excel->getActiveSheet()->mergeCells('B' . $row . ':C' . $row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '합계');
            $this->excel->getActiveSheet()->duplicateStyleArray(array(
                'font' => array(
                    'bold' => true
                )
              , 'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
              )
            ), 'B'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $a_trAmt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $a_depositAmt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $a_cdepositAmt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $a_fee);

            $this->excel->getActiveSheet()->getStyle('B4:G'.$row)->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
                'alignment' => array(
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            ));

            $this->excel->getActiveSheet()->duplicateStyleArray(array(
                'numberformat' => array(
                    'code' => '#,##0'
                )
            ), 'D4:G'.$row);
            $this->excel->getActiveSheet()->setSelectedCellByColumnAndRow(0, 1);
        }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="settlement_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function set_cal(){
        $result = array();

        $arr_tid = $this->input->post('arr_tid');

        $this->db
            ->set('cal_flag', 'Y')
            ->where_in('tid', $arr_tid)
            ->update('cb_wt_nicepay_settle');

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    public function cardstat(){
		$view = array();

        $view['month'] = !empty($this->input->get('month')) ? $this->input->get('month') : 12;
        // $view['type'] = !empty($this->input->get('type')) ? $this->input->get('type') : 1;
        $arr_dt = array();
        $sql = ' select d.mem_username ';
        for($i=$view['month'];$i>0;$i--){
            $dt = date('Ym', strtotime('-' . ($i - 1) . ' months', strtotime(date('Y-m-') . '01 00:00:01')));
            array_push($arr_dt, $dt);
            $sql .= ', ifnull(sum(case when substring(a.settlmntDt, 1, 6) = \'' . $dt . '\' then round(a.depositAmt) end), 0) as month_' . $dt . ' ';
            $sql .= ', ifnull(sum(case when substring(a.settlmntDt, 1, 6) = \'' . $dt . '\' and a.cal_flag = \'Y\' then round(a.depositAmt - a.trAmt*e.dhn_fee/100) end), 0) as cal_' . $dt . ' ';
            $sql .= ', count(case when substring(a.settlmntDt, 1, 6) = \'' . $dt . '\' and c.id is null and b.cancel_flag <> \'Y\' then a.tid end) as cnt_' . $dt . ' ';
            $sql .= ', count(case when substring(a.settlmntDt, 1, 6) = \'' . $dt . '\' and c.id is null and b.cancel_flag <> \'Y\' and a.cal_flag = \'Y\' then a.tid end) as ccnt_' . $dt . ' ';
        }
        $sql2 .= $sql . '
            from
                cb_wt_nicepay_settle a
            left join
                cb_wt_nicepay_result b on a.tid = b.tid
            left join
                cb_wt_nicepay_cancel c on a.tid = c.tid
            left join
                cb_member d on a.mem_id = d.mem_id
            left join
                cb_orders e on (b.oid = e.id) or (c.moid = e.id)
        ';
        $sql .= '
            from
                cb_wt_nicepay_settle a
            left join
                cb_wt_nicepay_result b on a.tid = b.tid
            left join
                cb_wt_nicepay_cancel c on a.tid = c.tid
            left join
                cb_member d on a.mem_id = d.mem_id
            left join
                cb_orders e on (b.oid = e.id) or (c.moid = e.id)
            group by
                a.mem_id
            order by
                d.mem_username
        ';
        // log_message('error', $sql);
        $view['list'] = $this->db->query($sql)->result();
        $view['all'] = $this->db->query($sql2)->row();

        $view['arr_dt'] = $arr_dt;


		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'cardstat',
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

    public function cardorder(){
		$view = array();

        $view['start_date'] = !empty($this->input->get('start_date')) ? $this->input->get('start_date') : date('Y-m-d', strtotime('-1 month'));
        $view['end_date'] = !empty($this->input->get('end_date')) ? $this->input->get('end_date') : date('Y-m-d');
        $view['perpage'] = !empty($this->input->get("per_page")) ? $this->input->get("per_page") : 20; //목록수
		$view['param']['page'] = !empty($this->input->get("page")) ? $this->input->get("page") : 1; //현재 페이지
        $view['sc'] = !empty($this->input->get('sc')) ? $this->input->get('sc') : 1;
        $view['sv'] = !empty($this->input->get('sv')) ? $this->input->get('sv') : '';

        $where = '';
        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where .= ' and b.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }

        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . $this->member->item('mem_id') . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
                count(*) as cnt
            from
                cb_orders a
            left join
                cb_member b on a.mem_id = b.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                a.creation_date between '" . $view['start_date'] . " 00:00:00' and '" . $view['end_date'] . " 23:59:59 '
                and
                f.mem_id is not null
                and
                a.charge_type in (5,6)
                and
                a.status <> 9
                and
                a.mem_id not in (3, 1580, 737)
                $where
        ";
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;

        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . $this->member->item('mem_id') . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
            	b.mem_username
              , a.orderno as id
              , (select sum(c.amount) from cb_order_details c where a.id = c.order_id and c.cal_yn = 'N') as amt
              , a.status
              , a.creation_date
              , a.mem_id
            from
                cb_orders a
            left join
                cb_member b on a.mem_id = b.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                a.creation_date between '" . $view['start_date'] . " 00:00:00' and '" . $view['end_date'] . " 23:59:59 '
                and
                f.mem_id is not null
                and
                a.charge_type in (5,6)
                and
                a.status <> 9
                and
                a.mem_id not in (3, 1580, 737)
                $where
            order by
            	a.creation_date DESC, a.id desc
            limit
                " . $view['perpage'] * ($view['param']['page'] - 1) . ", " . $view['perpage'] . "
        ";
        $view['list'] = $this->db->query($sql)->result();

        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = " . $this->member->item('mem_id') . " and cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            select
                sum((select sum(c.amount) from cb_order_details c where a.id = c.order_id and c.cal_yn = 'N')) as amt
            from
                cb_orders a
            left join
                cb_member b on a.mem_id = b.mem_id
            left join
                cmem f on a.mem_id = f.mem_id
            where
                a.creation_date between '" . $view['start_date'] . " 00:00:00' and '" . $view['end_date'] . " 23:59:59 '
                and
                f.mem_id is not null
                and
                a.charge_type in (5,6)
                and
                a.status <> 9
                and
                a.status <> 4
                and
                a.mem_id not in (3, 1580, 737)
                $where
        ";
        $view['sum'] = $this->db->query($sql)->row()->amt;

        $this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'card',
			'layout' => 'layout',
			'skin' => 'cardorder',
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

    public function orderexcel(){
        $mem_id = $this->member->item('mem_id');
        if($mem_id != '2'){
            alert('접금불가');
            redirect('/home');
            return;
        }

        $view['start_date'] = !empty($this->input->get('start_date')) ? $this->input->get('start_date') : date('Y-m-d', strtotime('-1 years'));
        $view['end_date'] = !empty($this->input->get('end_date')) ? $this->input->get('end_date') : date('Y-m-d');
        $view['sc'] = !empty($this->input->get('sc')) ? $this->input->get('sc') : 1;
        $view['sv'] = !empty($this->input->get('sv')) ? $this->input->get('sv') : '';

        $where = '';
        if (!empty($view['sv'])){
            if ($view['sc'] == '1'){
                $where .= ' and b.mem_username like \'%' . $view['sv'] . '%\' ';
            }
        }

        $sql = "
            select
            	b.mem_username
              , a.id
              , (select sum(c.amount) from cb_order_details c where a.id = c.order_id and c.cal_yn = 'N') as amt
              , a.status
              , a.creation_date
              , a.mem_id
            from
                cb_orders a
            left join
                cb_member b on a.mem_id = b.mem_id
            where
                a.creation_date between '" . $view['start_date'] . " 00:00:00' and '" . $view['end_date'] . " 23:59:59 '
                and
                a.charge_type in (5,6)
                and
                a.status <> 9
                and
                a.mem_id not in (3, 1580, 737)
                $where
            order by
            	a.id desc
        ";
        $list = $this->db->query($sql)->result();


        $list = $this->db->query($sql)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

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
	    ),	'A1:E1');

	    // $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    // $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "주문번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "결제금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "주문일시");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "진행상태");

	    $cnt = 1;
	    $row = 2;

	    foreach($list as $r) {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->id);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, number_format($r->amt) . '원');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->creation_date);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->funn->user_order_status($r->status));
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="order_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }
}
?>
