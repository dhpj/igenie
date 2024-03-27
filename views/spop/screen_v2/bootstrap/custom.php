<link rel="stylesheet" type="text/css" href="/views/dhnbiz/sender/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>
<link rel="stylesheet" type="text/css" href="/css/common.css?v=<?=date("ymdHis")?>"/>
<div class="wrap_leaflets custom">
<script src="/js/qrcode.js"></script>
<script src="/js/qrcode.min.js"></script>
<div id="placeHolder" style='display:none'></div>
<div id="placeHolder2" style='display:none'></div>
<script>
    function generateQrCode(id, qrContent) {
        return new QRCode(id, {
            text: qrContent,
            width: 150,
            height: 150,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.L,
        });
    }

<?if($this->member->item('mem_fixed_url1_flag') == 'Y'){?>
    var text = 'http://igenie.co.kr/gotourl/gohome/<?=$this->member->item('mem_userid')?>'
    var qrCode = generateQrCode(placeHolder, text);
<?}?>

<?if($this->member->item('mem_fixed_url2_flag') == 'Y'){?>
    text = 'http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>'
    qrCode = generateQrCode(placeHolder2, text);
<?}?>

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    }

<?if($this->member->item('mem_fixed_url1_flag') == 'Y'){?>
    function download_home(){
        downloadURI($('#placeHolder').find('img').attr('src'), "home_qr.png");
    }
<?}?>

<?if($this->member->item('mem_fixed_url2_flag') == 'Y'){?>
    function download_tem(){
        downloadURI($('#placeHolder2').find('img').attr('src'), "home_tem.png");
    }
<?}?>

