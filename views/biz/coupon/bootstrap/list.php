<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<!-- 타이틀 영역
<div class="tit_wrap">
	전용쿠폰 알림톡 보내기
</div>
타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>쿠폰 목록<p style="display: inline-block; margin-left: 10px;">(전체 <strong style="color: #f00"><?=number_format($total_rows)?></strong>개)</p></h3>
		</div>
		<div class="white_box">
			<!-- 테이블 상단 검색 영역 -->
			<div class="search_wrap clearfix" id="duration">
				<div class="fl"><button class="btn_st1" onclick="location.href='/biz/coupon/write'"><span class="material-icons" style="vertical-align: middle; font-size: 18px">add</span> 신규쿠폰 등록</button></div>
				<ul class="search_period fr" style="margin-right: 0;">
					<li id="today" value="today">오늘</li>
					<li id="week" value="week">1주일</li>
					<li id="1month" value="1month">1개월</li>
					<li id="3month" value="3month">3개월</li>
					<li id="6month" value="6month">6개월</li>
				</ul>

				<input type="hidden" id="set_date" name="set_date">
			</div>
			<div class="table_list" id="history_content">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
							<col width="*"><?//쿠폰명?>
							<col width="10%"><?//잔여수/발급수?>
							<col width="10%"><?//작성일자?>
							<col width="10%"><?//시작일?>
							<col width="10%"><?//종료일?>
							<col width="8%"><?//당첨자조회?>
							<col width="8%"><?//상태?>
							<col width="11%"><?//관리?>
					</colgroup>
					<thead>
						<tr>
							<th>쿠폰명</th>
							<th>잔여수/발급수</th>
							<th>작성일자</th>
							<th>시작일</th>
							<th>종료일</th>
							<th>상태</th>
							<th>당첨자조회</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
						<? if (count($list) > 0) {
								foreach($list as $r) {
						?>
						<tr>
							<!--td><?=$r->cc_creation_date.'/'.$r->cc_type.'/'.$r->cc_idx?></td-->
							<td class="tl">
							<? if($r->cc_type == 'AT' || empty($r->cc_type)) { ?>
								<a href="/biz/coupon/view/<?=$r->cc_idx?>" style="height: 100px; position: relative;display: block; line-height: 100px;" ><span style="padding-left:120px;"><?=$r->cc_title?></span><span style="position: absolute;top:0;left:0;"><img role="presentation" src="<?=$r->cc_img_url1?>" style="height:100px;"></span></a><?//쿠폰명?>
							<? } else { ?>
								<a href="/biz/coupon/viewft/<?=$r->cc_idx?>" style="height:100px; position: relative;display: block; line-height: 100px;" ><span style="padding-left:120px;"><?=$r->cc_title?></span><span style="position: absolute;top:0;left:0;"><img role="presentation" src="<?=$r->cc_img_url1?>" style="height:100px;"></span></a><?//쿠폰명?>
							<? } ?>
							</td>
							<td><? echo $r->used_cnt." / ".$r->cc_coupon_qty;?></td><?//잔여수/발급수?>
							<td><?=substr($r->cc_creation_date, 0, 10)?></td><?//작성일자?>
							<td><?=$r->cc_start_date?></td><?//시작일?>
							<td><?=$r->cc_end_date?></td><?//종료일?>
							<td><?echo ($r->cc_status=="P") ? "발행" : "임시저장"; ?></td><?//상태?>
							<td>
							<? if($r->cc_status=="P"){ ?>
							<button type="button " class="btn_st_small" onclick="open_result_list('<?=$r->cc_idx?>');"><i class="xi-search"></i> 당첨자조회</button>
							<? } ?>
							</td><?//당첨자조회?>
							<td style="white-space: nowrap; height:100px;">
								<? if(cdate("Y-m-d") > $r->cc_end_date){ ?>
									<button type="button " class="btn_st_gray" style="cursor:no-drop;" disabled>기간만료</button>
								<? }else if($r->cc_status !="P"){ ?>
									<button type="button " class="btn_st_gray" style="cursor:no-drop;" disabled>발송대기</button>
								<? }else{ ?>
									<button type="button " class="btn_st_yellow" onclick="go_send('<?=$r->cc_tpl_id?>', '<?=$r->profile_key?>', '<?=$r->cc_idx?>')">발송하기</button>
								<? } ?>
								<button type="button " class="btn_st_black" onclick="coupon_delete('<?=$r->cc_idx?>')">삭제하기</button>
							</td><?//관리?>
						</tr>
						<?    }
						} else {?>
						<tr>
							<td colspan="8">
								<div class="nodata">
									<i class="material-icons icon_error">error_outline</i>
									<p>현재 발행된 쿠폰이 없습니다.</p>
									<button class="btn_st1" onclick="location.href='/biz/coupon/write'"><span class="material-icons" style="vertical-align: middle; font-size: 18px">add</span> 신규쿠폰 등록</button>
								</div>
							</td>
						</tr>
						<? } ?>
					</tbody>
				</table>
			</div>
			<div class="page_cen"><?=$page_html?></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#nav li.nav10").addClass("current open");

	$('.searchBox input').unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			open_page(1);
		}
	});

	//window.onload = function () {
		var set_date = '<?=($param['duration']) ? $param['duration'] : 'week'?>';
		$("#"+set_date).addClass('active');
	//};

	function open_page(page) {
		//var duration = $('#duration a.active').attr('value') || 'week';
		var duration = $('#duration ul li.active').attr('value') || 'week';
		var type = $('#searchType').val() || 'all';
		var searchFor = $('#searchStr').val() || '';

		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/coupon/index");
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

	//발송하기
	function go_send(tmp, pro, idx) {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/coupon");

		var cfrsField = document.createElement("input");
		cfrsField.setAttribute("type", "hidden");
		cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(cfrsField);

		var tmpField = document.createElement("input");
		tmpField.setAttribute("type", "hidden");
		tmpField.setAttribute("name", "tmp_code");
		tmpField.setAttribute("value", tmp);
		form.appendChild(tmpField);

		var proField = document.createElement("input");
		proField.setAttribute("type", "hidden");
		proField.setAttribute("name", "tmp_profile");
		proField.setAttribute("value", pro);
		form.appendChild(proField);

		var idxField = document.createElement("input");
		idxField.setAttribute("type", "hidden");
		idxField.setAttribute("name", "iscoupon");
		idxField.setAttribute("value", idx);
		form.appendChild(idxField);

		form.submit();
	}

	//삭제
	function coupon_delete(idx) {
		if(confirm("정말 삭제 하시겠습니까?")){
			location.href = "/biz/coupon/coupon_delete/"+ idx;
		}
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
		form.setAttribute("action", "/sender/history_dt");
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

<!-- 당첨자 조회 모달 -->
<div class="modal select fade" id="myModalUserResultlist" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="modal_title">결과조회</div>
			<div class="modal-body">
				<div class="search_wrap" style="text-align: right;">
					<input type="hidden" id="cc_idx" name="cc_idx">
					<input type="text" id="search_phn" name="search_phn" placeholder="전화번호를 입력해 주세요" onKeypress="if(event.keyCode==13){ open_page_result(1); }">
					<button type="button" class="btn md yellow" style="margin-left: 10px" onclick="open_page_result(1)">검색</button>
				</div>
				<div class="content" id="modal_user_result_list"></div>
			</div>
		</div>
	</div>
</div>
<script>
	//당첨자 조회
	function open_result_list(cc_idx) {
 		$('#cc_idx').val(cc_idx);
		$("#myModalUserResultlist").modal({backdrop: 'static'});
 		$("#myModalUserResultlist").on('shown.bs.modal', function () {
 			$('.uniform').uniform();
 			$('select.select2').select2();
 		});
 		$('#myModalUserResultlist').unbind("keyup").keyup(function (e) {
 			var code = e.which;
 			if (code == 27) {
 				$(".btn-default.dismiss").click();
 			}
 		});
 		open_page_resultM('1');
 	}
    function open_page_resultM(page) {
		var cc_idx = $('#cc_idx').val() || '';
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';
		$('#myModalUserResultlist .content').html('').load(
			"/biz/coupon/coupon_result",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				'page': page,
				'coupon_id':cc_idx
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			}
		);
    }
</script>
