    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow: hidden;">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content" style="width: 320px;">
                <br>
                <div class="modal-body" style="height: 120px;">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br><br>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/partner/list">파트너</a></li>
            <li class="current"><a href="/biz/partner/partner_charge/aaa#" title="">파트너 상세보기</a></li>
        </ul>
    </div>
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                <form action="/biz/partner/partner_charge/aaa#">
                    <div class="widget">
                        <div class="widget-content">
                        
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle !important;">계정</th>
                                    <td><?=$rs->mem_userid?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">업체명</th>
                                    <td><?=$rs->mem_username?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">잔액</th>
                                    <td>₩<?=number_format($rs->mem_point + $rs->total_deposit, 1)?> &nbsp; [예치금 : <?=number_format($rs->total_deposit, 1)?>, 포인트 : <?=number_format($rs->mem_point, 1)?>] &nbsp; &nbsp; (사용가능: <font color='red' style="letter-spacing:1px;"><strong><?php echo number_format($this->Biz_model->getAbleCoin($rs->mem_id, $rs->mem_userid), 2); ?></strong></font>)</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="snb_nav">
            <ul class="clear">
                <li><a href="/biz/partner/view?<?=$param['key']?>">정보</a></li>
                <li class="active"><a href="/biz/partner/partner_charge?<?=$param['key']?>">충전내역</a></li>
                <li><a href="/biz/partner/partner_sent?<?=$param['key']?>">발신목록</a></li>
                <li><a href="/biz/partner/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
            </ul>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="modal">
                <div class="modal-content" style="width: 320px;">
                    <br>
                    <div class="modal-body" style="height: 120px;">
                        <div class="content">
                        </div>
                        <div>
                            <p align="right">
                                <br><br>
                                <button type="button" class="btn btn-custom" data-dismiss="modal">확인</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-content">
                        <form id="mainForm" name="mainForm" method="post" action="/biz/partner/partner_charge?<?=$param['key']?>">
                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                            <div class="mb20 mt10 clear">
                                <div class="flLeft">
                                    <a type="button" onclick="javascript:submit_handler_set(&#39;today&#39;)" class="submit btn btn-default btn-sm">오늘</a>
                                    <a type="button" onclick="javascript:submit_handler_set(&#39;week&#39;)" class="submit btn btn-default btn-sm">1주일</a>
                                    <a type="button" onclick="javascript:submit_handler_set(&#39;1month&#39;)" class="submit btn btn-default btn-sm">1개월</a>
                                    <a type="button" onclick="javascript:submit_handler_set(&#39;3month&#39;)" class="submit btn btn-default btn-sm">3개월</a>
                                    <a type="button" onclick="javascript:submit_handler_set(&#39;6month&#39;)" class="submit btn btn-default btn-sm">6개월</a>
												&nbsp;&nbsp;&nbsp;
                                    <input type="hidden" id="set_date" name="set_date">
                                    <input type="text" class="form-control input-width-small inline datepicker" name="startDate" id="startDate" value="<?=$param['startDate']?>" readonly="readonly" style="cursor: pointer;background-color: white"> ~
                                    <input type="text" class="form-control input-width-small inline datepicker" name="endDate" id="endDate" value="<?=$param['endDate']?>" readonly="readonly" style="cursor: pointer;background-color: white">
                                    <button class="btn btn-default" id="search" type="button" onclick="javascript:submit_handler()">조회
                                    </button>
                                </div>

                                <div class="pull-right mt10">전체 : <?=number_format($total_rows)?>건</div>
                            </div>
                            <table class="table table-striped table-bordered table-highlight-head t_center" id="table_id">  
                                <thead>
                                <tr>
                                    <th>충전/조정 일시</th>
                                    <th>충전 금액</th>
                                    <th>조정 금액</th>
                                    <th>비  고</th>
                                </tr>
                                </thead>
                                <tbody>
											<?foreach($list as $row) {?>
                                        <tr>
                                            <td width="25%" style="word-break:break-all;<?=($row->amt_kind=='2') ? 'color:red;' : ''?>"><?=$row->amt_datetime?></td>
                                            <td width="20%" style="word-break:break-all;<?=($row->amt_kind=='2') ? 'color:red;' : ''?>"><?=($row->amt_kind!='2') ? '₩'.number_format($row->amt_amount) : '&nbsp;'?></td>
                                            <td width="20%" style="word-break:break-all;<?=($row->amt_kind=='2') ? 'color:red;' : ''?>"><?=($row->amt_kind=='2') ? '₩'.number_format($row->amt_amount) : '&nbsp;'?></td>
                                            <td width="35%" style="word-break:break-all;<?=($row->amt_kind=='2') ? 'color:red;' : ''?>"><?=$row->amt_memo?></td>
                                        </tr>
											<?}?>
                                </tbody>
                            </table>
                            <!--<div class="align-left">
                                <button class="btn" type="button" onclick="download_charge()"><i class="icon-arrow-down"></i>엑셀 다운로드</button>
                            </div>-->
                            <div class="pagination align-center"><?echo $page_html?></div>
                        </form>
                        <div class="mt30 align-center">
                            <a type="button" href="/biz/partner/edit?<?=$param['key']?>" class="submit btn btn-custom">수정</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .select2-no-results {
            display: none !important;
        }

        textarea {
            resize: none;
        }
    </style>
    <script type="text/javascript">
        $("#nav li.nav60").addClass("current open");

        $("#wrap").css('position', 'absolute');
        $(".modal-content").css('width', '320px');
        $(".modal-body").css('height', '120px');


        $("#myModal").css('overflow-x', 'hidden');
        $("#myModal").css('overflow-y', 'hidden');

        $('#startDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: '-180d',
            endDate: '-1d'
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.date.valueOf());
            $('#endDate').datepicker('setStartDate', startDate);
        });

        var start = $("#startDate").val();
        $('#endDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: start,
            endDate: '-1d'
        }).on('changeDate', function (selected) {
            var endDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', endDate);
        });


        function open_page(page) {
            var form = document.getElementById("mainForm");

            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);

            form.appendChild(pageField);
            form.submit();
        }

        function submit_handler() {
            var form = document.getElementById('mainForm');

            if (($('#startDate').val() == "") || ($('#endDate').val() == "")) {
                $(".content").html("조회하실 기간을 선택해주세요.");
                $("#myModal").modal('show');
            } else {
                form.submit();
            }
        }

        function submit_handler_set(day) {
            var form = document.getElementById('mainForm');

            if(day == 'today'){
                $('input[name="set_date"]').attr('value','today');
            }
            else if (day == 'week'){
                $('input[name="set_date"]').attr('value','week');
            }
            else if (day == '1month'){
                $('input[name="set_date"]').attr('value','1month');
            }
            else if (day == '3month'){
                $('input[name="set_date"]').attr('value','3month');
            }
            else if (day == '6month'){
                $('input[name="set_date"]').attr('value','6month');
            }
            else {
                $('input[name="set_date"]').attr('value', '');
            }
            form.submit();
        }

        //CSV 파일 다운로드
        function download_charge() {
            var list = '';
            

            if(list==''){
                $(".content").html("충전 내역이 없습니다.");
                $("#myModal").modal('show');
            } else {
                var val=[];

                

                var value = JSON.stringify(val);
                var form = document.createElement("form");
                document.body.appendChild(form);
                form.setAttribute("method", "post");
                form.setAttribute("action", "/partner/download_charge_historyaaa");
                var valueField = document.createElement("input");
                valueField.setAttribute("type", "hidden");
                valueField.setAttribute("name", "value");
                valueField.setAttribute("value", value);
                form.appendChild(valueField);
                form.submit();
            }
        }

        jQuery.download = function (url, data, method) {
            //url and data options required
            if (url && data) {
                //data can be string of parameters or array/object
                data = typeof data == 'string' ? data : jQuery.param(data);
                //split params into form inputs
                var inputs = '';
                jQuery.each(data.split('&'), function () {
                    var pair = this.split('=');
                    inputs += '<input type="hidden" name="' + pair[0] + '" value="' + pair[1] + '" />';
                });
                //send request
                jQuery('<form action="' + url + '" method="' + (method || 'post') + '">' + inputs + '</form>')
                        .appendTo('body').submit().remove();
            };
        };


        //확인창 확인버튼
        function click_btn_custom() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-custom").click();
                }
            });
        }

        //예-아니오에서의 확인버튼
        function click_btn_primary() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-primary").click();
                }
            });
        }

    </script>
