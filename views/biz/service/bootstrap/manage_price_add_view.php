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

    <form action="/biz/service/manage_price_add" method="post" id="mainForm">
        <input type='hidden' name='csrfmiddlewaretoken' value='UyLehHwfdMNfcnYky0BXGjkJy8NZv3m3' />
        <div class="crumbs">
            <ul id="breadcrumbs" class="breadcrumb">
                <li><i class="icon-home"></i><a href="/biz/service/manage_price">상품 관리</a></li>
                <li><a href="/biz/service/manage_price" title="">메시지 요금관리</a></li>
                <li class="current"><a href="#" title="">메시지 요금등록</a></li>
            </ul>
        </div>
        <div class="content_wrap">
            <div class="page-header clear">
                <div class="page-title">
                    <h3>메시지 요금등록</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="widget">
                        <div class="widget-content">
                            <div class="mb10 mt10 clear">
                                <select class="select2 input-width-xlarge" name="pf_ynm" id="pf_ynm"
                                        onchange="checkCompany();">
                                    <option value="default">업체명</option>
                                    
                                        <option value="admin">go(admin)</option>
                                    
                                        <option value="dhn">대형네트웍스(dhn)</option>
                                    
                                        <option value="manager">go(manager)</option>
                                    
                                        <option value="prcompany">(prcompany)</option>
                                    
                                        <option value="PREPAY_USER">스윗트래커(PREPAY_USER)</option>
                                    
                                        <option value="setest">setest(setest)</option>
                                    
                                        <option value="TGTECH">티지테크(TGTECH)</option>
                                    
                                </select>&nbsp;
                            </div>
                            <table class="table table-bordered table-highlight-head t_center cursor ver-middle msg_tpl ht_auto" style="cursor:default;">
                                <colgroup>
                                    <col width="*">
                                    <col width="185">
                                    <col width="185">
                                    <col width="185">
                                    <col width="185">
                                    <col width="185">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>구분</th>
                                    <th>1구간</th>
                                    <th>2구간</th>
                                    <th>3구간</th>
                                    <th>4구간</th>
                                    <th>5구간</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <ul class="th">
                                            <li>
                                                <div>알림톡</div>
                                            </li>
                                            <li>
                                                <div>친구톡<br>(텍스트)</div>
                                            </li>
                                            <li>
                                                <div>친구톡<br>(이미지)</div>
                                            </li>
                                            <li>
                                                <div>실패시</div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>
                                                <div>
                                                    <input type="text" id="at_unit1_1" name="at_unit1_1" maxlength='10'
                                                           value="1" style="font-weight:bold;" tabindex='1'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);">
                                                    ~
                                                    <input type="text" id="at_unit1" name="at_unit1" maxlength='10'
                                                           style="font-weight:bold;" tabindex='2'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('at_unit1_1','at_unit1', 'at_unit2_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="at_price1" tabindex='3'
                                                           name="at_price1" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_txt_unit1_1" name="ft_txt_unit1_1"
                                                           maxlength='10' value="1" style="font-weight:bold;" tabindex='16'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_txt_unit1" name="ft_txt_unit1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='17'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_txt_unit1_1','ft_txt_unit1', 'ft_txt_unit2_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_txt_price1" tabindex='18'
                                                           name="ft_txt_price1" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_img_unit1_1" name="ft_img_unit1_1"
                                                           maxlength='10' value="1" style="font-weight:bold;" tabindex='31'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_img_unit1" name="ft_img_unit1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='32'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_img_unit1_1','ft_img_unit1', 'ft_img_unit2_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_img_price1" tabindex='33'
                                                           name="ft_img_price1" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <b>SMS</b><br>
                                                    <input type="text" style="width:150px;" id="sms_price" tabindex='46'
                                                           name="sms_price" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>
                                                <div>
                                                    <input type="text" id="at_unit2_1" name="at_unit2_1" maxlength='10'
                                                           style="font-weight:bold;" tabindex='4'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);">
                                                    ~
                                                    <input type="text" id="at_unit2" name="at_unit2" maxlength='10'
                                                           style="font-weight:bold;" tabindex='5'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('at_unit2_1', 'at_unit2', 'at_unit3_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="at_price2" tabindex='6'
                                                           name="at_price2" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_txt_unit2_1" name="ft_txt_unit2_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='19'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_txt_unit2" name="ft_txt_unit2"
                                                           maxlength='10' style="font-weight:bold;" tabindex='20'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_txt_unit2_1', 'ft_txt_unit2', 'ft_txt_unit3_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_txt_price2" tabindex='21'
                                                           name="ft_txt_price2" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_img_unit2_1" name="ft_img_unit2_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='34'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_img_unit2" name="ft_img_unit2"
                                                           maxlength='10' style="font-weight:bold;" tabindex='35'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_img_unit2_1', 'ft_img_unit2', 'ft_img_unit3_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_img_price2" tabindex='36'
                                                           name="ft_img_price2" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <b>LMS</b><br>
                                                    <input type="text" style="width:150px;" id="lms_price" tabindex='47'
                                                           name="lms_price" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>
                                                <div>
                                                    <input type="text" id="at_unit3_1" name="at_unit3_1" maxlength='10'
                                                           style="font-weight:bold;" tabindex='7'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);">
                                                    ~
                                                    <input type="text" id="at_unit3" name="at_unit3" maxlength='10'
                                                           style="font-weight:bold;" tabindex='8'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('at_unit3_1', 'at_unit3', 'at_unit4_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="at_price3" tabindex='9'
                                                           name="at_price3" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_txt_unit3_1" name="ft_txt_unit3_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='22'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_txt_unit3" name="ft_txt_unit3"
                                                           maxlength='10' style="font-weight:bold;" tabindex='23'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_txt_unit3_1', 'ft_txt_unit3', 'ft_txt_unit4_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_txt_price3" tabindex='24'
                                                           name="ft_txt_price3" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_img_unit3_1" name="ft_img_unit3_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='37'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_img_unit3" name="ft_img_unit3"
                                                           maxlength='10' style="font-weight:bold;" tabindex='38'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_img_unit3_1', 'ft_img_unit3', 'ft_img_unit4_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_img_price3" tabindex='39'
                                                           name="ft_img_price3" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <b>MMS</b><br>
                                                    <input type="text" style="width:150px;" id="mms_price" tabindex='48'
                                                           name="mms_price" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>
                                                <div>
                                                    <input type="text" id="at_unit4_1" name="at_unit4_1" maxlength='10'
                                                           style="font-weight:bold;" tabindex='10'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);">
                                                    ~
                                                    <input type="text" id="at_unit4" name="at_unit4" maxlength='10'
                                                           style="font-weight:bold;" tabindex='11'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('at_unit4_1', 'at_unit4', 'at_unit5_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="at_price4" tabindex='12'
                                                           name="at_price4" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_txt_unit4_1" name="ft_txt_unit4_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='25'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_txt_unit4" name="ft_txt_unit4"
                                                           maxlength='10' style="font-weight:bold;" tabindex='26'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_txt_unit4_1', 'ft_txt_unit4', 'ft_txt_unit5_1');">
                                                    건
                                                    <input type="text" style="width:150px;" id="ft_txt_price4" tabindex='27'
                                                           name="ft_txt_price4" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_img_unit4_1" name="ft_img_unit4_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='40'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_img_unit4" name="ft_img_unit4"
                                                           maxlength='10' style="font-weight:bold;" tabindex='41'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_img_unit4_1', 'ft_img_unit4', 'ft_img_unit5_1');">
                                                    건
                                                    <input type="text" style="width:150px;" style="width:150px;"
                                                           style="font-weight:bold;" tabindex='42'
                                                           id="ft_img_price4" name="ft_img_price4" maxlength='10'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>

                                                </div>
                                            </li>
                                        </ul>

                                    </td>
                                    <td>
                                        <ul>
                                            <li>
                                                <div>
                                                    <input type="text" id="at_unit5_1" name="at_unit5_1" maxlength='10'
                                                           style="font-weight:bold;" tabindex='13'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);">
                                                    ~
                                                    <input type="text" id="at_unit5" name="at_unit5" maxlength='10'
                                                           style="font-weight:bold;" tabindex='14'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('at_unit5_1', 'at_unit5', '');">
                                                    건
                                                    <input type="text" style="width:150px;" id="at_price5" tabindex='15'
                                                           name="at_price5" maxlength='10' style="font-weight:bold;"
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);">
                                                    원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_txt_unit5_1" name="ft_txt_unit5_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='28'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_txt_unit5" name="ft_txt_unit5"
                                                           maxlength='10' style="font-weight:bold;" tabindex='29'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_txt_unit5_1', 'ft_txt_unit5', '');"> 건
                                                    <input type="text" style="width:150px;" id="ft_txt_price5"
                                                           style="font-weight:bold;"
                                                           name="ft_txt_price5" maxlength='10' tabindex='30'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <input type="text" id="ft_img_unit5_1" name="ft_img_unit5_1"
                                                           maxlength='10' style="font-weight:bold;" tabindex='43'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this);"> ~
                                                    <input type="text" id="ft_img_unit5" name="ft_img_unit5"
                                                           maxlength='10' style="font-weight:bold;" tabindex='44'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); checkAndSetNumber('ft_img_unit5_1', 'ft_img_unit5', '');"> 건
                                                    <input type="text" style="width:150px;" style="width:150px;"
                                                           style="font-weight:bold;" tabindex='45'
                                                           id="ft_img_price5" name="ft_img_price5" maxlength='10'
                                                           onchange="noSpaceForm(this); InpuOnlyNumber(this); "> 원

                                                </div>
                                            </li>
                                            <li>
                                                <div>


                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <script>
                                $(".msg_tpl ul").each(function () {
                                    $(this).find("li:last").addClass("last");
                                })
                            </script>
                            <div class="align-center mt30">
                                <a href='#' onclick="javascript:goto_price_list()" class="btn"> 취소 </a>
                                &nbsp;
                                <a href='#' onclick="javascript:register_price()" class="btn btn-custom"> 등록 </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--content_wrap-->
    </form>

    <script type="text/javascript">
        $("#nav li.nav05").addClass("current open");

        var company_check = false;

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

        // 숫자만 입력
        function InpuOnlyNumber(obj) {
            var val = obj.value;
            val = new String(val);
            //var regex = /[^0-9^+]/g;
            var regex = /^\d+(?:[.]?[\d]?[\d])?$/;
            if (!regex.test(val)) {
                val = val.replace(regex, '');
            }
            obj.value = val;
        }


        // 건수 체킹 (이전 건수 +1 부터 시작, 이전보다 큰수로 제한)
        function checkAndSetNumber(unitBefore, unitNow, unitAfter) {

            var beforeValue = document.getElementById(unitBefore).value;
            var nowValue = document.getElementById(unitNow).value;

            if (nowValue != '+' && nowValue != '') {
                
                if (Number(nowValue) < Number(beforeValue) || Number(nowValue) == Number(beforeValue)) {
                    $(".content").html(beforeValue + "보다 큰 수를 입력해주세요");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        document.getElementById(unitNow).focus();
                        document.getElementById(unitNow).value=" ";
                    });
                } else {
                    var setvalue = Number(nowValue) + 1;
                    $("#" + unitAfter).val(setvalue);
                }
            }
        }

        // 업체 중복 등록 여부 확인
        function checkCompany() {
            var csrftoken = getCookie('csrftoken');
            var pf_ynm = $('#pf_ynm').val();
            $.ajax({
                url: '/service/manage_price_check_dupicated',
                type: "POST",
                data: {
                    csrfmiddlewaretoken: csrftoken,
                    pf_ynm: pf_ynm
                },
                success: function (json) {
                    get_result(json);
                },
                error: function (data, status, er) {
                    $(".content").html("처리중 오류가 발생하였습니다. 관리자에게 문의하세요");
                    $("#myModal").modal('show');
                    click_btn_primary();
                }
            });
            function get_result(json) {
                if (json['result'] == true) {
                    company_check = true;
                }
                else {
                    $(".content").html("이미 메시지 요금이 등록된 업체입니다");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    company_check = false;
                }
            }
        }


        function register_price() {
            var form = document.getElementById('mainForm');
            var pf_ynm = $("#pf_ynm").val();
            var at_unit1 = $("#at_unit1").val();
            var at_price1 = $("#at_price1").val();
            var at_unit2 = $("#at_unit2").val();
            var at_price2 = $("#at_price2").val();
            var at_unit3 = $("#at_unit3").val();
            var at_price3 = $("#at_price3").val();
            var at_unit4 = $("#at_unit4").val();
            var at_price4 = $("#at_price4").val();
            var at_unit5 = $("#at_unit5").val();
            var at_price5 = $("#at_price5").val();
            var ft_txt_unit1 = $("#ft_txt_unit1").val();
            var ft_txt_price1 = $("#ft_txt_price1").val();
            var ft_txt_unit2 = $("#ft_txt_unit2").val();
            var ft_txt_price2 = $("#ft_txt_price2").val();
            var ft_txt_unit3 = $("#ft_txt_unit3").val();
            var ft_txt_price3 = $("#ft_txt_price3").val();
            var ft_txt_unit4 = $("#ft_txt_unit4").val();
            var ft_txt_price4 = $("#ft_txt_price4").val();
            var ft_txt_unit5 = $("#ft_txt_unit5").val();
            var ft_txt_price5 = $("#ft_txt_price5").val();
            var ft_img_unit1 = $("#ft_img_unit1").val();
            var ft_img_price1 = $("#ft_img_price1").val();
            var ft_img_unit2 = $("#ft_img_unit2").val();
            var ft_img_unit1 = $("#ft_img_unit1").val();
            var ft_img_price2 = $("#ft_img_price2").val();
            var ft_img_unit3 = $("#ft_img_unit3").val();
            var ft_img_price3 = $("#ft_img_price3").val();
            var ft_img_unit4 = $("#ft_img_unit4").val();
            var ft_img_price4 = $("#ft_img_price4").val();
            var ft_img_unit5 = $("#ft_img_unit5").val();
            var ft_img_price5 = $("#ft_img_price5").val();
            var sms_price = $("#sms_price").val();
            var lms_price = $("#lms_price").val();
            var mms_price = $("#mms_price").val();

            if (pf_ynm == 'default') {
                $(".content").html("업체명을 선택해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();
            } else if (company_check == false) {
                $(".content").html("이미 메시지 요금이 등록된 업체입니다");
                $("#myModal").modal('show');
                click_btn_primary();
            } else if (at_unit1 != '+' && at_unit2 != '+' && at_unit3 != '+' && at_unit4 != '+' && at_unit5 != '+') {
                $(".content").html("알림톡 최대 발송 수(+)를 입력해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();

            } else if (ft_txt_unit1 != '+' && ft_txt_unit2 != '+' && ft_txt_unit3 != '+' && ft_txt_unit4 != '+' && ft_txt_unit5 != '+') {
                $(".content").html("친구톡(텍스트) 최대 발송 수(+)를 입력해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();

            } else if (ft_img_unit1 != '+' && ft_img_unit2 != '+' && ft_img_unit3 != '+' && ft_img_unit4 != '+' && ft_img_unit5 != '+') {
                $(".content").html("친구톡(이미지) 최대 발송 수(+)를 입력해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();

            } else if (!sms_price) {
                $(".content").html("SMS 가격을 입력해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();

            } else if (!lms_price) {
                $(".content").html("LMS 가격을 입력해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();

            } else if (!mms_price) {
                $(".content").html("MMS 가격을 입력해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();

            } else {
                if (at_unit1 && !at_price1) {
                    $(".content").html("알림톡 1구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#at_price1').focus();
                    });
                } else if (at_unit2 && !at_price2) {
                    $(".content").html("알림톡 2구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#at_price2').focus();
                    });
                } else if (at_unit3 && !at_price3) {
                    $(".content").html("알림톡 3구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#at_price3').focus();
                    });
                } else if (at_unit4 && !at_price4) {
                    $(".content").html("알림톡 4구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#at_price4').focus();
                    });
                } else if (at_unit5 && !at_price5) {
                    $(".content").html("알림톡 5구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#at_price5').focus();
                    });
                } else if (ft_txt_unit1 && !ft_txt_price1) {
                    $(".content").html("친구톡(텍스트) 1구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_txt_price1').focus();
                    });
                } else if (ft_txt_unit2 && !ft_txt_price2) {
                    $(".content").html("친구톡(텍스트) 2구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_txt_price2').focus();
                    });
                } else if (ft_txt_unit3 && !ft_txt_price3) {
                    $(".content").html("친구톡(텍스트) 3구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_txt_price3').focus();
                    });
                } else if (ft_txt_unit4 && !ft_txt_price4) {
                    $(".content").html("친구톡(텍스트) 4구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_txt_price4').focus();
                    });
                } else if (ft_txt_unit5 && !ft_txt_price5) {
                    $(".content").html("친구톡(텍스트) 5구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_txt_price5').focus();
                    });
                } else if (ft_img_unit1 && !ft_img_price1) {
                    $(".content").html("친구톡(이미지) 1구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_img_price1').focus();
                    });
                } else if (ft_img_unit2 && !ft_img_price2) {
                    $(".content").html("친구톡(이미지) 2구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_img_price2').focus();
                    });
                } else if (ft_img_unit3 && !ft_img_price3) {
                    $(".content").html("친구톡(이미지) 3구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_img_price3').focus();
                    });
                } else if (ft_img_unit4 && !ft_img_price4) {
                    $(".content").html("친구톡(이미지) 4구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_img_price4').focus();
                    });
                } else if (ft_img_unit5 && !ft_img_price5) {
                    $(".content").html("친구톡(이미지) 5구간 가격을 입력해주세요.");
                    $("#myModal").modal('show');
                    click_btn_primary();
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#ft_img_price5').focus();
                    });
                } else {
                    var pf_ynm = $("#pf_ynm").val();
                    form.submit();
                }

            }

        }

        function goto_price_list() {
            $(".content2").html("페이지를 벗어나시겠습니까?");
            $("#myModal2").modal('show');
            click_btn_primary();

            $("#confirm_btn").click(function () {
                return window.location.href = '/service/manage_price';
            });
        }


    </script>
