<?php
class Survey extends CB_Controller {
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
    
    function _remap($param) {
        $this->index($this->uri->segment(2));
    }
    
    
    public function index($survey_id = "") {
        
        if($survey_id != 'report') {
            $sql = "select * from cb_survey_m where short_url = '".$survey_id."'";
            
            //$q1 = $this->input->post("q1");
            //log_message("ERROR", "Q1 : ".$q1);
            
            //$this->load->library('dhnlurl');
            $view = array();
            //log_message("error", $sql);
            $view['info'] = $this->db->query($sql)->row();
            
            //log_message("error", $this->dhnlurl->get_short('http://www.bizalimtalk.kr/survey/'.$view['info']->short_url));
            
            $view['report'] = 'N';
            
            $timenow = date("Y-m-d");
            
            $str_now = strtotime($timenow);
            $str_start = strtotime($view['info']->start);
            $str_end = strtotime($view['info']->end);
            
            $view['enabled'] = 'Y';
            if($str_now < $str_start || $str_now > $str_end) {
                $view['enabled'] = 'N';
            } 
            
            $sql_q = "select * from cb_survey_q where svm_id = '".$view['info']->svm_id."'";
            $view['question'] = $this->db->query($sql_q)->result();
            
            foreach($view['question'] as $q) {
                $sql_a = "select * from cb_survey_a where que_id = '".$q->que_id."'";
                $view['answer'][$q->que_id] = $this->db->query($sql_a)->result();
            }
            
            $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
            $view['start_w'] = $week[ date('w'  , strtotime($view['info']->start))] ;
            $view['end_w'] = $week[ date('w'  , strtotime($view['info']->end))] ;
        } else {
            $result = json_decode( $this->input->post('result') );
            $sql = "insert into cb_survey_detail(svm_id, que_id, type, nvalue, tvalue) values(?, ?, ?, ?, ?)";
            foreach($result as $key => $value) {
                $r = explode('_', str_replace("q", "", $key));
                if($r[2] == '1' || $r[2] =='2') {
                    array_push($r, $value);
                } else {
                    array_push($r, null);
                }

                if($r[2] == '3') {
                    array_push($r, $value);
                }else {
                    array_push($r, null);
                }
                
                $this->db->query($sql, $r);
                log_message("ERROR", "DB : ".$this->db->error()['message']);
            }

            $survey_id = $this->input->post('svm_id');
            $sql = "select * from cb_survey_m where short_url = '".$survey_id."'";
            $view = array();
            $view['info'] = $this->db->query($sql)->row();
            $view['report'] = 'Y';
            
        }
        
       $this->load->view('biz/survey/bootstrap/survey',$view);
    }
    
    public function result($survey_id=1) {
        $result = array();
        
        $result['total_cnt'] = $this->db->query("select count(1) as cnt from cb_survey_detail where survey_id = '1'")->row()->cnt;
        
        $sql = "SELECT count(case when qn1 = 1 then 1 END) AS  q1_col1
            		  ,count(case when qn1 = 2 then 1 END) AS  q1_col2
            		  ,count(case when qn2 = 1 then 1 END) AS  q2_col1
            		  ,count(case when qn2 = 2 then 1 END) AS  q2_col2
            		  ,count(case when qn2 = 3 then 1 END) AS  q2_col3
            		  ,count(case when qn2 = 4 then 1 END) AS  q2_col4
            		  ,count(case when qn2 = 5 then 1 END) AS  q2_col5
            		  ,count(case when qn3 = 1 then 1 END) AS  q3_col1
            		  ,count(case when qn3 = 2 then 1 END) AS  q3_col2
            		  ,count(case when qn3 = 3 then 1 END) AS  q3_col3
            		  ,count(case when qn3 = 4 then 1 END) AS  q3_col4
            		  ,count(case when qn4 = 1 then 1 END) AS  q4_col1
            		  ,count(case when qn4 = 2 then 1 END) AS  q4_col2
            		  ,count(case when qn4 = 3 then 1 END) AS  q4_col3
            		  ,count(case when qn4 = 4 then 1 END) AS  q4_col4
            		  ,count(case when qn5 = 1 then 1 END) AS  q5_col1
            		  ,count(case when qn5 = 2 then 1 END) AS  q5_col2
            		  ,count(case when qn5 = 3 then 1 END) AS  q5_col3
            		  ,count(case when qn5 = 4 then 1 END) AS  q5_col4
            		  ,count(case when qn5 = 5 then 1 END) AS  q5_col5
            		  ,count(case when qn6 = 1 then 1 END) AS  q6_col1
            		  ,count(case when qn6 = 2 then 1 END) AS  q6_col2
            		  ,count(case when qn6 = 3 then 1 END) AS  q6_col3
            		  ,count(case when qn6 = 4 then 1 END) AS  q6_col4
            		  ,count(case when qn6 = 5 then 1 END) AS  q6_col5
            		  ,count(case when qn7 = 1 then 1 END) AS  q7_col1
            		  ,count(case when qn7 = 2 then 1 END) AS  q7_col2
            		  ,count(case when qn7 = 3 then 1 END) AS  q7_col3
            		  ,count(case when qn7 = 4 then 1 END) AS  q7_col4
            		  ,count(case when qn7 = 5 then 1 END) AS  q7_col5
            		  ,count(case when qn8 = 1 then 1 END) AS  q8_col1
            		  ,count(case when qn8 = 2 then 1 END) AS  q8_col2
            		  ,count(case when qn8 = 3 then 1 END) AS  q8_col3
            		  ,count(case when qn8 = 4 then 1 END) AS  q8_col4
            		  ,count(case when qn8 = 5 then 1 END) AS  q8_col5
            		  ,count(case when qn9 = 1 then 1 END) AS  q9_col1
            		  ,count(case when qn9 = 2 then 1 END) AS  q9_col2
            		  ,count(case when qn9 = 3 then 1 END) AS  q9_col3
            		  ,count(case when qn9 = 4 then 1 END) AS  q9_col4
            		  ,count(case when qn9 = 5 then 1 END) AS  q9_col5
            		  ,count(case when qn10 = 1 then 1 END) AS  q10_col1
            		  ,count(case when qn10 = 2 then 1 END) AS  q10_col2
            		  ,count(case when qn10 = 3 then 1 END) AS  q10_col3
            		  ,count(case when qn10 = 4 then 1 END) AS  q10_col4
                  FROM cb_survey_detail a
               WHERE a.survey_id = 1";
        $result['qn'] = $this->db->query($sql)->row();
        
        return $result;
    }

}
?>