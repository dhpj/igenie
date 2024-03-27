<?include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/manager/announce/bootstrap/announce.php');?>
<link href="/views/biz/customer/bootstrap/css/style.css" rel="stylesheet">
<link href="/views/biz/manager/announce/bootstrap/css/style.css" rel="stylesheet">
<link href="/views/biz/manager/announce/bootstrap/css/write.css" rel="stylesheet">

<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3><?= $an->an_title ?></h3>
      <span class="an_item_create">작성일: <?= $an->an_create_datetime ?></span>
    </div>
    <div class="an_item_btn_container">
      <? if (isset($an->an_send_datetime)) { ?>
        <button class="an_btn" disabled title="이미 발송한 공지입니다.">발송</button>
      <? } else { ?>
        <button class="an_btn" onclick="send_announce(<?= $an->an_id ?>)">발송</button>
      <? } ?>
      <button class="an_btn" onclick="location.href = '/biz/manager/announce/edit/<?= $an->an_id ?>'">수정</button>
      <button class="an_btn" onclick="delete_announce(<?= $an->an_id ?>, back_to_lists_page)">삭제</button>
    </div>
    <div class="white_box">
      <?= $an->an_content ?>
    </div>
  </div>
  <div class="form_section">
    <div class="inner_tit">
      <h3>확인 현황 (총 <span class="list_total"><?= number_format(count($states)) ?></span>건)</h3>
    </div>
    <div class="white_box">
      <div class="search_box">
        <select id="has_read_input" oninput="update_search_res()">
          <option value="all" <?= ($search_params['has_read'] === 'all') ? 'selected' : '' ?>>전체</option>
          <option value="true" <?= ($search_params['has_read'] === 'true') ? 'selected' : '' ?>>읽음</option>
          <option value="false" <?= ($search_params['has_read'] === 'false') ? 'selected' : '' ?>>읽지 않음</option>
        </select>
        <input id="username_input" placeholder="업체명 입력" value="<?= $search_params['username'] ?>">
        <button class="an_btn" onclick="update_search_res()">조회</button>
      </div>
      <div class="table_list">
        <table>
          <colgroup>
            <col width="*" />
            <col width="100px" />
            <col width="150px" />
          </colgroup>
          <thead>
            <tr>
              <th>업체명</th>
              <th>확인 여부</th>
              <th>확인 날짜</th>
            </tr>
          </thead>
          <tbody>
            <? foreach ($states as $s) { ?>
              <tr>
                <td>
                  <?= $s->mem_username ?>
                </td>
                <td>
                  <?= $s->ans_read == '1' ? 'O' : 'X' ?>
                </td>
                <td>
                  <?= $s->ans_read_datetime ?: '-' ?>
                </td>
              </tr>
            <? } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<form id="search_form" name="search_form" method="get">

<script>
  var $username_input = $("#username_input")
  var usernameVal = $username_input.val()
  $username_input
    .focus()
    .val("")
    .val(usernameVal) // 커서 위치를 맨 끝으로 옮기기
  $username_input.on("keydown", function(evt) {
    if (evt.key === "Enter") update_search_res()
  })
  function back_to_lists_page() {
    location.href = "/biz/manager/announce/lists"
  }
  function update_search_res() {
    var hasRead = $("#has_read_input").val()
    var username = $("#username_input").val()
    var form = $("#search_form")
    form.empty()
    form.append(
      create_form_input("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>"),
      create_form_input("has_read", hasRead),
      create_form_input("username", username),
    )
    form.submit()
  }
  function create_form_input(name, value) {
    return $("<input>")
      .attr("type", "hidden")
      .attr("name", name)
      .attr("value", value)
  }
</script>
