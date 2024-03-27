<div class="tit_wrap">
	이벤트 발송목록
</div>
<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>발송목록 (전체 <b style="color: red"><?=number_format($total_rows)?></b>건)</h3>
      <!-- <button class="btn md fr mt10" onclick="location.href='/biz/sender/send/agree';">뒤로가기</button> -->
    </div>
    <div class="inner_content">
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" border="0">
          <colgroup>
            <col width="80px"><?//발신번호?>
            <col width="200px"><?//발신시간?>
            <col width="250px"><?//업체명?>
            <col width=""><?//메시지 내용?>
            <col width="120px"><?//발신타입?>
            <col width="120px"><?//메시지확인?>
            <col width="120px"><?//수신동의?>
            <col width="120px">
          </colgroup>
          <thead>
            <tr>
              <th>발신번호</th>
              <th>발신시간</th>
              <th>업체명</th>
              <th>이벤트</th>
              <th>총발송</th>
              <th>확인</th>
              <th>참여</th>
              <th>수령</th>
            </tr>
          </thead>
          <tbody>
            <?
				foreach($list as $r) {

            ?>
			<tr class="<?=$reservedStatus?>">
              <td><?=$r->esi_mst_id?></td>
              <td><?=$r->esi_cre_date?></td>
              <td class="td_name" onclick="go_view('<?=$r->esi_idx?>');" style="cursor: pointer;"><?=$r->mem_username ?></td>
              <td class="tl" onclick="go_view('<?=$r->esi_idx?>');" style="cursor: pointer;display: flex;align-items: center;">
                  <div style="float: left;position: relative;width: 120px;height: 150px;overflow: hidden;">
                      <img src="<?=$r->emd_img_url?>" width="120px;"/>
                  </div>
                  <div style="float:left;margin-left:20px;">
                      <span><b><?=$r->emd_title?></b></span><br>
                      <span><?=$r->emd_sub_info?></span>
                  </div>
              </td>

              <td class="num_total"><?=number_format($r->esi_send_cnt)?></td>

              <td><?=number_format($r->esd1cnt)?> 건</td>
              <td><?=number_format($r->esd2cnt)?> 건</td>
              <td><?=number_format($r->esd3cnt)?> 건</td>
            </tr>
            <?
				}
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="align-center mt30"><?=$page_html?></div>
</div>
<script>
	//발송내역 상세화면 이동
	function go_view(id) {

		location.href = "/spop/evemaker/view/" + id;

	}

	//검색
	function open_page(page) {
		var url = "?page="+ page;
		location.href = url;
	}
</script>
