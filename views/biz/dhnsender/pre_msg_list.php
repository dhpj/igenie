<div class="temp_type_wrap tl" style="margin-left: 25px;">
	<ul style="display: inline-block;">
		<li><a href="javascript:open_page_user();">저장 메시지</a></li>
		<li class="active"><a href="javascript:open_page_pre();">발송 메시지</a></li>
	</ul>
	<div class="search_wrap" style="display: inline-block; margin-left: 10px;">
	    <select class="select2" id="searchKind" style="display:none;">
	        <option value="all">전체</option>
	        <option value="FT">친구</option>
	    </select>
	    <input type="text" class="" id="searchMsg" name="searchMsg" placeholder="메시지" value="<?=$param["search_msg"]?>" onKeypress="if(event.keyCode==13){ search_msg('P'); }">
	    <input type="button" class="btn md dark" id="check" value="조회" onclick="search_msg('P');"/>
	</div>
</div>
<div class="temp_card_wrap">
	<?foreach($list as $r) {?>
	<div class="temp_card2" style="cursor: default;">
		<!--<div class="icon_circle">
		<? //switch($r->mst_kind) { case 'at' : echo '알림톡'; break; case 'ft' : echo '친구톡'; if($r->mst_lms_content) { echo '<br>2차문자';} break; case 'MS' : echo '문자'; } ?>
		</div>-->
		<div class="card_top tl">
			<? if($r->mst_kind == "at"){ ?>알림톡<? }else if($r->mst_kind == "ft"){ ?>친구톡<? }else if($r->mst_kind == "MS"){ ?>문자<? } ?><? if($r->mst_lms_content){ ?>+2차문자<? } ?>
			<?=$r->mst_dt?>
		</div>
		<div class="card_contents" name="name" style="height: 170px;">
			<?
				$mst_content = "";
				if($r->mst_kind == "ft" and $r->mst_img_content != ""){ //친구톡
					$mst_content .= "<img src='". $r->mst_img_content ."' width='50%'><br>";
				}
				$mst_content .= $r->mst_content;
				if($param["search_msg"] != ""){
					$mst_content = str_replace($param["search_msg"], "<font color='red'>". $param["search_msg"] ."</font>", $mst_content); //검색내용 빨간색 처리
				}
			?>
			<?=nl2br($mst_content)?>
		</div>
		<div class="card_btn">
			<span><button type="button" name="code" onclick="set_lms_by_user_msg('<?=$r->mst_id?>', 'P');"><i class="xi-plus-circle-o"></i> 불러오기</button></button></span>
			<span><button type="button" name="code" onclick="sel_delete_msg('<?=$r->mst_id?>', 'P')"><i class="xi-close-circle-o"></i> 선택삭제</button></span>
		</div>
	</div>
	<?}?>
</div>
<div class="sticky_bottom tc" style="margin: 0 auto;"><?echo $page_html?></div>

<script type="text/javascript">
    $( document ).ready(function() {
        //$('.card_top input').uniform();
    });

    //수신번호 전체 선택 안되는 현상 수정
    $("#sel_all").click(function(){
        if($("#sel_all").prop("checked")) {
            $("input:checkbox[name='selCustomer']").prop("checked",true);
            //$.uniform.update();
        } else {
            $("input:checkbox[name='selCustomer']").prop("checked",false);
            //$.uniform.update();
		}
    });

	//저장 메시지
	function open_page_user(){
		$('#searchMsg').val('');
		open_page_user_lms_msg('1');
	}

	//발송 메시지
	function open_page_pre(){
		$('#searchMsg').val('');
		open_page_pre_lms_msg('1');
	}
</script>
