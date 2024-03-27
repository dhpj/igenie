<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<style type="text/css">
.board-faq h3{height:40px;margin-bottom:40px;padding:12px 0 0 15px;border-bottom:1px solid #d9d9d9;background:#f9f9f9;font-size:12px}
.board-faq h4{margin-bottom:0;padding-bottom:0;border-bottom:none;font-size:14px;font-weight:700;color:#3b1e1e}
.board-faq .wrap{width:100%;margin:0 auto}
</style>
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	<?php echo element('fgr_title', element('faqgroup', $view)); ?>
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="white_box">
			<form action="<?php echo current_url(); ?>" onSubmit="return faqSearch(this)">
				<input type="text" name="skeyword" value="<?php echo html_escape($this->input->get('skeyword')); ?>" style="width: calc(100% - 300px);" placeholder="검색어를 입력하세요" />
				<button class="btn md yellow" type="submit"><i class="fa fa-search"></i></button>
			</form>
			<script type="text/javascript">
			//<![CDATA[
			function faqSearch(f) {
					var skeyword = f.skeyword.value.replace(/(^\s*)|(\s*$)/g,'');
					if (skeyword.length < 2) {
							alert('2글자 이상으로 검색해 주세요');
							f.skeyword.focus();
							return false;
					}
					return true;
			}
			//]]>
			</script>
		</div>
		<div class="white_box mg_t20">
			<div class="panel-group mt20" id="accordion" role="tablist" aria-multiselectable="true">
			<?php
			$i = 0;
			if (element('list', element('data', $view))) {
					foreach (element('list', element('data', $view)) as $result) {
			?>
					<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading_<?php echo $i; ?>">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $i; ?>" class="<?php echo ($i>0) ? 'collapsed' : ''; ?> faq_title" >
											<div >
													<h4 class="panel-title">
															<?php echo element('title', $result); ?>
													</h4>
											</div>
									</a>
							</div>
							<div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse <?php echo $i === 0 ? 'in' : ''; ?>" role="tabpanel" aria-labelledby="heading_<?php echo $i; ?>">
									<div class="panel-body">
											<?php echo element('content', $result); ?>
									</div>
							</div>
					</div>
			<?php
							$i++;
					}
			}
			if ( ! element('list', element('data', $view))) {
			?>
					<div class="panel panel-default">
							<div class="panel-body nopost">내용이 없습니다.</div>
					</div>
			<?php
			}
			?>
			</div>

			<nav><?php echo element('paging', $view); ?></nav>

			<?php if ($this->member->is_admin() === 'super') { ?>
				<div class="text-center mb20">
						<a href="<?php echo admin_url('page/faq'); ?>?fgr_id=<?php echo element('fgr_id', element('faqgroup', $view)); ?>" class="btn_st3 mg_b50" target="_blank" title="FAQ 수정">FAQ 수정</a>
				</div>
			<?php } ?>

		</div>
	</div>
</div>




<div class="board-faq">
	<div class="wrap">




	</div>
	</div>
