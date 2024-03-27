<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=no">
	<title>대형네트웍스 마트 관리자</title>
	<link href="css/basic.css" rel="stylesheet" type="text/css">
	<link href="css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="login_wrap">
		<div class="left">
				<div class="logo">
					<img src="../img/login_logo.png" width="60px;">
					<h2>프랜즈채널스토어</h2>
					<p>프랜즈채널스토어에 등록한 판매자 아이디로 로그인해 주세요.</p>
					<p>처음 방문하신 분은 판매자 가입 후, 이용해 주세요.</p>
				</div>
				<div class="login_field">
		            <input type="text" name="mem_userid" placeholder="아이디">
		            <input type="password" name="mem_password" placeholder="비밀번호">
		            <div class="autologin">
			            <input type="checkbox" name="autologin" id="autologin" value="1">
			            <label for="autologin">아이디 저장</label>
		            </div>
		            <button type="submit" label="로그인하기" class="btn_login" onclick="location.href='main.php'">로그인</button>
					<!--div class="divider">회원가입</div-->
					<hr>
					<div class="join_link">
						서비스가 필요하세요?
						<p>1588-7985</p>
					</div>
				</div>
		</div>
		<div class="right">Banner Area</div>
	</div>
</body>
</html>