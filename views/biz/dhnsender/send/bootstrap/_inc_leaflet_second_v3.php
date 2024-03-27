<?
	//전단보기 링크 명칭
	$leaflet_link_name = "행사보기";
	$coupon_link_name = "쿠폰확인";
	$mem_purigo_yn = $this->member->item("mem_purigo_yn"); //Purigo 회원여부
	$link_type_option = "";
	$lms_purigo_yn = "N"; //Purigo 문자 발송 여부
	if($leaflet_link_type == "lms" and $mem_purigo_yn == "Y"){
		$lms_purigo_yn = "Y"; //Purigo 문자 발송 여부
	}
	if($tpl_button2_leaflet_yn == "Y"){ //알림톡 버튼2 전단보기의 경우
		$link_type_option = "smartEditor"; //스마트전단+에디터
	}else if($leaflet_link_type == "lms" and $lms_purigo_yn != "Y"){ //문자 & Purigo 회원이 아닌 경우
		if($psd_code != ""){
			$link_type_option = "smart"; //스마트전단
		}else if($pcd_code != ""){
			$link_type_option = "coupon"; //스마트전단
		}else{
			$link_type_option = "none"; //사용안함
		}
	}else if ($rs->tpl_btn1_type == "E" or ($lms_purigo_yn == "Y")){ //에디터사용 or (문자 & Purigo 회원)
		$link_type_option = "editor"; //에디터사용
	}else if($rs->tpl_btn1_type == "S"){ //직접입력
		$link_type_option = "self"; //직접입력
	}else if($rs->tpl_btn1_type == "C"){ //스마트쿠폰
		$link_type_option = "coupon"; //스마트쿠폰
	}else{ //스마트전단
		$link_type_option = "smart"; //스마트전단
	}

	//echo "/views/biz/dhnsender/send/bootstrap/_inc_leaflet.php<br>알림톡 버튼2 전단보기 여부 : ". $tpl_button2_leaflet_yn ."<br>";
?>
<div class="smart_btn_box">
	<div id="inhere"></div>
	<? if($leaflet_link_type = "at" || $leaflet_link_type == "ai"){ //알림톡의 경우 ?>
	<div id="se_field"></div><!-- 알림톡 버튼 영역 -->
	<? } ?>
	<div class="editor_box" id="div_editor_box" style="margin:<?=($lms_purigo_yn == "Y") ? "0px" : "15px"?> 0px; display:none;">
		<div style="display:<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/lms") == true) ? "" : "none"?>;">
			<div class="url_info" style="margin: 0px 0px 10px 0px;"><?=$dhnl_url?></div>
			<button class="btn_link2" onClick="linkview();">링크확인</button><?//에디터 링크확인?>
		</div>
		<!-- Summernote editor 시작 -->
		<script src="/summernote/summernote-lite.js"></script>
		<script src="/summernote/lang/summernote-ko-KR.js"></script>
		<link rel="stylesheet" href="/summernote/summernote-lite.css">
		<div id="summernote"></div>
		<script>
			$(document).ready(function() {
				$('#summernote').summernote({
					height: 340,                 // 에디터 높이
					minHeight: null,             // 최소 높이
					maxHeight: null,             // 최대 높이
					focus: false,                  // 에디터 로딩후 포커스를 맞출지 여부
					lang: "ko-KR",					// 한글 설정
					placeholder: '에디터 내용을 입력하세요.',	//placeholder 설정
					toolbar: [
						['fontsize', ['fontsize']],
						['style', ['bold', 'italic', 'underline','strikethrough']],
						['color', ['forecolor','color']],
						['insert',['picture']]
					],
					callbacks: {
						onImageUpload: function(image) {
                            for(var i = 0; i < image.length; i++){
							    uploadImage(image[i]);
                            }
						}
					},
				});
				chkword_lms();
				$('#summernote').summernote('fontSize', 18);
                // $('.note-editable').css('font-size','18px');
				<? if(!empty($savemsg->ums)) { ?>
				$('#summernote').summernote('code', '<?=$savemsg->ums?>');
				<? } ?>
				var html = $('#summernote').summernote('code'); //에디터 내용
				//alert("html : "+ html);
				//alert("html : "+ html); return;
				if(html == '' || html == '<p><br></p>' || html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){ //에디터의 경우
					$('#summernote').summernote('code', '');
				}
				$('.form-group note-group-image-url').hide();
			});

			function uploadImage(image) {
				var data = new FormData();
				data.append("image_file", image);
				data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				$.ajax({
					url: 'http://<?=$_SERVER['HTTP_HOST']?>/dhnbiz/sender/image_upload/talkadvimg',
					cache: false,
					contentType: false,
					processData: false,
                    async: false,
					data: data,
					type: "post",
					success: function(json) {
						console.log(json);
						if(json['code']=='S') {
							var image = $('<img>').attr('src', json['imgurl']);
							$('#summernote').summernote("insertNode", image[0]);
						}
					},
					error: function(data) {
						console.log(data);
					}
				});
			}
		</script>
        <button onClick="open_msg_editor_list();" class="btn md fr mg_t10"><span class="material-icons" style="font-size: 14px;">login</span> 이전 에디터내용 불러오기</button>
	</div>
