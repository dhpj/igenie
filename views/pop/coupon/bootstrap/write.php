<?
	$sample_img = "/dhn/images/leaflets/sample_img.jpg"; //샘플이미지 경로
?>
<div class="wrap_leaflets">
  <div class="s_tit">
    스마트쿠폰 만들기
    <div class="btn_list">
      <a href="/pop/coupon<?=($add !="") ? "?add=". $add : ""?>">스마트쿠폰 목록으로</a>
    </div>
  </div>
  <input type="hidden" id="data_id" value="<?=$data->pcd_id?>">
  <input type="hidden" id="pcd_type" value="<?=($data->pcd_type !="") ? $data->pcd_type : "1"?>">
  <input type="hidden" id="pcd_imgpath" value="<?=$data->pcd_imgpath?>" style="width:600px;">
  <div class="write_leaflets">
    <div class="wl_lbox">
      <div class="tit_box">
        <ul>
          <li id="li_type1"<? if($data->pcd_type != "2"){ ?> class="tm_on"<? } ?>><a href="javascript:coupon_type(1);"><span>TYPE 1.</span> 무료증정 쿠폰</a></li>
          <li id="li_type2"<? if($data->pcd_type == "2"){ ?> class="tm_on"<? } ?>><a href="javascript:coupon_type(2);"><span>TYPE 2.</span> 가격할인 쿠폰</a></li>
        </ul>
      </div>
      <div class="wl_main_goods" id="coupon_type1" style="display:<? if($data->pcd_type == "2"){ ?>none<? } ?>"><?//무료증정 쿠폰 입력란?>
        <dl class="dl_step1">
          <dt>
            <div class="templet_img_in" onclick="showImg('')">
              <div id="div_preview">
                  <img id="img_preview" src="<?=$data->pcd_imgpath?>">
              </div>
            </div>
          </dt>
            <dd>
              <ul>
                <li>
                  <span class="tit">행사기간</span>
				  <input type="text" id="pcd_date_1" value="<?=$data->pcd_date?>" onkeyup="chgData('pcd_date_1')" placeholder="2020.11.11. 단하루">
                  <div class="counter">
                    <span class="text">글자크기</span>
                    <span class="counter_minus button" onClick="size_change('pcd_date_1', 'minus');">-</span>
                    <span class="counter_plus button" onClick="size_change('pcd_date_1', 'plus');">+</span>
                  </div>
                </li>
                <li>
                  <span class="tit">쿠폰제목</span>
				  <input type="text" id="pcd_title_1" value="<?=$data->pcd_title?>" onkeyup="chgData('pcd_title_1')" placeholder="카톡친구 무료증정">
                  <div class="counter">
                    <span class="text">글자크기</span>
                    <span class="counter_minus" onClick="size_change('pcd_title_1', 'minus');">-</span>
                    <span class="counter_plus" onClick="size_change('pcd_title_1', 'plus');">+</span>
                  </div>
                </li>
                <li>
                  <span class="tit">상품정보</span>
				  <input type="text" id="pcd_option1_1" value="<?=$data->pcd_option1?>" onkeyup="chgData('pcd_option1_1')" placeholder="크리넥스 안심 키친타올">
                  <div class="counter">
                    <span class="text">글자크기</span>
                    <span class="counter_minus" onClick="size_change('pcd_option1_1', 'minus');">-</span>
                    <span class="counter_plus" onClick="size_change('pcd_option1_1', 'plus');">+</span>
                  </div>
                </li>
                <li>
                  <span class="tit">쿠폰옵션</span>
				  <input type="text" id="pcd_option2_1" value="<?=$data->pcd_option2?>" onkeyup="chgData('pcd_option2_1')" placeholder="선착순 100개 한정수량">
                  <div class="counter">
                    <span class="text">글자크기</span>
                    <span class="counter_minus" onClick="size_change('pcd_option2_1', 'minus');">-</span>
                    <span class="counter_plus" onClick="size_change('pcd_option2_1', 'plus');">+</span>
                  </div>
                </li>
              </ul>
            </dd>
         </dl>
      </div>
      <div class="wl_main_goods" id="coupon_type2" style="display:<? if($data->pcd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 입력란?>
        <ul>
            <li>
              <span class="tit">행사기간</span>
			  <input type="text" id="pcd_date_2" value="<?=$data->pcd_date?>" onkeyup="chgData('pcd_date_2')" placeholder="2020.11.11. 단하루">
              <div class="counter">
                  <span class="text">글자크기</span>
                  <span class="counter_minus" onClick="size_change('pcd_date_2', 'minus');">-</span>
                  <span class="counter_plus" onClick="size_change('pcd_date_2', 'plus');">+</span>
              </div>
            </li>
            <li>
              <span class="tit">쿠폰제목</span>
			  <input type="text" id="pcd_title_2" value="<?=$data->pcd_title?>" onkeyup="chgData('pcd_title_2')" placeholder=" 카톡친구 무료증정">
              <div class="counter">
                  <span class="text">글자크기</span>
                  <span class="counter_minus" onClick="size_change('pcd_title_2', 'minus');">-</span>
                  <span class="counter_plus" onClick="size_change('pcd_title_2', 'plus');">+</span>
              </div>
            </li>
            <li>
              <span class="tit">쿠폰옵션1</span>
			  <input type="text" id="pcd_option1_2" value="<?=$data->pcd_option1?>" onkeyup="chgData('pcd_option1_2')" placeholder="3만원이상 구매시(1인1매)">
              <div class="counter">
                  <span class="text">글자크기</span>
                  <span class="counter_minus" onClick="size_change('pcd_option1_2', 'minus');">-</span>
                  <span class="counter_plus" onClick="size_change('pcd_option1_2', 'plus');">+</span>
              </div>
            </li>
            <li>
              <span class="tit">할인금액</span>
			  <input type="text" id="pcd_price_2" value="<?=$data->pcd_price?>" onkeyup="chgData('pcd_price_2')" placeholder="3,000원">
              <div class="counter">
                  <span class="text">글자크기</span>
                  <span class="counter_minus" onClick="size_change('pcd_price_2', 'minus');">-</span>
                  <span class="counter_plus" onClick="size_change('pcd_price_2', 'plus');">+</span>
              </div>
            </li>
            <li>
              <span class="tit">쿠폰옵션2</span>
			  <input type="text" id="pcd_option2_2" value="<?=$data->pcd_option2?>" onkeyup="chgData('pcd_option2_2')" placeholder="선착순 100명 현장할인" >
              <div class="counter">
                  <span class="text">글자크기</span>
                  <span class="counter_minus" onClick="size_change('pcd_option2_2', 'minus');">-</span>
                  <span class="counter_plus" onClick="size_change('pcd_option2_2', 'plus');">+</span>
              </div>
            </li>
          </ul>
      </div>
    </div><!--//wl_lbox-->
    <div class="wl_rbox">
      <p class="wl_rbox_tit">
        스마트쿠폰 미리보기
      </p>
      <div class="wl_r_preview" id="pre_coupon_type1" style="display:<? if($data->pcd_type == "2"){ ?>none<? } ?>"><?//무료증정 쿠폰 미리보기?>
        <div class="pre_box_wrap1">
          <!--템플릿 이미지가 들어갈 공간-->
          <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg1.jpg') no-repeat top center;background-color:#47b5e5"></div>
          <!--//템플릿 이미지가 들어갈 공간-->
          <div class="pre_box1">
            <p id="pre_pcd_date_1" class="pre_date speech"<? if($data->pcd_date_size != "" and $data->pcd_date_size != "0"){ ?> style="font-size:<?=$data->pcd_date_size?>px;"<? } ?>><?=($data->pcd_date == "") ? "2020.11.11. 단하루" : $data->pcd_date?></p>
            <p id="pre_pcd_title_1" class="pre_tit"<? if($data->pcd_title_size != "" and $data->pcd_title_size != "0"){ ?> style="font-size:<?=$data->pcd_title_size?>px;"<? } ?>><?=($data->pcd_title == "") ? "카톡친구 무료증정" : $data->pcd_title?></p>
          </div>
          <div class="pre_box2" id="pre_goods_list_step1">
            <div id="pre_div_preview" class="templet_img_in3" style="background-image : url('<?=($data->pcd_imgpath == "") ? $sample_img : $data->pcd_imgpath?>');">
              <img id="pre_img_preview" style="display:none;">
            </div>
            <p id="pre_pcd_option1_1" class="pre_goodsinfo"<? if($data->pcd_option1_size != "" and $data->pcd_option1_size != "0"){ ?> style="font-size:<?=$data->pcd_option1_size?>px;"<? } ?>><?=($data->pcd_option1 == "") ? "크리넥스 안심 키친타올" : $data->pcd_option1?></p>
            <p id="pre_pcd_option2_1" class="pre_couoption"<? if($data->pcd_option2_size != "" and $data->pcd_option2_size != "0"){ ?> style="font-size:<?=$data->pcd_option2_size?>px;"<? } ?>><?=($data->pcd_option2 == "") ? "선착순 100개 한정수량" : $data->pcd_option2?></p>
          </div><!--//pre_box2-->
        </div><!--//pre_box_wrap-->
      </div><!--//wl_r_preview-->
      <div class="wl_r_preview" id="pre_coupon_type2" style="display:<? if($data->pcd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 미리보기?>
        <div class="pre_box_wrap2">
          <!--템플릿 이미지가 들어갈 공간-->
          <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg2.jpg') no-repeat top center;background-color:#47b5e5"></div>
          <!--//템플릿 이미지가 들어갈 공간-->
          <div class="pre_box1">
            <p id="pre_pcd_date_2" class="pre_date"<? if($data->pcd_date_size != "" and $data->pcd_date_size != "0"){ ?> style="font-size:<?=$data->pcd_date_size?>px;"<? } ?>><?=($data->pcd_date == "") ? "2020.11.11. 단하루" : $data->pcd_date?></p>
            <p id="pre_pcd_title_2" class="pre_tit"<? if($data->pcd_title_size != "" and $data->pcd_title_size != "0"){ ?> style="font-size:<?=$data->pcd_title_size?>px;"<? } ?>><?=($data->pcd_title == "") ? "카톡친구 무료증정" : $data->pcd_title?></p>
          </div>
          <div class="pre_box2" id="pre_goods_list_step1">
            <p id="pre_pcd_option1_2" class="pre_couoption1"<? if($data->pcd_option1_size != "" and $data->pcd_option1_size != "0"){ ?> style="font-size:<?=$data->pcd_option1_size?>px;"<? } ?>><?=($data->pcd_option1 == "") ? "3만원이상 구매시(1인1매)" : $data->pcd_option1?></p>
            <p id="pre_pcd_price_2" class="pre_saleprice"<? if($data->pcd_price_size != "" and $data->pcd_price_size != "0"){ ?> style="font-size:<?=$data->pcd_price_size?>px;"<? } ?>><?=($data->pcd_price == "") ? "3,000원" : $data->pcd_price?></p>
            <p id="pre_pcd_option2_2" class="pre_couoption2"<? if($data->pcd_option2_size != "" and $data->pcd_option2_size != "0"){ ?> style="font-size:<?=$data->pcd_option2_size?>px;"<? } ?>><?=($data->pcd_option2 == "") ? "선착순 100명 현장할인" : $data->pcd_option2?></p>
          </div><!--//pre_box2-->
        </div><!--//pre_box_wrap-->
      </div><!--//wl_r_preview-->
    </div><!--//wl_rbox-->
    <div class="write_leaflets_btn">
      <button type="button" class="pop_btn_save" onClick="saved('');"><span class="material-icons">save</span> 저장하기</button>
      <button type="button" class="pop_btn_send" onClick="saved('lms');"><span class="material-icons">forward_to_inbox</span> 문자발송</button>
      <button type="button" class="pop_btn_send" onClick="saved('talk_adv');"><span class="material-icons">chat_bubble_outline</span> 알림톡발송</button>
      <button type="button" class="pop_btn_cancel" onclick="location.href='/pop/coupon'"><span class="material-icons">highlight_off</span> 취소하기</button>
    </div>
  </div><!--//write_leaflets-->
