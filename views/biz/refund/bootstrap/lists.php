    <form action="/biz/refund/remove" method="post" id="mainForm">
        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="modal">
                <div class="modal-content">
                    <br/>
                    <div class="modal-body">
                        <div class="content">
                        </div>
                        <div class="btn_al_cen">
                                <button type="button" class="btn_st1" data-dismiss="modal" id="identify">
                                    확인
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="modal fade" id="myModalImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http://www.w3.org/1999/html" style="overflow-y: hidden; width: 100%; height: 100%">
			<div class="modal-dialog modal-lg" id="modal" style="width:620px;">
				<div class="modal-content">
					<br/>
					<div class="modal-body">
						<div class="content">
						</div>
						<div class="btn_al_cen">
								<button type="button" class="btn_st1" data-dismiss="modal">확인</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
             aria-labelledby="myModalCheckLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="modalCheck">
                <div class="modal-content">
                    <br/>
                    <div class="modal-body">
                        <div class="content">
                        </div>
                        <div class="btn_al_cen">
                                <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                                <button type="submit" class="btn_st1">확인</button>
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
							<h3>환불요청 목록 (<?=number_format($total_rows)?>건)</h3>
						</div>
						<div class="white_box">
							<div class="search_box">
								<select id="pf_list" name="pf_list" class="select2 input-width-large">
                                    <option value="all" <?=($param['search_stat']=="all") ? "selected" : ""?>>-ALL-</option>
                                    <option value="-" <?=($param['search_stat']=="-") ? "selected" : ""?>>미처리</option>
                                    <option value="N" <?=($param['search_stat']=="N") ? "selected" : ""?>>취소</option>
                                    <option value="Y" <?=($param['search_stat']=="Y") ? "selected" : ""?>>완료</option>
                                </select>
                                <input type="text" class="form-control input-width-medium inline number" placeholder="업체명" name="search_company_nm" id="search_company_nm" value="<?=$param['search_company_nm']?>">
                                <input type="button" class="btn btn-default" value="조회" onclick="javascript:search();">
							</div>
							<div class="table_list">
								<table class="table-checkable">
	                                <colgroup>
	                                    <col width="50px">
	                                    <col width="150px">
	                                    <col width="*">
	                                    <col width="100px">
	                                    <col width="100px">
	                                    <col width="200px">
	                                    <col width="70px">
	                                    <col width="70px">
	                                    <col width="70px">
	                                </colgroup>
	                                <thead>
	                                <tr>
	                                    <th class="checkbox-column">
	                                        <input type="checkbox" class="uniform">
	                                    </th>
	                                    <th class="text-center">요청시간</th>
	                                    <th class="text-center">업체명</th>
	                                    <th class="text-center">환불신청금액</th>
	                                    <th class="text-center">승인금액</th>
	                                    <th class="text-center">환불계좌</th>
	                                    <th class="text-center">통장사본</th>
	                                    <th class="text-center">환불사유</th>
	                                    <th class="text-center">처리상태</th>
	                                </tr>
	                                </thead>
	                                <tbody>
	<?foreach($list as $r) {?>
									<tr>
										<td class="checkbox-column text-center">
										<?if($r->ref_stat=="-") {?>
                      <label class="checkbox_container">
											<input type="checkbox" name="selRefund[]" id="check_id" class="uniform" value="<?=$r->ref_id?>">
                      <span class="checkmark"></span>
                      </label>
										<?}?>
										</td>
										<td class="text-center"><?=$r->ref_datetime?></td>
										<td class="text-center" name="company"><?=$r->mem_username?></td>
										<td class="text-center" name="amount"><?=number_format($r->ref_amount)?></td>
										<td class="text-center" name="appr_amount"><?=number_format($r->ref_appr_amount)?></td>
										<td class="text-center" name="bank"><?=$r->ref_bank_name." ".$r->ref_account." ".$r->ref_acc_master?></td>
										<td class="text-center" name="photo"><a href="#" onclick="show_sheet('<?=$r->ref_sheet?>')">보기</a></td>
										<td class="text-center" name="reason"><a href="#" onclick="show_reason($(this))">보기</a><input type="hidden" value="<?=$r->ref_memo?>" /></td>
										<td class="text-center"><?=($r->ref_stat=="Y") ? "완료" : (($r->ref_stat=="N") ? "취소" : "미처리")?></td>
									</tr>
	<?}?>
	                               </tbody>
	                            </table>
							</div>
							<div>
                                <button class="btn_st1" type="button" onclick="selectDelRow()"><i class="icon-trash"></i> 선택삭제</button>
                                <button class="btn_st1" type="button" onclick="selectApprRow()" style="float:right;"><i class="icon-ok"></i> 선택승인</button>
                        	</div>
							<div class="pagination align-center"><?echo $page_html?></div>
						</div>
					</div>
