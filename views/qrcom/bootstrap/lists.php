<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu19.php'); ?>

<style>
	.search_box {
		display: flex;
	}
</style>

<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>
				고객 목록
				<? if (!empty($total_count)) { ?>
					(총 <span class="list_total"><?= number_format($total_count) ?></span>건)
				<? } ?>
			</h3>
		</div>
		<div class="white_box">
			<? $fields = empty($qc) ? '' : json_decode($qc->qc_form) ?>
			<div class="search_box">
				<? $disabled = empty($qc) ? 'disabled' : '' ?>
				<select id="type_input" oninput="update_search_res()" <?= $disabled ?>>
					<? foreach ($fields as $field) { ?>
						<option value="<?= $field->type ?>" <?= ($search_params['type'] === $field->type) ? 'selected' : '' ?>><?= $field->type ?></option>
					<? } ?>
				</select>
				<input id="search_input" value="<?= $search_params['input'] ?>" <?= $disabled ?>>
				<input type="button" class="btn md" value="조회" onclick="update_search_res()" <?= $disabled ?>>
				<select id="page_unit_input" style="margin-left: auto;">
					<? foreach (array(25, 50, 100) as $unit) { ?>
						<option value="<?= $unit ?>" <?= ($search_params['page_unit'] == $unit) ? 'selected' : '' ?>><?= $unit ?> Line</option>
					<? } ?>
				</select>
				<input type="button" class="btn md" value="등록" onclick="send_db()">
			</div>
			<div class="table_list">
				<? if (empty($qc)) { ?>
					고객이 입력해야하는 정보를 설정해주세요.
				<? } else { ?>
					<table>
						<colgroup>
							<col width="50px" />
							<? foreach ($fields as $field) { ?>
								<col width="*" />
							<? } ?>
							<col width="150px" />
							<col width="75px" />
							<col width="100px" />
						</colgroup>
						<thead>
							<tr>
								<th>
									<label class="checkbox_container">
										<input id="all_checkbox" type="checkbox" oninput="toggle_all_customers()">
										<span class="checkmark"></span>
									</label>
								</th>
								<? foreach ($fields as $field) { ?>
									<? if ($field->use) { ?>
										<th>
											<?= $field->type ?>
										</th>
									<? } ?>
								<? } ?>
								<th>입력일</th>
								<th>등록여부</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
							<? foreach ($inputs as $input) { ?>
								<? $data = json_decode($input->qci_data) ?>
								<tr data-id="<?= $input->qci_id ?>">
									<td>
										<label class="checkbox_container">
											<input class="customer_checkbox" type="checkbox" oninput="check_customer(event)">
											<span class="checkmark"></span>
										</label>
									</td>
									<? foreach ($fields as $field) { ?>
										<? if ($field->use) { ?>
											<? $key = $field->type ?>
											<td><?= $data->$key ?: '-' ?></td>
										<? } ?>
									<? } ?>
									<td>
										<?= $input->qci_insert_date ?>
									</td>
									<td>
										<?= $input->qci_send_db ? '등록' : '미등록' ?>
									</td>
									<td>
	                  <button class="delete_btn" onclick="delete_qci(event)">×</button>
									</td>
								</tr>
							<? } ?>
						</tbody>
					</table>
					<div class="page_cen">
						<?= $page_html ?>
					</div>
				<? } ?>
			</div>
		</div>
	</div>
</div>
<form id="search_form" name="search_form" method="get">

<script>
  const $searchInput = $("#search_input")
  const searchVal = $searchInput.val()
  $searchInput
    .focus()
    .val("")
    .val(searchVal) // 커서 위치를 맨 끝으로 옮기기
	$searchInput.on("keydown", evt => {
		if (evt.key === "Enter") update_search_res()
	})

	const $allCheckbox = $("#all_checkbox")

	function toggle_all_customers() {
		$(".customer_checkbox").prop("checked", $allCheckbox.is(":checked"))
	}
	function check_customer(evt) {
		const everyCheckboxChecked = Array.from($(".customer_checkbox"))
			.map($)
			.every($checkbox => $checkbox.is(":checked"))
		$allCheckbox.prop("checked", everyCheckboxChecked)
	}

	function send_db() {
		alert("미구현.")
	}

	function delete_qci(evt) {
		if (!confirm("삭제하시겠습니까?")) return
		const $row = $(evt.currentTarget).closest("tr")
		$.ajax({
			url: "/qrcom/api_remove_input",
			type: "POST",
			data: {
        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
				id: $row[0].dataset.id,
			},
			success() {
				alert("삭제하였습니다.")
				$row.remove()
			}
		})
	}

	function update_search_res(page = 1) {
		const $form = $("#search_form")
		const pageUnit = $("#page_unit_input").val()
		const type = $("#type_input").val()
		const input = $("#search_input").val()
		$form.empty()
		$form.append(
			create_form_input("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>"),
			create_form_input("page", page),
			create_form_input("page_unit", pageUnit),
			create_form_input("type", type),
			create_form_input("input", input),
		)
		console.log($form)
		$form.submit()
	}
	function open_page(page) {
		update_search_res(page)
	}
  function create_form_input(name, value) {
    return $("<input>")
      .attr("type", "hidden")
      .attr("name", name)
      .attr("value", value)
  }
</script>
