<div class="table_list">
    <table>
    	<colgroup>
    		<col width="*"><?//소속?>
    		<col width="8%"><?//가입일?>
    		<col width="13%"><?//계정관리?>
    		<col width="8%"><?//계정?>
    		<col width="14%"><?//업체명?>
    		<col width="10%"><?//잔액?>
    		<col width="10%"><?//담당자 이름?>
    		<col width="10%"><?//담당자 연락처?>
    		<col width="5%"><?//2nd?>
    		<? if($this->member->item('mem_level') >= '50') { ?>
    			<col width="155px"><?//고객등록?>
    		<? } ?>
    	</colgroup>
    	<thead>
    	<tr>
    		<th>소속</th>
    		<th>가입일</th>
    		<th>계정관리</th>
    		<th>계정</th>
    		<th>업체명</th>
    		<th>잔액(예약 예상 차감)</th>
    		<th>담당자 이름</th>
    		<th>담당자 연락처</th>
    		<th>2nd</th>
    		<? if($this->member->item('mem_level') >= '50') { ?>
    			<th>고객등록</th>
    		<? } ?>
    		<th>휴면상태</th> <!-- 2021.12.30 조지영 휴면계정 노출 작업 -->
    	</tr>
    	</thead>
    	<tbody>
    		<?
    			$offer = "";
    			foreach($rs as $row) {
    		?>
    		<tr>
    			<td><?if($offer==$row->mrg_recommend_mem_id) { echo '&nbsp;'; } else { echo $row->mrg_recommend_mem_username; $offer=$row->mrg_recommend_mem_id; }?></td><?//소속?>
    			<td><?=substr($row->mem_register_datetime, 0, 10)?></td><?//가입일?>
    			<td>
    				<input type="button" style="height:25px;" class="btn_userinsert" value="계정수정" onclick="document.location.href='/biz/partner/view?<?=$row->mem_userid?>'"/>
    				<?if($this->member->item("mem_level")>=10) {?>
    				<input type="button" style="height:25px;margin-left:5px;" class="btn_userchange" value="계정전환" onclick="document.location.href='/biz/main?<?=$row->mem_id?>'"/><?//계정전환?>
    				<?}?>
    			</td><?//계정관리?>
    			<td><?=$row->mem_userid?></td><?//계정?>
    			<td><?=$row->mem_username?></td><?//업체명?>
    			<td class="text-right"><? if ($row->mem_pay_type == 'A') { ?>후불제<? } else { ?><?=number_format($this->Biz_model->getTotalDeposit($row->mem_userid))."(".number_format($this->Biz_dhn_model->getReservedCoin($row->mem_id)).")"?>원<? } ?></td><?//잔액?>
    			<td><?=$row->mem_nickname?></td><?//담당자 이름?>
    			<td><?//=$row->mem_phone?><?=$this->funn->format_phone($row->mem_emp_phone,"-")?></td><?//담당자 연락처?>
    			<td><? switch($row->mem_2nd_send) { case 'GREEN_SHOT' : echo '웹(A)'; break; case 'NASELF':echo '웹(B)'; break; case 'SMART':echo '웹(C)'; break; default:echo '';} ?></td><?//2nd?>
    			<? if($this->member->item('mem_level') >= '50') { ?>
    				<td>
    					<button type="button" class="btn_st_small" id="btn_upload<?=$row->mem_id?>" onclick="event.cancelBubble=true; btn_upload_click('<?=$row->mem_id?>', '<?=$row->mem_userid?>');" value="0">고객등록</button>
    					<button type="button" class="btn_st_small" id="btn_upload<?=$row->mem_id?>" onclick="event.cancelBubble=true; btn_delete_click('<?=$row->mem_id?>', '<?=$row->mem_userid?>');" value="0" style="margin-left:5px;">고객삭제</button>
    					<input type="hidden" id="real<?=$row->mem_id?>" value="0">
    				</td>
    			<? } ?>
    			<td><?=!empty($row->mem_id) ? ($row->mdd_dormant_flag == 1 ? "휴면" : "정상" ): "정상" ?></td>
    		</tr>
    		<?
    			}
    		?>
    	</tbody>
    </table>
<input type="file" name="filecount" id="filecount" multiple onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
</div>
<div class="page_cen"><?echo $page_html?></div>
<script>
	$("#total_rows").html("<?=number_format($total_rows)?>");
</script>
