<script>
  function send_announce(id) {
    if (!confirm("발송하시겠습니까?")) return
    
    $.ajax({
      url: "/biz/manager/announce/send_announce",
      type: "POST",
      data: {
        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
        id: id,
      },
      success: function() {
        alert("정상적으로 발송했습니다.")
        location.reload()
      }
    })
  }
  function delete_announce(id, cb) {
    if (!confirm("삭제하시겠습니까?")) return

    $.ajax({
      url: "/biz/manager/announce/delete_announce",
      type: "POST",
      data: {
        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
        id: id,
      },
      success: function() {
        alert("정상적으로 삭제했습니다.")
        cb()
      },
    })
  }
</script>
