<div id='modal1' class="kakao kakao_modal" style='display:none;'>
    <div class="modal-content">
        <span id="" onclick='open_modal("none", 1);' class="modal_close">×</span>
        <p class="modal-tit">소재 라이브러리</p>
        <ul class="modal_tab">
            <li id='m1_tab1' class="m1_tab" onclick='m1_change_tab(this, 1)'>이미지 불러오기</li>
            <li id='m1_tab2' class="m1_tab on" onclick='m1_change_tab(this, 2)'>직접 업로드</li>
        </ul>

        <section class="modal_upload">
            <div class="upload_info">
                <div class="upload_guide">
                    <div class="guide_register">
                        <span id='m1_guide' style='text-decoration:underline;'>소재 등록 가이드</span>

                        <div id="m1_guide_detail" style="display:none;" class="guide_register_hover">
                            <strong>파일형식</strong>
                            <ul>
                                <li>JPG, JPEG, PNG</li>
                            </ul>
                            <strong>사이즈/용량</strong>
                            <ul>
                                <li>사이즈 800x400(2:1 비율) 권장</li>
                                <li>사이즈 800x800(1:1 비율) 권장</li>
                                <li>사이즈 800x600(4:3 비율) 권장</li>
                                <li>용량 10MB</li>
                            </ul>
                            <strong>참고사항</strong>
                            <ul>
                                <li>· 가로 80px 이상</li>
                                <li>· 가로 세로 비율이 1:2.5 이하</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id='m1_filter1' class="upload_search_box" style='display:none;'>
                    <input id='file_name' class="upload_file_name" type="text" autocomplete="on" value="" placeholder="이미지 이름을 입력하세요." onkeyup="if(window.event.keyCode==13){open_page(1);}">
                    <button class="btn_search_del" onclick='{$("#file_name").val("");}'><span class="modal_close">×</span></button>
                    <button class="btn_gw link_selected upload_search_btn" onclick='open_page(1);'>
                        <span>검색</span>
                    </button>
                </div>

                <button id='m1_filter2' class="btn_gw link_selected upload_file_btn" onclick=''>
                    <label for="direct_upload">
                        <span class="ico_add">파일 업로드</span>
                    </label>
                </button>
                <input type="file" id="direct_upload" name='imgFile[]' accept=".jpg, .jpeg, .png" style="display:none;" onchange='select_img_upload(this);' multiple>


            </div>

            <div class="upload_view">
                <ul id='m1_tab_detail1' class="upload_view_list" style='display:none;'>
                    <!-- <li>
                        <a href="#"><img src="//t1.daumcdn.net/b2/creative/442247/50501a873e21a17d15f3d49f08ecc753" alt=""></a>
                        <span>800x800</span>
                        <span>20230831153449.jpg</span>
                    </li>
                    <li>
                        <a href="#"><img src="//t1.daumcdn.net/b2/creative/442247/50501a873e21a17d15f3d49f08ecc753" alt=""></a>
                        <span>800x800</span>
                        <span>2023083115asdf asdf asdf 3449.jpg</span>
                    </li>
                    <li>
                        <a href="#"><img src="//t1.daumcdn.net/b2/creative/442247/50501a873e21a17d15f3d49f08ecc753" alt=""></a>
                        <span>800x800</span>
                        <span>2023083asdf asdf 1153449.jpg</span>
                    </li> -->
                </ul>

                <!-- <div class="upload_drop_area">최근 사용한 이미지가 없습니다.</div> -->
                <div id='m1_tab_detail2' class="upload_drop_area">업로드할 이미지를 끌어다 놓으세요.</div>
                <div id="id_loading_mask" class="actionCon" style="display:none ;">
                    <div class="actionType4_1">
                        <div>
                            <div>
                                <div>
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loading_text">Loading ...</div>
                </div>
            </div>
        </section>

        <div class="modal_btns">
            <button type="button" class="btn_gw" onclick='open_modal("none", 1)'>
                <span>취소</span>
            </button>
            <button id='m1_confirm' type="button" class="btn_gw btn_bg_bk">
                <span>확인</span>
            </button>
        </div>

    </div>
