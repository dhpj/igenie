<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="crumbs">
		<ul id="breadcrumbs" class="breadcrumb">
				<li><i class="icon-home"></i><a href="/deposit">예치금 충전</a></li>
				<li class="current"><a href="#" title=""><?php echo html_escape($this->depositconfig->item('deposit_name')); ?> 최근 이용 내역</a></li>
		</ul>
</div>
<div class="content_wrap box widget">
	<div class="widget-header">
			<h4><?php echo html_escape($this->depositconfig->item('deposit_name')); ?> 최근 이용 내역</h4>
	</div>
	<div class="credit">
			<div class="credit_info">
					<span class="pull-left"><h5>현재 나의 <?php echo html_escape($this->depositconfig->item('deposit_name')); ?> : <?php echo number_format($this->member->item('total_deposit') + 0); ?> <?php echo html_escape($this->depositconfig->item('deposit_unit')); ?></h4></span>
					<span class="pull-right">전체 <?php echo number_format(element('total_rows', element('data', $view), 0)); ?> 건</span>
			</div>
			<table class="table table-hover table-striped table-bordered table-highlight-head t_center table-hover">
					<thead>
							<tr>
									<th>번호</th>
									<th>날짜</th>
									<th>충전</th>
									<th>사용</th>
									<th>잔액</th>
									<th class="col-md-6 col-md-offset-1">내용</th>
							</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
							foreach (element('list', element('data', $view)) as $result) {
					?>
							<tr>
									<td><?php echo html_escape(element('num', $result)); ?></td>
									<td><?php echo display_datetime(element('dep_deposit_datetime', $result)); ?></td>
									<td class="text-right text-primary"><?php if (element('dep_deposit', $result) >= 0) { echo number_format(element('dep_deposit', $result)) . html_escape($this->depositconfig->item('deposit_unit')); } ?></td>
									<td class="text-right text-danger"><?php if (element('dep_deposit', $result) < 0) { echo number_format(abs(element('dep_deposit', $result))) . html_escape($this->depositconfig->item('deposit_unit')); } ?></td>
									<td class="text-right"><?php echo number_format(element('dep_deposit_sum', $result)) . ' ' . html_escape($this->depositconfig->item('deposit_unit')); ?></td>
									<td><?php echo nl2br(html_escape(element('dep_content', $result))); ?></td>
							</tr>
					<?php
							}
					}
					if ( ! element('list', element('data', $view))) {
					?>
							<tr>
									<td colspan="6" class="nopost">회원님이 이용 내역이 없습니다</td>
							</tr>
					<?php
					}
					?>
					</tbody>
			</table>
			<nav><?php echo element('paging', $view); ?></nav>
	</div>
</div>
