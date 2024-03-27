<div class="snb_nav">
	<ul class="clear">
		<li class="active"><a href="#">이전 메세지</a></li>
		<li><a href="javascript:open_public_temp()">저장 메세지</a></li>
	</ul>
    <div align="left" style="overflow-y:scroll; height: 400px;">
        <table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
            <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
            <colgroup>
                <col width="40">
                <col width="10%">
                <col width="20%">
                <col width="*">
            </colgroup>
            <thead>
            <tr p style="cursor:default">
                <th class="checkbox-column">선택</th>
                <th class="text-center">종류</th>
                <th class="text-center">등록일</th>
                <th class="text-center">메세지</th>
            </tr>
            </thead>
            <tbody>
            <?foreach($list as $r) {?>
                
                    <!-- <tr onclick='set_lms_by_user_msg("<?=$r->msg_id?>");'> -->
                    <tr>
                        <td class="checkbox-column text-center">
                            <input type="checkbox" name="selMsg" class="uniform" value="<?=$r->mst_id?>">
                        </td>
                        <td class="text-center"><? switch($r->mst_kind) { case 'at' : echo '알림톡'; break; case 'ft' : echo '친구톡'; break; case 'MS' : echo '문자'; } ?></td>
                        <td class="text-center"><?=$r->mst_datetime?></td>
                        <td class="text-center" name="name"><?=$r->mst_content?></td>
                    </tr>
            <?}?>
            </tbody>
        </table>
    </div>
</div>
<div class="pagination align-center" style="width:100%"><?echo $page_html?></div>

<style>
    .text-center {
        vertical-align: middle !important;
    }
</style>

<script type="text/javascript">


    $( document ).ready(function() {

        $('tr input').uniform();
    });


    function open_page_user_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';

		$('#myModalUserMSGList .content').html('').load(
			"/biz/sender/lms/msg_save_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				"search_kind": "FT",
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
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
