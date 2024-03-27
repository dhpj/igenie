    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <!-- 타이틀 영역 -->
				<div class="tit_wrap">
					통계
				</div>
<!-- 타이틀 영역 END -->
<!-- 탭메뉴 영역 -->
				<div class="tab_menu">
					<ul>
						<li class="on"><a href="/biz/statistics/daily">일일통계</a></li>
						<li class=""><a href="/biz/statistics/day">구간통계</a></li>
					</ul>
				</div>
<!-- 탭메뉴 영역 END-->
<form method="post" id="mainForm">
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
<!-- 본문 영역 -->
				<div id="mArticle">
					<div class="rows" style="text-align: center">
						<div class="fl">
							<select name="pf_key" id="pf_key" class="selectpicker" data-live-search="true">
					        	<option value="ALL">-ALL-</option>
								<?foreach($profile as $r) {?>
								<option value="<?=$r->spf_key?>" <?=($param['pf_key']==$r->spf_key) ? 'selected' : ''?>><?=$r->spf_company?>(<?=$r->spf_friend?>)</option>
								<?}?>
						    </select>
						    <!--button class="btn md yellow" id="search" onclick="submit_handler()">조회</button-->
						</div>
						<button class="btn md excel fr" onclick="download_static()">엑셀 다운로드</button>
						<input type="text" name="startDate" id="startDate" value="<?=$param['startDate']?>" readonly="readonly" class="stat_date">
						<!--input type="text" name="endDate" id="endDate" value="<?=$param['endDate']?>" readonly="readonly" class="stat_date"-->
					</div>
					<!-- table start -->
					<div class="table_stat">
						<div class="table_stat_header">
							<table cellspacing="0" cellpadding="0" border="0">
								<colgroup>
									<col width="100px">
									<col width="80px">
									<col width="80px">
									<col width="80px">
									<col width="80px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">		
								</colgroup>
								<thead>
									<tr>
										
										<th rowspan="2">업체명</th>
										<th rowspan="2">요청</th>
										<th rowspan="2">성공</th>
										<th rowspan="2">실패</th>
										<th rowspan="2">대기</th>
										
										<th colspan="2">알림톡</th>
										<th colspan="2">친구톡</th>
										<th colspan="2">친구톡(이미지)</th>
										<th colspan="2">WEB(A)<em class="sms">SMS<em></th>
										<th colspan="2">WEB(A)<em class="lms">LMS<em></th>
										<th colspan="2">WEB(A)<em class="mms">MMS<em></th>
										<th colspan="2">WEB(B)<p>SMS<p></th>
										<th colspan="2">WEB(B)<p>LMS<p></th>
										<th colspan="2">WEB(B)<p>MMS<p></th>
									</tr>
									<tr>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="table_stat_body">
							<table cellspacing="0" cellpadding="0" border="0">
								<colgroup>
									<col width="100px">
									<col width="80px">
									<col width="80px">
									<col width="80px">
									<col width="80px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">		
								</colgroup>
								<tbody>
									<?$sum_req=0;
                                      $sum_ok=0;
                                      $sum_fail=0;
                                      $sum_at=0;
                                      $sum_ft=0;
                                      $sum_ft_img=0;
                                      $sum_phn=0;
                                      $sum_015=0;
                                      $sum_grs=0;
                                      $sum_nas=0;
                                      $sum_grs_sms=0;
                                      $sum_nas_sms=0;
                                      
                                      $sum_err_at=0;
                                      $sum_err_ft=0;
                                      $sum_err_ft_img=0;
                                      $sum_err_phn=0;
                                      $sum_err_015=0;
                                      $sum_err_grs=0;
                                      $sum_err_nas=0;
                                      $sum_err_grs_sms=0;
                                      $sum_err_nas_sms=0;
                                      $sum_wait = 0;
                                      
                                      $succ = 0;
                    						  foreach($list as $r) {
                                                $succ =  $r->mst_at
                    						            +$r->mst_ft
                    						            +$r->mst_ft_img
                    						            +$r->mst_phn
                    						            +$r->mst_sms
                    						            +$r->mst_lms
                    						            +$r->mst_mms
                    						            +$r->mst_015
                    						            +$r->mst_grs
                    						            +$r->mst_nas
                    						            +$r->mst_grs_sms
                    						            +$r->mst_nas_sms;
                    						      
                    						    $fail = $r->mst_qty - ( $succ + $r->qty_wait);
                                                 
                    							$err_phn = $r->qty_phn;
                    							 
                                                $sum_req+=$r->mst_qty;
                                                $sum_ok+=$succ;
                                                $sum_fail+=$fail;
                                                $sum_at+=$r->mst_at;
                                                $sum_ft+=$r->mst_ft;
                                                $sum_ft_img+=$r->mst_ft_img;
                                                $sum_grs+=$r->mst_grs;
                                                $sum_grs_sms+=$r->mst_grs_sms;
                                                $sum_nas+=$r->mst_nas;
                                                $sum_nas_sms+=$r->mst_nas_sms;
                                                $sum_015+=$r->mst_015;
                                                $sum_phn+=$r->mst_phn;
                    
                    							$sum_err_at+=$r->err_at;
                    							$sum_err_ft+=$r->err_ft;
                    							$sum_err_ft_img+=$r->err_ft_img;
                    							$sum_err_grs+=$r->qty_grs;
                    							$sum_err_grs_sms+=$r->qty_grs_sms;
                    							$sum_err_nas+=$r->qty_nas;
                    							$sum_err_nas_sms+=$r->qty_nas_sms;
                    							$sum_err_015+=$r->qty_015;
                    							$sum_err_phn+=$err_phn;
                    							$sum_wait = $sum_wait + $r->qty_wait;
                    								
                    				?> 
									<tr>
										
										<td><?=($company) ? $company->spf_company."(".$company->spf_friend.")" : "- all -"?></td><!-- 발송업체 -->
										<td class="total"><?=number_format($r->mst_qty)?></td><!-- 전체요청 -->
										<td class="total"><?=number_format($succ)?></td><!-- 전체성공 -->
										<td class="total"><?=number_format($fail)?></td><!-- 전체실패 -->
										<td class="total"><?=number_format($r->qty_wait)?></td><!-- 전체대기 -->
										<td class="succ"><?=number_format($r->mst_at)?></td><!-- 알림톡 성공 -->
										<td class="fail"><?=number_format($r->err_at)?></td><!-- 알림톡 실패 -->
										<td class="succ"><?=number_format($r->mst_ft)?></td><!-- 친구톡 성공 -->
										<td class="fail"><?=number_format($r->err_ft)?></td><!-- 친구톡 실패 -->
										<td class="succ"><?=number_format($r->mst_ft_img)?></td><!-- 친구톡이미지 성공 -->
										<td class="fail"><?=number_format($r->err_ft_img)?></td><!-- 친구톡이미지 실패 -->
										<td class="succ"><?=number_format($r->mst_grs_sms)?></td><!-- A-sms 성공 -->
										<td class="fail"><?=number_format($r->qty_grs_sms)?></td><!-- A-sms 실패 -->
										<td class="succ"><?=number_format($r->mst_grs)?></td><!-- A-lms 성공 -->
										<td class="fail"><?=number_format($r->qty_grs)?></td><!-- A-lms 실패 -->
										<td class="succ">0</td><!-- A-mms 성공 -->
										<td class="fail">0</td><!-- A-mms 실패 -->
										<td class="succ"><?=number_format($r->mst_nas_sms)?></td><!-- B-sms 성공 -->
										<td class="fail"><?=number_format($r->qty_nas_sms)?></td><!-- B-sms 실패 -->
										<td class="succ"><?=number_format($r->mst_nas)?></td><!-- B-lms 성공 -->
										<td class="fail"><?=number_format($r->qty_nas)?></td><!-- B-lms 실패 -->
										<td class="succ">0</td><!-- B-mms 성공 -->
										<td class="fail">0</td><!-- B-mms 실패 -->
									</tr>
									<?}?>				
									<tr class="total">
										<td class="name">소계</td>
										<td class="total"><?=number_format($sum_req)?></td>
										<td class="total"><?=number_format($sum_ok)?></td>
										<td class="total"><?=number_format($sum_fail)?></td>
										<td class="total"><?=number_format($sum_wait)?></td>
										
										<td class="succ"><?=number_format($sum_at)?></td>
										<td class="fail"><?=number_format($sum_err_at)?></td>
										<td class="succ"><?=number_format($sum_ft)?></td>
										<td class="fail"><?=number_format($sum_err_ft)?></td>
										<td class="succ"><?=number_format($sum_ft_img)?></td>
										<td class="fail"><?=number_format($sum_err_ft_img)?></td>
										
										<td class="succ"><?=number_format($sum_grs_sms)?></td>
										<td class="fail"><?=number_format($sum_err_grs_sms)?></td>
										<td class="succ"><?=number_format($sum_grs)?></td>
										<td class="fail"><?=number_format($sum_err_grs)?></td>
										<td class="succ">0</td>
										<td class="fail">0</td>
										
										<td class="succ"><?=number_format($sum_nas_sms)?></td>
										<td class="fail"><?=number_format($sum_err_nas_sms)?></td>
										<td class="succ"><?=number_format($sum_nas)?></td>
										<td class="fail"><?=number_format($sum_err_grs)?></td>
										<td class="succ">0</td>
										<td class="fail">0</td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div><!-- table stat end -->
				</div><!-- mArticle end -->
    </form>
    <script type="text/javascript">
        $("#nav li.nav40").addClass("current open");

        $("select").on("changed.bs.select", 
                function(e, clickedIndex, newValue, oldValue) {
             	submit_handler();
             });
                
        $(document).ready(function() {
           	 $('#data_grid').DataTable( {
        		 scrollY:        '50vh',
        	        scrollCollapse: true,
        	        paging:         false,
        	        searching:false,
        	        "ordering": false,
        	        "info":     false
        	    } );
        	});
    	
        $('#startDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: '-90d'
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.date.valueOf());
            $('#endDate').datepicker('setStartDate', startDate);
        });

        var start = $("#startDate").val();
        $('#endDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: start 
        }).on('changeDate', function (selected) {
            var endDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', endDate);
        });

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".btn-primary").click();
            }
        });

        function page(page) {
            var form = document.getElementById('mainForm');
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            form.submit();
        }

        function submit_handler() {
            var form = document.getElementById('mainForm');

            if (($('#startDate').val() == "") || ($('#endDate').val() == "")) {
                $(".content").html("조회하실 기간을 선택해주세요.");
                $("#myModal").modal('show');
            } else {
                $('#overlay').fadeIn();
                form.submit();
            }
        }

        //CSV 파일 다운로드
        function download_static() {
            var list = '<?=json_encode($list)?>';
            if (list == '[]') {
                $(".content").html("통계가 없습니다.");
                $("#myModal").modal('show');

            } else {
                var val = [];
                var tot = [];
                <?$sum_req=0;
                  $sum_ok=0;
                  $sum_fail=0;
                  $sum_at=0;
                  $sum_ft=0;
                  $sum_ft_img=0;
                  $sum_phn=0;
                  $sum_015=0;
                  $sum_grs=0;
                  $sum_nas=0;
                  $sum_grs_sms=0;
                  $sum_nas_sms=0;
                  
                  $sum_err_at=0;
                  $sum_err_ft=0;
                  $sum_err_ft_img=0;
                  $sum_err_phn=0;
                  $sum_err_015=0;
                  $sum_err_grs=0;
                  $sum_err_nas=0;
                  $sum_err_grs_sms=0;
                  $sum_err_nas_sms=0;
                  $sum_wait = 0;
                  $succ = 0;
						  foreach($list as $r) {
                            $succ =  $r->mst_at
						            +$r->mst_ft
						            +$r->mst_ft_img
						            +$r->mst_phn
						            +$r->mst_sms
						            +$r->mst_lms
						            +$r->mst_mms
						            +$r->mst_015
						            +$r->mst_grs
						            +$r->mst_nas
						            +$r->mst_grs_sms
						            +$r->mst_nas_sms;
						      
							$fail = $r->mst_qty - ( $succ + $r->qty_wait);
                             
							$err_phn = $r->qty_phn;
							 
                            $sum_req+=$r->mst_qty;
                            $sum_ok+=$succ;
                            $sum_fail+=$fail;
                            $sum_at+=$r->mst_at;
                            $sum_ft+=$r->mst_ft;
                            $sum_ft_img+=$r->mst_ft_img;
                            $sum_grs+=$r->mst_grs;
                            $sum_grs_sms+=$r->mst_grs_sms;
                            $sum_nas+=$r->mst_nas;
                            $sum_nas_sms+=$r->mst_nas_sms;
                            $sum_015+=$r->mst_015;
                            $sum_phn+=$r->mst_phn;

							$sum_err_at+=$r->err_at;
							$sum_err_ft+=$r->err_ft;
							$sum_err_ft_img+=$r->err_ft_img;
							$sum_err_grs+=$r->qty_grs;
							$sum_err_grs_sms+=$r->qty_grs_sms;
							$sum_err_nas+=$r->qty_nas;
							$sum_err_nas_sms+=$r->qty_nas_sms;
							$sum_err_015+=$r->qty_015;
							$sum_err_phn+=$err_phn;
							$sum_wait+=$r->qty_wait;
							$mem_price = $this->Biz_free_model->getMemberPrice($r->mst_mem_id);
							$parent_price = $this->Biz_free_model->getMemberPrice($r->parent_mem_id);
					?>    
					  sep = {
							"pf_ynm"				: '<?=($r->mem_username)?>',
							"cnt_total"				: '<?=($r->mst_qty)?>',
							"cnt_succ_total"		: '<?=($succ)?>',
							"cnt_invalid_total"		: '<?=($fail)?>',
							"cnt_wait_total"		: '<?=($r->qty_wait)?>',
							"cnt_succ_kakao"		: '<?=($r->mst_at)?>',
							"cnt_succ_friend"		: '<?=($r->mst_ft)?>',
							"cnt_succ_friend_img"	: '<?=($r->mst_ft_img)?>',
							"cnt_succ_grs"			: '<?=($r->mst_grs)?>',
							"cnt_succ_grs_sms"		: '<?=($r->mst_grs_sms)?>',
							"cnt_succ_nas"			: '<?=($r->mst_nas)?>',
							"cnt_succ_nas_sms"		: '<?=($r->mst_nas_sms)?>',
//							"cnt_succ_015"			: '<?=($r->mst_015)?>',
//							"cnt_succ_phn"			: '<?=($r->mst_phn)?>',
							"cnt_fail_kakao"		: '<?=($r->err_at)?>',
							"cnt_fail_friend"		: '<?=($r->err_ft)?>',
							"cnt_fail_friend_img"	: '<?=($r->err_ft_img)?>',
							"cnt_fail_grs"			: '<?=($r->qty_grs)?>',
							"cnt_fail_grs_sms"		: '<?=($r->qty_grs_sms)?>',
							"cnt_fail_nas"			: '<?=($r->qty_nas)?>',
							"cnt_fail_nas_sms"		: '<?=($r->qty_nas_sms)?>'
//							"cnt_fail_015"			: '<?=($r->qty_015)?>',
//							"cnt_fail_phn"			: '<?=($r->qty_phn)?>'
					  };
					  val.push(sep);
					  sep = {
								"pf_ynm"				: '<?=($r->parent_name)?>',
								"cnt_total"				: '마진',
								"cnt_succ_total"		: '',
								"cnt_invalid_total"		: '',
								"cnt_wait_total"		: '',
								"cnt_succ_kakao"		: '<?=($mem_price['mad_price_at'] - $parent_price['mad_price_at'])?>',
								"cnt_succ_friend"		: '<?=($mem_price['mad_price_ft'] - $parent_price['mad_price_ft'])?>',
								"cnt_succ_friend_img"	: '<?=($mem_price['mad_price_ft_img'] - $parent_price['mad_price_ft_img'])?>',
								"cnt_succ_grs"			: '<?=($mem_price['mad_price_grs'] - $parent_price['mad_price_grs'])?>',
								"cnt_succ_grs_sms"		: '<?=($mem_price['mad_price_grs_sms'] - $parent_price['mad_price_grs_sms'])?>',
								"cnt_succ_nas"			: '<?=($mem_price['mad_price_nas'] - $parent_price['mad_price_nas'])?>',
								"cnt_succ_nas_sms"		: '<?=($mem_price['mad_price_nas_sms'] - $parent_price['mad_price_nas_sms'])?>',
//								"cnt_succ_015"			: '<?=($mem_price['mad_price_015'] - $parent_price['mad_price_015'])?>',
//								"cnt_succ_phn"			: '<?=($mem_price['mad_price_phn'] - $parent_price['mad_price_phn'])?>',
								"cnt_fail_kakao"		: '',
								"cnt_fail_friend"		: '',
								"cnt_fail_friend_img"	: '',
								"cnt_fail_grs"			: '',
								"cnt_fail_grs_sms"		: '',
								"cnt_fail_nas"			: '',
								"cnt_fail_nas_sms"		: ''
//								"cnt_fail_015"			: '',
//								"cnt_fail_phn"			: ''
						  };
					  val.push(sep);
				  <?}?>
					  sum = {
							"get_statistics_cnt_total"			: '<?=($sum_req)?>',
							"get_statistics_cnt_succ_total"		: '<?=($sum_ok)?>',
							"get_statistics_cnt_invalid_total"	: '<?=($sum_fail)?>',
							"get_statistics_cnt_wait_total"	: '<?=($sum_wait)?>',
							"get_statistics_cnt_succ_kakao"		: '<?=($sum_at)?>',
							"get_statistics_cnt_succ_friend"	: '<?=($sum_ft)?>',
							"get_statistics_cnt_succ_friend_img": '<?=($sum_ft_img)?>',
							"get_statistics_cnt_succ_grs"		: '<?=($sum_grs)?>',
							"get_statistics_cnt_succ_grs_sms"	: '<?=($sum_grs_sms)?>',
							"get_statistics_cnt_succ_nas"		: '<?=($sum_nas)?>',
							"get_statistics_cnt_succ_nas_sms"	: '<?=($sum_nas_sms)?>',
//							"get_statistics_cnt_succ_015"		: '<?=($sum_015)?>',
//							"get_statistics_cnt_succ_phn"		: '<?=($sum_phn)?>',
							"get_statistics_cnt_fail_kakao"		: '<?=($sum_err_at)?>',
							"get_statistics_cnt_fail_friend"	: '<?=($sum_err_ft)?>',
							"get_statistics_cnt_fail_friend_img": '<?=($sum_err_ft_img)?>',
							"get_statistics_cnt_fail_grs"		: '<?=($sum_err_grs)?>',
							"get_statistics_cnt_fail_grs_sms"	: '<?=($sum_err_grs_sms)?>',
							"get_statistics_cnt_fail_nas"		: '<?=($sum_err_nas)?>',
							"get_statistics_cnt_fail_nas_sms"	: '<?=($sum_err_nas_sms)?>'
//							"get_statistics_cnt_fail_015"		: '<?=($sum_err_015)?>',
//							"get_statistics_cnt_fail_phn"		: '<?=($sum_err_phn)?>'
					  };
					  tot.push(sum);

                var value = JSON.stringify(val);
                var total = JSON.stringify(tot);
                var form = document.createElement("form");
                document.body.appendChild(form);
                form.setAttribute("method", "post");
                form.setAttribute("action", "/biz/statistics/download/index/1");

                var scrfField = document.createElement("input");
                scrfField.setAttribute("type", "hidden");
                scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
                scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
                form.appendChild(scrfField);

					 var valueField = document.createElement("input");
                valueField.setAttribute("type", "hidden");
                valueField.setAttribute("name", "value");
                valueField.setAttribute("value", value);
                form.appendChild(valueField);

                var totalField = document.createElement("input");
                totalField.setAttribute("type", "hidden");
                totalField.setAttribute("name", "total");
                totalField.setAttribute("value", total);
                form.appendChild(totalField);
                form.submit();
            }
        }
    </script>
