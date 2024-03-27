    <?function kind($code) {
		if($code=="at") return "알림톡(텍스트)";
		else if($code=="ft") return "친구톡(텍스트)";
		else if($code=="ftimg") return "친구톡(이미지)";
		else if($code=="sms") return "SMS";
		else if($code=="lms") return "LMS";
		else if($code=="phn") return "폰문자";
		else return "MMS";
	 }?>
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
    <div class="tit_wrap">
       파트너 관리
     </div>
    <div class="content_wrap">
      <div id="mArticle">
			<div class="form_section">
      <div class="inner_tit">
				<h3>파트너 상세보기</h3>
			</div>
        <div class="white_box">
                <form action="#">
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
                </form>
        </div>

    <div class="snb_nav">
        <ul class="clear">
            <li><a href="/biz/partner/view?<?=$param['key']?>">정보</a></li>
            <li><a href="/biz/partner/partner_charge?<?=$param['key']?>">충전내역</a></li>
            <li class="active"><a href="/biz/partner/partner_sent?<?=$param['key']?>">발신목록</a></li>
            <li><a href="/biz/partner/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
        </ul>
    </div>

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
                            <button type="button" class="btn btn-custom" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="white_box">

                        <form id="mainForm" name="mainForm" method="post" action="/biz/partner/partner_sent?<?=$param['key']?>">
                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                            <div class="mg_b20">
                                <div class="flLeft">
                                    <a type="button" onclick="javascript:submit_handler_set('today')" class="submit btn btn-default btn-sm">오늘</a>
                                    <a type="button" onclick="javascript:submit_handler_set('week')" class="submit btn btn-default btn-sm">1주일</a>
                                    <a type="button" onclick="javascript:submit_handler_set('1month')" class="submit btn btn-default btn-sm">1개월</a>
                                    <a type="button" onclick="javascript:submit_handler_set('3month')" class="submit btn btn-default btn-sm">3개월</a>
                                    <a type="button" onclick="javascript:submit_handler_set('6month')" class="submit btn btn-default btn-sm">6개월</a>
                                    <input type="hidden" id="set_date" name="set_date">
												&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control input-width-small inline datepicker"
                                           name="startDate" id="startDate" value="<?=$param['startDate']?>" readonly="readonly"
                                           style="cursor: pointer;background-color: white"> ~
                                    <input type="text" class="form-control input-width-small inline datepicker"
                                           name="endDate" id="endDate" value="<?=$param['endDate']?>" readonly="readonly"
                                           style="cursor: pointer;background-color: white">
                                    <button class="btn btn-default" id="search" type="button"
                                            onclick="javascript:submit_handler()">조회
                                    </button>
                                </div>
                                <div class="fr">전체 : <?=number_format($total_rows)?>건</div>
                            </div>
                            <table class="table table-hover table-striped table-bordered table-highlight-head t_center">
                                <colgroup>
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="*">
                                    <col width="25%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>사용날짜</th>
                                    <th>템플릿코드</th>
                                    <th>사용내역</th>
                                    <th>개수</th>
                                   <!--  <th>가격</th> -->
                                </tr>
                                </thead>
                                <tbody>
											<?foreach($list as $row) {
											    $send_t = $row->mst_ft + $row->mst_ft_img+ $row->mst_at+ $row->mst_sms+$row->mst_lms+$row->mst_mms+ $row->mst_phn+ $row->mst_015+ $row->mst_grs+ $row->mst_nas;
											    $err_t = $row->mst_err_ft + $row->mst_err_ft_img+ $row->mst_err_at+ $row->mst_err_sms+$row->mst_err_lms+$row->mst_err_mms+ $row->mst_err_phn+ $row->mst_err_015+ $row->mst_err_grs+ $row->mst_err_nas;
											    ?>
                                        <tr>
                                            <td><?=($row->mst_reserved_dt == '00000000000000') ? $row->mst_datetime : $row->mst_reserved_date; ?></td>
                                            <td><?=$row->mst_template?></td>
                                            <td><?
                                                  echo "총 발송 건수<br>";
                                                  if($row->mst_ft > 0 || $row->mst_err_ft > 0)
                                                      echo "친구톡<br>";
                                                  if($row->mst_ft_img > 0 || $row->mst_err_ft_img > 0 )
                                                      echo "친구톡 이미지<br>";
                                                  if($row->mst_at > 0 || $row->mst_err_at > 0)
                                                      echo "알림톡<br>";
                                                  if($row->mst_grs > 0 || $row->mst_err_grs > 0)
                                                      echo "웹(A)<br>";
                                                  if($row->mst_nas > 0 || $row->mst_err_nas > 0)
                                                      echo "웹(B)<br>";
                                                  if($row->mst_grs_sms > 0 || $row->mst_err_grs_sms > 0)
                                                      echo "웹(A) SMS<br>";
                                                  if($row->mst_nas_sms > 0 || $row->mst_err_nas_sms > 0)
                                                      echo "웹(B) SMS<br>";
                                                  if($row->mst_015 >  0 || $row->mst_err_015 > 0)
                                                      echo "015저가문자<br>";
                                                  if($row->mst_phn > 0  || $row->mst_err_phn > 0)
                                                      echo "폰문자<br>";
                                                ?></td>
                                            <td><?
                                            echo number_format($row->mst_qty)."건 <br>";
                                            if($row->mst_ft > 0)
                                                echo "성공 : ". number_format($row->mst_ft)." / 실패 : ". number_format($row->mst_err_ft)."<br>";
                                            if($row->mst_ft_img > 0 || $row->mst_err_ft_img > 0 )
                                                echo "성공 : ". number_format($row->mst_ft_img)." / 실패 : ". number_format($row->mst_err_ft_img)."<br>";
                                            if($row->mst_at > 0 || $row->mst_err_at > 0)
                                                echo "성공 : ". number_format($row->mst_at)." / 실패 : ". number_format($row->mst_err_at)."<br>";
                                            if($row->mst_grs > 0 || $row->mst_err_grs > 0)
                                                echo "성공 : ". number_format($row->mst_grs)." / 실패 : ". number_format($row->mst_err_grs)."<br>";
                                            if($row->mst_nas > 0 || $row->mst_err_nas > 0)
                                                echo "성공 : ". number_format($row->mst_nas)." / 실패 : ". number_format($row->mst_err_nas)."<br>";
                                            if($row->mst_grs_sms > 0 || $row->mst_err_grs_sms > 0)
                                                echo "성공 : ". number_format($row->mst_grs_sms)." / 실패 : ". number_format($row->mst_err_grs_sms)."<br>";
                                            if($row->mst_nas_sms > 0 || $row->mst_err_nas_sms > 0)
                                                echo "성공 : ". number_format($row->mst_nas_sms)." / 실패 : ". number_format($row->mst_err_nas_sms)."<br>";
                                            if($row->mst_015 >  0 || $row->mst_err_015 > 0)
                                                echo "성공 : ". number_format($row->mst_015)." / 실패 : ". number_format($row->mst_err_015)."<br>";
                                            if($row->mst_phn > 0  || $row->mst_err_phn > 0)
                                                echo "성공 : ". number_format($row->mst_phn)." / 실패 : ". number_format($row->mst_err_phn)."<br>";
                                            ?>
                                         <!--    <td>₩<?=number_format($row->mst_amount)?></td> -->
                                        </tr>
											<?}?>
                                </tbody>
                            </table>
                            <!--<div class="align-left">
                                <button class="btn" type="button" onclick="download_use_history()"><i class="icon-arrow-down"></i>엑셀 다운로드</button>
                            </div>-->
                            <div class="page_cen"><?echo $page_html?></div>
                        </form>

                    <div class="btn_al_cen mg_b20">
                        <a type="button" href="/biz/partner/edit?<?=$param['key']?>" class="btn_st3">수정하기</a>
                    </div>

        </div>
       </div>
      </div>
    </div><!--//.content_wrap-->
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
                $('input[name="set_date"]').attr('value',false);
            }
            form.submit();
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
            }
            ;
        };

        //CSV 파일 다운로드
        function download_use_history() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            var list = '';

                list = '2017-11-13 09:08:42';

                list = '2017-11-13 09:08:42';

                list = '2017-11-11 09:18:14';

                list = '2017-11-11 09:18:14';

                list = '2017-11-10 09:10:09';

                list = '2017-11-10 09:10:09';

                list = '2017-11-09 09:25:40';

                list = '2017-11-09 09:25:40';

                list = '2017-11-08 09:33:52';

                list = '2017-11-08 09:33:52';

                list = '2017-11-07 09:10:07';

                list = '2017-11-07 09:10:07';

                list = '2017-11-06 09:15:55';

                list = '2017-11-06 09:15:55';

                list = '2017-11-04 09:19:05';

            if(list==''){
                $(".content").html("발신 내역이 없습니다.");
                $("#myModal").modal('show');

                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".btn-primary").click();
                    }
                });

            } else {

                var val=[];


                    var REG_DT = '2017-11-13 09:08:42';
                    var TMPL_ID ='';
                    var QTY = '265';
                    var COST = '3,975';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-13 09:08:42';
                    var TMPL_ID ='';
                    var QTY = '1594';
                    var COST = '46,226';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-11 09:18:14';
                    var TMPL_ID ='';
                    var QTY = '265';
                    var COST = '3,975';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-11 09:18:14';
                    var TMPL_ID ='';
                    var QTY = '1572';
                    var COST = '45,588';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-10 09:10:09';
                    var TMPL_ID ='';
                    var QTY = '209';
                    var COST = '3,135';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-10 09:10:09';
                    var TMPL_ID ='';
                    var QTY = '1613';
                    var COST = '46,777';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-09 09:25:40';
                    var TMPL_ID ='';
                    var QTY = '264';
                    var COST = '3,960';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-09 09:25:40';
                    var TMPL_ID ='';
                    var QTY = '1545';
                    var COST = '44,805';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-08 09:33:52';
                    var TMPL_ID ='';
                    var QTY = '265';
                    var COST = '3,975';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-08 09:33:52';
                    var TMPL_ID ='';
                    var QTY = '1534';
                    var COST = '44,486';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-07 09:10:07';
                    var TMPL_ID ='';
                    var QTY = '266';
                    var COST = '3,990';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-07 09:10:07';
                    var TMPL_ID ='';
                    var QTY = '1520';
                    var COST = '44,080';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-06 09:15:55';
                    var TMPL_ID ='';
                    var QTY = '267';
                    var COST = '4,005';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-06 09:15:55';
                    var TMPL_ID ='';
                    var QTY = '1517';
                    var COST = '43,993';
                    var SENT_TYPE = 'L';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);

                    var REG_DT = '2017-11-04 09:19:05';
                    var TMPL_ID ='';
                    var QTY = '266';
                    var COST = '3,990';
                    var SENT_TYPE = 'FT';

                    char = {
                        "REG_DT": REG_DT,
                        "TMPL_ID": TMPL_ID,
                        "QTY": QTY,
                        "COST": COST,
                        "SENT_TYPE": SENT_TYPE
                    };
                    val.push(char);


                var value = JSON.stringify(val);
                var form = document.createElement("form");
                document.body.appendChild(form);

                form.setAttribute("method", "post");
                form.setAttribute("action", "/biz/partner/partner_download_sent/a01074993322");
                var valueField = document.createElement("input");
                valueField.setAttribute("type", "hidden");
                valueField.setAttribute("name", "value");
                valueField.setAttribute("value", value);
                form.appendChild(valueField);
                form.submit();
            }
        }

    </script>
