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
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>결제내역 관리</h3>
						</div>
						<div class="white_box">
							<?php
					        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
					        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
					        echo form_open(current_full_url(), $attributes);
					        ?>
							<input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
							<table class="table_list">
								<colgroup>
								<col width="150px">
								<col width="*">
								</colgroup>
								<tbody>
								<tr>
									<th>회원아이디</th>
									<td class="tl">
										<input type="hidden" name="mem_userid" id="mem_userid"  />
										<select name="userid" id="userid" class="selectpicker" data-live-search="true">
										<?foreach($rs as $r) {?>
											<option value="<?=$r->mem_userid?>"><?=$r->mem_username?></option>
										<?}?>
										</select>
										<span class="btn h34" style="display:inline-block;"><a type="button" id="check_double_id" onclick="check_account('userid')">아이디 확인</a>
										</span>
										<span id="id_findname" style="color:blue;"></span>
										<input type="hidden" name="findname" id="findname" />
									</td>
								</tr>
								<tr>
									<th><?php echo $this->depositconfig->item('deposit_name'); ?> 변동</th>
									<td class="tl">
										<input type="number" class="form-control input-width-medium" name="dep_deposit" value="<?php echo set_value('dep_deposit', element('dep_deposit', element('data', $view))); ?>" />
										<div class="help-inline"><font color='black'>예치금을 충전하는 경우는 양수로, <font color='red'>예치금을 조정(차감)하는 경우는 음수</font>로 입력해주세요</font></div>
									</td>
								</tr>
								<tr>
									<th>내용</th>
									<td class="tl">
										<textarea class="form-control" rows="5" name="dep_content"><?php echo set_value('dep_content', element('dep_content', element('data', $view))); ?></textarea>
									</td>
								</tr>
								<tr>
									<th>관리자 메모</th>
									<td>
										<textarea class="form-control" rows="5" name="dep_admin_memo"><?php echo set_value('dep_admin_memo', element('dep_admin_memo', element('data', $view))); ?></textarea>
									</td>
								</tr>
								</tbody>
							</table>
							    <?php
    echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
    $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
    echo form_open(current_full_url(), $attributes);
    ?>
	<input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
	<div class="box-table">
        <div class="btn-group" role="group" aria-label="...">
            <button type="submit" class="btn_st1">저장하기</button>
        </div>
	</div>
    <?php echo form_close(); ?>
						</div>
					</div>
</div>


<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        //ALERT($('#mem_userid').val());
        rules: {
            mem_userid: { required:true, minlength:3, maxlength:20 },
            dep_deposit: { required:true, number:true},
            dep_content: 'required'
        }
    });
});
//]]>
</script>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$("#nav li.nav100").addClass("current open");

	$(".modal-content").css('width', '320px');
	$(".modal-body").css('height', '120px');
	$("#myModal").css('overflow-x', 'hidden');
	$("#myModal").css('overflow-y', 'hidden');

	$('#fadminwrite').submit(function() {
		if($('#findname').val()=='') {
			$(".content").html("아이디 확인을 클릭하세요.");
			$('#myModal').modal({backdrop: 'static'});
			return false;
		}
		return true;
	});
});
//아이디 확인
function check_account(id) {
	id_check = true;
	var formId = '#' + id;
	var mb_id=  $(formId).val();

	if ($(formId).val().trim().length == 0 || !mb_id) {
		$(".content").html("아이디를 입력해주세요.");
		$('#myModal').modal({backdrop: 'static'});
	}
	else {
		$('#id_findname').html('');
		$('#findname').val('');
		 $.ajax({
			  url: '/biz/common/check_id/',
			  type: "POST",
			  data: {
					"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
					mb_id: mb_id
			  },
			  success: function (json) {
				  $('#mem_userid').val($('#userid').val());
					get_result(json);
			  },
			  error: function (data, status, er) {
					$(".content").html("처리중 오류가 발생하였습니다. 관리자에게 문의하세요.");
					$('#myModal').modal({backdrop: 'static'});
			  }
		 });
		 function get_result(json) {
			  if(json['result']==true) {
					$('#id_findname').html(json['name'] + " (" + json['nick'] + ")");
					$('#findname').val(json['name'] + " (" + json['nick'] + ")");
			  }
			  else{
					$('#id_findname').html('');
					$('#findname').val('');
			  }
		 }
	}
}
//-->
</script>
