<?php
class Qrcom extends CB_Controller {
  private $page_name = 'qrcom';
  private $required_level = 17;

  /**
   * 헬퍼를 로딩합니다
   */
  protected $helpers = array('form', 'array');

  function __construct() {
    parent::__construct();

    /**
     * 라이브러리를 로딩합니다
     */
    $this->load->library(array('querystring'));
  }

  public function index() {
    redirect('/'.$this->page_name.'/lists');
  }
  public function lists() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

    $view = array();
    $view['view'] = array();
    //echo $this->member->item('mem_id');
    $param = array();

    $page_unit = $this->input->get('page_unit') ?: 25;
    $page = $this->input->get('page') ?: 1;

    $view['page'] = $page;

    $type = $this->input->get('type');
    $input = $this->input->get('input');
		$view['search_params'] = array(
			'type' => $type,
			'input' => $input
		);
    log_message('error', json_encode($view['search_params'], JSON_UNESCAPED_UNICODE));

    $qc = $this->db->query("
      SELECT * FROM cb_qrcom WHERE qc_mem_id = ".$this->member->item('mem_id')."
    ")->row();
    $view['qc'] = $qc;
    if (!empty($qc)) {
      $view['inputs'] = $this->db->query("
        SELECT * FROM cb_qrcom_input
        WHERE qci_qc_id = ".$qc->qc_id."
          AND qci_discarded = FALSE
          ".(!empty($type) ? "
            AND JSON_VALUE(qci_data, '$.".$type."') LIKE '%".$input."%'"
            : ""
          )."
        ORDER BY qci_id DESC
        LIMIT ".(($page - 1) * $page_unit).", ".$page_unit."
      ")->result();
      $view['total_count'] = $this->db->query("
        SELECT COUNT(*) as total_count FROM cb_qrcom_input
        WHERE qci_qc_id = ".$qc->qc_id."
          AND qci_discarded = FALSE
          ".(!empty($type) ? "
            AND JSON_VALUE(qci_data, '$.".$type."') LIKE '%".$input."%'"
            : ""
          )."
      ")->row()->total_count;
    }
    // $view['total_rows'] = $rs->total_rows;
    $view['view']['canonical'] = site_url();

    $this->load->library('pagination');
    $page_cfg['link_mode'] = 'open_page';
    $page_cfg['base_url'] = $page;
    $page_cfg['total_rows'] = $view['total_count'];
    $page_cfg['per_page'] = $page_unit;
    $this->pagination->initialize($page_cfg);
    $this->pagination->cur_page = intval($page);

    $view['page_html'] = $this->pagination->create_links();

    /**
     * 레이아웃을 정의합니다
     */
    $page_title = $this->cbconfig->item('site_meta_title_main');
    $meta_description = $this->cbconfig->item('site_meta_description_main');
    $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
    $meta_author = $this->cbconfig->item('site_meta_author_main');
    $page_name = $this->cbconfig->item('site_page_name_main');

    $layoutconfig = array(
      'path' => $this->page_name,
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
  public function write_form() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

    $view['qc'] = $this->db->query("
      SELECT * FROM cb_qrcom WHERE qc_mem_id = ".$this->member->item('mem_id')."
    ")->row();

    $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => $this->page_name,
			'layout' => 'layout',
			'skin' => 'write_form',
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
  public function api_write_form() {
    if($this->member->item('mem_level') < $this->required_level){
      http_response_code(403);
      return;
    }

    $fields = $this->input->post('fields');

    echo "
      INSERT INTO cb_qrcom (qc_mem_id, qc_form)
      VALUES (
        ".$this->member->item('mem_id').",
        '".$fields."'
      )
      ON DUPLICATE KEY UPDATE
        qc_form = '".$fields."'";
    $this->db->query("
      INSERT INTO cb_qrcom (qc_mem_id, qc_form)
      VALUES (
        ".$this->member->item('mem_id').",
        '".$fields."'
      )
      ON DUPLICATE KEY UPDATE
        qc_form = '".$fields."'
    ");
  }
  public function qr($id = -1) {
    if ($id === -1) {
      if($this->member->item('mem_level') < $this->required_level){
        redirect('/home');
      }

  		$layoutconfig = array(
  			'path' => $this->page_name,
  			'layout' => 'layout',
  			'skin' => 'qr',
            'layout_dir' => $layout_dir,
			'mobile_layout_dir' => $mobile_layout_dir,
			'use_sidebar' => $use_sidebar,
			'use_mobile_sidebar' => $use_mobile_sidebar,
			'skin_dir' => $skin_dir,
			'mobile_skin_dir' => $mobile_skin_dir,
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
  		);
		$view['layout'] = $this->managelayout->front($layoutconfig, 'mobile');
		$this->data = $view;
		// $this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
        return;
    } else {
      $view['id'] = $id;
      $view['qc'] = $this->db->query("
        SELECT * FROM cb_qrcom WHERE qc_mem_id = ".$id."
      ")->row();

      $layoutconfig = array(
  			'path' => $this->page_name,
  			'layout' => 'layout',
  			'skin' => 'input',
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
    }

        $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->view = element('view_skin_file', element('layout', $view));
  }
  public function api_write_input() {
    $id = $this->input->post('id');
    $data = json_decode($this->input->post('data'));

    echo $id.'/'.json_encode($data);
    $this->db->query("
      INSERT INTO cb_qrcom_input (qci_qc_id, qci_data)
      VALUES (".$id.", '".json_encode($data, JSON_UNESCAPED_UNICODE)."')
    ");
  }
  public function api_remove_input() {
    if($this->member->item('mem_level') < $this->required_level){
      http_response_code(403);
      return;
    }

    $id = $this->input->post('id');
    $this->db->query("
      UPDATE cb_qrcom_input SET qci_discarded = TRUE WHERE qci_id = ".$id."
    ");
  }
}
?>
