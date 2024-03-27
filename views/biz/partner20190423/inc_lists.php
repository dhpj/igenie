    <table class="table table-hover table-bordered table-highlight-head table-checkable basic-board">
        <colgroup>
            <col width="*">
            <col width="10%">
            <col width="12%">
            <col width="13%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="15%">
            <col width="5%">
        </colgroup>
        <thead>
        <tr>
            <th class="text-center">소속</th>
            <th class="text-center">가입일</th>
            <th class="text-center">계정</th>
            <th class="text-center">업체명</th>
            <th class="text-center">잔액</th>
            <th class="text-center">담당자 이름</th>
            <th class="text-center">담당자 연락처</th>
            <th class="text-center">담당자 이메일</th>
            <th class="text-center">2nd</th>
        </tr>
        </thead>
        <tbody>
<?$offer = "";
foreach($rs as $row) {?>
                <tr onclick="window.document.location='/biz/partner/view?<?=$row->mem_userid?>'" style="cursor: pointer;">
                    <td class="text-center"><?if($offer==$row->mrg_recommend_mem_id) { echo '&nbsp;'; } else { echo $row->mrg_recommend_mem_username; $offer=$row->mrg_recommend_mem_id; }?></td>
                    <td class="text-center"><?=substr($row->mem_register_datetime, 0, 10)?></td>
                    <td class="text-left"><?=$row->mem_userid?></td>
                    <td class="text-left"><?=$row->mem_username?></td>
                    <td class="text-right">₩<?=number_format($row->mem_point + $row->total_deposit, 1)?></td>
                    <td class="text-left"><?=$row->mem_nickname?></td>
                    <td class="text-left"><?=$row->mem_phone?></td>
                    <td class="text-left"><?=$row->mem_email?></td>
                    <td class="text-center"><? switch($row->mem_2nd_send) { case 'GREEN_SHOT' : echo '웹(A)'; break; case 'NASELF':echo '웹(B)'; break; default:echo '';} ?></td>
                </tr>
<?}?>

        </tbody>
    </table>
    <div class="row">
        <div class="col-xs-12 align-center ">
			<div class="pagination align-center"><?echo $page_html?></div>
		</div> 
    </div>
  