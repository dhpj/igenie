	<div class="modal fade" id="myModalImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http://www.w3.org/1999/html" style="overflow-y: hidden; width: 100%; height: 100%">
		<div class="modal-dialog modal-lg" id="modal" style="width:620px;">
			<div class="modal-content" style="width:620px !important;">
				<br/>
				<div class="modal-body">
					<div class="content"></div>
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
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left: 40%; margin-top: 15%; overflow: hidden;">
		<div class="modal-dialog modal-lg" id="modal">
			<div class="modal-content" style="width: 320px;">
				<br>
				<div class="modal-body" style="height: 120px;">
					<div class="content"></div>
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
            <li><i class="icon-home"></i><a href="/partner/list">파트너</a></li>
            <li class="current"><a href="/partner/view/aaa#" title="">파트너 상세보기</a></li>
        </ul>
    </div>
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                <form action="/partner/view/aaa#">
                    <div class="widget">
                        <div class="widget-content">
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="400">
                                    <col width="130">
                                    <col width="*">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle !important;">계정</th>
<?if($this->member->item('mem_level')>=$this->Biz_model->manager_lv) {?>
                                    <td><?=$rs->mem_userid?></td>
												<th rowspan="2" style="border-left:1px solid #dedede;vertical-align: middle !important;">사업자등록증</th>
												<td rowspan="2">
<?	if($rs->mem_photo) {?>
												<a href="#" onclick="show_sheet('/mem_sheet/<?=$rs->mem_photo?>')"><img src="/mem_sheet/<?=$rs->mem_photo?>" style="height:50px;" /></a>&nbsp;
<?	}
	//foreach($biz_sheet as $bz) {?>
<!-- 												<a href="#" onclick="show_sheet('/biz_sheet/<?=$bz["spf_biz_sheet"]?>')"><img src="/biz_sheet/<?=$bz["spf_biz_sheet"]?>" style="height:50px;" /></a>&nbsp;-->
<?	//}?>
												</td>
<?} else {?>
                                    <td colspan="3"><?=$rs->mem_userid?></td>
<?}?>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">업체명</th>
<?if($this->member->item('mem_level')>=$this->Biz_model->manager_lv) {?>
                                    <td><?=$rs->mem_username?></td>
<?} else {?>
                                    <td colspan="3"><?=$rs->mem_userid?></td>
<?}?>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">잔액</th>
                                    <td colspan="3">₩<?=number_format($rs->mem_point + $rs->total_deposit, 1)?> &nbsp; [예치금 : <?=number_format($rs->total_deposit, 1)?><!-- , 포인트 : <?=number_format($rs->mem_point, 1)?>] &nbsp; &nbsp; --> ](사용가능: <font color='red' style="letter-spacing:1px;"><strong><?php echo number_format($this->Biz_model->getAbleCoin($rs->mem_id, $rs->mem_userid), 2); ?></strong></font>)</td>
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
            <li class="active"><a href="/biz/partner/view?<?=$param['key']?>">정보</a></li>
            <li><a href="/biz/partner/partner_charge?<?=$param['key']?>">충전내역</a></li>
            <li><a href="/biz/partner/partner_sent?<?=$param['key']?>">발신목록</a></li>
            <li><a href="/biz/partner/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
