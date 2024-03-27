<!DOCTYPE html>
<html lang="ko">
	<head>
		<? include_once $_SERVER["DOCUMENT_ROOT"] ."/views/_layout/bootstrap/_inc_head.php"; ?>
		<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/import.css?v=<?=date("ymdHis")?>" />
		<? if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/views/". $menu1 ."/". $menu2 ."/bootstrap/css/style.css")){ ?>
			<link rel="stylesheet" type="text/css" href="/views/<?=$menu1?>/<?=$menu2?>/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
		<? } ?>
		<? if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/views/". $menu1 ."/bootstrap/css/style.css")){ ?>
			<link rel="stylesheet" type="text/css" href="/views/<?=$menu1?>/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
		<? } ?>
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" />
		<!-- 송도휘 -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" />
		<!-- <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" /> -->
		<!-- <link href="/js/slickgrid/examples/examples.css" rel="stylesheet" type="text/css"> -->
		<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
		<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
		<script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.js"></script>
		<script src="https://uicdn.toast.com/tui.dom/v3.0.0/tui-dom.js"></script>
		<script src="/js/tui-time-picker.min.js"></script>
		<script src="/js/tui-date-picker.min.js"></script>
		<script src="/js/tui-calendar.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
		<!-- 송도휘 -->
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
		<link href="/css/common.css?v=<?=date("ymdHis")?>" rel="stylesheet" type="text/css">
		<link href="/bizM/css/datepicker3.css" rel="stylesheet" type="text/css">

		<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>?v=<?=date("ymdHis")?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
		<!-- GENIE JS 추가 -->
		<script type="text/javascript" src="<?php echo base_url('/js/common.js'); ?>?v=<?=date("ymdHis")?>"></script>
		<!-- 아이콘폰트 추가 -->
		<script src="https://kit.fontawesome.com/12fdcf5d4d.js" crossorigin="anonymous"></script>
		<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

		<?if($this->member->item('mem_id') == '2' || $this->member->item('mem_id') == '3'){?>
			<script src="https://t1.kakaocdn.net/kakao_js_sdk/2.3.0/kakao.min.js" integrity="sha384-70k0rrouSYPWJt7q9rSTKpiTfX6USlMYjZUtr1Du+9o4cGvhPAWxngdtVZDdErlh" crossorigin="anonymous"></script>
			<script>Kakao.init('<?=config_item('kakao_conf')['dhn_js_key']?>');</script>

			<script>
			  // $(document).ready(function (){
			  //     setToken();
			  //     if (check_token()){
			  //         $('#kakao-login-btn').remove();
			  //         $('#kakao-logout-btn').css('display', '');
			  //     } else {
			  //         $('#kakao-logout-btn').remove();
			  //         $('#kakao-login-btn').css('display', '');
			  //     }
			  // });
			  // 아래는 데모를 위한 UI 코드입니다.
			  function setToken() {
			    var token = '<?=$token_data->access_token?>';

			    if(token != '') {
			      Kakao.Auth.setAccessToken('<?=$token_data->access_token?>');
			      Kakao.Auth.getStatusInfo().then(function(res) {
			        return;
			        if (res.status === 'connected') {
			          location.href = '<?=config_item('kakao_conf')['dhn_logout_redirect_uri']?>';
			        }
			      }).catch(function(err) {
			        deleteCookie();
			        location.href = 'https://kauth.kakao.com/oauth/logout?client_id=<?=config_item('kakao_conf')['dhn_rest_key']?>&logout_redirect_uri=<?=config_item('kakao_conf')['dhn_logout_redirect_uri']?>';
			      });
			    }
			  }

			  function getCookie(name) {
			    var parts = document.cookie.split(name + '=');
			    if (parts.length === 2) { return parts[1].split(';')[0]; }
			  }

			  function kakao_login() {
			    if (!check_token()){
			      location.href = 'https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=<?=config_item('kakao_conf')['dhn_rest_key']?>&redirect_uri=<?=config_item('kakao_conf')['dhn_login_redirect_uri']?>';
			    }
			  }

			  function kakao_logout(){
			    Kakao.Auth.logout().then(function(response) {
			      deleteCookie();
			      location.href = '<?=config_item('kakao_conf')['dhn_logout_redirect_uri']?>';
			    }).catch(function(error) {
			      console.log('Not logged in.');
			    });
			  }

			  function deleteCookie() {
			    document.cookie = 'authorize-access-token=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			  }

			  function check_token(){
			    if (Kakao.Auth.getAccessToken()){
			      var res = false;
			      $.ajax({
							url: "/kakao/get_token_info",
							type: "POST",
			        async: false,
							data: {
			          "<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
								token: Kakao.Auth.getAccessToken()
			        },
							success: function (json) {
			          if (json.code == 'login' && json.data.expires_in > 100){
			            res = true;
			          }
							}
						});
			      return res;
			    } else {
			      return false;
			    }
			  }
			</script>
		<?}?>

		<?php echo $this->managelayout->display_js(); ?>
	</head>
	<body <?php echo isset($view) ? element('body_script', $view) : ''; ?>>
		<div id="snackbar"></div><?//모달창 div?>
		<div id="body_top" class="genie_wrap">
			<div class="header">
				<button class="openbtn" onclick="openNav()">☰</button>
				<div class="hamburger-menu"><div class="bar"></div></div>
				<a href="<?php echo site_url()."home"; ?>">
					<div class="logo">
						<!--?php echo html_escape($this->cbconfig->item('site_title'));?-->
						<h1><img src="/dhn/images/logo_v1.png"></h1>
					</div>
				</a>
				<div class="center">
					<div class="t_slogan"><?=config_item('site_full_name')?> '<?=config_item('site_name')?>' &nbsp; X &nbsp; 카카오 공식딜러사 (주)대형네트웍스</div>
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
	        <?
	          $parent_id = $this->funn->getParent($this->member->item('mem_id'));

	          $smart_yn_flag = $this->member->item('mem_send_smart_yn');

	          $nh_flag = "N";

	          $order_flag = "N";

	          if($parent_id == "1492"){
              $nh_flag = "Y";
	          }

	          if($this->member->item('mem_id') == "1946"){
              $nh_flag = "N";
	          }

	          if($this->member->item('mem_id') == "1823"){
              $order_flag = "Y";
	          }

	          if($this->member->item('mem_id') != '3') {
              $login_stack = $this->session->userdata('login_stack');
              if($this->session->userdata('login_stack')) {
                $login_stack = $this->session->userdata('login_stack');
                if($login_stack[0] == '3') {
                	$nh_flag = "N";
                	// $order_flag = "Y";
              	}
              }
	          }
	        ?>
				</div>

				<div class="right">
	        <?if($this->member->item('mem_id') == '2' || $this->member->item('mem_id') == '3'){?>
	            <!-- <button id='kakao-login-btn' onclick='kakao_login()' style='display:none'>카카오 로그인(테스트)</button>
	            <button id='kakao-logout-btn' onclick='kakao_logout()' style='display:none'>카카오 로그아웃(테스트)</button> -->
	        <?}?>
					<? if($this->member->item('mem_level') >= 100){ //중간관리자 이상 권한만 보임 ?>
						<!-- <a class="btn_calendar" style="margin-right:10px;" href="<?php echo site_url('untact/calendar'); ?>"><span class="material-icons">calendar_today</span> 일정관리</a> -->
	        <? } ?>
	        <? //if(($parent_id=="3"&&$this->member->item('mem_level') == 1)||$this->member->item("mem_id")=="3"||$this->member->item("mem_id")=="1260"||$this->member->item("mem_id")=="962"||$parent_id=="1260"||$parent_id=="962"){ ?>
		        <!-- <a class="btn_voucher" href="/untact/voucher" style="margin-right:8px;"><span class="material-icons">fact_check</span> 비대면 바우처 신청</a> -->
		        <!-- <a class="btn_voucher" href="/untact/voucher?type=ss" style="margin-right:8px;"><span class="material-icons">fact_check</span> 스마트상점 신청</a> -->
	        <? //} ?>
            <?if ($this->member->item("mem_id") == "2"){?>
                <a class="btn_voc" href="/voc"><span class="material-icons">headset_mic</span> 고객의 소리</a>
            <?}?>
	        <a class="btn_voucher" href="/card/main"><span class="material-icons">fact_check</span> 카드결제 신청</a>
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
					<a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" title="로그아웃"><span class="material-icons">power_settings_new</span></a>
					<!--<ul class="top_menu">
						<?php if ($this->member->is_admin() === 'super') { ?>
							<li tooltip-text="관리자"><a href="<?php echo site_url(config_item('uri_segment_admin')); ?>"><span class="material-icons">settings</span></a></li>
						<?php } ?>

						<?php if ($this->cbconfig->item('open_currentvisitor')) { ?>
							<li tooltip-text="현재접속자"><a href="<?php echo site_url('currentvisitor'); ?>"><span class="material-icons">record_voice_over</span><span class="badge"><?php echo element('current_visitor_num', $layout); ?></span></a></li>
						<?php } ?>

						<? if ($this->member->is_member()) { ?>
							<? if ($this->cbconfig->item('use_notification')) { ?>
								<li class="notifications">
									<span class="material-icons">notification_important</span><?php echo number_format((int) element('notification_num', $layout)); ?>
									<div class="notifications-menu"></div>
									<script type="text/javascript">
										//<![CDATA[
											$(document).mouseup(function(e) {
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
							<? } ?>
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
									<script>
								 		function closeNav() {
									 		document.getElementById("mySidebar").style.display= "none";
									 		document.getElementById("main_nav").style.width= "100%";
									 		document.getElementById("menu_dashboard").style.display= "none";
								 		}

										function openNav() {
									 		document.getElementById("mySidebar").style.display= "block";
									 		document.getElementById("main_nav").style.width= "calc(100% - 240px)";
									 		document.getElementById("menu_dashboard").style.display= "block";
								 		}
									</script>
									<h2 class="nickname"><?php echo html_escape($this->member->item('mem_username')); ?></h2>
									<div class="deposit_info">
										<?
											// 카카오시스템 정상화시 if문 전체 주석처리.
											// if ((strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/talk_") == true||strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/friend_") == true)&&$this->member->item("mem_level") < 100){
											//     echo "<script>";
											//     echo "  alert('카카오 서비스가 아직 정상화되지 않았습니다. 문자서비스를 이용해주세요. 문자 서비스로 이동합니다.');";
											//     echo "  location.replace('http://igenie.co.kr/dhnbiz/sender/send/lms');";
											//     echo "</script>";
											// }
							        // 친구톡 발송 전환
							        // if (strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/friend_") == true&&$this->member->item("mem_level") < 100){
							        //     echo "<script>";
							        //     echo "  alert('카카오 친구톡 서비스가 아직 정상화되지 않았습니다. 문자서비스를 이용해주세요. 문자 서비스로 이동합니다.');";
							        //     echo "  location.replace('http://igenie.co.kr/dhnbiz/sender/send/lms');";
							        //     echo "</script>";
							        // }

							        // $in_ip = $_SERVER['REMOTE_ADDR'];
											// if($in_ip=="61.75.230.209"){
						            $my = $this->funn->getCoin_new($this->member->item('mem_id'), $this->member->item('mem_userid'));
						            $v_coin["coin"] = $my["vcoin"];
						            $b_coin["coin"] = $my["bcoin"];
						            // $my = $this->funn->getCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
						            // $v_coin = $this->funn->getVCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
						            // $b_coin = $this->funn->getBCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
							        // }else{
							        //     $my = $this->funn->getCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
							        //     $v_coin = $this->funn->getVCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
							        //     $b_coin = $this->funn->getBCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
							        // }


							        $pre_coin = $this->funn->getPreCoin($this->member->item('mem_id'));

							        //바우처 flag 가져오기 2022-06-24 윤재박
							        $kvd_flag = $this->funn->kvdFlag($this->member->item('mem_id'));
							        // 바우처 잔액 업데이트 2022-06-10 윤재박
							        if($v_coin['coin'] < 0&&$kvd_flag=='N'){
						            $this->funn->uptVCoin($this->member->item('mem_id'),$v_coin['coin']);
							        }
							        //$my["coin"] = $this->Biz_model->getTotalDeposit($row->mem_userid);
							        //echo "my['coin'] : ". $my['coin'] .", my['price_phn'] : ". $my['price_phn'] ."<br>";
											//log_message("ERROR","예치금 잔액 : ".$my['coin']."/".$my['price_phn']);
							        $memPayType = $this->member->item('mem_pay_type');               // 선후불제 타입(선불제: B, 후불제:A, 정액제: T)
							        $totalCoin = number_format($my['coin'], 0);           // 충전금액 or 사용금액(-값)
							        //$totalCoin = number_format($my, 0);
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

							        // 2022.11.29 변수 추가
							        $rcsNumber = '';              // rcs lms 발송건수
							        $rcsUnitPrice = '';          // rcs lms 단가
							        $rcsSmsNumber = '';          // rcs sms 발송 건수
							        $rcsSmsUnitPrice = '';      // rcs sms 단가
							        $rcsMmsNumber = '';          // rcs Mms 발송 건수
							        $rcsMmsUnitPrice = '';      // rcs Mms 단가

							        //echo "memPayType : ". $memPayType ."<br>";
											//log_message("ERROR", "mem_pay_type : ".$memPayType);
							        //log_message("ERROR", "mem_pay_type = B : ".($memPayType === 'B'));
							        //log_message("ERROR", "empty(paty_type) = B : ".empty($memPayType));
							        //$memPayType = 'A';
											if($memPayType == 'B' || empty($memPayType)) {
						            if($parent_id!='962'&&$this->member->item('mem_id')!='962'){
					                //log_message("ERROR", "mem_pay_type1 : B");
					                if($v_coin['coin'] > 0&&$this->member->item("mem_voucher_yn")=="Y"){
				                    // if($in_ip=="61.75.230.209"){
		                        if( $v_coin['coin'] > 0 && $my['price_v_at'] > 0 ) {      // 알림톡 건수 및 단가
		                          $arlimTackNumber = number_format(floor($v_coin['coin'] / $my['price_v_at']));
		                          $arlimTackUnitPrice = $my['price_v_at'].'원/건';
		                        } else {
		                          $arlimTackNumber = 0;
		                          $arlimTackUnitPrice = $my['price_v_at'].'원/건';
		                        }
		                        if( $v_coin['coin'] > 0 && $my['price_v_ft'] > 0) {       // 친구톡 건수 및 단가
		                          $frinedTackNumber = number_format(floor($v_coin['coin'] / $my['price_v_ft']));
		                          $frinedTackUnitPrice = $my['price_v_ft'].'원/건';
		                        } else {
		                          $frinedTackNumber = 0;
		                          $frinedTackUnitPrice = $my['price_v_ft'].'원/건';
		                        }
		                        if( $v_coin['coin'] > 0 && $my['price_v_ft_img'] > 0 ) {  // 친구톡 이미지 건수 및 단가
		                          $frinedTackImageNumber = number_format(floor($v_coin['coin'] / $my['price_v_ft_img']));
		                          $frinedTackImageUnitPrice = $my['price_v_ft_img'].'원/건';
		                        } else {
		                          $frinedTackImageNumber = 0;
		                          $frinedTackImageUnitPrice = $my['price_v_ft_img'].'원/건';
		                        }
		                        if($this->member->item('mem_2nd_send')=="sms") {
		                          if( $v_coin['coin']>0 && $my['price_sms']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_sms']));
		                            $messageUnitPrice = $my['price_sms'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_sms'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="lms") {
		                          if( $v_coin['coin']>0 && $my['price_lms']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_lms']));
		                            $messageUnitPrice = $my['price_lms'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_lms'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="mms") {
		                          if( $v_coin['coin']>0 && $my['price_mms']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_mms']));
		                            $messageUnitPrice = $my['price_mms'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_mms'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="phn") {
		                          if( $v_coin['coin']>0 && $my['price_phn']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_phn']));
		                            $messageUnitPrice = $my['price_phn'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_phn'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
		                          if( $v_coin['coin']>0 && $my['price_grs']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_grs']));
		                            $messageUnitPrice = $my['price_grs'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_grs'].'원/건';
		                          }
		                          // 2019.09.20 추가
		                          if( $v_coin['coin']>0 && $my['price_grs_sms']>0) {
		                            $messageSmsNumber = number_format(floor($v_coin['coin'] / $my['price_grs_sms']));
		                            $messageSmsUnitPrice = $my['price_grs_sms'].'원/건';
		                          } else {
		                            $messageSmsNumber = 0;
		                            $messageSmsUnitPrice = $my['price_grs_sms'].'원/건';
		                          }
		                          if( $v_coin['coin']>0 && $my['price_grs_mms']>0) {
		                            $messageMmsNumber = number_format(floor($v_coin['coin'] / $my['price_grs_mms']));
		                            $messageMmsUnitPrice = $my['price_grs_mms'].'원/건';
		                          } else {
		                            $messageMmsNumber = 0;
		                            $messageMmsUnitPrice = $my['price_grs_mms'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="NASELF") {
		                          if( $v_coin['coin']>0 && $my['price_nas']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_nas']));
		                            $messageUnitPrice = $my['price_nas'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_nas'].'원/건';
		                          }
		                          // 2019.09.20 추가
		                          if( $v_coin['coin']>0 && $my['price_nas_sms']>0) {
		                            $messageSmsNumber = number_format(floor($v_coin['coin'] / $my['price_nas_sms']));
		                            $messageSmsUnitPrice = $my['price_nas_sms'].'원/건';
		                          } else {
		                            $messageSmsNumber = 0;
		                            $messageSmsUnitPrice = $my['price_nas_sms'].'원/건';
		                          }
		                          if( $v_coin['coin']>0 && $my['price_nas_mms']>0) {
		                            $messageMmsNumber = number_format(floor($v_coin['coin'] / v_coinmy['price_nas_mms']));
		                            $messageMmsUnitPrice = $my['price_nas_mms'].'원/건';
		                          } else {
		                            $messageMmsNumber = 0;
		                            $messageMmsUnitPrice = $my['price_nas_mms'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="SMART") {
		                          if( $v_coin['coin']>0 && $my['price_v_smt']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_v_smt']));
		                            $messageUnitPrice = $my['price_v_smt'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_v_smt'].'원/건';
		                          }
		                          // 2019.09.20 추가
		                          if( $v_coin['coin']>0 && $my['price_v_smt']>0) {
		                            $messageSmsNumber = number_format(floor($v_coin['coin'] / $my['price_v_smt_sms']));
		                            $messageSmsUnitPrice = $my['price_v_smt_sms'].'원/건';
		                          } else {
		                            $messageSmsNumber = 0;
		                            $messageSmsUnitPrice = $my['price_v_smt_sms'].'원/건';
		                          }
		                          if( $v_coin['coin']>0 && $my['price_v_smt']>0) {
		                            $messageMmsNumber = number_format(floor($v_coin['coin'] / $my['price_v_smt_mms']));
		                            $messageMmsUnitPrice = $my['price_v_smt_mms'].'원/건';
		                          } else {
		                            $messageMmsNumber = 0;
		                            $messageMmsUnitPrice = $my['price_v_smt_mms'].'원/건';
		                          }
		                        } else if($this->member->item('mem_2nd_send')=="015") {
		                          if( $v_coin['coin']>0 && $my['price_015']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_015']));
		                            $messageUnitPrice = $my['price_015'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_015'].'원/건';
		                          }
		                        } else {
		                          if( $v_coin['coin']>0 && $my['price_phn']>0) {
		                            $messageNumber = number_format(floor($v_coin['coin'] / $my['price_phn']));
		                            $messageUnitPrice = $my['price_phn'].'원/건';
		                          } else {
		                            $messageNumber = 0;
		                            $messageUnitPrice = $my['price_phn'].'원/건';
		                          }
		                        }

		                        if($this->member->item("mem_rcs_yn")=="Y"){
		                          if( $v_coin['coin'] > 0 && $my['price_v_rcs_sms'] > 0 ) {      // rcs sms 건수 및 단가
		                            $rcsSmsNumber = number_format(floor($v_coin['coin'] / $my['price_v_rcs_sms']));
		                            $rcsSmsUnitPrice = $my['price_v_rcs_sms'].'원/건';
		                          }else {
		                            $rcsSmsNumber = 0;
		                            $rcsSmsUnitPrice = $my['price_v_rcs_sms'].'원/건';
		                          }

		                          if( $v_coin['coin'] > 0 && $my['price_v_rcs'] > 0 ) {      // rcs sms 건수 및 단가
		                            $rcsNumber = number_format(floor($v_coin['coin'] / $my['price_v_rcs']));
		                            $rcsUnitPrice = $my['price_v_rcs'].'원/건';
		                          }else {
		                            $rcsNumber = 0;
		                            $rcsUnitPrice = $my['price_v_rcs_sms'].'원/건';
		                          }
		                        }
				                    // }else{
				                    //     if( $v_coin['coin'] > 0 && $v_coin['price_at'] > 0 ) {      // 알림톡 건수 및 단가
				                    //         $arlimTackNumber = number_format(floor($v_coin['coin'] / $v_coin['price_at']));
				                    //         $arlimTackUnitPrice = $v_coin['price_at'].'원/건';
				                    //     } else {
				                    //         $arlimTackNumber = 0;
				                    //         $arlimTackUnitPrice = $v_coin['price_at'].'원/건';
				                    //     }
				                    //     if( $v_coin['coin'] > 0 && $v_coin['price_ft'] > 0) {       // 친구톡 건수 및 단가
				                    //         $frinedTackNumber = number_format(floor($v_coin['coin'] / $v_coin['price_ft']));
				                    //         $frinedTackUnitPrice = $v_coin['price_ft'].'원/건';
				                    //     } else {
				                    //         $frinedTackNumber = 0;
				                    //         $frinedTackUnitPrice = $v_coin['price_ft'].'원/건';
				                    //     }
				                    //     if( $v_coin['coin'] > 0 && $v_coin['price_ft_img'] > 0 ) {  // 친구톡 이미지 건수 및 단가
				                    //         $frinedTackImageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_ft_img']));
				                    //         $frinedTackImageUnitPrice = $v_coin['price_ft_img'].'원/건';
				                    //     } else {
				                    //         $frinedTackImageNumber = 0;
				                    //         $frinedTackImageUnitPrice = $v_coin['price_ft_img'].'원/건';
				                    //     }
				                    //     if($this->member->item('mem_2nd_send')=="sms") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_sms']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_sms']));
				                    //             $messageUnitPrice = $v_coin['price_sms'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_sms'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="lms") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_lms']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_lms']));
				                    //             $messageUnitPrice = $v_coin['price_lms'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_lms'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="mms") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_mms']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_mms']));
				                    //             $messageUnitPrice = $v_coin['price_mms'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_mms'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="phn") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_phn']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_phn']));
				                    //             $messageUnitPrice = $v_coin['price_phn'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_phn'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_grs']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_grs']));
				                    //             $messageUnitPrice = $v_coin['price_grs'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_grs'].'원/건';
				                    //         }
				                    //         // 2019.09.20 추가
				                    //         if( $v_coin['coin']>0 && $v_coin['price_grs_sms']>0) {
				                    //             $messageSmsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_grs_sms']));
				                    //             $messageSmsUnitPrice = $v_coin['price_grs_sms'].'원/건';
				                    //         } else {
				                    //             $messageSmsNumber = 0;
				                    //             $messageSmsUnitPrice = $v_coin['price_grs_sms'].'원/건';
				                    //         }
				                    //         if( $v_coin['coin']>0 && $v_coin['price_grs_mms']>0) {
				                    //             $messageMmsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_grs_mms']));
				                    //             $messageMmsUnitPrice = $v_coin['price_grs_mms'].'원/건';
				                    //         } else {
				                    //             $messageMmsNumber = 0;
				                    //             $messageMmsUnitPrice = $v_coin['price_grs_mms'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="NASELF") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_nas']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_nas']));
				                    //             $messageUnitPrice = $v_coin['price_nas'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_nas'].'원/건';
				                    //         }
				                    //         // 2019.09.20 추가
				                    //         if( $v_coin['coin']>0 && $v_coin['price_nas_sms']>0) {
				                    //             $messageSmsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_nas_sms']));
				                    //             $messageSmsUnitPrice = $v_coin['price_nas_sms'].'원/건';
				                    //         } else {
				                    //             $messageSmsNumber = 0;
				                    //             $messageSmsUnitPrice = $v_coin['price_nas_sms'].'원/건';
				                    //         }
				                    //         if( $v_coin['coin']>0 && $v_coin['price_nas_mms']>0) {
				                    //             $messageMmsNumber = number_format(floor($v_coin['coin'] / v_coinmy['price_nas_mms']));
				                    //             $messageMmsUnitPrice = $v_coin['price_nas_mms'].'원/건';
				                    //         } else {
				                    //             $messageMmsNumber = 0;
				                    //             $messageMmsUnitPrice = $v_coin['price_nas_mms'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="SMART") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_smt']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_smt']));
				                    //             $messageUnitPrice = $v_coin['price_smt'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_smt'].'원/건';
				                    //         }
				                    //         // 2019.09.20 추가
				                    //         if( $v_coin['coin']>0 && $v_coin['price_smt']>0) {
				                    //             $messageSmsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_smt_sms']));
				                    //             $messageSmsUnitPrice = $v_coin['price_smt_sms'].'원/건';
				                    //         } else {
				                    //             $messageSmsNumber = 0;
				                    //             $messageSmsUnitPrice = $v_coin['price_smt_sms'].'원/건';
				                    //         }
				                    //         if( $v_coin['coin']>0 && $v_coin['price_smt']>0) {
				                    //             $messageMmsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_smt_mms']));
				                    //             $messageMmsUnitPrice = $v_coin['price_smt_mms'].'원/건';
				                    //         } else {
				                    //             $messageMmsNumber = 0;
				                    //             $messageMmsUnitPrice = $v_coin['price_smt_mms'].'원/건';
				                    //         }
				                    //     } else if($this->member->item('mem_2nd_send')=="015") {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_015']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_015']));
				                    //             $messageUnitPrice = $v_coin['price_015'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_015'].'원/건';
				                    //         }
				                    //     } else {
				                    //         if( $v_coin['coin']>0 && $v_coin['price_phn']>0) {
				                    //             $messageNumber = number_format(floor($v_coin['coin'] / $v_coin['price_phn']));
				                    //             $messageUnitPrice = $v_coin['price_phn'].'원/건';
				                    //         } else {
				                    //             $messageNumber = 0;
				                    //             $messageUnitPrice = $v_coin['price_phn'].'원/건';
				                    //         }
				                    //     }
				                    //
				                    //     if($this->member->item("mem_rcs_yn")=="Y"){
				                    //         if( $v_coin['coin'] > 0 && $v_coin['price_rcs_sms'] > 0 ) {      // rcs sms 건수 및 단가
				                    //             $rcsSmsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_rcs_sms']));
				                    //             $rcsSmsUnitPrice = $v_coin['price_rcs_sms'].'원/건';
				                    //         }else {
				                    //             $rcsSmsNumber = 0;
				                    //             $rcsSmsUnitPrice = $v_coin['price_rcs_sms'].'원/건';
				                    //         }
				                    //
				                    //         if( $v_coin['coin'] > 0 && $v_coin['price_rcs'] > 0 ) {      // rcs sms 건수 및 단가
				                    //             $rcsNumber = number_format(floor($v_coin['coin'] / $v_coin['price_rcs']));
				                    //             $rcsUnitPrice = $v_coin['price_rcs'].'원/건';
				                    //         }else {
				                    //             $rcsNumber = 0;
				                    //             $rcsUnitPrice = $v_coin['price_rcs_sms'].'원/건';
				                    //         }
				                    //     }
				                    // }
					                }else{
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
				                    if($this->member->item("mem_rcs_yn")=="Y"){
			                        if( $my['coin'] > 0 && $my['price_rcs_sms'] > 0 ) {      // rcs sms 건수 및 단가
		                            $rcsSmsNumber = number_format(floor($my['coin'] / $my['price_rcs_sms']));
		                            $rcsSmsUnitPrice = $my['price_rcs_sms'].'원/건';
			                        }else {
		                            $rcsSmsNumber = 0;
		                            $rcsSmsUnitPrice = $my['price_rcs_sms'].'원/건';
			                        }

			                        if( $my['coin'] > 0 && $my['price_rcs'] > 0 ) {      // rcs sms 건수 및 단가
		                            $rcsNumber = number_format(floor($my['coin'] / $my['price_rcs']));
		                            $rcsUnitPrice = $my['price_rcs'].'원/건';
			                        }else {
		                            $rcsNumber = 0;
		                            $rcsUnitPrice = $my['price_rcs_sms'].'원/건';
			                        }
				                    }
					                }
						            }
							        } else if ($memPayType == 'A'){ //후불제
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
											<h3>나의 예치금<a href="" title="새로고침"><span class="material-icons">cached</span></a></h3>
											<p><?=$totalCoin ?><span>원</span></p>
                      <? if($pre_coin>0){ ?>
                          <p><span class="precharge">선충전</span> <span class="material-icons">remove</span><?=number_format($pre_coin)?><span>원</span></p>
                      <? } ?>
                      <?if($b_coin['coin'] > 0 || $b_coin['coin'] < 0){?>
                          <p class="c_bonus"><span class="precharge">보너스</span> <span class="material-icons"><?if($b_coin['coin'] > 0){?>add<?}?></span><?=number_format($b_coin['coin'])?><span>원</span></p>
                      <?}?>
                      <? if($v_coin["coin"]>0&&$this->member->item("mem_voucher_yn")=="Y"){ ?>
                          <p class="c_voucher"><span class="precharge">바우처</span> <span class="material-icons">add</span><?=number_format($v_coin["coin"])?><span>원</span></p>
                      <? } ?>
										<? } ?>
										<div class="fee_box" id="id_SentNumber" style="display:<?=($memPayType == 'A'||$this->member->item('mem_id')=='962'||$parent_id=='962'|| in_array($this->member->item('mem_id'), config_item('price_display'))) ? "none" : ""?>;">
											<ul>
												<li><a href="javascript:area_over('id_SentNumber', 'id_SentUnitPrice');" class="price_on">발송잔여량</a></li>
												<li class="mg_lr5">|</li>
												<li><a href="javascript:area_over('id_SentUnitPrice', 'id_SentNumber');">발송단가 <span>( VAT포함 )</span></a></li>
											</ul>
											<dl>
                        <? if($nh_flag =="N"){ ?>
													<dt>알림톡</dt>
													<dd><?= $arlimTackNumber ?>건</dd>
                        <? }?>
												<dt>친구톡</dt>
												<dd><?= $frinedTackNumber ?>건</dd>
												<dt>친구톡(이미지)</dt>
												<dd><?= $frinedTackImageNumber ?>건</dd>
												<dt>단문문자(SMS)</dt>
												<dd><?= $messageSmsNumber ?>건</dd>
												<dt>장문문자(LMS)</dt>
												<dd><?= $messageNumber ?>건</dd>
                        <? if($this->member->item("mem_rcs_yn") =="Y"){ ?>
                          <dt>RCS(SMS)</dt>
				    							<dd><?= $rcsSmsNumber ?>건</dd>
				    							<dt>RCS(LMS)</dt>
				    							<dd><?= $rcsNumber ?>건</dd>
                        <? }?>
											</dl>
										</div>
										<div class="fee_box" id="id_SentUnitPrice" style="display:<?=($memPayType == 'A'&&$parent_id!='962') ? "" : "none"?>;">
											<ul>
												<? if($memPayType != 'A'){ ?>
													<li><a href="javascript:area_over('id_SentNumber', 'id_SentUnitPrice');">발송잔여량</a></li>
													<li class="mg_lr5">|</li>
												<? } ?>
												<li><a href="javascript:area_over('id_SentUnitPrice', 'id_SentNumber');" class="price_on">발송단가 <span>( VAT포함 )</span></a></li>
											</ul>
											<dl>
                        <? if($nh_flag =="N"){ ?>
													<dt>알림톡</dt>
													<dd><?=$arlimTackUnitPrice?></dd>
                        <? } ?>
												<dt>친구톡</dt>
												<dd><?= $frinedTackUnitPrice ?></dd>
												<dt>친구톡(이미지)</dt>
												<dd><?= $frinedTackImageUnitPrice ?></dd>
												<dt>단문문자(SMS)</dt>
												<dd><?=$messageSmsUnitPrice?></dd>
												<dt>장문문자(LMS)</dt>
												<dd><?=$messageUnitPrice?></dd>
                      	<? if($this->member->item("mem_rcs_yn") =="Y"){ ?>
                          <dt>RCS(SMS)</dt>
				    							<dd><?=$rcsSmsUnitPrice?></dd>
				    							<dt>RCS(LMS)</dt>
				    							<dd><?=$rcsUnitPrice?></dd>
                        <? }?>
											</dl>
										</div>
									</div>
									<!--<div class="menu_dashboard" id="menu_dashboard" onclick="location.href='/home'" style="cursor: pointer"><span class="material-icons">assessment</span> 실시간 현황</div>-->
									<div class="menu_dashboard" id="menu_dashboard" onclick="location.href='/deposit/redbank'" style="cursor: pointer"><span class="material-icons">account_balance_wallet</span> 충전하기</div>
									<div class="pf_shop">
										<? if($this->member->item('mem_contract_type') == "P"){ //계약형태 (S.스텐다드, P.프리미엄) ?>
											<a href="/login/pfmall_login" target="_blank"><span class="material-icons">add_shopping_cart</span> 쇼핑몰 바로가기</a>
										<? }else{ ?>
											<a href="/mall/info"><span class="material-icons">add_shopping_cart</span> 쇼핑몰 둘러보기</a>
										<? } ?>
									</div>
									<div class="snb_menu">
										<ul id="accordion" class="accordion">
											<li<?=(strpos("/".$req_url, "biz/sender/send/") == true OR strpos("/".$req_url, "/biz/coupon") == true) ? " class='open'" : ""?>>
	                      <? if($nh_flag =="N"){ ?>
	                        <? //if($this->member->item('mem_level') >= 100){ //최고관리자 이상 권한만 보임 ?>
	                        <!-- <a href="/dhnbiz/sender/send/talk_img_adv_v4"><div class="link"><span class="material-icons">send</span>메시지발송</div></a> -->
	                        <? //}else{ ?>
	                        <a href="/dhnbiz/sender/send/talk_img_adv_v<?=($smart_yn_flag=='Y')? '2_2' : '4'?>"><div class="link"><span class="material-icons">send</span>메시지발송</div></a>
	                        <? //} ?>
	                      <? }else{ ?>
	                        <a href="/dhnbiz/sender/send/friend_v5"><div class="link"><span class="material-icons">send</span>메시지발송</div></a>
	                      <? } ?>
											</li>
											<li<?=(strpos("/".$req_url, "/dhnbiz/sender/history") == true) ? " class='open'" : ""?>>
												<a href="/dhnbiz/sender/history"><div class="link"><span class="material-icons">list_alt</span>발송내역</div></a>
											</li>
	                    <? if($this->member->item('mem_level') == 17){ //영업자 계정만 보임 ?>
	                      <li<?=(strpos("/".$req_url, "/biz/partner/lists") == true) ? " class='open'" : ""?>>
				    							<a href="/biz/partner/lists"><div class="link"><span class="material-icons">fact_check</span>파트너관리</div></a>
				    						</li>
	                    <? } ?>
											<? if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
											<li<?=(strpos("/".$req_url, "/dhnbiz/template/") == true OR strpos("/".$req_url, "/dhnbiz/sendprofile/") == true OR strpos("/".$req_url, "/dhnbiz/nosend") OR strpos("/".$req_url, "/dhnbiz/brand") == true OR strpos("/".$req_url, "/biz/sendphnmng") == true OR strpos("/".$req_url, "/dhnbiz/sender/image") == true OR strpos("/".$req_url, "/biz/partner/") == true OR strpos("/".$req_url, "/biz/salesman") == true) ? " class='open'" : ""?>>
												<div class="link"><span class="material-icons">fact_check</span>발송관리<i class="fa fa-chevron-down"></i></div>
												<ul class="submenu">
													<li<?=(strpos("/".$req_url, "/dhnbiz/sendprofile/lists") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/sendprofile/lists">발신관리</a></li>
													<!--<li<?=(strpos("/".$req_url, "/dhnbiz/template/lists") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/template/lists">템플릿관리</a></li>
													<li<?=(strpos("/".$req_url, "/dhnbiz/sender/image_w_list") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/sender/image_w_list">친구톡 이미지관리</a></li>-->
													<li<?=(strpos("/".$req_url, "/biz/partner/lists") == true) ? " class='lm_on'" : ""?>><a href="/biz/partner/lists">파트너관리</a></li>
	                        <? if($this->member->item('mem_level') >= 150 or $this->member->item('mem_id') == "962"){ //최고관리자 권한만 보임 ?>
	                        	<li<?=(strpos("/".$req_url, "/biz/salesman") == true) ? " class='lm_on'" : ""?>><a href="/biz/salesman">영업자관리</a></li>
	                        <? } ?>
													<? if($this->member->item('mem_level') >= 150){ //최고관리자 권한만 보임 ?>
														<li<?=(strpos("/".$req_url, "/dhnbiz/template/public_lists") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/template/public_lists">템플릿관리</a></li>
		                        <li<?=(strpos("/".$req_url, "/dhnbiz/brand") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/brand">RCS관리</a></li>
													<? } //최고관리자 권한만 보임 ?>
				                  <? if($this->member->item('mem_id') != "3"){ //dhn 안보임 ?>
				                  	<li<?=(strpos("/".$req_url, "/dhnbiz/nosend") == true) ? " class='lm_on'" : ""?>><a href="/dhnbiz/nosend">미발송업체</a></li>
	                        <? } ?>
												</ul>
											</li>
										<? } //if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
	                	<? if($nh_flag =="N"){
	                  	$psd_user='';
		                  if($this->member->item('mem_id')=='2'||$this->member->item('mem_id')=='1789'){
	                      $psd_user='_v2';
		                  }
	                  ?>
	                  <? if($this->member->item('mem_level')>=100||$parent_id == "3"&&$this->member->item('mem_level')==1||$parent_id == "962"||$this->member->item('mem_id') == "962"||$parent_id == "1260"||$this->member->item('mem_id') == "1260"||$parent_id == "1294"||$this->member->item('mem_id') == "1294"||$parent_id == "911"||$this->member->item('mem_id') == "911"||$parent_id == "1308"||$this->member->item('mem_id') == "1308"||$parent_id == "2312"||$this->member->item('mem_id') == "2312"||$this->member->item('mem_id') == "1331"||$parent_id == "2574"||$this->member->item('mem_id') == "2574"){ ?>
	                    <li<?=(strpos("/".$req_url, "/spop/screen_v2/custom") == true) ? " class='open'" : ""?>>
	    							<a href="/spop/screen_v2/custom"><div class="link"><span class="material-icons">dataset_linked</span>커스텀메뉴</div></a>
	    						</li>
	              <? } ?>
								<li<?=((strpos("/".$req_url, "/spop/screen".$psd_user) == true and strpos("/".$req_url, "/spop/screen_v2/custom") == false) or strpos("/".$req_url, "/mall/compare") == true) ? " class='open'" : ""?>>
									<div class="link"><span class="material-icons">collections</span>스마트전단<i class="fa fa-chevron-down"></i></div>
									<ul class="submenu">
										<li<?=(strpos("/".$req_url, "/spop/screen".$psd_user) == true) ? " class='lm_on'" : ""?>><a href="/spop/screen<?=$psd_user?>">스마트전단 목록</a></li>
										<? if($this->member->item('mem_level') >= 100){?>
										<li<?=(strpos("/".$req_url, "/mall/compare") == true) ? " class='lm_on'" : ""?>><a href="/mall/compare">스마트전단 관리</a></li>
										<? } ?>
										<li<?=(strpos("/".$req_url, "/mall/compare") == true) ? " class='lm_on'" : ""?>><a href="/mall/compare/sample">스마트전단 샘플</a></li>
										<li<?=(strpos("/".$req_url, "/spop/screen".$psd_user."/write") == true) ? " class='lm_on'" : ""?>><a href="/spop/screen<?=$psd_user?>/write">스마트전단 만들기</a></li>
										<li<?=(strpos("/".$req_url, "/spop/screen".$psd_user."/write") == true) ? " class='lm_on'" : ""?>><a href="/spop/screen<?=$psd_user?>/images">이미지보관함 </a></li>
									</ul>
								</li>
	            	<?if($this->member->item('mem_mpop_yn') == 'Y'){?>
	                <li<?=(strpos("/".$req_url, "/mpop") == true) ? " class='open'" : ""?>>
	    							<div class="link"><span class="material-icons">collections</span>모바일전단<i class="fa fa-chevron-down"></i></div>
	    							<ul class="submenu">
	    								<li<?="/".$req_url == "/mpop/offline" ? " class='lm_on'" : ""?>><a href="/mpop/offline">모바일전단 목록</a></li>
	                    <li<?=(strpos("/".$req_url, "/mpop/offline/write") == true) ? " class='lm_on'" : ""?>><a href="/mpop/offline/write">모바일전단 만들기</a></li>
	    							</ul>
	    						</li>
	              <?}?>
								<li<?=(strpos("/".$req_url, "/spop/coupon") == true) ? " class='open'" : ""?>>
									<div class="link"><span class="material-icons">collections_bookmark</span>스마트쿠폰<i class="fa fa-chevron-down"></i></div>
									<ul class="submenu">
										<li<?=(strpos("/".$req_url, "/spop/coupon") == true) ? " class='lm_on'" : ""?>><a href="/spop/coupon">스마트쿠폰 목록</a></li>
										<li<?=(strpos("/".$req_url, "/spop/coupon/write") == true) ? " class='lm_on'" : ""?>><a href="/spop/coupon/write">스마트쿠폰 만들기</a></li>
									</ul>
								</li>
								<li<?=(strpos("/".$req_url, "/spop/printer") == true) ? " class='open'" : ""?>>
									<a href="/spop/printer"><div class="link"><span class="material-icons">wallpaper</span>스마트POP</div></a>
								</li>
	            <? } ?>
	            <? if($nh_flag =="N"||$order_flag=="Y"){ ?>
								<? if($this->member->item('mem_stmall_yn') == "Y"){ //스마트전단 주문하기 사용의 경우 ?>
									<li<?=(strpos("/".$req_url, "/mall/order") == true) ? " class='open'" : ""?>>
										<div class="link"><span class="material-icons">shop</span>주문관리<i class="fa fa-chevron-down"></i>
		                  <!-- <span id="" class="order_count">28</span> -->
										</div>
										<ul class="submenu">
											<li<?=(strpos("/".$req_url, "/mall/order/main") == true) ? " class='lm_on'" : ""?>><a href="/mall/order/main">주문내역</a></li>
											<li<?=(strpos("/".$req_url, "/mall/order/map") == true) ? " class='lm_on'" : ""?>><a href="/mall/order/map">배달분포</a></li>
	                    <?if($this->member->item('mem_shop_pay_inicis_flag') == "Y"){?>
	                    	<li<?=(strpos("/".$req_url, "/card/calendar") == true) ? " class='lm_on'" : ""?>><a href="/card/calendar">정산</a></li>
	                    <?}?>
										</ul>
									</li>
								<? } ?>
	            <? } ?>
							<? if ($memPayType != 'A'){ //후불제가 아닌 경우 ?>
								<li<?=(strpos("/".$_SERVER['HTTP_HOST'].$req_url, $_SERVER['HTTP_HOST']."/deposit") == true) ? " class='open'" : ""?>>
									<a href="/deposit"><div class="link"><span class="material-icons">battery_charging_full</span>충전하기</div></a>
								</li>
							<? } //if ($memPayType != 'A'){ //후불제가 아닌 경우 ?>
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
							<li<?=(strpos("/".$req_url, "/smart/map") == true) ? " class='open'" : ""?>>
								<a href="/smart/map"><div class="link"><span class="material-icons">people</span>고객분포</div></a>
							</li>
							<li<?=(strpos("/".$req_url, "/biz/myinfo/") == true) ? " class='open'" : ""?>>
								<a href="/biz/myinfo/info"><div class="link"><span class="material-icons">storefront</span>매장정보</div></a>
							</li>
	            <? if($this->member->item("mem_id")!="3"){ ?>
								<li<?=(strpos("/".$req_url, "/biz/stats") == true OR strpos("/".$req_url, "/biz/statistics/") == true) ? " class='open'" : ""?>>
									<a href="/biz/stats"><div class="link"><span class="material-icons">insights</span>발송통계</div></a>
								</li>
	            <? } ?>
							<!--<li<?=(strpos("/".$req_url, "mypage/point") == true) ? " class='open'" : ""?>>
								<a href="/mypage/point"><div class="link"><span class="material-icons">star_border</span>포인트설정</div></a>
							</li>-->
	            <? if($nh_flag =="N"){ ?>
								<li<?=(strpos("/".$req_url, "/board/") == true || strpos("/".$req_url, "/faq/") == true || strpos("/".$req_url, "/post/") == true || strpos("/".$req_url, "/write/") == true) ? " class='open'" : ""?>>
									<div class="link"><span class="material-icons">headset_mic</span>고객센터<i class="fa fa-chevron-down"></i></div>
									<ul class="submenu">
										<li<?=(strpos("/".$req_url, "/board/notice") == true) ? " class='lm_on'" : ""?>><a href="/board/notice">공지사항</a></li>
										<li<?=(strpos("/".$req_url, "/faq/faq") == true) ? " class='lm_on'" : ""?>><a href="/faq/faq">FAQ</a></li>
										<!--<li<?=(strpos("/".$req_url, "/board/qna") == true) ? " class='lm_on'" : ""?>><a href="/board/qna">1:1문의</a></li>-->
									</ul>
								</li>
								<!--<li<?=(strpos("/".$req_url, "/manual") == true) ? " class='open'" : ""?>>
									<a href="/manual"><div class="link"><span class="material-icons">dvr</span>메뉴얼</div></a>
								</li>-->
								<li<?=(strpos("/".$req_url, "/manual") == true OR strpos("/".$req_url, "/manual/send") == true OR strpos("/".$req_url, "/manual/leaflet") == true OR strpos("/".$req_url, "/manual/pop") == true) ? " class='open'" : ""?>>
									<div class="link"><span class="material-icons">dvr</span>메뉴얼<i class="fa fa-chevron-down"></i></div>
									<ul class="submenu">
										<li<?=(strpos("/".$req_url, "/manual/send") == true) ? " class='lm_on'" : ""?>><a href="/manual/send">메시지발송</a></li>
										<li<?=(strpos("/".$req_url, "/manual/leaflet") == true) ? " class='lm_on'" : ""?>><a href="/manual/leaflet">스마트전단</a></li>
										<!--<li<?=(strpos("/".$req_url, "/manual/pop") == true) ? " class='lm_on'" : ""?>><a href="/manual/pop">스마트POP</a></li>-->
									</ul>
								</li>
	            <? } ?>
							<? if($this->member->item('mem_level') >= 100 || $this->member->item('mem_id') == '962'){ //최고관리자만 ?>
		            <? if($this->member->item('mem_level') >= 100){ //최고admin관리자만 ?>
									<li<?=(strpos("/".$req_url, "/mng/design/") == true OR strpos("/".$req_url, "/biz/refund/") == true OR strpos("/".$req_url, "/card") == true OR strpos("/".$req_url, "/biz/manager/") == true) ? " class='open'" : ""?>>
		            <?}?>
		              <? if($this->member->item('mem_level') >= 100){ //최고admin관리자만 ?>
		  							<div class="link"><span class="material-icons">photo_filter</span>관리자기능<i class="fa fa-chevron-down"></i></div>
		  							<ul class="submenu">
		                  <li<?=(strpos("/".$req_url, "/mng/design/templet") == true) ? " class='lm_on'" : ""?>><a href="/mng/design/templet">디자인관리</a></li>
			                <? if($this->member->item('mem_level') >= 150){ //최고admin관리자만 ?>
			  								<li<?=(strpos("/".$req_url, "/biz/manager/pendingbank/index") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/pendingbank/index">충전관리</a></li>
			  								<li<?=(strpos("/".$req_url, "/biz/manager/settlement") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/settlement">정산관리</a></li>
			                  <li<?=(strpos("/".$req_url, "/card/cardlist") == true) ? " class='lm_on'" : ""?>><a href='/card/cardlist'>카드결제관리</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/manager/report/sale_report") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/report/sale_report">매출현황</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/partner/login_log_lists") == true) ? " class='lm_on'" : ""?>><a href="/biz/partner/login_log_lists">로그인현황</a></li>
			                  <li<?=(strpos("/".$req_url, "/untact/lists") == true) ? " class='lm_on'" : ""?>><a href="/untact/lists">지원사업 신청 목록</a></li>
			                  <li<?=(strpos("/".$req_url, "/card/lists") == true) ? " class='lm_on'" : ""?>><a href="/card/lists">카드 신청 목록</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/manager/dblist") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/dblist">DB열람</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/manager/statistics/index") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/statistics/index">통계(작업중)</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/manager/happycall") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/happycall">해피콜&불만사항</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/manager/announce") == true) ? " class='lm_on'" : ""?>><a href="/biz/manager/announce">공지관리</a></li>
			                  <li<?=(strpos("/".$req_url, "/biz/qrcom/lists") == true) ? " class='lm_on'" : ""?>><a href="/qrcom/lists">고객정보</a></li>
			                <? } ?>
		  							</ul>
		  						</li>
	        			<?}?>
							<? } ?>
	            <?if($this->member->item('mem_level') < 150 && $this->member->item('mem_level') >= 10){?>
	              <li<?=(strpos("/".$req_url, "/card/") == true) ? " class='open'" : ""?>>
	  							<a href="/card/cardlist"><div class="link"><span class="material-icons">credit_card</span>카드결제관리</div></a>
	  						</li>
	            <?}?>
	            <?if($this->member->item('mem_id') == '2' || $this->member->item('mem_id') == '3'){ //최고admin관리자만 ?>
	              <!-- <li<?=(strpos("/".$req_url, "/test/") == true) ? " class='open'" : ""?>>
	                <a href="/test"><div class="link"><span class="material-icons">credit_card</span>개인화 메세지 테스트</div></a>
	              </li>
	              <li<?=strpos("/".$req_url, "/kakao") ? " class='open'" : ""?>>
	        				<div class="link"><span class="material-icons">collections</span>개인화메시지<i class="fa fa-chevron-down"></i></div>
	        				<ul class="submenu">
	                  <li<?=(strpos("/".$req_url, "/kakao/campaign_lists") == true) ? " class='lm_on'" : ""?>><a href="/kakao/campaign_lists/c">캠페인 목록</a></li>
	                  <li<?=(strpos("/".$req_url, "/kakao/campaign_lists") == true) ? " class='lm_on'" : ""?>><a href="/kakao/campaign_lists/a">광고그룹 목록</a></li>
	                  <li<?=(strpos("/".$req_url, "/kakao/campaign_lists") == true) ? " class='lm_on'" : ""?>><a href="/kakao/campaign_lists/cr">소재 목록</a></li>
	  								<li<?=(strpos("/".$req_url, "/kakao/campaign_write") == true) ? " class='lm_on'" : ""?>><a href="/kakao/campaign_write">캠페인&광고그룹 만들기</a></li>
	  								<li<?=(strpos("/".$req_url, "/kakao/creative_write") == true) ? " class='lm_on'" : ""?>><a href="/kakao/creative_write">소재 만들기</a></li>
	  							</ul>
	  						</li> -->
	            <?}?>
						</ul>
					</div><!-- snb_menu END -->
				</div><!-- snb END -->
				<div class="contents_wrap" id="main_nav">
					<!-- 본문 시작 -->
					<?php if (isset($yield))echo $yield; ?>
					<!-- 본문 끝 -->
				</div><!-- contents_wrap END -->
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
						<!--<li onclick="location.href='/dhnbiz/sender/send/talk_adv'" class=""><span class="material-icons">send</span>메시지발송</li>-->
	          <? if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
							<li onclick="location.href='/biz/sendphnmng'" class=""><span class="material-icons">fact_check</span>발신관리</li>
	          <? } //if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
						<li onclick="location.href='/dhnbiz/sender/history'" class=""><span class="material-icons">ballot</span>발송내역</li>
						<!--<li onclick="location.href='/deposit'" class=""><span class="material-icons">battery_charging_full</span>충전하기</li>-->
						<!--<div class="m_line"></div>-->
						<li onclick="location.href='/biz/customer/lists'" class=""><span class="material-icons">account_box</span>고객관리</li>
						<li onclick="location.href='/biz/myinfo/info'" class=""><span class="material-icons">store</span>매장정보</li>
						<li onclick="location.href='/spop/screen/images'" class=""><span class="material-icons">perm_media</span>이미지보관함 </li>
						<li onclick="location.href='/board/notice'" class=""><span class="material-icons">headset_mic</span>고객센터</li>
						<!--<li onclick="location.href='/manual/send'" class=""><span class="material-icons">dvr</span>메뉴얼</li>-->
						<? if($this->member->item('mem_stmall_yn') == "Y"){ //스마트전단 주문하기 사용의 경우 ?>
							<li onclick="location.href='/mall/order/map'" class=""><span class="material-icons">local_shipping</span>배달분포</li>
							<li onclick="location.href='/mall/order/mapadd'" class=""><span class="material-icons">fmd_good</span>배달분포 주소추가</li>
						<? } //if($this->member->item('mem_stmall_yn') == "Y"){ //스마트전단 주문하기 사용의 경우 ?>
						<? if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
							<li onclick="location.href='/biz/partner/lists'" class=""><span class="material-icons">source</span>파트너관리</li>
						<? } //if($this->member->item('mem_level') >= 50){ //중간관리자 이상 권한만 보임 ?>
						<? if($this->member->item('mem_level') >= 100){ //최고관리자 이상 권한만 보임 ?>
							<li onclick="location.href='/biz/manager/pendingbank/index'" class=""><span class="material-icons">payments</span>충전관리</li>
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

    <!-- 사용자 팝업 용 -->
    <div class="dh_modal_pop" id="myModalUserNMSGList" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
      <div class="modal-content">
        <span class="dh_close" data-dismiss="modal">×</span>
        <p class="modal-tit">충전알림</p>
        <div class="content" id="modal_user_msg_list"></div>
      </div>
    </div>

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
				<p>본사 : 경상남도 창원시 의창구 차룡로 48번길 54(팔용동) 기업연구관 302/303호</p>
				<p>사업자등록번호 : 364-88-00974 / 통신판매업 : 신고번호 2018-창원의창-0272호 / 특수한 유형의 부가통신사업자 등록번호 : 제 3-02-19-0001호 / 대표전화 : <strong>1522-7985</strong> / 팩스 : 0505-299-0001</p>
			</div>
			<div class="footer_copy">
				copyright © 2020 (주)대형네트웍스. ALL RIGHTS RESERVED.
			</div>
			<span class="guide_link"><a href="/uploads/smartguide_v6.pdf" target="_blank"><i class="xi-arrow-bottom"></i> 지니가이드</a></span>
      <span class="helpu_link"><i class="xi-globus"></i> <a href="http://367.co.kr" target="_blank" title="AI 스마트 매장관리시스템 지니 원격지원 서비스">원격지원</a></span>
      <span class="terms_link"><a href="/user_terms.php" target="_blank">개인정보처리방침</a></span>
		</footer>
		<!-- footer end -->

		<script type="text/javascript">
			$(document).on('click', '.viewpcversion', function(){
				Cookies.set('device_view_type', 'desktop', { expires: 1 });
			});
			$(document).on('click', '.viewmobileversion', function(){
				Cookies.set('device_view_type', 'mobile', { expires: 1 });
			});

			$(document).ready(function() {
			// 팝업 대상 있는지 확인 하고 한번 발생한 팝업은 다시 발생 하지 않기 위해...
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

			function open_msg_list(a) {
				$("#myModalUserNMSGList").modal({backdrop: 'static'});
				$("#myModalUserNMSGList").on('shown.bs.modal', function() {
					//$('.uniform').uniform();
					//$('select.select2').select2();
				});

				$('#myModalUserNMSGList').unbind("keyup").keyup(function(e) {
					var code = e.which;
					if (code == 27) {
						$(".btn-default.dismiss").click();
					} else if (code == 13) {
						include_customer()
					}
				});

				$("#myModalUserNMSGList .include_phns").click(function() {
					include_customer();
				});

				open_page_user_msg('1');
			}

			function open_page_user_msg(page) {
				var searchMsg = $('#searchMsg').val() || '';
				var searchKind = $('#searchKind').val() || '';

				$('#myModalUserNMSGList .content').html('').load(
					"/biz/message/view_lists",
					{
						<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
						"search_msg": searchMsg,
						"search_kind": searchKind,
						"msgonly": "Y",
						'page': page,
						'is_modal': true,
						'isalam': 'Y'
					},
					function () {
						//$('.uniform').uniform();
					}
				);
			}
		</script>
		<!-- 팝업레이어 체크 [S] -->
		<?php echo element('popup', $layout); ?>
		<!-- 팝업레이어 체크 [E] -->
		<?php echo $this->cbconfig->item('footer_script'); ?>
		<!--
			Layout Directory : <?php echo element('layout_skin_path', $layout); ?>,
			Layout URL : <?php echo element('layout_skin_url', $layout); ?>,
			Skin Directory : <?php echo element('view_skin_path', $layout); ?>,
			Skin URL : <?php echo element('view_skin_url', $layout); ?>,
		-->
	</body>
</html>
