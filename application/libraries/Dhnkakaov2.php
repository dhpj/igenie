<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 스윗트렉커 처리 모듈 : ?를 /로 바꿈 crontab 5초단위 실행
 * *?1 * * * * root cat /dev/null > /root/script/msg_check.txt; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5
 */

class Dhnkakaov2 {

    public $userId = "dhn7985";
    var $apikey = "dhn_test";
    var $agent = 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)';

    //var $adm_server = "http://dhnmsg.com/api"; // 개발 서버
    var $adm_server = "http://210.114.225.57:8282";
    //var $adm_server = "http://61.76.30.100:8282"; // 개발 서버
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
        $url = $this->adm_server."/sender/token";
        $param = "?yellowId=".urlencode($plusFriend)."&phoneNumber=".$phoneNumber;

        $ch = curl_init();

        $options = array(
            CURLOPT_URL => $url.$param,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);

        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
    }

    public function category_all()
    {
        $url = $this->adm_server."/category/all";

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
        );

        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //log_message("ERROR", $buffer);
        $category = json_decode( $buffer , true );

        if ($cinfo['http_code'] == 200)
        {
            return $category;
        }
        return "";
    }

    public function category($categoryCode)
    {
        $url = $this->adm_server."/sender/category";
        $param = "?categoryCode=".$categoryCode;

        $ch = curl_init();

        $options = array(
            CURLOPT_URL => $url.$param,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 30
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);

        $category = json_decode( $buffer , true );

        if ($cinfo['http_code'] == 200)
        {
            return $category;
        }
        return "";
    }

    /* 발신프로필 추가 */
    public function sender_create($yellowId, $phoneNumber,$categoryCode, $token)
    {

        $url = $this->adm_server."/sender/create";
        //log_message("ERROR","Sender Create : ".$url);
        $headers = array(
            "Accept:application/json",
            "Content-Type:application/json",
            "account:".$this->userId,
            "token:".$token,
            "phoneNumber:".$phoneNumber
        );

        $postfields = array(
            "yellowId"=>$yellowId
            ,"categoryCode"=>$categoryCode
        );

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER =>$headers,
            CURLOPT_POSTFIELDS => json_encode($postfields),
//            CURLOPT_INFILESIZE => $filesize,
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
        //log_message("ERROR", $cinfo['http_code']);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
    }

    /* 발신프로필 삭제 */
    public function sender_delete($senderKey)
    {
        $url = $this->adm_server."/v1/sender/delete";

        $postfields = array(
            "senderKey"=>$senderKey
        );

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
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //return "RESULT : ".$cinfo['http_code'].":".$this->userId;
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 발신프로필 삭제 */
    public function sender_recover($senderKey)
    {
        $url = $this->adm_server."/v1/sender/recover";

        $postfields = array(
            "senderKey"=>$senderKey
        );

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
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //return "RESULT : ".$cinfo['http_code'].":".$this->userId;
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 발신프로필 조회 */
    public function sender($senderKey)
    {
        $url = $this->adm_server."/sender?senderKey=".$senderKey;
        //log_message("ERROR", $url);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        //log_message("ERROR", $buffer);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
    }

    /* 템플릿 추가 */
    public function template_crate($data)
    {
        $url = $this->adm_server."/template/create";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        //log_message("ERROR", $postfields);
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
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);

        curl_close($ch);
        //log_message("ERROR", "Buffer : ".$buffer);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 이미지 템플릿 추가 */
    public function template_create_with_image($data,$img_file, $img_type, $img_name)
    {
        $url = $this->adm_server."/template/create_with_image";
        $postfields = array();
        $postfields['json'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        $cfile = $this->getCurlValue($img_file, $img_type, $img_name);
        $postfields['image'] = $cfile;

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array("cache-control: no-cache","content-type: multipart/form-data"),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 30
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);

        curl_close($ch);
        //log_message("ERROR", "Buffer : ".$buffer);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 템플릿 검수요청 */
    /* [{"senderKey":"", "senderKeyType":"", "templateCode":""},{"senderKey":"", "senderKeyType":"", "templateCode":""}] */
    public function template_request($data)
    {
        $url = $this->adm_server."/template/request";
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
        //log_message("ERROR", "T Req : ".$buffer);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 템플릿 상태조회 */
    public function template($senderKey, $templateCode, $senderKeyType = "S")
    {
        $url = $this->adm_server."/template";
        $param = "?senderKey=".$senderKey."&templateCode=".$templateCode."&senderKeyType=".$senderKeyType;
        $ch = curl_init();
        //log_message("ERROR", "URL : ".$url.$param);
        $options = array(
            CURLOPT_URL => $url.$param,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        //log_message('error', '상태조회 buffer : '.$buffer);
        //if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
		if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
    }

    /* 템플릿 수정 : 한건씩 수정 */
    public function template_update($data)
    {
        $url = $this->adm_server."/template/update";
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

    /* 이미지 템플릿 수정 */
    public function template_update_with_image($data,$img_file, $img_type, $img_name)
    {
        $url = $this->adm_server."/template/update_with_image";
        $postfields = array();
        $postfields['json'] = json_encode($data, JSON_UNESCAPED_UNICODE);

        $cfile = $this->getCurlValue($img_file, $img_type, $img_name);

        $postfields['image'] = $cfile;

        //log_message("ERROR", $postfields);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array("cache-control: no-cache","content-type: multipart/form-data"),
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 30
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);

        curl_close($ch);
        //log_message("ERROR", "Buffer : ".$buffer);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 템플릿 삭제 */
    /* [{"senderKey":"", "senderKeyType":"", "templateCode":""},{"senderKey":"", "senderKeyType":"", "templateCode":""}] */
    public function template_delete($data)
    {
        $url = $this->adm_server."/template/delete";
        $postfields = json_encode($data, JSON_UNESCAPED_UNICODE);
        //log_message('error', 'post :'.$postfields);
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
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        //log_message('error', 'buffer : '.$buffer);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 템플릿 문의하기 */
    /* {"senderKey":"", "senderKeyType":"", "templateCode":"", "comment":""} */
    public function template_comment($data)
    {
        $url = $this->adm_server."/template/comment";
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
        $url = $this->adm_server."/template/list";
        $postfields = "senderKey=".$senderKey;
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

    /* 발신프로필 조회 */
    public function template_last_modified($senderKey='')
    {

        $url = "";
        if(empty($senderKey)) {
            $url = $this->adm_server."/template/last_modified";
        } else {
            $url = $this->adm_server."/template/last_modified?senderKey=".$senderKey;
        }

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
        ); // cURL options
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        //log_message("ERROR", $buffer);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 템플릿 상태조회 */
    public function template_category_all()
    {
        $url = $this->adm_server."/template/category/all";
        $ch = curl_init();

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);

        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 발신프로필 그룹 목록 조회 */
    public function group()
    {
        $url = $this->adm_server."/group";
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 3
        ); // cURL options

        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 발신프로필 그룹 목록 조회 */
    public function sender_group($groupKey)
    {
        $url = $this->adm_server."/group/sender?groupKey=".$groupKey;
        //log_message("ERROR", "url : ".$url);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => true,
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
    public function sender_group_add($groupKey, $senderKey)
    {
        $url = $this->adm_server."/group/sender/add";
        $pf = array();
        $pf["groupKey"] = $groupKey;
        $pf["senderKey"]= $senderKey;
        $postfields = json_encode($pf, JSON_UNESCAPED_UNICODE);
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

    /* 발신프로필 그룹에 프로필 삭제 */
    public function sender_group_remove($groupKey, $profileKey)
    {
        $url = $this->adm_server."/group/sender/remove";
        $pf = array();
        $pf["groupKey"] = $groupKey;
        $pf["senderKey"]= $profileKey;
        $postfields = json_encode($pf, JSON_UNESCAPED_UNICODE);
        //$postfields = "userId=".$userId."&groupKey=".$groupKey."&profileKey=".$profileKey;
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
        $url = $this->adm_server."/ft/image";
        $cfile = $this->getCurlValue($img_file, $img_type, $img_name);

        $post   = array('image' => $cfile);
        $curl = curl_init();
        //log_message("ERROR", "URL : ".$cfile);
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
                "Content-Type: multipart/form-data",
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl);
        curl_close($curl);
        log_message("ERROR", "F : ".$buffer);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 와이드 이미지 전송 2021-01-29 */
    public function upload_wide_image($profileKey, $img_file, $img_type, $img_name)
    {
        $url = $this->adm_server."/ft/wide/image";
        $cfile = $this->getCurlValue($img_file, $img_type, $img_name);

        $post   = array('image' => $cfile);
        $curl = curl_init();
        //log_message("ERROR", "URL : ".$cfile);
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
                "Content-Type: multipart/form-data",
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl);
        curl_close($curl);
        log_message("ERROR", "F : ".$buffer);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }

    /* 이미지 전송 */
//     public function upload_image($profileKey, $img_file, $img_type, $img_name)
//     {
//         $url = $this->adm_server."/ft/image";
//         $cfile = $this->getCurlValue($img_file, $img_type, $img_name);

//         $post   = array('image' => $cfile);
//         $curl = curl_init();
//         //log_message("ERROR", "URL : ".$url);
//         curl_setopt_array($curl, array(
//             CURLOPT_URL => $url,
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => "",
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 30,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => "POST",
//             CURLOPT_POSTFIELDS => $post,
//             CURLOPT_HTTPHEADER => array(
//                 "cache-control: no-cache",
//                 "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
//                 "postman-token: ff6642ed-facd-ed6f-5be2-a683387b6897"
//             ),
//         ));
//         $buffer = curl_exec($curl);
//         $cinfo = curl_getinfo($curl);
//         curl_close($curl);
//         //log_message("ERROR", "F : ".$buffer);
//         if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
//     }

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

        $couponid = $CI->input->post('couponid'); //타겟쿠폰번호
        $agreeid = $CI->input->post('agreeid'); //개인정보동의번호
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
            ($msgType=="at" || $msgType=="ai") ? $CI->input->post('templi_code') : '' ,			// 템플릿코드
            '' , // supplement
            '' , // price
            '' , // currency_type
            $CI->input->post('title'),  // title
        );
        $tel_number = $CI->input->post('tel_number');
        //log_message("ERROR", var_dump($param));
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        for($n=0; $n<count($tel_number); $n++) {
            $param[0] = $tel_number[$n];
            /*
             * 2019.01.19 SSG
             * 수신전화번호 체크
             * 수신 전화번호가 8자리보다 짧으면 발송 불가
             */
            if(strlen($param[0]) >= 8) {
                //log_message("ERROR", "couponid : ".$couponid);
				if($couponid or $agreeid){
					$btn1_array = json_decode($btn1);
					if($agreeid != ""){
						$gurl = base_url()."agreeview/index/".$agreeid."/".$tel_number[$n];
						$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
						$btn1_array[0]->url_mobile = base_url()."short_url/agree/".$md5;
						$btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
					}else{
						$gurl = base_url()."couponview/index/".$couponid."/".$tel_number[$n];
						$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
						$btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
						$btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
					}
					$shorturl = array();
					$shorturl["short_url"] = $md5;
					$shorturl["url"] = $gurl;
					$CI->db->insert("cb_short_url", $shorturl);
					$btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
					$param[2] = str_replace(array("[", "]", "\\"), "", $btn1);
				}
                //log_message("ERROR", "couponid : ".$param[0]);
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    					(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
    				values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

                $CI->db->query($sql, $param);
                if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error()); $err++; } else { $ok++; }
            }
        }
        return $ok;
    }

    public function phoneBookToSendCache($msgType, $customer_filter="", $limit = 9999999)
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }

        $couponid = $CI->input->post('couponid'); //타겟쿠폰번호
        $agreeid = $CI->input->post('agreeid'); //개인정보동의번호
        $btn1 = $CI->input->post('btn1');
        //log_message("ERROR", "phoneBookToSendCache");

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
            ($msgType=="at" || $msgType=="ai") ? $CI->input->post('templi_code')	: '' , // 템플릿코드
            '' , // supplement
            '' , // price
            '' , // currency_type
            $CI->input->post('title'),  // title
        );
        // 고객 필터 추가 2017.12.17
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }

        // 2019.01.17 이수환 customer_filter 'NONE'값(고객구분없음) 수정및 추가
        /*
         * 2019.01.19 SSG
         * 수신전화번호 체크
         * 수신 전화번호가 8자리보다 짧으면 발송 불가
         * length(ab_tel) >=8
         */

        $sql = "";
        if($customer_filter == "FT") {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)".(($limit < 9999999) ? " limit ".$limit : "");
        } else if($customer_filter == "NFT") {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)".(($limit < 9999999) ? " limit ".$limit : "");
        } else if($customer_filter == "NONE") { // 추가 2019.01.17
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ".(($customer_filter) ? " and  ifNULL(ab_kind, '') = ''" : "").(($limit < 9999999) ? " limit ".$limit : "");
        } else if(substr($customer_filter, 0, 7) == "groupId") { //그룹선택의 경우 추가 2020-09-22
            $groupId = substr($customer_filter, 7); //그룹번호
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > groupId : ". $groupId);
			$sch = "";
			if($groupId != "all"){ //전체고객목록 발송이 아닌 경우
				//그룹레벨 조회
				$sql = "SELECT cg_level FROM cb_customer_group WHERE cg_mem_id = '". $CI->member->item('mem_id') ."' AND cg_id = '". $groupId ."' AND cg_use_yn = 'Y' ";
				$cg_level = $CI->db->query($sql)->row()->cg_level;
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > cg_level : ". $cg_level);
				if($cg_level == 1){ //그룹 1차
					$sch .= "
					AND ab_id IN (
						SELECT aug_ab_id
						FROM cb_ab_".$CI->member->item('mem_userid')."_group
						WHERE aug_group_id IN (
							SELECT cg_id
							FROM cb_customer_group
							WHERE cg_mem_id = '".$CI->member->item('mem_id')."'
							AND cg_parent_id = '". $groupId ."'
							AND cg_use_yn = 'Y'
						)
					) ";
				}else{ //그룹 2차
					$sch .= "
					AND ab_id IN (
						SELECT aug_ab_id
						FROM cb_ab_".$CI->member->item('mem_userid')."_group
						WHERE aug_group_id IN (
							SELECT cg_id
							FROM cb_customer_group
							WHERE cg_mem_id = '".$CI->member->item('mem_id')."'
							AND cg_id = '". $groupId ."'
							AND cg_use_yn = 'Y'
						)
					) ";
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sch : ". $sch);
			}
			$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ". $sch ." ". (($limit < 9999999) ? " limit ".$limit : "");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > forsql : ". $forsql);
        } else if(substr($customer_filter, 0, 10) == "groupChkId") { //그룹체크의 경우 추가 2020-11-17
            $groupChkId = substr($customer_filter, 10); //체크된 그룹번호
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > groupChkId : ". $groupChkId);
			//체크된 그룹번호 조회
			$sch = "
			AND ab_id IN (
				SELECT aug_ab_id
				FROM cb_ab_".$CI->member->item('mem_userid')."_group
				WHERE aug_group_id IN (
					SELECT cg_id
					FROM cb_customer_group
					WHERE cg_mem_id = '".$CI->member->item('mem_id')."'
					AND cg_id IN (". $groupChkId .")
					AND cg_use_yn = 'Y'
				)
			) ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sch : ". $sch);
			$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ". $sch ." ". (($limit < 9999999) ? " limit ".$limit : "");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > forsql : ". $forsql);
        } else if($customer_filter == "groupPos") { //포스고객의 경우 추가 2021-02-03
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sch : ". $sch);
			$forsql = "select * from cb_tel_pos where ab_mem_id = '".$CI->member->item('mem_id')."' and length(ab_tel) >=8 ". $sch ." ". (($limit < 9999999) ? " limit ".$limit : "");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > forsql : ". $forsql);
        } else {
            // 2019.01.18 이수환 차후 고객구분 멀티 선택시 처리를 위해 변경(LIKE => IN) 및 추가
            $customer_filter_array = explode(',', $customer_filter);
            //log_message("ERROR", "-------------- customer_filter_array Count : ".count($customer_filter_array));
            $customer_filter_temp = "";
            for($i=0; $i < count($customer_filter_array); $i++) {
                if($customer_filter_array[$i] == "NONE") {
                    $customer_filter_array[$i] = "";
                }
                if ($i == 0) {
                    $customer_filter_temp.= "'".$customer_filter_array[$i]."'";
                } else {
                    $customer_filter_temp.= ",'".$customer_filter_array[$i]."'";
                }
            }
            //log_message("ERROR", "-------------- customer_filter_array Count : ".count($customer_filter_array));
            ////$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1'".(($customer_filter) ? " and  ab_kind like '%".$customer_filter."%'" : "").(($limit < 9999999) ? " limit ".$limit : "");
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ".(($customer_filter) ? " and  ifNULL(ab_kind, '') in (".$customer_filter_temp.")" : "").(($limit < 9999999) ? " limit ".$limit : "");
        }
        //log_message("ERROR", "ForSql :".$forsql);
        //log_message("ERROR", "I : ".$forsql);
        $lists = $CI->db->query($forsql)->result();
        //$i = 1;
        foreach($lists as $r)
        {
            //log_message("ERROR", "I : ".$i);
            //$i ++;
            if($couponid or $agreeid){
                $btn1_array = json_decode($btn1);
				if($agreeid){
					$gurl = base_url()."agreeview/index/".$agreeid."/".$r->ab_tel;
					$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/agree/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
				}else{
					$gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
				}
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                $CI->db->insert("cb_short_url", $shorturl);
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
        				values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
        			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
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

        $couponid = $CI->input->post('couponid'); //타겟쿠폰번호
        $agreeid = $CI->input->post('agreeid'); //개인정보동의번호
        $btn1 = $CI->input->post('btn1');
        //log_message("ERROR", "phoneBookToSendCacheNFT");

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
            ($msgType=="at" || $msgType=="ai") ? $CI->input->post('templi_code')	: '', // 템플릿코드
            '' , // supplement
            '' , // price
            '' , // currency_type
            $CI->input->post('title'),  // title
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        $sql = "";

        /*
         * 2019.01.19 SSG
         * 수신전화번호 체크
         * 수신 전화번호가 8자리보다 짧으면 발송 불가
         * length(ab_tel) >=8
         */
        $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)";

        //$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)" ;
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid or $agreeid){
                $btn1_array = json_decode($btn1);
				if($agreeid){
					$gurl = base_url()."agreeview/index/".$agreeid."/".$r->ab_tel;
					$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/agree/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
				}else{
					$gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
				}
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                $CI->db->insert("cb_short_url", $shorturl);
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
                  values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
        			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
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

        $couponid = $CI->input->post('couponid'); //타겟쿠폰번호
        $agreeid = $CI->input->post('agreeid'); //개인정보동의번호
        //$btn1 = $CI->input->post('btn1');
        //log_message("ERROR", "moveUploadToSendCache");

        $param = array(
            (($callback) ? $callback : $CI->input->post('senderBox')),
            ($msgType=="at" || $msgType=="ai") ? $CI->input->post('templi_code')	: '',
            $mem_id,
            '' , // supplement
            '' , // price
            '' , // currency_type
        );
        //log_message("ERROR", "BTN 1 : ". $btn1);
        $sql = "";
        $forsql = "select sc.*, (case when sc_lms_content='' then 'N' else 'Y' end) as sc_lm_,  '".$param[0]."' as callback, '".$param[1]."' as templi_code
			from cb_wt_send_cache sc where sc_mem_id='".$mem_id."'";
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid or $agreeid){
                $btn1_array = json_decode($r->sc_button1);
				if($agreeid){
					$gurl = base_url()."agreeview/index/".$agreeid."/".$r->sc_tel;
					$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/agree/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
				}else{
					$gurl = base_url()."couponview/index/".$couponid."/".$r->sc_tel;
					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
				}
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                $CI->db->insert("cb_short_url", $shorturl);
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $btn1 = str_replace(array("[", "]", "\\"), "", $btn1);
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    			  (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
                   sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type)
                   values('".$r->sc_name."', '".$r->sc_tel."', '".$r->sc_content."', '".$btn1."','".$r->sc_button2."', '".$r->sc_button3."','".$r->sc_button4."', '".$r->sc_button5."',
                 '".$r->sc_lm_."', '".$r->sc_lms_content."', '".$r->callback."', '".$r->sc_img_url."', '".$r->sc_img_link."', '".$r->templi_code."', ?, ?, ? )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    			(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
                 sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type)
                   values('".$r->sc_name."', '".$r->sc_tel."', '".$r->sc_content."', '".$r->sc_button1."','".$r->sc_button2."', '".$r->sc_button3."','".$r->sc_button4."', '".$r->sc_button5."',
                 '".$r->sc_lm_."', '".$r->sc_lms_content."', '".$r->callback."', '".$r->sc_img_url."', '".$r->sc_img_link."', '".$r->templi_code."', ?, ?, ? )";
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

        $couponid = $CI->input->post('couponid'); //타겟쿠폰번호
        $agreeid = $CI->input->post('agreeid'); //개인정보동의번호
        $btn1 = $CI->input->post('btn1');
        //log_message("ERROR", "NewUploadToSendCache");

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
            ($msgType=="at" || $msgType=="ai") ? $CI->input->post('templi_code')	: '', // 템플릿코드
            '' , // supplement
            '' , // price
            '' , // currency_type
            $CI->input->post('title'),  // title
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }

        $sql = "";
        $forsql = "select * from cb_tel_uploads where mem_id = '".$CI->member->item('mem_id')."'";
        //log_message("ERROR", "Query : ".$CI->input->post('msg'));

        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid or $agreeid){
				$btn1_array = json_decode($btn1);
                if($agreeid){
					$gurl = base_url()."agreeview/index/".$agreeid."/".$r->tel_no;
					$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/agree/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
				}else{
					$gurl = base_url()."couponview/index/".$couponid."/".$r->tel_no;
					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
					$btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
					$btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
				}
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                $CI->db->insert("cb_short_url", $shorturl);
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
        				values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
			} else { //일반 알림톡
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
        			values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
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
        $sql = "insert ignore into DHN_REQUEST
			(
				msgid, ad_flag,
				button1, button2, button3, button4, button5,
				image_link, image_url,
				message_type, msg, msg_sms, only_sms, p_com, p_invoice,
				phn, profile, reg_dt, remark4,  remark5,
				reserve_dt, s_code, sms_kind, sms_lms_tit, sms_sender, tmpl_id, remark2
			)
			select
				concat('".$mem_id."_', sc_id, '_D'), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."'
			from cb_sc_".$mem_userid.(($limit < 9999999) ? " limit ".$limit : "");
        //			echo '<pre> :: ';print_r($sql);
        $CI->db->query($sql, $param);
        //log_message("ERROR", $sql.json_encode($param));
        $mst_qty = $CI->db->affected_rows();

        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }

	//친구톡 2차 알림톡 내용 추가 2021-01-05
	public function sendAgentSendCache2nd($msgid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim)
	{
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > msgid : ". $msgid .", alim_code : ". $alim_code .", alim_cont : ". $alim_cont .", btn1_alim : ". $btn1_alim);
		$CI =& get_instance();
		$sql = "";
		$sql .= "INSERT IGNORE INTO DHN_REQUEST_2ND ";
		$sql .= "( ";
		$sql .= "	MSGID, ";
		$sql .= "	AD_FLAG, ";
		$sql .= "	BUTTON1,  ";
		$sql .= "	BUTTON2,  ";
		$sql .= "	BUTTON3,  ";
		$sql .= "	BUTTON4,  ";
		$sql .= "	BUTTON5, ";
		$sql .= "	IMAGE_LINK, IMAGE_URL, ";
		$sql .= "	MESSAGE_TYPE,  ";
		$sql .= "	MSG,  ";
		$sql .= "	MSG_SMS, ONLY_SMS, P_COM, P_INVOICE, ";
		$sql .= "	PHN, PROFILE, REG_DT,  ";
		$sql .= "	REMARK1, REMARK2, REMARK3, REMARK4, REMARK5, ";
		$sql .= "	RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, ";
		$sql .= "	TMPL_ID, ";
		$sql .= "	WIDE ";
		$sql .= ") ";
		$sql .= "SELECT ";
		$sql .= "	CONCAT(MSGID, 'AT') AS MSGID, ";
		$sql .= "	AD_FLAG, ";
		if($btn1_alim=="" or $btn1_alim=="[]"){
			$sql .= "	NULL AS BUTTON1,  ";
		}else{
			$sql .= "	'". str_replace(array("[", "]"), "", $btn1_alim) ."' AS BUTTON1,  ";
		}
		if($btn2_alim=="" or $btn2_alim=="[]"){
			$sql .= "	NULL AS BUTTON2,  ";
		}else{
			$sql .= "	'". str_replace(array("[", "]"), "", $btn2_alim) ."' AS BUTTON2,  ";
		}
		if($btn3_alim=="" or $btn3_alim=="[]"){
			$sql .= "	NULL AS BUTTON3,  ";
		}else{
			$sql .= "	'". str_replace(array("[", "]"), "", $btn3_alim) ."' AS BUTTON3,  ";
		}
		if($btn4_alim=="" or $btn4_alim=="[]"){
			$sql .= "	NULL AS BUTTON4,  ";
		}else{
			$sql .= "	'". str_replace(array("[", "]"), "", $btn4_alim) ."' AS BUTTON4,  ";
		}
		if($btn5_alim=="" or $btn5_alim=="[]"){
			$sql .= "	NULL AS BUTTON5,  ";
		}else{
			$sql .= "	'". str_replace(array("[", "]"), "", $btn5_alim) ."' AS BUTTON5,  ";
		}
		$sql .= "	IMAGE_LINK, IMAGE_URL, ";
		$sql .= "	'at' AS MESSAGE_TYPE,  ";
		$sql .= "	'". addslashes($alim_cont) ."' AS MSG,  ";
		$sql .= "	MSG_SMS, ONLY_SMS, P_COM, P_INVOICE, ";
		$sql .= "	PHN, PROFILE, REG_DT,  ";
		$sql .= "	REMARK1, REMARK2, REMARK3, REMARK4, REMARK5, ";
		$sql .= "	RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, ";
		$sql .= "	'". $alim_code ."' AS TMPL_ID, ";
		$sql .= "	WIDE ";
		$sql .= "FROM DHN_REQUEST ";
		$sql .= "WHERE REMARK4 = '". $msgid ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$CI->db->query($sql, $param);
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
        $sql = "insert ignore into DHN_REQUEST
			(
				msgid, ad_flag,
				button1, button2, button3, button4, button5,
				image_link, image_url,
				message_type, msg, msg_sms, only_sms, p_com, p_invoice,
				phn, profile, reg_dt, remark4,  remark5,
				reserve_dt, s_code, sms_kind, sms_lms_tit, sms_sender, tmpl_id, remark2
			)
			select
				concat('".$mem_id."_', sc_id, '_DAPI'), ?,
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

    public function sendAgent2SendAPIs($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $mem_send, $sms_kind)
    {
        $CI =& get_instance();
        $param = array(
            "N",	//(($msgType=="at") ? 'N'	: 'Y'),	// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
            $msgType,									// ft, at
            'N',										// onlySMS $CI->input->post('smsOnly')
            null,										// P_COM 택배사코드
            $mem_send,									// P_INVOICE 송장번호 -> 문자 발송 방법 웹(A) GREEN_SHOT, 웹(B) NASELF, 웹(C) SMART
            $pf_key,									// 프로필키
            $group_id,									// msg_sent에서 확인하기 위한 group id
            $reserveDt,									// 예약발송일시
            null,										// 쇼핑몰코드
            $sms_kind									// 전환발송시 SMS/LMS구분 S 혹은 L
        );
        $sql = "insert ignore into DHN_REQUEST
			(
				MSGID, AD_FLAG,
				BUTTON1, BUTTON2, BUTTON3, BUTTON4, BUTTON5,
				IMAGE_LINK, IMAGE_URL,
				MESSAGE_TYPE, MSG, MSG_SMS, ONLY_SMS, P_COM, P_INVOICE,
				PHN, PROFILE, REG_DT, REMARK4,  REMARK5,
				RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, TMPL_ID, REMARK2
			)
			select
				concat('".$mem_id."_', sc_id, '_DAPIS'), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."'
			from cb_api_sc_".$mem_userid." where sc_group_id='".$group_id."'";
        //			echo '<pre> :: ';print_r($sql);
        $CI->db->query($sql, $param);
        $mst_qty = $CI->db->affected_rows();

        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }

    //개인정보동의 데이타 2021-01-20
	public function sendAgentSendCacheAgree($agi_idx, $mem_id, $mst_id, $mem_userid)
    {
		$CI =& get_instance();
		$sql = "
		INSERT INTO cb_agree_data (
			  agd_agi_idx /* 개인정보동의번호 */
			, agd_mem_id /* 회원번호 */
			, agd_mst_id /* 발송번호 */
			, agd_phn /* 연락처 */
			, agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
		)
		SELECT
			  ". $agi_idx ." AS agd_agi_idx /* 개인정보동의번호 */
			, ". $mem_id ." AS agd_mem_id /* 회원번호 */
			, ". $mst_id ." AS agd_mst_id /* 발송번호 */
			, sc_tel /* 연락처 */
			, '0' AS agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
		FROM cb_sc_". $mem_userid;
		//echo '<pre> :: ';print_r($sql);
		//log_message("ERROR", "/application/libraries/Dhnkakao > Query : ".$sql);
		$CI->db->query($sql);
		if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }

    public function sendAgent_fail($msgType)
    {
        $CI =& get_instance();
        $ok = 0; $err = 0;
        /* 메시지 목록을 sc테이블에 담았다가 발송 */

        $couponid = $CI->input->post('couponid'); //타겟쿠폰번호
        $agreeid = $CI->input->post('agreeid'); //개인정보동의번호
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
            ($msgType=="at" || $msgType=="ai") ? $CI->input->post('templi_code') : '' ,			// 템플릿코드
            '' , // supplement
            '' , // price
            '' , // currency_type
            $CI->input->post('title'),  // title
        );
        $tel_number = $CI->input->post('tel_number');
        $mid = $CI->input->post('mid');
        $mtyle = $CI->input->post('mtype');
        $uid = $CI->input->post('uid');
        if ($mtyle == "친구톡"  || $mtyle == "알림톡" || $mtyle == "알림톡(이미지)" || $mtyle == "친구톡(이미지)" || $mtyle == "친구톡(와이드)"){
            $CI->db
                ->select(array("mem_userid AS userid", "PHN AS phnno"))
                ->from("cb_msg_" . $uid)
                ->where(array("CODE !=" => "0000", "REMARK4" => $mid));
            $list = $CI->db->get()->result();
        } else if ($mtyle == "웹(C) LMS"  || $mtyle == "웹(C) SMS"  || $mtyle == "웹(C) MMS" || $mtyle == "RCS LMS" || $mtyle == "RCS SMS" || $mtyle == "RCS 템플릿" || $mtyle == "RCS MMS"){
            $message = "";
            if ($mtyle == "웹(C) LMS"  || $mtyle == "웹(C) SMS"  || $mtyle == "웹(C) MMS") $message = "웹(C) 성공";
            if ($mtyle == "RCS LMS" || $mtyle == "RCS SMS" || $mtyle == "RCS 템플릿" || $mtyle == "RCS MMS") $message = "RCS 성공";
            $CI->db
                ->select(array("mem_userid AS userid", "PHN AS phnno"))
                ->from("cb_msg_" . $uid)
                ->where(array("MESSAGE !=" => $message, "REMARK4" => $mid));
            $list = $CI->db->get()->result();
        }

        //log_message("ERROR", var_dump($param));
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        foreach($list as $a) {
            $param[0] = $a->phnno;
            /*
             * 2019.01.19 SSG
             * 수신전화번호 체크
             * 수신 전화번호가 8자리보다 짧으면 발송 불가
             */
            if(strlen($param[0]) >= 8) {
                //log_message("ERROR", "couponid : ".$couponid);
				if($couponid or $agreeid){
					$btn1_array = json_decode($btn1);
					if($agreeid != ""){
						$gurl = base_url()."agreeview/index/".$agreeid."/".$a->phnno;
						$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
						$btn1_array[0]->url_mobile = base_url()."short_url/agree/".$md5;
						$btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
					}else{
						$gurl = base_url()."couponview/index/".$couponid."/".$a->phnno;
						$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
						$btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
						$btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
					}
					$shorturl = array();
					$shorturl["short_url"] = $md5;
					$shorturl["url"] = $gurl;
					$CI->db->insert("cb_short_url", $shorturl);
					$btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
					$param[2] = str_replace(array("[", "]", "\\"), "", $btn1);
				}
                //log_message("ERROR", "couponid : ".$param[0]);
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    					(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template, supplement, price, currency_type, title)
    				values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

                $CI->db->query($sql, $param);
                if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error()); $err++; } else { $ok++; }
            }
        }
        return $ok;
    }

}
