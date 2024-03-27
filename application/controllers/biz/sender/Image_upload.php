<?php
class Image_upload extends CB_Controller {
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
        //ib플래그
        $mem = $this->db->query("SELECT spf_key FROM ".config_item('ib_profile')." where spf_use='Y' and spf_mem_id=? and spf_status='A' order by spf_datetime asc limit 1", array($this->member->item('mem_id')))->row();
        $pf_key = $mem->spf_key;
        if(!$pf_key) { echo '{"code":"error", "message", "발신 프로필이 없습니다. "}'; exit; }
        $msg = "";
        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        if (isset($_FILES) && isset($_FILES['image_file']) && isset($_FILES['image_file']['name']) && isset($_FILES['image_file']['name'])) {
            $filecount = count($_FILES['image_file']['name']);
            $upload_path = config_item('uploads_dir') . '/user_images/';
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
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = ( 10 * 1024 );
            $uploadconfig['encrypt_name'] = true;

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['image_file']['name'];
            $_FILES['userfile']['type'] = $_FILES['image_file']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['image_file']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['image_file']['error'];
            $_FILES['userfile']['size'] = $_FILES['image_file']['size'];


            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');
                $this->image_lib->clear();
                $imgconfig = "";
                $filedata = $this->upload->data();
                $imgconfig['image_library'] = 'gd2';
                $imgconfig['source_image']	= $filedata['full_path'];
                $imgconfig['maintain_ratio'] = TRUE;
                $imgconfig['width']	= 720;
                $imgconfig['height']= 720;
                $imgconfig['new_image'] = $filedata['full_path'];

                $this->image_lib->initialize($imgconfig);
                $this->image_lib->resize();

                /* 스윗트렉커로 전송 : 먼저 전송하여 성공하면 처리 (쓰레기가 가더라도 관계없음) */
                //ib플래그
                if(config_item('ib_flag')=="Y"){
                    $this->load->library('dhnkakao_ib');
                    $swResult = $this->dhnkakao_ib->upload_image($filedata['file_path'], $filedata['file_type'], $filedata['file_name']);
                }else{
                    $this->load->library('dhnkakao');
                    $swResult = $this->dhnkakao->upload_image($pf_key, $filedata['file_path'], $filedata['file_type'], $filedata['file_name']);
                }

                if($swResult->code != "0000" && !is_null($swResult->code)) {header('Content-Type: application/json'); echo '{"code":"error", "message":"'.$swResult->code.'"}'; exit; }
                $img_url = $swResult->image;

                $uploadfiledata ="";
                $uploadfiledata['img_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['img_url'] = $img_url;
                $uploadfiledata['img_originname'] = element('orig_name', $filedata);
                $uploadfiledata['img_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['img_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['img_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['img_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadfiledata['img_is_image'] = element('is_image', $filedata) ? 1 : 0;
                $uploadfiledata['img_wide'] = ($this->input->post("img_wide")) ? $this->input->post("img_wide") : "N"; //이미지 구분(N.친구톡, W.와이드친구톡, C.쿠폰)

                //if($msg=="" && $uploadfiledata['img_filesize'] > 512000) { $msg = "InvalidImageMaxLengthException"; }
                //if($msg=="" && $uploadfiledata['img_width'] < 500) { $msg = "InvalidImageSizeException"; }
                //if($msg=="" && $uploadfiledata['img_type']!="jpg" && $uploadfiledata['img_type']!="png") { $msg = "InvalidImageFormatException"; }
                //log_message('ERROR', 'MSG : ' . $msg);
                if($msg=="") {
                    $this->Biz_model->make_user_image_table($this->member->item('mem_userid'));
                    $this->db->insert("img_".$this->member->item('mem_userid'), $uploadfiledata);

                    if ($this->db->error()['code'] > 0) $msg = "UnknownException";
                }
            } else {
                $file_error = $this->upload->display_errors();
            }

        }

        header('Content-Type: application/json');
        if ($msg=="") {
            echo '{"code":"success"}';
        } else {
            echo '{"code":"error", "message", "'.$msg.'"}';
        }
    }

    public function talkadvimg()
    {
        /* member의 대표 profileKey를 지정해야 스윗트렉커로 보낼수 있음 */
        $msg = "";
        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        //log_message("ERROR", "Img File Name :".$_FILES['image_file']);
        if (isset($_FILES) && isset($_FILES['image_file']) && isset($_FILES['image_file']['name']) && isset($_FILES['image_file']['name'])) {
            $filecount = count($_FILES['image_file']['name']);
            $upload_path = config_item('uploads_dir') . '/adv_images/';
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
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
            $uploadconfig['encrypt_name'] = true;

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['image_file']['name'];
            $_FILES['userfile']['type'] = $_FILES['image_file']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['image_file']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['image_file']['error'];
            $_FILES['userfile']['size'] = $_FILES['image_file']['size'];
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > do_upload :". $this->upload->do_upload());
            if ($this->upload->do_upload()) {

                $this->load->library('image_lib');
                $this->image_lib->clear();
                $imgconfig = "";
                $filedata = $this->upload->data();

                $this->image_lib->initialize($imgconfig);
                $msg = "http://". $_SERVER['HTTP_HOST'] ."/".$upload_path.$filedata['file_name'];
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > msg :". $msg);
            } else {
                //$msg = $this->upload->display_errors();
            }

        }
        header('Content-Type: application/json');
        if (!empty($msg)) {
            echo '{"code":"S", "imgurl":"'.$msg.'"}';
        } else {
            echo '{"code":"E", "message", "'.$msg.'"}';
        }
    }
}
?>
