<?php
class Myinfo extends CB_Controller {
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

	public function info()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['rs'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);

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
			'path' => 'biz/myinfo',
			'layout' => 'layout',
			'skin' => 'info',
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

	public function password()
	{
		$mb_passwd = $this->input->post('mb_passwd');
		header('Content-Type: application/json');
		echo '{"result": '.((password_verify($mb_passwd, $this->member->item('mem_password'))) ? 'true' : 'false').'}';
	}

	public function modify()
	{

		$mb_passwd = $this->input->post('mb_passwd');
		if(!password_verify($mb_passwd, $this->member->item('mem_password'))) { show_404(); exit; }

		$new_mb_passwd = $this->input->post('new_mb_passwd');
		//$bisness_file = $this->input->post('bisness_file');
		$admin_name = $this->input->post('admin_name');
		$admin_tel = $this->input->post('admin_tel');
		$emp_tel = $this->input->post('emp_tel');
		$admin_email = $this->input->post('admin_email');
		$phn_free = $this->input->post('phn_free');
		$mem_2nd_send = $this->input->post('mem_2nd_send');

		$data = array();
		if($new_mb_passwd) {
			if ( ! function_exists('password_hash')) { $this->load->helper('password'); }
			$data['mem_password'] = password_hash($new_mb_passwd, PASSWORD_BCRYPT);
		}
		$data['mem_nickname'] = $admin_name;
		$data['mem_email'] = $admin_email;
		$data['mem_phone'] = $admin_tel;
		$data['mem_emp_phone'] = $emp_tel;
		
		if($_SERVER['REMOTE_ADDR']==$this->config->config['developer_ip'] || $this->config->config['use_multi_agent'])
		{ $data['mem_2nd_send'] = $mem_2nd_send; }

		if(isset($_FILES)) {
			$this->load->library('upload');
			if ($this->cbconfig->item('use_member_photo')
				&& $this->cbconfig->item('member_photo_width') > 0
				&& $this->cbconfig->item('member_photo_height') > 0) {
				if (isset($_FILES['bisness_file']) && isset($_FILES['bisness_file']['name']) && $_FILES['bisness_file']['name']) {
					$upload_path = config_item('uploads_dir') . '/member_photo/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}
					$upload_path .= cdate('Y') . '/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}
					$upload_path .= cdate('m') . '/';
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
					$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
					$uploadconfig['max_size'] = '2000';
					$uploadconfig['max_width'] = '1000';
					$uploadconfig['max_height'] = '2000';
					$uploadconfig['encrypt_name'] = true;

					$this->upload->initialize($uploadconfig);

					if ($this->upload->do_upload('bisness_file')) {
						$img = $this->upload->data();
						$data['mem_photo'] = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
					} else {
						$file_error = $this->upload->display_errors();
					}
				}
			}
		}
		$this->db->update("cb_member", $data, array("mem_userid"=>$this->member->item('mem_userid')));
		if ($this->db->error()['code'] > 0) {
			echo "<script type='text/javascript'>alert('저장 오류입니다.');history.back();</script>";
		} else {
			$this->db->query("update cb_wt_member_addon set mad_free_hp=? where mad_mem_id=?", array($phn_free, $this->member->item('mem_id')));
			echo "<script type='text/javascript'>document.location.href='/biz/myinfo/info';</script>";
		}
	}

	public function my_price()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['rs'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);

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
			'path' => 'biz/myinfo',
			'layout' => 'layout',
			'skin' => 'my_price',
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

	public function charge()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

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
			'path' => 'biz/myinfo',
			'layout' => 'layout',
			'skin' => 'charge',
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

