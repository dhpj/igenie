<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>무통장입금목록</h3>
		</div>
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
		<div class="white_box">
            <div class="search_box search_period" id="status">
				<ul class="search_period">
					<a href="index"><li class="submit">입금내역</li></a>
					<a href="/biz/manager/pendingbank/errlist"><li class="submit">미처리내역</li></a>
                    <? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                    <a href="/biz/manager/pendingbank/secondlist"><li class="submit active">2차매칭</li></a>
                    <? } ?>
				</ul>
				<input type="hidden" id="set_date" name="set_date">
			</div>

			<table class="table_list">
				<thead>
					<tr>
						<th>No</th>
						<th>회원아이디</th>
                        <th>소속</th>
						<th>업체명</th>
						<th>입금자명(사업자번호)</th>
						<th>연락처</th>
						<th>요청일시</th>
						<th>입금액</th>
					</tr>
				</thead>
				<tbody>
				<?
                    $flag = $total_row > 0 ? false : true;
				    foreach($list as $r) {
				?>
					<tr class="<?php if ($r->dep_status == '1') {echo 'success';}?>">
						<td><?=$total_row?></td>
						<td><?=$r->mem_userid?></td>
                        <td><?=$r->adminname?></td>
						<td><?=$r->mem_username?></td>
						<td><?=$r->sender?></td>
						<td><?=$r->mem_phone?></td>
						<td><?=display_datetime($r->rsd_create_datetime, 'full')?></td>
						<td class="align-right"><?=number_format($r->money).'원'?></td>
					</tr>
				<?php
                        $total_row = $total_row - 1;
					}

					if ($flag) {
				?>
					<tr>
						<td colspan="<?=($this->member->item('mem_level') >= 100) ? "11" : "10"?>" class="nopost">자료가 없습니다</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			<div class="tc"><?=$page_html?></div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	function open_page(page){
		location.href = "?page="+ page;
	}


</script>
