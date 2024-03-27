<?php
class Get_short extends CI_Controller {
    /**
     * 모델을 로딩합니다
     */

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'url');


    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));
        $this->load->helper('url');

    }

    function _remap($param) {
        $postreq = json_decode(file_get_contents('php://input'));
        $this->index($postreq->long);
    }

    public function index($long_url) {

        //$sql = "select count(1) as cnt, max(short_url) as short_url from cb_url_short_url where url = '".$long_url."' and short_url is not null";
        //$db_result = $db->query($sql)->row();
        //log_message("error", "get short");
        $short_url = "";
        //if($db_result->cnt == 0) {
        $i = 0;

        while($i < 10) {
            $surl = array();
            $surl['url'] = $long_url;
            $db = $this->load->database("ppu", TRUE);

            if($db->insert('cb_url_short_url', $surl))
            {
                $id = $db->insert_id();

                if($id >= 999999) {
                  $str = '2'.sprintf("%06d", ($id % 999999) );
                } else {
                  $str = '2'.sprintf("%06d", $id);
                }

                $this->load->library('Base62');
                $short_url = $this->base62->encode($str);

                $db->query("delete from cb_url_short_url where short_url = '".$short_url."'");
                $db->query("update cb_url_short_url set short_url = '".$short_url."' where id = ".$id);

                $i = 11;
            } else {
                $i = $i + 1;
            }
        }
        //} else {
        //    $short_url = $db_result->short_url;
        //}

        header('Content-Type: application/json');
        echo '{"short_url": "dhnl.kr/'.$short_url.'", "origin_url":"'.$long_url.'"}';
    }
}
?>
