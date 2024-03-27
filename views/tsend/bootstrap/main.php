<link rel="stylesheet" type="text/css" href="/views/tsend/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />

<div class="tsend login_wrap">
    <div class="logo">
        <img src="/dhn/images/logo_v2.png">
        <p class="logo_txt">AI 스마트 매장관리시스템</p>
    </div>

    <div id='div_pw' class="login_field">
        <input type="password" placeholder="비밀번호를 입력하세요" id='pw' value='' onkeyup="enterkey()" autocomplete="off" />
        <button class="btn_login" onclick='check_pw()'>확인</button>
    </div>
</div>


<script>
    function check_pw(){
        $.ajax({
            url: "/tsend/check_pw",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , pw : $('#pw').val()
            },
            success: function (json) {
                if (json.code == '1'){
                    $('#div_pw').remove();
                    $('.login_wrap').append(json.html);
                } else {
                    alert(json.msg);
                    $('#pw').val('');
                    $('#pw').focus();
                }
            },
        });
    }

    function enterkey() {
	if (window.event.keyCode == 13) {
    	check_pw();
    }
}

    $(document).on('click', '#send', function(){
        var regExp = /(01[016789])(\d{4}|\d{3})\d{4}$/g;
		if(!regExp.test($('#tel').val())){
			alert("휴대폰번호가 올바른 형식이 아닙니다.");
			$("#tel").focus();
			return;
		}
        $.ajax({
            url: "/tsend/sending",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , tel : $('#tel').val()
              , r_at : $(':radio[name="r_at"]:checked').val()
            },
            success: function (json) {
                // alert(json["response_message"]);
                alert('발송되었습니다.');
                $("#tel").val('');
                $('#tel').focus();
            },
        });
    });

    $(document).on('click', '#send2', function(){
        var regExp = /(01[016789])(\d{4}|\d{3})\d{4}$/g;
		if(!regExp.test($('#tel2').val())){
			alert("휴대폰번호가 올바른 형식이 아닙니다.");
			$("#tel2").focus();
			return;
		}
        $.ajax({
            url: "/tsend/sending2",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , tel : $('#tel2').val()
              , r_ft : $(':radio[name="r_ft"]:checked').val()
            },
            success: function (json) {
                alert('발송되었습니다.');
                $("#tel2").val('');
                $('#tel2').focus();
            },
        });
    });
</script>
