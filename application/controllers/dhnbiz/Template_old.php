<?php
class Template extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Biz');

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
		/* 스윗트렉커와 연동하여 템플릿 목록을 정리해야 함 */
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

		$view['total_rows'] = $this->Biz_model->get_template_count($this->member->item('mem_id'), $where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);

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
		//	from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
		//	where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->Biz_model->get_template_list($this->member->item('mem_id'), ($view['param']['page']-1), $view['perpage'], $where, $param);	//$this->db->query($sql, $param)->result();
		$view['profile'] = $this->Biz_model->get_partner_profile();
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
			'path' => 'biz/template',
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

	function sync_template_status()
	{
		//- 정상승인 후에도 반려될 수 있어 전체 상태를 반영하도록 수정 : 2018.03.23
		$tpl_id = $this->input->POST("tpl_id");
		//$req = $this->db->query("select tpl_profile_key, tpl_code from cb_wt_template_dhn where tpl_mem_id=? and tpl_inspect_status='REQ'", array($this->member->item('mem_id')))->result();
		$sql ="select ws.spf_appr_id, cwt.* from cb_wt_template_dhn cwt inner join cb_wt_send_profile ws on cwt.tpl_profile_key = ws.spf_key where ( tpl_mem_id in 
(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> cm.mem_id 
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
                    )
 or tpl_mem_id = 1 )";

		if($tpl_id) {
		    $sql = $sql." and tpl_id = ".$tpl_id;
		} else {
		    $sql = $sql."  and tpl_inspect_status in ('REQ', 'INQ', 'REG')";
        }
	   // log_message("ERROR", "TEMP SQL : ".$sql);
		$req = $this->db->query($sql )->result();
		if($req) {
			$this->load->library('sweettracker');
			foreach($req as $r) {
			    
				$result = $this->sweettracker->template($r->tpl_profile_key, $r->tpl_code, $r->spf_appr_id);
				//log_message("ERROR", "T ".$result->data->comments->status);
				//ob_start();
				//var_dump($result);
				//$msg = ob_get_contents();
				//log_message("ERROR", "T ".$msg);
				if($result->code=="success") {
				    if($result->data->inspectionStatus != $r->tpl_inspect_status || $result->data->status!=$r->tpl_status || empty($r->tpl_inspect_status) || $r->tpl_status) {
				        if($result->data->inspectionStatus!=$r->tpl_inspect_status || empty($r->tpl_inspect_status)) {
				            log_message("ERROR", "TTT : ".$result->code."/".$r->tpl_profile_key."/".$r->tpl_code."/".$result->data->inspectionStatus."/".$r->tpl_inspect_status."/".$result->data->status."/".$r->tpl_status);
                            $ud_sql = "
                                        update cb_wt_template_dhn 
                                           set tpl_inspect_status='".$result->data->inspectionStatus."'
                                             , tpl_status='".$result->data->status."'
                                             , tpl_appr_id='".$r->spf_appr_id."'
                                             , tpl_appr_datetime='".cdate('Y-m-d H:i:s')."' 
                                         where tpl_mem_id='".$r->tpl_mem_id."' 
                                           and tpl_profile_key='".$r->tpl_profile_key."' 
                                           and tpl_code='".$r->tpl_code."'";
				            $this->db->query($ud_sql);
						} else {
							$this->db->query("update cb_wt_template_dhn set tpl_status='".$result->data->status."' where tpl_mem_id=? and tpl_profile_key=? and tpl_code=?", array($this->member->item('mem_id'), $r->tpl_profile_key, $r->tpl_code));
						}
					}
				} else {
				    log_message("ERROR", "Template Status : ".$result->message."/".$r->tpl_profile_key."/".$r->tpl_code);
				}
			}
		}
		if($this->input->post('public_flag') == 'Y') {
		    redirect('/biz/template/public_lists');
		} else {
		    redirect('/biz/template/lists');
		}
	}

	public function btn()
	{
		$tmpl_id = $this->input->post('tmpl_id');
		$view = array();
		$view['rs'] = $this->Biz_model->get_row("cb_wt_template_dhn", "tpl_id=?", array($tmpl_id));
		$this->load->view("biz/template/btn", $view);
	}

	/* 템플릿 검수 요청 : 여러건 검수요청 */
	public function check_inspect()
	{
		$pf_key = json_decode($this->input->post('pf_key'))->pf_key;
		$pf_type = json_decode($this->input->post('pf_type'))->pf_type;
		$tmp_code = json_decode($this->input->post('tmp_code'))->tmp_code;
		$count = $this->input->post('count');
		$ok = 0; $err = 0; $err_msg = "템플릿 검수요청에 실패하였습니다.";

		$this->load->library('sweettracker');

		for($n=0;$n<$count;$n++) {
			/* 스윗트렉커로 전송 */
			$swData = array(
				"senderKey"=>$pf_key[$n]
				,"senderKeyType"=>$pf_type[$n]
				,"templateCode"=>$tmp_code[$n]
			);
			$result = $this->sweettracker->template_request(array($swData));
			if($result[0]->code!="success") {
			    log_message("ERROR", "T REQ : ".$result[0]->message);
				$err_msg = $result[0]->message;
				$err++;
				continue;
			}
			
			$this->db->query("update cb_wt_template_dhn set tpl_inspect_status='REQ', tpl_check_datetime=? where tpl_profile_key=? and tpl_code=? and tpl_use='Y' and tpl_inspect_status='REG'", array(cdate('Y-m-d H:i:s'), $pf_key[$n], $tmp_code[$n]));
			if($this->db->affected_rows() > 0) { $ok++; } else { $err++; }
		}
		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	}

	/* 선택 템플릿 삭제 */
	public function check_delete()
	{
		$pf_key = json_decode($this->input->post('pf_key'))->pf_key;
		$pf_type = json_decode($this->input->post('pf_type'))->pf_type;
		$tmp_code = json_decode($this->input->post('tmp_code'))->tmp_code;
		$count = $this->input->post('count');
		$ok = 0; $err = 0; $err_msg = "템플릿 삭제에 실패하였습니다.";

		$this->load->library('sweettracker');

		for($n=0;$n<$count;$n++) {
			/* 스윗트렉커로 전송 */
			$swData = array(
				"senderKey"=>$pf_key[$n]
				,"senderKeyType"=>$pf_type[$n]
				,"templateCode"=>$tmp_code[$n]
			);
			$result = $this->sweettracker->template_delete(array($swData));
			if($result[0]->code!="success") {
				$err_msg = $result[0]->message;

				// 오류코드가 들어오더라도 템플릿이 반려이면 목록에서 삭제 : 2018.03.23
				$this->db->query("update cb_wt_template_dhn set tpl_use='N' where tpl_profile_key=? and tpl_code=? and tpl_inspect_status='REJ'", array($pf_key[$n], $tmp_code[$n]));

				$err++;
				continue;
			}

			$this->db->query("update cb_wt_template_dhn set tpl_use='N' where tpl_profile_key=? and tpl_code=? and (tpl_inspect_status='REG' OR tpl_inspect_status='REJ')", array($pf_key[$n], $tmp_code[$n]));
			if($this->db->affected_rows() > 0) { $ok++; } else { $err++; }
		}
		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	}

	/* 선택 템플릿 삭제 */
	public function hide_proc()
	{
	    $pf_key = json_decode($this->input->post('pf_key'))->pf_key;
	    $pf_type = json_decode($this->input->post('pf_type'))->pf_type;
	    $tmp_code = json_decode($this->input->post('tmp_code'))->tmp_code;
	    $count = $this->input->post('count');
	    $ok = 0; $err = 0; $err_msg = "템플릿 삭제에 실패하였습니다.";
	    
	    for($n=0;$n<$count;$n++) {
	        $this->db->query("update cb_wt_template_dhn set tpl_use='N' where tpl_profile_key=? and tpl_code=? ", array($pf_key[$n], $tmp_code[$n]));
	        if($this->db->affected_rows() > 0) { $ok++; } else { $err++; }
	    }
	    header('Content-Type: application/json');
	    echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	}
	
	/* 선택 템플릿 삭제 */
	public function check_delete_public()
	{
	    $pf_key = json_decode($this->input->post('pf_key'))->pf_key;
	    $pf_type = json_decode($this->input->post('pf_type'))->pf_type;
	    $tmp_code = json_decode($this->input->post('tmp_code'))->tmp_code;
	    $count = $this->input->post('count');
	    $ok = 0; $err = 0; $err_msg = "템플릿 삭제에 실패하였습니다.";
	    //log_message("ERROR", "TEMPLATE 삭제 ".$count);
	    for($n=0;$n<$count;$n++) {
	        /* 스윗트렉커로 전송 */
	        //$ok = $ok + 1;
	        
	        $this->db->query("update cb_wt_template_dhn set tpl_share_flag='U' where tpl_profile_key=? and tpl_code=? ", array($pf_key[$n], $tmp_code[$n]));
	        //log_message("ERROR", "UPDATE ".$this->db->affected_rows() );
	        if($this->db->affected_rows() > 0) { $ok++; } else { $err++; }
	    }
	    header('Content-Type: application/json');
	    echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	}
	
	
	public function write($public_flag='N')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['profile'] = $this->Biz_model->get_partner_profile();
		$view['profile_group'] = $this->Biz_model->get_profile_group();
		$view['view']['canonical'] = site_url();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
		$view['public_flag'] = $public_flag;
		/**
		* 레이아웃을 정의합니다
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/template',
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

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/* 템플릿 저장 */
	public function add_process()
	{
		//"buttons":[{"ordering":1,"linkType":"DS","name":"11"}]}]
		$jsonString = $this->input->post('jsonString');
		if(!$jsonString) { show_404(); exit; }
		$json = json_decode($jsonString);
		$ok = 0; $err = 0; $err_msg = "템플릿 저장에 실패하였습니다.";

		$this->load->library('sweettracker');

		foreach($json as $tr) {
			$data = array();
			$partner = $this->Biz_model->get_member_from_profile_key($tr->senderKey);
			$dup = ($partner) ? $this->Biz_model->check_template_code_dup($partner->spf_key, $tr->templateCode) : 1;
			$data['tpl_id'] = ($partner) ? $this->Biz_model->get_table_next_id('cb_wt_template_dhn', 'tpl_id', "", array()) : 1;	//'tpl_mem_id=?', array($partner->spf_mem_id)) : 0;
			if($dup || !$partner || !$data['tpl_id']) { $err++; continue; }
			$data['tpl_mem_id'] = $partner->spf_mem_id;
			$data['tpl_company'] = $partner->spf_company;
			$data['tpl_profile_key'] = $partner->spf_key;
			$data['tpl_code'] = $tr->templateCode;
			$data['tpl_name'] = $tr->templateName;
			$data['tpl_contents'] = $tr->templateContent;
			$data['tpl_button'] = json_encode($tr->buttons);
			$data['tpl_datetime'] = cdate('Y-m-d H:i:s');

			/* 스윗트렉커로 전송 */
			$swData = array(
				"senderKey"=>$data['tpl_profile_key']
				,"senderKeyType"=>$partner->spf_key_type
				,"templateCode"=>$data['tpl_code']
				,"templateName"=>$data['tpl_name']
				,"templateContent"=>$data['tpl_contents']
				,"buttons"=>$tr->buttons
			);
			$result = $this->sweettracker->template_crate(array($swData), $partner->spf_appr_id);
			$json = $result[0];
			log_message('error', 'Template Code = '.$json->code);
			if($json->code!="success") {
				$err_msg = $json->message;
				$err++;
				continue;
			}

			$this->db->insert('wt_template_dhn', $data);
			if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());$err++; } else { $ok++; }
		}
		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	}

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
		$view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, b.spf_appr_id from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where tpl_id=? limit 1", array($key))->row();
		if(!$view['rs']) { show_404(); exit; }
		/* 템플릿 상태조회 */
		$this->load->library('sweettracker');
		$view['tpl_status'] = $this->sweettracker->template($view['rs']->tpl_profile_key, $view['rs']->tpl_code, $view['rs']->spf_appr_id);
		if($view['tpl_status']->code=="success") {
			if(($view['rs']->tpl_inspect_status=="REQ" && $view['rs']->tpl_inspect_status!=$view['tpl_status']->data->inspectionStatus) || $view['tpl_status']->data->status!=$view['rs']->tpl_status) {
				if($view['rs']->tpl_inspect_status=="REQ" && $view['rs']->tpl_inspect_status!=$view['tpl_status']->data->status) {
					$sql = "update cb_wt_template_dhn set tpl_inspect_status='".$view['tpl_status']->data->inspectionStatus."', tpl_appr_id='admin', tpl_appr_datetime='".cdate('Y-m-d H:i:s')."' where tpl_id=?";
				} else {
					$sql = "update cb_wt_template_dhn set tpl_status='".$view['tpl_status']->data->status."' where tpl_id=?";
				}
				$this->db->query($sql, array($key));
			}
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
			'path' => 'biz/template',
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

	public function modify_success()
	{
		$pf_key = $this->input->post('pf_key');
		$pf_type = $this->input->post('pf_type');
		$tmp_code = $this->input->post('tmp_code');
		$new_tmp_code = $this->input->post('new_tmp_code');
		$new_tmp_name = $this->input->post('new_tmp_name');
		$new_tmp_cont = $this->input->post('new_tmp_cont');
		$new_sms_cont = $this->input->post('new_sms_cont');
		$new_btn_content = $this->input->post('new_btn_content');
	
		$data = array();
		$data['tpl_code'] = $new_tmp_code;
		$data['tpl_name'] = $new_tmp_name;
		$data['tpl_contents'] = $new_tmp_cont;
		$data['tpl_button'] = $new_btn_content;

		$ok = 0; $err = 0; $err_msg = "템플릿 수정에 실패하였습니다.";

		/* 스윗트렉커로 전송 */
		$this->load->library('sweettracker');
		$swData = array(
			"senderKey"=>$pf_key
			,"senderKeyType"=>$pf_type
			,"templateCode"=>$tmp_code
			,"newSenderKey"=>$pf_key
			,"newTemplateCode"=>$new_tmp_code
			,"newTemplateName"=>$new_tmp_name
			,"newTemplateContent"=>$new_tmp_cont
			,"newButtons"=>json_decode($new_btn_content)
		);
		$json = $this->sweettracker->template_update($swData);
		if($json->code!="success") {
			$err_msg = $json->message;
			$err++;
		} else {
			$this->db->update('wt_template_dhn', $data, array("tpl_profile_key"=>$pf_key, "tpl_code"=>$tmp_code));
			if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());$err++; } else { $ok++; }
		}

		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	
	}

	public function public_temp() {
	    $temp_id = $this->input->post('temp_id');
	    
	    $sql = "update cb_wt_template_dhn set tpl_share_flag = 'P' where tpl_id = '".$temp_id."'";
	    $this->db->query($sql);
	    	    
	    header('Content-Type: application/json');
	    echo '{"message": null,"success":1 }';
	    
	}
	
	public function comment_add()
	{
		$pf_key = $this->input->post('pf_key');
		$pf_type = $this->input->post('pf_type');
		$tmp_code = $this->input->post('tmp_code');
		$comment_content = $this->input->post('comment_content');

		$ok=0; $err=0; $err_msg = "요청사항을 등록하지 못했습니다.";
		if ($pf_type == 'G') {
		    $swData = array(
		        "senderKey"=>$pf_key
		        ,"senderKeyType"=>"G"
		        ,"templateCode"=>$tmp_code
		        ,"comment"=>$comment_content
		    );
		} else {
		    $swData = array(
		        "senderKey"=>$pf_key
		        ,"senderKeyType"=>"S"
		        ,"templateCode"=>$tmp_code
		        ,"comment"=>$comment_content
		    );
		}
// 		$swData = array(
// 			"senderKey"=>$pf_key
// 			,"senderKeyType"=>"S"
// 			,"templateCode"=>$tmp_code
// 			,"comment"=>$comment_content
// 		);
		
		log_message("ERROR", "sk : ".$pf_key."/type:".$pf_type."/code:".$tmp_code."/comment:".$comment_content);
		
		$this->load->library('sweettracker');
		$json = $this->sweettracker->template_comment($swData);
		if($json->code!="success") {
			$err_msg = $json->message;
			$err++;
		} else { $ok++; }
		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
	}

	public function download()
	{
		$tmp_id = $this->input->post('tmp_id');
		$ids = explode(',', $tmp_id);

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:AD2');
		$this->excel->getActiveSheet()->mergeCells('A1:A2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '업체명');
		$this->excel->getActiveSheet()->mergeCells('B1:B2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, '템플릿코드');
		$this->excel->getActiveSheet()->mergeCells('C1:C2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, '템플릿명');
		$this->excel->getActiveSheet()->mergeCells('D1:D2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, '템플릿내용');
		$this->excel->getActiveSheet()->mergeCells('E1:H1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, '버튼1');
		$this->excel->getActiveSheet()->mergeCells('I1:L1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, '버튼2');
		$this->excel->getActiveSheet()->mergeCells('M1:P1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, '버튼3');
		$this->excel->getActiveSheet()->mergeCells('Q1:T1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, '버튼4');
		$this->excel->getActiveSheet()->mergeCells('U1:X1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 1, '버튼5');
		$this->excel->getActiveSheet()->mergeCells('Y1:Y2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, 1, '검수상태');
		$this->excel->getActiveSheet()->mergeCells('Z1:Z2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, 1, '템플릿상태');
		$this->excel->getActiveSheet()->mergeCells('AA1:AA2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, 1, '문의상태');
		$this->excel->getActiveSheet()->mergeCells('AB1:AB2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, 1, '등록일');
		$this->excel->getActiveSheet()->mergeCells('AC1:AC2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, 1, '검수신청일');
		$this->excel->getActiveSheet()->mergeCells('AD1:AD2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, 1, '검수일');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 2, '버튼타입');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 2, '버튼명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 2, '버튼타입');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 2, '버튼명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 2, '버튼타입');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 2, '버튼명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 2, '버튼타입');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 2, '버튼명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 2, '버튼타입');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 2, '버튼명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 2, '버튼링크');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, 2, '버튼링크');

		$row = 3;
		foreach($ids as $id) {
			// 데이터를 읽어서 순차로 기록한다.
			$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
				'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER )
				),	'A'.$row.'1:AD'.$row);
			$rs = $this->db->query("select * from cb_wt_template_dhn where tpl_id=? and tpl_use='Y'", array($id))->row();
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $rs->tpl_company);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $rs->tpl_code);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $rs->tpl_name);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $rs->tpl_contents);
			if($rs->tpl_button) {
				$btns = json_decode($rs->tpl_button);
				$bcnt = 0;
				foreach($btns as $btn) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4+(4*$bcnt), $row, $this->funn->get_template_button_name($btn->linkType));
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4+(4*$bcnt+1), $row, $btn->name);
					if($btn->linkType=="WL") {
						$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4+(4*$bcnt+2), $row, $btn->linkMo);
						if($btn->linkPc) {
							$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4+(4*$bcnt+3), $row, $btn->linkPc);
						}
					} else if($btn->linkType=="AL") {
						$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4+(4*$bcnt+2), $row, $btn->linkAnd);
						$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4+(4*$bcnt+3), $row, $btn->linkIos);
					}
					$bcnt++;
				}
			}
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $this->funn->get_inspect_status_name($rs->tpl_inspect_status));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $this->funn->get_tmpl_status_name($rs->tpl_status));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $this->funn->get_comment_status_name($rs->tpl_comment_status));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $rs->tpl_datetime);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $rs->tpl_check_datetime);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $rs->tpl_appr_datetime);
			$row++;
		}


		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="template_list.xls"');
		header('Cache-Control: max-age=0');

 

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');


