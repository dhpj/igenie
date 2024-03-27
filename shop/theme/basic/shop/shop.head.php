<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

/*
if(G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
    return;
}
*/
include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>

<!-- 상단 시작 { -->
<div id="header">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>
    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>
    <div class="wrap">
			<div id="logo"><a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>"></a></div>
			<hr />
			<nav id="gnb">
					<h2>메인메뉴</h2>
					<ul id="gnb_1dul">
							<?php if ($is_member) { ?>
							<?php if ($is_admin) {  ?>
							<li class="gnb_1dli"><a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/" class="gnb_1da">관리자</a></li>
							<?php }  ?>
							<li class="gnb_1dli"><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop" class="gnb_1da">로그아웃</a></li>
							<li class="gnb_1dli"><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php" class="gnb_1da">정보수정</a></li>
							<?php } else { ?>
							<li class="gnb_1dli"><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="gnb_1da">로그인</a></li>
							<li class="gnb_1dli"><a href="<?php echo G5_BBS_URL; ?>/register.php" class="gnb_1da">회원가입</a></li>
							<?php } ?>
							<li class="gnb_1dli"><a href="<?php echo G5_SHOP_URL; ?>/mypage.php" class="gnb_1da">마이페이지</a></li>
							<li class="gnb_1dli"><a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="gnb_1da">장바구니</a></li>
							<li class="gnb_1dli"><a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" class="gnb_1da">주문/배송</a></li>
							<li class="gnb_1dli"><a href="<?php echo G5_BBS_URL; ?>/faq.php" class="gnb_1da">FAQ</a></li>
							<li class="gnb_1dli"><a href="<?php echo G5_BBS_URL; ?>/qalist.php" class="gnb_1da">1:1문의</a></li>
					</ul>
			</nav>
		</div>
</div>
<!-- } 상단 끝 -->

<div id="container" class="sub<?php echo $co_id;?>">
	<?php include(G5_SHOP_SKIN_PATH.'/boxtodayview.skin.php'); // 오늘 본 상품 ?>
	<?php if (($bo_table || strpos($_SERVER["PHP_SELF"], 'shop') == true  || strpos($_SERVER["PHP_SELF"], 'bbs/faq') == true || strpos($_SERVER["PHP_SELF"], 'bbs/qalist') == true) && !defined("_INDEX_")) { ?><div class="wrap"><?php } ?>
   <!-- 콘텐츠 시작 { -->
		<?php if(strpos($_SERVER["PHP_SELF"], 'item') == true){?>
		<div id="wrapper_title"><?php echo $g5['title2'] ?></div>
		<?php }else{?>
		<?php if ((!$bo_table || $w == 's') && !defined('_INDEX_')) { ?><div id="wrapper_title"><?php echo $g5['title'] ?></div><?php } ?>
		<?php } ?>
