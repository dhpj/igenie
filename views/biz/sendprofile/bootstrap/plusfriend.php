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
    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/sendprofile/list">발신 프로필</a></li>
            <li class="current"><a href="#" title="">플러스 친구 통계</a></li>
        </ul>
    </div>
    <div class="snb_nav">
        <ul class="clear">
            <li class="active"><a href="/biz/sendprofile/plusfriend">일별</a></li>
        </ul>
    </div>

    <input type='hidden' name='csrfmiddlewaretoken' value='46jMlCPiJqlrgyBaN6djd306uGmSiG8o' />
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                <div class="widget">
                    <div class="widget-content">
                        <div class="mb20 mt10 clear">
                            <input type="text" class="form-control input-width-medium inline datepicker"
                                   name="startDate" id="startDate" value="2017-10-17" readonly="readonly"
                                   style="cursor: pointer;background-color: white"> ~
                            <input type="text" class="form-control input-width-medium inline datepicker"
                                   name="endDate" id="endDate" value="2017-11-15" readonly="readonly"
                                   style="cursor: pointer;background-color: white">
                            <select name="yellowId" id="yellowId" class="select2 input-width-large search-static">
                                <option value="ALL">업체명</option>
                                
                                    
                                        <option value="@한국산업인력공단">한국산업인력공단(@한국산업인력공단)</option>
                                    
                                
                            </select>
                            <button class="btn btn-default" id="search" type="button"
                                    onclick="searchHistory()">조회
                            </button>
                        </div>

                        
                            <script type="text/javascript">
                                $("#yellowId").val("ALL");
                            </script>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget clear">
                    <div class="widget-header">
                        <h4>통계</h4>
                    </div>
                    <div class="widget-content">
                        <div class="mt10">
                            <div class="col-md-12" id="history_chart">
                                <canvas id="lineChart" width="900" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--//.content_wrap-->

    <style>
        .text-center {
            vertical-align: middle !important;
        }

        .text-right {
            vertical-align: middle !important;
        }

        #search {
            margin-left: 3px;
        }

        th[rowspan="2"] {
            line-height: 50px !important;
        }
    </style>
