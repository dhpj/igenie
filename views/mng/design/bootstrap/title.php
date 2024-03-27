<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu5.php');
?>
<!-- //3차 메뉴 -->
<div class="wrap_mng">
  <div class="tab_st1">
    <button class="tablinks" onclick="openBox(event, 'Box1')" id="defaultOpen">이미지형 타이틀</button>
    <button class="tablinks" onclick="openBox(event, 'Box2')">텍스트형 타이틀</button>
    <button class="tablinks" onclick="openBox(event, 'Box4')">커스텀형 타이틀</button>
    <button class="tablinks" onclick="openBox(event, 'Box3')">뱃지이미지</button>
  </div>

  <div id="Box1" class="list_title_img tabcontent">
	  <ul class="img_list" id="title_img_list">
		<? foreach($data_img_list as $r) {
        // 직접입력의 경우 제거
        if($r->tit_type_self != "S"){?>
      		<li id="<?=$r->tit_id?>" class="img_print">
      			<div class="register_img_wrap">
                    <span class="num"><?=$r->tit_id?></span><br/>
                    <div style="width:112px;text-align:center;"><?=$r->tit_text_info?></div>
      			<img src="<?=$r->tit_imgpath?>" alt="">
            <button class="mod_btn" onclick="title_mod('<?=$r->tit_id?>', '<?=$r->tit_imgpath?>', '<?=$r->tit_type?>','<?=$r->tit_text_info?>');">수정</button>
      			<button value="" class="del_btn" onClick="title_del('<?=$r->tit_id?>');">삭제</button>
      			</div>
      		</li>
		<?  }
       } ?>
	  </ul>
	</div>

    <div id="Box4" class="list_title_img tabcontent">
  	  <ul class="img_cus_list" id="title_cus_img_list">
  		<? foreach($data_cus_img_list as $r) {
          // 직접입력의 경우 제거
          if($r->tit_type_self != "S"){?>
        		<li id="<?=$r->tit_id?>" class="img_print">
        			<div class="register_img_wrap">
                      <span class="num"><?=$r->tit_id?></span><br/>
                      <div style="width:112px;text-align:center;"><?=$r->tit_text_info?></div>
        			<img src="<?=$r->tit_imgpath?>" alt="">
              <button class="mod_btn" onclick="title_mod('<?=$r->tit_id?>', '<?=$r->tit_imgpath?>', '<?=$r->tit_type?>','<?=$r->tit_text_info?>');">수정</button>
        			<button value="" class="del_btn" onClick="title_del('<?=$r->tit_id?>');">삭제</button>
        			</div>
        		</li>
  		<?  }
         } ?>
  	  </ul>
  	</div>

  <div id="Box2" class="list_title_img tabcontent">
	  <ul class="text_list">
		<? foreach($data_text_list as $r) {
      // 직접입력의 경우 제거
        if($r->tit_type_self != "S"){?>
      		<li id="<?=$r->tit_id?>" class="img_print">
      			<div class="register_img_wrap">
                    <span class="num"><?=$r->tit_id?></span><br/>
                    <div style="width:112px;text-align:center;"><?=$r->tit_text_info?></div>
      			<img src="<?=$r->tit_imgpath?>" alt="">
            <button class="mod_btn" onClick="title_mod('<?=$r->tit_id?>', '<?=$r->tit_imgpath?>', '<?=$r->tit_type?>','<?=$r->tit_text_info?>');">수정</button>
      			<button value="" class="del_btn" onClick="title_del('<?=$r->tit_id?>');">삭제</button>
      			</div>
      		</li>
    <? }
      } ?>
	  </ul>
	</div>

  <div id="Box3" class="list_badge_img tabcontent">
	  <ul class="text_list">
		<? foreach($data_badge_list as $r) { ?>
		<li id="<?=$r->tit_id?>" class="img_print">
			<div class="register_img_wrap">
                <span class="num"><?=$r->tit_id?></span>
			<img src="<?=$r->tit_imgpath?>" alt="">
			<button value="" class="del_btn" onClick="title_del('<?=$r->tit_id?>');">X</button>
			</div>
		</li>
		<? } ?>
	  </ul>
  </div>
	<div class="title_add">
		<ul>
		  <li>
			 <span class="tit">타이틀 이미지 등록</span>
			 <div class="checks">
			   <input type="radio" name="tit_type" value="I" id="tit_type_I" checked>
			   <label for="tit_type_I">이미지형 타이틀</label>
			   <input type="radio" name="tit_type" value="T" id="tit_type_T">
			   <label for="tit_type_T">텍스트형 타이틀</label>
               <input type="radio" name="tit_type" value="C" id="tit_type_C">
			   <label for="tit_type_C">커스텀형 타이틀</label>
			   <input type="radio" name="tit_type" value="B" id="tit_type_B">
			   <label for="tit_type_B">뱃지이미지</label>
			 </div>
			 <div class="text"><label for="img_file" class="templet_img_box"><div id="div_preview"><img id="img_preview" style="display:block;width:100%"></div></label></div>
			 <input type="hidden" id="tit_id">
			 <input type="hidden" id="tit_md">
			 <input type="file" title="이미지 파일" name="img_file" id="img_file" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
		  </li>
      <!-- 타이틀 정보입력 -->
        <li id="tit_input">
          <span class="tit mg_t20">타이틀 정보입력</span>
          <div class="text">
            <input type="text" id="tit_text_info">
          </div>
        </li>
        <li id="tit_cus_input1">
          <span class="tit mg_t20">배경색</span>
          <div class="text">
            <input type="text" id="tit_bgcolor" placeholder="#eeeeee">
          </div>
        </li>
        <li id="tit_cus_input2" style="display:none;">
            <span class="tit mg_t20">글꼴</span>
            <div class="text">
              <input type="text" id="tit_txcolor" placeholder="#ffffff">
              <select class="mg_l20" id="tit_align">
                    <option value="C" selected>가운데</option>
                    <option value="L">왼쪽</option>
                    <option value="R">오른쪽</option>
              </select>
            </div>
        </li>
		</ul>
	</div>
	<div class="btn_al_cen mg_t20 mg_b50">
	  <button type="button" class="btn_st1" onClick="title_save();"><i class="material-icons">done</i> 등록</button>
	  <button type="button" class="btn_st1" onClick="cancel();"><i class="material-icons">clear</i> 취소</button>
	</div>
  </div>
