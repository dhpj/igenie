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
  <form id="save" method="POST" onsubmit="return check_form()" EncType="Multipart/Form-Data">
	<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
	<input type='hidden' name='actions' value='partner_save' />
	<div class="form_section">
		<div class="inner_tit">
			<h3>매장정보</h3>
			<span class="fr fc_red mg_t20">* 사업자등록증은 필수 입력항목입니다.</span>
		</div>
		<div class="white_box" style="display:inline-block;">
			<div class="busi_box_l">
				<label for="biz_license" class="templet_img_box" style="cursor:pointer;">
					<div id="div_preview_shop">
						<img src="<? if($rs->mem_photo != ""){ ?>/mem_sheet/<?=$rs->mem_photo?><? } ?>" id="biz_license_preview" style="display:block;width:100%">
				   </div>
			   </label>
			  <input type="hidden" name="old_photo" id="old_photo" value="/mem_sheet/<?=$rs->mem_photo?>" style="width:100%; text-align:right;">
			  <input type="file" title="이미지 파일" name="biz_license" id="biz_license" onChange="imgChange(this, 'biz_license_preview');" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
			  <div class="name">사업자등록증(jpg 또는 png파일)</div>
			</div>
			<div class="busi_box_r">
			<ul class="info_contents_wrap" style="overflow: hidden;">
				<li>
					<label class="info_contents_label icon_must">상호<span class="essential">*</span></label>
					<div class="info_contents"><input type="text" id="id_biz_reg_name" name="biz_reg_name" style="width: 300px" value="<?=$rs->mem_biz_reg_name ?>"></div>
				</li>
				<?
				if (empty($rs->mem_biz_reg_no)) {
					$biz_reg_no1 = "";
					$biz_reg_no2 = "";
					$biz_reg_no3 = "";
				} else {
					$biz_reg_no = str_replace("-", "", $rs->mem_biz_reg_no);
					$biz_reg_no1 = substr($biz_reg_no, 0, 3);
					$biz_reg_no2 = substr($biz_reg_no, 3, 2);
					$biz_reg_no3 = substr($biz_reg_no, 5, 5);
				}
				?>
				<li>
					<label class="info_contents_label icon_must">사업자 등록번호<span class="essential">*</span></label>
					<div class="info_contents">
					  <input type="text" maxlength="3" style="width: 60px" id="id_biz_reg_no1" name="biz_reg_no1" value="<?=$biz_reg_no1 ?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');autoTab(this, 'id_biz_reg_no2');"> -
					  <input type="text" maxlength="2" style="width: 40px" id="id_biz_reg_no2" name="biz_reg_no2" value="<?=$biz_reg_no2 ?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');autoTab(this, 'id_biz_reg_no3');" onKeyPress="onlyNumber();"> -
					  <input type="text" maxlength="5" style="width: 100px" id="id_biz_reg_no3" name="biz_reg_no3" value="<?=$biz_reg_no3 ?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
					</div>
				</li>
				<li>
					<label class="info_contents_label icon_must">대표자 이름</label>
					<div class="info_contents"><input type="text" class="input-width-large" id="id_biz_reg_rep_name" name="biz_reg_rep_name" value="<?=$rs->mem_biz_reg_rep_name ?>"></div>
				</li>
				<li>
					<label class="info_contents_label icon_must">사업장소재지</label>
					<div class="info_contents">
					  <input type="text" id="sample6_postcode" name="biz_reg_zip_code" placeholder="우편번호" class="d_form mini" value="<?=$rs->mem_biz_reg_zip_code ?>">
					  <input type="button" onclick="sample6_execDaumPostcode()" value="우편번호 찾기" class="d_btn"><br>
					  <input type="text" id="sample6_address" name="biz_reg_add1" placeholder="주소" class="d_form large" value="<?=$rs->mem_biz_reg_add1 ?>"><br>
					  <input type="text" id="sample6_detailAddress" name="biz_reg_add2" placeholder="상세주소" class="d_form" value="<?=$rs->mem_biz_reg_add2 ?>">
					  <input type="text" id="sample6_extraAddress" name="biz_reg_add3" placeholder="참고항목" class="d_form mini" value="<?=$rs->mem_biz_reg_add3 ?>">
					</div>
				</li>
				<li>
					<label class="info_contents_label icon_must">사업자 구분</label>
					<div class="info_contents">
						<select id="id_biz_reg_div" name="biz_reg_div">
							<option value="" <?=$rs->mem_biz_reg_div=='' ? 'selected' : ''?>>선택하세요</option>
							<option value="p" <?=$rs->mem_biz_reg_div=='p' ? 'selected' : ''?>>개인사업자</option>
							<option value="c" <?=$rs->mem_biz_reg_div=='c' ? 'selected' : ''?>>법인사업자</option>
						</select>
					</div>
				</li>
				<li>
					<label class="info_contents_label">업태</label>
					<div class="info_contents"><input type="text" style="width: 300px" id="id_biz_reg_biz" name="biz_reg_biz" value="<?=$rs->mem_biz_reg_biz ?>"></div>
				</li>
				<li>
					<label class="info_contents_label">종목</label>
					<div class="info_contents"><input type="text" style="width: 300px" id="id_biz_reg_sector" name="biz_reg_sector" value="<?=$rs->mem_biz_reg_sector ?>"></div>
				</li>
				<li>
					<label class="info_contents_label">SMS 발신 전화번호<span class="essential">*</span></label>
					<div class="info_contents">
						<input class="input-width-large required" id="id_phone" maxlength="50" name="phone" placeholder="SMS 발신 전화번호" value="<?=($param['key']) ? $rs->mem_phone : ''?>" type="text" />
					</div>
				</li>
				<li>
					<label class="info_contents_label">담당자 이름<span class="essential">*</span></label>
					<div class="info_contents">
						<input class="input-width-large required" id="id_username" maxlength="50" name="username" placeholder="담당자 이름" value="<?=($param['key']) ? $rs->mem_nickname : ''?>" type="text" />
					</div>
				</li>
				<li>
					<label class="info_contents_label">담당자 연락처<span class="essential">*</span></label>
					<div class="info_contents">
						<input class="input-width-large required"  style="width: 250px" id="id_emp_phn" maxlength="25" name="emp_phn" placeholder="담당자 연락처 ( - 없이 입력해주세요.)" value="<?=($param['key']) ? $rs->mem_emp_phone : ''?>" type="text" />
					</div>
				</li>
				<li>
					<label class="info_contents_label">담당자 이메일<span class="essential">*</span></label>
					<div class="info_contents">
						<input class="input-width-large required" id="id_email" maxlength="254" style="width:500px !important;" name="email" placeholder="이메일" value="<?=($param['key']) ? $rs->mem_email : ''?>" type="email" />
					</div>
				</li>
			</ul>
		</div>
		</div>
	</div>
	<div class="form_section">
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
					<input class="input-width-large required"  id="id_userid" maxlength="20" name="userid" placeholder="아이디" type="text" />
					<!--<button id="check_double_id" class="btn h34" onclick="check_duplicated_account('id_userid')">중복 확인</button>-->
					<input type="button" id="check_double_id" class="btn h34" style="margin-left:5px;cursor:pointer;" onclick="check_duplicated_account('id_userid')" value="중복 확인">
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
					<label class="info_contents_label icon_must">비밀번호<? if(!$param['key']) { ?><span class="essential">*</span><? } ?></label>
					<div class="info_contents">
						<input class="input-width-large <?php if(!$param['key']) {?>required<?}?>" id="id_password" maxlength="128" name="password" placeholder="비밀번호" type="password" />
						<? if($param['key'] && $this->member->item('mem_level')>=100){ ?>
						<!--<button class="btn_st2_small" style="margin-left:5px;" onclick="chk_pass_visible();">비밀번호 조회</button>-->
						<input type="button" class="btn_st2_small" style="margin-left:5px;cursor:pointer;" onclick="chk_pass_visible()" value="비밀번호 조회">
						<span id="id_pass_visible" class="pass_visible" style="margin-left:5px;"></span><?//비밀번호 조회 리턴값 영역?>
						<? } ?>
						<text id="id_password_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 새로운 비밀번호를 입력해주세요.
						</text>
						<text id="id_password_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
						<span class="help-block">새로운 비밀번호를 입력해주세요.</span>
					</div>
				</li>
				<li>
					<label class="info_contents_label icon_must">비밀번호 확인<? if(!$param['key']) { ?><span class="essential">*</span><? } ?></label>
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
				<li style="display:none">
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
				</li>
				<li style="display:none">
					<label class="info_contents_label icon_must">결재방법</label>
					<div class="info_contents form_check">
						<input id="id_mem_payment_bank" name="mem_payment_bank" type="checkbox" <?=($rs->mem_payment_bank)?"checked":""?> /><label for="id_mem_payment_bank">계좌이체</label>
						<input id="id_mem_payment_card" name="mem_payment_card" type="checkbox" <?=($rs->mem_payment_card)?"checked":""?> /><label for="id_mem_payment_card">신용카드</label>
						<input id="id_mem_payment_realtime" name="mem_payment_realtime" type="checkbox" style="display: none" <?=($rs->mem_payment_realtime)?"checked":""?> />
						<input id="id_mem_payment_vbank" name="mem_payment_vbank" type="checkbox" <?=($rs->mem_payment_vbank)?"checked":""?> /><label for="id_mem_payment_vbank">무통장입금</label>
						<input id="id_mem_payment_phone" name="mem_payment_phone" type="checkbox" style="display: none" <?=($rs->mem_payment_phone)?"checked":""?> />
					</div>
				</li>
				<li>
					<label class="info_contents_label">계약형태<span class="essential">*</span></label>
					<div class="info_contents">
						<input type="radio" id="mem_contract_type_S" name="mem_contract_type" value="S" <?=($rs->mem_contract_type=="S" or $rs->mem_contract_type == "") ? "checked" : ""?> /><label for="mem_contract_type_S"> 스텐다드</label>
						<input type="radio" id="mem_contract_type_P" name="mem_contract_type" value="P" style="margin-left:10px;" <?=($rs->mem_contract_type=="P") ? "checked" : ""?> /><label for="mem_contract_type_P"> 프리미엄 (쇼핑몰 사용)</label>
					</div>
				</li>
				<li>
					<label class="info_contents_label">충전방식<span class="essential">*</span></label>
					<div class="info_contents">
						<input type="radio" id="charging_type-1" name="mem_pay_type" value="B" onClick="divSwow('id_fixed_area', 'none');" <?=($rs->mem_pay_type=="B" or $rs->mem_pay_type == "") ? "checked" : ""?> /><label for="charging_type-1"> 선불</label>
						<input type="radio" id="charging_type-2" name="mem_pay_type" value="A" style="margin-left:10px;" onClick="divSwow('id_fixed_area', 'none');" <?=($rs->mem_pay_type=="A") ? "checked" : ""?> /><label for="charging_type-2"> 후불</label>
						<span style="display:none;">
							<input type="radio" id="charging_type-3" name="mem_pay_type" value="T" onClick="divSwow('id_fixed_area', '');$('#id_fixed_from_date').focus();" <?=($rs->mem_pay_type=="T") ? "checked" : ""?> /><label for="charging_type-3"> 정액제</label>
						</span>
					</div>
				</li>
				<li id="id_fixed_area" style="display:<?=($rs->mem_pay_type=="T") ? "" : "none"?>">
					<label class="info_contents_label icon_must">정액제 기간<span class="essential">*</span></label>
					<div class="info_contents">
						<input class="input-width-large " id="id_fixed_from_date" maxlength=15 name="mem_fixed_from_date" placeholder="시작일" value="<?=$rs->mem_fixed_from_date?>" type="text" /> ~
						<input class="input-width-large " id="id_fixed_to_date" maxlength=15 name="mem_fixed_to_date" placeholder="종료일" value="<?=$rs->mem_fixed_to_date?>" type="text" />
					</div>
				</li>
				<li>
					<label class="info_contents_label">일 발신한도 지정</label>
					<div class="info_contents">
						<input class="tr input-width-large" id="id_day_limit" name="day_limit"  value="<?=number_format($rs->mem_day_limit)?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"> &nbsp; &nbsp;<span class="pt20">※ 0으로 지정하면 제한없음</span>
					</div>
				</li>
				<li style="display:none">
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
					<label class="info_contents_label">계약 기간</label>
					<div class="info_contents">
						<input id="id_cont_from_date" maxlength=15 name="mem_cont_from_date" placeholder="계약 시작일" value="<?=($rs->mem_cont_from_date != "0000-00-00") ? $rs->mem_cont_from_date : ''?>" type="text" /> ~ <input id="id_cont_to_date" maxlength=15 name="mem_cont_to_date" placeholder="계약 종료일" value="<?=($rs->mem_cont_to_date != "0000-00-00") ? $rs->mem_cont_to_date : ''?>" type="text" />
					</div>
				</li>
				<li>
					<label class="info_contents_label">계약 해지</label>
					<div class="info_contents">
						<input type="radio" id="mem_cont_cancel_yn_Y" name="cont_cancel_yn" value="Y" onClick="divSwow('id_cont_cancel_date', '');" <?=($rs->mem_cont_cancel_yn=="Y") ? "checked" : ""?> /><label for="mem_cont_cancel_yn_Y"> 설정함</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" id="mem_cont_cancel_yn_N" name="cont_cancel_yn" value="N" onClick="divSwow('id_cont_cancel_date', 'none');" <?=($rs->mem_cont_cancel_yn=="N" or $rs->mem_cont_cancel_yn == "") ? "checked" : ""?> /><label for="mem_cont_cancel_yn_N"> 설정안함</label>
					</div>
				</li>
				<li id="id_cont_cancel_date" style="display:<?=($rs->mem_cont_cancel_yn=="Y") ? "" : "none"?>;">
					<label class="info_contents_label">정산처리 일자</label>
					<div class="info_contents">
						<input id="id_mem_cont_cancel_date" maxlength="10" name="mem_cont_cancel_date" placeholder="정산처리 마지막일" value="<?=($rs->mem_cont_cancel_date != "0000-00-00") ? $rs->mem_cont_cancel_date : ''?>" type="text" />
					</div>
				</li>
				<li>
					<label class="info_contents_label">계약서 파일</label>
					<div class="info_contents">
						<div style="display: inline-block">
							<input type="hidden" id="id_old_contract_pdf" name="old_contract_pdf" value="<?=$rs->mem_contract_filepath?>" />
							<input type="text" name="contract_pdf_filename" id="id_contract_pdf_filename" style="width: 300px;" placeholder="파일 업로드" readonly value="<?=$rs->mem_contract_filename?>">
							<label class="file2"><input type="file" name="contract_pdf_file" id="contract_pdf_file" accept=".pdf"></label>
						</div>
						<? if ($rs->mem_contract_filepath != "") { ?>
							<span class="btn_st2_small" style="cursor:pointer; margin-left:5px;" onclick="view_contract_pdf()">계약서보기</span>
						<? } ?>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- 풀케어 서비스 [S] -->
	<div class="form_section" style="display:none;">
		<div class="inner_tit mg_t30">
			<h3>풀케어 서비스 등록</h3>
		</div>
		<div class="white_box">
			<ul class="info_contents_wrap">
				<li>
					<label class="info_contents_label icon_must">풀케어 서비스</label>
					<div class="info_contents">
						<input type="radio" id="mem_full_care_yn_Y" name="mem_full_care_yn" value="Y" onClick="divSwow('id_full_care', '');" <?=($rs->mem_full_care_yn=="Y") ? "checked" : ""?> /><label for="mem_full_care_yn_Y"> 설정함</label>&nbsp;&nbsp;&nbsp;
						<input type="radio" id="mem_full_care_yn_N" name="mem_full_care_yn" value="N" onClick="divSwow('id_full_care', 'none');" <?=($rs->mem_full_care_yn=="N" or $rs->mem_full_care_yn == "") ? "checked" : ""?> /><label for="mem_full_care_yn_N"> 설정안함</label>
					</div>
				</li>
			</ul>
			<ul class="info_contents_wrap" id="id_full_care" style="display:<?=$rs->mem_full_care_yn=='Y' ? 'block' : 'none' ?>;">
				<li>
					<label class="info_contents_label icon_must">풀케어 요금제<span class="essential">*</span></label>
					<div class="info_contents">
						<select id="id_mem_full_care_type" name="mem_full_care_type">
							<option value="" <?=$rs->mem_full_care_type=='' ? 'selected' : ''?>>풀케어 요금제를 선택하세요</option>
							<option value="a" <?=$rs->mem_full_care_type=='a' ? 'selected' : ''?>>풀케어 A</option>
							<option value="b" <?=$rs->mem_full_care_type=='b' ? 'selected' : ''?>>풀케어 B</option>
							<option value="c" <?=$rs->mem_full_care_type=='c' ? 'selected' : ''?>>풀케어 C</option>
						</select>
					</div>
				</li>
				<li>
					<label class="info_contents_label icon_must">서비스 요금<span class="essential">*</span></label>
					<div class="info_contents">
						<input class="won tr" type="text" id="id_price_full_care" name="price_full_care" value="<?= number_format($rs->mad_price_full_care) ?>" placeholder="" onkeyup="numberWithCommas(this, this.value)">
					</div>
				</li>
				<li>
					<label class="info_contents_label icon_must">서비스 횟수<span class="essential">*</span></label>
					<div class="info_contents">
						<select id="id_mem_full_care_max" name="mem_full_care_max">
							<option value="0" <?=$rs->mem_full_care_max==0 ? 'selected' : ''?>>월간 최대 이용횟수</option>
							<option value="1" <?=$rs->mem_full_care_max==1 ? 'selected' : ''?>>월 1회</option>
							<option value="2" <?=$rs->mem_full_care_max==2 ? 'selected' : ''?>>월 2회</option>
							<option value="3" <?=$rs->mem_full_care_max==3 ? 'selected' : ''?>>월 3회</option>
							<option value="4" <?=$rs->mem_full_care_max==4 ? 'selected' : ''?>>월 4회</option>
							<option value="5" <?=$rs->mem_full_care_max==5 ? 'selected' : ''?>>월 5회</option>
							<option value="6" <?=$rs->mem_full_care_max==6 ? 'selected' : ''?>>월 6회</option>
							<option value="7" <?=$rs->mem_full_care_max==7 ? 'selected' : ''?>>월 7회</option>
							<option value="8" <?=$rs->mem_full_care_max==8 ? 'selected' : ''?>>월 8회</option>
							<option value="9" <?=$rs->mem_full_care_max==9 ? 'selected' : ''?>>월 9회</option>
							<option value="10" <?=$rs->mem_full_care_max==10 ? 'selected' : ''?>>월 10회</option>
							<option value="11" <?=$rs->mem_full_care_max==11 ? 'selected' : ''?>>월 11회</option>
							<option value="12" <?=$rs->mem_full_care_max==12 ? 'selected' : ''?>>월 12회</option>
							<option value="13" <?=$rs->mem_full_care_max==13 ? 'selected' : ''?>>월 13회</option>
							<option value="14" <?=$rs->mem_full_care_max==14 ? 'selected' : ''?>>월 14회</option>
							<option value="15" <?=$rs->mem_full_care_max==15 ? 'selected' : ''?>>월 15회</option>
							<option value="16" <?=$rs->mem_full_care_max==16 ? 'selected' : ''?>>월 16회</option>
							<option value="17" <?=$rs->mem_full_care_max==17 ? 'selected' : ''?>>월 17회</option>
							<option value="18" <?=$rs->mem_full_care_max==18 ? 'selected' : ''?>>월 18회</option>
							<option value="19" <?=$rs->mem_full_care_max==19 ? 'selected' : ''?>>월 19회</option>
							<option value="20" <?=$rs->mem_full_care_max==20 ? 'selected' : ''?>>월 20회</option>
							<option value="21" <?=$rs->mem_full_care_max==21 ? 'selected' : ''?>>월 21회</option>
							<option value="22" <?=$rs->mem_full_care_max==22 ? 'selected' : ''?>>월 22회</option>
							<option value="23" <?=$rs->mem_full_care_max==23 ? 'selected' : ''?>>월 23회</option>
							<option value="24" <?=$rs->mem_full_care_max==24 ? 'selected' : ''?>>월 24회</option>
							<option value="25" <?=$rs->mem_full_care_max==25 ? 'selected' : ''?>>월 25회</option>
							<option value="26" <?=$rs->mem_full_care_max==26 ? 'selected' : ''?>>월 26회</option>
							<option value="27" <?=$rs->mem_full_care_max==27 ? 'selected' : ''?>>월 27회</option>
							<option value="28" <?=$rs->mem_full_care_max==28 ? 'selected' : ''?>>월 28회</option>
							<option value="29" <?=$rs->mem_full_care_max==29 ? 'selected' : ''?>>월 29회</option>
							<option value="30" <?=$rs->mem_full_care_max==30 ? 'selected' : ''?>>월 30회</option>
						</select>
					</div>
				</li>
				<li>
					<label class="info_contents_label icon_must">풀케어 기간</label>
					<div class="info_contents">
						<input id="id_full_care_from_date" maxlength=15 name="mem_full_care_from_date" placeholder="풀케어 시작일" value="<?=($rs->mem_full_care_from_date != "0000-00-00") ? $rs->mem_full_care_from_date : ''?>" type="text" /> ~ <input id="id_full_care_to_date" maxlength=15 name="mem_full_care_to_date" placeholder="풀케어 종료일" value="<?=($rs->mem_full_care_to_date != "0000-00-00") ? $rs->mem_full_care_to_date : ''?>" type="text" />
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- 풀케어 서비스 [E] -->
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
				<li>
					<label class="info_contents_label icon_must">포스연동 ID</label>
					<div class="info_contents">
						<input class="input-width-large" id="id_mem_pos_id" maxlength="50" name="mem_pos_id" placeholder="포스연동 ID" value="<?=$rs->mem_pos_id ?>" type="text" />
					</div>
				</li>
				<li style="display:none">
					<label class="info_contents_label">매장URL(카페,블로그)</label>
					<div class="info_contents">
						<input class="input-width-large" id="id_phn_free" maxlength="100" style="width:500px !important;" name="phn_free" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=($param['key']) ? $rs->mad_free_hp : ''?>" type="text" />
					</div>
				</li>
				<li style="display:none">
					<label class="info_contents_label icon_must">카카오채널 매니저 계정</label>
					<div class="info_contents">
						<input class="input-width-large" id="id_mem_pf_manager" maxlength="50" name="mem_pf_manager" placeholder="플러스 친구 매니저 계정" value="<?=$rs->mem_pf_manager ?>" type="text" />
					</div>
				</li>
				<li style="display:none">
					<label class="info_contents_label icon_must">플러스 친구수(카카오 기준)</label>
					<div class="info_contents">
						<input class="form-control  input-width-large" id="id_mem_pf_cnt" name="mem_pf_cnt" value="<?=$rs->mem_pf_cnt ?>" type="number" />
					</div>
				</li>
				<li style="display:none">
					<label class="info_contents_label">링크 버튼 이름</label>
					<div class="info_contents">
						<input class="input-width-large" id="id_linkbtn_name" maxlength="100" style="width:500px !important;" name="linkbtn_name" placeholder="링크버턴명" value="<?=($param['key']) ? $rs->mem_linkbtn_name : '세일전단지'?>" type="text" />
					</div>
				</li>
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
		<div class="white_box" style="display:inline-block;">
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

			<div class="table_stat" style="width:600px;">
				<table>
					<colgroup>
						<col width="150px">
						<col width="150px">
						<col width="150px">
						<col width="150px">
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
							<td><input class="tr input_w130" id="id_price_at" name="price_at" value="<?=($param['key']) ? number_format($rs->mad_price_at, 2) : number_format($this->Biz_model->base_price_at, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_at']?>"/></td>
							<td><input class="tr input_w130" id="id_price_ft" name="price_ft" value="<?=($param['key']) ? number_format($rs->mad_price_ft, 2) : number_format($this->Biz_model->base_price_ft, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft']?>"/></td>
							<td><input class="tr input_w130" id="id_price_ft_img" name="price_ft_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_img, 2) : number_format($this->Biz_model->base_price_ft_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_img']?>"/></td>
							<td><input class="tr input_w130" id="id_price_ft_w_img" name="price_ft_w_img" value="<?=($param['key']) ? number_format($rs->mad_price_ft_w_img, 2) : number_format($this->Biz_model->base_price_ft_w_img, 2)?>" type="number" step="any" min="<?=$parent_amt['mad_price_ft_w_img']?>"/></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="table_stat" style="width:<? if($this->member->item('mem_level')>=100) { ?>450px<? } else { ?>600px<? } ?>;">
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
							<td style="line-height:25pt;"><input class="tr input_w130" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
							<td style="line-height:25pt;"><input class="tr input_w130" id="id_price_grs_biz" name="price_grs_biz" value="<?=($param['key']) ? number_format($rs->mad_price_grs_biz, 2) : number_format($this->Biz_model->mad_price_grs_biz, 2) ?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs_biz']?>"/></td>
							<? } else { ?>
							<td style="line-height:25pt;"><input class="tr input_w130" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
							<? } } else {?>
							<td style="line-height:25pt;"><input class="tr input_w130" id="id_price_grs" name="price_grs" value="<?=($param['key']) ? number_format($rs->mad_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_grs']?>"/></td>
							<? } ?>
							<td style="line-height:25pt;"><input class="tr input_w130" id="id_price_grs_sms" name="price_grs_sms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_sms']?>" /></td>
							<td style="line-height:25pt;"><input class="tr input_w130" id="id_price_grs_mms" name="price_grs_mms" value="<?=($param['key']) ? number_format($rs->mad_price_grs_mms, 2) : number_format($this->Biz_model->base_price_grs_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>min="<?=$parent_amt['mad_price_grs_mms']?>" /></td>
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
			<div class="table_stat" style="width:450px;">
				<table>
					<colgroup>
						<col width="34%">
						<col width="33%">
						<col width="33%">
					</colgroup>
					<thead>
						<tr>
							<th colspan="3" style="background: #757575;">WEB C</th>
						</tr>
						<tr>
							<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>LMS</th>
							<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>SMS</th>
							<th<?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?>>MMS</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="tr input_w130" id="id_price_smt" name="price_smt"  value="<?=($param['key']) ? number_format($rs->mad_price_smt, 2) : number_format($this->Biz_model->base_price_smt, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt']?>"/></td>
							<td><input class="tr input_w130" id="id_price_smt_sms" name="price_smt_sms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_sms']?>"/></td>
							<td><input class="tr input_w130" id="id_price_smt_mms" name="price_smt_mms"  value="<?=($param['key']) ? number_format($rs->mad_price_smt_mms, 2) : number_format($this->Biz_model->base_price_smt_mms, 2)?>" type="number" step="any" <?=($rs->mem_level >= 100) ? " style='color:pink;'" : ""?> min="<?=$parent_amt['mad_price_smt_mms']?>"/></td>
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
  </form>
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
		//alert("document");
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

		//id_fixed.정액제 날짜 클릭시, id_cont.계약기잔, id_full_care.풀케어 기간, id_mem_cont.정산처리 일자
		$("#id_fixed_from_date, #id_fixed_to_date, #id_cont_from_date, #id_cont_to_date, #id_full_care_from_date, #id_full_care_to_date, #id_mem_cont_cancel_date").datepicker({
			format:'yyyy-mm-dd'	,
			todayBtn:"linked",
			language:"kr",
			autoclose:true,
			todayHighlight:true
		});

		$("#contract_pdf_file").change(function(e){
			var fileValue = $(this).val().split("\\");
			var fileName = fileValue[fileValue.length-1];
			$("#id_contract_pdf_filename").val(fileName);
		});
	});

	/*
	$("#biz_license").change(function() {
		var files = this.files;
		var reader = new FileReader();
		reader.onloadend = function () {
			$('#id_info_img').css('background-image', 'url(' + reader.result + ')');
		};
		reader.readAsDataURL(files[0]);
	});
	*/

	function methodChange() {
		//alert("methodChange");
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
		//alert("check_price");
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
		<? if($rs->mem_photo == "") { ?>
		if($("#biz_license").val() == "") {
			alert("사업자등록증 이미지를 업로드 해주세요.");
			$(window).scrollTop(0);
			return false;
		}
		<? } //if($rs->mem_photo == "") { ?>
		if($("#id_biz_reg_name").val() == "") {
			alert("상호를 입력하여 주세요.");
			$("#id_biz_reg_name").focus();
			return false;
		}
		if($("#id_biz_reg_no1").val() == "") {
			alert("사업자 등록번호 1번째 자리를 입력하여 주세요.");
			$("#id_biz_reg_no1").focus();
			return false;
		}
		if($("#id_biz_reg_no1").val().length != 3) {
			alert("사업자 등록번호 1번째 자리는 3자를 입력하여 주세요.");
			$("#id_biz_reg_no1").focus();
			return false;
		}
		if($("#id_biz_reg_no2").val() == "") {
			alert("사업자 등록번호 2번째 자리를 입력하여 주세요.");
			$("#id_biz_reg_no2").focus();
			return false;
		}
		if($("#id_biz_reg_no2").val().length != 2) {
			alert("사업자 등록번호 2번째 자리는 2자를 입력하여 주세요.");
			$("#id_biz_reg_no2").focus();
			return false;
		}
		if($("#id_biz_reg_no3").val() == "") {
			alert("사업자 등록번호 3번째 자리를 입력하여 주세요.");
			$("#id_biz_reg_no3").focus();
			return false;
		}
		if($("#id_biz_reg_no3").val().length != 5) {
			alert("사업자 등록번호 3번째 자리는 5자를 입력하여 주세요.");
			$("#id_biz_reg_no3").focus();
			return false;
		}
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
		var mem_pay_type = $(":input:radio[name=mem_pay_type]:checked").val(); //충전방식 (B.선불, A.후불, T.정액제)
		//alert("mem_pay_type : "+ mem_pay_type); return false;
		if(mem_pay_type == "T"){
			if($("#id_fixed_from_date").val().length < 10) {
				alert("정액제 시작일이 입력되지 않았거나 올바른 날짜 형식이 아닙니다.");
				$("#id_fixed_from_date").focus();
				return false;
			}
			if($("#id_fixed_to_date").val().length < 10) {
				alert("정액제 종료일이 입력되지 않았거나 올바른 날짜 형식이 아닙니다.");
				$("#id_fixed_to_date").focus();
				return false;
			}
			if($("#id_fixed_from_date").val() > $("#id_fixed_to_date").val()) {
				alert("정액제 종료일이 시작일 이전 입니다.");
				$("#id_fixed_to_date").focus();
				return false;
			}
		}
		var mem_full_care_yn = $(":input:radio[name=mem_full_care_yn]:checked").val(); //풀케어 사용유무
		//alert("mem_full_care_yn : "+ mem_full_care_yn); return false;
		if(mem_full_care_yn == "Y") {
			if($("#id_mem_full_care_type").val() == "") {
				alert("풀케어 요금제를 선택해 주세요.");
				$('#id_mem_full_care_type').focus();
				return false;					  
			}
			//alert("id_price_full_care : "+ $("#id_price_full_care").val()); return false;
			if($("#id_price_full_care").val() == "") {
				alert("서비스 요금를 입력해 주세요.");
				$('#id_price_full_care').focus();
				return false;					  
			}
			if (Number($("#id_price_full_care").val().replace(/,/gi,'')) <= 0) {
				alert("서비스 요금를 0보다 큰수를 입력해 주세요.");
				$('#id_price_full_care').focus();
				return false;					  
			}
			if($("#id_mem_full_care_max").val() == "0") {
				alert("서비스 횟수를 선택해 주세요.");
				$('#id_mem_full_care_max').focus();
				return false;					  
			}
	  }
		<? if($param['key'] != ""){ ?>
		if(!confirm("수정 하시겠습니까?")){
			return false;
		}
		<? } //if($param['key'] != ""){ ?>
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

	<? if($param['key'] && $this->member->item('mem_level')>=100){ ?>
	//비밀번호 조회
	function chk_pass_visible(){
		var mid = "<?=$rs->mem_id?>";
		var userid = "<?=$rs->mem_userid?>";
		$.ajax({
			url: '/biz/partner/pass_visible',
			type: "POST",
			data: {
				"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
				mid : mid,
				userid : userid
			},
			success: function (json) {
				var pass_msg = json.pass;
				if(pass_msg == ""){
					document.getElementById('id_pass_visible').innerHTML = "등록된 비밀번호가 없습니다.";
				}else{
					document.getElementById('id_pass_visible').innerHTML = pass_msg;
					setTimeout(function() {
						document.getElementById('id_pass_visible').innerHTML = "";
					}, (1000*60)); //60초 후 비밀번호 빈값 처리
				}
			},
			error: function (data, status, er) {
				document.getElementById('id_pass_visible').innerHTML = '처리중 오류가 발생하였습니다. 관리자에게 문의하세요';
			}
		});
		return true;
	}
	<? } ?>

	//div 영역 보기
	function divSwow(id, val){
		document.getElementById(id).style.display = val;
	}

	//이미지 선택 클릭시
	function imgChange(obj, id){
		//alert("id : "+ id +", obj.value : "+ obj.value); return;
		if(obj.value.length > 0) {
			//alert("obj.value : "+ obj.value);
			if (obj.files && obj.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#"+ id).attr("src", e.target.result);
				}
				reader.readAsDataURL(obj.files[0]);
			}
		}
	}

	//입력창 자동으로 탭 넘기기
	function autoTab(obj, next_id) {
		//alert("obj.value.length : "+ obj.value.length +", obj.maxLength : "+ obj.maxLength);
		if(obj.value.length == obj.maxLength) {
			$("#"+ next_id).focus();
		}
	}

	// 숫자만 입력받고, ","값 입력하기
	function numberWithCommas(obj, value) {
		value = value.replace(/[^0-9]/g, '');		// 입력값이 숫자가 아니면 공백
		value = value.replace(/,/g, '');			// ,값 공백처리
		$(obj).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	}

	//계약서 보기
	function view_contract_pdf() {
		var pdf_view_url = "/biz/partner/view_contract_pdf?<?=$rs->mem_userid?>";
		window.open(pdf_view_url, "", "_blank");
		return false;
	}
</script>
