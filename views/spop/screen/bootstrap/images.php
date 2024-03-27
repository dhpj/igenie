<div class="wrap_leaflets">
  <div class="s_tit">이미지보관함 (<?=$total_rows?>/<?=$member_images_max_cnt?>건)
    <label for="files" style="cursor: pointer;">
      <span class="btn_goodsup">
	    이미지등록
	    <? if($member_images_max_cnt <= $total_rows){ ?>
	      <input type="button" id="files" onclick="alert('이미지보관함은 <?=$member_images_max_cnt?>건까지 등록 가능합니다.\n\n이미지를 삭제 후 등록해 주세요.');" style="display:none"/>
	    <? }else{ ?>
	      <input type="file" name="files[]" id="files" multiple="multiple" accept=".jpg, .png, .gif" onchange="images_upload(this);" style="display:none"/>
	    <? } ?>
      </span>
    </label>
  </div>
  <div class="img_search_box">
    <select id="searchType" class="mg_l10" style="display:none;">
      <option value="1"<?=($searchType == "1") ? " Selected" : ""?>>이미지명</option>
    </select>
	  <input style="display:none;" type="text" id="searchStr" class="input_w200" name="searchStr" placeholder="이미지명 입력" value="<?=$searchStr?>" onKeypress="if(event.keyCode==13){ open_page(1); }">
    <input style="display:none;" type="button" class="btn_search" id="check" value="검색" onClick="open_page(1);">
    <button type="button" onclick="images_rotation('L');" class="btn_h34 ibig"><i class="xi-catched"></i> 반시계방향 이미지회전</button>
    <button type="button" onclick="images_rotation('R');" class="btn_h34 ibig_r"><i class="xi-renew"></i> 시계방향 이미지회전</button>
   <div class="mobile_w100">
      <button type="button" onclick="images_chk_del();" class="btn_h34_black mg_l20"><i class="xi-trash-o"></i> 선택삭제</button>
	   <select id="sort" onChange="open_page(1);">
        <option value="1"<?=($sort == "1") ? " Selected" : ""?>>등록일순</option>
        <option value="2"<?=($sort == "2") ? " Selected" : ""?>>이미지명순</option>
      </select>
      <select id="per_page" onChange="open_page(1);">
        <option value="10"<?=($perpage == "10") ? " Selected" : ""?>>10 Line</option>
        <option value="20"<?=($perpage == "20" or perpage == "") ? " Selected" : ""?>>20 Line</option>
        <option value="50"<?=($perpage == "50") ? " Selected" : ""?>>50 Line</option>
        <option value="100"<?=($perpage == "100") ? " Selected" : ""?>>100 Line</option>
      </select>
      <input type="hidden" id="page" value="<?=$param['page']?>">
    </div>
  </div>
  <ul class="imglib_list">
    <?
		foreach($data_list as $r) {
			$img_name = $r->img_name; //이미지명
			if($searchStr != ""){
				$img_name = str_replace($searchStr, "<font color='red'>". $searchStr ."</font>", $img_name);
			}
	?>
	<li>
      <p class="tem_img">
        <span class="material-icons btn_imgdown" onClick="images_down('<?=$r->img_id?>');">south</span><?//이미지 다운로드?>
        <img src="<?=($r->img_path != "") ? $r->img_path."?v=".date("ymdHis") : "/images/no_img.jpg"?>" alt="">
        <label class="check_con" for="chk<?=$r->img_id?>">
          <input type="checkbox" name="chkIds" id="chk<?=$r->img_id?>" value="<?=$r->img_id?>">
          <span class="checkmark cc_pointer"></span>
        </label>
      </p><?//제품이미지?>
      <p class="tem_name"><?=$img_name?></p><?//이미지명?>
    </li>
	<? } ?>
  </ul>
  <div class="page_cen" style="margin:0 0 50px 0;">
    <?=$page_html?>
  </div>
