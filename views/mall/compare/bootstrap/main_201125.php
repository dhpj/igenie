<div class="wrap_compare">
  <div class="s_tit">
    스마트전단 샘플
    <span class="t_small">
      <span class="material-icons">contact_support</span> [전단복사] 버튼을 클릭하시면 스마트전단 목록으로 복사됩니다.
    </span>
  </div>
  <div class="write_leaflets">
    <div class="wl_lbox">
      <div class="list_leaflets">
        <ul>
		  <? foreach($data_list as $r) { ?>
          <li>
            <div class="ll_date">
              <span class="date_d"><?=$r->psd_cre_dd?></span><?//등록일?>
              <span class="date_ym"><?=$r->psd_cre_ym?></span><?//등록년월?>
            </div>
            <div class="ll_title">
              <p class="title_t">
                <?=$r->psd_title?><?//제목?>
              </p>
              <p class="title_b">
                행사기간 : <?=$r->psd_date?><?//행사기간?>
              </p>
              <? if($this->member->item('mem_level') >= 100){ //최고관리자만 ?>
			  <button class="del" style="cursor:pointer;" onClick="viewn('<?=$r->psd_id?>');">삭제</button>
			  <? } ?>
            </div>
            <div class="ll_btn1">
              <button class="view" style="cursor:pointer;" onClick="preview('<?=$r->psd_code?>', 'Y');">미리보기</button>
            </div>
            <div class="ll_btn2">
              <button class="copy" style="cursor:pointer;" onClick="copy('<?=$r->psd_id?>');">전단복사</button>
            </div>
          </li>
		  <? } //foreach($data_list as $r) { ?>
        </ul>
        <div class="page_cen">
          <?=$page_html?>
        </div><!--//pagination-->
        <!--//pagination-->
      </div><!--//list_leaflets-->
    </div><!--//wl_lbox-->
    <div class="wl_rbox">
      <div class="wl_r_preview" id="div_preview"><?//미리보기 영역?>
      </div><!--//wl_r_preview-->
    </div><!--//wl_rbox-->
  </div><!--//write_leaflets-->
</div>

