<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
    return;
}
?>
			<?php if (($bo_table || strpos($_SERVER["PHP_SELF"], 'shop') == true || strpos($_SERVER["PHP_SELF"], 'bbs/faq') == true || strpos($_SERVER["PHP_SELF"], 'bbs/qalist') == true ) && !defined("_INDEX_")) { ?></div><?php } ?>
	</div>
	<!-- } 콘텐츠 끝 -->

	<!-- 하단 시작 { -->
	<footer id="footer">
		<div class="fnb wrap">
				<ul>
						<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a></li>
						<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">이용약관</a></li>
						<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보처리방침</a></li>
						<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=copyright">저작권정책</a></li>
						<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=email">이메일무단수집거부</a></li>
						<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=viewer">뷰어다운로드</a></li>
				</ul>
		</div>
		<address class="wrap">
			<p><span><b>회사명</b> : <?php echo $default['de_admin_company_name']; ?></span>
			<span><b>대표</b> : <?php echo $default['de_admin_company_owner']; ?></span>
			<span><b>개인정보 보호책임자</b> : <?php echo $default['de_admin_info_name']; ?></span>
			<span><b>사업자 등록번호</b> : <?php echo $default['de_admin_company_saupja_no']; ?></span>
			<span><b>통신판매업신고번호</b> : <?php echo $default['de_admin_tongsin_no']; ?></span>
			<span><a href="http://www.ftc.go.kr/info/bizinfo/communicationViewPopup.jsp?wrkr_no=<?php echo preg_replace("/[^0-9]*/s", "", $default['de_admin_company_saupja_no']); ?>" target="_blank" class="bizinfo"><b>사업자정보확인 ▶</b></a></span>
			<?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> '.$default['de_admin_buga_no'].'</span>'; ?></p>
			<p><span><b>주소</b> : <?php echo $default['de_admin_company_addr']; ?></span>
			<span><b>전화</b> : <?php echo $default['de_admin_company_tel']; ?></span>
			<span><b>팩스</b> : <?php echo $default['de_admin_company_fax']; ?></span></p>
			<p class="copyright">Copyright &copy; 2017 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.</p>
		</address>
		<a href="#" id="ft_totop">TOP</a>
	</footer>

<?php
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>
</div>
<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>