<style>
    ul#temp {
        padding:1px;
        list-style:none;
        width:800px;
        height:100%;
    }
    ul#temp li {
        float:left;
        margin:0;
        padding:0;
    }
    ul#temp li ul {
        position: relative;
        width: 230px;
        margin: 25px 5px 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        background: #fff;
    }
    ul#temp li ul .temptitle {
        width: 100%;
        height: 36px;
        line-height: 36px;
        text-align: center;
        background: #fae100;
        border-radius: 5px 5px 0 0;
    }
    ul#temp li ul .tempcont {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border-style: solid;
        border-color: gainsboro;
    }
</style>
<div class="snb_nav" style="height: 520px;">
	<ul class="clear" style="width:800px; border-bottom-style: ridge;">
		<li ><a href="javascript:open_user_temp()">나의 템플릿</a></li>
		<li class="active"><a href="#">공용 템플릿</a></li>
		<? // 2019.08.08 쿠폰 관련 실사용시 if문 제거, if문 hmtl만 사용.
		if($this->member->item("mem_id") == 3) { ?>
		<!-- li><a href="javascript:open_couponlist()">쿠폰</a></li-->
		<? } ?>
	</ul>
    <div align="left" style="width: 100% !important;">
        <ul id="temp">
            <? foreach($list as $r) {
                $num++; ?>
            <li>
                <input type="hidden" name="pf_key" class="pf_key" value="<?=$r->tpl_profile_key?>"/>
                <input type="hidden" name="tpl_id" class="tpl_id" value="<?=$r->tpl_id?>" />
               <ul>
                    <li class="temptitle" style="cursor: pointer;" onclick="select_temp('<?=$r->tpl_id?>', '<?=$r->tpl_profile_key?>', '')"><?=$r->tpl_name?> <?=$this->funn->setColorLabel($r->tpl_status)?> </li>
                    <li class="tempcont">
                        <div style="width:100%;height:150px;overflow:auto">
                        <?=str_replace("\n", "<br />", $r->tpl_contents)?>
                        </div>
                    </li>
               </ul> 
            </li>
            <?}?>
        </ul>
    </div>
</div>

	<div class="align-center"><?echo $page_html?></div>

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
		  "/dhnbiz/sender/send/public_temp",
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

    function open_user_temp () {
		$('#template_select .widget-content').html('').load(
				"/dhnbiz/sender/send/template",
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
				"/dhnbiz/sender/send/couponlist",
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
			    	form.setAttribute("action", "/dhnbiz/sender/send/talk");
                } else if (nft_value == "NFT") {
                	form.setAttribute("action", "/dhnbiz/sender/send/talk/nft_talk");
                } else {
                	form.setAttribute("action", "/dhnbiz/sender/send/talk");
                }
            } else {
			    form.setAttribute("action", "/dhnbiz/coupon/write");                
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
