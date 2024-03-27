<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu16.php');
?>
<div id="mArticle" class="card_wrap cardlist">
  <div class="form_section">
    <div class="inner_tit">
      <h3>카드정산 (<?=number_format($total_rows)?>건)</h3>
    </div>
    <div class="white_box mg_t20">
      <div class="mall_box_top">
          <label for="userid">소속</label>
          <select name="userid" id="userid" <?//class="selectpicker"?> data-live-search="true" onChange="open_page(1);">
              <option value="ALL">- ALL -</option>
          <?foreach($users as $r) {?>
              <option value="<?=$r->mem_id?>" <?=($userid==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
          <?}?>
          </select>
          <label for="username">업체명</label>
          <select name="username" id="username" <?//class="selectpicker"?> data-live-search="true" onChange="open_page(1);">
              <option value="ALL">- ALL -</option>
          <?foreach($username as $r) {?>
              <option value="<?=$r->mem_id?>" <?=($nameid==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?></option>
          <?}?>
          </select>
        <div class="row_label">기간</div>
        <div class="row_contents">
          <input type="text" id="start_date" class="calendar hasDatepicker" value="<?=$start_date?>" placeholder="검색 시작일" style="width:110px;"> ~
          <input type="text" id="end_date" class="calendar hasDatepicker" value="<?=$end_date?>" placeholder="검색 종료일" style="width:110px;">
        </div>
      </div>
      <div class="row_wrap">
        <div class="row_label">상세검색</div>
        <div class="row_contents">
          <select id="sc">
            <option value="1" <? if($sc == '1') { echo 'selected'; }?>>업체명</option>
          </select>
          <input type="text" id="sv" value="<?=$sv?>" style="margin-left:5px;" placeholder="검색내용" onkeypress="javascript:if(event.keyCode==13) { open_page(1); }">
          <button class="btn md dark" style="margin-left:5px;cursor:pointer;" onclick="open_page(1)">검색</button>
        </div>
      </div>
    </div>
    <div class="white_box mg_t20">
      <div class="widget-content" id="append_list">
        <div class="table_top">
        <?if($this->member->item('mem_level') >= 150){?>
            <div class="fr">
                <button class="btn md excel" onclick="download3()">전체 엑셀 다운로드</button>
            </div>
        <?}?>
          <div class="fr">
              <button class="btn md excel" onclick="download()">선택 엑셀 다운로드</button>
          </div>
        <?if($this->member->item('mem_level') >= 150){?>
          <div class="fr">
              <button class="btn md" onclick='set_cal("Y")'>정산</button>
          </div>
          <div class="fr">
              <button class="btn md excel" onclick="download2()">명세서</button>
          </div>
          <div class="fr">
              <button class="btn md excel" onclick="download4()">세금계산서</button>
          </div>
        <?}?>
        </div>
        <table class="order">
            <colgroup>
                <col width="2%"><?//체크박스?>
                <col width="5%"><?//주문번호?>
                <col width="*"><?//마트명?>
                <col width="5%"><?//구매자명?>
                <col width="7%"><?//결제일?>
                <col width="6%"><?//정산일?>
                <col width="6%"><?//결제금액?>
                <col width="6%"><?//수수료?>
                <col width="6%"><?//부가가치세?>
                <col width="6%"><?//정산받은금액?>
            <?if($this->member->item('mem_level') >= 150){?>
                <col width="6%"><?//전체수수료?>
            <?}?>
                <col width="6%"><?//업체정산금액?>
            <?if($this->member->item('mem_level') >= 150){?>
                <col width="6%"><?//관리자정산?>
            <?}?>
                <col width="6%"><?//수익금액?>
            <?if($this->member->item('mem_level') < 150){?>
                <col width="4%"><?//카드사?>
                <col width="6%"><?//카드번호?>
                <col width="4%"><?//카드형태?>
            <?}?>
            <?if($this->member->item('mem_level') >= 150){?>
                <col width="3%"><?//정산?>
            <?}?>
            </colgroup>
          <thead>
            <input type="hidden" id="idGoodsViewFl" value="CLOSE">
            <tr>
                <th class="align-center" width="30">
                    <?if($this->member->item('mem_level') >= 150){?>
                    <label class="checkbox_container">
                        <input type="checkbox" name="check_all" id="row_check_all" onclick="chk_all(this);">
                        <span class="checkmark"></span>
                    </label>
                    <?}?>
                </th>
                <th>주문번호</th>
                <th>업체명</th>
                <th>구매자명</th>
                <th>결제 및 부분취소</th>
                <th>정산일</th>
                <th>결제금액</th>
                <th>수수료</th>
                <th>부가가치세</th>
                <th>정산받은금액</th>
            <?if($this->member->item('mem_level') >= 150){?>
                <th>전체수수료(%)</th>
            <?}?>
                <th>업체정산금액</th>
            <?if($this->member->item('mem_level') >= 150){?>
                <th>관리자정산</th><?//관리자정산?>
            <?}?>
                <th>수익금액</th>
            <?if($this->member->item('mem_level') < 150){?>
                <th>카드사</th>
                <th>카드번호</th>
                <th>카드형태</th>
            <?}?>
            <?if($this->member->item('mem_level') >= 150){?>
                <th>정산</th>
            <?}?>
            </tr>
          </thead>
          <tbody class="tbody">
  <?
        if (!empty($list)){
            $card_fee;
            $cnt = 1;
            $flag2 = false;
            $trAmt = 0;
            $fee = 0;
            $vat = 0;
            $depositAmt = 0;
            $a_trAmt = 0;
            $a_fee = 0;
            $a_vat = 0;
            $a_afee = 0;
            $a_depositAmt = 0;
            $a_c_depositAmt = 0;
            $a_s_depositAmt = 0;
            $a_rev = 0;
            $a_ex_feevat = 0;
            foreach($list as $key => $a){
                $card_fee = strtotime($a->create_datetime) >= strtotime('2023-09-01 00:00:00') ? 2.75 : 2.64;
                $a_trAmt += $a->trAmt;
                $a_fee += $a->fee;
                $a_vat += $a->vat;
                $a_afee += round($a->trAmt / 100 * $a->dhn_fee) + $a->fee + $a->vat;
                if ($a->pc != '3'){
                    $a_depositAmt += $a->depositAmt;
                    $a_c_depositAmt += $a->depositAmt - round($a->trAmt * $a->dhn_fee / 100);
                } else {
                    $a_ex_feevat += round($a->trAmt / 100 * $card_fee);
                    $a_depositAmt += $a->trAmt - ($a->trAmt / 100 * $card_fee);
                    $a_c_depositAmt += $a->trAmt - round($a->trAmt / 100 * $a->dhn_fee) - $a->fee - $a->vat;
                }
                $a_s_depositAmt += round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100);
                if ($a->pc != '3'){
                    $a_rev += round($a->trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100);
                } else {
                    $a_rev += round(($a->trAmt - ($a->trAmt / 100 * $card_fee)) - (($a->trAmt - ($a->trAmt / 100 * $card_fee)) - ($a->trAmt / 100 * ($a->dhn_fee))));
                }

                $flag4 = false;
                $p_cal_flag;

                if (!empty($a->cardname)){
                    $trAmt = $a->trAmt;
                    $fee = $a->fee;
                    $vat = $a->vat;
                    $depositAmt = $a->depositAmt;
                }

                $flag = false;
                $flag3 = false;
                if ($list[$key-1]->id == $list[$key]->id){
                    $flag = true;
                    $trAmt += $a->trAmt;
                    $fee += $a->fee;
                    $vat += $a->vat;
                    $depositAmt += $a->depositAmt;
                } else if ($list[$key+1]->id == $list[$key]->id){
                    $flag = true;
                    $cnt++;
                }
                if ($list[$key+1]->id != $list[$key]->id){
                    $flag2 = true;
                }
                if ($list[$key-1]->id != $list[$key]->id){
                    $flag3 = true;
                    $p_cal_flag = $a->cal_flag;
                    $p_tid = $a->tid;
                }

                if ($list[$key-1]->id != $list[$key]->id && $list[$key+1]->id != $list[$key]->id){
                    $flag4 = true;
                }
  ?>
                <tr style='<?=$flag ? 'background:#FFCCCC' : ''?>'>
                    <td class="align-center">
                        <?if($a->cal_flag == 'N' && $flag4 && $this->member->item('mem_level') >= 150){?>
                        <label class="checkbox_container">
                            <input type="checkbox" class='row_check' name="row_check" value="<?=$a->tid?>" data-rid='<?=$a->rid?>'>
                            <span class="checkmark"></span>
                        </label>
                        <?}?>
                    </td>
                    <td><?=$a->orderno?></td>
                    <td><?=$flag3 ? $a->mem_username : ''?></td>
                    <td><?=$flag3 ? $a->receiver : ''?></td>
                    <td><?=$a->create_datetime?></td>
                    <td><?=!empty($a->settlmntDt) ? $a->settlmntDt : '예정'?></td>
                    <td><?=!empty($a->trAmt) ? number_format($a->trAmt) . '원' : ''?></td>
                    <td><?=!empty($a->fee) ? number_format($a->fee) . '원' : ''?></td>
                    <td><?=!empty($a->vat) ? number_format($a->vat) . '원' : ''?></td>
                    <td><?=!empty($a->depositAmt) ? number_format($a->depositAmt) . '원' : ''?></td>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td><?=!empty($a->trAmt) ? number_format(round($a->trAmt / 100 * ($card_fee + $a->dhn_fee))) . '원(' . ($card_fee + $a->dhn_fee) . ')' : ''?></td>
                <?}?>
                    <td><?=$flag ? '' : number_format($a->depositAmt - round($a->trAmt * $a->dhn_fee / 100)) . '원'?></td>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td>
                        <?=$flag ? '' : number_format($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) . '원'?>
                        <?='<br>(' . (empty($a->rec_mem_username) ? '' : $a->rec_mem_username) . ')'?>
                    </td>
                    <td><?=$flag ? '' : number_format(round($a->trAmt / 100 * $a->dhn_fee)) . '원'?></td>
                <?}else{?>
                    <td><?=$flag ? '' : number_format($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) . '원'?></td>
                <?}?>
                <?if($this->member->item('mem_level') < 150){?>
                    <td><?=$a->cardname?></td>
                    <td><?=$a->cardno?></td>
                    <td><?=$a->cardtype?></td>
                <?}?>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td><?=$flag4 ? $a->cal_flag == 'Y' ? 'O' : 'X' : ''?></td>
                <?}?>
                <tr>
  <?
                if ($cnt > 1 && $flag2){
  ?>
                <tr style='<?=$flag ? 'background:#FFCC99' : ''?>'>
                    <td class="align-center">
                        <?if ($p_cal_flag == 'N' && $trAmt > 0){?>
                        <label class="checkbox_container">
                            <input type="checkbox" class='row_check' name="row_check" value="<?=$p_tid?>">
                            <span class="checkmark"></span>
                        </label>
                        <?}?>
                    </td>
                    <td><?=$a->id?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?=number_format($trAmt) . '원'?></td>
                    <td><?=number_format($fee) . '원'?></td>
                    <td><?=$trAmt > 0 ? number_format($vat) . '원' : '0원'?></td>
                    <td><?=$trAmt > 0 ? number_format($depositAmt) . '원' : '0원'?></td>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td></td>
                <?}?>
                    <td><?=$trAmt > 0 ? number_format($depositAmt - round($trAmt * $a->dhn_fee / 100)) . '원' : '0원'?></td>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td><?=$trAmt > 0 ? number_format($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) . '원' : '0원'?></td>
                    <td><?=$trAmt > 0 ? number_format($trAmt * ($a->dhn_fee > config_item('FEE') ? config_item('FEE') : $a->dhn_fee) / 100) . '원' : '0원'?></td>
                <?}else{?>
                    <td><?=$trAmt > 0 ? number_format($a->trAmt * ($a->dhn_fee > config_item('FEE') ? ($a->dhn_fee - config_item('FEE')) : 0) / 100) . '원' : '0원'?></td>
                <?}?>
                <?if($this->member->item('mem_level') < 150){?>
                    <td></td>
                    <td></td>
                    <td></td>
                <?}?>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td><?=$trAmt > 0 ? $a->cal_flag == 'Y' ? 'O' : 'X' : ''?></td>
                <?}?>
                <tr>
  <?
                    $cnt = 1;
                    $flag2 = false;
                }
            }
  ?>
                <tr style='background:#CCFFFF'>
                    <td colspan=6>-합계-</td>
                    <td><?=number_format($a_trAmt) . '원'?></td>
                    <td colspan=2><?=number_format($a_fee + $a_vat + $a_ex_feevat) . '원'?></td>
                    <!-- <td><?=number_format($a_vat) . '원'?></td> -->
                    <td><?=number_format($a_depositAmt) . '원'?></td>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td><?=number_format($a_afee) . '원'?></td>
                <?}?>
                    <td><?=number_format($a_c_depositAmt) . '원'?></td>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td><?=number_format($a_s_depositAmt) . '원'?></td>
                    <td><?=number_format($a_rev) . '원'?></td>
                <?}else{?>
                    <td><?=number_format($a_s_depositAmt) . '원'?></td>
                <?}?>
                <?if($this->member->item('mem_level') < 150){?>
                    <td></td>
                    <td></td>
                    <td></td>
                <?}?>
                <?if($this->member->item('mem_level') >= 150){?>
                    <td></td>
                <?}?>
                <tr>
  <?
        }
  ?>
          </tbody>
        </table>
        <!-- <div class="page_cen"><?=$page_html?></div> -->
      </div>
    </div>
  </div>
