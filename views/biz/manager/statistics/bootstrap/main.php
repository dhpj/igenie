<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu18.php');
?>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$this->funn->get_kakao_key();?>&libraries=services"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div id="mArticle">
	<div class="form_section">
    <div class="inner_tit">
			<h3>통계메인</h3>
		</div>
		<div class="white_box">
            <div id="map" style="width:500px;height:400px;">
            </div>
		</div>
        <div class="white_box">
            <canvas id="myChart" width="1200" height="600"></canvas>
            <table id='tb_method'>
            </table>
        </div>
	</div>
</div>
<script type="text/javascript">
    let obj_method;
    var container = document.getElementById('map'); //지도를 담을 영역의 DOM 레퍼런스
    var options = { //지도를 생성할 때 필요한 기본 옵션
        center: new kakao.maps.LatLng(36.600824, 128.003660), //지도의 중심좌표.
        level: 13 //지도의 레벨(확대, 축소 정도)
    };

    var map = new kakao.maps.Map(container, options); //지도 생성 및 객체 리턴

    $(document).ready(function(){
        get_marker();
        get_method();
    });

    function get_marker(){
        $.ajax({
            url: "/biz/manager/statistics/get_marker",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
            },
            success: function (json) {
                var geocoder = new kakao.maps.services.Geocoder();
                if (json.cnt > 0){
                    const delay = () => {
                        const randomDelay = 0.000001;
                        return new Promise(resolve => setTimeout(resolve, randomDelay))
                    }
                    const result = async (list, geo) => {
                        for (const data of list) {
                            await delay()
                            .then(
                                () => {
                                    geocoder.addressSearch(data['addr'], function(result, status) {

                                        // 정상적으로 검색이 완료됐으면
                                         if (status === kakao.maps.services.Status.OK) {

                                            var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                                            // 결과값으로 받은 위치를 마커로 표시합니다
                                            var marker = new kakao.maps.Marker({
                                                map: map,
                                                position: coords,
                                                title: data['addr'] + '/'+ '주문건수 : ' + data['cnt']
                                            });

                                            var infowindow = new kakao.maps.InfoWindow({
                                                position : coords,
                                                content : '<div>' + data['addr'] + '/'+ '주문건수 : ' + data['cnt'] + '</div>'
                                            });
                                            infowindow.open(map, marker);
                                        }
                                    });
                                }
                            )
                        }
                    }
                    var geocoder = new kakao.maps.services.Geocoder();
                    result(json.list, geocoder);
                }
            }
        });
    }

    function get_method(){
        $.ajax({
            url: "/biz/manager/statistics/get_method",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
            },
            success: function (json) {
                set_tb_method('tb_method', json);
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

                const ctx = document.getElementById('myChart');

                obj_method = new Chart(ctx,{
                    type: 'line',
                    data: data,
                    options: {
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
                        responsive: false,
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
                        }
                    },
                });
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

</script>
