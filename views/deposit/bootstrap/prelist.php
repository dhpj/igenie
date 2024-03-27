<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>선충전이력 (<?=$total_row?>건)</h3>
		</div>
        <span style="font-weight:bold;color:red;font-size:20px;position:absolute; right:585px;top:70px;"><?="선충전한도 : " . number_format($limit_amt) . "원 "?></span>
        <span style="font-weight:bold;color:red;font-size:20px;position:absolute; right:340px;top:70px;"><?="선충전총금액 : " . number_format($total_amt) . "원 "?></span>
        <span style="font-weight:bold;color:red;font-size:20px;position:absolute; right:80px;top:70px;"><?="선충전한도금액 : " . number_format($able_amt) . "원 "?></span>
		<div class="white_box">
			<table class="table_list mg_t10">
				<colgroup>
				<col width="5%" />
				<col width="15%" />
				<col width="7%" />
				<col width="15%" />
				<col width="15%" />
				<col width="*" />
				<col width="7%" />
                <col width="7%" />
				</colgroup>
				<thead>
				<tr>
					<th>번호</a></th>
					<th>업체명</th>
					<th>입금자명</th>
					<th>구분</th>
					<th>일시</th>
					<th>내용</th>
					<th>현금/카드</th>
                    <th>차감주체</th>
				</tr>
				</thead>
				<tbody>
    <?
        if(!empty($list)){
            foreach($list as $a){
    ?>
				<tr>
					<td class="align-center"><?=$a->pdt_id?></td>
					<td class="align-center"><?=$a->mem_username?></td>
					<td class="align-center"><?=$a->mem_realname?></td>
					<td>
					<?php if ($a->dep_deposit >= 0) { ?>
                        <?php if (($a->dep_pay_type == 'pservice' || strpos($a->dep_content,"선충전")!==false || strpos($a->dep_admin_memo,"선충전")!==false)){ ?>
                            <button type="button" class="btn btn-xs btn-primary" >+ 선충전</button>
                        <? }else{ ?>
                            <button type="button" class="btn btn-xs btn-primary" >충전</button>
                        <?} ?>
					<?php } else { ?>
                        <?php if (($a->dep_pay_type == 'pservice' || $a->dep_content == '예치금 선충전 차감'|| strpos($a->dep_content,"선충전")!==false || strpos($a->dep_admin_memo,"선충전")!==false)) { ?>
                            <button type="button" class="btn btn-xs btn-danger" >- 선충전</button>
                        <? }else{ ?>
                            <button type="button" class="btn btn-xs btn-danger" >사용</button>
                        <?} ?>
					<?php } ?>
					</td>
					<td class="align-center"><?=$a->pdt_create_datetime?></td>
					<td><?php echo nl2br(html_escape($a->dep_content)); if(!empty($a->dep_admin_memo)){ echo ' - '.nl2br(html_escape($a->dep_admin_memo)); } ?></td>
					<td class="text-right"><?php echo number_format($a->pdt_amount) . '원'; ?></td>
                    <td class="align-center">
                        <?
                            if ($a->pdt_process_object == "rb"){
                                echo "계좌이체차감";
                            } else if ($a->pdt_process_object == "dhn"){
                                echo "대형";
                            } else if ($a->pdt_process_object == "tg"){
                                echo "TG";
                            }
                        ?>
                    </td>
				</tr>
    <?
            }
        } else {
    ?>
				<tr>
					<td colspan="12" class="nopost">자료가 없습니다</td>
				</tr>
		<?
			}
        ?>
				</tbody>
			</table>

			<div class="page_cen"><?=$page_html?></div>
			<div style="width:100%; margin-bottom:30px; display:inline-block;">
				<div class="fl" style="">
					<div class="box-search">
                        <input type="hidden" id="htype" value="<?=$type?>">
                        <input type="hidden" id="htxt" value="<?=$txt?>">
						<select class="" name="sfield" id="sfield">
                            <option value="username" <?=$type=="username" ? "selected" : ""?>>업체명</option>
                            <option value="realname" <?=$type=="realname" ? "selected" : ""?>>입금자명</option>
						</select>
						<input type="text" class="" name="skeyword" id="skeyword" value="<?=$txt?>" placeholder="Search for..." onKeypress="if(event.keyCode==13){ search(); }">
						<button class="btn btn-default btn-md" name="search_submit" type="button" onClick="search();">검색!</button>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//검색
	function open_page(page){
        if (page == -1){
            location.href = "?page="+ 1 + "&txt=" + skeyword.value.trim() + "&type=" + sfield.value;
        } else {
            if (htxt.value != ""){
                location.href = "?page="+ page + "&txt=" + skeyword.value.trim() + "&type=" + sfield.value;
            } else {
                location.href = "?page="+ page;
            }
        }
	}

    function search(){
        if (skeyword.value.trim() == ""){
            open_page(1);
        }
        open_page(-1);
    }
</script>
