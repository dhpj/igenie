<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu5.php');
?>
<!-- //3차 메뉴 -->
<div class="wrap_mng templet_wrap">
  <div class="s_tit"> 템플릿관리 (총 <?=number_format($total_rows)?>건)</div>
  <span class="btn_imgtem">
    <button id="myBtn" class="btn_myModal2" onClick="modal_tag_open('', 'I');">템플릿분류</button>
    <button id="dh_myBtn" class="btn_deModal" onClick="modal_templet_open('', 'I');">템플릿등록</button>
  </span>
  <div class="radio-field">
		<form method="POST" name="tag_list" onsubmit="return false;">
			<ul>
				<li style="cursor:pointer;" onClick="tag_choice('all', '1');"><input type="radio" name="tag_list" id="tag_all" class="tag_list" style="cursor:pointer;" value="all"<? if($tagid == "all"){ ?> checked<? } ?>><label for="tag_all" style="cursor:pointer;">전체</label></li>
				<? foreach($templet_tag as $r) { ?>
				<li style="cursor:pointer;" onClick="tag_choice('<?=$r->tag_name?>', '1');"><input type="radio" name="tag_list" id="tag_id_<?=$r->tag_id?>" class="tag_list" style="cursor:pointer;" value="<?=$r->tag_name?>"<? if($tag == $r->tag_name){ ?> checked<? } ?>><label for="tag_id_<?=$r->tag_id?>" style="cursor:pointer;"><?=$r->tag_name?></label></li></li>
				<? } ?>
			</ul>
		</form>
	</div>
  <ul class="templet_list">
    <? foreach($data_list as $r) {
        $shaptag = "";
        if(!empty($r->tem_tag_txt)){
            $tagexplode = explode( ',', $r->tem_tag_txt );
            if(!empty($tagexplode)){
                foreach($tagexplode as $row) {
                    $shaptag .= "<span>#".$row."</span>";
                }
            }
        }
        ?>
    <li>
      <p class="tem_img_tag"><?=$shaptag?></p>
      <p class="tem_img"><img src="<?=$r->tem_imgpath?>" alt=""></p>
      <p class="tem_text">
        <span class="num"><?=$this->funn->fnZeroAdd($r->tem_id)?></span>
        <span class="tem_color_box" style="background-color:<?=$r->tem_bgcolor?>;">&nbsp;</span>
        <button type="button" onClick="modal_templet_open('<?=$r->tem_id?>', 'M');"><i class="material-icons">create</i>수정</button>
        <button type="button" onClick="templet_del('<?=$r->tem_id?>');"><i class="material-icons">clear</i>삭제</button>
      </p>
    </li>
    <? } ?>
  </ul>
  <div class="page_cen">
    <?=$page_html?>
  </div>
</div>
<!-- 템플릿분류 Modal -->
<div id="modal_tag" class="dh_modal">
  <div class="modal-content2"> <span id="close_tag" class="dh_close">&times;</span>
    <p class="modal-tit">
      템플릿분류
    </p>
    <div class="tem_in_box">
      <ul>
        <li class="list1">
          <span class="tit">태그 모음</span>
          <input type="hidden" id="tag_id">
          <input type="hidden" id="tag_md">
          <div class="text">
            <ul class="tag_all" id="tag_list"><?//태그 모음 영역?>
            </ul>
          </div>
        </li>
        <li class="list2">
         <span class="tit">태그 추가</span>
         <div class="text">
		 <input type="text" accesskey="s" title="검색어" class="keyword" name="tag_name" id="tag_name" autocomplete="off" maxlength="20" onKeypress="if(event.keyCode==13){ templet_tag_save(); }">
		 <!--<button id="tag_add_btn" class="btnst01" onClick="templet_tag_add();">추가하기</button></div>-->
        </li>
      </ul>
    </div>
	<div class="btn_al_cen mg_t20">
	  <button type="button" class="btn_st1" onClick="templet_tag_save();"><i class="material-icons">done</i>등록</button>
	  <button type="button" class="btn_st1" onClick="modal_tag_close();" style="margin-left:5px;"><i class="material-icons">clear</i>취소</button>
	</div>
  </div>
