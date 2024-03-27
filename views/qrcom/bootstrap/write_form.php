<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu19.php'); ?>

<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />

<link href="/views/qrcom/bootstrap/css/write_form.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

<div id="mArticle">
  <div class="form_section">
    <div class="white_box">
      <input type="button" class="btn md" value="QR코드 다운로드" onclick="download_qc_qr()" />
      &nbsp;
      입력 페이지:
      <a target="_blank" href="/qrcom/qr/<?= $this->member->item('mem_id') ?>">
        <?= $_SERVER['HTTP_HOST'].'/qrcom/qr/'.$this->member->item('mem_id') ?>
      </a>
      <div>
        <table id="input_table">
          <colgroup>
            <col width="50px" />
            <col width="200px" />
            <col width="50px" />
          </colgroup>
          <thead>
            <tr>
              <th>사용</th>
              <th>종류</th>
              <th>필수</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <input type="button" class="btn md" value="저장" onclick="qc_save()" />
      <input type="button" class="btn md" value="종류 추가" onclick="qc_add_field()" />
    </div>
  </div>
</div>
<a id="qr_download_btn" style="display: none;"></a>
<div id="qr" style="display: none;"></div>

<?
  $default_fields = array(
    (object) array(
      'type' => '이름',
      'use' => true,
      'required' => true,
    ),
    (object) array(
      'type' => '전화번호',
      'use' => true,
      'required' => true,
    ),
    (object) array(
      'type' => '주소',
      'use' => true,
      'required' => false,
    )
  )
?>
<script>
  const memId = <?= $this->member->item('mem_id') ?>;

  const $inputTbody = $("#input_table tbody")

  const fields = <?= empty($qc) ? json_encode($default_fields) : $qc->qc_form ?>;
  fields.forEach(qc_add_field)

  function qc_add_field(field = { type: "", use: true, required: false }) {
    $inputTbody.append(
      $("<tr>")
        .addClass("field")
        .append(
          $("<td>")
            .append(
              $("<label>")
                .addClass("checkbox_container")
                .append(
                  $("<input>")
                    .addClass("qc_use")
                    .attr("type", "checkbox")
                    .prop("checked", field.use),
                  $("<span>")
                    .addClass("checkmark"),
                )
            ),
          $("<td>")
            .append(
              $("<input>")
                .addClass("qc_type")
                .val(field.type)
            ),
          $("<td>")
            .append(
              $("<label>")
                .addClass("checkbox_container")
                .append(
                  $("<input>")
                    .addClass("qc_required")
                    .attr("type", "checkbox")
                    .prop("checked", field.required),
                  $("<span>")
                    .addClass("checkmark"),
                )
            ),
        )
    )
  }
  function qc_save() {
    const $fields = $(".field")

    const fields = Array.from($fields).map($).map($field => ({
      type: $field.find(".qc_type").val(),
      use: $field.find(".qc_use").is(":checked"),
      required: $field.find(".qc_required").is(":checked"),
    }))

    $.ajax({
      url: "/qrcom/api_write_form",
      type: "POST",
      data: {
        fields: JSON.stringify(fields),
      },
      success() {
        alert("저장되었습니다.")
      }
    })
  }
  function download_qc_qr() {
  	const url = `${new URL(location.href).origin}/qrcom/qr/${memId}`
    const qrEl = document.getElementById("qr")
    $(qrEl).empty()
    new QRCode(qrEl, url)
    $(qrEl).find("img").on("load", () => {
      const $downloadBtn = $("#qr_download_btn")
        .attr("download", `qr_${memId}.png`)
      $(qrEl).find("canvas")[0].toBlob(blob => {
        $downloadBtn.attr("href", URL.createObjectURL(blob))
        $downloadBtn[0].click()
      })
    })
  }
</script>
