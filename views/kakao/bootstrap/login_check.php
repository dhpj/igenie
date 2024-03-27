<script>
    $(document).ready(function(){
        if (!check_token()){
            alert('카카오톡 로그인을 해주세요.');
            location.href = 'https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=<?=config_item('kakao_conf')['dhn_rest_key']?>&redirect_uri=<?=config_item('kakao_conf')['dhn_login_redirect_uri']?>';
        }
    });
</script>
