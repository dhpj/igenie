<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php $this->managelayout->add_js(base_url('assets/js/deposit.js')); ?>
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php');
?>
<div id="mArticle">
<div class="form_section">
<div class="inner_tit">
	<h3>예치금 결제상세내역</h3>
	<button class="btn_tr" onclick="location.href='/deposit/vbank'">가상계좌입금내역 바로가기</button>
<!--<div class="crumbs">
		<ul id="breadcrumbs" class="breadcrumb">
				<li><i class="icon-home"></i><a href="/deposit">예치금 충전</a></li>
				<li class="current"><?php echo $this->depositconfig->item('deposit_name'); ?> 결제상세내역</li>
		</ul>
</div>-->
</div>
<div class="white_box table_list">
    <table cellpadding="0" cellspacing="0" border="0">
        <tbody>
					<?php if (element('dep_pay_type', element('data', $view)) === 'vbank') {   //가상계좌 ?>
							<tr>
									<td class="text-center">입금자명</td>
									<td style="font-size:20px;"><?php echo html_escape(element('mem_realname', element('data', $view))); ?></td>
							</tr>
							<tr>
									<td class="text-center">입금계좌</td>
									<td style="font-size:20px;"><?php echo element('dep_bank_info', element('data', $view)); ?></td>
							</tr>
					<?php } ?>
					<tr>
							<td class="text-center col-md-3">총 주문액</td>
							<td style="color:#f00; font-size:20px;"><?php echo number_format(abs(element('dep_cash_request', element('data', $view))));?> 원</td>
					</tr>
					<tr style="display:none;">
							<td class="text-center">미결제액</td>
							<td style="font-size:20px;">
									<?php
											$notyet = abs(element('dep_cash_request', element('data', $view))) - abs(element('dep_cash', element('data', $view)));
											echo number_format($notyet);
									?> 원
							</td>
					</tr>
					<tr style="display:none;">
							<td class="text-center">결제액</td>
							<td style="font-size:20px;"><?php echo number_format(abs(element('dep_cash', element('data', $view))));?> 원</td>
					</tr>
            <tr style="display:none;">
                <td class="text-center col-md-3">주문번호</td>
                <td><?php echo element('dep_id', element('data', $view)); ?></td>
            </tr>
						<?php if (element('dep_pay_type', element('data', $view)) === 'vbank' OR element('dep_pay_type', element('data', $view)) === 'realtime') {?>
                <tr style="display:none;">
                    <td class="text-center">거래번호</td>
                    <td><?php echo html_escape(element('dep_tno', element('data', $view))); ?></td>
                </tr>
            <?php } ?>
            <?php if( element('is_test', element('data', $view) )) { ?>
            <tr style="display:none;">
                <td class="text-center col-md-3">테스트 결제</td>
                <td><span style="color:red;font-weight:bold">테스트로 결제하셨습니다.</span></td>
            </tr>
            <?php } ?>
            <!--<tr>
                <td class="text-center"><?php echo html_escape($this->depositconfig->item('deposit_name')); ?> 충전</td>
                <td><?php echo number_format(element('dep_deposit_request', element('data', $view))); ?> <?php echo html_escape($this->depositconfig->item('deposit_unit')); ?></td>
            </tr>-->
            <tr style="display:none;">
                <td class="text-center">결제방식</td>
                <td><?php echo $this->depositlib->paymethodtype[element('dep_pay_type', element('data', $view))];?></td>
            </tr>
            <tr style="display:none;">
                <td class="text-center">결제금액</td>
                <td><?php echo (element('dep_cash', element('data', $view))) ? number_format(abs(element('dep_cash', element('data', $view)))) : '아직 입금되지 않았습니다'; ?></td>
            </tr>
            <?php if (element('dep_deposit_datetime', element('data', $view)) > '0000-00-00 00:00:00') { ?>
                <tr style="display:none;">
                    <td class="text-center">결제일시</td>
                    <td><?php echo element('dep_deposit_datetime', element('data', $view)); ?></td>
                </tr>
            <?php } ?>
            <?php if (element('dep_pay_type', element('data', $view)) === 'bank') {?>
                <tr>
                    <td class="text-center">입금자명</td>
                    <td style="font-size:20px;"><?php echo html_escape(element('mem_realname', element('data', $view))); ?></td>
                </tr>
                <tr>
                    <td class="text-center">입금계좌</td>
                    <td style="font-size:20px;"><?php echo nl2br($this->member->item('paymemt_bank_info')); /*nl2br(html_escape($this->cbconfig->item('payment_bank_info')));*/ ?></td>
                </tr>
            <?php } ?>

            <?php if (element('dep_pay_type', element('data', $view)) === 'card') {?>
                <tr>
                    <td class="text-center">승인번호</td>
                    <td><?php echo html_escape(element('dep_app_no', element('data', $view))); ?></td>
                </tr>
            <?php } ?>
            <?php if (element('dep_pay_type', element('data', $view)) === 'phone') {?>
                <tr>
                    <td class="text-center">휴대폰번호</td>
                    <td><?php echo html_escape(element('dep_app_no', element('data', $view))); ?></td>
                </tr>
            <?php } ?>

            <?php if (element('dep_pay_type', element('data', $view)) === 'card' OR element('dep_pay_type', element('data', $view)) === 'phone') {?>
                <tr>
                    <td class="text-center">영수증</td>
                    <td>
                        <?php
                        if (element('dep_pay_type', element('data', $view)) === 'card') {
                            if ($this->cbconfig->item('use_pg_test')) {
                                $receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
                            } else {
                                $receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
                            }
                        ?>
                            <a href="javascript:;" onclick="window.open('<?php echo $receipturl; ?>card_bill&tno=<?php echo element('dep_tno', element('data', $view)); ?>&amp;order_no=<?php echo element('dep_id', element('data', $view)); ?>&trade_mony=<?php echo element('dep_cash', element('data', $view)); ?>', 'winreceipt', 'width=500,height=690,scrollbars=yes,resizable=yes');">영수증 출력</a>
                        <?php
                        }
                        if (element('dep_pay_type', element('data', $view)) === 'phone') {
                            if ($this->cbconfig->item('use_pg_test')) {
                                $receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
                            } else {
                                $receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
                            }
                        ?>
                            <a href="javascript:;" onclick="window.open('<?php echo $receipturl; ?>mcash_bill&tno=<?php echo element('dep_tno', element('data', $view)); ?>&amp;order_no=<?php echo element('dep_id', element('data', $view)); ?>&trade_mony=<?php echo element('dep_cash', element('data', $view)); ?>', 'winreceipt', 'width=500,height=690,scrollbars=yes,resizable=yes');">영수증 출력</a>
                        <?php } ?>
                    </td>
                </tr>
        <?php } ?>

        </tbody>
    </table>
		<p style="font-size:15px; color:#f00; margin-top:20px;">
			* 계좌이체 후 예치금 충전까지 3~5분의 시간이 소요됩니다.
		</p>
</div>
</div>
</div>
