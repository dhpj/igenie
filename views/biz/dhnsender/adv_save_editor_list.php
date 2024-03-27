<div class="temp_type_wrap tl" style="margin-left: 25px;">
	<div class="search_wrap" style="display: inline-block; margin-left: 0px;">
	    <input type="text" class="" id="searchEditor" name="searchEditor" placeholder="메시지" value=""/ onKeypress="if(event.keyCode==13){ search_editor(); }">
	    <input type="button" class="btn md dark" id="check" value="조회" onclick="search_editor();"/>
	</div>
</div>
<div class="temp_card_wrap">
	<?foreach($list as $r) {?>
	<div class="temp_card2" style="cursor: default;">
		<!--<div class="icon_circle">알림톡</div>-->
		<div class="card_top tl">
			에디터
			<?=$r->creation_date?>
		</div>
		<div class="card_contents" style="height: 220px; cursor:pointer;" onclick="include_editor('<?=$r->idx?>');">
            <?=$r->html?>
		</div>
		<!-- <div class="card_btn">
			<span><button type="button" name="code" onclick="include_editor('<?=$r->idx?>');"><i class="xi-plus-circle-o"></i> 불러오기</button></span>
		</div> -->
	</div>
	<?}?>
</div>
<div class="tc" style="margin: 0 auto;"><?echo $page_html?></div>
