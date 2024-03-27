<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>결제내역 목록 (<?php echo element('total_rows', element('data', $view), 0); ?>건)</h3>
						</div>
						<div class="white_box">
							<?php
							echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
							echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
							$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
							echo form_open(current_full_url(), $attributes);
							?>
							<div class="box-table-header">
								<div class="fr">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
								<div class="btn-group btn-group-sm" role="group">

									<a href="<?php echo '/biz/manager/deposit'; ?>" class="btn md <?php echo ( ! $this->input->get('dep_from_type') && ! $this->input->get('dep_to_type')) ? 'yellow' : '';?>">전체내역</a>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit" class="btn md <?php echo ($this->input->get('dep_to_type') === 'deposit' && ! $this->input->get('dep_pay_type')) ? 'yellow' : '';?>">충전내역</a>
									<?/*php if ($this->input->get('dep_to_type') === 'deposit') { */?>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=bank" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'bank') ? 'yellow' : '';?>">무통장</a>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=card" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'card') ? 'yellow' : '';?>">카드</a>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=realtime" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'realtime') ? 'yellow' : '';?>">실시간</a>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=vbank" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'vbank') ? 'yellow' : '';?>">가상계좌</a>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=phone" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'phone') ? 'yellow' : '';?>">핸드폰</a>
									<!-- <a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=service" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'service') ? 'btn-info' : 'btn-default';?>">서비스</a>
									<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=point" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'point') ? 'btn-info' : 'btn-default';?>">포인트결제</a>-->
									<?/*php } */?>
									<!--<a href="<?php echo '/biz/manager/deposit'; ?>?dep_from_type=deposit" class="btn btn-sm <?php echo ($this->input->get('dep_from_type') === 'deposit') ? 'btn-success' : 'btn-default';?>">사용내역</a>-->

								</div>

							<?php
							ob_start();
							?>
								<!--div class="" role="group" aria-label="...">
									<a href="<?php echo element('listall_url', $view); ?>" class="btn md">전체목록</a>
					<?/*if($_SERVER['REMOTE_ADDR']=='112.163.89.66') {? >
									<a href="< ?php echo element('write_url', $view); ? >" class="btn btn-outline btn-danger btn-sm">< ?php echo $this->depositconfig->item('deposit_name'); ? > 변동내역추가</a>
					< ?}*/?>
								</div-->
							<?php
							$buttons = ob_get_contents();
							ob_end_flush();
							?>
							</div>
							<table class="table_list mg_t10">
								<colgroup>
								<col width="5%" />
								<col width="15%" />
								<col width="10%" />
								<col width="20%" />
								<col width="10%" />
								<!-- <col width="9%" /> -->
								<col width="20%" />
								<col width="10%" />
								<!-- <col width="9%" /> -->
								<col width="*" />
								</colgroup>
								<thead>
								<tr>
									<th><a href="<?php echo element('dep_id', element('sort', $view)); ?>">번호</a></th>
									<th>업체명</th>
									<th>회원명</th>
									<th>구분</th>
									<th>일시</th>
									<!-- <th>결제</th> -->
									<th>내용</th>
			<!--					<th><?php echo $this->depositconfig->item('deposit_name'); ?> 변동</th>-->
									<th>현금/카드</th>
									<!-- <th>보너스<br/>포인트</th> -->
									<th>수정</th>
								</tr>
								</thead>
								<tbody>
							<?php
							if (element('list', element('data', $view))) {
								foreach (element('list', element('data', $view)) as $result) {?>
								<tr>
									<td class="align-center"><?php echo number_format(element('num', $result)); ?></td>
									<td class="align-center"><?php echo html_escape(element('mem_username', $result)); ?></td>
									<td class="align-center"><?php echo element('mem_nickname', $result); ?></td>
									<td>
									<?php if (element('dep_deposit', $result) >= 0) { ?>
									<button type="button" class="btn btn-xs btn-primary" >충전</button>
									<?php } else { ?>
									<button type="button" class="btn btn-xs btn-danger" >사용</button>
									<?php } ?>
									<?php echo element('dep_type_display', $result); ?>
									<?php if( element('is_test', $result) ){ ?>
									<span class="btn btn-xs btn-warning">테스트</span>
									<?php } ?>
									</td>
									<td class="align-center"><?php echo display_datetime(element('dep_datetime', $result), 'full'); ?></td>
									<!-- <td class="align-center"><?php echo element('dep_pay_type', $result) ? $this->depositlib->paymethodtype[element('dep_pay_type', $result)] : ''; ?></td> -->
									<td><?php echo nl2br(html_escape(element('dep_content', $result))); ?></td>
									<!--<td class="text-right"><?php echo number_format(element('dep_deposit', $result) + element('amt_point', $result)); ?><?php echo $this->depositconfig->item('deposit_unit'); ?></td>-->
									<td class="text-right"><?php echo number_format(element('dep_deposit', $result)) . '원'; ?></td>
									<!-- <td class="text-right"><?php echo number_format(element('amt_point', $result)); ?></td> -->
									<td><a href="<?php echo $this->pagedir; ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
								</tr>
							<?php
								}
							}
							if ( ! element('list', element('data', $view))) {?>
								<tr>
									<td colspan="12" class="nopost">자료가 없습니다</td>
								</tr>
							<?php
								}?>
								</tbody>
							</table>
							<?php echo element('paging', $view); ?>
							<div class="pull-left ml20 fr"><?php echo admin_listnum_selectbox();?></div>
							<?php echo $buttons; ?>
						<?php echo form_close(); ?>
								<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
			<div class="box-search">
				<div class="row">
					<div style="float:left;">
						<select class="" name="sfield" >
						<?php echo element('search_option', $view); ?>
						</select>
					</div>
					<div style="float:left;">
						<div class="input-group">
							<input type="text" class="" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
							<span class="input-group-btn">
							<button class="btn btn-default btn-md" name="search_submit" type="submit">검색!</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</form>
						</div>
					</div>
</div>
<script type="text/javascript">
  $("#nav li.nav100").addClass("current open");
</script>
