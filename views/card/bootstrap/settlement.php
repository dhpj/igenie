<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu16.php');
?>
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
<!-- 3차 메뉴 -->
<?php
// include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu12.php');
?>
<!-- //3차 메뉴 -->

<style type="text/css">
.table-responsive th { vertical-align:middle !important; }
.table-responsive table td{text-align: right !important;}
.sum_bg{background: #f8f9fd !important; font-weight: bold;}
</style>
<div id="mArticle">
	<div class="form_section">
    <div class="inner_tit">
			<h3>정산관리</h3>
		</div>
		<div class="white_box">
			<div class="search_wrap">
	                <div class="btn-group btn-group-sm" role="group">
                        <label>년</label>&nbsp;
                        <span class="dateBox" style="margin-right: 10px;">
        					<input type="text" class="datepicker" name="yeardate" id="yeardate" value="<?=$yeardate;?>" readonly="readonly">
        			    </span>
                        <label>월</label>&nbsp;
                        <span class="dateBox" style="margin-right: 10px;">
        					<input type="text" class="datepicker" name="monthdate" id="monthdate" value="<?=$monthdate;?>" readonly="readonly">
        			    </span>
                        <label>일</label>&nbsp;
    					<input type='checkbox' id='week1' <?=$week1 == 1 ? 'checked' : ''?>><label for='week1'>1주차</label>
                        <input type='checkbox' id='week2' <?=$week2 == 1 ? 'checked' : ''?>><label for='week2'>2주차</label>
                        <input type='checkbox' id='week3' <?=$week3 == 1 ? 'checked' : ''?>><label for='week3'>3주차</label>
                        <input type='checkbox' id='week4' <?=$week4 == 1 ? 'checked' : ''?>><label for='week4'>4주차</label>
                        <input type='checkbox' id='week5' <?=$week5 == 1 ? 'checked' : ''?>><label for='week5'>5주차</label>
                        &nbsp;&nbsp;
                        <?
                            $sd = substr($startdt, 0, 4) . "-" . substr($startdt, 4, 2) . "-" . substr($startdt, 6, 2);
                            $ed = substr($enddt, 0, 4) . "-" . substr($enddt, 4, 2) . "-" . substr($enddt, 6, 2);
                        ?>
                        <input type="button" class="btn md fr" id="check" value="조회" onclick="open_page()"/>
	                </div>
                    <button type="button" class="btn md excel fr" onclick="download_();">
    				<i class="icon-arrow-down"></i> 엑셀 다운로드</button>
			</div>
            <div class="mt20"><!--전체 : <span id="total_amount"></span>건--></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
					<colgroup>
    					<col width="*"/><?//업체명?>
                        <col width="10%"/><?//업체명?>
    					<col width="10%"/><?//거래금액?>
    					<col width="10%"/><?//전산금액?>
    					<col width="10%"/><?//수수료?>
                        <col width="10%"/><?//부가가치세?>
                        <col width="10%"/><?//무이자수수료?>
                        <col width="10%"/><?//지불금액?>
                        <col width="10%"/><?//수익금액?>
    					<!-- <col width="10%"/><?//상태?> -->
					</colgroup>
                    <thead>
                        <tr>
                            <th class="align-center">업체명</th>
                            <th class="align-center">주차</th>
                            <th class="align-center">거래금액</th>
                            <th class="align-center">정산금액</th>
                            <th class="align-center">수수료</th>
                            <th class="align-center">부가가치세</th>
                            <th class="align-center">무이자수수료</th>
                            <th class="align-center">지불금액</th>
                            <th class="align-center">수익금액</th>
                            <!-- <th class="align-center">상태</th> -->
                        </tr>
                    </thead>
                    <tbody>
            <?
                if (!empty($list)){
                    $aamt = 0;
                    $adepositAmt = 0;
                    $afee = 0;
                    $avat = 0;
                    $aninstFee = 0;
                    $apay = 0;
                    $arevenue = 0;
                    foreach ($list as $r) {
                        $aamt += !empty($r->amt) ? $r->amt : 0;
                        $adepositAmt += !empty($r->depositAmt) ? $r->depositAmt : 0;
                        $afee += !empty($r->fee) ? $r->fee : 0;
                        $avat += !empty($r->vat) ? $r->vat : 0;
                        $aninstFee += !empty($r->ninstFee) ? $r->ninstFee : 0;
                        $arevenue += !empty($r->ninstFee) ? ($r->amt * (config_item('FEE')/100)) : 0;
                        $revenue = $r->amt * (config_item('FEE')/100);
                        $apay += $r->depositAmt - $revenue;

            ?>
                        <tr>
                            <td class="align-center"><?=$r->mem_username?></td>
                            <td class="align-center"><?=$r->weeks?></td>
                            <td class="align-center"><?=number_format($r->amt)?></td>
                            <td class="align-center"><?=number_format($r->depositAmt)?></td>
                            <td class="align-center"><?=number_format($r->fee)?></td>
                            <td class="align-center"><?=number_format($r->vat)?></td>
                            <td class="align-center"><?=number_format($r->ninstFee)?></td>
                            <td class="align-center"><?=number_format($r->depositAmt - $revenue)?></td>
                            <td class="align-center"><?=number_format($revenue)?></td>
                            <!-- <td class="align-center">
                                <select onChange="set_status(this, <?=$r->mem_id?>, <?=$r->startdt?>, '<?=$r->mid?>', '<?=$r->serviceId?>', '<?=$r->subId?>');">
                                    <option value="Y" <?=$r->status == "Y" ? "selected" : ""?>>완</option>
                                    <option value="N" <?=$r->status == "N" ? "selected" : ""?>>미</option>
                                </select>
                            </td> -->
                        </tr>
			<?
                    }
            ?>
                        <tr>
                            <td colspan=2 class="align-center sum_bg">합  계</td>
                            <td class="align-right sum_bg"><?=number_format($aamt)?></td>
                            <td class="align-right sum_bg"><?=number_format($adepositAmt)?></td>
                            <td class="align-right sum_bg"><?=number_format($afee)?></td>
                            <td class="align-right sum_bg"><?=number_format($avat)?></td>
                            <td class="align-right sum_bg"><?=number_format($aninstFee)?></td>
                            <td class="align-right sum_bg"><?=number_format($apay)?></td>
                            <td class="align-right sum_bg"><?=number_format($arevenue)?></td>
                            <!-- <td></td> -->
                        </tr>
            <?
                } else {
            ?>
                        <tr>
                            <td colspan="20" class="nopost"><p class="icon_nopost">자료가 없습니다.</p></td>
                        </tr>
            <?
                }
            ?>
                    </tbody>
                </table>
            </div>
			<br/>
		</div>
	</div>
</div>

<script type="text/javascript">
    function open_page() {

        if ($('#yeardate').val() == ''){
            alert('년도를 지정해주세요.');
            $('#yeardate').focus();
            return;
        }

        if ($('#monthdate').val() == ''){
            alert('월을 지정해주세요.');
            $('#monthdate').focus();
            return;
        }

        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/card/settlement");

        var cfrsField = document.createElement("input");
        cfrsField.setAttribute("type", "hidden");
        cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(cfrsField);

        var searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "yeardate");
        searchTypeField.setAttribute("value", $('#yeardate').val());
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "monthdate");
        searchTypeField.setAttribute("value", $('#monthdate').val());
        form.appendChild(searchTypeField);

        var week = $('input:checkbox[id="week1"]').is(":checked") ? 1 : 2;
        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week1");
        searchTypeField.setAttribute("value", week);
        form.appendChild(searchTypeField);

        week = $('input:checkbox[id="week2"]').is(":checked") ? 1 : 2;
        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week2");
        searchTypeField.setAttribute("value", week);
        form.appendChild(searchTypeField);

        week = $('input:checkbox[id="week3"]').is(":checked") ? 1 : 2;
        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week3");
        searchTypeField.setAttribute("value", week);
        form.appendChild(searchTypeField);

        week = $('input:checkbox[id="week4"]').is(":checked") ? 1 : 2;
        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week4");
        searchTypeField.setAttribute("value", week);
        form.appendChild(searchTypeField);

        week = $('input:checkbox[id="week5"]').is(":checked") ? 1 : 2;
        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week5");
        searchTypeField.setAttribute("value", week);
        form.appendChild(searchTypeField);

        form.submit();
    }

    //엑셀 다운로드
    function download_() {
        if ("<?=$yeardate?>" == ''){
            return;
        }
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/card/wdownload");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "yeardate");
        searchTypeField.setAttribute("value", "<?=$yeardate?>");
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "monthdate");
        searchTypeField.setAttribute("value", "<?=$monthdate?>");
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week1");
        searchTypeField.setAttribute("value", "<?=$week1?>");
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week2");
        searchTypeField.setAttribute("value", "<?=$week2?>");
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week3");
        searchTypeField.setAttribute("value", "<?=$week3?>");
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week4");
        searchTypeField.setAttribute("value", "<?=$week4?>");
        form.appendChild(searchTypeField);

        searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "week5");
        searchTypeField.setAttribute("value", "<?=$week5?>");
        form.appendChild(searchTypeField);

        form.submit();

    }

    function set_status(t, mem_id, startdt, mid, serviceId, subId){
        $.ajax({
            url: "/card/set_status2",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : mem_id
              , status : $(t).val()
              , startdt : startdt
              , mid : mid
              , serviceId : serviceId
              , subId : subId
            },
            success: function (json) {
            }
        });
    }

    //2021-10-14 커스텀 날짜 추가
    $('#yeardate').datepicker({
        minViewMode: 'years',
        format: "yyyy",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
        startDate: '-60m',
        endDate: '-1d',
    }).on('changeDate', function (selected) {
        let startDate = new Date(Number($('#yeardate').val()), 0, 1);
        let endDate = new Date(Number($('#yeardate').val()), 11, 1);
        // alert(startDate);
        $('#monthdate').datepicker('setStartDate', startDate);
        $('#monthdate').datepicker('setEndDate', endDate);
    });

    $('#monthdate').datepicker({
        minViewMode: 'months',
        format: "mm",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
        startDate: '-60m',
        endDate: '-1d'
    }).on('changeDate', function (selected) {
        // var startDate = new Date(selected.date.valueOf());
        // $('#endDate').datepicker('setStartDate', startDate);
    });

    $(document).ready(function(){
        let startDate = new Date(Number($('#yeardate').val()), 0, 1);
        let endDate = new Date(Number($('#yeardate').val()), 11, 1);
        // alert(startDate);
        $('#monthdate').datepicker('setStartDate', startDate);
        $('#monthdate').datepicker('setEndDate', endDate);
    });

</script>
