<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>가상계좌입금목록</h3>
			<span class="btn_link_bank"><i class="xi-link"></i> <a href="https://iniweb.inicis.com/security/login.do" target="_blank">KG이니시스 바로가기</a></span>
      <span class="kgid">상점아이디 : dhncorp001</span>
		</div>
		<div class="white_box">
			<?php
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
			echo form_open(current_full_url(), $attributes);
			?>
			<div class="search_box search_period" role="group">
				<ul class="search_period">
					<a href="?"><li class="<?php echo ( ! $this->input->get('dep_status')) ? 'active' : ''; ?>">전체내역</li></a>
					<a href="?dep_status=N"><li class="<?php echo ($this->input->get('dep_status') === 'N') ? 'active' : ''; ?>">미수내역</li></a>
					<a href="?dep_status=Y"><li class="<?php echo ($this->input->get('dep_status') === 'Y') ? 'active' : ''; ?>">완료내역</li></a>
				</ul>
				<input type="hidden" id="set_date" name="set_date">
				<span>전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</span>
				<div class="fr">
					<!--<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">-->
						<div class="box-search">
							 <select class="" name="sfield" id="sfield">
								<?php echo element('search_option', $view); ?>
							</select>
							<input type="text" class="" name="skeyword" id="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." onKeypress="if(event.keyCode==13){ open_page(1); }">
								<button class="btn md yellow" name="search_submit" type="button" onClick="open_page(1);">검색</button>
								<a href="/biz/manager/vbank" class="btn md" style="display:inline-block; line-height:34px;">목록으로</a>
						</div>
					<!--</form>-->
				</div>
			</div>
			<table class="table_list">
				<colgroup>
					<!--<col width="3%">-->
					<col width="9%"><?//회원아이디?>
					<col width="*"><?//회원명?>
					<col width="13%"><?//입금자?>
					<col width="14%"><?//이메일?>
					<col width="10%"><?//연락처?>
					<col width="8%"><?//요청일시?>
					<!--<col width="9%"><?//예치금?>-->
					<col width="9%"><?//총결제해야할금액?>
					<col width="9%"><?//결제한금액?>
					<col width="9%"><?//미수금액?>
					<col width="5%"><?//수정?>
				</colgroup>
				<thead>
					<tr>
						<!--<th><input type="checkbox" name="chkall" id="chkall" /></th>-->
						<th>회원아이디</th>
						<th>회원명</th>
						<th>입금자</th>
						<th>이메일</th>
						<th>연락처</th>
						<th>요청일시</th>
						<!--<th>예치금</th>-->
						<th>총결제해야할금액</th>
						<th>결제한금액</th>
						<th>미수금액</th>
						<th>수정</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if (element('list', element('data', $view))) {
					foreach (element('list', element('data', $view)) as $result) {
				?>
					<tr class="<?php if (element('dep_status', $result) === '1') {echo 'success';}?>">
						<!--<td><?php if ( ! element('dep_status', $result)) { ?><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /><?php } ?></td>-->
						<td><?php echo html_escape(element('mem_userid', $result)); ?></td><?//회원아이디?>
						<td><?php echo element('mem_username', $result); ?></td><?//회원명?>
						<td><?php echo html_escape(element('mem_realname', $result)); ?></td><?//입금자?>
						<td><?php echo html_escape(element('mem_email', $result)); ?></td><?//이메일?>
						<td><?php echo $this->funn->format_phone(html_escape(element('mem_phone', $result)),"-"); ?></td><?//연락처?>
						<td><?php echo display_datetime(element('dep_datetime', $result), 'full'); ?></td><?//요청일시?>
						<!--<td class="align-right"><?php echo number_format(abs(element('dep_deposit_request', $result))); ?> <?php echo $this->depositconfig->item('deposit_unit'); ?></td><?//예치금?>-->
						<td class="align-right"><?php echo number_format(abs(element('dep_cash_request', $result))) . '원'; ?></td><?//총결제해야할금액?>
						<td class="align-right"><?php echo number_format(abs(element('dep_cash', $result))) . '원'; ?></td><?//결제한금액?>
						<td class="align-right"><?php echo number_format(abs(element('dep_cash_request', $result))-abs(element('dep_cash', $result))) . '원'; ?></td><?//미수금액?>
						<td><?php if ( ! element('dep_status', $result)) { ?><a href="<?php echo $this->pagedir; ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a><?php } ?></td><?//수정?>

					</tr>
				<?php
					}
				}
				if ( ! element('list', element('data', $view))) {
				?>
					<tr>
						<td colspan="12" class="nopost">자료가 없습니다</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			<?php
			ob_start();
			?>
				<!--<div class="btn-group" role="group" aria-label="...">
					<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
					<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
				</div>-->
			<?php
			$buttons = ob_get_contents();
			ob_end_flush();
			?>

			<?//php echo element('paging', $view); ?>
			<div class="page_cen"><?=$page_html?></div>
			<div class="mobile_none"><?php echo admin_listnum_selectbox();?></div>
			<?php echo $buttons; ?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$("#nav li.nav100").addClass("current open");

	//검색
	function open_page(page){
		var sfield = $("#sfield").val(); //검색타입
		var skeyword = $("#skeyword").val(); //검색내용
		var dep_status = "<?=$this->input->get('dep_status')?>"; //내역구분
		var pram = "";
		if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;
		if(dep_status != "") pram += "&dep_status="+ dep_status;
		//alert("page : "+ page +", tagid : "+ tagid);
		location.href = "?page="+ page + pram;
	}
</script>
