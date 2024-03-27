<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					파트너 관리
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>파트너 목록</h3>
						</div>
						<div class="inner_content">
			                    <div class="search_box">
											<label for="userid">소속</label>&nbsp;
											<select name="userid" id="userid" class="selectpicker" data-live-search="true">
												<option value="ALL">-ALL-</option>
											<?foreach($users as $r) {?>
												<option value="<?=$r->mem_id?>" <?=($param['userid']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
											<?}?>
											</select>
											&nbsp;
			                        <select class="select2 input-width-medium" id="searchType">
			                            <option value="username" <?=$param['search_type']=='username' ? 'selected' : ''?>>업체명</option>
			                            <option value="phone" <?=$param['search_type']=='phone' ? 'selected' : ''?>>전화번호</option>
			                            <option value="nickname" <?=$param['search_type']=='nickname' ? 'selected' : ''?>>담당자 이름</option>
			                        </select>&nbsp;
			                        <input type="text" class="search_box" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>"/>&nbsp;
			                        <input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
			                        
							        	<? if($this->member->item('mem_id')=='3') {?>
							            
							                <button type="button" class="btn md fr" onclick="download_();">
							                	<i class="icon-arrow-down"></i> 엑셀 다운로드</button>
							            
							            <? } ?>
				                    
			                    </div>
							<div class="widget-content"></div>
						</div>
					</div>
				</div>

    <style>
        .text-center {
            vertical-align: middle !important;
        }

        .text-left {
            vertical-align: middle !important;
        }
    </style>

    <script type="text/javascript">
		$(document).ready(function() {
			open_page(1);
		});

        $('.searchBox input').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                open_page(1);
            }
        });

        $("#nav li.nav60").addClass("current open");

        $(document).ajaxStart(function(){
            $("#wait").css("display", "block");
        });

        $(document).ajaxComplete(function(){
            $("#wait").css("display", "none");
        });

        //검색 조회
        function search_question(page) {
            open_page(page);
        }

        function open_page(page) {
            var uid = $('#userid').val();
            var type = $('#searchType').val();
            var searchFor = $('#searchStr').val();

            $('.widget-content').html('').load(
               "/biz/partner2/inc_lists",
					{
						"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
						"uid": uid,
						"search_type": type,
						"search_for": searchFor,
						'page': page
					}
            );
        }

        function download_() {

            var uid = $('#userid').val();
            var type = $('#searchType').val();
            var searchFor = $('#searchStr').val();
        	
			var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/partner2/download");

			var scrfField = document.createElement("input");
			scrfField.setAttribute("type", "hidden");
			scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(scrfField);
 
			var kindField = document.createElement("input");
			kindField.setAttribute("type", "hidden");
			kindField.setAttribute("name", "search_type");
			kindField.setAttribute("value", type);
			form.appendChild(kindField);

			var resultField = document.createElement("input");
			resultField.setAttribute("type", "hidden");
			resultField.setAttribute("name", "search_for");
			resultField.setAttribute("value", searchFor);
			form.appendChild(resultField);
			 
			form.submit();
 
        }
    </script>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
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
<div class="modal fade" id="myModalCustomer" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary customer_save" data-dismiss="modal" onclick="customer_save();">
                            확인
                        </button>
                    </p>
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
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary submit">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalAll" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary all" onclick="all_move();">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalDel" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary del">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalSelDel" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary selDel">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalTemp" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary temp">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalUpload" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary up">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalDownload" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary down">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
