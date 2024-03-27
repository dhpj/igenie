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
<!-- 타이틀 영역 -->
	<div class="tit_wrap">
		쿠폰
	</div>
<!-- 타이틀 영역 END -->
<form action="/biz/coupon/writeft" method="post" id="sendForm" name="sendForm" onsubmit="return check_form();" enctype="multipart/form-data">
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
<input type='hidden' name='sms_sender' id='sms_sender' value='<?=$tpl->spf_sms_callback?>' />
<input type='hidden' name='hidden_cont' id='hidden_cont' value='<?=$tpl->tpl_contents?>' />
<input type='hidden' name='tpl_button' id='tpl_button' value='<?=$tpl->tpl_button?>' />
<input type='hidden' name='uplo' id='tpl_button' value='<?=$tpl->tpl_button?>' />
<input type='hidden' name='cc_idx' id='cc_idx' value='<?=$ftrs->cc_idx?>' />
<input type='hidden' name='ud_cc_tpl_code' id='ud_cc_tpl_code' value='<?=$tpl->cc_tpl_code?>' />
<input type='hidden' name='ud_cc_tpl_id' id='ud_cc_tpl_id' value='<?=$tpl->cc_tpl_id?>' />
<input type='hidden' name='actions' id='id_actions'  value='coupon_save' />
<input type='hidden' id='couponwrite' value='YES' />
<input type='hidden' name="cc_msg" id="cc_msg" value="" />
<div id="mArticle">
	    <div class="snb_nav" style="margin-bottom: -1px; margin-left: 1px">
        <ul>
            <li ><a href="/biz/coupon/write">알림톡 쿠폰</a></li>
            <li class="active"><a href="/biz/coupon/writeft">친구톡 쿠폰</a></li>
        </ul>
    	</div>	
	<div class="form_section">
		<div class="inner_tit">
			<? if($resultcnt->total == 0) {?>
			<div class="widget-header">
				<h3>쿠폰 작성</h3>
			</div>
			<? } else {  ?>
			<div class="col-xs-12 text-right">
				<a href="javascript:open_result_list();" class="btn">당첨자 조회</a> 
			</div>
			<? }  ?>
		</div>
		<div class="inner_content preview_info">
			<div id="send_template_content">
			</div>
		</div>
	</div>
	<div class="btn_group_lg">
		<button type="submit" onclick="coupon_save()" class="btn lg" style="<? echo ($rs->cc_status=='P')?"display:none":""; ?>">임시저장</button>
		<? if($rs->cc_status == 'P') { ?>
		<button type="submit" onclick="coupon_close()" class="btn lg">닫기</button><br><br><br>
		<? } else {?>
		<button type="submit" onclick="coupon_proc()" class="btn lg yellow">쿠폰 저장</button><br><br><br>
		<? } ?>
	</div>
</div>
</form>

	<!--템플릿 선택-->

	<div class="modal select fade" id="template_select" tabindex="-1" role="dialog"
		 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:hidden;">
		<div class="modal-dialog modal-lg select-dialog" id="modal">
			<div class="modal-content">
				<br/>
				<h4 class="modal-title" align="center">템플릿 선택하기</h4>
				<div class="modal-body select-body">
					<div class="widget-content" id="template_list">


					</div>
					<div align="center">
						<br/>
						<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
						<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_template();">확인</button>
						<br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal select fade" id="IMG_select" tabindex="-1" role="dialog"
		 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:hidden;">
		<div class="modal-dialog modal-lg select-dialog" id="modal">
			<div class="modal-content">
				<br/>
				<h4 class="modal-title" align="center">이미지 선택하기</h4>
				<div class="modal-body select-body">
					<div class="widget-content" id="template_list">


					</div>
					<div align="center">
						<br/>
						<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
						<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_template();">확인</button>
						<br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>


    <div class="modal select fade" id="myModalUserResultlist" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true" style=" height: 600px;">
        <div class="modal-dialog modal-lg select-dialog" style="width: 800px;height: 540px;">
            <div class="modal-content" style="width: 800px;height: 560px;" >
                <br/>
                <div class="row">
                	<div class="col-xs-2">
					</div>
                	<div class="col-xs-8">
                		<h4 class="modal-title" align="center">결과조회</h4>
                	</div>
                	<div class="col-xs-2">
                        	<button type="button" class="btn btn-default dismiss" data-dismiss="modal">닫기</button>
                    </div>
                	
                </div>
                <div class="modal-body select-body" style="height: 500px;">
                    <div >
                        <div class="content" id="modal_user_result_list" style="overflow-y:scroll; height: 485px;" style="border:1px solid #aaa;">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
	<style>
	.text {
		vertical-align: middle !important;
		line-height: 20px !important;
	}
	.scrolltbody {
		border-collapse: collapse;
		padding: 0!important;
	}
	.scrolltbody th { text-align: center }
	.select{
		vertical-align: middle;
		top: 50%;
		transform: translateY(-50%);
		overflow-x: hidden;
		overflow-y: hidden;
		height: 730px;
	}
	.select-dialog {
		width: 820px;
		height: 1020px;
	}
	.select-body {
		width: 100%;
		height: 650px;
	}
	#wrap {
		position: absolute;
	}
	.modal-open {
		overflow: hidden;
		position: fixed;
		width: 100%;
	}
