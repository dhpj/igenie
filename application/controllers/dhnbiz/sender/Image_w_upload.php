<?php
class Image_w_upload extends CB_Controller {
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
        //log_message("ERROR", "Image_w_upload Start");
        /* member의 대표 profileKey를 지정해야 스윗트렉커로 보낼수 있음 */
        $mem = $this->db->query("SELECT spf_key FROM cb_wt_send_profile_dhn where spf_use='Y' and spf_mem_id=? and spf_status='A' order by spf_datetime asc limit 1", array($this->member->item('mem_id')))->row();
        $pf_key = $mem->spf_key;
        if(!$pf_key) { echo '{"code":"error", "message", "발신 프로필이 없습니다. "}'; exit; }
        $msg = "";
        $file_error = '';
        $uploadfiledata = '';
        //log_message("ERROR", "Image_w_upload : ---------    0");
        $this->load->library('upload');
        //log_message("ERROR", "Image_w_upload : ---------    1");
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
 
            //log_message("ERROR", "Image_w_upload : 00000000    ".element('upload_file_max_size', $board) * 1024);
            
            $uploadconfig = '';
            $uploadconfig['upload_path'] = $upload_path;
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
            $uploadconfig['encrypt_name'] = true;
            //log_message("ERROR", "Image_w_upload upload_path : ".$uploadconfig['upload_path']);
            //log_message("ERROR", "Image_w_upload allowed_types : ".$uploadconfig['allowed_types']);
            //log_message("ERROR", "Image_w_upload max_size : ".$uploadconfig['max_size']);
            //log_message("ERROR", "Image_w_upload encrypt_name : ".$uploadconfig['encrypt_name']);

            //log_message("ERROR", "Image_w_upload tmp_name : ".$_FILES['userfile']['tmp_name']);
            
            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['image_file']['name'];
            $_FILES['userfile']['type'] = $_FILES['image_file']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['image_file']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['image_file']['error'];
            $_FILES['userfile']['size'] = $_FILES['image_file']['size'];
            
            //log_message("ERROR", "Image_w_upload name : ".$_FILES['userfile']['name']);
            //log_message("ERROR", "Image_w_upload type : ".$_FILES['userfile']['type']);
            //log_message("ERROR", "Image_w_upload tmp_name : ".$_FILES['userfile']['tmp_name']);
            //log_message("ERROR", "Image_w_upload error : ".$_FILES['userfile']['error']);
            //log_message("ERROR", "Image_w_upload size : ".$_FILES['userfile']['size']);
            
            
            //log_message("ERROR", "Image_w_upload : 22222222");
            if ($this->upload->do_upload()) {
                
                //log_message("ERROR", "Image_w_upload : 333333333");
                $this->load->library('image_lib');
                $this->image_lib->clear();
                $imgconfig = "";
                $filedata = $this->upload->data();
                $imgconfig['image_library'] = 'gd2';
                $imgconfig['source_image']	= $filedata['full_path'];
                $imgconfig['maintain_ratio'] = TRUE;
                $imgconfig['width']	= 800;
                $imgconfig['height']= 600;
                $imgconfig['new_image'] = $filedata['full_path'];
                
                $this->image_lib->initialize($imgconfig);
                $this->image_lib->resize();
                
                /* 스윗트렉커로 전송 : 먼저 전송하여 성공하면 처리 (쓰레기가 가더라도 관계없음) */
                $this->load->library('dhnkakao');
                $swResult = $this->dhnkakao->upload_wide_image($pf_key, $filedata['file_path'], $filedata['file_type'], $filedata['file_name']);
                
                if($swResult->code != "success" && !is_null($swResult->code)) {header('Content-Type: application/json'); echo '{"code":"error", "message":"'.$swResult->message.'"}'; exit; }
                
                $img_url = $swResult->data->img_url;
                
                $uploadfiledata ="";
                $uploadfiledata['img_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['img_url'] = $img_url;
                $uploadfiledata['img_originname'] = element('orig_name', $filedata);
                $uploadfiledata['img_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['img_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['img_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['img_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadfiledata['img_is_image'] = element('is_image', $filedata) ? 1 : 0;
                $uploadfiledata['img_wide'] = 'Y';
                
                //if($msg=="" && $uploadfiledata['img_filesize'] > 2097152) { $msg = "InvalidImageMaxLengthException"; }
                //if($msg=="" && $uploadfiledata['img_width'] < 500) { $msg = "InvalidImageSizeException"; }
                //if($msg=="" && $uploadfiledata['img_type']!="jpg" && $uploadfiledata['img_type']!="png") { $msg = "InvalidImageFormatException"; }
                log_message('ERROR', 'MSG : ' . $msg);
                if($msg=="") {
                    $this->Biz_model->make_user_image_table($this->member->item('mem_userid'));
                    $this->db->insert("img_".$this->member->item('mem_userid'), $uploadfiledata);
                    
                    if ($this->db->error()['code'] > 0) $msg = "UnknownException";
                }
            } else {
                $file_error = $this->upload->display_errors();
                //log_message("ERROR", "Image_w_upload file_error" .$file_error);
            }
            
        }
        
        header('Content-Type: application/json');
        if ($msg=="") {
            echo '{"code":"success"}';
        } else {
            echo '{"code":"error", "message", "'.$msg.'"}';
        }
    }
}
?>