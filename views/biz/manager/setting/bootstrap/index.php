<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
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
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	관리자 : 기본 정보 설정
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3><i class="icon-edit"></i> 정산용 단가 지정</h3>
		</div>
		<div class="inner_content">
			<div class="inner_notice">
				※ 관리자 정산에 사용하기 위한 단가 입니다. <font color="blue">(V.A.T포함 가격을 입력하세요!)
				
			</div>
	<div class="box-table">
	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
	echo form_open(current_full_url(), $attributes);
	?>
	<input type="hidden" name="actions" value="save" />
	<input type="hidden" name="seq" value="<?=$rs->wst_mem_id?>" />
	<div class="box-table">
		 <table class="tpl_ver_form" width="100%">
			  <colgroup>
					<col width="14.2857%">
					<col width="*">
					<col width="14.2857%">
					<col width="14.2857%">
					<col width="14.2857%">
					<col width="14.2857%">
					<col width="14.2857%">
					<col width="10%">
			  </colgroup>
			  <thead>
			  <tr>
					<th>업체코드</th>
					<th>업체명</th>
					<th>SMS</th>
					<th>LMS</th>
					<th>LMS(Biz GreenShot용)</th>
					<th>MMS</th>
					<th>수정</th>
			  </tr>
			  </thead>
			  <tbody>
