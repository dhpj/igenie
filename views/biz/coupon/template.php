<div class="snb_nav">
	<ul class="clear">
		<li class="active"><a href="#">나의 템플릿</a></li>
		<li><a href="javascript:open_public_temp()">공용 템플릿</a></li>
	</ul>
    <div align="left" style="overflow-y:scroll;padding:-180px; height: 390px; width: 100% !important;">
        <table class="table table-bordered table-highlight-head scrolltbody" style="overflow-y:hidden;width:100% !important;">
            <thead>
            <tr style="cursor:default;">
                <th width="120">업체명</th>
                <th width="120">템플릿코드</th>
                <th width="120">템플릿명</th>
                <th width="">템플릿 내용</th>
                <th width="100">템플릿상태</th>
            </tr>
            </thead>
            <tbody class="table-content" style="cursor: pointer;">
            <?$num=0;
    		  foreach($list as $r) {$num++;?>
                <tr align="center" name="select">
                    <td class="text"><?=$r->tpl_company?><br/>(<?=$r->spf_friend?>)<input type="hidden" name="pf_key" class="pf_key" value="<?=$r->tpl_profile_key?>" /></td>
                    <td class="text tmpl_code"><?=$r->tpl_code?><input type="hidden" name="tpl_id" class="tpl_id" value="<?=$r->tpl_id?>" /></td>
                    <td class="text tmpl_name"><?=$r->tpl_name?><input id="<?=$num?>" type="hidden" value="<?=str_replace("\n", "<br />", str_replace('"', '', $r->tpl_contents))?>"></td>
    					 <td class="text tmpl_contents" style="vertical-align:middle !important;"><?=str_replace("\n", "<br />", cut_str($r->tpl_contents, 47))?></td>
                    <td class="text tmpl_status"><?=$this->funn->setColorLabel($r->tpl_status)?></td>
                </tr>
            <?}?>
            </tbody>
        </table>
    </div>
</div>
	
	<div class="pagination align-center mt30"><?echo $page_html?></div>

<script type="text/javascript">
	$("#template_list tbody tr").click(function() {
	  $("#template_list tbody tr").removeClass('active');
	  $(this).addClass('active');
	  // console.log('click');
	});

	function open_page(page) {
		var type = $('#searchType2').val();
		var searchFor = $('#searchStr2').val();
		$('#template_select .widget-content').html('').load(
		  "/biz/sender/send/template",
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

    function open_public_temp () {
		$('#template_select .widget-content').html('').load(
				"/biz/sender/send/public_temp",
				{
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"page": 1
				},
				function() {
					//$('#template_select').css({"overflow-y": "scroll"});
				}
			);
    }		
</script>

<style type="text/css">
    #template_list tbody tr.active td{
        background-color: rgb(77, 116, 150);
    }


    #template_list tbody tr.active:hover{
        background-color: rgb(77, 116, 150) !important;
    }

</style>
