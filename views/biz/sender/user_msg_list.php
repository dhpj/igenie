<div class="temp_type_wrap tl">
	<ul style="display: inline-block;">
		<li class="active"><a href="javascript:open_page_user_lms_msg('1');">저장 메시지</a></li>
		<li><a href="javascript:open_page_pre_lms_msg('1');">이전 메시지</a></li>
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
		<!--div class="icon_circle"><?=$r->msg_kind?><? echo ($r->msg_type == "TEMP" ? "" :"");?></div-->
		<div class="icon_circle"><?=$r->msg_kind?><? echo ($r->msg_type == "TEMP" ? "(임시)" :"");?></div>
		<div class="card_top tl">
			<input type="checkbox" name="selMsg" class="uniform" style="vertical-align: middle;" value="<?=$r->msg_id?>" ><?=$r->reg_date?>
		</div>
		<div class="card_contents" style="height: 180px;">
			<?=$r->msg?>
		</div>
	</div>
	<?}?>
</div>
<div class="tc" style="margin: 0 auto;"><?echo $page_html?></div>
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
