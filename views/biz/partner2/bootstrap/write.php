    <style>
        .errorlist {
            display: block; color: rgb(148, 42, 37); font-weight: bold;
        }
		  .upload-hidden { display:none !important; }
    </style>

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
							<h3>사업자정보 등록(필수)</h3>
						</div>
						<div class="inner_content">
							<div class="info_img">
								<!--
								<input type="hidden" id="old_photo" name="old_photo" value="<?=$rs->mem_photo?>" />
								<input type="text" id="pf_name" class="textbox form-control input-width-large" readonly="readonly" placeholder="파일이 선택되지 않았습니다."/>
								<label for="biz_license" class="btn btn-default find">파일찾기</label>
								<input type="file" id="biz_license" name="biz_license" class="upload-hidden" accept="image/jpeg, image/png"
															onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('pf_name').value=b;if($('#biz_license').val()!=''){
																 $('.danger').remove();
															 } CheckUploadFileSize(this)">
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
							<ul class="info_contents_wrap">
								<li>
									<label class="info_contents_label">상호</label>
									<div class="info_contents"><input type="text" style="width: 300px"></div>
								</li>
								<li>
									<label class="info_contents_label">사업자 등록번호</label>
									<div class="info_contents"><input type="text" maxlength="3" style="width: 60px"> - <input type="text" maxlength="2" style="width: 40px"> - <input type="text" maxlength="5" style="width: 100px"></div>
								</li>
								<li>
									<label class="info_contents_label">사업장소재지</label>
									<div class="info_contents"> <button class="btn sm">우편번호</button></div>
								</li>
								<li>
									<label class="info_contents_label">사업자 구분</label>
									<div class="info_contents">
										<select>
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
									<label class="info_contents_label">대표자 이름</label>
									<div class="info_contents"><input type="text" style="width: 300px"></div>
								</li>
							</ul>
						</div>
					</div>
					<div class="form_section">
						<form id="save" method="POST" onsubmit="return check_form()" EncType="Multipart/Form-Data">
                        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                        <input type='hidden' name='actions' value='partner_save' />
						<div class="inner_tit">
							<?if($this->member->item('mem_level')>=100) {?><div class="form_check fr" style="display: inline-block;"><input type="checkbox" name="useyn" id="useyn" <?=($rs->mem_useyn=="N") ? "checked" : ""?> value="N" /><label for="useyn">삭제</label></div><?}?>
							<h3>기본정보 등록</h3>
						</div>
						<div class="inner_content">
							<ul class="info_contents_wrap">
								<li>
									<label class="info_contents_label">계정*</label>
									<div class="info_contents">
									<?php if($param['key']) {?>
									<input class="required" id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" value="<?=$rs->mem_userid?>" readonly />
									<?php } else {?>
									<input id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" />
                                    <button id="check_double_id" class="btn sm" onclick="check_duplicated_account('id_userid')">중복 확인</button>
									<?php }?>
									
									
                                        <text id="mb_id_result_double" style="display:none; color:#942a25;" > 계정 중복확인을 해주세요. </text>
                                        <text id="mb_id_result" style="display:none; color:#942a25;" > 계정을 입력해주세요. </text>
                                        <text id="id_result_false" style="display:none; color:#942a25;" > 이미 사용중인 계정 입니다. </text>
                                        <text id="id_result_true" style="display:none; color: #2a6496;" > 사용 가능한 계정 입니다. </text>
									</div>
								</li>
								<li>
									<label class="info_contents_label" for="id_userid">소속*</label>
									<div class="info_contents">
										<select style="width: 300px;">
											<option>111</option>
											<option>222</option>
										</select>
									</div>
								</li>
								<li>
									<label class="info_contents_label">비밀번호*</label>
									<div class="info_contents">
										<input class="form-control input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password" maxlength="128" name="password" placeholder="비밀번호" type="password" />
                                    <text id="id_password_result"
                                          style="display:none; color:#942a25; font-weight: bold; "> 새로운 비밀번호를 입력해주세요.
                                    </text>
                                    <text id="id_password_rule"
                                          style="display:none; color:#942a25; font-weight: bold; "></text>

                                        <span class="help-block">새로운 비밀번호를 입력해주세요.</span>
									</div>
								</li>
								<li>
									<label class="info_contents_label">비밀번호 확인*</label>
									<div class="info_contents">
										<input class="form-control input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password2" name="password2" type="password" />
                                    <text id="id_password2_rule"
                                          style="display:none; color:#942a25; font-weight: bold; "></text>
                                    <text id="id_password2_result"
                                          style="display:none; color:#942a25; font-weight: bold; "> 비밀번호를 다시 한번 입력해주세요.
                                    </text>
									</div>
								</li>
								<li>
									<label class="info_contents_label">업체명*</label>
									<div class="info_contents">
										<input class="form-control input-width-large required" id="id_company_name" maxlength="50" name="company_name" placeholder="업체명" value="<?=($param['key']) ? $rs->mem_username : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">등급*</label>
									<div class="info_contents">
										<select class="select2 input-width-large" id="mem_level" name="mem_level">
													<option value="1" <?=($rs->mem_level<10) ? "selected" : ""?>>마트</option>