</div>
<script>
	//링크확인
	function linkview(id){
		var link_type = document.getElementById("link_type"+id).value;
		//alert("link_type : "+ link_type);
		if(link_type == "editor"){ //에디터
			var url = document.getElementById("dhnl_url").value; //에디터 링크 URL
			var bizurl =  document.getElementById("biz_url").value;
			//alert("bizurl : "+ bizurl); return;
			var html = $('#summernote').summernote('code');
			//alert("url : "+ url +", bizurl : "+ bizurl +", html : "+ html); return;
			if(html == '' || html == '<p><br></p>' || html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){ //에디터의 경우
				$('#summernote').summernote('code', '');
				alert("<?=($lms_purigo_yn != "Y") ? "에디터" : "URL 메시지 편집"?> 내용을 입력하세요.");
				return;
			}else{
				//alert("html : "+ html); return;
				//에디터 전단내용 저장
				$.ajax({
					url: "/dhnbiz/sender/talk_v3/ums_save",
					type: "POST",
					data: {"bizurl" : bizurl, "html" : html, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {}
				});
				if (url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0) {
				} else {
					url = "http://" + url;
				}
				editor_preview(url); //에디터 미리보기
			}
		}else if(link_type == "self"){ //직접입력
			//alert("link_type : "+ link_type);
			var btn_url = "";
			// var btn_no = 0;
			// $("div[name='field<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/select_template_second") == true) ? "_second" : ""?>-data']").each(function () {
				// btn_no++;
				//alert("btn_no : "+ btn_no);
				// var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
                var btn_type = $("#field-data-"+id).find("#btn_type").val();
                console.log(btn_type);
				if (btn_type != undefined) {
					var focus;
					btn_url =  $("#field-data-"+id).find("[name=btn_url1]").val() || "";
                    console.log(btn_url);
					if (btn_url == "" || !checkURL(btn_url)) {
						check = false;
                        focus =
						focus = $("#field-data-"+id).find("[name=btn_url1]").val();
						alert("버튼 링크가 입력되지 않았거나 URL 주소가 올바르지 않습니다.");
						focus.focus();
						return;
					}else{
						if (btn_url.toLowerCase().indexOf("http://") == 0 || btn_url.toLowerCase().indexOf("https://") == 0) {
						} else {
							btn_url = "http://" + btn_url;
						}
						window.open(btn_url, "", "");
					}
				}
			// });
			//alert("btn_url : "+ btn_url);
		}else if(link_type == "coupon"){ //스마트쿠폰
			var url = document.getElementById("pcd_url").value;
			var type = document.getElementById("pcd_type").value;
			//alert("url : "+ url);
			if(url == ""){
				alert("스마트쿠폰을 선택하세요.");
				coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기 => /views/biz/inc/send_menu1.php 있음
				return;
			}else{
				coupon_preview(url, type); //스마트쿠폰 미리보기
			}
		}else{ //스마트전단
			var url = document.getElementById("psd_url").value;
			//alert("url : "+ url);
			if(url == ""){
				alert("스마트전단을 선택하세요.");
				smart_page('1'); //스마트전단 불러오기 모달창 열기 => /views/biz/inc/send_menu1.php 있음
				return;
			}else{
				smart_preview(url); //스마트전단 미리보기
			}
		}
	}

    //링크확인
	function linkview_btn(id){
		var link_type = $("#link_type0").val();
		//alert("link_type : "+ link_type);
        if(link_type == "home"){
            var home_chk1 = $("#chk_a").is(":checked");//스마트전단
            var home_chk2 = $("#chk_b").is(":checked");//에디터
            var home_chk3 = $("#chk_c").is(":checked");//스마트쿠폰
            var home_chk4 = $("#chk_d").is(":checked");//직접입력
            if(home_chk1&&id==0){//스마트전단
                // var url = document.getElementById("psd_url").value;
                var url = $("#hdn_smart_url").val();
    			//alert("url : "+ url);
    			if(url == ""){
    				alert("스마트전단을 선택하세요.");
    				smart_page('1'); //스마트전단 불러오기 모달창 열기 => /views/biz/inc/send_menu1.php 있음
    				return;
    			}else{
    				smart_preview(url); //스마트전단 미리보기
    			}
            }else if(home_chk2&&id==1){//에디터
                var url = $("#hdn_editor_url").val();
                var url2 = $("#hdn_smart_url").val();
                var url3 = $("#hdn_coupon_url").val();
                var url4 = $("#hdn_order_url").val();

                if(home_chk4==false){
                    url4 = "";
                }
                var psdcode = "";
                var pcdcode = "";

                if(url2!=""){//스마트전단
                    psdcode = $("#psd_code").val();
                }

                if(url3!=""){//스마트쿠폰
                    pcdcode = $("#pcd_code").val();
                }

    			var bizurl = document.getElementById("biz_url").value;
    			//alert("bizurl : "+ bizurl); return;
    			var html = $('#summernote').summernote('code');
    			//alert("url : "+ url +", bizurl : "+ bizurl +", html : "+ html); return;
    			if(html == '' || html == '<p><br></p>' || html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){ //에디터의 경우
    				$('#summernote').summernote('code', '');
    				alert("<?=($lms_purigo_yn != "Y") ? "에디터" : "URL 메시지 편집"?> 내용을 입력하세요.");
    				return;
    			}else{
    				//alert("html : "+ html); return;
    				//에디터 전단내용 저장
    				$.ajax({
    					url: "/dhnbiz/sender/talk_v3/ums_save",
    					type: "POST",
    					data: {"bizurl" : bizurl
                            , "html" : html
                            , "link_type" : "home"
                            , "home_code" : $("#home_code").val()
                            , "home_url" : $("#home_url").val()
                            , "psd_code" : psdcode
                            , "psd_url" : url2
                            , "pcd_code" : pcdcode
                            , "pcd_type" : $("#pcd_type").val()
                            , "pcd_url" : url3
                            , "order_url" : url4
                            , "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                        },
    					success: function (json) {}
    				});
    				if (url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0) {
    				} else {
    					url = "http://" + url;
    				}
    				editor_preview(url); //에디터 미리보기
    			}
            }else if(home_chk3&&id==2){//스마트쿠폰
                var url = $("#hdn_coupon_url").val();
                // var url = document.getElementById("pcd_url").value;
    			var type = document.getElementById("pcd_type").value;
    			//alert("url : "+ url);
    			if(url == ""){
    				alert("스마트쿠폰을 선택하세요.");
    				coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기 => /views/biz/inc/send_menu1.php 있음
    				return;
    			}else{
    				coupon_preview(url, type); //스마트쿠폰 미리보기
    			}
            }else if(home_chk4&&id==3){//직접입력
                var url = $("#hdn_order_url").val();
                // var url = document.getElementById("pcd_url").value;

    			//alert("url : "+ url);
    			if(url == ""){
    				alert("주문하기 url을 넣어주세요");
    				return;
    			}else{
    				window.open(url, "", "");
    			}
            }

        }

	}

    //링크-홈
	function linkview_home(id){
		var link_type = $("#link_type"+id).val();
		//alert("link_type : "+ link_type);
        if(link_type == "home"&&id==0){
            var home_chk1 = $("#chk_a").is(":checked");//스마트전단
            var home_chk2 = $("#chk_b").is(":checked");//에디터
            var home_chk3 = $("#chk_c").is(":checked");//스마트쿠폰
            var home_chk4 = $("#chk_d").is(":checked");//직접입력

            if(home_chk1==""&&home_chk2==""&&home_chk3==""&&home_chk4==""){
                alert("스마트홈 기능을 선택하세요.");
                return;
            }else{
                var url = $("#hdn_editor_url").val();
                var url2 = $("#hdn_smart_url").val();
                var url3 = $("#hdn_coupon_url").val();
                var url4 = $("#hdn_order_url").val();

                if(home_chk4==false){
                    url4 = "";
                }
                var bizurl = $("#biz_url").val();
                var html = $('#summernote').summernote('code');
                var psdcode = "";
                var pcdcode = "";
                if(url2!=""){//스마트전단
                    psdcode = $("#psd_code").val();
                }
                if(url3!=""){//스마트쿠폰
                    pcdcode = $("#pcd_code").val();
                }
                if(home_chk1&&url2 == ""){
                    alert("스마트전단을 선택하세요.");
    				smart_page('1'); //스마트전단 불러오기 모달창 열기 => /views/biz/inc/send_menu1.php 있음
    				return;
                }else if(home_chk2&&url == ""){

                    if(html == '' || html == '<p><br></p>' || html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){ //에디터의 경우
        				$('#summernote').summernote('code', '');
        				alert("에디터내용을 입력하세요.");
        				return;
        			}
                }else if(home_chk3&&url3 == ""){
                    alert("스마트쿠폰을 선택하세요.");
    				coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기 => /views/biz/inc/send_menu1.php 있음
    				return;
                }
                $.ajax({
                    url: "/dhnbiz/sender/talk_v3/ums_save",
                    type: "POST",
                    data: {"bizurl" : bizurl
                        , "html" : html
                        , "link_type" : "home"
                        , "home_code" : $("#home_code").val()
                        , "home_url" : $("#home_url").val()
                        , "psd_code" : psdcode
                        , "psd_url" : url2
                        , "pcd_code" : pcdcode
                        , "pcd_type" : $("#pcd_type").val()
                        , "pcd_url" : url3
                        , "order_url" : url4
                        , "order_btn": $("#hdn_order_btn").val()
                        , "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                    },
                    success: function (json) {
                        home_preview($("#home_url").val());
                    }
                });
                // if (url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0) {
                // } else {
                //     url = "http://" + url;
                // }

            }
            check_shop2();
        }else{
            var btn_url = "";
            var btn_type = $("#field-data-"+id).find("#btn_type").val();
			if (btn_type != undefined) {
				var focus;
				btn_url =  $("#field-data-"+id).find("[name=btn_url1]").val() || "";
				if (btn_url == "" || !checkURL(btn_url)) {
					check = false;
                    focus =
					focus = $("#field-data-"+id).find("[name=btn_url1]").val();
					alert("버튼 링크가 입력되지 않았거나 URL 주소가 올바르지 않습니다.");
					focus.focus();
					return;
				}else{
					if (btn_url.toLowerCase().indexOf("http://") == 0 || btn_url.toLowerCase().indexOf("https://") == 0) {
					} else {
						btn_url = "http://" + btn_url;
					}
					window.open(btn_url, "", "");
				}
			}
        }

	}
</script>
<!-- 스마트전단 불러오기 Modal -->
<!-- <div id="modal_smart" class="dh_modal">
  <div class="modal-content md_width970"> <span id="close_smart" class="dh_close">&times;</span>
    <p class="modal-tit t_al_left">
      스마트전단 목록
      <span class="btn_smart"><a href="/spop/screen/write">스마트전단 만들기</a></span>
    </p>
    <ul id="id_smart_list"><?//스마트전단 리스트 영역?>
    </ul>
    <div class="page_cen" id="id_smart_page">
    </div>
  </div>
</div> -->
<!-- 스마트쿠폰 불러오기 Modal -->
<!-- <div id="modal_coupon" class="dh_modal">

  <div class="modal-content md_width970"> <span id="close_coupon" class="dh_close">&times;</span>
    <p class="modal-tit t_al_left">
      스마트쿠폰 목록
      <span class="btn_smart"><a href="/spop/coupon/write">스마트쿠폰 만들기</a></span>
    </p>
    <ul id="id_coupon_list"><?//스마트쿠폰 리스트 영역?>
    </ul>
    <div class="page_cen" id="id_coupon_page">
    </div>
  </div>
</div> -->
<script>
	//스마트전단 불러오기
	// var modal_smart = document.getElementById("modal_smart");
	// function smart_page(page){
	// 	//스마트전단 데이타 조회
	// 	$("#id_smart_list").html("");
	// 	$("#id_smart_page").html("");
	// 	$.ajax({
	// 		url: "/spop/screen/smart_data",
	// 		type: "POST",
	// 		data: {"per_page" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json) {
	// 			var html = "";
	// 			$.each(json, function(key, value){
	// 				var psd_code = value.psd_code; //전단코드
	// 				var tem_imgpath = value.tem_imgpath; //템플릿 이미지
	// 				var psd_title = value.psd_title; //전단제목
	// 				var psd_date = value.psd_date; //행사기간
	// 				var psd_credt = value.psd_credt; //등록일자
	// 				if(psd_code != "" && psd_code != undefined){
	// 					html += "<li>";
	// 					html += "  <div class=\"tem_img\">";
	// 					html += "    <div id=\"pre_smart_bg\" onClick=\"smart_choice('"+ psd_code +"');\" class=\"preview_bg\" style=\"background:url('"+ tem_imgpath +"') no-repeat; background-size:100%; background-position:0 50px; cursor:pointer;\"></div>"; //템플릿 이미지
	// 					html += "    <div class=\"pre_box1\">";
	// 					html += "      <p id=\"pre_wl_tit\" class=\"pre_tit\">"+ psd_title +"</p>"; //전단제목
	// 					html += "      <p id=\"pre_wl_date\" class=\"pre_date\">"+ psd_date +"</p>"; //행사기간
	// 					html += "    </div>";
	// 					html += "  </div>";
	// 					html += "  <p class=\"tem_text\">";
	// 					html += "    <span>"+ psd_credt +"</span>"; //등록일자
	// 					html += "    <button type=\"button\" onClick=\"smart_choice('"+ psd_code +"');\"><i class=\"xi-check-circle-o\"></i> 전단선택</button>";
	// 					html += "  </p>";
	// 					html += "</li>";
	// 				}
	// 			});
	// 			$("#id_smart_list").append(html);
	// 			$("#id_smart_page").append(json.page_html);
	// 		}
	// 	});
    //
	// 	//open the modal
	// 	modal_smart.style.display = "block";
    //
	// 	//close the modal
	// 	var close = document.getElementById("close_smart");
	// 	close.onclick = function() {
	// 		modal_smart.style.display = "none";
	// 	}
    //
	// 	// When the user clicks anywhere outside of the modal, close it
	// 	window.onclick = function(event) {
	// 		if (event.target == modal_smart) {
	// 			modal_smart.style.display = "none";
	// 		}
	// 	}
	// }

	//스마트전단 상품정보 가져오기
	// function smart_goods_call(pn){
    //     // var btn_cnt = buttonArr.indexOf("smart");
	// 	// var link_type = document.getElementById("link_type"+btn_cnt).value; //URL 타입
    //     var link_type = $("#link_type0").val();
    //     //alert("link_type : "+ link_type);
    //     if(link_type == "home"){
    //         var home_chk1 = $("#chk_a").is(":checked");//스마트전단
    //         if(!home_chk1){
    //
    //             showSnackbar("스마트전단을 체크해서 활성화 해주세요", 1500);
    //             window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
    //             return false;
    //
    //         }
    //
    //     }else{
    //         showSnackbar("스마트홈이 선택되지 않았습니다", 1500);
    //         window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
    //         return false;
    //     }
    //
	// 	var psd_code = document.getElementById("psd_code").value; //전단코드
	// 	//alert("link_type : "+ link_type +", psd_code : "+ psd_code);
	// 	if(psd_code == ""){
	// 		alert("스마트전단을 선택하세요.");
	// 		smart_page('1'); //스마트전단 불러오기 모달창 열기
	// 		return;
	// 	}
	// 	$.ajax({
	// 		url: "/spop/screen/smart_goods_call",
	// 		type: "POST",
	// 		data: {"psd_code" : psd_code, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json) {
	// 			var i = 0;
	// 			var html = "";
	// 			$.each(json, function(key, value){
	// 				var psg_name = value.psg_name; //상품명
	// 				var psg_option = value.psg_option; //옵션명
	// 				var psg_price = value.psg_price; //정상가
	// 				var psg_dcprice = value.psg_dcprice; //할인가
	// 				if(psg_name != "" && psg_name != undefined){
	// 					if(i > 0) html += "\n";
	// 					if(psg_option != "") psg_name = psg_name +" "+ psg_option;
	// 					html += psg_name +" "+ psg_dcprice;
	// 					i++;
	// 				}
	// 			});
	// 			if(pn == "at"){
	// 				//$("#lms").html(html);
	// 				$("textarea[name=var_name]")[2].value = html; //알림톡 변수 3번째 내용 => 스마트전단 상품정보 가져오기
	// 				view_preview();
	// 			}else if(pn == "ai"){
	// 				//$("#lms").html(html);
	// 				$("textarea[name=var_name]")[1].value = html; //알림톡 변수 3번째 내용 => 스마트전단 상품정보 가져오기
	// 				view_preview();
	// 			}else if(pn == "lms"){
    //                 $("#lms").val(html); //문자내용 => 스마트전단 상품정보 가져오기
    //                 onPreviewLms();
    //                 chkword_lms();
	// 			}
	// 		}
	// 	});
	// }

	//스마트쿠폰 불러오기
	// var modal_coupon = document.getElementById("modal_coupon");
	// function coupon_page(page){
	// 	//스마트쿠폰 데이타 조회
	// 	$("#id_coupon_list").html("");
	// 	$("#id_coupon_page").html("");
	// 	$.ajax({
	// 		url: "/spop/coupon/coupon_data",
	// 		type: "POST",
	// 		data: {"per_page" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json) {
	// 			var html = "";
	// 			$.each(json, function(key, value){
	// 				var pcd_code = value.pcd_code; //쿠폰코드
	// 				var pcd_type = value.pcd_type; //쿠폰타입(1.무료증정, 2.가격할인)
	// 				var pcd_viewpath = value.pcd_viewpath; //미리보기경로
	// 				var pcd_title = value.pcd_title; //쿠폰제목
	// 				var pcd_date = value.pcd_date; //행사기간
	// 				var pcd_credt = value.pcd_credt; //등록일자
	// 				//alert("pcd_code : "+ pcd_code)
	// 				if(pcd_code != "" && pcd_code != undefined){
	// 					html += "<li>";
	// 					html += "  <div class=\"tem_img\">";
	// 					html += "    <div id=\"pre_coupon_bg\" onClick=\"coupon_choice('"+ pcd_code +"', '"+ pcd_type +"');\" class=\"preview_bg\" style=\"background:url('"+ pcd_viewpath +"') no-repeat; background-size:100%; background-position:0 0px; cursor:pointer;\"></div>"; //미리보기경로
	// 					//html += "    <div class=\"pre_box1\">";
	// 					//html += "      <p id=\"pre_wl_tit\" class=\"pre_tit\">"+ pcd_title +"</p>"; //쿠폰제목
	// 					//html += "      <p id=\"pre_wl_date\" class=\"pre_date\">"+ pcd_date +"</p>"; //행사기간
	// 					//html += "    </div>";
	// 					html += "  </div>";
	// 					html += "  <p class=\"tem_text\">";
	// 					html += "    <span>"+ pcd_credt +"</span>"; //등록일자
	// 					html += "    <button type=\"button\" onClick=\"coupon_choice('"+ pcd_code +"', '"+ pcd_type +"');\"><i class=\"xi-check-circle-o\"></i> 쿠폰선택</button>";
	// 					html += "  </p>";
	// 					html += "</li>";
	// 				}
	// 			});
	// 			$("#id_coupon_list").append(html);
	// 			$("#id_coupon_page").append(json.page_html);
	// 		}
	// 	});
    //
	// 	//open the modal
	// 	modal_coupon.style.display = "block";
    //
	// 	//close the modal
	// 	var close = document.getElementById("close_coupon");
	// 	close.onclick = function() {
	// 		modal_coupon.style.display = "none";
	// 	}
    //
	// 	// When the user clicks anywhere outside of the modal, close it
	// 	window.onclick = function(event) {
	// 		if (event.target == modal_coupon) {
	// 			modal_coupon.style.display = "none";
	// 		}
	// 	}
	// }
</script>