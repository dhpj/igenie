<?php
class Swchk extends CB_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring' ));
    }
    
    public function index() {
        
        /* 메시지 전송 결과 */
        /* [{"msgid":""},{"msgid":""}] */
        //public function response($data)
        $this->load->library('sweettracker');
        $ok = $this->sweettracker->response('abc964f5f1e775d59f09254c58bf44328bd9605a', '[{"msgid":"622_109099"}]');
        print($ok);
    }
   
}
?>
