<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/login_check.php');
?>

<!-- 본문 시작 -->
<!-- 캠페인 만들기 -->
<div class="tit_wrap">
    캠페인 & 광고그룹 만들기
</div>
<div id="mArticle" class="kakao kakao_campaign">
	<div class="form_section">
        <div class="inner_tit">
            <h3>캠페인</h3>
        </div>
        <div class="white_box">

            <div class="reform_group_card campaign_set">
                <h4 class="reform_subtit_card">채널 선택</h4>
                <div class="reform_con_card">

                    <p class="reform_con_card_text">내 카카오계정에 연동된 채널 친구에게 메시지를 보낼 수 있습니다. 메시지를 발송할 카카오톡 채널을 선택하세요.</p>

                    <div class="reform_con_card_select" name="">

                        <select id="cam_select" class="select_channel_item" onchange="">
                            <option class='cam_default' value="0">선택안함</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="reform_group_card campaign_name">
                <h4 class="reform_subtit_card">캠페인 이름</h4>
                <div class="reform_con_card">

                    <input type="text" id="cam_name" name=""  value="" maxlength="50" onkeyup='check_char(event, this, 50)' placeholder="빈값일 시 자동 생성(예 : 개인화 메시지_도달_yyyyMMddhhmm)">
                    <span class="num_byte">50</span>

                </div>
            </div>

        </div><!-- white_box -->

        <div class="btn_send_cen">
    		<button class="btn lg btn_bk" onclick="set_campaign()">캠페인 만들기</button>
    	</div>

    </div>
</div>
<!-- // 캠페인 만들기 -->

<!-- 광고그룹 만들기 -->
<div id="mArticle" class="kakao kakao_campaign">
	<div class="form_section">
        <div class="inner_tit">
            <h3>광고그룹</h3>
        </div>
        <div class="white_box">
            <div class="reform_group_card campaign_set">
                <h4 class="reform_subtit_card">캠페인 선택</h4>
                <div class="reform_con_card">
                    <div class="reform_con_card_select" name="">

                        <select id="adg_select" class="select_channel_item" onchange="">
                            <option class='adg_default' value="0">선택안함</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="reform_group_card campaign_name">
                <h4 class="reform_subtit_card">광고그룹 이름</h4>
                <div class="reform_con_card">

                    <input type="text" id="adg_name" name=""  value="" maxlength="50" onkeyup='check_char(event, this, 50)' placeholder="빈값일 시 자동 생성(예 : 개인화 메시지_도달_yyyyMMddhhmm)">
                    <span class="num_byte">50</span>

                </div>
            </div>

        </div><!-- white_box -->

        <div class="btn_send_cen">
    		<button class="btn lg btn_bk" onclick="set_ad_group()">광고그룹 만들기</button>
    	</div>
    </div>
</div>
<!-- // 광고그룹 만들기 -->

<script>
    var chid = '';

    $(document).ready(function(){
        get_ch_profile();
        get_campaigns();
    });

    function get_ch_profile(){
        $('#cam_select').find('option').not('.cam_default').remove();
        $.ajax({
			url: "/kakao/get_ch_profile",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
            },
			success: function (json) {
                if (json.data.code == undefined){
                    if (json.data.length > 0){
                        var html = '';
                        $.each(json.data, function(idx, val){
                            html += '<option value="' + val.id + '">' + val.name + '</option>';
                        });
                        $('#cam_select').append(html);
                    } else {
                        alert('광고계정이 존재하지 않습니다.');
                    }
                } else {
                    if (json.data.msg != undefined){
                        alert(json.data.msg);
                    } else {
                        alert(json.data.extras.detailMsg);
                    }
                }
			}
		});
    }

    function get_campaigns(){
        $('#adg_select').find('option').not('.adg_default').remove();
        // $.ajax({
		// 	url: "/kakao/get_campaigns",
		// 	type: "POST",
		// 	data: {
        //         "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
        //       , token : Kakao.Auth.getAccessToken()
        //       , adid : '<?=config_item('adid')?>'
        //     },
		// 	success: function (json) {
        //         if (json.data.code == undefined){
        //             if (json.data.content.length > 0){
        //                 var html = '';
        //                 $.each(json.data.content.reverse(), function(idx, val){
        //                     html += '<option value="' + val.id + '">' + val.name + '</option>';
        //                 });
        //                 $('#adg_select').append(html);
        //             } else {
        //                 alert('캠페인이 존재하지 않습니다.');
        //             }
        //         } else {
        //             if (json.data.msg != undefined){
        //                 alert(json.data.msg);
        //             } else {
        //                 alert(json.data.extras.detailMsg);
        //             }
        //         }
		// 	}
		// });
        $.ajax({
			url: "/kakao/get_campaigns_in_local",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , adid : '<?=config_item('adid')?>'
            },
			success: function (json) {
                if (json.data.length > 0){
                    var html = '';
                    $.each(json.data, function(idx, val){
                        html += '<option value="' + val.pc_c_id + '">' + val.pc_name + '</option>';
                    });
                    $('#adg_select').append(html);
                } else {
                    // alert('캠페인이 존재하지 않습니다.');
                }
			}
		});
    }

    function check_char(e, t, max){
        $(t).val($(t).val().substring(0, max));
        $(t).next().html(50 - $(t).val().length);
    }

    function set_campaign(){
        var chid = $('#cam_select').val();
        if (chid == '0'){
            alert('채널을 선택해주세요.');
            return;
        }
        $.ajax({
			url: "/kakao/set_campaign",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , chid : chid
              , name : $('#cam_name').val()
              , chname : $("#cam_select option:selected" ).text()
            },
			success: function (json) {
                get_campaigns();
                if (json.data.id != undefined){
                    $("#cam_select option:eq(0)").prop("selected", true);
                    $('#cam_name').val('');
                    alert('캠페인이 생성되었습니다.');
                } else {
                    alert('캠페인 생성에 실패했습니다. 관리자에게 문의해주세요.');
                }
			}
		});
    }

    function set_ad_group(){
        var cid = $('#adg_select').val();
        if (cid == '0'){
            alert('캠페인을 선택해주세요.');
            return;
        }
        $.ajax({
			url: "/kakao/set_ad_group",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , token : Kakao.Auth.getAccessToken()
              , adid : '<?=config_item('adid')?>'
              , cid : cid
              , name : $('#adg_name').val()
            },
			success: function (json) {
                if (json.data.id != undefined){
                    $("#adg_select option:eq(0)").prop("selected", true);
                    $('#adg_name').val('');
                    alert('광고그룹이 생성되었습니다.');
                } else {
                    alert('광고그룹 생성에 실패했습니다. 관리자에게 문의해주세요.');
                }
			}
		});
    }

</script>
