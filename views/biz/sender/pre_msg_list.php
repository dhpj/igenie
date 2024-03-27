<div class="temp_type_wrap tl sticky_top">
	<i class="material-icons modal_close" data-dismiss="modal">close</i>
	<ul style="display: inline-block;">
		<li><a href="javascript:open_page_user_lms_msg('1');">저장 메시지</a></li>
		<li class="active"><a href="javascript:open_page_pre_lms_msg('1');">이전 메시지*</a></li>
	</ul>
	<div class="search_wrap" style="display: inline-block; margin-left: 25px;">
	    <select class="select2" id="searchKind">
	        <option value="all">전체</option>
	        <option value="FT">친구</option>
	    </select> 
	    <input type="text" class="" id="searchMsg" name="searchMsg" placeholder="메시지" value=""/>
	    <input type="button" class="btn md dark" id="check" value="조회" onclick="search_msg(1)"/>
	</div>
</div>
<div class="temp_card_wrap">
	<?foreach($list as $r) {?>
	<div class="temp_card">
		<div class="icon_circle"><? switch($r->mst_kind) { case 'at' : echo '알림톡'; break; case 'ft' : echo '친구톡'; if($r->mst_lms_content) { echo '2차문자';} break; case 'MS' : echo '문자'; } ?></div>
		<div class="card_top tl">
			<input type="checkbox" name="preselMsg" class="uniform" value="<?=$r->mst_id?>"> <?=$r->mst_datetime?>
		</div>
		<div class="card_contents" name="name">
			<?=$r->mst_content?>
		</div>
	</div>
	<?}?>
</div>
<div class="sticky_bottom tc" style="margin: 0 auto;"><?echo $page_html?></div>

<script type="text/javascript">
    $( document ).ready(function() {
        $('.card_top input').uniform();
    });

    //수신번호 전체 선택 안되는 현상 수정
    $("#sel_all").click(function(){
        if($("#sel_all").prop("checked")) {
            $("input:checkbox[name='selCustomer']").prop("checked",true);
            $.uniform.update();
        } else {
            $("input:checkbox[name='selCustomer']").prop("checked",false);
            $.uniform.update();
    }
    });
</script>
