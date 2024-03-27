<?php
class Image_upload_rcs extends CB_Controller {
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
        $mem = $this->db->query("SELECT brd_brand FROM cb_wt_brand where brd_use='Y' and brd_mem_id=? and brd_status='승인' order by brd_appr_date asc limit 1", array($this->member->item('mem_id')))->row();
        $brand = $mem->brd_brand;
        if(!$brand) { echo '{"code":"error", "message", "발신 브랜드가 없습니다. "}'; exit; }
        $msg = "";
        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        if (isset($_FILES) && isset($_FILES['image_file']) && isset($_FILES['image_file']['name']) && isset($_FILES['image_file']['name'])) {
            $filecount = count($_FILES['image_file']['name']);
            $upload_path = config_item('uploads_dir') . '/rcs_images/';
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
                // if(config_item('ib_flag')=="Y"){
                //     $this->load->library('dhnkakao_ib');
                //     $swResult = $this->dhnkakao_ib->upload_image($filedata['file_path'], $filedata['file_type'], $filedata['file_name']);
                // }else{
                //     $this->load->library('dhnkakao');
                //     $swResult = $this->dhnkakao->upload_image($brand, $filedata['file_path'], $filedata['file_type'], $filedata['file_name']);
                // }

                //이미지코드 생성
    			$this->load->library('base62');
    			$pcode = $this->base62->encode(cdate('YmdHis'));
                $brandid = $brand.".".$pcode;

                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > brandid :". $brandid);

                $this->load->library('KTrcs');
                $rcsResult = $this->ktrcs->upload_image($brandid, $filedata['file_path'], $filedata['file_type'], $filedata['file_name']);

                if($rcsResult->status != "200" && !is_null($rcsResult->status)) {header('Content-Type: application/json'); echo '{"code":"error", "message":"'.$rcsResult->status.'-'.$rcsResult->error->code.'-'.$rcsResult->error->message.'"}'; exit; }
                $img_url = $rcsResult->data->fileInfo->url;

                $uploadfiledata ="";
                $uploadfiledata['rmi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['rmi_url'] = $img_url;
                // $uploadfiledata['rmi_in_url'] = element('file_path', $filedata);
                $uploadfiledata['rmi_originname'] = element('orig_name', $filedata);
                $uploadfiledata['rmi_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['rmi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['rmi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['rmi_type'] = str_replace('.', '', element('file_ext', $filedata));

                $uploadfiledata['rmi_fileid'] = $rcsResult->data->fileInfo->fileId;
                $uploadfiledata['rmi_status'] = $rcsResult->data->fileInfo->status;
                $uploadfiledata['rmi_exp_date'] = $rcsResult->data->fileInfo->expiryDate;
                $uploadfiledata['rmi_mem_id'] = $this->member->item("mem_id");
                // $uploadfiledata['img_is_image'] = element('is_image', $filedata) ? 1 : 0;
                // $uploadfiledata['img_wide'] = ($this->input->post("img_wide")) ? $this->input->post("img_wide") : "N"; //이미지 구분(N.친구톡, W.와이드친구톡, C.쿠폰)

                //if($msg=="" && $uploadfiledata['img_filesize'] > 512000) { $msg = "InvalidImageMaxLengthException"; }
                //if($msg=="" && $uploadfiledata['img_width'] < 500) { $msg = "InvalidImageSizeException"; }
                //if($msg=="" && $uploadfiledata['img_type']!="jpg" && $uploadfiledata['img_type']!="png") { $msg = "InvalidImageFormatException"; }
                //log_message('ERROR', 'MSG : ' . $msg);
                if($msg=="") {
                    // $this->Biz_model->make_user_image_table($this->member->item('mem_userid'));
                    $this->db->insert("cb_rcs_mms_img", $uploadfiledata);

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
