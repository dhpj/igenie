<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu5.php');
?>
<!-- //3차 메뉴 -->
<div class="wrap_mng">
  <div class="s_tit">상품이미지 관리 (총 <?=number_format($total_rows)?>건)</div>
  <span class="btn_imgtem">
    <!--
	<label for="code_file" class="btn_myModal2" style="cursor:pointer;margin-right:130px;">상품코드 엑셀 업데이트</label>
	<input type="file" id="code_file" onchange="images_code_upload(this);" style="display:none;">
	-->
	<label for="category_file" class="btn_myModal2" style="cursor:pointer;margin-right:285px;">카테고리 엑셀 업데이트</label>
	<input type="file" id="category_file" onchange="images_category_upload(this);" style="display:none;">
    <button class="btn_myModal2" style="margin-right:138px;" onClick="images_download();">엑셀 다운로드</button>
    <button id="myBtn" class="btn_myModal2" style="margin-right:0px;" onClick="images_import();" title="/uploads/temp_images/">FTP Import</button>
    <button id="dh_myBtn" class="btn_deModal" onClick="modal_images_open('', 'I');">이미지등록</button>
  </span>
  <div class="img_search_box">
    <select id="searchCate1" class="mg_l20" onChange="$('#searchCate2').val('');open_page(1);">
      <option value="">- 대분류 -</option>
      <option value="0"<?=($searchCate1 == "0") ? " Selected" : ""?>>미분류</option>
      <? foreach($category_list as $r) { ?>
      <option value="<?=$r->id?>"<?=($searchCate1 == $r->id) ? " Selected" : ""?>><?=$r->description?></option>
      <? } ?>
    </select>
    <select id="searchCate2" class="mg_l10" style="width:220px;" onChange="open_page(1);">
      <option value="">- 소분류 -</option>
      <option value="0"<?=($searchCate2 == "0") ? " Selected" : ""?>>미분류</option>
      <?
			if(!empty($category_sub)){
				foreach($category_sub as $r) {
      ?>
      <option value="<?=$r->id?>"<?=($searchCate2 == $r->id) ? " Selected" : ""?>><?=$r->description?></option>
      <?
				}
			}
      ?>
    </select>
    <select id="searchType" class="mg_l10">
      <option value="1"<?=($searchType == "1") ? " Selected" : ""?>>제품명</option>
      <option value="2"<?=($searchType == "2") ? " Selected" : ""?>>상품코드</option>
    </select>
	<input type="text" id="searchStr" class="input_w200" name="searchStr" placeholder="검색 내용 입력" value="<?=$searchStr?>" onKeypress="if(event.keyCode==13){ open_page(1); }">
    <input type="button" class="btn_search" id="check" value="검색" onClick="open_page(1);">
    <button type="button" onclick="images_chk_del();" class="btn_h34 mg_l20">선택삭제</button>
    <div class="fr">
      <select id="imgcode" onChange="open_page(1);">
        <option value="">상품코드 (ALL)</option>
        <option value="Y"<?=($imgcode == "Y") ? " Selected" : ""?>>상품코드 (Y)</option>
        <option value="N"<?=($imgcode == "N") ? " Selected" : ""?>>상품코드 (N)</option>
      </select>
	  <select id="sort" onChange="open_page(1);">
        <option value="1"<?=($sort == "1") ? " Selected" : ""?>>등록일순</option>
        <option value="2"<?=($sort == "2") ? " Selected" : ""?>>제품명순</option>
      </select>
      <select id="per_page" onChange="open_page(1);">
        <option value="15"<?=($perpage == "15" or $perpage == "") ? " Selected" : ""?>>15 Line</option>
        <option value="30"<?=($perpage == "30") ? " Selected" : ""?>>30 Line</option>
        <option value="50"<?=($perpage == "50") ? " Selected" : ""?>>50 Line</option>
        <option value="100"<?=($perpage == "100") ? " Selected" : ""?>>100 Line</option>
        <option value="200"<?=($perpage == "200") ? " Selected" : ""?>>200 Line</option>
        <option value="500"<?=($perpage == "500") ? " Selected" : ""?>>500 Line</option>
        <option value="1000"<?=($perpage == "1000") ? " Selected" : ""?>>1000 Line</option>
      </select>
      <input type="text" title="이동할 페이지" id="page" value="<?=$param['page']?>" class="input_w50 mg_l10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" onKeypress='if(event.keyCode==13){ open_page($("#page").val()); }'>
      <button type="button" onClick='open_page($("#page").val());' class="btn_h34 mg_r20">페이지이동</button>
    </div>
  </div>
  <ul class="imglib_list">
    <?
		foreach($data_list as $r) {
			$img_name = $r->img_name; //제품명
			if($searchStr != ""){
				//$img_name = str_replace($searchStr, "<font color='red'>". $searchStr ."</font>", $img_name);
				$orstr = explode(' ', $searchStr);
				$orcnt = count($orstr);
				for($ii=0; $ii<$orcnt; $ii++){
					$img_name = str_replace($orstr[$ii], "<font color='red'>". addslashes($orstr[$ii]) ."</font>", $img_name);
				}
			}
	?>
	<li>
      <p class="tem_img">
        <img src="<?=($r->img_path != "") ? $r->img_path : "/images/no_img.jpg"?>" alt="">
        <label class="check_con" for="chk<?=$r->img_id?>">
          <input type="checkbox" name="chkIds" id="chk<?=$r->img_id?>" value="<?=$r->img_id?>">
          <span class="checkmark cc_pointer"></span>
        </label>
      </p><?//제품이미지?>
      <p class="tem_btn">
        <button type="button" onClick="modal_images_open('<?=$r->img_id?>', 'M');"><i class="material-icons">create</i>수정</button>
        <button type="button" onClick="images_del('<?=$r->img_id?>');"><i class="material-icons">clear</i>삭제</button>
      </p>
      <p class="tem_name"><?=$img_name?></p><?//제품명?>
      <p class="tem_cate"><?=$r->img_category1_nm?> > <?=$r->img_category2_nm?></p><?//대분류 > 소분류?>
    </li>
	<? } ?>
  </ul>
  <div class="page_cen" style="margin:0 0 50px 0;">
    <?=$page_html?>
  </div>