/*
		set_time_limit(0);
		ini_set('memory_limit','-1');
		$this->load->library('excelxml');

		$this->excelxml->docAuthor('Yang');

		$sheet = $this->excelxml->addSheet('sheet1');

		$format = $this->excelxml->addStyle('StyleHeader');
		$format->fontSize(12);
		$format->fontBold();
		$format->bgColor('#333333');
		$format->fontColor('#FFFFFF');
		$format->alignHorizontal('Center');
		$format->alignVertical('Center');
		$format->border();

		$sheet->writeString(1,1,'번호1','StyleHeader');
		$sheet->writeString(1,2,'번호2','StyleHeader');
		$sheet->writeString(1,3,'번호3','StyleHeader');
		$sheet->writeString(1,4,'번호4','StyleHeader');
		$sheet->writeString(1,5,'번호5','StyleHeader');

		$filename = 'template_list.xls';

		$this->excelxml->sendHeaders($filename);
		$this->excelxml->writeData();
*/
	}
	
	
	public function public_lists()
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
	    $where = " tpl_use='Y' and exists (select 1 from cb_wt_send_profile_group where spg_pf_key = tpl_profile_key)";	//and tpl_mem_id=? ";
	    if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
	    if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
	    if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
	    if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
	    if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }
	    
	    $view['total_rows'] = $this->Biz_model->get_public_template_count($where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);
	    
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
	    //	from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
	    //	where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
	    $view['list'] = $this->Biz_model->get_public_template_list(($view['param']['page']-1), $view['perpage'], $where, $param);	//$this->db->query($sql, $param)->result();
	    $view['profile'] = $this->Biz_model->get_partner_profile();
	    $view['view']['canonical'] = site_url();
	    $view['public'] = 'Y';
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
	        'path' => 'biz/template',
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
	
}
?>