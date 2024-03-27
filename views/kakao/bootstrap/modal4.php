<script>
    var parameters = new Array();
<?
    if (!empty($parameters)){
        foreach($parameters as $key => $a){
?>
            parameters.<?=$key?> = new Array();
        <?
            foreach($a as $key2 => $b){
        ?>
                parameters.<?=$key?>.push('<?=$b?>');
        <?
            }
        ?>
<?
        }
    }
?>
</script>
<div id='modal4' class="kakao kakao_modal" style='display:none;'>
    <div class="modal-content add_change_item">

        <span id="" class="modal_close" onclick='open_modal("none", 4);'>×</span>
        <p class="modal-tit">변수 항목 추가</p>

        <div class="item_choice">
            <h5>변수 항목</h5>
            <select id='m4_sel1' class="select_change_item" onchange='m4_change_sel1(this)'>
            <?
                if (!empty($parameters)){
                    foreach($parameters as $key => $a){
            ?>
                <option value='<?=$key?>'><span><?=$a[0]?></span></option>
            <?
                    }
                }
            ?>
            </select>

            <select id='m4_sel2' class="select_change_item" onchange='m4_change_sel2(this.value)'>
                <option value='1'>1</option>
                <option value='2'>2</option>
                <option value='3'>3</option>
                <option value='4'>4</option>
            </select>

            <h5>미리보기</h5>
            <span id='m4_p_para2' type="text" class="change_item_name">${date1}</span>
        </div>

        <div class="modal_btns">
            <button type="button" class="btn_gw" onclick='open_modal("none", 4);'>
                <span>취소</span>
            </button>
            <button type="button" class="btn_gw btn_bg_bk" onclick='m4_confirm();'>
                <span>확인</span>
            </button>
        </div>

    </div>
</div>

<script>
    function m4_change_sel1(t){
        $('#m4_sel2').html('');
        var html = '';
        $.each(parameters[$(t).val()], function(idx, val){
            if(idx === 0) return true;
            html += '<option value="' + idx + '">' + idx + '</option>';
        });
        $('#m4_sel2').html(html);
        $('#m4_p_para2').html(parameters[$(t).val()][1]);
    }

    function m4_confirm(){
        $('#c_title').val($('#c_title').val() + $('#m4_p_para2').html());
        open_modal("none", 4);
    }

    function m4_change_sel2(val){
        $('#m4_p_para2').html(parameters[$('#m4_sel1').val()][val]);
    }
</script>
