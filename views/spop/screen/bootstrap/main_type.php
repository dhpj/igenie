<div class="wrap_leaflets">
  <div class="s_tit">
    스마트전단 목록
    <span class="t_small">
      <span class="material-icons">contact_support</span> [공개_ON]으로 되어 있어야 스마트전단이 노출됩니다.
    </span>
    <div class="new_leaflets">
        <? if($this->member->item('mem_eve_yn')=="Y"){ ?>
        <a href="/login/emsg_login" target="_blank"><label style="cursor:pointer;">쿨톡 로그인</label></a>
        <? } ?>
        <a <?=($chk_test=="N")? "style='display:none;'" : "" ?>><label for="excelFile" style="cursor:pointer;">엑셀로 전단생성</label></a>
        <input type="file" id="excelFile" onChange="excelPSD(this)" style="display:none;">

      <a href="javascript:write();">스마트전단 만들기</a>
    </div>
  </div>
  <div class="list_leaflets">
    <? if(!empty($data_list)){ ?>
	<ul>
      <?
		foreach($data_list as $r){
			if($this->member->item("mem_stmall_yn") == "Y" and $r->psd_order_yn == "Y"){
				$stmall_yn = "Y"; //주문하기 사용여부
			}else{
				$stmall_yn = "N"; //주문하기 사용여부
			}
      ?>
	  <li>
        <div class="ll_date">
          <span class="date_d"><?=$r->psd_cre_dd?></span><?//등록일?>
          <span class="date_ym"><?=$r->psd_cre_ym?></span><?//등록년월?>
        </div>
        <div class="ll_title"  >
          <p class="title_t">
            <?=$r->psd_title?><?//제목?><?=($stmall_yn == "Y") ? ' <span class="material-icons">shopping_cart</span>' : ''?>
          </p>
          <p class="title_b">
            행사기간 : <?=$r->psd_date?><?//행사기간?>
          </p>
          <button <?=($chk_test=="N")? "style='display:none;'" : "" ?> class="<?=($this->member->item('mem_level') >= 100) ? "cus_excel2" : "cus_excel"?>" onClick="exelDown('<?=$r->psd_id?>')">엑셀 백업</button>
          <button   class="<?=($this->member->item('mem_level') >= 100) ? "cus_open2" : (($chk_test=="Y")? "cus_open3" : "cus_open")?> <?=($r->psd_open_yn=="N")? "cus_close_b" : "cus_open_b" ?>" onClick="psd_onoff('<?=$r->psd_id?>', '<?=($r->psd_open_yn=="N")? "Y" : "N" ?>')"><?=($r->psd_open_yn=="N")? "공개_OFF" : "공개_ON" ?></button>
          <? if($this->member->item('mem_level') >= 100){?>
          <button class="cus_sample" onClick="setSample('<?=$r->psd_id?>')">샘플</button>
          <? } ?>
          <button class="smart_pop_go" onClick="location.href='/spop/printer?page=1&spop=<?=$r->psd_id?>'">스마트POP</button>
        </div>
        <div class="ll_btn1">
          <button id="dh_myBtn" class="view" style="cursor:pointer;" onClick="preview('<?=$r->psd_code?>');">미리보기</button>
          <button class="send1" onClick="lms('<?=$r->psd_code?>');">문자발송</button>
          <button class="send2" onClick="talk_adv('<?=$r->psd_code?>');">알림톡발송</button>
          <? if($this->member->item("mem_eve_yn")=="Y"){ ?>
          <button class="send3" onClick="window.open('/login/emsg_login?psd_url=<?=$r->psd_code?>', '_blank');">쿨톡발송</button>
          <button class="send4" onclick="psd_url_copy('<?=$r->psd_code?>')">링크복사</button>
          <input id="hdn_psd_link_<?=$r->psd_code?>" type="text" value="http://smart.dhn.kr/smart/view/<?=$r->psd_code?>" style="display:none;"/>

          <? } ?>
        </div>
        <div class="ll_btn2">
          <button class="insert" onClick="view('<?=$r->psd_id?>');">수정</button>
          <button class="copy" onClick="copy('<?=$r->psd_id?>');">복사</button>
          <button class="del" onClick="del('<?=$r->psd_id?>');">삭제</button>
        </div>
      </li>
      <?
		  } //foreach($data_list as $r){
      ?>
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

function psd_url_copy(psd_code) {
    // input에 담긴 데이터를 선택
    // $('#hdn_psd_link_'+psd_code).attr('type', 'text');
    $('#hdn_psd_link_'+psd_code).show();

    $('#hdn_psd_link_'+psd_code).select();
    //  clipboard에 데이터 복사
    var copy = document.execCommand('copy');

    $('#hdn_psd_link_'+psd_code).hide();
    // $('#hdn_psd_link_'+psd_code).attr('type', 'hidden');
    // 사용자 알림
    if(copy) {
      // alert("데이터가 복사되었습니다.");
        showSnackbar("링크가 클립보드로 복사되었습니다..", 1500);
    }
}

    $(document).keypress(function(e) { if (e.keyCode == 13) e.preventDefault(); });
	var add = "<?=$add?>";
	if(add != "") add = "&add="+ add;
	//스마트전단 만들기 등록화면 이동
	function write(){
		window.location.href = "/spop/screen/write"+ add.replace(/&/gi, '?');
	}
	//스마트전단 만들기 수정화면 이동
	function view(id){
		window.location.href = "/spop/screen/write?psd_id="+ id +"&md=mod"+ add;
	}

	//문자발송 화면이동
	function lms(code){
		window.location.href = "/dhnbiz/sender/send/lms?psd_code="+ code;
	}

	//알림톡발송 화면이동
	function talk_adv(code){
		window.location.href = "/dhnbiz/sender/send/talk_adv_v3?psd_code="+ code;
	}

    //엑셀 업로드 전단생성
	function excelPSD(input){
		var file = document.getElementById("excelFile").value;
		//alert("file : "+ file); return;
		//alert("step : "+ step +", id : "+ id); return;
		var ext = file.slice(file.indexOf(".") + 1).toLowerCase();
		//alert("file : "+ file +", ext : "+ ext); return;
		if (ext == "xls" || ext == "xlsx") {
            jsLoading();

			var file_data = input.files[0];
			//alert("file_data : "+ file_data); return;
			var formData = new FormData();
			formData.append("file", file_data);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/spop/screen/excel_psd_upload_ok_tab_new",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
					//alert("excel_upload_ok");
					// console.log(json);
                    if(json.code=="0"){
                    showSnackbar("정상적으로 생성 되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
                    }else{
                        showSnackbar("전단생성에 문제가 있습니다", 1500);
                    }
				}
			});
			//alert("OK");
			var agent = navigator.userAgent.toLowerCase(); //브라우저
			//alert("agent : "+ agent);
			if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){ //ie 일때
				//alert("ie 일때");
				$("#excelFile").replaceWith( $("#excelFile").clone(true) ); //파일 초기화
			}else{
				$("#excelFile").val(""); //파일 초기화
			}
		} else {
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
		}
	}

	//스마트전단 복사
	function copy(data_id){
		if(confirm("해당 스마트전단을 복사 하시겠습니까?")){
            jsLoading();
			$.ajax({
				url: "/spop/screen/screen_copy",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 복사 되었습니다.", 1500);
					setTimeout(function() {
						//location.reload();
						location.href = "/spop/screen";
					}, 1000); //1초 지연
				}
			});
		}
	}

	//스마트전단 삭제
	function del(data_id){
		if(confirm("정말 삭제 하시겠습니까?")){
			$.ajax({
				url: "/spop/screen/screen_remove",
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
			url: "/spop/screen/setsample",
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

    //스마트전단 on/off
    function psd_onoff(data_id, flag){
        var flag_msg = "";
        if(flag=="N"){
            flag_msg = "비공개";
        }else{
            flag_msg = "공개";
        }
        if(confirm("해당전단이 "+flag_msg+"로 상태가 변경됩니다. 수정하시겠습니까?")){
			$.ajax({
				url: "/spop/screen/screen_onoff",
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

	//미리보기
	function preview(code){
		var url = "/smart/view/"+ code +"?ScreenShotYn=Y"+ add;
		//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
		//alert("url : "+ url);
		smart_preview(url); //스마트전단 미리보기
	}

    //스마트전단 다운로드
	function exelDown(data_id){

        var form = document.createElement("form");
    	document.body.appendChild(form);
    	form.setAttribute("method", "post");
    	form.setAttribute("action", "/spop/screen/psd_download_tab_new");

    	var scrfField = document.createElement("input");
    	scrfField.setAttribute("type", "hidden");
    	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(scrfField);

    	var kindField = document.createElement("input");
    	kindField.setAttribute("type", "hidden");
    	kindField.setAttribute("name", "data_id");
    	kindField.setAttribute("value", data_id);
    	form.appendChild(kindField);

    	form.submit();
	}

	//검색
	function open_page(page) {
		location.href = "/spop/screen?page="+ page + add;
	}
</script>
