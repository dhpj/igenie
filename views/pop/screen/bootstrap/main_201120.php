<div class="wrap_leaflets mw1300">
  <div class="s_tit">
    스마트전단 목록
    <span class="t_small">
      <span class="material-icons">contact_support</span> [미리보기] 버튼 클릭 후 스마트전단 이미지를 다운받으실 수 있습니다.
    </span>
    <div class="new_leaflets">
      <a href="/pop/screen/write">스마트전단 만들기</a>
    </div>
  </div>
  <div class="list_leaflets">
    <? if(!empty($data_list)){ ?>
	<ul>
      <? foreach($data_list as $r) { ?>
	  <li>
        <div class="ll_date">
          <span class="date_d"><?=$r->psd_cre_dd?></span><?//등록일?>
          <span class="date_ym"><?=$r->psd_cre_ym?></span><?//등록년월?>
        </div>
        <div class="ll_title" style="cursor:pointer;" onClick="view('<?=$r->psd_id?>');">
          <p class="title_t">
            <?=$r->psd_title?><?//제목?>
          </p>
          <p class="title_b">
            행사기간 : <?=$r->psd_date?><?//행사기간?>
          </p>
        </div>
        <div class="ll_btn1">
          <button id="dh_myBtn" class="view" style="cursor:pointer;" onClick="preview('<?=$r->psd_code?>');">미리보기</button>
          <button class="send1" style="cursor:pointer;" onClick="lms('<?=$r->psd_code?>');">문자발송</button>
          <button class="send2" style="cursor:pointer;" onClick="talk_adv('<?=$r->psd_code?>');">알림톡발송</button>
        </div>
        <div class="ll_btn2">
          <button class="insert" style="cursor:pointer;" onClick="view('<?=$r->psd_id?>');">수정</button>
          <button class="copy" style="cursor:pointer;" onClick="copy('<?=$r->psd_id?>');">복사</button>
          <button class="del" style="cursor:pointer;" onClick="del('<?=$r->psd_id?>');">삭제</button>
        </div>
      </li>
      <? } //foreach($data_list as $r) { ?>
    </ul>
	<? }else { //if(!empty($data_list)){ ?>
    <div class="smart_none">
      작성된 스마트 전단이 없습니다.<br />
      상단 우측 [ 스마트전단 만들기 ] 버튼을 클릭해주세요!<br />
      <span>TIP! [ 좌메뉴 > 메뉴얼 > 스마트전단문자 ] 이용가이드를 활용~</span>
    </div>
	<? } //if(!empty($data_list)){ ?>
  </div><!--//list_leaflets-->
  <div class="page_cen">
    <?=$page_html?>
  </div><!--//pagination-->
  <!-- The Modal -->
  <div id="dh_myModal" class="dh_modal">
    <!-- Modal content -->
    <div class="modal-content2"> <span id="dh_close" class="dh_close">&times;</span>
	  <p class="modal-tit">
	    미리보기
	  </p>
	  <div class="modal-img">
	    <img src="/dhn/images/leaflets/pre_sample.jpg" alt="" />
	  </div>
    </div>
  </div>
</div><!--//wrap_leaflets-->
<script>
	//수정화면 이동
	function view(id){
		window.location.href = "/pop/screen/write?psd_id="+ id;
	}

	//문자발송 화면이동
	function lms(code){
		window.location.href = "/dhnbiz/sender/send/lms?psd_code="+ code;
	}

	//알림톡발송 화면이동
	function talk_adv(code){
		window.location.href = "/dhnbiz/sender/send/talk_adv?psd_code="+ code;
	}

	//스마트전단 복사
	function copy(data_id){
		if(confirm("해당 스마트전단을 복사 하시겠습니까?")){
			$.ajax({
				url: "/pop/screen/screen_copy",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 복사 되었습니다.", 1500);
					setTimeout(function() {
						//location.reload();
						location.href = "/pop/screen";
					}, 1000); //1초 지연
				}
			});
		}
	}

	//스마트전단 삭제
	function del(data_id){
		if(confirm("정말 삭제 하시겠습니까?")){
			$.ajax({
				url: "/pop/screen/screen_remove",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 삭제 되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
				}
			});
		}
	}

	//미리보기
	function preview(code){
		var url = "/smart/view/"+ code +"?ScreenShotYn=Y";
		//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
		//alert("url : "+ url);
		smart_preview(url); //스마트전단 미리보기
	}

	//검색
	function open_page(page) {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "get");
		form.setAttribute("action", "/pop/screen");

		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page); //검색 페이지
		form.appendChild(pageField);

		var cfrsField = document.createElement("input");
		cfrsField.setAttribute("type", "hidden");
		cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		//form.appendChild(cfrsField);
		form.submit();
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
