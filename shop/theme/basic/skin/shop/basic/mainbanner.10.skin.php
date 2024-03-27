<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/js/flickity/flickity.css">', 0);
?>
<script src="<?php echo G5_THEME_URL; ?>/js/flickity/flickity.pkgd.min.js"></script>
<?php
$max_width = $max_height = 0;
$bn_first_class = ' class="carousel-cell"';

for ($i=0; $row=sql_fetch_array($result); $i++)
{
    if ($i==0) echo '<section id="sbn_idx" class="sbn">'.PHP_EOL.'<h2>쇼핑몰 배너</h2>'.PHP_EOL.'<div class="carousel">'.PHP_EOL;
    //print_r2($row);
    // 테두리 있는지
    $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

    $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
    if (file_exists($bimg))
    {
        $banner = '';
        $size = getimagesize($bimg);

        if($size[2] < 1 || $size[2] > 16)
            continue;

        if($max_width < $size[0])
            $max_width = $size[0];

        if($max_height < $size[1])
            $max_height = $size[1];

        echo '<div '.$bn_first_class.'>'.PHP_EOL;
        if ($row['bn_url'][0] == '#')
            $banner .= '<a href="'.$row['bn_url'].'">';
        else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
            $banner .= '<a href="'.G5_SHOP_URL.'/bannerhit.php?bn_id='.$row['bn_id'].'&amp;url='.urlencode($row['bn_url']).'"'.$bn_new_win.'>';
        }
        echo $banner.'<img src="'.G5_DATA_URL.'/banner/'.$row['bn_id'].'" width="'.$size[0].'" alt="'.$row['bn_alt'].'"'.$bn_border.'>';
        if($banner)
            echo '</a>'.PHP_EOL;
        echo '</div>'.PHP_EOL;

        //$bn_first_class = '';
    }
}
if ($i>0) echo '</div>'.PHP_EOL.'</section>'.PHP_EOL;
?>
<script>
$('.carousel').flickity({
	autoPlay: true,
	infinite: true,
	speed: 3000,
	centerMode: true,
	variableWidth: true,
	fade: true,
	cssEase: 'linear'
	});
</script>
