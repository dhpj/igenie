<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>무통장입금목록</h3>
						</div>
						<?php
						echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
						echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
						$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
						echo form_open(current_full_url(), $attributes);
						?>
						<div class="white_box">
							<div class="search_box search_period" id="status">
				                <ul class="search_period">
								    <a id="STATUS_ALL" value="A"><li class="submit <? if($param['dep_status']== 'A' ) echo "active";?>">전체내역</li></a>
								    <a id="STATUS_N" value="0"><li class="submit <? if($param['dep_status']== '0' ) echo "active";?>">미수내역</li></a>
								    <a id="STATUS_Y" value="1"><li class="submit <? if($param['dep_status']== '1' ) echo "active";?>">완료내역</li></a>
							    </ul>
							    <input type="hidden" id="set_date" name="set_date">
							    <span>전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</span>
							</div>
							<table class="table_list">
			                    <thead>
			                        <tr>
			                            <th>회원아이디</th>
			                            <th>회원명</th>
			                            <th>예금주</th>
			                            <!-- <th>이메일</th> -->
			                            <th>연락처</th>
			                            <th>요청일시</th>
			                            <th>예치금</th>
			                            <th>총결제해야할금액</th>
			                            <th>결제한금액</th>
			                            <th>미수금액</th>
			                            <? if($this->member->item('mem_level') >= 100) {?><th>수정</th>
			                            <th><input type="checkbox" name="chkall" id="chkall" /></th>
			                            <? } ?>
			                        </tr>
			                    </thead>
			                    <tbody>
			                    <?
			                       $cnt = 0;
			                       foreach($list as $r) {
			                           $cnt = $cnt + 1;
			                    ?>
			                        <tr class="<?php if ($r->dep_status == '1') {echo 'success';}?>">
			                            <td><?=$r->mem_userid?></td>
			                            <td><?=$r->mem_username?></td>
			                            <td><?=$r->mem_realname?></td>
			                           <!-- <td><?=$r->mem_email?></td> -->
			                            <td><?=$r->mem_phone?></td>
			                            <td><?=display_datetime($r->dep_datetime, 'full')?></td>
			                            <td class="align-right"><?=number_format($r->dep_deposit_request)?> <?php echo $this->depositconfig->item('deposit_unit'); ?></td>
			                            <td class="align-right"><?=number_format($r->dep_cash_request).'원'?></td>
			                            <td class="align-right"><?=number_format($r->dep_cash). '원'?></td>
			                            <td class="align-right"><?=number_format($r->dep_cash_request - abs($r->dep_cash)).'원'?></td>
			                            <? if($this->member->item('mem_level') >= 100) {?>
			                            <td><?php if ( !$r->dep_status){ ?><a href="<?php echo $this->pagedir; ?>/write/<?=$r->dep_id?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a><?php } ?></td>
			                            <td><?php if ( !$r->dep_status){ ?><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /><?php } ?></td>
			                            <? } ?>
			                        </tr>
			                    <?php
			                        }

			                        if ( $cnt == 0) {
			                    ?>
			                        <tr>
			                            <td colspan="12" class="nopost">자료가 없습니다</td>
			                        </tr>
			                    <?php
			                    }
			                    ?>
			                	</tbody>
		                	</table>
		                	<div class="tc"><?=$page_html?></div>
						</div>
						<?php echo form_close(); ?>
					</div>
</div>

<script type="text/javascript">
  $("#nav li.nav100").addClass("current open");

  function open_page(page) {

      var status = $('#status a.active').attr('value');
      var type = $('#searchType').val() || 'all';
      var searchFor = $('#searchStr').val() || '';

      var form = document.createElement("form");
      document.body.appendChild(form);
      form.setAttribute("method", "post");
      form.setAttribute("action", "/biz/manager/pendingbank");
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
      durationField.setAttribute("name", "dep_status");
      durationField.setAttribute("value", status);
      form.appendChild(durationField);
      form.submit();
  }

  $('#status a').click(function() {
      $('#status a').removeClass('active');
      $(this).addClass('active');
      // console.log($('#duration a.active').attr('value'));
      open_page(1);
  });

</script>
