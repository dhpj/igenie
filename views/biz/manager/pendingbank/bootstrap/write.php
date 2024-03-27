<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>무통장입금내역</h3>
		</div>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
		<input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
		<div class="box-table">
			<table class="tpl_ver_form" width="100%">
				<colgroup>
				<col width="200">
				<col width="*">
				</colgroup>
				<tbody>
					<tr>
						<th>회원정보</th>
						<td>
						<?php echo html_escape(element('mem_nickname', element('data', $view))); ?>
						( <?php echo html_escape(element('mem_userid', element('member', element('data', $view)))); ?> )
						</td>
					</tr>
					<tr>
						<th><?php echo $this->depositconfig->item('deposit_name'); ?> 충전</th>
						<td>
						<?php echo number_format(element('dep_deposit_request', element('data', $view))); ?> <?php echo html_escape($this->depositconfig->item('deposit_unit')); ?>
						</td>
					</tr>
					<tr>
						<th>결제해야할 금액</th>
						<td>
						<?php echo number_format(element('dep_cash_request', element('data', $view))); ?> 원
						</td>
					</tr>
					<tr>
						<th>결제상태</th>
						<td>
						<label class="radio-inline" for="dep_cash_status_1" >
						<input type="radio" name="dep_cash_status" class="dep_cash_status" id="dep_cash_status_1" value="not" <?php echo set_checkbox('dep_cash_status', 'not', ((int) element('dep_cash', element('data', $view)) === 0 ? true : false)); ?> /> 미납
						</label>
						<label class="radio-inline" for="dep_cash_status_2" >
						<input type="radio" name="dep_cash_status" class="dep_cash_status" id="dep_cash_status_2" value="some" <?php echo set_checkbox('dep_cash_status', 'some', ((element('dep_cash_request', element('data', $view)) > element('dep_cash', element('data', $view)) && element('dep_cash', element('data', $view))) ? true : false)); ?> /> 일부납
						</label>
						<label class="radio-inline" for="dep_cash_status_3" >
						<input type="radio" name="dep_cash_status" class="dep_cash_status" id="dep_cash_status_3" value="all" <?php echo set_checkbox('dep_cash_status', 'all', ((int) element('dep_cash_request', element('data', $view)) === (int) element('dep_cash', element('data', $view)) ? true : false)); ?> /> 완납
						</label>
						<div class="help-block">완납으로 변경시 예치금이 자동으로 충전완료됩니다</div>
						</td>
					</tr>
					<tr>
						<th>실제결제한 금액</th>
						<td>
						<input type="number" class="form-control" name="dep_cash" id="dep_cash" value="<?php echo set_value('dep_cash', element('dep_cash', element('data', $view)) + 0); ?>" style="width:300px;" /> 원
						</td>
					</tr>
					<tr>
						<th>결제일시</th>
						<td>
						<input type="text" class="form-control" name="dep_deposit_datetime" id="dep_deposit_datetime" value="<?php echo set_value('dep_deposit_datetime', (element('dep_deposit_datetime', element('data', $view)) > '0000-00-00 00:00:00' ? element('dep_deposit_datetime', element('data', $view)):cdate('Y-m-d H:i:s'))); ?>" />
						</td>
					</tr>
					<tr>
						<th>관리자 메모</th>
						<td>
						<textarea class="form-control" rows="5" name="dep_admin_memo"><?php echo set_value('dep_admin_memo', element('dep_admin_memo', element('data', $view))); ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-outline btn-success btn-sm">저장하기</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$("#nav li.nav100").addClass("current open");
$(document).ready(function() {
    if ($('.dep_cash_status:checked').val() === 'not') {
        $('.some_cash').hide();
        $('.approve_datetime').hide();
    }
    if ($('.dep_cash_status:checked').val() === 'some') {
        $('.some_cash').show();
        $('.approve_datetime').hide();
    }
    if ($('.dep_cash_status:checked').val() === 'all') {
        $('.some_cash').hide();
        $('.approve_datetime').show();
    }
});
$(document).on('click', '.dep_cash_status', function() {
    if ($(this).val() === 'not') {
        $('.some_cash').hide();
        $('.approve_datetime').hide();
    }
    if ($(this).val() === 'some') {
        $('.some_cash').show();
        $('.approve_datetime').hide();
    }
    if ($(this).val() === 'all') {
        $('.some_cash').hide();
        $('.approve_datetime').show();
    }
});
//]]>
</script>
