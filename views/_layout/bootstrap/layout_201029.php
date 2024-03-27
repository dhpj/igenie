<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=no">
<meta property="og:url" content="http://igenie.co.kr" />
<meta property="og:title" content="지니" />
<meta property="og:description" content="스마트 매장통합관리시스템, 알림톡 광고, 문자 광고" />
<meta property="og:site_name" content="지니" />
<meta property="og:type" content="website" />
<title>지니:스마트 매장통합관리시스템</title>
<meta name="description" content="스마트 매장통합관리시스템, 알림톡 광고, 문자 광고">
<meta name="keywords" content="스마트 매장통합관리시스템, 알림톡 광고, 문자 광고" />
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

<?php
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
<?php if (element('meta_description', $layout)) { ?><meta name="description" content="<?php echo html_escape(element('meta_description', $layout)); ?>"><?php } ?>
<?php if (element('meta_keywords', $layout)) { ?><meta name="keywords" content="<?php echo html_escape(element('meta_keywords', $layout)); ?>"><?php } ?>
<?php if (element('meta_author', $layout)) { ?><meta name="author" content="<?php echo html_escape(element('meta_author', $layout)); ?>"><?php } ?>
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>
<?php if (element('canonical', $view)) { ?><link rel="canonical" href="<?php echo element('canonical', $view); ?>" /><?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/import.css" />
<link rel="stylesheet" type="text/css" href="/views/<?=$req_url_arr[1]?>/<?=$req_url_arr[2]?>/bootstrap/css/style.css" />
<link rel="stylesheet" type="text/css" href="/views/<?=$req_url_arr[1]?>/bootstrap/css/style.css" />
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" />
<?php echo $this->managelayout->display_css(); ?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var cb_url = "<?php echo trim(site_url(), '/'); ?>";
var cb_cookie_domain = "<?php echo config_item('cookie_domain'); ?>";
var cb_charset = "<?php echo config_item('charset'); ?>";
var cb_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
var cb_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
var layout_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
var view_skin_path = "<?php echo element('view_skin_path', $layout); ?>";
var is_member = "<?php echo $this->member->is_member() ? '1' : ''; ?>";
var is_admin = "<?php echo $this->member->is_admin(); ?>";
var cb_admin_url = <?php echo $this->member->is_admin() === 'super' ? 'cb_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
var cb_board = "<?php echo isset($view) ? element('board_key', $view) : ''; ?>";
var cb_board_url = <?php echo ( isset($view) && element('board_key', $view)) ? 'cb_url + "/' . config_item('uri_segment_board') . '/' . element('board_key', $view) . '"' : '""'; ?>;
var cb_device_type = "<?php echo $this->cbconfig->get_device_type() === 'mobile' ? 'mobile' : 'desktop' ?>";
var cb_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url('assets/js/html5shiv.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
<![endif]-->

<!--=== JavaScript ===-->
<script type="text/javascript" src="/bizM/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="/bizM/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="/js/bss_js/bootstrap-select.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
<link href="/css/common.css" rel="stylesheet" type="text/css">
<link href="/bizM/css/datepicker3.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
<!-- GENIE JS 추가 -->
<script type="text/javascript" src="<?php echo base_url('/js/common.js'); ?>"></script>
<!-- 아이콘폰트 추가 -->
<script src="https://kit.fontawesome.com/12fdcf5d4d.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
<?php echo $this->managelayout->display_js(); ?>
</head>
<body <?php echo isset($view) ? element('body_script', $view) : ''; ?>>
	<div id="body_top" class="genie_wrap">
		<div class="header">
			<button class="openbtn" onclick="openNav()">☰</button>
			<div class="hamburger-menu"><div class="bar"></div></div>
			<a href="<?php echo site_url()."home"; ?>"><div class="logo"><!--?php echo html_escape($this->cbconfig->item('site_title'));?--><h1><img src="/dhn/images/logo_v1.png"></h1></div></a>
			<div class="center">
				<div class="t_slogan">AI 스마트 매장관리시스템 '지니'</div>
				<!-- 검색 기능
					<form name="header_search" id="header_search" action="<?php echo site_url('search'); ?>" onSubmit="return headerSearch(this);">
						<input type="text" placeholder="Search" class="input" name="skeyword" accesskey="s" />
						<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
					</form>
					<script type="text/javascript">
					//<![CDATA[
					$(function() {
						$('.dropdown').hover(function() {
							$(this).addClass('open');
						}, function() {
							$(this).removeClass('open');
						});
					});
					function headerSearch(f) {
						var skeyword = f.skeyword.value.replace(/(^\s*)|(\s*$)/g,'');
						if (skeyword.length < 2) {
							alert('2글자 이상으로 검색해 주세요');
							f.skeyword.focus();
							return false;
						}
						return true;
					}
					//]]>
					</script>
				-->
				<? if($this->session->userdata('login_stack')) { ?><button onclick="location.href='/main/manager/'" class="btn_admiin_change">관리자전환</button><? } ?>
				<div class="tm_today">
					<script>
						var today = new Date();
						var dd = today.getDate();
						var mm = today.getMonth()+1; //January is 0!
						var yyyy = today.getFullYear();
						if(dd<10) {
						    dd='0'+dd
						}
						if(mm<10) {
						    mm='0'+mm
						}
						today = yyyy+'년 ' +mm+'월 ' +dd+'일 ';
						document.write(today);
					</script>
				</div>
			</div>
			<div class="right">
				<a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" title="로그아웃"><span class="material-icons">power_settings_new</span></a>
				<!--<ul class="top_menu">
					<?php if ($this->member->is_admin() === 'super') { ?>
						<li tooltip-text="관리자"><a href="<?php echo site_url(config_item('uri_segment_admin')); ?>"><span class="material-icons">settings</span></a></li>
					<?php } ?>

					<?php if ($this->cbconfig->item('open_currentvisitor')) { ?>
						<li tooltip-text="현재접속자"><a href="<?php echo site_url('currentvisitor'); ?>"><span class="material-icons">record_voice_over</span><span class="badge"><?php echo element('current_visitor_num', $layout); ?></span></a></li>
					<?php } ?>

					<?php
					if ($this->member->is_member()) {
						if ($this->cbconfig->item('use_notification')) {
					?>
					<li class="notifications">
						<span class="material-icons">notification_important</span><?php echo number_format((int) element('notification_num', $layout)); ?>
						<div class="notifications-menu"></div>
						<script type="text/javascript">
						//<![CDATA[
						$(document).mouseup(function (e)
						{
							var noticontainer = $('.notifications-menu');

							if ( ! noticontainer.is(e.target) // if the target of the click isn't the container...
								&& noticontainer.has(e.target).length === 0) // ... nor a descendant of the container
							{
								noticontainer.hide();
							}
						});
						//]]>
						</script>
					</li>
					<?php
						}
					?>

				<li tooltip-text="마이페이지"><a href="<?php echo site_url('mypage'); ?>"><span class="material-icons">assignment_ind</span></a></li>
				<li tooltip-text="로그아웃"><a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" title="로그아웃"><span class="material-icons">power_settings_new</span></a></li>
					<?php } else { ?>
						<li tooltip-text="로그인"><a href="<?php echo site_url('login?url=' . urlencode(current_full_url())); ?>">로그인</a></li>
						<li tooltip-text="회원가입"><a href="<?php echo site_url('register'); ?>">회원가입</a></li>
					<?php } ?>
				</ul>-->
			</div><!-- right END -->
		</div><!-- header END -->
		<div class="genie_container">
			<div class="snb sidebar" id="mySidebar">
				 <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
				<h2 class="nickname"><?php echo html_escape($this->member->item('mem_username')); ?></h2>
				<div class="deposit_info">
<?
        $my = $this->funn->getCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        //echo "my['coin'] : ". $my['coin'] .", my['price_phn'] : ". $my['price_phn'] ."<br>";
		//log_message("ERROR","예치금 잔액 : ".$my['coin']."/".$my['price_phn']);
        $memPayType = $this->member->item('mem_pay_type');               // 선후불제 타입(선불제: B, 후불제:A, 정액제: T)
        $totalCoin = number_format($my['coin'], 0);           // 충전금액 or 사용금액(-값)
        $arlimTackNumber = '';               // 알림톡 발송건수
        $arlimTackUnitPrice = '';           // 알림톡 단가(값 예; 건당 1.55원)
        $frinedTackNumber = '';              // 친구톡 발송건수
        $frinedTackUnitPrice = '';          // 친구톡 단가
        $frinedTackImageNumber = '';         // 친구톡 이미지 발송건수
        $frinedTackImageUnitPrice = '';     // 친구톡 이미지 단가
        $messageNumber = '';              // 문자 발송건수
        $messageUnitPrice = '';          // 문자 단가
        // 2019.09.20 변수 추가
        $messageSmsNumber = '';          // sms 발송 건수
        $messageSmsUnitPrice = '';      // sms 단가
        $messageMmsNumber = '';          // Mms 발송 건수
        $messageMmsUnitPrice = '';      // Mms 단가

        //echo "memPayType : ". $memPayType ."<br>";
		//log_message("ERROR", "mem_pay_type : ".$memPayType);
        //log_message("ERROR", "mem_pay_type = B : ".($memPayType === 'B'));
        //log_message("ERROR", "empty(paty_type) = B : ".empty($memPayType));
        //$memPayType = 'A';
		if($memPayType == 'B' || empty($memPayType)) {
            //log_message("ERROR", "mem_pay_type1 : B");
            if( $my['coin'] > 0 && $my['price_at'] > 0 ) {      // 알림톡 건수 및 단가
                $arlimTackNumber = number_format(floor($my['coin'] / $my['price_at']));
                $arlimTackUnitPrice = $my['price_at'].'원/건';
            } else {
                $arlimTackNumber = 0;
                $arlimTackUnitPrice = $my['price_at'].'원/건';
            }
            if( $my['coin'] > 0 && $my['price_ft'] > 0) {       // 친구톡 건수 및 단가
                $frinedTackNumber = number_format(floor($my['coin'] / $my['price_ft']));
                $frinedTackUnitPrice = $my['price_ft'].'원/건';
            } else {
                $frinedTackNumber = 0;
                $frinedTackUnitPrice = $my['price_ft'].'원/건';
            }
            if( $my['coin'] > 0 && $my['price_ft_img'] > 0 ) {  // 친구톡 이미지 건수 및 단가
                $frinedTackImageNumber = number_format(floor($my['coin'] / $my['price_ft_img']));
                $frinedTackImageUnitPrice = $my['price_ft_img'].'원/건';
            } else {
                $frinedTackImageNumber = 0;
                $frinedTackImageUnitPrice = $my['price_ft_img'].'원/건';
            }
            if($this->member->item('mem_2nd_send')=="sms") {
                if( $my['coin']>0 && $my['price_sms']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_sms']));
                    $messageUnitPrice = $my['price_sms'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_sms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="lms") {
                if( $my['coin']>0 && $my['price_lms']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_lms']));
                    $messageUnitPrice = $my['price_lms'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_lms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="mms") {
                if( $my['coin']>0 && $my['price_mms']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_mms']));
                    $messageUnitPrice = $my['price_mms'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_mms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="phn") {
                if( $my['coin']>0 && $my['price_phn']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_phn']));
                    $messageUnitPrice = $my['price_phn'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_phn'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
                if( $my['coin']>0 && $my['price_grs']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_grs']));
                    $messageUnitPrice = $my['price_grs'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_grs'].'원/건';
                }
                // 2019.09.20 추가
                if( $my['coin']>0 && $my['price_grs_sms']>0) {
                    $messageSmsNumber = number_format(floor($my['coin'] / $my['price_grs_sms']));
                    $messageSmsUnitPrice = $my['price_grs_sms'].'원/건';
                } else {
                    $messageSmsNumber = 0;
                    $messageSmsUnitPrice = $my['price_grs_sms'].'원/건';
                }
                if( $my['coin']>0 && $my['price_grs_mms']>0) {
                    $messageMmsNumber = number_format(floor($my['coin'] / $my['price_grs_mms']));
                    $messageMmsUnitPrice = $my['price_grs_mms'].'원/건';
                } else {
                    $messageMmsNumber = 0;
                    $messageMmsUnitPrice = $my['price_grs_mms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="NASELF") {
                if( $my['coin']>0 && $my['price_nas']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_nas']));
                    $messageUnitPrice = $my['price_nas'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_nas'].'원/건';
                }
                // 2019.09.20 추가
                if( $my['coin']>0 && $my['price_nas_sms']>0) {
                    $messageSmsNumber = number_format(floor($my['coin'] / $my['price_nas_sms']));
                    $messageSmsUnitPrice = $my['price_nas_sms'].'원/건';
                } else {
                    $messageSmsNumber = 0;
                    $messageSmsUnitPrice = $my['price_nas_sms'].'원/건';
                }
                if( $my['coin']>0 && $my['price_nas_mms']>0) {
                    $messageMmsNumber = number_format(floor($my['coin'] / $my['price_nas_mms']));
                    $messageMmsUnitPrice = $my['price_nas_mms'].'원/건';
                } else {
                    $messageMmsNumber = 0;
                    $messageMmsUnitPrice = $my['price_nas_mms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="SMART") {
                if( $my['coin']>0 && $my['price_smt']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_smt']));
                    $messageUnitPrice = $my['price_smt'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_smt'].'원/건';
                }
                // 2019.09.20 추가
                if( $my['coin']>0 && $my['price_smt']>0) {
                    $messageSmsNumber = number_format(floor($my['coin'] / $my['price_smt_sms']));
                    $messageSmsUnitPrice = $my['price_smt_sms'].'원/건';
                } else {
                    $messageSmsNumber = 0;
                    $messageSmsUnitPrice = $my['price_smt_sms'].'원/건';
                }
                if( $my['coin']>0 && $my['price_smt']>0) {
                    $messageMmsNumber = number_format(floor($my['coin'] / $my['price_smt_mms']));
                    $messageMmsUnitPrice = $my['price_smt_mms'].'원/건';
                } else {
                    $messageMmsNumber = 0;
                    $messageMmsUnitPrice = $my['price_smt_mms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="015") {
                if( $my['coin']>0 && $my['price_015']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_015']));
                    $messageUnitPrice = $my['price_015'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_015'].'원/건';
                }
            } else {
                if( $my['coin']>0 && $my['price_phn']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_phn']));
                    $messageUnitPrice = $my['price_phn'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = $my['price_phn'].'원/건';
                }
            }

        } else if ($memPayType == 'A'){
            //log_message("ERROR", "mem_pay_type1 : A");
            $arlimTackUnitPrice = $my['price_at'];
            $frinedTackUnitPrice = $my['price_ft'];
            $frinedTackImageUnitPrice = $my['price_ft_img'];
            if($this->member->item('mem_2nd_send')=="sms") {
                $messageUnitPrice = $my['price_sms'];
            } else if($this->member->item('mem_2nd_send')=="lms") {
                $messageUnitPrice = $my['price_lms'];
            } else if($this->member->item('mem_2nd_send')=="mms") {
                $messageUnitPrice = $my['price_mms'];
            } else if($this->member->item('mem_2nd_send')=="phn") {
                $messageUnitPrice = $my['price_phn'];
            } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
                $messageUnitPrice = $my['price_grs'];
                // 2019.09.20 추가
                $messageSmsUnitPrice = $my['price_grs_sms'];
                $messageMmsUnitPrice = $my['price_grs_mms'];
            } else if($this->member->item('mem_2nd_send')=="NASELF") {
                // 2019.09.20 추가
                $messageSmsUnitPrice = $my['price_nas_sms'];
                $messageMmsUnitPrice = $my['price_nas_mms'];
                $messageUnitPrice = $my['price_nas'];
            } else if($this->member->item('mem_2nd_send')=="SMART") {
                $messageUnitPrice = $my['price_smt'];
                // 2019.09.20 추가
                $messageSmsUnitPrice = $my['price_smt_sms'];
                $messageMmsUnitPrice = $my['price_smt_mms'];
            } else if($this->member->item('mem_2nd_send')=="015") {
                $messageUnitPrice = $my['price_015'];
            } else {
                $messageUnitPrice = $my['price_phn'];
            }
        }
		if($messageSmsNumber == "") $messageSmsNumber = "0";
		if($messageNumber == "") $messageNumber = "0";
?>
					<? if($memPayType == 'A'){ ?>
					<h3>후불제</h3>
					<? }else{ ?>
					<h3>나의 예치금</h3>
					<p><?=$totalCoin ?></p>
					<? } ?>
					<div class="fee_box" id="id_SentNumber" style="display:<?=($memPayType == 'A') ? "none" : ""?>;">
						<ul>
							<li><a href="javascript:area_over('id_SentNumber', 'id_SentUnitPrice');" class="price_on">발송 잔여량</a></li>
							<li>|</li>
							<li><a href="javascript:area_over('id_SentUnitPrice', 'id_SentNumber');">발송 단가</a></li>
						</ul>
						<dl>
							<dt>알림톡</dt>
							<dd><?= $arlimTackNumber ?>건</dd>
							<dt>친구톡</dt>
							<dd><?= $frinedTackNumber ?>건</dd>
							<dt>친구톡(이미지)</dt>
							<dd><?= $frinedTackImageNumber ?>건</dd>
							<dt>단문문자(SMS)</dt>
							<dd><?= $messageSmsNumber ?>건</dd>
							<dt>장문문자(LMS)</dt>
							<dd><?= $messageNumber ?>건</dd>
						</dl>
					</div>
					<div class="fee_box" id="id_SentUnitPrice" style="display:<?=($memPayType == 'A') ? "" : "none"?>;">
						<ul>
							<? if($memPayType != 'A'){ ?>
							<li><a href="javascript:area_over('id_SentNumber', 'id_SentUnitPrice');">발송 잔여량</a></li>
							<li>|</li>
							<? } ?>
							<li><a href="javascript:area_over('id_SentUnitPrice', 'id_SentNumber');" class="price_on">발송 단가</a></li>
						</ul>
						<dl>
							<dt>알림톡</dt>
							<dd><?=$arlimTackUnitPrice?></dd>
							<dt>친구톡</dt>
							<dd><?= $frinedTackUnitPrice ?></dd>
							<dt>친구톡(이미지)</dt>
							<dd><?= $frinedTackImageUnitPrice ?></dd>
							<dt>단문문자(SMS)</dt>
							<dd><?=$messageSmsUnitPrice?></dd>
							<dt>장문문자(LMS)</dt>
							<dd><?=$messageUnitPrice?></dd>
						</dl>
					</div>
				</div>
				<div class="menu_dashboard" id="menu_dashboard" onclick="location.href='/home'" style="cursor: pointer"><span class="material-icons">assessment</span> 실시간 현황</div>
				<div class="pf_shop">
					<? if($this->member->item('mem_contract_type') == "P"){ //계약형태 (S.스텐다드, P.프리미엄) ?>
						<a href="/login/pfmall_login" target="_blank"><span class="material-icons">add_shopping_cart</span> 쇼핑몰 바로가기</a>
					<? }else{ ?>
						<a href="/mall/info"><span class="material-icons">add_shopping_cart</span> 쇼핑몰 둘러보기</a>
					<? } ?>
				</div>
				<div class="snb_menu">
					<ul id="accordion" class="accordion">
						<li<?=(strpos("/".$req_url, "/dhnbiz/sender/send/") == true OR strpos("/".$req_url, "/dhnbiz/sender/history") == true) ? " class='open'" : ""?>>
						<a href="/dhnbiz/sender/send/talk_adv"><div class="link"><span class="material-icons">send</span>메시지발송</div></a>
						</li>
						<? if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
						<li<?=(strpos("/".$req_url, "/dhnbiz/template/") == true OR strpos("/".$req_url, "/dhnbiz/sendprofile/") == true OR strpos("/".$req_url, "/biz/sendphnmng") == true OR strpos("/".$req_url, "/dhnbiz/sender/image") == true OR strpos("/".$req_url, "/biz/partner/") == true) ? " class='open'" : ""?>>
							<div class="link"><span class="material-icons">fact_check</span>발송관리<i class="fa fa-chevron-down"></i></div>
							<ul class="submenu">
								<li<?=(strpos("/".$req_url, "/dhnbiz/sendprofile/lists") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/sendprofile/lists">발신관리</a></li>
								<!--<li<?=(strpos("/".$req_url, "/dhnbiz/template/lists") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/template/lists">템플릿관리</a></li>
								<li<?=(strpos("/".$req_url, "/dhnbiz/sender/image_w_list") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/sender/image_w_list">친구톡 이미지관리</a></li>-->
								<li<?=(strpos("/".$req_url, "/biz/partner/lists") == true) ? " class='lm_on'" : ""?>><a href="/biz/partner/lists">파트너관리</a></li>
								<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 보임 ?>
								<li<?=(strpos("/".$req_url, "/dhnbiz/template/public_lists") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/template/public_lists">템플릿관리</a></li>
								<? } //최고관리자 권한만 보임 ?>
							</ul>
						</li>
						<? } //if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
						<li<?=(strpos("/".$req_url, "/pop/screen") == true or strpos("/".$req_url, "/mall/compare") == true) ? " class='open'" : ""?>>
							<div class="link"><span class="material-icons">format_shapes</span>스마트전단<i class="fa fa-chevron-down"></i></div>
							<ul class="submenu">
								<li<?=(strpos("/".$req_url, "/pop/screen") == true) ? " class='lm_on'" : ""?>><a href="/pop/screen">스마트전단 목록</a></li>
								<li<?=(strpos("/".$req_url, "/mall/compare") == true) ? " class='lm_on'" : ""?>><a href="/mall/compare">스마트전단 샘플</a></li>
								<li<?=(strpos("/".$req_url, "/pop/screen/write") == true) ? " class='lm_on'" : ""?>><a href="/pop/screen/write">스마트전단 만들기</a></li>
							</ul>
						</li>
						<!--<li<?=(strpos("/".$req_url, "/pop/printer") == true) ? " class='open'" : ""?>>
							<a href="/pop/printer"><div class="link"><span class="material-icons">wallpaper</span>스마트POP</div></a>
						</li>-->
						<li<?=(strpos("/".$_SERVER['HTTP_HOST'].$req_url, $_SERVER['HTTP_HOST']."/deposit") == true) ? " class='open'" : ""?>>
							<a href="/deposit"><div class="link"><span class="material-icons">battery_charging_full</span>충전하기</div></a>
						</li>
						<!--<li<?=(strpos("/".$req_url, "/mall/order") == true || strpos("/".$req_url, "/mall/goods") == true) ? " class='open'" : ""?>>
							<div class="link"><span class="material-icons">widgets</span>쇼핑몰관리<i class="fa fa-chevron-down"></i></div>
							<ul class="submenu">
								<li <?=(strpos("/".$req_url, "/mall/order") == true) ? " class='lm_on'" : ""?>><a href="/mall/order">주문관리</a></li>
								<li <?=(strpos("/".$req_url, "/mall/goods") == true) ? " class='lm_on'" : ""?>><a href="/mall/goods">상품관리</a></li>
							</ul>
						</li>-->
						<li<?=(strpos("/".$req_url, "/biz/customer/") == true) ? " class='open'" : ""?>>
							<a href="/biz/customer/lists"><div class="link"><span class="material-icons">account_box</span>고객관리</div></a>
						</li>
						<!--<li<?=(strpos("/".$req_url, "/customer/people") == true) ? " class='open'" : ""?>>
							<a href="/customer/people"><div class="link"><span class="material-icons">people</span>고객분포</div></a>
						</li>-->
						<li<?=(strpos("/".$req_url, "/biz/myinfo/") == true) ? " class='open'" : ""?>>
							<a href="/biz/myinfo/info"><div class="link"><span class="material-icons">storefront</span>매장정보</div></a>
						</li>
						<!--<li<?=(strpos("/".$req_url, "mypage/point") == true) ? " class='open'" : ""?>>
							<a href="/mypage/point"><div class="link"><span class="material-icons">star_border</span>포인트설정</div></a>
						</li>-->
						<li<?=(strpos("/".$req_url, "/board/") == true || strpos("/".$req_url, "/faq/") == true || strpos("/".$req_url, "/post/") == true || strpos("/".$req_url, "/write/") == true) ? " class='open'" : ""?>>
							<div class="link"><span class="material-icons">headset_mic</span>고객센터<i class="fa fa-chevron-down"></i></div>
							<ul class="submenu">
								<li<?=(strpos("/".$req_url, "/board/notice") == true) ? " class='lm_on'" : ""?>><a href="/board/notice">공지사항</a></li>
								<li<?=(strpos("/".$req_url, "/faq/faq") == true) ? " class='lm_on'" : ""?>><a href="/faq/faq">FAQ</a></li>
								<li<?=(strpos("/".$req_url, "/board/qna") == true) ? " class='lm_on'" : ""?>><a href="/board/qna">1:1문의</a></li>
							</ul>
						</li>
						<!--<li<?=(strpos("/".$req_url, "/manual") == true) ? " class='open'" : ""?>>
							<a href="/manual"><div class="link"><span class="material-icons">dvr</span>메뉴얼</div></a>
						</li>-->
						<li<?=(strpos("/".$req_url, "/manual") == true OR strpos("/".$req_url, "/manual/send") == true OR strpos("/".$req_url, "/manual/leaflet") == true OR strpos("/".$req_url, "/manual/pop") == true) ? " class='open'" : ""?>>
							<div class="link"><span class="material-icons">dvr</span>메뉴얼<i class="fa fa-chevron-down"></i></div>
							<ul class="submenu">
								<li<?=(strpos("/".$req_url, "/manual/send") == true) ? " class='lm_on'" : ""?>><a href="/manual/send">메시지발송</a></li>
								<li<?=(strpos("/".$req_url, "/manual/leaflet") == true) ? " class='lm_on'" : ""?>><a href="/manual/leaflet">스마트전단문자</a></li>
								<!--<li<?=(strpos("/".$req_url, "/manual/pop") == true) ? " class='lm_on'" : ""?>><a href="/manual/pop">스마트POP</a></li>-->
							</ul>
						</li>
						<? if($this->member->item('mem_level') >= 100){ //최고관리자만 ?>
						<li<?=(strpos("/".$req_url, "/mng/design/") == true OR strpos("/".$req_url, "/biz/refund/") == true OR strpos("/".$req_url, "/biz/manager/") == true OR strpos("/".$req_url, "/biz/statistics/") == true) ? " class='open'" : ""?>>
							<div class="link"><span class="material-icons">photo_filter</span>관리자기능<i class="fa fa-chevron-down"></i></div>
							<ul class="submenu">
								<li<?=(strpos("/".$req_url, "/mng/design/templet") == true) ? " class='lm_on'" : ""?>><a href="/mng/design/templet">디자인관리</a></li>
								<li<?=(strpos("/".$req_url, "/biz/manager/vbank") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/vbank">충전관리</a></li>
								<li<?=(strpos("/".$req_url, "/biz/manager/settlement") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/settlement">정산관리</a></li>
								<li<?=(strpos("/".$req_url, "/biz/statistics/day") == true) ? " class='lm_on'" : ""?>><a href="/biz/statistics/day">통계관리</a></li>
							</ul>
						</li>
						<? } ?>
					</ul>
				</div><!-- snb_menu END -->
			</div><!-- snb END -->
			<div class="contents_wrap" id="main">

				<!-- 본문 시작 -->
				<?php if (isset($yield))echo $yield; ?>
				<!-- 본문 끝 -->

			</div><!-- contents_wrap END -->

			<script>
			function openNav() {
			document.getElementById("mySidebar").style.width = "240px";
			document.getElementById("main").style.marginLeft = "0";
			document.getElementById("menu_dashboard").style.display= "block";
			}

			function closeNav() {
			document.getElementById("mySidebar").style.width = "0";
			document.getElementById("main").style.marginLeft= "0";
			document.getElementById("menu_dashboard").style.display= "none";
			}
			</script>

		</div><!-- genie_container END -->

		<!-- 모바일 사이드 메뉴 -->
		<div class="mobile-nav">
			<div class="hamburger-menu">
				<div class="bar"></div>
			</div>
			<div class="menu_top"><?php echo html_escape($this->member->item('mem_username')); ?></div>
			<div class="mobile_logout">
				<? if($this->session->userdata('login_stack')) { ?><button onclick="location.href='/main/manager/'" class="btn_admiin_change">관리자전환</button><? } ?>
				<a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" title="로그아웃"><span class="material-icons">power_settings_new</span></a>
			</div>
			<div class="m_menu">
				<div class="menu_dashboard" onclick="location.href='/home'" style="cursor: pointer">실시간 현황</div>
				<!--<div class="pf_shop_m"><a href="/login/pfmall_login" target="_blank"><span class="material-icons">add_shopping_cart</span> 쇼핑몰 바로가기</a></div>-->
				<ul>
					<li onclick="location.href='/dhnbiz/sender/send/talk_adv'" class=""><span class="material-icons">send</span>메시지발송</li>
					<li onclick="location.href='/deposit'" class=""><span class="material-icons">battery_charging_full</span>충전하기</li>
					<!--<div class="m_line"></div>-->
					<li onclick="location.href='/biz/customer/lists'" class=""><span class="material-icons">account_box</span>고객관리</li>
					<li onclick="location.href='/biz/myinfo/info'" class=""><span class="material-icons">store</span>매장정보</li>
					<li onclick="location.href='/board/notice'" class=""><span class="material-icons">headset_mic</span>고객센터</li>
					<!--<li onclick="location.href='/manual/send'" class=""><span class="material-icons">dvr</span>메뉴얼</li>-->
					<? if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
					<li onclick="location.href='/biz/partner/lists'" class=""><span class="material-icons">source</span>파트너관리</li>
					<? } //if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
					<? if($this->member->item('mem_level') >= 100){ //최고관리자 이상 권한만 보임 ?>
					<li onclick="location.href='/biz/manager/vbank'" class=""><span class="material-icons">payments</span>충전관리</li>
					<? } //if($this->member->item('mem_level') >= 100){ //최고관리자 이상 권한만 보임 ?>
					<div class="m_line"></div>
				</ul>
				  <div class="btn_add_home" onclick="HomeButtonAdd('지니','cm_id=bookmark');"><i class="material-icons">star</i> 홈화면에 바로가기 추가</div>
					<div id="myModal_HomeButton" class="pf_modal">
					<!-- Modal content -->
					<div class="pf_modal-content">
						<div class="pf_modal-header">
						<span class="pf_modal_close">×</span>
						<h2>아이폰, 아이패드 홈버튼 추가 안내</h2>
						</div>
						<div class="pf_modal-body">
						 <p>
							 1. 애플 사용자의 경우 사파리 브라우저에서 하단 중앙버튼을 클릭
						 </p>
						 <p>
							<img src="/images/app_info1.jpg" alt="">
						 </p>
						 <p class="mg_t20">
							 2. 버튼클릭 후 홈화면에 추가 메뉴를 클릭
						 </p>
						 <p>
							<img src="/images/app_info2.jpg" alt="">
						 </p>
						 <p class="mg_t20">
							 3. 추가 버튼 클릭 - 완료!
						 </p>
						 <p>
							<img src="/images/app_info3.jpg" alt="">
						 </p>
						</div>
					</div>
				</div>
				<script type="text/Javascript">
				/* ==============================================================================
				# 홈버튼 탭 추가
				* 사용방법 : onclick='HomeButtonAdd("플친몰","cm_id=bookmark")'
				============================================================================== */
				function HomeButtonAdd(title,code){
					var HomeButtonTitle = title;
					var LogAnalysisCode = code;
					//alert("HomeButtonTitle : "+ HomeButtonTitle +", LogAnalysisCode : "+ LogAnalysisCode); return;

					var HomeButtonTitle = encodeURI(HomeButtonTitle);
					var HomePageUri = document.domain;
					var WebRootPathUri = "http://"+document.domain;
					var HomePageUri = "http://"+document.domain;
					var iconurl="http://"+document.domain+"/favicon/android-icon-192x192.png";
					//alert("iconurl : "+ iconurl);
					//alert("HomeButtonTitle : "+ HomeButtonTitle +"\n"+"HomePageUri : "+ HomePageUri +"\n"+"WebRootPathUri : "+ WebRootPathUri +"\n"+"HomePageUri : "+ HomePageUri +"\n"+"iconurl : "+ iconurl); return;

					var HomeButtonIconUri = WebRootPathUri+$('link[rel="apple-touch-icon-precomposed"]').attr("href");
					var customUrlScheme= "intent://addshortcut?url="+HomePageUri+"%3F"+LogAnalysisCode+"&icon="+iconurl+"&title="+ HomeButtonTitle+"&oq="+HomeButtonTitle+"&serviceCode=nstore&version=7#Intent;scheme=naversearchapp;action=android.intent.action.VIEW;category=android.intent.category.BROWSABLE;package=com.nhn.android.search;end";
					//alert("HomeButtonIconUri : "+ HomeButtonIconUri +"\n"+"customUrlScheme : "+ customUrlScheme); return;

					var UserAgent = navigator.userAgent.toLowerCase();
					var BlockDevice1 = UserAgent.indexOf("iphone");
					var BlockDevice2 = UserAgent.indexOf("ipad");
					var BlockDevice = BlockDevice1 + BlockDevice2;
					//alert("UserAgent : "+ UserAgent +"\n"+"BlockDevice1 : "+ BlockDevice1 +"\n"+"BlockDevice2 : "+ BlockDevice2 +"\n"+"BlockDevice : "+ BlockDevice); return;

					if(BlockDevice == -2){
						//alert(title+'을(를) 홈화면에 추가합니다.\n\n네이버앱이 없는 고객 네이버앱 설치페이지로 이동됩니다.');
						window.open(customUrlScheme);
					}else{
						//alert("아이폰, 아이패드 계열은 직접 홈버튼 추가를 사용하셔야합니다.");
						HomeButtonAdd_iphone();
					}
				}
				</script>
				<script>
				// Get the modal
				var modal_HomeButton = document.getElementById("myModal_HomeButton");

				// Get the <span> element that closes the modal
				var span_HomeButton = document.getElementsByClassName("pf_modal_close")[0];

				// When the user clicks on <span> (x), close the modal
				span_HomeButton.onclick = function() {
					modal_HomeButton.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_HomeButton) {
						modal_HomeButton.style.display = "none";
					}
				}

				//아이폰, 아이패드 계열은 직접 홈버튼 추가 모달 호출
				function HomeButtonAdd_iphone(){
					//alert("HomeButtonAdd_iphone"); return;
					modal_HomeButton.style.display = "block";
				}
				</script>
			</div>
			</div>
		</div><!-- genie_wrap END -->

		<div class="backdrop"></div>
		<!-- 모바일 메뉴 끝 -->
		<!-- footer start -->
		<footer>
			<!--<div class="footer_menu clearfix">
				<ul>
					<li><a href="<?php echo document_url('company'); ?>" title="회사소개">회사소개</a></li>
					<li><a href="<?php echo document_url('policy'); ?>" title="이용약관">이용약관</a></li>
					<li><a href="<?php echo document_url('privacy'); ?>" title="개인정보 취급방침">개인정보 취급방침</a></li>
				</ul>
			</div>-->
			<!--<div class="footer_info">
				<p><?php echo $this->cbconfig->item('site_title'); ?></p>
				<?php if ($this->cbconfig->item('company_address')) { ?>
				<?php echo $this->cbconfig->item('company_address'); ?>
				<?php } ?>
				<?php if ($this->cbconfig->item('company_owner')) { ?><span><b>대표</b> <?php echo $this->cbconfig->item('company_owner'); ?></span><?php } ?>
				<?php if ($this->cbconfig->item('company_phone')) { ?><span><b>전화</b> <?php echo $this->cbconfig->item('company_phone'); ?></span><?php } ?>
				<?php if ($this->cbconfig->item('company_fax')) { ?><span><b>팩스</b> <?php echo $this->cbconfig->item('company_fax'); ?></span><?php } ?>
				<?php if ($this->cbconfig->item('company_reg_no')) { ?><span><b>사업자</b> <?php echo $this->cbconfig->item('company_reg_no'); ?></span><?php } ?>
				<?php if ($this->cbconfig->item('company_retail_sale_no')) { ?><span><b>통신판매</b> <?php echo $this->cbconfig->item('company_retail_sale_no'); ?></span><?php } ?>
				<?php if ($this->cbconfig->item('company_added_sale_no')) { ?><span><b>부가통신</b> <?php echo $this->cbconfig->item('company_added_sale_no'); ?></span><?php } ?>
				<?php if ($this->cbconfig->item('company_admin_name')) { ?><span><b>정보관리책임자명</b> <?php echo $this->cbconfig->item('company_admin_name'); ?></span><?php } ?>
			</div>-->
			<div class="footer_info">
				<p>본사 : 경상남도 창원시 의창구 차룡로 48번길 54 (팔용동) 기업연구관 302호 / 대구지사 : 대구시 수성구 알파시티1로 160 SW융합테크비즈센터 513호</p>
				<p>사업자등록번호 : 364-88-00974 / 통신판매업 : 신고번호 2018-창원의창-0272호 / 대표전화 : <strong>1522-7985</strong> / 팩스 : 0505-299-0001</p>
			</div>
			<div class="footer_copy">
				copyright © 2020 (주)대형네트웍스. .ALL RIGHTS RESERVED
			</div>
		</footer>
		<!-- footer end -->

<script type="text/javascript">
$(document).on('click', '.viewpcversion', function(){
	Cookies.set('device_view_type', 'desktop', { expires: 1 });
});
$(document).on('click', '.viewmobileversion', function(){
	Cookies.set('device_view_type', 'mobile', { expires: 1 });
});
</script>
<?php echo element('popup', $layout); ?>
<?php echo $this->cbconfig->item('footer_script'); ?>

<!--
Layout Directory : <?php echo element('layout_skin_path', $layout); ?>,
Layout URL : <?php echo element('layout_skin_url', $layout); ?>,
Skin Directory : <?php echo element('view_skin_path', $layout); ?>,
Skin URL : <?php echo element('view_skin_url', $layout); ?>,
-->

</body>
</html>
