<div class="input_content_wrap">
    <label class="input_tit" id="id_STEP0">
        <p class="txt_st_eng">STEP 1.</p>
        <p>광고그룹 선택</p>
    </label>
    <div class="input_content">

        <?
            if (!empty($adglist)){
        ?>
        <select id="select_adg" class="select_text_item" onchange="">
            <?
                foreach($adglist as $a){
            ?>
                <option value="<?=$a->pa_a_id?>" data-id='<?=$a->pa_id?>' <?=$data->adGroupId == $a->pa_a_id ? 'selected' : ''?>><?=$a->pa_name?></option>
            <?
                }
            ?>
        </select>
        <?
            } else {
        ?>
            <span>광고그룹을 생성해주세요.</span>
        <?
            }
        ?>
    </div>
</div>
