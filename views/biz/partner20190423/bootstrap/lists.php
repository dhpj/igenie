	 <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/biz/partner/lists">파트너 관리</a></li>
            <li class="current"><a href="#" title="">파트너 목록</a></li>
        </ul>
    </div>
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                <div class="widget">
                    <div class="pull-left searchBox">
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
                        <input type="text" class="form-control input-width-medium inline"
                               id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>"/>&nbsp;
                        <input type="button" class="btn btn-default" id="check" value="조회" onclick="search_question(1)"/>
                    </div>
                    <div class="mb20 mt10 clear">
                        <div class="align-right">
                            <a href="/biz/partner/write" class="btn btn-primary"><i
                                    class="icon-pencil"></i> 파트너 등록 </a>
                        </div>
                    </div>
                    <div class="widget-content">
                    
<div class="widget-content"></div>

                    </div>
<div class="col-xs-6">
        	<? if($this->member->item('mem_id')=='3') {?>
            <div class="align-right">
                <a type="button" class="btn btn-sm" onclick="download_();" style="margin-top: 20px;">
                	<i class="icon-arrow-down"></i> 엑셀 다운로드</a>
            </div>
            <? } ?>
        </div>                    
                </div>
            </div>
        </div>
    </div><!--//.content_wrap-->
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
               "/biz/partner/inc_lists",
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
			form.setAttribute("action", "/biz/partner/download");

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