</div>

    </form>

    <script type="text/javascript">
        $("#nav li.nav100").addClass("current open");

        $('.searchBox input').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                search();
            }
        });

        function page(page) {
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/refund/list");
            document.body.appendChild(form);
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "page");
            hiddenField.setAttribute("value", page);
            form.appendChild(hiddenField);
            var search_company_nm = $("#search_company_nm").val();
            var search_company_nmField = document.createElement("input");
            search_company_nmField.setAttribute("type", "hidden");
            search_company_nmField.setAttribute("name", "search_company_nm");
            search_company_nmField.setAttribute("value", search_company_nm);
            form.appendChild(search_company_nmField);
            var search_stat = $("#pf_list option:selected").val();
            var search_statField = document.createElement("input");
            search_statField.setAttribute("type", "hidden");
            search_statField.setAttribute("name", "search_stat");
            search_statField.setAttribute("value", search_stat);
            form.appendChild(search_statField);
            form.submit();
        }

        function view(obj, obj2) {
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/refund/view");
            document.body.appendChild(form);
            var companyField = document.createElement("input");
            companyField.setAttribute("type", "hidden");
            companyField.setAttribute("name", "company_name");
            companyField.setAttribute("value", obj);
            form.appendChild(companyField);
            var idField = document.createElement("input");
            idField.setAttribute("type", "hidden");
            idField.setAttribute("name", "id");
            idField.setAttribute("value", obj2);
            form.appendChild(idField);
            form.submit();
        }

        function selectDelRow() {
            if ($("input:checkbox[id='check_id']").is(":checked") == false) {
                $("#myModal").find(".content").html("삭제할 환불 목록을 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
					 $('#mainForm').attr("action", "/biz/refund/remove");
                $(".content").html("삭제 하시겠습니까?<br />삭제하면 예치금이 재적립됩니다.");
                $("#myModalCheck").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".submit").click();
                    }
                });
            }
        }

        function selectApprRow() {
            if ($("input:checkbox[id='check_id']").is(":checked") == false) {
                $("#myModal").find(".content").html("승인할 환불 목록을 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
					 $('#mainForm').attr("action", "/biz/refund/appr");
                $(".content").html("승인 하시겠습니까?");
                $("#myModalCheck").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".submit").click();
                    }
                });
            }
        }

        $('#mainForm').submit(function() {
            $('#overlay').fadeIn();
            return true;
        });

        function search() {
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/refund/lists");
            document.body.appendChild(form);
            var csrfField = document.createElement("input");
            csrfField.setAttribute("type", "hidden");
            csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
            csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            form.appendChild(csrfField);
            var search_company_nm = $("#search_company_nm").val();
            var search_company_nmField = document.createElement("input");
            search_company_nmField.setAttribute("type", "hidden");
            search_company_nmField.setAttribute("name", "search_company_nm");
            search_company_nmField.setAttribute("value", search_company_nm);
            form.appendChild(search_company_nmField);
            var search_stat = $("#pf_list option:selected").val();
            var search_statField = document.createElement("input");
            search_statField.setAttribute("type", "hidden");
            search_statField.setAttribute("name", "search_stat");
            search_statField.setAttribute("value", search_stat);
            form.appendChild(search_statField);
            form.submit();
        }


        function open_page(page) {
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/refund/lists");
            document.body.appendChild(form);
            var csrfField = document.createElement("input");
            csrfField.setAttribute("type", "hidden");
            csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
            csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            form.appendChild(csrfField);
            var search_company_nm = $("#search_company_nm").val();
            var search_company_nmField = document.createElement("input");
            search_company_nmField.setAttribute("type", "hidden");
            search_company_nmField.setAttribute("name", "search_company_nm");
            search_company_nmField.setAttribute("value", search_company_nm);
            form.appendChild(search_company_nmField);
            var search_stat = $("#pf_list option:selected").val();
            var search_statField = document.createElement("input");
            search_statField.setAttribute("type", "hidden");
            search_statField.setAttribute("name", "search_stat");
            search_statField.setAttribute("value", search_stat);
            form.appendChild(search_statField);

            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);

            form.submit();
        }

		function show_sheet(img) {
			$("#myModalImg").find(".content").html("<img src='/uploads/bank_depositor/"+img+"' width='570' border='0' />");
			$('#myModalImg').modal({backdrop: 'static'});
		}

		function show_reason($obj) {
			var memo = $obj.parent().find('input').val();
			$("#myModal").find(".content").html(memo);
			$('#myModal').modal({backdrop: 'static'});
		}
    </script>
