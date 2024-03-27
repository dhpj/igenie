<div class="temp_type_wrap tl" style="margin-left: 25px;">
	<div class="search_wrap" style="display: inline-block; margin-left: 0px;">
	    <input type="text" class="" id="searchMsg" name="searchMsg" placeholder="메시지" value=""/ onKeypress="if(event.keyCode==13){ search_msg(); }">
	    <input type="button" class="btn md dark" id="check" value="조회" onclick="search_msg();"/>
	</div>
</div>
<div class="temp_card_wrap">
	<?foreach($list as $r) {?>
	<div class="temp_card2" style="cursor: default;">
		<!--<div class="icon_circle">알림톡</div>-->
		<div class="card_top tl">
			알림톡<?=($r->lms_msg != "") ? "+2차문자" : ""?>
			<?=$r->reg_dt?>
		</div>
		<div class="card_contents" style="height: 170px;">
			<?
				//알림톡 내용
				$msg = $r->temp_cont;
				if($param["search_msg"] != ""){
					$msg = str_replace($param["search_msg"], "<font color='red'>". $param["search_msg"] ."</font>", $msg); //검색내용 빨간색 처리
				}

				//버튼정보
				$tpl_button = "";
				$tpl_button_arr = json_decode($r->tpl_button);
				$tpl_button_cnt = 0;
				if(!empty($tpl_button_arr)){
					$tpl_button .= "<div style=\"margin-top: 4px;\">";
					foreach ($tpl_button_arr as $arr) {
						$tpl_button_cnt++;
						$tpl_button .= "<p class=\"tem_btn\">". $arr->name ."</p>";
					}
					$tpl_button .= "</div>";
				}
			?>
			<?=nl2br($msg)?>
			<?=$tpl_button?>
		</div>
		<div class="card_btn">
			<span><button type="button" name="code" onclick="include_msg('<?=$r->id?>');"><i class="xi-plus-circle-o"></i> 불러오기</button></button></span>
			<span><button type="button" name="code" onclick="sel_delete_msg('<?=$r->id?>')"><i class="xi-close-circle-o"></i> 선택삭제</button></span>
		</div>
	</div>
	<?}?>
</div>
<div class="tc" style="margin: 0 auto;"><?echo $page_html?></div>