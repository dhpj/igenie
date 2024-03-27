<div align="left" style="overflow-y:scroll;padding:-180px; height: 390px; width: 100% !important;">
    <table class="table table-bordered table-highlight-head scrolltbody" style="overflow-y:hidden;width:100% !important;">
        <thead>
        <tr style="cursor:default;">
            <th width="100">업체명</th>
            <th width="100">프로필 키</th>
        </tr>
        </thead>
        <tbody class="table-content" style="cursor: pointer;">
        <?foreach($list as $row) {?>
            <tr align="center" name="select">
                <td class="text" width="181"><?=$row->spf_company?><br/>(<?=$row->spf_friend?>)</td>
                <td class="text pf_key" width="171"><?=$row->spf_key?></td>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>

<div class="pagination align-center mt30"><?echo $page_html?></div>


<script type="text/javascript">
	$("#profile_list tbody tr").click(function() {
	  $("#profile_list tbody tr").removeClass('active');
	  $(this).addClass('active');
	  // console.log('click');
	});
	function open_page(page) {
		var type = $('#searchType2').val();
		var searchFor = $('#searchStr2').val();
		$('#profile_select .widget-content').html('').load(
		  "/biz/sender/send/profile",
		  {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_type": type,
				"search_for": searchFor,
				'page': page
		  },
		  function () {
				$(window).scrollTop(0);
				$('.uniform').uniform();
				$('select.select2').select2();
		  }
		);
	}
</script>

<style type="text/css">
    #profile_list tbody tr.active td{
        background-color: rgb(77, 116, 150);
    }


    #profile_list tbody tr.active:hover{
        background-color: rgb(77, 116, 150) !important;
    }

</style>