<script>
	//미리보기
	function preview(code, scroll_yn){
		if(scroll_yn == "Y"){
			window.scroll(0, getOffsetTop(document.getElementById("div_preview"))); //미리보기 영역으로 스크롤 이동
		}
		$.ajax({
			url: "/mall/compare/preview",
			type: "POST",
			data: {"code" : code, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				//alert("json : "+ json);
				var tem_imgpath = json.screen_data["tem_imgpath"]; //텝플릿 이미지 경로
				var tem_bgcolor = json.screen_data["tem_bgcolor"]; //텝플릿 배경색
				//alert("tem_bgcolor : "+ tem_bgcolor);
				var html = "";
				html += "        <div class=\"pre_box_wrap\">";
				html += "          <!--템플릿 이미지가 들어갈 공간-->";
				html += "          <div class=\"wl_r_preview_bg\" style=\"background:url('"+ tem_imgpath +"') no-repeat top center;background-color:"+ tem_bgcolor +";\"></div>";
				html += "          <!--//템플릿 이미지가 들어갈 공간-->";
				html += "          <div class=\"pre_box1\">";
				html += "            <p class=\"pre_tit\">"+ json.screen_data["psd_title"] +"</p>";
				html += "            <p class=\"pre_date\">"+ json.screen_data["psd_date"] +"</p>";
				html += "          </div>";
				//STEP1 시작
				if(json.screen_data["psd_step1_yn"] == "Y"){ //STEP1 시작
					html += "          <div class=\"pre_box2\">";
					html += "            <!--상품추가시 looping-->";
					//alert("json.screen_step1 : "+ json.screen_step1);
					$.each(json.screen_step1, function(key, value){ //STEP1 상품 리스트 시작
						var badge_rate = ""; //할인율
						var psg_imgpath = value.psg_imgpath; //상품 이미지
						if(psg_imgpath == "") psg_imgpath = "/dhn/images/leaflets/sample_img.jpg";
						if(value.psg_price != "" && value.psg_dcprice != ""){
							var psg_price = value.psg_price.replace(/[^0-9]/g,""); //정상가 숫자만 출력
							var psg_dcprice = value.psg_dcprice.replace(/[^0-9]/g,""); //할인가 숫자만 출력
							badge_rate = 100 - ( ( (psg_dcprice / psg_price) * 100) ).toFixed(0); //할인율
						}
						html += "            <dl>";
						html += "              <dt>";
						if(value.psg_badge == "1"){ //할인뱃지(0.표기안함, 1.표기함)
							html += "                <div class=\"sale_badge\" style=\"display:;\">"+ badge_rate +"<span>%</span></div>"; //뱃지
						}
						html += "                <img src=\""+ psg_imgpath +"\" alt=\"\"/>"; //상품 이미지
						html += "              </dt>";
						html += "              <dd>";
						html += "                <ul>";
						html += "                  <li class=\"price1\">"+ value.psg_price +"</li>"; //정상가
						html += "                  <li class=\"price2\">"+ value.psg_dcprice +"</li>"; //할일가
						html += "                  <li class=\"name\">"+ value.psg_name +" "+ value.psg_option +"</li>"; //상품명
						html += "                </ul>";
						html += "              </dd>";
						html += "            </dl>";
					}); //STEP1 상품 리스트 종료
					html += "            <!--//상품추가시 looping-->";
					html += "          </div><!--//pre_box2-->";
				} //STEP1 종료
				html += "        </div>";
				html += "        <div class=\"pre_box_wrap\">";
				//STEP2 시작
				if(json.screen_data["psd_step2_yn"] == "Y"){ //STEP2 시작
					html += "          <div class=\"pre_box3\">";
					var ii = 0; //코너내 순번
					var chk_step_no = -1; //코너별 고유번호
					$.each(json.screen_step2, function(key, value){ //STEP2 상품 리스트 시작
						var psg_imgpath = value.psg_imgpath; //상품 이미지
						if(psg_imgpath == "") psg_imgpath = "/dhn/images/leaflets/sample_img.jpg";
						if(value.psg_step_no != chk_step_no){
							ii = 0; //코너내 순번
							if(value.tit_imgpath != "" && value.tit_imgpath != null){
								html += "            <p class=\"pre_tit\"><img src=\""+ value.tit_imgpath +"\" alt=\"\"></p>"; //STEP2 타이틀 이미지
							}
							html += "            <div class=\"pop_list_wrap\">";
						}
						html += "              <!--상품추가시 looping-->";
						html += "              <div class=\"pop_list01\">";
						html += "                <div class=\"pop_good_img\">";
						html += "                  <img src=\""+ psg_imgpath +"\" alt=\"\"/>"; //상품 이미지
						html += "                </div>";
						html += "                <div class=\"pop_price\">";
						html += "                  <p class=\"price1\">"+ value.psg_price +"</p>"; //정상가
						html += "                  <p class=\"price2\">"+ value.psg_dcprice +"</p>"; //할일가
						html += "                </div>";
						html += "                <div class=\"pop_name\">";
						html += "                  "+ value.psg_name +" "+ value.psg_option +""; //상품명
						html += "                </div>";
						html += "              </div>";
						html += "              <!--상품추가시 looping-->";
						ii++; //코너별 등록수
						if(ii >= value.rownum){
							html += "            </div><!--//pop_list_wrap-->";
						}
						chk_step_no = value.psg_step_no;
					}); //STEP2 상품 리스트 종료
					html += "          </div><!--//pre_box3-->";
				} //STEP2 종료
				//STEP3 시작
				if(json.screen_data["psd_step3_yn"] == "Y"){ //STEP3 시작
					html += "          <div class=\"pre_box4\">";
					var ii = 0; //코너내 순번
					var chk_step_no = -1; //코너별 고유번호
					$.each(json.screen_step3, function(key, value){ //STEP3 상품 리스트 시작
						if(value.psg_step_no != chk_step_no){
							ii = 0; //코너내 순번
							html += "            <p class=\"pre_tit\"><img src=\""+ value.tit_imgpath +"\" alt=\"\"></p>"; //STEP3 타이틀 이미지
							html += "            <div class=\"pop_list_wrap\">";
						}
						html += "              <!--상품추가시 looping-->";
						html += "              <div class=\"pop_list02\">";
						html += "                 <span class=\"name\">"+ value.psg_name +" "+ value.psg_option +"</span>"; //상품명
						html += "                 <span class=\"price1\">"+ value.psg_price +"</span>"; //정상가
						html += "                 <span class=\"price2\">"+ value.psg_dcprice +"</span>"; //할일가
						html += "              </div>";
						html += "              <!--상품추가시 looping-->";
						ii++; //코너별 등록수
						if(ii >= value.rownum){
							html += "            </div><!--//pop_list_wrap-->";
						}
						chk_step_no = value.psg_step_no;
					}); //STEP3 상품 리스트 종료
					html += "          </div><!--//pre_box4-->";
				} //STEP3 종료
				html += "        </div><!--//pre_box_wrap-->";
				$("#div_preview").html(html);
				$("#div_preview").scrollTop(0); //미리보기 영역 스크롤 상단으로 이동
			}
		});
	}
	
	//스마트전단 관리자 최근 등록건 조회
	<? if($max_code != ""){ ?>
	preview("<?=$max_code?>", "N");
	<? } ?>

	//스마트전단 삭제
	function viewn(data_id){
		if(confirm("해당 스마트전단 샘플을 삭제 하시겠습니까?\n해당 화면에서만 보이지 않게 처리 됩니다.")){
			$.ajax({
				url: "/mall/compare/viewn",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 삭제 되었습니다.", 1500);
					setTimeout(function() {
						//location.reload();
						location.href = "/mall/compare";
					}, 1000); //1초 지연
				}
			});
		}
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
						location.href = "/mall/compare";
					}, 1000); //1초 지연
				}
			});
		}
	}

	//검색
	function open_page(page) {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "get");
		form.setAttribute("action", "/mall/compare");

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