</div>
<!-- 템플릿등록 Modal -->
<div id="modal_templet" class="dh_modal templet_wrap">
  <!-- Modal content -->
  <div class="modal-content"> <span id="close_templet" class="dh_close">&times;</span>
	<p class="modal-tit">템플릿등록</p>
	<ul>
	  <li>
		 <span class="tit">이미지 등록</span>
		 <input type="hidden" id="tem_id">
		 <input type="hidden" id="tem_md">
         <input type="hidden" id="tem_tag_txt">
		 <div class="text"><label for="img_file" class="templet_img_in"><div id="div_preview"><img id="img_preview" style="display:block;"></div></label></div>
		 <input type="file" title="이미지 파일" id="img_file" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
	  </li>
	  <li>
		<span class="tit">배경색상</span>
		<div class="text">
      <input type="text" id="tem_bgcolor" value="" placeholder="#ffffff" maxlength="20" style="width:110px;" onKeyup="chg_bgcolor();">
      <span class="color_box" id="span_tem_bgcolor" style="background-color:#f00;">&nbsp;</span>
    </div>
	  </li>
	  <li>
		<span class="tit">태그선택</span>
		<div class="text">
			<select id="tem_tag_id">
				<option value="0">::선택하세요::</option>
				<? foreach($templet_tag as $r) { ?>
				<option value="<?=$r->tag_name?>"><?=$r->tag_name?></option>
				<? } ?>
			</select>
            <button type="button" class="btn md dark" onClick="add_tag();"><i class="material-icons">add</i>추가</button>
            <div id="tem_tag_list"></div>
		</div>
	  </li>
	</ul>
	<div class="btn_al_cen mg_t20">
	  <button type="button" class="btn_st1" onClick="templet_save();"><i class="material-icons">done</i>등록</button>
	  <button type="button" class="btn_st1" onClick="modal_close();" style="margin-left:5px;"><i class="material-icons">clear</i>취소</button>
	</div>
  </div>
