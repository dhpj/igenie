<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu5.php');
?>
<!-- //3차 메뉴 -->
<div class="wrap_mng">
  <div class="s_tit">고객이미지 관리 (총 <?=number_format($total_rows)?>건)</div>
  <span class="btn_imgtem">
    <!--
	<label for="category_file" class="btn_myModal2" style="cursor:pointer;margin-right:285px;">카테고리 엑셀 업데이트</label>
	<input type="file" id="category_file" onchange="images_category_upload(this);" style="display:none;">
    <button class="btn_myModal2" style="margin-right:138px;" onClick="images_download();">엑셀 다운로드</button>
    -->
  </span>
  <div class="img_search_box">
    <select id="searchType" class="mg_l20">
      <option value="1"<?=($searchType == "1") ? " Selected" : ""?>>제품명</option>
      <option value="2"<?=($searchType == "2") ? " Selected" : ""?>>업체명</option>
    </select>
	<input type="text" id="searchStr" class="input_w200" name="searchStr" placeholder="검색 내용 입력" value="<?=$searchStr?>" onKeypress="if(event.keyCode==13){ open_page(1); }">
    <input type="button" class="btn_search" id="check" value="검색" onClick="open_page(1);">
    <button type="button" onclick="images_chk_all('Y');" class="btn_h34 mg_l10" id="images_chk_all_Y">전체선택</button>
    <button type="button" onclick="images_chk_all('N');" class="btn_h34 mg_l10" id="images_chk_all_N" style="display:none;">선택해제</button>
    <button type="button" onclick="images_chk_del();" class="btn_h34 mg_l10">선택제외</button>
    <div class="fr">
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
			$mem_username = $r->mem_username; //업체명
			if($searchType == "1" and $searchStr != ""){ //제품명
				//$img_name = str_replace($searchStr, "<font color='red'>". $searchStr ."</font>", $img_name);
				$orstr = explode(' ', $searchStr);
				$orcnt = count($orstr);
				for($ii=0; $ii<$orcnt; $ii++){
					$img_name = str_replace($orstr[$ii], "<font color='red'>". addslashes($orstr[$ii]) ."</font>", $img_name);
				}
			}else if($searchType == "2" and $searchStr != ""){ //업체명
				$orstr = explode(' ', $searchStr);
				$orcnt = count($orstr);
				for($ii=0; $ii<$orcnt; $ii++){
					$mem_username = str_replace($orstr[$ii], "<font color='red'>". addslashes($orstr[$ii]) ."</font>", $mem_username);
				}
			}
	?>
	<li>
      <p class="tem_img">
        <img src="<?=($r->img_path != "") ? $r->img_path : "/images/no_img.jpg"?>" alt="">
        <label class="check_con" for="chk<?=$r->img_id?>">
          <input type="checkbox" name="chkIds" id="chk<?=$r->img_id?>" value="<?=$r->img_id?>" class="chk">
          <span class="checkmark cc_pointer"></span>
        </label>
      </p><?//제품이미지?>
      <p class="tem_btn">
        <button type="button" onClick="modal_images_open('<?=$r->img_id?>', 'M');"><i class="material-icons">unarchive</i> 업데이트</button>
        <button type="button" onClick="images_del('<?=$r->img_id?>');"><i class="material-icons">delete_sweep</i> 제외</button>
      </p>
      <p class="tem_name"><?=$img_name?></p><?//제품명?>
      <p class="tem_cate"><?=$mem_username?></p><?//업체명?>
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
	  상품이미지 라이브러리 업데이트
	</p>
	<ul>
	  <li>
		 <span class="tit">이미지*</span>
		 <input type="hidden" id="img_id">
		 <input type="hidden" id="img_md">
		 <input type="hidden" id="img_path">
		 <div class="text"><label class="templet_img_in3"><div id="div_preview"><img id="img_preview" style="display:block;"></div></label></div>
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
	  <button type="button" class="btn_st1" onClick="images_save();"><i class="material-icons">done</i>확인</button>
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
		if(img_id != ""){ //이전의 경우
			images_data(img_id); //고객이미지 데이타 조회
		}

		// Get the <span> element that closes the modal
		var span = document.getElementById("close_images");

		// Open the modal
		var modal = document.getElementById("modal_images");
		modal.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal_close(); //모달창 닫기
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal_close(); //모달창 닫기
			}
		}
	}

	//모달창 닫기
	function modal_close(){
		location.reload();
	}

	//고객이미지 데이타 조회
	function images_data(img_id){
		//alert("img_id : "+ img_id);
		$.ajax({
			url: "/mng/design/customer_images_data",
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
				$("#img_path").val(img_path); //이미지경로
				if(img_path != ""){
					$("#img_preview").attr("src", img_path);
					$("#img_preview").css("display", "");
				}
			}
		});
		//var img_category1 = $("#img_category1").val(); //대분류
		//alert("img_category1 : "+ img_category1);
	}

	//고객이미지 저장
	function images_save(){
		var img_id = $("#img_id").val(); //이미지번호
		var img_md = $("#img_md").val(); //모드
		var img_path = $("#img_path").val(); //상품이미지
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
		if(img_md == "M"){ //이전의 경우
			if(!confirm("상품이미지 관리로 이전하시겠습니까?")){
				return;
			}
		}
		var formData = new FormData();
		formData.append("img_id", img_id); //이미지번호
		formData.append("img_path", img_path); //이미지
		formData.append("img_name", img_name); //제품명
		formData.append("img_category1", img_category1); //대분류
		formData.append("img_category2", img_category2); //소분류
		formData.append("img_code", img_code); //상품코드
		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		$.ajax({
			url: "/mng/design/customer_images_save",
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

	//고객이미지 제외
	function images_del(img_id){
		if(confirm("제외 하시겠습니까?")){
			$.ajax({
				url: "/mng/design/customer_images_del",
				type: "POST",
				data: {"img_id":img_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("제외 되었습니다.", 1500);
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
		var searchType = $("#searchType").val(); //검색타입
		if(searchType != "") url += "&searchType="+ searchType;
		var searchStr = $("#searchStr").val(); //검색내용
		if(searchStr != "") url += "&searchStr="+ searchStr;
		var sort = $("#sort").val(); //정렬
		if(sort != "") url += "&sort="+ sort;
		var per_page = $("#per_page").val(); //리스트수
		if(per_page != "") url += "&per_page="+ per_page;
		//alert("page : "+ page);
		location.href = url;
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

	//선택제외
	function images_chk_del() {
		if ($("input:checkbox[name='chkIds']").is(":checked") == false) {
			alert("제외할 이미지를 선택해주세요.");
			return;
		} else {
			if(confirm("선택한 이미지를 제외 하시겠습니까?")){
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
					url: "/mng/design/customer_images_chk_del",
					type: "POST",
					data: {"chkIds" : chkIds, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						alert("정상적으로 제외 되었습니다.");
						open_page(1);
					}
				});
			}
	   }
	}

	//전체선택
	function images_chk_all(chk){
		//alert("chk : "+ chk); return;
		if(chk == "Y"){ //전체선택
			$("#images_chk_all_Y").hide(); //전체선택 버튼
			$("#images_chk_all_N").show(); //선택해제 버튼
			$(".imglib_list .chk").prop('checked', true); //전체선택 처리
		}else{ //선택해제
			$("#images_chk_all_Y").show(); //전체선택 버튼
			$("#images_chk_all_N").hide(); //선택해제 버튼
			$(".imglib_list .chk").prop('checked', false); //선택해제 처리
		}
	}
</script>