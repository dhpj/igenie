  <div class="pop_btn2">
    <button type="button" class="pop_btn_save" onClick="data_save();">저장하기</button>
    <button type="button" class="pop_btn_imgsave">파일다운</button>
    <button type="button" class="pop_btn_print" onClick="partShot('screen');">인쇄하기</button>
    <button type="button" class="pop_btn_list" onClick="location.href='/pop/printer'">목록으로</button>
  </div>
</div><!--//wrap_pop-->
<div class="info_text mg_t50">
  <p>* 인쇄버튼 클릭 후 스마트POP 타입의 <span>가로방향/세로방향</span>을 먼저 확인해주세요. </p>
  <p>* <span>A4 사이즈 기준</span>으로 제작되었습니다. <span>인쇄시 여백없음으로 출력</span>하실 것을 권장드립니다.</p>
</div>
<!-- 스마트POP 편집 Modal -->
<div id="myModal" class="dh_modal">
  <div class="modal-content">
    <span class="dh_close" id="modal_close">&times;</span>
    <p class="modal-tit">스마트POP 편집</p>
    <ul>
      <li id="modal_tit">
        <span class="tit" id="modal_pop_name">POP제목</span>
        <div class="text">
          <input type="text" id="goods_tit" value="" placeholder="<?=$base_goods_tit?>" maxlength="50" style="width:200px;">
        </div>
      </li>
      <li id="modal_img">
         <span class="tit">상품이미지</span>
         <input type="hidden" id="data_id" value="<?=$data_id?>">
         <input type="hidden" id="data_md" value="<?=$data_md?>">
         <input type="hidden" id="data_no" value="">
         <input type="hidden" id="imgpath" value="" style="width:100%;text-align:right;">
         <div class="text">
           <label for="img_file" class="img_mypick">내사진</label>
           <button onclick="showLibrary('img_preview', '', 'imgpath');" class="img_library">이미지선택</button>
           <label class="templet_img_in">
             <div id="div_preview">
               <img id="img_preview">
             </div>
           </label>
         </div>
         <input type="file" title="이미지 파일" name="img_file" id="img_file" class="upload-hidden" onChange="imgChange(this, 'img_preview', '', 'imgpath');" accept=".jpg, .jpeg, .png, .gif" style="width:100%;display:none;">
      </li>
      <li>
        <span class="tit">상품명</span>
        <div class="text">
          <input type="text" id="goods_name" value="" placeholder="<?=$base_goods_name?>" maxlength="100" style="width:200px;">
        </div>
      </li>
      <li id="modal_option" style="display:none;">
        <span class="tit">옵션명</span>
        <div class="text">
          <input type="text" id="goods_option" value="" placeholder="<?=$base_goods_option?>" maxlength="100" style="width:200px;">
        </div>
      </li>
      <li id="modal_org_price" style="display:none;">
        <span class="tit">정상가</span>
        <div class="text">
          <input type="text" id="goods_org_price" value="" placeholder="<?=$base_goods_org_price?>" maxlength="50" style="width:200px;">
        </div>
      </li>
      <li>
        <span class="tit">할인가</span>
        <div class="text">
          <input type="text" id="goods_price" value="" placeholder="<?=$base_goods_price?>" maxlength="50" style="width:200px;">
        </div>
      </li>
    </ul>
    <div class="btn_al_cen mg_t20">
      <button type="button" class="btn_st1" onclick="apply();"><i class="material-icons">done</i>적용</button>
      <button type="button" class="btn_st1" onclick="modal_close();" style="margin-left:5px;"><i class="material-icons">clear</i>취소</button>
    </div>
  </div>
</div>

