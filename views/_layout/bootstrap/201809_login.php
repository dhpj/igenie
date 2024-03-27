<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php if ($this->cbconfig->get_device_view_type() === 'desktop' && $this->cbconfig->get_device_type() === 'mobile') { ?>
<meta name="viewport" content="width=1000">
<?php } else { ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php } ?>
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
 
</head>


<body style="background-image:url('/images/20181008_bg_001.png'); background-size:100% 100%;overflow:hidden;"  >
	<div class="container"  >
		<div class="row" style="margin-top:3%">
			<div class="col-md-6 col-sm-6 col-xs-6">
			</div>
			<div class="col-md-2 col-sm-2  col-xs-2 text-right"  >
				<a target="_blank" href="http://pf.kakao.com/_xiQSnj"  style="background-color:#eeeeee; width:130px;font-size:15px" ><img src="/images/kakao_dhn_btn.jpg"></a>
			</div>
			<div class="col-md-2 col-sm-2  col-xs-2 text-right"  >
				<a type="button" target="_blank" href="https://center-pf.kakao.com/login" class="btn btn-primary btn-lg" style="background-color:#eeeeee; width:130px;font-size:15px" ><B>카카오친구 센터</B></a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-2 text-left">
				<a type="button" target="_blank" href="http://367.co.kr/" class="btn btn-primary btn-lg" style="background-color:#d48a3e; width:100px;font-size:15px; color:white;" ><B>원격지원</B></a>
			</div>
			
		</div>
		<div class="row">
		</div>
	</div>

	<div id="wrap" style="height: 90%">
		<div style="position: absolute; top:65%; left:50%">
			<a href="/login/proc" type="button" class="btn btn-primary btn-lg" style="background-color:#ffee42; width:100px;font-size:15px" ><B>로그인</B></a>
		</div> 
		<div style="position: absolute; top:50px; left:80%; width:300px">
		</div> 
								<div id="footer" style="left:0px">
										<address>
												대형네트웍스 ㅣ 사업자등록번호 : 364-88-00974 ㅣ 통신판매업 : 신고번호 2018-창원의창-0272호 ㅣ 대표이사 : 송종근 ㅣ 대표전화 : 1522-7985 ㅣ 팩스 : 0505-299-0001 ㅣ 주소 : 경상남도 창원시 의창구 차룡로 48번길 54 (팔용동) 기업연구관 302 ㅣ <a target="_blank" href="/user_terms.php">개인정보처리방침</a>
										</address>
										<p>Copyright ⓒ www.bizalimtalk.kr (알림톡친구톡) All Rights Reserved</p>
								</div>  <!--#footer-->		
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
