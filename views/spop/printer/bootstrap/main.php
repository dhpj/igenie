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
        <? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 보임 ?>
            <a href="/spop/printer/print_log">POP사용현황</a>
        <?}?>
			<button id="myLogoBtn">POP 로고 관리</button>
			<button id="myBtn">POP 행사타이틀 관리</button>
      <button onClick="printer_library_open(1);">POP 저장목록</button>
    </div>
  </div>

	<div id="myLogoModal" class="dh_modal4">
	<div class="modal-content">
	  <span class="dh_close3" id="logo_modal_close">&times;</span>
	  <p class="modal-tit">POP로고 관리</p>
	  <ul>
	    <li>
	      <span class="tit" id="modal_shop_name">업체명</span>
	      <div class="text">
	        <input type="text" id="com_name" value="<?=(!empty($logos->ppl_name))? $logos->ppl_name : $this->member->item("mem_username")?>" placeholder="업체명을 입력해주세요" maxlength="50" style="width:200px;">
	      </div>
	    </li>
	    <li id="modal_logo_img">
	       <span class="tit">로고등록</span>
	       <input type="hidden" id="imgpath" value="" style="width:100%;text-align:right;">
	       <div class="text">
	         <label for="img_file" class="img_mypick">내파일</label>
	         <label class="templet_img_in">
	           <div id="div_preview">
	             <img id="img_preview" src="<?=(!empty($logos->ppl_imgpath))? $logos->ppl_imgpath : '' ?>">
	           </div>
	         </label>
					 <button id="delimg" class="btn_imgdel" onclick="del_logo()" <? if(empty($logos->ppl_imgpath)){ ?>style="display:none" <? } ?>>이미지삭제</button>
	       </div>

	       <input type="file" title="이미지 파일" name="img_file" id="img_file" class="upload-hidden" onChange="imgChange(this, 'img_preview', '', 'imgpath');" accept=".jpg, .jpeg, .png, .gif" style="width:100%;display:none;">
				 <input type="hidden" id="hdn_use_img_yn" value="<? if(!empty($logos->ppl_imgpath)){ ?>Y<? }else{?>N<?}?>"/>
	    </li>
			<li>
	      <span class="tit">사용여부</span>
	      <div class="text">
					<label class="radio_con">
            <input type="radio" name="use_yn" value="Y" <? if(!empty($logos->ppl_useyn)){ if($logos->ppl_useyn=='Y'){?> checked <?} }else{?> checked <?}?>>
            사용함
          </label>
          <label class="radio_con mg_l10">
            <input type="radio" name="use_yn" value="N" <? if(!empty($logos->ppl_useyn)){ if($logos->ppl_useyn=='N'){?> checked <?} }?>>
            사용안함
          </label>
	      </div>
	    </li>
	  </ul>
	  <div class="btn_al_cen mg_t20">
	    <button type="button" class="btn_st1" onclick="logo_save();"><i class="material-icons">done</i>적용</button>
	    <button type="button" class="btn_st1" onclick="modal_close();" style="margin-left:5px;"><i class="material-icons">clear</i>취소</button>
	  </div>
	</div>
	</div>
	<!-- POP타입별 타이틀저장 Modal -->
<div id="myModal" class="dh_modal3">
	<div class="modal-content"> <span class="dh_close2">&times;</span>
	<p class="modal-tit">
		POP타입별 행사타이틀 저장
	</p>
	<p class="modal_info">
					* POP타입별 즐겨쓰는 행사타이틀을 저장해서 사용하실 수 있습니다~
					행사타이틀을 수정할 수 있는 <span>POP타입을 확인</span>하신 후 작성해주세요^^
	</p>
	 <div class="poptit_save">
		 <ul>
			<li>
				<img src="/images/pop_layout16.jpg" alt="">
				<span>상품개수 X 1 가로텍스트_1</span>
				<input type="text" id="type3_01" value="<?=$title->type3_01?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout17.jpg" alt="">
				<span>상품개수 X 1 가로텍스트_2</span>
				<input type="text" id="type3_02" value="<?=$title->type3_02?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout02.jpg" alt="">
				<span>상품개수 X 1 세로텍스트_1</span>
				<input type="text" id="type1_09" value="<?=$title->type1_09?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<!--<li>
				<span>가로 - 텍스트형3</span>
				<input type="text" id="type3_03" value="<?=$title->type3_03?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<span>가로 - 텍스트형4</span>
				<input type="text" id="type3_04" value="<?=$title->type3_04?>" placeholder="타이틀 입력" maxlength="50">
			</li>-->
			<!--<li>
				<span>가로 - 상품개수 : 1a</span>
				<input type="text" id="type2_05" value="<?=$title->type2_05?>" placeholder="타이틀 입력" maxlength="50">
			</li>-->
			<li>
				<img src="/images/pop_layout04.jpg" alt="">
				<span>상품개수 X 1 가로_1</span>
				<input type="text" id="type2_01" value="<?=$title->type2_01?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout06.jpg" alt="">
				<span>상품개수 X 2 가로_2</span>
				<input type="text" id="type2_02" value="<?=$title->type2_02?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<!--<li>
				<span>가로 - 상품개수 : 3</span>
				<input type="text" id="type2_03" value="<?=$title->type2_03?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<span>가로 - 상품개수 : 4</span>
				<input type="text" id="type2_04" value="<?=$title->type2_04?>" placeholder="타이틀 입력" maxlength="50">
			</li>-->

			<li>
				<img src="/images/pop_layout03.jpg" alt="">
				<span>상품개수 X 1 세로_1</span>
				<input type="text" id="type1_01" value="<?=$title->type1_01?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout05.jpg" alt="">
				<span>상품개수 X 2 세로_2</span>
				<input type="text" id="type1_02" value="<?=$title->type1_02?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout07.jpg" alt="">
				<span>상품개수 X 3 세로_3</span>
				<input type="text" id="type1_03" value="<?=$title->type1_03?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout09.jpg" alt="">
				<span>상품개수 X 4 세로_4</span>
				<input type="text" id="type1_04" value="<?=$title->type1_04?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout20.jpg" alt="">
				<span>상품개수 X 4 세로_5</span>
				<input type="text" id="type1_07" value="<?=$title->type1_07?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout11.jpg" alt="">
				<span>상품개수 X 5 세로_6</span>
				<input type="text" id="type1_05" value="<?=$title->type1_05?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout21.jpg" alt="">
				<span>상품개수 X 2 세로분할_2</span>
				<input type="text" id="type1_06" value="<?=$title->type1_06?>" placeholder="타이틀 입력" maxlength="50">
			</li>
			<li>
				<img src="/images/pop_layout22.jpg" alt="">
				<span>상품개수 X 3 세로분할_4</span>
				<input type="text" id="type1_08" value="<?=$title->type1_08?>" placeholder="타이틀 입력" maxlength="50">
			</li>
		 </ul>
   </div>
	 <p class="btn_al_cen">
		  <button class="btn_st3" type="button" onclick="title_save()"><i class="xi-check"></i> 저장하기</button>
		</p>
	</div>
