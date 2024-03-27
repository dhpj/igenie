<?php
class Template extends CB_Controller {
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
        //ib플래그
        if(config_item('ib_flag')=="Y"){
		    $this->load->library(array('querystring', 'dhnkakao_ib'));
        }else{
            $this->load->library(array('querystring', 'dhnkakao'));
        }
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
			'path' => 'biz/dhntemplate',
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
		$//ib플래그
		$sql ="select ws.spf_appr_id, cwt.* from ".config_item('ib_template')." cwt inner join ".config_item('ib_profile')." ws on cwt.tpl_profile_key = ws.spf_key where ( tpl_mem_id in
(SELECT distinct  a.mem_id
                                        FROM
                                            (SELECT
                                                GET_PARENT_EQ_PRIOR_ID(cmr.mem_id) AS _id, @level AS level
                                            FROM
                                                (SELECT @start_with:=".$this->member->item('mem_id').", @id:=@start_with) vars, cb_member_register cmr
                                            WHERE
                                                @id IS NOT NULL) ho
                                               LEFT JOIN
                                            cb_member_register c ON c.mem_id = ho._id
                                                INNER JOIN
                                            cb_member a ON (c.mem_id = a.mem_id or a.mem_id = ".$this->member->item('mem_id').")
                                                AND a.mem_useyn = 'Y'
				) or tpl_mem_id = 0 )";

		if($tpl_id) {
		    $sql = $sql." and tpl_id = ".$tpl_id;
		} else {
		    $sql = $sql."  and tpl_inspect_status in ('REQ', 'INQ', 'REG')";
        }
	    //log_message("ERROR", "TEMP SQL : ".$sql);
		$req = $this->db->query($sql )->result();
		if($req) {
            //ib플래그
            if(config_item('ib_flag')=="Y"){
    			$this->load->library('dhnkakao_ib');
            }else{
                $this->load->library('dhnkakao');
            }
			foreach($req as $r) {
                //ib플래그
                if(config_item('ib_flag')=="Y"){
    				$result = $this->dhnkakao_ib->template($r->tpl_profile_key, $r->tpl_code, ($r->tpl_share_flag == 'P')?"G":"S" );
                }else{
                    $result = $this->dhnkakao->template($r->tpl_profile_key, $r->tpl_code, ($r->tpl_share_flag == 'P')?"G":"S" );
                }
				//log_message("ERROR", "T ".$result->data->comments->status);
				//ob_start();
				//var_dump($result);
				//$msg = ob_get_contents();
				//log_message("ERROR", "T ".$msg);
				if($result->code=="200") {
				    if($result->data->inspectionStatus != $r->tpl_inspect_status || $result->data->status!=$r->tpl_status || empty($r->tpl_inspect_status) || $r->tpl_status) {
				        if($result->data->inspectionStatus!=$r->tpl_inspect_status || empty($r->tpl_inspect_status)) {
				            //log_message("ERROR", "TTT : ".$result->code."/".$r->tpl_profile_key."/".$r->tpl_code."/".$result->data->inspectionStatus."/".$r->tpl_inspect_status."/".$result->data->status."/".$r->tpl_status);
                            //ib플래그
                            $ud_sql = "
                                        update ".config_item('ib_template')."
                                           set tpl_inspect_status='".$result->data->inspectionStatus."'
                                             , tpl_status='".$result->data->status."'
                                             , tpl_appr_id='".$r->spf_appr_id."'
                                             , tpl_appr_datetime='".cdate('Y-m-d H:i:s')."'
                                         where tpl_mem_id='".$r->tpl_mem_id."'
                                           and tpl_profile_key='".$r->tpl_profile_key."'
                                           and tpl_code='".$r->tpl_code."'";
				            $this->db->query($ud_sql);
						} else {
                            //ib플래그
							$this->db->query("update ".config_item('ib_template')." set tpl_status='".$result->data->status."' where tpl_mem_id=? and tpl_profile_key=? and tpl_code=?", array($this->member->item('mem_id'), $r->tpl_profile_key, $r->tpl_code));
						}
					}
				} else {
				    //log_message("ERROR", "Template Status : ".$result->message."/".$r->tpl_profile_key."/".$r->tpl_code);
				}
			}
		}
		if($this->input->post('public_flag') == 'Y') {
		    redirect('/dhnbiz/template/public_lists');
		} else {
		    redirect('/dhnbiz/template/lists');
		}
	}

	public function btn()
	{
		$tmpl_id = $this->input->post('tmpl_id');
		$view = array();
        //ib플래그
		$view['rs'] = $this->Biz_dhn_model->get_row(config_item('ib_template'), "tpl_id=?", array($tmpl_id));
		$this->load->view("biz/dhntemplate/btn", $view);
	}

	/* 템플릿 검수 요청 : 여러건 검수요청 */
	public function check_inspect()
	{
		$pf_key = json_decode($this->input->post('pf_key'))->pf_key;
		$pf_type = json_decode($this->input->post('pf_type'))->pf_type;
		$tmp_code = json_decode($this->input->post('tmp_code'))->tmp_code;
		$count = $this->input->post('count');
		$ok = 0; $err = 0; $err_msg = "템플릿 검수요청에 실패하였습니다.";
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$this->load->library('dhnkakao_ib');
        }else{
            $this->load->library('dhnkakao');
        }

		for($n=0;$n<$count;$n++) {
			/* 스윗트렉커로 전송 */
			$swData = array(
				"senderKey"=>$pf_key[$n]
				,"senderKeyType"=>$pf_type[$n]
				,"templateCode"=>$tmp_code[$n]
			);
            //ib플래그
            if(config_item('ib_flag')=="Y"){
    			$result = $this->dhnkakao_ib->template_request($swData);
            }else{
                $result = $this->dhnkakao->template_request($swData);
            }
			if($result->code!="200") {
			    //log_message("ERROR", "T REQ : ".$result->message);
				$err_msg = $result->message;
				$err++;
				continue;
			}
            //ib플래그
			$this->db->query("update ".config_item('ib_template')." set tpl_inspect_status='REQ', tpl_check_datetime=? where tpl_profile_key=? and tpl_code=? and tpl_use='Y' and tpl_inspect_status='REG'", array(cdate('Y-m-d H:i:s'), $pf_key[$n], $tmp_code[$n]));
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
		$ok = 0;
		$err = 0;
		$err_msg = "템플릿 삭제에 실패하였습니다.";
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$this->load->library('dhnkakao_ib');
        }else{
            $this->load->library('dhnkakao');
        }
		//log_message("ERROR", "템플릿 삭제 : count : ".$count);
		for($n=0;$n<$count;$n++) {
			/* 스윗트렉커로 전송 */
			$swData = array(
				"senderKey"=>$pf_key[$n]
				,"senderKeyType"=>$pf_type[$n]
				,"templateCode"=>$tmp_code[$n]
			);
            //ib플래그
            if(config_item('ib_flag')=="Y"){
    			$result = $this->dhnkakao_ib->template_delete($swData);
            }else{
                $result = $this->dhnkakao->template_delete($swData);
            }
			if($result->code!="200") {
				$err_msg = $result->message;

				// 오류코드가 들어오더라도 템플릿이 반려이면 목록에서 삭제 : 2018.03.23
                //ib플래그
				$this->db->query("update ".config_item('ib_template')." set tpl_use='N' where tpl_profile_key=? and tpl_code=? and tpl_inspect_status='REJ'", array($pf_key[$n], $tmp_code[$n]));

				$err++;
				continue;
			}
            //ib플래그
			$this->db->query("update ".config_item('ib_template')." set tpl_use='N' where tpl_profile_key=? and tpl_code=? and (tpl_inspect_status='REG' OR tpl_inspect_status='REJ')", array($pf_key[$n], $tmp_code[$n]));
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
            //ib플래그
	        $this->db->query("update ".config_item('ib_template')." set tpl_use='N' where tpl_profile_key=? and tpl_code=? ", array($pf_key[$n], $tmp_code[$n]));
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
            //ib플래그
	        $this->db->query("update ".config_item('ib_template')." set tpl_share_flag='U' where tpl_profile_key=? and tpl_code=? ", array($pf_key[$n], $tmp_code[$n]));
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

		$view['profile'] = $this->Biz_dhn_model->get_partner_profile();
		$view['profile_group'] = $this->Biz_dhn_model->get_profile_group();
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
			'path' => 'biz/dhntemplate',
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
		//log_message("ERROR", "JSON : ".$jsonString);
		if(!$jsonString) { show_404(); exit; }
		$json = json_decode($jsonString);
		$ok = 0; $err = 0; $err_msg = "템플릿 저장에 실패하였습니다.";
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$this->load->library('dhnkakao_ib');
        }else{
            $this->load->library('dhnkakao');
        }

		foreach($json as $tr) {
			$data = array();
			$partner = $this->Biz_dhn_model->get_member_from_profile_key($tr->senderKey);
			$dup = ($partner) ? $this->Biz_dhn_model->check_template_code_dup($partner->spf_key, $tr->templateCode) : 1;
            //ib플래그
			$data['tpl_id'] = ($partner) ? $this->Biz_dhn_model->get_table_next_id(config_item('ib_template'), 'tpl_id', "", array()) : 1;	//'tpl_mem_id=?', array($partner->spf_mem_id)) : 0;
			if($dup || !$partner || !$data['tpl_id']) {
			    $err++;
			    //log_message('ERROR', '처리중 오류 :'.$dup.'/'.$data['tpl_id']);
			    continue;
			}
			$data['tpl_mem_id'] = $partner->spf_mem_id;
			$data['tpl_company'] = $partner->spf_company;
			$data['tpl_profile_key'] = $partner->spf_key;
			$data['tpl_code'] = $tr->templateCode;
			$data['tpl_name'] = $tr->templateName;
			$data['tpl_contents'] = $tr->templateContent;
			$data['tpl_button'] = json_encode($tr->buttons);
			$data['tpl_datetime'] = cdate('Y-m-d H:i:s');
			$data['tpl_share_flag'] = ($partner->spf_key_type == 'G')?"P":"U";

			$data['tpl_type_group'] = $tr->publicTemplatePart;


			/* 스윗트렉커로 전송 */
			$swData = array(
				"senderKey"=>$data['tpl_profile_key']
				,"senderKeyType"=>$partner->spf_key_type
				,"templateCode"=>$data['tpl_code']
				,"templateName"=>$data['tpl_name']
				,"templateContent"=>$data['tpl_contents']
			    ,"templateMessageType"=>$tr->templateMessageType
			    ,"templateEmphasizeType"=>$tr->templateEmphasizeType
//				,"buttons"=>$tr->buttons
			);

			$i = 0;
			foreach($tr->buttons as $btn) {
			    $swData['buttons['.$i.'].name'] = $btn->name;
			    $swData['buttons['.$i.'].linkType'] = $btn->linkType;
			    $swData['buttons['.$i.'].ordering'] = $btn->ordering;
			    $swData['buttons['.$i.'].linkMo'] = $btn->linkMo;
			    $swData['buttons['.$i.'].linkPc'] = $btn->linkPc;
			    $swData['buttons['.$i.'].linkAnd'] = $btn->linkAnd;
			    $swData['buttons['.$i.'].linkIos'] = $btn->linkIos;
			    $i++;
			}

			//log_message("ERROR", json_encode($swData, JSON_UNESCAPED_UNICODE ));
            //ib플래그
            if(config_item('ib_flag')=="Y"){
    			$result = $this->dhnkakao_ib->template_crate($swData);
            }else{
                $result = $this->dhnkakao->template_crate($swData);
            }
			$json = $result;
			//log_message('error', 'Template Code = '.$json->code);
			if($json->code!="200") {
				$err_msg = $json->message;
				$err++;
				continue;
			}
            //ib플래그
			$this->db->insert(config_item('ib_template'), $data);
			if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());$err++; } else { $ok++; }
		}
		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';
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
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$this->load->library('dhnkakao_ib'); //템플릿 상태조회
        }else{
            $this->load->library('dhnkakao'); //템플릿 상태조회
        }
		//log_message('error', $_SERVER['REQUEST_URI'] .' > key : '. $key .', rs tpl_profile_key : '. $view['rs']->tpl_profile_key .', tpl_code : '. $view['rs']->tpl_code);
		$sendtype = ($view['rs']->tpl_share_flag == 'P')?"G":"S";
        //ib플래그
        if(config_item('ib_flag')=="Y"){
		    $view['tpl_status'] = $this->dhnkakao_ib->template($view['rs']->tpl_profile_key, $view['rs']->tpl_code, $sendtype);
        }else{
            $view['tpl_status'] = $this->dhnkakao->template($view['rs']->tpl_profile_key, $view['rs']->tpl_code, $sendtype);
        }
		//log_message('error', $_SERVER['REQUEST_URI'] .' > view[tpl_status]->code : '. $view['tpl_status']->code);

		if($view['tpl_status']->code=="200") {
			//log_message('error', 'rs status : '.$view['rs']->tpl_inspect_status.' / '.$view['tpl_status']->data->inspectionStatus);
            //ib플래그
			if(($view['rs']->tpl_inspect_status!=$view['tpl_status']->data->inspectionStatus) || $view['tpl_status']->data->status!=$view['rs']->tpl_status) {
                if($view['rs']->tpl_inspect_status!=$view['tpl_status']->data->status) {
					$sql = "update ".config_item('ib_template')." set tpl_inspect_status='".$view['tpl_status']->data->inspectionStatus."', tpl_appr_id='admin', tpl_appr_datetime='".cdate('Y-m-d H:i:s')."' where tpl_id=?";
				} else {
					$sql = "update ".config_item('ib_template')." set tpl_status='".$view['tpl_status']->data->status."' where tpl_id=?";
				}
				$this->db->query($sql, array($key));
			}
            //ib플래그
			$view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, b.spf_appr_id from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where tpl_id=? limit 1", array($key))->row();
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
			'path' => 'biz/dhntemplate',
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
		$this->load->library('');
		$swData = array(
			"senderKey"=>$pf_key
			,"senderKeyType"=>$pf_type
			,"templateCode"=>$tmp_code
			,"newSenderKey"=>$pf_key
			,"newTemplateCode"=>$new_tmp_code
			,"newTemplateName"=>$new_tmp_name
			,"newTemplateContent"=>$new_tmp_cont
			//,"newButtons"=>json_decode($new_btn_content)
		);
		$newBtn = json_decode($new_btn_content);
		$i = 0;
		foreach($newBtn as $btn) {
		    $swData['buttons['.$i.'].name'] = $btn->name;
		    $swData['buttons['.$i.'].linkType'] = $btn->linkType;
		    $swData['buttons['.$i.'].ordering'] = $btn->ordering;
		    $swData['buttons['.$i.'].linkMo'] = $btn->linkMo;
		    $swData['buttons['.$i.'].linkPc'] = $btn->linkPc;
		    $swData['buttons['.$i.'].linkAnd'] = $btn->linkAnd;
		    $swData['buttons['.$i.'].linkIos'] = $btn->linkIos;
		    $i++;
		}
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$json = $this->dhnkakao_ib->template_update($swData);
        }else{
            $json = $this->dhnkakao->template_update($swData);
        }
		if($json->code!="200") {
			$err_msg = $json->message;
			$err++;
		} else {
            //ib플래그
			$this->db->update(config_item('ib_template'), $data, array("tpl_profile_key"=>$pf_key, "tpl_code"=>$tmp_code));
			if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());$err++; } else { $ok++; }
		}

		header('Content-Type: application/json');
		echo '{"message": '.(($ok>0) ? 'null' : '"'.$err_msg.'"').', "success": '.$ok.', "fail": '.$err.' }';

	}

	public function public_temp() {
	    $temp_id = $this->input->post('temp_id');
        //ib플래그
	    $sql = "update ".config_item('ib_template')." set tpl_share_flag = 'P' where tpl_id = '".$temp_id."'";
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

		//log_message("ERROR", "sk : ".$pf_key."/type:".$pf_type."/code:".$tmp_code."/comment:".$comment_content);
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$this->load->library('dhnkakao_ib');
    		$json = $this->dhnkakao_ib->template_comment($swData);
        }else{
            $this->load->library('dhnkakao');
    		$json = $this->dhnkakao->template_comment($swData);
        }
		if($json->code!="200") {
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
            //ib플래그
			$rs = $this->db->query("select * from ".config_item('ib_template')." where tpl_id=? and tpl_use='Y'", array($id))->row();
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
        $view['param']['pf_g'] = $this->input->post('pf_g');
	    $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
	    $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
	    $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
	    $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
	    $view['param']['tmpl_search'] = $this->input->post('tmpl_search');			//pf_ynm
	    $view['param']['searchStr'] = $this->input->post('searchStr');					//한국
	    $param = array();	//$this->member->item('mem_id'));
        //ib플래그
	    $where = " tpl_use='Y' and tpl_share_flag ='P' and exists (select 1 from ".config_item('ib_profile_group')." where spg_pf_key = tpl_profile_key)";	//and tpl_mem_id=? ";
        if($view['param']['pf_g']!="ALL" && $view['param']['pf_g']) { $where.=" and tpl_profile_key in (select ca.spg_pf_key from cb_wt_send_profile_group_dhn ca where ca.spg_pf_key = '" . $view['param']['pf_g'] . "' OR ca.spg_p_pf_key = '" . $view['param']['pf_g'] . "') "; array_push($param, $view['param']['pf_g']); }
	    if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
	    if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
	    if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
	    if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
	    if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }

		$view['param']['advyn'] = $this->input->post('advyn'); //알림톡 사용여부
		if($view['param']['advyn']!="ALL" && $view['param']['advyn']) { $where.=" and tpl_adv_yn=? "; array_push($param, $view['param']['advyn']); }
		//echo "where : ". $where ."<br>";

	    $view['total_rows'] = $this->Biz_dhn_model->get_public_template_count($where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);

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
	    $view['list'] = $this->Biz_dhn_model->get_public_template_list(($view['param']['page']-1), $view['perpage'], $where, $param);	//$this->db->query($sql, $param)->result();
	    $view['profile'] = $this->Biz_dhn_model->get_partner_profile();
        $view["group"] = $this->Biz_dhn_model->get_profile_group();
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
	        'path' => 'biz/dhntemplate',
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

	public function public_part_lists() {
	    //log_message("ERROR", "public_part_lists call");
	    $group_pf_key = $this->input->post('group_pf_key');
	    // 	    $mem_id = $this->input->post('mem_id');
	    // 	    $memo_kind = $this->input->post('memo_kind');

	    $partSql = "select count(tp_part_id) cnt
            from cb_wt_template_part_dhn
            where tp_profile_key = '".$group_pf_key."' and tp_used='Y'
        ";
	    //log_message("ERROR", "Pulbic Template partSql cnt : ".$partSql);
	    $part_count = $this->db->query($partSql)->row()->cnt;
	    //log_message("ERROR", "Pulbic Template partSql cnt : ".$view['part_cnt']);
	    $partSql = "select tp_part_id, tp_part_name
            from dhn.cb_wt_template_part_dhn
            where tp_profile_key = '".$group_pf_key."' and tp_used='Y'
            order by tp_order, tp_part_id
        ";
	    //log_message("ERROR", "Pulbic Template partSql : ".$partSql);
	    $part_list = $this->db->query($partSql)->result();

	    header('Content-Type: application/json');
	    echo '{"message":"success", "code":"success", "part_count":'.$part_count.', "part_list" : '.json_encode($part_list).'}';

	}

	//템플릿 관리 > 템플릿 가져오기 화면
	public function take($public_flag='N')
	{
	    // 이벤트 라이브러리를 로딩합니다
	    $eventname = 'event_main_index';
	    $this->load->event($eventname);

	    $view = array();
	    $view['view'] = array();

	    // 이벤트가 존재하면 실행합니다
	    $view['view']['event']['before'] = Events::trigger('before', $eventname);
        //ib플래그
	    $view['profile'] = $this->db->query("select a.* from ".config_item('ib_profile')." a")->result();
	    $view['profile_group'] = $this->Biz_dhn_model->get_profile_group();
	    $view['view']['canonical'] = site_url();

	    // 이벤트가 존재하면 실행합니다
	    $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
	    $view['public_flag'] = $public_flag;

	    $view['senderkey'] = $this->input->post("senderkey");
	    /**
	     *
	     * 레이아웃을 정의합니다
	     */
	    $page_title = $this->cbconfig->item('site_meta_title_main');
	    $meta_description = $this->cbconfig->item('site_meta_description_main');
	    $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
	    $meta_author = $this->cbconfig->item('site_meta_author_main');
	    $page_name = $this->cbconfig->item('site_page_name_main');

	    $layoutconfig = array(
	        'path' => 'biz/dhntemplate',
	        'layout' => 'layout',
	        'skin' => 'take',
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

	//템플릿 관리 > 템플릿 가져오기 화면 > 템플릿 가져오기
	public function taket()
	{
	    $senderkey = $this->input->post('senderKey');
	    $tcode = $this->input->post('tcode');
	    //log_message("ERROR", "Template Req : ".$senderkey.' / '.$tcode);
        //ib플래그
	    $sql="select * from ".config_item('ib_profile')." where spf_key='".$senderkey."'";
	    $spf = $this->db->query($sql)->row();
	    $skt = ((empty($spf->spf_key_type))?"S":"G");
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    	    $result = $this->dhnkakao_ib->template($senderkey, $tcode, $skt);
        }else{
            $result = $this->dhnkakao->template($senderkey, $tcode, $skt);
        }
		//log_message("ERROR", "/dhnbiz/template/taket > result : ".$result);
		$json = json_decode($result);
	    $rtn = $json->code;
	    if($json->code == "200" &&!empty($json->data)) {
	        $temp = array();
            //ib플래그
	        $sql="select * from ".config_item('ib_template')." where tpl_profile_key='".$json->data->senderKey."' and tpl_code = '".$json->data->templateCode."'" ;
	        $tpl = $this->db->query($sql)->row();
	        if(empty($tpl->tpl_id )) {
                //ib플래그
	            $temp['tpl_id'] = ($json->data) ? $this->Biz_dhn_model->get_table_next_id(config_item('ib_template'), 'tpl_id', "", array()) : 1;
	            $temp['tpl_mem_id'] =$spf->spf_mem_id;
	        } else {
	            $temp['tpl_id'] = $tpl->tpl_id;
	            $temp['tpl_mem_id'] = $tpl->tpl_mem_id;
	        }
	        $temp['tpl_company'] = $spf->spf_company;
	        $temp['tpl_profile_key'] = $json->data->senderKey;
	        //$temp['tpl_type_group'] = $json->;
	        $temp['tpl_code'] = $json->data->templateCode;
	        $temp['tpl_name'] = $json->data->templateName;
	        $temp['tpl_contents'] = $json->data->templateContent;
	        $temp['tpl_button'] =  json_encode($json->data->buttons);
	        $temp['tpl_datetime'] = $json->data->createdAt;
	        $temp['tpl_inspect_status'] = $json->data->inspectionStatus;
	        $temp['tpl_status'] = $json->data->status;
	        $temp['tpl_share_flag'] = ($skt=='S'?'U':'P');
	        $temp['tpl_categorycode'] = $json->data->categoryCode;
	        //$temp['tpl_securityflag'] = $json->data->securityFlag;
	        if($json->data->securityFlag) {
	            $temp['tpl_securityflag'] = '1';
	        } else {
	            $temp['tpl_securityflag'] = '0';
	        }
	        $temp['tpl_messagetype'] = $json->data->templateMessageType;
	        $temp['tpl_emphasizetype'] = $json->data->templateEmphasizeType;
	        $temp['tpl_extra'] = $json->data->templateExtra;
	        $temp['tpl_ad'] = $json->data->templateAd;
	        $temp['tpl_title'] = $json->data->templateTitle;
	        $temp['tpl_subtitle'] = $json->data->templateSubtitle;
	        $temp['tpl_imagename'] = $json->data->templateImageName;
	        $temp['tpl_imageurl'] = $json->data->templateImageUrl;
	        $temp['tpl_quickreplies'] = json_encode($json->data->quickReplies);
            //ib플래그
	        $this->db->replace(config_item('ib_template'), $temp);
			$result =  '{"code":"200","message":"템플릿 가져오기가 완료 되었습니다."}';
	    }

	    header('Content-Type: application/json');
	    //echo '{"code": '.$rtn.' }';
		echo $result;
	}

	// 템플릿 엑셀파일로 가져오기
	public function excel_upload(){
	    log_message("ERROR","/dhnbiz/template/excel_upload > excel_upload start");
	    $public_flag = $this->input->post('public_flag');
	    // 라이브러리를 로드한다. 위의 내용 참고
	    $this->load->library('excel');
	    $success = 0;
	    $fail = 0;
	    $all = 0;


	    // 업로드된 파일이 정상적으로 됬는지 확인 UPLOAD_ERR_OK는 제대로 됬다는것을 의미한다.
	    if ($_FILES['excel_upload_file']['error'] == UPLOAD_ERR_OK) {
	        $uploaded_file = $_FILES['excel_upload_file']['tmp_name'];

	        // 엑셀 파일을 읽는것이다.
	        $objPHPExcel = PHPExcel_IOFactory::load($uploaded_file);

	        // 첫 번째 시트 선택
	        $objPHPExcel->setActiveSheetIndex(0);
	        $worksheet = $objPHPExcel->getActiveSheet();

	        // 해당시트에서 데이터 읽기
	        $highestRow = $worksheet->getHighestRow(); // 마지막 행

	        $now_date = date('Y-m-d H:i:s');
	        $group_num = str_replace(array('-', ':',' '), '', $now_date);

	        // 데이터 처리 여기서 함
	        for ($row = 1; $row <= $highestRow; $row++) {
	            $all++;
	            $spf_company_re = str_replace(" ","",(trim($worksheet->getCell('A' . $row)->getValue())));
	            $spf_company = $worksheet->getCell('A' . $row)->getValue();
	            $tpl_code = trim($worksheet->getCell('B' . $row)->getValue());

	            //log_message("ERROR","업체명 : ".$spf_company);
	            //log_message("ERROR","템플릿 코드 : ".$tpl_code);
	            //log_message("ERROR","public_code".$public_flag);

	            $sql = "select spf_mem_id,spf_key,spf_key_type from cb_wt_send_profile_dhn where replace(spf_company,' ','') like '".$spf_company_re."'";


	            $sql_result = $this->db->query($sql)->row();

	            $skt = ((empty($sql_result->spf_key_type))?"S":"G");

	            /*
	             if (!empty($sql_result)) {
	             log_message("ERROR", "mem_id : " . $sql_result->spf_mem_id);
	             log_message("ERROR", "spf_key : " . $sql_result->spf_key);
	             } else {
	             log_message("ERROR", "No results found in the database.");
	             }
	             */

	            if($sql_result->spf_mem_id==1 || $sql_result->spf_mem_id==0){
	                $data = array(
	                    'senderKey' => $sql_result->spf_key,
	                    'senderKeyType' => 'G',
	                    'templateCode' => $tpl_code
	                );
	            }else {
	                $data = array(
	                    'senderKey' => $sql_result->spf_key,
	                    'senderKeyType' => 'S',
	                    'templateCode' => $tpl_code
	                );
	            }

	            $result = $this->dhnkakao->template_check($data);

	            $max_tpl_sql = 'select max(tpl_id) as max_tpl_id from dhn.cb_wt_template_dhn';
	            $sql_max_tpl_resulq = $this->db->query($max_tpl_sql)->row();

	            //log_message("ERROR",$result->code);
	            if($result->code==200){
	                //log_message("ERROR",$result->data->status);
	                $temp = array();

	                if($public_flag=="Y"){
	                    $sql="select * from cb_wt_template_dhn where tpl_profile_key='".$result->data->senderKey."' and tpl_code = '".$result->data->templateCode."'" ;
	                }else{
	                    $sql="select * from cb_wt_template_dhn where tpl_profile_key='".$result->data->senderKey."' and tpl_code = '".$result->data->templateCode."' and tpl_mem_id = '".$sql_result->spf_mem_id."'" ;
	                }
	                $tpl = $this->db->query($sql)->row();

	                if(empty($tpl->tpl_id)) {
	                    $temp['tpl_id'] = ($result->data) ? $this->Biz_dhn_model->get_table_next_id('cb_wt_template_dhn', 'tpl_id', "", array()) : 1;
	                    $temp['tpl_mem_id'] =$sql_result->spf_mem_id;
	                } else {
	                    $temp['tpl_id'] = $tpl->tpl_id;
	                    $temp['tpl_mem_id'] = $tpl->tpl_mem_id;
	                }
	                $temp['tpl_company'] = $spf_company;
	                $temp['tpl_profile_key'] = $result->data->senderKey;
	                $temp['tpl_code'] = $result->data->templateCode;
	                $temp['tpl_name'] = $result->data->templateName;
	                $temp['tpl_inspect_status'] = $result->data->inspectionStatus;
	                $temp['tpl_status'] = $result->data->status;
	                //$temp['tpl_comment_status'] = $result->data->
	                $temp['tpl_use'] = 'Y';
	                $temp['tpl_contents'] = $result->data->templateContent;
	                $temp['tpl_button'] =  json_encode($result->data->buttons);
	                $temp['tpl_datetime'] = $result->data->createdAt;

	                //$temp['tpl_check_datetime']
	                //$temp['tpl_appr_id']
	                //$temp['tpl_appr_datetime']

	                $temp['tpl_share_flag'] = ($skt=='S'?'U':'P');
	                // $temp['tpl_type_group']
	                $temp['tpl_categorycode'] = $result->data->categoryCode;

	                if($result->data->securityFlag) {
	                    $temp['tpl_securityflag'] = '1';
	                } else {
	                    $temp['tpl_securityflag'] = '0';
	                }

	                $temp['tpl_messagetype'] = $result->data->templateMessageType;
	                $temp['tpl_emphasizetype'] = $result->data->templateEmphasizeType;
	                $temp['tpl_extra'] = $result->data->templateExtra;
	                $temp['tpl_ad'] = $result->data->templateAd;
	                $temp['tpl_title'] = $result->data->templateTitle;
	                $temp['tpl_subtitle'] = $result->data->templateSubtitle;

	                //$temp['tpl_image_loc']

	                $temp['tpl_imagename'] = $result->data->templateImageName;
	                $temp['tpl_imageurl'] = $result->data->templateImageUrl;
	                $temp['tpl_quickreplies'] = json_encode($result->data->quickReplies);


	                $this->db->replace('cb_wt_template_dhn', $temp);
	                $success++;

	                $record_data = array();
	                //$record_data['tpl_mem_id'] = $temp['tpl_mem_id'];
	                //$record_data['tpl_id'] = ($result->data) ? $this->Biz_dhn_model->get_table_next_id('cb_wt_template_record', 'tpl_id', "", array()) : 1;
	                $record_data['tpl_mem_id'] = $sql_result->spf_mem_id;
	                $record_data['tpl_company'] = $spf_company;
	                $record_data['tpl_profile_key'] = $sql_result->spf_key;
	                $record_data['tpl_code'] = $tpl_code;
	                $record_data['tpl_res_code'] = $result->code;
	                $record_data['tpl_record_date'] = $now_date;
	                $record_data['tpl_group_num'] = $group_num;

	                $this->db->insert('cb_wt_template_record',$record_data);

	                //log_message("ERROR","성공한 업체명 : ".$spf_company);
	                //log_message("ERROR","성공한 템플릿 코드 : ".$tpl_code);
	            }else{
	                $fail++;

	                $record_data = array();
	                //$record_data['tpl_mem_id'] = $temp['tpl_mem_id'];
	                //$record_data['tpl_id'] = ($result->data) ? $this->Biz_dhn_model->get_table_next_id('cb_wt_template_record', 'tpl_id', "", array()) : 1;
	                if(empty($sql_result->spf_mem_id)){
	                    $record_data['tpl_mem_id'] = 0;
	                }else{
	                    $record_data['tpl_mem_id'] = $sql_result->spf_mem_id;
	                }
	                //$record_data['tpl_mem_id'] = $sql_result->spf_mem_id;
	                $record_data['tpl_company'] = $spf_company;
	                $record_data['tpl_profile_key'] = $sql_result->spf_key;
	                $record_data['tpl_code'] = $tpl_code;
	                $record_data['tpl_res_code'] = $result->code;
	                $record_data['tpl_record_date'] = $now_date;
	                $record_data['tpl_group_num'] = $group_num;

	                $this->db->insert('cb_wt_template_record',$record_data);

	                log_message("ERROR","템플릿 추가 실패 업체 ( ".$spf_company." ), 템플릿코드 ( ".$tpl_code." ), 실패코드 ( ".$result->code." )");
	                //log_message("ERROR","실패한 코드 : ".$result->code);
	                //log_message("ERROR","실패한 업체명 : ".$spf_company);
	                //log_message("ERROR","실패한 템플릿 코드 : ".$tpl_code);
	            }

	        }

	        $result =  '{"code":"200","message":"템플릿 가져오기가 완료 되었습니다.","all":"'.$all.'","success":"'.$success.'","fail":"'.$fail.'"}';
	    } else {
	        log_message("ERROR","/dhnbiz/template/excel_upload > 엑셀 템플릿 가져오기 오류 발생");
	    }

	    header('Content-Type: application/json');
	    echo json_encode($result, JSON_UNESCAPED_UNICODE);

	    log_message("ERROR","/dhnbiz/template/excel_upload > excel_upload end");

	}

	//템플릿 > 광고성 알림톡 여부 업데이트
	public function adv_yn(){
		$tpl_id = $this->input->post('tpl_id'); //템플릿번호
		$tpl_adv_yn = $this->input->post('tpl_adv_yn'); //광고성 알림톡 여부
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_id : ". $tpl_id .", tpl_adv_yn : ". $tpl_adv_yn);
		$where = array();
		$where["tpl_id"] = $tpl_id; //템플릿번호
		$data = array();
		$data["tpl_adv_yn"] = $tpl_adv_yn; //광고성 알림톡 여부
        //ib플래그
		$rtn = $this->db->update(config_item('ib_template'), $data, $where);
		header('Content-Type: application/json');
		echo '{"code":"200", "message":"success"}';
	}

	//템플릿 > 광고성 알림톡 메인 여부 업데이트
	public function adv_main_yn(){
		$tpl_id = $this->input->post('tpl_id'); //템플릿번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_id : ". $tpl_id .", tpl_adv_main_yn : ". $tpl_adv_main_yn);
		//기존 메인 사용 => 사안안함 처리
		$where = array();
		$where["tpl_adv_main_yn"] = "Y"; //광고성 알림톡 메인 여부
		$data = array();
		$data["tpl_adv_main_yn"] = "N"; //광고성 알림톡 메인 여부
        //ib플래그
		$rtn = $this->db->update(config_item('ib_template'), $data, $where);

		//신규 메인 사용
		$where = array();
		$where["tpl_id"] = $tpl_id; //템플릿번호
		$data = array();
		$data["tpl_adv_main_yn"] = "Y"; //광고성 알림톡 메인 여부
        //ib플래그
		$rtn = $this->db->update(config_item('ib_template'), $data, $where);
		header('Content-Type: application/json');
		echo '{"code":"200", "message":"success"}';
	}

	//템플릿 > 프리미엄 여부 업데이트
	public function premium_yn(){
		$tpl_id = $this->input->post('tpl_id'); //템플릿번호
		$tpl_premium_yn = $this->input->post('tpl_premium_yn'); //프리미엄 여부
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_id : ". $tpl_id .", tpl_premium_yn : ". $tpl_premium_yn);
		$where = array();
		$where["tpl_id"] = $tpl_id; //템플릿번호
		$data = array();
		$data["tpl_premium_yn"] = $tpl_premium_yn; //프리미엄 여부
        //ib플래그
		$rtn = $this->db->update(config_item('ib_template'), $data, $where);
		header('Content-Type: application/json');
		echo '{"code":"200", "message":"success"}';
	}

	//템플릿 > 버튼1타입 업데이트
	public function btn1_type(){
		$tpl_id = $this->input->post('tpl_id'); //템플릿번호
		$tpl_btn1_type = $this->input->post('tpl_btn1_type'); //버튼1타입
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_id : ". $tpl_id .", tpl_premium_yn : ". $tpl_premium_yn);
		$where = array();
		$where["tpl_id"] = $tpl_id; //템플릿번호
		$data = array();
		$data["tpl_btn1_type"] = $tpl_btn1_type; //버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
        //ib플래그
		$rtn = $this->db->update(config_item('ib_template'), $data, $where);
		header('Content-Type: application/json');
		echo '{"code":"200", "message":"success"}';
	}

	//템플릿 > 템플릿 카테고리 관리
	public function category() {
	    // 이벤트 라이브러리를 로딩합니다
	    $eventname = 'event_main_index';
	    $this->load->event($eventname);

	    $view = array();
	    $view['view'] = array();

	    // 이벤트가 존재하면 실행합니다
	    $view['view']['event']['before'] = Events::trigger('before', $eventname);

	    //태그관리 목록1
        //ib플래그
 	    $sql = "
 		SELECT *
 		FROM ".config_item('ib_category')."
		WHERE tc_type = 'T'
		ORDER BY tc_seq ASC ";
	    //echo $_SERVER['REQUEST_URI'] ." > sql : ".$sql ."<br>";
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
	    $view['list'] = $this->db->query($sql)->result();

	    //태그관리 목록2
        //ib플래그
	    $sql2 = "
		SELECT *
		FROM ".config_item('ib_category')."
		WHERE tc_type = 'I'
		ORDER BY tc_seq ASC ";
	    //echo $_SERVER['REQUEST_URI'] ." > sql : ".$sql ."<br>";
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
	    $view['list2'] = $this->db->query($sql2)->result();

	    // 이벤트가 존재하면 실행합니다
	    $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
	    $view['public_flag'] = $public_flag;

	    $view['senderkey'] = $this->input->post("senderkey");
	    /**
	     *
	     * 레이아웃을 정의합니다
	     */
	    $page_title = $this->cbconfig->item('site_meta_title_main');
	    $meta_description = $this->cbconfig->item('site_meta_description_main');
	    $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
	    $meta_author = $this->cbconfig->item('site_meta_author_main');
	    $page_name = $this->cbconfig->item('site_page_name_main');

	    $layoutconfig = array(
	        'path' => 'biz/dhntemplate',
	        'layout' => 'layout',
	        'skin' => 'category',
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

	public function category_list() {
	    $tc_type = $this->input->post("type");
        //ib플래그
	    $partSql = "select count(*) cnt
		FROM ".config_item('ib_category')."
		WHERE tc_useyn = 'Y'
		AND tc_type = '".$tc_type."'";

	    log_message("ERROR", "Pulbic Template partSql cnt : ".$partSql);
	    $list_count = $this->db->query($partSql)->row()->cnt;

	    //태그관리 목록
        //ib플래그
	    $sql = "
		SELECT *
		FROM ".config_item('ib_category')."
		WHERE tc_useyn = 'Y'
		AND tc_type = '".$tc_type."'
		ORDER BY tc_seq ASC ";
	    //echo $_SERVER['REQUEST_URI'] ." > sql : ".$sql ."<br>";
	    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
	    $list = $this->db->query($sql)->result();

	    header('Content-Type: application/json');
	    echo '{"message":"success", "code":200, "list_count":'.$list_count.', "category_list" : '.json_encode($list).'}';

	}

	//템플릿 > 카테고리 관리 > 저장
	public function category_save(){
	    //log_message("ERROR", $_SERVER['REQUEST_URI']);
	    $mem_id = $this->member->item("mem_id"); //회원번호
	    $data_id = $this->input->post("data_id"); //일련번호
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", data_id : ". $data_id);
	    $data = array();
	    $data["tc_description"] = $this->input->post("description"); //태그명
	    $data["tc_type"] = $this->input->post("type"); //태그 카테고리 T OR S
	    if(!empty($data_id)) { //수정의 경우
	        $where = array();
	        $where["tc_id"] = $data_id; //일련번호
	        $where["tc_create_by"] = $mem_id; //회원번호
            //ib플래그
	        $rtn = $this->db->update(config_item('ib_category'), $data, $where); //데이타 수정
	    }else{ //등록
	        //신규 정렬순서
            //ib플래그
	        $sql = "select ifnull(max(tc_seq),0)+1 as tc_seq from ".config_item('ib_category')." where tc_type='".$data["tc_type"]."' ";
	        $seq = $this->db->query($sql)->row()->tc_seq;

	        //카테고리 데이타 추가
	        $data["tc_create_by"] = $mem_id; //회원번호
	        $data["tc_seq"] = $seq; //정렬순서
            //ib플래그
	        $rtn = $this->db->replace(config_item('ib_category'), $data); //데이타 추가
	    }
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", rtn = ". $rtn);

	    $result = array();
	    $result['code'] = '0';
	    $result['id'] = $data_id;
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//템플릿 > 태그관리 > 삭제
	public function category_del(){
	    $id = $this->input->post("id"); //일련번호
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > id : ". $id);

	    //전체수
        //ib플래그
	    $sql = "	SELECT count(1) as cnt	FROM ".config_item('ib_categoryid')." WHERE tci_category_id = '". $id ."' ";
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
	    //echo $sql ."<br>";
	    $cnt = $this->db->query($sql)->row()->cnt;

	    //삭제 또는 사용안함 처리
        //ib플래그
	    if($cnt == 0){ //사용중인 태그번호가 없는 경우
	        $sql = "DELETE FROM ".config_item('ib_category')." WHERE tc_id = '". $id ."' "; //삭제
	    }else{ //태그번호가 사용중인 경우
	        $sql = "UPDATE ".config_item('ib_category')." SET tc_useyn = 'N' WHERE tc_id = '". $id ."' "; //사용안함 처리
	    }
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 템플릿 > 태그관리 삭제 : ". $sql);
	    $this->db->query($sql);
	    $result = array();
	    $result['code'] = '0';
	    $result['msg'] = $id;
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//템플릿 > 태그관리 > 순서변경
	public function category_sort(){
	    $seq = $this->input->post("seq"); //순번

	    for($row = 0; $row < count($seq); $row ++) {
	        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 템플릿 > 카테고리 순서변경 : ". $seq[$row]);
            //ib플래그
	        $sql = "UPDATE ".config_item('ib_category')." SET tc_seq='". ($row+1) ."' WHERE tc_id = '". $seq[$row] ."' ";
	        $this->db->query($sql);
	    }
	}

	public function template_confirm() {
	    $tpl_code = $this->input->post("tpl_code");
        //ib플래그 + 그룹
        if(config_item('ib_flag')=="Y"){
            $group_profile = "4e546c8c5b7a904a1c7926e8a8487f4c18cc377b";   //발신프로필키(현재 지니 그룹 프로필키)
            $group_profile2 = "01341cd3aee55b35e8d078049dfd2da31bd9c294";   //발신프로필키(하늘 그룹 프로필키) 화이트
            $group_profile3 = "d040e7437a574acada061c278733ab9f674d7b3d";
        }else{
            $group_profile = "c03b09ba2a86fc2984b940d462265dd4dbddb105";   //발신프로필키(현재 마트친구 그룹 프로필키)
            $group_profile2 = "0681d073d38f4124a5a34b2eab602f8a1e0509e9";   //발신프로필키(dhn공용 그룹 프로필키) 화이트
            $group_profile3 = "d040e7437a574acada061c278733ab9f674d7b3d";
        }

	    $msg_code = 200;
	    $message = "success";
	    $btn_cnt = 0;
	    $echo_str = "";
	    $template_type = "";

	    // 	    $sql = "
	    //             SELECT cwtd.tpl_id, cwtd.tpl_code, cwtd.tpl_name, cwtd.tpl_inspect_status, cwtd.tpl_use, cwtd.tpl_button, cwtd.tpl_adv_yn, cwtd.tpl_emphasizetype
	    //             FROM cb_wt_template_dhn cwtd
	    //             	INNER JOIN cb_wt_send_profile_dhn cwsp ON cwtd.tpl_profile_key = cwsp.spf_key AND cwsp.spf_use ='Y'
	    //             WHERE cwtd.tpl_profile_key = '".$group_profile."'
	    //             	AND cwtd.tpl_code = '".$tpl_code."'
	    //         ";

        //ib플래그
	    $sql = "
            SELECT cwtd.*
            FROM ".config_item('ib_template')." cwtd
            	INNER JOIN ".config_item('ib_profile')." cwsp ON cwtd.tpl_profile_key = cwsp.spf_key AND cwsp.spf_use ='Y'
            WHERE ( cwtd.tpl_profile_key = '".$group_profile."' OR cwtd.tpl_profile_key = '".$group_profile2."' OR cwtd.tpl_profile_key = '".$group_profile3."')
            	AND cwtd.tpl_code = '".$tpl_code."'
        ";

	    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);

	    //$tpl_preview = $this->db->query($sql)->result();
	    $tpl_query = $this->db->query($sql);
	    $tpl_prview = $tpl_query->result();

	    $temp_row = $tpl_query->row();
	    //$temp_row = $this->db->query($sql)->row();
	    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > teom_row : ".empty($temp_row));


	    if (empty($temp_row)) {
	        $msg_code = 101;   // 오류코드
	        $message = "템플릿이 존재하지 않습니다.";
	    } else if ($temp_row->tpl_inspect_status != 'APR') {   // 템플릿이 승인되지 않음.
	        $msg_code = 102;
	        $message = "템플릿이 승인되지 않았습니다.";
	    } else if ($temp_row->tpl_use == 'N') { // 템플릿 사용유무 사용안함.
	        $msg_code = 103;
	        $message = "템플릿을 사용할 수 없습니다.";
	    } else if ($temp_row->tpl_adv_yn == 'N') { // 광고형 알림톡 사용 유무 광고형 알림톡에서 사용안함
	        $msg_code = 104;
	        $message = "템플릿이 광고형 알림톡으로 설정되어 있지 않습니다.";
	    } else {   // 모든 조건이 만족할때

	        $msg_code = 200;
	        $message = "성공";
            //ib플래그
	        $sql = "
                SELECT GROUP_CONCAT(a.tci_category_id) category_ids
                FROM ".config_item('ib_categoryid')." a JOIN ".config_item('ib_category')." b ON a.tci_category_id = b.tc_id AND a.tci_type = b.tc_type
                WHERE a.tci_tem_id = ".$temp_row->tpl_id;
	        $category_ids = $this->db->query($sql)->row()->category_ids;

	        // 템플릿 카테고리 가져오기
            //ib플래그
	        if ($temp_row->tpl_emphasizetype == "IMAGE") {
	            $sql = "select *
                		FROM ".config_item('ib_category')."
                		WHERE tc_useyn = 'Y'
                		AND tc_type = 'I'";
	            $template_type = "I";
	        } else {
	            $sql = "select *
                		FROM ".config_item('ib_category')."
                		WHERE tc_useyn = 'Y'
                		AND tc_type = 'T'";
	            $template_type = "T";
	        }

	        $category_list = $this->db->query($sql)->result();

	        $btn_json = json_decode($temp_row->tpl_button, true);

	        $btn_cnt = count($btn_json);
	        if ($btn_cnt == 1) {
	            if($btn_json[0] == "") {
	                $btn_cnt = 0;
	            }
	        }
	        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > btn_cnt : ".$btn_cnt);
	    }

	    if ($msg_code == 200) {
	        $echo_str = '{"message":"'.$message.'", "code":"'.$msg_code.'", "tpl_id":"'.$temp_row->tpl_id.'", "tpl_code":"'.$temp_row->tpl_code.'", "tpl_name":"'.$temp_row->tpl_name.'", "tpl_emphasizetype":"'.$temp_row->tpl_emphasizetype.'", "template_type":"'.$template_type.'", "btn_cnt":"'.$btn_cnt.'", "category_ids":"'.$category_ids.'", "category_list" : '.json_encode($category_list).', "tpl_preview":'.json_encode($tpl_prview).'}';
	    } else {
	        $echo_str = '{"message":"'.$message.'", "code":'."$msg_code".'}';
	    }

	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > echo_str : ".$echo_str);

	    header('Content-Type: application/json');
	    echo $echo_str;
	}

	public function template_category_save() {
	    $tpl_id = $this->input->post("tpl_id"); // 템플릿 ID(tpl_id)
	    $tpl_case_type = $this->input->post("tpl_case_type");  // 템플릿 타입("T" : 텍스트형, "I" : 이미지형)
	    $tpl_btn_cnt = $this->input->post("tpl_btn_cnt");  // 템플릿 버턴 갯수
	    $category_ids = $this->input->post("category_ids"); //카테고리 배열

	    $msg_code = 200;
	    $msg_message = "success";

	    if (empty($tpl_id)) {
	        $msg_code = 101;
	        $msg_message = "템플릿 ID 값이 없습니다.";
	    } else if (empty($tpl_case_type)) {
	        $msg_code = 102;
	        $msg_message = "템플릿 타입 값이 없습니다.";
	    } else if ($tpl_btn_cnt < 0 && empty($tpl_btn_cnt)) {
	        $msg_code = 103;
	        $msg_message = "템플릿 버턴 갯수 값이 없습니다.";
	    } else if (empty($category_ids)) {
	        $msg_code = 104;
	        $msg_message = "카테고리 값이 없습니다.";
	    } else {   // 카테고리 변경하기
	        $sql = "SELECT COUNT(*) cnt FROM cb_wt_template_categoryid WHERE tci_tem_id=".$tpl_id;
	        $cnt = $this->db->query($sql)->row()->cnt;

	        if ($cnt > 0){ // 기존 등록된 카테고리 삭제
	            $del_sql = "DELETE FROM cb_wt_template_categoryid WHERE tci_tem_id=".$tpl_id;
	            $this->db->query($del_sql);
	        }

	        for($row = 0; $row < count($category_ids); $row ++) {
	            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 템플릿 > 카테고리 등록 : ". $category_ids[$row]);

	            //스마트전단 데이타 추가
	            $data = array();
	            $data["tci_tem_id"] = $tpl_id; //템플릿 ID
	            $data["tci_category_id"] = $category_ids[$row];    // 카테고리 ID
	            $data["tci_type"] = $tpl_case_type;    // 템플릿 타입("T" : 텍스트형, "I" : 이미지형)
	            $data["tci_btn_cnt"] = $tpl_btn_cnt; // 템플릿 버턴 갯수
	            $rtn = $this->db->replace("cb_wt_template_categoryid", $data); //데이타 추가
	        }
	    }

	    header('Content-Type: application/json');
	    echo '{"message":"'.$msg_message.'", "code":'."$msg_code".'}';
	}

	// 개별 휴면상태 해제
	public function dormant_unlock() {
	    $pf_key = json_decode($this->input->post('pf_key'))->pf_key;
	    $tmp_code = json_decode($this->input->post('tmp_code'))->tmp_code;
	    $mem_id = json_decode($this->input->post('mem_id'))->mem_id;
	    $partner_key = "51d3ab9bc25dceeee44c6f472fd16691c8b49d65";
	    $ok = 0;
	    $err_msg = "템플릿 휴면 해제에 실패하였습니다.";
	    $url = "https://bzm-center.kakao.com/api/v2/".$partner_key."/alimtalk/template/dormant/release";

	    // 테스트 카테고리 목록 조회
	    //$url = "https://bzm-center.kakao.com/api/v2/".$partner_key."/alimtalk/template/category/all";



	    log_message("ERROR","Template > ".$tmp_code." 휴면 해제 Start");
	    //log_message("ERROR",$partner_key);

	    if ($mem_id==1||$mem_id==0){
	        $data = array(
	            'senderKey' => $pf_key,
	            'senderKeyType' => 'G',
	            'templateCode' => $tmp_code
	        );
	    }else{
	        $data = array(
	            'senderKey' => $pf_key,
	            'senderKeyType' => 'S',
	            'templateCode' => $tmp_code
	        );
	    }

	    $result = $this->dhnkakao->template_dormant_uplock($data);
	    if($result->code!="200") {
	        log_message("ERROR", "실패 : ".$result->message);
	        echo '{"code":"'.$result->code.'", "message":"'.$err_msg.'"}';
	    }else{
	        // tpl_use 변경
	        //$sql = "update cb_wt_template_dhn set tpl_dormant = '0', tpl_reason = '', tpl_use = 'Y' where tpl_profile_key = '".$pf_key."' and tpl_code = '".$tmp_code."'";

	        // tpl_use 변경 x
	        $sql = "update cb_wt_template_dhn set tpl_dormant = '0', tpl_reason = '' where tpl_profile_key = '".$pf_key."' and tpl_code = '".$tmp_code."'";
	        $update_result = $this->db->query($sql);
	        echo '{"code":"'.$result->code.'"}';
	    }
	    log_message("ERROR","코드 : ".$result->code);


	    /*

	    $body = json_encode(data);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


	    $response = curl_exec($ch);
	    curl_close($ch);

	    $responseData = json_decode($response);
	    if($responseData->code=='200'){
	    log_message("ERROR","API 전송 테스트 확인");
	    }else{
	    log_message("ERROR","API전송실패");
	    log_message("ERROR",$responseData->code);
	    }
	    */


	    log_message("ERROR","Template > ".$tmp_code." 휴면 해제 처리 완료");

	}

	// 전체 휴면상태 해제
	public function dormant_all_unlock() {
	    //$sql = "select tpl_mem_id as mem_id, tpl_profile_key as pf_key, tpl_code as tmp_code from cb_wt_template_dhn where tpl_use = 'N' and tpl_dormant = '1'";
	    $sql = "select tpl_mem_id as mem_id, tpl_profile_key as pf_key, tpl_code as tmp_code from cb_wt_template_dhn where tpl_dormant = '1'";

	    $sql_result = $this->db->query($sql)->result();
	    $err_msg = "템플릿 휴면 해제에 실패하였습니다.";
	    $all = 0;
	    $success = 0;
	    $fail = 0;

	    log_message("ERROR","Template > 일괄 휴면 해제 Start");

	    foreach ($sql_result as $a){
	        $all++;
	        if ($a->mem_id==1||$a->mem_id==0){
	            $data = array(
	                'senderKey' => $a->pf_key,
	                'senderKeyType' => 'G',
	                'templateCode' => $a->tmp_code
	            );
	        }else{
	            $data = array(
	                'senderKey' => $a->pf_key,
	                'senderKeyType' => 'S',
	                'templateCode' => $a->tmp_code
	            );
	        }
	        $result = $this->dhnkakao->template_dormant_uplock($data);

	        if($result->code!="200") {
	            $fail++;
	            //log_message("ERROR", "실패 : ".$result->message);
	        }else{
	            // tpl_use 변경
	            //$sql = "update cb_wt_template_dhn set tpl_dormant = '0', tpl_reason = '', tpl_use = 'Y' where tpl_profile_key = '".$a->pf_key."' and tpl_code = '".$a->tmp_code."'";

	            // tpl_use 변경 x
	            $sql = "update cb_wt_template_dhn set tpl_dormant = '0', tpl_reason = '' where tpl_profile_key = '".$a->pf_key."' and tpl_code = '".$a->tmp_code."'";
	            $update_result = $this->db->query($sql);
	            $success++;
	        }
	    }

	    echo json_encode(array("all" => $all, "success" => $success, "fail" => $fail));

	    log_message("ERROR","Template > ".$all."개 일괄 휴면 해제 end > 성공 : ".$all.", 실패 : ".$fail);

	}

	// 사용여부 변경
	public function use_change(){
	    $pf_key = json_decode($this->input->post('pf_key'))->pf_key;
	    $tpl_code = json_decode($this->input->post('tpl_code'))->tpl_code;
	    $tpl_id = json_decode($this->input->post('tpl_id'))->tpl_id;

	    //log_message("ERROR","테스트 : ".$pf_key.", ".$tpl_code.", ".$tpl_id);

	    $sql = "update cb_wt_template_dhn set tpl_use = 'Y' where tpl_id = '".$tpl_id."' and tpl_profile_key = '".$pf_key."' and tpl_code = '".$tpl_code."'";
	    // 쿼리 실행
	    $sql_Result = $this->db->query($sql);

	    // 영향을 받은 행의 개수를 반환
	    $affected_rows = $this->db->affected_rows();

	    if ($affected_rows > 0) {
	        echo '{"result":"success"}';
	    } else {
	        echo '{"result":"fail"}';
	    }
	}

	// 상태이유 작성
	public function reason_input(){
	    $pf_key = json_decode($this->input->post('pf_key'))->pf_key;
	    $tpl_code = json_decode($this->input->post('tpl_code'))->tpl_code;
	    $tpl_id = json_decode($this->input->post('tpl_id'))->tpl_id;
	    $reason_message = json_decode($this->input->post('reason_message'))->reason_message;

	    //log_message("ERROR","테스트 : ".$pf_key.", ".$tpl_code.", ".$tpl_id.", ".$reason_message);


	    $sql = "update cb_wt_template_dhn set tpl_reason = '".$reason_message."' where tpl_id = '".$tpl_id."' and tpl_profile_key = '".$pf_key."' and tpl_code = '".$tpl_code."'";
	    // 쿼리 실행
	    $sql_Result = $this->db->query($sql);

	    // 영향을 받은 행의 개수를 반환
	    $affected_rows = $this->db->affected_rows();

	    if ($affected_rows > 0) {
	        echo '{"result":"success"}';
	    } else {
	        echo '{"result":"fail"}';
	    }

	}

	public function my_template_check(){
	    $mem_id = json_decode($this->input->post('mem_id'))->mem_id;
	    $change = 0;
	    $fail = 0;
	    $all = 0;

	    log_message("ERROR", "(".$mem_id.")의 템플릿 상태 갱신 > Start");
	    $sql = "select tpl_profile_key,tpl_code from cb_wt_template_dhn where tpl_delyn = 'N' and tpl_mem_id = '".$mem_id."'";

	    $sql_result = $this->db->query($sql)->result();

	    foreach ($sql_result as $a){

	        $all++;
	        if($mem_id==1 || $mem_id==0){
	            $data = array(
	                'senderKey' => $a->tpl_profile_key,
	                'senderKeyType' => 'G',
	                'templateCode' => $a->tpl_code
	            );
	        }else {
	            $data = array(
	                'senderKey' => $a->tpl_profile_key,
	                'senderKeyType' => 'S',
	                'templateCode' => $a->tpl_code
	            );
	        }

	        $result = $this->dhnkakao->template_dormant_uplock($data);

	        if($result->code=="508"){
	            // tpl_use 변경
	            //$sqlupdate = "update cb_wt_template_dhn set tpl_use='N', tpl_reason='(자동)삭제된 템플릿입니다.', tpl_delyn = 'Y' where tpl_mem_id='".$mem_id."' and tpl_code='".$a->tpl_code."' and tpl_profile_key='".$a->tpl_profile_key."'";

	            // tpl_use 변경 x
	            $sqlupdate = "update cb_wt_template_dhn set tpl_reason='(자동)삭제된 템플릿입니다.', tpl_delyn = 'Y' where tpl_mem_id='".$mem_id."' and tpl_code='".$a->tpl_code."' and tpl_profile_key='".$a->tpl_profile_key."'";
	            $update_result = $this->db->query($sqlupdate);
	            $change++;
	        }elseif($result->code=="200") {

	            if ($result->data->block) {
	                $block_value = '1';
	                $reason = '(자동)차단된 템플릿입니다.';
	            }else{
	                $block_value = '0';
	                $reason = '';
	            }

	            if ($result->data->dormant) {
	                $dormant_value = '1';
	                $reason = '(자동)휴면처리된 템플릿입니다.';
	            } else {
	                $dormant_value = '0';
	                $reason = '';
	            }

	            if($block_value==1 || $dormant_value==1){
	                // tpl_use 변경
	                /*
	                $use = 'N';
	                $sqlupdate = "update cb_wt_template_dhn set tpl_status='".$responseData->data->status."', tpl_block='".$block_value."', tpl_dormant='".$dormant_value."', tpl_use='".$use."', tpl_reason='".$reason."' where tpl_mem_id='".$mem_id."' and tpl_code='".$a->tpl_code."' and tpl_profile_key='".$a->tpl_profile_key."'";
	                */

	                // tpl_use 변경 x
	                $sqlupdate = "update cb_wt_template_dhn set tpl_status='".$responseData->data->status."', tpl_block='".$block_value."', tpl_dormant='".$dormant_value."', tpl_reason='".$reason."' where tpl_mem_id='".$mem_id."' and tpl_code='".$a->tpl_code."' and tpl_profile_key='".$a->tpl_profile_key."'";
	                $update_result = $this->db->query($sqlupdate);
	                $change++;
	            }

	        }else{
	            //log_message("ERROR",$result->code);
	            $fail++;
	        }

	    }

	    log_message("ERROR", "(".$mem_id.")의 템플릿 상태 갱신 > end > 전체 ( ".$all." ), 차단,휴면,삭제 변경 ( ".$change." )");
	    echo '{"result":"success"}';
	}

	public function template_record_data() {

	    log_message("ERROR","template_record_data start >>");

	    $view = array();
	    $view['view'] = array();
	    $view['perpage'] = 9;

	    $where = '1=1';

	    $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;

	    $view['total_rows'] = $this->Biz_dhn_model->get_public_template_record_count($where, $param);

	    $this->load->library('pagination');
	    $page_cfg['link_mode'] = 'search_template';
	    $page_cfg['base_url'] = '';
	    $page_cfg['total_rows'] = $view['total_rows'];
	    $page_cfg['per_page'] = $view['perpage'];
	    $this->pagination->initialize($page_cfg);
	    $this->pagination->cur_page = intval($view['param']['page']);
	    $view['page_html'] = $this->pagination->create_links();

	    $view['list'] = $this->Biz_dhn_model->get_public_template_record_list(($view['param']['page']-1), $view['perpage'], $where, $param);


	    $this->config->load('config');

	    $tmp_result_code = $this->config->item('tmp_result_code');
	    //log_message("ERROR","test : ".gettype($test));
	    //log_message("ERROR","test2 : ".gettype($test['200']));
	    //log_message("ERROR","test3 : ".$test['200']);
	    foreach ($view['list'] as $item) {
	        $item->tpl_reason = $tmp_result_code[$item->tpl_res_code];
	        //$item->tpl_reason = empty($tmp_result_code[$item->tpl_res_code])?"해당 업체가 없습니다.":$tmp_result_code[$item->tpl_res_code];
	    }

	    echo json_encode($view);

	    log_message("ERROR","template_record_data end >>");
	}
}
?>
