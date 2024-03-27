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
							<h3>공지메시지</h3>
						</div>
						<div class="inner_content clearfix">

		                        <form id="save" method="POST" onsubmit="return check_form()" EncType="Multipart/Form-Data">
		                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
		                            <input type='hidden' name='actions' value='msg_save' />
									
		                            <div class="table_list fl" style="width: 600px; margin: 0 15px 0 0;" >
			                            <table>
			                                <colgroup>
			                                    <col width="100px">
			                                    <col width="*">
			                                    <col width="100px">
			                                    <col width="*">
			                                </colgroup>
			                                <tr>
			                                    <th for="id_title" >제목<span class="required">*</span></th>
			                                    <td colspan = "3">
			                                       <?=$rs->title?> 
												</td>
			                                </tr>
			                                <tr>
			                                  <th for="id_message">내용<span class="required">*</span></th>
											  <td colspan = "3" style="text-align: left;">
			                                      <?=nl2br($rs->msg)?>
			                                  </td>                                 
			                                </tr>
										    <tr>
			                                <th for="id_company_name">시작일 </th>
				                                <td>
			    	           						 <?=$rs->send_date?> 
			        	                        </td>
											<th for="id_userid">종료일</th>
												<td>
			                						 <?=$rs->expiration_date?> 
												</td>
											 </tr>
			                            </table>
		                            </div>
		                            <div style="width: 240px; height: 500px; border: 1px solid #dedede; overflow: auto;">
			                            <h4 style="padding: 7px 15px; background: #f8f8f8;">수신목록</h4>
			                            <ul style="height:100%; padding: 15px;">
											<?foreach($partner as $r) {?>
											<li><?=$r->mem_username?></li>
											<?}?> 
										</ul>
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
			 
			 
			
		});
 
 

    </script>
