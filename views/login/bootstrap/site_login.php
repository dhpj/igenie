<script>
$( document ).ready(function() {
	var form = document.createElement("form");
	document.body.appendChild(form);
	form.setAttribute("method", "post");
	form.setAttribute("action", "<?=$login_url?>"); //로그인 URL
	form.setAttribute("accept-charset", "utf-8");

	var obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "site_url");
	obj.setAttribute("value", "<?=$_SERVER['HTTP_HOST']?>"); //현재 사이트
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "userid");
	obj.setAttribute("value", "<?=$userid?>"); //사이트 연동 ID
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "site_key");
	obj.setAttribute("value", "<?=$site_key?>"); //사이트 연동 Key
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "url_after_login");
	obj.setAttribute("value", "<?=$url_after_login?>"); //다음페이지 URL(기본값 / )
	form.appendChild(obj);

	form.submit();
});
</script>