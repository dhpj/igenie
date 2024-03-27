<link href="/views/biz/manager/announce/bootstrap/css/style.css" rel="stylesheet">
<link href="/views/biz/manager/announce/bootstrap/css/modal.css" rel="stylesheet">

<div id="an_targets_modal" class="an_wrap" style='display:none;'>
  <div class="modal_white_box" style='width: 350px; height: 450px;'>
    <main>
      <input id="an_target_search" placeholder="업체명 입력" oninput="search_an_target(event)">
      <ul id="an_targets"></ul>
    </main>
    <button class="modal_close" onclick='close_targets_modal()'><i class="xi-close"></i></button>
  </div>
</div>

<script>
  var targetsData = {<?
    foreach ($announces as $an) {
      echo '"'.$an->an_id.'": ['.$an->an_targets.'],';
    }
  ?>}
  function search_an_target(evt) {
    var search = $("#an_target_search").val()
    var firstMatch = $(".an_target_chunk_items > li").filter(function(_i, item) {
      return $(item).text().toLowerCase().indexOf(search.toLowerCase()) !== -1
    })[0]
    if (firstMatch) firstMatch.scrollIntoView()
  }
  function set_target_in_modal(targetId, cb) {
    $.ajax({
      url: "/biz/manager/announce/get_targets",
      type: "POST",
      data: {
        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
        id: targetId,
      },
      success: function(chunks) {
        $("#an_targets")
          .empty()
          .append(
            Object.keys(chunks)
              .sort(function(a, b) { return b - a })
              .map(function(lv) {
                return $("<li>")
                  .addClass("an_target_chunk")
                  .append(
                    $("<h2>")
                      .text(
                        lv == 100 ? "상위 관리자" :
                        lv == 50 ? "중간 관리자" :
                        lv == 17 ? "영업자" :
                        lv == 1 ? "하위 관리자" : ''
                      ),
                    $("<ul>")
                      .addClass("an_target_chunk_items")
                      .append(
                        chunks[lv].map(function(mem) {
                          return $("<li>").text(mem.name)
                        })
                      )
                  )
              })
          )
        cb()
      },
    })
  }
  function open_targets_modal() {
    $("#an_targets_modal").show();
  }
  function close_targets_modal(){
    $("#an_targets_modal").hide();
  }
</script>
