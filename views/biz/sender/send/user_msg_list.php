<div class="table_list">
    <!--table class="table table-hover table-striped table-bordered table-highlight-head table-checkable"-->
	<table>
        <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
        <colgroup>
            <col width="40px">
            <col width="80px">
            <col width="100px">
            <col width="*">
        </colgroup>
        <thead>
        <tr>
            <th class="checkbox-column">선택</th>
            <th class="tc">종류</th>
            <th class="tc">등록일</th>
            <th class="tc">메세지</th>
        </tr>
        </thead>
        <tbody>
        <?foreach($list as $r) {?>
                <!-- <tr onclick='set_lms_by_user_msg("<?=$r->msg_id?>");'> -->
                <tr>
                    <td class="checkbox-column tc">
                        <input type="checkbox" name="selMsg" class="" value="<?=$r->msg_id?>">
                    </td>
                    <td class="tc"><?=$r->msg_kind?><? echo ($r->msg_type == "TEMP" ? "<BR>임시저장" :"");?></td>
                    <td class="tc"><?=$r->reg_date?></td>
                    <td class="tc" name="name"><?=$r->msg?></td>
                </tr>
        <?}?>
        </tbody>
    </table>
</div>

<div class="pagination tc" style="width:100%"><?echo $page_html?></div>

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
