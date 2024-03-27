	<script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
	<script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script>
	<link rel='stylesheet' href='/css/plugins/flickity.css'>
	<head>
		<meta http-equiv="Expires" content="0"/>
		<meta http-equiv="Pragma" content="no-cache"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	</head>
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";
//-->
</script>
<!-- 컨텐츠 전체 영역 -->
<!-- form action="/dhnbiz/sender/talk/send" method="post" id="sendForm" name="sendForm" onsubmit="return false;" enctype="multipart/form-data" -->
<input type="hidden" id="navigateURL" value="" />
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
<input type='hidden' name='sms_sender' id='sms_sender' value='' />
<input type='hidden' name='hidden_cont' id='hidden_cont' value='' />
<input type='hidden' name='tpl_button' id='tpl_button' value='' />
<input type='hidden' name='uplo' id='tpl_button' value='' />
<input type="hidden" name="couponid" id="couponid"  value='' />

<input type="hidden" id="pf_ynm" value="<?=$mem->mem_username?>"/>
<input type="hidden" id="sms_sender" value="<?=$rs1->spf_sms_callback?>"/>
<input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name" value="">
<input type="hidden" name="pf_key" id="pf_key" value="">
<input type="hidden" name="pf_yid" id="pf_yid" value="">
<input type="hidden" name="templi_code" id="templi_code" value="">
<input type="hidden" name="sbid" id="sbid" value="">
<input type="hidden" name="kind" id="kind" value="L">
<input type="hidden" name="btn_name" id="btn_name">
<input type="hidden" name="btn_url" id="btn_url">


				<h3 class="delivery_tit">모바일 배송알림톡</h3>
				<div id="mArticle">
					<p class="templet_tit"><i class="zmdi zmdi-n-1-square zmdi-hc-lg" style="color: #0097e2;"></i> 원하는 템플릿을 선택하세요.</p>
					<div class="carousel" data-flickity='{ "wrapAround": true }'>
						<? $tplCount = 0;
						foreach($tpl as $rowtpl) {
                            $tplCount += 1;
                            // $rowContentsTemp = str_replace('\r\n', '<br>', $rowtpl->tpl_contents);
                            $rowContentsTemp = nl2br($rowtpl->tpl_contents);
                        ?>
						<div class="mobile_templet">
                            <input type='hidden' name='sms_sender' id='sms_sender<?= $tplCount?>' value='<?=$rowtpl->spf_sms_callback?>' />
                            <input type='hidden' name='hidden_cont' id='hidden_cont<?= $tplCount?>' value='<?=$rowtpl->tpl_contents?>' />
                            <input type='hidden' name='tpl_button' id='tpl_button<?= $tplCount?>' value='<?=$rowtpl->tpl_button?>' />
                            <input type='hidden' name='uplo' id='tpl_button<?= $tplCount?>' value='<?=$rowtpl->tpl_button?>' />
                            <input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name<?= $tplCount?>" value="<?=$rowtpl->mem_linkbtn_name?>">
                            <input type="hidden" name="pf_key" id="pf_key<?= $tplCount?>" value="<?=$rowtpl->tpl_profile_key?>">
                            <input type="hidden" name="pf_yid" id="pf_yid<?= $tplCount?>" value="<?=$rowtpl->spf_friend?>">
                            <input type="hidden" name="templi_code" id="templi_code<?= $tplCount?>" value="<?=$rowtpl->tpl_code?>">
                            <input type="hidden" name="kind" id="kind<?= $tplCount?>" value="L">
                            <input type="hidden" name="btn_name" id="btn_name<?= $tplCount?>">
                            <input type="hidden" name="btn_url" id="btn_url<?= $tplCount?>">
                            <input type="hidden" name="couponid" id="couponid<?= $tplCount?>"  value="<?=$rs->cc_idx?>">
 							<div class="preview_circle"></div>
							<div class="preview_round"></div>
							<div class="preview_msg_wrap">
								<div class="mobile_msg_window">
									<div class="preview_box_profile">
										<span class="profile_thumb">
											<img src="/img/logo/logo_dhn.png">
										</span>
										<span class="profile_text"><?=$mem->mem_username?></span>
									</div>
									<div class="preview_box_msg"><? echo $rowContentsTemp; ?>
									</div>
								</div>
							</div>
							<div class="templet_check"><input type="radio" name="temp_select" class="templet_check" id="temp_chk<?= $tplCount?>" <? if ($tplCount === 1) { echo "checked"; }?>><label for="temp_chk<?= $tplCount?>">선택</label></div>
						</div>
                        <?

						}
						?>
					</div>
					<script src='/js/plugins/flickity.pkgd.js'></script>
						<div class="inner_content">
								<p class="templet_tit"><i class="zmdi zmdi-n-2-square zmdi-hc-lg " style="color: #0097e2;"></i> 수신고객 선택</p>
							    <div id="#private_m">
									    <div class="msg_box">
											<div class="send_list">
												<ul class="num_list" id="num_list">
												</ul>
												<div class="send_num_add">
													<button class="btn md dark fr" id="add-todo-button">전화번호 추가</button>
													<div style="overflow: hidden;">
														<input type="tel" id="add-todo-input" name="phoneNum" maxlength="13" style="width:100%; margin-right: -2px;" placeholder="연락처를 입력해 주세요">
										    		</div>
									    		</div>
											</div>
											<div class="bottom tr">
												총 발송 <span class="num" id="input_phone_count">0</span>건
											</div>
										</div>
							    </div>
						</div><!-- inner_content END -->
					<div class="btn_group_lg tc">
						<!-- button class="btn lg">취소</button-->
						<button class="btn lg yellow" onclick="all_send();">보내기</button>
					</div>
				</div><!-- mArticle END -->

<!-- input 숫자만 입력가능/자동 하이픈 -->
<script type="text/javascript">
    function autoHypenPhone(str){
    	str = str.replace(/[^0-9]/g, '');
    	var tmp = '';
    	if( str.length < 4){
    		return str;
    	}else if(str.length < 7){
    		tmp += str.substr(0, 3);
    		tmp += '-';
    		tmp += str.substr(3);
    		return tmp;
    	}else if(str.length < 11){
    		tmp += str.substr(0, 3);
    		tmp += '-';
    		tmp += str.substr(3, 3);
    		tmp += '-';
    		tmp += str.substr(6);
    		return tmp;
    	}else{
    		tmp += str.substr(0, 3);
    		tmp += '-';
    		tmp += str.substr(3, 4);
    		tmp += '-';
    		tmp += str.substr(7);
    		return tmp;
    	}
    	return str;
    }

    var phoneNum1 = document.getElementById('add-todo-input');
    phoneNum1.onkeyup = function(event){
        event = event || window.event;
        var _val = this.value.trim();
        this.value = autoHypenPhone(_val) ;
    }
</script>
<!-- 전화번호 추가/삭제 스크립트 -->
<!-- script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script-->
<!-- 첫번째 -->
<script >
function onAddBtnClick() {
    // get the current value of .add-todo-input
    let newTodoText = $('#add-todo-input').val();

    // get the current value of .add-todo-input
    if (newTodoText === '') {
    	return;
    }

    // add a new todo with the text of .add-todo-input
    $('#num_list').append('<li class="todo-list-item"><span name="input_phone_no">' + newTodoText + '</span><button class="btn sm del fr" id="num_del">삭제</button></li>');
    // empty the .add-todo-input
    $('#add-todo-input').val('');



    var phoneCount = parseInt($("#input_phone_count").text());
    //alert(phoneCount);
    $("#input_phone_count").text(phoneCount + 1);
}

// adding a new todo
$('#add-todo-button').click(onAddBtnClick);
$('#add-todo-input').keypress(function (event) {
    if (event.which === 13) {
    onAddBtnClick();
    }
});

// $('.todo-list-item').remove()
$('#num_list').on(
    'click',
    '#num_del',
    function () {
      $(this).parent().remove();
      var phoneCount = parseInt($("#input_phone_count").text());
      //alert(phoneCount);
      $("#input_phone_count").text(phoneCount - 1);
});

