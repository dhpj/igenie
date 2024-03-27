<div class="snb_nav">
	<ul class="clear" style="width:788px">
		<li><a href="javascript:open_page_pre_lms_msg('1');">이전 메세지</a></li>
		<li class="active"><a href="#">저장 메세지</a></li>
	</ul>
    <div align="left" style="overflow-y:scroll; height: 400px;padding:-180px;width: 100% !important;">
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
                            <input type="checkbox" name="selMsg" class="uniform" value="<?=$r->msg_id?>">
                        </td>
                        <td class="text-center"><?=$r->msg_kind?><? echo ($r->msg_type == "TEMP" ? "<BR>임시저장" :"");?></td>
                        <td class="text-center"><?=$r->reg_date?></td>
                        <td class="text-center"><?=$r->msg?></td>
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