</div>
<script>
	//기간 시작일자 클리시
	$("#start_date").datepicker({
		format: "yyyy-mm-dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});

	//기간 종료일자 클리시
	$("#end_date").datepicker({
		format: "yyyy-mm-dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		endDate: "-0d",
		autoclose: true
	});

	$(function() {
		$(".onchange_search").change(function(){
			open_page(1);
		});
	});


	$(function(){
	    $("#check_all").click(function(){ //전체선택
	        var chk = $(this).is(":checked");//.attr('checked');
	        if(chk) $(".tbody .chk").prop('checked', true);
	        else  $(".tbody .chk").prop('checked', false);
	    });
	});

	//검색
	function open_page(page){
		var url = "?page="+ page; //현재 페이지
		if($("#per_page").val() != "") url += "&per_page="+ $("#per_page").val(); //목록수
		if($("#start_date").val() != "") url += "&start_date="+ $("#start_date").val(); //시작일
		if($("#end_date").val() != "") url += "&end_date="+ $("#end_date").val(); //종료일
		if($("#sc").val() != "" && $("#sv").val() != "") url += "&sc="+ $("#sc").val() + "&sv="+ $("#sv").val(); //검색내용
        if ($("#userid").val() != 'ALL') url += '&userid=' + $("#userid").val();
        if ($("#username").val() != 'ALL') url += '&nameid=' + $("#username").val();
		location.href = url;
    }

	//선택 엑셀 다운로드
	function download(){
        var arr_rid = new Array();
        $.each($('.row_check'), function(idx, val){
            if ($(this).is(':checked')){
                arr_rid.push($(this).data('rid'));
            }
        });
        if (arr_rid.length <= 0){
            alert('체크를 해주세요.');
            return;
        }

        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/card/cldownload");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "start_date");
        field.setAttribute("value", $("#start_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "end_date");
        field.setAttribute("value", $("#end_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sc");
        field.setAttribute("value", $("#sc").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sv");
        field.setAttribute("value", $("#sv").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "userid");
        field.setAttribute("value", $("#userid").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "nameid");
        field.setAttribute("value", $("#username").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "arr_rid[]");
        field.setAttribute("value", arr_rid);
        form.appendChild(field);

        form.submit();
    }

    //전체 엑셀 다운로드
	function download3(){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/card/cldownload3");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "start_date");
        field.setAttribute("value", $("#start_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "end_date");
        field.setAttribute("value", $("#end_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sc");
        field.setAttribute("value", $("#sc").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sv");
        field.setAttribute("value", $("#sv").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "userid");
        field.setAttribute("value", $("#userid").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "nameid");
        field.setAttribute("value", $("#username").val());
        form.appendChild(field);

        form.submit();
    }

    //세금계산서
	function download4(){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/card/cldownload4");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "start_date");
        field.setAttribute("value", $("#start_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "end_date");
        field.setAttribute("value", $("#end_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sc");
        field.setAttribute("value", $("#sc").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sv");
        field.setAttribute("value", $("#sv").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "userid");
        field.setAttribute("value", $("#userid").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "nameid");
        field.setAttribute("value", $("#username").val());
        form.appendChild(field);

        form.submit();
    }

    //엑셀 다운로드
	function download2(){
        var arr_rid = new Array();
        $.each($('.row_check'), function(idx, val){
            if ($(this).is(':checked')){
                arr_rid.push($(this).data('rid'));
            }
        });
        if (arr_rid.length <= 0){
            alert('체크를 해주세요.');
            return;
        }

        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/card/cldownload2");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "start_date");
        field.setAttribute("value", $("#start_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "end_date");
        field.setAttribute("value", $("#end_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sc");
        field.setAttribute("value", $("#sc").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sv");
        field.setAttribute("value", $("#sv").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "userid");
        field.setAttribute("value", $("#userid").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "nameid");
        field.setAttribute("value", $("#username").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "arr_rid[]");
        field.setAttribute("value", arr_rid);
        form.appendChild(field);

        form.submit();
    }

    function set_cal(flag){
        var arr_tid = new Array();
        $.each($('.row_check'), function(idx, val){
            if ($(this).is(':checked')){
                arr_tid.push($(this).val());
            }
        });
        if (arr_tid.length <= 0){
            alert('체크를 해주세요.');
            return;
        }
        $.ajax({
            url: "/card/set_cal",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , arr_tid : arr_tid
              , flag : flag
            },
            success: function (json) {
                location.reload();
            },
        });
    }

    function chk_all(t){
        var flag = $(t).is(':checked');
        $.each($('.row_check'), function(idx, val){
            $(this).prop('checked', flag);
        });
    }
</script>
