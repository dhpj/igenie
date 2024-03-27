<?php
class Image_delete extends CB_Controller {
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
		/* member의 대표 profileKey를 지정해야 스윗트렉커로 보낼수 있음 */
		$mem = $this->db->query("SELECT spf_key FROM cb_wt_send_profile_dhn where spf_use='Y' and spf_mem_id=? order by spf_datetime asc limit 1", array($this->member->item('mem_id')))->row();
		$pf_key = $mem->spf_key;
		/* 스윗트렉커로 전송 : 먼저 전송하여 성공하면 처리 (쓰레기가 가더라도 관계없음) */
		$this->load->library('sweettracker');

		$ok = 0; $err = 0; $err_msg="";
		$image_name = json_decode($this->input->post('image_name'));
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 삭제 > post('image_name') : ". $this->input->post('image_name'));
		$count = $this->input->post('count');
		for($n=0;$n<$count;$n++) {
			$img = explode('|', urldecode($image_name->image_name[$n]));
			if(count($img) == 2) {
				$img_info = $this->db->query("SELECT * FROM cb_img_".$this->member->item('mem_userid')." where img_id=?", array($img[1]))->row();
				if($img) {
					$swResult = $this->sweettracker->delete_image($pf_key, $img_info->img_originname);
					if($swResult->code != "success") {
						if(!$err_msg) { $err_msg = $swResult->message; }
						$err++; }
				}
				if(file_exists(config_item('uploads_dir').'/user_images/'.$img[0])) {
					unlink(config_item('uploads_dir').'/user_images/'.$img[0]);
					$this->db->delete("img_".$this->member->item('mem_userid'), array("img_filename"=>$img[0], "img_id"=>$img[1]));
					$ok++;
				}
			}
		}
		header('Content-Type: application/json');
		echo '{"success":'.$ok.', "fail":'.($count-$ok).', "message":"'.$err_msg.'"}';
	}
}
?>