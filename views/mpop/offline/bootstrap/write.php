<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://igenie.co.kr/views/spop/screen_v2/bootstrap/css/common.css?v=231123092757?v=<?=date("ymdHis")?>"/>
<div class="mpop_wrap">
    <h3 class="s_tit">모바일전단 만들기</h3>
    <div class="mpop_view_wrap">
        <div id='grid'></div>
        <div id='body_test'></div>
        <div id='body'></div>
    </div>
    <div class="mpop_btns_wrap">
      <button type="button" class="pop_btn_save" onclick="save(<?=$id?>, true)"><span class="material-icons">save</span> 저장하기</button>
      <button type="button" class="pop_btn_send" onclick="print('print_A4')"><span class="material-icons">print</span> A4프린트</button>
      <button type="button" class="pop_btn_cancel" onclick="location.href = '/mpop/offline'"><span class="material-icons">highlight_off</span> 목록으로</button>
    </div>
</div>


<script>
    var no_image = '/images/no_img.jpg';

    function load_grid(id, type){
        $("#grid").html("").load(
            "/mpop/offline/write_grid",
            {
                <?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"
              , id : id
              , type : type
            },
            function() {
            }
        );
    }

    $(document).ready(function(){
        load_grid(<?=$id?>, '<?=$type?>');
    });

    function print(size){
        if (confirm('저장된 후 프린트가 가능합니다. 저장하시겠습니까 ?')){
            save(<?=$id?>, false);
            $.ajax({
                url: "/mpop/offline/set_print_log",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                    , page : 'list'
                    , type : '<?=$type?>'
                    , print : size.split('_')[1]
                },
                success: function (json) {
                    win = window.open();

                    self.focus();

                    win.document.open();

                    win.document.write("<html><head><title></title><script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'><"+"/script><script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'><"+"/script><link rel='stylesheet' type='text/css' href='/views/_layout/bootstrap/css/import.css?v=<?=date("ymdHis")?>'><link rel='stylesheet' type='text/css' href='/views/mpop/offline/bootstrap/css/style.css?v=<?=date("ymdHis")?>'><style>");
                    win.document.write('</style>');
                    <? if($print_ladscape == 1){ ?>
                      win.document.write('<style>@page { size: landscape; }');
                      win.document.write('</style>');
                    <? } ?>

                    win.document.write('</style></head><body>');
                    $('#body').find('section').addClass(size);
                    win.document.write($('#body').html());
                    win.document.write('</body></html>');

                    win.document.close();

                    setTimeout(function(){
                        // win.print();
                        // win.close();
                    },100);
                    $('#body').find('section').removeClass(size);
                }
            });
        }
    }

    function save(id, flag){
        jsLoading();
        if($('#mpop_title').val().trim() == ''){
            alert('전단 이름을 작성해주세요.');
            $('#mpop_title').focus();
            return;
        }
        var arr_data = new Array();
        var type = selected_type;
        arr_data['type'] = type;
        arr_data['title'] = new Array();
        arr_data['title']['useyn'] = $('[name=title_yn]:checked').val();
        arr_data['title']['mart_name'] = new Array();
        arr_data['title']['mart_name']['main'] = $('#mart_name_main').val();
        arr_data['title']['mart_name']['sub'] = $('#mart_name_sub').val();
        arr_data['title']['mart_name'] = {...arr_data['title']['mart_name']};

        arr_data['title']['option'] = new Array();
        for(var i=0;i<type_data.title.option;i++){
            arr_data['title']['option'].push($('#option_' + i).val());
        }

        arr_data['title']['image'] = new Array();
        for(var i=0;i<type_data.title.image;i++){
            arr_data['title']['image'].push($('#image_' + i).attr('src'));
        }
        arr_data['title'] = {...arr_data['title']};

        var inc_no = 0;
        arr_data['block'] = new Array();
        for(var i=0;i<type_data.block.cnt;i++){
            var block_data = new Array();
            block_data['type'] = 'goods';
            block_data['image_path'] = '';
            block_data['header_yn'] = '';
            block_data['title'] = $('#block_title_' + i).val();
            block_data['header'] = new Array();
            block_data['header']['exist_yn'] = 'n';
            block_data['header']['image_yn'] = 'n';
            block_data['header']['image_path'] = '';
            block_data['header']['text'] = '';
            block_data['header']['text_color'] = 'rgb(255,0,0)';
            block_data['header']['background_color'] = 'rgb(255,0,0)';
            block_data['header']['background_image_yn'] = 'n';
            block_data['header']['background_image_path'] = '';
            block_data['header'] = {...block_data['header']};
            block_data['background'] = new Array();
            block_data['background']['image_yn'] = 'n';
            block_data['background']['image_path'] = '';
            block_data['background'] = {...block_data['background']};
            block_data['goods'] = new Array();
            for(var j=0;j<type_data.block.goods_cnt[i];j++){
                var goods_data = new Array();
                goods_data['background'] = new Array();
                goods_data['background']['image_yn'] = 'n';
                goods_data['background']['image_path'] = '';
                goods_data['background'] = {...goods_data['background']};
                goods_data['data'] = new Array();
                goods_data['data']['badge_yn'] = type_data.block.goods_badge[i] ? 'y' : 'n';
                goods_data['data']['badge_path'] = data[inc_no][(4+max_option)] != undefined ? data[inc_no][(4+max_option)] : '';
                goods_data['data']['image_yn'] = 'y';
                goods_data['data']['image_path'] = data[inc_no][0];
                goods_data['data']['price'] = data[inc_no][2];
                goods_data['data']['dcprice'] = data[inc_no][3];
                goods_data['data']['name'] = data[inc_no][1];
                goods_data['data']['option'] = new Array();
                for(var k=0;k<max_option;k++) goods_data['data']['option'].push(data[inc_no][4 + k]);
                inc_no++;
                goods_data['data'] = {...goods_data['data']};
                block_data['goods'].push({...goods_data});
            }

            arr_data['block'].push({...block_data});
        }
        setTimeout(() => {
            var file_data = new FormData();
            file_data.append("<?=$this->security->get_csrf_token_name() ?>", "<?=$this->security->get_csrf_hash() ?>");
            file_data.append("id", <?=$id?>);
            file_data.append("title", $('#mpop_title').val().trim());
            file_data.append("arr_data", JSON.stringify([{...arr_data}]));
            $.ajax({
                url: "/mpop/offline/save_mpop",
                type: "POST",
                cache : false,
                data:file_data,
                processData: false,
                contentType: false,
                success: function (json) {
                    hideLoading();
                    if (flag){
                        modal4.style.display = "block";
                    }
                }
            });
        }, 1000);

    }
</script>
