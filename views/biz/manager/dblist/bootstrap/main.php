<link rel="stylesheet" type="text/css" href="/views/biz/manager/dblist/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />

<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>업체 목록 (전체 <b style="color: red" id="total_rows"><?=$total_cnt?></b>개)</h3>
		</div>
		<div class="white_box" id="search_box">
            <div class="search_box">
                <ul id='search_period' class="search_period">
					<li id="all" value="all">전체</li>
					<li id="open" value="open">열람</li>
					<li id="close" value="close">잠금</li>
				</ul>
				<label for="userid">업체명</label>
				<input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="" onKeypress="if(event.keyCode==13){ open_page(); }">
				<input type="button" class="btn md" id="check" value="조회" onclick="open_page()"/>
                <?if($_SERVER['REMOTE_ADDR'] == '61.75.230.209'){?>
                    <input type="button" class="btn md" id="check" value="조회" onclick="test();"/>
                <?}?>
			</div>
            <div class="table_list">
                <table>
                	<colgroup>
                		<col width="*">
                		<col width="30%">
                	</colgroup>
                	<thead>
                	<tr>
                		<th>업체명</th>
                		<th>상세보기</th>
                	</tr>
                	</thead>
                	<tbody>
                    <?
                        if (!empty($list)){
                            foreach($list as $a){
                    ?>
                		<tr>
                            <td><?=$a->mem_username?></td>
                            <td><button id='btn_<?=$a->mem_id?>' onclick="open_pwd(this, <?=$a->mem_id?>, '<?=$a->mem_username?>')" class="btn sm"><?=$a->mem_parent_tel_mark_flag == 'Y' ? '잠금' : '열람'?></button></td>
                		</tr>
                    <?
                            }
                        }
                    ?>
                	</tbody>
                </table>
                <input type="file" name="filecount" id="filecount" multiple onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
            </div>
            <div class="page_cen"><?echo $page_html?></div>
		</div>
	</div>
</div>
<div id="pwd_modal" class="dh_modal dblist_pwd">
    <div class="modal-content"> <span id="pwd_close" class="dh_close">&times;</span>
        비밀번호  <input type='password' id='pwd' onKeypress="if(event.keyCode==13){ check_pwd();}"/> <button onclick='check_pwd()' class="btn md">확인</button>
    </div>
</div>

<div id="dbopen_modal" class="dh_modal dblist_dbopen">
    <div class="modal-content">
		<span id="dbopen_close" class="dh_close">&times;</span>
		<ul>
			<li><label for="sel_mem_username">업체명</label><span id='sel_mem_username'></span></li>
			<li><label for="status">DB</label><span id='status'></span></li>
			<li><label for="manager">처리자</label><input type='text' id='manager'></li>
			<li><label for="reason">사유</label><textarea id='reason'></textarea></li>
		</ul>
        <button onclick='set_db_status()' class="btn md">저장</button>
    </div>
</div>

<script>
    var sel_mem_id = '';
    var sel_mem_username = '';
    var sel_status = '';

    $(document).ready(function(){
        $('#searchStr').val('<?=$searchStr?>');
        $('#searchStr').focus();
    });

    $(document).on('click', '#pwd_close', function(){
        $('#pwd_modal').css('display', 'none');
    });

    $(document).on('click', '#dbopen_close', function(){
        $('#dbopen_modal').css('display', 'none');
    });

    function open_page() {
        var duration = $('#search_period').children('li.active').attr('value');

        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/biz/manager/dblist");

        var cfrsField = document.createElement("input");
        cfrsField.setAttribute("type", "hidden");
        cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(cfrsField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "searchStr");
        field.setAttribute("value", $('#searchStr').val()); //검색내용
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "page");
        field.setAttribute("value", '<?=$page?>'); //검색내용
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "duration");
        field.setAttribute("value", duration); //검색내용
        form.appendChild(field);

        form.submit();
    }

    function open_pwd(t, mem_id, mem_username){
        sel_mem_id = mem_id;
        sel_mem_username = mem_username;
        if($(t).html() == '잠금'){
            open_db('N');
        } else {
            $('#pwd').val('');
            $('#pwd_modal').css('display', 'block');
        }
    }

    function check_pwd(){
        $.ajax({
            url: '/biz/manager/dblist/check_pwd',
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>"
              , pwd: $('#pwd').val()
            },
            success: function (json) {
                if(json.code == '1'){
                    open_db('Y');
                } else {
                    alert(json.msg);
                }
            },
        });
    }

    function open_db(flag){
        sel_status = flag;
        $('#pwd_modal').css('display', 'none');
        $('#sel_mem_username').html(sel_mem_username);
        $('#status').html((flag == 'Y' ? '열람' : '잠금'));
        $('#manager').val('');
        $('#reason').val('');
        $('#dbopen_modal').css('display', 'block');
    }

    function set_db_status(){
        $.ajax({
            url: '/biz/manager/dblist/set_dbstatus',
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>"
              , mem_id : sel_mem_id
              , status : sel_status
              , manager : $('#manager').val()
              , reason : $('#reason').val()
            },
            success: function (json) {
                // $('#dbopen_modal').css('display', 'none');
                // $('#btn_' + sel_mem_id).html(sel_status == 'Y' ? '잠금' : '열람');
                location.reload(true);
            },
        });
    }

    var duration = '<?=$duration ? $duration : 'all'?>';
    $("#" + duration).addClass('active');

    $('#search_period li').click(function() {
		$('#search_period li').removeClass('active');
		$(this).addClass('active');
		open_page(1);
	});

    function test(){
        $.ajax({
            url: '/biz/manager/dblist/get_addr',
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>"
            },
            success: function (json) {
            },
        });
    }
</script>
