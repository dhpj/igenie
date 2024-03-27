								<div class="mb10 mt10">전체 : <?=number_format($total_rows)?>건</div>
									<div class="table_list">
										<table>
											 <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
											 <colgroup>
												  <col width="20%">
												  <col width="20%">
												  <col width="20%">
												  <col width="40%">
											 </colgroup>
											 <thead>
											 <tr>
	
												  <th>등록일</th>
												  <th>전화번호</th>
												  <th>상태</th>
												  <!-- 추가수정 수신거부, 메모 기능(메모,수정 타이틀 추가) -->
												  <th>메모</th>
												  
											 </tr>
											 </thead>
											 <tbody>
											 		<?foreach($list as $row) {?>
													<tr>
														<td><?=$row->ab_datetime?></td>
														<td name="phn"><?=$row->ab_tel?></td>
														<!-- 추가 수정 수신거부, 메모 기능(드롭박스로 변경, 인풋 추가, 수정버튼 생성) -->
														<td><select id="status_box" name="status_box" class="select2 input-width-small" disabled style="cursor: default;">
															<option value="1" <?=($row->ab_status) ? 'selected' : ''?>>정상</option>
															<option value="0" <?=($row->ab_status) ? '' : 'selected'?>>수신거부</option>
														</select></td>
														<td><input type="text" style="width: 100%;" id="memo" name="memo" value="<?=$row->ab_memo?>" disabled></td>
													</tr>
													<?}?>
											 </tbody>
										</table>
										<div class="tc"><?echo $page_html?></div>
									</div>
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