</div><!--//wrap_leaflets-->
<!-- 이미지 선택 Modal -->
<div id="dh_myModal2" class="dh_modal">
  <div class="modal-content2_1">
    <span id="dh_close2" class="dh_close">&times;</span>
    <div class="img_choice">
      <label for="img_file" class="img_mypick">내사진</label>
      <input type="file" title="이미지 파일" id="img_file" onChange="imgChange(this);" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
      <button onclick="showLibrary('img');" class="img_library" style="margin-left:10px;cursor:pointer;">이미지선택</button>
      <ul>
        <li>1. 내사진 :  <span>내 PC에 저장된 이미지</span>를 등록합니다.</li>
        <li>2. 이미지선택 : <span>지니 라이브러리에 있는 이미지</span>를 선택합니다.</li>
      </ul>
    </div>
  </div>
</div>
<!-- 라이브러리 Modal -->
<div id="dh_myModal3" class="dh_modal">
  <div class="modal-content">
    <span id="dh_close3" class="dh_close">&times;</span>
    <p class="modal-tit"><span id="id_modal_title">이미지</span> 라이브러리</p>
    <div class="search_input">
      <input type="search" placeholder="검색어를 입력하세요." id="id_searchLibrary">
      <button onclick="searchImgLibrary();">
        <i class="material-icons">search</i>
      </button>
    </div>
    <ul id="library_append_list" class="library_append_list"><?//라이브러리 리스트 영역?>
    </ul>
  </div>
