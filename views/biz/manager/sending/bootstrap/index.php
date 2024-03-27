<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
			<li><i class="icon-home"></i>관리자</li>
			<li class="current"><a href="/biz/manager/summary">메시지 발신 상태</a></li>
	</ul>
</div>
<style type="text/css">
.table-responsive th { vertical-align:middle !important; }
.red { color:red; }
</style>
<div class="box content_wrap">
    <div class="box-table">
		<div class="box-table">
			<table class="tpl_ver_form" width="100%">
				<colgroup>
				<col width="20%">
				<col width="20%">
				<col width="30%">
				<col width="*">
				</colgroup>
				<tbody>
					<tr style="height:150px">
						<th><?=$kakao['name']?></th>
						<td>발신대기 : <?=number_format($kakao['row'])?>건</td>
						<td>요청시간 : <?=($kakao['data']) ? $kakao['data'] : "-" ?></td>
						<td>&nbsp;</td>
					</tr>
<?foreach($agent AS $a) {?>
					<tr style="height:150px">
						<th><?=$a['name']?></th>
						<td>발신대기 : <?=number_format($a['row'])?>건</td>
						<td>요청시간 : <?=($a['data']) ? $a['data'] : "-" ?></td>
						<td style="line-height:300%">
<?	if($a['data']) {?>
							<button class="btn" type="button" id="btn3h" onclick="delete_agent('3', '<?=$a['agent']?>');"><i class="icon-trash"></i> 3시간 이상 대기자료 삭제</button><br />
							<button class="btn" type="button" id="btn3h" onclick="delete_agent('5', '<?=$a['agent']?>');"><i class="icon-trash"></i> 5시간 이상 대기자료 삭제</button><br />
							<button class="btn" type="button" id="btn3h" onclick="delete_agent('all', '<?=$a['agent']?>');"><i class="icon-trash"></i> 전체 대기자료 삭제</button>
<?} else { echo "&nbsp;"; }?>
						</td>
					</tr>
<?}?>
					</tr>
				</tbody>
			</table>
		</div>
    </div>
	 <p>&nbsp;</p>
	 <p>※ 알림톡, 친구톡, SMS, LMS, MMS 발신은 bizalimtalk 서버에서 실행됩니다.</p>
	 <p>※ 폰문자는 발신업체측 프로그램으로 처리되므로 장애시 폰문자 업체에 확인하여야 합니다.</p>
	 <p style="color:blue">※ 2만건 이상의 대기자료를 일괄 삭제하면 bizalimtalk 서버에도 과부하가 걸려 문제가 될 수 있으니 시간단위로 분리하여 삭제하는것을 추천합니다.</p>
</div>
<script type="text/javascript">
	function delete_agent(t, a) {
		if(confirm(((t!="all") ? t+"시간 이상" : "전체") + " 대기 자료를 삭제하시겠습니까?")) {
			$.ajax({
				url: "/biz/manager/sending/remove_wait",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", "time":t, "agent":a},
				success: function (json) {
					if(json.code=="ok") {
						document.location.reload();
					} else {
						alert(json.message);
					}
				}
			});
		}
	}
</script>