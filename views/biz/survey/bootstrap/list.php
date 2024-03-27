<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					설문 목록
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_content">
							<!-- 테이블 상단 검색 영역 -->
						    <div class="search_wrap" id="duration">
							    <P>제목 <input type="text" style="margin-right: 7px;" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>"/>
							    <input type="button" class="btn md color" id="check" value="조회" onclick="open_page(1)"/>
							    </P>
						    </div>
							<!--div class="icon_tooltip">
								<i class="fas fa-question-circle"></i>
								<div class="icon_tooltip_wrap">툴팁 내용이 들어가는 영역입니다.</div>
							</div-->
							<!-- 테이블 상단 검색 영역 END -->
					    
							<div class="table_list">
							<table cellpadding="0" cellspacing="0" border="0">
							    <colgroup>
								        <col width="15%">
								        <col width="15%">
								        <col width="%">
								        <col width="8%">
								        <col width="8%">
								        <col width="20%">
							    </colgroup>
								<thead>
									<tr>
										<th>설문URL</th>
										<th>제목</th>
										<th>설명</th>
										<th>시작일</th>
										<th>종료일</th>
										<th>...</th>
									</tr>
								</thead>
								<tbody>
									<? foreach($list as $r) {?>
									     
									<tr class="" onclick="go_view(<?=$r->svm_id?>)">
										<td>
											<a href='<?=$r->call_url?>' target="_blank"><?=$r->call_url?></a>
										</td>
										<td>
											<?=$r->title?>
										</td>
										<td>
											<?=$r->description?>
										</td>
										<td>
											<?=$r->start?>
										</td>
										<td>
											<?=$r->end?>
										</td>
										<? if($isManager == 'Y') { // 2019.08.14 LSH 관리자만 결과 조회 가능하게 처리 ?>
										<td>
											<button class="btn" onclick="result('<?=$r->short_url?>');">결과조회</button>
											<!-- button class="btn" onclick="#">수정</button-->
										</td>
										<? } else { ?>
											<button class="btn" onclick="result('<?=$r->short_url?>');">결과조회</button>										
										<? } ?>
									</tr>
																	        
									<?}?>
								</tbody>
							</table>
							</div>

							<div class="align-center mt30"><?=$page_html?></div>
						</div>
					</div><!-- form_section End -->
				</div><!-- #mArticle end -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br><br>
                            <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $("#nav li.nav10").addClass("current open");

        $('#searchStr').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                open_page(1);
            }
        });

//         window.onload = function () {
            var set_date = '<?=($param['duration']) ? $param['duration'] : 'week'?>';
             $("#"+set_date).addClass('active');
//         };

        function open_page(page) {
        	
            var duration = $('#duration ul li.active').attr('value') || 'week';
            var type = $('#searchType').val() || 'all';
            var searchFor = $('#searchStr').val() || '';

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/survey");
				var cfrsField = document.createElement("input");
				cfrsField.setAttribute("type", "hidden");
				cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            form.appendChild(cfrsField);
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            var typeField = document.createElement("input");
            typeField.setAttribute("type", "hidden");
            typeField.setAttribute("name", "search_type");
            typeField.setAttribute("value", type);
            form.appendChild(typeField);
            var searchForField = document.createElement("input");
            searchForField.setAttribute("type", "hidden");
            searchForField.setAttribute("name", "search_for");
            searchForField.setAttribute("value", searchFor);
            form.appendChild(searchForField);
            var durationField = document.createElement("input");
            durationField.setAttribute("type", "hidden");
            durationField.setAttribute("name", "duration");
            durationField.setAttribute("value", duration);
            form.appendChild(durationField);
            form.submit();
        }

 
        function result(svm_key) {
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "get");
            form.setAttribute("action", "/biz/survey/result/" +svm_key);

            //var cfrsField = document.createElement("input");
			//cfrsField.setAttribute("type", "hidden");
			//cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			//cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            //form.appendChild(cfrsField);

            form.submit();
        }
        
        $('#duration ul li').click(function() {
            $('#duration ul li').removeClass('active');
            $(this).addClass('active');
            // console.log($('#duration a.active').attr('value'));
            open_page(1);
        });

        function page(page) {
            var duration = $('#duration ul li .active').attr('value');
            var type = $('#searchType').val();
            var searchFor = $('#searchStr').val();

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/survey");
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            var typeField = document.createElement("input");
            typeField.setAttribute("type", "hidden");
            typeField.setAttribute("name", "search_type");
            typeField.setAttribute("value", type);
            form.appendChild(typeField);
            var searchForField = document.createElement("input");
            searchForField.setAttribute("type", "hidden");
            searchForField.setAttribute("name", "search_for");
            searchForField.setAttribute("value", searchFor);
            form.appendChild(searchForField);
            var durationField = document.createElement("input");
            durationField.setAttribute("type", "hidden");
            durationField.setAttribute("name", "duration");
            durationField.setAttribute("value", duration);
            form.appendChild(durationField);
            form.submit();
        }

    </script>
