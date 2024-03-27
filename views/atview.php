<!DOCTYPE html>
<html lang="ko">
<head>
<? include_once $_SERVER["DOCUMENT_ROOT"] ."/views/_layout/bootstrap/_inc_head.php"; ?>
<link rel="stylesheet" type="text/css" href="http://igenie.co.kr/assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="http://igenie.co.kr/assets/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="http://igenie.co.kr/views/_layout/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://igenie.co.kr/assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언*
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
<script type="text/javascript" src="http://igenie.co.kr/assets/js/html5shiv.min.js"></script>
<script type="text/javascript" src="http://igenie.co.kr/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="http://igenie.co.kr/assets/js/common.js?v=<?=date("ymdHis")?>"></script>
<script type="text/javascript" src="http://igenie.co.kr/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://igenie.co.kr/assets/js/jquery.validate.extension.js"></script>
<script type="text/javascript" src="http://igenie.co.kr/assets/js/sideview.js?v=<?=date("ymdHis")?>"></script>
</head>
<body >

<!-- 여기까지 레이아웃 상단입니다 -->
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
  <div class="smart_header">
    <span class="logo"><img src="/dhn/images/logo_v2.png" alt="지니"></span>
    <div class="smart_home2" <?=(!empty($this->input->get("home")))? "onClick='location.href=\"/smart/shome/".$this->input->get("home")."\";'" : "style='display:none;'" ?>><img src="/dhn/images/home_icon1.png" /></div>
    <!-- <span class="header_txt">AI 스마트 매장관리시스템</span> -->
  </div>
  <div class="smart_top" style="background: url(<? if($shop->mem_shop_img != ""){ ?><?=$shop->mem_shop_img?><? }else{ ?>/dhn/images/mi_pick.jpg<? } ?>)no-repeat; background-size: cover;">
    <p class="mart_name"><?=$shop->mem_username?></p><?//매장 이름 ?>
  </div>
  <div class="smart_info"><?=nl2br($shop->mem_shop_intro)?></div><?//매장 소개글 ?>
  <div class="smart_con fs18" id="smart_capture">

    <?=str_replace("175.199.47.43", "14.43.241.107", $html->html)?>
  </div>
  <div class="smart_bot">
	  <ul>
		<li>
		 <span class="material-icons">history</span> 운영시간 : <?=$shop->mem_shop_time?>
		</li>
		<li>
		  <span class="material-icons">history_toggle_off</span> 휴무일 : <?=$shop->mem_shop_holiday?>
		</li>
		<li>
		  <span class="material-icons">phone_in_talk</span> 전화번호 : <?=$shop->mem_shop_phone?>
		</li>
		<li>
		  <span class="material-icons">place</span> 주소 : <?=$shop->mem_shop_addr?>
		</li>
	  </ul>
  </div><!--//smart_bot-->
  <div class="smart_map" id="smart_map" style="width:100%;height:250px"><!-- 카카오맵 - 지도 -->
  </div>
  <!-- <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div> -->
   <? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
</div><!--//wrap_smart-->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=config_item('kakao_map_appkey')?>&libraries=services"></script>
<script>
	//카카오맵 - 지도
	var mapContainer = document.getElementById('smart_map'), // 지도를 표시할 div
	mapOption = {
		center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
		level: 3 // 지도의 확대 레벨
	};

	// 지도를 생성합니다
	var map = new kakao.maps.Map(mapContainer, mapOption);

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new kakao.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch('<?=$shop->mem_shop_addr?>', function(result, status) {
		// 정상적으로 검색이 완료됐으면
		 if (status === kakao.maps.services.Status.OK) {
			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new kakao.maps.Marker({
				map: map,
				position: coords
			});
			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div style="width:200px;text-align:center;padding:3px 0; font-size:14px;"><?=$shop->mem_username?></div>'
			});
			infowindow.open(map, marker);
			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			map.setCenter(coords);
		}
	});
</script>
<!-- 본문 끝 -->

<!-- 여기부터 레이아웃 하단입니다 -->
<!--
Layout Directory : <?php echo element('layout_skin_path', $layout); ?>,
Layout URL : <?php echo element('layout_skin_url', $layout); ?>,
Skin Directory : <?php echo element('view_skin_path', $layout); ?>,
Skin URL : <?php echo element('view_skin_url', $layout); ?>,
-->
</body>
</html>
