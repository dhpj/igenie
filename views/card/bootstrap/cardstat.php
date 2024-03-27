<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu16.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>카드정산통계</h3>
		</div>
		<div class="white_box">
			<div class="fr">
        		<select name="date_type" id="date_type" style="display:none;">
        			<option value="reg">요청일</option>
        			<option value="due">처리일</option>
        		</select>
                <!-- <div class="t_total_num1">전체 : <span><?=number_format($total_rows)?></span><span>[<font color='blue'><?=number_format($total_rows_taxbill)?></font>]</span> 건</div>
                <div class="t_total_num2">/ 합계 : <span><?if(!empty($tsum)){ echo number_format($tsum);}else{echo "0";}?></span><span>[<font color='blue'><?if(!empty($tsum_taxbill)){ echo number_format($tsum_taxbill);}else{echo "0";}?></font>]</span> 원</div> -->
    		</div>
			<div class="search_box search_period" id="status">
                <ul id='month' class="search_period">
					<li onclick='open_page("m", 12)' value='12' class="submit <?=($month == '12')? "active" : '' ?>">12개월</li>
					<li onclick='open_page("m", 9)' value='9' class="submit <?=($month == '9')? "active" : '' ?>">9개월</li>
                    <li onclick='open_page("m", 6)' value='6' class="submit <?=($month == '6')? "active" : '' ?>">6개월</li>
                    <li onclick='open_page("m", 3)' value='3' class="submit <?=($month == '3')? "active" : '' ?>">3개월</li>
				</ul>
                <!-- <ul id='type' class="search_period">
					<li onclick='open_page("t", 1)' value='1' class="submit <?=($type == '1')? "active" : '' ?>">금액</li>
                    <li onclick='open_page("t", 2)' value='2' class="submit <?=($type == '2')? "active" : '' ?>">건수</li>
				</ul> -->
			</div>
			<table class="table_list">
				<thead>
					<tr>
						<th>업체명</th>
                <?if(!empty($list)){?>
                    <?foreach($arr_dt as $a){?>
                        <th><?=$a?></th>
                    <?}?>
                <?}?>
                        <th>합계</th>
					</tr>
				</thead>
				<tbody>
            <?if(!empty($list)){?>
            <?
                foreach($list as $a){
            ?>
					<tr>
						<td><?=$a->mem_username?></td>
            <?
                    $a_month = 0;
                    $a_cal = 0;
                    $a_cnt = 0;
                    $a_ccnt = 0;
                    foreach($arr_dt as $b){
                        $a_month += $a->{'month_' . $b};
                        $a_cal += $a->{'cal_' . $b};
                        $a_cnt += $a->{'cnt_' . $b};
                        $a_ccnt += $a->{'ccnt_' . $b};
            ?>
                        <td>
                            <?=number_format($a->{'month_' . $b}) . '원'?> / <?=number_format($a->{'cnt_' . $b}) . '건'?><br>
                            [<span style='color:blue'><?=number_format($a->{'cal_' . $b}) . '원'?> / <?=number_format($a->{'ccnt_' . $b}) . '건'?></span>]

                        </td>
            <?
                        }
            ?>
                        <td>
                            <?=number_format($a_month) . '원'?> / <?=number_format($a_cnt) . '건'?><br>
                            [<span style='color:blue'><?=number_format($a_cal) . '원'?> / <?=number_format($a_ccnt) . '건'?></span>]
                        </td>
					</tr>
            <?
                }
            ?>
                    <tr>
                        <td>- 합계 -</td>
            <?
                $a_a_month = 0;
                $a_a_cal = 0;
                $a_a_cnt = 0;
                $a_a_ccnt = 0;
                foreach($arr_dt as $b){
                    $a_a_month += $all->{'month_' . $b};
                    $a_a_cal += $all->{'cal_' . $b};
                    $a_a_cnt += $all->{'cnt_' . $b};
                    $a_a_ccnt += $all->{'ccnt_' . $b};
            ?>
                        <td>
                            <?=number_format($all->{'month_' . $b}) . '원'?> / <?=number_format($all->{'cnt_' . $b}) . '건'?><br>
                            [<span style='color:blue'><?=number_format($all->{'cal_' . $b}) . '원'?> / <?=number_format($all->{'ccnt_' . $b}) . '건'?></span>]

                        </td>
            <?
                }
            ?>
                        <td>
                            <?=number_format($a_a_month) . '원'?> / <?=number_format($a_a_cnt) . '건'?><br>
                            [<span style='color:blue'><?=number_format($a_a_cal) . '원'?> / <?=number_format($a_a_ccnt) . '건'?></span>]
                        </td>
                    </tr>
            <?}else{?>
					<tr>
						<td colspan="2" class="nopost">자료가 없습니다</td>
					</tr>
            <?}?>
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
    function open_page(a, b) {
        var month = '';
        // var type = '';
        month = b;
        // if (a == 'm'){
        //     type = $('#type').children('li.active').attr('value')
        // } else if (a == 't'){
        //     month = $('#month').children('li.active').attr('value')
        //     type = b;
        // }

        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/card/cardstat");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "month");
        field.setAttribute("value", month);
        form.appendChild(field);

        // field = document.createElement("input");
        // field.setAttribute("type", "hidden");
        // field.setAttribute("name", "type");
        // field.setAttribute("value", type);
        // form.appendChild(field);

        form.submit();
    }

</script>