</style>
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

	function coupon_save() {
		$('#id_actions').val('coupon_save');
		
		var cont_header_temp = $("#span_ft_adv").text() + $("#ft_companyName").val();
		var coun_footer_temp = $("#cont_footer").html().replace("<BR>", "\n").replace("<br>", "\n");
		cont_header_temp = cont_header_temp.replace(/&gt;/g, ">").replace(/&lt;/g, "<");
		coun_footer_temp = coun_footer_temp.replace(/&gt;/g, ">").replace(/&lt;/g, "<");
		//var templi_cont = cont_header_temp + "\n" + $("#templi_cont").val() + "\n" + coun_footer_temp;
		var templi_cont = $("#templi_cont").val();
		 
		$("#cc_msg").val(templi_cont);
	}

	function coupon_proc() {
		$('#id_actions').val('coupon_proc');
		var cont_header_temp = $("#span_ft_adv").text() + $("#ft_companyName").val();
		var coun_footer_temp = $("#cont_footer").html().replace("<BR>", "\n").replace("<br>", "\n");
		cont_header_temp = cont_header_temp.replace(/&gt;/g, ">").replace(/&lt;/g, "<");
		coun_footer_temp = coun_footer_temp.replace(/&gt;/g, ">").replace(/&lt;/g, "<");
		//var templi_cont = cont_header_temp + "\n" + $("#templi_cont").val() + "\n" + coun_footer_temp;
		var templi_cont = $("#templi_cont").val();
		 
		$("#cc_msg").val(templi_cont);		
	}

	function coupon_close() {
		$('#id_actions').val('coupon_close');
	}
	
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
		} else if (msg_length > limit_length) {
			$(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#templi_cont").focus();
			});
			var cont = $("#templi_cont").val();
			var cont_slice = cont.slice(0,1000);
			$("#templi_cont").val(cont_slice);
			$("#type_num").html(1000);
			 return;
		 } else {
			$("#type_num").html(msg_length);
		}
		<?}?>
	}
	
	//미리보기 - 템플릿내용
	function templi_preview(obj) {
		<?if($tpl) {?>

		var returnTempli_cont = obj.value;
		//alert(returnTempli_cont);
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
	
	  function check_form() {
		  var err = '';
		  var $err_obj = null;
		  
		  $('input.required').each(function() {
				if(err=='' && ($(this).val()=='' || $(this).val()==$(this).attr('placeholder'))) { err = $(this).attr('placeholder'); $err_obj = $(this); }
		  });

		  if($("#id_cc_title").val() == "") {
			  alert("쿠품명을 입력 하세요.");
			  $("#id_cc_title").focus();
			  return false;
		  }
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
	}

	//미리보기 - 이미지
	function readImage(selctImgValue) {
		$("#image").remove();
		var image = "<img id='image' name='image' src='" + selctImgValue + "' style='width:100%; margin-bottom:5px;'/>";
		var text_temp = $("#text").html();

		$("#text").html(image + text_temp);

		
// 		var cont_length = $("#templi_cont").val().length;
// 		var span = "<span id='type_num'>" + cont_length + "</span>";
// 		//document.getElementById('templi_length').innerHTML = span + "/400자";
// 		$("#text").val($("#templi_cont").val());

// 		$('.uniform').uniform();
// 		$("#img_link").attr("readonly", false);
// 		$("#text").before(image);
// 		$("#friendtalk_table tr").each(function () {
// 			if($(this).find("#no").text()) {
// 				var current_btn_num = $(this).find("#no").parent().attr("name");
// 				link_name(document.getElementById("btn_type"+current_btn_num),current_btn_num);
// 			}
// 		});
		//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
    }

	function link_name(obj,no) {
		//var file = document.getElementById('filecount').value;
		//if (file == '' && $("input:checkbox[id='lms_select']").is(":checked") != true) {
		var name = $("#btn_preview"+no).val();
		if (name == undefined) {
			if(!(obj.value=="N" || obj.value=="DS" || obj.value=="WL" || obj.value=="AL" || obj.value=="BK" || obj.value=="MD")) {
				var count = no-1;
				$("#text").css("margin-bottom","10px");
				var html = '<div id="btn_preview_div' + no + '" class="' + no + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
				'<p data-always-visible="1" id="btn_preview' + no + '" data-rail-visible="0" cols="20" readonly="readonly" ' +
				'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
				'>' + obj.value + '</p></div>';
				if (no == 1) {
					//$("#text").after(html);
					$("#text").html($("#text").html() + html);
					//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				} else {
					for(count; count >= 0 ; count --) {
						if($("#btn_preview" + count).val() != undefined && $("#btn_preview" + no).val() == undefined) {
							$("#btn_preview_div" + count).after(html);
							//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
						} else if(count == 0) {
							if($("#btn_preview" + no).val() == undefined) {
								//$("#text").after(html);
								$("#text").html($("#text").html() + html);
								//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
							}
						}
					}
				}
			} else {
				var count = no-1;
				var btn = 0;
				if(obj.value=="DS"){
					btn = 1;
				} else if (obj.value=="WL") {
					btn = 2;
				} else if (obj.value=="AL") {
					btn = 3;
				} else if (obj.value=="BK") {
					btn = 4;
				} else if (obj.value=="MD") {
					btn = 5;
				}
				var content;
				if($("#btn_name"+btn+"_"+no).val().trim() != "") {
					content = $("#btn_name"+btn+"_"+no).val();
				} else {
					content = "버튼명을 입력해주세요.";
				}
				$("#text").css("margin-bottom","10px");
				var html = '<div id="btn_preview_div' + no + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
				'<p data-always-visible="1" id="btn_preview' + no + '" data-rail-visible="0" cols="20" readonly="readonly" ' +
				'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
				'>' + content + '</p></div>';
				if (no == 1) {
					//$("#text").after(html);
					$("#text").html($("#text").html() + html);
					//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				} else {
					for(count; count >= 0 ; count --) {
						if($("#btn_preview" + count).val() != undefined && $("#btn_preview" + no).val() == undefined) {
							$("#btn_preview_div" + count).after(html);
							//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
						} else if(count == 0) {
							if($("#btn_preview" + no).val() == undefined) {
								//$("#text").after(html);
								$("#text").html($("#text").html() + html);
								//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
							}
						}
					}
				}
			}
		} else {
			if(obj.value=="DS" || obj.value=="WL" || obj.value=="AL" || obj.value=="BK" || obj.value=="MD") {
				var btn;
				if(obj.value=="DS"){
					btn = 1;
				} else if (obj.value=="WL") {
					btn = 2;
				} else if (obj.value=="AL") {
					btn = 3;
				} else if (obj.value=="BK") {
					btn = 4;
				} else if (obj.value=="MD") {
					btn = 5;
				}
				var content;
				if ($("#btn_name"+btn+"_"+no).val().trim() != "") {
					content = $("#btn_name"+btn+"_"+no).val();
				} else {
					content = "버튼명을 입력해주세요.";
				}
				$("#btn_preview" + no).text(content);
				//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
			} else if (obj.value=="N") {
				if(no==1){
					$("#text").css("margin-bottom","0px");
				}
				$("#btn_preview_div" + no).remove();
			} else {
				if(obj.value.replace(/ /gi,"").trim()) {
					$("#btn_preview" + no).text(obj.value);
					//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				} else {
					$("#btn_preview" + no).text("버튼명을 입력해주세요.");
					//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				}
			}
		}
		//}
	}

	function chkword_templi() {
		<? // 친구톡 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var cont_header_temp = $("#cont_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var cont_header_temp = $("#span_ft_adv").text() + $("#ft_companyName").val();
		var coun_footer_temp = $("#cont_footer").html().replace("<BR>", "\n").replace("<br>", "\n");
		cont_header_temp = cont_header_temp.replace(/&gt;/g, ">").replace(/&lt;/g, "<");
		coun_footer_temp = coun_footer_temp.replace(/&gt;/g, ">").replace(/&lt;/g, "<");
		var msg_text = cont_header_temp + "\n" + $("#templi_cont").val() + "\n" + coun_footer_temp;
		var msg_length = msg_text.length || 0;
		
		$("#type_num").html(msg_length);
		<?/* 이미지가 포함되면 400자 이내로 제한 */?>
		if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
			var limit_length = 400;
			//var msg_length = $("#templi_cont").val().length;
			if (msg_length <= limit_length) {
				$("#type_num").html(msg_length);
			} else if (msg_length > limit_length) {
// 				$(".content").html("템플릿 내용을 400자 이내로 입력해주세요.");
// 				$("#myModal").modal({backdrop: 'static'});
// 				$('#myModal').on('hidden.bs.modal', function () {
// 					$("#templi_cont").focus();
// 				});
// 				var cont = $("#templi_cont").val();
// 				var cont_slice = cont.slice(0, 400 - cont_header_temp.length - coun_footer_temp.length - 2);
// 				$("#templi_cont").val(cont_slice);
// 				$("#type_num").html(400);
// 				$("#text").val(cont_slice);
// 				return;
			} else {
				$("#type_num").html(msg_length);
			}
		} else if ($("#img_url").val() == undefined || $("#img_url").val() == "") {
			var limit_length = 1000;
			//var msg_length = $("#templi_cont").val().length;
			if (msg_length <= limit_length) {
				$("#type_num").html(msg_length);
			} else if (msg_length > limit_length) {
// 				$(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
// 				$("#myModal").modal({backdrop: 'static'});
// 				$('#myModal').on('hidden.bs.modal', function () {
// 					$("#templi_cont").focus();
//				});
// 			var cont = $("#templi_cont").val();
// 			var cont_slice = cont.slice(0, 1000 - cont_header_temp.length - coun_footer_temp.length - 2);
// 			$("#templi_cont").val(cont_slice);
// 			$("#type_num").html(1000);
// 			$("#text").val(cont_slice);
// 				return;
// 			} else {
// 				$("#type_num").html(msg_length);
 			}
		}
	}

	<?/*-------------------------*
	  | 2차발신 내용 글자수 제한 |
	  *--------------------------*/?>
	function chkword_lms() {
		chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '80' : '2000') ?>);
	}

	function chkbyte($obj_header, $obj, $obj_footer, $num, maxByte) {
		var oriMaxByte = maxByte;
		var phn = '<?=$this->Biz_dhn_model->reject_phn ?>';
		var phn_len = phn.length + 16;

		<? // 문자 헤드부분 회사명 변경가능하도록 수정 문자길이체크 처리 2019-07-25 ?>
		var lms_header_temp = $obj_header.find("#span_adv").text() + $obj_header.find("#companyName").val();
		//var lms_header_temp = $obj_header.html().replace("<BR>", "\n").replace("<br>", "\n");
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $obj_footer.html().replace("<BR>", "\n").replace("<br>", "\n");
		
		var strValue = lms_header_temp + "\n" + $obj.val() + "\n" + lms_footer_temp;
		var strLen = strValue.length || 0;

		
		//var strValue = $obj.val();
		//var strLen = strValue.length;
		var totalByte = 0;
		var len = 0;
		var oneChar = "";
		var str2 = "";

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
//			<?/* 입력한 문자 길이보다 넘치면 잘라내기 위해 저장 */?>
// 			if (totalByte <= maxByte) {
// 				 len = i + 1;
// 			}
		}
		$num.html(totalByte);

		if (maxByte <= 80) {
			$("#lms_limit_str").html("SMS");
			$("#lms_character_limit").html("80");
		} else {
			$("#lms_limit_str").html("LMS");
			$("#lms_character_limit").html("2000");
		}