function templeSelect() {
	//alert($("input:radio[name='temp_select']").length);
	//$("input:radio[name='temp_select']").prop("checked", false);
	$(".is-selected").find("input:radio").prop("checked", true);
	var radioId = $(".is-selected").find("input:radio").attr("id");
	var no = radioId.substr(8);

	$("#sms_sender").val($("#sms_sender" + no).val());
	$("#hidden_cont").val($("#hidden_cont" + no).val());
	$("#tpl_button").val($("#tpl_button" + no).val());
	$("#couponid").val($("#couponid" + no).val());
	$("#find_linkbtn_name").val($("#find_linkbtn_name" + no).val());
	$("#pf_key").val($("#pf_key" + no).val());
	$("#pf_yid").val($("#pf_yid" + no).val());
	$("#templi_code").val($("#templi_code" + no).val());
	$("#kind").val($("#kind" + no).val());
	$("#btn_name").val($("#btn_name" + no).val());
	$("#btn_url").val($("#btn_url" + no).val());

	//alert($(".is-selected").find("input:radio").attr("id"));
	//alert($(".is-selected").find("input:radio").prop("checked"));
	//alert($("#temp_chk1").prop("checked"));
	//alert($("#temp_chk2").prop("checked"));
	//alert($("#temp_chk3").prop("checked"));
}
</script>

	<div class="modal select fade" id="sendbox_select" tabindex="-1" role="dialog"
		 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
		<div class="modal-dialog modal-lg sendbox-dialog" id="modal">
			<div class="modal-content">
				<h4 class="modal-title" align="center">내 문자함 (알림톡)</h4>
				<div class="modal-body select-body">
					<div class="clear sendbox-tab_wrap">
						 <ul class="sendbox-tab">
							<li class="on"><a href="javascript:void(bind_sendbox('history'))">보낸문자</a></li>
							<li><a href="javascript:void(bind_sendbox('save'))">저장문자</a></li>
						 </ul>
						 <div style="float:right;">
							 <select class="select2 input-width-medium" id="searchType_sendbox" onchange="getSelectValue(this.form);">
								<option value="subject">문자제목</option>
								<option value="content">문자내용</option>
							 </select>&nbsp;
							 <input type="text" class="form-control input-width-medium inline"
									  id="searchFor_sendbox" name="search" placeholder="검색어 입력" value=""/>&nbsp;
							 <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_sendbox();'"/>
						 </div>
					</div>


					<div class="widget-content" id="sendbox_list">


					</div>
					<div class="btn_wrap" align="center">
						<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
						<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_sendbox();">확인</button>
						<br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>



	<!--템플릿 선택-->
	<div class="modal fade" id="template_danger" tabindex="-1" role="dialog"
		 aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modal" style="width:400px;">
			<div class="modal_content">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal_body">
					<div class="content">
						<div class="row align-center" id="danger" style="height: 300px; padding-top: 70px;">
							<p class="alert alert-danger">
								승인된 템플릿이 없습니다. 템플릿을 등록해주세요.
							</p>
							<button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 85px;">닫기</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal select fade" id="template_select" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:hidden;">
		<div class="modal-dialog modal-lg select-dialog" id="modal" data-keyboard="false" data-backdrop="static">
			<div class="modal_content">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기test</h3>
				</div>
				<div class="modal_body">
					<div class="widget-content" id="template_list"></div>
				</div>
				<div class="modal_bottom">
					<button type="button" class="btn md" data-dismiss="modal">취소</button>
					<button type="button" class="btn md" id="code" name="code" onclick="select_template();">확인</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modal">
			<div class="modal_content">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3></h3>
				</div>
				<div class="modal-body">
					<div class="content identify">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
								확인
							</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary submit">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalSelect" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary select-send" onclick="select_move();">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalAll" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>발송하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary all" onclick="all_move();">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalDel" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary del">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalSelDel" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary selDel">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalTemp" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary temp">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalUpload" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary up">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalDownload" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
							<button type="button" class="btn btn-primary down">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModalFilterAll" tabindex="-1" role="dialog"
		 aria-labelledby="myModalCheckLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" id="modalCheck">
			<div class="modal_content select_send">
				<div class="modal_title">
					<button class="modal_close" data-dismiss="modal"></button>
					<h3>템플릿 선택하기</h3>
				</div>
				<div class="modal-body">
					<div class="content">
					</div>
					<div>
						<p align="right">
							<br/><br/>
							<button type="button" class="btn btn-default" data-dismiss="modal">선택추가</button>
							<button type="button" class="btn btn-primary filter_all">전체추가</button>
						</p>
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

	//수신번호 전체 선택 안되는 현상 수정
	$("#all_select").click(function(){
		if($("#all_select").prop("checked")) {
			$("input:checkbox[name='sel_del']").prop("checked",true);
		} else {
			$("input:checkbox[name='sel_del']").prop("checked",false);
		}
	});

	//미리보기 - 템플릿내용
	function templi_preview(obj) {
		<?if($tpl) {?>
		$("#text").val(obj.value);
		var height = $("#text").prop("scrollHeight");
		if (height < 468) {
			$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
		} else {
			var message = $("#text").val();
			var height = $("#text").prop("scrollHeight");
			if (message == "") {
				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
			} else {
				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
				$("#templi_cont").keyup(function() {
					$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
				});
			}
		}
		<?}?>
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
				$("#text").after(p);
		  } else {
				$("#btn_preview" + i).after(p);
		  }
		}
		<?}?>
	}

	//템플릿 선택 - 모달 확인
	function select_template() {
			selected = $("#template_list").find(".active").find(".tpl_id").val();
			selected_profile = $("#template_list").find(".active").find(".pf_key").val();
			iscoupon = $("#template_list").find(".active").find(".iscoupon").val();
			//alert(iscoupon);
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
			form.setAttribute("action", "/dhnbiz/sender/send/talk_dt");
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
			var selProfileField = document.createElement("input");
			selProfileField.setAttribute("type", "hidden");
			selProfileField.setAttribute("name", "iscoupon");
			selProfileField.setAttribute("value", iscoupon);
			form.appendChild(selProfileField);
			var nft_ = document.createElement("input");
			nft_.setAttribute("type", "hidden");
			nft_.setAttribute("name", "nft");
			nft_.setAttribute("value", "<?=$nft?>");
			form.appendChild(nft_);

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

	//예약발송 체크박스 체크
	function ReserveCheck() {
		if($("#reserve_check").is(":checked") == true) {
			$(".reserve_content").attr("hidden", false);
		} else {
			$(".reserve_content").attr("hidden", true);
		}
	}

	//수신번호 추가
	function number_add() {
		var lastItemNo = $("#tel tbody tr:last").attr("class");
		var no = parseInt(lastItemNo) + 1;
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		if ($("#templi_code").val() == null) {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if ($("input:checkbox[value='1']").is(":checked") == true) {
			if (row_index <= 100) {
				if (no == 2) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + 1).after(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#" + no).uniform();
				} else if (row_index >= 5) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="' + no + '" value="' + no + '" type="checkbox"></td>'+
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>'+
							'</tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr." + no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="' + no + '"  value="' + no + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + lastItemNo).after(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#" + no).uniform();
				}
			} else {
				$(".content").html("최대 100개까지 가능합니다.");
				$("#myModal").modal({backdrop: 'static'});
			}
		} else {
			if (row_index <= 100) {
				if (row_index > 5) {
					var tr = '<tr class="' + no + '" name="' + no + '" id="tel_tbody_tr" name="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input type="checkbox" name="sel_del" id="' + no + '"  value="' + no + '" class="uniform"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr." + no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="' + no + '"  value="' + no + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + lastItemNo).after(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#" + no).uniform();
				}
			} else {
				$(".content").html("최대 100개까지 가능합니다.");
				$("#myModal").modal({backdrop: 'static'});
			}
		}
	}

	//수신번호 개별삭제
	function tel_remove(obj) {
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		if ($("#templi_code").val() == null) {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if (row_index == 2) {
			var tel_number = $("#tel_number").val();
			if (tel_number == "") {
				$(".content").html("삭제할 수신 번호가 없습니다.");
				$("#myModal").modal({backdrop: 'static'});
				$(document).unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".enter").click();
					}
				});
			} else {
				$(".content").html("삭제하시겠습니까?");
				$("#myModalDel").modal({backdrop: 'static'});
				$("#myModalDel").unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						tel_del(obj);
					}
				});
				$(".del").click(function () {
					tel_del(obj);
					$(".del").unbind("click");
				});
			}
		} else {
			$(".content").html("삭제하시겠습니까?");
			$("#myModalDel").modal({backdrop: 'static'});
			$("#myModalDel").unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					tel_del(obj);
				}
			});
			$(".del").click(function () {
				tel_del(obj);
				$(".del").unbind("click");
			});
		}
	}
	function tel_del(obj) {
		$("#myModalDel").modal("hide");
		var table = document.getElementById("tel");
		var row_index = table.rows.length-1;
		var tr = $("." + obj);

		if (row_index != 1) {
			if (row_index > 5) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "235px");
				$(this).css("overflow", "scroll");
			} else if (row_index < 6) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", (height - 47) + "px");
			}
		} else {
			tr.remove();
			var tr = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
					'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td>' +
					'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
					'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
					'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
					'</tr>';
			$("#tel_tbody").html(tr);
			var height = $("#tel_tbody").prop("scrollHeight");
			$("#tel_tbody").css("height", "47px");
			$("#1").uniform();
		}
	}

	//수신번호 선택삭제
	function selectDelRow() {
		if ($("#templi_code").val() == null) {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
			$(".content").html("삭제할 수신 번호를 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			$("input[name=sel_del]:checked").each(function () {
				var obj = $(this).val();
				var table = document.getElementById("tel");
				var row_index = table.rows.length;
				if (row_index == 2) {
					var tel_number = $("#tel_number").val();
					if (tel_number == "") {
						$(".content").html("삭제할 수신 번호가 없습니다.");
						$("#myModal").modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("삭제하시겠습니까?");
						$("#myModalSelDel").modal({backdrop: 'static'});
						$("#myModalSelDel").unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".selDel").click();
							}
						});
						$(".selDel").click(function() {
							tel_select_del(obj);
							$(".submit").unbind("click");
						});
					}
				} else {
					$(".content").html("삭제하시겠습니까?");
					$("#myModalSelDel").modal({backdrop: 'static'});
					$("#myModalSelDel").unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".selDel").click();
						}
					});
					$(".selDel").click(function() {
						tel_select_del(obj);
						$(".selDel").unbind("click");
					});
				}
			});
		}
	}
	function tel_select_del(obj) {
		$("#myModalSelDel").modal("hide");
		var tr = $("." + obj);
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		var cnt = $("input[name=sel_del]:checked").length;
		var row = row_index-cnt;
		if(cnt!=0) {
			if (row_index >= 7) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "235px");
				$(this).css("overflow", "scroll");
			} else if (row < 6 && row >= 2) {
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", (row*47-47) + "px");
			} else if (1 >= row) {
				tr.remove();
				$("#tel_number").val("");
				$("input[name=sel_del]").prop("checked", false);
				$('#all_select').prop('checked',false);
				$.uniform.update('#all_select');
				if(row==1) {
					var tr = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("#tel_tbody").html(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "47px");
					$("#1").uniform();
				}
			}
		}
	}

	//수신번호 숫자만 입력 가능
	//숫자 여부 확인
	function check_digit(evt){
		if($("#templi_code").val()==null){
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#tel_number").val("");
			});
		} else {
			var code = evt.which ? evt.which : event.keyCode;
			if (code < 48 || code > 57) {
				return false;
			}
		}
	}

	//모달 검색
	function search() {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/sender/send/template_dt");
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
		form.setAttribute("action", "/dhnbiz/sender/send/template_dt");
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

	function showNeedCoin() {
		 $(".content").html("충전 잔액이 부족합니다.");
		 $('#myModal').modal({backdrop: 'static'});
		 $('#filecount').filestyle('clear');
	 }

	function showLimitOver()
	{
		 $(".content").html("금일 발송 가능 건수를 모두 사용하였습니다.");
		 $('#myModal').modal({backdrop: 'static'});
		 $('#filecount').filestyle('clear');
	}

	function search_sendbox() {
		try {
			open_page_sendbox(1);
		}catch(e){}
	}


<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>
	var ms = '<?=($mem->mem_2nd_send) ? substr(strtoupper($mem->mem_2nd_send), 0, 1) : ""?>';
   var at_price = '<?=$this->Biz_model->price_at?>';
	var sms_price = '<?=$this->Biz_model->price_sms?>';
	var lms_price = '<?=$this->Biz_model->price_lms?>';
	var mms_price = '<?=$this->Biz_model->price_mms?>';
	var phn_price = '<?=$this->Biz_model->price_phn?>';
<?if($mem->mem_2nd_send=='phn') { echo "var resend_price = '".$this->Biz_model->price_phn."';"; }
	else if($mem->mem_2nd_send=='sms') { echo "var resend_price = '".$this->Biz_model->price_sms."';"; }
	else if($mem->mem_2nd_send=='lms') { echo "var resend_price = '".$this->Biz_model->price_lms."';"; }
	else if($mem->mem_2nd_send=='mms') { echo "var resend_price = '".$this->Biz_model->price_mms."';"; }
	else if($mem->mem_2nd_send=='GREEN_SHOT') { echo "var resend_price = '".$this->Biz_model->price_grs."';"; }
	else if($mem->mem_2nd_send=='NASELF') { echo "var resend_price = '".$this->Biz_model->price_nas."';"; }

	else { echo "var resend_price = '0';"; }
?>

	var resend_type = ms;			<?/* 2차발신 종류 */?>
	var resend_type_name = "<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?>";	<?/* 2차발신 이름 */?>
	var charge_type = 0;				<?/* 발신단가 최대값 */?>
	<?/* 발신단가의 최대값과 재발신 방법을 설정 */?>
	function check_resend_method()
	{
		<?/* mms로 지정했어도 이미지가 없으면 LMS 또는 80자 미만이면 sms로 보내야 합니다. */?>
		<?/* 재발신 단가 산정 */?>
		if($('#lms_select').prop('checked')) {
			charge_type = resend_price;
			resend_type_name = "폰문자";
			resend_type = "P";
		} else {
    		if (ms=="M" && $("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "" && $('#lms').val().replace(/ /gi, "") != "" && resend_price > charge_type) {
        		charge_type = resend_price;
        		resend_type_name = "MMS";
        		resend_type = "M";
        	}
    		if ($("#img_url").val() == "" && $('#lms').val().replace(/ /gi, "") != "" && getByteLength($('#lms')) > 80 && lms_price > charge_type) {
        		charge_type = lms_price;
        		resend_type_name = "LMS";
        		resend_type = "L";
        	}

    		if ($("#img_url").val() == "" && $('#lms').val().replace(/ /gi, "") != "" && getByteLength($('#lms')) <= 80 && sms_price > charge_type){
        		charge_type = sms_price;
        		resend_type_name = "SMS";
        		resend_type = "S";
        	}
		}
		<?/* 알림톡 가격 산정 */?>
		if (Number(at_price) > Number(charge_type))	<?/* 알림톡 */?>
			{ charge_type = at_price; }
	}
<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>

	var senderBox = $('#sms_sender').val();
	coin=0;

	<?/*----------------------------------------------------------------------------------------------------------------*
	   | 전체고객정보 불러오기 하여 전체 발송버튼 클릭 [업로드 발송 포함] 또는 수신목록이 표시된 상태에서 전체발송 클릭 |
	   *----------------------------------------------------------------------------------------------------------------*/?>
	function all_send() {
		//var emptyVar = isEmptyVariable();
		var sendPhoneCount = 0;
		if($("#templi_code").val() == null) {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
// 		} else if (emptyVar !== true) {
// 			$(".content").html("템플릿 변수를 입력해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("input[name=var_name]")[emptyVar].focus();
// 			});
// 		} else if ($('#lms_select').prop('checked') && $("#lms").val().replace(/ /gi, "") == "") {
// 			$(".content").html("2차 발송 내용을 입력해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#lms").focus();
// 			});
		} else if ($("#hidden_cont").val().length > 1000) {
			$(".content").html("알림톡은 내용을 1,000자 이내로 입력하여야 합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("input[name=var_name]")[0].focus();
			});
// 		} else if (chkword_lmsCount() > 2000) {
// 			$(".content").html("2차 발송 내용은 2,000byte(한글 1,000자)이내로<br/>입력하여야 합니다.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#lms").focus();
// 			});
// 		} else if ($("#switch_upload").is(":checked") && parseInt($("#upload_tel_count").text()) == 0) {		// upload 발송
// 			$(".content").html("수신 전화번호 엑셀파일 업로드하세요.");
// 			$('#myModal').modal({backdrop: 'static'});
//     	} else if ($("#switch_select").is(":checked") && $("input[name=checkOne]:checked").length == 0) {	// 고객 DB에서 그룹 선택
//     		if ($("input[name=checkOne]:checked").length == 0) {
//     			$(".content").html("고객 그룹을 선택하세요.");
//     			$('#myModal').modal({backdrop: 'static'});
//     		}
    	} else if ($("span[name=input_phone_no]").length == 0) {	// 연락처 직접 입력
    		if ($("span[name=input_phone_no]").length == 0) {
    			$(".content").html("연락처를 입력하세요.");
    			$('#myModal').modal({backdrop: 'static'});
    		}
    	} else {
			var check = true;

			if (check == true) {
				$("div[name='field-data']").each(function () {
					var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
					if (btn_type != undefined) {
						var focus;
						var btn_url1 = $(this).find(document.getElementsByName("btn_url1")).val() || "";
						var btn_url2 = $(this).find(document.getElementsByName("btn_url2")).val() || "";
						if (btn_type == "WL") {
    						if (btn_url1 == "" || !checkURL(btn_url1)) {
    							check = false;
    							focus = $(this).find(document.getElementsByName("btn_url1"));
    							$(".content").html("웹링크 버튼의 Mobile 버튼링크를 입력해주세요.");
    							$("#myModal").modal('show');
    							$('#myModal').on('hidden.bs.modal', function () {
    								focus.focus();
    							});
    						} else if (btn_url2 == "" || !checkURL(btn_url2)) {
    							focus = $(this).find(document.getElementsByName("btn_url2"));
    							$(".content").html("웹링크 버튼의 PC 버튼링크를 입력해주세요.");
    							$("#myModal").modal('show');
    							$('#myModal').on('hidden.bs.modal', function () {
    								focus.focus();
    							});
    						}
    					} else if (btn_type == "AL") {
    						if (btn_url1 == "" || !checkURL(btn_url1)) {
    							check = false;
    							focus = $(this).find(document.getElementsByName("btn_url31"));
    							$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
    							$("#myModal").modal('show');
    							$('#myModal').on('hidden.bs.modal', function () {
    								focus.focus();
    							});
    						} else if (btn_url2 == "" || !checkURL(btn_url2)) {
    							check = false;
    							focus = $(this).parent().find(document.getElementsByName("btn_url32"));
    							$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
    							$("#myModal").modal('show');
    							$('#myModal').on('hidden.bs.modal', function () {
    								focus.focus();
    							});
    						}
    					}

					}
				});
			}


			if (check == true) {
				var _2ndflag = "N";
				if ($('#lms_select').prop('checked'))
					_2ndflag = "Y";

				$.ajax({
					url: "/dhnbiz/sender/coin",
					type: "POST",
					data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
			 		       "2ndflang":_2ndflag
						},
					success: function (json) {
						coin = json['coin'];
						var limit = parseInt(json['limit']);
						var sent = parseInt(json['sent']);
						var limit_msg = "";
						if(limit > 0 && sent >= limit) { showLimitOver("("+sent+"/"+limit+")"); return; }
						if(limit > 0) {
							limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>";
						}

						var kakao = Math.floor(Number(coin) / Number(at_price));
						var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');

						check_resend_method();
						// 재발신 가능 건수를 산정합니다.
						var resends = (charge_type > 0) ? Math.floor(Number(coin) / Number(charge_type)) : 0;
						var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');


						row_index = parseInt($("#input_phone_count").text());

						if (Number(coin) - (Number(charge_type) * Number(row_index)) < 0) {
							$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(charge_type) * Number(row_index)));
							$('#myModal').modal({backdrop: 'static'});
							$(document).unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".enter").click();
								}
							});
						} else {
							var content_msg = "알림톡 발송 가능 건수 : " + k + "건";
// 							if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
// 								content_msg += "<br/>" + "2차문자 발송 가능 건수 : " + resend_cnt + "건";
// 							}
							content_msg += "<br/><br/>" + "알림톡 발송 예상 금액 : " + (Number(at_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + at_price + ")";
// 							if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
// 								content_msg += "<br/>" + "2차문자 발송 예상 금액 : " + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + charge_type + ")";
// 							}

							$(".content").html(limit_msg + content_msg + "<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
							$("#myModalAll").modal({backdrop: 'static'});
							$("#myModalAll").unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".all").click();
								}
							});
							$(".all").click(function () {
								$("#myModalAll").modal('hide');
							});
						}

