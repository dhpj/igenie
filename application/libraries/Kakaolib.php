<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Kakaolib extends CI_Controller {

    public $urls = array(
        'token' => 'https://kauth.kakao.com/oauth/token'
      , 'token_info' => 'https://kapi.kakao.com/v1/user/access_token_info'
      , 'ad_account' => 'https://apis.moment.kakao.com/openapi/v4/adAccounts'
      , 'campaign' => 'https://apis.moment.kakao.com/openapi/v4/campaigns'
      , 'ch_profile' => 'https://apis.moment.kakao.com/openapi/v4/adAccounts/channel/profiles'
      , 'pixel_sdk' => 'https://apis.moment.kakao.com/openapi/v4/adAccounts/trackers'
      , 'ad_groups' => 'https://apis.moment.kakao.com/openapi/v4/adGroups'
      , 'creative' => 'https://apis.moment.kakao.com/openapi/v4/creatives'
      , 'kakaotv_ch' => 'https://apis.moment.kakao.com/openapi/v4/messages/kakaotv/channels'
      , 'personal_img_video' => 'https://apis.moment.kakao.com/openapi/v4/messages/personal'
      , 'message' => 'https://apis.moment.kakao.com/openapi/v4/messages/creatives'
    );

    public $parameters = array(
        'date' => array(
            '날짜'
          , '${date1}'
          , '${date2}'
          , '${date3}'
          , '${date4}'
        )
      , 'site_name' => array(
            '사이트 이름'
          , '${site_name1}'
        )
      , 'brand_name' => array(
            '브랜드 이름'
          , '${brand_name1}'
        )
      , 'user_name' => array(
            '고객 이름'
          , '${user_name1}'
        )
      , 'user_id' => array(
            '고객 ID'
          , '${user_id1}'
        )
      , 'user_rating' => array(
            '고객 등급'
          , '${user_rating1}'
        )
      , 'available_point' => array(
            '적립금'
          , '${available_point1}'
        )
      , 'available_coupon' => array(
            '쿠폰 개수'
          , '${available_coupon1}'
      )
      , 'product_id' => array(
            '상품 ID'
          , '${product_id1}'
          , '${product_id2}'
          , '${product_id3}'
          , '${product_id4}'
          , '${product_id5}'
          , '${product_id6}'
          , '${product_id7}'
      )
      , 'product_name' => array(
            '상품명'
          , '${product_name1}'
          , '${product_name2}'
          , '${product_name3}'
          , '${product_name4}'
          , '${product_name5}'
          , '${product_name6}'
          , '${product_name7}'
      )
      , 'price' => array(
            '상품 가격 - 정가'
          , '${price1}'
          , '${price2}'
          , '${price3}'
          , '${price4}'
          , '${price5}'
          , '${price6}'
          , '${price7}'
      )
      , 'sal_price' => array(
            '상품 가격 - 세일가'
          , '${sale_price1}'
          , '${sale_price2}'
          , '${sale_price3}'
          , '${sale_price4}'
          , '${sale_price5}'
          , '${sale_price6}'
          , '${sale_price7}'
      )
      , 'discount_amount' => array(
            '할인금액'
          , '${discount_amount1}'
          , '${discount_amount2}'
          , '${discount_amount3}'
          , '${discount_amount4}'
          , '${discount_amount5}'
          , '${discount_amount6}'
          , '${discount_amount7}'
      )
      , 'discount_percent' => array(
            '할인율'
          , '${discount_percent1}'
          , '${discount_percent2}'
          , '${discount_percent3}'
          , '${discount_percent4}'
          , '${discount_percent5}'
          , '${discount_percent6}'
          , '${discount_percent7}'
      )
      // , 'image_url' => array(
      //       '${image_url1}'
      //     , '${image_url2}'
      //     , '${image_url3}'
      //     , '${image_url4}'
      //     , '${image_url5}'
      //     , '${image_url6}'
      //     , '${image_url7}'
      // )
      // , 'video_url' => array(
      //       '${video_url}'
      // )
      // , 'mobile_url' => array(
      //       '${mobile_url1}'
      //     , '${mobile_url2}'
      //     , '${mobile_url3}'
      // )
      // , 'pc_url' => array(
      //       '${pc_url1}'
      //     , '${pc_url2}'
      //     , '${pc_url3}'
      // )
    );

    function __construct()
    {
        $this->CI = & get_instance();
    }

    //토큰 받기
    public function get_token($code){
        $url = $this->urls['token'] . '?grant_type=authorization_code&client_id='.config_item('kakao_conf')['dhn_rest_key'].'&redirect_uri='.config_item('kakao_conf')['dhn_login_redirect_uri'].'&code='.$code;
        if(empty($url)){ return false ; }
        $headers = array();
    	$purl = parse_url($url);
        $fields = array();
    	if( !empty($purl['query']) && trim($purl['query']) != ''){
    		$fields = explode("&",$purl['query']);
    	}
        return $this->go_curl($url, 'post', $headers, $fields);
    }

    //토큰 정보 보기
    public function get_token_info($token){
        $url = $this->urls['token_info'];
        $headers = array(
            'Authorization: Bearer ' . $token
        );
        $fields = array();
        return $this->go_curl($url, '', $headers, $fields);
    }

    //토큰 갱신하기
    public function put_token($token){
        $url = $this->urls['token'] . '?grant_type=refresh_token&client_id='.config_item('kakao_conf')['dhn_rest_key'].'&refresh_token='.$token;
        $headers = array();
        $purl = parse_url($url);
        $fields = array();
    	if( !empty($purl['query']) && trim($purl['query']) != ''){
    		$fields = explode("&",$purl['query']);
    	}
        return $this->go_curl($url, 'post', $headers, $fields);
    }

    //캠페인 목록 보기 & 캠페인 보기
    public function get_campaigns($token, $adid, $cid = ''){
        if(empty($cid)){
            $url = $this->urls['campaign'] . '?config=ON,OFF';
        } else {
            $url = $this->urls['campaign'] . '/' . $cid;
        }
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );
        $fields = array();
    	return $this->go_curl($url, '', $headers, $fields);
    }

    //카카오톡 채널 프로필 목록 보기
    public function get_ch_profile($token, $adid){
        $url = $this->urls['ch_profile'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );
        $fields = array();
    	return $this->go_curl($url, '', $headers, $fields);
    }

    //캠페인 생성하기
    public function set_campaign($token, $adid, $chid, $name){
        $url = $this->urls['campaign'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
             'campaignTypeGoal' => array(
                'campaignType' => 'PERSONAL_MESSAGE' //고정
              , 'goal' => 'REACH' //고정
            )
          , 'objective' => array(
                'type' => 'TALK_CHANNEL' //고정
              , 'value' => $chid //채널 id값
            )
        );

        if (!empty($name)){
            $fields['name'] = $name;
        }

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'post', $headers, $fields);
    }

    //픽셀 & SDK 목록 보기
    public function get_pixel_sdk($token, $adid){
        $url = $this->urls['pixel_sdk'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );
        $fields = array();
    	return $this->go_curl($url, '', $headers, $fields);
    }

    //캠페인 수정하기(개인화 메시지 경우 안됌)
    public function put_campaign($token, $adid, $cid, $name = '', $trackId = '', $dailyBudgetAmount = ''){
        $url = $this->urls['campaign'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'id' => $cid
        );
        $fields['name'] = !empty($name) ? $name : '';
        $fields['trackId'] = !empty($trackId) ? $trackId : null;
        $fields['dailyBudgetAmount'] = !empty($dailyBudgetAmount) ? $dailyBudgetAmount : null;

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'put', $headers, $fields);
    }

    //캠페인 상태 변경하기
    public function put_campaign_status($token, $adid, $cid, $config){
        $url = $this->urls['campaign'] . '/onOff';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'id' => $cid
          , 'config' => $config
        );

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'put', $headers, $fields);
    }

    //캠페인 삭제하기
    public function delete_campaign($token, $adid, $cid){
        $url = $this->urls['campaign'] . '/' . $cid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'id' => $cid
        );

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'delete', $headers, $fields);
    }

    //캠페인 시스템 정지 사유 보기
    public function get_campaign_reason($token, $adid, $cid){
        $url = $this->urls['campaign'] . '/' . $cid . '/latestSystemConfigHistory';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //캠페인 시스템 정지 사유 목록 보기
    public function get_campaign_reasons($token, $adid, $cid){
        $url = $this->urls['campaign'] . '/' . $cid . '/systemConfigHistories';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //광고그룹 목록 보기
    public function get_ad_groups($token, $adid, $cid){
        $url = $this->urls['ad_groups'] . '?campaignId=' . $cid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );
        $fields = array();
    	return $this->go_curl($url, '', $headers, $fields);
    }

    //광고그룹 보기
    public function get_ad_group($token, $adid, $aid){
        $url = $this->urls['ad_groups'] . '/' . $aid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );
        $fields = array();
    	return $this->go_curl($url, '', $headers, $fields);
    }

    //개인화 메시지 광고그룹 생성하기
    public function set_ad_group($token, $adid, $cid, $name){
        $url = $this->urls['ad_groups'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'campaign' => array(
                'id' =>  $cid
            )
        );

        if (!empty($name)){
            $fields['name'] = $name;
        }

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'post', $headers, $fields);
    }

    //개인화 메시지 광고그룹 수정하기
    public function put_ad_group($token, $adid, $cid, $aid, $name){
        $url = $this->urls['ad_groups'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'id' => $aid
          , 'campaign' => array(
                'id' =>  $cid
            )
          , 'name' => $name
        );

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'put', $headers, $fields);
    }

    //광고그룹 상태 변경하기
    public function put_ad_group_status($token, $adid, $aid, $config){
        $url = $this->urls['ad_groups'] . '/onOff';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'id' => $aid
          , 'config' => $config
        );

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'put', $headers, $fields);
    }

    //광고그룹 삭제하기
    public function delete_ad_group($token, $adid, $aid){
        $url = $this->urls['ad_groups'] . '/' . $aid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, 'delete', $headers, $fields);
    }

    //광고그룹 시스템 정지 사유 보기
    public function get_ad_group_reason($token, $adid, $aid){
        $url = $this->urls['ad_groups'] . '/' . $aid . '/latestSystemConfigHistory';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //광고그룹 시스템 정지 사유 목록 보기
    public function get_ad_group_reasons($token, $adid, $aid){
        $url = $this->urls['ad_groups'] . '/' . $aid . '/systemConfigHistories';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //소재 목록 보기
    public function get_creatives($token, $adid, $aid, $config){
        $url = $this->urls['creative'] . '?adGroupId=' . $aid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        // $fields = array(
        //     'adGroupId' => $aid
        // );

        $fields = array();

        if (!empty($config)){
            // $fields['config'] = $config;
            $url .= '&config=' . $config;
        }

        // $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, '', $headers, $fields);
    }

    //소재 보기
    public function get_creative($token, $adid, $crid){
        $url = $this->urls['creative'] . '/' . $crid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //소재 상태 변경하기
    public function put_creative_status($token, $adid, $crid, $config){
        $url = $this->urls['creative'] . '/' . 'onOff';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'id' => $crid
          , 'config' => $config
        );

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'put', $headers, $fields);
    }

    //소재 삭제하기
    public function delete_creative($token, $adid, $crid){
        $url = $this->urls['creative'] . '/' . $crid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        return $this->go_curl($url, 'delete', $headers, $fields);
    }

    //소재 시스템 정지 사유 보기
    public function get_creative_reason($token, $adid, $crid){
        $url = $this->urls['creative'] . '/' . $crid . '/systemConfigHistory';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //소재 시스템 정지 사유 목록 보기
    public function get_creative_reasons($token, $adid, $crid){
        $url = $this->urls['creative'] . '/' . $crid . '/systemConfigHistories';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }



    //개인화 메시지 소재 생성하기
    public function set_personal_msg($token, $adid, $aid, $para, $method){
        $url = $this->urls['creative'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );

        $fields = array(
            'adGroupId' => $aid
          , 'format' => $para['format']
        );
        if (!empty($para['name'])){ $fields['name'] = $para['name']; }

        //공용 파라미터
        $fields['messageElement.creativeFormat'] = $para['format'];
        $fields['messageElement.profileId'] = $para['profileId'];
        $fields['messageElement.shareFlag'] = 'false';
        $fields['messageElement.adFlag'] = $para['adFlag'];
        $fields['messageElement.csInfo'] = $para['csInfo'];

        if ($para['format'] == 'BASIC_TEXT_MESSAGE'){
            $fields['messageElement.title'] = $para['title'];
            if ($para['image']){
                if (strpos($para['imagePara'], 'image')){
                    $fields['messageElement.image.valueWithVariable'] = $para['imagePara'];
                } else if (strpos($para['imagePara'], 'video')){
                    $fields['messageElement.videoMeta.valueWithVariable'] = $para['imagePara'];
                }
            } else {
                if ($para['video']){
                    $fields['messageElement.videoMeta.id'] = $para['videoId'];
                    $fields['messageElement.videoMeta.thumbnail'] = $para['videoThumbnail'];
                    $fields['messageElement.videoMeta.isLoad'] = 'true';
                    $fields['messageElement.videoMeta.isLive'] = 'false';
                    $fields['messageElement.videoMeta.isLink'] = 'true';
                } else {
                    if (!empty($para['imageFile'])){ $fields['messageElement.imageFile'] = curl_file_create($para['imageFile']['path'], $para['imageFile']['type'], basename($para['imageFile']['name'])); }
                }
            }
            if ($para['item']){
                $fields['messageElement.itemAssetGroups[0].videoMeta.id'] = $para['item']['videoMeta']['id'];
                $fields['messageElement.itemAssetGroups[0].videoMeta.thumbnail'] = $para['item']['videoMeta']['thumbnail'];
                $fields['messageElement.itemAssetGroups[0].videoMeta.isLoad'] = 'true';
                $fields['messageElement.itemAssetGroups[0].videoMeta.isLive'] = 'false';
                $fields['messageElement.itemAssetGroups[0].videoMeta.isLink'] = 'true';
            }
        } else if ($para['format'] == 'WIDE_MESSAGE'){
            $fields['messageElement.title'] = $para['title'];
            foreach ($para['item'] as $key => $a){
                $fields['messageElement.itemAssetGroups[0].landingType'] = $a['landingType'];
                $fields['messageElement.itemAssetGroups[0].channelPostId'] = $a['channelPostId'];
                // $fields['messageElement.itemAssetGroups[0].mobileLandingUrl'] = $a['mobileLandingUrl'];
                // $fields['messageElement.itemAssetGroups[0].pcLandingUrl'] = $a['pcLandingUrl'];
                $fields['messageElement.itemAssetGroups[0].imageFile'] = curl_file_create($a['imageFile']['path'], $a['imageFile']['type'], basename($a['imageFile']['name']));
            }
        } else if ($para['format'] == 'WIDE_LIST_MESSAGE'){

        }

        if (!empty($para['button'])){
            foreach($para['button'] as $key => $a){
                $fields['messageElement.buttonAssetGroups[' . $key . '].ordering'] = $a['ordering'];
                $fields['messageElement.buttonAssetGroups[' . $key . '].pcLandingUrl'] = $a['pcLandingUrl'];
                $fields['messageElement.buttonAssetGroups[' . $key . '].mobileLandingUrl'] = $a['mobileLandingUrl'];
                $fields['messageElement.buttonAssetGroups[' . $key . '].title'] = $a['title'];
                $fields['messageElement.buttonAssetGroups[' . $key . '].landingType'] = $a['landingType'];
                if ($a['landingType'] == 'CHANNEL_COUPON'){ $fields['messageElement.buttonAssetGroups[' . $key . '].channelCouponId'] = $a['channelCouponId']; }
                if ($a['landingType'] == 'CHANNEL_POST'){ $fields['messageElement.buttonAssetGroups[' . $key . '].channelPostId'] = $a['channelPostId']; }
                if ($a['landingType'] == 'BIZ_FORM'){ $fields['messageElement.buttonAssetGroups[' . $key . '].bizFormId'] = $a['bizFormId']; }
                if ($a['landingType'] == 'AD_VIEW'){ $fields['messageElement.buttonAssetGroups[' . $key . '].adViewId'] = $a['adViewId']; }
            }
        }
        foreach($fields as $key => $a){
            log_message('error', $key . '  /  ' . $a);
            if (is_array($a)){
                foreach($a as $key2 => $b){
                    log_message('error', $key2 . '  /  ' . $b);
                    if (is_array($b)){
                        foreach($b as $key3 => $c){
                            log_message('error', $key3 . '  /  ' . $c);
                        }
                    }
                }
            }
        }

        return $this->go_curl($url, $method, $headers, $fields);
    }

    //개인화 메시지 이미지 업로드하기
    public function set_personal_img_upload($token, $adid, $para){
        $url = $this->urls['personal_img_video'] . '/images/upload';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
        );
        $files = array();
        foreach($para as $a){
            array_push($files, curl_file_create($a['path'], $a['type'], basename($a['name'])));

        }

        $fields['files'] = $files[0];


        return $this->go_curl($url, 'post', $headers, $fields);
    }


    //카카오TV 채널 목록 보기
    public function get_kakaotv_chs($token){
        $url = $this->urls['kakaotv_ch'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //카카오TV 채널 상세 보기
    public function get_kakaotv_ch($token, $chid){
        $url = $this->urls['kakaotv_ch'] . '/' . $chid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //카카오TV 채널 영상 목록 보기
    public function get_kakaotv_ch_movs($token, $chid){
        $url = $this->urls['kakaotv_ch'] . '/' . $chid . '/clipLinks';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //카카오TV 채널 영상 상세 보기
    public function get_kakaotv_ch_mov($token, $chid, $liid){
        $url = $this->urls['kakaotv_ch'] . '/' . $chid . '/clipLinks/' . $liid;
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'Content-Type: application/json'
        );

        $fields = array();

        return $this->go_curl($url, '', $headers, $fields);
    }

    //광고계정 보고서 보기
    public function get_ad_account_report($token, $para){
        $url = $this->urls['ad_account'] . '/report' . '?level=' . $para['level'] . '&metricsGroup=' . $para['metricsGroup'];
        if (!empty($para['datePreset'])) $url .= '&datePreset=' . $para['datePreset'];
        if (!empty($para['timeUnit'])) $url .= '&timeUnit=' . $para['timeUnit'];
        if (!empty($para['start'])) $url .= '&start=' . $para['start'];
        if (!empty($para['end'])) $url .= '&end=' . $para['end'];
        if (!empty($para['dimension'])) $url .= '&dimension=' . $para['dimension'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $para['adid']
        );
        $purl = parse_url($url);
        $fields = array();
    	// if( !empty($purl['query']) && trim($purl['query']) != ''){
    	// 	$fields = explode("&",$purl['query']);
    	// }
        return $this->go_curl($url, '', $headers, $fields);
    }

    //캠페인 보고서 보기
    public function get_campaign_report($token, $para){
        $url = $this->urls['campaign'] . '/report' . '?campaignId=' . $para['chid'] . '&level=' . $para['level'] . '&metricsGroup=' . $para['metricsGroup'];
        if (!empty($para['datePreset'])) $url .= '&datePreset=' . $para['datePreset'];
        if (!empty($para['timeUnit'])) $url .= '&timeUnit=' . $para['timeUnit'];
        if (!empty($para['start'])) $url .= '&start=' . $para['start'];
        if (!empty($para['end'])) $url .= '&end=' . $para['end'];
        if (!empty($para['dimension'])) $url .= '&dimension=' . $para['dimension'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $para['adid']
        );
        $purl = parse_url($url);
        $fields = array();
        return $this->go_curl($url, '', $headers, $fields);
    }

    //광고그룹 보고서 보기
    public function get_ad_group_report($token, $para){
        $url = $this->urls['ad_groups'] . '/report' . '?adGroupId=' . $para['aid'] . '&level=' . $para['level'] . '&metricsGroup=' . $para['metricsGroup'];
        if (!empty($para['datePreset'])) $url .= '&datePreset=' . $para['datePreset'];
        if (!empty($para['timeUnit'])) $url .= '&timeUnit=' . $para['timeUnit'];
        if (!empty($para['start'])) $url .= '&start=' . $para['start'];
        if (!empty($para['end'])) $url .= '&end=' . $para['end'];
        if (!empty($para['dimension'])) $url .= '&dimension=' . $para['dimension'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $para['adid']
        );
        $fields = array();
        return $this->go_curl($url, '', $headers, $fields);
    }

    //소재 보고서 보기
    public function get_creative_report($token, $para){
        $url = $this->urls['creative'] . '/report' . '?creativeId=' . $para['cid'] . '&metricsGroup=' . $para['metricsGroup'];
        if (!empty($para['datePreset'])) $url .= '&datePreset=' . $para['datePreset'];
        if (!empty($para['timeUnit'])) $url .= '&timeUnit=' . $para['timeUnit'];
        if (!empty($para['start'])) $url .= '&start=' . $para['start'];
        if (!empty($para['end'])) $url .= '&end=' . $para['end'];
        if (!empty($para['dimension'])) $url .= '&dimension=' . $para['dimension'];
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $para['adid']
        );
        $fields = array();
        return $this->go_curl($url, '', $headers, $fields);
    }

    //개인화 메시지 테스트 발송하기
    public function send_personal_message_test($token, $adid, $crid, $para){
        $url = $this->urls['message'] . '/' . $crid . '/sendTestPersonalMessage';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'phoneNumber' => $para['receiverKey']
        );

        if(!empty($para['variables'])){
            $fields['variables'] = $para['variables'];
        }

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'post', $headers, $fields);
    }

    //개인화 메시지 단건 발송 요청하기
    public function send_personal_message($token, $adid, $crid, $para){
        $url = $this->urls['message'] . '/' . $crid . '/sendPersonalMessage';
        $headers = array(
            'Authorization: Bearer ' . $token
          , 'adAccountId: ' . $adid
          , 'Content-Type: application/json'
        );

        $fields = array(
            'messageSerialNumber' => $para['messageSerialNumber']
          , 'receiverType' => 'PHONE_NUMBER'
          , 'receiverKey' => $para['receiverKey']
        );

        if(!empty($para['variables'])){
            $fields['variables'] = $para['variables'];
        }

        $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);

        return $this->go_curl($url, 'post', $headers, $fields);
    }










    function go_curl($url, $method, $headers, $fields){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post'){ curl_setopt($curl, CURLOPT_POST, 1); }
        if ($method == 'put'){ curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT'); }
        if ($method == 'delete'){ curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE'); }
        if( count($fields) > 0){ curl_setopt($curl, CURLOPT_POSTFIELDS, $fields); }
    	if( count($headers) > 0){ curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); }

        ob_start(); // prevent any output
    	$data = curl_exec($curl);
    	ob_end_clean(); // stop preventing output

    	if (curl_error($curl)){ return false;}

    	curl_close($curl);
        // log_message('error', $data);
        $data = json_decode($data);
    	return $data;
    }

}
