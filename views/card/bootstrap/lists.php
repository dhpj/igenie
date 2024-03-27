<!-- 3차 메뉴 -->

<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>카드 신청 목록 (전체 <b style="color: red" id="total_rows"><?=$total_rows?></b>개)</h3>
    </div>
    <div class="white_box" id="serarch_box">

      <div class="search_box">
        <span style="margin-left:10px;">신청일 </span> <input type="text" onChange="search_question(1);" class="datepicker" name="reg_date" id="reg_date" value="<?=$reg_date?>" readonly="readonly" placeholder="날짜를 선택하세요">
        <span style="margin-left:10px;">진행상태 </span>
        <select class="select2 input-width-medium" id="state" onChange="search_question(1);">
          <option value="ALL" <?=$state=='ALL' ? 'selected' : ''?>>- ALL -</option>
          <option value="S" <?=$state=='S' ? 'selected' : ''?>>신청</option>
          <option value="P" <?=$state=='P' ? 'selected' : ''?>>진행</option>
          <option value="C" <?=$state=='C' ? 'selected' : ''?>>완료</option>
        </select>
        <select class="select2 input-width-medium" id="searchType" style="margin-left:10px;">
          <option value="1" <?=$search_type=='company' ? 'selected' : ''?>>업체명</option>
          <option value="2" <?=$search_type=='name' ? 'selected' : ''?>>신청자 성명</option>
        </select>
        <input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>" onKeypress="if(event.keyCode==13){ search_question(1); }">
        <input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
        <a href="/untact/lists/" class="btn md fr" style="<?=(empty($search_for)&&empty($selectdate))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>

      </div>
      <?
          if($total_rows > 0){
      ?>
      <div class="table_list">
        <table>
          <colgroup>
            <col width="5%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="10%">
            <col width="10%">
            <col width="7%">
          </colgroup>
          <thead>
            <tr>
              <th>상태</th>
              <th>이름</th>
              <th>전화번호</th>
              <th>이메일</th>
              <th>업체명</th>
              <th>사업자등록증</th>
              <th>통장사본</th>
              <th>신청서</th>
              <th>업체문의내용</th>
              <th>비고</th>
              <th>신청일</th>
            </tr>
          </thead>
          <tbody>
            <? foreach($row as $r){ ?>
              <tr>
                <td>
                    <select class="select2 input-width-medium" id="" onChange="set_status(this, <?=$r->mem_id?>);">
                        <option value="S" <?=$r->status=='S' ? 'selected' : ''?>>신청</option>
                        <option value="P" <?=$r->status=='P' ? 'selected' : ''?>>진행</option>
                        <option value="C" <?=$r->status=='C' ? 'selected' : ''?>>완료</option>
                    </select>
                </td>
                <td><?=$r->name?></td>
                <td><?=$r->tel?></td>
                <td><?=$r->email?></td>
                <td><?=$r->company?></td>
                <td><a href="<?=$r->buis_img?>" target="_blank"><img src="<?=$r->buis_img?>" style="width:30px; height:50px;"></a></td>
                <td><a href="<?=$r->bank_img?>" target="_blank"><img src="<?=$r->bank_img?>" style="width:30px; height:50px;"></a></td>
                <td><a href="<?=$r->sub_img?>" target="_blank"><img src="<?=$r->sub_img?>" style="width:30px; height:50px;"></a></td>
                <td><?=$r->cus_memo?></td>
                <td><input type=text class="memo" value="<?=$r->memo?>" memid="<?=$r->mem_id?>"></td>
                <td><?=date("Y-m-d", strtotime($r->create_datetime))?></td>
              </tr>
            <? } ?>
          </tbody>
        </table>
      </div>
      <!-- </form> -->
      <!-- <div class="border_button">
        <div class="pull-left mr10">
          <a href="http://kakaobrandtalk.com/board/notice?" class="btn_st1">목록</a>
        </div>
        <div class="pull-right">
          <a href="http://kakaobrandtalk.com/untact/voucher" class="btn_st1">글쓰기</a>
        </div>
      </div> -->
<div class="page_cen"><?echo $page_html?></div> <!-- 페이지 -->
</div>
<?
} else{
?>
<?
    echo "항목이 없습니다.";
}
?>
</div>
</div>

<script type="text/javascript">

    //검색 조회
    function search_question(page) {
      open_page(page);
    }

    function open_page(page){
      var reg_date = $('#reg_date').val();
      var customer_type = $('#customer_type').val();
      var state = $('#state').val();
      var searchType = $('#searchType').val();
      var searchFor = $('#searchStr').val();
      var pram = "";

      if(reg_date != "") pram += "&reg_date="+ reg_date;
      if(customer_type != "") pram += "&customer_type="+ customer_type;
      if(state != "") pram += "&state="+ state;
      if(searchType != "" && searchFor != "") pram += "&search_type="+ searchType +"&search_for="+ searchFor;

          // if(varp != "") pram += "&p="+ varp;
      //alert("page : "+ page +", tagid : "+ tagid);
      location.href = "?page="+ page + pram;
    }

    function set_status(t, mem_id){
        $.ajax({
            url: "/card/set_status",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : mem_id
              , status : $(t).val()
            },
            success: function (json) {
            }
        });
    }

    $('.memo').focusout(function() {
        $.ajax({
            url: "/card/set_memo",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : $(this).attr("memid")
              , memo : $(this).val()
            },
            success: function (json) {
            }
        });
    });

</script>
