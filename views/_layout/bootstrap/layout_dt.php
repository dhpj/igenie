<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo html_escape(element('page_title', $layout)); ?></title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
<link rel="manifest" href="/favicon/site.webmanifest">
<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<?php if (element('meta_description', $layout)) { ?><meta name="description" content="<?php echo html_escape(element('meta_description', $layout)); ?>"><?php } ?>
<?php if (element('meta_keywords', $layout)) { ?><meta name="keywords" content="<?php echo html_escape(element('meta_keywords', $layout)); ?>"><?php } ?>
<?php if (element('meta_author', $layout)) { ?><meta name="author" content="<?php echo html_escape(element('meta_author', $layout)); ?>"><?php } ?>
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>
<?php if (element('canonical', $view)) { ?><link rel="canonical" href="<?php echo element('canonical', $view); ?>" /><?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/css/bss_css/bootstrap-select.css" />
<link rel="stylesheet" type="text/css" href="/assets/DataTables/datatables.min.css"/>
<script type="text/javascript" src="/assets/DataTables/datatables.min.js"></script>

<?php echo $this->managelayout->display_css(); ?>

<link href="/bizM/css/datepicker3.css" rel="stylesheet" type="text/css">

<!-- Theme -->
<link href="/bizM/css/main.css" rel="stylesheet" type="text/css">
<link href="/bizM/css/plugins.css" rel="stylesheet" type="text/css">
<!--<link href="/collected_statics/assets/css/responsive.css" rel="stylesheet" type="text/css" />-->
<link href="/bizM/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/bizM/css/font-awesome.min.css">
<!--[if IE 7]>
<link rel="stylesheet" href="/collected_statics/assets/css/fontawesome/font-awesome-ie7.min.css">
<![endif]-->
<!--[if IE 8]>
<link href="assets/css/ie8.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="/bizM/css/css.css" rel="stylesheet" type="text/css">
<!--=== JavaScript ===-->
<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script> -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<!-- 2019.01.25 kim min soo 추가 -->
<link href="/css/common.css?v=<?=date("ymdHis")?>" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="/js/common.js?v=<?=date("ymdHis")?>"></script>
<!-- 링크확인 스크립트 -->
  <script
    type="text/javascript"
    src="//code.jquery.com/jquery-1.11.0.js"
    
  ></script>


  <!-- TODO: Missing CoffeeScript 2 -->

  <script type="text/javascript">


    $(window).load(function(){
      
$("#find_url").click( function() {
    var url = "" + $("#url_valid").val();
    window.open();
});

    });

</script>


<script type="text/javascript" src="/bizM/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="/bizM/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="/js/bss_js/bootstrap-select.js"></script>

<!-- CB -->
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
//var test = "<?php echo $_SERVER['REQUEST_URI'];?>"
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
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
<?php echo $this->managelayout->display_js(); ?>

<!-- BizM -->
<style type="text/css">
	body.imp-payment-progress {position: static}
	body.imp-payment-progress > :not(.imp-dialog) {display: none}
	.imp-dialog {display : none; position : fixed; top : 0; bottom : 0;left : 0; right : 0; width : 100%; height: 100%; z-index:99999;}
	.imp-dialog .imp-frame-pc.imp-frame-danal, .imp-dialog .imp-frame-pc.imp-frame-danal_tpay { left:50% !important; margin-left:-345px; margin-top: 50px;}
	.imp-close {text-decoration : none; position : absolute; top : 50px; right : 10px; font-size : 48px; color : #fff; cursor : pointer}
	.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}
</style>
</head>

<body>
<div id="dhnWrap">
	<header role="banner">
		<div class="head_logo">
			<a href="/main/"><span>카카오</span>마트톡</a>
		</div>
		<div class="head_loginInfo">
			<ul>
				<li><a href="/biz/myinfo/info"><?php echo html_escape($this->member->item('mem_nickname')); ?></a></li>
				<? if($this->session->userdata('login_stack')) { ?>
				    <li><span class="account"><a href="/main/manager/">관리자 전환</a></span></a></li>
				<? } ?>
				<li class="msg"><a href="javascript:open_all_msg_list();">메세지함</a></li>
				<li class="logout"><a href="/login/logout/">로그아웃</a></li>
			</ul>
		</div>

	</header>
	<!--//#header-->

	<?
        $my = $this->funn->getCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        //log_message("ERROR","예치금 잔액 : ".$my['coin']."/".$my['price_phn']);
        $memPayType = $this->member->item('mem_pay_type');               // 선후불제 타입(선불제: B, 후불제:A, 정액제: T) 
        $totalCoin = number_format($my['coin'], 0);           // 충전금액 or 사용금액(-값)
        $arlimTackNumber = '';               // 알림톡 발송건수
        $arlimTackUnitPrice = '';           // 알림톡 단가(값 예; 건당 1.55원)
        $frinedTackNumber = '';              // 친구톡 발송건수
        $frinedTackUnitPrice = '';          // 친구톡 단가
        $frinedTackImageNumber = '';         // 찬구톡 이미지 발송건수
        $frinedTackImageUnitPrice = '';     // 친구톡 이미지 단가
        $messageNumber = '';              // 문자 발송건수
        $messageUnitPrice = '';          // 문자 단가
        
        log_message("ERROR", "mem_pay_type : ".$memPayType);
        log_message("ERROR", "mem_pay_type = B : ".($memPayType === 'B'));
        log_message("ERROR", "empty(paty_type) = B : ".empty($memPayType));
        if($memPayType == 'B' || empty($memPayType)) {
            log_message("ERROR", "mem_pay_type1 : B");
            if( $my['coin'] > 0 && $my['price_at'] > 0 ) {      // 알림톡 건수 및 단가
                $arlimTackNumber = number_format(floor($my['coin'] / $my['price_at']));
                $arlimTackUnitPrice = '단가 : '.$my['price_at'].'원/건';            	
            } else {
                $arlimTackNumber = 0;
                $arlimTackUnitPrice = '단가 : '.$my['price_at'].'원/건'; 
            }
            if( $my['coin'] > 0 && $my['price_ft'] > 0) {       // 친구톡 건수 및 단가
                $frinedTackNumber = number_format(floor($my['coin'] / $my['price_ft']));
                $frinedTackUnitPrice = '단가 : '.$my['price_ft'].'원/건';
            } else {
                $frinedTackNumber = 0;
                $frinedTackUnitPrice = '단가 : '.$my['price_ft'].'원/건';
            }
            if( $my['coin'] > 0 && $my['price_ft_img'] > 0 ) {  // 친구톡 이미지 건수 및 단가
                $frinedTackImageNumber = number_format(floor($my['coin'] / $my['price_ft_img']));
                $frinedTackImageUnitPrice = '단가 : '.$my['price_ft_img'].'원/건';
            } else {
                $frinedTackImageNumber = 0;
                $frinedTackImageUnitPrice = '단가 : '.$my['price_ft_img'].'원/건';
            }
            if($this->member->item('mem_2nd_send')=="sms") {
                if( $my['coin']>0 && $my['price_sms']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_sms']));
                    $messageUnitPrice = '단가 : '.$my['price_sms'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_sms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="lms") {
                if( $my['coin']>0 && $my['price_lms']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_lms']));
                    $messageUnitPrice = '단가 : '.$my['price_lms'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_lms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="mms") {
                if( $my['coin']>0 && $my['price_mms']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_mms']));
                    $messageUnitPrice = '단가 : '.$my['price_mms'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_mms'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="phn") {
                if( $my['coin']>0 && $my['price_phn']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_phn']));
                    $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
                if( $my['coin']>0 && $my['price_grs']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_grs']));
                    $messageUnitPrice = '단가 : '.$my['price_grs'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_grs'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="NASELF") {
                if( $my['coin']>0 && $my['price_nas']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_nas']));
                    $messageUnitPrice = '단가 : '.$my['price_nas'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_nas'].'원/건';
                }
            } else if($this->member->item('mem_2nd_send')=="015") {
                if( $my['coin']>0 && $my['price_015']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_015']));
                    $messageUnitPrice = '단가 : '.$my['price_015'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_015'].'원/건';
                }
            } else {
                if( $my['coin']>0 && $my['price_phn']>0) {
                    $messageNumber = number_format(floor($my['coin'] / $my['price_phn']));
                    $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
                } else {
                    $messageNumber = 0;
                    $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
                }
            }

        } else if ($memPayType == 'A'){
            log_message("ERROR", "mem_pay_type1 : A");
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
            } else if($this->member->item('mem_2nd_send')=="NASELF") {
                $messageUnitPrice = $my['price_nas'];
            } else if($this->member->item('mem_2nd_send')=="015") {
                $messageUnitPrice = $my['price_015'];
            } else {
                $messageUnitPrice = $my['price_phn'];
            }
        }
	
	
	?>

    <main>       
<!-- Sidebar -->
		<aside>
	        <div id="side">
				<!--button class="nav_hide"></button-->
					<div class="snb_mart">
						<div class="profile"></div>
						<div class="profile_name"><?php echo html_escape($this->member->item('mem_username')); ?></div>
						<select style="width: 100%;">
							<option>계정 바로가기</option>
							<option>계정1</option>
							<option>계정2</option>
							<option>계정3</option>
						</select>
						<div class="deposit">
							<div class="deposit_total">
								<? if ($memPayType == 'B' || empty($memPayType)) { log_message("ERROR", "mem_pay_type2 : B"); ?>
								<span class="deposit_tit">충전액</span>
								<? } else if ($memPayType == 'A') { log_message("ERROR", "mem_pay_type2 : B"); ?>
								<span class="deposit_tit">후불제</span>
								<? } ?>
								<span class="deposit_total_num"><?=$totalCoin ?></span>
							</div>
							<div class="deposit_panel">
								<ul>
									<li>
										<span class="deposit_tit">알림톡</span>
										<? if ($memPayType == 'B' || empty($memPayType)) { ?> 
										<span class="deposit_num"><?= $arlimTackNumber ?></span>
											<div class="icon_tooltip unit">
	            								<i class="fas fa-info-circle"></i>
	            								<div class="icon_tooltip_wrap"><?= $arlimTackUnitPrice ?></div>
	            							</div>
            							
										<? }  else if ($memPayType == 'A') { ?>
										<span class="deposit_total_num"><?= $arlimTackUnitPrice ?></span>
										<? } ?>
									</li>
									<li>
										<span class="deposit_tit">친구톡</span>
										<? if ($memPayType == 'B' || empty($memPayType)) { ?> 
										<span class="deposit_num"><?= $frinedTackNumber ?></span>
            							<div class="icon_tooltip unit">
            								<i class="fas fa-question-circle"></i>
            								<div class="icon_tooltip_wrap"><?= $frinedTackUnitPrice ?></div>
            							</div>
										<? }  else if ($memPayType == 'A') { ?>
										<span class="deposit_total_num"><?= $frinedTackUnitPrice ?></span>
										<? } ?>								</li>
									<li>
										<span class="deposit_tit">친구톡<em>(이미지)</em></span>
										<? if ($memPayType == 'B' || empty($memPayType)) { ?> 
										<span class="deposit_num"><?= $frinedTackImageNumber ?></span>
            							<div class="icon_tooltip unit">
            								<i class="fas fa-question-circle"></i>
            								<div class="icon_tooltip_wrap"><?= $frinedTackImageUnitPrice ?></div>
            							</div>
										<? }  else if ($memPayType == 'A') { ?>
										<span class="deposit_total_num"><?= $frinedTackImageUnitPrice ?></span>
										<? } ?>								</li>
									<li>
										<span class="deposit_tit">문자</span>
										<? if ($memPayType == 'B' || empty($memPayType)) { ?> 
										<span class="deposit_num"><?= $messageNumber ?></span>
            							<div class="icon_tooltip unit">
            								<i class="fas fa-question-circle"></i>
            								<div class="icon_tooltip_wrap"><?= $messageUnitPrice ?></div>
            							</div>
										<? }  else if ($memPayType == 'A') { ?>
										<span class="deposit_total_num"><?= $messageUnitPrice ?></span>
										<? } ?>
									</li>
									<li>
								</ul>
							</div>
						</div>
						<? if ($memPayType == 'B' || empty($memPayType)) { ?>
						<button class="btn charge">충전하기</button>
						<? } ?>
					</div>
					
					<div class="snb_menu">
							<!-- 
					        <ul>
						        <li><h3>메시지 보내기<i class="fas fa-chevron-down"></i></h3>
						        	<ul>
							        	<li>알림톡 보내기</li>
							        	<li>친구톡 보내기</li>
							        	<li>문자 보내기</li>
							        	<li>메시지목록</li>
						        	</ul>
						        </li>
						        <li><h3>이미지 관리</h3>
						        </li>
						        <li><h3>템플릿 관리</h3>
						        </li>
						        <li><h3>파트너 관리</h3>
						        </li>
						        <li><h3>고객 관리</h3>
						        </li>
						        <li><h3>통계</h3>
						        </li>
						        <li><h3>계정관리<i class="fas fa-chevron-down"></i></h3>
						        	<ul>
							        	<li><a href="#">알림톡 보내기</a></li>
							        	<li><a href="#">친구톡 보내기</a></li>
							        	<li><a href="#">문자 보내기</a></li>
						        	</ul>
						        </li>
						        <li><h3>정산관리<i class="fas fa-chevron-down"></i></h3>
						        	<ul>
							        	<li><a href="#">알림톡 보내기</a></li>
							        	<li><a href="#">친구톡 보내기</a></li>
							        	<li><a href="#">문자 보내기</a></li>
						        	</ul>
						        </li>
					        </ul>
					        -->
					        <ul>
					        <? 
					        $menuhtml = '';
					        if (element('menu', $layout)) {
					            $menu = element('menu', $layout);
					            if (element(0, $menu)) {
					                foreach (element(0, $menu) as $mkey => $mval) {    // 1레벨 메뉴
					                    // (메뉴사용(men_show) != 'Y') 이거나 (현메뉴 권한 레벨 > 접속 사용자 레벨 메뉴) 이면 메뉴 안보임 처리
					                    if(element('men_show', $mval)!="Y" || intval(element('men_allow_lv', $mval)) > intval($this->member->item('mem_level'))) { continue; }
					                    // (졉속 사용자 레벨 < 100) 고 (메뉴 권한 최대 레벨 <= 접속 사용자 레벨) 이면 메뉴 안보임 처리
					                    if($this->member->item('mem_level') < 100 && element('men_max_lv', $mval) <= $this->member->item('mem_level')) { continue; }
					                    
					                    if (element(element('men_id', $mval), $menu)) {    // 하위 메뉴가 있으면
					                        $mlink = element('men_link', $mval) ? element('men_link', $mval) : 'javascript:;'; // 메뉴 링크 설정
					                        
					                        //$menuhtml .= '<li class="nav'.element('men_order', $mval).'">
		                                    //<a href="' . $mlink . '" ' . element('men_custom', $mval);
					                        //$menuhtml .= '<li><h3><a href="' . $mlink . '" ' . element('men_custom', $mval);
					                        $menuhtml .= '<li><h3>';
					                        //if (element('men_target', $mval)) {
					                        //    $menuhtml .= ' target="' . element('men_target', $mval) . '"';
					                        //}
					                        //$menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '"><i class="icon-'.element('men_order', $mval).'"></i>' . html_escape(element('men_name', $mval)) . '</a>
		                                    //<ul class="sub-menu">';
					                        $menuhtml .= html_escape(element('men_name', $mval)) . '<i class="fas fa-chevron-down"></i></h3><ul>';
					                        
					                        // 서버 메뉴 가져 오기
					                        foreach (element(element('men_id', $mval), $menu) as $skey => $sval) {
					                            // (메뉴사용(men_show) != 'Y') 이거나 (현메뉴 권한 레벨 > 접속 사용자 레벨 메뉴) 이면 서버메뉴 안보임 처리
					                            if(element('men_show', $sval)!="Y" || intval(element('men_allow_lv', $sval)) > intval($this->member->item('mem_level'))) { continue; }
					                            // (졉속 사용자 레벨 < 100) 고 (메뉴 권한 최대 레벨 <= 접속 사용자 레벨) 이면 서버메뉴 안보임 처리
					                            if($this->member->item('mem_level') < 100 && element('men_max_lv', $sval) <= $this->member->item('mem_level')) { continue; }
					                            
					                            $slink = element('men_link', $sval) ? element('men_link', $sval) : 'javascript:;'; // 서버 메뉴 링크 설정
					                            
					                            $menuhtml .= '<li><a href="' . $slink . '" ' . element('men_custom', $sval);
					                            if (element('men_target', $sval)) {
					                                $menuhtml .= ' target="' . element('men_target', $sval) . '"';
					                            }
					                            //if (element('men_order', $sval)=='12') {
					                            //    $menuhtml .=' style="background-color: #fae100;color: #3b1e1e;';
					                            //}
					                            //$menuhtml .= ' title="' . html_escape(element('men_name', $sval)) . '"><i class="icon-angle-right"></i>' . html_escape(element('men_name', $sval)) . '</a></li>';
					                            $menuhtml .= ' title="' . html_escape(element('men_name', $sval)) . '">' . html_escape(element('men_name', $sval)) . '</a></li>';
					                        }
					                        
					                        $menuhtml .= '</ul></li>';
					                    } else { // 하위 메뉴가 없으면
					                        $mlink = element('men_link', $mval) ? element('men_link', $mval) : 'javascript:;'; // 메뉴 링크 설정
					                        //$menuhtml .= '<li class="nav'.element('men_order', $mval).'"><a href="' . $mlink . '" ' . element('men_custom', $mval);
					                        $menuhtml .= '<li><h3><a href="' . $mlink . '" ' . element('men_custom', $mval);
					                        if (element('men_target', $mval)) {        // 메뉴 Table target 정보가 있으면
					                            $menuhtml .= ' target="' . element('men_target', $mval) . '"';
					                        }
					                        //$menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '"><i class="icon-'.element('men_order', $mval).'"></i>' . html_escape(element('men_name', $mval)) . '</a></li>';
					                        $menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '">' . html_escape(element('men_name', $mval)) . '</a></h3></li>';
					                    }
					                    
					                }
					            }
					        }
					        echo $menuhtml;
					        ?>					        
					        </ul>					        
					        
					</div>
					<div class="snb_link">
						<button class="btn link">공지사항</button>
						<button class="btn link"><a target="_blank" href="http://367.co.kr">원격지원</a></button>
					</div>
					<div class="snb_link">
						<button class="btn link"><a href="/biz/myinfo/charge#collapseOne">개발지원</a></button>
						<button class="btn link"><a href="/biz/collected_statics/assets_landing/pdf/bizm_api_v0.1.pdf" target="_blank">API 연동 가이드</a></button>
					</div>

<!-- 		            <div id="sidebar-content">
		            	<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
		            	<div id="sidebar-content" style="overflow: hidden; width: auto; height: 100%;">
		                <ul id="nav">
		                    <?php
		                    $menuhtml = '';
		                    if (element('menu', $layout)) {
		                        $menu = element('menu', $layout);
		                        if (element(0, $menu)) {
		                            foreach (element(0, $menu) as $mkey => $mval) {
										if(element('men_show', $mval)!="Y" || intval(element('men_allow_lv', $mval)) > intval($this->member->item('mem_level'))) { continue; }
										if($this->member->item('mem_level') < 100 && element('men_max_lv', $mval) <= $this->member->item('mem_level')) { continue; }
										
		                                if (element(element('men_id', $mval), $menu)) {
		                                    $mlink = element('men_link', $mval) ? element('men_link', $mval) : 'javascript:;';
		                                    $menuhtml .= '<li class="nav'.element('men_order', $mval).'">
		                                    <a href="' . $mlink . '" ' . element('men_custom', $mval);
		                                    if (element('men_target', $mval)) {
		                                        $menuhtml .= ' target="' . element('men_target', $mval) . '"';
		                                    }
		                                    $menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '"><i class="icon-'.element('men_order', $mval).'"></i>' . html_escape(element('men_name', $mval)) . '</a>
		                                    <ul class="sub-menu">';
		
		                                    foreach (element(element('men_id', $mval), $menu) as $skey => $sval) {
															if(element('men_show', $sval)!="Y" || intval(element('men_allow_lv', $sval)) > intval($this->member->item('mem_level'))) { continue; }
															if($this->member->item('mem_level') < 100 && element('men_max_lv', $sval) <= $this->member->item('mem_level')) { continue; }
		                                        $slink = element('men_link', $sval) ? element('men_link', $sval) : 'javascript:;';
		                                        $menuhtml .= '<li><a href="' . $slink . '" ' . element('men_custom', $sval);
		                                        if (element('men_target', $sval)) {
		                                            $menuhtml .= ' target="' . element('men_target', $sval) . '"';
		                                        }
		                                        if (element('men_order', $sval)=='12') {
		                                            $menuhtml .=' style="background-color: #fae100;color: #3b1e1e;';
		                                        }
		                                        $menuhtml .= ' title="' . html_escape(element('men_name', $sval)) . '"><i class="icon-angle-right"></i>' . html_escape(element('men_name', $sval)) . '</a></li>';
		                                    }
		                                    $menuhtml .= '</ul></li>';
		
		                                } else {
		                                    $mlink = element('men_link', $mval) ? element('men_link', $mval) : 'javascript:;';
		                                    $menuhtml .= '<li class="nav'.element('men_order', $mval).'"><a href="' . $mlink . '" ' . element('men_custom', $mval);
		                                    if (element('men_target', $mval)) {
		                                        $menuhtml .= ' target="' . element('men_target', $mval) . '"';
		                                    }
		                                    $menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '"><i class="icon-'.element('men_order', $mval).'"></i>' . html_escape(element('men_name', $mval)) . '</a></li>';
		                                }
		                            }
		                        }
		                    }
		                    echo $menuhtml;
		                    ?>
		                </ul>
						</div>
						</div>
					</div>  -->
	<!-- Sidebar END -->
	        </div><!-- mSnb end -->
		</aside>
		<article>
				<div id="mContent">
	
	                <!-- 본문 시작 -->
	                <?php if (isset($yield))echo $yield; ?>
	                <!-- 본문 끝 -->
	
	            </div><!-- mContent -->
		</article>
	</main><!-- dhnContent -->
</div><!-- dhnWrap -->

    <div class="modal select fade" id="myModalUserNMSGList" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true" style=" height: 600px;">
        <div class="modal-dialog modal-lg select-dialog" style="width: 800px;height: 540px;">
            <div class="modal-content" style="width: 800px;height: 540px;" >
                <br/>
                <h4 class="modal-title" align="center">공지사항</h4>
                <div class="modal-body select-body" style="height: 500px;">
                    <div >

                        <div class="content" id="modal_user_msg_list" style="overflow-y:scroll; height: 440px;" style="border:1px solid #aaa;">
									
                        </div>
                    </div>
                    <div align="center">
                        	<button type="button" class="btn btn-default dismiss" onclick="open_page_user_msg('1')">다음</button>
                        	<button type="button" class="btn btn-default dismiss" data-dismiss="modal">닫기</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal select fade" id="myModalUserAMSGList" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true" style=" height: 600px;">
        <div class="modal-dialog modal-lg select-dialog" style="width: 800px;height: 540px;">
            <div class="modal-content" style="width: 800px;height: 540px;" >
                <br/>
                <h4 class="modal-title" align="center">공지사항</h4>
                <div class="modal-body select-body" style="height: 500px;">
                    <div >

                        <div class="content" id="modal_user_msg_list" style="overflow-y:scroll; height: 440px;" style="border:1px solid #aaa;">
									
                        </div>
                    </div>
                    <div align="center">
                        	<button type="button" class="btn btn-default dismiss" onclick="open_all_prevpage()">이전</button>
                        	<button type="button" class="btn btn-default dismiss" onclick="open_all_nextpage()">다음</button>
                        	<button type="button" class="btn btn-default dismiss" data-dismiss="modal">닫기</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
		if(document.location.href.indexOf("board/notice") > -1)
			$("#nav li.nav80").addClass("current open");
		else if(document.location.href.indexOf("faq/faq") > -1 || document.location.href.indexOf("board/qna") > -1)
			$("#nav li.nav90").addClass("current open");
	 </script>


    <!-- Smartphone Touch Events -->
    <script type="text/javascript" src="/bizM/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.event.move.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.event.swipe.js"></script>

    <!-- General -->
    <script type="text/javascript" src="/bizM/js/breakpoints.js"></script>
    <script type="text/javascript" src="/bizM/js/respond.min.js"></script>
    <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
    <script type="text/javascript" src="/bizM/js/jquery.cookie.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.slimscroll.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.slimscroll.horizontal.min.js"></script>

    <!-- Forms -->
    <script type="text/javascript" src="/bizM/js/typeahead.min.js"></script>
    <!-- AutoComplete -->
    <script type="text/javascript" src="/bizM/js/jquery.autosize.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.inputlimiter.min.js"></script>
	 <link href="/css/plugins/uniform.css" rel="stylesheet" />
    <script type="text/javascript" src="/bizM/js/jquery.uniform.min.js"></script>
    <!-- Styled radio and checkboxes -->
    <script type="text/javascript" src="/bizM/js/jquery.tagsinput.min.js"></script>
	 <link href="/css/plugins/select2.min.css" rel="stylesheet" />
	 <script type="text/javascript" src="/js/plugins/select2.min.js"></script>
    <!-- Styled select boxes -->
    <script type="text/javascript" src="/bizM/js/fileinput.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.duallistbox.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.inputmask.min.js"></script>
    <script type="text/javascript" src="/bizM/js/wysihtml5.min.js"></script>
    <script type="text/javascript" src="/bizM/js/bootstrap-wysihtml5.min.js"></script>
    <!-- <script type="text/javascript" src="/bizM/js/bootstrap-multiselect.min.js"></script> -->
    <script type="text/javascript" src="/bizM/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="/js/multiselect.js"></script>
    <!-- Page specific plugins -->
    <!-- Charts -->
    <!--[if lt IE 9]>
		<script type="text/javascript" src="plugins/flot/excanvas.min.js"></script>
	<![endif]-->
    <script type="text/javascript" src="/bizM/js/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.tooltip.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.resize.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.orderBars.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.selection.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.flot.growraf.min.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.easy-pie-chart.min.js"></script>


    <script type="text/javascript" src="/bizM/js/moment.min.js"></script>
    <script type="text/javascript" src="/bizM/js/daterangepicker.js"></script>
    <script type="text/javascript" src="/bizM/js/jquery.blockUI.min.js"></script>


    <!-- Form Validation -->
    <script type="text/javascript" src="/bizM/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/bizM/js/additional-methods.min.js"></script>

    <!-- Noty -->
    <script type="text/javascript" src="/bizM/js/jquery.noty.js"></script>
    <script type="text/javascript" src="/bizM/js/top.js"></script>
    <script type="text/javascript" src="/bizM/js/default.js"></script>

    <!-- App -->
    <script type="text/javascript" src="/bizM/js/app.js"></script>
    <script type="text/javascript" src="/bizM/js/plugins.js"></script>
    <script type="text/javascript" src="/bizM/js/plugins.form-components.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            "use strict";

            App.init(); // Init layout and core plugins
            Plugins.init(); // Init all plugins
            FormComponents.init(); // Init all form-specific plugins

            $('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                // startView: 1,
                todayBtn: "linked",
                language: "kr",
                orientation: "top auto",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                todayHighlight: true
            });

            $("[data-toggle='tooltip']").tooltip();
<? if($this->session->userdata('isNotice') == 'Y' ) { ?>
            $.ajax({
                url: "/biz/main/get_stat",
                type: "GET",
                success: function (data) {
                    var stats_template = data['tmpl_info'];

                    if (stats_template[0].msg_cnt != null && stats_template[0].msg_cnt > 0) {
                    	open_msg_list();
                    }
                  
                }
            });
<? } ?>
        });

     	function open_msg_list() {

     		$("#myModalUserMSGList").modal({backdrop: 'static'});
     		$("#myModalUserMSGList").on('shown.bs.modal', function () {
     			$('.uniform').uniform();
     			$('select.select2').select2();
     		});

     		$('#myModalUserMSGList').unbind("keyup").keyup(function (e) {
     			var code = e.which;
     			if (code == 27) {
     				$(".btn-default.dismiss").click();
     			} else if (code == 13) {
     				include_customer()
     			}
     		});

     		$("#myModalUserMSGList .include_phns").click(function () {
     			include_customer();
     		});
     		
     		open_page_user_msg('1');		
     	}

     	function open_all_msg_list() {

     		$("#myModalUserAMSGList").modal({backdrop: 'static'});
     		$("#myModalUserAMSGList").on('shown.bs.modal', function () {
     			$('.uniform').uniform();
     			$('select.select2').select2();
     		});

     		$('#myModalUserAMSGList').unbind("keyup").keyup(function (e) {
     			var code = e.which;
     			if (code == 27) {
     				$(".btn-default.dismiss").click();
     			} else if (code == 13) {
     				include_customer()
     			}
     		});

     		$("#myModalUserAMSGList .include_phns").click(function () {
     			include_customer();
     		});
     		
     		open_all_user_msg('1', '', 'N');		
     	}
     	
        function open_page_user_msg(page, delids ) {
            alert('1');
    		var searchMsg = $('#searchMsg').val() || '';
    		var searchKind = $('#searchKind').val() || '';

    		$('#myModalUserNMSGList .content').html('').load(
    			"/biz/message/view_lists",
    			{
    				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
    				"search_msg": searchMsg,
    				"search_kind": searchKind,
    				"msgonly":"Y",
    				"del_ids[]":delids,
    				'page': page,
    				'is_modal': true 
    			},
    			function () {
    				$('.uniform').uniform();
    			}
    		);
			alert('2');
        }

        function open_all_user_msg(page, delids, isalam) {
            //alert('1');
    		var searchMsg = $('#searchMsg').val() || '';
    		var searchKind = $('#searchKind').val() || '';

    		$('#myModalUserAMSGList .content').html('').load(
    			"/biz/message/view_lists",
    			{
    				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
    				"search_msg": searchMsg,
    				"search_kind": searchKind,
    				"msgonly":"Y",
    				"del_ids[]":delids,
    				'page': page,
    				'is_modal': true,
    				'isalam':isalam
    			},
    			function () {
    				$('.uniform').uniform();
    			}
    		);

        }

        function open_all_nextpage() {
            page = Number($("#amcurrentpage").val()) + 1;
            //alert(page);
			open_all_user_msg(page, '', 'N');
        }        

        function open_all_prevpage() {
            page = Number($("#amcurrentpage").val()) - 1;
            //alert(page);
			open_all_user_msg(page, '', 'N');
        }        
    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            "use strict";

            $('.select2').select2();
        });

			if (!String.format) {
				 String.format = function(format) {
					  var args = Array.prototype.slice.call(arguments, 1);
					  return format.replace(/{(\d+)}/g, function(match, number) {
							return typeof args[number] != 'undefined'
								 ? args[number]
								 : match;
					  });
				 };
			}

			//발신번호 숫자만 입력 가능
			function SetNum(obj) {
				 val = obj.value;
				 re = /[^0-9]/gi;
				 obj.value = val.replace(re, "");
			}

	 </script>
    <!-- Demo JS -->
    <script type="text/javascript" src="/bizM/js/custom.js"></script>
    <script type="text/javascript" src="/bizM/js/form_validation.js"></script>

<style>
    #overlay {
        background: rgba(255, 255, 255, .8) 50% 50% no-repeat;
        color: #666666;
        position: fixed;
        height: 100%;
        width: 100%;
        z-index: 5000;
        top: 0;
        left: 0;
        float: left;
        text-align: center;
        padding-top: 25%;
        opacity: 1.0;
    }
</style>

<div id="overlay" style="display: none">
    <img src="/bizM/images/loader.gif" alt="Loading"><br>
    <p id="loading_ptag">Loading...</p>
</div>
<script>
  feather.replace()
</script>
</body>
<!-- 2019.01.25 kim min soo 추가 -->

</html>
