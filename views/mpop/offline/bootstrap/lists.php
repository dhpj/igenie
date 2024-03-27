<link rel="stylesheet" type="text/css" href="/views/spop/screen_v2/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>
<div id='body' style='display:none'></div>
<div class="wrap_leaflets mpop_wrap">
    <div class="s_tit">
        모바일전단 목록
        <span class="t_small">
            <span class="material-icons">contact_support</span> [미리보기] 버튼 클릭 후 모바일전단 이미지를 다운받으실 수 있습니다.
        </span>
        <div class="new_leaflets">
            <!-- <a><label for="excelFile" style="cursor:pointer;">엑셀로 전단생성</label></a> -->
            <input type="file" id="excelFile" onchange="excelPSD(this)" style="display:none;">
            <a href="/mpop/offline/write">모바일전단 만들기</a>
        </div>
    </div>
<?
    if(!empty($list)){
?>
    <div class="list_leaflets">
        <ul>
        <?
            foreach($list as $a){
                $date = split(' ', $a->pmd_create_datetime)[0];
                $time = split(' ', $a->pmd_create_datetime)[1];
                $s_date = split('-', $date);
                $s_time = split(':', $time);
                $type = json_decode($a->pmd_data)[0]->type;
        ?>
            <li>
                <div class="ll_date">
                    <span class="date_d"><?=$s_date[2]?></span>
                    <span class="date_ym"><?=$s_date[0] . '.' . $s_date[1]?></span>
                </div>
                <div class="ll_title">
                    <p class="title_t"><?=$a->pmd_title?></p>
                    <!-- <button class="cus_excel2" onclick="backup_excel(<?=$a->pmd_id?>)">엑셀 백업</button> -->
                </div>
                <div class="ll_btn1">
                    <button id="dh_myBtn" class="view" style="cursor:pointer;" onclick="print_a4(<?=$a->pmd_id?>, '<?=$type?>', 'print_A4')">A4 프린트</button>
                    <!-- <button id="dh_myBtn" class="view" style="cursor:pointer;" onclick="printer(<?=$a->pmd_id?>, 'a3');">A3 프린트</button> -->
                </div>
                <div class="ll_btn2">
                    <button class="insert" onclick="location.href='/mpop/offline/write?id=<?=$a->pmd_id?>'">수정</button>
                    <button class="copy" onclick="copy(<?=$a->pmd_id?>);">복사</button>
                    <button class="del" onclick="del(<?=$a->pmd_id?>);">삭제</button>
                </div>
            </li>
        <?
            }
        ?>
        </ul>
    </div>
<?
    } else {
?>
    <div class='empty_list'>모바일전단이 존재하지 않습니다.</div>
<?
    }
?>
    <div class="page_cen">
      <?=$page_html?>
    </div>
</div>

<script>
    function print_a4(id, type, size){
        $("#body").html("").load(
            "/mpop/offline/write_body",
            {
                <?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"
              , id : id
              , type : type
            },
            function() {
                $.ajax({
                    url: "/mpop/offline/set_print_log",
                    type: "POST",
                    data: {
                        "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                      , page : 'list'
                      , type : type
                      , print : size.split('_')[1]
                    },
                    success: function (json) {
                    }
                });
                print(size);
            }
        );
    }

    function print(size){
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

    function download_excel(id){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/mpop/offline/backup_excel");

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "id");
        field.setAttribute("value", id);
        form.appendChild(field);

        form.submit();
    }

    function open_page(page){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/mpop/offline");

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "page");
        field.setAttribute("value", page);
        form.appendChild(field);

        form.submit();
    }

    function write(id){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "");

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "id");
        field.setAttribute("value", id);
        form.appendChild(field);

        form.submit();
    }

    function copy(id){
        if (confirm('복사하시겠습니까?')){
            $.ajax({
                url: "/mpop/offline/copy",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                    , id : id
                },
                success: function (json) {
                    alert('복사되었습니다.');
                    location.reload();
                }
            });
        }
    }

    function del(id){
        if (confirm('삭제하시겠습니까?')){
            $.ajax({
                url: "/mpop/offline/del",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                    , id : id
                },
                success: function (json) {
                    alert('삭제되었습니다.');
                    location.reload();
                }
            });
        }
    }
</script>
