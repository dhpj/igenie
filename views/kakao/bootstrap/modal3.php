<div id='modal3' class="kakao kakao_modal" style='display:none;'>
    <div class="modal-content add_change_item">

        <span id="" class="modal_close" onclick='open_modal("none", 3);'>×</span>
        <p class="modal-tit">변수 항목 추가</p>

        <div class="item_choice">
            <h5>변수 항목</h5>
            <select id='m3_sel1' class="select_change_item" onchange='sel2_init(this)'>
                <option value='1'><span>홍보 이미지</span></option>
                <option value='2'><span>홍보 동영상</span></option>
            </select>

            <select id='m3_sel2' class="select_change_item" onchange='change_sel2(this.value)'>
                <option value='1'>1</option>
                <option value='2'>2</option>
                <option value='3'>3</option>
                <option value='4'>4</option>
                <option value='5'>5</option>
                <option value='6'>6</option>
                <option value='7'>7</option>
            </select>

            <h5>미리보기</h5>
            <span id='p_para' type="text" class="change_item_name">${image_url1}</span>
        </div>

        <div class="modal_btns">
            <button type="button" class="btn_gw" onclick='open_modal("none", 3);'>
                <span>취소</span>
            </button>
            <button type="button" class="btn_gw btn_bg_bk" onclick='m3_confirm();'>
                <span>확인</span>
            </button>
        </div>

    </div>
</div>

<script>
    function sel2_init(t){
        $('#m3_sel2').val(1);
        if ($(t).val() == '1'){
            $('#p_para').html('${image_url1}');
        } else if ($(t).val() == '2'){
            $('#p_para').html('${video_url1}');
        }
    }

    function m3_confirm(){
        if ($('#m3_sel1').val() == '1'){
            $('#p_img').attr('src', '/images/pm_01.jpg');
            $('#m_img').attr('src', '/images/pm_02.jpg');
            $('#img_para_description').html('발송 요청 시 전달한 이미지 소재를 발송합니다.');
        } else if ($('#m3_sel1').val() == '2'){
            $('#p_img').attr('src', '/images/pm_03.jpg');
            $('#m_img').attr('src', '/images/pm_04.jpg');
            $('#img_para_description').html('발송 요청 시 전달한 동영상 소재를 발송합니다.');
        }
        $('#p_img').css('display', '');
        $('#m_img_box').css('display', '');
        $('#img_para_description').css('display', '');
        $('#m_name').html($('#p_para').html());
        $('#select_img').data('path', '');
        $('#select_img').data('name', '');
        $('#select_img').data('type', '');
        $('#select_video').data('liid', '');
        $('#select_video').data('thumb', '');
        $('#select_video').data('name', '');
        paraImageFlag = true;
        imgOrVideoFlag = '';
        open_modal("none", 3);
    }

    function change_sel2(val){
        if ($('#m3_sel1').val() == '1'){
            $('#p_para').html('${image_url' + val + '}');
        } else if($('#m3_sel1').val() == '2') {
            $('#p_para').html('${video_url' + val + '}');
        }
    }
</script>