</div>
<!-- 스샷 -->
<script src="/assets/js/FileSaver.min.js"></script>
<script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
<script src="/assets/js/canvas-toBlob.js"></script>
<script>
	//쿠폰 타입 선택
	function coupon_type(no){
		$("#pcd_type").val(no);
		if(no == 1){ //TYPE 1. 무료증정 쿠폰 입력란 오픈
			$('#li_type1').addClass('tm_on');
			$('#li_type2').removeClass('tm_on');
			area_over('coupon_type1', 'coupon_type2'); //무료증정 입력란
			area_over('pre_coupon_type1', 'pre_coupon_type2'); //무료증정 미리보기
		}else if(no == 2){ //TYPE 2. 가격할인 쿠폰 입력란 오픈
			$('#li_type2').addClass('tm_on');
			$('#li_type1').removeClass('tm_on');
			area_over('coupon_type2', 'coupon_type1'); //가격할인 입력란
			area_over('pre_coupon_type2', 'pre_coupon_type1'); //가격할인 미리보기
		}
	}

	//폰트 사이즈 변경
	function size_change(id, type){
		var classId = $("#pre_"+ id);
		var currentSize = classId.css("fontSize"); //폰트사이즈를 알아낸다.
		var num = parseFloat(currentSize, 10); //parseFloat()은 숫자가 아니면 숫자가 아니라는 뜻의 NaN을 반환한다.
		var unit = currentSize.slice(-2); //끝에서부터 두자리의 문자를 가져온다.
		//alert("classId : "+ classId +"\n"+ "currentSize : "+ currentSize +"\n"+ "num : "+ num +"\n"+ "unit : "+ unit);
		//alert("id : "+ id +"\n"+ "num : "+ num);
		if(type == "plus"){
			num *= 1.05;
			if((id == "pcd_date_1" || id == "pcd_date_2") && num > 35){ //행사기간 : 기본30-최대35/최소25
				num = 35;
			}else if((id == "pcd_title_1" || id == "pcd_title_2") && num > 45){ //쿠폰제목 : 기본40-최대45/최소35
				num = 45;
			}else if((id == "pcd_option1_1" || id == "pcd_option2_2") && num > 30){ //TYPE1 상품정보, TYPE2 쿠폰옵션2 : 기본25-최대30/최소20
				num = 30;
			}else if(id == "pcd_option2_1" && num > 25){ //TYPE1 쿠폰옵션 : 기본20-최대25/최소15
				num = 25;
			}else if(id == "pcd_option1_2" && num > 28){ //TYPE2 쿠폰옵션1 : 기본23-최대28/최소18
				num = 28;
			}else if(id == "pcd_price_2" && num > 80){ //TYPE2 할인금액 : 기본70-최대80/최소60
				num = 80;
			}
		} else if(type == "minus"){
			num /= 1.05;
			if((id == "pcd_date_1" || id == "pcd_date_2") && num < 25){ //행사기간 : 기본30-최대35/최소25
				num = 25;
			}else if((id == "pcd_title_1" || id == "pcd_title_2") && num < 35){ //쿠폰제목 : 기본40-최대45/최소35
				num = 35;
			}else if((id == "pcd_option1_1" || id == "pcd_option2_2") && num < 20){ //TYPE1 상품정보, TYPE2 쿠폰옵션2 : 기본25-최대30/최소20
				num = 20;
			}else if(id == "pcd_option2_1" && num < 15){ //TYPE1 쿠폰옵션 : 기본20-최대25/최소15
				num = 15;
			}else if(id == "pcd_option1_2" && num < 18){ //TYPE2 쿠폰옵션1 : 기본23-최대28/최소18
				num = 18;
			}else if(id == "pcd_price_2" && num < 60){ //TYPE2 할인금액 : 기본70-최대80/최소60
				num = 60;
			}
		}
		//alert("id : "+ id +"\n"+ "num : "+ num);
		classId.css("fontSize", num + unit);
	}

	//이미지 추가 모달창 열기
	var modal2 = document.getElementById("dh_myModal2");
	function showImg(imgid){
		//alert("imgid : "+ imgid); return;
		$("#library_imgid").val(imgid); //이미지경로 ID
		var span = document.getElementById("dh_close2");
		modal2.style.display = "block";
		span.onclick = function() {
			modal2.style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == modal2) {
				modal2.style.display = "none";
			}
		}
	}

	//이미지 선택 클릭시
	function imgChange(input){
		//alert("input.value : "+ input.value); return;
		if(input.value.length > 0) {
			//alert("input.value : "+ input.value); return;
  			var ext = input.value.split('.').pop().toLowerCase(); //파일 확장자
			//alert("ext : "+ ext); return;
  			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				alert("해당 파일은 이미지 파일이 아닙니다.\n(이미지 파일 : jpg, jpeg, png, gif)");
				return;
			}else{
				if (input.files && input.files[0]){
					var fileSize = input.files[0].size; //파일사이즈
					var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
					//alert("fileSize : "+ fileSize +", maxSize : "+ maxSize);
					if(maxSize < fileSize){
						jsFileRemove(input); //파일 초기화
						alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;
					modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
					var reader = new FileReader();
					reader.onload = function(e) {
						$("#img_preview").attr("src", e.target.result);
						$("#pre_div_preview").css({"background":"url(" + e.target.result + ")"}); //미리보기 이미지 배경 URL
					}
					reader.readAsDataURL(input.files[0]);

					var formData = new FormData();
					formData.append("imgfile", input.files[0]); //이미지
					formData.append("uppath", "<?=$upload_path?>"); //업로드경로
					formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
					$.ajax({
						url: "/pop/printer/imgfile_save",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function (json) {
							if(json.code == "0") { //성공
								jsFileRemove(input); //파일 초기화
								$("#img_preview").attr("src", json.imgpath); //이미지 URL
								$("#pre_div_preview").css({"background":"url(" + json.imgpath + ")"}); //미리보기 이미지 배경 URL
								$("#pcd_imgpath").val(json.imgpath); //사진 경로
							}else{ //실패
								jsFileRemove(input); //파일 초기화
								$("#img_preview").attr("src", ""); //이미지 URL
								$("#pre_div_preview").css({"background":"url(<?=$sample_img?>)"}); //미리보기 이미지 배경 URL
								$("#pcd_imgpath").val(""); //사진 경로
								alert(json.msg);
								return;
							}
						}
					});
				}
			}
		}
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

	//라이브러리 조회
	function searchLibrary(){
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		//alert("pageLibrary : "+ pageLibrary +", searchstr : "+ searchstr); return;
		//url: "/imglibrary/search_library",
		$.ajax({
			url: "/pop/screen/search_library",
			type: "POST",
			data: {"perpage" : "15", "page" : pageLibrary, "searchnm" : searchnm, "searchstr" : searchstr, "library_type" : library_type, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				pageLibrary = json.page;
				totalLibrary = json.total;
				//alert("pageLibrary : "+ pageLibrary +"\n"+ "totalLibrary : "+ totalLibrary +"\n"+ "json.html : "+ json.html); return;
				$("#library_append_list").append(json.html);
			}
		});
	}

	//라이브러리 모달창 열기
	var modal3 = document.getElementById("dh_myModal3");
	function showLibrary(type){
		$("#id_searchName").val("name"); //검색이름
		if(type == "img"){
			$("#id_searchName").show();
		}else{
			$("#id_searchName").hide();
		}
		var span = document.getElementById("dh_close3");
		modal3.style.display = "block";
		$("#library_type").val(type); //라이브러리 타입
		if(type == "goods"){
			$("#id_modal_title").html("상품");
		}else{
			$("#id_modal_title").html("이미지");
		}
		var goods_name = ""; //상품명
		var goods_option = ""; //옵션명
		//alert("goods_step_id : "+ goods_step_id +", goods_name : "+ goods_name +", goods_option : "+ goods_option); return;
		var searchstr = trim(goods_name +" "+ goods_option);
		//alert("searchstr : "+ searchstr); return;
		$("#id_searchLibrary").val(searchstr); //검색내용
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
		span.onclick = function() {
			removeImgLibrary(); //라이브러리 초기화
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == modal3) {
				removeImgLibrary(); //라이브러리 초기화
				modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
				modal3.style.display = "none"; //라이브러리 모달창 닫기
			}
		}
	}

	//이미지 라이브러리 선택 클릭시
	function set_img_library(imgpath){
		var id = ""; //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
			$("#img_preview").attr("src", imgpath); //이미지 URL
			$("#pre_div_preview").css({"background":"url(" + imgpath + ")"}); //미리보기 이미지 배경 URL
			$("#pcd_imgpath").val(imgpath); //사진 경로
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

	//데이타 변경시
	function chgData(id) {
		var data = $("#"+ id).val();
		if(data != "") data = data.trim(); //상품명
		$("#pre_"+ id).html(data); //미리보기 영역 표시값 변경
	}

	//저장하기
	function saved(flag){
		var data_id = $("#data_id").val(); //쿠폰번호
		var pcd_type = $("#pcd_type").val(); //쿠폰타입(1.무료증정, 2.가격할인)
		//alert("data_id : "+ data_id +"\n"+ "pcd_type : "+ pcd_type);
		var pcd_imgpath = ""; //이미지경로
		var pcd_date = $("#pcd_date_"+ pcd_type).val().trim(); //행사기간
		var pcd_title = $("#pcd_title_"+ pcd_type).val().trim(); //쿠폰제목
		var pcd_option1 = $("#pcd_option1_"+ pcd_type).val().trim(); //상품정보/쿠폰옵션1
		var pcd_option2 = $("#pcd_option2_"+ pcd_type).val().trim(); //쿠폰옵션/쿠폰옵션2
		var pcd_price = ""; //할인금액
		var pcd_date_size = jsFontSize("pre_pcd_date_"+ pcd_type); //행사기간 폰트크기
		var pcd_title_size = jsFontSize("pre_pcd_title_"+ pcd_type); //쿠폰제목 폰트크기
		var pcd_option1_size = jsFontSize("pre_pcd_option1_"+ pcd_type); //상품정보/쿠폰옵션1 폰트크기
		var pcd_option2_size = jsFontSize("pre_pcd_option2_"+ pcd_type); //쿠폰옵션/쿠폰옵션2 폰트크기
		var pcd_price_size = ""; //할인금액 폰트크기
		//alert("pcd_date : "+ pcd_date +"\n"+ "pcd_date_size : "+ pcd_date_size);
		if(pcd_type == "1"){ //무료증정
			pcd_imgpath = $("#pcd_imgpath").val().trim(); //이미지경로
			if(pcd_imgpath == ""){
				alert("쿠폰 이미지를 추가하세요.");
				showImg('');
				return false;
			}
		}
		if(pcd_date == ""){
			alert("행사기간을 입력하세요.");
			$("#pcd_date_"+ pcd_type).focus();
			return false;
		}
		if(pcd_title == ""){
			alert("쿠폰제목을 입력하세요.");
			$("#pcd_title_"+ pcd_type).focus();
			return false;
		}
		if(pcd_type == "1"){ //무료증정
			if(pcd_option1 == ""){
				alert("상품정보를 입력하세요.");
				$("#pcd_option1_"+ pcd_type).focus();
				return false;
			}
			if(pcd_option2 == ""){
				alert("쿠폰옵션을 입력하세요.");
				$("#pcd_option2_"+ pcd_type).focus();
				return false;
			}
		}else if(pcd_type == "2"){ //가격할인
			pcd_price = $("#pcd_price_"+ pcd_type).val().trim(); //할인금액
			pcd_price_size = jsFontSize("pre_pcd_price_"+ pcd_type); //쿠폰옵션/쿠폰옵션2 폰트크기
			if(pcd_option1 == ""){
				alert("쿠폰옵션1을 입력하세요.");
				$("#pcd_option1_"+ pcd_type).focus();
				return false;
			}
			if(pcd_price == ""){
				alert("할인금액을 입력하세요.");
				$("#pcd_price_"+ pcd_type).focus();
				return false;
			}
			if(pcd_option2 == ""){
				alert("쿠폰옵션2를 입력하세요.");
				$("#pcd_option2_"+ pcd_type).focus();
				return false;
			}
		}
		//alert("스크립트 OK"); return;

		var formData = new FormData();
		var target = $("#pre_coupon_type"+ pcd_type);
		if (target != null && target.length > 0) {
			var t = target[0];
			html2canvas(t).then(function(canvas) {
				var imgfile = canvas.toDataURL("image/png");
				formData.append("flag", flag); //구분
				formData.append("data_id", data_id); //일련번호
				formData.append("pcd_type", pcd_type); //쿠폰타입(1.무료증정, 2.가격할인)
				formData.append("pcd_date", pcd_date); //행사기간
				formData.append("pcd_title", pcd_title); //쿠폰제목
				formData.append("pcd_option1", pcd_option1); //상품정보/쿠폰옵션1
				formData.append("pcd_option2", pcd_option2); //쿠폰옵션/쿠폰옵션2
				formData.append("pcd_price", pcd_price); //할인금액
				formData.append("pcd_imgpath", pcd_imgpath); //이미지경로
				formData.append("pcd_date_size", pcd_date_size); //행사기간 폰트크기
				formData.append("pcd_title_size", pcd_title_size); //쿠폰제목 폰트크기
				formData.append("pcd_option1_size", pcd_option1_size); //상품정보/쿠폰옵션1 폰트크기
				formData.append("pcd_option2_size", pcd_option2_size); //쿠폰옵션/쿠폰옵션2 폰트크기
				formData.append("pcd_price_size", pcd_price_size); //할인금액 폰트크기
				formData.append("imgfile", imgfile); //HTML to Image
				formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				$.ajax({
					url: "/pop/coupon/coupon_save",
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function (json) {
						//alert("json.id : "+ json.id +", json.pcd_code : "+ json.pcd_code +", json.flag : "+ json.flag);
						if(json.code == "0") { //성공
							$("#data_id").val(json.id); //쿠폰번호
							if(json.flag == "lms"){ //문자메시지 발송
								location.href = "/dhnbiz/sender/send/lms?pcd_code="+ json.pcd_code +"&pcd_type="+ pcd_type; //문자메시지 발송 페이지
							}else if(json.flag == "talk_adv"){ //알림톡 발송
								location.href = "/dhnbiz/sender/send/talk_adv?pcd_code="+ json.pcd_code +"&pcd_type="+ pcd_type; //알림톡 발송 페이지
							}else{
								showSnackbar("저장 되었습니다.", 1500);
								setTimeout(function() {
									list(); //목록 페이지
								}, 1000); //1초 지연
							}
						}else{ //실패
							alert(json.msg);
						}
					}
				});
				//alert("OK1"); return;
			});
		}
		//alert("OK2"); return;
	}

	//목록
	function list(){
		location.href = "/pop/coupon<?=($add !="") ? "?add=". $add : ""?>"; //목록 페이지
	}
</script>