<!-- 이미지 라이브러리 Modal -->
<div id="imgLibraryModal" class="dh_modal2">
	<div class="modal-content">
		<span id="imgLibraryClose" class="dh_close">&times;</span>
		<p class="modal-tit">이미지 라이브러리</p>
		<div class="search_input">
			<select id="id_searchName">
				<option value="name">상품명</option>
				<option value="code">상품코드</option>
			</select>
			<input type="search" placeholder="검색어를 입력하세요." id="id_searchLibrary">
			<button onclick="searchImgLibrary();">
				<i class="material-icons">search</i>
			</button>
		</div>
		<ul id="library_append_list" class="library_append_list"><?//이미지 라이브러리 영역?>
		</ul>
	</div>
</div>

<!-- 스샷
<script src='/js/dist/html2canvas.min.js'></script>
<script src='/js/dist/html2canvas.svg.js'></script>
<div id="screenshotDiv"><img src="" id="screenshot"></div>-->

<!-- 스샷 -->
<script src="/assets/js/FileSaver.min.js"></script>
<script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
<script src="/assets/js/canvas-toBlob.js"></script>

<script>
	var modal = document.getElementById("myModal");//모달
	var goods_cnt = "<?=$goods_cnt?>"; //상품수
	var data_type = "<?=$type?>"; //타입
	var addMsg = ""; //추가 메시지

	//스마트POP 편집 Modal 닫기
	function modal_close(){
		$("#data_no").val("");
		modal.style.display = "none";
	}

	//스마트POP 편집 Modal 열기
	function modal_open(no){
		//data_search(no); //선택된 POP 데이타 조회
		$("#data_no").val(no); //순번
		var goods_tit = $("#data_goods_tit").val(); //타이틀
		var goods_name = $("#data_goods_name"+ no).val(); //상품명
		var goods_price = $("#data_goods_price"+ no).val(); //가격
		var data_tit_img = $("#data_tit_img").val(); //타이틀 이미지 여부
		var data_img_yn = $("#data_img_yn").val(); //상품이미지 사용 여부
		var data_pop_name = $("#data_pop_name").val(); //POP제목
		var data_option_yn = $("#data_option_yn").val(); //옵션명 사용 여부
		var data_org_price_yn = $("#data_org_price_yn").val(); //정상가 사용 여부
		$("#goods_tit").val(goods_tit); //타이틀
		$("#goods_name").val(goods_name); //상품명
		$("#goods_price").val(goods_price); //가격
		if(data_tit_img == "Y") $("#modal_tit").hide(); //타이틀 이미지 숨김 처리
		if(data_img_yn == "N"){
			$("#modal_img").hide(); //상품이미지 숨김 처리
		}else{
			var imgpath = $("#data_imgpath"+ no).val(); //이미지경로
			$("#imgpath").val(imgpath); //이미지경로
			$("#img_preview").attr("src", imgpath); //이미지
		}
		if(data_pop_name != "") $("#modal_pop_name").html(data_pop_name); //POP제목 변경
		if(data_option_yn == "Y"){ //옵션명 사용
			var data_goods_option = $("#data_goods_option"+ no).val(); //옵션명
			$("#goods_option").val(data_goods_option); //옵션명
			$("#modal_option").show(); //옵션명 오픈 처리
		}
		if(data_org_price_yn == "Y"){ //정상가 사용
			var data_goods_org_price = $("#data_goods_org_price"+ no).val(); //정상가
			$("#goods_org_price").val(data_goods_org_price); //정상가
			$("#modal_org_price").show(); //정상가 오픈 처리
		}
		modal.style.display = "block"; //모달 열기
		var close = document.getElementById("modal_close");
		close.onclick = function() {
			modal.style.display = "none"; //모달 닫기
		}
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none"; //모달 닫기
			}
		}
	}

	//이미지 추가
	function imgChange(input, imgid, viewid, imgpath){
		var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
		var save_url = "/pop/printer/imgfile_save"; //저장 경로
		var csrf_token = "<?=$this->security->get_csrf_token_name()?>";
		var csrf_hash = "<?=$this->security->get_csrf_hash()?>";
		jsImgChange(input, imgid, viewid, imgpath, maxSize, save_url, csrf_token, csrf_hash); //이미지 추가
	}
	
	//적용
	function apply(){
		var data_img_yn = $("#data_img_yn").val(); //타이틀 이미지 여부
		var modal_pop_name = $("#modal_pop_name").html(); //POP제목
		var data_no = $("#data_no").val(); //순번
		var goods_tit = $("#goods_tit").val().trim(); //타이틀
		var goods_name = $("#goods_name").val().trim(); //상품명
		var goods_option = $("#goods_option").val().trim(); //옵션명
		var goods_org_price = $("#goods_org_price").val().trim(); //정상가
		var goods_price = $("#goods_price").val().trim(); //가격
		var data_option_yn = $("#data_option_yn").val(); //옵션명 사용 여부
		var data_org_price_yn = $("#data_org_price_yn").val(); //정상가 사용 여부
		//alert("data_no : "+ data_no +"\n"+"data_option_yn : "+ data_option_yn +"\n"+"goods_option : "+ goods_option +"\n"+"data_org_price_yn : "+ data_org_price_yn +"\n"+"goods_org_price : "+ goods_org_price);
		if(goods_cnt > 1) addMsg = data_no +"번째 ";
		if(goods_tit == ""){
			alert(addMsg + modal_pop_name +"을 입력하세요.");
			$("#goods_tit").focus();
			return false;
		}
		if(data_img_yn == "Y"){ //이미지형
			var imgpath = $("#imgpath").val(); //이미지경로
			if(imgpath == ""){
				alert(addMsg +"상품이미지를 추가 해주세요.");
				//window.scroll(0, getOffsetTop(document.getElementById("div_box")));
				return false;
			}
		}
		if(goods_name == ""){
			alert(addMsg +"상품명을 입력하세요.");
			$("#goods_name").focus();
			return false;
		}
		if(data_option_yn == "Y" && goods_option == ""){
			alert(addMsg +"옵션명을 입력하세요.");
			$("#goods_option").focus();
			return false;
		}
		if(data_org_price_yn == "Y" && goods_org_price == ""){
			alert(addMsg +"정상가를 입력하세요.");
			$("#goods_org_price").focus();
			return false;
		}
		if(goods_price == ""){
			alert(addMsg +"할인가를 입력하세요.");
			$("#goods_price").focus();
			return false;
		}
		$("#data_goods_tit").val(goods_tit); //타이틀
		$("#data_goods_name"+ data_no).val(goods_name); //상품명
		$("#data_goods_price"+ data_no).val(goods_price); //가격
		$("#view_goods_tit").html(goods_tit); //뷰 타이틀
		$("#view_goods_name"+ data_no).html(goods_name); //뷰 상품명
		$("#view_goods_price"+ data_no).html(goods_price); //뷰 가격
		if(data_img_yn == "Y"){ //이미지형
			$("#data_imgpath"+ data_no).val(imgpath); //이미지경로
			$("#view_imgpath"+ data_no).attr("src", imgpath); //뷰 이미지
		}
		if(data_option_yn == "Y"){ //옵션명 사용
			$("#data_goods_option"+ data_no).val(goods_option); //옵션명
			$("#view_goods_option"+ data_no).html(goods_option); //뷰 옵션명
		}
		if(data_org_price_yn == "Y"){ //정상가 사용
			$("#data_goods_org_price"+ data_no).val(goods_org_price); //정상가
			$("#view_goods_org_price"+ data_no).html(goods_org_price); //뷰 정상가
		}
		modal_close(); //스마트POP 편집 Modal 닫기
	}

	//데이타 저장
	var request = new XMLHttpRequest();
	function data_save(){
		var formData = new FormData();
		var ui = new Array();
		var data_img_yn = $("#data_img_yn").val(); //타이틀 이미지 여부
		var data_option_yn = $("#data_option_yn").val(); //옵션명 사용 여부
		var data_org_price_yn = $("#data_org_price_yn").val(); //정상가 사용 여부
		var modal_pop_name = $("#modal_pop_name").html(); //POP제목
		var data_id = $("#data_id").val(); //일련번호
		var data_md = $("#data_md").val(); //모드
		var goods_tit = $("#data_goods_tit").val(); //타이틀
		//alert("data_id : "+ data_id +", data_md : "+ data_md +", imgpath : "+ imgpath); return;
		//alert("goods_tit : "+ goods_tit +", goods_name : "+ goods_name +", goods_price : "+ goods_price +", imgpath : "+ imgpath); return;
		//alert("data_type : "+ data_type.substring(0, 2));
		if(goods_tit == ""){
			modal_open(1);
			alert(modal_pop_name +"을 입력하세요.");
			$("#goods_tit").focus();
			return false;
		}
		for(var i=1; i <= goods_cnt; i++){
			//alert("i : "+ i);
			if(goods_cnt > 1) addMsg = i +"번째 ";
			var goods_name = $("#data_goods_name"+ i).val(); //상품명
			var goods_option = $("#data_goods_option"+ i).val(); //옵션명
			var goods_org_price = $("#data_goods_org_price"+ i).val(); //정상가
			var goods_price = $("#data_goods_price"+ i).val(); //가격
			if(data_img_yn == "N"){ //이미지형
				var imgpath = ""; //이미지경로
			}else{
				var imgpath = $("#data_imgpath"+ i).val(); //이미지경로
				if(imgpath == ""){
					modal_open(i);
					alert(addMsg +"상품이미지를 추가 해주세요.");
					//window.scroll(0, getOffsetTop(document.getElementById("div_box")));
					return false;
				}
			}
			if(goods_name == ""){
				modal_open(i);
				alert(addMsg +"상품명을 입력하세요.");
				$("#goods_name").focus();
				return false;
			}
			if(goods_price == ""){
				modal_open(i);
				alert(addMsg +"할인가를 입력하세요.");
				$("#goods_price").focus();
				return false;
			}
			var line = { "imgpath" : imgpath, "goods_name" : goods_name, "goods_option" : goods_option, "goods_org_price" : goods_org_price, "goods_price" : goods_price };
			ui.push(line);
		}
		//alert("OK"); return;
		if(data_md == "M"){ //수정의 경우
			if(!confirm("수정 하시겠습니까?")){
				return;
			}
		}
		var uiStr = JSON.stringify(ui);
		var target = $("#screen");
		if (target != null && target.length > 0) {
			var t = target[0];
			html2canvas(t).then(function(canvas) {
				var imgfile = canvas.toDataURL("image/png");
				//alert("imgfile : "+ imgfile); return;
				formData.append("data_id", data_id); //일련번호
				formData.append("data_type", data_type); //타입
				formData.append("imgfile", imgfile); //HTML to Image
				formData.append("goods_tit", goods_tit); //타이틀
				formData.append("updata", uiStr); //상품정보
				formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				$.ajax({
					url: "/pop/printer/data_save_arr",
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function (json) {
						if(json.code == "0") { //성공
							//$("#data_id").val(json.data_id); //일련번호
							showSnackbar("저장 되었습니다.", 1500); //1.5초
							setTimeout(function() {
								location.href = "?ppd_id="+ json.data_id;
							}, 1000); //1초 지연
						} else { //오류
							//alert("오류가 발생하였습니다.");
							showSnackbar("오류가 발생하였습니다.", 1500);
							return;
						}
					}
				});
			});
		}
	}

	//파일다운
	$(".pop_btn_imgsave").on("click", function(){ //스크린샷 클릭시
		html2canvas(document.querySelector("#screen"), {scale:1}).then(function(canvas){
			canvas.toBlob(function(blob){
				saveAs(blob, "download-<?=$type?>.png"); //파일 저장
			});
		});
	﻿});

	//파일다운
	/*
	$(".pop_btn_imgsave").on("click", function(e) {
		e.preventDefault();
		document.getElementById("the-link").focus();
		html2canvas(document.querySelector("#screen"), {
			scrollX: 0,
			scrollY: 0
		  }).then(function(canvas) {
			var image = canvas.toDataURL('image/jpeg');
			document.getElementById("screenshot").src = image;
			$(this).attr('href', image);
			saveScreenshot(canvas);
		  });
	});

	//파일저장
	function saveScreenshot(canvas) {
		var downloadLink = document.createElement('a');
		downloadLink.download = 'download-<?=$type?>.jpg';
		canvas.toBlob(function(blob) {
		  downloadLink.href = URL.createObjectURL(blob)
		  downloadLink.click();
		});
	}
	*/

	//인쇄하기
	function partShot(id){
		/*
		document.getElementById("the-link").focus();
		setTimeout(() => {
			html2canvas(document.getElementById('screen'), {
			logging: true,
			profile: true,
			useCORS: true }).then(function (canvas) {
			//var data = canvas.toDataURL('image/jpeg', 0.9);
			var data = canvas.toDataURL('image/jpeg');
			var src = encodeURI(data);
			document.getElementById('screenshot').src = src;
		});}, 10);
		setTimeout(function() {
			window.print();
		}, 1000); //1초 지연
		*/
		//$("#screenshotDiv").hide();
		//document.getElementById("screenshot").src = "";
		window.print();
	}

	//라이브러리 페이지
	var pageLibrary = 1; //페이지
	var totalLibrary = 0; //전체수

	//라이브러리 검색내용 엔터키
	$("#id_searchLibrary").keydown(function(key) {
		if (key.keyCode == 13) {
			searchImgLibrary(); //라이브러리 검색
		}
	});

	//라이브러리 초기화
	function removeImgLibrary(){
		pageLibrary = 1; //페이지
		totalLibrary = 0; //전체수
		$("#library_append_list").html(""); //초기화
	}

	//라이브러리 검색시
	function searchImgLibrary(){
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
	}

	//라이브러리 스크롤시
	$("#library_append_list").scroll(function(){
		//alert("library_append_list"); return;
		var dh = $("#library_append_list")[0].scrollHeight;
		var dch = $("#library_append_list")[0].clientHeight;
		var dct = $("#library_append_list").scrollTop();
		//alert("스크롤 : " + dh + "=" + dch +  " + " + dct); return;
		if(dh == (dch+dct)) {
			var rowcnt = $(".img_select").length;
			//alert("totalLibrary : " + totalLibrary + " / rowcnt : " + rowcnt); return;
			if(rowcnt < totalLibrary) {
				searchLibrary();
			}
		}
	});

	//이미지 라이브러리 조회
	function searchLibrary(){
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = "img"; //라이브러리 타입
		//alert("pageLibrary : "+ pageLibrary +", searchstr : "+ searchstr); return;
		//url: "/imglibrary/search_library",
		$.ajax({
			url: "/pop/screen/search_library",
			type: "POST",
			data: {"perpage" : "15", "page" : pageLibrary, "searchnm" : searchnm, "searchstr" : searchstr, "library_type" : library_type, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				pageLibrary = json.page;
				totalLibrary = json.total;
				$("#library_append_list").append(json.html);
			}
		});
	}

	//라이브러리 모달창 열기
	var imgLibraryModal = document.getElementById("imgLibraryModal");
	function showLibrary(){
		var span = document.getElementById("imgLibraryClose");
		imgLibraryModal.style.display = "block";
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
		span.onclick = function() {
			removeImgLibrary(); //라이브러리 초기화
			imgLibraryModal.style.display = "none"; //라이브러리 모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == imgLibraryModal) {
				removeImgLibrary(); //라이브러리 초기화
				imgLibraryModal.style.display = "none"; //라이브러리 모달창 닫기
			}
		}
	}

	//이미지 라이브러리 선택 클릭시
	function set_img_library(imgpath){
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
			$("#img_preview").attr("src", imgpath);
			$("#imgpath").val(imgpath);
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			imgLibraryModal.style.display = "none"; //라이브러리 모달창 닫기
		}
	}
</script>
<div id="snackbar"></div><?//저장메시지 모달창 div?>