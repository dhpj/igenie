    <style>
        .dual-list .list-group {
            margin-top: 20px;
        }

        .list-left li, .list-right li, .list-profile-group li {
            cursor: pointer;
        }

        .list-arrows {
            padding-top: 150px;
        }

        .list-arrows button {
            margin-bottom: 20px;
        }
    </style>
    <input type='hidden' name='csrfmiddlewaretoken' value='46jMlCPiJqlrgyBaN6djd306uGmSiG8o' />
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
                            <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
                                확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="submit" class="btn btn-primary delete-btn" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/sendprofile/list">발신 프로필</a></li>
            <li class="current"><a href="#" title="">발신 프로필 그룹</a></li>
        </ul>
    </div>
    <div class="content_wrap">
        <!-- 발신 프로필 그룹 가이드 -->
        <div class="row">
            <div class="widget">
                <div class="widget-header">
                    <h3>발신 프로필 그룹 가이드</h3>
                </div>
                <div class="widget-content">
                    <div class="well">
                        <p>
                        <li>하나의 발신프로필은 여러 그룹에 중복으로 속할 수 있습니다.</li>
                        <li>동일 그룹에 속한 발신프로필은 발신 프로필 그룹으로 등록된 템플릿을 공유하여 사용 할 수 있습니다.</li>
                        <li>발신 프로필 그룹 신규 등록/삭제는 관리자에게 요청해주시기 바랍니다.</li>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- 발신 프로필 그룹 가이드 end-->

        <div class="row">
            <div class="col-xs-12" style="padding: 0 0 0 0 !important;">

                <!-- 그룹 리스트-->
                <div class="dual-list list-profile-group" style="width: 250px; float: left;">
                    <div class="list-header" style="text-align: center; background-color: #f5f5f5; padding: 10px; border: 1px solid #d9d9d9;
                        font-weight: bold; border-right: 0px;">
                        그룹
                    </div>
                    <div class="well" style="border-right: 0px; background-color: #fafafa;">
                        <div class="input-group">
                            <input type="text" name="SearchDualList" class="form-control" placeholder="검색"/>
                            <span class="input-group-addon glyphicon glyphicon-search"
                                  style="position: initial; cursor: pointer;"></span>
                        </div>

                        <ul class="list-group group-area" style="height: 720px; overflow: auto">
                            
                        </ul>
                    </div>
                </div>
                <!-- 그룹 리스트 end-->

                <!-- 그룹에 할당된 발신 프로필 리스트-->
                <div class="dual-list list-left" style="width: 365px; float: left;">
                    <div class="list-header"
                         style="text-align: center; background-color: #f5f5f5; padding: 10px; border: 1px solid #d9d9d9;  font-weight: bold;">
                        <span class="group-notice"></span> 발신 프로필
                    </div>
                    <div class="well" style="background-color: #fafafa;">
                        <div class="input-group">
                            <input type="text" name="SearchDualList" class="form-control" placeholder="검색"/>
                            <span class="input-group-addon glyphicon glyphicon-search"
                                  style="position: initial;"></span>
                        </div>
                        <ul class="list-group profile-area assigned-list" style="height: 720px; overflow: auto">
                        </ul>
                    </div>
                </div>
                <!-- 그룹에 할당된 발신 프로필 리스트 end-->

                <!-- dual switch button-->
                <div class="list-arrows text-center" style="width: 50px; float: left;">
                    <div>
                        <button class="btn btn-default btn-sm move-left">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div>
                        <button class="btn btn-default btn-sm move-right">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                </div>
                <!-- dual switch button end-->

                <!-- 전체 발신 프로필 리스트 (할당되지않은)-->
                <div class="dual-list list-right" style="width: 365px; float: left;">
                    <div class="list-header"
                         style="text-align: center; background-color: #f5f5f5; padding: 10px; border: 1px solid #d9d9d9; font-weight: bold;">
                        전체 발신 프로필
                    </div>
                    <div class="well" style="background-color: #fafafa;">
                        <div class="input-group">
                            <input type="text" name="SearchDualList" class="form-control" placeholder="검색"/>
                            <span class="input-group-addon glyphicon glyphicon-search"
                                  style="position: initial;"></span>
                        </div>
                        <ul class="list-group profile-area non-assigned-list" style="height: 720px; overflow: auto">
                        </ul>
                    </div>
                </div>
                <!-- 전체 발신 프로필 리스트 (할당되지않은) end-->
            </div>
        </div>
    </div>
    <div id="overlay" style="display: none;">
        <img src="/collected_statics/assets/img/loader.gif" alt="Loading"/><br/>
        Loading...
    </div>
