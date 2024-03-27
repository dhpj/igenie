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
		공지메시지
	</div>
<!-- 타이틀 영역 END -->
	<div id="mArticle">
		<div class="form_section">
			<div class="inner_tit">
				<h3>
					작성하기 
				</h3>
				
			</div>
			<div class="inner_content">
				
<form id="save" method="POST" onsubmit="return check_form()" EncType="Multipart/Form-Data">
                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                            <input type='hidden' name='actions' value='msg_save' />
                            
                            <div class="table_list fl" style="width: 600px; margin: 0 15px 0 0;" >
                            <table>
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tr>
                                    <th for="id_title" >제목<span class="required">*</span></th>
                                    <td colspan = "3">
                                      <input class="form-control input-width-large required" id="id_title" maxlength="150" style="width:500px !important;" name="title" placeholder="제목" type="text" value="<?=$rs->mem_userid?>"   />
									</td>
                                </tr>
                                <tr>
                                  <th for="id_message">내용<span class="required">*</span></th>
                                  <td colspan = "3">
                                     <textarea id="id_message" name="message" cols="30" rows="10" class="form-control" style="resize:auto;cursor: default;" ></textarea>
                                  </td>
                                </tr>
							    <tr>
                                <th for="id_company_name">시작일 </th>
	                                <td>
    	           						<input class="form-control input-width-large " id="id_send_date" maxlength=15 name="send_date" placeholder="시작일" value="<?=$rs->mem_fixed_from_date?>" type="text" />
        	                        </td>
								<th for="id_userid">종료일</th>
									<td>
                						<input class="form-control input-width-large " id="id_expiration_date" maxlength=15 name="expiration_date" placeholder="종료일" value="<?=$rs->mem_fixed_to_date?>" type="text" />
									</td>
								 </tr>
                            </table>
                            </div>
                            
                            <div style="width: 240px; height: 500px; border: 1px solid #dedede; overflow: auto;">
                                <center>수신목록</center>
                                <select name="receiver_list[]" id="multiselect" class="form-control" size="13" multiple="multiple">
                                </select>
                              </div>
                              <div class="col-xs-2">
                                <br>
                                <br>
                                <br>
                                <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                                <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                              </div>
                              <div class="col-xs-5">
                                <center>전체 목록</center>
                                <select name="to" id="multiselect_to" class="form-control" size="13" multiple="multiple">
                                  <?foreach($partner as $key => $r) {?>
                                  <option value="<?=$key?>"><?=$r?></option>
                                  <?}?>
                                </select>
                              </div>
                            </div>
                            <div class="mt30 align-center">
                                <button onclick="location.href='/biz/partner/lists'" class="btn md">취소</button>
                                <button type="submit" class="submit btn btn-custom">저장</button>
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

		$(document).ready(function() {
			$("#nav li.nav80").addClass("current open");

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
			
			$("#id_send_date, #id_expiration_date").datepicker({
				format:'yyyy-mm-dd'			
			});

			$('#multiselect').multiselect({
				search: {
					left:'<input type="text" name="q" class="form-control" placeholder="검색.." />', 
					right:'<input type="text" name="q" class="form-control" placeholder="검색.." />', 
				}
			});
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

			  if($("#multiselect option").size() ==0 ) {
				  alert("수신 업체가 없습니다.");
				  return false;
		      }
		      return true; 
		  }

    </script>
