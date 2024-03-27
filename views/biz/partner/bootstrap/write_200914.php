    <style>
        .errorlist {
            display: block; color: #f00; font-weight: bold;
        }
		  .upload-hidden { display:none !important; }
    </style>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function sample6_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                if(data.userSelectedType === 'R'){
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraAddr !== ''){
                        extraAddr = ' (' + extraAddr + ')';
                    }
                    // 조합된 참고항목을 해당 필드에 넣는다.
                    document.getElementById("sample6_extraAddress").value = extraAddr;

                } else {
                    document.getElementById("sample6_extraAddress").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('sample6_postcode').value = data.zonecode;
                document.getElementById("sample6_address").value = addr;
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("sample6_detailAddress").focus();
            }
        }).open();
    }
</script>
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
<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					파트너 관리
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>매장정보</h3>
              <span class="fr fc_red mg_t20">* 사업자등록증은 필수 입력항목입니다.</span>
						</div>
						<div class="white_box">
							<div class="info_img">
								<!--
								<input type="hidden" id="old_photo" name="old_photo" value="<?=$rs->mem_photo?>" />
								<input type="text" id="pf_name" class="textbox form-control input-width-large" readonly="readonly" placeholder="파일이 선택되지 않았습니다."/>
								<label for="biz_license" class="btn btn-default find">파일찾기</label>
								<input type="file" id="biz_license" name="biz_license" class="upload-hidden" accept="image/jpeg, image/png" onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('pf_name').value=b;if($('#biz_license').val()!=''){ $('.danger').remove(); } CheckUploadFileSize(this)">
								-->
								<div class="poster">
									<input type="hidden" id="old_photo" name="old_photo" value="<?=$rs->mem_photo?>" />
								<div class="img_upload_info">
									<i class="far fa-file-image"></i>
									<h4>사업자등록증 이미지 업로드</h4>
									<p>파일 형식은 jpg 또는 png형식의 이미지만 업로드 가능합니다.</p>
								</div>
								<input type="file" id="biz_license" name="biz_license" accept="image/jpeg, image/png">
								</div>
							</div>
							<ul class="info_contents_wrap" style="overflow: hidden;">
								<li>
									<label class="info_contents_label icon_must">상호</label>
									<div class="info_contents"><input type="text" style="width: 300px"></div>
								</li>
								<li>
									<label class="info_contents_label icon_must">대표자 이름</label>
									<div class="info_contents"><input type="text" style="width: 300px"></div>
								</li>
								<li>
									<label class="info_contents_label icon_must">사업자 등록번호</label>
									<div class="info_contents"><input type="text" maxlength="3" style="width: 60px"> - <input type="text" maxlength="2" style="width: 40px"> - <input type="text" maxlength="5" style="width: 100px"></div>
								</li>
								<li>
									<label class="info_contents_label icon_must">사업장소재지</label>
									<div class="info_contents">
