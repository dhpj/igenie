<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					공지메시지
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<button onclick="location.href='/biz/message/write';" class="btn sm fr yellow"><i class="icon-pencil"></i>메시지 작성</button>
							<h3>공지메시지</h3>
						</div>
						<div class="inner_content">
							<div class="table_list">
					            <table>
					                <colgroup>
					                    <col width="20%">
					                    <col width="25%">
					                    <col width="*">
					                    <col width="12%">
					                </colgroup>
					                <thead>
					                <tr>
					                    <th>수신</th>
					                    <th>제  목</th>
					                    <th>내    용</th>
					                    <th>작성일</th>
					                </tr>
					                </thead>
					                <tbody>
					                 <?foreach($list as $r) {?>   
					                        <tr onclick="window.document.location='/biz/message/view/<?=$r->msg_id?>'" style="cursor: pointer; height:20px" >
					                            <td class="text-center"><? echo $r->rec_user; if($r->rec_cnt>1) { echo " 외 ".($r->rec_cnt-1);}?></td>
					                            <td class="text-center"><?=$r->title?></td>
					                            <td style="text-align:left"> <?=substr($r->msg, 0, 80)." ... "?> </td>
					                            <td class="text-center"><?=substr($r->send_date,0,10)?></td>
					                        </tr>
					                 <?}?>
					                </tbody>
					        	</table>
					        </div>
							<div class="align-center mt30"><?=$page_html?></div>
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
			$("#nav li.nav80").addClass("current open");
		});

        function open_page(page) {

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/message/lists");

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
 
            form.submit();
        }
				
     </script>
