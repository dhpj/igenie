<div class="md_personal_v1_list_wrap">
    <p class="modal-tit t_al_left">
        소재 선택하기
        <select class="tem_sel_type" id="aaaaaa">
            <option value="">전체</option>
            <option value="bt">기본텍스트</option>
            <!-- <option value="wm">와이드이미지</option>
            <option value="wl">와이드리스트</option>
            <option value="cc">캐러셀커머스</option>
            <option value="cf">캐러셀피드</option> -->
        </select>
    </p>
<? if($tr > 0) { ?>

    <div class="md_personal_v1_list">
        <ul>
    <?
        foreach($list as $a){
            if (empty($a->pcr_para)) continue;
            $data = json_decode($a->pcr_para);
            $str = $data->title;
            $str = str_replace("\n\n", "<p>&nbsp;</p>", $str);
            $str = nl2br($str);
            $img = '';
            if (!empty($data->imagePara)){
                if (strpos($data->imagePara, '${image_url') >= 0){
                    $img = '/images/pm_02.jpg';
                } else if (strpos($data->imagePara, '${video_url') >= 0){
                    $img = '/images/pm_04.jpg';
                }
            }
            if (!empty($data->imageFile->path)){
                $img = str_replace('/var/www/igenie', '', str_replace('\\\\', '', $data->imageFile->path));
            }
    ?>
            <li onclick='selected_template2(<?=$a->pcr_id?>)'>
                <div class="list_tit"><?=$a->type?></div>
                <p>
                    <?if(!empty($img)){?>
                    <img src="<?=$img?>" alt="소재이미지">
                    <?}?>
                </p>
                <div class="list_text"><?=$str?></div>
                <div class="btn_wrap">
                <?
                    if(!empty($data->button)){
                ?>
                    <button type="button" name="button" class="btn"><?=$data->button[0]->title?></button>
                    <?
                        if (!empty($data->button[1])){
                    ?>
                    <button type="button" name="button" class="btn"><?=$data->button[1]->title?></button>
                    <?
                        }
                    ?>
                <?
                    }
                ?>
                </div>
            </li>
    <?
        }
    ?>
        </ul>
    </div>
    <div class="btn_group tc">
        <?echo $page_html?>
    </div>


<?} else { ?>
	<h2>발송 가능한 소재가 없습니다.</h2>
<?} ?>
</div>

<script>
    function open_page(page) {
        $('#template_select .widget-content').html('').load(
          "/dhnbiz/sender/send/template_p",
          {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"
              , 'page' : page
              , 'type' : $('#tem_sel_type').val()
          },
          function () {
          }
        );
    }
</script>
