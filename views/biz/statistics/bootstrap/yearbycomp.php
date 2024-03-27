<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.canvaswrapper.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.colorhelpers.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.flot.saturated.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.flot.browser.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.flot.drawSeries.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.flot.uiConstants.js"></script>
<script language="javascript" type="text/javascript" src="/js/plugins/flot-2.1.3/source/jquery.flot.categories.js"></script>
<script language="javascript" type="text/javascript" src="/js/moment.js"></script>
    <style>
        .text-center {
            vertical-align: middle !important;
            line-height: 1;
        }

        .text-right {
            vertical-align: middle !important;
            line-height: 1;
        }

        #search {
            margin-left: 3px;
        }

        th[rowspan="2"] {
            line-height: 50px !important;
        }
        
        /* Resets */
        .graph-container,
        .graph-container div,
        .graph-container a,
        .graph-container span {
            margin: 0;
            padding: 0;
        }
        
         /* Gradinet and Rounded Corners */
        .graph-container, #tooltip, .graph-info a {
            background: #ffffff;
            background: -moz-linear-gradient(top, #ffffff 0%, #f9f9f9 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f9f9f9));
            background: -webkit-linear-gradient(top, #ffffff 0%,#f9f9f9 100%);
            background: -o-linear-gradient(top, #ffffff 0%,#f9f9f9 100%);
            background: -ms-linear-gradient(top, #ffffff 0%,#f9f9f9 100%);
            background: linear-gradient(to bottom, #ffffff 0%,#f9f9f9 100%);
         
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        } 

        /* Graph Container */
        .graph-container {
            position: relative;
            width: 900px;
            height: 400px;
            padding: 50px;
         
            -webkit-box-shadow: 0px 1px 2px rgba(0,0,0,.1);
            -moz-box-shadow: 0px 1px 2px rgba(0,0,0,.1);
            box-shadow: 0px 1px 2px rgba(0,0,0,.1);
        }
         
        .graph-container &gt; div {
            position: absolute;
            width: inherit;
            height: inherit;
            top: 10px;
            left: 25px;
        }
   
        .graph-info {
            width: 900px;
            margin-bottom: 10px;
        }   

        .graph-info a {
            position: relative;
            display: inline-block;
            float: left;
            padding: 7px 10px 5px 30px;
            margin-right: 10px;
            text-decoration: none;
            cursor: default;
        }

        /* Color Circles */
        .graph-info a:before {
            position: absolute;
            display: block;
            content: '';
            width: 8px;
            height: 8px;
            top: 13px;
            left: 13px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
         
        .graph-info .alim { border-bottom: 2px solid MEDIUMPURPLE; }
        .graph-info .friend { border-bottom: 2px solid LIMEGREEN; }
        .graph-info .friend-img { border-bottom: 2px solid DEEPSKYBLUE; }
        .graph-info .friend-wide { border-bottom: 2px solid CHOCOLATE; }
        .graph-info .friend-total { border-bottom: 2px solid CRIMSON; }
        .graph-info .m2nd-message { border-bottom: 2px solid DARKCYAN; }
         
        .graph-info .alim:before { background: MEDIUMPURPLE; }
        .graph-info .friend:before { background: LIMEGREEN; }
        .graph-info .friend-img:before { background: DEEPSKYBLUE; }
        .graph-info .friend-wide:before { background: CHOCOLATE; }
        .graph-info .friend-total:before { background: CRIMSON; }
        .graph-info .m2nd-message:before { background: DARKCYAN; }
 
         /* Clear Floats */
        .graph-info:before, .graph-info:after,
        .graph-container:before, .graph-container:after {
            content: '';
            display: block;
            clear: both;
        }
        
        #tooltip {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-weight: bold;
            font-size: 12px;
            line-height: 20px;
            color: #646464;
            position: absolute;
            display: none;
            padding: 5px 10px;
            border: 1px solid #e1e1e1;
        }
         
        .tickLabel {
            font-weight: bold;
            font-size: 12px;
            color: #666;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }  
        
        .yAxis .tickLabel:first-child {display: none; }      
    </style>
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
                            <button type="button" class="btn btn-primary enter" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	<div class="modal fade" id="modal_memo_all_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: hidden; width: 840px; height: 600px; left: 50%; transform: translate(-50%,-50%);">
		<div class="modal-dialog modal-lg select-dialog" id="modal" data-keyboard="false" data-backdrop="static" style="width: 100%; height: 100%; margin: 0;">
			<div class="modal_content" style="margin: 0; height: 100%; overflow-y: auto;">
				<div class="modal_title" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 100;">
					<h2>메모 전체 보기</h2>
					<i class="material-icons modal_close fr " data-dismiss="modal">close</i>
				</div>
				<div class="modal_body" style="padding-top: 80px;">
					<div class="widget-content" id="memo_all_list"></div>
					<!--<div class="modal_bottom tc">
						<button type="button" class="btn md" data-dismiss="modal">취소</button>
						<button type="button" class="btn md" id="code" name="code" onclick="select_template();">확인</button>
					</div>-->
				</div>
			</div>
		</div>
	</div>    
    
<!-- 타이틀 영역 -->
	<div class="tit_wrap">
		업체별 발송량 통계
	</div>
<!-- 타이틀 영역 END -->
<?
$alimTitle[0] = "";
$alimData[0] = "알림톡";
$sum_alim = 0;
$friendTitle[0] = "";
$friend_text[0] = "친구톡<br>텍스트";
$sum_friend_text = 0;
$friend_image[0] = "친구톡<br>이미지";
$sum_friend_image =0;
$friend_wide[0] = "친구톡<br>와이드";
$sum_friend_wide = 0;
$friend_total[0] = "친구톡<br>전체";
$sum_friend_total = 0;
$m2ndTitle[0] = "";
$m2ndData[0] = "2차<br>문자";
$sum_m2nd = 0;



$rowCount = 1;
foreach($list as $r) {
    $yyyymm = explode('-', $r->yyyymm);
    $yyyymmStr = $yyyymm[1].'월<br>('.$yyyymm[0].')';
    $alimTitle[$rowCount] = $friendTitle[$rowCount] = $m2ndTitle[$rowCount] = $yyyymmStr;
    $alimData[$rowCount] = number_format($r->mst_at);
    $sum_alim += $r->mst_at;
    $friend_text[$rowCount] = number_format($r->mst_ft);
    $sum_friend_text += $r->mst_ft;
    $friend_image[$rowCount] = number_format($r->mst_ft_img);
    $sum_friend_image += $r->mst_ft_img;
    $friend_wide[$rowCount] = number_format($r->mst_ft_wide_img);
    $sum_friend_wide += $r->mst_ft_wide_img;
    $friend_total[$rowCount] = number_format($r->mst_ft_total);
    $sum_friend_total += $r->mst_ft_total;
    $m2ndData[$rowCount] = number_format($r->mst_2nd);
    $sum_m2nd += $r->mst_2nd;
    
    $rowCount += 1;
}

$alimTitle[$rowCount] = $friendTitle[$rowCount] = $m2ndTitle[$rowCount] = "합계";
$alimData[$rowCount] = number_format($sum_alim);
$friend_text[$rowCount] = number_format($sum_friend_text);
$friend_image[$rowCount] = number_format($sum_friend_image);
$friend_wide[$rowCount] = number_format($sum_friend_wide);
$friend_total[$rowCount] = number_format($sum_friend_total);
$m2ndData[$rowCount] = number_format($sum_m2nd);

$rowCount += 1;
?>


<div id="mArticle">
	<div class="form_section">
		<div class="inner_content">
	        <div class="search_box" style="width: 1050px">
				<div class="fr">
					<!-- button class="btn md" onclick="download_static()"><i class="icon-arrow-down"></i> 엑셀 다운로드</button-->
				</div>
	            <div class="form_check fr" style="margin-top: 15px;">
        	    	<input type="checkbox" id="div_all_view" checked ><label for="div_all_view">전체항목</label>
        	    	<input type="checkbox" id="div_alim_view" checked ><label for="div_alim_view">알림톡</label>
        	    	<input type="checkbox" id="div_friend_view" checked ><label for="div_friend_view">친구톡</label>
        	    	<input type="checkbox" id="div_2nd_view" checked ><label for="div_2nd_view">2차 문자</label>
	            </div>
				<form action="/biz/statistics/yearbycomp" method="post" id="mainForm">
					<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
					<input type='hidden' name='groupChange' id='groupChange' value='N' />
    	            <!-- input type="text" class="form-control input-width-small inline datepicker"
    	                   name="startDate" id="startDate" readonly="readonly"
    	                   style="cursor: pointer;background-color: white" value="<?=$param['startDate']?>"> ~
    	            <input type="text" class="form-control input-width-small inline datepicker"
    	                   name="endDate" id="endDate" readonly="readonly"
    	                   style="cursor: pointer;background-color: white" value="<?=$param['endDate']?>"-->
    	            
    	            <select name="group_id" id="group_id">
    						 <?foreach($group as $r) {?>
    							   <option value="<?=$r->mem_id?>" <?=($param['group_id']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?></option>
    						 <?}?>
    	            </select>
    	            <select name="pf_key" id="pf_key" class="selectpicker" data-live-search="true">
    						 <?foreach($profile as $r) {?>
    							   <option value="<?=$r->mem_id?>" <?=($param['pf_key']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?></option>
    						 <?}?>
    	            </select>
    	            
    	    	</form>
	        </div>  
	        <div class="table_graph">
		        <table>
			        <colgroup>
			        	<col width="10%">
			        	<col width="23%">
			        	<col width="10%">
			        	<col width="23%">
			        	<col width="10%">
			        	<col width="24%">
			        </colgroup>
			        <tbody>
				        <tr>
					        <th>영업담당자</th>
					        <td class="tl"><?=$mem_sales_name ?></td>
					        <th>고객담당자</th>
					        <td class="tl"><?=$memberinfo->mem_nickname ?></td>
					        <th>연락처1</th>
					        <td class="tl"><?=$memberinfo->mem_phone ?></td>
				        </tr>
				        <tr>
					        <th>계정생성일</th>
					        <td class="tl"><?=$memberinfo->mem_register_datetime ?></td>
					        <th>Full Care</th>
					        <td class="tl"><?=$memberinfo->mem_full_care_type ?></td>
					        <th>연락처2</th>
					        <td class="tl"><?=$memberinfo->mem_emp_phone ?></td>
				        </tr>
				        <tr>
					        <th>카카오친구수</th>
					        <td class="tl"><?=$memberinfo->mem_pf_cnt ?></td>
					        <th>DB 친구수</th>
					        <td class="tl"><?=number_format($ftCount) ?></td>
					        <th></th>
					        <td class="tl"></td>
				        </tr>
			        </tbody>
		        </table>
	        </div>
		</div>
	</div>
	<div class="form_section">
		<div class="inner_tit">
			<h3>통계그래프</h3>
		</div>
		<div class="inner_content">   
	        <!-- 그래프 시작  -->
				<div class="input_content_wrap table_graph" id="div_all">
					<label class="input_tit" id="all_lable">전체 항목</label>
					<div class="input_content">
						<div id="table_all">
							<table>
                                <colgroup>
                                    <col width="8%">
                                    <col width="7%">
                					<col width="7%">
                					<col width="7%">
                                    <col width="7%">
                					<col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="8%">
                                </colgroup>
								<thead>
								<? for ($i =0; $i < $rowCount; $i++) { ?>
                                    <th class="text-center"><?=$friendTitle[$i]?></th>
                                <? } ?> 
                    			</thead>
                    			<tbody>
                    				<tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$alimData[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$alimData[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_text[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_text[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_image[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_image[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_wide[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_wide[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$m2ndData[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$m2ndData[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    			</tbody>
							</table>
						</div>
						<div class="graph-info form_check">
							<a href="javascript:void(0)" class="alim"><input type="checkbox" name="graph_all" id="alim" value="alim" checked ><label for="alim">알림톡</label></a>
							<a href="javascript:void(0)" class="friend"><input type="checkbox" name="graph_all" id="friend" value="friend" checked><label for="friend">친구톡 텍스트</label></a>
							<a href="javascript:void(0)" class="friend-img"><input type="checkbox" name="graph_all" id="friend_img" value="friend_img" checked><label for="friend_img">친구톡 이미지</label></a>
							<a href="javascript:void(0)" class="friend-wide"><input type="checkbox" name="graph_all" id="friend_wide" value="friend_wide" checked><label for="friend_wide">친구톡 와이드</label></a>
							<a href="javascript:void(0)" class="m2nd-message"><input type="checkbox" name="graph_all" id="m2nd_message" value="m2nd_message" checked><label for="m2nd_message">2차 문자</label></a>
						</div>
						<div class="graph-container" id="placeholder_all"></div>
					</div>
				</div>
				<div class="input_content_wrap table_graph" id="div_alim">
					<label class="input_tit" id="alim_lable">알림톡</label>
					<div class="input_content">
						<div id="table_alim">
							<table>
                                <colgroup>
                                    <col width="8%">
                                    <col width="7%">
                					<col width="7%">
                					<col width="7%">
                                    <col width="7%">
                					<col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="8%">
                                </colgroup>
								<thead>
								<? for ($i =0; $i < $rowCount; $i++) { ?>
                                    <th class="text-center"><?=$alimTitle[$i]?></th>
                                <? } ?> 
                    			</thead>
                    			<tbody>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$alimData[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$alimData[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    			</tbody>
							</table>
						</div>
						<div class="graph-info">
							<a href="javascript:void(0)" class="alim">알림톡</a>
						</div>
						<div class="graph-container" id="placeholder_alim"></div>
					</div>
				</div>
				<div class="input_content_wrap table_graph" id="div_friend">
					<label class="input_tit" id="friend_lable">친구톡</label>
					<div class="input_content">
						<div id="table_friend">
							<table>
                                <colgroup>
                                    <col width="8%">
                                    <col width="7%">
                					<col width="7%">
                					<col width="7%">
                                    <col width="7%">
                					<col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="8%">
                                </colgroup>
								<thead>
								<? for ($i =0; $i < $rowCount; $i++) { ?>
                                    <th class="text-center"><?=$friendTitle[$i]?></th>
                                <? } ?> 
                    			</thead>
                    			<tbody>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_text[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_text[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_image[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_image[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_wide[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_wide[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$friend_total[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$friend_total[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    			</tbody>
							</table>
						</div>
						<div class="graph-info">
							<a href="javascript:void(0)" class="friend-total">친구톡 전체</a>
						</div>
						<div class="graph-container" id="placeholder_friend"></div>
					</div>
				</div>
				<div class="input_content_wrap table_graph" id="div_2nd">
					<label class="input_tit" id='2nd_lable'>2차 문자</label>
					<div class="input_content">
						<div id="table_2nd">
							<table>
                                <colgroup>
                                    <col width="8%">
                                    <col width="7%">
                					<col width="7%">
                					<col width="7%">
                                    <col width="7%">
                					<col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="7%">
                                    <col width="8%">
                                </colgroup>
								<thead>
								<? for ($i =0; $i < $rowCount; $i++) { ?>
                                    <th class="text-center"><?=$m2ndTitle[$i]?></th>
                                <? } ?> 
                    			</thead>
                    			<tbody>
                    				<tr>
								<? for ($i =0; $i < $rowCount; $i++) { 
								    if ($i <= 0) { ?>
                                    	<th class="text-center"><?=$m2ndData[$i]?></th>
                                <?  } else { ?>
                                    	<td class="text-right" style="padding:8px 1px 8px 0px;"><?=$m2ndData[$i]?></td>
                                <?  }
								   } ?> 
                    				</tr>
                    			</tbody>
							</table>
						</div>
						<div class="graph-info">
							<a href="javascript:void(0)" class="m2nd-message">2차 문자</a>
						</div>
						<div class="graph-container" id="placeholder_2nd"></div>
					</div>
				</div>
				<div class="input_content_wrap table_graph" id="div_memo">
					<label class="input_tit" id='memo_lable'>메모</label>
					<div class="input_content">
						<textarea class="memo" id="memo" name="memo" placeholder="메모를 입력해주세요."></textarea>
						<button class="btn md" style="display: block; margin-top: 10px;" id="memo_save" name="memo_save" onclick="happyCallSave();">저장</button>
					</div>
				</div>
	        <!--- 그래프 끝 -->
			<div class="table_list" style="display: none">
                <table>
                    <colgroup>
                        <col width="70">
                        <col width="100">
    					<col width="100">
    					<col width="100">
                        <col width="100">
    					<col width="100">
                        <col width="100">
                        <col width="100">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">발송월</th>
                        <th class="text-center">발송요청</th>
                        <th class="text-center">알림톡</th>
                        <th class="text-center">친구톡<br/>텍스트</th>
                        <th class="text-center">친구톡<br/>이미지</th>
                        <th class="text-center">친구톡<br/>와이드<br/>이미지</th>
                        <th class="text-center">친구톡<br/>전체</th>
                        <th class="text-center">2차 문자</th>
                    </thead>
                    <tbody>
                        <?$sum_req=0;
                          $sum_at=0;
                          $sum_ft=0;
                          $sum_ft_img=0;
                          $sum_ft_wide_img=0;
                          $sum_ft_total = 0;
                          $sum_2nd = 0;
                          
                          foreach($list as $r) {
                                $sum_req+=$r->mst_qty;
                                    $sum_at+=$r->mst_at;
                                    $sum_ft+=$r->mst_ft;
                                    $sum_ft_img+=$r->mst_ft_img;
                                    $sum_ft_wide_img+=$r->mst_ft_wide_img;
                                    $sum_ft_total += $r->mst_ft_total;
                                    $sum_2nd += $r->mst_2nd;
        						  ?>    
    
                            <tr>
                                <td class="text-center" style="padding:0;"><?=substr($r->yyyymm, 0, 7)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_qty)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ft_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ft_wide_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ft_total)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_2nd)?></td>
                            </tr>
                            <? } ?>
                            <tr>
                                <td class="text-center" style="padding:0;">합계</td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_req)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 224, 212);"><?=number_format($sum_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 244, 192);"><?=number_format($sum_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_ft_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_ft_wide_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_ft_total)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_2nd)?></td>
                            </tr>
                     </tbody>
                </table>
        	</div>
        </div>
	</div>
	<!-- 메모 목록 시작 -->
	<div class="form_section">
        <div class="inner_tit">
       		<h3 class="table_graph">
	       		<button class="btn md fr" id="memo_all_view" onclick="btnMemeAllView()">메모 전체 보기</button>
	       		<!-- button class="btn md fr" onclick="happyCallList()">Test용 버턴</button-->
	       		메모 목록 (총 <span class="list_total" id="memo_total_count"><?=number_format($memo_total)?></span>건)<span class="txt_guide">* 최근 5건만 보입니다.</span>
	       	</h3>
        </div>
        <div class="inner_content">
	        <div class="table_list" id="memo_table">
	        	<table style="width: 1050px;">
				    <colgroup>
				        <col width="150px">
				        <col width="*">
				    </colgroup>
				    <thead>
						<tr>
							<th>등록일시</th>
							<th>메모내용</th>
						</tr>
					</thead>
					<tbody>
					<? $memoCount = 0;
					foreach($memo_list as $r) { 
					    $memo = str_replace(array("\r\n","\r","\n"),'<br>', $r->sh_text); 
					    //$memo = $r->sh_text;
					    ?>
						<tr>
							<td><?=$r->sh_reg_date ?></td>
							<td class="tl"><?=$memo?></td>
						</tr>
					<? $memoCount += 1;
					}
					if ($memoCount == 0) {
					?>
						<tr>
							<td colspan="2">등록된 메모가 없습니다.</td>
						</tr>
					<? } ?>					
					</tbody>
	        	</table>
	        </div>
	        
	        
        </div>
	</div>
	<!-- 메모 목록 끝 -->
</div>
    
    <script type="text/javascript">
        $("#nav li.nav40").addClass("current open");

//         $("#group_id").on("changed.bs.select", 
//             function(e, clickedIndex, newValue, oldValue) {
//             	alert("a");
//             	$("#groupChange").val("Y");
//              	submit_handler();
//              });
        
        $("#group_id").change(function() {
            $("#groupChange").val("Y");
            submit_handler();
        });
        
        $("#pf_key").on("changed.bs.select",
			function(e, clickedIndex, newValue, oldValue) {
             	submit_handler();
			});
        
//         var end = $("#endDate").val();
//         $("#startDate").datepicker({
//             format: "yyyy-mm",
//             viewMode: "months",
//             minViewMode: "months",
//             language: "kr",
//             autoclose: true,
//             startDate: '-3m',
//             endDate: end
//         }).on('changeDate', function (selected) {
//             var startDate = new Date(selected.date.valueOf());
//             $('#endDate').datepicker('setStartDate', startDate);
//         });

//         var start = $("#startDate").val();
//         $("#endDate").datepicker({
//             format: "yyyy-mm",
//             viewMode: "months",
//             minViewMode: "months",
//             language: "kr",
//             autoclose: true,
//             startDate: start,
//             endDate: '0m'
//         }).on('changeDate', function (selected) {
//             var endDate = new Date(selected.date.valueOf());
//             $('#startDate').datepicker('setEndDate', endDate);
//         });

//         $(document).unbind("keyup").keyup(function (e) {
//             var code = e.which;
//             if (code == 13) {
//                 $(".btn-primary").click();
//             }
//         });

	var previousPoint = null;
	var xAxisLabels = new Array(12);
	function xAxisLabelGenerator(x){
	    return xAxisLabels[x];
	}


	function numberWithCommas(x) {
	    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	$(document).ready(function() { 
		var monthXstr = new Array(12);
		var monthAlim = new Array(12);
		var monthFriend = new Array(12);
		var monthFriendText = new Array(12);
		var monthFriendImage = new Array(12);
		var monthFriendWide = new Array(12);
		var month2nd = new Array(12);
		
		<? $count = 0; 
		$yAlimMaxValue = 0;
		$yFriendTextMaxValue = 0;
		$yFriendImageMaxValue = 0;
		$yFriendWideMaxValue = 0;
		$yFriendMaxValue = 0;
		$y2ndMaxValue = 0;
		$sumAlime = 0;
		$sumFriend = 0;
		$sum2nd = 0;
		$startDt = '';
		foreach($list as $r) { 
		    if ($count == 0) { $startDt = $r->yyyymm; }
		    $yyyymm = explode('-', $r->yyyymm);
		    $yyyymmStr = $yyyymm[1].'월<br>('.$yyyymm[0].')';
		    $sumAlime += $r->mst_at;
		    $sumFriend += $r->mst_ft_total;
		    $sum2nd += $r->mst_2nd;
		    if ($r->mst_at > $yAlimMaxValue) { $yAlimMaxValue = $r->mst_at; }
		    if ($r->mst_ft > $yFriendTextMaxValue) { $yFriendTextMaxValue = $r->mst_ft; }
		    if ($r->mst_ft_img > $yFriendImageMaxValue) { $yFriendImageMaxValue = $r->mst_ft_img; }
		    if ($r->mst_ft_w_img > $yFriendWideMaxValue) { $yFriendWideMaxValue = $r->mst_ft_w_img; }
		    if ($r->mst_ft_total > $yFriendMaxValue) { $yFriendMaxValue = $r->mst_ft_total; }
		    if ($r->mst_2nd > $y2ndMaxValue) { $y2ndMaxValue = $r->mst_2nd; }
		    
		?>
		xAxisLabels[<?=$count ?>] = "<?=$yyyymmStr ?>";
		//monthAlim[<?=$count ?>] = new Array("<?=$yyyymmStr ?>", <?=$r->mst_at ?>);
		//monthXstr[<?=$count ?>] = "<?=$r->yyyymm ?>";
		monthAlim[<?=$count ?>] = new Array(<?=$count ?>, <?=$r->mst_at ?>);
		monthFriendText[<?=$count ?>] = new Array(<?=$count ?>, <?=$r->mst_ft ?>);
		monthFriendImage[<?=$count ?>] = new Array(<?=$count ?>, <?=$r->mst_ft_img ?>);
		monthFriendWide[<?=$count ?>] = new Array(<?=$count ?>, <?=$r->mst_ft_w_img ?>);
		monthFriend[<?=$count ?>] = new Array(<?=$count ?>, <?=$r->mst_ft_total ?>);
		month2nd[<?=$count ?>] = new Array(<?=$count ?>, <?=$r->mst_2nd ?>);
		<? $count +=1;
        } ?>


        var sumAlime = <?=$sumAlime ?>;
        var sumFriend = <?=$sumFriend ?>;
        var sum2nd = <?=$sum2nd ?>;

        var registerDT = "<?=$register_dt ?>";
        var divMonth = 1;

		var registerMonth = registerDT.substring(0,7);
		var regDay = parseInt(registerDT.substring(8,10));
        if (registerMonth <= "<?=$startDt ?>") {
        	divMonth = 12;
        } else {
            var d = new Date();
            var nowDay = d.getDate();
			var nowDate = moment(d);
			var regDate = moment(new Date(registerDT));
			var diffMonths = nowDate.diff(regDate, 'months');
			if (nowDay < regDay) {
				divMonth = diffMonths + 2;
			} else {
				divMonth = diffMonths + 1;
			}
        }
		var avrAlim = (sumAlime/divMonth).toFixed(0);
		var avrFriend = (sumFriend/divMonth).toFixed(0);
		var avr2nd = (sum2nd/divMonth).toFixed(0);


		
		$("#alim_lable").html("알림톡<br>(평균:" + numberWithCommas(avrAlim) + ")");
		$("#friend_lable").html("친구톡<br>(평균:" + numberWithCommas(avrFriend) + ")");
		$("#2nd_lable").html("2차 문자<br>(평균:" + numberWithCommas(avr2nd) + ")");

//      .graph-info .alim { border-bottom: 2px solid MEDIUMPURPLE; }
//      .graph-info .friend { border-bottom: 2px solid LIMEGREEN; }
//      .graph-info .friend-img { border-bottom: 2px solid DEEPSKYBLUE; }
//      .graph-info .friend-wide { border-bottom: 2px solid MEDIUMSLATEBLUE; }
//      .graph-info .friend-total { border-bottom: 2px solid CRIMSON; }
//      .graph-info .m2nd-message { border-bottom: 2px solid DARKCYAN; }

		var graphAll = [ {
            	data : monthAlim,
            	color: 'MEDIUMPURPLE',
               	points: { radius: 3, fillColor: 'MEDIUMPURPLE' }
				
			},
			{
            	data : monthFriendText,
            	color: 'LIMEGREEN',
               	points: { radius: 3, fillColor: 'LIMEGREEN' }
				
			},
			{
            	data : monthFriendImage,
            	color: 'DEEPSKYBLUE',
               	points: { radius: 3, fillColor: 'DEEPSKYBLUE' }
				
			},
			{
            	data : monthFriendWide,
            	color: 'CHOCOLATE',
               	points: { radius: 3, fillColor: 'CHOCOLATE' }
				
			},
			{
            	data : month2nd,
            	color: 'DARKCYAN',
               	points: { radius: 3, fillColor: 'DARKCYAN' }
				
			}
		];
		
        var graphAlim = [ {
        	data : monthAlim,
        	color: 'MEDIUMPURPLE',
           	points: { radius: 3, fillColor: 'MEDIUMPURPLE' }
        } ];
        var graphFriend = [ {
        	data : monthFriend,
        	color: 'CRIMSON',
        	points: { radius: 3, fillColor: 'CRIMSON' }
        } ];
        var graph2nd = [ {
        	data : month2nd,
        	color: 'DARKCYAN',
        	points: { radius: 3, fillColor: 'DARKCYAN' }
        } ];
        
		var yAlimMaxValue = <?=$yAlimMaxValue ?>;
		var yAlimTickValue = 0;
		var yFriendMaxValue = <?=$yFriendMaxValue ?>;
		var yFriendTickValue = 0;
		var y2ndMaxValue = <?=$y2ndMaxValue ?>;
		var y2ndTickValue = 0;
		var arrAllMaxs = [<?=$yAlimMaxValue?>, <?=$yFriendTextMaxValue?>, <?=$yFriendImageMaxValue?>, <?=$yFriendWideMaxValue?>, <?=$y2ndMaxValue?>];
		var yAllMaxValue = Math.max.apply(null, arrAllMaxs);
		var yAllTickValue = 0;

		if (yAllMaxValue > 1000) {
			var tempRound = 0;
			var strYAllMaxValue = yAllMaxValue.toString();
			var arrYAllMaxValue = strYAllMaxValue.split('');

			for(var i = (arrYAllMaxValue.length - 1); i > 1; i--) {
				if (parseInt(arrYAllMaxValue[i]) > 0) {
					tempRound = 1;
					arrYAllMaxValue[i] = "0";
					arrYAllMaxValue[i - 1] = (parseInt(arrYAllMaxValue[i - 1]) + tempRound).toString();
					tempRound = 0;
				}
			}
			
			tempRound = 0;
			if (parseInt(arrYAllMaxValue[1]) > 9) {
				tempRound = 1;
				arrYAllMaxValue[1] = "0";
			}
			arrYAllMaxValue[0] = (parseInt(arrYAllMaxValue[0]) + tempRound).toString();
			 
			yAllMaxValue = parseInt(arrYAllMaxValue.join(''));
			yAllTickValue = parseInt(yAllMaxValue/10);			
		} else {
			yAllMaxValue = 1000;
			yAllTickValue = 100;
		}
		
		if (yAlimMaxValue > 1000) {
			var tempRound = 0;
			var strYAlimMaxValue = yAlimMaxValue.toString();
			var arrYAlimMaxValue = strYAlimMaxValue.split('');

			for(var i = (arrYAlimMaxValue.length - 1); i > 1; i--) {
				if (parseInt(arrYAlimMaxValue[i]) > 0) {
					tempRound = 1;
					arrYAlimMaxValue[i] = "0";
					arrYAlimMaxValue[i - 1] = (parseInt(arrYAlimMaxValue[i - 1]) + tempRound).toString();
					tempRound = 0;
				}
			}
			
			tempRound = 0;
			if (parseInt(arrYAlimMaxValue[1]) > 9) {
				tempRound = 1;
				arrYAlimMaxValue[1] = "0";
			}
			arrYAlimMaxValue[0] = (parseInt(arrYAlimMaxValue[0]) + tempRound).toString();
			 
			yAlimMaxValue = parseInt(arrYAlimMaxValue.join(''));
			yAlimTick = parseInt(yAlimMaxValue/10);
		} else {
			yAlimMaxValue = 1000;
			yAlimTick = 100;
		}

		if (yFriendMaxValue > 1000) {
			var tempRound = 0;
			var strYFriendMaxValue = yFriendMaxValue.toString();
			var arrYFriendMaxValue = strYFriendMaxValue.split('');

			for(var i = (arrYFriendMaxValue.length - 1); i > 1; i--) {
				if (parseInt(arrYFriendMaxValue[i]) > 0) {
					tempRound = 1;
					arrYFriendMaxValue[i] = "0";
					arrYFriendMaxValue[i - 1] = (parseInt(arrYFriendMaxValue[i - 1]) + tempRound).toString();
					tempRound = 0;
				}
			}
			
			tempRound = 0;
			if (parseInt(arrYFriendMaxValue[1]) > 9) {
				tempRound = 1;
				arrYFriendMaxValue[1] = "0";
			}
			arrYFriendMaxValue[0] = (parseInt(arrYFriendMaxValue[0]) + tempRound).toString();

			yFriendMaxValue = parseInt(arrYFriendMaxValue.join(''));
			yFriendTickValue = parseInt(yFriendMaxValue/10);
			
		} else {
			yFriendMaxValue = 1000;
			yFriendTickValue = 100;
		}

		if (y2ndMaxValue > 1000) {
			var tempRound = 0;
			var strY2ndMaxValue = y2ndMaxValue.toString();
			var arrY2ndMaxValue = strY2ndMaxValue.split('');

			for(var i = (arrY2ndMaxValue.length - 1); i > 1; i--) {
				if (parseInt(arrY2ndMaxValue[i]) > 0) {
					tempRound = 1;
					arrY2ndMaxValue[i] = "0";
					arrY2ndMaxValue[i - 1] = (parseInt(arrY2ndMaxValue[i - 1]) + tempRound).toString();
					tempRound = 0;
				}
			}
			
			tempRound = 0;
			if (parseInt(arrY2ndMaxValue[1]) > 9) {
				tempRound = 1;
				arrY2ndMaxValue[1] = "0";
			}
			arrY2ndMaxValue[0] = (parseInt(arrY2ndMaxValue[0]) + tempRound).toString();

			y2ndMaxValue = parseInt(arrY2ndMaxValue.join(''));
			y2ndTickValue = parseInt(y2ndMaxValue/10);
		} else {
			y2ndMaxValue = 1000;
			y2ndTickValue = 100;
		}

		$.plot("#placeholder_all", graphAll, {
			series: {
				points: {
					show: true,
					radius: 4
				},
				lines: {
					show: true
				},
				shadowSize: 0
			},
			grid: {
				color: '#646464',
				borderColor: 'transparent',
				borderWidth: 20,
				hoverable: true
			},
			xaxis: {
				tickColor: 'transparent',
				tickFormatter: xAxisLabelGenerator,
				showTicks: true
			},
			yaxis: {
				max: yAllMaxValue,
				min: 0,
				tickSize: yAllTickValue
			}
		});

		
		$.plot("#placeholder_alim", graphAlim, {
			series: {
				points: {
					show: true,
					radius: 4
				},
				lines: {
					show: true
				},
				shadowSize: 0
			},
			grid: {
				color: '#646464',
				borderColor: 'transparent',
				borderWidth: 20,
				hoverable: true
			},
			xaxis: {
				tickColor: 'transparent',
				tickFormatter: xAxisLabelGenerator,
				showTicks: true
			},
			yaxis: {
				max: yAlimMaxValue,
				min: 0,
				tickSize: yAlimTick
			}
		});


		$.plot("#placeholder_friend", graphFriend, {
			series: {
				points: {
					show: true,
					radius: 4
				},
				lines: {
					show: true
				},
				shadowSize: 0
			},
			grid: {
				color: '#646464',
				borderColor: 'transparent',
				borderWidth: 20,
				hoverable: true
			},
			xaxis: {
				tickColor: 'transparent',
				tickFormatter: xAxisLabelGenerator,
				showTicks: true
			},
			yaxis: {
				max: yFriendMaxValue,
				min: 0,
				tickSize: yFriendTickValue
			}
		});

		$.plot("#placeholder_2nd", graph2nd, {
			series: {
				points: {
					show: true,
					radius: 4
				},
				lines: {
					show: true
				},
				shadowSize: 0
			},
			grid: {
				color: '#646464',
				borderColor: 'transparent',
				borderWidth: 20,
				hoverable: true
			},
			xaxis: {
				tickColor: 'transparent',
				tickFormatter: xAxisLabelGenerator,
				showTicks: true
			},
			yaxis: {
				max: y2ndMaxValue,
				min: 0,
				tickSize: y2ndTickValue
			}
		});

		$('#placeholder_all').bind('plothover', function (event, pos, item) {
		    if (item) {
		        if (previousPoint != item.dataIndex) {
		            previousPoint = item.dataIndex;
		            $('#tooltip').remove();
		            var x = item.datapoint[0],
		                y = item.datapoint[1];
		                showTooltip(item.pageX, item.pageY, '성공 : ' + y + '건');
		        }
		    } else {
		        $('#tooltip').remove();
		        previousPoint = null;
		    }
		});
		
		$('#placeholder_alim').bind('plothover', function (event, pos, item) {
		    if (item) {
		        if (previousPoint != item.dataIndex) {
		            previousPoint = item.dataIndex;
		            $('#tooltip').remove();
		            var x = item.datapoint[0],
		                y = item.datapoint[1];
		                showTooltip(item.pageX, item.pageY, '성공 : ' + y + '건');
		        }
		    } else {
		        $('#tooltip').remove();
		        previousPoint = null;
		    }
		});

		$('#placeholder_friend').bind('plothover', function (event, pos, item) {
		    if (item) {
		        if (previousPoint != item.dataIndex) {
		            previousPoint = item.dataIndex;
		            $('#tooltip').remove();
		            var x = item.datapoint[0],
		                y = item.datapoint[1];
		                showTooltip(item.pageX, item.pageY, '성공 : ' + y + '건');
		        }
		    } else {
		        $('#tooltip').remove();
		        previousPoint = null;
		    }
		});

		$('#placeholder_2nd').bind('plothover', function (event, pos, item) {
		    if (item) {
		        if (previousPoint != item.dataIndex) {
		            previousPoint = item.dataIndex;
		            $('#tooltip').remove();
		            var x = item.datapoint[0],
		                y = item.datapoint[1];
		                showTooltip(item.pageX, item.pageY, '성공 : ' + y + '건');
		        }
		    } else {
		        $('#tooltip').remove();
		        previousPoint = null;
		    }
		});

		$('input[name="graph_all"]').change(function() { 
			if($('input[name="graph_all"]:checked').length >=1) {
				var arrCount = 0;
				var arrSelectType = [];
				var graphSelectData = [];
				var arrSelectMax = [];
    			$('input[name="graph_all"]:checked').each(function() {
    				arrSelectType[arrCount] = $(this).val();
    				arrCount += 1;
    			});

				for(var ti = 0; ti < arrSelectType.length; ti++) {
					switch(arrSelectType[ti]) {
						case "alim":
							arrSelectMax[ti] = <?=$yAlimMaxValue?>;
							graphSelectData[ti] = { data : monthAlim,	color: 'MEDIUMPURPLE', points: { radius: 3, fillColor: 'MEDIUMPURPLE' } };
							break;
						case "friend":
							arrSelectMax[ti] = <?=$yFriendTextMaxValue?>;
							graphSelectData[ti] = { data : monthFriendText, color: 'LIMEGREEN', points: { radius: 3, fillColor: 'LIMEGREEN' } };
							break;
						case "friend_img":
							arrSelectMax[ti] = <?=$yFriendImageMaxValue?>;
							graphSelectData[ti] = { data : monthFriendImage, color: 'DEEPSKYBLUE', points: { radius: 3, fillColor: 'DEEPSKYBLUE' } };
							break;
						case "friend_wide" :
							arrSelectMax[ti] = <?=$yFriendWideMaxValue?>;
							graphSelectData[ti] = { data : monthFriendWide, color: 'CHOCOLATE', points: { radius: 3, fillColor: 'CHOCOLATE' } };
							break;
						case "m2nd_message" :
							arrSelectMax[ti] = <?=$y2ndMaxValue?>;
							graphSelectData[ti] = { data : month2nd, color: 'DARKCYAN', points: { radius: 3, fillColor: 'DARKCYAN' } };
							break;
					}
				}
				
				var ySelectMaxValue = Math.max.apply(null, arrSelectMax);
				var ySelectTickValue = 0;

				if (ySelectMaxValue > 1000) {
					var tempRound = 0;
					var strYSelectMaxValue = ySelectMaxValue.toString();
					var arrYSelectMaxValue = strYSelectMaxValue.split('');

					for(var i = (arrYSelectMaxValue.length - 1); i > 1; i--) {
						if (parseInt(arrYSelectMaxValue[i]) > 0) {
							tempRound = 1;
							arrYSelectMaxValue[i] = "0";
							arrYSelectMaxValue[i - 1] = (parseInt(arrYSelectMaxValue[i - 1]) + tempRound).toString();
							tempRound = 0;
						}
					}
					
					tempRound = 0;
					if (parseInt(arrYSelectMaxValue[1]) > 9) {
						tempRound = 1;
						arrYSelectMaxValuearrYSelectMaxValue[1] = "0";
					}
					arrYSelectMaxValue[0] = (parseInt(arrYSelectMaxValue[0]) + tempRound).toString();
					 
					ySelectMaxValue = parseInt(arrYSelectMaxValue.join(''));
					ySelectTickValue = parseInt(ySelectMaxValue/10);			
				} else {
					ySelectMaxValue = 1000;
					ySelectTickValue = 100;
				}	

				$.plot("#placeholder_all", graphSelectData, {
					series: {
						points: {
							show: true,
							radius: 4
						},
						lines: {
							show: true
						},
						shadowSize: 0
					},
					grid: {
						color: '#646464',
						borderColor: 'transparent',
						borderWidth: 20,
						hoverable: true
					},
					xaxis: {
						tickColor: 'transparent',
						tickFormatter: xAxisLabelGenerator,
						showTicks: true
					},
					yaxis: {
						max: ySelectMaxValue,
						min: 0,
						tickSize: ySelectTickValue
					}
				});
			} else {
				$(this).prop("checked", true);
			}
			
		});

		$('#div_all_view').change(function() { 
			if($(this).prop("checked")) {
				$("#div_all").css("display", "block");
			} else {
				$("#div_all").css("display", "none");
			}
		});

		$('#div_alim_view').change(function() { 
			if($(this).prop("checked")) {
				$("#div_alim").css("display", "block");
			} else {
				$("#div_alim").css("display", "none");
			}
		});

		$('#div_friend_view').change(function() { 
			if($(this).prop("checked")) {
				$("#div_friend").css("display", "block");
			} else {
				$("#div_friend").css("display", "none");
			}
		});
		
		$('#div_2nd_view').change(function() { 
			if($(this).prop("checked")) {
				$("#div_2nd").css("display", "block");
			} else {
				$("#div_2nd").css("display", "none");
			}
		});

		if (<?=$memo_total ?> > 5) {
			$("#memo_all_view").attr('disabled', false);
		} else {
			$("#memo_all_view").attr('disabled', true);
		}
	});

	function showTooltip(x, y, contents) {
	    $('<div id="tooltip">' + contents + '</div>').css({
	    		        top: y - 45,
	    		        left: x - 30
	    		    }).appendTo('body').fadeIn();	
	    //alert($("#tooltip").val());
	}
        
        function substr(datestr) {
            var result = datestr.substring(0, 7);
            return result;
        }

//         function page(page) {
//             var form = document.getElementById("mainForm");

//             var startDateField = document.getElementById("startDate");
//             startDateField.setAttribute("type", "hidden");
//             startDateField.setAttribute("name", "startDate");
//             startDateField.setAttribute("value", startDateField.getAttribute("value") + "-01");

//             var endDateField = document.getElementById("endDate");
//             endDateField.setAttribute("type", "hidden");
//             endDateField.setAttribute("name", "endDate");

//             var endDateVal = endDateField.getAttribute("value");
//             var year = endDateVal.substring(0, 4);
//             var month = endDateVal.substr(5, 2);
//             var lastDay = ( new Date(year, month, 0) ).getDate();

//             endDateField.setAttribute("value", endDateField.getAttribute("value") + "-" + lastDay);

//             var pageField = document.createElement("input");
//             pageField.setAttribute("type", "hidden");
//             pageField.setAttribute("name", "page");
//             pageField.setAttribute("value", page);
//             form.appendChild(pageField);

//             form.submit();
//         }

        function submit_handler() {
            if (($('#startDate').val() == "") || ($('#endDate').val() == "")) {
                $(".content").html("조회하실 기간을 선택해주세요.");
                $("#myModal").modal('show');
            } else {
                var form = document.getElementById("mainForm");
					form.submit();
            }
        }

        function happyCallSave() {
            var mem_id = $("#pf_key").val();
            var memo = $("#memo").val();
            if (memo) {
        		$.ajax({
        			url: "/biz/statistics/yearbycomp/happycall_save",
        			type: "POST",
        			data: {
        				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
        				"mem_id": mem_id, "memo": memo, "memo_kind":"H"
        				},
        			beforeSend: function () {
        				$('#overlay').fadeIn();
        			},
        			complete: function () {
        				$('#overlay').fadeOut();
        			},
        			success: function (json) {
        				var code = json['code'];
        				var message = json['message'];
        				if (code == "success") {
            				$('#memo').val("");
            				$(".content").html("저장이 완료 되었습니다.");
        					$('#myModal').modal({backdrop: 'static'});
        					happyCallList();
        					$(document).unbind("keyup").keyup(function (e) {
        						var code = e.which;
        						if (code == 13) {
        							$(".enter").click();
        						}
        					});
        				} else {
        					$(".content").html("저장이 실패되었습니다.<br/>" + message);
        					$('#myModal').modal({backdrop: 'static'});
        					$(document).unbind("keyup").keyup(function (e) {
        						var code = e.which;
        						if (code == 13) {
        							$(".enter").click();
        						}
        					});
        				}
        			},
        			error: function () {
        				$(".content").html("시스템 오류로 처리되지 않았습니다.");
        				$('#myModal').modal({backdrop: 'static'});
        				$(document).unbind("keyup").keyup(function (e) {
        					var code = e.which;
        					if (code == 13) {
        						$(".enter").click();
        					}
        				});
        			}
        		});
            } else {
				$(".content").html("메모 내용을 입력하세요.<br/>");
				$('#myModal').modal({backdrop: 'static'});
				$("#memo").focus();
				$(document).unbind("keyup").keyup(function (e) {
					var code = e.which;
					if (code == 13) {
						$(".enter").click();
					}
				});
            }
        }

        function happyCallList() {
        	var mem_id = $("#pf_key").val();
    		$.ajax({
    			url: "/biz/statistics/yearbycomp/happycall_list",
    			type: "POST",
    			data: {
    				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
    				"mem_id": mem_id, "memo_kind":"H"
    				},
    			beforeSend: function () {
    				$('#overlay').fadeIn();
    			},
    			complete: function () {
    				$('#overlay').fadeOut();
    			},
    			success: function (json) {
    				var code = json['code'];
    				var message = json['message'];
    				var memo_total_count = json['memo_total_count'];
    				var memo_list = json['memo_list'];

    				var tableHTML = "";
   				
					if (code == "success") {
						console.log("code :" + code);
						console.log("memo_total_count :" + memo_total_count);
						console.log("memo_list :" + memo_list);

	    				tableHTML += "<table style='width: 1050px;'>";
	    				tableHTML += "	<colgroup>";
	    				tableHTML += "		<col width='150px'>";
	    				tableHTML += "		<col width='*'>";
	    				tableHTML += "	</colgroup>";
	    				tableHTML += "	<thead>";
	    				tableHTML += "		<tr>";
	    				tableHTML += "			<th>등록일시</th>";
	    				tableHTML += "			<th>메모내용</th>";
	    				tableHTML += "		</tr>";
	    				tableHTML += "	</thead>";
	    				tableHTML += "	<tbody>";
						if (memo_list.length > 0) {
    						for(var mi = 0; mi < memo_list.length; mi++) {
    							var memo_body = memo_list[mi]["sh_text"].replace(/\n/g, "<br>");
    		    				tableHTML += "		<tr>";
    		    				tableHTML += "			<td>"+ memo_list[mi]["sh_reg_date"] +"</td>";
    		    				tableHTML += "			<td class='tl'>"+memo_body+"</td>";
    		    				tableHTML += "		</tr>";
    						}
						} else {
		    				tableHTML += "		<tr>";
		    				tableHTML += "			<td colspan='2'>등록된 메모가 없습니다.</td>";
		    				tableHTML += "		</tr>";
						}
	    				tableHTML += "	</tbody>";
	    				tableHTML += "</table>";
	    				
						$("#memo_total_count").text(numberWithCommas(memo_total_count));
						$("#memo_table").html(tableHTML);
						
						if (memo_total_count > 5) {
							$("#memo_all_view").attr('disabled', false);
						} else {
							$("#memo_all_view").attr('disabled', true);
						}
						
					}
    			},
    			error: function () {
    				$(".content").html("시스템 오류로 처리되지 않았습니다.");
    				$('#myModal').modal({backdrop: 'static'});
    				$(document).unbind("keyup").keyup(function (e) {
    					var code = e.which;
    					if (code == 13) {
    						$(".enter").click();
    					}
    				});
    			}
    		});
        }

    	// 메모 전체 보기 버튼
    	function btnMemeAllView() {
    		var mem_id = $("#pf_key").val();
			
			$("#modal_memo_all_list").modal({backdrop: 'static'});
			$('#modal_memo_all_list').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					console.log('code == 27');
					$("#template_select").modal("hide");
				}
			});
			
     		$('#modal_memo_all_list .widget-content').html('').load(
     			"/biz/statistics/memo_view",
     			{
    				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
     				"mem_id": mem_id, "memo_kind":"H", "page": 1
     			},
     			function() {
     				// $('#modal_memo_all_list').css({"overflow-y": ""});
     			}
     		);
    	}
    </script>