</script>
    <div class="s_tit">
        <? //$test_txt = $this->funn->get_img_cate("12");  ?>

        카카오 채널 커스텀 메뉴 설정
        <span class="t_small">
            <span class="material-icons">contact_support</span> [발송] 후 설정하실 수 있습니다.
        </span>
        <!-- <div class="new_leaflets">
            <a><label for="excelFile" style="cursor:pointer;">엑셀로 전단생성</label></a>
            <input type="file" id="excelFile" onChange="excelPSD(this)" style="display:none;">

            <a href="javascript:write();">스마트전단 만들기</a>
        </div> -->
        <div class="new_leaflets">
            <?if($this->member->item('mem_fixed_url1_flag') == 'Y' && $home_cnt > 0){?>
            <a href="javascript:download_home();">스마트홈QR코드 다운로드</a>
            <?}?>
            <?if($this->member->item('mem_fixed_url2_flag') == 'Y'){?>
            <a href="javascript:download_tem();">스마트전단QR코드 다운로드</a>
            <?}?>
        </div>
    </div>
    <!-- <div class="list_leaflets"> -->
    <div class="">
        <? if(!empty($data_list)){ ?>
        <div class="white_box modal-content">
            <div class="cus_menu">
                <div>
                    커스텀 메뉴 1 &nbsp;
                    <button class="btn md" id="custom_link1">복사</button>
                    <input type="text" id="txtCustom1" value="http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>" style="width:350px;" readonly/>
                    <!-- <input type="button" class="btn md" id="btn_custom_review" value="미리보기" onclick="search_question(1)"> -->
                    <button class="btn md dark" id="btn_custom_review1" onclick="smart_preview('http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>');">
                        미리보기</button>
                    <span class="sel_add"> [커스텀 메뉴1] 은 <em>가장 최근에 보낸 스마트전단 또는 에디터</em>로 고정되어 있습니다.</span>
                </div>
                <div>
                    커스텀 메뉴 2 &nbsp;
                    <button class="btn md" id="custom_link2">복사</button>
                    <input type="text" id="txtCustom2" value="http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>?mode=2" style="width:350px;" readonly/>
                    <button class="btn md dark" id="btn_custom_review2" onclick="smart_preview('http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>?mode=2');">
                        미리보기
                    </button>
                    <span class="sel_add"> [커스텀 메뉴2] 는 <em>아래 리스트에서 선택</em>할 수 있으며, 사용 안 함을 설정하셔도 됩니다.</span>
                    <div id="select_smart" <?=($custom_cnt>0)? "" : "style='display:none;'"?>>
                        <span>
                            <?=($custom_cnt>0)? ((!empty($custom_select))? "현재 설정된 스마트전단 : <em>".$custom_select->psd_title."</em> ".$custom_select->psd_date : "현재 설정된 에디터 : <em>".$custom_row->sent_date."</em> 발송" ) : "" ?>
                        </span>
                        <button type="button" class="btn md" id="btn_custom_blank" onclick="reset();">
                            <i class="icon-arrow-down"></i> 사용안함
                        </button>
                    </div>
                </div>
            </div>

            <div class="tit_box">
                <ul>
                    <li id="li_type_0_1" class="<?=($mode!='edit')? "tm_on" : "" ?>" onclick="location.href='/spop/screen_v2/custom';">스마트전단</li>
                    <li id="li_type_0_2" class="<?=($mode=='edit')? "tm_on" : "" ?>" onclick="location.href='/spop/screen_v2/custom?mode=edit';">에디터</li>
                </ul>
            </div>
            <!-- <div>
                <button type="button" class="btn md" onclick="location.href='/spop/screen_v2/custom';">
                    <i class="icon-arrow-down"></i> 스마트전단</button>
                <button type="button" class="btn md" onclick="location.href='/spop/screen_v2/custom?mode=edit';">
                    <i class="icon-arrow-down"></i> 에디터</button>
            </div> -->

            <ul class="temp_card_wrap">
                <?
                foreach($data_list as $r){
                if($mode=="edit"){
                ?>
                <li class="temp_card2">
                    <!-- <div class="icon_circle">알림톡</div> -->
                    <div class="card_top">에디터 <?=$r->sent_date?></div>
                    <div class="card_contents" style="" onclick="custom_choice('<?=$r->short_url?>', 'edit');">
                        <?=$r->html?>
                    </div>

                    <!-- <div class="tem_img">
                        <div id="pre_smart_bg" onClick="smart_choice('<?=$r->psd_code?>');" class="preview_bg" style="background:url('<?$r->tem_imgpath?>') no-repeat; background-size:100%; background-position:0 50px; cursor:pointer;"></div>
                        <div class="pre_box1">
                            <?=$r->html?>
                            <p id="pre_wl_tit" class="pre_tit"><?=$r->psd_title?></p>
                            <p id="pre_wl_date" class="pre_date"><?=$r->psd_date?></p>
                        </div>
                    </div> -->
                    <p class="tem_text">
                        <span><?=$r->sent_date?></span>
                        <button type="button" onClick="custom_choice('<?=$r->short_url?>', 'edit');"><i class="xi-check-circle-o"></i> 연결하기</button>
                    </p>
                </li>
                <? }else{ ?>
                <li class="smart_list">
                    <div class="tem_img">
                        <div id="pre_smart_bg" onClick="custom_choice('<?=$r->psd_code?>', 'smart');" class="preview_bg" style="background:url('<?=$r->tem_imgpath?>') no-repeat; background-size:100%; background-position:0 50px; cursor:pointer;"></div>
                        <div class="pre_box1">
                            <p id="pre_wl_tit" class="pre_tit"><?=$r->psd_title?></p>
                            <p id="pre_wl_date" class="pre_date"><?=$r->psd_date?></p>
                        </div>
                    </div>
                    <p class="tem_text">
                        <span><?=$r->sent_date?></span>
                        <button type="button" onClick="custom_choice('<?=$r->psd_code?>', 'smart');"><i class="xi-check-circle-o"></i> 연결하기</button>
                    </p>
                </li>
                <? }
                ?>

                <?
                } //foreach($data_list as $r){
                ?>
            </ul>
        </div>
        <? }else { //if(!empty($data_list)){ ?>
        <!-- <div class="tit_box">
            <ul>
                <li id="li_type_0_1" class="tm_on" onclick="location.href='/spop/screen_v2/custom';">스마트전단</li>
                <li id="li_type_0_2" class="" onclick="location.href='/spop/screen_v2/custom?mode=edit';">에디터</li>
            </ul>
        </div> -->
        <!-- <div>
            <button type="button" class="btn md" onclick="location.href='/spop/screen_v2/custom';">
                <i class="icon-arrow-down"></i> 스마트전단</button>
            <button type="button" class="btn md" onclick="location.href='/spop/screen_v2/custom?mode=edit';">
                <i class="icon-arrow-down"></i> 에디터</button>
        </div> -->
        <div class="white_box modal-content">
            <div class="cus_menu">
                <div>
                    커스텀 메뉴 1 &nbsp;
                    <button class="btn md" id="custom_link1">복사</button>
                    <input type="text" id="txtCustom1" value="http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>" style="width:350px;" readonly/>
                    <!-- <input type="button" class="btn md" id="btn_custom_review" value="미리보기" onclick="search_question(1)"> -->
                    <button class="btn md dark" id="btn_custom_review1" onclick="smart_preview('http://smart.dhn.kr/smart/smart_view/<?=$this->member->item('mem_userid')?>');">
                        미리보기</button>
                    <span class="sel_add"> [커스텀 메뉴1] 은 <em>가장 최근에 보낸 스마트전단 또는 에디터</em>로 고정되어 있습니다.</span>
                </div>
            </div>
            <div class="tit_box">
                <ul>
                    <li id="li_type_0_1" class="<?=($mode!='edit')? "tm_on" : "" ?>" onclick="location.href='/spop/screen_v2/custom';">스마트전단</li>
                    <li id="li_type_0_2" class="<?=($mode=='edit')? "tm_on" : "" ?>" onclick="location.href='/spop/screen_v2/custom?mode=edit';">에디터</li>
                </ul>
            </div>
            <div class="smart_none">
                500여건 이상 발송된 스마트 전단/에디터가 없습니다.<br />
                발송 후 이용해주세요!<br />
                <span>[ 좌메뉴 > 메시지발송 ]</span>
            </div>
        </div>
            <? } //if(!empty($data_list)){ ?>
    </div><!--//list_leaflets-->
    <div class="page_cen">
        <?=$page_html?>
    </div><!--//pagination-->
    <!-- The Modal -->
    <div id="dh_myModal" class="dh_modal">
        <!-- Modal content -->
        <div class="modal-content2"> <span id="dh_close" class="dh_close">&times;</span>
            <p class="modal-tit">
                미리보기
            </p>
            <div class="modal-img">
                <img src="/dhn/images/leaflets/pre_sample.jpg" alt="" />
            </div>
        </div>
    </div>
