<div class="temp_type_wrap tl" style="margin-left: 25px;">
	<ul style="display: inline-block;">
		<li class="active"><a href="javascript:open_page_user();">저장 메시지</a></li>
		<li><a href="javascript:open_page_pre();">발송 메시지</a></li>
	</ul>
	<div class="search_wrap" style="display: inline-block; margin-left: 10px;">
	    <select class="select2" id="searchKind" style="display:none;">
	        <option value="all">전체</option>
	        <option value="FT">친구</option>
	    </select>
	    <input type="text" class="" id="searchMsg" name="searchMsg" placeholder="메시지" value="<?=$param["search_msg"]?>" onKeypress="if(event.keyCode==13){ search_msg('U'); }">
	    <input type="button" class="btn md dark" id="check" value="조회" onclick="search_msg('U');"/>
	</div>
</div>
<div class="temp_card_wrap">
	<?foreach($list as $r) {?>
	<div class="temp_card2" style="cursor: default;">
		<!--div class="icon_circle"><?=$r->msg_kind?><? echo ($r->msg_type == "TEMP" ? "" :"");?></div-->
		<!--<div class="icon_circle"><?=($r->msg_kind == "FT") ? "친구톡" : $r->msg_kind?><? echo ($r->msg_type == "TEMP" ? "(임시)" :"");?></div>-->
		<div class="card_top tl">
			<? if($r->msg_kind == "AT"){ ?>
			알림톡<? if($r->msg){ ?>+2차문자<? } ?>
			<? }else if($r->msg_kind == "FT"){ ?>
			친구톡<? if($r->msg){ ?>+2차문자<? } ?>
			<? }else{ ?>
			문자
			<? } ?>
			<?=$r->reg_dt?>
		</div>
		<div class="card_contents" style="height: 170px;">
			<?
				$msg = "";
				if($r->msg_kind == "FT"){ //친구톡
					if($r->kakao_img_filename != ""){
						$msg .= "<img src='". $r->kakao_img_filename ."' width='50%'><br>";
					}
					$msg .= $r->kakao_msg;
				}else{ //알림톡, 문자
					$msg .= $r->msg;
				}
				if($param["search_msg"] != ""){
					$msg = str_replace($param["search_msg"], "<font color='red'>". $param["search_msg"] ."</font>", $msg); //검색내용 빨간색 처리
				}
			?>
			<?=nl2br($msg)?>
		</div>
		<div class="card_btn">
			<span><button type="button" name="code" onclick="set_lms_by_user_msg('<?=$r->msg_id?>', 'U');"><i class="xi-plus-circle-o"></i> 불러오기</button></button></span>
			<span><button type="button" name="code" onclick="sel_delete_msg('<?=$r->msg_id?>', 'U')"><i class="xi-close-circle-o"></i> 선택삭제</button></span>
		</div>
	</div>
	<?}?>
</div>
<div class="tc" style="margin: 0 auto;"><?echo $page_html?></div>
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
