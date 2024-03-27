<div class="table_list">
	<table>
		<colgroup>
			<col width="8%">
			<col width="*">
			<col width="15%">
			<col width="20%">
			<col width="20%">
			<col width="20%">
		</colgroup>
		<thead>
			<tr>
				<th>No</th>
				<th>전화번호</th>
				<th>상태</th>
				<th>발송일자</th>
				<th>확인일자</th>
				<th>수신동의일자</th>
			</tr>
		</thead>
		<tbody>
			<?
				$i = 0;
				if(!empty($list)){
					foreach($list as $r){
						$num = $total_rows-($perpage*($page-1))-$i; //순번
						$agd_phn = $this->funn->format_phone($r->agd_phn, "-"); //전화번호
						$agd_state = $r->agd_state; //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
						$agd_cre_date = $r->agd_cre_date; //발송일자
						$agd_view_date = ""; //확인일자
						$agd_agree_date = ""; //수신동의일자
						if($agd_state == "0"){
							$agd_state = "발송완료";
						}else{
							$agd_view_date = $r->agd_view_date; //확인일자
							if($agd_state == "1"){
								$agd_state = "확인완료";
							}else if($agd_state == "2"){
								$agd_state = "수신동의";
								$agd_agree_date = $r->agd_agree_date; //수신동의일자
							}
						}
			?>
			<tr>
				<td><?=$num //No?></td>
				<td><?=$agd_phn //전화번호?></td>
				<td><?=$agd_state //상태?></td>
				<td><?=$agd_cre_date //확인일자?></td>
				<td><?=$agd_view_date //확인일자?></td>
				<td><?=$agd_agree_date //수신동의일자?></td>
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
<div style="padding-bottom:15px;"><?=$page_html?></div>
