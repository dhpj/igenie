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

	/* 프린트 가로인쇄 */
	@media print{@page {size: landscape}}
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
<page size="A4" layout="portrait">
  <div style="padding:0px">
    <div id="current_time" style="border-right: none"><?=date("Y년 m월 d일")?></div>
    <div>
      <div class="widget-content" id="append_list">
        <table class="order print_page">
          <colgroup>
            <col style="width: 70px;"><?//주문번호?>
            <col style="width: 100px;"><?//주문정보?>
            <col style="width: 100px;"><?//배송지?>
            <col style="width: 90px;"><?//결제정보?>
            <col style="width: 250px;"><?//상품정보?>
            <col style="width: 125px;"><?//결제금액?>
            <col style="width: 80px;"><?//진행상태?>
            <col style="width: 140px;"><?//추가정보?>
          </colgroup>
          <thead>
            <input type="hidden" id="idGoodsViewFl" value="CLOSE">
            <tr>
              <th>주문번호</th>
              <th>주문정보</th>
              <th>배송지</th>
              <th>결제정보</th>
              <th>상품정보</th>
              <th>결제금액</th>
              <th>진행상태</th>
              <th>추가정보</th>
            </tr>
          </thead>
          <tbody class="tbody">
			<?
				$no = 0;
				$pre_order_id = -1;
				$dcnt = 0;
				$subamt = 0; //총상품금액
				$totalamt = 0; //총결제금액
				foreach($orderlist as $r) {
					//log_message("ERROR", "ddd : ".$pre_order_id .'/'. $r->order_id );
					if($pre_order_id != $r->order_id) {
						$no++;
						$goods_cnt = $r->rowspan; //주문 상품수
						if($goods_cnt < 2){
							$goods_name = $this->funn->StringCut($r->goods_name, 44, "..");
						}else{
							$goods_name = $this->funn->StringCut($r->goods_name, 36, "..") ." 외 ". ($r->rowspan-1) ."건";
						}
			?>
            <tr>
              <td ><?=$r->orderno?></td><? //주문번호 ?>
              <td>
                <p title="<?=$r->creation_date?>"><?=date('Y.m.d', strtotime($r->creation_date))?></p><? //주문정보 : 주문일자 ?>
                <p class="list_name"><?=$r->receiver?></p><? //주문정보 : 주문자 성명 ?>
                <p class="list_contact"><?=$this->funn->format_phone($r->phnno, "-")?></p><? //주문정보 : 주문자 연락처 ?>
              </td>
              <td class="tl"><?=trim($r->postcode ." ". $r->addr1 ." ". $r->addr2)?></td><? //배송지 ?>
              <td><?=$this->funn->get_charge_type($r->charge_type)?></td><? //결제정보 ?>
              <td >
                <!--상세내역 펼쳐보기-->
                <ul id="idOrderDetail<?=$no?>" class="order_detail_wrap" style="display:;">
				<?
					} //if($pre_order_id != $r->order_id) {
						$delivery_amt = $r->deliv_amt; //배송비
						//$delivery_amt = $delivery_amt + $add_deliv_amt;
						$pre_amount = $r->pre_amount;
						$pre_deliv = $r->deliv_amt;
						$subamt = $r->amount; //총상품금액 (주문수량 계산된 금액)
						$totalamt = $r->all_amount; //$totalamt + $subamt; //총결제금액
						$can_amount = $r->can_amount;

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
				?>
                  <li>
                    <div class="order_detail">
                      <div class="p-name">
                        <p style="<?=($r->cal_yn=='Y')?'text-decoration:line-through':''?>"><?=($r->rowspan > 1) ? ($dcnt+1) ."." : ""?><?=trim($r->goods_name ." ". $r->goods_option)?><? //상품명 규격 ?></p>
                      </div>
                      <div class="count"><?=$r->qty?>개</div><? //갯수 ?>
                      <div class="price"><?=number_format($subamt)?>원</div><? //총상품금액 ?>
											<?=!empty($r->barcode) ? '<div id="goods_idx_'.($dcnt+1).'" class="barcode_box" style="width:100%; display:inline-block; margin-bottom:10px;" name="code_box" barcode_num="'.$r->barcode.'"></div>':'' //20211221 조지영 제품 바코드 출력 ?>
                    </div>
                  </li>
				 <?
				 $dcnt++;
				 //log_message("ERROR", "ddd : ".$pre_order_id .'/'. $r->order_id.'/'.$dcnt.'/'.$totalamt.'/'.$r->rowspan );
				 if($dcnt >= $r->rowspan) {
					$dcnt = 0;
				 ?>
                 <li class="flex"<? if($delivery_amt == 0){ ?> style="display:none;"<? } ?>><div class="flex-1 tl">배송비</div><div class="flex-1 tr"><?=number_format($delivery_amt)?>원</div></li><? //배송비 ?>
                </ul>
                <!--//상세내역 펼쳐보기-->
                <div id="idAskInfo<?=$no?>"<? if($r->user_req != ""){ ?> class="ask_info"<? } ?> style="display:;">
                  <? if($r->user_req != ""){ ?>
				  				<div class="ask_q">추가주문 및 요청사항</div>
                  <div class="ask_a"><?=nl2br($r->user_req)?></div>
				  				<? } ?>
                </div>
								<? if(!empty($r->s_reason)){ ?>
								<div class="ask_info">
									<div class="ask_q">부분취소사유</div>
									<div class="ask_a"><?=nl2br($r->s_reason)?></div>
								</div>
								<? } ?>
              </td>
              <td class="order_total_price tr">
								<p><?=number_format(floor( ($itotal+$r->price_change) / 10 ) * 10)?>원</p><? //전체금액 ?>
								<? if($r->can_amount > 0||$r->price_change != 0){ ?>
								<div class="order_cancle_box">

									<p><span>[주문금액]</span><?=number_format($totalamt)?>원
										<?
										if($r->deliv_amt!=0){
										?>
										<br/><span>* 배송비</span><?=number_format($r->deliv_amt)?>원
										<?
										}
										?>
										<?
										if($r->deliv_amt!=0||$r->dc_amt!=0){
										?>
										<br/><span>* 결제금액</span><?=number_format($pre_amount - $r->dc_amt + $r->deliv_amt )?>원
										<?
										}
										?>
									</p>
									<ul>
										<? if($r->can_amount > 0){ ?>
										<li><span>* 부분취소</span>-<?=number_format($r->can_amount)?>원</li>
										<? } ?>
										<?
										if($mdeliv_price!=0){
										?>
										<li><span>* 배송비부과</span>+<?=number_format($mdeliv_price)?>원</li>
										<?
										}
										if($r->price_change!=0){
										?>
										<li><span>* 금액조정</span><?=($r->price_change>0)?'+' :''?><?=number_format($r->price_change)?>원</li>
										<?
										}
										?>

									</ul>
								</div>
								<? } ?>

							</td><? //결제금액 ?>
              <td><?=$this->funn->get_order_status($r->status)?><? if($r->status=='4'){  ?><br/><?=($r->calp=='S')? '(판매자)' : '(구매자)' ?> <? } ?></td><? //진행상태 ?>
              <td class="tl"><?=nl2br($r->mng_note)?>
								<? if(!empty($r->sc_reason)){ ?>
								<div class="cancel_all">
									<p>주문취소사유</p>
									<p><?=$r->sc_reason?></p>
								</div>
								<? } ?>
							</td><? //추가정보 ?>
            </tr>
			<?
						$totalamt = 0;
					}
					$pre_order_id = $r->order_id;
				}
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</page>
<script>
	  window.print();
</script>