	public function charge_history()
	{
		$this->Biz_model->make_user_deposit_table($this->member->item('mem_userid'));
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['perpage'] = 20;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$view['param']['set_date'] = $this->input->post('set_date');
		$term = $this->Biz_model->set_Term($view['param']['set_date']);
		$view['param']['endDate'] = $term->endDate;
		$view['param']['startDate'] = $term->startDate;
		$param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
		$view['total_rows'] = $this->Biz_model->get_table_count("cb_amt_".$this->member->item('mem_userid'), "amt_kind<'9' and amt_datetime between ? and ?", $param);
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select *
			from cb_amt_".$this->member->item('mem_userid')."
			where amt_kind<'9' and amt_datetime between ? and ? order by amt_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
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
			'path' => 'biz/myinfo',
			'layout' => 'layout',
			'skin' => 'charge_history',
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

	public function refund()
	{
		if($this->input->post('refund_amount_req')) { $this->refund_save(); return; }
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

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
			'path' => 'biz/myinfo',
			'layout' => 'layout',
			'skin' => 'refund',
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

	public function refund_save()
	{
		if($this->member->item('mem_deposit') < $this->input->post('refund_amount_req')) { show_404(); exit; }
		if(!password_verify($this->input->post('refund_pw'), $this->member->item('mem_password'))) {
			header('Content-Type: application/json');
			echo '{"result":false,"message": "비밀번호가 일치하지 않습니다."}';
			exit;
		}
		/* 잔액에서 환불신청한 금액을 제외시켜야 함 */
		$this->ref_mem_id = $this->member->item('mem_id');
		$this->ref_datetime = cdate('Y-m-d H:i:s');
		$this->ref_amount = $this->input->post('refund_amount_req');
		$this->ref_appr_amount = $this->input->post('refund_amount');
		$this->ref_bank_name = $this->input->post('bank_nm');
		$this->ref_account = $this->input->post('bank_account');
		$this->ref_acc_master = $this->input->post('bank_depositor');
		$this->ref_memo = $this->input->post('refund_resn');
		$this->ref_tel = $this->input->post('contact');
		$this->ref_stat = "-";
		if(isset($_FILES)) {
			$this->load->library('upload');
			if ($this->cbconfig->item('use_member_photo')
				&& $this->cbconfig->item('member_photo_width') > 0
				&& $this->cbconfig->item('member_photo_height') > 0) {
				if (isset($_FILES['file_attach']) && isset($_FILES['file_attach']['name']) && $_FILES['file_attach']['name']) {
					$upload_path = config_item('uploads_dir') . '/bank_depositor/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}
					$upload_path .= cdate('Y') . '/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}
					$upload_path .= cdate('m') . '/';
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
					$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
					$uploadconfig['max_size'] = '2000';
					$uploadconfig['max_width'] = '1000';
					$uploadconfig['max_height'] = '1000';
					$uploadconfig['encrypt_name'] = true;

					$this->upload->initialize($uploadconfig);

					if ($this->upload->do_upload('file_attach')) {
						$img = $this->upload->data();
						$this->ref_sheet = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
					} else {
						$file_error = $this->upload->display_errors();

					}
				}
			}
		}
		$this->db->insert("cb_wt_refund", $this);
		header('Content-Type: application/json');
		if ($this->db->error()['code'] > 0) {
			echo '{"result":false,"message": "저장오류입니다.('.$this->db->error()['message'].')"}';
			exit;
		}
		$ref_id = $this->db->insert_id();
		$data = array();
		$data['amt_datetime'] = cdate('Y-m-d H:i:s');
		$data['amt_kind'] = "2";
		$data['amt_amount'] = $this->input->post('refund_amount_req');
		$data['amt_memo'] = "환불신청";
		$data['amt_reason'] = $ref_id;
		$this->db->insert("cb_amt_".$this->member->item('mem_userid'), $data);
		if ($this->db->error()['code'] > 0) {	// 오류시 환불 신청 취소
			$this->db->delete("cb_wt_refund", array("ref_id"=>$ref_id));
			if(file_exists(config_item('uploads_dir').'/bank_depositor/'.$this->ref_sheet)) {
				unlink(config_item('uploads_dir').'/bank_depositor/'.$this->ref_sheet);
			}
			echo '{"result":false,"message": "저장오류입니다.('.$this->db->error()['message'].')"}';
			exit;
		}
		echo '{"result":true,"message": null}';
	}

	public function use_history()
	{
		$this->Biz_model->make_user_deposit_table($this->member->item('mem_userid'));
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['perpage'] = 20;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$view['param']['set_date'] = $this->input->post('set_date');
		$term = $this->Biz_model->set_Term($view['param']['set_date']);
		$view['param']['endDate'] = $term->endDate;
		$view['param']['startDate'] = $term->startDate;
		$param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
		$view['total_rows'] = $this->Biz_model->get_table_count("cb_amt_".$this->member->item('mem_userid'), "amt_kind>='A' and amt_datetime between ? and ?", $param);
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select *
			from cb_amt_".$this->member->item('mem_userid')."
			where amt_kind>='A' and amt_datetime between ? and ? order by amt_datetime desc, amt_kind asc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		//log_message("ERROR", $sql);
		$view['list'] = $this->db->query($sql, $param)->result();
		$view['view']['canonical'] = site_url();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		$layoutconfig = array(
			'path' => 'biz/myinfo',
			'layout' => 'layout',
			'skin' => 'use_history',
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

	public function download_charge_history()
	{
		$value = json_decode($this->input->post('value'));

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:D1');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A3:D3');

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

		$this->excel->getActiveSheet()->mergeCells('A1:D1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '충 전 내 역');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '충전일시');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '충전금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '환불금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '비고');

		$row = 4;
		foreach($value as $val) {
			// 데이터를 읽어서 순차로 기록한다.
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->amt_datetime);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, ($val->amt_kind=="1") ? $val->amt_amount : "");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val->amt_kind=="3") ? $val->amt_amount : "");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->amt_memo);
			$row++;
		}

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A4:A'.$row);
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'B4:C'.$row);
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'D4:D'.$row);

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="charge_list.xls"');
		header('Cache-Control: max-age=0');

 

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');
	}

	public function download_use_history()
	{
		$value = json_decode($this->input->post('value'));

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:D1');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A3:D3');

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

		$this->excel->getActiveSheet()->mergeCells('A1:D1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '예 치 금 사 용 내 역');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '사용일시');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '사용내역');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '개수');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '가격');

		$row = 4;
		foreach($value as $val) {
			// 데이터를 읽어서 순차로 기록한다.
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->amt_datetime);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->amt_memo);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "1");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->amt_amount);
			$row++;
		}

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A4:C'.$row);
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'D4:D'.$row);

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="use_list.xls"');
		header('Cache-Control: max-age=0');

 

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');
	}
	
	//담당자 정보 수정
	public function manager_info_modify(){
	    $mem_userid = $this->member->item('mem_userid'); //회원 ID
	    $nickname = $this->input->post('nickname'); //담당자 이름
	    $phoneNum = $this->input->post('phoneNum'); //담당자 연락처
	    //log_Message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_userid : ". $mem_userid .", nickname : ". $nickname .", phoneNum : ". $phoneNum);
	    
	    $data = array();
	    $data['mem_nickname'] = $nickname; //담당자 이름
	    $data['mem_emp_phone'] = $phoneNum; //담당자 연락처
	    $this->db->update("cb_member", $data, array("mem_userid"=>$mem_userid));
	    
	    header('Content-Type: application/json');
	    if ($this->db->error()['code'] > 0) {
	        echo '{"message": "저장 오류입니다.", "code": "fail"}';
	    }else{
	        echo '{"message": "success", "code":"success"}';
	    }
	    return;
	}

}