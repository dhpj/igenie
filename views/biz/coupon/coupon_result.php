
    <div class="table_list" style="height:500px;overflow:auto;">
        <table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
            <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
            <colgroup>
                <col width="16%">
                <col width="14%">
                <col width="15%">
                <col width="20%">
                <col width="15%">
                <col width="20%">
            </colgroup>
            <thead>
            <tr>
                <th>전화번호</th>
                <th>당첨여부</th>
                <th>당첨쿠폰번호</th>
                <th>당첨일시</th>
                <th>직원확인여부</th>
                <th>직원확인일시</th>
            </tr>
            </thead>
            <tbody>
            <?foreach($rs as $r) {?>
				<!-- <tr onclick='set_lms_by_user_msg("<?=$r->msg_id?>");'> -->
				<tr>
					<td><?=$this->funn->format_phone($r->phn, "-")?></td>
					<td><? if($r->iswin=='O') {echo '당첨';} else {echo '낙첨';}?></td>
					<td><? if($r->iswin=='O') {echo $r->coupon_no;} ?></td>
					<td><? if($r->iswin=='O') {echo $r->reg_date;} ?></td>
					<td><? if($r->iswin=='O') { if($r->isend == 'O') { echo '확인완료';} else {echo '미완료';} } ?></td>
					<td><? if($r->iswin=='O') { if($r->isend == 'O') { echo $r->end_date ;} } ?></td>
				</tr>
            <?}?>
            </tbody>
        </table>
    </div>
<div>
		<?echo $page_html?>
</div>
<!--div>
   	<input type="text" id="search_phn" name="search_phn" placeholder="전화번호">
   	<button type="button" class="btn md btn-default"  onclick="open_page_result(1)">조회</button>
</div-->

<style>
    .text-center {
        vertical-align: middle !important;
    }
</style>

<script type="text/javascript">
    $( document ).ready(function() {
        $('tr input').uniform();
    });

    function open_page_result(page  ) {
		var search_phn = $('#search_phn').val() || ''; 
		//alert(search_phn);
		$('#myModalUserResultlist .content').html('').load(
			"/biz/coupon/coupon_result",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				'page': page,
				'coupon_id':<?=$coupon_id?>,
			    'search_phn':search_phn
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();				
			}
		);
    }
  
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