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
	obj.setAttribute("value", "<?=$_SERVER['HTTP_HOST']?>"); //�α��� ����Ʈ
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "userid");
	obj.setAttribute("value", "<?=$userid?>"); //ȸ�����̵�
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "username");
	obj.setAttribute("value", "<?=$username?>"); //ȸ����
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "url_after_login"); //���������� URL
	obj.setAttribute("value", ""); //ȸ����
	form.appendChild(obj);

	form.submit();
});
</script>