</div>

<script>
    var ext_array = ['image/jpg', 'image/jpeg', 'image/png'];
    $(document).on('mouseover', '#m1_guide', function(){
        $('#m1_guide_detail').css('display', '');
    }).on('mouseout', '#m1_guide', function(){
        $('#m1_guide_detail').css('display', 'none');
    }).on('click', '#m1_confirm', function(){
        var t = $('#m1_tab_detail1').find('a.on').parent('li');
        $('#img_para_description').css('display', 'none');
        open_modal("none", 1);
        $('#m_img_box').css('display', '');
        var path = t.find('input').data('path');
        var name = t.find('input').data('name');
        $('#m_img').attr('src', path);
        $('#p_img').css('display', '');
        $('#p_img').attr('src', path);
        $('#m_name').html(name);
        $('#select_img').data('path', '/var/www/igenie' + path);
        var split_name = path.split('/').reverse()[0];
        $('#select_img').data('name', name);
        $('#select_img').data('type', 'image/' + split_name.split('.')[1]);
        paraImageFlag = false;
        imgOrVideoFlag = 'i';
    }).on('click', '.m1_img_list', function(){
        $('#m1_tab_detail1').find('a.on').removeClass('on');
        $(this).find('a').addClass('on');
    });

    $("#m1_tab_detail2").on("dragenter", function(e){
		e.preventDefault();
		e.stopPropagation();
	}).on("dragover", function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).css("background-color", "rgb(102, 102, 255)");
	}).on("dragleave", function(e){
		e.preventDefault();
		e.stopPropagation();
        $(this).css("background-color", "");
	}).on("drop", function(e){
		e.preventDefault();
		$(this).css("background-color", "");

		var files = e.originalEvent.dataTransfer.files;
        $('#id_loading_mask').css('display', '');
        img_uploader(files);

	});

    function img_uploader(files){
        var formData = new FormData();
        var for_flag = true;
        $.each(files, function(idx, val){
            if (ext_array.indexOf(val.type) === -1){
                for_flag = false;
                alert('이미지 파일만 등록해주세요.');
                return false;
            }
            formData.append('imgfile[]', val);
        });
        if (for_flag){
			// formData.append("files", files);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
            $.ajax({
                url: "/kakao/set_creative_img",
                type: "POST",
                processData: false,
				contentType: false,
                data: formData,
                success: function (json) {
                    get_creative_img(1);
                    setTimeout(() => {
                        $('#id_loading_mask').css('display', 'none');
                        $('#m1_tab1').trigger('click');
                    }, 300);
                }
            });
        }
    }

    function m1_change_tab(t, seq){
        m1_show_tab('none');
        $('.m1_tab').removeClass('on');
        $(t).addClass('on');
        $('#m1_tab_detail' + seq).css('display', '');
        $('#m1_filter' + seq).css('display', '');
        if (seq === 1){
            $('.pagination').css('display', '');
        } else {
            $('.pagination').css('display', 'none');
        }
    }

    function m1_show_tab(display){
        $('#m1_tab_detail1').css('display', display);
        $('#m1_filter1').css('display', display);
        $('#m1_tab_detail2').css('display', display);
        $('#m1_filter2').css('display', display);
    }

    function get_creative_img(page){
        $.ajax({
            url: "/kakao/get_creative_img",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , page : page
              , file_name : $('#file_name').val().trim()
            },
            success: function (json) {
                $('#m1_tab_detail1').html('');
                $('#m1_tab_detail1').html(json.text);
                $('.pagination').remove();
                $('#m1_tab_detail1').after(json.page);
                if ($('.m1_tab').eq(0).hasClass('on')){
                    $('.pagination').css('display', '');
                }
            }
        });
    }

    function open_page(page){
        get_creative_img(page);
    }

    function select_img_upload(t){
        $('#id_loading_mask').css('display', '');
        img_uploader(t.files);
        var dataTranster = new DataTransfer();
        t.files = dataTranster.files;
    }

</script>
