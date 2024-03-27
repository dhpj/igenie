<link rel="stylesheet" type="text/css" href="/views/spop/evemaker/bootstrap/css/common.css?v=<?=date("ymdHis")?>"/>

<div class="wrap_leaflets">

    <!-- 타이틀 영역 -->
    <div class="s_tit">
        <? //$test_txt = $this->funn->get_img_cate("12");  ?>
        <!-- <?=$test_txt["cate1"]?>-<?=$test_txt["cate2"]?>-<?=$test_txt["cate3"]?>-<?=$test_txt["cate4"]?> -->
        스마트이벤트 목록
        <span class="t_small">
        <span class="material-icons">contact_support</span> [미리보기] 버튼 클릭 후 스마트전단 이미지를 다운받으실 수 있습니다.
        </span>
        <div class="new_leaflets">
            <!-- <a><label for="excelFile" style="cursor:pointer;">엑셀로 전단생성</label></a>
            <input type="file" id="excelFile" onChange="excelPSD(this)" style="display:none;"> -->
            <a href="javascript:write();">스마트이벤트 만들기</a>
        </div>
    </div>
    <!-- 타이틀 영역 END -->

  <div class="list_leaflets">
    <? if(!empty($data_list)){ ?>
	<ul>
      <?
		foreach($data_list as $r){
			// if($this->member->item("mem_stmall_yn") == "Y" and $r->emd_order_yn == "Y"){
			// 	$stmall_yn = "Y"; //주문하기 사용여부
			// }else{
			// 	$stmall_yn = "N"; //주문하기 사용여부
			// }
      ?>
	  <li>
        <div class="ll_date">
          <span class="date_d"><?=$r->emd_cre_dd?></span><?//등록일?>
          <span class="date_ym"><?=$r->emd_cre_ym?></span><?//등록년월?>
        </div>
        <div class="ll_title"  >
          <p class="title_t">
            <?=$r->emd_title?><?//제목?><?//($stmall_yn == "Y") ? ' <span class="material-icons">shopping_cart</span>' : ''?>
          </p>
          <p class="title_b">
            이벤트기간 : <?=date("Y-m-d", strtotime($r->emd_open_sdt))?> ~ <?=date("Y-m-d", strtotime($r->emd_open_edt))?><?//행사기간?>
          </p>
          <!-- <button class="cus_excel2" onClick="exelDown('<?=$r->emd_id?>')">엑셀 백업</button> -->
          <!-- <button class="<?=($this->member->item('mem_level') >= 100) ? "cus_open2" : "cus_open"?> <?=($r->emd_open_yn=="N")? "cus_close_b" : "cus_open_b" ?>" onClick="emd_onoff('<?=$r->emd_id?>', '<?=($r->emd_open_yn=="N")? "Y" : "N" ?>')"><?=($r->emd_open_yn=="N")? "공개_OFF" : "공개_ON" ?></button> -->

          <!-- <button class="smart_pop_go" onClick="location.href='/spop/printer?page=1&spop=<?=$r->emd_id?>'">스마트POP</button> -->
        </div>
        <div class="ll_btn1">
          <button id="dh_myBtn" class="view" style="cursor:pointer;" onClick="preview('<?=$r->emd_code?>');">미리보기</button>
          <!-- <button class="send1" onClick="lms('<?=$r->emd_code?>');">문자발송</button>
          <button class="send2" onClick="talk_adv('<?=$r->emd_code?>');">알림톡발송</button> -->
        </div>
        <div class="ll_btn2">
          <button class="insert" onClick="view('<?=$r->emd_id?>');">수정</button>
          <button class="copy" onClick="copy('<?=$r->emd_id?>');">복사</button>
          <button class="del" onClick="del('<?=$r->emd_id?>');">삭제</button>
        </div>
      </li>
      <?
		  } //foreach($data_list as $r){
      ?>
    </ul>
	<? }else { //if(!empty($data_list)){ ?>
    <div class="smart_none">
      작성된 스마트이벤트가 없습니다.<br />
      상단 우측 [ 스마트이벤트 만들기 ] 버튼을 클릭해주세요!<br />
      <!-- <span>TIP! [ 좌메뉴 > 메뉴얼 > 스마트전단문자 ] 이용가이드를 활용~</span> -->
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
    $(document).keypress(function(e) { if (e.keyCode == 13) e.preventDefault(); });

	var add = "<?=$add?>";
	if(add != "") add = "&add="+ add;
	//스마트전단 만들기 등록화면 이동
	function write(){
		window.location.href = "/spop/evemaker/write"+ add.replace(/&/gi, '?');
	}
	//스마트전단 만들기 수정화면 이동
	function view(id){
		window.location.href = "/spop/evemaker/write?emd_id="+ id +"&md=mod"+ add;
	}

	//문자발송 화면이동
	// function lms(code){
	// 	window.location.href = "/dhnbiz/sender/send/lms?emd_code="+ code;
	// }

	//알림톡발송 화면이동
	// function talk_adv(code){
	// 	window.location.href = "/dhnbiz/sender/send/talk_adv?emd_code="+ code;
	// }

    //엑셀 업로드 전단생성
	// function excelPSD(input){
	// 	var file = document.getElementById("excelFile").value;
	// 	//alert("file : "+ file); return;
	// 	//alert("step : "+ step +", id : "+ id); return;
	// 	var ext = file.slice(file.indexOf(".") + 1).toLowerCase();
	// 	//alert("file : "+ file +", ext : "+ ext); return;
	// 	if (ext == "xls" || ext == "xlsx") {
    //         jsLoading();
    //
	// 		var file_data = input.files[0];
	// 		//alert("file_data : "+ file_data); return;
	// 		var formData = new FormData();
	// 		formData.append("file", file_data);
	// 		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
	// 		$.ajax({
	// 			url: "/spop/evemaker/excel_emd_upload_ok_mt",
	// 			type: "POST",
	// 			data: formData,
	// 			processData: false,
	// 			contentType: false,
	// 			success: function (json) {
	// 				//alert("excel_upload_ok");
	// 				// console.log(json);
    //                 if(json.code=="0"){
    //                 showSnackbar("정상적으로 생성 되었습니다.", 1500);
	// 				setTimeout(function() {
	// 					location.reload();
	// 				}, 1000); //1초 지연
    //                 }else{
    //                     showSnackbar("전단생성에 문제가 있습니다", 1500);
    //                 }
	// 			}
	// 		});
	// 		//alert("OK");
	// 		var agent = navigator.userAgent.toLowerCase(); //브라우저
	// 		//alert("agent : "+ agent);
	// 		if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){ //ie 일때
	// 			//alert("ie 일때");
	// 			$("#excelFile").replaceWith( $("#excelFile").clone(true) ); //파일 초기화
	// 		}else{
	// 			$("#excelFile").val(""); //파일 초기화
	// 		}
	// 	} else {
	// 		alert("엑셀(xls, xlsx) 파일만 가능합니다.");
	// 	}
	// }

	//스마트전단 복사
	function copy(data_id){
		if(confirm("해당 스마트전단을 복사 하시겠습니까?")){
            jsLoading();
			$.ajax({
				url: "/spop/evemaker/evemaker_copy",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 복사 되었습니다.", 1500);
					setTimeout(function() {
						//location.reload();
						location.href = "/spop/evemaker";
					}, 1000); //1초 지연
				}
			});
		}
	}

	//스마트전단 삭제
	function del(data_id){
		if(confirm("정말 삭제 하시겠습니까?")){
			$.ajax({
				url: "/spop/evemaker/evemaker_remove",
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

	//스마트전단 삭제
	function setSample(data_id){
		$.ajax({
			url: "/spop/evemaker/setsample",
			type: "POST",
			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				showSnackbar("정상적으로 지정 되었습니다.", 1500);
				setTimeout(function() {
					location.reload();
				}, 1000); //1초 지연
			}
		});
	}

	//미리보기
	function preview(code){
		var url = "/smart/eveview/"+ code+"?code=preview";
		//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
		//alert("url : "+ url);
		smart_preview(url); //스마트전단 미리보기
	}

    //스마트전단 on/off
    function emd_onoff(data_id, flag){
        var flag_msg = "";
        if(flag=="N"){
            flag_msg = "비공개";
        }else{
            flag_msg = "공개";
        }
        if(confirm("해당 이벤트가"+flag_msg+"로 상태가 변경됩니다. 수정하시겠습니까?")){
			$.ajax({
				url: "/spop/evemaker/evemaker_onoff",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>", "flag" : flag},
				success: function (json) {
					showSnackbar("정상적으로 변경 되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
				}
			});
		}
    }

    //스마트전단 다운로드
	// function exelDown(data_id){
    //     if(data_id==''){
    //         showSnackbar("오류가 있습니다.", 1500);
    //         return false;
    //     }
	// 	$.ajax({
	// 		url: "/spop/evemaker/emd_chk_goods",
	// 		type: "POST",
	// 		data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json) {
    //             if(json.code=='1'){
    //                 showSnackbar("대용량전단은 지원하지 않습니다. 상품수를 줄이고 다시 시도하시기 바랍니다.", 1500);
    //                 return false;
    //             }else{
    //
    //                 if(json.mode!='O'){
    //                     var form = document.createElement("form");
    //                 	document.body.appendChild(form);
    //                 	form.setAttribute("method", "post");
    //                 	form.setAttribute("action", "/spop/evemaker/emd_download_all");
    //
    //                 	var scrfField = document.createElement("input");
    //                 	scrfField.setAttribute("type", "hidden");
    //                 	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    //                 	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    //                 	form.appendChild(scrfField);
    //
    //                 	var kindField = document.createElement("input");
    //                 	kindField.setAttribute("type", "hidden");
    //                 	kindField.setAttribute("name", "data_id");
    //                 	kindField.setAttribute("value", data_id);
    //                 	form.appendChild(kindField);
    //
    //
    //                 	form.submit();
    //                 }else{
    //                     showSnackbar("전단생성 후 최초저장을 하지 않으셨습니다. 저장 후 다시 시도하세요.", 2000);
    //                 }
    //
    //             }
    //
	// 			// showSnackbar("해당 전단 엑셀이 다운로드 되었습니다.", 1500);
	// 			// setTimeout(function() {
	// 			// 	location.reload();
	// 			// }, 1000); //1초 지연
	// 		}
	// 	});
    //
    //
	// }

	//검색
	function open_page(page) {
		location.href = "/spop/evemaker?page="+ page + add;
	}
</script>