</div>
<!-- 이미지등록 Modal -->
<div id="modal_images" class="dh_modal">
  <!-- Modal content -->
  <div class="modal-content"> <span id="close_images" class="dh_close">&times;</span>
	<p class="modal-tit">
	  이미지등록
	</p>
	<ul>
	  <li>
		 <span class="tit">이미지*</span>
		 <input type="hidden" id="img_id">
		 <input type="hidden" id="img_md">
		 <div class="text"><label for="img_file" class="templet_img_in"><div id="div_preview"><img id="img_preview" style="display:block;"></div></label></div>
		 <input type="file" title="이미지 파일" id="img_file" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
	  </li>
	  <li>
		<span class="tit">제품명*</span>
		<div class="text"><input style="width:100%;" type="text" id="img_name" value="" placeholder="제품명" maxlength="100"></div>
	  </li>
	  <li>
		<span class="tit">대분류</span>
		<div class="text">
			<select id="img_category1" style="width:100%;" onChange="search_category2('');">
				<option value="">- 대분류 -</option>
				<? foreach($category_list as $r) { ?>
				<option value="<?=$r->id?>"><?=$r->description?></option>
				<? } ?>
			</select>
		</div>
	  </li>
	  <li>
		<span class="tit">소분류</span>
		<div class="text">
			<select id="img_category2" style="width:100%;">
				<option value="">- 소분류 -</option>
			</select>
		</div>
	  </li>
	  <li>
		<span class="tit">상품코드</span>
		<div class="text"><input style="width:100%;" type="text" id="img_code" value="" placeholder="상품코드" maxlength="30"></div>
	  </li>
	</ul>
	<div class="btn_al_cen mg_t20">
	  <button type="button" class="btn_st1" onClick="images_save();"><i class="material-icons">done</i>저장</button>
	  <button type="button" class="btn_st1" onClick="modal_close();" style="margin-left:5px;"><i class="material-icons">clear</i>취소</button>
	</div>
  </div>