// 						if (!$("#switch_upload").is(":checked")) {
// 							var row_index = 0;

// 							if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
// 								row_index = parseInt($("#groupSendTotal").text());
// 							} else if ($("#switch_customer").is(":checked")) {	// 연락처 직접 입력
// 								row_index = parseInt($("#input_phone_count").text());
// 							}

// 							if (Number(coin) - (Number(charge_type) * Number(row_index)) < 0) {
// 								$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(charge_type) * Number(row_index)));
// 								$('#myModal').modal({backdrop: 'static'});
// 								$(document).unbind("keyup").keyup(function (e) {
// 									var code = e.which;
// 									if (code == 13) {
// 										$(".enter").click();
// 									}
// 								});
// 							} else {
// 								var content_msg = "알림톡 발송 가능 건수 : " + k + "건";
// 								if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
// 									content_msg += "<br/>" + "2차문자 발송 가능 건수 : " + resend_cnt + "건";
// 								}
// 								content_msg += "<br/><br/>" + "알림톡 발송 예상 금액 : " + (Number(at_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + at_price + ")";
// 								if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
// 									content_msg += "<br/>" + "2차문자 발송 예상 금액 : " + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + charge_type + ")";
// 								}

// 								$(".content").html(limit_msg + content_msg + "<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
// 								$("#myModalAll").modal({backdrop: 'static'});
// 								$("#myModalAll").unbind("keyup").keyup(function (e) {
// 									var code = e.which;
// 									if (code == 13) {
// 										$(".all").click();
// 									}
// 								});
// 								$(".all").click(function () {
// 									$("#myModalAll").modal('hide');
// 								});
// 							}
// 						} else {
							<?/* 업로드 된 건수 */?>
