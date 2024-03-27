<div id='modal2' class="kakao kakao_modal" style='display:none;'>
    <div class="modal-content">
        <span id="" class="modal_close" onclick='open_modal("none", 2);'>×</span>
        <p class="modal-tit">소재 라이브러리</p>
        <ul class="modal_tab">
            <li id='m2_tab1' class="m2_tab on" onclick='m2_change_tab(this, 1)'>디스플레이 동영상</li>
            <!-- <li class="">메시지 동영상</li> -->
            <!-- <li id='m2_tab2' class="m2_tab" onclick='m2_change_tab(this, 2)'>직접 업로드</li> -->
        </ul>

        <section class="modal_upload">
            <div class="upload_info">
                <!-- <div class="upload_tip">
                    <span class="icon_tip"></span>영상은 카카오TV ‘카카오 광고’ 채널에 업로드됩니다.
                </div> -->
                <div class="upload_guide">
                    <div class="guide_register">
                        <span id='m2_guide'>소재 등록 가이드</span>

                        <div id="m2_guide_detail" class="guide_register_hover" style='display:none;'>
                            <strong>파일형식</strong>
                            <ul>
                                <li>AVI, FLV, MP4 권장</li>
                            </ul>
                            <strong>사이즈/용량</strong>
                            <ul>
                                <li>가로 80px 이상의 2:1~3:4 비율 권장</li>
                                <li>용량 1GB</li>
                            </ul>
                            <strong>참고사항</strong>
                            <ul>
                                <li>· 한 번에 1개만 업로드 가능</li>
                                <li>· 동영상 3초 이상 업로드 가능</li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div id='m2_filter1' class="upload_search_box">
                    <input id='file_name2' class="upload_file_name" type="text" id="" name="" maxlength="30" autocomplete="on" value="" placeholder="동영상 이름을 입력하세요." onkeyup="if(window.event.keyCode==13){open_page2(1);}">
                    <button id="" class="btn_search_del" onclick='{$("#file_name2").val("");}'><span class="modal_close">×</span></button>
                    <button class="btn_gw link_selected upload_search_btn" onclick='open_page2(1);'>
                        <span>검색</span>
                    </button>
                </div>

                <button id='m2_filter2' class="btn_gw link_selected upload_file_btn" onclick='' style='display:none;'>
                    <span class="ico_add">파일 업로드</span>
                </button>

            </div>

            <div class="upload_view">

                <ul id='m2_tab_detail1' class="upload_view_list">
                    <!-- <li>
                        <a href="#"><video src="videofile.ogg" autoplay poster="posterimage.jpg"></a>
                        <span>800x800</span>
                        <span>20230831153449.MP4</span>
                        <button type="button" name="button">미리보기</button>
                    </li>
                    <li>
                        <a href="#"><video src="videofile.ogg" autoplay poster="posterimage.jpg"></a>
                        <span>800x800</span>
                        <span>2023083115asdf asdf asdf 3449.MP4</span>
                        <button type="button" name="button">미리보기</button>
                    </li>
                    <li>
                        <a href="#"><video src="videofile.ogg" autoplay poster="posterimage.jpg"></a>
                        <span>800x800</span>
                        <span>2023083asdf asdf 1153449.MP4</span>
                        <button type="button" name="button">미리보기</button>
                    </li> -->
                </ul>

                <!-- <div class="upload_drop_area" style='display:none;'>최근 사용한 동영상이 없습니다.</div> -->
                <!-- <div id='m2_tab_detail2' class="upload_drop_area" style='display:none;'>업로드할 동영상을 끌어다 놓으세요.</div> -->
                <div id='zero_video' class="no_upload_video" style='display:none;'>
                    <span>동영상이 존재하지 않습니다.</span>
                    <a href="https://tv.kakao.com/station/uploader" target="_blank">카카오TV 바로가기</a>
                </div>
            </div>
        </section>

        <div class="modal_btns">
            <button class="btn_gw" onclick='open_modal("none", 2);'>
                <span>취소</span>
            </button>
            <button id='m2_confirm' class="btn_gw btn_bg_bk">
                <span>확인</span>
            </button>
        </div>

    </div>
</div>

<script>
    var chid = '';
    var m2_page = '1';

    $(document).on('mouseover', '#m2_guide', function(){
        $('#m2_guide_detail').css('display', '');
    }).on('mouseout', '#m2_guide', function(){
        $('#m2_guide_detail').css('display', 'none');
    }).on('click', '.m2_video_list', function(){
        $('#m2_tab_detail1').find('a.on').removeClass('on');
        $(this).addClass('on');
    }).on('click', '#m2_confirm', function(){
        var t = $('#m2_tab_detail1').find('a.on').parent('li');
        open_modal("none", 2);
        $('#m_img_box').css('display', '');
        var liid = t.find('input').data('liid');
        var thumb = t.find('input').data('thumb');
        var name = t.find('input').data('name');
        $('#m_img').attr('src', thumb);
        $('#p_img').css('display', '');
        $('#p_img').attr('src', thumb);
        $('#m_name').html(name);
        $('#select_video').data('liid', liid);
        $('#select_video').data('thumb', thumb);
        $('#select_video').data('name', name);
        paraImageFlag = false;
        imgOrVideoFlag = 'v';
    });

    function m2_change_tab(t, seq){
        m2_show_tab('none');
        $('.m2_tab').removeClass('on');
        $(t).addClass('on');
        $('#m2_tab_detail' + seq).css('display', '');
        $('#m2_filter' + seq).css('display', '');
        if (seq === 1){
            $('.pagination').css('display', '');
        } else {
            $('.pagination').css('display', 'none');
        }
    }

    function m2_show_tab(display){
        $('#m2_tab_detail1').css('display', display);
        $('#m2_filter1').css('display', display);
        $('#m2_tab_detail2').css('display', display);
        $('#m2_filter2').css('display', display);
    }

    function get_kakaotv_ch_movs(page){
        $.ajax({
			url: "/kakao/get_kakaotv_ch_movs_modal",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , chid : chid
              , page : page
              , file_name : $('#file_name2').val().trim()
            },
			success: function (json) {
                $('#m2_tab_detail1').html('');
                if (json.code == 's'){
                    $('.pagination2').remove();
                    $('#zero_video').css('display', 'none');
                    $('#m2_tab_detail1').html(json.text);
                    $('#m2_tab_detail1').after(json.page);
                } else if (json.code == 'z'){
                    $('#zero_video').css('display', '');
                } else if (json.code == 'f'){
                    alert(json.data.msg);
                }
			}
		});
    }

    function open_page2(page){
        get_kakaotv_ch_movs(page);
    }

</script>
