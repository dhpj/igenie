<?
	$userAgent = $_SERVER["HTTP_USER_AGENT"]; //웹사이트에 접속한 사용자의 정보
	$ie_yn = $this->funn->fnIeYn($userAgent);
	//echo "userAgent : ". $userAgent ."<br>";
	//echo "ie_yn : ". $ie_yn ."<br>";
?>
<? if($ie_yn == "Y"){ ?>
<div class="api_none">
  <p>
    <i class="xi-error-o"></i>해당 메뉴는 익스플로러를 지원하지 않습니다. 크롬/엣지/네이버 웨일 사용을 권장드립니다.<br  /><br  />
    <a href="https://www.google.com/chrome/" target="_blank">[ 구글 크롬 설치하러가기 ]</a>
  </p>
</div>
<? }else{ //if($ie_yn == "Y"){ ?>
<div class="wrap_pop">
  <div class="s_tit">
    스마트POP 목록
    <div class="pop_library">
      <button onClick="printer_library_open(1);">스마트POP 라이브러리</button>
    </div>
  </div>
  <!-- 스마트POP 라이브러리 Modal -->
  <div id="printer_modal" class="dh_modal3">
    <!-- Modal content -->
    <div class="modal-content"> <span class="dh_close">&times;</span>
  	<p class="modal-tit">
  	  스마트POP 라이브러리
  	</p>
  	<div id="printer_library_list"><?//스마트POP 라이브러리 리스트 영역?>
	</div>
  	<div class="page_cen" id="printer_library_page"><?//스마트POP 라이브러리 pagination?>
  	</div><!--//pagination-->
    </div>
  </div>
  <p class="step_tit">
    <span class="text_st1">STEP 1.</span> <span class="text_st2">스마트POP 타입을 선택하세요~!</span>
  </p>
  <div class="pop_type2">
    <ul>
      <li>
        <a href="/pop/printer/type/3_01">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_01.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형1
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/3_02">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_02.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형2
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/3_03">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_03.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형3
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/3_04">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_04.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형4
        </p>
        </a>
      </li>
    </ul>
  </div>
  <div class="pop_type1">
    <ul>
      <li>
        <a href="/pop/printer/type/1_01">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_01.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 1
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/1_02">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_02.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 2
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/1_03">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_03.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 3
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/1_04">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_04.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 4
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/1_05">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_05.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 5
        </p>
        </a>
      </li>
    </ul>
  </div>
  <div class="pop_type2">
    <ul>
      <li>
        <a href="/pop/printer/type/2_01">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_01.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 1
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/2_02">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_02.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 2
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/2_03">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_03.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 3
        </p>
        </a>
      </li>
      <li>
        <a href="/pop/printer/type/2_04">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_04.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 4
        </p>
        </a>
      </li>
    </ul>
  </div>
</div>
<div class="info_text">
  <p>* 가로형/세로형, 또는 상품개수에 따라 <span>스마트POP 타입을 선택</span>하신 후 상품이미지와 상품명, 가격을 입력하시면 바로 인쇄하여 사용하실 수 있습니다.  </p>
  <p>* <span>A4 사이즈 기준</span>으로 제작되었습니다. <span>인쇄시 여백없이 출력</span>하실 것을 권장드립니다.</p>
</div>
<script>
	//스마트POP 라이브러리 모달 오픈
	function printer_library_open(page){
		// Get the modal
		var modal = document.getElementById("printer_modal");

		// Get the button that opens the modal
		var btn = document.getElementById("dh_myBtn");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("dh_close")[0];

		//스마트POP 라이브러리 데이타 조회
		printer_library_data(page);

		//모달 열기
		modal.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	}

	//스마트POP 라이브러리 데이타 조회
	function printer_library_data(page){
		$.ajax({
			url: "/pop/printer/search_library",
			type: "POST",
			data: {"perpage" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				//alert(json.page);
				//alert(json.page_html);
				//alert(json.list);
				var no = 0;
				var html = "";
				var page_html = json.page_html; //pagination
				$.each(json.list, function(key, value){
					no++;
					var ppd_id = value.ppd_id; //POP번호
					var ppd_type = value.ppd_type; //타입
					var ppd_title = value.ppd_title; //업체명
					var ppd_imgpath = value.ppd_imgpath; //본문 이미지경로
					html += "<li>";
					html += "  <p class=\"tem_img\"><img src=\""+ ppd_imgpath +"\" alt=\"\"/></p>";
					html += "  <p class=\"tem_btn\">";
					html += "    <button class=\"btn_insert\" onClick=\"data_mod('"+ ppd_id +"', '"+ ppd_type +"');\">수정</button>";
					html += "    <button class=\"btn_del\" onClick=\"data_del('"+ ppd_id +"');\">삭제</button>";
					html += "  </p>";
					html += "</li>";
				});
				if(no == 0){ //등록건이 없는 경우
					html = "<p class=\"no_data\">저장된 스마트POP가 없습니다.</p>";
				}else{
					html = "<ul>"+ html +"</ul>";
				}
				$("#printer_library_list").html(html);
				$("#printer_library_page").html(page_html);
			}
		});
	}

	//스마트POP 라이브러리 데이타 수정
	function data_mod(data_id, ppd_type){
		location.href = "/pop/printer/type/"+ ppd_type +"?ppd_id="+data_id;
	}

	//스마트POP 라이브러리 데이타 삭제
	function data_del(data_id){
		if(!confirm("삭제 하시겠습니까?")){
			return;
		}
		$.ajax({
			url: "/pop/printer/data_del",
			type: "POST",
			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(json.code == "0") { //성공
					showSnackbar("삭제 되었습니다.", 1500);
					setTimeout(function() {
						printer_library_data(1); //스마트POP 라이브러리 데이타 조회
					}, 1000); //1초 지연
				} else { //오류
					showSnackbar("오류가 발생하였습니다.", 1500);
					return;
				}
			}
		});
	}
</script>
<div id="snackbar"></div><?//저장메시지 모달창 div?>
<? } //if($ie_yn == "Y"){ ?>