// 							var upload_send = $("#upload_tel_count").text();
							<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
// 							if (Number(coin) - (Number(charge_type) * Number(upload_send)) < 0) {
// 								$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(charge_type) * Number(upload_send)));
// 								$('#myModal').modal({backdrop: 'static'});
// 								$(document).unbind("keyup").keyup(function (e) {
// 									var code = e.which;
// 									if (code == 13) {
// 										$(".enter").click();
// 									}
// 								});
// 							} else {
// 								var content_msg = "알림톡 발송 가능 건수 : " + k + "건";
// 								if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
// 									content_msg += "<br/>" + "2차문자 발송 가능 건수 : " + resend_cnt + "건";
// 								}
// 								content_msg += "<br/><br/>" + "알림톡 발송 예상 금액 : " + (Number(at_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + at_price + ")";
// 								if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
// 									content_msg += "<br/>" + "2차문자 발송 예상 금액 : " + (Number(charge_type) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + charge_type + ")";
// 								}
// 								$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까? (발송할 건수 : " + upload_send + "건)");

// 								$("#myModalAll").modal({backdrop: 'static'});
// 								$(document).unbind("keyup").keyup(function (e) {
// 									var code = e.which;
// 									if (code == 13) {
// 										$(".all").click();
// 									}
// 								});
// 								$(".all").click(function () {
// 									$("#myModalAll").modal("hide");
// 								});
// 							}
// 						}
					}
				});
			}
		}
	}

	/**
	 * 한글포함 문자열 길이를 구한다
	 */
	function getTextLength(str) {
		var len = 0;
		for (var i = 0; i < str.length; i++) {
			if (escape(str.charAt(i)).length == 6) {
				len++;
			}
			len++;
		}
		return len;
	}
	function cutTextLength(msg, len) {
		var text = msg;
		var leng = text.length;
		while(getTextLength(text) > len){
			leng--;
			text = text.substring(0, leng);
		}
		return text;
	}

	<?/*--------------------------------------------------------------------------------*
		| 전체발송(전체고객정보불러오기 포함) 버튼 클릭시 실제 발송 처리하는 부분입니다. |
		*--------------------------------------------------------------------------------*/?>
	function all_move() {
		var nextNavigateURL = "/dhnbiz/sender/history";
		var templi_cont = $("#hidden_cont").val();
		var code = document.getElementById("templi_code").value;
		var profile = document.getElementById("pf_key").value;
		var kind = document.getElementById("kind").value;
		var couponid = $("#couponid").val();
		var kind = ms;
		var lms_header_temp = "";
		var lms_footer_temp = "";
		var msg = "";
// 		if ($("input:checkbox[id='lms_select']").is(":checked") == true && $("#lms").val().trim() != "") {
//     		lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
//     		lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
//     		msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
// 		}


		var reserveDt = "00000000000000";
// 		if($("#reserve_check").is(":checked") == true) {
// 			if($("#reserve").val().trim() != "" && $("#reserve_hours").val() != "" && $("#reserve_minutes").val() != ""){
// 				var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
// 				var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
// 				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "") + reserve_hours + reserve_minutes;
// 				reserveDt = reserve+"00";
// 			}
// 		}

		var btn = [];
		for (var b = 1; b <= 5; b++) {
			btn[b] = new Array();
		}
		var obj = [];
		var btn_num = 1;

		$("div[name='field-data']").each(function () {
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			if (btn_type != undefined) {
				obj[btn_num] = new Object();
				obj[btn_num].type = btn_type;
				obj[btn_num].name = $(this).find(document.getElementsByName("btn_name")).val();
				if (btn_type == "WL") {
					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url1")).val();
					if ($(this).find(document.getElementsByName("btn_url2")).val() != undefined) {
					obj[btn_num].url_pc = $(this).find(document.getElementsByName("btn_url2")).val();
					}
				} else if (btn_type == "AL") {
					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url1")).val().trim();
					obj[btn_num].scheme_ios = $(this).find(document.getElementsByName("btn_url2")).val().trim();
				}
				btn[btn_num].push(obj[btn_num]);
				btn_num++;
			}
		});
		var btn1 = JSON.stringify(btn[1]);
		var btn2 = JSON.stringify(btn[2]);
		var btn3 = JSON.stringify(btn[3]);
		var btn4 = JSON.stringify(btn[4]);
		var btn5 = JSON.stringify(btn[5]);

		// 2019.01.31. 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
		var mst_type1 = '';
		var mst_type2 = '';

		mst_type1 = 'at';
		if(msg != '') {		// 2차 문자가 있으면(웹(A)LMS : wa, 웹(A)SMS : was, 웹(A) MMS : wam, 웹(B)LMS : wb, 웹(B)SMS : wbs, 웹(B) : wbm, 폰문자: phn
			var temp_mst_kind = "<?= $mem->mem_2nd_send ?>";
			if(temp_mst_kind == "GREEN_SHOT") {
				var temp_resend_type = "";
				if (resend_type != "L" && resend_type != "P") {
					temp_resend_type = resend_type.toLowerCase();
				}
				mst_type2 = "wa" + temp_resend_type;
			} else if (temp_mst_kind == "NASELF"){
				var temp_resend_type = "";
				if (resend_type != "L" && resend_type != "P") {
					temp_resend_type = resend_type.toLowerCase();
				}
				mst_type2 = "wb" + temp_resend_type;
			} else if (temp_mst_kind == "phn") {
				mst_type2 = temp_mst_kind;
			}
		}
		// 2019.01.31. 이수환 추가 끝 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)

		if ($("span[name=input_phone_no]").length > 0) {	// 연락처 직접 입력
			var tel_number = new Array();
			$("span[name=input_phone_no]").each(function () {
				//var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
				var array = $(this).text().replace(/-/gi, "");
				tel_number.push(array);
			});

			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/dhnbiz/sender/talk/send",
				type: "POST",
				data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
						  "tel_number[]":tel_number,"templi_cont":templi_cont,"msg":msg,"kind":kind,
					"templi_code":code,"pf_key":profile,"senderBox":senderBox,
					"btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"reserveDt":reserveDt, "couponid":couponid,
					"mst_type1" : mst_type1, "mst_type2" : mst_type2	// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
				},
				beforeSend: function () {
	                           $('#overlay').fadeIn();
	                       },
				complete: function () {
								$('#overlay').fadeOut();
	                       },
				success: function (json) {
						$('#navigateURL').val("");
						var code = json['code'];
						var message = json['message'];
						if(code == "success") {
							//$('#navigateURL').val(document.location.href);
							$('#navigateURL').val(nextNavigateURL);
							//alert($('#navigateURL').val());
							$(".content").html("발송 요청되었습니다.");
							$('#myModal').modal({backdrop: 'static'});
							$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
							$(document).unbind("keyup").keyup(function (e) {
								 var code = e.which;
								 if (code == 13) {
									  $(".enter").click();
								 }
							});
						} else {
							$(".content").html("발송 실패되었습니다.<br/>"+message);
							$('#myModal').modal({backdrop: 'static'});
							$(document).unbind("keyup").keyup(function (e) {
								 var code = e.which;
								 if (code == 13) {
									  $(".enter").click();
								 }
							});
						}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			});
		}

	}

	//글자수 제한 - 템플릿 내용
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

	function bindKeyUpEvent() {
		//글자수 제한 - LMS 내용
		$("#lms").keyup(function(){
			chkbyte($("#lms"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>);
		});
	}

	bindKeyUpEvent();

	// 템플릿 선택 버튼
	function btnSelectTemplate() {
		var count = '';
		console.log('count', count);
		if('' == "none") {
			$('#template_danger').modal('show');
		} else {
			$("#template_select").modal({backdrop: 'static'});
			$('#template_select').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					console.log('code == 27');
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
			"/dhnbiz/sender/send/template_dt",
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
					form.setAttribute("action", "/dhnbiz/sender/send/template_dt");
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
			form.setAttribute("action", "/dhnbiz/sender/send/template_dt");
				var csrfField = document.createElement("input");
				csrfField.setAttribute("type", "hidden");
				csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
				form.appendChild(csrfField);
			form.submit();
		}
	}

	//업로드 양식 다운로드
	function download() {
		if ($("#templi_code").val() == null) {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			document.location.href="/uploads/number_list.xlsx";
		}
	}

	//업로드
	function readURL(input) {
		var file = document.getElementById('filecount').value;
		file = file.slice(file.indexOf(".") + 1).toLowerCase();
		if (file == "xls" || file == "xlsx" || file=="txt") {
			var formData = new FormData();
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("templ_id", "<?=$tpl->tpl_id?>");
			formData.append("templ_profile", "<?=$tpl->tpl_profile_key?>");
			formData.append("templ_code", "<?=$tpl->tpl_code?>");
			formData.append("file", $("input[name=filecount]")[0].files[0]);
			formData.append("ext", file);
			$.ajax({
				url: "/dhnbiz/sender/upload",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					var status = json['status'];
					if (status == 'error') {
						var msg = json['msg'];
						$(".content").html("올바르지 않은 파일입니다.<br/>"+msg);
						$('#myModal').modal({backdrop: 'static'});
						$('#filecount').filestyle('clear');
					} else if (json['nrow_len'] > 60000) {
						$(".content").html("대량 발송은 최대 6만건까지 가능합니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#filecount').filestyle('clear');
					} else if (json['nrow_len'] != undefined){
						var bulk_send = json['nrow_len'];
						var upload_file = json['upload_file'];
						var coin = json['coin'];
						var limit_count;
						if (ms != "") {
							limit_count = Number(resend_price)*Number(bulk_send);
						} else {
							limit_count = Number(at_price)*Number(bulk_send);
						}
						if(coin >= limit_count) {
							$("#number_add").hide();
							$("#select_del").hide();
							$("#select_send").hide();
							$("#tel").hide();
							var hidden = $("#hidden_cont").val();
							var hidden_length = hidden.length;
							$("#type_num").html(hidden_length);
							var message = $("#text").val(hidden);
							var height = $("#text").prop("scrollHeight");
							if (height < 468) {
								$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
							} else {
								var message = $("#text").val();
								var height = $("#text").prop("scrollHeight");
								if (message == "") {
									$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
								} else {
									$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
									$("#templi_cont").keyup(function() {
										$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
									});
								}
							}
/*
							if (ms!="") {
								$("#lms").val("").attr("readonly", true).css("cursor", "default").css("background-color", "#EEEEEE");
								$("#lms_num").html(0);
							}
							$("#alimtalk_table tr").each(function () {
								var hidden_url = $(this).find("#hidden_url").val();
								$(this).find("#btn_url").val(hidden_url);
								$(this).find("#btn_url").attr("readonly",true);
								$(this).find("#btn_url").css("cursor","default")
							});
							$("#templi_cont").val(hidden).attr("readonly", true).css("background-color", "#EEEEEE");
							*/
							//$(".reserve").attr("hidden", true); //대량발송 예약 막음
							//$(".reserve_content").attr("hidden", true); //대량발송 예약 막음
							//$("input:checkbox[id='reserve_check']").removeAttr('checked'); //대량발송 예약 막음
							//$("#reserve_check").uniform(); //대량발송 예약 막음
								 $("#upload_result").remove();

							$(".tel_content").after('<div class="widget-content" id="upload_result"><p>업로드 결과 : ' + bulk_send + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="bulk_send" value="' + bulk_send + '"></div><br>');
						} else {
							$(".content").html("충전 잔액이 부족합니다.");
							$('#myModal').modal({backdrop: 'static'});
							$('#filecount').filestyle('clear');
						}
					} else {
						var cols = json['col_value'];
						var rows = new Array();

						var btnMaxCols = 1;
						var btn_content;

						var buttons = '<?=$tpl->tpl_button?>';
						var btn = buttons.replace(/&quot;/gi, "\"");
						btn_content = JSON.parse(btn);
						for (var i=0;i<=btn_content.length-1;i++) {
							if (btn_content[i]["linkType"] == "WL" && btn_content[i]["linkPc"] != null || btn_content[i]["linkType"] == "AL") {
								btnMaxCols = btnMaxCols + 2;
							} else if (btn_content[i]["linkType"] == "WL" && btn_content[i]["linkPc"] == null) {
								btnMaxCols ++;
							}
							if(i==btn_content.length-1){
								break;
							}
						}


						for (var i=0;i<cols.length;i++){
							var num = cols[i][0].replace(/[^ㄱ-ㅎ가-힣|^a-z|^A-Z|^0-9\n,.]/gim, "").replace(/\.+0/,"").replace(/-/gim, ""); // 전화번호
							var tmpli_cont = String(cols[i][1]); // 템플릿 내용
							var btn_cont = true;
							if (btnMaxCols > 1) {
								for (var btn = 2; btn <= btnMaxCols; btn++) {
									if (String(cols[i][btn]).replace(/\s/gi, '') == "") {
										btn_cont = false;
									}
								}
							}
							if(ms!='') {
								var sms_cont = String(cols[i][2]); // 2차발신 내용
								if (btnMaxCols > 1) {
									sms_cont = String(cols[i][Number(btnMaxCols)+1]); // 2차발신 내용(버튼이 있을경우 밀려남)
									if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "" && sms_cont.replace(/\s/gi, '') != "" && btn_cont != false) {
										rows.push(cols[i]);
									}
								} else {
									if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "" && sms_cont.replace(/\s/gi, '') != "") {
										rows.push(cols[i]);
									}
								}
							} else if (btnMaxCols > 1) {
								if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "" && btn_cont != false) {
									rows.push(cols[i]);
								}
							} else {
								if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "") {
									rows.push(cols[i]);
								}
							}
						}
						if (rows.length == 0) {
							$(".content").html("파일의 내용이 모두 누락되었습니다.");
							$('#myModal').modal({backdrop: 'static'});
							$('#filecount').filestyle('clear');
						} else {
							$("#text").val(rows[0][1]);
							var height = $("#text").prop("scrollHeight");
							if (height < 468) {
								$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
							} else {
								var message = $("#text").val();
								var height = $("#text").prop("scrollHeight");
								if (message == "") {
									$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
								} else {
									$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
								}
							}
							/*
							var hidden = $("#hidden_cont").val();
							var hidden_length = hidden.length;
							$("#type_num").html(hidden_length);
							$("#templi_cont").val(hidden);
							//$("#templi_cont").attr("readonly",true);
							$("#templi_cont").css("background-color","#EEEEEE");
							if (btnMaxCols > 1) {
								$("#alimtalk_table tr").each(function () {
									var hidden_url = $(this).find("#hidden_url").val();
									$(this).find("#btn_url").val(hidden_url);
									$(this).find("#btn_url").attr("readonly",true);
									$(this).find("#btn_url").css("cursor","default")
								});
							}
							if (ms!="") {
								$("#lms").val("");
								$("#lms").attr("readonly",true);
								$("#lms").css("cursor","default");
								$("#lms").css("background-color","#EEEEEE");
								$("#lms_num").html(0);
							}
							$("#number_add").unbind("click");
							$("#number_add").click(function () {
								if(document.getElementById('filecount').value!="") {
									$(".content").html("파일 업로드 되어 번호를 추가할 수 없습니다.");
									$("#myModal").modal({backdrop: 'static'});
								}
							});
							var fail = cols.length-rows.length;
							if(fail != 0 && cols.length != fail) {
								$(".content").html("파일의 내용 중 누락된 데이터가 " + fail + "건 있습니다.");
								$('#myModal').modal({backdrop: 'static'});
							}
							if (rows.length < 6) {
								for (var i = 0; i < rows.length; i++) {
									var tr = '';
									var num = String(rows[i][0]).trim().replace(/-/gim, "");
									var temp = String(rows[i][1]).replace(/"/gim,'&quot;');
									var no = i + 1;
									tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
											'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td>' +
											'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
											'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" value="' + num + '" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">' +
											'<input type="hidden" id="tel_temp" name="tel_temp" value="'+temp+'"/>';
									if(btnMaxCols > 1) {
										var url_count = 1;
										for (var b = 2 ; b<=btnMaxCols ; b++) {
											var btn_url = String(rows[i][b]).trim();
											tr += '<input type="hidden" id="tel_url' + url_count + '" name="tel_url" value="'+btn_url+'"/>';
											url_count++;
										}
										if (ms!="") {
											var sms = String(rows[i][Number(btnMaxCols) + 1]);
											tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="' + sms + '"/>';
										}
									}
									if (ms!="") {
										var sms = rows[i][2];
										tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="'+sms+'"/>';
									}
									tr += '</td>' +
											'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
											'</tr>';
									no = no - 1;
									if (i == 0) {
										$("#tel_tbody").html(tr);
										$("#tel_tbody").css("height", "47px");
									} else {
										$("." + no).after(tr);
										var height = $("#tel_tbody").prop("scrollHeight");
										$("#tel_tbody").css("height", (height) + "px");
									}
									no = no + 1;
									$("#" + no).uniform();
								}
							} else {
								for (var i = 0; i < rows.length; i++) {
									var tr = '';
									var num = String(rows[i][0]).trim().replace(/-/gim, "");
									var temp = String(rows[i][1]).replace(/"/gim,'&quot;');
									var no = i + 1;
									tr += '<tr class="' + no + '" id="tel_tbody_tr">' +
													'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td>' +
													'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
													'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" value="' + num + '" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">' +
													'<input type="hidden" id="tel_temp" name="tel_temp" value="'+temp+'"/>';
									if(btnMaxCols > 1) {
										var url_count = 1;
										for (var b = 2 ; b<=btnMaxCols ; b++) {
											var btn_url = String(rows[i][b]).trim();
											tr += '<input type="hidden" id="tel_url' + url_count + '" name="tel_url" value="'+btn_url+'"/>';
											url_count++;
										}
										if (ms!="") {
											var sms = String(rows[i][Number(btnMaxCols) + 1]);
											tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="' + sms + '"/>';
										}
									}
									if (ms!="") {
										var sms = rows[i][2];
										tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="'+sms+'"/>';
									}
									tr += '</td>' +
													'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
													'</tr>';
									no = no - 1;
									if (i == 0) {
										$("#tel_tbody").html(tr);
										$("#tel_tbody").css("height", "47px");
									} else {
										$("." + no).after(tr);
										$("#tel_tbody").css("height", "235px");
									}
									no = no + 1;
									$("#" + no).uniform();
								}
							}*/
						}
					}
				}
			});
		} else {
			$(".content").html("xls,xlsx 파일만 가능합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$('#filecount').filestyle('clear');
				var table = document.getElementById("tel");
				var row_index = table.rows.length;

				for (var i = 1; i < row_index; i++) {
					var tr = $("." + i);
					tr.remove();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "0px");
				}

				var add = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
						'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>' +
						'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
						'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
						'</tr>';
				$("#tel_tbody").html(add);
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "47px");
				$("#1").uniform();
			});
			$(document).unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					$(".enter").click();
				}
			});
		}
	}

	// 쿠폰 관련 미리보기  함수들

	function view_preview() {
		$("#image").remove();
		$("#btn_preview1").remove();
		$("#pre_title").remove();

		templi_preview(document.getElementById('templi_cont'));
		scroll_prevent();
		templi_chk();
		link_preview();
	}

	function view_result() {
		if($("#cp_img_link").val()=='') {
			alert("이미지를 선택 하세요.");
		} else {
			$("#image").remove();
			$("#btn_preview1").remove();
			$("#pre_title").remove();

			readImage($("#cp_img_link").val());
    		re_link_preview();
    		re_templi_preview($("#id_cc_memo").get(0));
    		scroll_prevent();
    		templi_chk();
		}
	}

	//업로드 선택시
	function upload() {
		if ($("#templi_code").val() == null) {
			$('#filecount').attr('disabled','disabled');

			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});

			$(".enter").click(function () {
				$("#filecount").removeAttr("disabled");
			})
		} else {
			$('#filecount').attr('disabled','disabled');
			$(".content").html("입력하신 수신 번호가 초기화됩니다.<br/>업로드 하시면 업로드 파일의 내용으로 발송됩니다.<br/>업로드 하시겠습니까?");
			$('#myModalUpload').modal({backdrop: 'static'});
			$('#myModalUpload').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default").click();
				} else if (code == 13) {
					check();
				}
			});
			$(".up").click(function () {
				check();
			});
		}
	}
	function check(){
		if($('#filecount').is('[disabled=disabled]')==true) {
			if($("#bulk_send").val()!=undefined){
				$("#upload_result").remove();
				$("#bulk_send").remove();
				$("#tel").show();
				$("#number_add").show();
				$("#select_del").show();
				$("#select_send").show();
			}
			$(".reserve").attr("hidden", false); //대량발송 예약 막음
			$("#filecount").removeAttr("disabled");

			$("#myModalUpload").modal('hide');
			var contents = document.getElementById('templi_cont').value;
			$("#text").css("height","1px").css("height",($("#text").prop("0px")));
			$('#text').val(contents);
			var height = $("#text").prop("scrollHeight");
			if (height < 460) {
				$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
			} else {
				$("#text").css("height", "1px").css("height", "460px");
			}
			$("#templi_cont").attr("readonly",false);
			$("#templi_cont").css("background-color","white");
			if (ms!="") {
				$("#lms").attr("readonly",false);
				$("#lms").css("cursor","default");
				$("#lms").css("background-color","white");
			}
			$("#alimtalk_table tr").each(function () {
				$(this).find("#btn_url").attr("readonly",false);
			});

			if($('#filecount').val()!=""){
				$("#number_add").click(function () {
					add_number();
				});
			}
			$('#filecount').filestyle('clear');
			var table = document.getElementById("tel");
			var row_index = table.rows.length;
			for (var i = 1; i < row_index; i++) {
				var tr = $("." + i);
				tr.remove();
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "0px");
				var tr = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
						'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>' +
						'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
						'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
						'</tr>';
				$("#tel_tbody").html(tr);
				var height = $("#tel_tbody").prop("scrollHeight");
				$("#tel_tbody").css("height", "47px");
				$("#1").uniform();
			}
			$("#filecount").removeAttr("disabled");
			$("#filecount").attr('onclick', '');
			$("#filecount").click();
			$(".up").unbind("click");
			return $("#filecount").attr('onchange','readURL()');
		}
	}

	function insert_kakao_link(check){
		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;
		$.ajax({
			url: "/dhnbiz/short_url",
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
					chkword_lms();
				} else {
					if ((new RegExp(/무료수신거부/gi).test(cont))) {
						index = cont.indexOf("무료수신거부");
						var first = cont.slice(0, index - 1).replace(short_url, "");
						var last = cont.slice(index);
						$("#lms").val(first + last).focus();
						chkword_lms();
					} else {
						index = cont.indexOf(short_url);
						$("#lms").val(cont.slice(0, index - 1)).focus();
						chkword_lms();
					}
					if ($("#lms").prop("scrollHeight") < 412 && $("#lms").prop("scrollHeight") > 103) {
						$("#lms").css("height", "1px").css("height", ($("#lms").prop("scrollHeight") - 1) + "px");
					} else {
						resize_cont(document.getElementById("lms"));
					}
					chkword_lms();
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

	//글자수 제한 - 2차발신 내용
	function chkword_lms() {
		chkbyte($("#lms"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>);
	}

	function chkbyte($obj, $num, maxByte) {
		var oriMaxByte = maxByte;
		var phn = '<?=$this->Biz_model->reject_phn?>';
		var phn_len = phn.length + 16;

		var strValue = $obj.val();
		var strLen = strValue.length;
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
			 // 입력한 문자 길이보다 넘치면 잘라내기 위해 저장
			if (totalByte <= maxByte) {
				 len = i + 1;
			}
		}
		$num.html(totalByte);

		// 넘어가는 글자는 자른다.
		if (totalByte > maxByte) {
			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 " + oriMaxByte + "자(한글 " + parseInt(oriMaxByte / 2) + "자) 이내로<br/>입력해주세요.<br/>(실제내용 " + (maxByte - phn_len - 20) + "자 이내)");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});

<?if($mem->mem_2nd_send=='phn') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입해요 */ ?>
			if ($obj.val().lastIndexOf("무료수신거부") > -1) {
				strValue = $obj.val().substr(0, $obj.val().lastIndexOf("무료수신거부"));
			}
			str2 = strValue.substr(0, len - phn_len - 20) + "\r무료수신거부 : " + phn;
<?} else {?>
			str2 = $obj.val().substr(0, len);
<?}?>
			$obj.val(str2);
			$num.html(totalByte);
		 chkbyte($obj, $num, oriMaxByte);
	  }
   }

	function getByteLength($obj)
	{
		var strValue = $obj.val();
		var strLen = strValue.length;
		var totalByte = 0;
		var oneChar = "";

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

	//lms 발신여부 체크박스
	function select_lms(check){
		var sms_sender = $("#sms_sender").val();
		if (sms_sender == "None" || sms_sender.replace(/ /gi, "") == "") {
			$(".content").html("발신번호가 등록되지 않았습니다.<br>" +
				"발신프로필 목록에서 발신번호를 등록해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$("input:checkbox[id='lms_select']").removeAttr('checked');
			$(".uniform").uniform();
		} else {
			if (check.checked) {
				var pf_ynm = $("#pf_ynm").val();
				var phn = '<?=$this->Biz_model->reject_phn?>';
					 var cont = $("#templi_cont").val();
<?if($mem->mem_2nd_send=='phn') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입해요 */ ?>
					 if(cont.indexOf("(광고)") < 0) { cont = "(광고)" + cont; }
				if (phn != "None" && phn.replace(/ /gi, "") != "") {
						if(cont.indexOf("무료수신거부") < 0) { cont = cont + "\n\n무료수신거부 : " + phn; }
				} else {
						if(cont.indexOf("무료수신거부") < 0) { cont = cont + "\n\n무료수신거부 : "; }
				}
<?}?>
				if ($("#lms").val().replace(/ /gi, "") == "") {
					$("#lms").val(cont);
					$("#text").val(cont);
				}
				$("#lms_kakaolink_select").removeAttr("disabled");
				$("#lms_kakaolink_select").uniform();
				$("#lms").removeAttr("disabled").css("background-color", "white").focus();
				resize_cont(document.getElementById("lms"));

				//미리보기 링크 삭제
				$("#friendtalk_table tr").each(function () {
					if ($(this).find("#no").text()) {
						var name = $(this).find("#no").parent().attr("name");
						$("#btn_preview_div" + name).remove();
					}
				});

				$("#text").css("margin-bottom", "0px");

				var height = $("#text").prop("scrollHeight");
				if (height < 408) {
					$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
				} else {
					var message = $("#text").val();
					var height = $("#text").prop("scrollHeight");
					if (message == "") {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
					} else {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					}
				}
				chkword_lms();
			} else {
				//카카오 친구추가 링크 체크박스 선택 되어있을 경우 해제
				if ($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true) {
					$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
				}
				$("#lms_kakaolink_select").attr("checked", false).attr('disabled', true);
				$("#lms_kakaolink_select").uniform();
				$("#lms").val("").css("background-color", "#EEEEEE").attr('disabled', 'disabled').css("height", "103px");
				$('.uniform').uniform();

				//미리보기 친구톡으로 변경되는 부분
				var templi_cont = $("#templi_cont").val();
				$("#text").val(templi_cont);
				//미리보기 링크 보이기
				$("#friendtalk_table tr").each(function () {
					if ($(this).find("#no").text()) {
						var current_btn_num = $(this).find("#no").parent().attr("name");
						link_name(document.getElementById("btn_type" + current_btn_num), current_btn_num);
					}
				});
				$("#text").css("margin-bottom", "10px");
				var height = $("#text").prop("scrollHeight");
				if (height < 408) {
					$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
				} else {
					var message = $("#text").val();
					var height = $("#text").prop("scrollHeight");
					if (message == "") {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
					} else {
						$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
						$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
					}
				}
			}
		}
	}

	// 고객정보 선택 불러오기
	function load_customer_select() {
		if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			if ($("#upload_result").val() != undefined) {
				$("#upload_result").remove();
				check('customer_select');
			}

			$("#myModalLoadCustomers").modal({backdrop: 'static'});
			$("#myModalLoadCustomers").on('shown.bs.modal', function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			});
			$('#myModalLoadCustomers').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default.dismiss").click();
				} else if (code == 13) {
					include_customer()
				}
			});
			$("#myModalLoadCustomers .include_phns").click(function () {
				include_customer();
			});
			open_page(1);
		}
	}

	// 고객정보 구분 불러오기 2019.01.18 이수환 추가
	function load_customer_gubun() {
		load_customer_all($('#searchGroup2').val());
	}

	// 고객정보 전체 불러오기
	function load_customer_all(filter) {
		filter = ((typeof(filter)=='undefined' || filter==null) ? '' : filter);
		if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			if (document.getElementById('filecount').value != '') {
				 check('customer_all');
			}
			$.ajax({
				 url: "/dhnbiz/sender/friend/load_customer",
				 data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				 type: "POST",
				 success: function (json) {
					  var customer_count = json['customer_count'];
					  if (customer_count > 60000) {
							$(".content").html("최대 60,000명까지 가능합니다.");
							$('#myModal').modal({backdrop: 'static'});
					  } else if (customer_count > 0) {
							$("#number_add").hide();
							$("#number_del").hide();
							$("#send_select").hide();
							$("#tel").hide();
							/*
							$(".reserve").attr("hidden", true); //대량발송 예약 막음
							$(".reserve_content").attr("hidden", true); //대량발송 예약 막음
							$("input:checkbox[id='reserve_check']").removeAttr('checked'); //대량발송 예약 막음
							$("#reserve_check").uniform(); //대량발송 예약 막음
							*/
							$("#upload_result").remove();
							// 2019.01.17. 이수환 고객구분없음 추가 및 수정
							var filterText = (filter == 'NONE') ?  '고객구분없음' : filter;
							$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filterText)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
							//$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filter)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
					  } else {
							$(".content").html("고객정보가 없습니다.");
							$('#myModal').modal({backdrop: 'static'});
					  }
				 },
				 error: function (data, status, er) {
					  $(".content").html("처리중 오류가 발생하였습니다.");
					  $("#myModal").modal('show');
				 }
			});
			$("#myModalFilterAll").modal('hide');
		}
	}

	// 친구톡 실패 고객정보 전체 불러오기
	function load_customer_nft(filter) {
		filter = ((typeof(filter)=='undefined' || filter==null) ? '' : filter);
		/*if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else*/ {
			if (document.getElementById('filecount').value != '') {
				 check('customer_all');
			}
			$.ajax({
				 url: "/dhnbiz/sender/friend/load_customernft",
				 data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				 type: "POST",
				 success: function (json) {
					  var customer_count = json['customer_count'];
					  if (customer_count > 60000) {
							$(".content").html("최대 60,000명까지 가능합니다.");
							$('#myModal').modal({backdrop: 'static'});
					  } else if (customer_count > 0) {
							$("#number_add").hide();
							$("#number_del").hide();
							$("#send_select").hide();
							$("#tel").hide();
/*
							$(".reserve").attr("hidden", true); //대량발송 예약 막음
							$(".reserve_content").attr("hidden", true); //대량발송 예약 막음
							$("input:checkbox[id='reserve_check']").removeAttr('checked'); //대량발송 예약 막음
							$("#reserve_check").uniform(); //대량발송 예약 막음
							*/
							$("#upload_result").remove();
							$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filter)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="friend_list" value="' + customer_count + '"></div>');
					  } else {
							$(".content").html("고객정보가 없습니다.");
							$('#myModal').modal({backdrop: 'static'});
					  }
				 },
				 error: function (data, status, er) {
					  $(".content").html("처리중 오류가 발생하였습니다.");
					  $("#myModal").modal('show');
				 }
			});
			$("#myModalFilterAll").modal('hide');
		}
	}

	function include_customer(group ) {
		if(($("#sel_all").prop('checked') && $('#searchGroup').val()!='') || group == 'GS') {
			// 2019.01.17 이수환 고객구분없음 추가로 추가및 수정
			var searchGroupText = $('#searchGroup').val();
			if(searchGroupText == "NONE") {
				searchGroupText = "고객구분없음";
			}
			//$(".content").html("선택하신 " + $('#searchGroup').val() + " 고객 전체를 추가 하시겠습니까?");
			$(".content").html("선택하신 " + searchGroupText + " 고객 전체를 추가 하시겠습니까?");
			$('#myModalFilterAll').modal({backdrop: 'static'});
			$('#myModalFilterAll').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default").click();
				} else if (code == 13) {
					load_customer_all($('#searchGroup').val());
					return;
				}
			});
			$(".filter_all").click(function () {
				load_customer_all($('#searchGroup').val());
				return;
			});
		}
		$("input[name='selCustomer']:checked").each(function () {
			var checked = $(this).parents('tr:eq(0)');
			var kind = $(checked).find('td:eq(2)').find('#kind').val();
			var name = $(checked).find('td:eq(3)').text();
			var phn = $(checked).find('td:eq(4)').text();
			add_number(phn, kind, name);
		});
		$("#myModalLoadCustomers").modal('hide');
		$("#myModalLoadCustomers .include_phns").unbind("click");
	}

	//발신번호 추가
	function add_number(phn, kind, name) {
		  if(typeof(kind)=="undefined") { kind = ""; }
		  if(typeof(name)=="undefined") { name = ""; }
		var lastItemNo = $("#tel tbody tr:last").attr("class");
		var no = parseInt(lastItemNo) + 1;
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		var phnNumVal = '';
		if (typeof phn != 'undefined') {
			phnNumVal = ' value=' +  phn + ' ';
		}

		if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("템플릿을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else if ($("input:checkbox[value='1']").is(":checked") == true) {
			var custom_check = row_index-1;
			if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
				$("."+custom_check).remove();
				no=1;
			}

			if (row_index <= 60000) {
				if (row_index >= 5) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input class="uniform" name="sel_del" id="'+ no +'" value="' + no + '" type="checkbox"></td>' +
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' + phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr."+no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' +  phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					if (no == 1) {
						$("#tel_tbody").html(tr);
						$("#tel_tbody").css("height", "47px");
					} else {
						$("." + lastItemNo).after(tr);
					}
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#"+no).uniform();
				}
			} else {

				$(".content").html("최대 60,000개까지 가능합니다.");
				$("#myModal").modal({backdrop: 'static'});
			}
		} else {
			var custom_check = row_index-1;
			if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
				$("."+custom_check).remove();
				no=1;
			}

			if (row_index <= 60000) {
				if (row_index > 5) {
					var tr = '<tr class="' + no + '" name="'+no+'" id="tel_tbody_tr" name="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input type="checkbox" name="sel_del" id="'+ no +'"  value="'+no+'" class="uniform"></td>'+
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' + phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr."+no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' +  phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					if (no == 1) {
						$("#tel_tbody").html(tr);
						$("#tel_tbody").css("height", "47px");
					} else {
						$("." + lastItemNo).after(tr);
					}
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#"+no).uniform();
				}
			} else {

				$(".content").html("최대 60,000개까지 가능합니다.");
				$("#myModal").modal({backdrop: 'static'});
			}
		}
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

		console.log('open_page', page);

		$('#myModalLoadCustomers .widget-content').html('').load(
			"/dhnbiz/customer/inc_lists",
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

	//이미지 선택시-에이젝스
	function imageSelect() {
		cw = screen.availWidth;     //화면 넓이
		ch = screen.availHeight;    //화면 높이
		sw = 720;    //띄울 창의 넓이
		sh = 630;    //띄울 창의 높이
		ml = (cw - sw) / 2;        //가운데 띄우기위한 창의 x위치
		mt = (ch - sh) / 2;         //가운데 띄우기위한 창의 y위치

		imgSelectBox = window.open("/dhnbiz/sender/images", 'tst', 'width=' + sw + ',height=' + sh + ',top=' + mt + ',left=' + ml + ',location=no, resizable=no');
	}

	function setImageValue(selctImgValue, selctImgLink) {
		$("#img_url").val(selctImgValue);

		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		$("input[name=img_link]").val(selctImgLink);
	}

	$(document).ready(function() {
		$('#send_template_content').html('').load(
			"/dhnbiz/sender/send/select_template_dt",
			{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				tmp_code: "<?=$param['tmp_code']?>",
				tmp_profile: "<?=$param['tmp_profile']?>",
				iscoupon:"<?=$param['iscoupon']?>",
				sb_send: "<?=$param['sb_send']?>",
				sb_id: "<?=$param['sb_id']?>",
				sb_kind: "<?=$param['sb_kind']?>"
		   }, function() {

				templi_preview(document.getElementById('templi_cont'));
			   link_preview();
			   templi_chk();
			   bindKeyUpEvent();
			   <?
					   if($nft) {
					       ?>
					       load_customer_nft();
					       <?
					   }
					   ?>
		   }
		);

	});
</script>