</div>
<script>
	//이미지등록 모달창 열기
	function modal_images_open(img_id, img_md){
		//alert("img_id : "+ img_id +", img_md : "+ img_md); return;
		$("#img_id").val(img_id); //이미지번호
		$("#img_md").val(img_md); //모드
		//alert("img_id : "+ img_id +", img_md : "+ img_md); return;
		if(img_id != ""){ //수정의 경우
			images_data(img_id); //이미지관리 데이타 조회
		}

		// Get the <span> element that closes the modal
		var span = document.getElementById("close_images");

		// Open the modal
		var modal = document.getElementById("modal_images");
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

	//이미지관리 데이타 조회
	function images_data(img_id){
		//alert("img_id : "+ img_id);
		$.ajax({
			url: "/mng/design/images_data",
			type: "POST",
			data: {"img_id":img_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$("#img_name").val(json.img_name); //제품명
				//alert("json.img_category1 : "+ json.img_category1 +", json.img_category2 : "+ json.img_category2);
				if(json.img_category1 != "" && json.img_category1 != "0"){
					$("#img_category1").val(json.img_category1); //대분류
					search_category2(json.img_category2); //소분류 조회
				}
				$("#img_code").val(json.img_code); //상품코드
				var img_path = json.img_path; //이미지경로
				if(img_path != ""){
					$("#img_preview").attr("src", img_path);
					$("#img_preview").css("display", "");
				}
			}
		});
		//var img_category1 = $("#img_category1").val(); //대분류
		//alert("img_category1 : "+ img_category1);
	}

	//이미지관리 저장
	function images_save(){
		var img_id = $("#img_id").val(); //이미지번호
		var img_md = $("#img_md").val(); //모드
		var imgfile = $("#img_file")[0].files[0]; //상품이미지
		var img_name = $("#img_name").val().trim(); //제품명
		var img_category1 = $("#img_category1").val(); //대분류
		var img_category2 = $("#img_category2").val(); //소분류
		var img_code = $("#img_code").val().trim(); //상품코드
		//alert("img_id : "+ img_id +", img_md : "+ img_md +", imgfile : "+ imgfile); return;
		if(img_md != "M" && (!(imgfile) || imgfile == undefined)){
			alert("이미지를 유첨하세요.");
			return;
		}
		//alert("imgfile : "+ imgfile +"\n"+"img_name : "+ img_name); return;
		if(img_name == ""){
			alert("제품명을 입력하세요.");
			$("#img_name").focus();
			return;
		}
		//alert("OK"); return;
		if(img_md == "M"){ //수정의 경우
			//if(!confirm("수정 하시겠습니까?")){
			//	return;
			//}
		}
		var formData = new FormData();
		formData.append("img_id", img_id); //이미지번호
		formData.append("imgfile", imgfile); //이미지
		formData.append("img_name", img_name); //제품명
		formData.append("img_category1", img_category1); //대분류
		formData.append("img_category2", img_category2); //소분류
		formData.append("img_code", img_code); //상품코드
		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		$.ajax({
			url: "/mng/design/images_save",
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

	//이미지관리 삭제
	function images_del(img_id){
		if(confirm("삭제 하시겠습니까?")){
			$.ajax({
				url: "/mng/design/images_del",
				type: "POST",
				data: {"img_id":img_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("삭제 되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
				}
			});
		}
	}

	//검색
	function open_page(page) {
		if(page == ""){
			alert("올바른 페이지 번호를 입력하세요.");
			return;
		}
		var url = "?page="+ page;
		var searchCate1 = $("#searchCate1").val(); //대분류
		if(searchCate1 != "") url += "&searchCate1="+ searchCate1;
		var searchCate2 = $("#searchCate2").val(); //소분류
		if(searchCate2 != "") url += "&searchCate2="+ searchCate2;
		var searchType = $("#searchType").val(); //검색타입
		if(searchType != "") url += "&searchType="+ searchType;
		var searchStr = $("#searchStr").val(); //검색내용
		if(searchStr != "") url += "&searchStr="+ searchStr;
		var imgcode = $("#imgcode").val(); //상품코드
		if(imgcode != "") url += "&imgcode="+ imgcode;
		var sort = $("#sort").val(); //정렬
		if(sort != "") url += "&sort="+ sort;
		var per_page = $("#per_page").val(); //리스트수
		if(per_page != "") url += "&per_page="+ per_page;
		//alert("page : "+ page);
		location.href = url;
	}

	//FTP Import
	function images_import(){
		if(confirm("이미지 가져오기 하시겠습니까?")){
			var url = "/mng/design/images_import";
			window.open(url, 'images_import', 'width=1000, height=780, location=no, resizable=no, scrollbars=yes');
		}
	}

	//상품코드 엑셀 업데이트
	function images_code_upload(input){
		var file_value = input.value;
		//alert("file_value : "+ file_value); return;
		var ext = file_value.slice(file_value.indexOf(".") + 1).toLowerCase();
		//alert("file_value : "+ file_value +", ext : "+ ext); return;
		if (ext == "xls" || ext == "xlsx"){
			if(confirm("상품코드 엑셀 업데이트 하시겠습니까?")){
				jsLoading(); //로딩중 호출
				var file_data = input.files[0];
				//alert("file_data : "+ file_data); return;
				var formData = new FormData();
				formData.append("file", file_data);
				formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				$.ajax({
					url: "/mng/design/images_code_upload",
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function (json) {
						input.value = null;
						hideLoading(); //로딩중 닫기
						alert("총 "+ json.cnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") +"건 중 "+ json.upd.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") +"건 상품코드가 업데이트 되었습니다.");
					}
				});
			}else{
				input.value = null;
			}
		}else{
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
			input.value = null;
		}
	}

	//소분류 조회
	function search_category2(category2){
		var category1 = $("#img_category1").val(); //대분류
		$("#img_category2").empty(); //소분류 초기화
		var option = $("<option value=''>- 소분류 -</option>");
		$("#img_category2").append(option);
		//alert("category1 : "+ category1 +", category2 : "+ category2);
		//alert("category1 : "+ category1); return;
		//소분류 조회
		$.ajax({
			url: "/mng/design/search_category2",
			type: "POST",
			data: {"category1" : category1, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					//if(key == 0) alert("["+ (key+1) +"] value.id : "+ value.id +", value.description : "+ value.description);
					if(category2 != "" && category2 == value.id){
						option = $("<option value='"+ value.id +"' Selected>"+ value.description +"</option>");
					}else{
						option = $("<option value='"+ value.id +"'>"+ value.description +"</option>");
					}
					$("#img_category2").append(option);
				});
			}
		});
	}

	//엑셀 다운로드 2021-02-03
	function images_download() {
		var url = "/mng/design/images_download?page=";
		var searchCate1 = $("#searchCate1").val(); //대분류
		if(searchCate1 != "") url += "&searchCate1="+ searchCate1;
		var searchCate2 = $("#searchCate2").val(); //소분류
		if(searchCate2 != "") url += "&searchCate2="+ searchCate2;
		var searchType = $("#searchType").val(); //검색타입
		if(searchType != "") url += "&searchType="+ searchType;
		var searchStr = $("#searchStr").val(); //검색내용
		if(searchStr != "") url += "&searchStr="+ searchStr;
		var imgcode = $("#imgcode").val(); //상품코드
		if(imgcode != "") url += "&imgcode="+ imgcode;
		var sort = $("#sort").val(); //정렬
		if(sort != "") url += "&sort="+ sort;
		location.href = url;
	}

	//선택삭제 2021-02-03
	function images_chk_del() {
		if ($("input:checkbox[name='chkIds']").is(":checked") == false) {
			alert("삭제할 이미지를 선택해주세요.");
			return;
		} else {
			if(confirm("선택한 이미지를 삭제 하시겠습니까?")){
				var chkIds = "";
				$("input[name='chkIds']:checked").each(function () {
					var id = $(this).val();
					if(chkIds == ""){
						chkIds = id;
					}else{
						chkIds += ", "+ id;
					}
				});
				//alert("chkIds : "+ chkIds); return;
				$.ajax({
					url: "/mng/design/images_chk_del",
					type: "POST",
					data: {"chkIds" : chkIds, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						alert("정상적으로 삭제 되었습니다.");
						open_page(1);
					}
				});
			}
	   }
	}

	//카테고리 엑셀 업데이트 2021-02-09
	function images_category_upload(input){
		var file_value = input.value;
		//alert("file_value : "+ file_value); return;
		var ext = file_value.slice(file_value.indexOf(".") + 1).toLowerCase();
		//alert("file_value : "+ file_value +", ext : "+ ext); return;
		if (ext == "xls" || ext == "xlsx"){
			if(confirm("카테고리 엑셀 업데이트 하시겠습니까?")){
				jsLoading(); //로딩중 호출
				var file_data = input.files[0];
				//alert("file_data : "+ file_data); return;
				var formData = new FormData();
				formData.append("file", file_data);
				formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				$.ajax({
					url: "/mng/design/images_category_upload",
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function (json) {
						input.value = null;
						hideLoading(); //로딩중 닫기
						alert("총 "+ json.cnt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") +"건 중 "+ json.upd.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") +"건 카테고리가 업데이트 되었습니다.");
					}
				});
			}else{
				input.value = null;
			}
		}else{
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
			input.value = null;
		}
	}
</script>