<input type="text" id="sample6_postcode" placeholder="우편번호" class="d_form mini">
<input type="button" onclick="sample6_execDaumPostcode()" value="우편번호 찾기" class="d_btn"><br>
<input type="text" id="sample6_address" placeholder="주소" class="d_form large"><br>
<input type="text" id="sample6_detailAddress" placeholder="상세주소" class="d_form">
<input type="text" id="sample6_extraAddress" placeholder="참고항목" class="d_form mini">
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">사업자 구분</label>
									<div class="info_contents">
										<select>
											<option>선택하세요</option>
											<option>개인사업자</option>
											<option>법인사업자</option>
										</select>
									</div>
								</li>
								<li>
									<label class="info_contents_label">업태</label>
									<div class="info_contents"><input type="text" style="width: 300px"></div>
								</li>
								<li>
									<label class="info_contents_label">종목</label>
									<div class="info_contents"><input type="text" style="width: 300px"></div>
								</li>
                <li>
									<label class="info_contents_label">SMS 발신 전화번호</label>
									<div class="info_contents">
										<input class="input-width-large required" id="id_phone" maxlength="50" name="phone" placeholder="전화번호" value="<?=($param['key']) ? $rs->mem_phone : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">담당자 이름</label>
									<div class="info_contents">
										<input class="input-width-large required" id="id_username" maxlength="50" name="username" placeholder="담당자이름" value="<?=($param['key']) ? $rs->mem_nickname : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">담당자 연락처</label>
									<div class="info_contents">
										<input class="input-width-large required" id="id_emp_phn" maxlength="25" name="emp_phn" placeholder="- 없이 입력해주세요" value="<?=($param['key']) ? $rs->mem_emp_phone : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">담당자 이메일</label>
									<div class="info_contents">
										<input class="input-width-large required" id="id_email" maxlength="254" style="width:500px !important;" name="email" placeholder="이메일" value="<?=($param['key']) ? $rs->mem_email : ''?>" type="email" />
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="form_section">
						<form id="save" method="POST" onsubmit="return check_form()" EncType="Multipart/Form-Data">
                        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                        <input type='hidden' name='actions' value='partner_save' />
						<div class="inner_tit mg_t20">
							<!--<?if($this->member->item('mem_level')>=100) {?><div class="form_check fr"><input type="checkbox" name="useyn" id="useyn" <?=($rs->mem_useyn=="N") ? "checked" : ""?> value="N" /><label for="useyn">계정삭제</label></div><?}?>-->
							<h3>계정정보</h3>
              <span class="fr fc_red mg_t20">* 필수 입력항목입니다.</span>
						</div>
						<div class="white_box">
							<ul class="info_contents_wrap">
								<li>
									<label class="info_contents_label icon_must">계정<span class="essential">*</span></label>
									<div class="info_contents">
									<?php if($param['key']) {?>
									<input class="required" id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" value="<?=$rs->mem_userid?>" readonly />
									<?php } else {?>
									<input id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" />
                                    <button id="check_double_id" class="btn h34" onclick="check_duplicated_account('id_userid')">중복 확인</button>
									<?php }?>
                                        <p id="mb_id_result_double" style="display:none; color:#942a25;" > 계정 중복확인을 해주세요.</p>
                                        <p id="mb_id_result" style="display:none; color:#942a25;" > 계정을 입력해주세요.</p>
                                        <p id="id_result_false" style="display:none; color:#942a25;" > 이미 사용중인 계정 입니다.</p>
                                        <p id="id_result_true" style="display:none; color: #2a6496;" > 사용 가능한 계정 입니다.</p>
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must" for="id_userid">소속<span class="essential">*</span></label>
									<div class="info_contents">
										<?if($this->member->item('mem_level')>=10) {?>
													<select <?//class="selectpicker "?> data-live-search="true" id="recommend_mem_id" name="recommend_mem_id" onchange="methodChange()">
														<option value="<?=($member->mrg_recommend_mem_id) ? $member->mrg_recommend_mem_id : '3'?>">없음</option>
														<?foreach($offer as $o) {?>
					                            <option value="<?=$o->mem_id?>" <?=($o->mem_id==$rs->mrg_recommend_mem_id) ? "selected" : ($o->mem_id==$this->member->item('mem_id'))?"selected":""?>><?=$o->mem_username?></option>
														<?}?>
						                      </select>
												<?} else if($param['key']) {
														foreach($offer as $o) { if($o->mem_id==$rs->mrg_recommend_mem_id) { echo $o->mem_username; } }
												} else {?>
													<select class="select2 input-width-large" id="recommend_mem_id" name="recommend_mem_id">
														<?foreach($offer as $o) {
															if($o->mem_id==$this->member->item('mem_id')) {?>
					                            <option value="<?=$o->mem_id?>" <?=($o->mem_id==$rs->mrg_recommend_mem_id) ? "selected" : ""?>><?=$o->mem_username?></option>
														<?	break; }
														}?>
						                      </select>
										<?}?>
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">비밀번호<span class="essential">*</span></label>
									<div class="info_contents">
										<input class="input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password" maxlength="128" name="password" placeholder="비밀번호" type="password" />
                                    <text id="id_password_result"
                                          style="display:none; color:#942a25; font-weight: bold; "> 새로운 비밀번호를 입력해주세요.
                                    </text>
                                    <text id="id_password_rule"
                                          style="display:none; color:#942a25; font-weight: bold; "></text>

                                        <span class="help-block">새로운 비밀번호를 입력해주세요.</span>
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">비밀번호 확인<span class="essential">*</span></label>
									<div class="info_contents">
										<input class=" input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password2" name="password2" type="password" />
                                    <text id="id_password2_rule"
                                          style="display:none; color:#942a25; font-weight: bold; "></text>
                                    <text id="id_password2_result"
                                          style="display:none; color:#942a25; font-weight: bold; "> 비밀번호를 다시 한번 입력해주세요.
                                    </text>
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">업체명<span class="essential">*</span></label>
									<div class="info_contents">
										<input class=" input-width-large required" id="id_company_name" maxlength="50" name="company_name" placeholder="업체명" value="<?=($param['key']) ? $rs->mem_username : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">등급<span class="essential">*</span></label>
									<div class="info_contents">
										<select style="min-width: 140px;" id="mem_level" name="mem_level">
													<option value="1" <?=($rs->mem_level<10) ? "selected" : ""?>>마트</option>