</div>
<script>
	$(function() {
		//템플릿분류 순서변경
		$(".tag_all").sortable({
			stop: function(event, ui) {
				var seq = [];
				$(".tag_all > li.saved_tag").each(function(t) {
					//alert("value : "+ $(this).attr("value"));
					seq.push($(this).attr("value"));
				});
				//alert("seq : "+ seq); return;
				$.ajax({url:"/mng/design/templet_tag_sort",
					data:{"seq[]":seq, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
					type:"post",
					success:function(json) {
						//console.log('완료');
						showSnackbar("정렬순서가 변경 되었습니다.", 1500);
					}
				});
			}
		});
	});

    function add_tag(){
        var tag_list_html = $("#tem_tag_list").html();
        var tem_tag_txt = $("#tem_tag_txt").val();
        var tem_tag_id = $("#tem_tag_id").val();

        if(tem_tag_id!="0"){
            if(tem_tag_txt!=""){
                var arr = tem_tag_txt.split(",");
                for(let i = 0; i < arr.length; i++){
                  if(tem_tag_id==arr[i]){
                      showSnackbar("이미 등록된 태그입니다.", 1500);
                      return false;
                  }
                }
                tem_tag_txt += ","+tem_tag_id;
            }else{
                tem_tag_txt = tem_tag_id;
            }
            $("#tem_tag_txt").val(tem_tag_txt);

            tag_list_html += "<div class='tem_tag'>"+"<span>"+tem_tag_id+"</span>"+"<button type='button' onClick=\"del_tag('"+tem_tag_id+"')\">X</button>"+"</div>";

            $("#tem_tag_list").html(tag_list_html);


        }else{
            showSnackbar("추가할 태그를 선택하세요.", 1500);
            return false;
        }
    }

    function del_tag(del_name){
        var tag_list_html = $("#tem_tag_list").html();
        var tem_tag_txt = $("#tem_tag_txt").val();
        var tem_tag_id = $("#tem_tag_id").val();

        var tag_txt="";
        if(tem_tag_txt!=""){
            var arr = tem_tag_txt.split(",");
            var new_tem_tag_txt = "";
            for(let i = 0; i < arr.length; i++){
              if(del_name!=arr[i]){
                  tag_txt += "<div class='tem_tag'>"+"<span>"+arr[i]+"</span>"+"<button type='button' onClick=\"del_tag('"+arr[i]+"')\">X</button>"+"</div>";
                  if(new_tem_tag_txt==""){
                      new_tem_tag_txt += arr[i];
                  }else{
                      new_tem_tag_txt += ","+arr[i];
                  }
              }

            }
            $("#tem_tag_list").html(tag_txt); //태그
            $("#tem_tag_txt").val(new_tem_tag_txt);

        }else{
            showSnackbar("삭제할 대상이 없습니다.", 1500);
            return false;
        }
    }

	//템플릿분류 모달창 열기
	var modal_tag = document.getElementById("modal_tag");
	function modal_tag_open(tag_id, tag_md){
		//alert("tag_id : "+ tag_id +", tag_md : "+ tag_md); return;
		$("#tag_id").val(tag_id); //템플릿분류번호
		$("#tag_md").val(tag_md); //모드
		$("#tag_name").val(""); //태그
		//alert("tag_id : "+ tag_id +", tag_md : "+ tag_md); return;
		templet_tag_data(); //템플릿분류 데이타 조회

		// Get the <span> element that closes the modal
		var span = document.getElementById("close_tag");

		// Open the modal
		modal_tag.style.display = "block";
		$("#tag_name").focus(); //태그 포커스

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal_tag_close(); //모달창 닫기
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal_tag) {
				//modal.style.display = "none";
				//remove_img();
				modal_tag_close(); //모달창 닫기
			}
		}
	}

	//템플릿분류 모달창 닫기
	function modal_tag_close(){
		//modal_tag.style.display = "none";
		location.reload();
	}

	//템플릿등록 모달창 열기
	function modal_templet_open(tem_id, tem_md){
		//alert("tem_id : "+ tem_id +", tem_md : "+ tem_md); return;
		$("#tem_id").val(tem_id); //템플릿번호
		$("#tem_md").val(tem_md); //모드
		//alert("tem_id : "+ tem_id +", tem_md : "+ tem_md); return;
		if(tem_id != ""){ //수정의 경우
			templet_data(tem_id); //템플릿관리 데이타 조회
		}

		// Get the <span> element that closes the modal
		var span = document.getElementById("close_templet");

		// Open the modal
		var modal = document.getElementById("modal_templet");
		modal.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			//modal.style.display = "none";
			//remove_img();
			modal_close(); //모달창 닫기
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				//modal.style.display = "none";
				//remove_img();
				modal_close(); //모달창 닫기
			}
		}
	}

	//모달창 닫기
	function modal_close(){
		location.reload();
	}

	//배경 이미지 초기화
	function remove_img(){
		$("#img_preview").attr("src", "");
		$("#img_preview").css("display", "none");
		$("#div_preview").css({"background":"url('')"});
		$("#div_preview").addClass("templet_img_in2");
	}

	//이미지 선택 클릭시
	$("#img_file").change(function() {
		//alert("#img_file_change");
		if(this.value.length > 0) {
			//alert("this.value : "+ this.value);
			if (this.files && this.files[0]) {
				remove_img();
				readURL(this, "div_preview");
			}
		}
	});

	//이미지 경로 세팅
	function readURL(input, divid) {
		//alert("readURL(input, divid) > input.value : "+ input.value +", divid : "+ divid);
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#"+divid).css({"background":"url(" + e.target.result + ")"});
				//$("#"+divid).css({"background-size":"contain"});
				//$("#"+divid).css({"background-repeat":"repeat-x"});
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	//배경색상 영역에 적용
	function chg_bgcolor(){
		var tem_bgcolor = $("#tem_bgcolor").val();
		if(tem_bgcolor != ""){
			$("#span_tem_bgcolor").css({"background-color":tem_bgcolor}); //배경색상 영역
		}
	}

	//템플릿관리 데이타 조회
	function templet_data(tem_id){
		//alert("tem_id : "+ tem_id);
		$.ajax({
			url: "/mng/design/templet_data",
			type: "POST",
			data: {"tem_id":tem_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					$("#tem_bgcolor").val(value.tem_bgcolor); //배경색상
					$("#span_tem_bgcolor").css({"background-color":value.tem_bgcolor}); //배경색상 영역
					// $("#tem_tag_id").val(value.tem_tag_id); //태그
					var tem_imgpath = value.tem_imgpath; //이미지경로
					if(tem_imgpath != ""){
						$("#img_preview").attr("src", tem_imgpath);
						$("#img_preview").css("display", "");
					}

                    var tem_tag_txt = value.tem_tag_txt;
                    var tag_txt = "";
                    if(tem_tag_txt!=""){
                        $("#tem_tag_txt").val(tem_tag_txt);
                        var arr = tem_tag_txt.split(",");
                        for(let i = 0; i < arr.length; i++){
                          tag_txt += "<div class='tem_tag'>"+"<span>"+arr[i]+"</span>"+"<button type='button' onClick=\"del_tag('"+arr[i]+"')\">X</button>"+"</div>";
                        }
                        $("#tem_tag_list").html(tag_txt); //태그

                    }

				});
			}
		});
	}

	//템플릿관리 저장
	function templet_save(){
		var tem_id = $("#tem_id").val(); //템플릿번호
		var tem_md = $("#tem_md").val(); //모드
		var imgfile = $("#img_file")[0].files[0];
        var tem_tag_txt = $("#tem_tag_txt").val().trim(); //태그
		//alert("tem_id : "+ tem_id +", tem_md : "+ tem_md +", imgfile : "+ imgfile); return;
		if(tem_md != "M" && (!(imgfile) || imgfile == undefined)){
			alert("이미지를 유첨하세요.");
			return;
		}
		var tem_bgcolor = $("#tem_bgcolor").val().trim(); //배경색상
		//alert("imgfile : "+ imgfile +"\n"+"tem_bgcolor : "+ tem_bgcolor); return;
		if(tem_bgcolor == ""){
			alert("배경색상을 입력하세요.");
			$("#tem_bgcolor").focus();
			return;
		}
        var tem_tag_txt = $("#tem_tag_txt").val().trim();
		if(tem_tag_txt == ""){
			alert("태그를 선택하세요.");
			$("#tem_tag_id").focus();
			return;
		}
		//alert("OK"); return;
		if(tem_md == "M"){ //수정의 경우
			if(!confirm("수정 하시겠습니까?")){
				return;
			}
		}
		var formData = new FormData();
		formData.append("tem_id", tem_id); //템플릿번호
		formData.append("imgfile", imgfile); //이미지
		formData.append("tem_bgcolor", tem_bgcolor); //배경색상
		formData.append("tem_tag_txt", tem_tag_txt); //태그번호
		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		$.ajax({
			url: "/mng/design/templet_save",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function (json) {
				showSnackbar("저장 되었습니다.", 1500);
				setTimeout(function() {
					location.reload();
				}, 1000); //1초 지연
			}
		});
	}

	//템플릿관리 삭제
	function templet_del(tem_id){
		if(confirm("삭제 하시겠습니까?")){
			$.ajax({
				url: "/mng/design/templet_del",
				type: "POST",
				data: {"tem_id":tem_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("삭제 되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
				}
			});
		}
	}

	//템플릿분류 데이타 조회
	function templet_tag_data(){
		$.ajax({
			url: "/mng/design/templet_tag_data",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				var seq = 0;
				$("#tag_list").html("");
				$.each(json, function(key, value){
					var tag_id = value.tag_id; //템플릿분류번호
					var tag_name = value.tag_name; //태그명
					$("#tag_list").append("<li class=\"saved_tag\" id=\"li_tag_"+ tag_id +"\" value=\""+ tag_id +"\"><input type='text' id=\"sp_tag_"+ tag_id +"\" value=\""+ tag_name.replace(/\"/g,"&quot;") +"\"><span onClick=\"templet_tag_del('"+ tag_id +"')\"><i title='삭제하기' class=\"material-icons\">clear</i></span> <span onClick=\"templet_tag_mod('"+ tag_id +"')\"><i title='수정하기' class=\"material-icons\">done</i></span></li>");
					seq++;
				});
			}
		});
	}

	//템플릿분류 저장
	function templet_tag_save(){
		var tag_id = $("#tag_id").val(); //템플릿분류번호
		var tag_md = $("#tag_md").val(); //모드
		var tag_name = $("#tag_name").val().trim(); //태그
		//alert("imgfile : "+ imgfile +"\n"+"tag_name : "+ tag_name); return;
		if(tag_name == ""){
			alert("태그를 입력하세요.");
			$("#tag_name").focus();
			return;
		}
		//alert("OK"); return;
		if(tag_md == "M"){ //수정의 경우
			if(!confirm("수정 하시겠습니까?")){
				return;
			}
		}
		var formData = new FormData();
		formData.append("tag_id", tag_id); //템플릿분류번호
		formData.append("tag_name", tag_name); //태그
		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		//중복체크
		$.ajax({
			url: "/mng/design/templet_tag_chk",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function (json) {
				if(json.cnt != "0"){ //중복
					showSnackbar("현재 태그모음에 추가된 내용입니다.", 1500);
					$("#tag_name").focus();
					return;
				}else{
					//추가하기
					$.ajax({
						url: "/mng/design/templet_tag_save",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function (json) {
							var tag_id = json.tag_id; //템플릿분류번호
							showSnackbar("추가 되었습니다.", 1500);
							$("#tag_name").val("");
							$("#tag_name").focus();
							$("#tag_list").append("<li class=\"saved_tag\" id=\"li_tag_"+ tag_id +"\" value=\""+ tag_id +"\"><input type=text' id=\"sp_tag_"+ tag_id +"\" value=\""+ tag_name.replace(/\"/g,"&quot;") +"\"><span onClick=\"templet_tag_del('"+ tag_id +"')\"><i title='삭제하기' class=\"material-icons\">clear</i></span> <span onClick=\"templet_tag_mod('"+ tag_id +"')\"><i title='수정하기' class=\"material-icons\">done</i></span></li>");
						}
					});
				}
			}
		});
	}

	//템플릿분류 수정
	function templet_tag_mod(tag_id){
		var tag_name = $("#sp_tag_"+ tag_id).val();
		if(tag_name == ""){
			alert("태그를 입력하세요.");
			$("#sp_tag_"+ tag_id).focus();
			return;
		}
		//alert("tag_name : "+ tag_name); return;
		if(confirm("수정 하시겠습니까?")){
			var formData = new FormData();
			formData.append("tag_id", tag_id); //템플릿분류번호
			formData.append("tag_name", tag_name); //태그
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/mng/design/templet_tag_mod",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
					showSnackbar("수정 되었습니다.", 1500);
				}
			});
		}
	}

	//템플릿분류 삭제
	function templet_tag_del(tag_id){
		if(confirm("삭제 하시겠습니까?")){
			$.ajax({
				url: "/mng/design/templet_tag_del",
				type: "POST",
				data: {"tag_id":tag_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("삭제 되었습니다.", 1500);
					$("#li_tag_"+ tag_id).remove();
				}
			});
		}
	}

	//템플릿분류 추가하기
	function templet_tag_add(){
		var tag_name = $('#tag_name').val();
		if(tag_name.length >= 1){
			$('#tag_list').append("<li class=\"new_tag\">"+tag_name+"<i class=\"material-icons\">clear</i></li>");
			$('#tag_name').val('');
			var seen = {};
			$('#tag_list li').each(function() {
				var txt = $(this).text();
				if (seen[txt]){
					$(this).remove();
					alert('현재 태그모음에 추가된 내용입니다.');
					$('#tag_name').focus();
				}else{
					seen[txt] = true;
				}
			});
		}else{
			alert('태그를 입력하세요.');
			$('#tag_name').focus();
		}
	}

	//이미지 템플릿선택 모달창 열기 > 태그선택
	function tag_choice(tag_id, page){
		$("input:radio[name=tag_list]:radio[value='"+ tag_id +"']").prop('checked', true); //태그 선택하기
		open_page(page); //검색
	}

	//검색
	function open_page(page) {
        var tag = $(":input:radio[name=tag_list]:checked").val(); //선택된 태그번호
		//alert("page : "+ page +", tagid : "+ tagid);
		location.href = "/mng/design/templet?page="+ page +"&tag="+ tag;
	}
</script>
<div id="snackbar"></div><?//모달창 div?>
<script>
	//모달창 메시지
	function showSnackbar(msg, delay) {
		var x = document.getElementById("snackbar");
		x.innerHTML = msg;
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, delay);
	}
</script>
