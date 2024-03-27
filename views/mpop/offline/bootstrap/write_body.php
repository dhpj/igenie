<script>
    <?
        $td = json_decode($type_data);
        $td_t = $td->title;
        $dt = json_decode($data->pmd_data);
        $dt_t = $dt[0]->title;
        $dt_b = $dt[0]->block;
    ?>
</script>

<!-- 워터마크 -->
<!-- <div class="watermark">
<?for($a=0;$a<10;$a++){?>
    <span><?=$this->member->item('mem_username')?></span>
<?}?>
</div> -->

<section class="mpop_view <?=$type?>">
    <!-- 타이틀 -->
<?if($td_t->useyn){?>
    <article id='prev_title' class="mpop_view_top" style='display:<?=$dt_t->useyn == 'y' || empty($dt[0]->title->useyn) ? '' : 'none'?>'>
        <h4>
        <?if($td_t->mart_name->main){?>
            <span id='prev_mart_name_main' class="mv_mart_main"><?=$dt_t->mart_name->main?></span>
        <?}?>
        <?if($td_t->mart_name->sub){?>
            <span id='prev_mart_name_sub' class="mv_mart_sub"><?=$dt_t->mart_name->sub?></span>
        <?}?>
        </h4>
        <?for($a=0;$a<$td_t->option;$a++){?>
            <div class="mv_info_box info_option_<?=$a?>"><span id='prev_option_<?=$a?>'><?=$dt_t->option[$a]?></span></div>
        <?}?>
        <?for($a=0;$a<$td_t->image;$a++){?>
            <div id='prev_image_<?=$a?>' class="mv_info_box info_img_<?=$a?>" style='display:<?=!empty($dt_t->image[$a]) ? '' : 'none'?>'><img src="<?=str_replace('\\', '', $dt_t->image[$a])?>" alt="title" /></div>
        <?}?>
    </article>
<?}?>
<?$seq=0?>
<?for($a=0;$a<$td->block->cnt;$a++){?>
    <?
        $c_block = $dt_b[$a];
    ?>
    <article class="mpop_corner col_x<?=$td->block->goods_cnt[$a]?>">
    <?if($td->block->title[$a]){?>
        <div id='prev_block_<?=$a?>' class="mpop_goods_title"><span><?=!empty($c_block->title) ? $c_block->title : ''?></span></div>
    <?}?>
    <?for($b=0;$b<$td->block->goods_cnt[$a];$b++){?>
        <ul id="goods_<?=$seq?>" class="mpop_goods">
            <li class="goods_img">
                <img src="<?=!empty($c_block->goods[$b]->data->image_path) ? str_replace('\\', '', $c_block->goods[$b]->data->image_path) : '/images/no_img.jpg'?>" alt="goods" />
            </li>
            <li class="goods_name"><span><?=!empty($c_block->goods[$b]->data->name) ? $c_block->goods[$b]->data->name : ''?></span></li>
            <li class="goods_price" style='display:<?=$td->block->goods_price[$a] ? '' : 'none'?>'><span><?=!empty($c_block->goods[$b]->data->price) ? $c_block->goods[$b]->data->price : ''?></span></li>
            <li class="goods_dcprice"><span><?=!empty($c_block->goods[$b]->data->dcprice) ? $c_block->goods[$b]->data->dcprice : ''?></span></li>
        <?for($c=0;$c<$td->block->goods_option_cnt[$a];$c++){?>
            <li class="goods_option_<?=$c?>"><span><?=!empty($c_block->goods[$b]->data->option[$c]) ? $c_block->goods[$b]->data->option[$c] : ''?></span></li>
        <?}?>
            <li class="goods_badge" style='display:<?=!empty($c_block->goods[$b]->data->badge_path) && $td->block->goods_badge ? '' : 'none'?>'>
                <img src="<?=str_replace('\\', '', $c_block->goods[$b]->data->badge_path)?>" alt="goods" />
            </li>
        </ul>
        <?$seq++?>
    <?}?>
    </article>
<?}?>
</section>
