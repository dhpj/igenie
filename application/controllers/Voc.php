<?php
class Voc extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */

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
        $this->load->library(array('querystring' ));
    }

    public function index() {
        if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }

        $view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;

        $view["total_rows"] = $this->db
            ->select("count(1) as cnt")
            ->from("cb_voc")
            ->get()->row()->cnt;

        $view["lists"] = $this->db
            ->select("*")
            ->from("cb_voc")
            ->order_by("v_id", "desc")
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

	    $layoutconfig = array(
			'path' => 'voc',
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

    public function write($id) {
        if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }

        if (!empty($id) && $this->member->item("mem_id") == "2"){
            $view["detail"] = $this->db
                ->select("*")
                ->from("cb_voc")
                ->where("v_id", $id)
                ->get()->row();
        }

	    $layoutconfig = array(
			'path' => 'voc',
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

    public function image_upload(){
        $uploadDirectory = 'uploads/voc/';

        // 업로드할 이미지 파일 처리
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tempFile = $_FILES['image']['tmp_name'];
            $targetFile = $uploadDirectory . base_convert(date("YmdHisu"), 10, 36) . "." . end(explode(".", basename($_FILES['image']['name'])));

            // 이미지 파일을 지정된 디렉토리로 이동
            move_uploaded_file($tempFile, $targetFile);

            // 이미지 파일의 URL을 반환
            echo json_encode(['url' => $targetFile]);
        } else {
            // 이미지 업로드 실패 시 오류 메시지 반환
            echo json_encode(['error' => 'Failed to upload image']);
        }
    }

    public function file_upload(){
        $uploadDirectory = 'uploads/voc/';
        //
        $fileName = uniqid() . "." . end(explode(".", basename($_FILES['file']['name'])));
        $fileTmpName = $_FILES['file']['tmp_name'];
        $targetFile = $uploadDirectory . $fileName;

        move_uploaded_file($fileTmpName, $targetFile);
        header('Content-Type: application/json');
        echo json_encode(array($_FILES['file']['name'], $fileName), JSON_UNESCAPED_UNICODE);
    }

    public function saved(){
        $name = $this->input->post("name");
        $tel = $this->input->post("tel");
        $email = $this->input->post("email");
        $title = $this->input->post("title");
        $detail = $this->input->post("detail");
        $filename0 = $this->input->post("filename0");
        $ufilename0 = $this->input->post("ufilename0");
        $filename1 = $this->input->post("filename1");
        $ufilename1 = $this->input->post("ufilename1");
        $this->db
            ->set("v_mem_id", $this->member->item("mem_id"))
            ->set("v_mem_userid", $this->member->item("mem_userid"))
            ->set("v_mem_username", $this->member->item("mem_username"))
            ->set("v_writer", $name)
            ->set("v_tel", $tel)
            ->set("v_email", $email)
            ->set("v_title", $title)
            ->set("v_detail", $detail);
        if (!empty($ufilename0)){
            $this->db
                ->set("v_filename0", $filename0)
                ->set("v_ufilename0", $ufilename0);
        }

        if (!empty($ufilename1)){
            $this->db
                ->set("v_filename1", $filename1)
                ->set("v_ufilename1", $ufilename1);
        }

        echo $this->db->insert("cb_voc");
    }

    public function saved_check(){
        $id = $this->input->post("id");
        $check_name = $this->input->post("check_name");
        $currentDate = date("Y-m-d H:i:s");

        echo $this->db
            ->set("v_check_flag" , 1)
            ->set("v_check_name", $check_name)
            ->set("v_check_date", $currentDate)
            ->where("v_id", $id)
            ->update("cb_voc");
    }
}
?>
