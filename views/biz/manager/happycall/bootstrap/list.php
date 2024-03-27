<?include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/manager/happycall/bootstrap/modal.php');?>
<link href="/views/biz/manager/happycall/bootstrap/css/style.css" rel="stylesheet">

<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>해피콜&불만사항</h3>
    </div>

    <div class="white_box" id="search_box">
      <div class="search_box">
        <!-- <label></label> -->
        <select id='mem_list' onchange='open_page(1)'>
            <option value='0' <?=$mid == '0' ? 'selected' : ''?>>- 전체 -</option>
            <?if(!empty($mem_list)){?>
                <?foreach($mem_list as $a){?>
                    <option value='<?=$a->mem_id?>' <?=$mid == $a->mem_id ? 'selected' : ''?>><?=$a->mem_username?></option>
                <?}?>
            <?}?>
        </select>
        <label>기간</label>
        <span class="dateBox" style="margin-right: 10px;">
            <input type="text" class="datepicker" name="startDate" id="start_date" value="<?=$start_date?>" onchange='open_page(1)' readonly="readonly"> ~
            <input type="text" class="datepicker" name="endDate" id="end_date" value="<?=$end_date;?>" onchange='open_page(1)' readonly="readonly">
        </span>
        <select id='search_type'>
            <option value='1' <?=$search_type == '1' ? 'selected' : ''?>>업체명</option>
            <option value='2' <?=$search_type == '2' ? 'selected' : ''?>>작성자</option>
        </select>
        <input id='search_str' type='text' value ='<?=$search_str?>' onkeyup = 'if(window.event.keyCode==13){open_page(1);}'/>
        <button onclick='open_page(1);'>검색</button>
        <button onclick='open_modal(true);' class="happycall_btn1">작성</button>
      </div>
      <div class="table_list">
        <table>
          <colgroup>
            <col width="250px" />
            <col width="100px" />
            <col width="100px" />
            <col width="*" />
            <col width="150px" />
            <col width="150px" />
          </colgroup>
          <thead>
            <tr>
              <th>업체명</th>
              <th>카테고리</th>
              <th>작성자</th>
              <th>내용</th>
              <th>작성일</th>
              <th>삭제</th>
            </tr>
          </thead>
          <tbody>
              <?if(!empty($list)){?>
                  <?foreach($list as $key => $a){?>
                      <tr>
                        <td><?=$a->mem_username?></td>
                        <td><?=config_item('hc_category')[$a->hc_category]?></td>
                        <td><?=$a->hc_writer?></td>
                        <td><?=preg_replace('/[\n\r]/', '<br>', $a->hc_contents)?></td>
                        <td><?=$a->hc_create_datetime?></td>
                        <td><button onclick='del_history("<?=$a->hc_id?>")'>삭제</button></td>
                      </tr>
                  <?}?>
              <?} else {?>
                  <tr>
                    <td colspan='5'>데이터가 없습니다.</td>
                  </tr>
              <?}?>
          </tbody>
        </table>
      </div>
      <div class="page_cen"><?echo $page_html?></div>
    </div>
  </div>
</div>
<script>
    function open_page(page) {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/biz/manager/happycall");

        var cfrsField = document.createElement("input");
        cfrsField.setAttribute("type", "hidden");
        cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(cfrsField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "page");
        field.setAttribute("value", page);
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "start_date");
        field.setAttribute("value", $('#start_date').val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "end_date");
        field.setAttribute("value", $('#end_date').val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "mid");
        field.setAttribute("value", $('#mem_list').val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "search_type");
        field.setAttribute("value", $('#search_type').val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "search_str");
        field.setAttribute("value", $('#search_str').val());
        form.appendChild(field);

        form.submit();
    }

    $('#startDate').datepicker({
       format: "yyyy-mm-dd",
       todayHighlight: true,
       language: "kr",
       autoclose: true,
       startDate: '-6m',
       endDate: '-1d'
   }).on('changeDate', function (selected) {
       var startDate = new Date(selected.date.valueOf());
       $('#endDate').datepicker('setStartDate', startDate);
   });

       var start = $("#startDate").val();

   $('#endDate').datepicker({
       format: "yyyy-mm-dd",
       todayHighlight: true,
       language: "kr",
       autoclose: true,
       startDate: start,
       endDate: '+1m'
   }).on('changeDate', function (selected) {
       var endDate = new Date(selected.date.valueOf());
       $('#startDate').datepicker('setEndDate', endDate);
   });

   function del_history(id){
       if (confirm('삭제하시겠습니까?')){
           $.ajax({
               url: "/biz/manager/happycall/del_history",
               type: "POST",
               data: {
                   "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                 , id : id
               },
               success: function (json) {
                   location.reload();
               }
           });
       }
   }
</script>
