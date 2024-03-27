<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu18.php');
?>
<link rel="stylesheet" type="text/css" href="/views/biz/manager/statistics/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div id="mArticle" class="statistics_detail">
	<div class="form_section">
    <div class="inner_tit">
			<h3>업체통계</h3>
		</div>
        <?=$mem_info->mem_username?>
		<div class="white_box">
            <button></button>
            <label>월</label>
            <input type="text" class="datepicker" id="monthdate1" value="" readonly="readonly">
            <input type="text" class="datepicker" id="monthdate2" value="" readonly="readonly">
            <button onclick='get_method();get_method2();'>조회</button>
            <input type="radio" id="opt_method" class='opt_method' name="opt_method" value="A" checked="">
            <label for="opt_method">월간</label>
            <input type="radio" id="opt_method2" class='opt_method' name="opt_method" value="B">
            <label for="opt_method2">주간</label>
            <canvas id="myChart" width="600" height="450"></canvas>
            <table id='tb_method'>
            </table>
            <canvas id="myChart5" width="1000" height="450" style='display:none'></canvas>
            <table id='tb_method2' style='display:none'>
            </table>
        </div>
        <div class="white_box">
            <label>월</label>
            <input type="text" class="datepicker" id="monthdate3" value="" readonly="readonly">
            <input type="text" class="datepicker" id="monthdate4" value="" readonly="readonly">
            <button onclick='get_cycle();get_amount();'>조회</button>
            <canvas id="myChart2" width="600" height="450"></canvas>
        </div>
        <div class="white_box">
            <canvas id="myChart3" width="600" height="450"></canvas>
        </div>
        <div class="white_box">
            <canvas id="myChart4" width="600" height="450"></canvas>
        </div>
	</div>
