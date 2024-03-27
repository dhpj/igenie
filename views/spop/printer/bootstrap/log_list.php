<!-- 타이틀 영역 -->
<div class="tit_wrap">
	POP사용현황
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>POP사용현황 (전체 <b style="color: red" id="total_rows">0</b>개)</h3>
			<!-- <button class="btn_tr" onclick="location.href='/biz/partner/write'">파트너 등록하기</button> -->
		</div>
		<div class="white_box" id="search_box">
			<div class="search_box">
				<select class="select2 input-width-medium" id="searchType">
					<option value="1" <?=$search_type=='1' ? 'selected' : ''?>>업체명</option>
					<!-- <option value="2" <?=$search_type=='2' ? 'selected' : ''?>>로그내용</option> -->
				</select>
				<input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>" onKeypress="if(event.keyCode==13){ search_question(1); }">
				<input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
                <a href="/spop/printer/print_log/" class="btn md fr" style="<?=(empty($search_for)&&empty($selectdate))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>

			</div>
			<div class="widget-content">

                <table>
                	<colgroup>

                		<col width="17%"><?//시간?>
                		<col width="17%"><?//계정?>
                		<col width="17%"><?//업체명?>
                        <col width="17%"><?//타입?>
                        <col width="17%"><?//스타일?>
                		<col width="*"><?//로그내용?>
                	</colgroup>
                	<thead>
                	<tr>

                		<th>시간</th>
                		<th>계정</th>
                		<th>업체명</th>
                        <th>타입</th>
                		<th>스타일</th>
                		<th>로그내용</th>
                	</tr>
                	</thead>
                	<tbody>
                		<?
                			$offer = "";
                			foreach($lists as $row) {
                		?>
                		<tr>
                			<td><?=$row->sl_create_datetime?></td><?//접근시간?>
                			<td><?=$row->mem_userid?></td><?//계정?>
                			<td><?=$row->mem_username?></td><?//업체명?>
                			<td><?=$row->sl_spop_type?></td><?//타입?>
                            <td><?=$row->sl_spop_style?></td><?//스타일?>
                            <td><?=($row->sl_type == 0) ? "다운로드" : "프린터"?></td><?//로그내용?>
                		</tr>
                		<?
                			}
                		?>
                	</tbody>
                </table>
                <input type="file" name="filecount" id="filecount" multiple onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
                </div>
                <div class="page_cen"><?echo $page_html?></div>
                <script>
                	$("#total_rows").html("<?=number_format($total_rows)?>");
                </script>
            </div>
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
			// open_page(1);
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

        //검색
    	function open_page(page){
            var type = $('#searchType').val();
    		var searchFor = $('#searchStr').val();

    		var pram = "";
    		if(type != "" && searchFor != "") pram += "&search_type="+ type +"&search_for="+ searchFor;

    		location.href = "?page="+ page + pram;
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
