	<div class="modal fade" id="myModalImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http://www.w3.org/1999/html" style="overflow-y: auto; width: 100%; height: 100%">
		<div class="modal-dialog modal-lg" id="modal" style="width:800px;">
			<div class="modal-content" style="width:100% !important;">
				<div class="modal-body modal_bg">
					<div class="modal_top">
						<i class="material-icons fr" data-dismiss="modal">clear</i>
						<h3>이미지 상세보기</h3>
						</div>
					<div class="modal_contents">
						<div class="content"></div>
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

<!-- 타이틀 영역 -->
<div class="tit_wrap">
	파트너
</div>
<!-- 타이틀 영역 END -->
    <div class="account_tab">
        <ul>
            <li class="selected"><a href="/biz/partner2/view?<?=$param['key']?>">정보</a></li>
            <li><a href="/biz/partner2/partner_charge?<?=$param['key']?>">충전내역</a></li>
            <li><a href="/biz/partner2/partner_sent?<?=$param['key']?>">발신목록</a></li>
            <li><a href="/biz/partner2/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
        </ul>
    </div>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<?if($this->member->item("mem_level")>=10) {?>
			<input type="button" class="btn intitle yellow fr" id="check" value="<?=$rs->mem_username?> 계정으로 전환" onclick="document.location.href='/biz/main?<?=$rs->mem_id?>'"/>
			<?}?>
			<h3>사업자 정보</h3>
						
		</div>
		<div class="inner_content">
			<?	if($rs->mem_photo) {?>
			<div class="info_img">
				<img src="/mem_sheet/<?=$rs->mem_photo?>" onclick="show_sheet('/mem_sheet/<?=$rs->mem_photo?>')" width="100%">
			</div>
			<?}?>
			<ul class="info_contents_wrap">
				<li>
					<label class="info_contents_label">상호</label>
					<div class="info_contents">(주)대형네트웍스</div>
				</li>
				<li>
					<label class="info_contents_label">사업자 등록번호</label>
					<div class="info_contents">123-56-12345</div>
				</li>
				<li>
					<label class="info_contents_label">사업장소재지</label>
					<div class="info_contents"> 경상남도 창원시 의창구 차룡로 48번길 54(팔용동) 경남창원산학융합원 기업연구관 302호</div>
				</li>
				<li>
					<label class="info_contents_label">사업자 구분</label>
					<div class="info_contents">개인사업자/법인사업자</div>
				</li>
				<li>
					<label class="info_contents_label">업태</label>
					<div class="info_contents">정보서비스</div>
				</li>
				<li>
					<label class="info_contents_label">종목</label>
					<div class="info_contents">소프트웨어자문</div>
				</li>
				<li>
					<label class="info_contents_label">대표자 이름</label>
					<div class="info_contents">송종근</div>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="form_section">
		<div class="inner_tit">
			<h3>파트너 상세 정보</h3>
						
		</div>
		<div class="inner_content">
			<ul class="info_contents_wrap">
				<li>
					<label class="info_contents_label">계정</label>
					<div class="info_contents"><?=$rs->mem_userid?></div>
				</li>
				
				<?if($this->member->item('mem_level')>=$this->Biz_model->manager_lv) {?>
				<li>
					<label class="info_contents_label">업체명</label>
					<div class="info_contents"><?=$rs->mem_username?></div>
				</li>
				<?} else {?>
				<li>
					<label class="info_contents_label">업체명</label>
					<div class="info_contents"><?=$rs->mem_userid?></div>
				</li>
				<?}?>
				
				<? if($rs->mem_pay_type == 'A') { ?>
				<li>
					<label class="info_contents_label">잔액</label>
					<div class="info_contents">후불제</div>
				</li>
				<? } else { ?>
				<li>
					<label class="info_contents_label">잔액</label>
					<div class="info_contents">₩<?=number_format($rs->mem_point + $rs->total_deposit)?> &nbsp; [예치금 : <?=number_format($rs->total_deposit)?><!-- , 포인트 : <?=number_format($rs->mem_point)?>] &nbsp; &nbsp; --> ](사용가능: <font color='red' style="letter-spacing:1px;"><strong><?php echo number_format($this->Biz_model->getAbleCoin($rs->mem_id, $rs->mem_userid)); ?></strong></font>)</div>
				</li>
				<? } ?>
				
				<li>
					<label class="info_contents_label">전화번호(SMS 발신번호)</label>
					<div class="info_contents"><?=$rs->mem_phone?></div>
				</li>
				
				<li>
					<label class="info_contents_label">담당자이름</label>
					<div class="info_contents"><?=$rs->mem_nickname?></div>
				</li>
				
				<li>
					<label class="info_contents_label">담당자 연락처</label>
					<div class="info_contents"><?=$rs->mem_emp_phone?></div>
				</li>
				
				<li>
					<label class="info_contents_label">담당자 이메일</label>
					<div class="info_contents"><?=$rs->mem_email?></div>
				</li>
				
				<li>
					<label class="info_contents_label">채널 매니저 계정</label>
					<div class="info_contents"><?=$rs->mem_pf_manager?></div>
				</li>
				
				<li>
					<label class="info_contents_label">송금인 이름</label>
					<div class="info_contents"><?=$rs->mem_bank_user?></div>
				</li>
				<li>
					<label class="info_contents_label">매장URL(카페,블로그)</label>
					<div class="info_contents"><?=$rs->mad_free_hp?></div>
				</li>
				<li>
					<label class="info_contents_label">일 발신한도 지정</label>
					<div class="info_contents"><?=number_format($rs->mem_day_limit)?>건<span class="pt20">( ※ 0으로 지정하면 제한없음 )</span></div>
				</li>
				<li>
					<label class="info_contents_label">Full Care 비용</label>
					<div class="info_contents"><?=number_format($rs->mad_price_full_care)?>원</div>
				</li>
				<li>
					<label class="info_contents_label">링크 버튼 이름</label>
					<div class="info_contents"><?=$rs->mem_linkbtn_name?></div>
				</li>
				<li>
					<label class="info_contents_label">2차 발신</label>
					<div class="info_contents"><?=$this->funn->get_2send_kind($rs->mem_2nd_send)?></div>
				</li>
				<li>
					<label class="info_contents_label">충전방식</label>
					<div class="info_contents"><? if($rs->mem_pay_type == 'A') { echo "후불"; } else if($rs->mem_pay_type == 'T') {echo "정액제( ".$rs->mem_fixed_from_date." ~ ".$rs->mem_fixed_to_date." )"; } else { echo "선불"; } ?></div>
				</li>
				<li>
					<label class="info_contents_label">발신 단가</label>
					<div class="info_contents">
						<p class="fr">※ 부가세 포함 가격</p>
						<p>※ 사용안함 : 0원 입력</p>
						<div class="table_list">
						<table>
							<colgroup>
							    <col width="100px">
							    <col width="100px">
							    <col width="100px">
							    <col width="100px">
							<? if($this->member->item('mem_level')>=100) { ?>
							<? if($rs->mem_level >= 50) {  ?>	
							    <col width="100px">
							    <col width="100px">
							<? } else { ?>
							    <col width="100px">
							<? } } else {?>
								<col width="100px">
							<? } ?> 
								<col width="100px">     
							    <col width="100px">
							    <col width="100px">
							    <col width="100px">
							    <col width="100px">
							   <!--  <col width="8%">
							    <col width="8%"> -->
							    <col width="100px">
							    <col width="100px">
							    <col width="100px">
							</colgroup>
							<thead>
							<tr>
								<th>알림톡</th>
								<th>친구톡<br>(텍스트)</th>
								<th>친구톡<br>(이미지)</th>
								<th>친구톡<br>(와이드이미지)</th>
							<? if($this->member->item('mem_level')>=100) { ?>
							<? if($rs->mem_level >= 50) {  ?>		
								<th>WEB(A)<br>KT, LGT</th>
								<th>WEB(A)<br>SKT</th>
							<? } else { ?>
								<th>WEB(A)</th>
							<? } } else {?>
								<th>WEB(A)</th>
							<? } ?>																
								<th>WEB(A)<br>SMS</th>
								<th>WEB(A)<br>MMS</th>
								<th>WEB(B)</th>
								<th>WEB(B)<br>SMS</th>
								<th>WEB(B)<br>MMS</th>
								<!-- <th>SMS</th>
								<th>LMS</th> -->
								<th>WEB(C)</th>
								<th>WEB(C)<br>SMS</th>
								<th>WEB(C)<br>MMS</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<th style="background: white"><?=number_format($rs->mad_price_at, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_ft, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_ft_img, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_ft_w_img, 2)?></th>
								<? if($this->member->item('mem_level')>=100) { ?>
								<? if($rs->mem_level >= 50) { ?>
								<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_grs_biz, 2)?></th>
								<? } else { ?>
								<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>																
								<? } } else {?>
								<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>
								<? } ?>
								<th style="background: white"><?=number_format($rs->mad_price_grs_sms, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_grs_mms, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_nas, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_nas_sms, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_nas_mms, 2)?></th>
								<!-- <th style="background: white"><?=number_format($rs->mad_price_015, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_dooit, 2)?></th> -->
								<!-- <th style="background: white"><?=number_format($rs->mad_price_sms, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_lms, 2)?></th> -->
								<th style="background: white"><?=number_format($rs->mad_price_smt, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_smt_sms, 2)?></th>
								<th style="background: white"><?=number_format($rs->mad_price_smt_mms, 2)?></th>
							</tr>
							</tbody>
						</table>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="tc">
	 <a type="button" href="/biz/partner2/edit?<?=$param['key']?>" class="btn md yellow">수정</a>
	</div>
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