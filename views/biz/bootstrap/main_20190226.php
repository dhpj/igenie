    <div class="content_wrap">
        <br>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget clear">
                    <div class="widget-header">
                        <h4>템플릿 목록</h4>
                    </div>
                    <div class="widget-content">
                        <div class="mt10">
                            <div class="col-xs-4">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content no-padding">
                                        <div class="visual green">
                                            <i class="icon-ok"></i>
                                        </div>
                                        <div class="title">승인</div>
														<div id="template_apr" class="value green">0</div>
                                    </div>

                                </div> <!-- /.smallstat -->
                            </div> <!-- /.col-md-3 -->
                            <div class="col-xs-4">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content no-padding">
                                        <div class="visual yellow">
                                            <i class=" icon-file"></i>
                                        </div>
                                        <div class="title">대기</div>
														<div id="template_req" class="value yellow">0</div>
                                    </div>
                                </div> <!-- /.smallstat -->
                            </div> <!-- /.col-md-3 -->
                            <div class="col-xs-4">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content no-padding">
                                        <div class="visual red">
                                            <i class="icon-remove"></i>
                                        </div>
                                        <div class="title">반려</div>
                                           <div id="template_rej" class="value red">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget clear">
                    <div class="widget-header">
                        <h4>발송 통계
                            <script language="javascript">
                                function leadingZeros(n, digits) {
                                    var zero = '';
                                    n = n.toString();

                                    if (n.length < digits) {
                                        for (i = 0; i < digits - n.length; i++)
                                            zero += '0';
                                    }
                                    return zero + n;
                                }

                                var today = new Date();
                                var res =
                                        leadingZeros(today.getFullYear(), 4) + '-' +
                                        leadingZeros(today.getMonth() + 1, 2) + '-' +
                                        leadingZeros(today.getDate(), 2);

                                document.write('(' + res + ')');
                            </script>
                        </h4>
                    </div>
                    <div class="widget-content">
                        <div class="mt10">
                            <div class="col-xs-4">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content no-padding">
                                        <div class="visual alimtalk">
                                            <i class="fa fa-bell" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">알림톡</div>
														<div id="cnt_at" class="value alimtalk">0</div>
                                    </div>
                                </div> <!-- /.smallstat -->
                            </div> <!-- /.col-md-3 -->
                            <div class="col-xs-4">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content no-padding">
                                        <div class="visual friendtalk">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">친구톡</div>
                                        <div id="cnt_ft" class="value friendtalk">0</div>
                                    </div>
                                </div> <!-- /.smallstat -->
                            </div> <!-- /.col-md-3 -->
                            <div class="col-xs-4">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content no-padding">
                                        <div class="visual sms">
                                            <i class="fa fa-comment" aria-hidden="true"></i>
                                        </div>
                                        <div class="title">폰문자</div>
                                        <div id="cnt_phn" class="value sms">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget clear">
                    <div class="widget-header">
                        <h4>주간 통계</h4>
                    </div>
                    <div class="widget-content">
                        <div class="mt10">
                            <div class="col-md-12"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;" src="/bizM/saved_resource.html"></iframe>
                                <canvas id="lineChart" width="970" height="323" style="display: block; width: 970px; height: 323px;"></canvas>
                            </div>
                        </div>
                        <a class="more" href="/biz/statistics/day">더보기<i class="pull-right icon-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div><!--//.content_wrap-->
    
    <div class="modal select fade" id="myModalUserMSGList" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true" style=" height: 600px;">
        <div class="modal-dialog modal-lg select-dialog" style="width: 800px;height: 540px;">
            <div class="modal-content" style="width: 800px;height: 540px;" >
                <br/>
                <h4 class="modal-title" align="center">공지사항</h4>
                <div class="modal-body select-body" style="height: 500px;">
                    <div >

                        <div class="content" id="modal_user_msg_list" style="overflow-y:scroll; height: 440px;" style="border:1px solid #aaa;">
									
                        </div>
                    </div>
                    <div align="center">
                        	<button type="button" class="btn btn-default dismiss" onclick="open_page_user_msg('1')">다음</button>
                        	<button type="button" class="btn btn-default dismiss" data-dismiss="modal">닫기</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="/bizM/js/Chart.bundle.js"></script>

    <script type="text/javascript">
        $("#header .left_box p").addClass("main");

        function getTimeStamp(dateStr) {
            var day = new Date(dateStr);
            var s =
                    leadingZeros(day.getFullYear(), 4) + '-' +
                    leadingZeros(day.getMonth() + 1, 2) + '-' +
                    leadingZeros(day.getDate(), 2);
            return s;
        }

        function getCalcTimeStamp(day) {
            var today = new Date();
            var d = new Date(Date.parse(today) - day * 1000 * 60 * 60 * 24); //특정일수 이전날짜 계산
            var s =
                    leadingZeros(d.getFullYear(), 4) + '-' +
                    leadingZeros(d.getMonth() + 1, 2) + '-' +
                    leadingZeros(d.getDate(), 2);
            return s;
        }

        function leadingZeros(n, digits) {
            var zero = '';
            n = n.toString();

            if (n.length < digits) {
                for (i = 0; i < digits - n.length; i++)
                    zero += '0';
            }
            return zero + n;
        }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        "use strict";
        $(document).ready(function () {
            var data_days = [];
            var data_success = [];
            var data_fail = [];

            for (var i = 7; i >= 1; i--) {
                data_days.push(getCalcTimeStamp(i));
                data_success.push(0);
                data_fail.push(0);
            }

            $( "#dialog-message" ).dialog({
                modal: true,
                buttons: {
                  Ok: function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
				/*
            var ctx = document.getElementById("lineChart");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data_days,
                    datasets: [
                        {
                            label: "발송 성공",
                            data: data_success,
                            backgroundColor: 'rgba(151,187,205,0.2)',
                            borderColor: 'rgba(5,141,199,1.0)',
                            borderWidth: 3,
                            lineTension: 0,
                            pointBorderColor: "rgba(5,141,199,1.0)",
                            pointBackgroundColor: "rgba(5,141,199,1.0)",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(5,141,199,1.0)",
                            pointHoverBorderColor: "rgba(5,141,199,1.0)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 4,
                            pointHitRadius: 10,
                            spanGaps: false
                        },
                        {
                            label: "발송 실패",
                            data: data_fail,
                            backgroundColor: 'rgba(255,101,82,0.2)',
                            borderColor: 'rgba(255,99,132,1)',
                            borderWidth: 3,
                            lineTension: 0,
                            pointBorderColor: "rgba(255,99,132,1)",
                            pointBackgroundColor: "rgba(255,99,132,1)",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(255,99,132,1)",
                            pointHoverBorderColor: "rgba(255,99,132,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 4,
                            pointHitRadius: 10,
                            spanGaps: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                callback: function(value, index, values) {
                                    if (value > 0 && value < 1) {
                                        return value.toFixed(1).toString();
                                    } else if (value > 999) {
                                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    } else {
                                        return value.toString();
                                    }
                                }
                            }
                        }],
                    },
                    title: { display: false },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var cnt_success = data.datasets[0].data[tooltipItem.index];
                                var cnt_fail = data.datasets[1].data[tooltipItem.index];

                                return "발송 성공 : " + Number(cnt_success).toFixed(0).replace(/./g, function(c, i, a) {
                                    return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                                }) + " 건" + " / " + "발송 실패 : " + Number(cnt_fail).toFixed(0).replace(/./g, function(c, i, a) {
                                    return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                                }) + " 건";
                            }
                        }
                    }
                }
            });*/
        });
    </script>

	 <script type="text/javascript">
        $(document).ready(function () {
            "use strict";

            
        });

        $(window).load(function(){
            $.ajax({
                url: "/biz/main/get_stat",
                type: "GET",
                success: function (data) {
                    var stats_template = data['tmpl_info'];

                    if (stats_template[0].apr_cnt != null) {
                        $("#template_apr").text(getIntComma(stats_template[0].apr_cnt));
                    }
                    if (stats_template[0].req_cnt != null) {
                        $("#template_req").text(getIntComma(stats_template[0].req_cnt));
                    }
                    if (stats_template[0].rej_cnt != null) {
                        $("#template_rej").text(getIntComma(stats_template[0].rej_cnt));
                    }
                    //alert(stats_template[0].msg_cnt);
                    if (stats_template[0].msg_cnt != null && stats_template[0].msg_cnt > 0) {
                    	open_msg_list();
                    }
                    
                    var stats_today = data['stats_today'];
                    if (stats_today[0].cnt_succ_kakao_at != null) {
                        $("#cnt_at").text(getIntComma(stats_today[0].cnt_succ_kakao_at));
                    }
                    if (stats_today[0].cnt_succ_kakao_ft != null) {
                        $("#cnt_ft").text(getIntComma(stats_today[0].cnt_succ_kakao_ft));
                    }
                    if (stats_today[0].cnt_succ_phn != null) {
                        $("#cnt_phn").text(getIntComma(stats_today[0].cnt_succ_phn));
                    }
                    getCharts(data['stats_weekly']);
                }
            });
        });

        $("#header .left_box p").addClass("main");

        /*
         * 문자 내역 저장용
         */
     	function open_msg_list() {

     		$("#myModalUserMSGList").modal({backdrop: 'static'});
     		$("#myModalUserMSGList").on('shown.bs.modal', function () {
     			$('.uniform').uniform();
     			$('select.select2').select2();
     		});

     		$('#myModalUserMSGList').unbind("keyup").keyup(function (e) {
     			var code = e.which;
     			if (code == 27) {
     				$(".btn-default.dismiss").click();
     			} else if (code == 13) {
     				include_customer()
     			}
     		});

     		$("#myModalUserMSGList .include_phns").click(function () {
     			include_customer();
     		});
     		
     		open_page_user_msg('1');		
     	}
         
        function open_page_user_msg(page, delids) {
            //alert('1');
    		var searchMsg = $('#searchMsg').val() || '';
    		var searchKind = $('#searchKind').val() || '';

    		$('#myModalUserMSGList .content').html('').load(
    			"/biz/message/view_lists",
    			{
    				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
    				"search_msg": searchMsg,
    				"search_kind": searchKind,
    				"msgonly":"Y",
    				"del_ids[]":delids,
    				'page': page,
    				'is_modal': true
    			},
    			function () {
    				$('.uniform').uniform();
    			}
    		);

        }

                
        function getIntComma(data) {
            return Number(data).toFixed(0).replace(/./g, function(c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;})
        }

        function getTimeStamp(dateStr) {
            var day = new Date(dateStr);
            var s =
                    leadingZeros(day.getFullYear(), 4) + '-' +
                    leadingZeros(day.getMonth() + 1, 2) + '-' +
                    leadingZeros(day.getDate(), 2);
            return s;
        }

        function getCalcTimeStamp(day) {
            var today = new Date();
            var d = new Date(Date.parse(today) - day * 1000 * 60 * 60 * 24); //특정일수 이전날짜 계산
            var s =
                    leadingZeros(d.getFullYear(), 4) + '-' +
                    leadingZeros(d.getMonth() + 1, 2) + '-' +
                    leadingZeros(d.getDate(), 2);
            return s;
        }

        function leadingZeros(n, digits) {
            var zero = '';
            n = n.toString();

            if (n.length < digits) {
                for (i = 0; i < digits - n.length; i++)
                    zero += '0';
            }
            return zero + n;
        }

        function getCharts(data) {
            var data_days = [];
            var data_success = [];
            var data_fail = [];

            for (var i = 7; i >= 1; i--) {
                data_days.push(getCalcTimeStamp(i));
                data_success.push(0);
                data_fail.push(0);
            }

            if (data.length > 0) {
                for (var j = 0; j < data_days.length; j++) {
                    //console.log(data[j]);
                    for (var k = 0; k < data.length; k++) {
                        if (data_days[j] === getTimeStamp(data[k].time)) {
                            data_success[j] = data[k].cnt_succ_total;
                            data_fail[j] = data[k].cnt_fail_total;
                        }
                    }
                }
            }

            var ctx = document.getElementById("lineChart");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data_days,
                    datasets: [
                        {
                            label: "발송 성공",
                            data: data_success,
                            backgroundColor: 'rgba(151,187,205,0.2)',
                            borderColor: 'rgba(5,141,199,1.0)',
                            borderWidth: 3,
                            lineTension: 0,
                            pointBorderColor: "rgba(5,141,199,1.0)",
                            pointBackgroundColor: "rgba(5,141,199,1.0)",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(5,141,199,1.0)",
                            pointHoverBorderColor: "rgba(5,141,199,1.0)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 4,
                            pointHitRadius: 10,
                            spanGaps: false
                        },
                        {
                            label: "발송 실패",
                            data: data_fail,
                            backgroundColor: 'rgba(255,101,82,0.2)',
                            borderColor: 'rgba(255,99,132,1)',
                            borderWidth: 3,
                            lineTension: 0,
                            pointBorderColor: "rgba(255,99,132,1)",
                            pointBackgroundColor: "rgba(255,99,132,1)",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(255,99,132,1)",
                            pointHoverBorderColor: "rgba(255,99,132,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 4,
                            pointHitRadius: 10,
                            spanGaps: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontFamily: "나눔고딕, NanumGothic, ng, tahoma, sans-serif"
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                fontFamily: "나눔고딕, NanumGothic, ng, tahoma, sans-serif",
                                beginAtZero: true,
                                min: 0,
                                callback: function(value, index, values) {
                                    if (value > 0 && value < 1) {
                                        return value.toFixed(1).toString();
                                    } else if (value > 999) {
                                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    } else {
                                        return value.toString();
                                    }
                                }
                            }
                        }]
                    },
                    title: { display: false },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var cnt_success = data.datasets[0].data[tooltipItem.index];
                                var cnt_fail = data.datasets[1].data[tooltipItem.index];

                                return "발송 성공 : " + getIntComma(cnt_success) + " 건" + " / " + "발송 실패 : " + getIntComma(cnt_fail) + " 건";
                            }
                        }
                    }
                }
            });
        }
    </script>