<div class="wrap_compare">
  <div class="s_tit">
    스마트전단 관리
    <span class="t_small">
      <span class="material-icons">contact_support</span> [전단복사] 버튼을 클릭하시면 스마트전단 목록으로 복사됩니다.
    </span>
  </div>
  <div class="search_sample_wrap">
    <select id='search_type'>
        <!-- <option value='1' <?=$search_type == '1' ? 'selected' : ''?>>업체명</option> -->
        <option value='2' <?=$search_type == '2' ? 'selected' : ''?>>전단명</option>
    </select>
    <input type='text' id='search_txt' value='<?=$search_txt?>' onkeyup="if(window.event.keyCode==13){open_page(1)}">
    <button class="btn md dark" onclick='open_page(1)'>검색</button>
  </div>
  <div class="write_leaflets">
    <div class="wl_lbox">
      <div class="list_leaflets">
        <ul>
		  <?
			foreach($data_list as $r){
				$stmall_yn = $this->funn->get_stmall_yn($r->mem_stmall_yn, $r->psd_order_yn, $r->psd_order_sdt, $r->psd_order_edt); //스마트전단 주문하기 사용여부
		  ?>
          <li>
            <div class="ll_date">
              <span class="date_d"><?=$r->psd_cre_dd?></span><?//등록일?>
              <span class="date_ym"><?=$r->psd_cre_ym?></span><?//등록년월?>
            </div>
            <div class="ll_title">
              <p class="title_t">
                <?=$r->psd_title?><?//제목?>
                <? if($stmall_yn == "Y"){ ?><span class="material-icons">shopping_cart</span><? } ?>
              </p>
              <p class="title_b">
                행사기간 : <?=$r->psd_date?><?//행사기간?>
				<? if($this->member->item('mem_level') >= 100){ //최고관리자만 ?>
					[<?=$r->mem_username?>]
				<? } ?>
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
		  <?
			} //foreach($data_list as $r){
		  ?>
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
    var igenie_path = "<?=config_item('igenie_path')?>";
	//미리보기
	function preview(code, scroll_yn){
		if(scroll_yn == "Y"){
			window.scroll(0, getOffsetTop(document.getElementById("div_preview"))); //미리보기 영역으로 스크롤 이동
		}
		$.ajax({
			url: "/mall/compare/preview",
			type: "POST",
			data: {"code" : code, "add" : "<?=$add?>", "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
                console.log("asdf");
				//alert("json : "+ json);
				var tem_imgpath = json.screen_data["tem_imgpath"]; //텝플릿 이미지 경로
				var tem_bgcolor = json.screen_data["tem_bgcolor"]; //텝플릿 배경색
				var tem_useyn = json.screen_data["tem_useyn"]; //템플릿 사용여부(Y.사용, N.사용안함, S.직접입력)
				var mem_stmall_yn = json.screen_data["mem_stmall_yn"]; //파트너 스마트전단 주문하기 사용여부
				var psd_order_yn = json.screen_data["psd_order_yn"]; //주문하기 사용여부
				var psd_order_sdt = json.screen_data["psd_order_sdt"]; //주문하기 시작일자
				var psd_order_edt = json.screen_data["psd_order_edt"]; //주문하기 종료일자
				var add_cart_yn = "N"; //장바구니 아이콘 표기여부
				if(mem_stmall_yn == "Y" && psd_order_yn == "Y"){
					var today = getToday(); //오늘 날짜 (yyyy-mm-dd) 형식으로 가져오기
					if(psd_order_sdt <= today && psd_order_edt >= today){
						add_cart_yn = "Y";
					}
				}
				var div_class = "pre_box2";
				if(tem_useyn == "S") div_class = "pre_box2 mg_t0";
				if(tem_imgpath == "") tem_imgpath = "<?=$this->config->item('screen_tem_img')?>";
				if(tem_bgcolor == "") tem_bgcolor = "<?=$this->config->item('screen_tem_bgcolor')?>";
				//alert("tem_bgcolor : "+ tem_bgcolor);
				var html = "";
				html += "        <div class=\"pre_box_wrap\">";
				html += "          <!--템플릿 이미지가 들어갈 공간-->";
				html += "          <div class=\"wl_r_preview_bg\" style=\"background:url('"+ igenie_path + tem_imgpath +"') no-repeat top center;background-color:"+ tem_bgcolor +";\"></div>";
				html += "          <!--//템플릿 이미지가 들어갈 공간-->";
				html += "          <div class=\"pre_box1\">";
				html += "            <p class=\"pre_tit\">"+ json.screen_data["psd_title"] +"</p>";
				html += "            <p class=\"pre_date\">"+ json.screen_data["psd_date"] +"</p>";
				html += "          </div>";
				if(tem_useyn == "S"){ //템플릿 사용여부(Y.사용, N.사용안함, S.직접입력)
					html += "          <div class=\"img100\">";
					html += "            <img src=\""+ igenie_path + tem_imgpath +"\">";
					html += "          </div>";
				}
				//STEP1 시작
				if(json.screen_data["psd_step1_yn"] == "Y"){ //STEP1 시작
					html += "          <div class=\""+ div_class +"\">";
					html += "            <!--상품추가시 looping-->";
					//alert("json.screen_step1 : "+ json.screen_step1);
					$.each(json.screen_step1, function(key, value){ //STEP1 상품 리스트 시작
						var psg_imgpath = value.psg_imgpath; //상품 이미지
						if(psg_imgpath == "") psg_imgpath = "/dhn/images/leaflets/sample_img.jpg";
						var badge_rate = ""; //할인율
						if(value.psg_price != "" && value.psg_dcprice != ""){
							var psg_price = value.psg_price.replace(/[^0-9]/g,""); //정상가 숫자만 출력
							var psg_dcprice = value.psg_dcprice.replace(/[^0-9]/g,""); //할인가 숫자만 출력
							if(psg_price != "" && psg_dcprice != ""){
								badge_rate = 100 - ( ( (psg_dcprice / psg_price) * 100) ).toFixed(0); //할인율
							}
						}
						var psg_dcprice = value.psg_dcprice; //STEP1 할인가
						if(psg_dcprice != ""){
							var psg_dcprice_last = value.psg_dcprice.substr(value.psg_dcprice.length-1, 1); //할인가 마지막 단어
							if(psg_dcprice_last == "원"){ //할인가 마지막 단어가 원인 경우
								psg_dcprice = value.psg_dcprice.substr(0 , value.psg_dcprice.length-1) + "<span class='sm_small'>원</span>"; //할인가 원에 class 주기
							}
						}
						html += "            <dl>";
						if(add_cart_yn == "Y"){
              if(value.psg_stock == "N"){
                	html += "              <button class=\"icon_add_cart\" style=\"display:none\">장바구니</button>"; //장바구니
              }else{
                  html += "              <button class=\"icon_add_cart\">장바구니</button>"; //장바구니
              }
						}

            if(value.psg_stock == "N"){
              html += "              <dt class=\"soldout\">";
            }else{
              html += "              <dt>";
            }
						if(value.psg_badge != "0"){ //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
							if(value.psg_badge == "1"){ //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
								html += "                <div class=\"sale_badge\" style=\"display:;\">"+ badge_rate +"<span>%</span></div>"; //할인뱃지
							}else{
								html += "                <div class=\"design_badge\" style=\"display:;\"><img src=\""+ igenie_path + value.badge_imgpath +"\"></div>"; //행사뱃지
							}
						}

                        if(psg_imgpath.indexOf('.')>=0) {

                         var filename = psg_imgpath.substring(0,psg_imgpath.lastIndexOf('.'));
                         var exp = "."+psg_imgpath.substring(psg_imgpath.lastIndexOf('.')+1,psg_imgpath.length);
                         if(filename.indexOf('_thumb')>=0){

                         }else{
                             if(!filename.indexOf('sample_img')){
                                 psg_imgpath = igenie_path + filename + "_thumb"+exp;
                              }
                         }
                        }

						html += "                <img src=\""+ psg_imgpath +"\" alt=\"\"/>"; //상품 이미지
						html += "              </dt>";
						html += "              <dd>";
						html += "                <ul>";
						html += "                  <li class=\"price1\">"+ value.psg_price +"</li>"; //정상가
						html += "                  <li class=\"price2\">"+ psg_dcprice +"</li>"; //할일가
						if(value.psg_option2 != ""){
							html += "                  <li><span class=\"pop_option\">"+ value.psg_option2 +"</span></li>"; //옵션
						}
						html += "                  <li class=\"name\">"+ value.psg_name +" "+ value.psg_option +"</li>"; //상품명 규격
						html += "                </ul>";
						html += "              </dd>";
						html += "            </dl>";
					}); //STEP1 상품 리스트 종료
					html += "            <!--//상품추가시 looping-->";
					html += "          </div><!--//pre_box2-->";
				} //STEP1 종료
				html += "        </div>";
				html += "        <div class=\"pre_box_wrap\">";
				//코너별 리스트
				var ii = 0; //코너내 순번
				var chk_step_no = -1; //코너별 고유번호
				$.each(json.screen_box, function(key, value){ //코너별 리스트 시작
					var psg_step = value.psg_step; //스텝(1.할인&대표상품, 2.이미지형, 3.텍스트형)
					var psg_imgpath = value.psg_imgpath; //상품 이미지
					if(psg_imgpath == "") psg_imgpath = "/dhn/images/leaflets/sample_img.jpg";
					var badge_rate = ""; //할인율
					if(value.psg_price != "" && value.psg_dcprice != ""){
						var psg_price = value.psg_price.replace(/[^0-9]/g,""); //정상가 숫자만 출력
						var psg_dcprice = value.psg_dcprice.replace(/[^0-9]/g,""); //할인가 숫자만 출력
						if(psg_price != "" && psg_dcprice != ""){
							badge_rate = 100 - ( ( (psg_dcprice / psg_price) * 100) ).toFixed(0); //할인율
						}
					}
					if(psg_step == 2 || psg_step == 4 || psg_step == 5){ //스텝(1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형)
						var pop_list_class = "pop_list01";
						if(psg_step == "4"){ //4.3단 이미지형
							pop_list_class = "pop_list03";
						}else if(psg_step == "5"){ //5.4단 이미지형
							pop_list_class = "pop_list04";
						}
						if(((psg_step*10)+value.psg_step_no) != chk_step_no){
							ii = 0; //코너내 순번
							html += "          <div class=\"pre_box3\">";
							if(value.tit_imgpath != "" && value.tit_imgpath != null){
								html += "            <p class=\"pre_tit\"><img src=\""+ igenie_path + value.tit_imgpath +"\" alt=\"\"></p>"; //STEP2 타이틀 이미지
							}
							html += "            <div class=\"pop_list_wrap\">";
						}
						html += "              <!--상품추가시 looping-->";
						html += "              <div class=\""+ pop_list_class +"\">";
            if(add_cart_yn == "Y"){
              if(value.psg_stock == "N"){
                html += "              <button class=\"icon_add_cart\" style=\"display:none\">장바구니</button>"; //장바구니
              }else{
                html += "              <button class=\"icon_add_cart\">장바구니</button>"; //장바구니
              }
            }
						if(value.psg_badge != "0"){ //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
							if(value.psg_badge == "1"){ //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
								html += "                <div class=\"sale_badge\" style=\"display:;\">"+ badge_rate +"<span>%</span></div>"; //할인뱃지
							}else{
								html += "                <div class=\"design_badge\" style=\"display:;\"><img src=\""+ igenie_path + value.badge_imgpath +"\"></div>"; //행사뱃지
							}
						}
            if(value.psg_stock == "N"){
              html += "                <div class=\"pop_good_img soldout\">";
            }else{
              html += "                <div class=\"pop_good_img\">";
            }
                        if(psg_imgpath.indexOf('.')>=0) {

                         var filename = psg_imgpath.substring(0,psg_imgpath.lastIndexOf('.'));
                         var exp = "."+psg_imgpath.substring(psg_imgpath.lastIndexOf('.')+1,psg_imgpath.length);
                         if(filename.indexOf('_thumb')>=0){

                         }else{
                             if(!filename.indexOf('sample_img')){
                                  psg_imgpath = igenie_path + filename + "_thumb"+exp;
                             }
                         }
                        }
						html += "                  <img src=\""+ psg_imgpath +"\" alt=\"\"/>"; //상품 이미지
						html += "                </div>";
						html += "                <div class=\"pop_price\">";
						html += "                  <p class=\"price1\">"+ value.psg_price +"</p>"; //정상가
						html += "                  <p class=\"price2\">"+ value.psg_dcprice +"</p>"; //할일가
						html += "                </div>";
						if(value.psg_option2 != ""){
							html += "                <div><span class=\"pop_option\">"+ value.psg_option2 +"</span></div>"; //옵션
						}
						html += "                <div class=\"pop_name\">";
						html += "                  "+ value.psg_name +" "+ value.psg_option +""; //상품명 규격
						html += "                </div>";
						html += "              </div>";
						html += "              <!--상품추가시 looping-->";
						ii++; //코너별 등록수
						if(ii >= value.rownum){
							html += "            </div><!--//pop_list_wrap-->";
							html += "          </div><!--//pre_box3-->";
						}
						chk_step_no = ((psg_step*10)+value.psg_step_no);
					}else if(psg_step == 3){ //텍스트형
						if(((psg_step*10)+value.psg_step_no) != chk_step_no){
							ii = 0; //코너내 순번
							html += "          <div class=\"pre_box4\">";
							if(value.tit_imgpath != "" && value.tit_imgpath != null){
								html += "            <p class=\"pre_tit\"><img src=\""+ igenie_path + value.tit_imgpath +"\" alt=\"\"></p>"; //STEP2 타이틀 이미지
							}
							html += "            <div class=\"pop_list_wrap\">";
						}
						html += "              <!--상품추가시 looping-->";
            //html +="<span>"+value.psg_stock+"</span>";//테스
            if(value.psg_stock == "N"){
              if(add_cart_yn == 'Y'){
                html += "              <div class=\"pop_list02 cartplus_t soldout\">";
              }else{
                html += "              <div class=\"pop_list02 cartplus soldout\">";
              }

            }else{
              if(add_cart_yn == 'Y'){
                html += "              <div class=\"pop_list02 cartplus_t\">";
              }else{
                html += "              <div class=\"pop_list02 cartplus\">";
              }

            }
						html += "                 <span class=\"name\">"+ value.psg_name +" "+ value.psg_option +"</span>"; //상품명
						html += "                 <span class=\"price1\">"+ value.psg_price +"</span>"; //정상가
						html += "                 <span class=\"price2\">"+ value.psg_dcprice +"</span>"; //할일가
            if(add_cart_yn == "Y"){
              if(value.psg_stock == "N"){
                html += "              <button class=\"icon_add_cart\" style=\"display:none\">장바구니</button>"; //장바구니
              }else{
                html += "              <button class=\"icon_add_cart\">장바구니</button>"; //장바구니
              }
            }
						html += "              </div>";
						html += "              <!--상품추가시 looping-->";
						ii++; //코너별 등록수
						if(ii >= value.rownum){
							html += "            </div><!--//pop_list_wrap-->";
							html += "          </div><!--//pre_box4-->";
						}
						chk_step_no = ((psg_step*10)+value.psg_step_no);
					}else if(psg_step == 9){ //행사이미지
						html += "          <div class=\"pre_box4\">";
						if(value.psg_imgpath != "" && value.psg_imgpath != null){
							html += "            <p class=\"pre_tit\"><img src=\""+ igenie_path + value.psg_imgpath +"\" alt=\"\"></p>"; //STEP2 타이틀 이미지
						}
						html += "          </div><!--//pre_box4-->";
					}
				}); //코너별 리스트 종료
				html += "        </div><!--//pre_box_wrap-->";
				$("#div_preview").html(html);
				$("#div_preview").scrollTop(0); //미리보기 영역 스크롤 상단으로 이동
			},
            error:function(request, status, error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
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
				url: "/spop/screen/screen_mng_copy",
				type: "POST",
				data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("정상적으로 복사 되었습니다.", 1500);
					setTimeout(function() {
						//location.reload();
						location.href = "/mall/compare?page=<?=$param['page']?>";
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

		var field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "page");
		field.setAttribute("value", page); //검색 페이지
		form.appendChild(field);

        field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "search_type");
		field.setAttribute("value", $('#search_type').val());
		form.appendChild(field);

        field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "search_txt");
		field.setAttribute("value", $('#search_txt').val().trim());
		form.appendChild(field);

		field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(field);
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
