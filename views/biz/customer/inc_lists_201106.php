<?
	$referrer=$this->agent->referrer();
?>
<div class="cus_list_l">
	<div class="cus_list_l_box">
	<button title="그룹을 추가하거나 수정, 삭제하실 수 있습니다." class="btn_cus_add" id="cus_Btn" onClick="cus_modal_open();"><i title="하위등록" class="material-icons">touch_app</i> 그룹추가/관리</button>
  <ul id="myUL">
    <li>
      <span><i class="xi-folder-check"></i> <span<? if($customer_cnt > 0){ //등록수가 있는 경우 ?> onClick="group_search('', '');" style="cursor:pointer;"<? } //if($r->cg_cnt > 0){ //등록수가 있는 경우 ?>>전체고객목록 <span class="cus_cnt"><?=$customer_cnt?></span></span></span>
      <ul class="nested active">
		<?
			$scnt = 0;
			$org_parent_id = -1;
			foreach($customer_group as $r){
				if($org_parent_id != $r->cg_parent_id){
					$cg_gname = $r->cg_gname; //그룹명
					if($gid == $r->cg_id){ //선택된 그룹인 경우
						//$cg_gname = "<b>". $cg_gname ."</b>";
					}
		?>
        <li>
        <? if($r->cg_level == 1 && $r->rowspan > 1){ //1차 그룹 등록수가 있는 경우 ?>
		  <span class="caret caret-down"><i class="xi-folder-check"></i> <span onClick="group_search('<?=$r->cg_level?>', '<?=$r->cg_id?>');"><?=$cg_gname?> <span class="cus_cnt"><?=$r->cg_cnt?></span></span></span><?//1차 그룹?>
		  <? //if($r->rowspan > 1){ ?>
		  <ul class="nested active">
		  <? //} //if($r->rowspan > 1){ ?>
		<? }else if($r->cg_level == 1 && $r->rowspan == 1){ //if($r->cg_level == 1 && $r->rowspan > 1){ //1차 그룹 등록수가 있는 경우 ?>
		  <i class="xi-file-text-o"></i> <span<? if($r->cg_cnt > 0){ //등록수가 있는 경우 ?> onClick="group_search('<?=$r->cg_level?>', '<?=$r->cg_id?>');" style="cursor:pointer;"<? } //if($r->cg_cnt > 0){ //등록수가 있는 경우 ?>><?=$cg_gname?> <span class="cus_cnt"><?=$r->cg_cnt?></span></span></span><?//1차 그룹?>
		<? } //if($r->cg_level == 1 && $r->rowspan > 1){ //1차 그룹 등록수가 있는 경우 ?>
        <?
				} //if($org_parent_id != $r->cg_parent_id){
				if($r->cg_level == 2){ //2차 그룹
		?>
		  <li><i class="xi-file-text-o"></i> <span<? if($r->cg_cnt > 0){ //등록수가 있는 경우 ?> onClick="group_search('<?=$r->cg_level?>', '<?=$r->cg_id?>');" style="cursor:pointer;"<? } //if($r->cg_cnt > 0){ //등록수가 있는 경우 ?>><?=$cg_gname?> <span class="cus_cnt"><?=$r->cg_cnt?></span></span></li><?//2차 그룹?>
		<?
				} //if($r->cg_level == 2){ //2차 그룹
				$scnt++;
				if($scnt >= $r->rowspan){
					$scnt = 0;
		?>
		  <? if($r->rowspan > 1){ ?>
		  </ul>
		  <? } //if($r->rowspan > 1){ ?>
		</li>
		<?
				} //if($scnt >= $r->rowspan){
				$org_parent_id = $r->cg_parent_id;
			} //foreach($customer_group as $r){
		?>
      </ul>
    </li>
  </ul>
</div>
<p class="mg_t10">
	* 상단 [그룹추가/관리] 버튼을 클릭하세요!
</p>
<div class="btn_al_cen">
	<? if($this->member->item("mem_id") != 121) {  ?>
		<button class="btn_st1" onclick="allDelRow();"><span class="material-icons">error_outline</span> 전체 고객 삭제</button>
	<? } ?>
</div>
</div>
<div id="cus_modal" class="cus_modal">
  <!-- Modal content -->
  <div class="cus_modal-content"> <span id="cus_close" class="cus_close">×</span>
    <p class="modal-tit"> 고객그룹관리 </p>
	<ul id="myUL">
		<li><span><i class="xi-folder-check"></i> 전체고객목록</span>
			<button class="btn_cus" onclick="group1_add('1');"><i class="material-icons">add</i>하위등록</button><?//1차 그룹 추가 버튼?>
			<input type="hidden" id="group_cnt" value="">
			<ul class="nested active" id="ul_group_list">
				<li>
					<i class="xi-file-text-o"></i>
					<input type="text" placeholder="1차 그룹명" value="">
					<button class="btn_cus" onclick=""><i class="material-icons">add</i>하위등록</button>
					<button class="btn_cus" onclick=""><i class="material-icons">done</i>수정</button>
					<button class="btn_cus" onclick=""><i class="material-icons">clear</i>삭제</button>
				</li>
				<li>
					<span class="caret"><i class="xi-folder-check"></i></span>
					<input type="text" placeholder="1차 그룹명" value="">
					<button class="btn_cus" onclick=""><i class="material-icons">add</i>하위등록</button>
					<button class="btn_cus" onclick=""><i class="material-icons">done</i>수정</button>
					<button class="btn_cus" onclick=""><i class="material-icons">clear</i>삭제</button>
					<ul class="nested">
						<li>
							<i class="xi-file-text-o"></i>
							<input type="text" placeholder="2차 그룹명" value="">
							<button class="btn_cus" onclick=""><i class="material-icons">done</i>수정</button>
							<button class="btn_cus" onclick=""><i class="material-icons">clear</i>삭제</button>
						</li>
						<li>
							<i class="xi-file-text-o"></i>
							<input type="text" placeholder="2차 그룹명" value="">
							<button class="btn_cus" onclick=""><i class="material-icons">done</i>수정</button>
							<button class="btn_cus" onclick=""><i class="material-icons">clear</i>삭제</button>
						</li>
						<li>
							<i class="xi-file-text-o"></i>
							<input type="text" placeholder="2차 그룹명" value="">
							<button class="btn_cus" onclick=""><i class="material-icons">done</i>수정</button>
							<button class="btn_cus" onclick=""><i class="material-icons">clear</i>삭제</button>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
  </div>
</div>
<script>
	// Get the modal
	var cus_modal = document.getElementById("cus_modal");

	// Get the <span> element that closes the modal
	var cus_close = document.getElementById("cus_close");

	//그룹관리 모달창 열기
	function cus_modal_open(){
		$.ajax({
			url: "/biz/customer/ajax_group_list",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				count = json.count;
				//alert("count : "+ count);
				var html = "";
				var no = 0;
				var scnt = 0;
				var org_parent_id = "-1";
				$.each(json.list, function(key, value){
					no++; //순번
					var cg_id = value.cg_id; //그룹번호
					var cg_parent_id = value.cg_parent_id; //부모번호
					var cg_level = value.cg_level; //레벨
					var cg_gname = value.cg_gname; //그룹명
					var cg_cnt = value.cg_cnt; //고객수
					var rowspan = value.rowspan; //라인수
					if(org_parent_id != cg_parent_id){
						html += '<input type="hidden" id="group_rn_'+ cg_id +'" value="'+ rowspan +'">';
						html += '<li id="li_cg_id_'+ cg_id +'">';
						//<span class="caret"><i class="xi-folder-check"></i></span>
						//<span class="caret caret-down"><i class="xi-folder-check"></i></span>
						if(rowspan > 1){ //하위 그룹이 있는 경우
							html += '  <span id="sp_cg_id_'+ cg_id +'"><span class="*caret caret-down"><i class="xi-folder-check"></i></span></span>';
						}else{ //하위 그룹이 없는 경우
							html += '  <span id="sp_cg_id_'+ cg_id +'"><i class="xi-file-text-o"></i></span>';
						}
						html += '	<input type="text" id="group1_nm_'+ no +'" placeholder="1차 그룹명" value="'+ cg_gname +'">';
						html += '	<button class="btn_cus" onClick="group_save(\'1\', \'group1_nm_'+ no +'\', \''+ cg_parent_id +'\', \''+ cg_id +'\');"><i class="material-icons">done</i>수정</button>';
						html += '	<button class="btn_cus" onClick="group_del(\'1\', \'li_cg_id_'+ cg_id +'\', \''+ cg_cnt +'\', \''+ cg_id +'\');"><i class="material-icons">clear</i>삭제</button>';
						html += '	<button class="btn_cus" onClick="group2_add(\''+ cg_id +'\', \''+ cg_parent_id +'\');"><i class="material-icons">add</i>하위등록</button>';
						if(rowspan > 1){ //하위 그룹이 있는 경우
							html += '	<ul class="nested active" id="ul_cg_id_'+ cg_id +'">';
						}
					} //if(org_parent_id != cg_parent_id){
					if(cg_level == 2){ //2차 그룹
						html += '	  <li id="li_sub_cg_id_'+ cg_id +'">';
						html += '	    <i class="xi-file-text-o"></i>';
						html += '	    <input type="text" id="group2_nm_'+ no +'" placeholder="2차 그룹명" value="'+ cg_gname +'">';
						html += '	    <button class="btn_cus" onClick="group_save(\'2\', \'group2_nm_'+ no +'\', \''+ cg_parent_id +'\', \''+ cg_id +'\');"><i class="material-icons">done</i>수정</button>';
						html += '	    <button class="btn_cus" onClick="group_del(\'2\', \'li_sub_cg_id_'+ cg_id +'\', \''+ cg_cnt +'\', \''+ cg_id +'\');"><i class="material-icons">clear</i>삭제</button>';
						html += '	  </li>';
					} //if(cg_level == 2){ //2차 그룹
					scnt++;
					if(scnt >= rowspan){
						scnt = 0;
						if(rowspan > 1){ //하위 그룹이 있는 경우
							html += '	</ul>';
						} //if(rowspan > 1){ //하위 그룹이 있는 경우
						html += '</li>';
					}
					org_parent_id = cg_parent_id;
				});
				//$("#ul_group_list").append(html);
				$("#ul_group_list").html(html);
				$("#group_cnt").val(no);
			}
		});
		cus_modal.style.display = "block";
	}

	// When the user clicks on <span> (x), close the modal
	cus_close.onclick = function() {
		cus_modal.style.display = "none";
		location.reload();
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == cus_modal) {
			cus_modal.style.display = "none";
			location.reload();
		}
	}

	//cus_modal_open(); //그룹관리 모달창 열기

	//그룹 1차 하위그룹 추가
	function group1_add(glv){
		var no = Number($("#group_cnt").val())+1;
		var html = '';
		html += '<li id="li_group_'+ no +'"><i class="xi-file-text-o"></i>';
		html += '	<input type="text" id="group'+ glv +'_name_'+ no +'" placeholder="1차 그룹명" value="">';
		html += '	<button class="btn_cus" onClick="group_save(\''+ glv +'\', \'group'+ glv +'_name_'+ no +'\', \'\', \'\');"><i class="material-icons">done</i>추가</button>';
		html += '	<button class="btn_cus" onClick="id_remove(\'li_group_'+ no +'\');"><i class="material-icons">clear</i>취소</button>';
		html += '</li>';
		//$("#ul_group_list").append(html);
		$("#ul_group_list").prepend(html);
		$("#group_cnt").val(no);
		$("#group"+ glv +"_name_"+ no).focus();
	}

	//그룹 2차 하위그룹 추가
	function group2_add(cg_id, pid){
		var rn = Number($("#group_rn_"+ cg_id).val());
		var no = rn+1;
		var id = cg_id +'_'+ no;
		var html = '';
		if(rn == 1){ //하위그룹이 없는 경우
			$("#sp_cg_id_"+ cg_id).html('<span class="caret caret-down"><i class="xi-folder-check"></i></span>');
			html += '	<ul class="nested active" id="ul_cg_id_'+ cg_id +'">';
		}
		html += '	  <li id="li_sub_group_'+ id +'">';
		html += '	    <i class="xi-file-text-o"></i>';
		html += '	    <input type="text" id="group2_name_'+ id +'" placeholder="2차 그룹명" value="">';
		html += '	    <button class="btn_cus" onClick="group_save(\'2\', \'group2_name_'+ id +'\', \''+ pid +'\', \'\');""><i class="material-icons">done</i>추가</button>';
		html += '	    <button class="btn_cus" onClick="id_remove(\'li_sub_group_'+ id +'\');"><i class="material-icons">clear</i>취소</button>';
		html += '	  </li>';
		if(rn == 1){ //하위그룹이 없는 경우
			html += ' </ul>';
			$("#li_cg_id_"+ cg_id).append(html);
		}else{
			$("#ul_cg_id_"+ cg_id).append(html);
		}
		$("#group_rn_"+ cg_id).val(no);
		$("#group2_name_"+ id).focus();
	}

	//그룹 추가/수정
	function group_save(glv, id, pid, gid){
		//alert("glv : "+ glv +", id : "+ id +", pid : "+ pid +", gid : "+ gid); return;
		var gnm = $("#"+ id).val().trim(); //그룹명
		var regExp = /[\{\}\/?.,;:|*~`!^\+<>@\#$%&\\\=\'\"]/gi;
		if(regExp.test(gnm)){
			gnm = gnm.replace(regExp, "");
			$("#"+ id).val(gnm);
		}
		//alert("gnm : "+ gnm); return;
		if(gnm == ""){
			alert(glv +"차 그룹명을 입력하세요.");
			$("#"+ id).focus();
			return;
		}else{
			//alert("glv : "+ glv +", gnm : "+ gnm +", pid : "+ pid +", gid : "+ gid); return;
			$.ajax({
				url: "/biz/customer/group_save",
				type: "POST",
				data: {"glv":glv, "gnm":gnm, "pid":pid, "gid":gid, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					if(gid != ""){ //수정의 경우
						alert("성공적으로 "+ glv +"차 그룹명이 수정 되었습니다.");
					}else{
						alert("성공적으로 "+ glv +"차 그룹명이 추가 되었습니다.");
						cus_modal_open(); //그룹관리 모달창 열기
					}
					return;
				}
			});
		}
	}

	//그룹 추가/수정
	function group_del(glv, id, cnt, gid){
		//alert("gid : "+ gid); return;
		//alert("glv : "+ glv +", id : "+ id +", cnt : "+ cnt +", gid : "+ gid);
		var msg = "해당 그룹을 삭제하 시겠습니까?"
		if(cnt > 0){
			msg = "해당 그룹에 소속된 고객 "+ cnt +"명이 있습니다.\n"+ msg;
		}
		if(!confirm(msg)){
			return;
		}
		$.ajax({
			url: "/biz/customer/group_del",
			type: "POST",
			data: {"glv":glv, "gid":gid, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(glv == 1){
					id_remove(id); //영역 삭제
				}else{
					cus_modal_open(); //그룹관리 모달창 열기
				}
				alert("성공적으로 삭제 되었습니다.");
				return;
			}
		});
	}

	//영역 삭제
	function id_remove(id){
		$("#"+ id).remove();
	}
</script>
<script>
	var toggler = document.getElementsByClassName("caret");
	var i;
	for (i = 0; i < toggler.length; i++) {
		toggler[i].addEventListener("click", function() {
			this.parentElement.querySelector(".nested").classList.toggle("active");
			this.classList.toggle("caret-down");
		});
	}
</script>
<div class="cus_list_r table_list">
	<table cellpadding="0" cellspacing="0" border="0">
	    <colgroup>
	        <col width="40px"><?//체크?>
	        <col width="80px"><?//등록일?>
	        <col width="210px"><?//그룹?>
	        <col width="70px"><?//고객명?>
	        <col width="100px"><?//전화번호?>
	        <col width="90px"><?//수신여부?>
	        <col width="*"><?//주소?>
	        <col width="120px"><?//메모?>
			<?if(strpos($referrer, 'customer/lists')!==false) {?>
	        <col width="50px"><?//수정?>
			<?}?>
	    </colgroup>
		<thead>
			<tr>
		        <th>
					<label class="checkbox_container">
						<input type="checkbox" name="sel_all" id="sel_all">
						<span class="checkmark"></span>
					</label>
		        </th><?//체크?>
		        <th>등록일</th>
		        <th>그룹</th>
		        <th>고객명</th>
		        <th>전화번호</th>
		        <th>수신여부</th>
		        <!-- 추가수정 수신거부, 메모 기능(메모,수정 타이틀 추가) -->
		        <th>주소</th>
		        <th>메모</th>
				<?if(strpos($referrer, 'customer/lists')!==false) {?>
				<th>수정</th>
				<?}?>
			</tr>
		</thead>
		<tbody class="tbody">
			<?
				foreach($list as $r) {
					// 수신자 전화번호 국번 별표 처리
					$gname = $r->gname; //그룹명1##그룹번호1@@그룹명2##그룹번호2
					if($gname != ""){
						$exp1 = explode("@@", $gname);
						for($ii = 0; $ii < count($exp1); $ii++){
							$str1 = $exp1[$ii];
							$exp2 = explode("##", $str1);
							$gid = $exp2[1]; //그룹번호
							$gnm = $exp2[0] .' <button title="선택고객을 해당그룹에서 삭제합니다." class="btn_cus_del" onClick="cus_group_remove(\''. $r->ab_id .'\', \''. $gid .'\');"><i class="material-icons">clear</i>그룹삭제</button>'; //그룹명 삭제버튼
							if($ii == 0){
								$gname = $gnm;
							}else{
								$gname .= "<br>". $gnm;
							}
						}
					}
					$ab_name_ori = $r->ab_name; //고객명
					$ab_tel_ori = $r->ab_tel; //전화번호
					$ab_tel = "";
					$ab_name = "";
					if (strlen($ab_tel_ori) > 10) {
						$ab_tel = substr($ab_tel_ori, 0, 3)."****".substr($ab_tel_ori, 7);
					} else {
						$ab_tel = substr($ab_tel_ori, 0, 3)."***".substr($ab_tel_ori, 6);
					}
					$ab_name_count = mb_strlen("$ab_name_ori", "UTF-8");
					if ($ab_name_count > 3) {
						//$ab_name = substr($ab_name_ori, 0, 1)."**".substr($ab_name_ori, strlen($ab_name_ori) - 1);
						$tempStr = "";
						for($strCount = 1; $strCount <= $ab_name_count - 2; $strCount++) {
							$tempStr .= "*";
						}
						$ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8").$tempStr.iconv_substr($ab_name_ori, $ab_name_count - 1, 1, "utf-8");
					} else if ($ab_name_count == 3) {
						//$ab_name = substr($ab_name_ori, 0, 1)."*".substr($ab_name_ori, 2);
						$ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8")."*".iconv_substr($ab_name_ori, 2, 1, "utf-8");
					} else if ($ab_name_count == 2) {
						$ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8")."*";
					} else if ($ab_name_count == 1){
						$ab_name = "*";
					}
			?>
			<tr>
                <td>
					<label class="checkbox_container">
						<input type="checkbox" id="selCustomer" name="selCustomer" value="<?=$r->ab_id?>" class="chk">
						<span class="checkmark"></span>
					</label>
				</td><?//체크?>
                <td title="<?=$r->ab_datetime?>"><?=$r->ab_date?></td><?//등록일?>
                <td name="kind" style="text-align:left;"><?=$gname?></td><?//그룹?>
                <td name="name"><?=$ab_name?></td><?//고객명?>
                <td name="phn"><?=$ab_tel?></td><?//전화번호?>
                <!-- 추가 수정 수신거부, 메모 기능(드롭박스로 변경, 인풋 추가, 수정버튼 생성) -->
                <td>
	                <select id="status_box" name="status_box" <?=(strpos($referrer, 'customer/lists')===false) ? 'disabled' : ''?>>
						<option value="none" <?=($r->ab_status=='1') ? 'selected' :''?>>정상</option>
						<option value="reject" <?=($r->ab_status!='1') ? 'selected' :''?>>거부</option>
					</select>
				</td><?//수신여부?>
				<td><input type="text" id="addr" name="addr" style="width: 100%;" value="<?=$r->ab_addr?>" onkeyup="chkword_addr(this);" <?=(strpos($referrer, 'customer/lists')===false) ? 'disabled' : ''?>></td><?//주소?>
				<td><input type="text" id="memo" name="memo" style="width: 100%;" value="<?=$r->ab_memo?>" onkeyup="chkword_memo(this);" <?=(strpos($referrer, 'customer/lists')===false) ? 'disabled' : ''?>></td><?//메모?>
				<?if(strpos($referrer, 'customer/lists')!==false) {?>
				<td><input type="hidden" name="customer_id" id="customer_id" value="<?=$r->ab_id?>"><button class="btn sm" style="padding: 0 6px;" onclick="customer_modify(this);"><i class="icon-pencil"></i> 수정</button></td><?//수정?>
				<?}?>
            </tr>
			<?
				} //foreach($list as $r) {
			?>
		</tbody>
	</table>
	<?if(strpos($referrer, 'customer/lists')!==false) {?>
	<div class="mg_t20" style="width:100%; display:inline-block;">
		<button class="btn_st1 fr" onclick="selectDelRow(1);"><span class="material-icons">person_remove</span> 선택 고객 삭제</button>
	</div>
	<?}?>
	<div class="page_cen"><?echo $page_html?></div>
</div>
<style>
    .text-center {
        vertical-align: middle !important;
    }
</style>
<script type="text/javascript">
	/*
    $( document ).ready(function() {
        $('tr input').uniform();
    });

    $('tbody tr').click(function() {
        var check = $(this).find('.checker').children('span').is('.checked');
        if(check == true) {
            $(this).find('td input[type="checkbox"]').prop('checked', true);
            $(this).addClass('checked');
        } else {
            $(this).find('td input[type="checkbox"]').prop('checked', false);
            $(this).removeClass('checked');
        }
        $.uniform.update();
    });
	*/

    //수신번호 전체 선택 안되는 현상 수정
	/*$("#sel_all").click(function(){
		if($("#sel_all").prop("checked")) {
			$("input:checkbox[name='selCustomer']").prop("checked",true);
			$.uniform.update();
		} else {
			$("input:checkbox[name='selCustomer']").prop("checked",false);
			$.uniform.update();
		}
	});*/

	//전체선택
	$("#sel_all").click(function(){
		//alert("sel_all");
		var chk = $(this).is(":checked");//.attr('checked');
		//alert("chk : "+ chk);
		if(chk) $(".tbody .chk").prop('checked', true);
		else  $(".tbody .chk").prop('checked', false);
	});

	//소속그룹 삭제
	function cus_group_remove(abid, gid){
		//alert("cus_group_remove("+ abid +", "+ gid +")");
		if(!confirm("소속된 그룹을 삭제 하시겠습니까?")){
			return;
		}
		$.ajax({
			url: "/biz/customer/cus_group_remove",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				abid: abid,
				gid: gid
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				if(!json['success']) {
					//$(".content").html("저장중 오류가 발생하였습니다.");
					//$("#myModal").modal('show');
					alert("삭제중 오류가 발생하였습니다.");
				}else{
					alert("정상적으로 삭제 되었습니다.");
					open_page(1);
				}
			},
			error: function (data, status, er) {
				//$(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
				//$("#myModal").modal('show');
				alert("처리중 오류가 발생하였습니다.\n관리자에게 문의해주시기 바랍니다.");
			}
		});
	}
</script>
