<?include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/manager/announce/bootstrap/targets_modal.php');?>
<?include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/manager/announce/bootstrap/announce.php');?>
<link href="/views/biz/manager/announce/bootstrap/css/style.css" rel="stylesheet">

<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>공지관리</h3>
    </div>
    <div class="white_box" id="search_box">
      <div class="search_box">
        <a href="/biz/manager/announce/write">
          <button class="an_btn">작성</button>
        </a>
      </div>
      <div class="table_list">
        <table>
          <colgroup>
            <col width="50px" />
            <col width="*" />
            <col width="150px" />
            <col width="150px" />
            <col width="150px" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
            <col width="50px" />
          </colgroup>
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>글쓴이</th>
              <th>작성 날짜</th>
              <th>발송 날짜</th>
              <th>대상</th>
              <th>발송</th>
              <th>수정</th>
              <th>삭제</th>
            </tr>
          </thead>
          <tbody>
            <? foreach ($announces as $an) { ?>
              <tr data-an-id="<?= $an->an_id ?>">
                <td>
                  <?= $an->an_id ?>
                </td>
                <td>
                  <a class="an_link" href="/biz/manager/announce/lists/<?= $an->an_id ?>">
                    <?= $an->an_title ?>
                  </a>
                </td>
                <td>
                  <?= $an->author_name ?>
                </td>
                <td>
                  <?= $an->an_create_datetime ?>
                </td>
                <td>
                  <?= $an->an_send_datetime ?: '-' ?>
                </td>
                <td>
                  <button class="an_btn" onclick="set_target_in_modal(<?= $an->an_id ?>, open_targets_modal)">확인하기</button>
                </td>
                <td>
                  <? if (isset($an->an_send_datetime)) { ?>
                    <button class="an_btn" disabled title="이미 발송한 공지입니다.">발송</button>
                  <? } else { ?>
                    <button class="an_btn" onclick="send_announce(<?= $an->an_id ?>)">발송</button>
                  <? } ?>
                </td>
                <td>
                  <button class="an_btn" onclick="location.href = '/biz/manager/announce/edit/<?= $an->an_id ?>'">수정</button>
                </td>
                <td>
                  <button class="an_delete_btn" onclick="delete_announce_lists(event)">×</button>
                </td>
              </tr>
            <? } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function delete_announce_lists(event) {
    var $row = $(event.currentTarget).parent().parent()
    var id = $row[0].dataset.anId
    delete_announce(id, function() {
      $row.remove()
    })
  }
</script>
