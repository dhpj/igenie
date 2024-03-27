<div class="tit_wrap">
	이벤트 상세내역
</div>
<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>상세내역 (전체 <b style="color: red"><?=number_format($total_rows)?></b>건)</h3>
      <button class="btn md fr mt10" onclick="location.href='/spop/evemaker/lists';"><i class="xi-list"></i> 목록으로</button>
    </div>
    <div class="inner_content">
			<button class="btn md fl excel" onclick="download();">엑셀 다운로드</button>
      <div class="search_wrap fr">
        <select id="search_state" onchange="open_page(1);">
          <option value="">- 상태전체 -</option>
          <option value="0"<?=($search_state == "0") ? " Selected" : ""?>>발송완료</option>
          <option value="1"<?=($search_state == "1") ? " Selected" : ""?>>이벤트 확인</option>
          <option value="2"<?=($search_state == "2") ? " Selected" : ""?>>이벤트 참여</option>
          <option value="3"<?=($search_state == "3") ? " Selected" : ""?>>상품수령</option>
        </select>
        <input type="text" id="search_phn" value="<?=$search_phn?>" style="margin-left:5px;" placeholder="전화번호를 입력해 주세요" onkeypress="if(event.keyCode==13){ open_page(1); }">
        <button type="button" class="btn md" style="margin-left:5px;" onclick="open_page(1)">검색</button>
      </div>
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" border="0">
          <colgroup>
            <col width="8%">
            <col width="*">
            <col width="8%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="15%">
            <col width="10%">
          </colgroup>
          <thead>
            <tr>
              <th>No</th>
              <th>전화번호</th>
              <th>상태</th>
              <th>발송일자</th>
              <th>이벤트 확인</th>
              <th>이벤트 참여</th>
              <th>상품수령</th>
              <th>당첨상품</th>
              <th>직원확인</th>
            </tr>
          </thead>
          <tbody>
			<?
				$i = 0;
				if(!empty($list)){
					foreach($list as $r){
						$num = $total_rows-($perpage*($page-1))-$i; //순번
						$esd_phn = $this->funn->format_phone($r->esd_phn, "-"); //전화번호
						$esd_state = $r->esd_state; //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
						$esd_cre_date = $r->esd_cre_date; //발송일자
						$esd_view_date = ""; //확인일자
                        $esd_eve_date = ""; //수령일자
						$esd_goods_date = ""; //수령일자
                        $backcolor = "";
						if($esd_state == "0"){
							$esd_state = "발송완료";
						}else{
                            if(!empty($r->esd_view_date)){
                                $esd_view_date = $r->esd_view_date; //확인일자
                            }
                            if(!empty($r->esd_eve_date)){
                                $esd_eve_date = $r->esd_eve_date;
                            }
                            if(!empty($r->esd_goods_date)){
                                $esd_goods_date = $r->esd_goods_date;
                            }
							if($esd_state == "1"){
								$esd_state = "이벤트 확인";
                                $backcolor = " style='background-color:#fdf7bc;'";
							}else if($esd_state == "2"){
								$esd_state = "이벤트 참여";
                                $backcolor = " style='background-color:#c6f4fa;'";
							}else if($esd_state == "3"){
								$esd_state = "상품수령";
                                $backcolor = " style='background-color:#ffd9d9;'";
							}
						}
			?>
			<tr>
				<td><?=$num //No?></td>
				<td><?=$esd_phn //전화번호?></td>
				<td <?=$backcolor?>><?=$esd_state //상태?></td>
				<td><?=$esd_cre_date //확인일자?></td>
				<td><?=$esd_view_date //확인일자?></td>
				<td><?=$esd_eve_date ?></td>
                <td><?=$esd_goods_date ?></td>
                <td><?=$r->esd_emg_name ?></td>
                <td <?=($r->esd_state=="3")? " style='background-color:#efefef;'" : "" ?>>
                    <? if($r->esd_state=="2"&&$r->esd_emg_name!="꽝"){ ?>
                        <button type="button" class="md" onclick="set_state('<?=$r->esd_code?>')">확인하기</button>
                    <? } ?>
                    <? if($r->esd_state=="3"){ ?>
                        확인완료
                    <? } ?>
                </td>
			</tr>
			<?
						$i++;
					}
				}else{
			?>
			<tr>
				<td colspan="6">no data.</td>
			</tr>
			<?
				}
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="align-center mt30"><?=$page_html?></div>
</div>
<script>
	//검색
	function open_page(page) {
		var url = "?page="+ page;
		var search_phn = $("#search_phn").val(); //연락처 검색
		if(search_phn != "") url += "&search_phn="+ search_phn;
		var search_state = $("#search_state").val(); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색
		if(search_state != "") url += "&search_state="+ search_state;
		location.href = url;
	}

    function set_state(id){
        if(id==""){
            alert("잘못된 접근입니다.");
        }else{
            $.ajax({
                url: "/smart/reward_confirm",
                type: "POST",
                async: false,
                data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                        // ,"emd_id":"<?=$data->emd_id?>"
                        ,"code":id
                },
                success: function (json) {
                    // alert(json.msg);
                    if(json.code==0){
                        alert("상태가 변경되었습니다.");
                        location.reload();
                    }else{
                        alert("확인을 완료하지 못했습니다.");
                    }
                    //history.go(0);
                    //$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
                }
            });
        }
    }

	//엑셀 다운로드
	function download() {
		var url = "/spop/evemaker/download?eveid=<?=$eveid?>";
		var search_phn = $("#search_phn").val(); //연락처 검색
		if(search_phn != "") url += "&search_phn="+ search_phn;
		var search_state = $("#search_state").val(); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색
		if(search_state != "") url += "&search_state="+ search_state;
		location.href = url;
	}
</script>
