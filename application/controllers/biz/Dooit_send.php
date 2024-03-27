<?php
class Dooit_send extends CB_Controller {
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

	public function send()
	{
	    $sms_url = "http://www.martapp.co.kr/ezcom/smssend_phone.php";		// 변경X
	    //$returnurl  = "http://";			// 반환 URL
	    $sms['transtype'] = '1';					// base64_encode 사용시 1 미사용시 0 ※추가된 항목
	    $sms['sendtype'] = base64_encode($_POST["sendtype"]);						// 전송 Mode : SMS:1 LMS:2
	    $sms['testflag'] = base64_encode("Y");						// 실제 전송 없이 TEST = Y/ 실제 전송시 생략
	    $sms['uid'] = base64_encode($_POST["uid"]);					// 사용자 ID
	    $sms['passwd'] = base64_encode($_POST["passwd"]);					// 사용자 PWD
	    $sms['title'] = base64_encode($_POST['title']);				// MMS 전송 제목(SMS 전송시 생략) (※전송 방법[POST/GET]에 관계 없이 무조건 Encodeing 해줍니다)
	    $sms['msg'] = base64_encode($_POST['msg']);					// 전송 메시지 본문 (※전송 방법[POST/GET]에 관계 없이 무조건 Encodeing 해줍니다)
	    $sms['sphone'] = base64_encode($_POST['sphone']);			// 발진자 번호
	    $sms['rphone'] = base64_encode($_POST['rphone']);			// 수신자 번호 ( ","(콤마)를 구분자로 다중 전송 지원
	    $sms['rdate'] = base64_encode($_POST['rdate']);				// 예약 전송 날짜YYYYMMDD(즉시 전송시 생략)
	    $sms['rtime'] = base64_encode($_POST['rtime']);				// 예약 전송 시간HHMMSS(즉시 전송시 생략)
	    //$nointeractive = base64_encode($_POST['nointeractive']);	// 사용할 경우 : 1, 성공시 대화상자(alert)생략
	    
	    $host_info = explode("/", $sms_url);
	    $host = $host_info[2];
	    $path = $host_info[3]."/".$host_info[4];
	    
	    $boundary = '';
	    $data = '';
	    
	    srand((double)microtime()*1000000);
	    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	    //print_r($sms);
	    
	    
	    // 헤더 생성
	    $header = "POST /".$path ." HTTP/1.0\r\n";
	    $header .= "Host: ".$host."\r\n";
	    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
	    
	    // 본문 생성
	    foreach($sms AS $index => $value){
	        $data .="--$boundary\r\n";
	        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
	        $data .= "\r\n".$value."\r\n";
	        $data .="--$boundary\r\n";
	    }
	    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";
	    
	    $fp = fsockopen($host, 80);
	    
	    $code = "";
	    $result_msg = "";
	    if ($fp) {
	        fputs($fp, $header.$data);
	        $rsp = '';
	        while(!feof($fp)) {
	            $rsp .= fgets($fp,8192);
	        }
	        fclose($fp);
	        $msg =explode("\r\n\r\n", trim($rsp));
	        $rMsg = explode(",", $msg[1]);
	        $Result = $rMsg[0];	// 전송결과 : 전송 결과는 데이터 시트 참조
	        
	        // 발송 결과 알림
	       
	        if(0 == $Result || '0' == $Result){
	            $code = "SUCCESS";
	        } else {
	            $code = "ERROR";
	            if('100' == $Result || 100 == $Result) {
	                $result_msg = "Disabled Account";
	            } else if('200' == $Result|| 200 == $Result) {
	                $result_msg = "No exist ID or wrong Password";
	            } else if('300' == $Result || 300 == $Result) {
	                $result_msg = "Send type not matched";
	            } else if('301' == $Result || 301 == $Result) {
	                $result_msg = "'GET' Method Message length can not over 512 character";
	            } else if('302' == $Result || 302 == $Result) {
	                $result_msg = "Parameter value has incorrent";
	            } else if('303' == $Result || 303 == $Result) {
	                $result_msg = "Cybercash not enough";
	            } else if('304' == $Result || 304 == $Result) {
	                $result_msg = 'Sender Phone number required';
	            } else if('305' == $Result || 305 == $Result) {
	                $result_msg = "Reciever Phone number required";
	            } else if('900' == $Result || 900 == $Result) {
	                $result_msg = "busy try again later";
	            } else {
	                $result_msg = "$Result";
	            }
	        }
	    } else {
	        $result_msg = "Error : Connection Failed";
	        $code = "ERROR";
	    }
        log_message("ERROR", "Code : ".$code." / MSG : ".$msg);
	    echo '{"code": "'.$code.'", "msg":"'.$result_msg.'"}';
	}
}
?>