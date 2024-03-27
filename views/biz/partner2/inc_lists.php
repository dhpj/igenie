    <div class="table_list">
    <table>
        <colgroup>
            <col width="*">
            <col width="10%">
            <col width="12%">
            <col width="13%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="15%">
            <col width="80px">
        </colgroup>
        <thead>
        <tr>
            <th>소속</th>
            <th>가입일</th>
            <th>계정</th>
            <th>업체명</th>
            <th>잔액</th>
            <th>담당자 이름</th>
            <th>담당자 연락처</th>
            <th>담당자 이메일</th>
            <th>2nd</th>
        </tr>
        </thead>
        <tbody>
<?$offer = "";
foreach($rs as $row) {?>
                <tr onclick="window.document.location='/biz/partner2/view?<?=$row->mem_userid?>'" style="cursor: pointer;">
                    <td><?if($offer==$row->mrg_recommend_mem_id) { echo '&nbsp;'; } else { echo $row->mrg_recommend_mem_username; $offer=$row->mrg_recommend_mem_id; }?></td>
                    <td><?=substr($row->mem_register_datetime, 0, 10)?></td>
                    <td><?=$row->mem_userid?></td>
                    <td><?=$row->mem_username?></td>
                    <? if ($row->mem_pay_type == 'A') { ?>
                    <td class="text-right">-</td>
                    <? } else { ?>
                    <td class="text-right">₩<?=number_format($row->mem_point + $row->total_deposit)?></td>
                    <? } ?>
                    <td><?=$row->mem_nickname?></td>
                    <td><?=$row->mem_phone?></td>
                    <td><?=$row->mem_email?></td>
                    <td><? switch($row->mem_2nd_send) { case 'GREEN_SHOT' : echo '웹(A)'; break; case 'NASELF':echo '웹(B)'; break; case 'SMART':echo '웹(C)'; break; default:echo '';} ?></td>
                </tr>
<?}?>

        </tbody>
    </table>
    </div>
    <div class="row">
        <div class="col-xs-12 align-center ">
			<div class="pagination align-center"><?echo $page_html?></div>
		</div> 
    </div>
  