    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http://www.w3.org/1999/html">
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
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content2">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" id="close_btn" class="btn btn-default" data-dismiss="modal">취소
                            </button>
                            <button type="button" id="confirm_btn" class="btn btn-primary" data-dismiss="modal">확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="#">상품 관리</a></li>
            <li class="current"><a href="#" title="">메시지 요금관리</a></li>
        </ul>
    </div>
    <form id="mainForm" name="mainForm" method="post" action="/biz/service/manage_price">
        <input type='hidden' name='csrfmiddlewaretoken' value='UyLehHwfdMNfcnYky0BXGjkJy8NZv3m3' />
        <div class="content_wrap">
            <div class="row">
                <div class="col-xs-12">
                    <div class="widget">
                        <div class="widget-content">
                            <div class="mb10 mt10 clear">
                                <input type="text" class="form-control input-width-large inline" id="searchStr"
                                       placeholder="검색어를 입력하세요"
                                       name="searchStr" value="" maxlength="10"
                                       onchange="noSpaceForm(this);">
                                <button class="btn btn-default" type="button" onclick="search_question(0)"><i
                                        class="icon-search ">조회</i>
                                </button>
                                <a class="btn btn-custom pull-right"
                                   href="/biz/service/manage_price_add_view">메시지 요금 등록</a>
                            </div>
                            <table class="table table-hover table-striped table-bordered table-highlight-head table-checkable t_center cursor ver-middle msg_tpl" style="cursor: default">
                                <colgroup>
                                    <col width="">
                                    <col width="*">
                                    <col width="100">
                                    <col width="150">
                                    <col width="150">
                                    <col width="150">
                                    <col width="150">
                                    <col width="150">
                                    <col width="80">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th class="checkbox-column" rowspan="2">
                                        <input type="checkbox" class="uniform" id="check_all" onclick="checkAll();">
                                    </th>
                                    <th rowspan="2" colspan="2">업체명</th>
                                    <th colspan="5">요금</th>
                                    <th rowspan="2">비고</th>
                                </tr>
                                <tr>
                                    <th>1구간</th>
                                    <th>2구간</th>
                                    <th>3구간</th>
                                    <th>4구간</th>
                                    <th>5구간</th>
                                </tr>
                                </thead>
                                <tbody style="cursor: pointer;">
                                
                                </tbody>
                            </table>
                            <script>
                                $(".msg_tpl ul").each(function () {
                                    $(this).find("li:last").addClass("last");
                                })
                            </script>
                            <div>
                                <button class="btn" type="button" onclick="javascript:delete_price()"><i
                                        class="icon-trash"></i>선택 삭제
                                </button>
                            </div>
                            <div class="align-center mt30">
                                <ul class="pagination pagination-sm">
                                    
                                        <li class="disabled"><a style="cursor:default">처음</a></li>
                                    

                                    
                                        <li class="disabled"><a style="cursor:default">이전</a></li>
                                    
                                    <script>
                                        var current_page = '1';
                                        
                                            var page = '1';
                                        
                                        if (page > 10) {
                                            if (current_page > 6) {
                                                var dot = Number(current_page) - 10;
                                                if (dot >= 1) {
                                                    document.writeln("<li><a href='javascript:page(" + dot + ");'>...</a></li>");
                                                } else {
                                                    document.writeln("<li><a href='javascript:page(" + 1 + ");'>...</a></li>");
                                                }
                                            }
                                        }
                                        var range = 5;
                                        if ((current_page - range) <= 1) {
                                            var start = 1;
                                            var end = 10;
                                        } else if (current_page >= (page - range)) {
                                            start = page - 10;
                                            end = page;
                                        } else {
                                            start = current_page - range;
                                            var current = Number(current_page);
                                            var ran = Number(range);
                                            end = current + ran;
                                        }
                                        for (var x = start; x < (end + 1); x++) {
                                            if ((x > 0) && (x <= page)) {
                                                if (x == current_page) {
                                                    document.writeln("<li class='active'><a style='cursor:default'><b>" + x + "</b></a></li>");
                                                } else {
                                                    document.writeln("<li><a href='javascript:page(" + x + ");'>" + x + "</a></li>");
                                                }
                                            }
                                        }
                                        if (page > 10) {
                                            if (current_page < page - 5) {
                                                var dot = Number(current_page) + 10;
                                                if (dot <= page) {
                                                    document.writeln("<li><a href='javascript:page(" + dot + ");'>...</a></li>");
                                                } else {
                                                    document.writeln("<li><a href='javascript:page(" + page + ");'>...</a></li>");
                                                }
                                            }
                                        }
                                    </script>
                                    
                                        <li class="disabled"><a style="cursor:default">다음</a></li>
                                    

                                    
                                        <li class="disabled"><a style="cursor:default">마지막</a></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--content_wrap-->
    </form>

    <style>
        th[rowspan="2"] {
            line-height: 50px !important;
        }
    </style>

    <script type="text/javascript">
        $("#nav li.nav05").addClass("current open");

        function page(page) {
            var form = document.getElementById("mainForm");

            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);

            form.appendChild(pageField);
            form.submit();
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

        // 공백 제거
        function noSpaceForm(obj) {
            var str_space = /\s/;  // 공백체크
            if (str_space.exec(obj.value)) { //공백 체크
                obj.focus();
                obj.value = obj.value.replace(/(\s*)/g, ''); // 공백제거
                return false;
            }
        }

        //전체 선택
        function checkAll() {
            if (document.getElementById("check_all").checked) {
                $("input[type = checkbox]").prop("checked", true);
            } else {
                $("input[type = checkbox]").prop("checked", false);
            }
        }

        //CSRF token획득
        function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }

        //선택된 업체 확인
        function check_price(obj) {
            var sum = 0, tag = [];
            var chk = document.getElementsByName(obj);
            var chk_id = document.getElementsByName("chk_price_id[]");
            var tot = chk.length;

            for (var i = 0; i < tot; i++) {
                if (chk[i].checked == true) {
                    chk_id[i].checked = true;
                    tag[sum] = chk[i].value;
                    sum++;
                }
            }
            return tag
        }


        //메시지 요금 삭제
        function delete_price() {
            var csrftoken = getCookie('csrftoken');
            var user_id = check_price("chk_price_id[]");
            var obj_user_id = [];

            for (var i = 0; i < check_price("chk_price_id[]").length; i++) {
                obj_user_id.push(user_id[i]);
            }
            if (check_price("chk_price_id[]").length > 0) {

                $(".content2").html(check_price("chk_price_id[]").length + "건을 삭제 하시겠습니까?");
                $("#myModal2").modal('show');
                click_btn_primary();
                $("#confirm_btn").click(function () {
                    $.ajax({
                        url: "/biz/service/manage_price_delete",
                        type: "POST",
                        data: {
                            csrfmiddlewaretoken: csrftoken,
                            user_id: JSON.stringify({user_id: obj_user_id}),
                            count: check_price("chk_price_id[]").length
                        },
                        beforeSend: function () {
                            $('#overlay').fadeIn();
                        },
                        complete: function () {
                            $('#overlay').fadeOut();
                        },
                        success: function (json) {
                            window.location.href = '/service/manage_price';
                        },
                        error: function (data, status, er) {
                            $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오.");
                            $("#myModal").modal('show');
                        }
                    });
                });


            } else {
                $(".content").html("삭제할 요금을 선택해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();
            }
        }

        //검색 조회 (업체명검색)
        function search_question(page) {
            if (page == 0) {
                page = 1;
            }

            var form = document.getElementById('mainForm');
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            form.submit();
        }


        //상세 페이지 이동
        function clickTrEvent(trObj) {
            var userid = trObj.id;
            var csrftoken = getCookie('csrftoken');

            $.ajax({
                url: '/service/manage_price_modify_view',
                async: false,
                type: "POST",
                data: {
                    csrfmiddlewaretoken: csrftoken,
                    userid: userid
                },
                success: function (data) {
                    window.location.href = '/service/manage_price_modify_view';
                },
                error: function (data, status, er) {
                    $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오.");
                    $("#myModal").modal('show');
                }
            });
        }

    </script>
