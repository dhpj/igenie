<script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
<script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script>
<head>
<meta http-equiv="Expires" content="0"/>
<meta http-equiv="Pragma" content="no-cache"/>
</head>
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";
//-->
</script>
<input type="hidden" id="navigateURL" value="" />
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<!-- 타이틀 영역
<div class="tit_wrap">
	쿠폰 등록
</div>
타이틀 영역 END -->
<form action="/biz/coupon/write" method="post" id="sendForm" name="sendForm" onsubmit="return check_form();" enctype="multipart/form-data">
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
<input type='hidden' name='sms_sender' id='sms_sender' value='<?=$tpl->spf_sms_callback?>' />
<input type='hidden' name='hidden_cont' id='hidden_cont' value='<?=$tpl->tpl_contents?>' />
<input type='hidden' name='tpl_button' id='tpl_button' value='<?=$tpl->tpl_button?>' />
<input type='hidden' name='cc_idx' id='cc_idx' value='<?=$tpl->cc_idx?>' />
<input type='hidden' name='ud_cc_tpl_code' id='ud_cc_tpl_code' value='<?=$tpl->cc_tpl_code?>' />
<input type='hidden' name='ud_cc_tpl_id' id='ud_cc_tpl_id' value='<?=$tpl->cc_tpl_id?>' />
<input type='hidden' name='actions' id='id_actions'  value='coupon_save' />
<input type='hidden' id='couponwrite' value='YES' />
<input type="hidden" name="cc_type" value="AT">
<div id="mArticle">
	    <!--div class="snb_nav" style="margin-bottom: -1px; margin-left: 1px">
        <ul>
            <li class="active"><a href="/biz/coupon/write">알림톡 쿠폰</a></li>
            <li><a href="/biz/coupon/writeft">친구톡 쿠폰</a></li>
        </ul>
    	</div-->
	<div class="form_section">
		<div class="inner_tit">
			<? if($resultcnt->total == 0) {?>
				<h3>타겟쿠폰 작성하기</h3>
			<? } else {  ?>
				<!--<a href="javascript:open_result_list();" class="btn md yellow"><span class="material-icons" style="vertical-align: middle;">search</span>당첨자 조회</a> -->
				<h3>타겟쿠폰 작성하기</h3>
			<? }  ?>
		</div>
		<div class="inner_content preview_info">
			<div id="send_template_content"></div>
		</div>
	</div>
	<p class="noted2">
		<i class="xi-error-o"></i> 쿠폰저장을 클릭하시면 <span>수정이 불가</span>합니다. 발송 전 추가 수정을 원하실 경우 <span>임시저장</span>을 이용해주세요.
	</p>
		<div class="btn_box_630">
		<button type="button" onclick="coupon_close()" class="btn_st2">목록으로</button>
		<button type="submit" onclick="coupon_save()" class="btn_st1" style="<? echo ($rs->cc_status=='P')?"display:none":""; ?>">임시저장</button>
		<? if($rs->cc_status == 'P') { ?>
			<? if(cdate("Y-m-d") > $rs->cc_end_date) { ?>
				<button type="button" class="btn_st1" disabled>기간만료</button>
			<? } else {?>
				<button type="button" class="btn_st4" onclick="go_send('<?=$rs->cc_tpl_id?>', '<?=$rs->profile_key?>', '<?=$rs->cc_idx?>')">발송하기</button>
			<? } ?>
		<? } else {?>
			<button type="submit" onclick="coupon_proc()" class="btn_st5">쿠폰저장</button>
		<? } ?>
		</div>
</div>
</form>

<!--템플릿 선택-->
<!--div class="modal select fade" id="template_select" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg select-dialog" id="modal">
		<div class="modal-content">
			<h4 class="modal-title" align="center">템플릿 선택하기</h4>
			<div class="modal-body select-body">
				<div class="widget-content" id="template_list">
				</div>
				<div align="center">
					<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
					<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_template();">확인</button>
				</div>
			</div>
		</div>
	</div>
</div-->

<div class="modal select fade" id="template_select" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" id="modal" data-keyboard="false" data-backdrop="static">
		<div class="modal-content alim_templet">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="widget-content" id="template_list"></div>
		</div>
	</div>
</div>

<div class="modal select fade" id="IMG_select" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" id="modal">
		<div class="modal-content">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="modal_title">이미지 선택하기</div>
			<div class="modal-body">
				<div class="widget-content" id="template_list"></div>
				<div class="modal_bottom">
					<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_template();">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--  이미지 선택 모달 -->
