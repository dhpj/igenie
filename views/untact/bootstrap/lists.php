<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu13.php');
?>
<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>지원사업 신청목록 (전체 <b style="color: red" id="total_rows">0</b>개)</h3>
    </div>
    <div class="white_box" id="serarch_box">
      <div class="search_box">
        <span style="margin-left:10px;">작성일 </span> <input type="text" onChange="search_question(1);" class="datepicker" name="reg_date" id="reg_date" value="<?=$reg_date?>" readonly="readonly" placeholder="날짜를 선택하세요">
        <span style="margin-left:10px;">작성일 </span> <input type="text" onChange="search_question(1);" class="datepicker" name="reg_date2" id="reg_date2" value="<?=$reg_date2?>" readonly="readonly" placeholder="날짜를 선택하세요">
        <span style="margin-left:10px;">지원사업 </span>
        <select class="select2 input-width-medium" id="type" onChange="search_question(1);">
          <option value="ALL"<?=$type=='ALL' ? 'selected' : ''?>>- ALL -</option>
          <option value="v" <?=$type=='v' ? 'selected' : ''?>>바우처</option>
          <option value="s" <?=$type=='s' ? 'selected' : ''?>>스마트상점</option>
        </select>
        <span style="margin-left:10px;">업종 </span>
        <select class="select2 input-width-medium" id="sectors" onChange="search_question(1);">
          <option value="ALL"<?=$sectors=='ALL' ? 'selected' : ''?>>- ALL -</option>
          <?
            foreach(config_item('sectors') as $key => $a){
          ?>
          <option value="<?=$key?>" <?=$sectors==$key ? 'selected' : ''?>><?=$a?></option>
          <?
            }
          ?>
        </select>
        <span style="margin-left:10px;">진행상태 </span>
        <select class="select2 input-width-medium" id="state" onChange="search_question(1);">
          <option value="ALL" <?=$state=='ALL' ? 'selected' : ''?>>- ALL -</option>
          <option value="신청" <?=$state=='신청' ? 'selected' : ''?>>신청</option>
          <option value="진행" <?=$state=='진행' ? 'selected' : ''?>>진행</option>
          <option value="승인" <?=$state=='승인' ? 'selected' : ''?>>승인</option>
          <option value="종료" <?=$state=='종료' ? 'selected' : ''?>>종료</option>
        </select>
        <select class="select2 input-width-medium" id="searchType" style="margin-left:10px;">
          <option value="1" <?=$search_type=='company_name' ? 'selected' : ''?>>단체명</option>
          <option value="2" <?=$search_type=='user_name' ? 'selected' : ''?>>신청자 성명</option>
        </select>
        <input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>" onKeypress="if(event.keyCode==13){ search_question(1); }">
        <input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
        <a href="/untact/lists/" class="btn md fr" style="<?=(empty($search_for)&&empty($selectdate))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>

      </div>
      <div class="table_list">
        <table>
          <colgroup>
            <!-- <col width="5%"> -->
            <col width="5%">
            <col width="19%">
            <col width="19%">
            <col width="19%">
            <col width="19%">
            <col width="19%">
          </colgroup>
          <thead>
            <tr>
              <!-- <th>번호</th> -->
              <th>지원사업</th>
              <th>회사 또는 단체명</th>
              <th>신청자 성명</th>
              <th>업종</th>
              <th>진행상태</th>
              <th>작성일</th>
            </tr>
          </thead>
          <tbody>
            <? foreach($row as $r){ ?>
              <tr>
                <!-- <td><?=$r->idx?></td> -->
                <td><?=$r->type == 'v' ? '바우처' : '스마트상점'?></td>
                <td><a href="/untact/view/<?=$r->type.$r->idx?>" title="<?=$r->company_name?>"><?=$r->company_name?></a></td>
                <td><a href="/untact/view/<?=$r->type.$r->idx?>" title="<?=$r->user_name?>"><?=$r->user_name?><?=!empty($r->rec_name) ? '<br>(추천업체:' . $r->rec_name . ')' : ''?></a></td>
                <td><?=config_item('sectors')[$r->sector]?></td>
                <td><?=$r->state?></td>
                <td><?=$r->reg_date?></td>
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
<script>
  $("#total_rows").html("<?=number_format($total_rows)?>");
</script>

</div>
</div>
</div>

<script type="text/javascript">

//검색 조회
function search_question(page) {
  open_page(page);
}

function open_page(page){
  var reg_date = $('#reg_date').val();
  var reg_date2 = $('#reg_date2').val();
  var sectors = $('#sectors').val();
  var type = $('#type').val();
  var state = $('#state').val();
  var searchType = $('#searchType').val();
  var searchFor = $('#searchStr').val();
  var pram = "";

  if(reg_date != "") pram += "&reg_date="+ reg_date;
  if(reg_date2 != "") pram += "&reg_date2="+ reg_date2;
  if(sectors != "") pram += "&sectors="+ sectors;
  if(type != "") pram += "&type="+ type;
  if(state != "") pram += "&state="+ state;
  if(searchType != "" && searchFor != "") pram += "&search_type="+ searchType +"&search_for="+ searchFor;

      // if(varp != "") pram += "&p="+ varp;
  //alert("page : "+ page +", tagid : "+ tagid);
  location.href = "?page="+ page + pram;
}

</script>
