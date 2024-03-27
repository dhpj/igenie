<div class="wrap_leaflets mw1300">
  <div class="s_tit">
    스마트쿠폰 목록
    <span class="t_small">
      <span class="material-icons">contact_support</span> [미리보기] 버튼 클릭 후 스마트쿠폰 이미지를 다운받으실 수 있습니다.
    </span>
    <div class="new_leaflets">
        <? if($this->member->item('mem_eve_yn')=="Y"){ ?>
        <a href="/login/emsg_login" target="_blank"><label style="cursor:pointer;">쿨톡 로그인</label></a>
        <? } ?>
      <a href="/spop/coupon/write">스마트쿠폰 만들기</a>
    </div>
  </div>
  <div class="list_leaflets">
    <? if(!empty($data_list)){ ?>
	<ul>
      <? foreach($data_list as $r) { ?>
	  <li>
        <div class="ll_date">
          <span class="date_d"><?=$r->pcd_cre_dd?></span><?//등록일?>
          <span class="date_ym"><?=$r->pcd_cre_ym?></span><?//등록년월?>
        </div>
        <div class="ll_title" style="cursor:pointer;" onClick="view('<?=$r->pcd_id?>');">
          <p class="title_t">
            <?=$r->pcd_title?><?//제목?>
          </p>
          <p class="title_b">
            행사기간 : <?=$r->pcd_date?><?//행사기간?>
          </p>
        </div>
        <div class="ll_btn1">
          <button id="dh_myBtn" class="view" style="cursor:pointer;" onClick="preview('<?=$r->pcd_code?>', '<?=$r->pcd_type?>');">미리보기</button>
          <button class="send1" style="cursor:pointer;" onClick="lms('<?=$r->pcd_code?>', '<?=$r->pcd_type?>');">문자발송</button>
          <button class="send2" style="cursor:pointer;" onClick="talk_adv('<?=$r->pcd_code?>', '<?=$r->pcd_type?>');">알림톡발송</button>
          <? if($this->member->item("mem_eve_yn")=="Y"){ ?>
          <button class="send3" onClick="window.open('/login/emsg_login?pcd_url=<?=$r->pcd_code?>', '_blank');">쿨톡발송</button>
          <button class="send4" onclick="pcd_url_copy('<?=$r->pcd_code?>')">링크복사</button>
          <input id="hdn_pcd_link_<?=$r->pcd_code?>" type="text" value="http://smart.dhn.kr/smart/coupon/<?=$r->pcd_code?>" style="display:none;"/>

          <? } ?>
        </div>
        <div class="ll_btn2">
          <button class="insert" style="cursor:pointer;" onClick="view('<?=$r->pcd_id?>');">수정</button>
          <button class="copy" style="cursor:pointer;" onClick="copy('<?=$r->pcd_id?>');">복사</button>
          <button class="del" style="cursor:pointer;" onClick="del('<?=$r->pcd_id?>');">삭제</button>
        </div>
      </li>
      <? } //foreach($data_list as $r) { ?>
    </ul>
	<? }else { //if(!empty($data_list)){ ?>
    <div class="smart_none">
      작성된 스마트 쿠폰이 없습니다.<br />
      상단 우측 [ 스마트쿠폰 만들기 ] 버튼을 클릭해주세요!
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

function pcd_url_copy(pcd_code) {
    // input에 담긴 데이터를 선택
    // $('#hdn_psd_link_'+psd_code).attr('type', 'text');
    $('#hdn_pcd_link_'+pcd_code).show();

    $('#hdn_pcd_link_'+pcd_code).select();
    //  clipboard에 데이터 복사
    var copy = document.execCommand('copy');

    $('#hdn_pcd_link_'+pcd_code).hide();
    // $('#hdn_psd_link_'+psd_code).attr('type', 'hidden');
    // 사용자 알림
    if(copy) {
      // alert("데이터가 복사되었습니다.");
        showSnackbar("링크가 클립보드로 복사되었습니다..", 1500);
    }
}
	//수정화면 이동
	function view(id){
		window.location.href = "/spop/coupon/write?pcd_id="+ id +"<?=($add !="") ? "&add=". $add : ""?>";
	}

	//문자발송 화면이동
	function lms(code, type){
		window.location.href = "/dhnbiz/sender/send/lms?pcd_code="+ code +"&pcd_type="+ type;
	}

	//알림톡발송 화면이동
	function talk_adv(code, type){
		window.location.href = "/dhnbiz/sender/send/talk_img_adv_v4?pcd_code="+ code +"&pcd_type="+ type;
	}

	//스마트쿠폰 복사
	function copy(data_id){
		if(confirm("해당 스마트쿠폰을 복사 하시겠습니까?")){
			$.ajax({
				url: "/spop/coupon/coupon_copy",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 복사 되었습니다.", 1500);
					setTimeout(function() {
						//location.reload();
						location.href = "/spop/coupon<?=($add !="") ? "?add=". $add : ""?>";
					}, 1000); //1초 지연
				}
			});
		}
	}

	//스마트쿠폰 삭제
	function del(data_id){
		if(confirm("정말 삭제 하시겠습니까?")){
			$.ajax({
				url: "/spop/coupon/coupon_remove",
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
	function preview(code, type){
		var url = "/smart/coupon/"+ code +"?ScreenShotYn=Y";
		//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
		//alert("url : "+ url);
		coupon_preview(url, type); //스마트쿠폰 미리보기
	}

	//검색
	function open_page(page) {
		location.href = "/spop/coupon?page="+ page +"<?=($add !="") ? "&add=". $add : ""?>"; //목록 페이지
	}
</script>
