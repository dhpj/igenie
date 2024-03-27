<!DOCTYPE html>
<!-- saved from url=(0036)<?php echo $base_url;?> -->
<html lang="ko"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<meta name="description" content="국내 유일 웹에서 발신하는 카카오톡 비즈메시지(알림톡, 친구톡)">
<meta name="keywords" content="비즈메시지,알림톡,알림톡발송,알림톡 발송,친구톡발송,친구톡 발송,카카오톡API,카카오알림톡,카카오톡알림톡,카톡알림톡,카카오톡알림,우체국알림톡,카톡알림,비즈메세징,비즈메시징,문자연동">
<meta name="naver-site-verification" content="4a475c27f060d4c14afbaf0fd18871ff9c269705">
<meta name="google-site-verification" content="cTypcG9LlhGnrzHo6DruqvUDOKj-hsEA3Jj0CPX71Ls">
<meta property="og:type" content="website">
<meta property="og:title" content="대형네트웍스">
<meta property="og:description" content="국내 유일 웹에서 발신하는 카카오톡 비즈메시지(알림톡, 친구톡)">
<meta property="og:url" content="">
<link rel="canonical" href="">
<title>대형네트웍스</title>
<link rel="stylesheet" href="/js/owlcarousel/owl.carousel.min.css" />
<link rel="stylesheet" href="/js/owlcarousel/owl.theme.default.min.css" />
<link rel="stylesheet" href="/bizM/css/style.css" />
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/js/jquery.min.js"></script>
<script src="/js/owlcarousel/owl.carousel.min.js"></script>
</head>
<body>
<div id="wrap">
<?
/*
	?mode=iframe 을 붙여서 호출하면 상단의 메뉴부분등을 제외하고 표시함

	게시판 write skin에 input hidden mode 필드 바인딩 추가
	controller/Board_write.php 1337라인에 저장후 처리에 대해 기록
	(상담게시판의 경우 안내멘트 다르게 처리) 저장후 다시 글쓰기 페이지로 이동하므로 글쓰기 30초 제한을 환경설정에서 0으로 설정해야함
*/
if($_GET['mode']=='iframe') {?>
<style type="text/css">
.board h3 { display:none; }
.board .btn-group { display:none; }
</style>
<?} else {?>
	<h1 class="hidden">대형네트웍스</h1>
	<header id="header" class="header">
		<nav id="tnb">
			<div class="wrap">
				<h2 class="hidden">회원메뉴</h2>
				<ul>
					<li><a href="<?php echo $base_url?>/login">로그인</a></li>
					<li><a href="<?php echo $base_url?>/register">회원가입</a></li>
				</ul>
			</div>
		</nav>
		<div class="wrap">
		<!--	<div id="logo"><a href="/"><img src="/images/logo.png" alt="대형네트웍스"></a></div> -->
			<nav id="gnb">
				<h2>메인메뉴</h2>
				<ul id="gnb">
					<li><a href="/homepage/alimtalk">알림톡</a></li>
					<li><a href="/homepage/friendtalk">친구톡</a></li>
					<li><a href="/homepage/marttalk">마트톡</a></li>
					<li><a href="/homepage/sangdamtalk">상담톡</a></li>
					<li><a href="/homepage/sms">광고문자</a></li>
					<li><a href="/write/request">상담신청</a></li>
					<li><a href="/board/notice_01">고객센터</a>
						<ul class="submenu">
							<li class=""><a href="/board/notice_01" target="_self" class="gnb_2da">공지/뉴스</a></li>
							<li class=""><a href="/board/data" target="_self" class="gnb_2da">자료실</a></li>
							<li class=""><a href="/homepage/company" target="_self" class="gnb_2da">회사소개</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</header>
<?}?>
	<div id="container">