</div>
<script>
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

	//이미지등록
	function images_upload(input){
        const target = document.getElementsByName('files[]');
		var formData = new FormData();
		var fno = 0;
		var fcnt = <?=$total_rows?>; //현재 등록수
		var fmax = <?=$member_images_max_cnt?>; //이미지보관함 제한 등록수
		//alert("fcnt : "+ fcnt +", fmax : "+ fmax);
		$.each(target[0].files, function(index, file){
			var file_value = file.name; //파일명
			var ext = file_value.slice(file_value.indexOf(".") + 1).toLowerCase(); //확장자
			var fileSize = file.size; //파일사이즈
			var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
			//alert("file_value"+ index +" : "+ file_value +", ext"+ index +" : "+ ext +", fileSize"+ index +" : "+ fileSize +", maxSize : "+ maxSize);
			//alert("fileSize : "+ fileSize +", maxSize : "+ maxSize);
			if((ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "gif") && maxSize >= fileSize && fmax > fcnt){ //이미지
				//alert("imgfile"+ index +" : "+ file +", fmax : "+ fmax +", fcnt : "+ fcnt);
				formData.append("imgfile"+ index, file); //업로드 파일정보
				fno++;
			}
			if((ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "gif") && maxSize >= fileSize){
				fcnt++;
			}
		});
		if(fno == 0){
			jsFileRemove(input);
			alert("한 개 이상의 이미지 파일을 선택해주세요.\n\n이미지 파일만 업로드 가능합니다. ( jpg, png, gif )\n\n업로드 가능 파일 크기는 "+ (<?=$upload_max_size?>/(1024*1024)) +"MB 입니다.");
			return;
		}
		if(fmax < fcnt){ //이미지
			alert("이미지보관함은 "+ fmax +"건까지만 등록됩니다.");
		}
		//alert("fno : "+ fno +", fcnt : "+ fcnt); return;
		formData.append("upload_cnt", fno); //이미지 갯수
		formData.append("upload_id", "imgfile"); //이미지 ID
		formData.append("upload_path", "<?=$member_images_path?>"); //업로드 경로
		formData.append("upload_img_size", "200"); //이미지 제한 크기(px 단위)(넓이 또는 높이 최대값)
		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		jsLoading(); //로딩중 호출
		$.ajax({
			url: "/spop/screen/images_save_arr",
			type: "POST",
			enctype: "multipart/form-data",
			data: formData,
			processData: false,
			contentType: false,
			success: function (json) {
				hideLoading(); //로딩중 닫기
				//alert("imgpath : "+ json.imgpath);
				showSnackbar("이미지가 등록 되었습니다.", 1500);
				setTimeout(function() {
					location.reload();
				}, 1000); //1초 지연
			}
		});
	}

	//이미지 선택삭제
	function images_chk_del(){
		if ($("input:checkbox[name='chkIds']").is(":checked") == false) {
			alert("삭제할 이미지를 선택해주세요.");
			return;
		} else {
			if(confirm("선택한 이미지를 삭제 하시겠습니까?")){
				var chkIds = [];
				$("input[name='chkIds']:checked").each(function () {
					var id = $(this).val();
					chkIds.push(id);
				});
				//alert("chkIds : "+ chkIds); return;
				$.ajax({
					url: "/spop/screen/images_chk_del",
					type: "POST",
					data: {"chkIds" : chkIds, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						showSnackbar("삭제 되었습니다.", 1500);
						setTimeout(function() {
							location.reload();
						}, 1000); //1초 지연
					}
				});
			}
	   }
	}

	//선택회전
	function images_rotation(direction){
		if ($("input:checkbox[name='chkIds']").is(":checked") == false) {
			alert("회전시킬 이미지를 선택해주세요.");
			return;
		} else {
			var str_direction = "";
			if(direction == "R"){
				str_direction = "시계방향으로 회전";
			}else{
				str_direction = "반시계방향으로 회전";
			}
			if(confirm("선택한 이미지를 "+ str_direction +" 하시겠습니까?")){
				var chkIds = [];
				$("input[name='chkIds']:checked").each(function () {
					var id = $(this).val();
					chkIds.push(id);
				});
				//alert("chkIds : "+ chkIds); return;
				$.ajax({
					url: "/spop/screen/images_rotation",
					type: "POST",
					data: {"chkIds" : chkIds, "direction" : direction, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						showSnackbar(str_direction +" 처리 되었습니다.", 1500);
						setTimeout(function() {
							location.reload();
						}, 1000); //1초 지연
					}
				});
			}
	   }
	}

	//이미지 다운로드
	function images_down(key){
		//alert("images_down > key : "+ key);
		location.href = "images_down?key="+ key;
	}
</script>
