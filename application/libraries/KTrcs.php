<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 스윗트렉커 처리 모듈 : ?를 /로 바꿈 crontab 5초단위 실행
 * *?1 * * * * root cat /dev/null > /root/script/msg_check.txt; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5
 */

class KTrcs {

    var $userid = "dhn2021g";
    var $apikey = "SK.Wf1vK26VO9OI362";
    var $agent = 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)';

    var $adm_server = "https://api.rcsbizcenter.com/api/1.1";

    var $rcs_server = "https://agency.hermes.kt.com";

    var $rcs_id = "dhn7137985";

    var $rcs_pw = "\$2a\$10\$wss410VSvWDh7lABAGdjvu.iJnQ4jziEnzXlDB2./PVBcTrO5L/iK";

    var $request_table = "DHN_REQUEST_RESULT";

    //var $adm_server = "http://61.76.30.100:8282"; // 개발 서버
    // public function getUserId($profileKey = "")
    // {
    //     $CI =& get_instance();
    //     if(is_null($profileKey) || $profileKey == "") {
    //         return $this->userId;
    //     } else {
    //         $str = "select * from cb_wt_send_profile where spf_key = '".$profileKey."'";
    //         $profile_row = $CI->db->query($str)->row();
    //
    //         return $profile_row->spf_appr_id;
    //     }
    // }
