<?include $_SERVER["DOCUMENT_ROOT"]."/views/homepage/top.php";?>
<link rel="stylesheet" href="/bizM/css/style.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/views/board/bootstrap/css/style.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/main.css" />
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/js/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<style type="text/css">
	a.btn-success,button.btn-success,a.btn-primary,button.btn-primary,button.btn-primary i{color:#fff}
	.board h3{width:100%;height:110px;margin-bottom:50px;padding:25px 0 0 0;border-bottom:none;background-color:#fae100;font-size:50px;font-weight:700;letter-spacing:-.08em;text-align:center}
	.board .row{width:1170px;margin:0 auto}
	#fboardlist{width:1170px;margin:0 auto}
	.border_button{width:1170px;margin:0 auto 50px}
	.board{width:100%;margin:0 auto;padding:0}
	.board .wrap{width:1170px;margin:0 auto}
</style>
		<!-- 본문 시작 -->
			<?php if (isset($yield))echo $yield; ?>
		<!-- 본문 끝 -->
<?include $_SERVER["DOCUMENT_ROOT"]."/views/homepage/bottom.php";?>