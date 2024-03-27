<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<?php if ($default['de_rel_list_use']) { ?>
<!-- 관련상품 시작 { -->
<section id="sit_rel">
    <h2><span class="tit_bullet"></span>관련상품</h2>
    <div class="sct_wrap">
        <?php
        $rel_skin_file = $skin_dir.'/'.$default['de_rel_list_skin'];
        if(!is_file($rel_skin_file))
            $rel_skin_file = G5_SHOP_SKIN_PATH.'/'.$default['de_rel_list_skin'];

        $sql = " select b.* from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id) where a.it_id = '{$it['it_id']}' and b.it_use='1' ";
        $list = new item_list($rel_skin_file, $default['de_rel_list_mod'], 0, $default['de_rel_img_width'], $default['de_rel_img_height']);
        $list->set_query($sql);
        echo $list->run();
        ?>
    </div>
</section>
<!-- } 관련상품 끝 -->
<?php } ?>
<!-- 상품상세공지 시작 { -->
<div class="itemnotice">
		<?php echo display_banner('상품상세공지'); ?>
</div>
<!-- } 상품상세공지 끝 -->
<!-- 상품 정보 시작 { -->
<section id="sit_inf">
    <h2>상품 정보</h2>
    <?php if ($it['it_explan']) { // 상품 상세설명 ?>
    <h3 class="hidden">상품 상세설명</h3>
    <div id="sit_inf_explan">
        <?php echo conv_content($it['it_explan'], 1); ?>
    </div>
    <?php } ?>

</section>
<!-- } 상품 정보 끝 -->

<div class="tab" id="item_tab">
    <ul>
        <li class="tab-1"><a href="#sit_use" class="tab_selected">상품후기</a></li>
        <li><a href="#sit_qa">상품문의</a></li>
        <li><a href="#sit_dvr">상품고시</a></li>
        <li><a href="#sit_ex">배송/교환/반품</a></li>
    </ul>
</div>

<div class="tab_content sitWrap">

    <!-- 사용후기 시작 { -->
    <section id="sit_use">
        <h2 class="hidden">사용후기</h2>
        <div id="itemuse"><?php include_once(G5_SHOP_PATH.'/itemuse.php'); ?></div>
    </section>
    <!-- } 사용후기 끝 -->

    <!-- 상품문의 시작 { -->
    <section id="sit_qa">
        <h2 class="hidden">상품문의</h2>
        <div id="itemqa"><?php include_once(G5_SHOP_PATH.'/itemqa.php'); ?></div>
    </section>
    <!-- } 상품문의 끝 -->

    <?php if ($default['de_baesong_content']) { // 배송정보 내용이 있다면 ?>
    <!-- 배송정보 시작 { -->
    <section id="sit_dvr">
        <h2 class="hidden">상품고시</h2>
        <?/*php echo conv_content($default['de_baesong_content'], 1); */?>

				<?php
					if ($it['it_info_value']) { // 상품 정보 고시
							$info_data = unserialize(stripslashes($it['it_info_value']));
							if(is_array($info_data)) {
									$gubun = $it['it_info_gubun'];
									$info_array = $item_info[$gubun]['article'];
					?>
					<table id="sit_inf_open">
					<colgroup>
							<col class="grid_4">
							<col>
					</colgroup>
					<tbody>
					<?php
					foreach($info_data as $key=>$val) {
							$ii_title = $info_array[$key][0];
							$ii_value = $val;
					?>
					<tr>
							<th scope="row"><?php echo $ii_title; ?></th>
							<td><?php echo $ii_value; ?></td>
					</tr>
					<?php } //foreach?>
					</tbody>
					</table>
					<!-- 상품정보고시 end -->
					<?php
							} else {
									if($is_admin) {
											echo '<p>상품 정보 고시 정보가 올바르게 저장되지 않았습니다.<br>config.php 파일의 G5_ESCAPE_FUNCTION 설정을 addslashes 로<br>변경하신 후 관리자 &gt; 상품정보 수정에서 상품 정보를 다시 저장해주세요. </p>';
									}
							}
					} //if
					?>
    </section>
    <!-- } 배송정보 끝 -->
    <?php } ?>


    <?php if ($default['de_change_content']) { // 교환/반품 내용이 있다면 ?>
    <!-- 교환/반품 시작 { -->
    <section id="sit_ex">
        <h2 class="hidden">배송/교환/반품</h2>
        <?php echo conv_content($default['de_change_content'], 1); ?>
    </section>
    <!-- } 교환/반품 끝 -->
    <?php } ?>


</div>
<script>
$(window).on("load", function() {
    $("#sit_inf_explan").viewimageresize2();
});

$(function(){
    $(".tab_content section:not("+$(".tab li a.tab_selected").attr("href")+")").hide();
    $(".tab li a").click(function(){
        $(".tab li a").removeClass("tab_selected");
        $(this).addClass("tab_selected");
        $(".tab_content section").hide();
        $($(this).attr("href")).show();
    });
});
</script>