<button onclick="status_templet()">템플릿상태</button>

<script>
    function status_templet(){
        $.ajax({
            url: "/dev_test/status_templet",
            type: "POST",
            data: {
                '<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>',
               },
            success: function (json) {
            },
        });
    }
</script>
