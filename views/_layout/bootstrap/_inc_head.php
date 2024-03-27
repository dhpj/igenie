<?
	$req_uri = $_SERVER['REQUEST_URI']; //도메인을 제외한 전체 경로
	$req_uri_arr = explode('?', $req_uri); //경로 배열 처리
	$req_url = $req_uri_arr[0]; //도메인을 제외한 전체 경로 (쿼리스트링 제외)
	$req_url_arr = explode('/', $req_url); //경로 (쿼리스트링 제외)  배열 처리
	$menu1 = $req_url_arr[1]; //1차 메뉴
	$menu2 = $req_url_arr[2]; //2차 메뉴
	$menu3 = $req_url_arr[3]; //3차 메뉴
	//echo "req_uri : ". $req_uri ."<br>";
	//echo "req_url : ". $req_url ."<br>";
	//echo "menu1 : ". $menu1 ."<br>"; //1레벨 경로
	//echo "menu2 : ". $menu2 ."<br>"; //2레벨 경로
	//echo "menu3 : ". $menu3 ."<br>"; //3레벨 경로
?>
<meta name="naver-site-verification" content="ad7e4227e6535f45b2aab47ee9d21e914cb3e61d" />
<meta name="google-site-verification" content="2yymrTG5Qrm_Ck-sGcpVnEsq6VMP1Wb9RmD1WlUbcqQ" />
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<? if(strpos("/".$req_url, "/at/") == true){ //UMS 에디터 전단 페이지 ?>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<? }else if(strpos("/".$req_url, "/smart/orders") == true){ //스마트전단 > 주문하기 페이지 ?>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<? }else{ ?>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<? } ?>

<? if(strpos("/".$req_url, "/smart/") == true){ //스마트전단 페이지 ?>
<meta property="og:image" content="http://14.43.241.107/uploads/og_genie.jpg"/>
<meta property="og:url" content="http://igenie.co.kr/smart/" />
<? }else{ ?>
    <meta property="og:url" content="http://igenie.co.kr" />
<? } ?>
<meta property="og:title" content="지니" />
<meta property="og:description" content="스마트전단, 디지털전단, 모바일전단, 지니, 스마트 매장통합관리시스템, 알림톡 광고, 문자 광고" />
<meta property="og:site_name" content="지니" />
<meta property="og:type" content="website" />
<title>지니:스마트 매장통합관리시스템</title>
<meta property="title" content="지니 - 스마트전단, 디지털전단, 모바일전단">
<meta name="subject" content="지니 - 스마트전단, 디지털전단, 모바일전단">
<meta name="description" content="스마트전단, 디지털전단, 모바일전단, 지니, 스마트 매장통합관리시스템, 알림톡 광고, 문자 광고">
<meta name="keywords" content="스마트전단, 디지털전단, 모바일전단, 지니, 스마트 매장통합관리시스템, 알림톡 광고, 문자 광고" />
<link rel="canonical" href="http://igenie.co.kr">
<!-- GENIE favicon 추가 -->
<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon"  sizes="120x120" href="/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
<link rel="manifest" href="/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<?php if (element('meta_description', $layout)) { ?><meta name="description" content="<?php echo html_escape(element('meta_description', $layout)); ?>"><?php } ?>
<?php if (element('meta_keywords', $layout)) { ?><meta name="keywords" content="<?php echo html_escape(element('meta_keywords', $layout)); ?>"><?php } ?>
<?php if (element('meta_author', $layout)) { ?><meta name="author" content="<?php echo html_escape(element('meta_author', $layout)); ?>"><?php } ?>
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>
<?php if (element('canonical', $view)) { ?><link rel="canonical" href="<?php echo element('canonical', $view); ?>" /><?php } ?>
