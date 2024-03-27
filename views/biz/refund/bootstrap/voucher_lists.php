
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
        include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu13.php');
        ?>
        <!-- //3차 메뉴 -->
<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>바우처소진 목록 (<?=number_format($total_rows)?>건)</h3>
						</div>
						<div class="white_box">
							<div class="search_box">
								<select id="search_flag" name="search_flag" class="select2 input-width-large" onchange="search();">
                                    <option value="all" <?=($search_flag=="all") ? "selected" : ""?>>-ALL-</option>
                                    <option value="N" <?=($search_flag=="N") ? "selected" : ""?>>미처리</option>
                                    <option value="Y" <?=($search_flag=="Y") ? "selected" : ""?>>처리</option>
                                </select>
                                <input type="text" class="form-control input-width-medium inline number" placeholder="업체명" name="skeyword" id="skeyword" value="<?=$skeyword?>">
                                <input type="button" class="btn btn-default" value="조회" onclick="javascript:search();">
							</div>
							<div class="table_list">
								<table class="table-checkable">
	                                <colgroup>
                                        <col width="10%">
                                        <col width="10%">
                                        <col width="*">
	                                    <col width="*">
	                                    <col width="9%">
                                        <col width="9%">
                                        <col width="7%">
	                                    <col width="7%">
                                        <col width="12%">
	                                </colgroup>
	                                <thead>
	                                <tr>
                                        <th class="text-center">소진일</th>
                                        <th class="text-center">처리일</th>
                                        <th class="text-center">소속</th>
	                                    <th class="text-center">업체명</th>
	                                    <th class="text-center">바우처잔액(현재)</th>
                                        <th class="text-center">바우처잔액(소진시)</th>
                                        <th class="text-center">전환금액</th>
	                                    <th class="text-center">처리상태</th>
                                        <th class="text-center">예치금정산</th>
	                                </tr>
	                                </thead>
	                                <tbody>
	<?
    foreach($list as $r) {?>
									<tr <?=($r->voucher_deposit<0&&$r->kvd_proc_flag=="N") ? "style='background:#fff5e5;'" : "style='background:#;'"?>>
                                        <td class="text-center" name="company"><?=$r->kvd_minus_date?></td>
                                        <td class="text-center" name="company"><?=$r->kvd_fixed_date?></td>
                                        <td class="text-center" name="company"><?=$r->adminname?></td>
										<td class="text-center" name="company"><?=$r->mem_username?></td>
										<td class="text-center" name="amount"><?=(!empty($r->voucher_deposit))? $r->voucher_deposit : "0.00" ?></td>
                                        <td class="text-center" name="amount"><?=$r->kvd_remain_cash?></td>
                                        <td class="text-center" name="amount"><?=abs($r->adjust_deposit)?></td>
										<td class="text-center"><?=($r->voucher_deposit<0&&$r->kvd_proc_flag=="N") ? "미처리" : "처리완료"?></td>
                                        <td class="text-center"><button class="btn sm" id="btn_<?=$r->mem_id?>" <?=($r->kvd_proc_flag=="Y")? "style='display:none;'" : "" ?> type="button" onclick="voucherEnding('<?=$r->mem_id?>', '<?=$r->voucher_deposit?>');">바우처잔액 -> 예치금정산</button></td>
									</tr>
	<?}?>
	                               </tbody>
	                            </table>
							</div>
							<!-- <div>
                                <button class="btn_st1" type="button" onclick="selectDelRow()"><i class="icon-trash"></i> 선택삭제</button>
                                <button class="btn_st1" type="button" onclick="selectApprRow()" style="float:right;"><i class="icon-ok"></i> 선택승인</button>
                        	</div> -->
							<div class="pagination align-center"><?echo $page_html?></div>
						</div>
					</div>
</div>



    <script type="text/javascript">
        $("#nav li.nav100").addClass("current open");

        $('#skeyword').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                search();
            }
        });

        function voucherEnding(id, deposit){

            if (confirm("해당 파트너의 바우처 잔액을 수정하시겠습니까??") == true){    //확인
                jsLoading();
                $.ajax({
                    url: "/biz/refund/voucher_adjust",
                    type: "POST",
                    data: {
                          "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                        , "id" : id
                        , "minus_deposit" : deposit
                    },
                    success: function (json) {
                        hideLoading();
                        showSnackbar(json.msg, 1500);
                        if(json.code=='0'){
                            $("#btn_"+id).hide();
                            setTimeout(function(){
                				location.reload();
                			},1500);
                        }

                    }
                });

             }else{   //취소

                 return false;

             }

        }

        function open_page(page){
    		// var sfield = $("#sfield").val(); //검색타입
    		var skeyword = $("#skeyword").val(); //검색내용
            var search_flag = $("#search_flag").val();
            // if(search_flag==""){
            //     search_flag = "ALL";
            // }
            var firstparam = "?search_flag="+search_flag;

    		var param = "";
    		if(skeyword != "") param += "&skeyword="+ skeyword;
    		//alert("page : "+ page +", tagid : "+ tagid);
    		location.href = firstparam + param +"&page="+ page;
    	}

        // function page(page) {
        //     var form = document.createElement("form");
        //     document.body.appendChild(form);
        //     form.setAttribute("method", "post");
        //     form.setAttribute("action", "/biz/refund/list");
        //     document.body.appendChild(form);
        //     var hiddenField = document.createElement("input");
        //     hiddenField.setAttribute("type", "hidden");
        //     hiddenField.setAttribute("name", "page");
        //     hiddenField.setAttribute("value", page);
        //     form.appendChild(hiddenField);
        //     var search_company_nm = $("#search_company_nm").val();
        //     var search_company_nmField = document.createElement("input");
        //     search_company_nmField.setAttribute("type", "hidden");
        //     search_company_nmField.setAttribute("name", "search_company_nm");
        //     search_company_nmField.setAttribute("value", search_company_nm);
        //     form.appendChild(search_company_nmField);
        //     var search_stat = $("#pf_list option:selected").val();
        //     var search_statField = document.createElement("input");
        //     search_statField.setAttribute("type", "hidden");
        //     search_statField.setAttribute("name", "search_stat");
        //     search_statField.setAttribute("value", search_stat);
        //     form.appendChild(search_statField);
        //     form.submit();
        // }

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
            open_page('1');
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
