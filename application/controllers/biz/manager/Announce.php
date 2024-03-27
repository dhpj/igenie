<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announce extends CB_Controller {
	private $testing = true;
	private $required_level = 150;
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board');

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
	private function get_children_include_indirect($id) {
		return array_map(function($res) { return $res->mem_id; }, $this->db->query("
			WITH RECURSIVE cte AS (
				SELECT mem_id, mrg_recommend_mem_id, 1 AS lv
				FROM cb_member_register
				WHERE mrg_recommend_mem_id = ".$id." AND mrg_recommend_mem_id != mem_id

				UNION ALL

				SELECT mr.mem_id, mr.mrg_recommend_mem_id, lv + 1 AS lv
				FROM cb_member_register mr
				INNER JOIN cte
				ON mr.mrg_recommend_mem_id = cte.mem_id
			)
			SELECT mem_id FROM cte ORDER BY mem_id, mrg_recommend_mem_id
		")->result());
	}
	public function index() {
		redirect('/biz/manager/announce/lists');
	}
	public function lists($an_id = -1) {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		if ($an_id != -1) {
			$this->item($an_id);
			return;
		}

    $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/announce',
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

		$my_id = $this->member->item("mem_id");
		$interested_ids = array_merge([$my_id], $this->get_children_include_indirect($my_id));
		$view['announces'] = $this->db->query("
			SELECT *, (SELECT mem_username FROM cb_member WHERE mem_id = an.an_author_id) AS author_name
			FROM cb_announce an
			WHERE an_discarded = FALSE
				AND an_author_id IN (
					SELECT m.mem_id FROM cb_member m
		      WHERE m.mem_id IN (".join(',', $interested_ids).")
					 	AND mem_level != 30
						AND mem_level <= m.mem_level
				)
			ORDER BY an_create_datetime DESC
		")->result();

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
	private function item($an_id) {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

    $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/announce',
			'layout' => 'layout',
			'skin' => 'item',
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

		$an = $this->db->query("SELECT * FROM cb_announce WHERE an_id = ".$an_id)->row();
		$view['an'] = $an;
		$has_read = $this->input->get('has_read') ?: 'all';
		$username = $this->input->get('username');
		$view['search_params'] = array(
			'has_read' => $has_read,
			'username' => $username
		);
		$view['states'] = empty($an->an_targets)
			? array()
			: $this->db->query("
					SELECT
						mem_id,
						mem_username,
						ans_read,
						ans_read_datetime
					FROM cb_member
					INNER JOIN cb_announce_state
					ON mem_id IN (".$an->an_targets.")
						AND ans_mem_id = mem_id
						AND ans_an_id = ".$an_id."
						".(
							$has_read !== 'all' ? "AND ans_read = ".$has_read : ""
						)."
						".(
							!empty($username) ? "AND mem_username LIKE '%".$username."%'" : ""
						)."
				")->result();

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
  public function write() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

    $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/announce',
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

    $my_id = $this->member->item('mem_id');
		$interested_ids = $this->get_children_include_indirect($my_id);
    $view['an_targets'] = $this->db->query("
      SELECT mem_id, mem_username, mem_level
      FROM cb_member
      WHERE mem_id IN (".join(',', $interested_ids).")
				AND mem_level != 30
				".($this->testing ? "" : "AND mem_id != 3")."
      ORDER BY mem_level DESC, mem_username
    ")->result();

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
  }
  public function edit($an_id) {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

    $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/announce',
			'layout' => 'layout',
			'skin' => 'edit',
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

		$view['an'] = $this->db->query("SELECT * FROM cb_announce WHERE an_id = ".$an_id)->row();

    $my_id = $this->member->item('mem_id');
		$interested_ids = $this->get_children_include_indirect($my_id);
    $view['an_targets'] = $this->db->query("
      SELECT mem_id, mem_username, mem_level
      FROM cb_member
      WHERE mem_id IN (".join(',', $interested_ids).")
				AND mem_level != 30
				".($this->testing ? "" : "AND mem_id != 3")."
      ORDER BY mem_level DESC, mem_username
    ")->result();

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
  }
  public function write_announce() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		$my_id = $this->member->item('mem_id');
		$title = $this->input->post('title');
    $content = $this->input->post('content');

    $this->db->query("
      INSERT INTO cb_announce (an_author_id, an_title, an_content, an_targets) VALUES (".$my_id.", '".$title."', '".$content."', '')
    ");

    echo $this->db->insert_id();
  }
	public function edit_announce() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		$id = $this->input->post('id');
		$title = $this->input->post('title');
		$content = $this->input->post('content');

		// an_targets를 비워준 뒤 append_announce_targets를 이용해 다시 채운다.
		$this->db->query("
			UPDATE cb_announce SET
				an_title = '".$title."',
				an_content = '".$content."',
				an_targets = ''
			WHERE an_id = ".$id."
		");
	}
	public function append_announce_targets() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		$id = $this->input->post('id');
		$ids = json_decode($this->input->post('ids'));

		$ids_str = join(',', $ids);
		$this->db->query("
			UPDATE cb_announce SET an_targets = CONCAT_WS(',', NULLIF(an_targets, ''), '".$ids_str."')
			WHERE an_id = ".$id."
		");

		header('Content-Type: application/json');
		echo '[]';
	}
	public function send_announce() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		$id = $this->input->post('id');

		$an = $this->db->query("SELECT * FROM cb_announce WHERE an_id = ".$id)->row();
		$targets = explode(',', $an->an_targets);

		$rows = join(',', array_map(function($target_id) use($id) { return "(".$id.", ".$target_id.")"; }, $targets));
		$this->db->query("INSERT INTO cb_announce_state (ans_an_id, ans_mem_id) VALUES ".$rows);
		$this->db->query("UPDATE cb_announce SET an_send_datetime = NOW() WHERE an_id = ".$id);
	}
  public function read_announce() {
    $id = $this->input->post('id');

    $this->db->query("
			UPDATE cb_announce_state SET ans_read = TRUE, ans_read_datetime = NOW()
			WHERE ans_an_id = ".$id." AND ans_mem_id = ".$this->member->item("mem_id")
		);

    header('Content-Type: application/json');
    echo '[]';
  }
	public function delete_announce() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		$id = $this->input->post('id');

		$this->db->query("
			UPDATE cb_announce SET an_discarded = TRUE
			WHERE an_id = ".$id
		);
		$this->db->query("
			UPDATE cb_announce_state SET ans_discarded = TRUE
			WHERE ans_an_id = ".$id." AND ans_read = FALSE
		");

		header('Content-Type: application/json');
		echo '[]';
	}
	public function get_targets() {
    if($this->member->item('mem_level') < $this->required_level){
      redirect('/home');
    }

		$id = $this->input->post('id');

		$target_ids_str = $this->db->query("SELECT an_targets FROM cb_announce WHERE an_id = ".$id)->row()->an_targets;
		if (empty($target_ids_str)) {
			header('Content-Type: application/json');
			echo '[]';
			return;
		}

		$targets = group_array(
			$this->db->query("
				SELECT mem_level, mem_username AS name
				FROM cb_member
				WHERE mem_id IN (".$target_ids_str.")
				ORDER BY mem_level DESC, mem_username
			")->result(),
			'mem_level'
		);

		header('Content-Type: application/json');
		echo json_encode($targets);
	}
}
function group_array($arr, $key) {
	$res = [];
	foreach ($arr as $elem) $res[$elem->$key][] = $elem;
	return $res;
}
?>