<?if($this->member->item("mem_level")>=10) {?>
				<li style="float:right;">
					<input type="button" class="btn btn-default" id="check" value="<?=$rs->mem_username?> 계정으로 보기" onclick="document.location.href='/biz/main?<?=$rs->mem_id?>'"/>
				</li>
<?}?>
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
            <div class="col-xs-12">
                <form action="/partner/view/aaa#">
                    <div class="widget">
                        <div class="widget-content">
                            
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle !important;">전화번호<br>(SMS 발신번호)</th>
                                    <td><?=$rs->mem_phone?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">담당자이름</th>
                                    <td><?=$rs->mem_nickname?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">담당자 연락처</th>
                                    <td><?=$rs->mem_emp_phone?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">담당자 이메일</th>
                                    <td><?=$rs->mem_email?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">매장URL(카페,블로그)</th>
                                    <td><?=$rs->mad_free_hp?></td>
                                </tr>
                                <tr>
                                    <th>일 발신한도 지정</th>
                                    <td style="line-height:2.3;"><?=number_format($rs->mem_day_limit)?>건 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class="pt20">( ※ 0으로 지정하면 제한없음 )</span></td>
                                </tr>
                                <!-- 2019.02.18 Full Care비용 추가 -->
                                <tr>
                                    <th style="vertical-align: middle !important;">Full Care 비용</th>
                                    <td><?=number_format($rs->mad_price_full_care)?>원</td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">링크 버턴 이름</th>
                                    <td><?=$rs->mem_linkbtn_name?></td>
                                </tr>
                                <tr>
                                    <th>2차 발신</th>
                                    <td style="line-height:2.3;"><?=$this->funn->get_2send_kind($rs->mem_2nd_send)?></span></td>
                                </tr>
                                <tr>
                                    <th>충전방식</th>
                                    <td style="line-height:2.3;"><? if($rs->mem_pay_type == 'A') { echo "후불"; } else if($rs->mem_pay_type == 'T') {echo "정액제( ".$rs->mem_fixed_from_date." ~ ".$rs->mem_fixed_to_date." )"; } else { echo "선불"; } ?></span></td>
                                </tr>                                
                                <tr>
												<th style="vertical-align: middle !important;">발신 단가<br/><br/><font color="blue">※ 사용안함 : 0원 입력</font></th>
                                    <td>
													 <font color="red">※ 발신단가는 부가세 포함 가격입니다!</font><br />
													 <table class="tpl_ver_form" width="100%">
														  <colgroup>
                                                                <col width="8%">
                                                                <col width="9%">
                                                                <col width="9%">
                                                                <col width="8%">
                                                                <col width="9%">
                                                                <col width="8%">
                                                                <col width="9%">
                                                                <col width="9%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="*">
														  </colgroup>
														  <thead>
														  <tr>
																<th>알림톡</th>
																<th>친구톡<BR>(텍스트)</th>
																<th>친구톡<BR>(이미지)</th>
																<th>WEB(A)</th>
																<th>WEB(A)<BR>SMS</th>
																<th>WEB(B)</th>
																<th>WEB(B)<BR>SMS</th>
																<th>015<BR>저가문자</th>
																<th>폰문자</th>
																<th>SMS</th>
																<th>LMS</th>
																<th>MMS</th>
														  </tr>
														  </thead>
														  <tbody>
														  <tr>
																<th style="background: white"><?=number_format($rs->mad_price_at, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_ft, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_ft_img, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_grs_sms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_nas, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_nas_sms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_015, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_dooit, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_sms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_lms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_mms, 2)?></th>
														  </tr>
														  </tbody>
													 </table>
												</td>
										  </tr>
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;" >충전가중치</th>

                                    <td>
                                        
                                            <table class="tpl_hor_form" width="100%" style="display:none">




                                                <thead>
                                                <tr>
                                                    <th>금액</th>
                                                    <th>가중치(%)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[0])?></th>
                                                    <td><?=number_format($rs->mad_weight1, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[1])?></th>
                                                    <td><?=number_format($rs->mad_weight2, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[2])?></th>
                                                    <td><?=number_format($rs->mad_weight3, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[3])?></th>
                                                    <td><?=number_format($rs->mad_weight4, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[4])?></th>
                                                    <td><?=number_format($rs->mad_weight5, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[5])?></th>
                                                    <td><?=number_format($rs->mad_weight6, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[6])?></th>
                                                    <td><?=number_format($rs->mad_weight7, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[7])?></th>
                                                    <td><?=number_format($rs->mad_weight8, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[8])?></th>
                                                    <td><?=number_format($rs->mad_weight9, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[9])?></th>
                                                    <td><?=number_format($rs->mad_weight10, 2)?>%</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="mt30 align-center">
                                <a type="button" href="/biz/partner/edit?<?=$param['key']?>" class="submit btn btn-custom">수정</a>
                            </div>
                        </div>
                    </div>
                </form>
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
        $("#myModal.modal-content").css('width', '320px');
        $("#myModal.modal-body").css('height', '120px');
        $("#myModal").css('margin-left', '40%');
        $("#myModal").css('margin-top', '15%');
        $("#myModal").css('overflow-x', 'hidden');
        $("#myModal").css('overflow-y', 'hidden');

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

		function show_sheet(img) {
			$("#myModalImg").find(".content").html("<img src='"+img+"' width='570' border='0' />");
			$('#myModalImg').modal({backdrop: 'static'});
		}
    </script>