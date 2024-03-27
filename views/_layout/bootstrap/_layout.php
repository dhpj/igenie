<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo html_escape(element('page_title', $layout)); ?></title>
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
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<script type="text/javascript" src="/bizM/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="/bizM/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="/js/bss_js/bootstrap-select.js"></script>

<!-- CB -->
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

<body style="height: 887px;" class="breakpoint-1200" <?php echo isset($view) ? element('body_script', $view) : ''; ?>>
<div id="wrap">
	<header class="header navbar navbar-fixed-top" role="banner" id="header">
		<!--
		<div class="left_box">
			<h1><a href="/biz/main"><img src="/images/biz/logo.png" alt="logo"></a></h1><a href="/main/"></a>
		</div>
		-->
		<div class="right_box">
<?$my = $this->funn->getCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));?>
			<div style="float:left;margin-top:15px;margin-right:30px;">발송가능 : 알림톡 <? number_format($my['price_at']) ?>원(<?=number_format(floor($my['coin'] / $my['price_at']))?>건) , 
				친구톡 <? number_format($my['price_ft']) ?>원(<?=number_format(floor($my['coin'] / $my['price_ft']))?>건) ,
				친구톡이미지 <? number_format($my['price_ft_img']) ?>원(<?=number_format(floor($my['coin'] / $my['price_ft_img']))?>건)
<?if($_SERVER['REMOTE_ADDR']==$this->config->config['developer_ip'] || $this->config->config['use_multi_agent']) {
    if($this->member->item('mem_2nd_send')=="sms") { echo ', SMS '.number_format($my['price_sms']).'원('.number_format(floor($my['coin'] / $my['price_sms'])).'건)'; }
		else if($this->member->item('mem_2nd_send')=="lms") { echo ', LMS '.number_format($my['price_lms']).'원('.number_format(floor($my['coin'] / $my['price_lms'])).'건)'; }
		else if($this->member->item('mem_2nd_send')=="mms") { echo ', MMS '.number_format($my['price_mms']).'원('.number_format(floor($my['coin'] / $my['price_mms'])).'건)'; }
		else if($this->member->item('mem_2nd_send')=="phn") { echo ', 폰문자 '.number_format($my['price_phn']).'원('.number_format(floor($my['coin'] / $my['price_phn'])).'건)'; }
} else {?>
				, 폰문자(<?=number_format(floor($my['coin'] / $my['price_phn']))?>건)
<?}?>
			</div>
			<ul class="navbar-right">
				<li><a href="/deposit"><b style="letter-spacing:1px;"><? if($my['coin'] > 99999999) { echo '후불'; } else { echo '₩ '.number_format($my['coin'], 0).' 원'; } ?></b>&nbsp; <? if($my['coin'] < 99999999) { ?><button class="btn btn-sm">충전</button> <? }?> </a>(계좌 : )</li>
				<li class="dropdown user">
					<a href="/biz/myinfo/charge#" class="dropdown-toggle" data-toggle="dropdown"><img src="/bizM/images/ico_people.jpg" alt="">&nbsp;<span class="username"><?php echo html_escape($this->member->item('mem_nickname')); ?></span><i class="icon-caret-down small"></i></a>
					<ul class="dropdown-menu">
						<li><a href="/biz/myinfo/info">내 정보</a></li><!--<?php echo site_url('mypage'); ?>-->
						<li><a href="/login/logout/">로그아웃</a></li><!--<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>-->
					</ul>
				</li>
			</ul>
		</div>
	</header>
	 <!--//#header-->
    <div id="container" class="fixed-header">
        <div id="sidebar" class="sidebar-fixed">
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;"><div id="sidebar-content" style="overflow: hidden; width: auto; height: 100%;">
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
                                    $menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '"><i     class="icon-'.element('men_order', $mval).'"></i>' . html_escape(element('men_name', $mval)) . '</a>
                                    <ul class="sub-menu">';

                                    foreach (element(element('men_id', $mval), $menu) as $skey => $sval) {
													if(element('men_show', $sval)!="Y" || intval(element('men_allow_lv', $sval)) > intval($this->member->item('mem_level'))) { continue; }
													if($this->member->item('mem_level') < 100 && element('men_max_lv', $sval) <= $this->member->item('mem_level')) { continue; }
                                        $slink = element('men_link', $sval) ? element('men_link', $sval) : 'javascript:;';
                                        $menuhtml .= '<li><a href="' . $slink . '" ' . element('men_custom', $sval);
                                        if (element('men_target', $sval)) {
                                            $menuhtml .= ' target="' . element('men_target', $sval) . '"';
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
                                    $menuhtml .= ' title="' . html_escape(element('men_name', $mval)) . '"><i  class="icon-'.element('men_order', $mval).'"></i>' . html_escape(element('men_name', $mval)) . '</a></li>';
                                }
                            }
                        }
                    }
                    echo $menuhtml;
                    ?>
                </ul>
            <div class="fill-nav-space"></div></div><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 940px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
						<div class="sidebar_bottom" style="display:none">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="/biz/myinfo/charge#collapseOne"> <i class="icon-cog"></i>개발지원</a>
                        </h3>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse">
                        <div class="panel-body">
                             <ul>
                                <li><a href="/biz/collected_statics/assets_landing/pdf/bizm_api_v0.1.pdf" target="_blank"><i class="icon-angle-right"></i>API 연동 가이드</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--//#Sidebar -->
        <div id="content">
                <!-- 본문 시작 -->
                <?php if (isset($yield))echo $yield; ?>
                <!-- 본문 끝 -->
		<!--
								<div id="footer">
										<address>
												대형네트웍스 ㅣ 사업자등록번호 : 608-14-76994 ㅣ 대표이사 : 송종근 ㅣ 대표전화 : 1800-7540 ㅣ 팩스 : 0505-299-0001 | 주소 : 경상남도 창원시 의창구 원이대로240번길 31 KT 빌딩
										</address>
										<p>Copyright ⓒ www.bizalimtalk.kr (알림톡친구톡) All Rights Reserved</p>
								</div> --> <!--#footer-->
        </div><!--//.container-->
    </div><!--//#content-->
</div><!--#wrap-->

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
    <script type="text/javascript" src="/bizM/js/bootstrap-multiselect.min.js"></script>
    <script type="text/javascript" src="/bizM/js/bootstrap-switch.min.js"></script>

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


        });
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

</body></html>
