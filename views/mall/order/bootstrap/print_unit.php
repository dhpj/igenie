<link rel="stylesheet" type="text/css" href="/views/_layout/bootstrap/css/import.css?v=<?=date("ymdHis")?>">
<link rel="stylesheet" type="text/css" href="/views/mall/order/bootstrap/css/style.css?v=<?=date("ymdHis")?>">
<link rel="stylesheet" type="text/css" href="/css/common.css?v=<?=date("ymdHis")?>">
<script type="text/javascript" src="/js/jquery-barcode.js"></script>
<style type="text/css">
	/* 프린트 배경색 인쇄 */
	h1, h2, h3, h4, h5, dl, dt, dd, ul, li, ol, th, td, p, blockquote, form, fieldset, legend, div, body { -webkit-print-color-adjust:exact; }

	/* 프린트 여백설정 */
	@page{
		size: A4 landscape;
		margin-left: 20px;
		margin-right: 20px;
		margin-top: 20px;
		margin-bottom: 20px;
	}

	/* 프린트 세로인쇄 */
	@media print{@page {size: portrait}}
</style>
<script>
//20211222 조지영 제품 바코드 출력
$(document).ready(function(){
	//var cnt = ($('.barcode_box').attr('id')).replace(/[^0-9]/g, ""); //상품 순서 값 idx 요소 값.
	var code_box = document.getElementsByName('code_box');
	for(var i=0;i<code_box.length;i++){ //name.length로 반복 범위 지정
		var cnt = i+1; //idx 값은 1부터 시작하므로 + 1 해서 count
		var bacode_num = $('#goods_idx_'+cnt).attr('barcode_num'); //바코드 뿌릴 div에서 바코드 번호 받아옴.
			if(bacode_num.length > 8){
				$('#goods_idx_'+cnt).barcode(bacode_num.padStart(13,'0'), "ean13",{barWidth:2, barHeight:30});
			}else if (bacode_num.length<=8) {
				$('#goods_idx_'+cnt).barcode(bacode_num.padStart(8,'0'), "ean8",{barWidth:2, barHeight:30});
			}
				//alert(cnt+" "+bacode_num);
		}
});
</script>
<page size="A4">
<div style="padding: 25px">
	<div class="print_title"><h1>주문내역</h1></div>
		<div>
			<div class="row_box" style="padding: 25px 0;">
				<?
					$pre_order_id = -1;
					$dcnt = 0;
					$totalamt = 0;
					foreach($orderlist as $r) {
						//log_message("ERROR", "ddd : ".$pre_order_id .'/'. $r->order_id );
						if($pre_order_id != $r->order_id) {
				?>
				<table class="tbl">
					<colgroup>
						<col width="150px">
						<col width="">
						<col width="150px">
						<col width="">
					</colgroup>
					<tbody>
						<tr>
							<th>주문번호</th>
							<td class="tl"><?=$r->orderno?></td>
							<th>주문날짜</th>
							<td class="tl"><?=$r->creation_date?></td>
						</tr>
						<tr>
							<th>주문자명</th>
							<td class="tl"><?=$r->receiver?></td>
							<th>연락처</th>
							<td class="tl"><?=$this->funn->format_phone($r->phnno, "-")?></td>
						</tr>
						<tr>
							<th>배송지</th>
							<td colspan="3" class="tl"><?=trim($r->postcode ." ". $r->addr1 ." ". $r->addr2)?></td>
						</tr>
						<tr>
							<th>결제정보</th>
							<td colspan="3" class="tl"><?=$this->funn->get_charge_type($r->charge_type)?></td>
						</tr>
						<tr>
							<th>추가주문 및 요청사항</th>
							<td colspan="3" class="tl"><?=nl2br($r->user_req)?></td>
						</tr>
						<tr>
							<th>추가정보</th>
							<td colspan="3" class="tl"><?=nl2br($r->mng_note)?></td>
						</tr>
						<? if($r->s_reason != ""){ ?>
						<tr>
							<th>부분취소사유</th>
							<td colspan="3" class="tl"><?=nl2br($r->s_reason)?></td>
						</tr>
						<? } ?>
						<? if($r->point_save_no != ""){ ?>
						<tr>
							<th>마트적립번호</th>
							<td colspan="3" class="tl"><?=$r->point_save_no?></td>
						</tr>
						<? } ?>
						<tr>
							<th>주문상품</th>
							<td colspan="3">
								<ul class="order_detail_wrap" style="margin:0px;">
								<? } ?>
									<li>
										<div class="order_detail">
											<div class="p-name" style="width:400px;<?=($r->cal_yn=='Y')?'text-decoration:line-through;':''?>"><?=($r->rowspan > 1) ? ($dcnt+1) ."." : ""?><?=trim($r->goods_name .' '. $r->goods_option)?>
                         <?=!empty($r->barcode) ? '<div id="goods_idx_'.($dcnt+1).'" class="barcode_box mg_t10" name="code_box" barcode_num="'.$r->barcode.'"></div>':'' //20211221 조지영 제품 바코드 출력 ?>
											</div><? //상품명 규격 ?>
											<div class="count"><?=$r->qty?>개</div><? //갯수 ?>
											<div class="price"><?=number_format($r->amount)?>원</div><? //총상품금액 ?>
										</div>
									</li>
									<?
										$dcnt++;
										$delivery_amt = $r->deliv_amt; //배송비
										//$delivery_amt = $delivery_amt + $add_deliv_amt;
										$pre_amount = $r->pre_amount;
										$pre_deliv = $r->deliv_amt;
										$subamt = $r->amount; //총상품금액 (주문수량 계산된 금액)
										$totalamt = $r->all_amount; //$totalamt + $subamt; //총결제금액
										$can_amount = $r->can_amount;
										$price_change = $r->price_change;

										if(empty($r->can_amount)){
												$can_amount = 0;
										}
										$iprice = $totalamt  - $can_amount;
										$mdeliv_price = 0;

										if($can_amount>0){
											if($iprice < $r->free_delivery_amt){
													$delivery_amt = $r->delivery_amt;
													if($delivery_amt >= $r->deliv_amt){
														$mdeliv_price = $delivery_amt - $r->deliv_amt;
													}
													if($mdeliv_price<0){
														$mdeliv_price = $mdeliv_price * -1;
													}
											}else{
												$delivery_amt=0;
											}
											//log_message("ERROR", " sub_type : ".$r->sub_type." subamt : ".$iprice."  can_amount :".$can_amount." delivery_amt :".$delivery_amt." free_delivery_amt :".$r->free_delivery_amt." order_id :".$r->order_id);
										}
										$itotal = $iprice + $delivery_amt;
										//log_message("ERROR", "ddd : ".$pre_order_id .'/'. $r->order_id.'/'.$dcnt.'/'.$totalamt.'/'.$r->rowspan );
										if($dcnt >= $r->rowspan) {
										    $dcnt = 0;
									 ?>
									 <? if($can_amount > 0){ ?>
									 <li>
									 	<div class="order_detail">
										 	<div class="p-name">부분취소</div>
										 	<div class="count"></div>
										 	<div class="price">-<?=number_format($can_amount)?>원</div>
									 	</div>
								 	</li>
									<? } ?>
									 <? if($delivery_amt > 0){ ?>
									 <li>
									 	<div class="order_detail">
										 	<div class="p-name">배송비</div>
										 	<div class="count"></div>
										 	<div class="price"><?=number_format($delivery_amt)?>원</div>
									 	</div>
								 	</li>
									<? } ?>
									<? if($price_change != 0){ ?>
									<li>
									 <div class="order_detail">
										 <div class="p-name">결제금액조정</div>
										 <div class="count"></div>
										 <div class="price"><?=number_format($price_change)?>원</div>
									 </div>
								 </li>
								 <? } ?>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>
				<?
						}
						$pre_order_id = $r->order_id;
					}
				?>
			</div>
		<div class="panel_footer">
        	<div class="order_total_price tr">
				총 결제금액 : <span class="print_sum"><?=number_format(floor( ($itotal+$price_change) / 10 ) * 10)?>원</span>
        	</div>
		</div>
	</div>
</div>
</page>
<script>
	window.print();
</script>
