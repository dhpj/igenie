<script>
    var paraImageFlag = false;

    function valid_title(t){
        if ($(t).val().trim() == ''){
            showSnackbar('홍보문구를 입력해주세요.', 2000);
            $(t).focus();
            return false;
        } else {
            return true;
        }
    }

    function valid_button(t){
        if ($(t).val().trim() == ''){
            showSnackbar('버튼문구를 입력해주세요.', 2000);
            $(t).focus();
            return false;
        } else {
            return true;
        }
    }

    function valid_adgroup(){
        if ($('#select_adg') == undefined){
            showSnackbar('광고그룹을 생성해주세요.', 2000);
            return false;
        } else {
            return true;
        }
    }

    //개인화 메시지 소재 생성하기(기본텍스트)
    function set_personal_msg1(format){
        if (!valid_adgroup()){
            return;
        }

        if (!valid_title($('#c_title'))){
            return;
        }

        var imageFile = {
            path : $('#select_img').data('path')
          , name : $('#select_img').data('name')
          , type : $('#select_img').data('type')
        }

        var stop_flag = false;
        var buttons = [];
        if ($('#chk_btn1').prop('checked')){
            if (!valid_button($('#txt_btn1'))){
                stop_flag = true;
                return;
            }
            buttons.push({
                ordering : '0'
              // , pcLandingUrl : 'http://naver.com'
              , mobileLandingUrl : '${mobile_url1}'
              , title : $('#txt_btn1').val()
              , landingType : 'LANDING_URL'
            });
            if ($('#chk_btn2').prop('checked')){
                if (!valid_button($('#txt_btn2'))){
                    stop_flag = true;
                    return;
                }
                buttons.push({
                    ordering : '1'
                  // , pcLandingUrl : 'http://naver.com'
                  , mobileLandingUrl : '${mobile_url2}'
                  , title : $('#txt_btn2').val()
                  , landingType : 'LANDING_URL'
                });
            }
        }

        if (stop_flag){
            return;
        }

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

        var format = format;
        var title = $('#c_title').val();


        var para = {
            format : format
          , name : $('#c_name').val()
          , profileId : '_xeJlKb'
          , adFlag : $('#chk_adFlag').prop('checked') ? 'true' : 'false'
          , csInfo : 'csInfo'
        };

        if (format == 'BASIC_TEXT_MESSAGE'){
            para.title = title;
            if (paraImageFlag){
                para.image = 1;
                para.imagePara = $('#m_name').html();
            }

            if (imgOrVideoFlag == 'i'){
                para.imageFile = imageFile;
            } else if (imgOrVideoFlag == 'v'){
                para.video = 1;
                para.videoId = $('#select_video').data('liid');
                para.videoThumbnail = $('#select_video').data('thumb');
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
              , aid : $('#select_adg').val()
              , para : para
              , pa_id : $("#select_adg option:selected").data('id')
            },
            success: function (json) {
                if (json.data.id != undefined){
                    alert('소재가 생성되었습니다.');
                    location.reload(true);
                } else {
                    alert('소재 생성에 실패했습니다. 관리자에게 문의해주세요.');
                }
            }
        });
    }

</script>