<?$cnt = 0;
foreach($price as $p) { $cnt++;?>		
			  <tr>
					<td><input class="form-control align-center inline price adm_agent" id="adm_agent<?=$cnt?>" name="adm_agent[]" placeholder="" value="<?=$p->adm_agent?>" step="1" type="text" readonly="true" /></td>
					<td><input class="form-control align-center inline price adm_company" id="adm_company<?=$cnt?>" name="adm_company[]" placeholder="" value="<?=$p->adm_company?>" step="1" type="text" /></td>
					<td><input class="form-control align-center inline price adm_sms" id="adm_sms<?=$cnt?>" name="adm_sms[]" placeholder="0" value="<?=$p->adm_price_sms?>" step="1" type="decimal" /></td>
					<td><input class="form-control align-center inline price adm_lms" id="adm_lms<?=$cnt?>" name="adm_lms[]" placeholder="0" value="<?=$p->adm_price_lms?>" step="1" type="decimal" /></td>
					<td><input class="form-control align-center inline price adm_phn" id="adm_phn<?=$cnt?>" name="adm_phn[]" placeholder="0" value="<?=$p->adm_price_phn?>" step="1" type="decimal" /></td>
					<td><input class="form-control align-center inline price adm_mms" id="adm_mms<?=$cnt?>" name="adm_mms[]" placeholder="0" value="<?=$p->adm_price_mms?>" step="1" type="decimal" /></td>
					<td><button class="btn price_edit" type="button"><i class="icon-pencil"></i>수정</button></td>
			  </tr>
<?}?>
			  </tbody>
		 </table>

		 <br/><br/>
		 <div class="widget-header">
			  <h3><i class="icon-edit"></i> 기본 발신 단가 적용 : 파트너 등록시 기본 표시되는 단가입니다.</h3>
			  <br /><font color="red">※ 발신단가를 0원 이하로 지정하면 해당발신 서비스는 사용하지 않습니다.</font> <font color="blue">(V.A.T포함 가격을 입력하세요!)</font>
		 </div>
		 <table class="tpl_ver_form" width="100%">
			  <colgroup>
                    <col width="8%">
                    <col width="8%">
                    <col width="8%">
                    <col width="7%">
                    <col width="7%">
                    <col width="8%">
                    <col width="8%">
                    <col width="7%">
                    <col width="8%">
                    <col width="8%">
                    <col width="7%">
                    <col width="8%">
                    <col width="*">
			  </colgroup>
			  <thead>
			  <tr>
					<th>알림톡</th>
					<th>친구톡<BR>(텍스트)</th>
					<th>친구톡<BR>(이미지)</th>
					<th>WEB(A)<BR>LG,KT</th>
					<th>WEB(A)<BR>SKT</th>
					<th>WEB(A)<BR>SMS</th>
					<th>WEB(A)<BR>MMS</th>
					<th>WEB(B)</th>
					<th>WEB(B)<BR>SMS</th>
					<th>WEB(B)<BR>MMS</th>
					<th>WEB(C)</th>
					<th>WEB(C)<BR>SMS</th>
					<th>WEB(C)<BR>MMS</th>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
					<td><input class="form-control align-center inline price" id="id_price_at" name="price_at" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_at, 2) : number_format($this->Biz_model->base_price_at, 2)?>" step="1" type="decimal" value="0" /></td>
					<td><input class="form-control align-center inline price" id="id_price_ft" name="price_ft" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_ft, 2) : number_format($this->Biz_model->base_price_ft, 2)?>" step="1" type="decimal" value="0" /></td>
					<td><input class="form-control align-center inline price" id="id_price_ft_img" name="price_ft_img" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_ft_img, 2) : number_format($this->Biz_model->base_price_ft_img, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs" name="price_grs" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_grs, 2) : number_format($this->Biz_model->base_price_grs, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_biz" name="price_grs_biz" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_grs_biz, 2) : number_format($this->Biz_model->base_price_grs_biz, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_sms" name="price_grs_sms" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_grs_sms, 2) : number_format($this->Biz_model->base_price_grs_sms, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_grs_mms" name="price_grs_mms" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_grs_mms, 2) : number_format($this->Biz_model->base_price_grs_mms, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas" name="price_nas" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_nas, 2) : number_format($this->Biz_model->base_price_nas, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas_sms" name="price_nas_sms" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_nas_sms, 2) : number_format($this->Biz_model->base_price_nas_sms, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_nas_mms" name="price_nas_mms" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_nas_mms, 2) : number_format($this->Biz_model->base_price_nas_mms, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_smt" name="price_smt" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_smt, 2) : number_format($this->Biz_model->base_price_smt, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_smt_sms" name="price_smt_sms" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_smt_sms, 2) : number_format($this->Biz_model->base_price_smt_sms, 2)?>" step="1" type="decimal" value="0" /></td>
					<td style="line-height:25pt;"><input class="form-control align-center inline price" id="id_price_smt_mms" name="price_smt_mms" placeholder="0" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_price_smt_mms, 2) : number_format($this->Biz_model->base_price_smt_mms, 2)?>" step="1" type="decimal" value="0" /></td>
			  </tr>
			  </tbody>
		 </table>
		 <div class="widget-header" style="display: none">
			  <h3><i class="icon-edit"></i> 기본 충전 가중치 적용</h3>
		 </div>
		 <table class="tpl_ver_form" width="60%" style="display: none">
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
					<td><input class="form-control input-width-large inline" id="id_rate1" name="rate1" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight1, 2) : number_format($this->Biz_model->weight[0], 2)?>" step="1" type="number" value="0" />%</td>
			  </tr>
			  <tr>
					<th>&#8361;<?=number_format($this->Biz_model->charge[1])?></th>
					<td><input class="form-control input-width-large inline" id="id_rate2" name="rate2" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight2, 2) : number_format($this->Biz_model->weight[1], 2)?>" step="1" type="number" value="0" />%</td>
			  </tr>
			  <tr>
					<th>&#8361;<?=number_format($this->Biz_model->charge[2])?></th>
					<td><input class="form-control input-width-large inline" id="id_rate3" name="rate3" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight3, 2) : number_format($this->Biz_model->weight[2], 2)?>" step="1" type="number" value="3" />%</td>
			  </tr>
			  <tr>
					<th>&#8361;<?=number_format($this->Biz_model->charge[3])?></th>
					<td><input class="form-control input-width-large inline" id="id_rate4" name="rate4" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight4, 2) : number_format($this->Biz_model->weight[3], 2)?>" step="1" type="number" value="5" />%</td>
			  </tr>
			  <tr>
					<th>&#8361;<?=number_format($this->Biz_model->charge[4])?></th>
					<td><input class="form-control input-width-large inline" id="id_rate5" name="rate5" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight5, 2) : number_format($this->Biz_model->weight[4], 2)?>" step="1" type="number" value="7" />%</td>
			  </tr>
			  <tr>
					<th>&#8361;<?=number_format($this->Biz_model->charge[5])?></th>
					<td><input class="form-control input-width-large inline" id="id_rate6" name="rate6" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight6, 2) : number_format($this->Biz_model->weight[5], 2)?>" step="1" type="number" value="10" />%</td>
			  </tr>
			  <tr>
					<th>&#8361;<?=number_format($this->Biz_model->charge[6])?></th>
					<td><input class="form-control input-width-large inline" id="id_rate7" name="rate7" placeholder="%" value="<?=($rs->wst_mem_id) ? number_format($rs->wst_weight7, 2) : number_format($this->Biz_model->weight[6], 2)?>" step="1" type="number" value="20" />%</td>
			  </tr>
			  </tbody>
		 </table>

		 <div class="mt30 align-center">
			  <button type="submit" class="submit btn btn-custom">저장</button>
		 </div>
		<br />
		<!-- div class="btn-group pull-right" role="group" aria-label="...">
			<button type="submit" class="btn btn-outline btn-success btn-sm">저장하기</button>
		</div-->
		<?php echo form_close(); ?>
	</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        rules: {
            dep_content: 'required'
        }
    });
});
//]]>
</script>
<script type="text/javascript">
  $("#nav li.nav100").addClass("current open");

  $(document).ready(function() {
		$(".price_edit").click(function() {
			var agent = $(this).parent().parent().find(".adm_agent").val();
			var company = $(this).parent().parent().find(".adm_company").val();
			var price_phn = $(this).parent().parent().find(".adm_phn").val();
			var price_sms = $(this).parent().parent().find(".adm_sms").val();
			var price_lms = $(this).parent().parent().find(".adm_lms").val();
			var price_mms = $(this).parent().parent().find(".adm_mms").val();

			$.ajax({
				 url: "/biz/manager/setting/adm_price",
				 type: "POST",
				 data: {
					  <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					  agent: agent, company: company, adm_phn: price_phn, adm_sms: price_sms, adm_lms: price_lms, adm_mms: price_mms
				 },
				 beforeSend: function () {
					  $('#overlay').fadeIn();
				 },
				 complete: function () {
					  $('#overlay').fadeOut();
				 },
				 success: function (json) {
					 if(!json['success']) {
						$(".content").html("저장중 오류가 발생하였습니다.");
						$("#myModal").modal('show');
					 }
				 },
				 error: function (data, status, er) {
					  $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
					  $("#myModal").modal('show');
				 }
			});
		});
  });
</script>