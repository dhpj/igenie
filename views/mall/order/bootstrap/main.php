<div class="tit_wrap">
	주문관리
</div>
<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>주문내역 (<?=number_format($total_rows)?>건)</h3>
    </div>
    <div class="white_box">
        <div class="info_summery">
          <div id="current_time"><?=date("Y년 n월 j일")?></div>
          <ul>
            <li>
              <span class="info_icon"><i class="material-icons">grid_on</i></span>
              <div class="infodetail"><p>전체주문내역</p><strong><?=(empty($order_cnt))?'0':number_format($order_cnt)?></strong><em>건</em></div>
            </li>
            <li>
              <span class="info_icon"><i class="material-icons">check_circle_outline</i></span>
              <div class="infodetail"><p><?=$this->funn->get_order_status("0")//신규주문?></p><strong><?=(empty($order_com))?'0':number_format($order_com)?></strong><em>건</em></div>
            </li>
            <li>
              <span class="info_icon"><i class="material-icons">add_shopping_cart</i></span>
              <div class="infodetail"><p><?=$this->funn->get_order_status("1")//상품준비중?></p><strong><?=(empty($order_pre))?'0':number_format($order_pre)?></strong><em>건</em></div>
            </li>
            <li>
              <span class="info_icon"><i class="material-icons">local_shipping</i></span>
              <div class="infodetail"><p><?=$this->funn->get_order_status("2")//배송중?></p><strong><?=(empty($order_dlv))?'0':number_format($order_dlv)?></strong><em>건</em></div>
            </li>
            <li>
              <span class="info_icon"><i class="material-icons">done_outline</i></span>
              <div class="infodetail"><p><?=$this->funn->get_order_status("3")//배송완료?></p><strong><?=(empty($order_fin))?'0':number_format($order_fin)?></strong><em>건</em></div>
            </li>
            <li>
              <span class="info_icon"><i class="material-icons">cancel</i></span>
              <div class="infodetail"><p><?=$this->funn->get_order_status("4")//주문취소?></p><strong><?=(empty($order_can))?'0':number_format($order_can)?></strong><em>건</em></div>
            </li>
          </ul>
        </div>
    </div>
    <div class="white_box mg_t20">
      <div class="mall_box_top">
        <div class="row_label">기간</div>
        <div class="row_contents">
          <div class="date_radio" style="margin-right: 15px;">
            <input type="radio" class="date_radio_btn" name="order_period" id="today" value="today" onclick="order_period();" <? if($param['order_period'] =='today') { echo 'checked'; }?>><label for="today">오늘</label>
            <input type="radio" class="date_radio_btn" name="order_period" id="week" value="week" onclick="order_period();" <? if($param['order_period'] =='week' or $param['order_period'] =='') { echo 'checked'; }?>><label for="week">일주일</label>
            <input type="radio" class="date_radio_btn" name="order_period" id="month" value="month" onclick="order_period();" <? if($param['order_period'] =='month') { echo 'checked'; }?>><label for="month">한달</label>
            <input type="radio" class="date_radio_btn" name="order_period" id="3month" value="3month" onclick="order_period();" <? if($param['order_period'] =='3month') { echo 'checked'; }?>><label for="3month">3개월</label>
            <input type="radio" class="date_radio_btn" name="order_period" id="6month" value="6month" onclick="order_period();" <? if($param['order_period'] =='6month') { echo 'checked'; }?>><label for="6month">6개월</label>
            <input type="radio" class="date_radio_btn" name="order_period" id="year" value="year" onclick="order_period();" <? if($param['order_period'] =='year') { echo 'checked'; }?>><label for="year">1년</label>
          </div>
          <input type="text" id="start_date" class="calendar hasDatepicker" value="<?=$param['start_date']?>" placeholder="검색 시작일" style="width:110px;"> ~
          <input type="text" id="end_date" class="calendar hasDatepicker" value="<?=$param['end_date']?>" placeholder="검색 종료일" style="width:110px;">
        </div>
      </div>
      <div class="row_wrap">
        <div class="row_label">상세검색</div>
        <div class="row_contents">
          <select id="status" class="onchange_search" style="margin-right:5px;" onChange="open_page(1);">
						<option value="">주문내역선택</option>
            <option value="10" <? if($param['status'] =='10') { echo 'selected'; }?>>전체주문내역</option>
            <option value="0" <? if($param['status'] =='0') { echo 'selected'; }?>><?=$this->funn->get_order_status("0")//신규주문?></option>
            <option value="1" <? if($param['status'] =='1') { echo 'selected'; }?>><?=$this->funn->get_order_status("1")//상품준비중?></option>
            <option value="2" <? if($param['status'] =='2') { echo 'selected'; }?>><?=$this->funn->get_order_status("2")//배송중?></option>
            <option value="3" <? if($param['status'] =='3') { echo 'selected'; }?>><?=$this->funn->get_order_status("3")//배송완료?></option>
            <option value="4" <? if($param['status'] =='4') { echo 'selected'; }?>><?=$this->funn->get_order_status("4")//주문취소?></option>
          </select>
          <select id="charge_type" class="onchange_search" style="margin-right:5px;" onChange="open_page(1);">
            <option value="">전체결제방법</option>
            <option value="1" <?=($param['charge_type']=='7')?'selected':''?>>현장결제</option>
            <option value="1" <?=($param['charge_type']=='1')?'selected':''?>>현장결제(카드)</option>
            <option value="2" <?=($param['charge_type']=='2')?'selected':''?>>현장결제(현금)</option>
			<option value="3" <?=($param['charge_type']=='3')?'selected':''?>>현장결제(지역화폐)</option>
            <option value="4" <?=($param['charge_type']=='4')?'selected':''?>>계좌이체</option>
            <option value="5" <?=($param['charge_type']=='5')?'selected':''?>>카드결제(집배송)</option>
            <option value="5" <?=($param['charge_type']=='6')?'selected':''?>>카드결제(방문)</option>
          </select>
          <select id="sc">
            <option value="receiver" <? if($param['sc'] =='receiver') { echo 'selected'; }?>>주문자명</option>
            <option value="phnno" <? if($param['sc'] =='phnno') { echo 'selected'; }?>>전화번호</option>
            <option value="user_req" <? if($param['sc'] =='user_req') { echo 'selected'; }?>>추가정보</option>
          </select>
          <input type="text" id="sv" value="<?=$param['sv']?>" style="margin-left:5px;" placeholder="검색내용" onkeypress="javascript:if(event.keyCode==13) { open_page(1); }">
          <button class="btn md dark" style="margin-left:5px;cursor:pointer;" onclick="open_page(1)">검색</button>
          <select id="per_page" class="select_op onchange_search fr" onChange="open_page(1);">
            <option value="10" <?=($perpage == 10)?'selected':''?>>10개씩</option>
            <option value="25" <?=($perpage == 25)?'selected':''?>>25개씩</option>
            <option value="50" <?=($perpage == 50)?'selected':''?>>50개씩</option>
            <option value="100" <?=($perpage == 100)?'selected':''?>>100개씩</option>
          </select>
					<span class="fr total_num">총 <strong><?=$search_cnt?></strong> 건</span>
        </div>
      </div>
    </div>
    <div class="white_box mg_t20">
      <div class="widget-content" id="append_list">
        <div class="table_top">
          <div class="fr">
					<span class="order_t_info">* 결제금액을 조정하실때 차감하는 경우는 음수(-)로 입력해주세요!</span>
          <button class="btn md print" onclick="print_list();">전체 주문내역 프린트</button>
          <button class="btn md excel" onclick="download()">엑셀 다운로드</button>
          </div>
          선택한 주문 상태 변경
          <select style="margin-left: 15px;" id="change_status">
            <option value="">변경할 상태 선택</option>
            <option value="1"><?=$this->funn->get_order_status("1")//상품준비중?></option>
            <option value="2"><?=$this->funn->get_order_status("2")//배송중?></option>
            <option value="3"><?=$this->funn->get_order_status("3")//배송완료?></option>
            <option value="4"><?=$this->funn->get_order_status("4")//주문취소?></option>
          </select>
					<input id="cancel_reason" type="text" placeholder="주문취소사유입력"  onkeypress="javascript:if(event.keyCode==13) { sel_change_status(); }" style="display:none;"/>
          <button class="btn md dark" onclick="sel_change_status();" style="margin-left:5px;">변경저장</button>
        </div>
        <table class="order">
          <colgroup>
            <col style="width: 40px;"><?//체크?>
            <col style="width: 100px;"><?//주문번호?>
            <col style="width: 120px;"><?//주문정보?>
            <col style="width:;"><?//배송지?>
            <col style="width: 90px;"><?//결제정보?>
            <col style="width: 350px;"><?//상품정보?>
            <col style="width: 150px;"><?//결제금액?>
            <col style="width: 85px;"><?//진행상태?>
            <col style="width:;"><?//추가정보?>
            <col style="width: 60px;"><?//프린트?>
          </colgroup>
          <thead>
            <input type="hidden" id="idGoodsViewFl" value="CLOSE">
            <tr>
              <th>
                <label class="checkbox_container" for="check_all">
                  <input type="checkbox" id="check_all">
                  <span class="checkmark"></span>
                </label>
              </th>
              <th>주문번호</th>
              <th>주문정보</th>
              <th>배송지</th>
              <th>결제정보</th>
              <th>상품정보 <button class="btn_goods_detail fr" onClick="jsGoodsView('ALL');"><span id="idGoodsViewText">전체열기</span></button></th>
              <th>결제금액</th>
              <th>진행상태</th>
              <th>추가정보</th>
              <th>프린트</th>
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
              <td>
                  <label class="checkbox_container" for="chk_<?=$r->order_id?>">
                    <input type="hidden" id="org_status_<?=$r->order_id?>" value="<?=$r->status?>">
                    <input type="hidden" id="org_charge_type_<?=$r->order_id?>" value="<?=$r->charge_type?>">
                    <input type="checkbox" id="chk_<?=$r->order_id?>" class="chk">
                    <span class="checkmark"></span>
                  </label>
              </td>
              <td ><?=$r->orderno?></td><? //주문번호 ?>
              <td>
                <p title="<?=$r->creation_date?>"><?=date('Y.m.d', strtotime($r->creation_date))?></p><? //주문정보 : 주문일자 ?>
                <p class="list_name"><?=$r->receiver?></p><? //주문정보 : 주문자 성명 ?>
                <p class="list_contact"><?=$this->funn->format_phone($r->phnno, "-")?></p><? //주문정보 : 주문자 연락처 ?>
              </td>
              <td class="tl"><?=trim($r->postcode ." ". $r->addr1 ." ". $r->addr2)?></td><? //배송지 ?>
              <td><?=$this->funn->get_division_type($r->division)?><?if(!empty($r->division)){?><br><?}?><?=$this->funn->get_charge_type($r->charge_type)?></td><? //결제정보 ?>
              <td >
                <div class="order_goods_topbox">
                  <button class="btn_goods_detail fr" onClick="jsGoodsView('<?=$no?>');"><span id="idGoodsView<?=$no?>">상세보기</span></button>
                  <p class="text_goods_info tl"><?=$goods_name?></p>
                </div>
                <!--상세내역 펼쳐보기-->
                <ul id="idOrderDetail<?=$no?>" class="order_detail_wrap" style="display:none;">
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

										$chk_dis="";
										if($r->realrow>1&&$r->status==0&&$r->cal_yn=='N'){

										}else{
											 $chk_dis = 'disabled';
										}
										?>
                  <li>
                    <div class="order_detail">
											<label class="checkbox_container" for="chk_good_<?=$r->gid?>">
	                      <input id="chk_good_<?=$r->gid?>" name="chk_good_<?=$r->order_id?>" type="checkbox" class="chk2" value="<?=$r->gid?>"  onchange="onChangeHandler('<?=$r->order_id?>','<?=$r->gid?>', '<?=$r->dcprice?>')" <?=$chk_dis?>>
	                      <span class="checkmark"></span>
	                    </label>
                      <!--div class="photo"><img src="<?=str_replace('/var/www/mall','',$r->thumb_path)?>"></div-->
                      <div class="p-name">
                        <p><span style="<?=($r->cal_yn=='Y')?'text-decoration:line-through':''?>"><?=($r->rowspan > 1) ? ($dcnt+1) ."." : ""?><?=trim($r->goods_name ." ". $r->goods_option)?><? //상품명 규격 ?></span>

                      </div>
                      <div class="count"><?=$r->qty?>개</div><? //갯수 ?>
                      <div class="price"><?=number_format($subamt)?>원</div><? //총상품금액 ?>
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
                <?if($r->charge_type != '5' && $r->charge_type != '6'){?>
								<div id="idCancelInfo<?=$no?>" class="good_cancel" style="display:none;">
									<input type="hidden" id="hdn_good_<?=$r->order_id?>" value="0"/>
									<input type="hidden" id="hdn_realrow_<?=$no?>" value="<?=$r->realrow?>"/>
									<input type="hidden" id="hdn_status_<?=$no?>" value="<?=$r->status?>"/>
									<input type="text" class="mg_b5" id="gc_input_<?=$r->order_id?>" style="width: 100%;" value="<?=$r->s_reason?>" placeholder="부분취소 사유입력">
									<div class="fl">
										<label class="checkbox_container" for="chk_goodall_<?=$r->order_id?>">
											<input id="chk_goodall_<?=$r->order_id?>" type="checkbox" class="chk2">
                      <span class="checkmark"></span>
                    </label>
										<span class="good_cancel_txt">선택상품을 부분취소합니다.</span>
									</div>
									<button class="fr" onclick="onchange_cancel('<?=$r->order_id?>', '<?=$iprice?>', '<?=$delivery_amt?>', '<?=$r->free_delivery_amt?>', '<?=$r->delivery_amt?>', '<?=$r->charge_type?>')">부분취소</button><?}?>
								</div>

                <!--//상세내역 펼쳐보기-->
								<div id="idAskInfo<?=$no?>" class="ask_info" style="display:none;">
								<input type="hidden" id="idAskInfoYn<?=$no?>" value="<? if($r->user_req != "" ){ ?>Y<? } ?>">
								<div class="ask_q">추가주문 및 요청사항</div>
								<div class="ask_a"><?=nl2br($r->user_req)?></div>
							</div>
								<input type="hidden" id="hdn_s_reason<?=$no?>" value="<?=$r->s_reason?>"/>
							<div id="idReasonInfo<?=$no?>" class="ask_info" style="display:none;">
								<input type="hidden" id="idReasonInfoYn<?=$no?>" value="<? if(!empty($r->s_reason)){ ?>Y<? } ?>">
								<div class="ask_q">부분취소사유</div>
								<div class="ask_a"><?=$r->s_reason?></div>
							</div>
							<input type="hidden" id="hdn_c_reason<?=$no?>" value="<?=$r->c_reason?>"/>
							<div id="idcReasonInfo<?=$no?>" class="ask_info" style="display:none;">
								<input type="hidden" id="idcReasonInfoYn<?=$no?>" value="<? if(!empty($r->c_reason)){ ?>Y<? } ?>">
								<div class="ask_q">고객부분취소사유</div>
								<div class="ask_a"><?=$r->c_reason?></div>
							</div>
                <!--<div id="idAskInfo<?=$no?>"<? if($r->user_req != ""){ ?> class="ask_info"<? } ?> style="display:none;">
                  <? if($r->user_req != ""){ ?>
				        <div class="ask_q">추가주문 및 요청사항</div>
                  <div class="ask_a"><?=nl2br($r->user_req)?></div>
				          <? } ?>
                </div>-->
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
                    <?
                        if ($r->charge_type != "5"){
                    ?>
                  <button class="price_change_btn" onclick="price_change_show('<?=$r->order_id?>')"><i class="xi-won"></i>결제금액조정</button>
                  <?
                    }
                  ?>
                  <div class="price_change" id="price_change_div_<?=$r->order_id?>" style="display:none;">
										<input type="text" class="input_pc" id="price_change_<?=$r->order_id?>" value="<?=$r->price_change?>" placeholder="예)9900"> <button onclick="changeprice('<?=$r->order_id?>', '<?=$r->charge_type?>')">확인</button>
									</div>
							</td>

              <td><?=$this->funn->get_order_status($r->status)?><? if($r->status=='4'){ ?><?=($r->calp=='S')? '(판매자)' : '(구매자)' ?> <? } ?></td><? //진행상태 ?>
              <td><input type="text" class="goods_manager_change" id="<?=$r->order_id?>" style="width: 100%;" value='<?=str_replace("'", "‘", $r->mng_note)?>'>
								<? if($r->status=='4'){ if(!empty($r->sc_reason)){ ?>
								<div class="cancel_all">
									<p>주문취소사유</p>
									<p><?=$r->sc_reason?></p>
								</div>
							<? } } ?>
							</td><? //추가정보 ?>
              <td><i class="material-icons print_unit" onclick="window.open('print_unit/<?=$r->order_id?>','print_unit','width=800,height=800,location=no,status=no,scrollbars=yes');" style="border: 1px solid #dedede; border-radius: 3px; padding: 4px; cursor: pointer;">print</i></td>
            </tr>
			<?
						$totalamt = 0;
					}
					$pre_order_id = $r->order_id;
				}
			?>
          </tbody>
        </table>
        <div class="page_cen"><?=$page_html?></div>
      </div>
    </div>
  </div>
