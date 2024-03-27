<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 스윗트렉커 처리 모듈 : ?를 /로 바꿈 crontab 5초단위 실행
 * *?1 * * * * root cat /dev/null > /root/script/msg_check.txt; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5
 */

class Image_api {

    /* 이미지 전송 */
    public function api_image($path)
    {
        $server_path = "/var/www/igenie";
        $api_url = "http://14.43.241.107:8080/upload";

        $ori_file_info = pathinfo($path);
        $upload_path = $ori_file_info["dirname"];

        // $file_info = pathinfo($server_path.'/uploads/user_images/2021/11/06dcda672827b3ee75ea123015ee1611.jpg');
        $file_info = pathinfo($server_path.$path);
        // log_message("ERROR", "path : ".$path);
        $url = $api_url;
        $cfile = $this->getCurlValue($file_info['dirname'], 'image/jpeg', $file_info['basename']);
        //log_message("ERROR", "cfile : ".$file_info['dirname'].'/');
        //log_message("ERROR", "URL : ".$cfile);

        $post   = array(
            'path' => $upload_path."/"
          , 'file' => $cfile
        );

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
                "Content-Type: multipart/form-data",
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        // echo $buffer;
        // log_message("ERROR", "F : ".$buffer.$cinfo);

        //foreach($cinfo as $key => $element) {
        //    echo $key . " - " . $element."<br />";
        //}
        if ($cinfo['http_code'] != 200){
            if($buffer){
                // log_message("ERROR", "buffer : ".json_decode($buffer));
            }
            // log_message("ERROR", "cinfo : ".$cinfo);

        }

    if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : '';  } else { return json_decode($buffer); }
    }

    function getCurlValue($filename, $contentType, $postname)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        $fullname = $filename.'/'.$postname;
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

}