</div>
	<script>




// Get the modal
var modal = document.getElementById("myModal");


// Get the button that opens the modal
var btn = document.getElementById("myBtn");

var logobtn = document.getElementById("myLogoBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("dh_close2")[0];

var span3 = document.getElementsByClassName("dh_close3")[0];

var page = '<?=$page?>';
var type = '<?=$type?>';
var spop = '<?=$spop?>';
// var favlist = [];

var iurl = location.search;
console.log(iurl);


var favlist = JSON.parse('<?php echo json_encode($fav_list)?>');
// console.log(favlist.length);
// onsole.log(Object.keys(favlist).length);
// When the user clicks the button, open the modal
btn.onclick = function() {
  //modal.style.display = "block";
  if($('.pop_tit_btn').is(':visible')==true){
      $('.pop_tit_btn').hide();
      $('.showtitle').hide();
      $('.hidetitle').show();
  }else{
      $('.pop_tit_btn').show();
      $('.showtitle').show();
      $('.hidetitle').hide();
  }

}

logobtn.onclick = function() {
  $('#myLogoModal').show();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

span3.onclick = function() {
  $('#myLogoModal').hide();
}

function modal_close(){
  // $("#data_no").val("");
	$('#myLogoModal').hide();
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
$(document).ready(function(){
    var title_cnt = '<?=$title_cnt?>';
    var title_list = JSON.parse('<?=json_encode($title_list)?>');
    console.log("title_cnt :"+title_cnt +" - title_list : "+title_list);
    console.log("title_list.length :"+title_list.length);

    if(title_cnt>0){
        for(var i in title_list){
            console.log(title_list[i].type);
            $('#t_'+title_list[i].type).val(title_list[i].title);
        }
    }
    $('.showtitle').hide();
	if(page!=''){
		if(page==1){
			$('#step1_title').show();
			$('#step1_div').show();
			$('#step2_title').hide();
			$('#step2_div').hide();
			$('.pop_typelist').show();
            $('#myBtn').show();

		}else if(page==2){
			$('#step2_title').show();
			$('#step2_div').show();
			$('#step1_title').hide();
			$('#step1_div').hide();
			$('.pop_typelist').hide();
            $('#myBtn').hide();
		}else if(page==3){
			$('#step2_title').hide();
			$('#step2_div').show();
			$('#step1_title').show();
			$('#step1_div').hide();
			$('.pop_typelist').show();
            $('#myBtn').hide();
		}
	}else{
		$('#step1_title').show();
		$('#step1_div').show();
		$('#step2_title').hide();
		$('#step2_div').hide();
		$('.pop_typelist').show();
        $('#myBtn').show();

	}
	if(type!=''){
		$('.step2_item').hide();
		$('.'+type).show();
		if(favlist.length>0){
			for(var i in favlist){
				$('#'+favlist[i].ppf_type).children('dt').addClass('favortype_on');
			}
		}
		$('#step2_div').removeClass('favortype');
	}else{
		$('#step2_div').addClass('favortype');
		if(page!=''){
			if(page==3){
				$('.step2_item').hide();

					if(favlist.length>0){
						for(var i in favlist){
							$('#'+favlist[i].ppf_type).show();
							$('#'+favlist[i].ppf_type).children('dt').addClass('favortype_on');
						}
					}
			}
		}
	}



});

//이미지 추가
function imgChange(input, imgid, viewid, imgpath){
  var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
  var save_url = "/spop/printer/imgfile_save"; //저장 경로
  var csrf_token = "<?=$this->security->get_csrf_token_name()?>";
  var csrf_hash = "<?=$this->security->get_csrf_hash()?>";
  jsImgChange(input, imgid, viewid, imgpath, maxSize, save_url, csrf_token, csrf_hash); //이미지 추가
  //setTimeout(() =>ondatachange(), 2000);
	setTimeout(function() {
		$('#hdn_use_img_yn').val('Y');
		$('#delimg').show();
	}, 1000); //1초 지연
}

function del_logo(){
	$('#imgpath').val('');
	$('#img_preview').attr('src', '');
	$('#img_file').val('');
	$('#hdn_use_img_yn').val('N');
	$('#delimg').hide();
}

</script>
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
  <p id="step1_title" class="step_tit">
    <span class="text_st1">STEP 1.</span> <span class="text_st2">스마트POP 타입을 선택하세요~!</span>
  </p>
	<p id="step2_title" class="step_tit" style="display:none;">
    <span class="text_st1">STEP 2.</span> <span class="text_st2">스마트POP 디자인을 선택하세요~!</span>
  </p>
	<div class="spop_step1_cate">
		<div class="pop_typelist">
			<ul>
				<li <?=($page=='1')? "class='pop_mon'" : ''?> onclick="wholelink();"><span class="material-icons">list_alt</span> 전체보기</li>
				<li <?=($page=='3')? "class='pop_mon'" : ''?> onclick="favlink();"><span class="material-icons">folder_special</span> 즐겨찾기</li>
			</ul>
		</div>
		<div id="step1_div">

			<div class="pop_typebox">
				<p class="pop_tit">
				 <span>POP 텍스트형</span> <span class="material-icons">info</span> <span>상품이미지가 없는 POP 텍스트 타입입니다.</span>
				</p>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 1</span> 가로텍스트_1</p>
			<p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_3_01" name="hdn_title" value="3_01"/><input type="text" id="t_3_01" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1ht_1<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout16.jpg" alt="">
	      </dd>

	    </dl>
		<dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 1</span> 가로텍스트_2</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_3_02" name="hdn_title" value="3_02"/><input type="text" id="t_3_02" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1ht_2<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout17.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
	        상품개수 <span>X 1</span> 가로텍스트_3
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1ht_3<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout19.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
	        상품개수 <span>X 1</span> 가로텍스트_4
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1ht_4<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout18.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
		     <p class="hidetitle">상품개수 <span>X 1</span> 가로텍스트_5</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_3_05" name="hdn_title" value="3_05"/><input type="text" id="t_3_05" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1ht_5<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		    <img src="/images/pop_layout23.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
          <dt>
	          상품개수 <span>X 1</span> 가로텍스트_6
          </dt>
          <dd onclick="location.href='/spop/printer?page=2&type=1ht_6<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout25.jpg" alt="">
          </dd>
        </dl>
		<dl>
          <dt>
	          <p class="hidetitle">상품개수 <span>X 1</span> 가로텍스트_7</p>
              <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_3_07" name="hdn_title" value="3_07"/><input type="text" id="t_3_07" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
          </dt>
          <dd onclick="location.href='/spop/printer?page=2&type=1ht_7<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout26.jpg" alt="">
          </dd>
        </dl>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 1</span> 세로텍스트_1</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_09" name="hdn_title" value="1_09"/><input type="text" id="t_1_09" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1vt<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout02.jpg" alt="">
	      </dd>
	    </dl>
        <dl>
	      <dt>
	        상품개수 <span>X 1</span> 세로텍스트_2
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1vt_2<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout27.jpg" alt="">
	      </dd>
	    </dl>
        <dl>
	      <dt>
	        상품개수 <span>X 1</span> 세로텍스트_3
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1vt_3<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout28.jpg" alt="">
	      </dd>
	    </dl>
			</div>
			<div class="pop_typebox">
				<p class="pop_tit">
				 <span>POP 텍스트 분할형</span> <span class="material-icons">info</span> <span>A4사이즈로 인쇄 후 2~8컷으로 잘라서 쓰는 POP 텍스트 타입입니다.</span>
				</p>
                <dl>
        	      <dt>
        	        <p class="hidetitle">상품개수 <span>X 2</span> 세로텍스트_1</p>
                    <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_4_05" name="hdn_title" value="4_05"/><input type="text" id="t_4_05" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
        	      </dt>
        	      <dd onclick="location.href='/spop/printer?page=2&type=2vtd_1<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
        	        <img src="/images/pop_layout29.jpg" alt="">
        	      </dd>
        	    </dl>
                <dl>
        	      <dt>
        	        <p class="hidetitle">상품개수 <span>X 3</span> 세로텍스트_2</p>
                    <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_4_06" name="hdn_title" value="4_06"/><input type="text" id="t_4_06" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
        	      </dt>
        	      <dd onclick="location.href='/spop/printer?page=2&type=2vtd_2<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
        	        <img src="/images/pop_layout30.jpg" alt="">
        	      </dd>
        	    </dl>
                <dl>
        	      <dt>
        	        <p class="hidetitle">상품개수 <span>X 4</span> 가로텍스트_1</p>
                    <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_4_07" name="hdn_title" value="4_07"/><input type="text" id="t_4_07" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
        	      </dt>
        	      <dd onclick="location.href='/spop/printer?page=2&type=2vtd_3<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
        	        <img src="/images/pop_layout31.jpg" alt="">
        	      </dd>
        	    </dl>
                <dl>
        	      <dt>
        	        <p class="hidetitle">상품개수 <span>X 8</span> 세로텍스트_3</p>
                    <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_4_08" name="hdn_title" value="4_08"/><input type="text" id="t_4_08" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
        	      </dt>
        	      <dd onclick="location.href='/spop/printer?page=2&type=2vtd_4<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
        	        <img src="/images/pop_layout32.jpg" alt="">
        	      </dd>
        	    </dl>
						</div>

			<div class="pop_typebox">
				<p class="pop_tit">
				 <span>POP 이미지형</span> <span class="material-icons">info</span> <span>상품이미지가 있는 POP 이미지 타입입니다.</span>
				</p>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 1</span> 가로_1</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_2_01" name="hdn_title" value="2_01"/><input type="text" id="t_2_01" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1hi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout04.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
	        상품개수 <span>X 2</span> 가로_2
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=2hi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout06.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
	        상품개수 <span>X 3</span> 가로_3
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=3hi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout08.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
	        상품개수 <span>X 4</span> 가로_4
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=4hi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout10.jpg" alt="">
	      </dd>
	    </dl>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 1</span> 세로_1</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_01" name="hdn_title" value="1_01"/><input type="text" id="t_1_01" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=1vi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout03.jpg" alt="">
	      </dd>
	    </dl>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 2</span> 세로_2</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_02" name="hdn_title" value="1_02"/><input type="text" id="t_1_02" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=2vi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout05.jpg" alt="">
	      </dd>
	    </dl>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 3</span> 세로_3</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_03" name="hdn_title" value="1_03"/><input type="text" id="t_1_03" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=3vi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout07.jpg" alt="">
	      </dd>
	    </dl>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 4</span> 세로_4</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_04" name="hdn_title" value="1_04"/><input type="text" id="t_1_04" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=4vi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout09.jpg" alt="">
	      </dd>
	    </dl>
		<dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 4</span> 세로_5</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_07" name="hdn_title" value="1_07"/><input type="text" id="t_1_07" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=4vi_1<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout20.jpg" alt="">
	      </dd>
	    </dl>
	    <dl>
	      <dt>
	        <p class="hidetitle">상품개수 <span>X 5</span> 세로_6</p>
            <p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_05" name="hdn_title" value="1_05"/><input type="text" id="t_1_05" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	      </dt>
	      <dd onclick="location.href='/spop/printer?page=2&type=5vi<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
	        <img src="/images/pop_layout11.jpg" alt="">
	      </dd>
	    </dl>
			</div>
			<div class="pop_typebox">
				<p class="pop_tit">
				 <span>POP 이미지 분할형</span> <span class="material-icons">info</span> <span>A4사이즈로 인쇄 후 2~4컷으로 잘라서 쓰는 POP 이미지 타입입니다.</span>
				</p>
<dl>
	<dt>
		상품개수 <span>X 2</span> 세로이미지_1
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=2vid<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout12.jpg" alt="">
	</dd>
</dl>
<dl>
	<dt>
		<p class="hidetitle">상품개수 <span>X 2</span> 세로이미지_2</p>
				<p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_06" name="hdn_title" value="1_06"/><input type="text" id="t_1_06" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=2vid_1<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout21.jpg" alt="">
	</dd>
</dl>
<dl>
	<dt>
		상품개수 <span>X 3</span> 세로이미지_3
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=3vid<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout13.jpg" alt="">
	</dd>
</dl>
<dl>
	<dt>
		<p class="hidetitle">상품개수 <span>X 3</span> 세로이미지_4</p>
				<p class="showtitle" style="display:none;"><input type="hidden" id="hdnt_1_08" name="hdn_title" value="1_08"/><input type="text" id="t_1_08" name="title_content" placeholder="행사타이틀 입력" maxlength="50"/></p>
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=3vid_1<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout22.jpg" alt="">
	</dd>
</dl>
<dl>
	<dt>
		상품개수 <span>X 4</span> 세로이미지_5
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=4vid<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout14.jpg" alt="">
	</dd>
</dl>
<dl>
	<dt>
		상품개수 <span>X 4</span> 가로이미지_1
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=4hid<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout15.jpg" alt="">
	</dd>
</dl>
<dl>
	<dt>
		상품개수 <span>X 4</span> 세로이미지_1
	</dt>
	<dd onclick="location.href='/spop/printer?page=2&type=9hid<?=(!empty($spop))? '&spop='.$spop : '' ?>'">
		<img src="/images/pop_layout44.jpg" alt="">
	</dd>
</dl>
</div>
		</div>

		<div id="step2_div" style="display:none;">
	    <dl class="1ht_1 step2_item" id="t3_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_01', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type3_01.jpg" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_1 step2_item" id="t3_01_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_01', 'popbg301_01');">
	        <img src="/dhn/images/leaflets/pop/popbg301_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_1 step2_item" id="t3_01_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_01', 'popbg301_02');">
	        <img src="/dhn/images/leaflets/pop/popbg301_02_p.png" alt="">
	      </dd>
	    </dl>
	    <dl class="1ht_2 step2_item" id="t3_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_02', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type3_02.jpg" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_2 step2_item" id="t3_02_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_02', 'popbg302_01');">
	        <img src="/dhn/images/leaflets/pop/popbg302_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_2 step2_item" id="t3_02_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_02', 'popbg302_02');">
	        <img src="/dhn/images/leaflets/pop/popbg302_02_p.png" alt="">
	      </dd>
	    </dl>
	    <dl class="1ht_3 step2_item" id="t3_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_03', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type3_03.jpg" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_3 step2_item" id="t3_03_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_03', 'popbg303_01');">
	        <img src="/dhn/images/leaflets/pop/popbg303_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_3 step2_item" id="t3_03_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_03', 'popbg303_02');">
	        <img src="/dhn/images/leaflets/pop/popbg303_02_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_3 step2_item" id="t3_03_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_03', 'popbg303_03');">
	        <img src="/dhn/images/leaflets/pop/popbg303_03_p.png" alt="">
	      </dd>
	    </dl>
	    <dl class="1ht_4 step2_item" id="t3_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_04', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type3_04.jpg" alt="">
	      </dd>
	    </dl>
		<dl class="1ht_4 step2_item" id="t3_04_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_04', 'popbg304_01');">
	        <img src="/dhn/images/leaflets/pop/popbg304_01_p.png" alt="">
	      </dd>
	    </dl>
		<dl class="1ht_4 step2_item" id="t3_04_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_04', 'popbg304_02');">
	        <img src="/dhn/images/leaflets/pop/popbg304_02_p.png" alt="">
	      </dd>
	    </dl>
		<dl class="1ht_5 step2_item" id="t3_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_05', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type3_05.png" alt="">
	      </dd>
	    </dl>
		<dl class="1ht_5 step2_item" id="t3_05_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_05', 'popbg305_01');">
	        <img src="/dhn/images/leaflets/pop/popbg305_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_5 step2_item" id="t3_05_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_05', 'popbg305_02');">
	        <img src="/dhn/images/leaflets/pop/popbg305_02_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_6 step2_item" id="t3_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_06', '');">
	        <img src="/dhn/images/leaflets/pop/popbg306_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_6 step2_item" id="t3_06_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_06', 'popbg306_02');">
	        <img src="/dhn/images/leaflets/pop/popbg306_02_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_7 step2_item" id="t3_07">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_07', '');">
	        <img src="/dhn/images/leaflets/pop/popbg307_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_7 step2_item" id="t3_07_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_07', 'popbg307_01');">
	        <img src="/dhn/images/leaflets/pop/popbg307_02_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1ht_7 step2_item" id="t3_07_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('3_07', 'popbg307_02');">
	        <img src="/dhn/images/leaflets/pop/popbg307_03_p.png" alt="">
	      </dd>
	    </dl>
	    <dl class="1vi step2_item" id="t1_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_01', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_01.png" alt="">
	      </dd>
	    </dl>
			<dl class="1vi step2_item" id="t1_01_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_01', 'popbg101_01');">
	        <img src="/dhn/images/leaflets/pop/popbg101_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1vi step2_item" id="t1_01_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_01', 'popbg101_02');">
	        <img src="/dhn/images/leaflets/pop/popbg101_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1vi step2_item" id="t1_01_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_01', 'popbg101_03');">
	        <img src="/dhn/images/leaflets/pop/popbg101_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1vi step2_item" id="t1_01_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_01', 'popbg101_04');">
	        <img src="/dhn/images/leaflets/pop/popbg101_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1vi step2_item" id="t1_01_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_01', 'popbg101_05');">
	        <img src="/dhn/images/leaflets/pop/popbg101_05_p.png" alt="">
	      </dd>
	    </dl>
	    <dl class="2vi step2_item" id="t1_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_02', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_02.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vi step2_item" id="t1_02_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_02', 'popbg102_01');">
	        <img src="/dhn/images/leaflets/pop/popbg102_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vi step2_item" id="t1_02_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_02', 'popbg102_02');">
	        <img src="/dhn/images/leaflets/pop/popbg102_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vi step2_item" id="t1_02_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_02', 'popbg102_03');">
	        <img src="/dhn/images/leaflets/pop/popbg102_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vi step2_item" id="t1_02_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_02', 'popbg102_04');">
	        <img src="/dhn/images/leaflets/pop/popbg102_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid_1 step2_item" id="t1_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_06', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_06.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', '');">
	        <img src="/dhn/images/leaflets/pop/popbg401_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', 'popbg401_01');">
	        <img src="/dhn/images/leaflets/pop/popbg401_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', 'popbg401_02');">
	        <img src="/dhn/images/leaflets/pop/popbg401_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', 'popbg401_03');">
	        <img src="/dhn/images/leaflets/pop/popbg401_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', 'popbg401_04');">
	        <img src="/dhn/images/leaflets/pop/popbg401_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', 'popbg401_05');">
	        <img src="/dhn/images/leaflets/pop/popbg401_06_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2vid step2_item" id="t4_01_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_01', 'popbg401_06');">
	        <img src="/dhn/images/leaflets/pop/popbg401_07_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid_1 step2_item" id="t1_08">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_08', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_08.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', '');">
	        <img src="/dhn/images/leaflets/pop/popbg402_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', 'popbg402_01');">
	        <img src="/dhn/images/leaflets/pop/popbg402_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', 'popbg402_02');">
	        <img src="/dhn/images/leaflets/pop/popbg402_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', 'popbg402_03');">
	        <img src="/dhn/images/leaflets/pop/popbg402_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', 'popbg402_04');">
	        <img src="/dhn/images/leaflets/pop/popbg402_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', 'popbg402_05');">
	        <img src="/dhn/images/leaflets/pop/popbg402_06_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vid step2_item" id="t4_02_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_02', 'popbg402_06');">
	        <img src="/dhn/images/leaflets/pop/popbg402_07_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vid step2_item" id="t4_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_03', '');">
	        <img src="/dhn/images/leaflets/pop/popbg403_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vid step2_item" id="t4_03_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_03', 'popbg403_01');">
	        <img src="/dhn/images/leaflets/pop/popbg403_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vid step2_item" id="t4_03_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_03', 'popbg403_02');">
	        <img src="/dhn/images/leaflets/pop/popbg403_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vid step2_item" id="t4_03_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_03', 'popbg403_03');">
	        <img src="/dhn/images/leaflets/pop/popbg403_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vid step2_item" id="t4_03_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_03', 'popbg403_04');">
	        <img src="/dhn/images/leaflets/pop/popbg403_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vid step2_item" id="t4_03_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_03', 'popbg403_05');">
	        <img src="/dhn/images/leaflets/pop/popbg403_06_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="4vid step2_item" id="t4_03_06">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_03', 'popbg403_06');">
            <img src="/dhn/images/leaflets/pop/popbg403_07_p.png" alt="">
          </dd>
        </dl>
        <dl class="4vid step2_item" id="t4_03_07">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_03', 'popbg403_07');">
            <img src="/dhn/images/leaflets/pop/popbg403_08_p.png" alt="">
          </dd>
        </dl>
        <dl class="4vid step2_item" id="t4_03_08">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_03', 'popbg403_08');">
            <img src="/dhn/images/leaflets/pop/popbg403_09_p.png" alt="">
          </dd>
        </dl>
		<dl class="4hid step2_item" id="t4_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', '');">
	        <img src="/dhn/images/leaflets/pop/popbg404_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hid step2_item" id="t4_04_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', 'popbg404_01');">
	        <img src="/dhn/images/leaflets/pop/popbg404_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hid step2_item" id="t4_04_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', 'popbg404_02');">
	        <img src="/dhn/images/leaflets/pop/popbg404_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hid step2_item" id="t4_04_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', 'popbg404_03');">
	        <img src="/dhn/images/leaflets/pop/popbg404_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hid step2_item" id="t4_04_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', 'popbg404_04');">
	        <img src="/dhn/images/leaflets/pop/popbg404_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hid step2_item" id="t4_04_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', 'popbg404_05');">
	        <img src="/dhn/images/leaflets/pop/popbg404_06_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hid step2_item" id="t4_04_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_04', 'popbg404_06');">
	        <img src="/dhn/images/leaflets/pop/popbg404_07_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="9hid step2_item" id="t4_09_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_09', 'popbg409_01');">
	        <img src="/dhn/images/leaflets/pop/popbg409_01_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="9hid step2_item" id="t4_09_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_09', 'popbg101_01');">
	        <img src="/dhn/images/leaflets/pop/popbg409_02_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="9hid step2_item" id="t4_09_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_09', 'popbg101_02');">
	        <img src="/dhn/images/leaflets/pop/popbg409_03_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="9hid step2_item" id="t4_09_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_09', 'popbg101_03');">
	        <img src="/dhn/images/leaflets/pop/popbg409_04_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="9hid step2_item" id="t4_09_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_09', 'popbg101_04');">
	        <img src="/dhn/images/leaflets/pop/popbg409_05_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="9hid step2_item" id="t4_09_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('4_09', 'popbg101_05');">
	        <img src="/dhn/images/leaflets/pop/popbg409_06_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1vt step2_item" id="t1_09">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_09', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_09.png" alt="">
	      </dd>
	    </dl>
		<dl class="1vt step2_item" id="t1_09_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_09', 'popbg109_01');">
	        <img src="/dhn/images/leaflets/pop/popbg109_01_p.png" alt="">
	      </dd>
	    </dl>
		<dl class="1vt step2_item" id="t1_09_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_09', 'popbg109_02');">
	        <img src="/dhn/images/leaflets/pop/popbg109_02_p.png" alt="">
	      </dd>
	    </dl>
		<dl class="1vt step2_item" id="t1_09_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_09', 'popbg109_03');">
	        <img src="/dhn/images/leaflets/pop/popbg109_03_p.png" alt="">
	      </dd>
	    </dl>
		<dl class="1vt step2_item" id="t1_09_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_09', 'popbg109_04');">
	        <img src="/dhn/images/leaflets/pop/popbg109_04_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1vt_2 step2_item" id="t1_10">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('1_10', '');">
            <img src="/dhn/images/leaflets/pop/popbg110_01_p.png" alt="">
          </dd>
        </dl>
        <dl class="1vt_2 step2_item" id="t1_10_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('1_10', 'popbg110_01');">
            <img src="/dhn/images/leaflets/pop/popbg110_02_p.png" alt="">
          </dd>
        </dl>
        <dl class="1vt_2 step2_item" id="t1_10_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('1_10', 'popbg110_02');">
            <img src="/dhn/images/leaflets/pop/popbg110_03_p.png" alt="">
          </dd>
        </dl>
        <dl class="1vt_3 step2_item" id="t1_11_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('1_11', '');">
            <img src="/dhn/images/leaflets/pop/popbg111_01_p.png" alt="">
          </dd>
        </dl>
        <dl class="1vt_3 step2_item" id="t1_11_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('1_11', 'popbg111_02');">
            <img src="/dhn/images/leaflets/pop/popbg111_02_p.png" alt="">
          </dd>
        </dl>
        <dl class="1vt_3 step2_item" id="t1_11_03">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('1_11', 'popbg111_03');">
            <img src="/dhn/images/leaflets/pop/popbg111_03_p.png" alt="">
          </dd>
        </dl>
        <dl class="2vtd_1 step2_item" id="t4_05">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_05', '');">
            <img src="/dhn/images/leaflets/pop/popbg405_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t4_05_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_05', 'popbg405_01');">
            <img src="/dhn/images/leaflets/pop/popbg405_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t4_05_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_05', 'popbg405_02');">
            <img src="/dhn/images/leaflets/pop/popbg405_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t3_08">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_08', '');">
            <img src="/dhn/images/leaflets/pop/popbg308_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t3_08_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_08', 'popbg308_01');">
            <img src="/dhn/images/leaflets/pop/popbg308_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t3_08_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_08', 'popbg308_02');">
            <img src="/dhn/images/leaflets/pop/popbg308_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t3_09">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_09', '');">
            <img src="/dhn/images/leaflets/pop/popbg309_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t3_09_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_09', 'popbg309_01');">
            <img src="/dhn/images/leaflets/pop/popbg309_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_1 step2_item" id="t3_09_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_09', 'popbg309_02');">
            <img src="/dhn/images/leaflets/pop/popbg309_02_p.png" alt="">
          </dd>
        </dl>
        <dl class="2vtd_2 step2_item" id="t4_06">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_06', '');">
            <img src="/dhn/images/leaflets/pop/popbg406_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t4_06_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_06', 'popbg406_01');">
            <img src="/dhn/images/leaflets/pop/popbg406_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t4_06_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_06', 'popbg406_02');">
            <img src="/dhn/images/leaflets/pop/popbg406_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t3_10">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_10', '');">
            <img src="/dhn/images/leaflets/pop/popbg310_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t3_10_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_10', 'popbg310_01');">
            <img src="/dhn/images/leaflets/pop/popbg310_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t3_10_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_10', 'popbg310_02');">
            <img src="/dhn/images/leaflets/pop/popbg310_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t3_11">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_11', '');">
            <img src="/dhn/images/leaflets/pop/popbg311_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t3_11_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_11', 'popbg311_01');">
            <img src="/dhn/images/leaflets/pop/popbg311_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_2 step2_item" id="t3_11_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_11', 'popbg311_02');">
            <img src="/dhn/images/leaflets/pop/popbg311_02_p.png" alt="">
          </dd>
        </dl>
        <dl class="2vtd_3 step2_item" id="t4_07">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_07', '');">
            <img src="/dhn/images/leaflets/pop/popbg407_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t4_07_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_07', 'popbg407_01');">
            <img src="/dhn/images/leaflets/pop/popbg407_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t4_07_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_07', 'popbg407_02');">
            <img src="/dhn/images/leaflets/pop/popbg407_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t3_12">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_12', '');">
            <img src="/dhn/images/leaflets/pop/popbg312_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t3_12_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_12', 'popbg312_01');">
            <img src="/dhn/images/leaflets/pop/popbg312_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t3_12_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_12', 'popbg312_02');">
            <img src="/dhn/images/leaflets/pop/popbg312_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t3_13">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_13', '');">
            <img src="/dhn/images/leaflets/pop/popbg313_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t3_13_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_13', 'popbg313_01');">
            <img src="/dhn/images/leaflets/pop/popbg313_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_3 step2_item" id="t3_13_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_13', 'popbg313_02');">
            <img src="/dhn/images/leaflets/pop/popbg313_02_p.png" alt="">
          </dd>
        </dl>
        <dl class="2vtd_4 step2_item" id="t4_08">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_08', '');">
            <img src="/dhn/images/leaflets/pop/popbg408_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t4_08_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_08', 'popbg408_01');">
            <img src="/dhn/images/leaflets/pop/popbg408_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t4_08_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('4_08', 'popbg408_02');">
            <img src="/dhn/images/leaflets/pop/popbg408_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t3_14">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_14', '');">
            <img src="/dhn/images/leaflets/pop/popbg314_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t3_14_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_14', 'popbg314_01');">
            <img src="/dhn/images/leaflets/pop/popbg314_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t3_14_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_14', 'popbg314_02');">
            <img src="/dhn/images/leaflets/pop/popbg314_02_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t3_15">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_15', '');">
            <img src="/dhn/images/leaflets/pop/popbg315_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t3_15_01">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_15', 'popbg315_01');">
            <img src="/dhn/images/leaflets/pop/popbg315_01_p.png" alt="">
          </dd>
        </dl>
				<dl class="2vtd_4 step2_item" id="t3_15_02">
          <dt onclick="fav_on_off(this);">
            <span class="material-icons">folder_special</span> 즐겨찾기 추가
          </dt>
          <dd onclick="print_type('3_15', 'popbg315_02');">
            <img src="/dhn/images/leaflets/pop/popbg315_02_p.png" alt="">
          </dd>
        </dl>
		<dl class="3vi step2_item" id="t1_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_03', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_03.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vi step2_item" id="t1_03_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_03', 'popbg103_01');">
	        <img src="/dhn/images/leaflets/pop/popbg103_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vi step2_item" id="t1_03_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_03', 'popbg103_02');">
	        <img src="/dhn/images/leaflets/pop/popbg103_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vi step2_item" id="t1_03_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_03', 'popbg103_03');">
	        <img src="/dhn/images/leaflets/pop/popbg103_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vi step2_item" id="t1_03_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_03', 'popbg103_04');">
	        <img src="/dhn/images/leaflets/pop/popbg103_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3vi step2_item" id="t1_03_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_03', 'popbg103_05');">
	        <img src="/dhn/images/leaflets/pop/popbg103_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi_1 step2_item" id="t1_07">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_07', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_07.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi step2_item" id="t1_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_04', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_04.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi step2_item" id="t1_04_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_04', 'popbg104_01');">
	        <img src="/dhn/images/leaflets/pop/popbg104_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi step2_item" id="t1_04_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_04', 'popbg104_02');">
	        <img src="/dhn/images/leaflets/pop/popbg104_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi step2_item" id="t1_04_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_04', 'popbg104_03');">
	        <img src="/dhn/images/leaflets/pop/popbg104_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi step2_item" id="t1_04_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_04', 'popbg104_04');">
	        <img src="/dhn/images/leaflets/pop/popbg104_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4vi step2_item" id="t1_04_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_04', 'popbg104_05');">
	        <img src="/dhn/images/leaflets/pop/popbg104_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="5vi step2_item" id="t1_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_05', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type1_05.png" alt="">
	      </dd>
	    </dl>
			<dl class="5vi step2_item" id="t1_05_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_05', 'popbg105_01');">
	        <img src="/dhn/images/leaflets/pop/popbg105_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="5vi step2_item" id="t1_05_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_05', 'popbg105_02');">
	        <img src="/dhn/images/leaflets/pop/popbg105_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="5vi step2_item" id="t1_05_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_05', 'popbg105_03');">
	        <img src="/dhn/images/leaflets/pop/popbg105_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="5vi step2_item" id="t1_05_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_05', 'popbg105_04');">
	        <img src="/dhn/images/leaflets/pop/popbg105_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="5vi step2_item" id="t1_05_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('1_05', 'popbg105_05');">
	        <img src="/dhn/images/leaflets/pop/popbg105_05_p.png" alt="">
	      </dd>
	    </dl>
			<!--<dl  class="1hi step2_item" onclick="print_type('2_05', '');">
	      <dt onclick="fav_on_off(this);">
	        가로  -  상품개수 : 1a
	      </dt>
	      <dd>
	        <img src="/dhn/images/leaflets/pop/pop_type2_05.png" alt="">
	      </dd>
	    </dl>-->
		<dl class="1hi step2_item" id="t2_01_09">
	      <dt onclick="fav_on_off(this);" class="favortype_on">
	       <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_09');">
	        <img src="/dhn/images/leaflets/pop/popbg201_09_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type2_01.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_01');">
	        <img src="/dhn/images/leaflets/pop/popbg201_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_02');">
	        <img src="/dhn/images/leaflets/pop/popbg201_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_03');">
	        <img src="/dhn/images/leaflets/pop/popbg201_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_04');">
	        <img src="/dhn/images/leaflets/pop/popbg201_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_05');">
	        <img src="/dhn/images/leaflets/pop/popbg201_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_06">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_06');">
	        <img src="/dhn/images/leaflets/pop/popbg201_06_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_07">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_07');">
	        <img src="/dhn/images/leaflets/pop/popbg201_07_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="1hi step2_item" id="t2_01_08">
	      <dt onclick="fav_on_off(this);">
	      <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_08');">
	        <img src="/dhn/images/leaflets/pop/popbg201_08_p.png" alt="">
	      </dd>
	    </dl>
        <dl class="1hi step2_item" id="t2_01_10">
	      <dt onclick="fav_on_off(this);">
	       <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_01', 'popbg201_10');">
	        <img src="/dhn/images/leaflets/pop/popbg201_10_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2hi step2_item" id="t2_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_02', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type2_02.jpg" alt="">
	      </dd>
	    </dl>
			<dl class="2hi step2_item" id="t2_02_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_02', 'popbg202_01');">
	        <img src="/dhn/images/leaflets/pop/popbg202_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2hi step2_item" id="t2_02_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_02', 'popbg202_02');">
	        <img src="/dhn/images/leaflets/pop/popbg202_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="2hi step2_item" id="t2_02_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_02', 'popbg202_03');">
	        <img src="/dhn/images/leaflets/pop/popbg202_03_p.png" alt="">
	      </dd>
	    </dl>
			<!-- <dl class="2hi step2_item" onclick="print_type('2_02', 'popbg202_04');">
	      <dt onclick="fav_on_off(this);">
	        가로  -  상품개수 : 2
	      </dt>
	      <dd>
	        <img src="/dhn/images/leaflets/pop/popbg202_04_p.png" alt="">
	      </dd>
	    </dl> -->
			<dl class="2hi step2_item" id="t2_02_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_02', 'popbg202_05');">
	        <img src="/dhn/images/leaflets/pop/popbg202_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3hi step2_item" id="t2_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_03', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type2_03.png" alt="">
	      </dd>
	    </dl>
			<dl class="3hi step2_item" id="t2_03_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_03', 'popbg203_01');">
	        <img src="/dhn/images/leaflets/pop/popbg203_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3hi step2_item" id="t2_03_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_03', 'popbg203_02');">
	        <img src="/dhn/images/leaflets/pop/popbg203_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3hi step2_item" id="t2_03_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_03', 'popbg203_03');">
	        <img src="/dhn/images/leaflets/pop/popbg203_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3hi step2_item" id="t2_03_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_03', 'popbg203_04');">
	        <img src="/dhn/images/leaflets/pop/popbg203_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="3hi step2_item" id="t2_03_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_03', 'popbg203_05');">
	        <img src="/dhn/images/leaflets/pop/popbg203_05_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hi step2_item" id="t2_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_04', '');">
	        <img src="/dhn/images/leaflets/pop/pop_type2_04.jpg" alt="">
	      </dd>
	    </dl>
			<dl class="4hi step2_item" id="t2_04_01">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_04', 'popbg204_01');">
	        <img src="/dhn/images/leaflets/pop/popbg204_01_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hi step2_item" id="t2_04_02">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_04', 'popbg204_02');">
	        <img src="/dhn/images/leaflets/pop/popbg204_02_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hi step2_item" id="t2_04_03">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_04', 'popbg204_03');">
	        <img src="/dhn/images/leaflets/pop/popbg204_03_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hi step2_item" id="t2_04_04">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_04', 'popbg204_04');">
	        <img src="/dhn/images/leaflets/pop/popbg204_04_p.png" alt="">
	      </dd>
	    </dl>
			<dl class="4hi step2_item" id="t2_04_05">
	      <dt onclick="fav_on_off(this);">
	        <span class="material-icons">folder_special</span> 즐겨찾기 추가
	      </dt>
	      <dd onclick="print_type('2_04', 'popbg204_05');">
	        <img src="/dhn/images/leaflets/pop/popbg204_05_p.png" alt="">
	      </dd>
	    </dl>
	    <button class="btn_popback" onclick="window.history.back();"><i class="xi-hamburger-back"></i> 이전으로</button>
	  </div>
  </div>




  <!-- <div class="pop_type2">
    <ul>
      <li>
        <a href="/spop/printer/type/3_01">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_01.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형1
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/3_02">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_02.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형2
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/3_03">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type3_03.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  텍스트형3
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/3_04">
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
        <a href="/spop/printer/type/1_01">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_01.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 1
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/1_02">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_02.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 2a
        </p>
        </a>
      </li>
			<li>
        <a href="/spop/printer/type/1_06">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_06.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 2b
        </p>
        </a>
      </li>
			<li>
        <a href="/spop/printer/type/1_08">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_08.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 3a
        </p>
        </a>
      </li>
			<li>
        <a href="/spop/printer/type/1_09">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_09.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  텍스트형
        </p>
        </a>
      </li>
      <li style="clear:left;">
        <a href="/spop/printer/type/1_03">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_03.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 3b
        </p>
        </a>
      </li>
			<li>
        <a href="/spop/printer/type/1_07">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_07.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 4a
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/1_04">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type1_04.jpg" alt="" />
        </p>
        <p class="pop_text">
          세로  -  상품개수 : 4b
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/1_05">
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
        <a href="/spop/printer/type/2_05">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_05.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 1a
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/2_01">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_01.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 1b
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/2_02">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_02.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 2
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/2_03">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_03.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 3
        </p>
        </a>
      </li>
      <li>
        <a href="/spop/printer/type/2_04">
        <p class="pop_img">
          <img src="/dhn/images/leaflets/pop/pop_type2_04.jpg" alt="" />
        </p>
        <p class="pop_text">
          가로  -  상품개수 : 4
        </p>
        </a>
      </li>
    </ul>
  </div> -->
	<div class="pop_tit_btn" style="display:none;">
		  <p>POP 행사타이틀 입력 후 저장 클릭!</p>
      <button type="button" class="pop_btn_save" onclick="title_save()"><span class="material-icons">check</span> 저장</button>
      <button type="button" class="pop_btn_cancel" onclick="title_cancel()"><span class="material-icons">close</span> 취소</button>
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
			url: "/spop/printer/search_library",
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
					var ppd_style = value.ppd_style; //css
					var ppd_title = value.ppd_title; //업체명
					var ppd_imgpath = value.ppd_imgpath; //본문 이미지경로
					html += "<li>";
					html += "  <p class=\"tem_img\"><img src=\""+ ppd_imgpath +"\" alt=\"\"/></p>";
					html += "  <p class=\"tem_btn\">";
					html += "    <button class=\"btn_insert\" onClick=\"data_mod('"+ ppd_id +"', '"+ ppd_type +"', '"+ ppd_style +"');\">수정</button>";
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
	function data_mod(data_id, ppd_type, ppd_style){
		location.href = "/spop/printer/type/"+ ppd_type +"?ppd_id="+data_id+"&style="+ppd_style;
	}

	//스마트POP 라이브러리 데이타 삭제
	function data_del(data_id){
		if(!confirm("삭제 하시겠습니까?")){
			return;
		}
		$.ajax({
			url: "/spop/printer/data_del",
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

	function logo_save(){
		var yn = $('input[name=use_yn]:checked').val();
		if(yn=='Y'){
			if($('#com_name').val()==''){
				alert('업체명을 입력하세요');
				$('#com_name').focus();
				return;
			}
		}
		var com_name = $('#com_name').val();
		var imgpath = $('#imgpath').val();
		var hdn_use_img_yn = $('#hdn_use_img_yn').val();

		$.ajax({
			url: "/spop/printer/logo_save",
			type: "POST",
			async: false,
			data: {"com_name" : com_name, "imgpath" : imgpath, "use_yn" : yn, "hdn_use_img_yn" : hdn_use_img_yn, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(json.code == "0") { //성공
					showSnackbar(json.msg, 1500);

					setTimeout(function() {
						$('#myLogoModal').hide();
						// printer_library_data(1); //스마트POP 라이브러리 데이타 조회
					}, 1000); //1초 지연
				} else { //오류
					showSnackbar('오류가 발생하였습니다', 1500);
					return;
				}
			}
		});
	}

	// //스마트POP 라이브러리 데이타 삭제
	// function title_save(){
    //
	// 	$.ajax({
	// 		url: "/spop/printer/title_save",
	// 		type: "POST",
	// 		data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
	// 			   "type3_01":$("#type3_01").val(),
	// 			   "type3_02":$("#type3_02").val(),
	// 			   "type3_03":$("#type3_03").val(),
	// 			   "type3_04":$("#type3_04").val(),
	// 			   "type2_05":$("#type2_05").val(),
	// 			   "type2_01":$("#type2_01").val(),
	// 			   "type2_02":$("#type2_02").val(),
	// 			   "type2_03":$("#type2_03").val(),
	// 			   "type2_04":$("#type2_04").val(),
	// 			   "type1_09":$("#type1_09").val(),
	// 			   "type1_01":$("#type1_01").val(),
	// 			   "type1_02":$("#type1_02").val(),
	// 			   "type1_06":$("#type1_06").val(),
	// 			   "type1_08":$("#type1_08").val(),
	// 			   "type1_03":$("#type1_03").val(),
	// 			   "type1_07":$("#type1_07").val(),
	// 			   "type1_04":$("#type1_04").val(),
	// 			   "type1_05":$("#type1_05").val()
	// 			},
	// 		success: function (json) {
	// 			if(json.code == "0") { //성공
	// 				showSnackbar("저장 되었습니다.", 1500);
	// 				setTimeout(function() {
	// 					printer_library_data(1); //스마트POP 라이브러리 데이타 조회
	// 				}, 1000); //1초 지연
	// 			} else { //오류
	// 				showSnackbar("오류가 발생하였습니다.", 1500);
	// 				return;
	// 			}
	// 		}
	// 	});
	// }

    //스마트POP 타이틀 저장기능 수정 2021-10-13 윤재박
	function title_save(){
        // 전체 갯수
        // var num = $("input[name=title_content]").length;
        var type_arr =[];
        var title_arr =[];

        var chk=0;
        $("input[name=title_content]").each(function(i){
            if($(this).val()){
                var tcont = $(this).val();
                tcont.replace(/\s/gi, "");
                if(tcont){
                    var tcont = $(this).val();
                    var id = $(this).attr("id");
                    var type = id.substr(2);
                    type_arr.push(type);
                    title_arr.push(tcont);
                    chk++;
                }
            }
        });
        // not null 갯수
        if(chk > 0){
            var type_in = '';
            var title_in = '';

            for(i=0;i<chk;i++){
                if(i==0){
                  type_in = type_arr[i];
                  title_in = title_arr[i];
                }else{
                  type_in += "§§"+ type_arr[i];
                  title_in += "§§"+ title_arr[i];
                }
            }

            $.ajax({
    			url: "/spop/printer/title_save",
    			type: "POST",
                async : false,
    			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
    				   "type":type_in,
    				   "title":title_in
    				},
    			success: function (json) {
    				if(json.code == "0") { //성공
    					showSnackbar("저장 되었습니다.", 1500);
    					setTimeout(function() {
    						// printer_library_data(1); //스마트POP 라이브러리 데이타 조회
                            $('.pop_tit_btn').hide();
                            $('.showtitle').hide();
                            $('.hidetitle').show();
    					}, 200); //0.2초 지연
    				} else { //오류
    					showSnackbar("오류가 발생하였습니다.", 1500);
    					return;
    				}
    			}
    		});

        }



	}
    //스마트POP 타이틀 관리 취소 버튼 2021-10-13 윤재박
	function title_cancel(){
        $('.pop_tit_btn').hide();
        $('.showtitle').hide();
        $('.hidetitle').show();
	}

	function print_type(ptype, style){
		var spop = '<?=$spop?>';
		var plusclass1 = '';
		var plusclass2 = '';
		if(style!=''){
			plusclass1 = "&style="+style;
			plusclass2 = "?style="+style;
		}
		if(spop!=''){
			location.href = '/spop/printer/spop?spop='+ spop + '&type=' + ptype + plusclass1;
		}else{
			location.href = '/spop/printer/type/' + ptype + plusclass2;
		}

	}

	function fav_on_off(id){
				var favid = $(id).parent().prop('id');
				$.ajax({
					url: "/spop/printer/fav_on_off",
					type: "POST",
					async: false,
					data: {"favid" : favid, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						if(json.code == 0) { //성공
                            $('#'+favid).children('dt').addClass('favortype_on');
							showSnackbar(json.msg, 1500);
                                console.log($('#'+favid).attr('id'));
						} else { //오류
                            $('#'+favid).children('dt').removeClass('favortype_on');
							showSnackbar(json.msg, 1500);
						}
					}
				});

	}

	function favlink(){
		if(iurl!=''){
			var params = new URLSearchParams(iurl);
			var sch_keyword = params.get('page');
			params.set('page', '3');
			var url = params.toString();
			location.href='/spop/printer?' + url;
		}else{
			location.href='/spop/printer?page=3';
		}
	}

	function wholelink(){
		if(iurl!=''){
			var params = new URLSearchParams(iurl);
			var sch_keyword = params.get('page');
			params.set('page', '1');
			var url = params.toString();
			location.href='/spop/printer?' + url;
		}else{
			location.href='/spop/printer';
		}
	}

</script>
<div id="snackbar"></div><?//저장메시지 모달창 div?>
<? } //if($ie_yn == "Y"){ ?>
