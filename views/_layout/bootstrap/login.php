<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($this->cbconfig->get_device_view_type() === 'desktop' && $this->cbconfig->get_device_type() === 'mobile') { ?>
<meta name="viewport" content="width=1000">
<?php } else { ?>
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=no">

<meta property="og:url" content="http://o2omsg.com" />
<meta property="og:title" content="오투오메시지" />
<meta property="og:description" content="알림톡, 친구톡, 오투오 메시지, 카카오 마트톡, 플친몰, (주)대형네트웍스, DHN" />
<meta property="og:site_name" content="오투오메시지" />
<meta property="og:type" content="website" />

<?php } ?>
<title>오투오메시지</title>
<?php if (element('meta_description', $layout)) { ?><meta name="description" content="<?php echo html_escape(element('meta_description', $layout)); ?>"><?php } ?>
<?php if (element('meta_keywords', $layout)) { ?><meta name="keywords" content="<?php echo html_escape(element('meta_keywords', $layout)); ?>"><?php } ?>
<?php if (element('meta_author', $layout)) { ?><meta name="author" content="<?php echo html_escape(element('meta_author', $layout)); ?>"><?php } ?>
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>
<?php if (element('canonical', $view)) { ?><link rel="canonical" href="<?php echo element('canonical', $view); ?>" /><?php } ?>

<meta name="description" content="알림톡, 친구톡, 오투오 메시지, 카카오 마트톡, 플친몰, (주)대형네트웍스, DHN">
<meta name="keywords" content="알림톡, 친구톡, 오투오 메시지, 카카오 마트톡, 플친몰, (주)대형네트웍스, DHN" />
<link rel="canonical" href="http://o2omsg.com">

<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
<link rel="manifest" href="/favicon/site.webmanifest">
<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/ui-lightness/jquery-ui.css" />
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
<!--=== JavaScript ===-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="/bizM/js/lodash.compat.min.js"></script>

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

<!-- 2020.01.29 디자인 수정/추가 by 김민수-->
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link href="css/login.css" rel="stylesheet" type="text/css">
<script src="https://unpkg.com/feather-icons"></script>

</head>
<!--body class="breakpoint-1200" <?php echo isset($view) ? element('body_script', $view) : ''; ?>-->
<body id="login">
	<!--div id="wrap">
		<header class="header navbar navbar-fixed-top" role="banner" id="header" style="background-color:#fae100;border-bottom:1px solid #8e886c">
			<div class="left_box" style="font-size:20px; margin-top: 12px;"><span style="color:#693609"><b>카카오마트톡</b></span> <span style="color:#8a4405">로그인</span>
			</div>
		</header>

		<div id="contenta" >
			<!-- 본문 시작 -->
			<?php if (isset($yield))echo $yield; ?>
		<!--/div><!--#footer>
	</div><!--#wrap-->

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
    <script type="text/javascript" src="/bizM/js/jquery.uniform.min.js"></script>
    <!-- Styled radio and checkboxes -->
    <script type="text/javascript" src="/bizM/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="/bizM/js/select2.min.js"></script>
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
    .vcenter {
   display: inline-block;
   vertical-align: middle;
   float: none;
}
</style>

<div id="overlay" style="display: none">
    <img src="/bizM/images/loader.gif" alt="Loading"><br>
    <p id="loading_ptag">Loading...</p>
</div>
	<script>
      feather.replace()
    </script>
</body></html>
