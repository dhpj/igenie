<link href="/views/biz/manager/happycall/bootstrap/css/style.css" rel="stylesheet">

<div id='hc_modal' class="happycall_wrap" style='display:none;'>
  <div class="modal_white_box">
    <div class="modal_textarea">
      <select id='partner_list' onchange='get_history_from_partner(this);'>
          <option value='0'>업체명</option>
          <?if (!empty($modal_list)){?>
          <?foreach($modal_list as $a){?>
              <option value='<?=$a->mem_id?>'><?=$a->mem_username?></option>
          <?}?>
          <?}?>
      </select>
      <span id='patner_name' class="modal_list_name"></span>
      <select id='category_list' class="margin_select">
          <?foreach(config_item('hc_category') as $key => $a){?>
              <option value='<?=$key?>'><?=$a?></option>
          <?}?>
      </select>
      <label>작성자</label>
      <input id='writer' type="text" />
    </div>
    <textarea id='contents'></textarea>
    <button onclick='save_history()'>등록</button>
    <div class="modal_list_box">
      <table>
        <colgroup>
          <col width="100px">
          <col width="100px">
          <col width="*">
          <col width="150px">
          <col width="100px">
        </colgroup>
        <thead>
          <tr>
            <th>카테고리</th>
            <th>작성자</th>
            <th>내용</th>
            <th>작성일</th>
            <th>삭제</th>
          </tr>
        </thead>
        <tbody id='list_box'>
            <tr>
                <td colspan='4'>히스토리가 없습니다.</td>
            </tr>
          <!-- <tr>
            <td>작성자</td>
            <td>내용</td>
            <td>2023.06.08</td>
            <td><button>삭제</button></td>
          </tr> -->
        </tbody>
      </table>
    </div>
    <button class="modal_close" onclick='close_modal()'><i class="xi-close"></i></button>
  </div>
</div>
<script>
    var mid = '0';
    var page_flag;
    var category = [];

    <?foreach(config_item('hc_category') as $key => $a){?>
        category[<?=$key?>] = '<?=$a?>';
    <?}?>

    function open_modal(flag){
        if (flag){
            $('#patner_name').css('display', 'none');
            $('#partner_list').css('display', '');
        } else {
            $('#patner_name').css('display', '');
            $('#partner_list').css('display', 'none');
            get_history(mid);
        }
        page_flag = flag;
        $('#hc_modal').css('display', '');
    }

    function close_modal(){
        $('#hc_modal').css('display', 'none');
    }

    function get_history_from_partner(t){
        mid = $('#partner_list').val();
        get_history($(t).val());
    }

    function save_history(){
        if (page_flag){
            mid = $('#partner_list').val();
            if (mid == '0'){
                alert('업체를 선택해주세요.');
                return;
            }
        }
        if ($('#writer').val().trim() == ''){
            alert('작성자를 입력해주세요.');
            $('#writer').focus()
            return;
        }

        if ($('#contents').val().trim() == ''){
            alert('내용을 입력해주세요.');
            $('#contents').focus()
            return;
        }
        ins_history();
    }

    function ins_history(){
        if(confirm('등록하시겠습니까?')){
            $.ajax({
                url: "/biz/manager/happycall/ins_history",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                  , mid : mid
                  , category : $('#category_list').val()
                  , writer : $('#writer').val()
                  , contents : $('#contents').val()
                },
                success: function (json) {
                    $('#writer').val('');
                    $('#contents').val('');
                    set_history(json.list);
                }
            });
        }
    }

    function set_history(list){
        var html = '';
        $('#list_box').html('');
        if (list != undefined){
            if (list.length > 0){
                $.each(list, function(idx, val){
                    html += '<tr>';
                    html += '<td>' + category[val.hc_category] + '</td>';
                    html += '<td>' + val.hc_writer + '</td>';
                    html += '<td>' + val.hc_contents.replaceAll('\n', '<br>') + '</td>';
                    html += '<td>' + val.hc_create_datetime + '</td>';
                    html += '<td><button onclick="del_history_from_modal(' + val.hc_id + ')">삭제</button></td>';
                    html += '</tr>';
                });
            } else {
                html += '<tr><td colspan=\'4\'>히스토리가 없습니다.</td></tr>'
            }
        } else {
            html += '<tr><td colspan=\'4\'>히스토리가 없습니다.</td></tr>'
        }
        $('#list_box').append(html);
    }

    function get_history(mid){
        $.ajax({
            url: "/biz/manager/happycall/get_history",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , mid : mid
            },
            success: function (json) {
                set_history(json.list);
            }
        });
    }

    function del_history_from_modal(id){
        if (confirm('삭제하시겠습니까?')){
            $.ajax({
                url: "/biz/manager/happycall/del_history",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                  , id : id
                },
                success: function (json) {
                    get_history(mid);
                }
            });
        }
    }
</script>
