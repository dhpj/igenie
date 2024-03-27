								<div class="mg_b20" style="font-size:15px;">전체 : <?=number_format($total_rows)?>건</div>
								<div align="left">
									<table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
										 <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
										 <colgroup>

											  <col width="20%">
											  <col width="15%">
											  <col width="15%">
											  <col width="30%">
											  <col width="10%">
										 </colgroup>
										 <thead>
										 <tr p style="cursor:default">

											  <th class="text-center">등록일</th>
											  <th class="text-center">전화번호</th>
											  <th class="text-center">상태</th>
											  <!-- 추가수정 수신거부, 메모 기능(메모,수정 타이틀 추가) -->
											  <th class="text-center">메모</th>

										 </tr>
										 </thead>
										 <tbody>
										<?foreach($list as $row) {?>
												 <tr>
													 <td class="text-center"><?=$row->ab_datetime?></td>
													 <td class="text-center" name="phn"><?=$row->ab_tel?></td>
													 <!-- 추가 수정 수신거부, 메모 기능(드롭박스로 변경, 인풋 추가, 수정버튼 생성) -->

													 <td class="text-center"><select id="status_box" name="status_box" class="select2 input-width-small" disabled style="cursor: default;">
														 <option value="1" <?=($row->ab_status) ? 'selected' : ''?>>정상</option>
														 <option value="0" <?=($row->ab_status) ? '' : 'selected'?>>수신거부</option>
													 </select></td>
													 <td class="text-center"><input type="text" class="input-width-large" id="memo" name="memo" value="<?=$row->ab_memo?>" disabled></td>

												 </tr>
										<?}?>
										 </tbody>
									</table>
								</div>
								<div class="page_cen"><?echo $page_html?></div>

								<style>
									 .text-center {
										  vertical-align: middle !important;
									 }
								</style>

								<script type="text/javascript">


									 $( document ).ready(function() {

										  $('tr input').uniform();
									 });

									 $('tbody tr').click(function() {
										  var check = $(this).find('.checker').children('span').is('.checked');

										  if(check == true) {
												$(this).find('td input[type="checkbox"]').prop('checked', true);
												$(this).addClass('checked');
										  } else {
												$(this).find('td input[type="checkbox"]').prop('checked', false);
												$(this).removeClass('checked');
										  }
										  $.uniform.update();
									 });

									 //수신번호 전체 선택 안되는 현상 수정
									 $("#sel_all").click(function(){
										  if($("#sel_all").prop("checked")) {
												$("input:checkbox[name='selCustomer']").prop("checked",true);
												$.uniform.update();
										  } else {
												$("input:checkbox[name='selCustomer']").prop("checked",false);
												$.uniform.update();
									 }
									 });

								</script>