</div>
<script>

$(document).ready( function() {
			//숫자만
			$(".input_pc").keyup(function(event){
					var str;
					if(event.keyCode != 8){
							if (!(event.keyCode >=37 && event.keyCode<=40)) {
									var inputVal = $(this).val();
									str = inputVal.replace(/[^-0-9]/gi,'');
									if(str.lastIndexOf("-")>0){ //중간에 -가 있다면 replace
											if(str.indexOf("-")==0){ //음수라면 replace 후 - 붙여준다.
													str = "-"+str.replace(/[-]/gi,'');
											}else{
													str = str.replace(/[-]/gi,'');
											}
									}
									$(this).val(str);
							}
					}
			});

			$('#change_status').change(function(){
				 var selectstatus = $('#change_status').val();
				 if(selectstatus=='4'){
					 //alert(selectstatus+"선택");
					 $('#cancel_reason').show();
				 }else{
					 $('#cancel_reason').hide();
				 }
			})
	});
	//기간 시작일자 클리시
	$("#start_date").datepicker({
		format: "yyyy-mm-dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});

	//기간 종료일자 클리시
	$("#end_date").datepicker({
		format: "yyyy-mm-dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		endDate: "-0d",
		autoclose: true
	});

	$(function() {
		$(".onchange_search").change(function(){
			open_page(1);
		});
	});

	$(function() {
		$(".goods_manager").change(function(){
			//console.log($(this).val());
			var id = $(this).attr("id");
			var manager = $(this).val();
			$.ajax({
				url: "/ordermng/changemanager",
				type: "POST",
				data: {"goodsid":id, "manager":manager},
				success: function (json) {

	 			}
			});

		});
	});

	$(function(){
	    $("#check_all").click(function(){ //전체선택
	        var chk = $(this).is(":checked");//.attr('checked');
	        if(chk) $(".tbody .chk").prop('checked', true);
	        else  $(".tbody .chk").prop('checked', false);
	    });
	});

	//기간선택
	function order_period(){
		var order_period = $(":input:radio[name=order_period]:checked").val(); //주문기간
		if(order_period == "today"){ //오늘
			$("#start_date").val("<?=date("Y-m-d")?>");
			$("#end_date").val("<?=date("Y-m-d")?>");
		}else if(order_period == "week"){ //일주일
			$("#start_date").val("<?=date("Y-m-d", strtotime("-1 week"))?>");
			$("#end_date").val("<?=date("Y-m-d")?>");
		}else if(order_period == "month"){ //한달
			$("#start_date").val("<?=date("Y-m-d", strtotime("-1 month"))?>");
			$("#end_date").val("<?=date("Y-m-d")?>");
		}else if(order_period == "3month"){ //3개월
			$("#start_date").val("<?=date("Y-m-d", strtotime("-3 month"))?>");
			$("#end_date").val("<?=date("Y-m-d")?>");
		}else if(order_period == "6month"){ //6개월
			$("#start_date").val("<?=date("Y-m-d", strtotime("-6 month"))?>");
			$("#end_date").val("<?=date("Y-m-d")?>");
		}else if(order_period == "year"){ //1년
			$("#start_date").val("<?=date("Y-m-d", strtotime("-1 year"))?>");
			$("#end_date").val("<?=date("Y-m-d")?>");
		}
		open_page(1);
	}

	//검색
	function open_page(page){
		var url = "?page="+ page; //현재 페이지
		if($("#per_page").val() != "") url += "&per_page="+ $("#per_page").val(); //목록수
		var order_period = $(":input:radio[name=order_period]:checked").val(); //주문기간
		if(order_period != "") url += "&order_period="+ order_period; //주문기간
		if($("#start_date").val() != "") url += "&start_date="+ $("#start_date").val(); //시작일
		if($("#end_date").val() != "") url += "&end_date="+ $("#end_date").val(); //종료일
		if($("#status").val() != "") url += "&status="+ $("#status").val(); //진행상태
		if($("#charge_type").val() != "") url += "&charge_type="+ $("#charge_type").val(); //결재방법
		if($("#sc").val() != "" && $("#sv").val() != "") url += "&sc="+ $("#sc").val() + "&sv="+ $("#sv").val(); //검색내용
		location.href = url;
    }

	//선택한 주문 상태 변경
	function sel_change_status(){
		//alert("sel_change_status"); return;
		var num = $(".chk:checked").length;
		var cnt = 0;
		var pass_flag = 'N';
		var msg="";
		var all_status = $("#change_status").val();
		var sc_reason = $("#cancel_reason").val();
		if(all_status=='4'){
			if(Number(num)==1){
				pass_flag = 'Y';

			}else{
				alert("주문취소는 1개씩 가능합니다");
				return;
			}
		}else{
			pass_flag = 'Y';
		}
		//alert("all_status : "+ all_status); return;
		if(all_status != ""&&pass_flag == 'Y'){
			$(".chk").each(function() {
				var chk = $(this).is(":checked");
				if(chk){
					cnt++;
					var oid = $(this).attr("id").replace(/[^0-9]/g,"");
					var org_status = $("#org_status_" + oid).val();
                    var org_charge_type = $("#org_charge_type_" + oid).val();
                    if (($("#org_status_" + oid).val() == "3" && all_status == "4") ||($("#org_status_" + oid).val() == "2" && all_status == "4")){
                        msg = "배송중 및 배송완료 상품은 주문취소할 수 없습니다.";
                        return;
                    }
					if(org_status==all_status){
						if(all_status=='4'){
							//alert("이미 취소된 주문입니다");
							msg = "이미 취소된 주문입니다";
							return;
						}
					}else{
						changestatus(oid, all_status, org_status, sc_reason, org_charge_type);
						del_flag = 'Y';
					}

				}
			});
			if(cnt > 0){
				//alert("주문상태가 변경 되었습니다.");
				//location.href = location.href;
				if(msg!=''){
					showSnackbar(msg, 1500);
				}else{
					showSnackbar("주문상태가 변경 되었습니다.", 1500);
				}

				setTimeout(function (){
					location.href = location.href;
				}, 1500);
			}else{
				alert("선택된 주문 내역이 없습니다.");
				return;
			}
		}else{
			alert("변경할 상태를 선택하세요.");
			return;
		}
	}

	function price_change_show(id){
		$('#price_change_div_'+id).show();
	}
	//결제금액조정
	//주문 상태 변경 처리
	function changeprice(id, type){
		var price = $('#price_change_'+id).val();
		if(price==''){
			alert("결제조정금액을 넣어주세요");
			$('#price_change_'+id).focus();
			return;
		}
        if (type == "5" & Number(price) > 0){
            alert("카드결제의 경우 금액을 더하실 수 없습니다");
            $('#price_change_'+id).focus();
			return;
        }
		$.ajax({
			url: "changeprice",
			type: "POST",
			data: {"id" : id, "price" : price, "type" : type, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json){
				if(json.code=='1'){
					alert(json.msg);
					setTimeout(function (){
						location.href = location.href;
					}, 1000);
				}else{
					alert(json.msg);
				}
 			}
		});
	}

	//주문 상태 변경 처리
	function changestatus(oid, status, org_status, sc_reason, org_charge_type){
		$.ajax({
			url: "changestatus",
			type: "POST",
			data: {
                "oid" : oid
              , "status" : status
              , "org_status" : org_status
              , "sc_reason" : sc_reason
              , "charge_type" : org_charge_type
              , "cal_person" : 'S'
              , "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json){
 			}
		});
	}

	//전체 주문내역 프린트
	function print_list() {
		//alert("print_list"); return;
		var url = "print_list?start_date="+ $("#start_date").val(); //시작일
		if($("#end_date").val() != "") url += "&end_date="+ $("#end_date").val(); //종료일
		if($("#status").val() != "") url += "&status="+ $("#status").val(); //진행상태
		if($("#charge_type").val() != "") url += "&charge_type="+ $("#charge_type").val(); //결재방법
		if($("#sc").val() != "" && $("#sv").val() != "") url += "&sc="+ $("#sc").val() + "&sv="+ $("#sv").val(); //검색내용
		window.open(url, 'print_list', 'width=1200,height=680,location=no,status=no,scrollbars=yes');
    }

	//엑셀 다운로드
	function download(){
        //alert("download"); return;
		var url = "excel_down?start_date="+ $("#start_date").val(); //시작일
		if($("#end_date").val() != "") url += "&end_date="+ $("#end_date").val(); //종료일
		if($("#status").val() != "") url += "&status="+ $("#status").val(); //진행상태
		if($("#charge_type").val() != "") url += "&charge_type="+ $("#charge_type").val(); //결재방법
		if($("#sc").val() != "" && $("#sv").val() != "") url += "&sc="+ $("#sc").val() + "&sv="+ $("#sv").val(); //검색내용
		location.href = url;
    }

	//상품정보 상세보기
	function jsGoodsView(id){
		//alert("id : "+ id);
		if(id == "ALL"){ //전체 상세보기
			var no = <?=$no?>;
			var idGoodsViewFl = document.getElementById("idGoodsViewFl").value; //전체보기 (CLOSE, OPEN)
			var idGoodsViewText = document.getElementById("idGoodsViewText");
			//alert("idGoodsViewFl : "+ idGoodsViewFl);
			if(idGoodsViewFl == "CLOSE"){ //전체닫기 상태
				document.getElementById("idGoodsViewFl").value = "OPEN";
				idGoodsViewText.innerHTML = "전체닫기";
			}else{
				document.getElementById("idGoodsViewFl").value = "CLOSE";
				idGoodsViewText.innerHTML = "전체보기";
			}
			var btn = ""; //상세보기 버튼
			var id1 = ""; //주문 상품 영역
			var id2 = ""; //주문상품이 품절되었을 경우 영역
			for(var i=1; i<=no; i++) {
				//alert("i : "+ i +", idGoodsViewFl : "+ idGoodsViewFl);
				var btn = document.getElementById("idGoodsView"+ i); //상세보기 버튼
				var id1 = document.getElementById("idOrderDetail"+ i); //주문 상품 영역
				var id2 = document.getElementById("idAskInfo"+ i); //주문상품이 품절되었을 경우 영역

				var idAskInfoYn = document.getElementById("idAskInfoYn"+ i).value; //주문시 요청사항 답변 여부
				var hdn_realrow = $("#hdn_realrow_"+i).val(); //주문상품수
				var hdn_status = $("#hdn_status_"+i).val(); //주문상태
				var hdn_s_reason = $('#hdn_s_reason'+i).val(); //판매자 부분취소사유
				var hdn_c_reason = $('#hdn_c_reason'+i).val(); //고객 부분취소사유

				if(idGoodsViewFl == "CLOSE"){ //전체닫기 상태
					id1.style.display = "block";
					if(idAskInfoYn == "Y"){
						id2.style.display = "block";
					}else{
						id2.style.display = "none";
					}
					if(hdn_status==0 && hdn_realrow >1){
							$('#idCancelInfo'+i).show();
					}
					if(hdn_realrow==1&&hdn_s_reason!=''||hdn_status>0&&hdn_s_reason!=''){
						$('#idReasonInfo'+i).show();
					}
					if(hdn_c_reason!=''){
						$('#idcReasonInfo'+i).show();
					}
					btn.innerHTML = "상세닫기";
				}else{
					id1.style.display = "none";
					id2.style.display = "none";
					$('#idCancelInfo'+i).hide();
					$('#idReasonInfo'+i).hide();
					$('#idcReasonInfo'+i).hide();
					btn.innerHTML = "상세보기";
				}
			}
			//alert("for exit");
		}else{ //개별 상세보기
			var btn = document.getElementById("idGoodsView"+ id); //상세보기 버튼
			var id1 = document.getElementById("idOrderDetail"+ id); //주문 상품 영역
			if(id1.style.display == "none"){
				id1.style.display = "block";
				btn.innerHTML = "상세닫기";
			}else{
				id1.style.display = "none";
				btn.innerHTML = "상세보기";
			}


			var id2 = document.getElementById("idAskInfo"+ id); //주문상품이 품절되었을 경우 영역
			var idAskInfoYn = document.getElementById("idAskInfoYn"+ id).value; //주문시 요청사항 답변 여부
			if(id2.style.display == "none"){
				if(idAskInfoYn == "Y"){
					id2.style.display = "block";
				}else{
					id2.style.display = "none";
				}
			}else{
				id2.style.display = "none";
			}

			var hdn_realrow = $("#hdn_realrow_"+id).val(); //주문상품수
			var hdn_status = $("#hdn_status_"+id).val(); //주문상태
			var id4 = document.getElementById("idCancelInfo"+ id); //고객 요청사항 영역
			//var idEtcInfoYn = document.getElementById("idEtcInfoYn"+ id).value; //고객 요청사항 답변 여부
			if(id4.style.display == "none"){
				//id4.style.display = "block";
				if(hdn_status == 0 && hdn_realrow > 1){
					$('#idCancelInfo'+id).show();
				}
			}else{
				$('#idCancelInfo'+id).hide();
				//id4.style.display = "none";
			}
			var id5 = document.getElementById("idReasonInfo"+ id); //판매자 부분취소사유 영역
			var hdn_s_reason = $('#hdn_s_reason'+i).val(); //판매자 부분취소사유
			var idReasonInfoYn = document.getElementById("idReasonInfoYn"+ id).value;
			if(id5.style.display == "none"){
				//id4.style.display = "block";
				if(idReasonInfoYn=='Y'){
					if(hdn_realrow == 1||hdn_status>0){
						$('#idReasonInfo'+id).show();
					}
				}
			}else{
				$('#idReasonInfo'+id).hide();
				//id4.style.display = "none";
			}


		}
	}

	//추가정보 업데이트
	$(".goods_manager_change").change(function(){
		//console.log($(this).val());
		var id = $(this).attr("id");
		var mng_note = $(this).val();
		$.ajax({
			url: "changemngnote",
			type: "POST",
			data: {"oid" : id, "mng_note" : mng_note, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
			}
		});
	});
	function onChangeHandler(id, gid, dcprice) {
		var iiprice = $('#hdn_good_'+id).val();
		if ($('#chk_good_'+gid).is(':checked')) {
			iiprice = Number(iiprice) + Number(dcprice);
			//alert("iiprice : "+iiprice + " dcprice : " + dcprice);
		}else{
			iiprice = Number(iiprice) - Number(dcprice);
			//alert("iiprice : "+iiprice + " dcprice : " + dcprice);
		}
		$('#hdn_good_'+id).val(iiprice);
	}

	//부분취소
	function onchange_cancel(id, iprice, deliv_amt, free_delivery_amt, delivery_amt, charge_type) {
		var cnt = 0;
		//var all_status = $("#change_status").val();
		//alert("all_status : "+ all_status); return;
		var allNum = $("input:checkbox[name=chk_good_"+id+"]").length;//전체개수
		var checkedNum = $("input:checkbox[name=chk_good_"+id+"]:checked").length;//선택된 개수
		var selectChk = [];
		if(checkedNum < allNum){
			if($("input:checkbox[id=chk_goodall_"+id+"]").is(":checked") == true) { //선택된 것이 있으면
				if(checkedNum > 0){
					$("input:checkbox[name=chk_good_"+id+"]:checked").each(function() {
						selectChk.push($(this).val());
					});

					var iiprice = $('#hdn_good_'+id).val();
					var deliv_change_yn='N';

					var delivery = Number(deliv_amt);

					var real_price = Number(iprice) - Number(iiprice);
                    var pre_real_price = Number(iprice) + delivery;

					if(Number(real_price)<Number(free_delivery_amt) && Number(iprice) > Number(free_delivery_amt)){
                        if (Number(iiprice) <= delivery){
                            alert("취소하려는 상품이 부가되는 배송비보다 싼거나 같은 가격이기 때문에 부분취소를 할 수 없습니다.");
                            return;
						deliv_change_yn='Y';
                        }
					}

					var cancel_msg = '';
					if(deliv_change_yn=='Y'){
						cancel_msg = "부분취소로 인해 배송비가 부과됩니다";
					}else{
						cancel_msg = "확인을 누르시면 부분취소가 완료됩니다";
					}

					var s_reason = $('#gc_input_'+id).val();
					if (!confirm(cancel_msg)) {
						 // 아니오
						 return false;
				 }else{
						$.ajax({
							url: "partcancel",
							type: "POST",
							data: {
                                "orderid":id
                              , "gid":selectChk
                              , "s_reason":s_reason
                              , "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
							success: function (json) {
								alert(json.msg);
                                if (json.code == "0"){
                                    setTimeout(function () {
                                        location.reload();
                                    }, 500);
                                }
				 			}
						});
					}
				}else{
					alert("부분취소할 상품을 선택하세요.");
					return;
				}
			}else{
				alert("부분취소에 동의하세요.");
				return;
			}
		}else{
			alert("주문 상품을 모두 선택할 수 없습니다.");
			return;
		}
	}
</script>