<?if($this->member->item('mem_level')>=30) {?><option value="10" <?=($rs->mem_level>=10 && $rs->mem_level<30) ? "selected" : ""?>>하위관리자</option><?}?>
<?if($this->member->item('mem_level')>=50) {?><option value="30" <?=($rs->mem_level>=30 && $rs->mem_level<50) ? "selected" : ""?>>중간관리자</option><?}?>
<?if($this->member->item('mem_level')>=100) {?><option value="50" <?=($rs->mem_level>=50 && $rs->mem_level<100) ? "selected" : ""?>>상위관리자</option><?}?>
<?if($this->member->item('mem_level')>=100) {?><option value="100" <?=($rs->mem_level>=100) ? "selected" : ""?>>관리자</option><?}?>
												 </select>
									</div>
								</li>
								<li>
									<label class="info_contents_label">계좌정보*</label>
									<div class="info_contents">
										<input class="form-control input-width-large " id="id_mem_payment_desc" maxlength=100 name="mem_payment_desc" placeholder="계좌정보" value="<?=$rs->mem_payment_desc?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">결재방법*</label>
									<div class="info_contents">
										<input class="uniform" id="id_mem_payment_bank" name="mem_payment_bank" type="checkbox" <?=($rs->mem_payment_bank)?"checked":""?> />계좌이체
   										<input class="uniform" id="id_mem_payment_card" name="mem_payment_card" type="checkbox" <?=($rs->mem_payment_card)?"checked":""?> />신용카드
   										<input class="uniform" id="id_mem_payment_realtime" name="mem_payment_realtime" type="checkbox" style="display: none" <?=($rs->mem_payment_realtime)?"checked":""?> /><!-- 계좌이체 -->
   										<input class="uniform" id="id_mem_payment_vbank" name="mem_payment_vbank" type="checkbox" <?=($rs->mem_payment_vbank)?"checked":""?> />무통장입금
   										<input class="uniform" id="id_mem_payment_phone" name="mem_payment_phone" type="checkbox" style="display: none" <?=($rs->mem_payment_phone)?"checked":""?> /><!-- 휴대폰결제 -->
									</div>
								</li>
								<li>
									<label class="info_contents_label">충전방식*</label>
									<div class="info_contents checks">
										<input type="radio" id="charging_type-1" name="mem_pay_type" value="B" <?=($rs->mem_pay_type=="B") ? "checked" : ""?> /><label for="charging_type-1">선불</label> 
										<input type="radio" id="charging_type-2" name="mem_pay_type" value="A" <?=($rs->mem_pay_type=="A") ? "checked" : ""?> /><label for="charging_type-2">후불</label>
										<input type="radio" id="charging_type-3" name="mem_pay_type" value="T" <?=($rs->mem_pay_type=="T") ? "checked" : ""?> /><label for="charging_type-3">정액제</label>
									</div>
								</li>
								<li>
									<label class="info_contents_label">최소 충전금액*</label>
									<div class="info_contents">
										<input type="text" id="mem_min_charge_amt" name="mem_min_charge_amt"
															class="textbox form-control input-width-large" placeholder="관리업체 최소 충전 금액" value="<?=$rs->mem_min_charge_amt?>"/>
									</div>
								</li>
								<li>
									<label class="info_contents_label">정액제 기간*</label>
									<div class="info_contents">
										<input class="form-control input-width-large " id="id_fixed_from_date" maxlength=15 name="mem_fixed_from_date" placeholder="시작일" value="<?=$rs->mem_fixed_from_date?>" type="text" />~
                						<input class="form-control input-width-large " id="id_fixed_to_date" maxlength=15 name="mem_fixed_to_date" placeholder="종료일" value="<?=$rs->mem_fixed_to_date?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">카카오채널 매니저 계정*</label>
									<div class="info_contents">
										<input class="form-control input-width-large" id="id_mem_pf_manager" maxlength="50" name="mem_pf_manager" placeholder="플러스 친구 매니저 계정" value="<?=$rs->mem_pf_manager ?>" type="text" />
									</div>
								</li>
							</ul>
						</div>

					</div>
					<div class="form_section">
						<div class="inner_tit">
							<h3>매장정보 등록</h3>
						</div>
						<div class="inner_content">
							<ul class="info_contents_wrap">
								<li>
									<label class="info_contents_label">SMS 발신 전화번호*</label>
									<div class="info_contents">
										<input class="form-control input-width-large required" id="id_phone" maxlength="50" name="phone" placeholder="전화번호" value="<?=($param['key']) ? $rs->mem_phone : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">담당자 이름*</label>
									<div class="info_contents">
										<input class="form-control input-width-large required" id="id_username" maxlength="50" name="username" placeholder="담당자이름" value="<?=($param['key']) ? $rs->mem_nickname : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">담당자 연락처*</label>
									<div class="info_contents">
										<input class="form-control input-width-large required" id="id_emp_phn" maxlength="25" name="emp_phn" placeholder="- 없이 입력해주세요" value="<?=($param['key']) ? $rs->mem_emp_phone : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">담당자 이메일*</label>
									<div class="info_contents">
										<input class="form-control input-width-large required" id="id_email" maxlength="254" style="width:500px !important;" name="email" placeholder="이메일" value="<?=($param['key']) ? $rs->mem_email : ''?>" type="email" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">송금인 이름*</label>
									<div class="info_contents">
										<input class="form-control input-width-large required" id="id_bank_user" maxlength="50" name="bank_user" placeholder="송금인 이름" value="<?=($param['key']) ? $rs->mem_bank_user : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">매장URL(카페,블로그)*</label>
									<div class="info_contents">
										<input class="form-control input-width-large" id="id_phn_free" maxlength="100" style="width:500px !important;" name="phn_free" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=($param['key']) ? $rs->mad_free_hp : ''?>" type="text" />
									</div>
								</li>
								<li>
									<label class="info_contents_label">일 발신한도 지정*</label>
									<div class="info_contents">
										<input class="form-control align-center input-width-large" id="id_day_limit" name="day_limit"  value="<?=number_format($rs->mem_day_limit)?>" step="1"  /> &nbsp; &nbsp;<span class="pt20">※ 0으로 지정하면 제한없음</span>
									</div>
								</li>
								<li>
									<label class="info_contents_label">Full Care 비용*</label>
									<div class="info_contents">
										<input class="form-control align-center input-width-large" id="id_price_full_care" name="price_full_care"  value="<?=number_format($rs->mad_price_full_care)?>" step="1"  /> 원
									</div>
								</li>
								<li>
									<label class="info_contents_label">링크 버튼 이름*</label>
									<div class="info_contents">
										<input class="form-control input-width-large" id="id_linkbtn_name" maxlength="100" style="width:500px !important;" name="linkbtn_name" placeholder="링크버턴명" value="<?=($param['key']) ? $rs->mem_linkbtn_name : '세일전단지'?>" type="text" />
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit">
							<h3>2차발신 선택</h3>
						</div>
						<div class="inner_content">
							<p>※ 카카오 알림톡이나 친구톡 발송 실패시 재전송 할 서비스를 지정합니다.</p>
							<div class="checks">
								<input type="radio" id="second_send1" name="mem_2nd_send" value="NONE" <?=($rs->mem_2nd_send=="" || $rs->mem_2nd_send=="NONE") ? "checked" : ""?> /><label for="second_send1">보내지않음</label>
								<input type="radio" id="second_send2" name="mem_2nd_send" value="GREEN_SHOT" <?=($rs->mem_2nd_send=="GREEN_SHOT") ? "checked" : ""?> /><label for="second_send2">WEB문자(A)</label>
								<input type="radio" id="second_send3" name="mem_2nd_send" value="NASELF" <?=($rs->mem_2nd_send=="NASELF") ? "checked" : ""?> /><label for="second_send3">WEB문자(B)</label>
								<input type="radio" id="second_send4" name="mem_2nd_send" value="SMART" <?=($rs->mem_2nd_send=="SMART") ? "checked" : ""?> /><label for="second_send4">WEB문자(C)</label>
								<!-- input type="radio" class="" name="mem_2nd_send" value="015" <?=($rs->mem_2nd_send=="015") ? "checked" : ""?> />&nbsp;015문자
								<input type="radio" class="" name="mem_2nd_send" value="DOOIT" <?=($rs->mem_2nd_send=="DOOIT") ? "checked" : ""?> />&nbsp;폰문자 &nbsp; &nbsp; -->
							</div>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit">
							<h3>발신단가 등록</h3>
						</div>
						<div class="inner_content">
							<p>※ 발신단가를 0원 이하로 지정하면 해당발신 서비스는 사용하지 않습니다. (부가세 포함 가격을 입력하세요!)</p>
							<? if($rs->mem_level >= 100) {?>
							<p>※ 관리자의 <b>폰문자/SMS/LMS/MMS 단가</b>는 발신업체 다중화로 좌측메뉴의 <font color='black'><b>관리자 > 기본정보설정</b></font>에서 수정하셔야 합니다.</p>
							<?}?>
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
                                   <!--  <col width="100px">
                                    <col width="100px"> -->
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>알림톡</th>
                                    <th>친구톡<BR>(텍스트)</th>
                                    <th>친구톡<BR>(이미지)</th>
                                    <th>친구톡<BR>(와이드 이미지)</th>
									<? if($this->member->item('mem_level')>=100) { ?>
									<? if($rs->mem_level >= 50) {  ?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>KT, LGT</th>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>SKT</th>		
									<? } else {?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)</th>
									<? } } else {?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)</th>
									<? } ?>	                                    
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>MMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)<BR>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)<BR>MMS</th>
                                    <!-- <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th> -->
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)<BR>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)<BR>MMS</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input class="form-control align-center inline" id="id_price_at" name="price_at" value="<?=($param['key']) ? number_format($rs->mad_price_at, 2) : number_format($this->Biz_model->base_price_at, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_at']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_ft" name="price_ft" value="<?=($param['key']) ? number_format($rs->mad_price_ft, 2) : number_format($this->Biz_model->base_price_ft, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_ft_img" name="price_ft_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_img, 2) : number_format($this->Biz_model->base_price_ft_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_img']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_ft_w_img" name="price_ft_w_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_w_img, 2) : number_format($this->Biz_model->base_price_ft_w_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_w_img']?>"/></td>
                                    <? if($this->member->item('mem_level')>=100) { ?>
									<? if($rs->mem_level >= 50) {  ?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_biz" name="price_grs_biz" value="<?=($param['key']) ? number_format($rs->mad_price_grs_biz, 2) : number_format($this->Biz_model->mad_price_grs_biz, 2) ?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs_biz']?>"/></td>
									<? } else { ?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
									<? } } else {?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
									<? } ?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_sms" name="price_grs_sms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_sms, 2) : number_format($this->Biz_model->base_price_grs_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_sms']?>" /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_mms" name="price_grs_mms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_mms, 2) : number_format($this->Biz_model->base_price_grs_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_mms']?>" /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas" name="price_nas" value="<?=($param['key']) ? number_format($rs->mad_price_nas, 2) : number_format($this->Biz_model->base_price_nas, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas_sms" name="price_nas_sms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_sms, 2) : number_format($this->Biz_model->base_price_nas_sms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_sms']?>"/></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas_mms" name="price_nas_mms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_mms, 2) : number_format($this->Biz_model->base_price_nas_mms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_mms']?>"/></td>
<!--                                     <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_015" name="price_015" value="<?=($param['key']) ? number_format($rs->mad_price_015, 2) : number_format($this->Biz_model->base_price_015, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_015']?>" /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_dooit" name="price_dooit" value="<?=($param['key']) ? number_format($rs->mad_price_dooit, 2) : number_format($this->Biz_model->base_price_dooit, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_dooit']?>"/></td> 
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_sms" name="price_sms" value="<?=($param['key']) ? number_format($rs->mad_price_sms, 2) : number_format($this->Biz_model->base_price_sms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_sms']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_lms" name="price_lms" value="<?=($param['key']) ? number_format($rs->mad_price_lms, 2) : number_format($this->Biz_model->base_price_lms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_lms']?>"/></td>-->
                                    <td><input class="form-control align-center inline price" id="id_price_smt" name="price_smt"  value="<?=($param['key']) ? number_format($rs->mad_price_smt, 2) : number_format($this->Biz_model->base_price_smt, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_smt_sms" name="price_smt_sms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_sms']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_smt_mms" name="price_smt_mms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_mms, 2) : number_format($this->Biz_model->base_price_smt_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_mms']?>"/></td>
                                </tr>
										  </tbody>
									 </table>
							</div>
						</div>
					</div>



					<div class="form_section">
						<div class="inner_tit">
							<h3>파트너 등록</h3>
						</div>
						<div class="inner_content">
							
                            <div class="widget-header">
                                <h3><i class="icon-edit"></i> 기본정보 입력</h3>
                            </div>


                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tr>
                                    <th for="id_userid" >계정<span class="required">*</span></th>
                                    <td>

                                        <div class="input-group" style="display:block;">
													 <?php if($param['key']) {?>
														  <input class="form-control input-width-large required" id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" value="<?=$rs->mem_userid?>" readonly />
													 <?php } else {?>
														  <input class="form-control input-width-large required" id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" />
                                            <span class="input-group-btn">
                                             <a type="button" id="check_double_id" class="btn btn-success"
                                                onclick="check_duplicated_account('id_userid')">중복 확인</a>
                                            </span>
													 <?php }?>
                                        </div>
<?if($this->member->item('mem_level')>=100) {?><div style="float:right;margin-top:5px;"><input type="checkbox" class="uniform" name="useyn" id="useyn" <?=($rs->mem_useyn=="N") ? "checked" : ""?> value="N" />삭제</div><?}?>
                                        <text id="mb_id_result_double" style="display:none; color:#942a25; font-weight: bold; " > 계정 중복확인을 해주세요. </text>
                                        <text id="mb_id_result" style="display:none; color:#942a25; font-weight: bold; " > 계정을 입력해주세요. </text>
                                        <text id="id_result_false" style="display:none; color:#942a25; font-weight: bold; " > 이미 사용중인 계정 입니다. </text>
                                        <text id="id_result_true" style="display:none; color: #2a6496; font-weight: bold; " > 사용 가능한 계정 입니다. </text>


                                    </td>
                                    <th for="id_userid" >소속 <span class="required">*</span></th>
												<td>
												<?if($this->member->item('mem_level')>=10) {?>
													<select class="selectpicker" data-live-search="true" id="recommend_mem_id" name="recommend_mem_id" onchange="methodChange()">
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
												</td>
                                </tr>

                            <tr>
                                <th for="id_password">비밀번호<?php if(!$param['key']) {?><span class="required">*</span><?}?></th>
                                <td>
                                    <input class="form-control input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password" maxlength="128" name="password" placeholder="비밀번호" type="password" />
                                    <text id="id_password_result"
                                          style="display:none; color:#942a25; font-weight: bold; "> 새로운 비밀번호를 입력해주세요.
                                    </text>
                                    <text id="id_password_rule"
                                          style="display:none; color:#942a25; font-weight: bold; "></text>

                                        <span class="help-block">새로운 비밀번호를 입력해주세요.</span>


                                </td>
                                <th>비밀번호 재입력<?php if(!$param['key']) {?><span class="required">*</span><?}?></th>
                                <td>
                                    <input class="form-control input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password2" name="password2" type="password" />
                                    <text id="id_password2_rule"
                                          style="display:none; color:#942a25; font-weight: bold; "></text>
                                    <text id="id_password2_result"
                                          style="display:none; color:#942a25; font-weight: bold; "> 비밀번호를 다시 한번 입력해주세요.
                                    </text>
                                </td>
                            </tr>
									 <tr>
                                <th for="id_company_name">업체명<span class="required">*</span></th>
                                <td <?=($this->member->item('mem_level')<100) ? 'colspan="3"' : ''?>>
                                    <input class="form-control input-width-large required" id="id_company_name" maxlength="50" name="company_name" placeholder="업체명" value="<?=($param['key']) ? $rs->mem_username : ''?>" type="text" />

                                </td>
										<?if($this->member->item('mem_level')>=100) {?>
											<th for="id_userid" >등급<span class="required">*</span></th>
											<td>
												<select class="select2 input-width-large" id="mem_level" name="mem_level">
													<option value="1" <?=($rs->mem_level<10) ? "selected" : ""?>>마트</option>
<?if($this->member->item('mem_level')>=30) {?><option value="10" <?=($rs->mem_level>=10 && $rs->mem_level<30) ? "selected" : ""?>>하위관리자</option><?}?>
<?if($this->member->item('mem_level')>=50) {?><option value="30" <?=($rs->mem_level>=30 && $rs->mem_level<50) ? "selected" : ""?>>중간관리자</option><?}?>
<?if($this->member->item('mem_level')>=100) {?><option value="50" <?=($rs->mem_level>=50 && $rs->mem_level<100) ? "selected" : ""?>>상위관리자</option><?}?>
<?if($this->member->item('mem_level')>=100) {?><option value="100" <?=($rs->mem_level>=100) ? "selected" : ""?>>관리자</option><?}?>
												 </select>
											</td>
										<?}?>
									 </tr>
									 <?if($this->member->item('mem_level')>=$this->Biz_model->manager_lv) {?>
									 <tr>
                                <th for="biz_sheet">사업자등록증</th>
                                <td colspan="3">
											 <div class="file">
												  <input type="hidden" id="old_photo" name="old_photo" value="<?=$rs->mem_photo?>" />
												  <input type="text" id="pf_name"
															class="textbox form-control input-width-large" readonly="readonly"
															placeholder="파일이 선택되지 않았습니다."/>
												  <label for="biz_license" class="btn btn-default find">파일찾기</label>
												  <input type="file" id="biz_license" name="biz_license" class="upload-hidden" accept="image/jpeg, image/png"
															onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('pf_name').value=b;if($('#biz_license').val()!=''){
																 $('.danger').remove();
															 } CheckUploadFileSize(this)">
												  <span class="help-block msg"> &nbsp;jpg, png 파일만 업로드 됩니다. 최대 500KB </span>
											 </div>
                                </td>
                                </tr>
<?if($this->member->item('mem_level')>=100) {?>                                
                                <tr>
                                    <th for="biz_addition">계좌정보</th>
                                    <td >
    									<input class="form-control input-width-large " id="id_mem_payment_desc" maxlength=100 name="mem_payment_desc" placeholder="계좌정보" value="<?=$rs->mem_payment_desc?>" type="text" />
    								</td>       
                                    <th for="biz_addition">결재방법</th>
                                    <td >
   										<input class="uniform" id="id_mem_payment_bank" name="mem_payment_bank" type="checkbox" <?=($rs->mem_payment_bank)?"checked":""?> />계좌이체
   										<input class="uniform" id="id_mem_payment_card" name="mem_payment_card" type="checkbox" <?=($rs->mem_payment_card)?"checked":""?> />신용카드
   										<input class="uniform" id="id_mem_payment_realtime" name="mem_payment_realtime" type="checkbox" style="display: none" <?=($rs->mem_payment_realtime)?"checked":""?> /><!-- 계좌이체 -->
   										<input class="uniform" id="id_mem_payment_vbank" name="mem_payment_vbank" type="checkbox" <?=($rs->mem_payment_vbank)?"checked":""?> />무통장입금
   										<input class="uniform" id="id_mem_payment_phone" name="mem_payment_phone" type="checkbox" style="display: none" <?=($rs->mem_payment_phone)?"checked":""?> /><!-- 휴대폰결제 -->
                                    </td>                             
								 </tr>
								 
									 <? } ?>
                                <tr>
                                    <th for="biz_addition">충전방식</th>
                                    <td >
										<input type="radio" class="" name="mem_pay_type" value="B" <?=($rs->mem_pay_type=="B") ? "checked" : ""?> />&nbsp;선불&nbsp; &nbsp; 
										<input type="radio" class="" name="mem_pay_type" value="A" <?=($rs->mem_pay_type=="A") ? "checked" : ""?> />&nbsp;후불 &nbsp; &nbsp; 
										<input type="radio" class="" name="mem_pay_type" value="T" <?=($rs->mem_pay_type=="T") ? "checked" : ""?> />&nbsp;정액제&nbsp; &nbsp; 
                                    </td>                             
                                    <th for="biz_addition">최소충전<BR>금액</th>
                                    <td >
										<input type="text" id="mem_min_charge_amt" name="mem_min_charge_amt"
															class="textbox form-control input-width-large" placeholder="관리업체 최소 충전 금액" value="<?=$rs->mem_min_charge_amt?>"/>
                                    </td>                             
								 </tr>
                                <tr>
                                    <th for="biz_addition">정액제 기간</th>
                                    <td colspan="3">
                						<input class="form-control input-width-large " id="id_fixed_from_date" maxlength=15 name="mem_fixed_from_date" placeholder="시작일" value="<?=$rs->mem_fixed_from_date?>" type="text" />~
                						<input class="form-control input-width-large " id="id_fixed_to_date" maxlength=15 name="mem_fixed_to_date" placeholder="종료일" value="<?=$rs->mem_fixed_to_date?>" type="text" />
                		   			</td>                             
								 </tr>
                                <tr>
                                    <th>플러스 친구 매니저 계정</th>
                                    <td colspan="3">
                                        <input class="form-control input-width-large" id="id_mem_pf_manager" maxlength="50" name="mem_pf_manager" placeholder="플러스 친구 매니저 계정" value="<?=$rs->mem_pf_manager ?>" type="text" />
                                    </td>
                                </tr>
							<?	}?>
                            </table>


                            <br/><br/>
                            <div class="widget-header">
                                <h3><i class="icon-edit"></i> 매장 정보 입력</h3>
                            </div>

                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tr>
                                    <th>SMS 발신 전화번호<span class="required">*</span></th>
                                    <td>
                                        <input class="form-control input-width-large required" id="id_phone" maxlength="50" name="phone" placeholder="전화번호" value="<?=($param['key']) ? $rs->mem_phone : ''?>" type="text" />

                                    </td>
                                </tr>
                                <tr>
                                    <th>담당자 이름<span class="required">*</span></th>
                                    <td>
                                        <input class="form-control input-width-large required" id="id_username" maxlength="50" name="username" placeholder="담당자이름" value="<?=($param['key']) ? $rs->mem_nickname : ''?>" type="text" />

                                    </td>
                                </tr>
                                <tr>
                                    <th>담당자 연락처<span class="required">*</span></th>
                                    <td>
                                        <input class="form-control input-width-large required" id="id_emp_phn" maxlength="25" name="emp_phn" placeholder="- 없이 입력해주세요" value="<?=($param['key']) ? $rs->mem_emp_phone : ''?>" type="text" />

                                    </td>
                                </tr>
                                <tr>
                                    <th>담당자 이메일<span class="required">*</span></th>
                                    <td>
                                        <input class="form-control input-width-large required" id="id_email" maxlength="254" style="width:500px !important;" name="email" placeholder="이메일" value="<?=($param['key']) ? $rs->mem_email : ''?>" type="email" />

                                    </td>
                                </tr>
                                <tr>
                                    <th>송금인 이름<span class="required">*</span></th>
                                    <td>
                                        <input class="form-control input-width-large required" id="id_bank_user" maxlength="50" name="bank_user" placeholder="송금인 이름" value="<?=($param['key']) ? $rs->mem_bank_user : ''?>" type="text" />

                                    </td>
                                </tr>
                                <tr>
                                    <th>매장URL(카페,블로그)</th>
                                    <td>
                                        <input class="form-control input-width-large" id="id_phn_free" maxlength="100" style="width:500px !important;" name="phn_free" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=($param['key']) ? $rs->mad_free_hp : ''?>" type="text" />

                                    </td>
                                </tr>
                                <tr>
                                    <th>일 발신한도 지정</th>
                                    <td style="line-height:2.3;"><input class="form-control align-center input-width-large" id="id_day_limit" name="day_limit"  value="<?=number_format($rs->mem_day_limit)?>" step="1"  /> &nbsp; &nbsp;<span class="pt20">※ 0으로 지정하면 제한없음</span></td>
                                </tr>
                                <!-- 2019.02.18 Full Care비용 추가 -->
                                <tr>
                                    <th>Full Care 비용</th>
                                    <td style="line-height:2.3;"><input class="form-control align-center input-width-large" id="id_price_full_care" name="price_full_care"  value="<?=number_format($rs->mad_price_full_care)?>" step="1"  /> 원</td>
                                </tr>
                                <tr>
                                    <th>링크 버튼 이름</th>
                                    <td>
                                        <input class="form-control input-width-large" id="id_linkbtn_name" maxlength="100" style="width:500px !important;" name="linkbtn_name" placeholder="링크버턴명" value="<?=($param['key']) ? $rs->mem_linkbtn_name : '세일전단지'?>" type="text" />
                                    </td>
                                </tr>
                            </table>

									 <br/><br/>
                            <div class="widget-header">
                                <h3><i class="icon-edit"></i> 2차 발신 지정</h3>
										  <br /><font color="red">※ 카카오 알림톡이나 친구톡 발송 실패시 재전송 할 서비스를 지정합니다.</font>
                            </div>
                            <table class="tpl_ver_form" width="100%">
                                <tr>
                                    <td>
										<input type="radio" class="" name="mem_2nd_send" value="NONE" <?=($rs->mem_2nd_send=="" || $rs->mem_2nd_send=="NONE") ? "checked" : ""?> />&nbsp;보내지않음 &nbsp; &nbsp; 
										<input type="radio" class="" name="mem_2nd_send" value="GREEN_SHOT" <?=($rs->mem_2nd_send=="GREEN_SHOT") ? "checked" : ""?> />&nbsp;WEB문자(A) &nbsp; &nbsp; 
										<input type="radio" class="" name="mem_2nd_send" value="NASELF" <?=($rs->mem_2nd_send=="NASELF") ? "checked" : ""?> />&nbsp;WEB문자(B)&nbsp; &nbsp; 
										<input type="radio" class="" name="mem_2nd_send" value="SMART" <?=($rs->mem_2nd_send=="SMART") ? "checked" : ""?> />&nbsp;WEB문자(C)&nbsp; &nbsp; 
										<!-- input type="radio" class="" name="mem_2nd_send" value="015" <?=($rs->mem_2nd_send=="015") ? "checked" : ""?> />&nbsp;015문자&nbsp; &nbsp; 
										<input type="radio" class="" name="mem_2nd_send" value="DOOIT" <?=($rs->mem_2nd_send=="DOOIT") ? "checked" : ""?> />&nbsp;폰문자 &nbsp; &nbsp; -->
                                </tr>
									 </table>

									 <br/><br/>
                            <div class="widget-header">
                                <h3><i class="icon-edit"></i> 발신 단가 적용</h3>
										  <br /><font color="red">※ 발신단가를 0원 이하로 지정하면 해당발신 서비스는 사용하지 않습니다.</font> <font color="blue">(부가세 포함 가격을 입력하세요!)</font>
<? if($rs->mem_level >= 100) {?>
										  <br /><font color="blue">※ 관리자의 <b>폰문자/SMS/LMS/MMS 단가</b>는 발신업체 다중화로 좌측메뉴의 <font color='black'><b>관리자 > 기본정보설정</b></font>에서 수정하셔야 합니다.</font>
<?}?>
                            </div>
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="8%">
                          	<? if($this->member->item('mem_level')>=100) { ?>
							<? if($rs->mem_level >= 50) {  ?>	
                                    <col width="8%">
                                    <col width="8%">
                            <? } else { ?>
                                    <col width="8%">
                            <? } } else {?>
                            		<col width="8%">
                            <? } ?> 
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="8%">
                                   <!--  <col width="8%">
                                    <col width="8%"> -->
                                    <col width="8%">
                                    <col width="8%">
                                    <col width="*">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>알림톡</th>
                                    <th>친구톡<BR>(텍스트)</th>
                                    <th>친구톡<BR>(이미지)</th>
                                    <th>친구톡<BR>(와이드 이미지)</th>
									<? if($this->member->item('mem_level')>=100) { ?>
									<? if($rs->mem_level >= 50) {  ?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>KT, LGT</th>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>SKT</th>		
									<? } else {?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)</th>
									<? } } else {?>
										<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)</th>
									<? } ?>	                                    
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(A)<BR>MMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)<BR>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(B)<BR>MMS</th>
                                    <!-- <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th> -->
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)<BR>SMS</th>
                                    <th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>WEB(C)<BR>MMS</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input class="form-control align-center inline" id="id_price_at" name="price_at" value="<?=($param['key']) ? number_format($rs->mad_price_at, 2) : number_format($this->Biz_model->base_price_at, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_at']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_ft" name="price_ft" value="<?=($param['key']) ? number_format($rs->mad_price_ft, 2) : number_format($this->Biz_model->base_price_ft, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_ft_img" name="price_ft_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_img, 2) : number_format($this->Biz_model->base_price_ft_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_img']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_ft_w_img" name="price_ft_w_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_w_img, 2) : number_format($this->Biz_model->base_price_ft_w_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_w_img']?>"/></td>
                                    <? if($this->member->item('mem_level')>=100) { ?>
									<? if($rs->mem_level >= 50) {  ?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_biz" name="price_grs_biz" value="<?=($param['key']) ? number_format($rs->mad_price_grs_biz, 2) : number_format($this->Biz_model->mad_price_grs_biz, 2) ?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs_biz']?>"/></td>
									<? } else { ?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
									<? } } else {?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
									<? } ?>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_sms" name="price_grs_sms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_sms, 2) : number_format($this->Biz_model->base_price_grs_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_sms']?>" /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_mms" name="price_grs_mms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_mms, 2) : number_format($this->Biz_model->base_price_grs_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_mms']?>" /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas" name="price_nas" value="<?=($param['key']) ? number_format($rs->mad_price_nas, 2) : number_format($this->Biz_model->base_price_nas, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas_sms" name="price_nas_sms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_sms, 2) : number_format($this->Biz_model->base_price_nas_sms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_sms']?>"/></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas_mms" name="price_nas_mms" value="<?=($param['key']) ? number_format($rs->mad_price_nas_mms, 2) : number_format($this->Biz_model->base_price_nas_mms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_nas_mms']?>"/></td>
<!--                                     <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_015" name="price_015" value="<?=($param['key']) ? number_format($rs->mad_price_015, 2) : number_format($this->Biz_model->base_price_015, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_015']?>" /></td>
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_dooit" name="price_dooit" value="<?=($param['key']) ? number_format($rs->mad_price_dooit, 2) : number_format($this->Biz_model->base_price_dooit, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_dooit']?>"/></td> 
                                    <td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_sms" name="price_sms" value="<?=($param['key']) ? number_format($rs->mad_price_sms, 2) : number_format($this->Biz_model->base_price_sms, 2)?>"  type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_sms']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_lms" name="price_lms" value="<?=($param['key']) ? number_format($rs->mad_price_lms, 2) : number_format($this->Biz_model->base_price_lms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_lms']?>"/></td>-->
                                    <td><input class="form-control align-center inline price" id="id_price_smt" name="price_smt"  value="<?=($param['key']) ? number_format($rs->mad_price_smt, 2) : number_format($this->Biz_model->base_price_smt, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_smt_sms" name="price_smt_sms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_sms']?>"/></td>
                                    <td><input class="form-control align-center inline price" id="id_price_smt_mms" name="price_smt_mms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_mms, 2) : number_format($this->Biz_model->base_price_smt_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_mms']?>"/></td>
                                </tr>
										  </tbody>
									 </table>

                            <div class="widget-header" style="display:none">
                                <h3><i class="icon-edit"></i> 충전 가중치 적용</h3>
                            </div>


                            <table class="tpl_ver_form" width="60%" style="display:none">
                                <colgroup>
                                    <col width="30%">
                                    <col width="30%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>금액</th>
                                    <th>가중치(%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[0])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate1" name="rate1" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight1, 2) : number_format($this->Biz_model->weight[0], 2)?>" step="1" type="number" value="0" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[1])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate2" name="rate2" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight2, 2) : number_format($this->Biz_model->weight[1], 2)?>" step="1" type="number" value="0" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[2])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate3" name="rate3" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight3, 2) : number_format($this->Biz_model->weight[2], 2)?>" step="1" type="number" value="3" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[3])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate4" name="rate4" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight4, 2) : number_format($this->Biz_model->weight[3], 2)?>" step="1" type="number" value="5" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[4])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate5" name="rate5" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight5, 2) : number_format($this->Biz_model->weight[4], 2)?>" step="1" type="number" value="7" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[5])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate6" name="rate6" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight6, 2) : number_format($this->Biz_model->weight[5], 2)?>" step="1" type="number" value="10" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[6])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate7" name="rate7" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight7, 2) : number_format($this->Biz_model->weight[6], 2)?>" step="1" type="number" value="20" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[7])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate8" name="rate8" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight8, 2) : number_format($this->Biz_model->weight[7], 2)?>" step="1" type="number" value="20" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[8])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate9" name="rate9" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight9, 2) : number_format($this->Biz_model->weight[8], 2)?>" step="1" type="number" value="20" />%</td>
                                </tr>
                                <tr>
                                    <th>&#8361;<?=number_format($this->Biz_model->charge[9])?></th>
                                    <td><input class="form-control input-width-large inline" id="id_rate10" name="rate10" placeholder="%" value="<?=($param['key']) ? number_format($rs->mad_weight10, 2) : number_format($this->Biz_model->weight[9], 2)?>" step="1" type="number" value="20" />%</td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="mt30 align-center">
                                <a  href="/biz/partner2/lists"><span class="btn">취소</span></a>
                                <button type="submit" class="submit btn btn-custom">저장</button>
                            </div>
                        </form>
						</div>
					</div>
					<div class="footer" style="display: none">
						<div class="cs_info">
							<div class="cs_call">
								<span>1522-7985</span>
							</div>
							<div class="cs_support">
								<!--a href="#"><span class="icon_cs request">1:1문의</span></a-->
								<a target="_blank" href="http://367.co.kr/"><span class="icon_cs remote">원격지원</span></a>
							</div>
						</div>
	
						<div class="info">
							<ul>
								<li>사업자등록번호 : 364-88-00974</li>
								<li>통신판매업 : 신고번호 2018-창원의창-0272호</li>
								<li>대표이사 : 송종근 </li>
								<li class="paragraph">주소 : 경상남도 창원시 의창구 차룡로 48번길 54(팔용동) 경남창원산학융합원 기업연구관 302호</li>
								<li>대표전화 : 1522-79854</li>
								<li>팩스 : 0505-299-0001</li>
								<li>개인정보처리방침</li>
								<li>원격지원</li>
							</ul>
						</div>
						<div class="copyright">
							Copyright © DHN. All rights reserved.
						</div>
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
