<? $count1 = 0;
   $count2 = 0;
   $mst_qty = 0;
   $s_qty = 0;
   $e_qty = 0;
   $result_wait_count = 0;
   foreach($rs1 as $r1) {
       $count1 ++;
       $mst_qty += $r1->mst_qty;
       $s_qty =+ $r1->s_qty;
       $e_qty =+ $r1->e_qty;
   }
   $send_wait_count = $mst_qty - ($s_qty + $e_qty);
   foreach($rs2 as $r2) {
       $count2 ++;
       $result_wait_count += $r2->mst_wait;
   }
?>
                    	<table class="table t_center">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                            	<tr>
                            		<td>
                            			<div>
                                        	<div><H3>발송 지연 : <?= $count1; ?> 건 (발송 지연 문자 건수: <?= $send_wait_count; ?> 건)</H3></div><br>
                                            <table class="table table-hover table-striped table-bordered table-highlight-head t_center"
                                                   style="table-layout:fixed cellapadding=0px;">
                                                <colgroup>
                                                    <col width="10%">
                                                    <col width="20%">
                                                    <col width="50%">
                                                    <col width="20%">
                                                </colgroup>
                                                <thead>
                                                <tr p style="cursor:default">
                                                    <th>번호</th>
                                                    <th>발신/예약일자</th>
                                                    <th>업체명</th>
                                                    <th>발송개수<BR>대기/성공/실패</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                        <!-- 템플릿이슈 수정 시작 -->
                    							<?$num = 0;
                    							if ($count1 > 0) {
                    							     foreach($rs1 as $r1) { $num++;?>
                                                        <tr>
                                                            <!-- 템플릿이슈 수정 끝 -->
                                                              <td class="text-center">
                                                                 <?= $r1->mst_id?>
                                                            </td>
                                                            <td class="text-center"><?=$r1->mst_datetime?><?=(($r1->mst_reserved_dt!="00000000000000") ? ($r1->mst_ft > 0 || $r1->mst_err_ft > 0 || $r1->mst_at > 0 || $r1->mst_err_at > 0 || $r1->mst_ft_img > 0 || $r1->mst_err_ft_img > 0 || $r1->mst_grs > 0 || $r1->mst_err_grs > 0 || $r1->mst_nas > 0 || $r1->mst_err_nas > 0 || $r1->mst_grs_sms > 0 || $r1->mst_err_grs_sms > 0 || $r1->mst_nas_sms > 0 || $r1->mst_err_nas_sms > 0) ? 
                                                                "<br/><font>".$this->funn->format_date($r1->mst_reserved_dt, '-', 14)."</font>" : 
                                                                "<br/><font color='blue'>".$this->funn->format_date($r1->mst_reserved_dt, '-', 14)."</font>" : "")?></td>
                        									<td class="text-center"><? if($r1->spf_company) { echo $r1->spf_company."(".$r1->spf_friend.")"; } else { echo $r1->mem_username; } ?> </td>
                        									<td class="text-center"><?=number_format($r1->mst_qty)?><br> <? if ($r1->mst_ft > 0 || $r1->mst_err_ft > 0 ) echo "친구톡 : ".$r1->mst_ft." / ".$r1->mst_err_ft."<BR>";
                                                                                                if ($r1->mst_at > 0 || $r1->mst_err_at > 0) echo "알림톡 : ".$r1->mst_at." / ". $r1->mst_err_at."<BR>"; 
                                                                                                if ($r1->mst_ft_img > 0 || $r1->mst_err_ft_img > 0) echo "친구톡 이미지 : ".$r1->mst_ft_img." / ".$r1->mst_err_ft_img."<BR>";
                                                                                                if ($r1->mst_grs > 0 || $r1->mst_err_grs > 0) echo "웹(A) : ".$r1->mst_wait." / ".$r1->mst_grs." / ".$r1->mst_err_grs."<BR>";
                                                                                                if ($r1->mst_nas > 0 || $r1->mst_err_nas > 0) echo "웹(B) : ".$r1->mst_wait." / ".$r1->mst_nas." / ".$r1->mst_err_nas."<BR>";
                                                                                                if ($r1->mst_grs_sms > 0 || $r1->mst_err_grs_sms > 0) echo "웹(A) SMS : ".$r1->mst_wait." / ".$r1->mst_grs_sms." / ".$r1->mst_err_grs_sms."<BR>";
                                                                                                if ($r1->mst_nas_sms > 0 || $r1->mst_err_nas_sms > 0) echo "웹(B) SMS : ".$r1->mst_wait." / ".$r1->mst_nas_sms." / ".$r1->mst_err_nas_sms."<BR>";?>
                                                                                                </td>
                                                        </tr>
                                                <?}} else {?>
                                                	<tr><td class="text-center" colspan="4">No Data</td></tr>
                                                <?} ?>
                                                </tbody>
                                            </table>                            			
										</div>
                            		</td>
                            		<td>
                            			<div>
                                            <div><H3>결과 대기 : <?= $count2; ?> 건 (결과 대기 문자 건수: <?= $result_wait_count; ?> 건)</H3></div><br>
                                            <table class="table table-hover table-striped table-bordered table-highlight-head t_center"
                                                   style="table-layout:fixed cellapadding=0px;">
                                                <colgroup>
                                                    <col width="10%">
                                                    <col width="20%">
                                                    <col width="50%">
                                                    <col width="20%">
                                                </colgroup>
                                                <thead>
                                                <tr p style="cursor:default">
                                                    <th>번호</th>
                                                    <th>발신/예약일자</th>
                                                    <th>업체명</th>
                                                    <th>&nbsp;<BR>대기/발송건수</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- 템플릿이슈 수정 시작 -->
                    							<?$num = 0;
                    							if ($count2 > 0) {
                    							     foreach($rs2 as $r2) { $num++;?>
                                                        <tr>
                                                            <!-- 템플릿이슈 수정 끝 -->
                                                            <td class="text-center">
                                                                 <?= $r2->mst_id?>
                                                            </td>
                                                            <td class="text-center"><?=$r2->mst_datetime?><?=(($r2->mst_reserved_dt!="00000000000000") ? ($r2->mst_ft > 0 || $r2->mst_err_ft > 0 || $r2->mst_at > 0 || $r2->mst_err_at > 0 || $r2->mst_ft_img > 0 || $r2->mst_err_ft_img > 0 || $r2->mst_grs > 0 || $r2->mst_err_grs > 0 || $r2->mst_nas > 0 || $r2->mst_err_nas > 0 || $r2->mst_grs_sms > 0 || $r2->mst_err_grs_sms > 0 || $r2->mst_nas_sms > 0 || $r2->mst_err_nas_sms > 0) ? 
                                                            "<br/><font>".$this->funn->format_date($r2->mst_reserved_dt, '-', 14)."</font>" : 
                                                            "<br/><font color='blue'>".$this->funn->format_date($r2->mst_reserved_dt, '-', 14)."</font>" : "")?></td>
                    										<td class="text-center"><? if($r2->spf_company) { echo $r2->spf_company."(".$r2->spf_friend.")"; } else { echo $r2->mem_username; } ?> </td>
                    										<td class="text-center"><? if ($r2->mst_grs > 0 || $r2->mst_err_grs > 0) echo "웹(A) : ".$r2->mst_wait." / ".$r2->talk_count."<BR>";
                    										if ($r2->mst_nas > 0 || $r2->mst_err_nas > 0) echo "웹(B) : ".$r2->mst_wait." / ".$r2->talk_count."<BR>";
                    										if ($r2->mst_grs_sms > 0 || $r2->mst_err_grs_sms > 0) echo "웹(A) SMS : ".$r2->mst_wait." / ".$r2->talk_count."<BR>";
                    										if ($r2->mst_nas_sms > 0 || $r2->mst_err_nas_sms > 0) echo "웹(B) SMS : ".$r2->mst_wait." / ".$r2->talk_count."<BR>";?>
                                                            </td>
                                                        </tr>
                                                <?}} else {?>
                                                	<tr><td class="text-center" colspan="4">No Data</td></tr>
                                                <?} ?>
                                                
                                                </tbody>
                                            </table>                                                  			
										</div>
                            		</td>
                            	</tr>
                            </tbody>                    	
                    	</table>