</div>
<script>
//탭박스 추가
function openBox(evt, BoxName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(BoxName).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();

	//이미지형 타이틀 순서변경
	$(".img_list").sortable({
		stop: function(event, ui) {
			var seq = [];
			$(".img_list > li.img_print").each(function(t) {
				//alert("id : "+ $(this).attr("id"));
				seq.push($(this).attr("id"));
			});
			//alert("seq : "+ seq);
			$.ajax({url:"/mng/design/title_sort",
				data:{"seq[]":seq, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				type:"post",
				success:function(json) {
					//console.log('완료');
					showSnackbar("정렬순서가 변경 되었습니다.", 1500);
				}
			});
		}
	});

    $(".img_cus_list").sortable({
		stop: function(event, ui) {
			var seq = [];
			$(".img_cus_list > li.img_print").each(function(t) {
				//alert("id : "+ $(this).attr("id"));
				seq.push($(this).attr("id"));
			});
			//alert("seq : "+ seq);
			$.ajax({url:"/mng/design/title_sort",
				data:{"seq[]":seq, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				type:"post",
				success:function(json) {
					//console.log('완료');
					showSnackbar("정렬순서가 변경 되었습니다.", 1500);
				}
			});
		}
	});

	//텍스트형 타이틀 순서변경
	$(".text_list").sortable({
		stop: function(event, ui) {
			var seq = [];
			$(".text_list > li.img_print").each(function(t) {
				//alert("id : "+ $(this).attr("id"));
				seq.push($(this).attr("id"));
			});
			//alert("seq : "+ seq);
			$.ajax({url:"/mng/design/title_sort",
				data:{"seq[]":seq, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				type:"post",
				success:function(json) {
					//console.log('완료');
					showSnackbar("정렬순서가 변경 되었습니다.", 1500);
				}
			});
		}
	});

	//배경 이미지 초기화
	function remove_img(){
		$("#img_preview").attr("src", "");
		$("#img_preview").css("display", "none");
		$("#div_preview").css({"background":"url('')"});
		$("#div_preview").addClass("templet_img_box2");
	}

	//이미지 선택 클릭시
	$("#img_file").change(function() {
		//alert("#img_file_change");
		if(this.value.length > 0) {
			//alert("this.value : "+ this.value);
			if (this.files && this.files[0]) {
				remove_img();
				readURL(this, "div_preview");
			}
		}
	});

	//이미지 경로 세팅
	function readURL(input, divid) {
		//alert("readURL(input, divid) > input.value : "+ input.value +", divid : "+ divid);
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#"+divid).css({"background":"url(" + e.target.result + ")"});
				//$("#"+divid).css({"background-size":"contain"});
				//$("#"+divid).css({"background-repeat":"repeat-x"});
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
  //뱃지이미지 라디오버튼 클릭이벤트
  $("input:radio[name='tit_type']").click(function(){

    if($("input[name='tit_type']:checked").val() == "B"){
      $("#tit_input").hide();
      $("#tit_cus_input1").hide();
      $("#tit_cus_input2").hide();

    }else if($("input[name='tit_type']:checked").val() == "C"){
      $("#tit_input").show();
      $("#tit_cus_input1").show();
      $("#tit_cus_input2").show();

    }else{
        $("#tit_input").show();
        $("#tit_cus_input1").show();
        $("#tit_cus_input2").hide();
    }



  });
  //수정
  function title_mod(id, src, code, info){
    // alert("id : " + id + ", src : " + src + ", code : " + code +", info : "+ info);

    $("input:radio[name='tit_type']").removeAttr('checked');
    $("#tit_id").val(id); //일련번호
    $("#img_preview").attr("src", src);; //이미지 뷰
    $("#tit_text_info").val(info); //텍스트 정보
    $("input:radio[name='tit_type']:radio[value='"+code+"']").prop('checked', true);
    $("#tit_text_info").focus(); //텍스트입력 포커스
  }


	//타이틀관리 저장
	var request = new XMLHttpRequest();
	function title_save(){
		var formData = new FormData();
		var tit_id = $("#tit_id").val(); //타이틀번호
		var tit_md = $("#tit_md").val(); //모드
		var tit_type = $(":input:radio[name=tit_type]:checked").val(); //구분(I.이미지형, T.텍스트형)
        var tit_align = $("#tit_align").val();
        var tit_bgcolor = $("#tit_bgcolor").val();
        var tit_txcolor = $("#tit_txcolor").val();
		var imgfile = $("#img_file")[0].files[0]; //이미지
    var tit_text_info = $("#tit_text_info").val(); // 타이틀 정보
		//alert("tit_id : "+ tit_id +", tit_md : "+ tit_md +", imgfile : "+ imgfile); return;
		if(tit_id =="" && (!(imgfile) || imgfile == undefined)){
			alert("이미지를 유첨하세요.");
			return;
		}
    if(tit_text_info == ""&&tit_type!="B"){
      alert("타이틀정보를 입력하세요.")
      return;
    }
		//alert("OK"); return;
		// if(tit_md == "M"){ //수정의 경우
		// 	if(!confirm("수정 하시겠습니까?")){
		// 		return;
		// 	}
		// }
		formData.append("tit_id", tit_id); //타이틀번호
		formData.append("tit_type", tit_type); //구분(I.이미지형, T.텍스트형)
        formData.append("tit_bgcolor", tit_bgcolor);
        formData.append("tit_txcolor", tit_txcolor);
        formData.append("tit_align", tit_align);
		formData.append("imgfile", imgfile); //이미지
        formData.append("tit_text_info", tit_text_info); //타이틀 텍스트 정보
		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		request.onreadystatechange = callbackF;
		request.open("POST", "/mng/design/title_save");
		request.send(formData);
	}

	function callbackF() {
		//console.log(request.responseText);
		if(request.readyState  == 4) {
			var obj = JSON.parse(request.responseText);
			if(obj.code == "0") { //성공
				showSnackbar("저장 되었습니다.", 1500);
				setTimeout(function() {
					location.reload();
				}, 1000); //1초 지연
			} else { //오류
				alert("오류가 발생하였습니다.");
				return;
			}
		}
	}

	//타이틀관리 삭제
	function title_del(tit_id){
		if(confirm("삭제 하시겠습니까?")){
			$.ajax({
				url: "/mng/design/title_del",
				type: "POST",
				data: {"tit_id":tit_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					showSnackbar("삭제 되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
				}
			});
		}
	}

	//취소
	function cancel(){
		location.reload();
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