<div id="image_list_modal" class="dh_modal">
  <!-- Modal content -->
  <div class="modal-content md_width970">
	  <span class="dh_close" onClick="image_list_close();">&times;</span>
	  <p class="modal-tit t_al_left">쿠폰 이미지 관리</p>
		<button class="btn_friimg" type="button" onclick="javascript:click_bntimage()"><i class="material-icons">insert_photo</i> 이미지추가</button>
		<input type="file" name="image_file" id="image_file" accept="image/*" value="upload" style="display: none;" class="upload-hidden" accept="image/jpeg, image/png" onchange="img_size();">
		<p class="modal_info">
			* 이미지 권장 사이즈 : <span>720px X 720px</span> / 지원 파일형식 및 크기 : <span>jpg, png</span> / 최대용량 : <span>500KB</span>
			<br  />* 이미지 제한 사이즈 : 가로 500px 미만, 가로:세로 비율이 2:1 미만, 3:4 초과시 업로드 불가
			<br  />* 이미지 편집이 어려우실 경우 <span>[알씨]를 설치</span>(<a href="https://www.altools.co.kr" target="_blank">https://www.altools.co.kr</a>)하거나 <span>이미지 무료편집사이트</span>(<a href="https://www.iloveimg.com/ko" target="_blank">https://www.iloveimg.com/ko</a>)를 이용해보세요~
		</p>
		<ul class="frided_imglist" id="image_list_content"><? //친구톡 이미지 리스트 ?>
		</ul>
		<div class="page_cen" id="image_list_pagination" style="margin-top:10px;"><? //친구톡 이미지 페이징 ?>
		</div>
	  </div>
</div>
<!-- 당첨자 조회 모달 -->
<div class="modal select fade" id="myModalUserResultlist" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="modal_title">결과조회*</div>
			<div class="modal-body">
				<div class="search_wrap" style="text-align: right;">
					<input type="text" id="search_phn" name="search_phn" placeholder="전화번호를 입력해 주세요" onKeypress="if(event.keyCode==13){ open_page_result(1); }">
					<button type="button" class="btn md yellow" style="margin-left: 10px" onclick="open_page_result(1)">검색</button>
				</div>
				<div class="content" id="modal_user_result_list"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#nav li.nav10").addClass("current open");

	//스크롤 올라가는 현상 방지
	function scroll_prevent() {
		window.onscroll = function(){
		  var arr = document.getElementsByTagName('textarea');
			for( var i = 0 ; i < arr.length ; i ++  ){
				try { arr[i].blur(); }catch(e){}
			}
		}
	}

	//발송하기
    function go_send(tmp, pro, idx) {

        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/biz/sender/send/coupon");

		var cfrsField = document.createElement("input");
		cfrsField.setAttribute("type", "hidden");
		cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(cfrsField);

        var tmpField = document.createElement("input");
        tmpField.setAttribute("type", "hidden");
        tmpField.setAttribute("name", "tmp_code");
        tmpField.setAttribute("value", tmp);
        form.appendChild(tmpField);

        var proField = document.createElement("input");
        proField.setAttribute("type", "hidden");
        proField.setAttribute("name", "tmp_profile");
        proField.setAttribute("value", pro);
        form.appendChild(proField);

        var idxField = document.createElement("input");
        idxField.setAttribute("type", "hidden");
        idxField.setAttribute("name", "iscoupon");
        idxField.setAttribute("value", idx);
        form.appendChild(idxField);

        form.submit();
    }

	function coupon_save() {
		$('#id_actions').val('coupon_save');
	}

	function coupon_proc() {
		$('#id_actions').val('coupon_proc');
	}

	function coupon_close() {
		//$('#id_actions').val('coupon_close');
		location.href = "/biz/coupon";
	}

 	//당첨자 조회
	function open_result_list() {
 		$("#myModalUserResultlist").modal({backdrop: 'static'});
 		$("#myModalUserResultlist").on('shown.bs.modal', function () {
 			$('.uniform').uniform();
 			$('select.select2').select2();
 		});
 		$('#myModalUserResultlist').unbind("keyup").keyup(function (e) {
 			var code = e.which;
 			if (code == 27) {
 				$(".btn-default.dismiss").click();
 			}
 		});
 		open_page_resultM('1');
 	}
    function open_page_resultM(page) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';
		$('#myModalUserResultlist .content').html('').load(
			"/biz/coupon/coupon_result",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				'page': page,
				'coupon_id':'<?=$tpl->cc_idx?>'
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			}
		);
    }

	//확인모달 엔터 누르면 닫기
	$("#myModal").unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			$(".enter").click();
		}
	});



	//템플릿 선택 - 검색 포커스일때, 아닐때 엔터
	$("#template_select").unbind("keyup").keyup(function (e) {
		var code = e.which;
		if ($("#search").is(":focus")) {
			if(code == 13) {
				search_enter();
			}
		} else{
			if(code == 13) {
				select_template();
			}
		}
	});

	//템플릿 선택 모달
	var templi = '';
	if(templi!="") {
		var count = '';
		if('' == "none") {
			$('#template_danger').modal('show');
		} else {
			$('#template_select').modal('show');
		}
	}

	//템플릿 선택시 CSS 변경
	var selected="";
	var selected_profile="";
	$(".scrolltbody tbody tr").click(function() {
		$(".scrolltbody tbody tr").css("background-color", "white");
		$(".scrolltbody tbody tr").css("color", "dimgrey");
		$(this).css("background-color", "#4d7496");
		$(this).css("color", "white");
		selected = $(this).find(".tmp_code").text();
		selected_profile = $(this).find(".tmp_profile").text();
	});

	//미리보기 - 클릭시 그림자 효과 없애기
	$("#text").click(function() {
		$(this).css("box-shadow","none");
	});


	function view_preview() {
		$("#text").html("");
		$("#image").remove();
		//$("#btn_preview1").remove();
		//$("#pre_title").remove();

		templi_preview(document.getElementById('templi_cont'));
		//scroll_prevent();
		templi_chk();
		link_preview();

//		$("#image").remove();
//		$("#btn_preview1").remove();
//		$("#pre_title").remove();

//		templi_preview($("#templi_cont").get(0));
//		scroll_prevent();
//		templi_chk();
//		link_preview();
	}

	function view_result() {
		if($("#img_link").val()=='') {
			alert("이미지를 선택 하세요.");
		} else {
			$("#text").html("");
			//$("#image").remove();
			//$("#btn_preview1").remove();
			//$("#pre_title").remove();

    		re_templi_preview($("#id_cc_memo").get(0));
			readImage($("#img_link").val());
    		re_link_preview();
    		scroll_prevent();
    		templi_chk();
		}
	}

    //글자 수 체크
	function templi_chk() {
		<?if ($tpl) {?>
		var limit_length = 1000;
		var msg_length = $("#templi_cont").val().length;
		if (msg_length <= limit_length) {
			$("#type_num").html(msg_length);
		//} else if (msg_length > limit_length) {
		//	$(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
		//	$("#myModal").modal({backdrop: 'static'});
		//	$('#myModal').on('hidden.bs.modal', function () {
		////		$("#templi_cont").focus();
		//	});
		//	var cont = $("#templi_cont").val();
		//	var cont_slice = cont.slice(0,1000);
		//	$("#templi_cont").val(cont_slice);
		//	$("#type_num").html(1000);
		//	 return;
		} else {
			$("#type_num").html(msg_length);
		}
		<?}?>
	}

	//미리보기 - 템플릿내용
	function templi_preview(obj) {
		<?if($tpl) {?>

		var dumyTempli_cont = obj.value.replace(/\n/g, "<br>");
		var temp = dumyTempli_cont.split("#{");
		var returnTempli_cont = dumyTempli_cont;
		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = temp[i];
			} else {
				var varsplit = temp[i].split("}")
				var varName = "#{" + varsplit[0] + "}";

				var var_name = $("input[name^='var_name']")[(i-1)].value;
				//var var_name = $("textarea[name=var_name]")[(i-1)].value;
				//alert(var_name);
				if (var_name == "") {
					var_name = varName;
				}
				returnTempli_cont = returnTempli_cont + var_name.replace(/(\n|\r\n)/g, '<br>');
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}

		returnTempli_cont = replaceKakaoEmoticon(returnTempli_cont, 20);



		$("#text").html(returnTempli_cont);

		//alert(obj.value);
//		$("#text").val(obj.value);
//		var height = $("#text").prop("scrollHeight");
//		if (height < 468) {
//			$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
//		} else {
//			var message = $("#text").val();
//			var height = $("#text").prop("scrollHeight");
//			if (message == "") {
//				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
//			} else {
//				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
//				$("#templi_cont").keyup(function() {
//					$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
//				});
//			}
//		}
		<?}?>
	}

	function replaceKakaoEmoticon(msg_text, emoticonSize)
	{
		if (!emoticonSize) emoticonSize = 30;
		msg_text = msg_text.replace(/\(미소\)/g, "<img src=\"/images/kakaoimg/kakaoimg_001.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(윙크\)/g, "<img src=\"/images/kakaoimg/kakaoimg_002.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(방긋\)/g, "<img src=\"/images/kakaoimg/kakaoimg_003.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(반함\)/g, "<img src=\"/images/kakaoimg/kakaoimg_004.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(눈물\)/g, "<img src=\"/images/kakaoimg/kakaoimg_005.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(절규\)/g, "<img src=\"/images/kakaoimg/kakaoimg_006.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(크크\)/g, "<img src=\"/images/kakaoimg/kakaoimg_007.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(메롱\)/g, "<img src=\"/images/kakaoimg/kakaoimg_008.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(잘자\)/g, "<img src=\"/images/kakaoimg/kakaoimg_009.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(잘난척\)/g, "<img src=\"/images/kakaoimg/kakaoimg_010.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(헤롱\)/g, "<img src=\"/images/kakaoimg/kakaoimg_011.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(놀람\)/g, "<img src=\"/images/kakaoimg/kakaoimg_012.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(아픔\)/g, "<img src=\"/images/kakaoimg/kakaoimg_013.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(당황\)/g, "<img src=\"/images/kakaoimg/kakaoimg_014.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(풍선껌\)/g, "<img src=\"/images/kakaoimg/kakaoimg_015.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(버럭\)/g, "<img src=\"/images/kakaoimg/kakaoimg_016.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(부끄\)/g, "<img src=\"/images/kakaoimg/kakaoimg_017.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(궁금\)/g, "<img src=\"/images/kakaoimg/kakaoimg_018.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(흡족\)/g, "<img src=\"/images/kakaoimg/kakaoimg_019.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(깜찍\)/g, "<img src=\"/images/kakaoimg/kakaoimg_020.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(으으\)/g, "<img src=\"/images/kakaoimg/kakaoimg_021.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(민망\)/g, "<img src=\"/images/kakaoimg/kakaoimg_022.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(곤란\)/g, "<img src=\"/images/kakaoimg/kakaoimg_023.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(잠\)/g, "<img src=\"/images/kakaoimg/kakaoimg_024.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(행복\)/g, "<img src=\"/images/kakaoimg/kakaoimg_025.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(안도\)/g, "<img src=\"/images/kakaoimg/kakaoimg_026.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(우웩\)/g, "<img src=\"/images/kakaoimg/kakaoimg_027.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(외계인\)/g, "<img src=\"/images/kakaoimg/kakaoimg_028.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(외계인녀\)/g, "<img src=\"/images/kakaoimg/kakaoimg_029.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(공포\)/g, "<img src=\"/images/kakaoimg/kakaoimg_030.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(근심\)/g, "<img src=\"/images/kakaoimg/kakaoimg_031.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(악마\)/g, "<img src=\"/images/kakaoimg/kakaoimg_032.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(썩소\)/g, "<img src=\"/images/kakaoimg/kakaoimg_033.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(쳇\)/g, "<img src=\"/images/kakaoimg/kakaoimg_034.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(야호\)/g, "<img src=\"/images/kakaoimg/kakaoimg_035.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(좌절\)/g, "<img src=\"/images/kakaoimg/kakaoimg_036.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(삐침\)/g, "<img src=\"/images/kakaoimg/kakaoimg_037.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(하트\)/g, "<img src=\"/images/kakaoimg/kakaoimg_038.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(실연\)/g, "<img src=\"/images/kakaoimg/kakaoimg_039.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(별\)/g, "<img src=\"/images/kakaoimg/kakaoimg_040.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(브이\)/g, "<img src=\"/images/kakaoimg/kakaoimg_041.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(오케이\)/g, "<img src=\"/images/kakaoimg/kakaoimg_042.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(최고\)/g, "<img src=\"/images/kakaoimg/kakaoimg_043.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(최악\)/g, "<img src=\"/images/kakaoimg/kakaoimg_044.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(그만\)/g, "<img src=\"/images/kakaoimg/kakaoimg_045.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(땀\)/g, "<img src=\"/images/kakaoimg/kakaoimg_046.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(알약\)/g, "<img src=\"/images/kakaoimg/kakaoimg_047.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(밥\)/g, "<img src=\"/images/kakaoimg/kakaoimg_048.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(커피\)/g, "<img src=\"/images/kakaoimg/kakaoimg_049.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(맥주\)/g, "<img src=\"/images/kakaoimg/kakaoimg_050.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(소주\)/g, "<img src=\"/images/kakaoimg/kakaoimg_051.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(와인\)/g, "<img src=\"/images/kakaoimg/kakaoimg_052.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(치킨\)/g, "<img src=\"/images/kakaoimg/kakaoimg_053.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(축하\)/g, "<img src=\"/images/kakaoimg/kakaoimg_054.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(음표\)/g, "<img src=\"/images/kakaoimg/kakaoimg_055.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(선물\)/g, "<img src=\"/images/kakaoimg/kakaoimg_056.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(케이크\)/g, "<img src=\"/images/kakaoimg/kakaoimg_057.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(촛불\)/g, "<img src=\"/images/kakaoimg/kakaoimg_058.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(컵케이크a\)/g, "<img src=\"/images/kakaoimg/kakaoimg_059.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(컵케이크b\)/g, "<img src=\"/images/kakaoimg/kakaoimg_060.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(해\)/g, "<img src=\"/images/kakaoimg/kakaoimg_061.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(구름\)/g, "<img src=\"/images/kakaoimg/kakaoimg_062.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(비\)/g, "<img src=\"/images/kakaoimg/kakaoimg_063.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(눈\)/g, "<img src=\"/images/kakaoimg/kakaoimg_064.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(똥\)/g, "<img src=\"/images/kakaoimg/kakaoimg_065.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(근조\)/g, "<img src=\"/images/kakaoimg/kakaoimg_066.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(딸기\)/g, "<img src=\"/images/kakaoimg/kakaoimg_067.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(호박\)/g, "<img src=\"/images/kakaoimg/kakaoimg_068.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(입술\)/g, "<img src=\"/images/kakaoimg/kakaoimg_069.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(야옹\)/g, "<img src=\"/images/kakaoimg/kakaoimg_070.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(돈\)/g, "<img src=\"/images/kakaoimg/kakaoimg_071.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(담배\)/g, "<img src=\"/images/kakaoimg/kakaoimg_072.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(축구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_073.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(야구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_074.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(농구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_075.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(당구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_076.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(골프\)/g, "<img src=\"/images/kakaoimg/kakaoimg_077.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(카톡\)/g, "<img src=\"/images/kakaoimg/kakaoimg_078.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(꽃\)/g, "<img src=\"/images/kakaoimg/kakaoimg_079.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(총\)/g, "<img src=\"/images/kakaoimg/kakaoimg_080.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(크리스마스\)/g, "<img src=\"/images/kakaoimg/kakaoimg_081.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(콜\)/g, "<img src=\"/images/kakaoimg/kakaoimg_082.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		return msg_text;
	}

	//미리보기 - 버튼링크
	function link_preview() {
		<?if($tpl) {?>
		$("#text").css("margin-bottom", "10px");
		var buttons = '<?=$tpl->tpl_button?>';
		var btn = buttons.replace(/&quot;/gi, "\"");
		var btn_content = JSON.parse(btn);
		for (var i = 0; i < btn_content.length; i++) {
		  var no = i + 1;
		  var p = '<div id="btn_preview' + no + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
					 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly" ' +
					 'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
					 '>' + btn_content[i]["name"] + '</p></div>';

		  if (i == 0) {
			  var previewText = $("#text").html() + "<br><br>" + p;
			  $("#text").html(previewText);
		  } else {
			   $("#btn_preview" + i).after(p);
		  }
		}
		<?}?>
	}

	//미리보기 - 템플릿내용
	function re_templi_preview(obj) {
		<?if($tpl) {?>
		//var returnTempli_cont = obj.value;
		//returnTempli_cont = replaceKakaoEmoticon(returnTempli_cont, 20);
		var returnTempli_cont = "";

		var title = "<div id='pre_title' style='padding-bottom: 10px;'><strong style='margin-top: 1px;display: block; padding: 2px 9px; border: 1px solid transparent; font-weight: normal; font-size: 16px; color: rgb(136,136,136);'>";
	    title = title + $("#id_cc_title").val() + "</strong>";
	    title = title +	"<li style='padding-bottom: 10px;display: block;padding: 0 10px;color: rgb(46,172,188);font-size: 12px;'>사용기간 : ";
	    title = title +	$("#id_cc_end_date").val() + "까지</li></div>";
	    title = title + '<div id="btn_preview" style="height: 200px;padding-bottom: 10px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;background-color:#ffd900;">' +
		 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly" ' +
		 'style="text-align: center !important; padding-top:10px !important; overflow:hidden;border:0;background-color:#ffd900;resize:none;cursor:default;"' +
		 '>친구 추가</p></div>';

		$("#text").html(title);

// 		$("#text").val(obj.value);
// 		var height = $("#text").prop("scrollHeight");
// 		if (height < 468) {
// 			$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
// 		} else {
// 			var message = $("#text").val();
// 			var height = $("#text").prop("scrollHeight");
// 			if (message == "") {
// 				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
// 			} else {
// 				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
// 				$("#templi_cont").keyup(function() {
// 					$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
// 				});
// 			}
// 		}
		<?}?>
	}

	//미리보기 - 버튼링크
	function re_link_preview() {
		<?if($tpl) {?>
		$("#text").css("margin-bottom", "10px");
		var returnTempli_cont = $("#id_cc_memo").val().replace(/(\n|\r\n)/g, '<br>');
		var buttons = '<?=$tpl->tpl_button?>';
		var btn = buttons.replace(/&quot;/gi, "\"");
		var btn_content = JSON.parse(btn);
		for (var i = 0; i < btn_content.length; i++) {
		  var no = i + 1;
		  var no = i + 1;
		  var p = '<div id="btn_preview' + no + '" style="height: 200px;padding-bottom: 10px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;background-color:#ffd900;">' +
					 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly" ' +
					 'style="text-align: center !important; padding-top:10px !important; overflow:hidden;border:0;background-color:#ffd900;resize:none;cursor:default;"' +
					 '>직원확인</p></div>';
		  if (i == 0) {

			  var previewText = $("#text").html() + "<br>" + p + "<br>";
			  $("#text").html(previewText + returnTempli_cont);
		  } else {
			  //$("#btn_preview" + i).after(p);
		  }
		}

// 		$("#text").css("margin-bottom", "10px");

//		var buttons = '<?=$tpl->tpl_button?>';
// 		var btn = buttons.replace(/&quot;/gi, "\"");
// 		var btn_content = JSON.parse(btn);


// 		$("#text").before(title);

// 		for (var i = 0; i < btn_content.length; i++) {
// 		  var no = i + 1;
// 		  var p = '<div id="btn_preview' + no + '" style="height: 200px;padding-bottom: 10px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
// 					 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly" ' +
// 					 'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
// 					 '>직원확인</p></div>';

// 		  if (i == 0) {
// 				$("#text").before(p);
// 		  } else {
// 				$("#btn_preview" + i).after(p);
// 		  }
// 		}


		<?}?>
	}

	//템플릿 선택 - 모달 확인
	function select_template() {
			selected = $("#template_list").find(".active").find(".tpl_id").val();
			selected_profile = $("#template_list").find(".active").find(".pf_key").val();
		if(selected=="") {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$("#myModal").on('hidden.bs.modal', function () {
				$("#template_select").focus();
			});
		} else {
			var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/coupon/write");
			var csrfField = document.createElement("input");
			csrfField.setAttribute("type", "hidden");
			csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(csrfField);
			var selectedField = document.createElement("input");
			selectedField.setAttribute("type", "hidden");
			selectedField.setAttribute("name", "tmp_code");
			selectedField.setAttribute("value", selected);
			form.appendChild(selectedField);
			var selProfileField = document.createElement("input");
			selProfileField.setAttribute("type", "hidden");
			selProfileField.setAttribute("name", "tmp_profile");
			selProfileField.setAttribute("value", selected_profile);
			form.appendChild(selProfileField);

			form.submit();
		}
	}

	//템플릿 선택 - 검색 후 엔터 누를 경우
	function search_enter() {
		$(document).unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$("#check").click();
			}
		});
	}

	//모달 검색
	function search() {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/template");
		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "search");
		selectedField.setAttribute("value", search);
		form.appendChild(selectedField);
		var selectedField2 = document.createElement("input");
		selectedField2.setAttribute("type", "hidden");
		selectedField2.setAttribute("name", "selectBox");
		selectedField2.setAttribute("value", selectBox);
		form.appendChild(selectedField2);
		form.submit();
	}

	//모달 페이징
	function page(page) {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/template");
		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page);
		form.appendChild(pageField);
		var searchField = document.createElement("input");
		searchField.setAttribute("type", "hidden");
		searchField.setAttribute("name", "search");
		searchField.setAttribute("value", search);
		form.appendChild(searchField);
		var selectField = document.createElement("input");
		selectField.setAttribute("type", "hidden");
		selectField.setAttribute("name", "selectBox");
		selectField.setAttribute("value", selectBox);
		form.appendChild(selectField);
		form.submit();
	}


	function search_sendbox() {
		try {
			open_page_sendbox(1);
		}catch(e){}
	}

	// 템플릿 선택 버튼
	function btnSelectTemplate() {
		var count = '';
		if('' == "none") {
			$('#template_danger').modal('show');
		} else {
			$("#template_select").modal({backdrop: 'static'});
			$('#template_select').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {

					$("#template_select").modal("hide");
				}
				else if ($("#search").is(":focus")) {
					if (code == 13) {
						search_enter(); // todo check
					}
				} else {
					if (code == 13) {
						select_profile();
					}
				}
			});
		}

		$('#template_select .widget-content').html('').load(
			"/biz/coupon/template",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"page": 1
			},
			function() {
				//$('#template_select').css({"overflow-y": "scroll"});
			}
		);
	}

	// 템플릿 선택 버튼
	function btnSelectImg() {
		var count = '';

		if('' == "none") {
			$('#template_danger').modal('show');
		} else {
			$("#IMG_select").modal({backdrop: 'static'});
			$('#IMG_select').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {

					$("#IMG_select").modal("hide");
				}
				else if ($("#search").is(":focus")) {
					if (code == 13) {
						search_enter(); // todo check
					}
				} else {
					if (code == 13) {
						select_profile();
					}
				}
			});
		}

		$('#IMG_select .widget-content').html('').load(
			"/biz/coupon/img_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"page": 1
			},
			function() {
				//$('#template_select').css({"overflow-y": "scroll"});
			}
		);
	}


	//템플릿 선택
	function template() {
		if("<?=$param['tmp_profile']?>" != "") {
			$("#myModalTemp").modal({backdrop: 'static'});
			$("#myModalTemp").unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".temp").click();
					} else if (code == 27) {
						$(".btn-default").click();
					}
				});
				$(".temp").click(function () {
					var form = document.createElement("form");
					document.body.appendChild(form);
					form.setAttribute("method", "post");
					form.setAttribute("action", "/biz/sender/send/template");
						  var csrfField = document.createElement("input");
						  csrfField.setAttribute("type", "hidden");
						  csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
						  csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
						  form.appendChild(csrfField);
					form.submit();
				});
		}else{
			var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/sender/send/template");
				var csrfField = document.createElement("input");
				csrfField.setAttribute("type", "hidden");
				csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
				form.appendChild(csrfField);
			form.submit();
		}
	}

	function insert_kakao_link(check){
		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;
		$.ajax({
			url: "/biz/short_url",
			type: "POST",
			data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", "long_url":long_url},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var short_url = "카톡친구추가 바랍니다!  " + json['short_url'];
				var content;
				var index;
				var cont = $("#lms").val();
				if (check.checked) {
					if (new RegExp(/무료수신거부/gi).test($("#lms").val())) {
						content = $("#lms").val().replace("무료수신거부", short_url + "\r무료수신거부");
						$("#lms").val(content).focus();
					} else {
						content = $("#lms").val().replace(cont, cont + "\r" + short_url);
						$("#lms").val(content).focus();
					}
					//lms 입력란 가변 처리

					resize_cont(document.getElementById("lms"));

				} else {
					if ((new RegExp(/무료수신거부/gi).test(cont))) {
						index = cont.indexOf("무료수신거부");
						var first = cont.slice(0, index - 1).replace(short_url, "");
						var last = cont.slice(index);
						$("#lms").val(first + last).focus();

					} else {
						index = cont.indexOf(short_url);
						$("#lms").val(cont.slice(0, index - 1)).focus();

					}
					if ($("#lms").prop("scrollHeight") < 412 && $("#lms").prop("scrollHeight") > 103) {
						$("#lms").css("height", "1px").css("height", ($("#lms").prop("scrollHeight") - 1) + "px");
					} else {
						resize_cont(document.getElementById("lms"));
					}

				}
			}
		});
	}

	//알림톡, lms 입력란 가변 처리
	function resize_cont(obj) {
		$(this).on('keyup', function(evt){
		  if(evt.keyCode == 8){
			if (obj.scrollHeight < 103){
				obj.style.height = "1px";
				obj.style.height = "103px";
			}
		  }
		});
		if (obj.scrollHeight <= 409 && obj.scrollHeight > 113 ) {
			obj.style.height = "1px";
			obj.style.height = (20 + obj.scrollHeight) + "px";
		}else if (obj.scrollHeight > 412){
			obj.style.height = "1px";
			obj.style.height = "412px";
		}else if (obj.scrollHeight <= 430 && obj.scrollHeight > 133) {
			obj.style.height = "1px";
			obj.style.height = (obj.scrollHeight-20) + "px";
		} else if (obj.scrollHeight < 103){
			obj.style.height = "1px";
			obj.style.height = "103px";
		}
		obj.focus();
	}

	//검색 조회
	// copy from customer_list
	function search_question(page) {
		open_page(page);
	}

	// copy from customer_list
	function open_page(page) {
		var type = $('#searchType').val() || 'all';
		var searchFor = $('#searchStr').val() || '';
		var searchGroup = $('#searchGroup').val() || '';
		var searchName = $('#searchName').val() || '';
		$('#myModalLoadCustomers .widget-content').html('').load(
			"/biz/customer/inc_lists",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_type": type,
				"search_for": searchFor,
				"search_group": searchGroup,
				"search_name": searchName,
				'page': page,
				'is_modal': true
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			}
		);
	}

	function check_form(){
		var err = '';
		var $err_obj = null;

		$('input.required').each(function() {
			if(err=='' && ($(this).val()=='' || $(this).val()==$(this).attr('placeholder'))) { err = $(this).attr('placeholder'); $err_obj = $(this); }
		});

		if($("#id_cc_title").val() == "") {
			alert("쿠폰명을 입력 하세요.");
			$("#id_cc_title").focus();
			return false;
		}
		var emptyVar = isEmptyVariable();
		//alert("emptyVar : "+ emptyVar); return false;
		if (emptyVar !== true) {
			alert("템플릿 "+ (emptyVar+1) +"번째 변수를 입력해주세요.");
			//$("textarea[name=var_name]")[emptyVar].focus();
			//$("input[name=var_name]")[emptyVar].focus();
			$("#var_name_"+ emptyVar).focus();
			return false;
		}
		if($("#id_cc_start_date").val() == "") {
			alert("기간(시작일)을 선택하세요.");
			$("#id_cc_start_date").focus();
			return false;
		}
		if($("#id_cc_end_date").val() == "") {
			alert("기간(종료일)을 선택하세요.");
			$("#id_cc_end_date").focus();
			return false;
		}
		if($("#id_cc_start_date").val() > $("#id_cc_end_date").val()) {
			alert("기간(종료일)을 잘못 선택하셨습니다.");
			$("#id_cc_end_date").focus();
			return false;
		}
		if($("#id_cc_coupon_qty").val() == "" || $("#id_cc_coupon_qty").val() == "0") {
			alert("쿠폰 발급수를 0개 이상 입력하세요.");
			$("#id_cc_coupon_qty").focus();
			return false;
		}
		var cc_rate = $(":input:radio[name=cc_rate]:checked").val(); //당첨확률
		//alert("cc_rate : "+ cc_rate); return false;
		if(cc_rate == undefined){
			alert("당첨활률을 선택하세요.");
			return false;
		}
		var id_actions = $("#id_actions").val();
		if(id_actions == "coupon_proc"){ //쿠폰저장의 경우
			if($("#img_link").val() == "" && $("#id_cc_memo").val() == "") {
				alert("당첨페이지 이미지 선택 또는 텍스트를 입력하세요.");
				$("#img_link").focus();
				return false;
			}
		}
		//
		//alert("check_form() OK"); return false;
		return true;
	}

	//이미지 선택시-에이젝스
	function imageSelect() {
		cw = screen.availWidth;     //화면 넓이
		ch = screen.availHeight;    //화면 높이
		sw = 720;    //띄울 창의 넓이
		sh = 630;    //띄울 창의 높이
		ml = (cw - sw) / 2;        //가운데 띄우기위한 창의 x위치
		mt = (ch - sh) / 2;         //가운데 띄우기위한 창의 y위치

		imgSelectBox = window.open("/biz/sender/images", 'tst', 'width=' + sw + ',height=' + sh + ',top=' + mt + ',left=' + ml + ',location=no, resizable=no');
	}

	function setImageValue(selctImgValue, selctImgLink) {
		$("#img_url").val(selctImgValue);

		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		$("input[name=img_link]").val(selctImgLink);

		show_pre_draw();
	}

	//미리보기 - 이미지
	function readImage(selctImgValue) {
		$("#image").remove();
		var image = "<img id='image' name='image' src='" + selctImgValue + "' style='width:100%; margin-bottom:5px;'/>";
		var text_temp = $("#text").html();

		$("#text").html(image + text_temp);
    }

	$(document).ready(function() {
		$('#send_template_content').html('').load(
			"/biz/coupon/select_template/<? if ($rs->cc_idx) { echo $rs->cc_idx; } else { echo 0; }?>",
			{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				tmp_code: "<?=$param['tmp_code']?>",
				tmp_profile: "<?=$param['tmp_profile']?>",
				sb_send: "<?=$param['sb_send']?>",
				sb_id: "<?=$param['sb_id']?>",
				sb_kind: "<?=$param['sb_kind']?>"
		   }, function() {

				templi_preview(document.getElementById('templi_cont'));
			   link_preview();
			   //templi_chk();
			   //bindKeyUpEvent();
		   }
		);
		$("#id_cc_start_date, #id_cc_end_date").datepicker({
			format:'yyyy.mm.dd'
		});
	});

	//템플릿 변수 체크
	function isEmptyVariable() {
		var returnCheck = true;
		var i = 0;
		//$("textarea[name^='var_name']").each(function(){
		$("input[name^='var_name']").each(function(){
			var var_name = $(this).val().trim();
			//alert("var_name["+ i +"] : "+ var_name);
			if(var_name == "") {
				returnCheck = i;
				return false;
			}
			i++;
		});
		//alert("returnCheck : "+ returnCheck);
		return returnCheck;
	}

	//카카오톡 이미지 모달창 오픈
	function image_list_open(page){
		//카카오톡 이미지 리스트 조회
		$.ajax({
			url: "/dhnbiz/sender/send/friend/ajax_image_list",
			type: "POST",
			data: {"per_page" : "8", "page" : page, "img_wide" : "C", "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json){
				var html = "";
				//alert("json.page_html : "+ json.page_html);
				$.each(json, function(key, value){
					var img_id = value.img_id; //이미지 일련번호
					var img_link = "http://<?=$_SERVER['HTTP_HOST']?>/pop/"+ value.img_filename; //이미지 파일명
					var img_url = value.img_url; //카카오 URL
					var del_url = encodeURI(value.img_filename +"|"+ img_id);
					if(img_id != undefined && img_url != undefined){
						html += "<li>";
						html += "	<p class=\"tem_img\" style=\"background-image: url('"+ img_url +"');cursor:pointer;\" onClick=\"image_choose('"+ img_url +"', '"+ img_link +"');\"></p>";
						html += "	<p class=\"tem_text\">";
						html += "	 <button type=\"button\" onClick=\"image_choose('"+ img_url +"', '"+ img_link +"');\"><i class=\"material-icons\">add</i>이미지선택</button>";
						html += "	 <button type=\"button\" onClick=\"delete_image('"+ del_url +"');\"><i class=\"material-icons\">remove</i>이미지삭제</button>";
						html += "	</p>";
						html += "</li>";
					}
				});
				$("#image_list_content").html(html);
				$("#image_list_pagination").html(json.page_html);
				//$("#image_list_pagination").html("<ul class=\"pagination\"><li class=\"active\"><a>1</a></li><li><a href=\"javascript:void(image_list_open('2'));\">2</a></li><li><a href=\"javascript:void(image_list_open('2'));\">&gt;</a></li></ul>");
			}
		});
		document.getElementById("image_list_modal").style.display = "block";
	}

	//카카오톡 이미지 모달창 > 이미지 선택
	function image_choose(img_url, img_link){
		//alert("img_url : "+ img_url +"\n"+ "img_link : "+ img_link);
		setImageValue(img_url, img_link);
		//document.getElementById("img_select").style.display = "none"; //이미지 선택 버튼
		image_list_close();
	}

	//카카오톡 이미지 모달창 닫기
	function image_list_close(){
		document.getElementById("image_list_modal").style.display = "none";
	}

	//카카오톡 이미지 모달창 닫기
	window.onclick = function(event) {
		if (event.target == document.getElementById("image_list_modal")) {
			document.getElementById("image_list_modal").style.display = "none";
		}
	}

	//이미지 업로드 버튼 클릭
	function click_bntimage() {
		$("#image_file").trigger("click");
	}

	//카카오톡 이미지 추가시 체크
	function img_size(){
		var img_file = $("#image_file")[0].files[0];
		var _URL = window.URL || window.webkitURL;
		var img = new Image();
		img.src = _URL.createObjectURL(img_file);
		img.onload = function() {
			var img_width = img.width;
			var img_height = img.height;
			var img_check = true;
			if (img_width/img_height < 0.75 && img_width/img_height > 2) { //이미지 사이즈 체크
				img_check = false;
			}
			upload(img_check);
		};
	}

	//파일 용량 및 확장자 확인 ->500KB제한
	function upload(check) {
		var thumbext = document.getElementById('image_file').value;
		//alert("upload > thumbext : "+ thumbext);
		var file_data = new FormData();
		file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		file_data.append("image_file", $("input[name=image_file]")[0].files[0]);
		file_data.append("img_wide", "C"); //이미지 구분(N.친구톡, W.와이드친구톡, C.쿠폰)
		thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();
		if (thumbext) {
			showSnackbar("처리중입니다. 잠시만 기다려 주세요.", 1500); //1.5초
			$.ajax({
				url: "/biz/sender/image_upload",
				type: "POST",
				data: file_data,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					showResult(json);
				},
				error: function (data, status, er) {
					//$(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
					//$("#myModal").modal('show');
					alert("처리중 오류가 발생했습니다.");return;
				}
			});
			function showResult(json) {
				//alert("code : "+ json['code']);
				if (json['code'] == 'success') {
					//window.location.href = '/biz/sender/image_w_list';
					image_list_open(1);
				} else {
					var message = json['message'];
					//console.log(message);
					//alert("message : "+ message);
					var messagekr = '';
					if (message == 'UnknownException') {
						messagekr = '관리자에게 문의하십시오';
					} else if (message == 'InvalidImageMaxLengthException') {
						messagekr = '이미지 용량을 초과하였습니다 (최대500KB)';
					} else if (message == 'InvalidImageSizeException') {
						messagekr = '가로 500px 미만 또는 가로*로 비율이 1:1.5 초과시 업로드 불가합니다';
					} else if (message == 'InvalidImageFormatException') {
						messagekr = '지원하지 않는 이미지 형식입니다 (jpg,png만 가능)';
					} else {
						if(message.indexOf("FailedToUploadImageException") != -1) {
							message = "발송할 수 없는 이미지 크기 입니다.\n가로:세로 비율은 2:1 이상 또는 3:4 이하여야 합니다.";
						}
						//messagekr = '관리자에게 문의하십시오.(' + message + ")";
						messagekr = message;
					}
					var text = '이미지 업로드에 실패하였습니다' + '\n\n' + messagekr;
					//$(".content").html(text.replace(/\n/g, "<br/>"));
					//$("#myModal").modal('show');
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".btn-primary").click();
					//	}
					//});
					alert(text);return;
				}
			}
		}
	}

	//카카오톡 이미지 삭제
	function delete_image(image) {
		if(!confirm("삭제 하시겠습니까?")){
			return;
		}
		var obj_image_name = [];
		obj_image_name.push(image);
		$.ajax({
			url: "/biz/sender/image_delete",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
				image_name: JSON.stringify({image_name: obj_image_name}),
				count: 1
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				//showResult(json);
				showSnackbar("삭제 되었습니다.", 1500); //1.5초
				image_list_open(1);
			},
			error: function (data, status, er) {
				//$(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오.");
				//$("#myModal").modal('show');
				alert("처리중 오류가 발생했습니다.");return;
			}
		});
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
