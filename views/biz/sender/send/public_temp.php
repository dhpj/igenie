<div class="temp_type_wrap">
	<ul>
		<li><a href="javascript:open_user_temp()">나의 템플릿</a></li>
		<li class="active"><a href="javascript:open_public_temp()">공용 템플릿</a></li>
		<? // 2019.08.08 쿠폰 관련 실사용시 if문 제거, if문 hmtl만 사용.
		if($this->member->item("mem_id") == 3) { ?>
		<!--<li><a href="javascript:open_couponlist()">쿠폰</a></li>-->
		<? } ?>
	</ul>
</div>
<div class="modal_alim_cate">
	<?if($group_cnt < 2) { ?>
    <!-- select name="group_key" id="group_key" style="display: none"-->
    <select name="group_key" id="group_key" disabled>
    <?} else { ?>
    <select name="group_key" id="group_key">
    <?}
    foreach($group_list as $r) {?>
        <option value="<?=$r->spg_p_pf_key?>" <?=($param['group_key']==$r->spg_p_pf_key) ? 'selected' : ''?>><?=$r->spf_friend?></option>
    <?}?>
    </select>
    
    <?if($part_cnt < 1) { ?>
    <div class="checks" style="display: none;">
    <?} else { ?>
    <div class="alim_checks">
    <?} ?>
		<input type="radio" id="part_id_all" name="part_id" value="ALL" <?=($param['part_id']=="ALL") ? 'checked' : ''?>><label for="part_id_all">전체</label><? $partRowNo = 0;
	foreach($part_list as $r) { 
	    $partRowNo += 1;
	?>	
		<!-- input type="radio" id="part_id_1" name="part_id" checked><label for="part_id_1">환경청소년부</label-->
		<input type="radio" id="part_id_<?=$partRowNo ?>" name="part_id" value="<?=$r->tp_part_id ?>" <?=($param['part_id']==$r->tp_part_id) ? 'checked' : ''?>><label for="part_id_<?=$partRowNo ?>"><?=$r->tp_part_name?></label>
	<?} ?>
	</div>
</div>
<? if($total_count > 0) { ?>
<div class="temp_card_wrap" id="temp">
	<? foreach($list as $r) {
	    $num++; ?>
    <input type="hidden" name="pf_key" class="pf_key" value="<?=$r->tpl_profile_key?>"/>
    <input type="hidden" name="tpl_id" class="tpl_id" value="<?=$r->tpl_id?>" />
	<div class="temp_card" onclick="select_temp('<?=$r->tpl_id?>', '<?=$r->tpl_profile_key?>', '')">
		<div class="icon_circle<?=($r->tpl_status == "S" ? " off" :"")?>"><?=$this->funn->setColorLabel($r->tpl_status)?></div>
		<div class="card_top">
			<?=$r->tpl_name?>
		</div>
		<div class="card_contents">
			<?=str_replace("\n", "<br />", $r->tpl_contents)?>
		</div>
		<?
			$rj = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($r->tpl_button, TRUE)), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($rj as $key => $val) {
				if( !is_array($val) && $key == "name" && $val != "" ){ //버튼명
		?>
		<div class="card_bottom">
			<?=$val?>
			<span class="material-icons fr">chevron_right</span>
		</div>
		<?
				}
			}
		?>
	</div>
	<?}?>
</div>
    <?} else { ?>
    	<h2>발송 가능한 템플릿이 없습니다.</h2>
    <?} ?>
<div style="margin-top: -25px">
<?echo $page_html?>
</div>
<script type="text/javascript">
	$("#template_list tbody tr").click(function() {
	  $("#template_list tbody tr").removeClass('active');
	  $(this).addClass('active');
	  // console.log('click');
	});

    $("#group_key").change(function() {
        $("#groupChange").val("Y");
        open_page(1);
    });
    
//     $("#part_id").change(function() {
//     	open_page(1);
// 		});

	$('input[name="part_id"]').change(function() {
		open_page(1);
	});
	
	function open_page(page) {
		var group_key = $('#group_key').val();
		var part_id = $('input[name="part_id"]:checked').val();
		var groupChange = $('#groupChange').val();
		
		$('#template_select .widget-content').html('').load(
		  "/biz/sender/send/public_temp",
		  {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"group_key": group_key,
				"part_id": part_id,
				"groupChange" : groupChange,
				'page': page
		  },
		  function () {
				$(window).scrollTop(0);
				$('.uniform').uniform();
				$('select.select2').select2();
		  }
		);
	}

    function open_user_temp () {
		$('#template_select .widget-content').html('').load(
				"/biz/sender/send/template",
				{
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"page": 1
				},
				function() {
					//$('#template_select').css({"overflow-y": "scroll"});
				}
			);
    }	
    
    function open_couponlist () {
		$('#template_select .widget-content').html('').load(
				"/biz/sender/send/couponlist",
				{
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"page": 1
				},
				function() {
					//$('#template_select').css({"overflow-y": "scroll"});
				}
			);
    }		    

    function select_temp(selected,selected_profile) {
        
        iscoupon = $("#couponwrite").val();
		nft_value = $("#NFTVALUE").val();
		
        var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
            if(iscoupon == undefined) {
                if (nft_value == undefined) {
			    	form.setAttribute("action", "/biz/sender/send/talk");
                } else if (nft_value == "NFT") {
                	form.setAttribute("action", "/biz/sender/send/talk/nft_talk");
                } else {
                	form.setAttribute("action", "/biz/sender/send/talk");
                }
            } else {
			    form.setAttribute("action", "/biz/coupon/write");                
            }

            var csrfField = document.createElement("input");
			csrfField.setAttribute("type", "hidden");
			csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(csrfField);

			var selectedField = document.createElement("input");
			selectedField.setAttribute("type", "hidden");
			selectedField.setAttribute("name", "tmp_code");
			selectedField.setAttribute("value", selected);
			form.appendChild(selectedField);
			
            var selProfileField = document.createElement("input");
			selProfileField.setAttribute("type", "hidden");
			selProfileField.setAttribute("name", "tmp_profile");
			selProfileField.setAttribute("value", selected_profile);
			form.appendChild(selProfileField);
			
            var selProfileField = document.createElement("input");
			selProfileField.setAttribute("type", "hidden");
			selProfileField.setAttribute("name", "iscoupon");
			selProfileField.setAttribute("value", iscoupon);
			form.appendChild(selProfileField);
			
            var nft_ = document.createElement("input");
			nft_.setAttribute("type", "hidden");
			nft_.setAttribute("name", "nft");
			nft_.setAttribute("value", nft_value);
			form.appendChild(nft_);

			form.submit();
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
