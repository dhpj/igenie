<button onclick='get_campaigns()'>캠페인 목록 보기</button><br>
<button onclick='get_campaign()'>캠페인 보기</button><br>
<button onclick='get_ch_profile()'>카카오톡 채널 프로필 목록 보기</button><br>
<button onclick='set_campaign()'>캠페인 생성하기</button><br>
<button onclick='get_pixel_sdk()'>픽셀 & SDK 목록 보기</button><br>
<button onclick='put_campaign()'>캠페인 수정하기</button><br>
<button onclick='put_campaign_status()'>캠페인 상태 변경하기</button><br>
<button onclick='delete_campaign()'>캠페인 삭제하기</button><br>
<button onclick='get_campaign_reason()'>캠페인 시스템 정지 사유 보기</button><br>
<button onclick='get_campaign_reasons()'>캠페인 시스템 정지 사유 목록 보기</button><br>
<button onclick='get_ad_groups()'>광고그룹 목록 보기</button><br>
<button onclick='get_ad_group()'>광고그룹 보기</button><br>
<button onclick='set_ad_group()'>개인화 메시지 광고그룹 생성하기</button><br>
<button onclick='put_ad_group()'>개인화 메시지 광고그룹 수정하기</button><br>
<button onclick='put_ad_group_status()'>광고그룹 상태 변경하기</button><br>
<button onclick='delete_ad_group()'>광고그룹 삭제하기</button><br>
<button onclick='get_ad_group_reason()'>광고그룹 시스템 정지 사유 보기</button><br>
<button onclick='get_ad_group_reasons()'>광고그룹 시스템 정지 사유 목록 보기</button><br>
<button onclick='get_creatives()'>소재 목록 보기</button><br>
<button onclick='get_creative()'>소재 보기</button><br>
<button onclick='put_creative_status()'>소재 상태 변경하기</button><br>
<button onclick='delete_creative()'>소재 삭제하기</button><br>
<button onclick='get_creative_reason()'>소재 시스템 정지 사유 보기</button><br>
<button onclick='get_creative_reasons()'>소재 시스템 정지 사유 목록 보기</button><br>
<button onclick='set_personal_msg1()'>개인화 메시지 소재 생성하기(기본텍스트)</button><br>
<button onclick='set_personal_msg2()'>개인화 메시지 소재 생성하기(와이드이미지)</button><br>
<button onclick='set_personal_msg3()'>개인화 메시지 소재 생성하기(와이드리스트)</button><br>
<button onclick='set_personal_img_upload()'>개인화 메시지 이미지 업로드하기</button><br>
<br>
<br>
<button onclick='get_kakaotv_chs()'>카카오TV 채널 목록 보기</button><br>
<button onclick='get_kakaotv_ch()'>카카오TV 채널 상세 보기</button><br>
<button onclick='get_kakaotv_ch_movs()'>카카오TV 채널 영상 목록 보기</button><br>
<button onclick='get_kakaotv_ch_mov()'>카카오TV 채널 영상 상세 보기</button><br>
<br>
<br>
<button onclick='get_ad_account_report()'>광고계정 보고서 보기</button><br>
<button onclick='get_campaign_report()'>캠페인 보고서 보기</button><br>
<button onclick='get_ad_group_report()'>광고그룹 보고서 보기</button><br>
<button onclick='get_creative_report()'>소재 보고서 보기</button><br>
<br>
<br>
<button onclick='send_personal_message_test()'>개인화 메시지 테스트 발송하기</button><br>
<button onclick='send_personal_message()'>개인화 메시지 단건 발송 요청하기</button><br>
<br>
<br>
<button onclick='test1()'>소재 테이블 저장 테스트</button><br>
<textarea id='res' value='' style='height:500px'></textarea>
<script>
    //캠페인 목록 보기
    function get_campaigns(){
        $.ajax({
			url: "/kakao/get_campaigns",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 보기
    function get_campaign(){
        $.ajax({
			url: "/kakao/get_campaign",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1152696'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //카카오톡 채널 프로필 목록 보기
    function get_ch_profile(){
        $.ajax({
			url: "/kakao/get_ch_profile",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 생성하기
    function set_campaign(){
        $.ajax({
			url: "/kakao/set_campaign",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , chid : '_xeJlKb'
              , name : '개인화 메시지 테스트 캠페인_' + timestamp()
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //픽셀 & SDK 목록 보기
    function get_pixel_sdk(){
        $.ajax({
			url: "/kakao/get_pixel_sdk",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 수정하기(개인화 메시지 경우 안됌)
    function put_campaign(){
        $.ajax({
			url: "/kakao/put_campaign",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1117474'
              , name : '테스트 입니다123'
              , trackId : '6328570191426207199'
              , dailyBudgetAmount : ''
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 상태 변경하기
    function put_campaign_status(){
        $.ajax({
			url: "/kakao/put_campaign_status",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1120665'
              , config : 'OFF'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 삭제하기
    function delete_campaign(){
        $.ajax({
			url: "/kakao/delete_campaign",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1124882'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 시스템 정지 사유 보기
    function get_campaign_reason(){
        $.ajax({
			url: "/kakao/get_campaign_reason",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1124760'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 시스템 정지 사유 목록 보기
    function get_campaign_reasons(){
        $.ajax({
			url: "/kakao/get_campaign_reasons",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1124760'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 목록 보기
    function get_ad_groups(){
        $.ajax({
			url: "/kakao/get_ad_groups",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1152696'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 보기
    function get_ad_group(){
        $.ajax({
			url: "/kakao/get_ad_group",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '3065954'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 광고그룹 생성하기
    function set_ad_group(){
        $.ajax({
			url: "/kakao/set_ad_group",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1120665'
              , name : '개인화 메시지 테스트 광고그룹_' + timestamp()
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 광고그룹 수정하기
    function put_ad_group(){
        $.ajax({
			url: "/kakao/put_ad_group",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : '1120665'
              , aid : '2996958'
              , name : '개인화 메시지 테스트 광고그룹_' + timestamp()
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 상태 변경하기
    function put_ad_group_status(){
        $.ajax({
			url: "/kakao/put_ad_group_status",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
              , config : 'OFF'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 삭제하기
    function delete_ad_group(){
        $.ajax({
			url: "/kakao/delete_ad_group",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2997049'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 시스템 정지 사유 보기
    function get_ad_group_reason(){
        $.ajax({
			url: "/kakao/get_ad_group_reason",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 시스템 정지 사유 목록 보기
    function get_ad_group_reasons(){
        $.ajax({
			url: "/kakao/get_ad_group_reasons",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 목록 보기
    function get_creatives(){
        $.ajax({
			url: "/kakao/get_creatives",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '3065954'
              , config : ''
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 보기
    function get_creative(){
        $.ajax({
			url: "/kakao/get_creative",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23457438'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 상태 변경하기
    function put_creative_status(){
        $.ajax({
			url: "/kakao/put_creative_status",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23457438'
              , config : 'ON'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 삭제하기
    function delete_creative(){
        $.ajax({
			url: "/kakao/delete_creative",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23458796'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 시스템 정지 사유 보기
    function get_creative_reason(){
        $.ajax({
			url: "/kakao/get_creative_reason",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23458796'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 시스템 정지 사유 목록 보기
    function get_creative_reasons(){
        $.ajax({
			url: "/kakao/get_creative_reasons",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23458796'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 소재 생성하기(기본텍스트)
    function set_personal_msg1(){
        var imageFile = {
            path : '/var/www/igenie/uploads/products/2023/09/bcbde78730cb418fb59fcdeff17c1484.jpg'
          , name : 'bcbde78730cb418fb59fcdeff17c1484.jpg'
          , type : 'image/jpg'
        }

        var buttons = [];
        buttons.push({
            ordering : '0'
          , pcLandingUrl : 'http://naver.com'
          , mobileLandingUrl : 'http://naver.com'
          , title : '버튼테스트1'
          , landingType : 'LANDING_URL'
        });

        buttons.push({
            ordering : '1'
          , pcLandingUrl : 'http://naver.com'
          , mobileLandingUrl : 'http://naver.com'
          , title : '버튼테스트2'
          , landingType : 'LANDING_URL'
        });

        var items = [];
        items.push({
            landingType : 'CHANNEL_POST'
          , channelPostId : '102056777'
          , imageFile : {
                path : '/var/www/igenie/uploads/products/2023/09/bcbde78730cb418fb59fcdeff17c1484.jpg'
              , name : 'bcbde78730cb418fb59fcdeff17c1484.jpg'
              , type : 'image/jpg'
            }
        });

        var format = 'BASIC_TEXT_MESSAGE';
        var title = '테테테테스스스스트';
        var item_flag = false;
        var adFlag = 'true';

        var para = {
            format : format
          , name : '소재 테이블 저장 테스트01'
          , profileId : '_xeJlKb'
          , adFlag : adFlag
          , csInfo : 'csInfo'
          , image : 1
        };

        if (format == 'BASIC_TEXT_MESSAGE'){
            para.title = title;

            if (item_flag){
                para.image = {valueWithVariable : '${image_url1}'};
            }

            if (Object.keys(imageFile).length !== 0){
                para.imageFile = imageFile;
            }
        } else if (format == 'WIDE_MESSAGE'){
            para.title = title;
            para.item = items;
        } else if (format == 'WIDE_LIST_MESSAGE'){
            para.item = items;
        }

        if (buttons.length !== 0){
            para.button = buttons;
        }

        $.ajax({
			url: "/kakao/set_personal_msg1",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
              , para : para
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 소재 생성하기(와이드이미지)
    function set_personal_msg2(){
        $.ajax({
			url: "/kakao/set_personal_msg2",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 소재 생성하기(와이드리스트)
    function set_personal_msg3(){
        $.ajax({
			url: "/kakao/set_personal_msg3",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 소재 생성하기(와이드리스트)
    function set_personal_img_upload(){
        $.ajax({
			url: "/kakao/set_personal_img_upload",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //카카오TV 채널 목록 보기
    function get_kakaotv_chs(){
        $.ajax({
			url: "/kakao/get_kakaotv_chs",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //카카오TV 채널 상세 보기
    function get_kakaotv_ch(){
        $.ajax({
			url: "/kakao/get_kakaotv_ch",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , chid : '10003867'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //카카오TV 채널 영상 목록 보기
    function get_kakaotv_ch_movs(){
        $.ajax({
			url: "/kakao/get_kakaotv_ch_movs",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , chid : '10003867'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //카카오TV 채널 영상 목록 보기
    function get_kakaotv_ch_mov(){
        $.ajax({
			url: "/kakao/get_kakaotv_ch_mov",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , chid : '10003867'
              , liid : '441916938'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고계정 보고서 보기
    function get_ad_account_report(){
        var para = {
            adid : '<?=config_item('adid')?>'
          , datePreset : 'THIS_MONTH'
          , level : 'AD_ACCOUNT'
          , metricsGroup : 'MESSAGE'
        };
        $.ajax({
			url: "/kakao/get_ad_account_report",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , para : para
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //캠페인 보고서 보기
    function get_campaign_report(){
        var para = {
            adid : '<?=config_item('adid')?>'
          , chid : '1120665'
          // , datePreset : 'THIS_MONTH'
          , start : '20230914'
          , end : '20230916'
          , level : 'AD_ACCOUNT'
          , metricsGroup : 'MESSAGE'
        };
        $.ajax({
			url: "/kakao/get_campaign_report",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , para : para
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //광고그룹 보고서 보기
    function get_ad_group_report(){
        var para = {
            adid : '<?=config_item('adid')?>'
          , aid : '1120665'
          // , datePreset : 'THIS_MONTH'
          , start : '20230914'
          , end : '20230916'
          , level : 'AD_ACCOUNT'
          , metricsGroup : 'MESSAGE'
        };
        $.ajax({
			url: "/kakao/get_ad_group_report",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , para : para
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 보고서 보기
    function get_creative_report(){
        var para = {
            adid : '<?=config_item('adid')?>'
          , cid : '1120665'
          // , datePreset : 'THIS_MONTH'
          , start : '20230914'
          , end : '20230916'
          , level : 'AD_ACCOUNT'
          , metricsGroup : 'MESSAGE'
        };
        $.ajax({
			url: "/kakao/get_creative_report",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , para : para
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 테스트 발송하기
    function send_personal_message_test(){
        $.ajax({
			url: "/kakao/send_personal_message_test",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23457438'
              , phone : '010-9344-0043'
              , var_image_url1 : 'http://t1.daumcdn.net/b2/personalMessage/442247/07c7c796e19111cee4f6bfd6e741e4dd'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //개인화 메시지 단건 발송 요청하기
    function send_personal_message(){
        $.ajax({
			url: "/kakao/send_personal_message",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , crid : '23457438'
              , phone : '010-9344-0043'
              , var_image_url1 : 'http://igenie.co.kr/uploads/products/2023/09/bcbde78730cb418fb59fcdeff17c1484.jpg'
            },
			success: function (json) {
                $('#res').val(json.data);
			}
		});
    }

    //소재 테이블 저장 테스트
    function test1(){
        var imageFile = {
            path : '/var/www/igenie/uploads/products/2023/09/bcbde78730cb418fb59fcdeff17c1484.jpg'
          , name : 'bcbde78730cb418fb59fcdeff17c1484.jpg'
          , type : 'image/jpg'
        }

        var buttons = [];
        buttons.push({
            ordering : '0'
          , pcLandingUrl : 'http://naver.com'
          , mobileLandingUrl : 'http://naver.com'
          , title : '버튼테스트1'
          , landingType : 'LANDING_URL'
        });

        buttons.push({
            ordering : '1'
          , pcLandingUrl : 'http://naver.com'
          , mobileLandingUrl : 'http://naver.com'
          , title : '버튼테스트2'
          , landingType : 'LANDING_URL'
        });

        var items = [];
        items.push({
            landingType : 'CHANNEL_POST'
          , channelPostId : '1000000'
          , imageFile : {
                path : '/var/www/igenie/uploads/products/2023/09/bcbde78730cb418fb59fcdeff17c1484.jpg'
              , name : 'bcbde78730cb418fb59fcdeff17c1484.jpg'
              , type : 'image/jpg'
            }
        });

        var format = 'BASIC_TEXT_MESSAGE';
        var title = '테테테테스스스스트';
        var item_flag = false;
        var adFlag = 'true';

        var para = {
            format : format
          , name : '소재 테이블 저장 테스트01'
          , profileId : '_xeJlKb'
          , adFlag : adFlag
          , csInfo : 'csInfo'
        };

        if (format == 'BASIC_TEXT_MESSAGE'){
            para.title = title;

            if (item_flag){
                para.image = {valueWithVariable : '${image_url1}'};
            }

            if (Object.keys(imageFile).length !== 0){
                para.imageFile = imageFile;
            }
        } else if (format == 'WIDE_MESSAGE'){
            para.title = title;
            para.item = items;
        } else if (format == 'WIDE_LIST_MESSAGE'){
            para.item = items;
        }

        if (buttons.length !== 0){
            para.button = buttons;
        }

        $.ajax({
			url: "/kakao/creative_save",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , mem_id : '2'
              , adid : '<?=config_item('adid')?>'
              , aid : '2996959'
              , para : para
            },
			success: function (json) {
                if (Number(json.code)){
                    alert('생성 성공');
                } else {
                    alert('생성 실패');
                    console.log(json.res_code + ' / ' + json.msg + ' / ' + json.detailCode);
                }
			}
		});
    }







    function timestamp(){
        function pad(n) { return n<10 ? "0"+n : n }
        d=new Date()
        return d.getFullYear()+""+
        pad(d.getMonth()+1)+""+
        pad(d.getDate())+""+
        pad(d.getHours())+""+
        pad(d.getMinutes())+""+
        pad(d.getSeconds())
    }

</script>
