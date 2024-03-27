<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>결제내역수정</h3>
		</div>
<div class="white_box">
	<div class="box-table">
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
				<?php if( element('is_test', element('data', $view)) ){ ?>
				<tr>
					<th>테스트결제<th>
					<td>
						<span style="color:red;font-weight:bold">테스트로 결제 되었습니다.</span>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<th>회원명</th>
					<td>
					<?php
					echo html_escape(element('mem_nickname', element('member', element('data', $view))));
					echo ' ( ' . html_escape(element('mem_userid', element('member', element('data', $view)))) . ' ) ';
					?>
					</td>
				</tr>
				<tr>
					<th>구분</th>
					<td>
					<?php
					echo element(element('dep_from_type', element('data', $view)), element('deptype', $view));
					echo ' =&gt; ';
					echo element(element('dep_to_type', element('data', $view)), element('deptype', $view));
					?>
					</td>
				</tr>
				<tr>
					<th>결제방법</th>
					<td>
					<?php
					echo element(element('dep_pay_type', element('data', $view)), element('paymethodtype', $view));
					?>
					</td>
				</tr>
				<tr>
					<th><?php echo $this->depositconfig->item('deposit_name'); ?> 변동</th>
					<td>
					<?php
					echo number_format(element('dep_deposit', element('data', $view)) + element('amt_point', element('data', $view)));
					echo $this->depositconfig->item('deposit_unit');
					?>
					</td>
				</tr>
				<tr>
					<th>현금/카드</th>
					<td>
					<?php echo number_format(element('dep_cash', element('data', $view))); ?> 원
					</td>
				</tr>
				<tr>
					<th>보너스포인트</th>
					<td>
					<?php echo number_format(element('amt_point', element('data', $view))); ?> 점
					</td>
				</tr>
				<tr>
					<th>내용</th>
					<td>
					<textarea class="form-control" rows="5" name="dep_content"><?php echo set_value('dep_content', element('dep_content', element('data', $view))); ?></textarea>
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
		<div class="btn_al_cen" role="group" aria-label="...">
			<button type="submit" class="btn_st1">저장하기</button>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        rules: {
            dep_content: 'required'
        }
    });
});
//]]>
</script>
<script type="text/javascript">
  $("#nav li.nav100").addClass("current open");
</script>
