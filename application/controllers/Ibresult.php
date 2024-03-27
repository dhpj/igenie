<?php
class Ibresult extends CB_Controller {
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
        //$this->load->library(array('depositlib', 'alimtalk'));
    }
    

    public function proc() {
        
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));
        //$debug = file_get_contents('php://input');
        
        $messageId = $kakao_d->messageId;
        $resultCode = $kakao_d->resultCode;
        $reportTime = $kakao_d->reportTime;
        $errorText = $kakao_d->errorText; 
        
        log_message("error", "MESSAGE ID : ".$messageId."/".$resultCode."/".$errorText);
        
        if($resultCode == '3005') {
            $resultCode = '0000';
            $errorText = '';
        }
        
        $this->db->query("insert into IB_RESULT(message_id, result_code, report_time, error_text) values('".$messageId."', '".$resultCode."', '".$reportTime."', '".$errorText."')");
        
        
      /*  
        $r = $this->db->query("select * from DHN_REQUEST_RESULT_TEMP where remark3 = '".$messageId."'")->row();
        
        if(!empty($r)) {
            
            $result = array();
            $result["msgid"] = $r->msgid;
            $result["ad_flag"] = $r->ad_flag;
            $result["button1"] = $r->button1;
            $result["button2"] = $r->button2;
            $result["button3"] = $r->button3;
            $result["button4"] = $r->button4;
            $result["button5"] = $r->button5;
            $result["code"] = $resultCode;
            $result["image_link"] = $r->image_link;
            $result["image_url"] = $r->image_url;
            $result["kind"] = $r->kind;
            $result["message"] = $errorText;
            $result["message_type"] = $r->message_type;
            $result["msg"] = $r->msg;
            $result["msg_sms"] = $r->msg_sms;
            $result["only_sms"] = $r->only_sms;
            $result["p_com"] = $r->p_com;
            $result["p_invoice"] = $r->p_invoice;
            $result["phn"] = $r->phn;
            $result["profile"] = $r->profile;
            $result["reg_dt"] = $r->reg_dt;
            $result["remark1"] = $r->remark1;
            $result["remark2"] = $r->remark2;
            $result["remark3"] = null;
            $result["remark4"] = $r->remark4;
            $result["remark5"] = $r->remark5;
            $result["res_dt"] = $reportTime;
            $result["reserve_dt"] = $r->reserve_dt;
            
            if($resultCode == "A000") {
                $result["result"] = "Y";
            } else {
                $result["result"] = "N";
            }
            
            $result["s_code"] = $r->s_code;
            $result["sms_kind"] = $r->sms_kind;
            $result["sms_lms_tit"] = $r->sms_lms_tit;
            $result["sms_sender"] = $r->sms_sender;
            $result["sync"] = $r->sync;
            $result["tmpl_id"] = $r->tmpl_id;
            $result["wide"]  = $r->wide;
            $result["supplement"] = $r->supplement;
            $result["price"] = $r->price;
            $result["currency_type"] = $r->currency_type;
            $result["title"] = $r->title;
            
            $sql = "insert ignore into DHN_REQUEST_RESULT(msgid
    ,ad_flag
    ,button1
    ,button2
    ,button3
    ,button4
    ,button5
    ,code
    ,image_link
    ,image_url
    ,kind
    ,message
    ,message_type
    ,msg
    ,msg_sms
    ,only_sms
    ,p_com
    ,p_invoice
    ,phn
    ,profile
    ,reg_dt
    ,remark1
    ,remark2
    ,remark3
    ,remark4
    ,remark5
    ,res_dt
    ,reserve_dt
    ,result
    ,s_code
    ,sms_kind
    ,sms_lms_tit
    ,sms_sender
    ,sync
    ,tmpl_id
    ,wide
    ,supplement
    ,price
    ,currency_type
    ,title
    ) values(?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?)";
            
            $this->db->query($sql, $result);
        }
    }
 
    public function proc() {
        
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));
        //$debug = file_get_contents('php://input');
        //log_message("ERROR", " D : ".$debug."/".$kakao_d->messageId);
        $messageId = $kakao_d->messageId;
        $resultCode = $kakao_d->resultCode;
        $reportTime = $kakao_d->reportTime;
        $errorText = $kakao_d->errorText;
        
        log_message("error", "MESSAGE ID : ".$messageId."/".$resultCode."/".$errorText);
        
        $r = $this->db->query("select * from DHN_REQUEST_RESULT_TEMP where remark3 = '".$messageId."'")->row();
        
        if(!empty($r)) {
            
            $result = array();
            $result["msgid"] = $r->msgid;
            $result["ad_flag"] = $r->ad_flag;
            $result["button1"] = $r->button1;
            $result["button2"] = $r->button2;
            $result["button3"] = $r->button3;
            $result["button4"] = $r->button4;
            $result["button5"] = $r->button5;
            $result["code"] = $resultCode;
            $result["image_link"] = $r->image_link;
            $result["image_url"] = $r->image_url;
            $result["kind"] = $r->kind;
            $result["message"] = $errorText;
            $result["message_type"] = $r->message_type;
            $result["msg"] = $r->msg;
            $result["msg_sms"] = $r->msg_sms;
            $result["only_sms"] = $r->only_sms;
            $result["p_com"] = $r->p_com;
            $result["p_invoice"] = $r->p_invoice;
            $result["phn"] = $r->phn;
            $result["profile"] = $r->profile;
            $result["reg_dt"] = $r->reg_dt;
            $result["remark1"] = $r->remark1;
            $result["remark2"] = $r->remark2;
            $result["remark3"] = null;
            $result["remark4"] = $r->remark4;
            $result["remark5"] = $r->remark5;
            $result["res_dt"] = $reportTime;
            $result["reserve_dt"] = $r->reserve_dt;
            
            if($resultCode == "A000") {
                $result["result"] = "Y";
            } else {
                $result["result"] = "N";
            }
            
            $result["s_code"] = $r->s_code;
            $result["sms_kind"] = $r->sms_kind;
            $result["sms_lms_tit"] = $r->sms_lms_tit;
            $result["sms_sender"] = $r->sms_sender;
            $result["sync"] = $r->sync;
            $result["tmpl_id"] = $r->tmpl_id;
            $result["wide"]  = $r->wide;
            $result["supplement"] = $r->supplement;
            $result["price"] = $r->price;
            $result["currency_type"] = $r->currency_type;
            $result["title"] = $r->title;
            
            $sql = "insert ignore into DHN_REQUEST_RESULT(msgid
    ,ad_flag
    ,button1
    ,button2
    ,button3
    ,button4
    ,button5
    ,code
    ,image_link
    ,image_url
    ,kind
    ,message
    ,message_type
    ,msg
    ,msg_sms
    ,only_sms
    ,p_com
    ,p_invoice
    ,phn
    ,profile
    ,reg_dt
    ,remark1
    ,remark2
    ,remark3
    ,remark4
    ,remark5
    ,res_dt
    ,reserve_dt
    ,result
    ,s_code
    ,sms_kind
    ,sms_lms_tit
    ,sms_sender
    ,sync
    ,tmpl_id
    ,wide
    ,supplement
    ,price
    ,currency_type
    ,title
    ) values(?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?
    ,?)";
            
            $this->db->query($sql, $result);
        }
        */
    }
    
}
?>