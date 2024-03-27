<link href="/views/biz/customer/bootstrap/css/style.css" rel="stylesheet">
<link href="/views/biz/manager/announce/bootstrap/css/style.css" rel="stylesheet">
<link href="/views/biz/manager/announce/bootstrap/css/write.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>공지 수정</h3>
    </div>
    <button id="an_target_select_btn" class="an_btn" onclick="open_an_dropdown(event)">대상 선택</button>
    <div id="an_dropdown_container">
      <div id="an_dropdown" style="display: none;">
        <input id="an_search" placeholder="업체명 입력" oninput="search_target(event)">
        <ul></ul>
      </div>
    </div>
    <div class="an_area">
      <div class="an_title_area">
        <span>제목</span>
        <input id="an_title" value="<?= $an->an_title ?>">
      </div>
      <textarea id="an_content" cols="80" rows="10"><?= $an->an_content ?></textarea>
      <button class="an_btn" onclick="an_edit()">수정</button>
    </div>
  </div>
</div>

<?include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/manager/announce/bootstrap/write_edit_common.php');?>
<script>
  var anId = <?= $an->an_id ?>;
  var allTargets = [<?= join(',', array_map(function($a) { return json_encode($a, JSON_UNESCAPED_UNICODE); }, $an_targets)) ?>]
  var existingTargetIds = [<?= $an->an_targets ?>].map(String)
  var checkedTargetIds = existingTargetIds.slice()

  render_targets(allTargets)

  function an_edit() {
    var title = $("#an_title").val()
    var content = $an_content.summernote("code")

    if (checkedTargetIds.length < 1) {
      alert("발송 대상을 선택해주세요.")
      return
    }

    if (title.trim() === "") {
      alert("제목을 입력해주세요.")
      return
    }

    if (content.trim() === "") {
      alert("내용을 입력해주세요.")
      return
    }

    $.ajax({
      url: "/biz/manager/announce/edit_announce",
      type: "POST",
      data: {
        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
        id: anId,
        title: title,
        content: content,
      },
      success: function() {
        var i = 0
        var unit = 1500
        for (var i = 0; i < checkedTargetIds.length; i += unit) {
          var chunk = checkedTargetIds.slice(i, i + unit)
          $.ajax({
            url: "/biz/manager/announce/append_announce_targets",
            type: "POST",
            async: false,
            data: {
              "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
              id: anId,
              ids: JSON.stringify(chunk),
            },
          })
        }
        location.href = "/biz/manager/announce/lists/" + anId
      },
    })
  }
</script>
