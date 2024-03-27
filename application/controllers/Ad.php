<?php
class Ad extends CB_Controller {
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

    public function _remap($idx) {
        $this->index($idx);
    }

    public function index($idx) {
        $sql = "select * from cb_short_url where short_url = '".$idx."'";
        $result = $this->db->query($sql)->row();
        redirect($result->url);

    }

    public function v($idx) {
        $sql = "select * from cb_mms_images where short_url = '".$idx."'";
        $result = $this->db->query($sql)->row();
        // log_message("ERROR", " : ".$sql);
        $view['file']=$result;
        $this->load->view('adview',$view);
    }

}
?>
