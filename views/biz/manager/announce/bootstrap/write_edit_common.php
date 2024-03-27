<script>
  function render_targets(targets) {
    var groupLvs = []
    for (var i = 0; i < targets.length; i++) {
      var lv = targets[i].mem_level;
      if (groupLvs.indexOf(lv) == -1) groupLvs.push(lv)
    }
    groupLvs.sort(function(a, b) { return b - a })
    var $groupList = $("#an_dropdown > ul")
    $groupList.empty()
    $groupList.append(
      groupLvs.map(function(groupLv) {
        var $memList = $("<ul>").addClass("an_dd_mem_list")
        $memList.append(
          targets
            .filter(function(t) {
              return t.mem_level === groupLv
            })
            .sort(function(a, b) {
              return a.mem_username.localeCompare(b.mem_username)
            })
            .map(function(t) {
              return $("<li>")
                .addClass("an_dd_mem")
                .attr("data-mem-id", t.mem_id)
                .append(
                  $("<label>")
                    .addClass("an_dd_mem_checkbox_container checkbox_container")
                    .append(
                      $("<input>")
                        .addClass("an_dd_mem_checkbox")
                        .attr("type", "checkbox")
                        .attr("oninput", "toggle_dd_mem(event)"),
                      $("<span>")
                        .addClass("checkmark"),
                      $("<span>")
                        .addClass("an_dd_mem_username")
                        .text(t.mem_username)
                    )
                )
            })
        )
        return $("<li>")
          .addClass("an_dd_chunk")
          .append(
            $("<header>")
              .addClass("an_dd_chunk_header")
              .append(
                $("<label>")
                  .addClass("checkbox_container")
                  .append(
                    $("<input>")
                      .addClass("an_dd_all_checkbox")
                      .attr("type", "checkbox")
                      .attr("oninput", "toggle_all_dd_mem(event)"),
                    $("<span>")
                      .addClass("checkmark")
                  ),
                $("<label>")
                  .addClass("an_dd_toggle_btn_container checkbox_container")
                  .append(
                    $("<span>")
                      .addClass("an_dd_chunk_title")
                      .text(
                        groupLv == 100 ? "상위 관리자" :
                        groupLv == 50 ? "중간 관리자" :
                        groupLv == 17 ? "영업자" :
                        groupLv == 1 ? "하위 관리자" : ""
                      ),
                    $("<input>")
                      .addClass("an_dd_toggle_btn")
                      .attr("type", "checkbox")
                      .attr("oninput", "toggle_dd_chunk_body(event)"),
                    $("<span>")
                  )
              ),
            $("<main>")
              .addClass("an_dd_chunk_body")
              .hide()
              .append($memList)
          )
      })
    )
    check_targets(checkedTargetIds)
  }
  function search_target(evt) {
    var search = $(evt.target).val()
    if (!search) {
      render_targets(allTargets)
      return
    }
    var filtered = allTargets.filter(function(t) {
      return t.mem_username.toLowerCase().indexOf(search.toLowerCase()) != -1
    })
    render_targets(filtered)
    $(".an_dd_chunk .an_dd_toggle_btn").click()
  }

  function check_targets(targetIds) {
    $(".an_dd_mem_list")
      .children()
      .filter(function(_i, $mem) {
        return targetIds.indexOf($mem.dataset.memId) !== -1
      })
      .find("input")
      .attr("checked", true)
    $(".an_dd_chunk").each(function(_i, $chunk) {
      $chunk = $($chunk)
      var $allCheckbox = $chunk.find(".an_dd_all_checkbox")
      var $mems = $chunk.find(".an_dd_mem_list").children()
      var checkedCount = Array.prototype.filter.call($mems, function($mem) {
        $mem = $($mem)
        return $mem.find(".an_dd_mem_checkbox").is(":checked")
      }).length
      $chunk.attr("data-checked-count", checkedCount)
      if (checkedCount === $mems.length) $allCheckbox.prop("checked", true)
    })
  }

  var $an_content = $("#an_content")
  $an_content.summernote({
    width: 800,
    height: 300,
    focus: true,
  })
  var an_dropdown = document.getElementById("an_dropdown")
  function open_an_dropdown(evt) {
    $(an_dropdown).toggle()
    evt.stopPropagation()
  }
  function toggle_dd_chunk_body(evt) {
    var chunk = $(evt.currentTarget).parents(".an_dd_chunk")
    chunk.find(".an_dd_chunk_body").toggle()
  }
  function toggle_all_dd_mem(evt) {
    var allCheckbox = $(evt.currentTarget)
    var chunk = allCheckbox.parents(".an_dd_chunk")
    var chunkBody = chunk.find(".an_dd_chunk_body")
    var checked = allCheckbox.is(":checked")
    var $mems = chunkBody.find(".an_dd_mem")
    $mems.find("input[type='checkbox']").prop("checked", checked)
    if (checked) {
      Array.prototype.push.apply(
        checkedTargetIds,
        Array.prototype.filter.call($mems, function(mem) {
          return checkedTargetIds.indexOf(mem.dataset.memId) === -1
        })
          .map(function(mem) { return mem.dataset.memId })
      )
    } else {
      $mems.each(function(_i, mem) {
        var memId = mem.dataset.memId
        var i = checkedTargetIds.indexOf(memId)
        if (i !== -1) checkedTargetIds.splice(i, 1)
      })
    }
    chunk[0].dataset.checkedCount = checked ? $mems.length : 0
  }
  function toggle_dd_mem(evt) {
    var checkbox = $(evt.currentTarget)
    var chunk = checkbox.parents(".an_dd_chunk")
    var allCheckbox = chunk.find(".an_dd_all_checkbox")
    var checked = checkbox.is(":checked")
    var chunkData = chunk[0].dataset
    chunkData.checkedCount = chunkData.checkedCount || 0
    var memId = checkbox.closest(".an_dd_mem")[0].dataset.memId
    if (!checked) {
      allCheckbox.prop("checked", false)
      checkedTargetIds.splice(checkedTargetIds.indexOf(memId), 1)
      chunkData.checkedCount--
    } else {
      chunkData.checkedCount++
      var memList = chunk.find(".an_dd_chunk_body > ul")
      if (checkedTargetIds.indexOf(memId) === -1) checkedTargetIds.push(memId)
      if (chunkData.checkedCount == memList.children().length) allCheckbox.prop("checked", true)
    }
  }
  $(window).on("click", function(evt) {
    if (evt.target === an_dropdown || $.contains(an_dropdown, evt.target)) return
    $(an_dropdown).hide()
  })
</script>
