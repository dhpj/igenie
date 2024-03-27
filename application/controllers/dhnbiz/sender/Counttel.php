<?php
class Counttel extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');

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
    }

    //중복제거 개수
	public function load_count_tel_list(){
		$chkwherein  = $this->input->post('chkwherein'); //조건문(그룹)
        $mem_userid = $this->member->item("mem_userid");
        $fullchk = $this->input->post('fullchk'); //전체여부
        $result = array();

        if(!empty($chkwherein)){
            if($fullchk=='N'){
                $sql = "select count(*) as cnt from ( select a.ab_tel from cb_ab_".$mem_userid." a left join cb_ab_".$mem_userid."_group b ON b.aug_ab_id = a.ab_id WHERE b.aug_group_id IN (".$chkwherein.") GROUP by a.ab_tel ) t";
            }else{
                $sql = "select count(*) as cnt from cb_ab_".$mem_userid;
            }

            log_message("ERROR", "Query : ".$sql);
            $cnt = $this->db->query($sql)->row()->cnt;
            $result['code'] = '0';
    		$result['cnt'] = $cnt;
        }else{
            $result['code'] = '1';
    		$result['cnt'] = '0';
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;

	}

}
?>
