<script>
$( document ).ready(function() {
	var form = document.createElement("form");
	document.body.appendChild(form);
	form.setAttribute("method", "post");
	form.setAttribute("action", "<?=$login_url?>"); //�α��� URL
	form.setAttribute("accept-charset", "utf-8");

	var obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "site_url");
	obj.setAttribute("value", "<?=$_SERVER['HTTP_HOST']?>"); //���� ����Ʈ
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "userid");
	obj.setAttribute("value", "<?=$userid?>"); //����Ʈ ���� ID
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "site_key");
	obj.setAttribute("value", "<?=$site_key?>"); //����Ʈ ���� Key
	form.appendChild(obj);

	obj = document.createElement("input");
	obj.setAttribute("type", "hidden");
	obj.setAttribute("name", "url_after_login");
	obj.setAttribute("value", "<?=$url_after_login?>"); //���������� URL(�⺻�� / )
	form.appendChild(obj);

	form.submit();
});
</script>