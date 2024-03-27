<?php
class Couponview extends CB_Controller {
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
        $this->load->library(array('querystring', 'google_url_api'));
    }
    
    public function index() {
        
        $idx = $this->uri->segment(3);
        $phn = $this->uri->segment(4);
        $iswin = "Y";

        $sql = "
		select
			cc.cc_idx
			,cc.cc_mem_id
			,cc.cc_tpl_id
			,cc.cc_tpl_code
			,cc.cc_msg as tpl_contents
			,cc.cc_button1
			,cc.cc_title
			,cc.cc_coupon_qty
			,cc.cc_start_date
			,cc.cc_end_date
			,(case when curdate() > cc.cc_end_date then 0 else 1 end) as isexpdate
			,cc.cc_img_url1  as cc_img_link
			,cc.cc_memo
			,cc.cc_rate
			,cc.cc_rate_txt
			,cc.cc_user_barcode
			,csp.spf_friend
		from cb_coupon cc left join cb_wt_send_profile_dhn csp on cc.cc_mem_id = csp.spf_mem_id and csp.spf_use = 'Y'
		where cc.cc_idx = '".$idx."'";
        //echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
        $c = $this->db->query($sql)->row();
        
        // 직원확인이 완료된 쿠폰인지 확인
        $check1 = "
		select count(1) as cnt, max(app_date) as app_date, max(coupon_no) as coupon_no 
		from cb_coupon_result ccr 
		where ccr.coupon_id = '".$idx."' 
		and phn = '".$phn."' 
		and app_date is not null " ;
        //echo $_SERVER['REQUEST_URI'] ." > check1 : ". $check1 ."<br>";
		$check1_ = $this->db->query($check1)->row();
		//echo "check1_->cnt : ". $check1_->cnt .", c->isexpdate : ". $c->isexpdate ."<br>";
        $coupon_list = array();
        $view = array();
        $view['rs']=$c;
        
        if($check1_->cnt > 0 || $c->isexpdate == 0) {
            // 직원확인 완료 되었다면..
            $view['check1'] = $check1_;
            $this->load->view('biz/coupon/couponview',$view);
            //header('content="width=device-width, user-scalable=no" ');
           // echo $html;
            return;
        } 
        
        // 쿠폰은 발급 되었지만 직원 확인이 안된것이 있는지 확인.
        $check2 = "select count(1) as cnt from cb_coupon_result ccr where ccr.coupon_id = '".$idx."' and phn = '".$phn."' and app_date is null " ;
        //echo $_SERVER['REQUEST_URI'] ." > check2 : ". $check2 ."<br>";
		$check2_ = $this->db->query($check2)->row();
		//echo "check2_->cnt : ". $check2_->cnt ."<br>";

        $cc_no = "";
        
        if($check2_->cnt == 0) {
            // 직원확인이 안된 Row 가 없다면 쿠폰이 발행 되지 않았음을 의미함.
            // 신규 발행
            
            // 확률 계산이 필요함.
            // 복잡한 계산로직을 제외하고 간단하게  1~ 100까지 숫자중 임의 숫자를 골라서
            // rate 보다 작으면 당첨으로 한다. 
            $rate = (int)($c->cc_rate == 0) ? $c->cc_rate_txt : $c->cc_rate;
            $rand_ = rand ( 1 , 100 );
            //log_message("ERROR","rate : ".$rate." / ".$rand_);
            if($rate < $rand_) {
                $view['rate_fail'] = 'Y';
            } else {
                //$this->db->trans_start();
                    $this->db->trans_begin();
                    $c_list = "select min(cl_idx) as cl_idx, count(1) as cnt from cb_coupon_list a where a.cl_cc_id = '$idx' and ( LENGTH(a.cl_phn) < 1 OR a.cl_phn IS NULL ) for update " ;
                    //echo $_SERVER['REQUEST_URI'] ." > c_list : ". $c_list ."<br>";
					$cl = $this->db->query($c_list)->row();
                    if($cl->cnt == 0) {
                        $view['check1'] = $check1_;
                        $view['remainempty'] = '0';
                        
                        $this->db->trans_rollback();
                        
                        $this->load->view('biz/coupon/couponview',$view);
                        return;
                    } else {
                        $c_ud = "update cb_coupon_list set cl_phn = '$phn', cl_updated_date = now() where cl_idx = '".$cl->cl_idx."'";
                        $this->db->query($c_ud);
                        
                        $c_list = "select * from cb_coupon_list a where a.cl_idx = '".$cl->cl_idx."'";
                        $coupon_list = $this->db->query($c_list)->row();
                    }
                    $cc_no = $coupon_list->cl_cc_no;
                    
                    $this->db->trans_commit();
            }
            
            $ins = array();
            $ins["coupon_id"] = $idx;
            $ins["phn"] = $phn;
            $ins["reg_date"] =  cdate('Y-m-d H:i:s');
            $ins["result"] = '1';
            
            if(!empty($cc_no)) {
                $ins["coupon_no"] = $cc_no;
            } else {
                $ins["app_date"] = cdate('Y-m-d H:i:s');
                $iswin = "Y";
            }
            
            $this->db->insert("coupon_result", $ins);
            
        } else {
            
            $c_list = "select * from cb_coupon_list a where a.cl_cc_id = '$idx' and a.cl_phn = '$phn'";
            $coupon_list = $this->db->query($c_list)->row();
            $cc_no = $coupon_list->cl_cc_no;
        }
        
        $view['idx'] = $idx;
        $view['phn'] = $phn;
        $view['cc_no'] = $cc_no;

        $this->load->view('biz/coupon/couponapp',$view);
    }
    
    public function approval() {
        
        $idx = $this->input->POST("idx");
        $phn = $this->input->POST("phn");
        $msg = "";
       // $this->db->trans_start();
            $sql = "select count(1) as cnt from cb_coupon_result ccr where ccr.coupon_id = '".$idx."' and ccr.phn = '".$phn."' and app_date is not null";
            $re = $this->db->query($sql)->row();
            
            if($re->cnt == 0) {
                $up = "update cb_coupon_result ccr set ccr.app_date = now() where ccr.coupon_id = '".$idx."' and ccr.phn = '".$phn."'";
                $this->db->query($up);
                $msg = "쿠폰이 정상적으로 사용 되었습니다.";
            } else {
                $msg = "이미 사용된 쿠폰입니다.";
            }
       // $this->db->trans_complete();
        header('Content-Type: application/json');
        echo '{"result": "'.$msg.'"}';
        //echo 'header("Content-type: application/x-javascript")';
        //echo 'window.location.href="/couponview/index/$idx/$phn/"';
        //header("Location:");
    }
}
?>