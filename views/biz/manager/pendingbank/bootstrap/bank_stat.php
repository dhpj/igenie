<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>무통장입금통계</h3>
		</div>

        <?
           //  $mon_cnt = 0;
           //  // echo count($monthlist);
           // foreach($monthlist as $r) {
           //     $mon_cnt = $mon_cnt + 1;
           //     if($mon_cnt==1){
           //         echo "(";
           //     }
           //     echo $r->mon."월 : ".number_format($r->tsum)."원";
           //     if($mon_cnt != count($monthlist)){
           //         echo " | ";
           //     }else{
           //         echo ")";
           //     }
           //  } ?>
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
		<div class="white_box">
    		<div class="fr mg_l20" style="display:none;">
				<!--2021.07.30입금자명 검색 추가-->
				<div class="box-search">
						 <select class="" name="sfield" id="sfield">
                            <option value="1">입금자</option>
                            <option value="2">회원명</option>
						 	<!-- <?php echo element('search_option', $view); ?> -->
					   </select>
						 <input type="text" class="" name="skeyword" id="skeyword"  value="<?=$skeyword?>"  placeholder="Search for..." onkeypress="if(event.keyCode==13){ open_page(1); }">
						 <button class="btn md yellow" id="search_btn" name="search_submit" type="button" onclick="open_page(1);">검색</button>
             <a href="/biz/manager/pendingbank/" class="btn md" style="display:inline-block; line-height:34px;">목록으로</a>
				</div>
				<!--//입금자명 검색 추가-->
				</div>
				<div class="fr">
    		<select name="date_type" id="date_type" style="display:none;">
    			<option value="reg">요청일</option>
    			<option value="due">처리일</option>
    		</select>
    		<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$startDate?>" readonly="readonly" style="display:none;">
    		<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$endDate?>" readonly="readonly" style="display:none;">
            <div class="t_total_num1">전체 : <span><?=number_format($total_rows)?></span><span>[<font color='blue'><?=number_format($total_rows_taxbill)?></font>]</span> 건</div>
            <div class="t_total_num2">/ 합계 : <span><?if(!empty($tsum)){ echo number_format($tsum);}else{echo "0";}?></span><span>[<font color='blue'><?if(!empty($tsum_taxbill)){ echo number_format($tsum_taxbill);}else{echo "0";}?></font>]</span> 원</div>
    		<!-- <input type="button" class="btn_excel_down" id="excel_down" value="엑셀 다운로드" onclick="due_download()"/> -->
    		</div>
			<div class="search_box search_period" id="status">
				<!-- <ul class="search_period">
					<a href="index"><li class="submit active">입금내역</li></a>
					<a href="errlist"><li class="submit">미처리내역</li></a>
				</ul> -->
                <ul class="search_period">
					<a href="bank_stat?mon=12&mart=<?=$mart?>&group=<?=$group?><?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($mon=='12')? "active" : '' ?>">12개월</li></a>
					<a href="bank_stat?mon=9&mart=<?=$mart?>&group=<?=$group?><?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($mon=='9')? "active" : '' ?>">9개월</li></a>
                    <a href="bank_stat?mon=6&mart=<?=$mart?>&group=<?=$group?><?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($mon=='6')? "active" : '' ?>">6개월</li></a>
                    <a href="bank_stat?mon=3&mart=<?=$mart?>&group=<?=$group?><?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($mon=='3')? "active" : '' ?>">3개월</li></a>
				</ul>

                <ul class="search_period">
					<a href="bank_stat?mon=<?=$mon?>&mart=1&group=<?=$group?><?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($mart=='1')? "active" : '' ?>">소속</li></a>
                    <a href="bank_stat?mon=<?=$mon?>&mart=2&group=<?=$group?><?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($mart=='2')? "active" : '' ?>">업체</li></a>
				</ul>

                <ul class="search_period">
					<a href="bank_stat?mon=<?=$mon?>&mart=<?=$mart?>&group=1<?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($group=='1')? "active" : '' ?>">금액</li></a>
                    <a href="bank_stat?mon=<?=$mon?>&mart=<?=$mart?>&group=2<?=(!empty($param->page))? "&page=".$param->page : ''?>"><li class="submit <?=($group=='2')? "active" : '' ?>">건수</li></a>
				</ul>

				<input type="hidden" id="set_date" name="set_date">
                <? $time = time();
                $montext = (int)$mon;
                $year = (int)date("Y",strtotime("-".$montext." month", $time));
                $month = (int)date("m",strtotime("-".$montext." month", $time));
                $imonth = "";
                 ?>
                <!-- <?=date("m",strtotime("-12 month", $time))." 1년전"; ?> -->

			</div>
			<table class="table_list">
				<thead>
					<tr>
						<th>소속</th>
                        <? if($mart=='2'){ ?>
                        <th>업체명</th>
                        <? } ?>
                        <?
                        if($month<12){
                            $month = $month+1;
                        }else if($month==12){
                            $month = 1;
                            $year = $year+1;
                        }
                        for($i=0;$i<(int)$mon;$i++){

                            if($month<10){
                                $imonth = "0".$month;
                            }else{
                                $imonth = $month;
                            }
                            echo "<th>".$year."-".$imonth."</th>";
                            if($month<12){
                                $month = $month+1;
                            }else if($month==12){
                                $month = 1;
                                $year = $year+1;
                            }
                        }?>
                        <th>합계</th>
					</tr>
				</thead>
				<tbody>
				<?
				   $cnt = 0;
				   foreach($list as $r) {
					   // $num = $total_rows-($perpage*($param['page']-1))-$cnt;
					   $cnt = $cnt + 1;
				?>
					<tr>
						<td><?=$r->adminname?></td>
                        <? if($mart=='2'){ ?>
                        <td><?=$r->mem_username?></td>
                        <? } ?>
                        <? if((int)$mon>11){ ?>
                        <td class="align-right"><p><?=number_format($r->m11)?></p><p>[<font color='blue'><?=number_format($r->m11_taxbill)?></font>]</p></td>
						<td class="align-right"><p><?=number_format($r->m10)?></p><p>[<font color='blue'><?=number_format($r->m10_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m9)?></p><p>[<font color='blue'><?=number_format($r->m9_taxbill)?></font>]</p></td>
                        <? } ?>
                        <? if((int)$mon>8){ ?>
                        <td class="align-right"><p><?=number_format($r->m8)?></p><p>[<font color='blue'><?=number_format($r->m8_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m7)?></p><p>[<font color='blue'><?=number_format($r->m7_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m6)?></p><p>[<font color='blue'><?=number_format($r->m6_taxbill)?></font>]</p></td>
                        <? } ?>
                        <? if((int)$mon>5){ ?>
                        <td class="align-right"><p><?=number_format($r->m5)?></p><p>[<font color='blue'><?=number_format($r->m5_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m4)?></p><p>[<font color='blue'><?=number_format($r->m4_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m3)?></p><p>[<font color='blue'><?=number_format($r->m3_taxbill)?></font>]</p></td>
                        <? } ?>
                        <td class="align-right"><p><?=number_format($r->m2)?></p><p>[<font color='blue'><?=number_format($r->m2_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m1)?></p><p>[<font color='blue'><?=number_format($r->m1_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m0)?></p><p>[<font color='blue'><?=number_format($r->m0_taxbill)?></font>]</p></td>
                        <td class="align-right"><p><?=number_format($r->m11+$r->m10+$r->m9+$r->m8+$r->m7+$r->m6+$r->m5+$r->m4+$r->m3+$r->m2+$r->m1+$r->m0)?></p><p>[<font color='blue'><?=number_format($r->m11_taxbill+$r->m10_taxbill+$r->m9_taxbill+$r->m8_taxbill+$r->m7_taxbill+$r->m6_taxbill+$r->m5_taxbill+$r->m4_taxbill+$r->m3_taxbill+$r->m2_taxbill+$r->m1+$r->m0_taxbill)?></font>]</p></td>
						<!-- <td><?=$r->mem_realname?></td>
						<td><?=$r->mem_phone?></td>
						<td><?=display_datetime($r->dep_datetime, 'full')?></td>
						<td class="align-right"><?=number_format($r->dep_cash_request).'원'?></td>
						<td class="align-right"><?=number_format($r->dep_cash). '원'?></td>
						<td class="align-right"><?=number_format($r->dep_cash_request - abs($r->dep_cash)).'원'?></td> -->
						<? if($this->member->item('mem_level') >= 100) {?>
						<!-- <td><?php if ( !$r->dep_status){ ?><a href="<?php echo $this->pagedir; ?>/write/<?=$r->dep_id?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a><?php } ?></td> -->
						<? } ?>
					</tr>
				<?php
					}

					if ( $cnt == 0) {
				?>
					<tr>
						<td colspan="<?=($this->member->item('mem_level') >= 100) ? "10" : "9"?>" class="nopost">자료가 없습니다</td>
					</tr>
				<?php
                    }else{
                        ?>
                        <tr class="total_line">
    						<td <?=($mart=='2')? "colspan='2' " : ''?>>합계</td>
                            <? if((int)$mon>11){ ?>
                            <td class="align-right"><p><?=number_format($monthlist->m11)?><br></p><p>[<font color='blue'><?=number_format($monthlist->m11_taxbill)?></font>]</p></td>
    						<td class="align-right"><p><?=number_format($monthlist->m10)?></p><p>[<font color='blue'><?=number_format($monthlist->m10_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m9)?></p><p>[<font color='blue'><?=number_format($monthlist->m19_taxbill)?></font>]</p></td>
                            <? } ?>
                            <? if((int)$mon>8){ ?>
                            <td class="align-right"><p><?=number_format($monthlist->m8)?></p><p>[<font color='blue'><?=number_format($monthlist->m8_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m7)?></p><p>[<font color='blue'><?=number_format($monthlist->m7_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m6)?></p><p>[<font color='blue'><?=number_format($monthlist->m6_taxbill)?></font>]</p></td>
                            <? } ?>
                            <? if((int)$mon>5){ ?>
                            <td class="align-right"><p><?=number_format($monthlist->m5)?></p><p>[<font color='blue'><?=number_format($monthlist->m5_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m4)?></p><p>[<font color='blue'><?=number_format($monthlist->m4_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m3)?></p><p>[<font color='blue'><?=number_format($monthlist->m3_taxbill)?></font>]</p></td>
                            <? } ?>
                            <td class="align-right"><p><?=number_format($monthlist->m2)?></p><p>[<font color='blue'><?=number_format($monthlist->m2_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m1)?></p><p>[<font color='blue'><?=number_format($monthlist->m1_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m0)?></p><p>[<font color='blue'><?=number_format($monthlist->m0_taxbill)?></font>]</p></td>
                            <td class="align-right"><p><?=number_format($monthlist->m11+$monthlist->m10+$monthlist->m9+$monthlist->m8+$monthlist->m7+$monthlist->m6+$monthlist->m5+$monthlist->m4+$monthlist->m3+$monthlist->m2+$monthlist->m1+$monthlist->m0)?></p><p>[<font color='blue'><?=number_format($monthlist->m11_taxbill+$monthlist->m10_taxbill+$monthlist->m9_taxbill+$monthlist->m8_taxbill+$monthlist->m7_taxbill+$monthlist->m6_taxbill+$monthlist->m5_taxbill+$monthlist->m4_taxbill+$monthlist->m3_taxbill+$monthlist->m2_taxbill+$monthlist->m1_taxbill+$monthlist->m0_taxbill)?></font>]</p></td>
    					</tr>
                        <?
                    }
				?>
				</tbody>
			</table>
            <? if($mart=='2'){ ?>
			<div class="tc"><?=$page_html?></div>
            <? } ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
    //2021-06-29 엑셀 다운로드 관련 시작
    function getFormatDate(date){
        var year = date.getFullYear();
        var month = (1 + date.getMonth());
        month = month >= 10 ? month : '0' + month;
        var day = date.getDate();
        day = day >= 10 ? day : '0' + day;
        return year + '-' + month + '-' + day;
    }

    var date = new Date();
    var sdate = "<?=$startDate?>";
    var edate = "<?=$endDate?>";
    var firstDay = new Date(date.getFullYear(), date.getMonth() - 2, 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    // if(sdate!=""){
    //     firstDay = sdate;
    // }
    //
    // if(edate!=""){
    //     lastDay = edate;
    // }


    $('#startDate').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
        startDate: '-3m',
        //endDate: '-1d'
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', startDate);
    });
    $("#startDate").val(getFormatDate(firstDay));
    var start = $("#startDate").val();

    $('#endDate').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
        startDate: start,
        endDate: '+3m'
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#startDate').datepicker('setEndDate', endDate);
    });
    $("#endDate").val(getFormatDate(lastDay));
    var end = $("#endDate").val();

    function due_download() {

    	var type = $('#date_type').val();
    	var start_date = $('#startDate').val();
    	var end_date = $('#endDate').val();

    	var form = document.createElement("form");
    	document.body.appendChild(form);
    	form.setAttribute("method", "post");
    	form.setAttribute("action", "/biz/manager/pendingbank/due_download");

    	var scrfField = document.createElement("input");
    	scrfField.setAttribute("type", "hidden");
    	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(scrfField);

    	var kindField = document.createElement("input");
    	kindField.setAttribute("type", "hidden");
    	kindField.setAttribute("name", "search_type");
    	kindField.setAttribute("value", type);
    	form.appendChild(kindField);

    	var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "start_date");
    	resultField.setAttribute("value", start_date);
    	form.appendChild(resultField);

    	var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "end_date");
    	resultField.setAttribute("value", end_date);
    	form.appendChild(resultField);

    	form.submit();
    }
  	//2021-06-29 엑셀 다운로드 관련 끝

		// //검색
		// function open_page(page){
		// 	var sfield = $("#sfield").val(); //검색타입
		// 	var skeyword = $("#skeyword").val(); //검색내용
		// 	var form = document.createElement("form");
    	// document.body.appendChild(form);
    	// form.setAttribute("method", "post");
    	// form.setAttribute("action", "/biz/manager/pendingbank?page="+page);
        //
    	// var scrfField = document.createElement("input");
    	// scrfField.setAttribute("type", "hidden");
    	// scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	// scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	// form.appendChild(scrfField);
        //
    	// var kindField = document.createElement("input");
    	// kindField.setAttribute("type", "hidden");
    	// kindField.setAttribute("name", "sfield");
    	// kindField.setAttribute("value", sfield);
    	// form.appendChild(kindField);
        //
    	// var resultField = document.createElement("input");
    	// resultField.setAttribute("type", "hidden");
    	// resultField.setAttribute("name", "skeyword");
    	// resultField.setAttribute("value", skeyword);
    	// form.appendChild(resultField);
		// 	form.submit();
		// }
        //검색
    	function open_page(page){
    		var sfield = $("#sfield").val(); //검색타입
    		var skeyword = $("#skeyword").val(); //검색내용
            var mon = '<?=$mon?>';
            var mart = '<?=$mart?>';
            var group = '<?=$group?>';
            var firstparam = "?mon="+mon;

    		//var dep_to_type = "<?=$this->input->get('dep_to_type')?>"; //내역구분
    		//var dep_pay_type = "<?=$this->input->get('dep_pay_type')?>"; //내역구분
            //var start_date = $('#startDate').val();
        	//var end_date = $('#endDate').val();
    		var pram = "";
    		if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;
            if(mart != "") pram += "&mart="+ mart;
            if(group != "") pram += "&group="+ group;
    		//alert("page : "+ page +", tagid : "+ tagid);
    		location.href = firstparam + pram +"&page="+ page;
    	}


</script>