<?if($this->member->item('mem_level')>=30) {?><option value="10" <?=($rs->mem_level>=10 && $rs->mem_level<30) ? "selected" : ""?>>하위관리자</option><?}?>
<?if($this->member->item('mem_level')>=50) {?><option value="30" <?=($rs->mem_level>=30 && $rs->mem_level<50) ? "selected" : ""?>>중간관리자</option><?}?>
<?if($this->member->item('mem_level')>=100) {?><option value="50" <?=($rs->mem_level>=50 && $rs->mem_level<100) ? "selected" : ""?>>상위관리자</option><?}?>
<?if($this->member->item('mem_level')>=100) {?><option value="100" <?=($rs->mem_level>=100) ? "selected" : ""?>>관리자</option><?}?>
												 </select>
									</div>
								</li>
								<!--<li>
									<label class="info_contents_label icon_must">계좌정보</label>
									<div class="info_contents">
										<select name="account_bank_id" style="min-width: 140px;">
       <option value='선택'>은행을 선택하세요</option>
       <option value='기업은행'>기업은행</option>
       <option value='SC제일은행'>SC제일은행</option>
       <option value='경남은행'>경남은행</option>
       <option value='광주은행'>광주은행</option>
       <option value='국민은행'>국민은행</option>
       <option value='굿모닝신한증권'>굿모닝신한증권</option>
       <option value='농협중앙회'>농협중앙회</option>
       <option value='농협회원조합'>농협회원조합</option>
       <option value='대구은행'>대구은행</option>
       <option value='대신증권'>대신증권</option>
       <option value='대우증권'>대우증권</option>
       <option value='동부증권'>동부증권</option>
       <option value='동양종합금융증권'>동양종합금융증권</option>
       <option value='메리츠증권'>메리츠증권</option>
       <option value='미래에셋증권'>미래에셋증권</option>
       <option value='부국증권'>부국증권</option>
       <option value='부산은행'>부산은행</option>
       <option value='산림조합중앙회'>산림조합중앙회</option>
       <option value='산업은행'>산업은행</option>
       <option value='삼성증권'>삼성증권</option>
       <option value='상호신용금고'>상호신용금고</option>
       <option value='새마을금고'>새마을금고</option>
       <option value='수출입은행'>수출입은행</option>
       <option value='수협중앙회'>수협중앙회</option>
       <option value='신영증권'>신영증권</option>
       <option value='신한은행'>신한은행</option>
       <option value='신협중앙회'>신협중앙회</option>
       <option value='에스케이증권'>에스케이증권</option>
       <option value='에이치엠씨투자증권'>에이치엠씨투자증권</option>
       <option value='엔에이치투자증권'>엔에이치투자증권</option>
       <option value='엘아이지투자증권'>엘아이지투자증권</option>
       <option value='외환은행'>외환은행</option>
       <option value='우리은행'>우리은행</option>
       <option value='우리투자증권'>우리투자증권</option>
       <option value='우체국'>우체국</option>
       <option value='유진투자증권'>유진투자증권</option>
       <option value='전북은행'>전북은행</option>
       <option value='제주은행'>제주은행</option>
       <option value='키움증권'>키움증권</option>
       <option value='하나대투증권'>하나대투증권</option>
       <option value='하나은행'>하나은행</option>
       <option value='하이투자증권'>하이투자증권</option>
       <option value='한국씨티은행'>한국씨티은행</option>
       <option value='한국투자증권'>한국투자증권</option>
       <option value='한화증권'>한화증권</option>
       <option value='현대증권'>현대증권</option>
       <option value='케이뱅크'>케이뱅크</option>
       <option value='카카오뱅크'>카카오뱅크</option>
										</select>
										<input class=" input-width-large " id="id_mem_payment_desc" maxlength=100 name="mem_payment_desc" placeholder="계좌번호" value="<?=$rs->mem_payment_desc?>" type="text" />
									</div>
								</li>-->

								<!--<li>
									<label class="info_contents_label icon_must">결재방법</label>
									<div class="info_contents form_check">
										<input id="id_mem_payment_bank" name="mem_payment_bank" type="checkbox" <?=($rs->mem_payment_bank)?"checked":""?> /><label for="id_mem_payment_bank">계좌이체</label>
   										<input id="id_mem_payment_card" name="mem_payment_card" type="checkbox" <?=($rs->mem_payment_card)?"checked":""?> /><label for="id_mem_payment_card">신용카드</label>
   										<input id="id_mem_payment_realtime" name="mem_payment_realtime" type="checkbox" style="display: none" <?=($rs->mem_payment_realtime)?"checked":""?> />
   										<input id="id_mem_payment_vbank" name="mem_payment_vbank" type="checkbox" <?=($rs->mem_payment_vbank)?"checked":""?> /><label for="id_mem_payment_vbank">무통장입금</label>
   										<input id="id_mem_payment_phone" name="mem_payment_phone" type="checkbox" style="display: none" <?=($rs->mem_payment_phone)?"checked":""?> />
									</div>
								</li>-->
								<li>
									<label class="info_contents_label icon_must">충전방식<span class="essential">*</span></label>
									<div class="info_contents checks">
										<input type="radio" id="charging_type-1" name="mem_pay_type" value="B" <?=($rs->mem_pay_type=="B") ? "checked" : ""?> /><label for="charging_type-1">선불</label>
										<!--<input type="radio" id="charging_type-2" name="mem_pay_type" value="A" <?=($rs->mem_pay_type=="A") ? "checked" : ""?> /><label for="charging_type-2">후불</label>-->
										<input type="radio" id="charging_type-3" name="mem_pay_type" value="T" <?=($rs->mem_pay_type=="T") ? "checked" : ""?> /><label for="charging_type-3">정액제</label>
									</div>
								</li>
                <li>
									<label class="info_contents_label">일 발신한도 지정</label>
									<div class="info_contents">
										<input class="tr input-width-large" id="id_day_limit" name="day_limit"  value="<?=number_format($rs->mem_day_limit)?>" step="1"  /> &nbsp; &nbsp;<span class="pt20">※ 0으로 지정하면 제한없음</span>
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">최소 충전금액</label>
									<div class="info_contents">
										<input type="number" id="mem_min_charge_amt" name="mem_min_charge_amt" class="input-width-large" placeholder="관리업체 최소 충전 금액" value="<?=$rs->mem_min_charge_amt?>"/>
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">송금인 이름<span class="essential">*</span></label>
									<div class="info_contents">
										<input class="input-width-large required" id="id_bank_user" maxlength="50" name="bank_user" placeholder="송금인 이름" value="<?=($param['key']) ? $rs->mem_bank_user : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">정액제 기간</label>
									<div class="info_contents">
										<input class="input-width-large " id="id_fixed_from_date" maxlength=15 name="mem_fixed_from_date" placeholder="시작일" value="<?=$rs->mem_fixed_from_date?>" type="text" /> ~
                		<input class="input-width-large " id="id_fixed_to_date" maxlength=15 name="mem_fixed_to_date" placeholder="종료일" value="<?=$rs->mem_fixed_to_date?>" type="text" />
									</div>
								</li>
								<!--<li>
									<label class="info_contents_label">Full Care 비용</label>
									<div class="info_contents">
										<input class="tr input-width-large" id="id_price_full_care" name="price_full_care"  value="<?=number_format($rs->mad_price_full_care)?>" step="1"  /> 원
									</div>
								</li>-->
							</ul>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit mg_t30">
							<h3>영업정보</h3>
						</div>
						<div class="white_box">
							<ul class="info_contents_wrap">
								<li>
									<label class="info_contents_label icon_must">영업 담당자</label>
									<div class="info_contents">
										<input class="input-width-large" id="id_mem_sales_name" maxlength="50" name="mem_sales_name" placeholder="현재 영업 담당자" value="<?=$rs->mem_sales_name ?>" type="text" />
									</div>
								</li>

								<!--<li>
									<label class="info_contents_label">매장URL(카페,블로그)</label>
									<div class="info_contents">
										<input class="input-width-large" id="id_phn_free" maxlength="100" style="width:500px !important;" name="phn_free" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=($param['key']) ? $rs->mad_free_hp : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">카카오채널 매니저 계정</label>
									<div class="info_contents">
										<input class="input-width-large" id="id_mem_pf_manager" maxlength="50" name="mem_pf_manager" placeholder="플러스 친구 매니저 계정" value="<?=$rs->mem_pf_manager ?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label icon_must">플러스 친구수(카카오 기준)</label>
									<div class="info_contents">
										<input class="form-control  input-width-large" id="id_mem_pf_cnt" name="mem_pf_cnt" value="<?=$rs->mem_pf_cnt ?>" type="number" />
									</div>
								</li>

								<li>
									<label class="info_contents_label">링크 버튼 이름</label>
									<div class="info_contents">
										<input class="input-width-large" id="id_linkbtn_name" maxlength="100" style="width:500px !important;" name="linkbtn_name" placeholder="링크버턴명" value="<?=($param['key']) ? $rs->mem_linkbtn_name : '세일전단지'?>" type="text" />
									</div>
								</li>-->
							</ul>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit mg_t20">
							<h3>2차발신 선택</h3>
						</div>
						<div class="white_box">
							<p style="margin-bottom: 15px;">※ 카카오 알림톡이나 친구톡 발송 실패시 재전송 할 서비스를 지정합니다.</p>
							<div class="checks">
								<input type="radio" id="second_send1" name="mem_2nd_send" value="NONE" <?=($rs->mem_2nd_send=="" || $rs->mem_2nd_send=="NONE") ? "checked" : ""?> /><label for="second_send1">보내지않음</label>
								<input type="radio" id="second_send2" name="mem_2nd_send" value="GREEN_SHOT" <?=($rs->mem_2nd_send=="GREEN_SHOT") ? "checked" : ""?> /><label for="second_send2">WEB문자(A)</label>
								<!--<input type="radio" id="second_send3" name="mem_2nd_send" value="NASELF" <?=($rs->mem_2nd_send=="NASELF") ? "checked" : ""?> /><label for="second_send3">WEB문자(B)</label>-->
								<input type="radio" id="second_send4" name="mem_2nd_send" value="SMART" <?=($rs->mem_2nd_send=="SMART") ? "checked" : ""?> /><label for="second_send4">WEB문자(C)</label>
								<!-- input type="radio" class="" name="mem_2nd_send" value="015" <?=($rs->mem_2nd_send=="015") ? "checked" : ""?> />&nbsp;015문자
								<input type="radio" class="" name="mem_2nd_send" value="DOOIT" <?=($rs->mem_2nd_send=="DOOIT") ? "checked" : ""?> />&nbsp;폰문자 &nbsp; &nbsp; -->
							</div>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit mg_t20">
							<h3>발신단가 등록</h3>
						</div>
						<div class="white_box">
							<p>※ 발신단가를 0원 이하로 지정하면 해당발신 서비스는 사용하지 않습니다. (부가세 포함 가격을 입력하세요!)</p>
							<? if($rs->mem_level >= 100) {?>
							<p>※ 관리자의 <b>폰문자/SMS/LMS/MMS 단가</b>는 발신업체 다중화로 좌측메뉴의 <font color='black'><b>관리자 > 기본정보설정</b></font>에서 수정하셔야 합니다.</p>
							<?}?>
							<!--div class="table_stat">
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
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>알림톡</th>
                                    <th>친구톡 (텍스트)</th>
                                    <th>친구톡 (이미지)</th>
                                    <th>친구톡 (와이드 이미지)</th>



									<? if($this->member->item('mem_level')>=100) { ?>
									<? if($rs->mem_level >= 50) {  ?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A) KT, LGT</th>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A) SKT</th>
									<? } else {?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)</th>
									<? } } else {?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)</th>
									<? } ?>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A) SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A) MMS</th>



                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B) SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B) MMS</th>



                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C) SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C) MMS</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input class="align-center inline" id="id_price_at" name="price_at" value="<?=($param['key']) ? number_format($rs->mad_price_at, 2) : number_format($this->Biz_model->base_price_at, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_at']?>"/></td>
                                    <td><input class="align-center inline price" id="id_price_ft" name="price_ft" value="<?=($param['key']) ? number_format($rs->mad_price_ft, 2) : number_format($this->Biz_model->base_price_ft, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft']?>"/></td>
                                    <td><input class="align-center inline price" id="id_price_ft_img" name="price_ft_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_img, 2) : number_format($this->Biz_model->base_price_ft_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_img']?>"/></td>
                                    <td><input class="align-center inline price" id="id_price_ft_w_img" name="price_ft_w_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_w_img, 2) : number_format($this->Biz_model->base_price_ft_w_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_w_img']?>"/></td>




                                    <? if($this->member->item('mem_level')>=100) { ?>
									<? if($rs->mem_level >= 50) {  ?>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_grs_biz" name="price_grs_biz" value="<?=($param['key']) ? number_format($rs->mad_price_grs_biz, 2) : number_format($this->Biz_model->mad_price_grs_biz, 2) ?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs_biz']?>"/></td>
									<? } else { ?>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
									<? } } else {?>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
									<? } ?>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_grs_sms" name="price_grs_sms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_sms, 2) : number_format($this->Biz_model->base_price_grs_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_sms']?>" /></td>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_grs_mms" name="price_grs_mms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_mms, 2) : number_format($this->Biz_model->base_price_grs_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_mms']?>" /></td>



                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_nas" name="price_nas" value="<?=($param['key']) ? number_format($rs->mad_price_nas, 2) : number_format($this->Biz_model->base_price_nas, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> /></td>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_nas_sms" name="price_nas_sms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_sms, 2) : number_format($this->Biz_model->base_price_nas_sms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_sms']?>"/></td>
                                    <td style="line-height:25pt;"><input class="align-center inline price" id="id_price_nas_mms" name="price_nas_mms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_mms, 2) : number_format($this->Biz_model->base_price_nas_mms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_mms']?>"/></td>



                                    <td><input class="align-center inline price" id="id_price_smt" name="price_smt"  value="<?=($param['key']) ? number_format($rs->mad_price_smt, 2) : number_format($this->Biz_model->base_price_smt, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt']?>"/></td>
                                    <td><input class="align-center inline price" id="id_price_smt_sms" name="price_smt_sms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_sms']?>"/></td>
                                    <td><input class="align-center inline price" id="id_price_smt_mms" name="price_smt_mms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_mms, 2) : number_format($this->Biz_model->base_price_smt_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_mms']?>"/></td>
                                </tr>
										  </tbody>
									 </table>
							</div-->

							<div class="table_stat" style="width: 800px;">
								<table>
	                                <colgroup>
	                                    <col width="25%">
	                                    <col width="25%">
	                                    <col width="25%">
	                                    <col width="25%">
	                                </colgroup>
	                                <thead>
		                                <tr>
			                                <th colspan="4" style="background: #757575;">카카오톡</th>
		                                </tr>
		                                <tr>
		                                    <th>알림톡</th>
		                                    <th>친구톡(텍스트)</th>
		                                    <th>친구톡(이미지)</th>
		                                    <th>친구톡(와이드형)</th>
		                                </tr>
	                                </thead>
	                                <tbody>
		                                <tr>
		                                    <td><input class="tr" id="id_price_at" name="price_at" value="<?=($param['key']) ? number_format($rs->mad_price_at, 2) : number_format($this->Biz_model->base_price_at, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_at']?>"/></td>
		                                    <td><input class="tr" id="id_price_ft" name="price_ft" value="<?=($param['key']) ? number_format($rs->mad_price_ft, 2) : number_format($this->Biz_model->base_price_ft, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft']?>"/></td>
		                                    <td><input class="tr" id="id_price_ft_img" name="price_ft_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_img, 2) : number_format($this->Biz_model->base_price_ft_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_img']?>"/></td>
		                                    <td><input class="tr" id="id_price_ft_w_img" name="price_ft_w_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_w_img, 2) : number_format($this->Biz_model->base_price_ft_w_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_w_img']?>"/></td>
		                                </tr>
							  		</tbody>
					 			</table>
							</div>
							<div class="table_stat" style="width: 800px;">
								<table>
	                                <colgroup>
	                                    <? if($this->member->item('mem_level')>=100) { ?>
										<? if($rs->mem_level >= 50) {  ?>
			                                    <col width="17%">
			                                    <col width="17%">
			                            <? } else { ?>
			                                    <col width="34%">
			                            <? } } else {?>
			                            		<col width="34%">
			                            <? } ?>
			                                    <col width="33%">
			                                    <col width="33%">
	                                </colgroup>
	                                <thead>
		                                <tr>
			                                <th colspan="4" style="background: #757575;">WEB A</th>
		                                </tr>
		                                <tr>
		                                    <? if($this->member->item('mem_level')>=100) { ?>
											<? if($rs->mem_level >= 50) {  ?>
												<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS(KT, LGT)</th>
												<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS(SKT)</th>
											<? } else {?>
												<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th>
											<? } } else {?>
												<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th>
											<? } ?>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>SMS</th>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>MMS</th>
		                                </tr>
	                                </thead>
	                                <tbody>
		                                <tr>
		                                    <? if($this->member->item('mem_level')>=100) { ?>
											<? if($rs->mem_level >= 50) {  ?>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_grs_biz" name="price_grs_biz" value="<?=($param['key']) ? number_format($rs->mad_price_grs_biz, 2) : number_format($this->Biz_model->mad_price_grs_biz, 2) ?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs_biz']?>"/></td>
											<? } else { ?>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
											<? } } else {?>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
											<? } ?>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_grs_sms" name="price_grs_sms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_sms, 2) : number_format($this->Biz_model->base_price_grs_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_sms']?>" /></td>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_grs_mms" name="price_grs_mms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_mms, 2) : number_format($this->Biz_model->base_price_grs_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_mms']?>" /></td>
		                                </tr>
							  		</tbody>
					 			</table>
				 			</div>
				 			<!--<div class="table_stat" style="width: 800px;">
					 			<table>
	                                <colgroup>
	                                    <col width="34%">
	                                    <col width="33%">
	                                    <col width="33%">
	                                </colgroup>
	                                <thead>
		                                <tr>
			                                <th colspan="4" style="background: #757575;">WEB B</th>
		                                </tr>
		                                <tr>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>SMS</th>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>MMS</th>
		                                </tr>
	                                </thead>
	                                <tbody>
		                                <tr>
		                                    <td style="line-height:25pt;"><input class="tr" id="id_price_nas" name="price_nas" value="<?=($param['key']) ? number_format($rs->mad_price_nas, 2) : number_format($this->Biz_model->base_price_nas, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> /></td>
                                    <td style="line-height:25pt;"><input class="tr" id="id_price_nas_sms" name="price_nas_sms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_sms, 2) : number_format($this->Biz_model->base_price_nas_sms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_sms']?>"/></td>
                                    <td style="line-height:25pt;"><input class="tr" id="id_price_nas_mms" name="price_nas_mms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_mms, 2) : number_format($this->Biz_model->base_price_nas_mms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_mms']?>"/></td>
		                                </tr>
							  		</tbody>
					 			</table>
					 		</div>-->
							<div class="table_stat" style="width: 800px;">
					 			<table>
	                                <colgroup>
	                                    <col width="34%">
	                                    <col width="33%">
	                                    <col width="33%">
	                                </colgroup>
	                                <thead>
		                                <tr>
			                                <th colspan="4" style="background: #757575;">WEB C</th>
		                                </tr>
		                                <tr>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>SMS</th>
		                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>MMS</th>
		                                </tr>
	                                </thead>
	                                <tbody>
		                                <tr>
		                                    <td><input class="tr" id="id_price_smt" name="price_smt"  value="<?=($param['key']) ? number_format($rs->mad_price_smt, 2) : number_format($this->Biz_model->base_price_smt, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt']?>"/></td>
                                    <td><input class="tr" id="id_price_smt_sms" name="price_smt_sms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_sms']?>"/></td>
                                    <td><input class="tr" id="id_price_smt_mms" name="price_smt_mms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_mms, 2) : number_format($this->Biz_model->base_price_smt_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_mms']?>"/></td>
		                                </tr>
							  		</tbody>
					 			</table>
							</div>
						</div>
					</div>
					<div class="tc mg_t30 mg_b50">
            <button type="submit" class="btn_st3">저장</button>
                    	<?if ($rs->mem_userid) {?>
                    	<a  href="/biz/partner/view?<?=$rs->mem_userid?>" class="btn_st3"><span>취소</span></a>
                    	<? } else { ?>
                        <a  href="/biz/partner/lists" class="btn_st3"><span>취소</span></a>
                        <? } ?>

					</div>
				</div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <style>
        .select2-no-results {
            display: none !important;
        }

        textarea {
            resize: none;
        }
    </style>
    <script type="text/javascript">
		var check_password_ = false;
		var check_password_rule_ = false;
		var check_password_new_ = false;
		var auth_result_ = false;
		var email_result_ = false;
		var file_check_ = 0;
		var set_id = "";
		var price_at = <?=$this->Biz_model->price_at?>;
		var price_ft = <?=$this->Biz_model->price_ft?>;
		var price_ft_img = <?=$this->Biz_model->price_ft_img?>;
		var price_phn = <?=$this->Biz_model->price_phn?>;
		var price_sms = <?=$this->Biz_model->price_sms?>;
		var price_lms = <?=$this->Biz_model->price_lms?>;
		var price_mms = <?=$this->Biz_model->price_mms?>;
		var price_smt = <?=$this->Biz_model->price_smt ? $this->Biz_model->price_smt : 0 ?>;

		$(document).ready(function() {
            var elem = document.getElementById('id_password');
            elem.setAttribute('onblur', 'check_empty("id_password"); check_password_rule("id_password");');
            elem.setAttribute('onchange', 'noSpaceForm(this);');
            var elem2 = document.getElementById('id_password2');
            elem2.setAttribute('onblur', 'check_password_new("id_password", "id_password2"); check_empty("id_password2");');
            elem2.setAttribute('onchange', 'noSpaceForm(this);');

			$("#nav li.nav60").addClass("current open");

			$("#wrap").css('position', 'absolute');
			$(".modal-content").css('width', '320px');
			$(".modal-body").css('height', '120px');
			//$("#myModal").css('margin-left', '40%');
			//$("#myModal").css('margin-top', '15%');
			$("#myModal").css('overflow-x', 'hidden');
			$("#myModal").css('overflow-y', 'hidden');

			$('.price').change(function() {
				check_price();
			});

			$("#id_fixed_from_date, #id_fixed_to_date").datepicker({
				format:'yyyy-mm-dd'	,
				todayBtn:"linked",
				language:"kr",
				autoclose:true,
				todayHighlight:true
			});
		});


		function methodChange() {

			recommend_mem_id = $("#recommend_mem_id").val();
			console.log(recommend_mem_id);
			if(recommend_mem_id <= 0) {
				recommend_mem_id = 3;
			}

			$.ajax({
			url: "/biz/partner2/getMemberPriceJSON",
			type: "POST",
			data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"parent_mem_id" : recommend_mem_id },
			success: function (json) {
				$("#id_price_at").attr({"min":json['mad_price_at']});
				$("#id_price_ft").attr({"min":json['mad_price_ft']});
				$("#id_price_ft_img").attr({"min":json['mad_price_ft_img']});
				$("#id_price_grs").attr({"min":json['mad_price_grs']});
				$("#id_price_grs_sms").attr({"min":json['mad_price_grs_sms']});
				$("#id_price_nas").attr({"min":json['mad_price_nas']});
				$("#id_price_nas_sms").attr({"min":json['id_price_nas_sms']});
				$("#id_price_015").attr({"min":json['mad_price_015']});
				$("#id_price_phn").attr({"min":json['mad_price_phn']});
				$("#id_price_dooit").attr({"min":json['mad_price_dooit']});
				$("#id_price_sms").attr({"min":json['mad_price_sms']});
				$("#id_price_lms").attr({"min":json['mad_price_lms']});
				$("#id_price_mms").attr({"min":json['mad_price_mms']});
				$("#id_price_smt").attr({"min":json['mad_price_smt']});
			}
			});
		}

		function check_price()
		{
			var err = 0;
<?if($this->member->item('mem_level') < 100) {?>
			$('.price').each(function() {
				if($(this).val()!=0) {
					if($(this).prop('id').indexOf("price_at") > -1) { if($(this).val() < price_at) { alert("알림톡 단가는 " + price_at + "원 이상이어야 합니다."); $(this).val(price_at); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_ft_img") > -1) { if($(this).val() < price_ft_img) { alert("친구톡(이미지) 단가는 " + price_ft_img + "원 이상이어야 합니다."); $(this).val(price_ft_img); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_ft") > -1) { if($(this).val() < price_ft) { alert("친구톡(텍스트) 단가는 " + price_ft + "원 이상이어야 합니다."); $(this).val(price_ft); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_phn") > -1) { if($(this).val() < price_phn) { alert("폰문자 단가는 " + price_phn + "원 이상이어야 합니다."); $(this).val(price_phn); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_sms") > -1) { if($(this).val() < price_sms) { alert("SMS 단가는 " + price_sms + "원 이상이어야 합니다."); $(this).val(price_sms); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_lms") > -1) { if($(this).val() < price_lms) { alert("LMS 단가는 " + price_lms + "원 이상이어야 합니다."); $(this).val(price_lms); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_mms") > -1) { if($(this).val() < price_mms) { alert("MMS 단가는 " + price_mms + "원 이상이어야 합니다."); $(this).val(price_mms); err++; $(this).focus(); } }
					else if($(this).prop('id').indexOf("price_smt") > -1) { if($(this).val() < price_mms) { alert("WEB(C) 단가는 " + price_smt + "원 이상이어야 합니다."); $(this).val(price_smt); err++; $(this).focus(); } }
				}
			});
<?}?>
			if(err == 0) {
				if($('#id_price_at').val()=="" || $('#id_price_ft').val()=="" || $('#id_price_ft_img').val()=="" || ($('#id_price_at').val() * 1) < 1 || ($('#id_price_ft').val() * 1) < 1 || ($('#id_price_ft_img').val() * 1) < 1) {
					alert("카카오 서비스는 미사용으로 지정할 수 없습니다."); err++;
				}
			}
			return err;
		}

        //사업자 등록증 크기 제한
        function CheckUploadFileSize(objFile) {
            var maxSize = 500 * 1024;
            var fileSize = objFile.files[0].size;
            if (fileSize > maxSize) {
                $(".content").html("최대 500KB까지만 가능합니다.");
                $('#myModal').modal({backdrop: 'static'});
                $("#biz_license").val("");
                $("#pf_name").val("");
            }
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

        // 공백 제거
        function noSpaceForm(obj) {
            var str_space = /\s/;  // 공백체크
            if (str_space.exec(obj.value)) { //공백 체크
                obj.focus();
                obj.value = obj.value.replace(/(\s*)/g, ''); // 공백제거
                return false;
            }
        }

        <!-- 추가수정 아이디 한글 입력 제외 TO DO 특수문자포함할거변경(_) -->
        var formId = '#'+'id_userid';
        $(formId).keyup(function(event) {
            if (!(event.keyCode >=37 && event.keyCode<=40)) {
                var mb_id = $(formId).val();
                $(formId).val(mb_id.replace(/[^a-z0-9_]/gi, ''));

            }
        });

			// 필수 입력 검사
		  function check_form() {
			  var err = '';
			  var $err_obj = null;
			  $('input.required').each(function() {
					if(err=='' && ($(this).val()=='' || $(this).val()==$(this).attr('placeholder'))) { err = $(this).attr('placeholder'); $err_obj = $(this); }
			  });
			  if(err!='') {
				  alert(err + " 항목을 입력하여 주세요.");
				  $err_obj.focus();
				  return false;
			  }
			  <?if(!$param['key']) {?>
			  if($('#id_userid').val()=="" || set_id!=$('#id_userid').val()) {
				  alert("아이디 중복확인을 클릭해주세요.");
				  $('#check_double_id').focus();
				  return false;
			  }
			  if(!check_password_new_) {
					alert("비밀번호를 확인하여 주세요.");
					return false;
			  }
			  <?}?>
			  if(!email_check($('#id_email').val())) {
					alert("메일주소를 바르게 입력하여 주세요.");
					$('#id_email').focus();
					return false;
			  }
			  if(check_price() > 0) { return false; }
			  return true;
		  }

        //아이디 중복 확인 //to do: change as dynamic id for reduce duplication
        function check_duplicated_account(id) {
            id_check = true;
            var formId = '#' + id;
            var mb_id=  $(formId).val();

            var passwd_id_rule = id + '_rule';
            var passwd_id_result = id + '_result';

            if ($(formId).val().trim().length == 0 || !mb_id) {
                document.getElementById('mb_id_result').style.display = 'block';
                document.getElementById('id_result_true').style.display = 'none';
                document.getElementById('id_result_false').style.display = 'none';
            }
            else {
                $.ajax({
                    url: '/biz/common/check_dup_id/',
                    type: "POST",
                    data: {
                        "<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
                        mb_id: mb_id
                    },
                    success: function (json) {
                        get_result(json);
                    },
                    error: function (data, status, er) {
                        document.getElementById('mb_id_result').innerHTML = '처리중 오류가 발생하였습니다. 관리자에게 문의하세요';
                        document.getElementById('mb_id_result').style.color = '#942a25';
                        document.getElementById('mb_id_result').style.display = 'block';
                    }
                });
                function get_result(json) {
                    document.getElementById('mb_id_result').style.display = 'none';
                    document.getElementById('mb_id_result_double').style.display = 'none';
                    if(json['result']==true) {
                        document.getElementById('id_result_false').style.display = 'none';
                        document.getElementById('id_result_true').style.display = 'block';
                        set_id=mb_id;
                    }
                    else{
                        document.getElementById('id_result_true').style.display = 'none';
                        document.getElementById('id_result_false').style.display = 'block';
								set_id="";
                    }
                }
            }
        }

        //빈칸 여부 확인
        function check_empty(id) {
            var get_id = '#' + id;
            if ($(get_id).val().trim().length > 0) {
                document.getElementById(id + '_result').style.display = 'none';
            }
            if (!id || $(get_id).val().trim().length == 0) {
                document.getElementById(id + '_rule').style.display = 'none';
            }
        }

        //이메일 유효성 검사
        function email_check(email) {
            var regex = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            return (email != '' && email != 'undefined' && regex.test(email) === true);
        }

        //패스워드 확인
        function check_password_rule(id) {
            var passwd_id = '#' + id;
            var passwd_id_rule = id + '_rule';
            var passwd_id_result = id + '_result';
            var password = $(passwd_id).val();

            var elemPasswordRule = document.getElementById(passwd_id_rule);
            var elemPasswordResult = document.getElementById(passwd_id_result);

            if (password) {
					 var reg_pwd = /([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[azA-Z0-9])/;
                if (password && $(passwd_id).val().trim().length > 5 && $(passwd_id).val().trim().length < 16 && reg_pwd.test($(passwd_id).val())) {
                    elemPasswordResult.style.display = 'none';
                    <!-- 추가수정 비밀번호 validate 주석처리 -->
                        elemPasswordRule.innerHTML = '사용가능한 비밀번호 입니다.';
                        elemPasswordRule.style.color = '#2a6496';
                        elemPasswordRule.style.display = 'block';
                        check_password_rule_ = true;

                } else {
                    elemPasswordRule.innerHTML = '사용할 수 없는 비밀번호입니다. (숫자,영문,특수문자조합 6~15자리) ';
                    elemPasswordRule.style.color = '#942a25';
                    elemPasswordRule.style.display = 'block';
                    check_password_rule_ = false;
                }
            }
            else {
                elemPasswordResult.style.display = 'none';
                check_password_rule_ = false;
            }

        }

        //비밀번호 일치 확인 (새로운 비밀번호)
        function check_password_new(id, id2) {
            var passwd_id = '#' + id;
            var passwd_id2 = '#' + id2;
            var passwd_id_rule = id + '_rule';
            var passwd_id_rule2 = id2 + '_rule';
            var passwd_id_result = id + '_result';
            var passwd_id_result2 = id2 + '_result';
            var password = $(passwd_id).val();

            var new_mb_passwd = $(passwd_id).val();
            var new_mb_passwd2 = $(passwd_id2).val();

            var domPasswordResult = document.getElementById(passwd_id_result);
            var domPasswordResult2 = document.getElementById(passwd_id_result2);
            var domPasswordRule = document.getElementById(passwd_id_rule);
            var domPasswordRule2 = document.getElementById(passwd_id_rule2);

            if (new_mb_passwd.trim().length == 0 || new_mb_passwd2.trim().length == 0) {
                if (new_mb_passwd.trim().length == 0) { //공백문자 일 경우(mb_passwd)
                    domPasswordResult.style.display = 'block';
                    check_password_new_ = false;
                }
                if (new_mb_passwd2.trim().length == 0) { //공백문자 일 경우(mb_passwd2)
                    domPasswordResult2.style.display = 'block';
                    check_password_new_ = false;
                }
            }
            else if (!new_mb_passwd || !new_mb_passwd2) {

                if (!new_mb_passwd) {
                    domPasswordResult.style.display = 'block';
                    check_password_new_ = false;
                }
                if (!new_mb_passwd2) {
                    domPasswordResult2.style.display = 'block';
                    check_password_new_ = false;
                }

            }
            else {
                domPasswordResult.style.display = 'none';
                if (new_mb_passwd != new_mb_passwd2) {
                    domPasswordRule2.innerHTML = '비밀번호가 일치하지 않습니다.';
                    domPasswordRule2.style.color = '#942a25';
                    domPasswordRule2.style.display = 'block';
                    check_password_new_ = false;
                } else {
                    domPasswordRule2.innerHTML = '비밀번호가 일치 합니다.';
                    domPasswordRule2.style.color = '#2a6496';
                    domPasswordRule2.style.display = 'block';
                    check_password_new_ = true;
                }
            }
        }

//         window.onload = function () {
//             var elem = document.getElementById('id_password');
//             elem.setAttribute('onblur', 'check_empty("id_password"); check_password_rule("id_password");');
//             elem.setAttribute('onchange', 'noSpaceForm(this);');
//             var elem2 = document.getElementById('id_password2');
//             elem2.setAttribute('onblur', 'check_password_new("id_password", "id_password2"); check_empty("id_password2");');
//             elem2.setAttribute('onchange', 'noSpaceForm(this);');
//         }

    </script>
