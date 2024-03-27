<?
	//전단보기 링크 명칭
	$leaflet_link_name = "행사보기";
?>
<div style="width:100%; display:inline-block;">
	<select style="width: 130px;"  name="link_type" id="link_type" onChange="chg_link_type();">
	  <option value="smart"<?=($rs->tpl_id == $bt3_tpl_id) ? "" : " selected"?>>스마트전단</option>
	  <option value="editor">에디터사용</option>
	  <? if($leaflet_link_type == "lms"){ ?>
	  <option value="none">사용안함</option>
	  <? }elseif($leaflet_link_type == "at"){ ?>
	  <option value="self"<?=($rs->tpl_id == $bt3_tpl_id) ? " selected" : ""?>>직접입력</option>
	  <? } ?>
	</select>
	<div id="div_blank_box" style="display:none;margin: 0px 0px 10px 0px;"><? //사용안함 영역 ?>
	</div>
	<div id="div_smart_box" style="display:inline;">
		<button class="btn_add btn_myModal" onClick="smart_page('1');">스마트전단 불러오기</button>
		<p class="noted">
			* 링크는 스마트전단, 에디터사용, 직접입력으로 선택하실 수 있습니다.
		</p>
		<div class="editor_box" style="margin:15px 0; display:<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/lms") == true) ? "" : "none"?>;">
			<div id="div_smart_url" class="url_info" style="margin: 10px 0px 0px 0px;"><?=$psd_url?></div>
			<button class="btn_link2" onClick="linkview();">링크확인</button>
		</div>
	</div>
	<div class="editor_box" id="div_editor_box" style="margin:15px 0; display:none;">
		<div style="display:<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/lms") == true) ? "" : "none"?>;">
			<div class="url_info" style="margin: 0px 0px 10px 0px;"><?=$dhnl_url?></div>
			<button class="btn_link2" onClick="linkview();">링크확인</button>
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
					placeholder: '내용을 입력하세요.',	//placeholder 설정
					toolbar: [
						['fontsize', ['fontsize']],
						['style', ['bold', 'italic', 'underline','strikethrough']],
						['color', ['forecolor','color']],
						['insert',['picture']]
					],
					callbacks: {
						onImageUpload: function(image) {
							uploadImage(image[0]);
						}
					},
				});
				chkword_lms();
				$('#summernote').summernote('fontSize', 18);
				<? if(!empty($savemsg->ums)) { ?>
				$('#summernote').summernote('code', '<?=$savemsg->ums?>');
				<? } ?>
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
	</div>
</div>
<script>
	//링크확인
	function linkview(){
		var link_type = document.getElementById("link_type").value;
		//alert("link_type : "+ link_type);
		if(link_type == "editor"){ //에디터
			var url = document.getElementById("dhnl_url").value; //에디터 링크 URL
			var bizurl =  document.getElementById("biz_url").value;
			var html = $('#summernote').summernote('code');
			//alert("url : "+ url +", bizurl : "+ bizurl +", html : "+ html); return;
			if(html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){
				alert("에디터에 내용을 입력하세요.");
				return;
			}else{
				//에디터 전단내용 저장
				$.ajax({
					url: "/dhnbiz/sender/talk/ums_save",
					type: "POST",
					data: {"bizurl" : bizurl, "html" : html, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {}
				});
				editor_preview(url); //에디터 미리보기
			}
		}else if(link_type == "self"){ //직접입력
			//alert("link_type : "+ link_type);
			var btn_url = "";
			var btn_no = 0;
			$("div[name='field-data']").each(function () {
				btn_no++;
				//alert("btn_no : "+ btn_no);
				var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
				if (btn_type != undefined) {
					var focus;
					btn_url = $(this).find(document.getElementsByName("btn_url1")).val() || "";
					if (btn_url == "" || !checkURL(btn_url)) {
						check = false;
						focus = $(this).find(document.getElementsByName("btn_url1"));
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
			});
			//alert("btn_url : "+ btn_url);
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
</script>
<!-- 스마트전단 불러오기 Modal -->
<div id="modal_smart" class="dh_modal">
  <!-- Modal content -->
  <div class="modal-content md_width970"> <span id="close_smart" class="dh_close">&times;</span>
    <p class="modal-tit t_al_left">
      스마트전단 목록
      <span class="btn_smart"><a href="/pop/screen/write">스마트전단 만들기</a></span>
    </p>
    <ul id="id_smart_list"><?//스마트전단 리스트 영역?>
    </ul>
    <div class="page_cen" id="id_pagination">
    </div><!--//pagination-->
  </div>
</div>
<script>
	//스마트전단 불러오기
	var modal_smart = document.getElementById("modal_smart");
	function smart_page(page){
		//스마트전단 데이타 조회
		$("#id_smart_list").html("");
		$("#id_pagination").html("");
		$.ajax({
			url: "/pop/screen/smart_data",
			type: "POST",
			data: {"per_page" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				var html = "";
				$.each(json, function(key, value){
					var psd_code = value.psd_code; //전단코드
					var tem_imgpath = value.tem_imgpath; //템플릿 이미지
					var psd_title = value.psd_title; //전단제목
					var psd_date = value.psd_date; //행사기간
					var psd_credt = value.psd_credt; //등록일자
					if(psd_code != "" && psd_code != undefined){
						html += "<li>";
						html += "  <div class=\"tem_img\">";
						html += "    <div id=\"pre_smart_bg\" onClick=\"smart_choice('"+ psd_code +"');\" class=\"preview_bg\" style=\"background:url('"+ tem_imgpath +"') no-repeat; background-size:100%; background-position:0 50px; cursor:pointer;\"></div>"; //템플릿 이미지
						html += "    <div class=\"pre_box1\">";
						html += "      <p id=\"pre_wl_tit\" class=\"pre_tit\">"+ psd_title +"</p>"; //전단제목
						html += "      <p id=\"pre_wl_date\" class=\"pre_date\">"+ psd_date +"</p>"; //행사기간
						html += "    </div>";
						html += "  </div>";
						html += "  <p class=\"tem_text\">";
						html += "    <span>"+ psd_credt +"</span>"; //등록일자
						html += "    <button type=\"button\" onClick=\"smart_choice('"+ psd_code +"');\"><i class=\"xi-check-circle-o\"></i> 전단선택</button>";
						html += "  </p>";
						html += "</li>";
					}
				});
				$("#id_smart_list").append(html);
				$("#id_pagination").append(json.page_html);
			}
		});

		//open the modal
		modal_smart.style.display = "block";

		//close the modal
		var close = document.getElementById("close_smart");
		close.onclick = function() {
			modal_smart.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal_smart) {
				modal_smart.style.display = "none";
			}
		}
	}
</script>