<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<link href="/css/common.css?v=<?=date("ymdHis")?>" rel="stylesheet" type="text/css">
<link href="/views/_layout/bootstrap/css/style.css" rel="stylesheet">
<link href="/views/qrcom/bootstrap/css/input.css" rel="stylesheet">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<main id="main">
	<h1>정보 입력</h1>
	<? foreach (json_decode($qc->qc_form) as $field) { ?>
		<? if ($field->use) { ?>
			<div class="qc_field" data-type="<?= $field->type ?>" data-required="<?= json_encode(boolval($field->required)) ?>">
				<div class="qc_field_type"><?= $field->type ?><? if ($field->required) { ?><span class="required">*</span><? } ?></div>
				<input placeholder="<?= $field->type ?> 입력" />
			</div>
		<? } ?>
	<? } ?>
	<div>
		<label class="checkbox_container">
			<input id="privacy_agree" type="checkbox" onclick="update_submit_disabled()">
			<span class="checkmark"></span>
			개인정보 이용 동의
		</label>
		<label class="checkbox_container">
			<input id="marketing_agree" type="checkbox" onclick="update_submit_disabled()">
			<span class="checkmark"></span>
			마케팅 동의
		</label>
	</div>
	<input id="submit_btn" type="button" value="제출" onclick="submit()" disabled>
</main>

<script>
	function update_submit_disabled() {
		$("#submit_btn").prop("disabled", !$("#privacy_agree").is(":checked") || !$("#marketing_agree").is(":checked"))
	}
	function submit() {
		let interrupted = false
		const data = Array.from($(".qc_field")).reduce((acc, fieldEl) => {
			const content = $(fieldEl).find("input").val()
			if (fieldEl.dataset.required === 'true' && !content.trim()) {
				interrupted = true
				return acc
			}
			acc[fieldEl.dataset.type] = content
			return acc
		}, {})

		if (interrupted) {
			alert(`필수 정보가 입력되지 않았습니다.`)
			return
		}

		$.ajax({
			url: "/qrcom/api_write_input",
			type: "POST",
			data: {
        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
				id: <?= $qc->qc_id ?>,
				data: JSON.stringify(data),
			},
			success() {
				alert("제출하였습니다.")
			}
		})
	}
</script>
