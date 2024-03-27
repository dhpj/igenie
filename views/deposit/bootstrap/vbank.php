<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>계좌입금내역</h3>
		</div>
		<div class="white_box">
			<?php
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
			echo form_open(current_full_url(), $attributes);
			?>
			<div class="search_box search_period" role="group">
				<ul class="search_period">
					<a href="?"><li class="<?php echo ( ! $this->input->get('dep_status')) ? 'active' : ''; ?>">전체내역</li></a>
					<a href="?dep_status=N"><li class="<?php echo ($this->input->get('dep_status') === 'N') ? 'active' : ''; ?>">미수내역</li></a>
					<a href="?dep_status=Y"><li class="<?php echo ($this->input->get('dep_status') === 'Y') ? 'active' : ''; ?>">완료내역</li></a>
				</ul>
				<input type="hidden" id="set_date" name="set_date">
				<span>전체 : <?=number_format($total_rows)?>건</span>
				<div class="fr">
					<div class="mobile_none"><?php echo admin_listnum_selectbox();?></div>
				</div>
			</div>
			<table class="table_list">
				<colgroup>
					<col width="7%"><?//No?>
					<col width="15%"><?//회원명?>
					<col width="15%"><?//입금자?>
					<col width="*"><?//입금계좌?>
					<col width="10%"><?//요청일시?>
					<col width="10%"><?//결제한금액?>
					<col width="10%"><?//미수금액?>
				</colgroup>
				<thead>
					<tr>
						<th>No</th>
						<th>회원명</th>
						<th>입금자</th>
						<th>입금계좌</th>
						<th>요청일시</th>
						<th>결제금액</th>
						<th>미수금액</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if(!empty($list)){
						$no = 0;
						foreach ($list as $r) {
							$num = $total_rows-($per_page*($page-1)) - $no;
							if($r->dep_pay_type == "bank" and $r->dep_bank_info == ""){
								$dep_bank_info = $this->config->item('payment_bank_name') ." ". $this->config->item('payment_bank_number') ." (예금주 : ". $this->config->item('payment_bank_owner') .")";
							}else{
								$dep_bank_info = $r->dep_bank_info;
							}
				?>
				<tr class="<? if($r->dep_status == '1') {echo 'success';}?>">
					<td><?=$num?></td><?//No?>
					<td><?=$r->mem_username?></td><?//회원명?>
					<td><?=$r->mem_realname?></td><?//입금자?>
					<td><?=$dep_bank_info?></td><?//입금계좌?>
					<td><?php echo display_datetime($r->dep_datetime, 'full'); ?></td><?//요청일시?>
					<td class="align-right"><?php echo number_format(abs($r->dep_cash)) . '원'; ?></td><?//결제금액?>
					<td class="align-right"><?php echo number_format(abs($r->dep_cash_request)-abs($r->dep_cash)) . '원'; ?></td><?//미수금액?>
				</tr>
				<?php
							$no++;
						}
					}else{
				?>
					<tr>
						<td colspan="7" class="nopost">자료가 없습니다</td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
			<?php
			ob_start();
			$buttons = ob_get_contents();
			ob_end_flush();
			?>
			<?//php echo $r->paging', $view); ?>
			<? if($total_rows > $per_page){ ?><div class="page_cen"><?=$page_html?></div><? } ?>
			<?php echo $buttons; ?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	//검색
	function open_page(page){
		//alert("page : "+ page);
		location.href = "?page="+ page;
	}
</script>