</div>
<script>
    let obj_method;
    let obj_method2;
    let obj_cycle;
    let obj_amount;

    let date = new Date();
    pdate = new Date(date.getFullYear(), date.getMonth(), 1);
    cdate = new Date(date.getFullYear(), date.getMonth(), 1, 23, 59, 59);
    pdate.setFullYear(date.getFullYear() -2);

    $(document).ready(function(){
        get_method();
        get_cycle();
        get_amount();
        get_method2();
    });

    function get_method(){
        $.ajax({
            url: "/biz/manager/statistics/get_method",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : '<?=$mem_id?>'
              , sdate : $('#monthdate1').val()
              , edate : $('#monthdate2').val()
              , dtype : 'month'
            },
            success: function (json) {
                set_method(json, 1, 'myChart', true);
                set_tb_method('tb_method', json);
            }
        });
    }

    function get_method2(){
        $.ajax({
            url: "/biz/manager/statistics/get_method",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : '<?=$mem_id?>'
              , sdate : $('#monthdate1').val()
              , edate : $('#monthdate2').val()
              , dtype : 'weeks'
            },
            success: function (json) {
                set_method(json, 2, 'myChart5', false);
                set_tb_method('tb_method2', json);
            }
        });
    }

    function set_tb_method(id, json){
        $('#' + id).children().remove();
        let spot = 0;
        let m_spot = 0;
        let elec = 0;
        let m_elec = 0;
        let acc = 0;
        let m_acc = 0;
        let lcl = 0;
        let m_lcl = 0;
        let a_tr = 0;
        let a_amt = 0;

        let tr = 0;

        let html = '';
        html += '<thead><tr>';
        html += '<th>날짜</th>';
        html += '<th>현장결제</th>';
        html += '<th>현장결제금액</th>';
        html += '<th>카드결제</th>';
        html += '<th>카드결제금액</th>';
        html += '<th>계좌이체</th>';
        html += '<th>계좌이체금액</th>';
        html += '<th>지역화폐</th>';
        html += '<th>지역화폐금액</th>';
        html += '<th>총거래</th>';
        html += '<th>총금액</th>';
        html += '</tr></thead>';
        html += '<tbody>';
        for(var i=0; i<json.mdate.length; i++){
            tr = Number(json.spot[i]) + Number(json.elec[i]) + Number(json.acc[i]) + Number(json.lcl[i]);

            spot += Number(json.spot[i]);
            m_spot += Number(json.m_spot[i]);
            elec += Number(json.elec[i]);
            m_elec += Number(json.m_elec[i]);
            acc += Number(json.acc[i]);
            m_acc += Number(json.m_acc[i]);
            lcl += Number(json.lcl[i]);
            m_lcl += Number(json.m_lcl[i]);
            a_tr += tr;
            a_amt += Number(json.money[i]);


            html += '<tr>';
            html += '<td>' + json.mdate[i] + '</td>';
            html += '<td>' + rtnComma(json.spot[i]) + '건</td>';
            html += '<td>' + rtnComma(json.m_spot[i]) + '원</td>';
            html += '<td>' + rtnComma(json.elec[i]) + '건</td>';
            html += '<td>' + rtnComma(json.m_elec[i]) + '원</td>';
            html += '<td>' + rtnComma(json.acc[i]) + '건</td>';
            html += '<td>' + rtnComma(json.m_acc[i]) + '원</td>';
            html += '<td>' + rtnComma(json.lcl[i]) + '건</td>';
            html += '<td>' + rtnComma(json.m_lcl[i]) + '원</td>';
            html += '<td>' + rtnComma(tr) + '건</td>';
            html += '<td>' + rtnComma(json.money[i]) + '원</td>';
            html += '</tr>';
        }
        html += '<tr>';
        html += '<td>합계</td>';
        html += '<td>' + rtnComma(spot) + '건</td>';
        html += '<td>' + rtnComma(m_spot) + '원</td>';
        html += '<td>' + rtnComma(elec) + '건</td>';
        html += '<td>' + rtnComma(m_elec) + '원</td>';
        html += '<td>' + rtnComma(acc) + '건</td>';
        html += '<td>' + rtnComma(m_acc) + '원</td>';
        html += '<td>' + rtnComma(lcl) + '건</td>';
        html += '<td>' + rtnComma(m_lcl) + '원</td>';
        html += '<td>' + rtnComma(a_tr) + '건</td>';
        html += '<td>' + rtnComma(a_amt) + '원</td>';
        html += '</tr>';
        html += '</tbody>';
        $('#' + id).append(html);
    }

    function set_method(json, seq, id, flag){
        const data = {
            labels: json.mdate,
            datasets: [
                {
                    label: '현장결제',
                    data: json.spot,
                    fill: false,
                    borderColor: 'rgb(240,128,128)',
                    yAxisID:'y',
                },
                {
                    label: '카드결제',
                    data: json.elec,
                    fill: false,
                    borderColor: 'rgb(30,144,255)',
                    yAxisID:'y',

                },
                {
                    label: '계좌이체',
                    data: json.acc,
                    fill: false,
                    borderColor: 'rgb(34,139,34)',
                    yAxisID:'y',
                },
                {
                    label: '지역화폐',
                    data: json.lcl,
                    fill: false,
                    borderColor: 'rgb(218,165,32)',
                    yAxisID:'y',
                },
                {
                    label: '현장결제금액',
                    data: json.m_spot,
                    fill: false,
                    backgroundColor: 'rgb(240,128,128,0.3)',
                    type:'bar',
                    stack:'stack0',
                    yAxisID:'y1'
                },
                {
                    label: '카드결제금액',
                    data: json.m_elec,
                    fill: false,
                    backgroundColor: 'rgb(30,144,255,0.3)',
                    type:'bar',
                    stack:'stack0',
                    yAxisID:'y1'
                },
                {
                    label: '계좌이체금액',
                    data: json.m_acc,
                    fill: false,
                    backgroundColor: 'rgb(34,139,34,0.3)',
                    type:'bar',
                    stack:'stack0',
                    yAxisID:'y1'
                },
                {
                    label: '지역화폐금액',
                    data: json.m_lcl,
                    fill: false,
                    backgroundColor: 'rgb(218,165,32,0.3)',
                    type:'bar',
                    stack:'stack0',
                    yAxisID:'y1'
                },
                {
                    label: '총금액',
                    data: json.money,
                    fill: false,
                    backgroundColor: 'rgb(221,160,221,0.3)',
                    type:'bar',
                    stack:'stack1',
                    yAxisID:'y1'
                },
            ]
        };

        const ctx = document.getElementById(id);

        let opt = {
            responsive: false,
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default') {
                        delay = context.dataIndex * 100 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            plugins: {
                title: {
                    display: true,
                    text: '결제방식과 금액'
                }
            },
            scales: {
                x: {
                    ticks: {
                        callback: function(val, index) {
                            return index % 1 === 0 ? this.getLabelForValue(val) : '';
                        },
                        color: 'black',
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',

                    // grid line settings
                    grid: {
                      drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                },
            },
        };

        if (seq == 1){
            if (obj_method != undefined){
                obj_method.destroy();
            }
            obj_method = new Chart(ctx,{
                type: 'line',
                data: data,
                options: opt,
            });
        } else if (seq == 2){
            if (obj_method2 != undefined){
                obj_method2.destroy();
            }
            obj_method2 = new Chart(ctx,{
                type: 'line',
                data: data,
                options: opt,
            });
        }



        if (flag){
            $('#monthdate1').datepicker('setStartDate', pdate);
            $('#monthdate1').datepicker('setEndDate', new Date(json.edate + '-01'));
            $('#monthdate1').datepicker('setDate', new Date(json.sdate + '-01 00:00:00'));
            $('#monthdate2').datepicker('setStartDate', new Date(json.sdate + '-01'));
            $('#monthdate2').datepicker('setEndDate', cdate);
            $('#monthdate2').datepicker('setDate', new Date(json.edate + '-01 00:00:00'));
        }
    }

    function get_cycle(){
        $.ajax({
            url: "/biz/manager/statistics/get_cycle",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : '<?=$mem_id?>'
              , sdate : $('#monthdate3').val()
              , edate : $('#monthdate4').val()
            },
            success: function (json) {
                if (obj_cycle != undefined){
                    obj_cycle.destroy();
                }
                set_cycle(json);
            }
        });
    }

    function set_cycle(json){
        const data = {
            labels: json.ticks,
            datasets: [
                {
                    label: '고객',
                    data: json.cnt,
                    fill: false,
                    backgroundColor: 'rgb(221,160,221,0.5)',
                    type:'bar',
                },
            ]
        };

        const ctx = document.getElementById('myChart2');

        obj_cycle = new Chart(ctx,{
            type: 'bar',
            data: data,
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: '구매횟수(2년)'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(val, index) {
                                return index % 1 === 0 ? this.getLabelForValue(val) : '';
                            },
                            color: 'black',
                        }
                    },
                },
            },
        });
        $('#monthdate3').datepicker('setStartDate', pdate);
        $('#monthdate3').datepicker('setEndDate', new Date(json.edate + '-01'));
        $('#monthdate3').datepicker('setDate', new Date(json.sdate + '-01 00:00:00'));
        $('#monthdate4').datepicker('setStartDate', new Date(json.sdate + '-01'));
        $('#monthdate4').datepicker('setEndDate', cdate);
        $('#monthdate4').datepicker('setDate', new Date(json.edate + '-01 00:00:00'));
    }

    function get_amount(){
        $.ajax({
            url: "/biz/manager/statistics/get_amount",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , mem_id : '<?=$mem_id?>'
              , sdate : $('#monthdate3').val()
              , edate : $('#monthdate4').val()
            },
            success: function (json) {
                if (obj_amount != undefined){
                    obj_amount.destroy();
                }
                set_amount(json);
            }
        });
    }

    function set_amount(json){
        const data = {
            labels: json.amt,
            datasets: [
                {
                    label: '횟수',
                    data: json.cnt,
                    fill: false,
                    backgroundColor: 'rgb(221,160,221,0.5)',
                    type:'bar',
                },
            ]
        };

        const ctx = document.getElementById('myChart3');

        obj_amount = new Chart(ctx,{
            type: 'bar',
            data: data,
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: '구매액수(2년)'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(val, index) {
                                return index % 1 === 0 ? this.getLabelForValue(val) : '';
                            },
                            color: 'black',
                        }
                    },
                },
            },
        });
    }

    $('.datepicker').datepicker({
        minViewMode: 'months',
        format: "yyyy-mm",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
    });

    $('.opt_method').on('click', function(){
        if ($(this).val() == 'A'){
            $('#myChart').css('display', '');
            $('#tb_method').css('display', '');
            $('#myChart5').css('display', 'none');
            $('#tb_method2').css('display', 'none');
        } else if($(this).val() == 'B') {
            $('#myChart').css('display', 'none');
            $('#tb_method').css('display', 'none');
            $('#myChart5').css('display', '');
            $('#tb_method2').css('display', '');
        }
    });

</script>
