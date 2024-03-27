<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>가상계좌입금목록</h3>
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
							    <span>전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</span>
							    <div class="fr">
									<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
								        <div class="box-search">
								             <select class="" name="sfield">
								                <?php echo element('search_option', $view); ?>
								            </select>
								            <input type="text" class="" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
								                <button class="btn md yellow" name="search_submit" type="submit">검색</button>
								        </div>
								    </form>
							    </div>
							</div>
							<table class="table_list">
								<colgroup>
									<col width="5%">
									<col width="10%">
									<col width="15%">
									<col width="5%">
									<col width="10%">
									<col width="5%">
									<col width="5%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="5%">

								</colgroup>
			                    <thead>
			                        <tr>
				                        <th><input type="checkbox" name="chkall" id="chkall" /></th>
			                            <th>회원아이디</th>
			                            <th>회원명</th>
			                            <th>입금자</th>
			                            <th>이메일</th>
			                            <th>연락처</th>
			                            <th>요청일시</th>
			                            <th>예치금</th>
			                            <th>총결제해야할금액</th>
			                            <th>결제한금액</th>
			                            <th>미수금액</th>
			                            <th>수정</th>

			                        </tr>
			                    </thead>
			                    <tbody>
			                    <?php
			                    if (element('list', element('data', $view))) {
			                        foreach (element('list', element('data', $view)) as $result) {
			                    ?>
			                        <tr class="<?php if (element('dep_status', $result) === '1') {echo 'success';}?>">
				                        <td><?php if ( ! element('dep_status', $result)) { ?><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /><?php } ?></td>
			                            <td><?php echo html_escape(element('mem_userid', $result)); ?></td>
			                            <td><?php echo element('mem_username', $result); ?></td>
			                            <td><?php echo html_escape(element('mem_realname', $result)); ?></td>
			                            <td><?php echo html_escape(element('mem_email', $result)); ?></td>
			                            <td><?php echo html_escape(element('mem_phone', $result)); ?></td>
			                            <td><?php echo display_datetime(element('dep_datetime', $result), 'full'); ?></td>
			                            <td class="align-right"><?php echo number_format(abs(element('dep_deposit_request', $result))); ?> <?php echo $this->depositconfig->item('deposit_unit'); ?></td>
			                            <td class="align-right"><?php echo number_format(abs(element('dep_cash_request', $result))) . '원'; ?></td>
			                            <td class="align-right"><?php echo number_format(abs(element('dep_cash', $result))) . '원'; ?></td>
			                            <td class="align-right"><?php echo number_format(abs(element('dep_cash_request', $result))-abs(element('dep_cash', $result))) . '원'; ?></td>
			                            <td><?php if ( ! element('dep_status', $result)) { ?><a href="<?php echo $this->pagedir; ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a><?php } ?></td>

			                        </tr>
			                    <?php
			                        }
			                    }
			                    if ( ! element('list', element('data', $view))) {
			                    ?>
			                        <tr>
			                            <td colspan="12" class="nopost">자료가 없습니다</td>
			                        </tr>
			                    <?php
			                    }
			                    ?>
			                    </tbody>
			                </table>
			                <?php
			                ob_start();
			                ?>
			                    <!--<div class="btn-group" role="group" aria-label="...">
			                        <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
			                        <button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
			                    </div>-->
			                <?php
			                $buttons = ob_get_contents();
			                ob_end_flush();
			                ?>

							<?php echo element('paging', $view); ?>
							<div class="mobile_none"><?php echo admin_listnum_selectbox();?></div>
							<?php echo $buttons; ?>
						</div>
					</div>
</div>

        <?php echo form_close(); ?>

<script type="text/javascript">
  $("#nav li.nav100").addClass("current open");
</script>