</div><!--//wrap_leaflets-->
<script>

    $('#custom_link1').click(function() {

        // input에 담긴 데이터를 선택
        $('#txtCustom1').select();

        //  clipboard에 데이터 복사
        var copy = document.execCommand('copy');

        // 사용자 알림
        if(copy) {
        	// alert("데이터가 복사되었습니다.");
            showSnackbar("링크가 클립보드로 복사되었습니다..", 1500);
        }
    });

    $('#custom_link2').click(function() {

        // input에 담긴 데이터를 선택
        $('#txtCustom2').select();

        //  clipboard에 데이터 복사
        var copy = document.execCommand('copy');

        // 사용자 알림
        if(copy) {
        	// alert("데이터가 복사되었습니다.");
            showSnackbar("링크가 클립보드로 복사되었습니다..", 1500);
        }
    });

    function custom_choice(code, flag){
        if (!confirm("커스텀메뉴2링크로 연결하시겠습니까?")) {
            return;
        } else {
            $.ajax({
				url: "/spop/screen_v2/custom_choice",
				type: "POST",
				data: {"code" : code, "flag" : flag, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
                    if(json.code=="0"){
                        $("#select_smart").find('span').html(json.remsg);
                        $("#select_smart").show();
                        showSnackbar("정상적으로 연결 되었습니다.", 1500);

                    }else{
                        showSnackbar("설정에 문제가 있습니다", 1500);
                    }
				}
			});
        }



    }

    function reset(){
        if (!confirm("커스텀메뉴2를 사용안함 처리하시겠습니까?")) {
            return;
        } else {
            $.ajax({
				url: "/spop/screen_v2/custom_reset",
				type: "POST",
				data: {"<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
                    if(json.code=="0"){
                        $("#select_smart").find('span').html("");
                        $("#select_smart").hide();
                        showSnackbar("사용안함 처리되었습니다.", 1500);

                    }else{
                        showSnackbar("설정에 문제가 있습니다", 1500);
                    }
				}
			});
        }



    }
    // $(document).keypress(function(e) { if (e.keyCode == 13) e.preventDefault(); });
    //
	// var add = "<?=$add?>";
	// if(add != "") add = "&add="+ add;
	// //스마트전단 만들기 등록화면 이동
	// function write(){
	// 	window.location.href = "/spop/screen_v2/write"+ add.replace(/&/gi, '?');
	// }
	// //스마트전단 만들기 수정화면 이동
	// function view(id){
	// 	window.location.href = "/spop/screen_v2/write?psd_id="+ id +"&md=mod"+ add;
	// }
    //
	// //문자발송 화면이동
	// function lms(code){
	// 	window.location.href = "/dhnbiz/sender/send/lms?psd_code="+ code;
	// }
    //
	// //알림톡발송 화면이동
	// function talk_adv(code){
	// 	window.location.href = "/dhnbiz/sender/send/talk_adv?psd_code="+ code;
	// }
    //
    // //엑셀 업로드 전단생성
	// function excelPSD(input){
	// 	var file = document.getElementById("excelFile").value;
	// 	//alert("file : "+ file); return;
	// 	//alert("step : "+ step +", id : "+ id); return;
	// 	var ext = file.slice(file.indexOf(".") + 1).toLowerCase();
	// 	//alert("file : "+ file +", ext : "+ ext); return;
	// 	if (ext == "xls" || ext == "xlsx") {
    //         jsLoading();
    //
	// 		var file_data = input.files[0];
	// 		//alert("file_data : "+ file_data); return;
	// 		var formData = new FormData();
	// 		formData.append("file", file_data);
	// 		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
	// 		$.ajax({
	// 			url: "/spop/screen_v2/excel_psd_upload_ok_tab",
	// 			type: "POST",
	// 			data: formData,
	// 			processData: false,
	// 			contentType: false,
	// 			success: function (json) {
	// 				//alert("excel_upload_ok");
	// 				// console.log(json);
    //                 if(json.code=="0"){
    //                 showSnackbar("정상적으로 생성 되었습니다.", 1500);
	// 				setTimeout(function() {
	// 					location.reload();
	// 				}, 1000); //1초 지연
    //                 }else{
    //                     showSnackbar("전단생성에 문제가 있습니다", 1500);
    //                 }
	// 			}
	// 		});
	// 		//alert("OK");
	// 		var agent = navigator.userAgent.toLowerCase(); //브라우저
	// 		//alert("agent : "+ agent);
	// 		if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){ //ie 일때
	// 			//alert("ie 일때");
	// 			$("#excelFile").replaceWith( $("#excelFile").clone(true) ); //파일 초기화
	// 		}else{
	// 			$("#excelFile").val(""); //파일 초기화
	// 		}
	// 	} else {
	// 		alert("엑셀(xls, xlsx) 파일만 가능합니다.");
	// 	}
	// }
    //
	// //스마트전단 복사
	// function copy(data_id){
	// 	if(confirm("해당 스마트전단을 복사 하시겠습니까?")){
    //         jsLoading();
	// 		$.ajax({
	// 			url: "/spop/screen_v2/screen_copy",
	// 			type: "POST",
	// 			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	// 			success: function (json) {
	// 				showSnackbar("정상적으로 복사 되었습니다.", 1500);
	// 				setTimeout(function() {
	// 					//location.reload();
	// 					location.href = "/spop/screen_v2";
	// 				}, 1000); //1초 지연
	// 			}
	// 		});
	// 	}
	// }
    //
	// //스마트전단 삭제
	// function del(data_id){
	// 	if(confirm("정말 삭제 하시겠습니까?")){
	// 		$.ajax({
	// 			url: "/spop/screen_v2/screen_remove",
	// 			type: "POST",
	// 			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	// 			success: function (json) {
	// 				showSnackbar("정상적으로 삭제 되었습니다.", 1500);
	// 				setTimeout(function() {
	// 					location.reload();
	// 				}, 1000); //1초 지연
	// 			}
	// 		});
	// 	}
	// }
    //
	// //스마트전단 삭제
	// function setSample(data_id){
	// 	$.ajax({
	// 		url: "/spop/screen_v2/setsample",
	// 		type: "POST",
	// 		data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json) {
	// 			showSnackbar("정상적으로 지정 되었습니다.", 1500);
	// 			setTimeout(function() {
	// 				location.reload();
	// 			}, 1000); //1초 지연
	// 		}
	// 	});
	// }
    //
	// //미리보기
	// function preview(code){
	// 	var url = "/smart/view/"+ code +"?ScreenShotYn=Y"+ add;
	// 	//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
	// 	//alert("url : "+ url);
	// 	smart_preview(url); //스마트전단 미리보기
    //     $.ajax({
    //         url: "/spop/screen_v2/set_preview_log",
    //         type: "POST",
    //         data: {
    //             "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
    //           , code : code
    //           , mem_id : '<?=$this->member->item('mem_id')?>'
    //         },
    //         success: function (json) {
    //         }
    //     });
	// }
    //
    // //스마트전단 on/off
    // function psd_onoff(data_id, flag){
    //     var flag_msg = "";
    //     if(flag=="N"){
    //         flag_msg = "비공개";
    //     }else{
    //         flag_msg = "공개";
    //     }
    //     if(confirm("해당전단이 "+flag_msg+"로 상태가 변경됩니다. 수정하시겠습니까?")){
	// 		$.ajax({
	// 			url: "/spop/screen_v2/screen_onoff",
	// 			type: "POST",
	// 			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>", "flag" : flag},
	// 			success: function (json) {
	// 				showSnackbar("정상적으로 변경 되었습니다.", 1500);
	// 				setTimeout(function() {
	// 					location.reload();
	// 				}, 1000); //1초 지연
	// 			}
	// 		});
	// 	}
    // }
    //
    // //스마트전단 다운로드
	// function exelDown(data_id){
    //     if(data_id==''){
    //         showSnackbar("오류가 있습니다.", 1500);
    //         return false;
    //     }
	// 	$.ajax({
	// 		url: "/spop/screen_v2/psd_chk_goods",
	// 		type: "POST",
	// 		data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json) {
    //             if(json.code=='1'){
    //                 showSnackbar("대용량전단은 지원하지 않습니다. 상품수를 줄이고 다시 시도하시기 바랍니다.", 1500);
    //                 return false;
    //             }else{
    //                 var oldflag = "";
    //                 if(json.mode=='O'){
    //                     oldflag = "_old";
    //                 }
    //                 var form = document.createElement("form");
    //             	document.body.appendChild(form);
    //             	form.setAttribute("method", "post");
    //             	form.setAttribute("action", "/spop/screen_v2/psd_download_tab"+oldflag);
    //
    //             	var scrfField = document.createElement("input");
    //             	scrfField.setAttribute("type", "hidden");
    //             	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    //             	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    //             	form.appendChild(scrfField);
    //
    //             	var kindField = document.createElement("input");
    //             	kindField.setAttribute("type", "hidden");
    //             	kindField.setAttribute("name", "data_id");
    //             	kindField.setAttribute("value", data_id);
    //             	form.appendChild(kindField);
    //
    //
    //             	form.submit();
    //             }
    //
	// 			// showSnackbar("해당 전단 엑셀이 다운로드 되었습니다.", 1500);
	// 			// setTimeout(function() {
	// 			// 	location.reload();
	// 			// }, 1000); //1초 지연
	// 		}
	// 	});
    //
    //
	// }

	//검색
	function open_page(page) {
		location.href = "/spop/screen_v2/custom?page="+ page + add;
	}
</script>
