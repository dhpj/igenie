                <div class="widget" style="margin:20px 0 0 0">
                     <div class="widget-content">
                            <? if(count($list) > 0) {?>
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <? foreach($list as $r) {?>
                                <tr>
                                    <th for="id_title" >제목</th>
                                    <td colspan = "3" >
                                       <?=$r->title?>
									</td>
                                </tr>
                                <tr>
                                  <th for="id_message">내용</th>
                                  <td colspan = "3">
                                      <div class="pop_message"><?=nl2br($r->msg)?> </div>
                                  </td>
                                </tr>
							    <tr>
                                <th for="id_company_name">시작일 </th>
	                                <td>
    	           						 <?=$r->send_date?>
        	                        </td>
								<th for="id_userid">종료일</th>
									<td>
                						 <?=$r->expiration_date?>
									</td>
								 </tr>
								 <? } ?>
                            </table>
                            <? } else { ?>
                            	<div class="align-center ">데이타가 존재 하지 않습니다.</div>
                            <?}?>
                    </div>
                    <input type="hidden" id="amcurrentpage" value="<?=$currentpage?>">
			<!-- <div class="align-center "><?=$page_html?></div> -->
    </div>
