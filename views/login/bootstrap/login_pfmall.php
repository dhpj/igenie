<script>
$( document ).ready(function() {
	var form = document.createElement("form");
	document.body.appendChild(form);
	form.setAttribute("method", "post");
	form.setAttribute("action", "http://pfmall.co.kr/login/login_api");
	form.setAttribute("accept-charset", "EUC-KR");

	var obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "site_url");
	obj.setAttribute("value", "<?=$_SERVER['HTTP_HOST']?>"); //로그인 사이트
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "userid");
	obj.setAttribute("value", "<?=$userid?>"); //회원아이디
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "username");
	obj.setAttribute("value", "<?=$username?>"); //회원명
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "url_after_login"); //다음페이지 URL
	obj.setAttribute("value", ""); //회원명
	form.appendChild(obj);

	form.submit();
});
</script>