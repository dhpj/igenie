<?php
class Refund extends CB_Controller {
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

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$view['param']['search_company_nm'] = $this->input->post('search_company_nm');
		$view['param']['search_stat'] = $this->input->post('search_stat');
		$param = array();
		$where = " 1=1 ";
		if($view['param']['search_stat']!="all" && $view['param']['search_stat']) { $where.=" and ref_stat=? "; array_push($param, $view['param']['search_stat']); }
		if($view['param']['search_company_nm']) { $where.=" and mem_userid like ? "; array_push($param, '%'.$view['param']['search_company_nm'].'%'); }
		$view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id", $where, $param);
		$sql = "
		select count(*) as cnt
		from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id
		where ". $where;
		//echo $sql ."<br>";
		//$view['total_rows'] = $this->db->query($sql)->row()->cnt;
		//echo "total_rows : ". $view['total_rows'] ."<br>";

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select a.*, b.mem_username
			from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id
			where ". $where ." order by ref_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, $param)->result();

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
			'path' => 'biz/refund',
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

	public function remove()
	{
		$selRefund = $this->input->post('selRefund');
		if(count($selRefund) > 0) {
			foreach($selRefund as $id) {
				$rs = $this->db->query("select a.*, b.mem_userid from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id where a.ref_id=?", array($id))->row();
				if($rs && $rs->ref_stat=="-") {
					$this->db->query("update cb_wt_refund set ref_stat='N', ref_appr_id=?, ref_appr_datetime=?, ref_end_datetime=? where ref_id=?", array($this->member->item('mem_userid'), cdate('Y-m-d H:i:s'), cdate('Y-m-d H:i:s'), $id));
					if ($this->db->error()['code'] < 1) {
						$data = array();
						$data['amt_datetime'] = cdate('Y-m-d H:i:s');
						$data['amt_kind'] = "1";
						$data['amt_amount'] = $rs->ref_amount;
						$data['amt_memo'] = "환불취소";
						$data['amt_reason'] = $id;
						$this->db->insert("cb_amt_".$rs->mem_userid, $data);
					}
				}
			}
		}
		Header("Location: /biz/refund/lists");
	}

	public function appr()
	{
		$selRefund = $this->input->post('selRefund');
		if(count($selRefund) > 0) {
			foreach($selRefund as $id) {
				$rs = $this->db->query("select a.*, b.mem_userid from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id where a.ref_id=?", array($id))->row();
				if($rs && $rs->ref_stat=="-") {
					$this->db->query("update cb_wt_refund set ref_stat='Y', ref_appr_id=?, ref_appr_datetime=?, ref_end_datetime=? where ref_id=?", array($this->member->item('mem_userid'), cdate('Y-m-d H:i:s'), cdate('Y-m-d H:i:s'), $id));
				}
			}
		}
		Header("Location: /biz/refund/lists");
	}

    public function voucher_lists()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['perpage'] = 20;
        $param = array();
        $result = array();
		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['page'] = ($this->input->get('page')) ? $this->input->get('page') : 1;
		// $view['param']['search_company_nm'] = $this->input->post('search_company_nm');
		$view['search_flag'] = ($this->input->get('search_flag'))? $this->input->get('search_flag') : "ALL";
        $view['skeyword'] = $this->input->get('skeyword');

        $where = " where 1=1  and aa.mem_voucher_yn = 'Y' ";
        if($view['search_flag']=="Y"){
            $where .= " and kvd_proc_flag='Y' ";
        }else if($view['search_flag']=="N"){
            $where .= "and (kvd_remain_cash < 0 and kvd_proc_flag='N') ";
        }else{
            $where .= " and (kvd_remain_cash < 0 or kvd_proc_flag='Y') ";
        }

        if(!empty($view['skeyword'])){
            $where .= " and aa.mem_username like '%".$view['skeyword']."%' ";
        }

		// if($view['param']['search_stat']!="all" && $view['param']['search_stat']) { $where.=" and ref_stat=? "; array_push($param, $view['param']['search_stat']); }
		// if($view['param']['search_company_nm']) { $where.=" and mem_userid like ? "; array_push($param, '%'.$view['param']['search_company_nm'].'%'); }
		// $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id", $where, $param);
		$sql = "
		select count(1) as cnt
		from cb_member  aa left join cb_kvoucher_deposit bb ON aa.mem_id=bb.kvd_mem_id  ". $where;

		//echo $sql ."<br>";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;
		//echo "total_rows : ". $view['total_rows'] ."<br>";

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
        select aa.mem_id, aa.mem_userid, aa.mem_username, bb.kvd_remain_cash, bb.kvd_proc_flag, bb.kvd_minus_date, bb.kvd_fixed_date, d.mem_username as adminname
        from cb_member aa left join cb_kvoucher_deposit bb ON aa.mem_id=bb.kvd_mem_id LEFT JOIN cb_member_register c ON aa.mem_id = c.mem_id LEFT JOIN cb_member d ON c.mrg_recommend_mem_id = d.mem_id ". $where ." order by kvd_proc_flag asc, kvd_fixed_date desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        log_message("ERROR", "voucher > sql --------------- : ". $sql);
		$view['list'] = $this->db->query($sql, $param)->result();

        for($n=0;$n<count($view['list']);$n++) {
            $iid = $view['list'][$n]->mem_userid;
            $sql = "select sum(amt_amount*amt_deduct) as sumamt from cb_amt_".$iid." where FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9'";
            // log_message("ERROR", "voucher > amtplus --------------- : ". $sql);

            //echo $sql.'<br>';
            $amtplus = $this->db->query($sql)->row()->sumamt;

            $sql = "select sum(amt_amount*amt_deduct) as sumamt from cb_amt_".$iid." where FIND_IN_SET('조정', amt_memo)";
            // log_message("ERROR", "voucher > amtplus --------------- : ". $sql);

            //echo $sql.'<br>';
            $adjustplus = $this->db->query($sql)->row()->sumamt;
            $view['list'][$n]->voucher_deposit = $amtplus;
            $view['list'][$n]->adjust_deposit = $adjustplus;
        }

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
			'path' => 'biz/refund',
			'layout' => 'layout',
			'skin' => 'voucher_lists',
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


    public function voucher_adjust(){
		$mem_id  = $this->input->post('id'); //파트너 mem_id
        $deposit  = $this->input->post('minus_deposit'); //바우처잔액

        log_message("ERROR", "mem_id : ".$mem_id);
        log_message("ERROR", "deposit : ".$deposit);
        $result = array();

        if(!empty($mem_id)&&$deposit<0){

            // $sql = "select count(*) as cnt, html from cb_alimtalk_ums where idx = '".$save_msg_id."'";
            //
            //
            // // log_message("ERROR", "Query : ".$sql);
            // $editor_row = $this->db->query($sql)->row();
            // if($editor_row->cnt>0){
            //     $result['code'] = '0';
            //     $result['html'] = $editor_row->html;
            // }else{
            //     $result['code'] = '1';
            //     $result['html'] = '';
            // }

            $sql = "select aa.*, bb.mem_userid from cb_wt_msg_sent aa left join cb_member bb on aa.mst_mem_id = bb.mem_id where mst_sent_voucher = 'V' and mst_sent_platform = 'S' and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1";
            $voucher_msg_sent = $this->db->query($sql)->row();
            $userid = $voucher_msg_sent->mem_userid;
            $mst_id = $voucher_msg_sent->mst_id;
            $mst_at = $voucher_msg_sent->mst_at;
            $mst_ft = $voucher_msg_sent->mst_ft;
            $mst_ft_img = $voucher_msg_sent->mst_ft_img;
            $mst_at = $voucher_msg_sent->mst_at;
            $mst_smt = $voucher_msg_sent->mst_smt;
            $mst_rcs = $voucher_msg_sent->mst_rcs;
            $mst_type1 = $voucher_msg_sent->mst_type1;
            $mst_type2 = $voucher_msg_sent->mst_type2;
            $mst_type3 = $voucher_msg_sent->mst_type3;
            $mst_kind = $voucher_msg_sent->mst_kind;

            $mst_price_at = $voucher_msg_sent->mst_price_at;
            $mst_price_ft = $voucher_msg_sent->mst_price_ft;
            $mst_price_ft_img = $voucher_msg_sent->mst_price_ft_img;

            $mst_price_smt = $voucher_msg_sent->mst_smt_price;
            $mst_price_smt_sms = $voucher_msg_sent->mst_price_smt_sms;

            $mst_price_rcs = $voucher_msg_sent->mst_rcs_price;
            $mst_price_rcs_sms = $voucher_msg_sent->mst_price_rcs_sms;
            $mst_price_rcs_mms = $voucher_msg_sent->mst_price_rcs_mms;


            if($mst_at>0||$mst_ft>0||$mst_ft_img>0||$mst_at>0||$mst_smt>0||$mst_rcs>0){
                $sql = "select * from cb_wt_member_addon cwma left join cb_wt_voucher_addon cwva on cwma.mad_mem_id = cwva.vad_mem_id where cwma.mad_mem_id = '".$mem_id."'";
                $addon = $this->db->query($sql)->row();
                $ori_price_at = $addon->mad_price_at;
                $ori_price_ft = $addon->mad_price_ft;
                $ori_price_ft_img = $addon->mad_price_ft_img;
                $ori_price_smt = $addon->mad_price_smt;
                $ori_price_smt_sms = $addon->mad_price_smt_sms;

                $ori_price_rcs = $addon->mad_price_rcs;
                $ori_price_rcs_sms = $addon->mad_price_rcs_sms;
                $ori_price_rcs_mms = $addon->mad_price_rcs_mms;

                $v_price_at = $addon->vad_price_at;
                $v_price_ft = $addon->vad_price_ft;
                $v_price_ft_img = $addon->vad_price_ft_img;
                $v_price_smt = $addon->vad_price_smt;
                $v_price_smt_sms = $addon->vad_price_smt_sms;

                $v_price_rcs = $addon->vad_price_rcs;
                $v_price_rcs_sms = $addon->vad_price_rcs_sms;
                $v_price_rcs_mms = $addon->vad_price_rcs_mms;

                $send1_cnt=0;
                $send2_cnt=0;
                $send1_minus = 0.00;
                $send2_minus = 0.00;

                $sendsend_cnt = 0;
                $sendsend_minus = 0.00;
                $sendsend_price = 0.00;
                $sendsend_v_price = 0.00;
                $sendsend_kind = "";

                $amt_kind1 = "";
                $amt_kind2 = "";
                $amt_kind2_kind = "";
                $send1_price = 0.00;
                $send2_price = 0.00;

                $send1_v_price = 0.00;
                $send2_v_price = 0.00;


                switch ($mst_kind) {
                  case 'ai':
                  case 'at':
                      if(!empty($mst_type2)){
                          switch ($mst_type2) {
                            case 'wc':
                                $send2_minus = $mst_price_smt - $ori_price_smt;
                                $amt_kind2_kind = "L";
                                $send2_price = $ori_price_smt;
                                $send2_v_price = $v_price_smt;
                              break;
                            case 'wcs':
                                $send2_minus = $mst_price_smt_sms - $ori_price_smt_sms;
                                $amt_kind2_kind = "S";
                                $send2_price = $ori_price_smt_sms;
                                $send2_v_price = $v_price_smt_sms;
                              break;
                          }
                          $amt_kind2 = "P";
                          $send2_cnt = $mst_smt;
                      }
                      $send1_cnt = $mst_at;
                      $send1_minus = $mst_price_at - $ori_price_at;
                      if($mst_type1=="at"){
                          $amt_kind1 = "A";
                      }else{
                          $amt_kind1 = "E";
                      }

                      $send1_price = $ori_price_at;
                      $send1_v_price = $v_price_at;

                    break;
                  case 'ft':
                      if(!empty($mst_type2)){
                          switch ($mst_type2) {
                            case 'wc':
                                $send2_minus = $mst_price_smt - $ori_price_smt;
                                $amt_kind2_kind = "L";
                                $send2_price = $ori_price_smt;
                                $send2_v_price = $v_price_smt;
                              break;
                            case 'wcs':
                                $send2_minus = $mst_price_smt_sms - $ori_price_smt_sms;
                                $amt_kind2_kind = "S";
                                $send2_price = $ori_price_smt_sms;
                                $send2_v_price = $v_price_smt_sms;
                              break;
                          }
                          $amt_kind2 = "P";
                          $send2_cnt = $mst_smt;
                      }
                      switch ($mst_type1) {
                        case 'ft':
                            $send1_cnt = $mst_ft;
                            $send1_minus = $mst_price_ft - $ori_price_ft;
                            $amt_kind1 = "F";
                            $send1_price = $ori_price_ft;
                            $send1_v_price = $v_price_ft;
                          break;
                        case 'fti':
                            $send1_cnt = $mst_ft_img;
                            $send1_minus = $mst_price_ft_img - $ori_price_ft_img;
                            $amt_kind1 = "I";
                            $send1_price = $ori_price_ft_img;
                            $send1_v_price = $v_price_ft_img;
                          break;
                      }

                    break;
                  case 'MS':
                      switch ($mst_type2) {
                        case 'wc':
                            $send2_minus = $mst_price_smt - $ori_price_smt;
                            $amt_kind2_kind = "L";
                            $send2_price = $ori_price_smt;
                            $send2_v_price = $v_price_smt;
                          break;
                        case 'wcs':
                            $send2_minus = $mst_price_smt_sms - $ori_price_smt_sms;
                            $amt_kind2_kind = "S";
                            $send2_price = $ori_price_smt_sms;
                            $send2_v_price = $v_price_smt_sms;
                          break;
                      }
                      $send2_cnt = $mst_smt;
                      $amt_kind2 = "P";
                    break;

                    case 'rc':
                        if(!empty($mst_type3)){
                            switch ($mst_type3) {
                              case 'wc':
                                  $send2_minus = $mst_price_smt - $ori_price_smt;
                                  $amt_kind2_kind = "L";
                                  $send2_price = $ori_price_smt;
                                  $send2_v_price = $v_price_smt;
                                break;
                              case 'wcs':
                                  $send2_minus = $mst_price_smt_sms - $ori_price_smt_sms;
                                  $amt_kind2_kind = "S";
                                  $send2_price = $ori_price_smt_sms;
                                  $send2_v_price = $v_price_smt_sms;
                                break;
                            }
                            $amt_kind2 = "P";
                            $send2_cnt = $mst_smt;
                        }
                        switch ($mst_type2) {
                          case 'rc':
                              $send1_minus = $mst_price_rcs - $ori_price_rcs;
                              $amt_kind1_kind = "L";
                              $send1_price = $ori_price_rcs;
                              $send1_v_price = $v_price_rcs;
                            break;
                          case 'rcs':
                              $send1_minus = $mst_price_rcs_sms - $ori_price_rcs_sms;
                              $amt_kind1_kind = "S";
                              $send1_price = $ori_price_rcs_sms;
                              $send1_v_price = $v_price_rcs_sms;
                            break;
                         case 'rcm':
                            $send1_minus = $mst_price_rcs_sms - $ori_price_rcs_sms;
                            $amt_kind1_kind = "M";
                            $send1_price = $ori_price_rcs_sms;
                            $send1_v_price = $v_price_rcs_sms;
                          break;
                        }
                        $send1_cnt = $mst_rcs;
                        $amt_kind1 = "R";
                      break;
                }

                if(!empty($amt_kind2_kind)){
                    if($amt_kind2_kind == "L"){
                        $send2_price = $ori_price_smt;
                    }else{
                        $send2_price = $ori_price_smt_sms;
                    }
                }



                log_message("ERROR", "send1_cnt : ".$send1_cnt." / send1_minus : ".$send1_minus." / send2_cnt : ".$send2_cnt." / send2_minus : ".$send2_minus);


                $sendsend2nd_cnt = 0;
                $sendsend2nd_minus = 0.00;
                $sendsend2nd_price = 0.00;
                $sendsend2nd_v_price = 0.00;
                $sendsend2nd_kind = "";

                if($send1_cnt>0||$send2_cnt>0){
                    if($send1_cnt>0&&$send2_cnt>0){
                        if($send1_cnt>$send2_cnt){
                            $sendsend_cnt = $send1_cnt;
                            $sendsend_minus = $send1_minus;
                            $sendsend_price = $send1_price;
                            $sendsend_v_price = $send1_v_price;
                            $sendsend_kind = $amt_kind1;
                            $sendsend2nd_cnt = $send2_cnt;
                            $sendsend2nd_minus = $send2_minus;
                            $sendsend2nd_price = $send2_price;
                            $sendsend2nd_v_price = $send2_v_price;
                            $sendsend2nd_kind = $amt_kind2;
                        }else{
                            $sendsend_cnt = $send2_cnt;
                            $sendsend_minus = $send2_minus;
                            $sendsend_price = $send2_price;
                            $sendsend_v_price = $send2_v_price;
                            $sendsend_kind = $amt_kind2;
                            $sendsend2nd_cnt = $send1_cnt;
                            $sendsend2nd_minus = $send1_minus;
                            $sendsend2nd_price = $send1_price;
                            $sendsend2nd_v_price = $send1_v_price;
                            $sendsend2nd_kind = $amt_kind1;
                        }
                    }else if($send1_cnt>0){
                        $sendsend_cnt = $send1_cnt;
                        $sendsend_minus = $send1_minus;
                        $sendsend_kind = $amt_kind1;
                        $sendsend_price = $send1_price;
                        $sendsend_v_price = $send1_v_price;
                    }else if($send2_cnt>0){
                        $sendsend_cnt = $send2_cnt;
                        $sendsend_minus = $send2_minus;
                        $sendsend_kind = $amt_kind2;
                        $sendsend_price = $send2_price;
                        $sendsend_v_price = $send2_v_price;
                    }
                    $send_divide = floor(abs($deposit) / $sendsend_v_price);
                    $remain_deposit = abs($deposit + ($send_divide *$sendsend_v_price));

                    log_message("ERROR", "deposit : ".$deposit);

                    log_message("ERROR", "sendsend_cnt : ".$sendsend_cnt." / sendsend_minus : ".$sendsend_minus." / sendsend2nd_cnt : ".$sendsend2nd_cnt." / sendsend2nd_minus : ".$sendsend2nd_minus);

                    log_message("ERROR", "send_divide : ".$send_divide." / remain_deposit : ".$remain_deposit);

                    $all_divide = $send_divide;
                    if($remain_deposit>0){
                        $all_divide = $send_divide + 1;
                    }

                    log_message("ERROR", "all_divide : ".$all_divide." / amt_kind2_kind : ".$amt_kind2_kind);

                    $where_type = "";

                    switch ($sendsend_kind) {
                      case 'A':
                      case 'E':
                          $where_type = " ((message_type = 'at' or message_type = 'ai') and code='0000') ";
                        break;
                      case 'F':
                          $where_type = " (message_type = 'ft' and code='0000') ";
                        break;
                      case 'I':
                          $where_type = " (message_type = 'ft' and code='0000' and image_url <> '') ";
                        break;
                      case 'P':
                          $where_type = " (code='SMT' and message like '%성공') ";
                        break;
                      case 'R':
                          $where_type = " (code='RCS' and message like '%성공') ";
                        break;
                    }

                    $sql = "select * from (select * from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                        	(select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                        		select * from
                        		(
                        			(select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S' and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                        		)
                        	) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc limit 1) aaa ";

                    $sample_row = $this->db->query($sql)->row();

                    $arr_memo = explode(",", $sample_row->amt_memo);
                    $update_amt_memo = $arr_memo[0].",조정";

                    $update_amt_payback = $sample_row->amt_payback - $sendsend_minus;

                    $last_price = $sendsend_v_price - $remain_deposit;
                    log_message("ERROR", "update_amt_memo : ".$update_amt_memo." / update_amt_payback : ".$update_amt_payback);

                    if($sendsend_cnt >= $all_divide){ //전체개수가 루프 수보다 크거나 같을때


                        $sql = "update cb_amt_".$userid." set amt_amount = ".$sendsend_price.", amt_admin = ".$sendsend_price.", amt_memo = '".$update_amt_memo."', amt_payback=".$update_amt_payback."  where amt_kind = '".$sendsend_kind."' and amt_reason in
                                (select * from (select amt_reason from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                (select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                    select * from
                                    (
                                        (select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                    )
                                ) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc limit ".$send_divide.") as ttt )";

                        $this->db->query($sql);
                        log_message("ERROR", "분기1-1");
                        log_message("ERROR", "sql : ".$sql);

                        if($remain_deposit>0){

                            $sql = "update cb_amt_".$userid." set amt_amount = ".$last_price.", amt_admin = ".$last_price.", amt_payback=0  where amt_kind = '".$sendsend_kind."' and amt_reason in
                                	(select * from (select amt_reason from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                	(select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                		select * from
                                		(
                                			(select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                		)
                                	) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc limit 1) as ttt )";

                            $this->db->query($sql);
                            log_message("ERROR", "분기1-2");
                            log_message("ERROR", "sql : ".$sql);
                        }

                        $sql = "update cb_kvoucher_deposit set kvd_proc_flag = 'Y', kvd_fixed_date = now() where kvd_mem_id ='".$mem_id."'";
                        $this->db->query($sql);


                        log_message("ERROR", "sql : ".$sql);
                        $result['code'] = '0';
                        $result['msg'] = '처리되었습니다';

                    }else if($sendsend2nd_cnt>0){

                        log_message("ERROR", "분기2-1");

                        $sql = "select count(1) as cnt from (select amt_reason from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                (select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                    select * from
                                    (
                                        (select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                    )
                                ) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc) as ttt ";

                        $real_cnt = $this->db->query($sql)->row()->cnt;
                        if($real_cnt != $sendsend_cnt){
                            $sendsend_cnt = $real_cnt;
                        }

                        $sql = "update cb_amt_".$userid." set amt_amount = ".$sendsend_price.", amt_admin = ".$sendsend_price.", amt_memo = '".$update_amt_memo."', amt_payback=".$update_amt_payback."  where amt_kind = '".$sendsend_kind."' and  amt_reason in
                                (select * from (select amt_reason from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                (select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                    select * from
                                    (
                                        (select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                    )
                                ) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc) as ttt )";
                        $this->db->query($sql);
                        log_message("ERROR", "sql : ".$sql);
                        $deposit = $deposit + ($sendsend_cnt*$sendsend_v_price);

                        log_message("ERROR", "deposit : ".$deposit);

                        $send_divide = floor(abs($deposit) / $sendsend2nd_v_price);
                        $remain_deposit = abs($deposit + ($send_divide *$sendsend2nd_v_price));

                        $all_divide = $send_divide;
                        if($remain_deposit>0){
                            $all_divide = $send_divide + 1;
                        }

                        log_message("ERROR", "all_divide : ".$all_divide."send_divide : ".$send_divide."sendsend2nd_cnt : ".$sendsend2nd_cnt);

                        if($sendsend2nd_cnt >= $all_divide){

                                switch ($sendsend2nd_kind) {
                                  case 'A':
                                  case 'E':
                                      $where_type = " ((message_type = 'at' or message_type = 'ai') and code='0000') ";
                                    break;
                                  case 'F':
                                      $where_type = " (message_type = 'ft' and code='0000') ";
                                    break;
                                  case 'I':
                                      $where_type = " (message_type = 'ft' and code='0000' and image_url <> '') ";
                                    break;
                                  case 'P':
                                      $where_type = " (code='SMT' and message like '%성공') ";
                                    break;
                                  case 'R':
                                      $where_type = " (code='RCS' and message like '%성공') ";
                                    break;
                                }

                                $sql = "select * from (select * from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend2nd_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                    	(select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                    		select * from
                                    		(
                                    			(select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                    		)
                                    	) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc limit 1) aaa ";

                                $sample_row = $this->db->query($sql)->row();

                                $arr_memo = explode(",", $sample_row->amt_memo);
                                $update_amt_memo = $arr_memo[0].",조정";

                                $update_amt_payback = $sample_row->amt_payback - $sendsend2nd_minus;
                                $last_price = $sendsend2nd_v_price - $remain_deposit;

                                log_message("ERROR", "send_divide : ".$send_divide." / remain_deposit : ".$remain_deposit);




                                $sql = "update cb_amt_".$userid." set amt_amount = ".$sendsend2nd_price.", amt_admin = ".$sendsend2nd_price.", amt_memo = '".$update_amt_memo."', amt_payback=".$update_amt_payback."  where amt_kind = '".$sendsend2nd_kind."' and amt_reason in
                                        (select * from (select amt_reason from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend2nd_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                        (select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                            select * from
                                            (
                                                (select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                            )
                                        ) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc limit ".$send_divide.") as ttt )";
                                $this->db->query($sql);
                                log_message("ERROR", "sql : ".$sql);

                                log_message("ERROR", "분기2-2");

                                if($remain_deposit>0){

                                    $sql = "update cb_amt_".$userid." set amt_amount = ".$last_price.", amt_admin = ".$last_price.", amt_payback=0  where amt_kind = '".$sendsend2nd_kind."' and  amt_reason in
                                        	(select * from (select amt_reason from cb_amt_".$userid." aaa where FIND_IN_SET('바우처', aaa.amt_memo) and amt_kind = '".$sendsend2nd_kind."' and  SUBSTRING_INDEX(amt_reason, '/', 1) in
                                        	(select msgid from cb_msg_".$userid." where ".$where_type." and REMARK4 in (
                                        		select * from
                                        		(
                                        			(select mst_id from cb_wt_msg_sent where mst_sent_voucher = 'V' and mst_sent_platform = 'S'  and mst_mem_id = '".$mem_id."' order by mst_id desc limit 1) as tmp
                                        		)
                                        	) order by msgid desc ) order by aaa.amt_datetime, amt_reason desc limit 1) as ttt )";
                                    $this->db->query($sql);
                                    log_message("ERROR", "sql : ".$sql);

                                    log_message("ERROR", "분기2-3");
                                }

                                $sql = "update cb_kvoucher_deposit set kvd_proc_flag = 'Y', kvd_fixed_date = now() where kvd_mem_id ='".$mem_id."'";
                                $this->db->query($sql);

                                $result['code'] = '0';
                                $result['msg'] = '처리되었습니다';
                            }else{
                                $result['code'] = '1';
                                $result['msg'] = '처리중 오류가 있습니다';
                            }

                    }

                    log_message("ERROR", "sendsend_cnt : ".$sendsend_cnt." / sendsend_minus : ".$sendsend_minus);
                    log_message("ERROR", "deposit : ".$deposit." / send_divide : ".$send_divide." / remain_deposit : ".$remain_deposit);
                }



            }else{
                $result['code'] = '1';
                $result['msg'] = '처리중 오류가 있습니다';
            }





        }else{
            $result['code'] = '1';
            $result['msg'] = '처리중 오류가 있습니다';
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;

	}
}
?>
