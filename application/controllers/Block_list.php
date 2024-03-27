<?php
class Block_list extends CB_Controller {
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
        //$this->load->library(array('querystring' ));
    }

    public function add_block()
    {
        //$block_h = getallheaders();
        //$block_d = json_decode(file_get_contents('php://input'));
        log_message("ERROR", "080 호출 됨.");
        $block_d = array();
        $block_d['P_ID'] = $this->input->post('P_ID');
        $block_d['S_ID'] = $this->input->post('S_ID');
        $block_d['R_ID'] = $this->input->post('R_ID');
        $block_d['T_ID'] = $this->input->post('T_ID');
        $block_d['T_TIME'] = $this->input->post('T_TIME');
        $block_d['B_LEN'] = $this->input->post('B_LEN');
        $block_d['MENU_NAME'] = $this->input->post('MENU_NAME');

        $bl = array();
        $bl['sender'] = $this->input->post("DTMF_1") ;
        $bl['phn'] = $this->input->post("ANI");

        if(!empty( $bl['sender']) && !empty( $bl['phn']))
            $this->db->insert('block_lists', $bl);

        log_message("ERROR", "080 확인용 : ".$bl['sender']);

        //header('Content-Type: application/json');
        echo "<html><body><form name='outfrm' method='post'>";
        //echo '<input type="hidden" name="P_ID" value="1" />';
        //echo '<input type="hidden" name="S_ID" value="EXT  " />';
        //echo '<input type="hidden" name="R_ID" value="ARS  " />';
        echo "<input type='hidden' name='T_ID' value='".$block_d['T_ID']."' />";
        echo "<input type='hidden' name='T_TIME' value='".$block_d['T_TIME']."' />";
        //echo '<input type="hidden" name="B_LEN" value="0127" />";
        echo "<input type='hidden' name='RESULT' value='0' />";
        echo "<input type='hidden' name='MENU_NAME' value='".$block_d['MENU_NAME']."' />";
        echo "<input type='hidden' name='ACTION_TYPE' value='3' />";
        echo "<input type='hidden' name='NEXT_MENU' value='' />";
        echo "<input type='hidden' name='MENT_FLAG' value='0' />";
        echo "<input type='hidden' name='MENT_CNT' value='1' />";

        if ($this->db->error()['code'] == 0 && !empty($bl['phn']) && !empty($bl['sender'])) {
            echo "<input type='hidden' name='MENT_1' value='F_SUCCESS' />";
        } else {
            echo "<input type='hidden' name='MENT_1' value='F_FAIL' />";
        }
        //echo '<input type="text" name="DTMF_1" value="01036150442" />';
        //echo '<input type="text" name="ANI" value="0552802675" />';
        //echo '<input type="submit"   />';
        echo "</form></body></html>";

    }

    public function search_block($idx) {
        $sql = 'select * from cb_block_lists where idx > '.$idx;
        $list = $this->db->query($sql)->result_array();

        $json_str = json_encode($list);
        header('Content-Type: application/json');
        echo $json_str;
    }

    public function get_block_list() {
        //log_message("ERROR","get_block_list Call : Start");
        $sql = 'select ifnull(max(idx),0) as idx from cb_block_lists ';
        $idx = $this->db->query($sql)->row();
        // $url = 'http://api.kakaomarttalk.com/block_list/search_block/'.$idx->idx;
        $url = 'http://kakaomarttalk.com/block_list/search_block/'.$idx->idx;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)',
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 30
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        // log_message("ERROR",$_SERVER['REQUEST_URI'] ." > Call : ".$cinfo['http_code']);
        if ($cinfo['http_code'] == 200){
            $block_lists = json_decode( $buffer , true );
            //header('Content-Type: application/json');
            //var_dump($block_lists);
            foreach($block_lists as $r){
                $this->db->insert('block_lists', $r);
            }
        }
    }

}
?>
