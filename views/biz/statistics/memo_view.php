<div class="snb_nav">
	<div align="left" style="width: 100% !important;">
        <div class="table_list" id="memo_table">
        	<table>
    		    <colgroup>
    		        <col width="150px">
    		        <col width="*">
    		    </colgroup>
    		    <thead>
    				<tr>
    					<th>등록일시</th>
    					<th>메모내용</th>
    				</tr>
    			</thead>
    			<tbody>
    			<? foreach($pop_mome_list as $r) { 
    			    $memo = str_replace(array("\r\n","\r","\n"),'<br>', $r->sh_text); 
    			    //$memo = $r->sh_text;
    			    ?>
    				<tr>
    					<td><?=$r->sh_reg_date ?></td>
    					<td class="tl"><?=$memo?></td>
    				</tr>
    			<? } ?>
    			</tbody>
        	</table>
        </div>
	</div>
</div>
<div class="align-center mt30"><?=$page_html?></div>

<script type="text/javascript">
	function open_page(page) {
		var mem_id = $("#pf_key").val();
		
		$('#modal_memo_all_list .widget-content').html('').load(
		  "/biz/statistics/memo_view",
		  {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
						"mem_id": mem_id, "memo_kind":"H", "page": page
		  },
		  function () {
				$(window).scrollTop(0);
		  }
		);
	}

</script>