//		<?/* 넘어가는 글자는 자른다. */?>
// 		if (totalByte > maxByte) {
//			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 " + oriMaxByte + "자(한글 " + parseInt(oriMaxByte / 2) + "자) 이내로<br/>입력해주세요.<br/>(실제내용 " + (maxByte - phn_len - 20) + "자 이내)");
// 			$("#myModal").modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#lms").focus();
// 			});

// 			$obj.val(str2);
// 			$num.html(totalByte);
//          chkbyte($obj_header, $obj, $obj_footer, $num, oriMaxByte);
//		}
   }

	<?/*--------------------*
       | 링크 확인창 띄우기 |
       *--------------------*/?>
	function urlConfirm(obj) {

		var parent = obj.parentElement;

		var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
		
		
		var url = $(parent).find('input').val();

		if(url != "") {
			if(pattern.test(url)) {
    		 	if (url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0) {
    	 			url = url;
    	 		} else {
    	 			url = "http://" + url;
    	 			$(parent).find('input').val(url);
    	 		}
    	 		window.open(url, '_blank', 'location=no, resizable=no');
			} else {
				$(".content").html("링크 경로가 잘 못되었습니다.");
				$("#myModal").modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$(parent).find('input').focus();
				});
			}
		} else {
			$(".content").html("링크 경로를 입력하세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$(parent).find('input').focus();
			});
		}
	}	

	
	
	<?/*--------------------*
	  | 글자수(Byte)를 반환 |
	  *---------------------*/?>
	function getByteLength($obj)
	{
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

		var totalByte = 0;
		var oneChar = "";
		
		var strValue = lms_header_temp + "\n" + $obj.val() + "\n" + lms_footer_temp;
		var strLen = strValue.length;

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
		}
		return totalByte;
	}
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
	//글자수 제한 - 링크 이름
	function chkword_btn() {
		var limit_length = 30;
		var link_length = $("#btn_name").val().length;
		if (link_length <= limit_length) {
			$("#link_num").html(link_length);
		} else if (link_length > limit_length) {
			$(".content").html("링크 버튼 이름을 30자 이내로 입력해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#btn_name").focus();
			});
			var cont = $("#btn_name").val();
			var cont_slice = cont.slice(0, 30);
			$("#btn_name").val(cont_slice);
			$("#link_num").html(30);
			return;
		} else {
			$("#link_num").html(link_length);
		}
	}

	//글자수 제한 - 링크 url
	function chkword_url() {
		var limit_length = 250;
		var link_length = $("#btn_url").val().length;
		if (link_length > limit_length) {
			var cont = $("#btn_url").val();
			var cont_slice = cont.slice(0, 250);
			$("#btn_url").val(cont_slice);
			$("#btn_url").focus();
		}
	}

	// input enter 키 방지
	function captureReturnKey(e) {
		if(e.keyCode==13 && e.srcElement.type != 'textarea')
			return false;
	}

	//프로필 선택시
	function profile() {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile");
		form.submit();
	}


	// 프로필선택 버튼
	function btnSelectProfile() {
		var count = '';
		console.log('count', count);
		if('' == "none") {
			$('#profile_danger').modal('show');
		} else {
			$("#profile_select").modal({backdrop: 'static'});
			$('#profile_select').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					console.log('code == 27');
					$("#profile_select").modal("hide");
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

		$('#profile_select .widget-content').html('').load(
			"/biz/sender/send/profile",
			{
				<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>",
				"page": 1
			},
			function() {
				//$('#profile_select').css({"overflow-y": "scroll"});
			}
		);
	}
		 
	function onPreviewText() {
		//var selctImgValue = ("#img_url").val();
		<? // 친구톡 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var cont_header_temp = $("#cont_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var cont_header_temp = $("#span_ft_adv").text() + $("#ft_companyName").val();
		var coun_footer_temp = $("#cont_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var msg_text = "";	
		var image;

		//alert($("#img_url").length);
		if ($("#img_url").length > 0 && $("#img_url").val() !== "") {
			var selectImgValue = $("#img_url").val();
			//alert(selectImgValue);
			
			image = "<img id='image' name='image' src='" + selectImgValue + "' style='width:100%; margin-bottom:5px;'/>";
			msg_text = image + msg_text;
			//alert(msg_text);
		} 
		//alert("=====>" + msg_text);
		if ($("#templi_cont").val().replace(/\n/g, "").length > 0) {
			msg_text = msg_text + cont_header_temp + "\n" + $("#templi_cont").val() + "\n" + coun_footer_temp;
		} else {
			msg_text = msg_text + cont_header_temp + "\n\n메세지를 입력해주세요.\n\n" + coun_footer_temp;
		}
		//alert(msg_text);
		msg_text = msg_text.replace(/\n/g, "<br>");
		msg_text = replaceKakaoEmoticon(msg_text, 20);

		msg_text = msg_text + "<br><br>";

		//alert(msg_text);
		$("#text").html(msg_text);
		
		for (var i = 0; i < 5; i++) {
			if ($("#field-data-" + i).length > 0) {
				link_name(document.getElementById("btn_type" + (i + 1)),(i + 1));
			} else {
				break;
			}
		}
		
		//#text	
		//var file = document.getElementById('filecount').value;
		//if (file == ''){
		//	var cont=$('#lms').val();
		//	if (!cont || cont == "" ) {
		//		cont=$('#templi_cont').val();
		//	}
		//	$('#text').val(cont);

		//	var height = $(".message").prop("scrollHeight");
		//	if( height < 408 ) {
		//		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
		//	} else {
		//		var message = $("#text").val();
		//		if(message==""){
		//			$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
		//		} else {
		//			$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
		//			$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
		//		}
		//	}
		//	if (cont && cont == "") {
		//		$('#text').val(' 메세지를 입력해주세요.');
		//	}
		//}
		//scroll_prevent();
	}

	function insert_homepage_link(check){
		//var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		//var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;

		var html_url = "<?=((strpos($mem->mad_free_hp, 'http')===false) ? '': $mem->mad_free_hp) ?>";
		var content;
		var index;
		var cont;
		cont = $("#cont_footer").html().replace("<br>", "\n");

		if (check.checked) {
			if (new RegExp(/수신거부/gi).test(cont)) {
				content = cont.replace("수신거부", html_url + "\n수신거부");
				$("#cont_footer").html(content.replace("\n", "<br>"));
				//content = $("#lms").val().replace("무료수신거부", short_url + "무료수신거부");
				//$("#lms").val(content).focus();
			} else {
				content = cont.replace(cont, cont + html_url);
				$("#cont_footer").html(content.replace("\n", "<br>"));
				//content = $("#lms").val().replace(cont, cont + "<BR>" + short_url);
				//$("#lms").val(content).focus();
			}
		} else {
			if ((new RegExp(/수신거부/gi).test(cont))) {
				index = cont.indexOf("수신거부");
				var first = cont.slice(0, index - 1).replace(html_url, "");
				var last = cont.slice(index);
				content = first + last;
				//$("#lms").val(first + last).focus();
				$("#cont_footer").html(content.replace("\n", "<br>"));
			} else {
				index = cont.indexOf(short_url);
				content = cont.slice(0, index - 1);
				$("#cont_footer").html(content.replace("\n", "<br>"));
				//$("#lms").val(cont.slice(0, index - 1));
			}
		}
		chkword_templi();
		onPreviewText();
	}
	
	$(document).ready(function() {
		 
		$('#send_template_content').html('').load(
			"/biz/coupon/select_profile/",
			{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
			  "cc_idx":"<?=$ftrs->cc_idx?>"}, 
			function() {

					$("#btn_type1").val("WL");
					
					var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
					var pf_ynm = $("#pf_ynm").val();

				 	var content_footer = "수신거부 : 홈>친구차단";
					
					$("#cont_footer").html(content_footer);
										
					var phn = '080-888-7985'; //'<?=$this->Biz_dhn_model->reject_phn ?>';
					<?if( $mem->mem_2nd_send=='015') { ?>
					phn = '080-888-7985'; 
					<?} else if( $mem->mem_2nd_send=='NASELF') { ?>
					phn = '080-888-7985'; 
					<?} else if($mem->mem_2nd_send=='GREEN_SHOT') { ?>
					phn = '080-888-7985'; 
					<?} else if($mem->mem_2nd_send=='PHONE') { ?>
					phn = '080-888-7985';
					<?}
					if($this->member->item("mem_id") == 228 || $this->member->item("mem_id") == 93 || $this->member->item("mem_id") == 519
					    || $this->member->item("mem_id") == 445 || $this->member->item("mem_id") == 446 || $this->member->item("mem_id") == 447
					    || $this->member->item("mem_id") == 448 || $this->member->item("mem_id") == 484 || $this->member->item("mem_id") == 572) {
					        
                    ?>
					phn = '080-053-7590';
					<? } ?>
					
				
					$("#unsubscribe_num").val(phn);
							
					//add_info('');
				
					chkword_templi();
					chkword_lms();
					
					//$("#btn_name2_1").val("♡  쿠폰받기 ♡");
					//$("input[name=cc_button1]").val("http://#{url}");
					onPreviewText();
			}
		);
		$("#id_cc_start_date, #id_cc_end_date").datepicker({
			format:'yyyy-mm-dd'			
		});		
	});
</script>
