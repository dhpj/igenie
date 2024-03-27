<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 스윗트렉커 처리 모듈 : ?를 /로 바꿈 crontab 5초단위 실행
 * *?1 * * * * root cat /dev/null > /root/script/msg_check.txt; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5
 */

class Sweettracker {
    /* 관리자 API */
    // 개발서버 : https://dev-alimtalk-api.bizmsg.kr:1443
    // 운영서버 : https://alimtalk-api.bizmsg.kr
    /* 메시지 발송 */
    // 개발서버 : https://dev-alimtalk-api.sweettracker.net
    // 운영서버 : https://alimtalk-api.sweettracker.net
    
    public $userId = "dhn7985";
    var $agent = 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)';
    
    var $adm_server = "https://alimtalk-api.bizmsg.kr"; //https://dev-alimtalk-api.bizmsg.kr:1443";
    var $api_server = "https://alimtalk-api.sweettracker.net";
    var $dev_server = "https://alimtalk-api.sweettracker.net";
    
    public function getUserId($profileKey = "")
    {
        $CI =& get_instance();
        if(is_null($profileKey) || $profileKey == "") {
            return $this->userId;
        } else {
            $str = "select * from cb_wt_send_profile where spf_key = '".$profileKey."'";
            $profile_row = $CI->db->query($str)->row();
            
            return $profile_row->spf_appr_id;
        }
    }
    
    /* 발신프로필 추가시 인증번호 요청 */
    public function sender_token($plusFriend, $phoneNumber)
    {
        $url = $this->adm_server."/v2/sender/token";
        $postfields = json_encode(array("plusFriend"=>$plusFriend, "phoneNumber"=>$phoneNumber), JSON_UNESCAPED_UNICODE);
        $param = "?plusFriend=".$plusFriend."&phoneNumber=".$phoneNumber;
        $ch = curl_init();
        log_message("ERROR", " 인증번호요청 URL : ".$url.$param);
        $options = array(
            CURLOPT_URL => $url.$param,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            //CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            //CURLOPT_POSTFIELDS => $postfields,
            //CURLOPT_INFILESIZE => $filesize,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        //log_message("ERROR", $buffer);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
    }
    
    public function get_category()
    {
        $url = $this->adm_server."/v2/sender/category/all";
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 30
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        $category = json_decode( $buffer , true );
        //log_message("error", " Category : ".$cinfo['http_code']);
        if ($cinfo['http_code'] == 200)
        {
            return $category;
        }
        return "";
    }
    
    /* 발신프로필 추가 */
    public function sender_create($userId, $yellowId, $phoneNumber,$category, $licenseNumber, $token)
    {
        /* 사용하지 않음 : javascript ajax로 대체 */
        exit;
        $filename = $_FILES['biz_license']['name'];
        $filedata = $_FILES['biz_license']['tmp_name'];
        $filesize = $_FILES['biz_license']['size'];
        $biz_license = "";
        if(file_exists($_FILES['biz_license']['tmp_name'])) {
            $biz_size = filesize($_FILES['biz_license']['tmp_name']);
            $fp1 = fopen($_FILES['biz_license']['tmp_name'], "r");
            $biz_license = fread($fp1, $biz_size);
        }
        
        $url = $this->adm_server."/v2/sender/create";
        $postfields = array(
            "userId"=>$userId
            ,"plusFriend"=>$yellowId
            ,"phoneNumber"=>$phoneNumber
            ,"categoryCode"=>$category
            ,"licenseNumber"=>$licenseNumber
            ,"token"=>$token
            ,"filename"=>$filename
            ,"file"=>$biz_license
        );
        
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array("Content-Type:multipart/form-data", "userId:".$this->userId),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_INFILESIZE => $filesize,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 10
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        //log_message("ERROR", $buffer);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 발신프로필 삭제 */
    public function sender_delete($senderKey)
    {
        $url = $this->adm_server."/v2/sender/delete";
        $postfields = json_encode($senderKey, JSON_UNESCAPED_UNICODE);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json','userId:'.$this->userId),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //return "RESULT : ".$cinfo['http_code'].":".$this->userId;
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 발신프로필 조회 */
    public function sender($userId, $senderKey)
    {
        $url = $this->adm_server."/v2/sender?senderKey=".$senderKey;
        //$postfields = "userId=".$userId;
        
        $postfields = array(
            "senderKey"=>$senderKey
        );
        
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            //CURLOPT_POST => 1,
            //CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json',"userId:".$this->userId),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        //log_message("ERROR", $buffer);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 추가 */
    public function template_crate($data, $userId = '')
    {
        $url = $this->adm_server."/v2/template/create";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        if(empty($userId))
        {
            $user_Id = $this->userId;
        } else {
            $user_Id = $userId ;
        }
        
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json', 'userId:'.$user_Id),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        log_message("ERROR", "template_crate : ". json_decode($buffer));
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 검수요청 */
    /* [{"senderKey":"", "senderKeyType":"", "templateCode":""},{"senderKey":"", "senderKeyType":"", "templateCode":""}] */
    public function template_request($data)
    {
        $url = $this->adm_server."/v2/template/request";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json',"userId:".$this->userId),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        log_message("ERROR", "T Req : ".$cinfo['http_code']);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 상태조회 */
    public function template($senderKey, $templateCode, $userId='')
    {
        $url = $this->adm_server."/v2/template";
        $postfields = "senderKey=".$senderKey."&templateCode=".$templateCode;
        $ch = curl_init();
        if(empty($userId))
        {
            $user_Id = $this->userId;
        } else {
            $user_Id = $userId ;
        }
        //log_message("ERROR", "user id ".$user_Id);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array("userId:".$user_Id),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //log_message("ERROR", "TEMPLATE :".$senderKey. "/ ".$templateCode."/".json_decode($buffer));
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 수정 : 한건씩 수정 */
    public function template_update($data)
    {
        $url = $this->adm_server."/v2/template/update";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json',"userId:".$this->userId ),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 삭제 */
    /* [{"senderKey":"", "senderKeyType":"", "templateCode":""},{"senderKey":"", "senderKeyType":"", "templateCode":""}] */
    public function template_delete($data)
    {
        $url = $this->adm_server."/v2/template/delete";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json', "userId:".$this->userId),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 문의하기 */
    /* {"senderKey":"", "senderKeyType":"", "templateCode":"", "comment":""} */
    public function template_comment($data)
    {
        $url = $this->adm_server."/v2/template/comment";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 템플릿 목록조회 */
    public function template_list($senderKey)
    {
        $url = $this->adm_server."/v2/template/list";
        $postfields = "senderKey=".$senderKey;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array("userId:".$this->userId),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 발신프로필 그룹 목록 조회 */
    public function sender_group($userId)
    {
        $url = $this->adm_server."/v2/sender/group";
        $postfields = "userId=".$userId;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 발신프로필 그룹에 프로필 추가 */
    public function sender_group_add($userId, $groupKey, $profileKey)
    {
        $url = $this->adm_server."/v2/sender/group/add";
        $postfields = "userId=".$userId."&groupKey=".$groupKey."&profileKey=".$profileKey;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 발신프로필 그룹에 프로필 삭제 */
    public function sender_group_remove($userId, $groupKey, $profileKey)
    {
        $url = $this->adm_server."/v2/sender/group/remove";
        $postfields = "userId=".$userId."&groupKey=".$groupKey."&profileKey=".$profileKey;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    function getCurlValue($filename, $contentType, $postname)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        $fullname = $filename.$postname;
        if (function_exists('curl_file_create')) {
            return curl_file_create($fullname, $contentType, $postname);
        }
        // Use the old style if using an older version of PHP
        
        $value = "@{$fullname};filename=" . $postname;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }
        return $value;
    }
    
    /* 이미지 전송 */
    public function upload_image($profileKey, $img_file, $img_type, $img_name)
    {
        $url = $this->adm_server."/v2/ft/".$profileKey."/upload_image";
        $cfile = $this->getCurlValue($img_file, $img_type, $img_name);
        //      log_message("ERROR", "F : ".$url);
        $post   = array('image' => $cfile);
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: ff6642ed-facd-ed6f-5be2-a683387b6897",
                "userId:".$this->getUserId($profileKey)
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl);
        curl_close($curl);
        //log_message("ERROR", "F : ".$buffer);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 이미지 삭제 */
    public function delete_image($profileKey, $img_filename)
    {
        $url = $this->adm_server."/v2/ft/".$profileKey."/delete_image";
        $postfields = "image_name=".$img_filename;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3,
            CURLOPT_HTTPHEADER => array(
                "userId:".$this->getUserId($profileKey)
            )
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 메시지 전송 요청 : 100개까지 동시발송 가능 */
    public function sendMessage($real, $profile_key, $data)
    {
        $url = (($real) ? $this->api_server : $this->dev_server)."/v2/".$profile_key."/sendMessage";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        //echo $postfields;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json', 'userID:'.$this->getUserId($profile_key)),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //leg_message("ERROR", "Send Message : ".$cinfo['http_code']);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    /* 메시지 전송 결과 */
    /* [{"msgid":""},{"msgid":""}] */
    public function response($data)
    {
        $url = $this->api_server."/v2/".$profile_key."/response";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
            //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
            //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
    
    public function sendAgent($msgType)
    {
        $CI =& get_instance();
        $ok = 0; $err = 0;
        /* 메시지 목록을 sc테이블에 담았다가 발송 */
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        //log_message("ERROR", "sendAgent coupon id : ".$couponid);
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        $param = array(
            '',											// 전화번호
            $CI->input->post('templi_cont'),			// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",		// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code') : ''			// 템플릿코드
        );
        $tel_number = $CI->input->post('tel_number');
        //log_message("ERROR", var_dump($param));
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        for($n=0; $n<count($tel_number); $n++) {
            $param[0] = $tel_number[$n];
            
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$tel_number[$n];
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[2] = str_replace(array("[", "]", "\\"), "", $btn1);
            }
            
            $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
					(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
				values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error()); $err++; } else { $ok++; }
        }
        return $ok;
    }
    
    public function phoneBookToSendCache($msgType, $customer_filter="")
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        log_message("ERROR", "phoneBookToSendCache");
        
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code')	: '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        
        $sql = "";
        if($customer_filter == "FT") {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)";
        } else if($customer_filter == "NFT") {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)";
        } else {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1'".(($customer_filter) ? " and  ab_kind like '%".$customer_filter."%'" : "");
        }
        //$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1'".(($customer_filter) ? " and  ab_kind like '%".$customer_filter."%'" : "");
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        				values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
                
            } else {
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
            //log_message("ERROR", "QUERY : ".$sql);
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function phoneBookToSendCacheNFT($msgType )
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        log_message("ERROR", "phoneBookToSendCacheNFT");
        
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn1')),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code')	: '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        $sql = "";

        $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)";
        
        //$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)" ;
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
                  values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function moveUploadToSendCache($msgType, $mem_id, $callback="")
    {
        $CI =& get_instance();
        /* 업로드 목록 발송 -> sendCache 삭제후 sc_userid로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        //$btn1 = $CI->input->post('btn1');
        log_message("ERROR", "moveUploadToSendCache");
        
        $param = array(
            (($callback) ? $callback : $CI->input->post('senderBox')),
            ($msgType=="at") ? $CI->input->post('templi_code')	: '',
            $mem_id
        );
        //log_message("ERROR", "BTN 1 : ". $btn1);
        $sql = "";
        $forsql = "select sc.*, (case when sc_lms_content='' then 'N' else 'Y' end) as sc_lm_,  '".$param[0]."' as callback, '".$param[1]."' as templi_code
			from cb_wt_send_cache sc where sc_mem_id='".$mem_id."'";
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($r->sc_button1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->sc_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                // $btn1_array = array();
                $btn1_array->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $btn1 = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    			  (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
                   sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
            values('".$r->sc_name."', '".$r->sc_tel."', '".$r->sc_content."', '".$btn1."','".$r->sc_button2."', '".$r->sc_button3."','".$r->sc_button4."', '".$r->sc_button5."',
                 '".$r->sc_lm_."', '".$r->sc_lms_content."', '".$r->callback."', '".$r->sc_img_url."', '".$r->sc_img_link."', '".$r->templi_code."' )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    			(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
                 sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
            values('".$r->sc_name."', '".$r->sc_tel."', '".$r->sc_content."', '".$r->sc_button1."','".$r->sc_button2."', '".$r->sc_button3."','".$r->sc_button4."', '".$r->sc_button5."',
                 '".$r->sc_lm_."', '".$r->sc_lms_content."', '".$r->callback."', '".$r->sc_img_url."', '".$r->sc_img_link."', '".$r->templi_code."' )";
            }
            
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function NewUploadToSendCache($msgType )
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        log_message("ERROR", "NewUploadToSendCache");
        
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code')	: '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        
        $sql = "";
        $forsql = "select * from cb_tel_uploads where mem_id = '".$CI->member->item('mem_id')."'";
        //log_message("ERROR", "Query : ".$CI->input->post('msg'));
        
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        				values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
                
            } else {
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        			values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
            //log_message("ERROR", "QUERY : ".$sql);
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function sendAgentSendCache($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $limit=9999999)
    {
        $CI =& get_instance();
        $param = array(
            "N",	//(($msgType=="at") ? 'N'	: 'Y'),		// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
            $msgType,									// ft, at
            'N',											// onlySMS $CI->input->post('smsOnly')
            null,											// P_COM 택배사코드
            null,											// P_INVOICE 송장번호
            $pf_key,										// 프로필키
            $group_id,									// msg_sent에서 확인하기 위한 group id
            $reserveDt,									// 예약발송일시
            null,											// 쇼핑몰코드
            ''												// 전환발송시 SMS/LMS구분
        );
        $sql = "insert ignore into TBL_REQUEST
			(
				MSGID, AD_FLAG,
				BUTTON1, BUTTON2, BUTTON3, BUTTON4, BUTTON5,
				IMAGE_LINK, IMAGE_URL,
				MESSAGE_TYPE, MSG, MSG_SMS, ONLY_SMS, P_COM, P_INVOICE,
				PHN, PROFILE, REG_DT, REMARK4,  REMARK5,
				RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, TMPL_ID, REMARK2
			)
			select
				concat('".$mem_id."_', sc_id), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."'
			from cb_sc_".$mem_userid.(($limit < 9999999) ? " limit ".$limit : "");
        //			echo '<pre> :: ';print_r($sql);
        $CI->db->query($sql, $param);
        $mst_qty = $CI->db->affected_rows();
        
        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
    
    public function sendAgentSendAPI($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $sc_id)
    {
        $CI =& get_instance();
        $param = array(
            "N",	//(($msgType=="at") ? 'N'	: 'Y'),		// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
            $msgType,									// ft, at
            'N',											// onlySMS $CI->input->post('smsOnly')
            null,											// P_COM 택배사코드
            null,											// P_INVOICE 송장번호
            $pf_key,										// 프로필키
            $group_id,									// msg_sent에서 확인하기 위한 group id
            $reserveDt,									// 예약발송일시
            null,											// 쇼핑몰코드
            ''												// 전환발송시 SMS/LMS구분
        );
        $sql = "insert ignore into TBL_REQUEST
			(
				MSGID, AD_FLAG,
				BUTTON1, BUTTON2, BUTTON3, BUTTON4, BUTTON5,
				IMAGE_LINK, IMAGE_URL,
				MESSAGE_TYPE, MSG, MSG_SMS, ONLY_SMS, P_COM, P_INVOICE,
				PHN, PROFILE, REG_DT, REMARK4,  REMARK5,
				RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, TMPL_ID, REMARK2
			)
			select
				concat('".$mem_id."_', sc_id, '_API'), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."'
			from cb_api_sc_".$mem_userid." where sc_id='".$sc_id."'";
        //			echo '<pre> :: ';print_r($sql);
        $CI->db->query($sql, $param);
        $mst_qty = $CI->db->affected_rows();
        
        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
}