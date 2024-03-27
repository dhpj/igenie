<?php
class Short_url extends CB_Controller {
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
    
    //전용쿠폰 알림톡 당첨페이지
	public function coupon($shorturl) {
        $sql = "select * from cb_short_url where short_url = '$shorturl'";
        $result = $this->db->query($sql)->row();
        
        $view['url']=$result->url;
        $this->load->view('short_url',$view);
    }
    
    //개인정보동의 자세히 보러가기
	public function agree($shorturl) {
        $sql = "select * from cb_short_url where short_url = '$shorturl'";
        $result = $this->db->query($sql)->row();
        
        $view['url']=$result->url;
        $this->load->view('short_url',$view);
    }

}
?>