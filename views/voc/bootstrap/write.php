<link rel="stylesheet" type="text/css" href="/views/voc/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>
<?$df = !empty($detail) ? true : false;?>
<div class="tit_wrap">
	고객의 소리
</div>
<div id="mArticle" class="voc_wrap <?=$df ? "voc_view" : ""?>">
    <ul class="white_box">
        <li>
            <span class="voc_input_tit" data-order="first" data-style="necessary">작성자 : </span>
            <?if($df){?>
            <div><?=$detail->v_writer?></div>
            <?}else{?>
            <input type="text" id="name" value="" placeholder="작성자">
            <?}?>
        </li>
        <li>
            <span class="voc_input_tit" data-style="necessary">전화번호 : </span>
            <?if($df){?>
            <div><?=$detail->v_tel?></div>
            <?}else{?>
            <input type="text" id="tel" value="" placeholder='"-" 없이 작성해주시면 됩니다.'>
            <?}?>
        </li>
        <li>
            <span class="voc_input_tit">이메일 : </span>
            <?if($df){?>
            <div><?=$detail->v_email?></div>
            <?}else{?>
            <input type="text" id="email" value="" placeholder="example@example.com">
            <?}?>
        </li>
        <li>
            <span class="voc_input_tit" data-order="first" data-style="necessary">제목 : </span>
            <?if($df){?>
            <div><?=$detail->v_title?></div>
            <?}else{?>
            <input type="text" id="title" value="" class="voc_tit">
            <?}?>
        </li>
        <li>
            <span class="voc_input_tit" data-style="content">내용</span>
            <?if($df){?>
            <div><?=$detail->v_detail?></div>
            <?}else{?>
            <textarea id="summernote" name="content"></textarea>
            <?}?>
            <!-- 텍스트 에디터 -->
        </li>
        <li class="uploadFile_wrap">
            <?if($df){?>
                <?if ($detail->v_ufilename0){?>
                <div>
                    파일명 : <?=$detail->v_filename0?>
                    <a href="<?="http://".$_SERVER['SERVER_NAME'] . "/uploads/voc/" . $detail->v_ufilename0?>" download>다운로드</a>
                <div>
                <?}?>
                <?if ($detail->v_ufilename1){?>
                <div>
                    파일명 : <?=$detail->v_filename1?>
                    <a href="<?="http://".$_SERVER['SERVER_NAME'] . "/uploads/voc/" . $detail->v_ufilename1?>" download>다운로드</a>
                <div>
                <?}?>
            <?}else{?>
            <input type="file" id="fileInput0" onchange="uploadFile(this, 0)">
            <input type="file" id="fileInput1" onchange="uploadFile(this, 1)">
            <?}?>
        </li>
    </ul>
    <div class="btn_wrap">
        <?if($df){?>
        <input type="button" value="목록으로" onclick="location.href='/voc'">
        <?}else{?>
        <input type="button" value="보내기" onclick="saved()">
        <input type="button" value="뒤로가기" onclick="history.go(-1)">
        <?}?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        // Summernote 초기화
        $('#summernote').summernote({
            height: 300,
            placeholder: '내용을 입력하세요...',
            // toolbar: [
            //     ['style', ['bold', 'italic', 'underline', 'clear']],
            //     ['fontsize', ['fontsize']],
            //     ['color', ['color']],
            //     ['para', ['ul', 'ol', 'paragraph']],
            //     ['height', ['height']],
            //     ['insert', ['picture', 'file']], // 이미지 삽입 버튼 추가
            // ],
            callbacks: { // 이미지 업로드 콜백 함수 설정
                onImageUpload: function(files) {
                    var formData = new FormData();
                    formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
                    formData.append('image', files[0]);

                    // 이미지 업로드 처리
                    $.ajax({
                        url: '/voc/image_upload', // 이미지 업로드 핸들러 URL
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            var imageUrl = JSON.parse(response).url;
                            $('#summernote').summernote('insertImage', "<?="http://".$_SERVER['SERVER_NAME']?>/" +imageUrl);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                },
            }
        });
    });

    const maxFileSize = 10 * 1024 * 1024; // 10MB

    function uploadFile(t, seq) {
        var fileInput = document.getElementById('fileInput' + seq);
        var file =fileInput.files[0];

        if (file && file.size > maxFileSize) {
          alert('파일 크기가 너무 큽니다. 최대 크기는 10MB입니다.');
          t.value = '';
          return;
        }

        if (file.type == "application/x-msdownload"){
            alert('실행 파일은 업로드 불가능합니다.');
            t.value = '';
            return;
        }

        var formData = new FormData();
        formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
        formData.append('file', file);

        $.ajax({
            url: '/voc/file_upload',
            method: 'POST',
            data: formData,
            contentType : false,
            processData : false,
            success: function(response) {
                $(t).data("name", response[0]);
                $(t).data("uname", response[1]);
            },
            error: function(xhr, status, error) {

            }
        });
    }
<?if(!$df){?>
    fileInput0.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file && file.size > maxFileSize) {
        alert('파일 크기가 너무 큽니다. 최대 크기는 10MB입니다.');
        fileInput.value = ''; // 파일 선택 초기화
      }
    });

    fileInput1.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file && file.size > maxFileSize) {
        alert('파일 크기가 너무 큽니다. 최대 크기는 10MB입니다.');
        fileInput.value = ''; // 파일 선택 초기화
      }
    });
<?}?>
    function saved(){
        if (valid()){
            $.ajax({
                url: '/voc/saved',
                method: 'POST',
                data: {
                    "<?= $this->security->get_csrf_token_name() ?>" : "<?= $this->security->get_csrf_hash() ?>",
                    "name" : $("#name").val(),
                    "tel" : $("#tel").val(),
                    "email" : $("#email").val(),
                    "title" : $("#title").val(),
                    "detail" : $("#summernote").val(),
                    "filename0" : $("#fileInput0").data("name"),
                    "ufilename0" : $("#fileInput0").data("uname"),
                    "filename1" : $("#fileInput1").data("name"),
                    "ufilename1" : $("#fileInput1").data("uname"),
                },
                success: function(response) {
                    if (response > 0){
                        alert("정상적으로 제출 되었습니다.\n감사합니다.");
                        history.go(-1);
                    } else {
                        alert("정상적으로 제출 되지 못하였습니다.\n관리자에게 문의 바랍니다.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("정상적으로 제출 되지 못하였습니다.\n관리자에게 문의 바랍니다.");
                }
            });
        }
    }

    function valid(){
        if ($("#name").val().trim() == ""){
            $("#name").focus();
            alert("작성자를 적어주세요.");
            return false;
        }

        if (!/^[0-9]{10,11}$/.test($("#tel").val())){
            $("#tel").focus();
            alert("올바른 형식의 전화번호를 적어주세요.");
            return false;
        }

        if ($("#email").val().trim() != "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($("#email").val())){
            $("#email").focus();
            alert("올바른 형식의 이메일를 적어주세요.");
            return false;
        }

        if ($("#title").val().trim() == ""){
            $("#title").focus();
            alert("제목을 적어주세요.");
            return false;
        }

        if ($("#summernote").val().trim() == ""){
            $("#summernote").focus();
            alert("내용을 적어주세요.");
            return false;
        }

        return true;
    }
</script>