//
    public function get_token_info()
    {
        $url = $this->adm_server."/token";

        $headers = array(
            "cache-control: no-cache",
            "content-type: application/json"
            // ,
            // "siteid:".$this->userid,
            // "auth_key:".$this->apikey
        );

        $postfields = array(
            "clientId"=>$this->userid,
            "clientSecret"=>$this->apikey
        );

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER =>$headers,
            CURLOPT_POSTFIELDS => json_encode($postfields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->agent,
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 10
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        log_message("ERROR", $buffer);
        log_message("ERROR", $buffer->accessToken);
        // log_message("ERROR", $cinfo['http_code']);
        curl_close($ch);
        if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return   json_decode($buffer)->accessToken; }
    }


    //     /* common템플릿 상세조회 */
        public function template($messagebaseId)
        {

            $url = $this->adm_server."/messagebase/common/".$messagebaseId;
            // $param = "?messagebaseId=".$senderKey."&templateCode=".$templateCode."&senderKeyType=".$senderKeyType;

            $token = $this->get_token_info();

            $headers = array(
                "cache-control: no-cache",
                "content-type: application/json",
                'Authorization: Bearer '.$token
                // ,
                // "siteid:".$this->userid,
                // "auth_key:".$this->apikey
            );
            $ch = curl_init();
            log_message("ERROR", "URL : ".$url);
            log_message("ERROR", "Authorization : ".$token);
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPGET => TRUE,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT => $this->agent,
                CURLOPT_REFERER => "",
                CURLOPT_TIMEOUT => 3
            );
            curl_setopt_array($ch, $options);
            $buffer = curl_exec ($ch);
            $cinfo = curl_getinfo($ch);
            curl_close($ch);
            log_message('error', '상태조회 buffer : '.$buffer);
            log_message('error', 'cinfo : '.$cinfo['http_code']);
            if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
        }

        /* 소속 브랜드 list조회 */
         public function get_brand()
         {
             $CI =& get_instance();

             $list = $this->brand_list(0, 1000);

             $result_msg = '';
             $result_reason = '';
             $result_up = 0; //업데이트 개수
             $result_suc = 0; //가져오기 성공개수
             $result_fail = 0; //가져오기 실패개수
             log_message('error', 'get_brand 시작');

             foreach($list as $r)
             {
                 $sql = "select count(1) as cnt, brd_brand_key from cb_wt_brand where brd_brand = '".$r->brandId."'";
                 $brand_already = $CI->db->query($sql)->row();
                 $brand_cnt = $brand_already->cnt;
                 $brand_key = $brand_already->brd_brand_key;
                  log_message('error', 'get_brand sql : '.$sql);
                  log_message('error', 'brand_cnt : '.$brand_cnt);
                 if($brand_cnt==0 && $r->status == "승인"){
                     $result_up++;
                     $sql = "select count(1) as cnt, mem_id from cb_member where mem_biz_reg_no = '".$r->corpRegNum."'";
                     $mem_info = $CI->db->query($sql)->row();
                     log_message('error', 'get_brand sql : '.$sql);
                     log_message('error', 'mem_info->cnt : '.$mem_info->cnt.'  / mem_info->mem_id : '.$mem_info->mem_id);
                     if($mem_info->cnt == 1){
                         $brand_data = array();
                         $brand_data["brd_mem_id"] = $mem_info->mem_id;
                         $brand_data["brd_brand"] = $r->brandId;
                         $brand_data["brd_company"] = $r->brandName;
                         $brand_data["brd_biz_no"] = $r->corpRegNum;
                         $brand_data["brd_date"] = $r->registerDate;
                         $brand_data["brd_appr_date"] = $r->approvalDate;
                         $brand_data["brd_status"] = $r->status;
                         $brand_data["brd_make_user"] = $CI->member->item('mem_id');

                         $b_detail = $this->brand_detail($r->brandId);
                         $brand_data["brd_brand_key"] = $b_detail->result[0]->brandKey;

                         $CI->db->insert("cb_wt_brand", $brand_data);
                         $result_suc++;
                     }else{
                         $result_fail++;
                         if($mem_info->cnt == 0){
                             $result_reason = "일치하는 사업자번호가 없습니다.";
                         }else{
                             $result_reason = "같은 사업자번호가 ".$mem_info->cnt."개 존재합니다.";
                         }

                     }
                 }else if($brand_cnt==1 && $r->status == "승인"&&empty($brand_key)){

                     $b_detail = $this->brand_detail($r->brandId);
                     $brand_updata = array();
                     $brand_updata["brd_brand_key"] = $b_detail->result[0]->brandKey;

                     $where = array();
                     $where["brd_brand"] = $r->brandId;

                     $CI->db->update("cb_wt_brand", $brand_updata, $where);
                 }
             }
             if($result_up==0){
                 $result_msg = "가져올 새 브랜드가 없습니다.";
             }else{
                 if($result_suc==0){
                      $result_msg = $result_up."개의 새 브랜드를 가져올 수 없습니다.<br/>(".$result_reason.")";
                 }else{
                      $result_msg = $result_up."개의 새 브랜드 중 <br/>[성공:".$result_suc." , 실패:".$result_fail." ]<br/> 가져오기가 완료되었습니다.";
                 }
             }
             log_message('error', 'result_msg : '.$result_msg);
             return  $result_msg;


         }

         /* 브랜드 발신번호 list가져오기 */
          public function get_chatbot($brand)
          {
              $CI =& get_instance();

              $list = $this->brand_chatbot_list($brand, 0, 100);

              $result_msg = '';
              $result_reason = '';
              $result_up = 0; //업데이트 개수
              $result_suc = 0; //가져오기 성공개수
              $result_fail = 0; //가져오기 실패개수
              log_message('error', 'get_chatbot 시작');
              if(!empty($list)){
                  $sql = "select brd_mem_id, count(1) as cnt from cb_wt_brand where brd_brand = '".$brand."'";
                  $brd_info = $CI->db->query($sql)->row();
              }
              foreach($list as $r)
              {
                  $sql = "select count(1) as cnt from cb_wt_brand_tel where brt_tel_no = '".$r->chatbotId."' and brt_brand = '".$r->brandId."'";
                  $chatbot_cnt = $CI->db->query($sql)->row()->cnt;
                   log_message('error', 'get_chatbot sql : '.$sql);
                   log_message('error', 'chatbot_cnt : '.$chatbot_cnt);
                  if($chatbot_cnt==0 && $r->approvalResult == "승인"){
                      $result_up++;
                      log_message('error', 'brd_info->cnt : '.$brd_info->cnt.'  / brd_info->brd_mem_id : '.$brd_info->brd_mem_id);
                      if($brd_info->cnt == 1){
                          $chatbot_data = array();
                          $chatbot_data["brt_mem_id"] = $brd_info->brd_mem_id;
                          $chatbot_data["brt_brand"] = $r->brandId;
                          $chatbot_data["brt_company"] = $r->subTitle;
                          $chatbot_data["brt_tel_no"] = $r->chatbotId;
                          $chatbot_data["brt_date"] = $r->registerDate;
                          $chatbot_data["brt_appr_date"] = $r->approvalDate;
                          $chatbot_data["brt_status"] = $r->approvalResult;
                          $chatbot_data["brt_make_user"] = $CI->member->item('mem_id');

                          $CI->db->insert("cb_wt_brand_tel", $chatbot_data);
                          $result_suc++;
                      }else{
                          $result_fail++;
                          if($brd_info->cnt == 0){
                              $result_reason = "일치하는 브랜드가 없습니다.";
                          }else{
                              $result_reason = "같은 브랜드가 ".$brd_info->cnt."개 존재합니다.";
                          }

                      }
                  }
              }
              if($result_up==0){
                  $result_msg = "가져올 새 발신번호가 없습니다.";
              }else{
                  if($result_suc==0){
                       $result_msg = $result_up."개의 새 발신번호를 가져올 수 없습니다.<br/>(".$result_reason.")";
                  }else{
                       $result_msg = $result_up."개의 새 발신번 중 <br/>[성공:".$result_suc." , 실패:".$result_fail." ]<br/> 가져오기가 완료되었습니다.";
                  }
              }
              log_message('error', 'result_msg : '.$result_msg);
              return  $result_msg;


          }


          /* 브랜드 템플 가져오기 */
           public function get_template($brand)
           {
               $CI =& get_instance();

               $list = $this->brand_template_list($brand, 0, 100);

               $result_msg = '';
               $result_reason = '';
               $result_up = 0; //업데이트 개수
               $result_suc = 0; //가져오기 성공개수
               $result_fail = 0; //가져오기 실패개수
               log_message('error', 'get_template 시작');
               if(!empty($list)){
                   $sql = "select brd_mem_id, brd_company, count(1) as cnt from cb_wt_brand where brd_brand = '".$brand."'";
                   $brd_info = $CI->db->query($sql)->row();
               }
               foreach($list as $r)
               {
                   $sql = "select count(1) as cnt from cb_wt_brand_template where tpl_messagebaseId = '".$r->messagebaseId."' and tpl_brand = '".$r->brandId."'";
                   $template_cnt = $CI->db->query($sql)->row()->cnt;
                    log_message('error', 'get_template sql : '.$sql);
                    log_message('error', 'template_cnt : '.$template_cnt);
                   if($template_cnt==0){
                       $result_up++;
                       log_message('error', 'brd_info->cnt : '.$brd_info->cnt.'  / brd_info->brd_mem_id : '.$brd_info->brd_mem_id);
                       if($brd_info->cnt == 1){
                           $template_detail = $this->brand_template_detail($r->brandId,$r->messagebaseId);
                           if($template_detail->code=="20000000"){
                               $tmp_data = array();
                               $tmp_data["tpl_mem_id"] = $brd_info->brd_mem_id;
                               $tmp_data["tpl_brand"] = $r->brandId;
                               $tmp_data["tpl_company"] = $brd_info->brd_company;
                               $tmp_data["tpl_messagebaseId"] = $r->messagebaseId;
                               $tmp_data["tpl_name"] = $r->tmpltName;

                               $tmp_data["tpl_date"] = $r->registerDate;
                               $tmp_data["tpl_appr_date"] = $r->approvalDate;
                               $tmp_data["tpl_status"] = $r->status;
                               $tmp_data["tpl_approvalResult"] = $r->approvalResult;
                               $tmp_data["tpl_approvalReason"] = $r->approvalReason;
                               $tmp_data["tpl_messagebaseformId"] = $r->messagebaseformId;


                               $tmp_data["tpl_description"] = $template_detail->result[0]->inputText;
                               $tmp_data["tpl_productCode"] = $template_detail->result[0]->productCode;
                               $tmp_data["tpl_cardType"] = $template_detail->result[0]->cardType;

                               $tmp_data["tpl_button"] =json_encode($template_detail->result[0]->formattedString->RCSMessage->openrichcardMessage->suggestions,JSON_UNESCAPED_UNICODE);;

                               // $tmp_data["brt_make_user"] = $CI->member->item('mem_id');

                               $CI->db->insert("cb_wt_brand_template", $tmp_data);
                               $result_suc++;
                           }

                       }else{
                           $result_fail++;
                           if($brd_info->cnt == 0){
                               $result_reason = "일치하는 브랜드가 없습니다.";
                           }else{
                               $result_reason = "같은 브랜드가 ".$brd_info->cnt."개 존재합니다.";
                           }

                       }
                   }
               }
               if($result_up==0){
                   $result_msg = "가져올 새 템플릿이 없습니다.";
               }else{
                   if($result_suc==0){
                        $result_msg = $result_up."개의 새 템플릿을 가져올 수 없습니다.<br/>(".$result_reason.")";
                   }else{
                        $result_msg = $result_up."개의 새 템플릿 중 <br/>[성공:".$result_suc." , 실패:".$result_fail." ]<br/> 가져오기가 완료되었습니다.";
                   }
               }
               log_message('error', 'result_msg : '.$result_msg);
               return  $result_msg;


           }


        /* 소속 브랜드 list조회 */
         public function brand_list($offset, $limit)
         {
             $agencyId = $this->userid;
             $url = $this->adm_server."/agency/".$agencyId."/contract";

             $param = "?offset=".$offset."&limit=".$limit;

             $token = $this->get_token_info();

             $headers = array(
                 "cache-control: no-cache",
                 "content-type: application/json",
                 'Authorization: Bearer '.$token
                 // ,
                 // "siteid:".$this->userid,
                 // "auth_key:".$this->apikey
             );
             $ch = curl_init();
             log_message("ERROR", "URL : ".$url.$param );
             log_message("ERROR", "Authorization : ".$token);
             $options = array(
                 CURLOPT_URL => $url.$param ,
                 CURLOPT_HEADER => false,
                 CURLOPT_HTTPGET => TRUE,
                 CURLOPT_HTTPHEADER => $headers,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_USERAGENT => $this->agent,
                 CURLOPT_REFERER => "",
                 CURLOPT_TIMEOUT => 3
             );
             curl_setopt_array($ch, $options);
             $buffer = curl_exec ($ch);
             $cinfo = curl_getinfo($ch);
             curl_close($ch);
             log_message('error', '브랜드list buffer : '.$buffer);
             log_message('error', 'cinfo : '.$cinfo['http_code']);
             if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return json_decode($buffer)->result; }
         }

         /* 브랜드 상세조회 */
          public function brand_detail($brandId)
          {
              $agencyId = $this->userid;
              $url = $this->adm_server."/agency/".$agencyId."/brand/".$brandId;

              // $param = "?offset=".$offset."&limit=".$limit;

              $token = $this->get_token_info();

              $headers = array(
                  "cache-control: no-cache",
                  "content-type: application/json",
                  'Authorization: Bearer '.$token
                  // ,
                  // "siteid:".$this->userid,
                  // "auth_key:".$this->apikey
              );
              $ch = curl_init();
              log_message("ERROR", "URL : ".$url );
              log_message("ERROR", "Authorization : ".$token);
              $options = array(
                  CURLOPT_URL => $url ,
                  CURLOPT_HEADER => false,
                  CURLOPT_HTTPGET => TRUE,
                  CURLOPT_HTTPHEADER => $headers,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_USERAGENT => $this->agent,
                  CURLOPT_REFERER => "",
                  CURLOPT_TIMEOUT => 3
              );
              curl_setopt_array($ch, $options);
              $buffer = curl_exec ($ch);
              $cinfo = curl_getinfo($ch);
              curl_close($ch);
              log_message('error', '브랜드 detail buffer : '.$buffer);
              log_message('error', 'cinfo : '.$cinfo['http_code']);
              if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return json_decode($buffer); }
          }

          /* 브랜드 발신번호 list조회 */
           public function brand_chatbot_list($brandId, $offset, $limit)
           {
               $agencyId = $this->userid;
               $url = $this->adm_server."/brand/".$brandId."/chatbot";

               $param = "?offset=".$offset."&limit=".$limit;

               $brandkey = $this->get_brandkey($brandId);
               $token = $this->get_token_info();

               $headers = array(
                   "cache-control: no-cache",
                   "content-type: application/json",
                    'X-RCS-Brandkey: '.$brandkey,
                   'Authorization: Bearer '.$token
                   // ,
                   // "siteid:".$this->userid,
                   // "auth_key:".$this->apikey
               );
               $ch = curl_init();
               log_message("ERROR", "URL : ".$url.$param );
               log_message("ERROR", "Authorization : ".$token);
               $options = array(
                   CURLOPT_URL => $url.$param ,
                   CURLOPT_HEADER => false,
                   CURLOPT_HTTPGET => TRUE,
                   CURLOPT_HTTPHEADER => $headers,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT => $this->agent,
                   CURLOPT_REFERER => "",
                   CURLOPT_TIMEOUT => 3
               );
               curl_setopt_array($ch, $options);
               $buffer = curl_exec ($ch);
               $cinfo = curl_getinfo($ch);
               curl_close($ch);
               log_message('error', '브랜드 chatbot list buffer : '.$buffer);
               log_message('error', 'cinfo : '.$cinfo['http_code']);
               if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return json_decode($buffer)->result; }
           }


         /* common 템플릿 list조회_sms,lms,mms,chat용 */
          public function template_common_list($productCode,  $cardType, $offset, $limit)
          {

              $url = $this->adm_server."/messagebase/common";

              $param = "?productCode=".$productCode."&cardType=".$cardType."&offset=".$offset."&limit=".$limit;

              $token = $this->get_token_info();

              $headers = array(
                  "cache-control: no-cache",
                  "content-type: application/json",
                  'Authorization: Bearer '.$token
                  // ,
                  // "siteid:".$this->userid,
                  // "auth_key:".$this->apikey
              );
              $ch = curl_init();
              log_message("ERROR", "URL : ".$url.$param );
              log_message("ERROR", "Authorization : ".$token);
              $options = array(
                  CURLOPT_URL => $url.$param ,
                  CURLOPT_HEADER => false,
                  CURLOPT_HTTPGET => TRUE,
                  CURLOPT_HTTPHEADER => $headers,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_USERAGENT => $this->agent,
                  CURLOPT_REFERER => "",
                  CURLOPT_TIMEOUT => 3
              );
              curl_setopt_array($ch, $options);
              $buffer = curl_exec ($ch);
              $cinfo = curl_getinfo($ch);
              curl_close($ch);
              log_message('error', '상태조회 buffer : '.$buffer);
              log_message('error', 'cinfo : '.$cinfo['http_code']);
              if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
          }

          /* 브랜드 상세조회 */
           public function get_brandkey($brandId)
           {
               $agencyId = $this->userid;
               $url = $this->adm_server."/agency/".$agencyId."/brand/".$brandId;

               // $param = "?offset=".$offset."&limit=".$limit;

               $token = $this->get_token_info();

               $headers = array(
                   "cache-control: no-cache",
                   "content-type: application/json",
                   'Authorization: Bearer '.$token
                   // ,
                   // "siteid:".$this->userid,
                   // "auth_key:".$this->apikey
               );
               $ch = curl_init();
               log_message("ERROR", "URL : ".$url );
               log_message("ERROR", "Authorization : ".$token);

               $options = array(
                   CURLOPT_URL => $url ,
                   CURLOPT_HEADER => false,
                   CURLOPT_HTTPGET => TRUE,
                   CURLOPT_HTTPHEADER => $headers,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT => $this->agent,
                   CURLOPT_REFERER => "",
                   CURLOPT_TIMEOUT => 3
               );
               curl_setopt_array($ch, $options);
               $buffer = curl_exec ($ch);
               $cinfo = curl_getinfo($ch);
               curl_close($ch);
               log_message('error', '브랜드list buffer : '.$buffer);
               log_message('error', 'cinfo : '.$cinfo['http_code']);
               log_message("ERROR", "brandKey : ".json_decode($buffer)->result[0]->brandKey);
               if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return json_decode($buffer)->result[0]->brandKey; }
           }


         /* 브랜드별 템플릿 list 조회 */
          public function brand_template_list($brandId, $offset, $limit)
          {

              $url = $this->adm_server."/brand/".$brandId."/messagebase";


              $param = "?offset=".$offset."&limit=".$limit;

              $brandkey = $this->get_brandkey($brandId);
              $token = $this->get_token_info();

              $headers = array(
                  "cache-control: no-cache",
                  "content-type: application/json",
                  'X-RCS-Brandkey: '.$brandkey,
                  'Authorization: Bearer '.$token

                  // ,
                  // "siteid:".$this->userid,
                  // "auth_key:".$this->apikey
              );
              $ch = curl_init();
              log_message("ERROR", "URL : ".$url.$param );
              log_message("ERROR", "Authorization : ".$token);
              log_message("ERROR", "X-RCS-Brandkey : ".$brandkey);
              $options = array(
                  CURLOPT_URL => $url.$param ,
                  CURLOPT_HEADER => false,
                  CURLOPT_HTTPGET => TRUE,
                  CURLOPT_HTTPHEADER => $headers,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_USERAGENT => $this->agent,
                  CURLOPT_REFERER => "",
                  CURLOPT_TIMEOUT => 3
              );
              curl_setopt_array($ch, $options);
              $buffer = curl_exec ($ch);
              $cinfo = curl_getinfo($ch);
              curl_close($ch);
              log_message('error', '상태조회 buffer : '.$buffer);
              log_message('error', 'cinfo : '.$cinfo['http_code']);
              if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return json_decode($buffer)->result; }
          }


          /* 브랜드 템플릿 상세조회 */
           public function brand_template_detail($brandId, $messagebaseId)
           {

               $url = $this->adm_server."/brand/".$brandId."/messagebase/".$messagebaseId;
               $brandkey = $this->get_brandkey($brandId);

               // $param = "?X-RCS-Brandkey=".$brandkey."&offset=".$offset."&limit=".$limit;
               $brandkey = $this->get_brandkey($brandId);
               $token = $this->get_token_info();

               $headers = array(
                   "cache-control: no-cache",
                   "content-type: application/json",
                   'X-RCS-Brandkey: '.$brandkey,
                   'Authorization: Bearer '.$token
                   // ,
                   // "siteid:".$this->userid,
                   // "auth_key:".$this->apikey
               );
               $ch = curl_init();
               log_message("ERROR", "URL : ".$url);
               log_message("ERROR", "Authorization : ".$token);
               log_message("ERROR", "X-RCS-Brandkey : ".$brandkey);
               $options = array(
                   CURLOPT_URL => $url,
                   CURLOPT_HEADER => false,
                   CURLOPT_HTTPGET => TRUE,
                   CURLOPT_HTTPHEADER => $headers,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT => $this->agent,
                   CURLOPT_REFERER => "",
                   CURLOPT_TIMEOUT => 3
               );
               curl_setopt_array($ch, $options);
               $buffer = curl_exec ($ch);
               $cinfo = curl_getinfo($ch);
               curl_close($ch);
               log_message('error', '상태조회 buffer : '.$buffer);
               log_message('error', 'cinfo : '.$cinfo['http_code']);
               if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return json_decode($buffer); }
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
                // $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
                // if($url_cnt->cnt > 0) {
                //     $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
                //     $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
                // }
                // if(empty($mem_url)) {
                    $mem_url = $CI->input->post('img_link');
                // }

                /* 고객 정보를 sendCache에 insert */
                $param = array(
                    // $CI->input->post('templi_cont'),	// 템플릿내용
                    // ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
                    // ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
                    // ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
                    // ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
                    // ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
                    ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
                    $CI->input->post('msg'),				// LMS/SMS내용
                    $CI->input->post('senderBox'),
                    ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
                    ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
                    // ($msgType=="rc") ? $CI->input->post('templi_code')	: '' // 템플릿코드
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
        					$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/agree/".$md5;
        					// $btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
        				}else{
        					$gurl = base_url()."couponview/index/".$couponid."/".$r->tel_no;
        					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
        					$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/coupon/".$md5;
        					// $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
        				}
                        $shorturl = array();
                        $shorturl["short_url"] = $md5;
                        $shorturl["url"] = $gurl;
                        $CI->db->insert("cb_short_url", $shorturl);
                        $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                        $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                				(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                				values('', '".$r->tel_no."',  ?, ?, ?, ?, ? )";
        			} else { //일반 알림톡
                        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                				(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                			values('', '".$r->tel_no."', ?, ?, ?, ?, ? )";
        			}
                    //log_message("ERROR", "QUERY : ".$sql);
                    $CI->db->query($sql, $param);
                    if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
                }
                return 1;
            }


           public function sendAgentSendCache($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $limit=9999999, $wide = 'N')
           {
               $CI =& get_instance();

               $param = array(
                   "N",	//(($msgType=="at") ? 'N'	: 'Y'),		// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
                   "rc",									// ft, at
                   'N',											// onlySMS $CI->input->post('smsOnly')
                   null,											// P_COM 택배사코드
                   "KT",											// P_INVOICE 송장번호
                   $pf_key,										// 프로필키
                   $group_id,									// msg_sent에서 확인하기 위한 group id
                   $reserveDt,									// 예약발송일시
                   null,											// 쇼핑몰코드
                   $msgType												// 전환발송시 SMS/LMS구분
               );
               $sql = "insert ignore into  ".$this->request_table."
       			(
       				msgid, ad_flag,
       				button1, button2, button3, button4, button5,
       				image_link, image_url,
       				message_type, msg, msg_sms, only_sms, p_com, p_invoice,
       				phn, profile, reg_dt, remark4,  remark5,
       				reserve_dt, s_code, sms_kind, sms_lms_tit, sms_sender, tmpl_id, remark2, wide, supplement, price, currency_type, title
       			)
       			select
       				concat('".$mem_id."_', sc_id, '_D'), ?,
       				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
       				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
       				?, sc_content, sc_lms_content, ? , ?, ?,
       				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
       				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."', '".$wide."', supplement, price, currency_type, title
       			from cb_sc_".$mem_userid.(($limit < 9999999) ? " limit ".$limit : "");
               //			echo '<pre> :: ';print_r($sql);
               $CI->db->query($sql, $param);
               //log_message("ERROR", $sql.json_encode($param));
               $mst_qty = $CI->db->affected_rows();

               $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
               $CI->db->query($sql);
               if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
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
                    // $CI->input->post('templi_cont'),	// 템플릿내용
                    // ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn1')),
                    // ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
                    // ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
                    // ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
                    // ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
                    ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
                    $CI->input->post('msg'),				// LMS/SMS내용
                    $CI->input->post('senderBox'),
                    ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
                    ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
                    // ($msgType=="rc") ? $CI->input->post('templi_code')	: '' // 템플릿코드
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
        					$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/agree/".$md5;
        					// $btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
        				}else{
        					$gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
        					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
        					$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/coupon/".$md5;
        					// $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
        				}
                        $shorturl = array();
                        $shorturl["short_url"] = $md5;
                        $shorturl["url"] = $gurl;
                        $CI->db->insert("cb_short_url", $shorturl);
                        $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                        $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                				(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                          values('".$r->ab_name."', '".$r->ab_tel."', ?, ?, ?, ?, ? )";
                    } else {
                        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                				(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?, ?, ?, ? )";
                    }
                    $CI->db->query($sql, $param);
                    if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
                }
                return 1;
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
                // $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
                // if($url_cnt->cnt > 0) {
                //     $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
                //     $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
                // }
                if(empty($mem_url)) {
                    $mem_url = $CI->input->post('img_link');
                }

                /* 고객 정보를 sendCache에 insert */
                $param = array(
                    // $CI->input->post('templi_cont'),	// 템플릿내용
                    // ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
                    // ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
                    // ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
                    // ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
                    // ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
                    ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
                    $CI->input->post('msg'),				// LMS/SMS내용
                    $CI->input->post('senderBox'),
                    ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
                    ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
                    // ($msgType=="rc") ? $CI->input->post('templi_code')	: '' // 템플릿코드
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
        					$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/agree/".$md5;
        					// $btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
        				}else{
        					$gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
        					$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
        					$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/coupon/".$md5;
        					// $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
        				}
                        $shorturl = array();
                        $shorturl["short_url"] = $md5;
                        $shorturl["url"] = $gurl;
                        $CI->db->insert("cb_short_url", $shorturl);
                        $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                        $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                				(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                				values('".$r->ab_name."', '".$r->ab_tel."', ?, ?, ?, ?, ? )";
                    } else {
                        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                				(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?, ?, ?, ? )";
                    }
                    $CI->db->query($sql, $param);
                    if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
                }
                return 1;
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
                    // $CI->input->post('templi_cont'),			// 템플릿내용
                    // ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
                    // ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
                    // ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
                    // ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
                    // ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
                    ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",		// SMS재발신여부
                    $CI->input->post('msg'),				// LMS/SMS내용
                    $CI->input->post('senderBox'),
                    ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
                    ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
                    // ($msgType=="rc") ? $CI->input->post('templi_code') : ''			// 템플릿코드
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
        					if($agreeid){
        						$gurl = base_url()."agreeview/index/".$agreeid."/".$tel_number[$n];
        						$md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
        						$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/agree/".$md5;
        						// $btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
        					}else{
        						$gurl = base_url()."couponview/index/".$couponid."/".$tel_number[$n];
        						$md5 = md5($gurl).":".crc32($couponid.cdate('His'));
        						$btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/coupon/".$md5;
        						// $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
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
            					(sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
            				values ('', ? , ? , ? , ? , ? , ? )";
                        $CI->db->query($sql, $param);
                        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error()); $err++; } else { $ok++; }
                    }
                }
                return $ok;
            }

            /* 이미지 전송 */
            public function upload_image($fileid, $img_file, $img_type, $img_name)
            {
                $url = $this->rcs_server."/corp/v1/file";
                $cfile = $this->getCurlValue($img_file, $img_type, $img_name);
                $token = $this->get_rcs_token();
                $post   = array('file' => $cfile
                                ,'fileId' => $fileid
                                ,'usageType' => "send"
                                ,'usageService' => "RCS"
                                ,'mimeType' => $img_type);
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
                        'Authorization: Bearer '.$token
                    ),
                ));
                $buffer = curl_exec($curl);
                $cinfo = curl_getinfo($curl);
                curl_close($curl);
                log_message("ERROR", "rcs buffer : ".$buffer);
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

            public function get_rcs_token()
            {
                $url = $this->rcs_server."/corp/v1/token";

                $headers = array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                    // ,
                    // "siteid:".$this->userid,
                    // "auth_key:".$this->apikey
                );

                $postfields = array(
                    "rcsId"=>$this->rcs_id,
                    "rcsSecret"=>$this->rcs_pw,
                    "grantType"=>"clientCredentials"
                );

                $ch = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => false,
                    CURLOPT_POST => 1,
                    CURLOPT_HTTPHEADER =>$headers,
                    CURLOPT_POSTFIELDS => json_encode($postfields),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT => $this->agent,
                    CURLOPT_REFERER => "",
                    CURLOPT_TIMEOUT => 10
                );
                curl_setopt_array($ch, $options);
                $buffer = curl_exec ($ch);
                $cinfo = curl_getinfo($ch);
                log_message("ERROR", $buffer);
                log_message("ERROR", $buffer->data->tokenInfo->accessToken);
                // log_message("ERROR", $cinfo['http_code']);
                curl_close($ch);
                if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return   json_decode($buffer)->data->tokenInfo->accessToken; }
            }


            //개인정보동의 데이타 2021-01-20
        	// public function sendAgentSendCacheAgree($agi_idx, $mem_id, $mst_id, $mem_userid)
            // {
        	// 	$CI =& get_instance();
        	// 	$sql = "
        	// 	INSERT INTO cb_agree_data (
        	// 		  agd_agi_idx /* 개인정보동의번호 */
        	// 		, agd_mem_id /* 회원번호 */
        	// 		, agd_mst_id /* 발송번호 */
        	// 		, agd_phn /* 연락처 */
        	// 		, agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
        	// 	)
        	// 	SELECT
        	// 		  ". $agi_idx ." AS agd_agi_idx /* 개인정보동의번호 */
        	// 		, ". $mem_id ." AS agd_mem_id /* 회원번호 */
        	// 		, ". $mst_id ." AS agd_mst_id /* 발송번호 */
        	// 		, sc_tel /* 연락처 */
        	// 		, '0' AS agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
        	// 	FROM cb_sc_". $mem_userid;
        	// 	//echo '<pre> :: ';print_r($sql);
        	// 	//log_message("ERROR", "/application/libraries/Dhnkakao > Query : ".$sql);
        	// 	$CI->db->query($sql);
        	// 	if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
            // }

            public function NewUploadToSendCache_fail($msgType )
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
                    // $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
                    // if($url_cnt->cnt > 0) {
                    //     $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
                    //     $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
                    // }
                    // if(empty($mem_url)) {
                        $mem_url = $CI->input->post('img_link');
                    // }

                    /* 고객 정보를 sendCache에 insert */
                    $param = array(
                        // $CI->input->post('templi_cont'),	// 템플릿내용
                        // ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
                        // ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
                        // ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
                        // ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
                        // ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
                        ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
                        $CI->input->post('msg'),				// LMS/SMS내용
                        $CI->input->post('senderBox'),
                        ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
                        ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
                        // ($msgType=="rc") ? $CI->input->post('templi_code')	: '' // 템플릿코드
                    );
                    // 고객 필터 추가 2017.12.19
                    //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }

                    $sql = "";

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

                    foreach($list as $r)
                    {
                        if($couponid or $agreeid){
                            $btn1_array = json_decode($btn1);
                            if($agreeid){
                                $gurl = base_url()."agreeview/index/".$agreeid."/".$r->phnno;
                                $md5 = md5($gurl).":".crc32($agreeid.cdate('His'));
                                $btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/agree/".$md5;
                                // $btn1_array[0]->url_pc = base_url()."short_url/agree/".$md5;
                            }else{
                                $gurl = base_url()."couponview/index/".$couponid."/".$r->phnno;
                                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                                $btn1_array[0]->action->urlAction->openUrl->url = base_url()."short_url/coupon/".$md5;
                                // $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                            }
                            $shorturl = array();
                            $shorturl["short_url"] = $md5;
                            $shorturl["url"] = $gurl;
                            $CI->db->insert("cb_short_url", $shorturl);
                            $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                            $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                            $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                                    (sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                                    values('', '".$r->phnno."',  ?, ?, ?, ?, ? )";
                        } else { //일반 알림톡
                            $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
                                    (sc_name , sc_tel, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link)
                                values('', '".$r->phnno."', ?, ?, ?, ?, ? )";
                        }
                        //log_message("ERROR", "QUERY : ".$sql);
                        $CI->db->query($sql, $param);
                        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
                    }
                    return 1;
                }

}
