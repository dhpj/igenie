<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <p class="fr" style="font-size: 16px;line-height: 1.8;"><?=$row->reg_date?></p>
      <div class="inner_tit">
        <h3>바우처 신청 상세정보</h3>
      </div>
      <div class="white_box" style="line-height:100%;">
        <div class="board">
          <!--h3><i class="icon-home"></i>공지사항</h3-->
          <div class="wrap">
            <div class="contents-view">
              <div class="contents-view-img">
              </div>
              <!-- 본문 내용 시작 -->
              <div id="post-content">
                <p>
                  <div class="table_list">
                    <table>
                      <colgroup>
                        <col width="150px">
                        <col width="150px">
                        <col width="100px">
                        <col width="100px">
                        <col width="100px">
                        <col width="150px">
                        <col width="100px">
                        <col width="100px">
                        <col width="100px">
                        <col width="*">
                        <col width="100px">
                        <col width="120px">
                      </colgroup>
                      <tbody>
                        <tr>
                          <th>회사 또는 단체명</th>
                          <td><?=$row->company_name?></td>
                          <th>신청자 성명</th>
                          <td><?=$row->user_name?><?=!empty($row->rec_name) ? '<br>(추천업체:' . $row->rec_name . ')' : ''?></td>
                          <th>연락처</th>
                          <td><?=$row->tel?></td>
                          <th>업종</th>
                          <td><?=config_item('sectors')[$row->sector]?></td>
                          <th>이메일</th>
                          <td><?=$row->email?></td>
                          <th>진행상태</th>
                          <td>
                            <select id="state" style="width:100%;">
                              <option value="신청" <?=$row->state=='신청' ? 'selected' : ''?>>신청</option>
                              <option value="진행" <?=$row->state=='진행' ? 'selected' : ''?>>진행</option>
                              <option value="승인" <?=$row->state=='승인' ? 'selected' : ''?>>승인</option>
                              <option value="종료" <?=$row->state=='종료' ? 'selected' : ''?>>종료</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <th>문의내용</th>
                          <td colspan="9" class="al_left"><?=nl2br($row->content)?></td>
                          <th>추천인</th>
                          <td colspan='2' class="al_left"><?=$row->rec_name?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </p>
              </div>
              <!-- 본문 내용 끝 -->
            </div>
          </div>

          <?php
          if($row->cnt >= 1){ //해당 게시글에 댓글이 있을 경우 출력
            echo '<p class="comment_all">총 <span>'.$row->cnt.'</span> 건의 댓글</p>';
          ?>
              <? foreach($row_cmt as $r){ ?>
                <div class="comment_view_box_inner">
                  <span><?=nl2br($r->cmt_content)?></span>
                  <span>작성자 : <?=$r->reg_name?></span>
                  <span>등록일 : <?=$r->cmt_reg_date?></span>
                  <a href="#none" id="<?=$r->idx?>" cmt_cnt="<?=$row->cnt?>" onclick="delete_cmt(this)" class="btn_del">삭제</a>
                </div>
              <? } ?>
          <? } ?>

          <div id="comment_write_box">
            <div class="well comment_write_box_inner">
              <input type="hidden" id="board_idx" value="<?=$idx?>">
              <div class="btn-group">
                <span>이름</span>
                <input type="text" name="reg_name" id="reg_name" >
              </div>
              <textarea class="form-control" name="cmt_content" id="cmt_content" rows="5" accesskey="c"></textarea>
              <div class="comment_write_button_area">
                <div class="form-group">
                  <a href="#none" class="btn btn-danger btn-sm" id="cmt_btn_submit">댓글등록</a>
                </div>
              </div>
            </div>
          </div>

          <div class="border_button mg_b20">
            <div class="btn-group pull-right">
              <a href="/untact/lists" class="btn btn-sm">목록</a>
            </div>
          </div>
          <!--</div>-->

        </div>
      </div>
    </div>
  </div>
  <!-- 본문 끝 -->

</div>
<script>

$('#state').change(function(){ // 진행상태 업데이트

  var board_idx = $('#board_idx').val();
  var state = $('#state').val();

  $.ajax({
    type : "POST",
    url : "/untact/state_update",
    data : {
      <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
      board_idx: board_idx,
      state: state,
      type: '<?=$type?>'
    },
    success: function (json) {
      if(!json['success']) {
        alert("저장중 오류가 발생하였습니다.");
      }else{
        alert("수정 되었습니다.");
      }
    },
    error: function (data, status, er) {
      alert("처리중 오류가 발생하였습니다.");
    }
  });
});


$('#cmt_btn_submit').click(function(){ //댓글 등록

  if($('#reg_name').val() == ''){
    alert('이름 입력해주세요.');
    $('#reg_name').focus();
    return false;
  }
  if($('#cmt_content').val() == ''){
    alert('내용을 입력해주세요.');
    $('#cmt_content').focus();
    return false;
  }

  var board_idx = $('#board_idx').val();
  var reg_name = $('#reg_name').val();
  var cmt_content = $('#cmt_content').val();

  $.ajax({
    type : "POST",
    url : "/untact/comment_save",
    // async : false,
    dataType : 'json',
    async: false,
    data : {
      <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
      board_idx: board_idx,
      reg_name: reg_name,
      cmt_content: cmt_content,
      type: '<?=$type?>'
    },
    success: function (data) {
      console.log(data.cmt_cnt);
      if(data.cmt_cnt == 1){
        $('.wrap').after('<p class="comment_all">총 <span>'+data.cmt_cnt+'</span> 건의 댓글</p>');
        $('.comment_all').after('<div class="comment_view_box_inner"><span>'+(data.cmt_content).replace(/\n/g, '<br/>')+'</span><span> 작성자 : '+data.reg_name+'</span><span> 등록일 : '+data.cmt_reg_date+'</span><a href="#none" id='+data.cmt_idx+' onclick="delete_cmt(this)" class="btn_del">삭제</a></div>');
      }else if (data.cmt_cnt > 1) {
        $('.comment_all span').text(data.cmt_cnt);
        $('.comment_view_box_inner').last().after('<div class="comment_view_box_inner"><span>'+(data.cmt_content).replace(/\n/g, '<br/>')+'</span><span> 작성자 : '+data.reg_name+'</span><span> 등록일 : '+data.cmt_reg_date+'</span><a href="#none" id='+data.cmt_idx+' onclick="delete_cmt(this)" class="btn_del">삭제</a></div>');
      }
      $('#reg_name').val('');
      $('#cmt_content').val('');
      alert("등록 되었습니다.");
    },
    error: function (data, status, er) {
      alert("처리중 오류가 발생하였습니다.");
    }
  });

});


function delete_cmt(obj) { // 댓글 삭제

  var board_idx = $('#board_idx').val();
  var idx = $(obj).attr('id');
  var cmt_cnt = $(obj).attr('cmt_cnt');

  $.ajax({
    type : "POST",
    url : "/untact/delete_cmt",
    data : {
      <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
      board_idx: board_idx,
      idx: idx,
      type: '<?=$type?>'
    },
    success: function (data) {
      console.log(data);
      if(data < 1){
        $('.comment_all').remove();
      }else{
        $('.comment_all span').text(data);
      }
      alert("삭제 되었습니다.");
    },
    error: function (data, status, er) {
      alert("처리중 오류가 발생하였습니다.");
    }
  });
  $("#"+$(obj).attr('id')).parent('div').remove(); //라인 삭제
  return true;
};

</script>
