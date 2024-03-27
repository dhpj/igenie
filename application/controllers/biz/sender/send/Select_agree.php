<?php
class Select_agree extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');
    
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');
    
    private $upload_path; //업로드 경로
	private $upload_max_size; //파일 제한 사이지
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));

		/* 이미지 등록될 기본 디렉토리 설정 */
		$this->upload_path =  config_item('uploads_dir') .'/agree/'; //업로드 경로

		/* 업로드할 용량지정 byte 단위 */
		$this->upload_max_size = 5 * 1024 * 1024; //파일 제한 사이지(byte 단위) (5MB)

		/* 기본 폴더가 없다면 생성을 위해 실행 */
		$this->create_upload_dir($this->upload_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->create_upload_dir($this->upload_path . date("Y") ."/");

		/* 월 폴더가 없다면 생성을 위해 실행 */
		$this->create_upload_dir($this->upload_path . date("Y") ."/". date("m") ."/");

		$this->upload_path = $this->upload_path . date("Y") ."/". date("m") ."/";
		//echo "upload_path : ". $this->upload_path;
    }

	//폴더가 없다면 생성
	public function create_upload_dir($dir = null){
		//값이 존재한다면 실행
		if(is_null($dir) == false || empty($dir) == false){
			//디렉토리 값이 없다면 실행 (폴더 생성)
			if (is_dir($dir) === false) {
				//log_message("error", "폴더 없음");
				@mkdir($dir, 0707);
				$file = $dir . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
		}
	}
    
    public function index()
    {
        $tmp_code = $this->input->post('tmp_code');
        $tmp_profile = $this->input->post('tmp_profile');
        $view = array();
        $view['view'] = array();
        if($tmp_profile && $tmp_code) {
            $sql = "select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id='".$tmp_code."' and tpl_profile_key='".$tmp_profile."' order by tpl_id desc limit 1";
            //log_message("ERROR", "SQL : ".$sql);
            $view['rs'] = $this->db->query($sql)->row();
        }

		//발신번호 조회
		$view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();

        $sms_callback = $view['rs']->spf_sms_callback;
		if(empty($sms_callback)) $sms_callback = $this->member->item("mem_phone");
        //log_message("ERROR", "SMS : ".$sms_callback);
        
        $view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['agree_url'] = base_url()."short_url/agree/";
        
		$sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;


		$view['upload_path'] = $this->upload_path; //업로드 경로
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이지
		//echo $_SERVER['REQUEST_URI'] ." > upload_path : ". $this->upload_path ."<br>";
		//echo $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size ."<br>";

        $this->load->view('biz/sender/send/select_agree',$view);
    }

	//개인정보수신동의 이미지 저장
	public function imgfile_save(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
		$agreeid = "";
		$imgpath = "";
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
			$img_data = $this->img_upload($this->upload_path, 'imgfile', $this->upload_max_size, "");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
            if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/'.$this->upload_path.$img_data['file_name']; //이미지 경로
				$data = array();
				$data["agi_mem_id"] = $this->member->item("mem_id"); //회원번호
				$data["agi_imgpath"] = $imgpath; //자세히 보러가기 이미지
				$rtn = $this->db->replace("cb_agree_info", $data); //데이타 추가
				$agreeid = $this->db->insert_id();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
			}
		}
		$result = array();
		$result['agreeid'] = $agreeid; //개인정보수신동의번호
		$result['imgpath'] = $imgpath; //이미지경로
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}
    
    //이미지 업로드 함수
	public function img_upload($upload_img_path = null, $field_name = null, $size = null, $thumb = "" ){
		//log_message("ERROR", "maker/img_upload Start");
		if( is_dir($upload_img_path)  == false ){
			//alert('해당 디렉도리가 존재 하지 않음'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 해당 디렉도리가 존재 하지 않음");
		}
		if ( is_null($field_name) ){
			//alert('필드값을 기입하지 않았습니다.'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
		}
		if( is_null($size) || is_numeric($size) == false || $size == ''){
			$size = 10 * 1024 * 1024; //파일 제한 사이지(byte 단위) (10MB)
		}
		if(is_array($_FILES) && empty($_FILES[$field_name]['name']) == false){
			$upload_img_config = '';
			$upload_img_config['upload_path'] = $upload_img_path;
			$upload_img_config['allowed_types'] = 'gif|jpg|png|jpeg';
			$upload_img_config['encrypt_name'] = true;
			$upload_img_config['max_size'] = $size;
			//$upload_img_config['max_width'] = 1200;
			//$upload_img_config['max_height'] = 1200;
			$this->load->library('upload',$upload_img_config);
			$this->upload->initialize($upload_img_config);
			if ($this->upload->do_upload($field_name)) {
				//업로드 성공
				$filedata = $this->upload->data();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata .", thumb : ". $thumb);
				if($thumb != ""){ //썸네일 추가
					$this->load->library('image_lib');
					$this->image_lib->clear();
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']);
					$simgconfig = array();
					$simgconfig['image_library'] = 'gd2';
					$simgconfig['source_image']	= $filedata['full_path'];
					$simgconfig['maintain_ratio'] = TRUE;
					$simgconfig['width']	= $thumb; //썸네일 가로 사이즈
					$simgconfig['height']= $thumb; //썸네일 가로 사이즈
					$simgconfig['new_image'] = $filedata['file_path'].$filedata['raw_name'].'_thumb'.$filedata['file_ext'];
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > new_image : ". $simgconfig['new_image']);
					$this->image_lib->initialize($simgconfig);
					if(!$this->image_lib->resize()) {
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지 저장 실패 : ". $this->upload->display_errors());
					}
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
				return $filedata;
			}else{
				//업로드 실패
				$error = $this->upload->display_errors();
				//alert($error, '/maker'); exit('');
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 실패");
			}
		} else {
			//alert('파일이 업로드 되지 않았습니다.'); exit('');
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 파일이 업로드 되지 않았습니다.");
		}
	}

	//개인정보수신동의 ID 추가
	public function ajax_agreeid_add(){
		$imgpath = $this->input->POST("imgpath");
		//log_message("ERROR", "/biz/sender/send/Select_agree.php > imgfile_add> imgpath : ". $imgpath);

		$data = array();
		$data["agi_mem_id"] = $this->member->item("mem_id"); //회원번호
		$data["agi_imgpath"] = $imgpath; //자세히 보러가기 이미지
		$rtn = $this->db->replace("cb_agree_info", $data); //데이타 추가
		$agreeid = $this->db->insert_id();

		$result = array();
		$result['agreeid'] = $agreeid; //개인정보수신동의번호
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

}
?>