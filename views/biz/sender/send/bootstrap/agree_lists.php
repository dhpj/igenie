<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>개인정보동의 발송목록 (전체 <b style="color: red"><?=number_format($total_rows)?></b>건)</h3>
      <button class="btn_tr" onclick="location.href='/biz/sender/send/agree';">뒤로가기</button>
    </div>
    <div class="white_box">
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" border="0">
          <colgroup>
            <col width="80px"><?//발신번호?>
            <col width="120px"><?//발신시간?>
            <col width="170px"><?//업체명?>
            <col width=""><?//메시지 내용?>
            <col width="130px"><?//이미지 다운로드?>
            <col width="110px"><?//총발송?>
            <col width="100px"><?//발신타입?>
            <col width="210px"><?//발송내역?>
            <col width="110px"><?//메시지확인?>
            <col width="110px"><?//수신동의?>
          </colgroup>
          <thead>
            <tr>
              <th>발신번호</th>
              <th>발신시간</th>
              <th>업체명</th>
              <th>메시지 내용</th>
              <th>개인정보 전문</th>
              <th>총발송</th>
              <th>발신타입</th>
              <th>발송내역</th>
              <th>메시지확인</th>
              <th>수신동의</th>
            </tr>
          </thead>
          <tbody>
            <?
				foreach($list as $r) {
					$reservedStatus = ''; // 예약관련 정보 html class명이나 비교에 사용. 예약건 : reserve, 아니면 ''
					$sendedDate = '';   // 발신 시간; 예약이 있을시에는 예약시간.
					$companyName = '';  // 회사명 또는 사용자 명
					$friendName = '';   // 플러스 친구 명 앞두에 ()포함.
					$summaryContent = '';   // 문자 내용 summary

					//$summaryContent = '<p>'. substr($r->mst_content, 0, 100) .'...</p>';
					//$summaryContent = '<p>'. mb_substr($r->mst_content, 0, 60, "UTF-8") .'...</p>';
					$summaryContent = '<p>'. $this->funn->StringCut($r->mst_content, 60, "..") .'</p>';
					$summaryContent = str_replace("\n", '</p><p>', $summaryContent);
					//$summaryContent = preg_replace('\n', '</p><p style="display: block">', $summaryContent);

					$sendedTypeText1 = '-'; // 1차 발신 종류 명칭
					$sendedTypeText2 = '-'; // 2차 발신 종류 명칭

					$sucessCount1 = '-';  // 1차 발송 성공 수량
					$resultDelayCount1 = '';  //1차 발송 결과 대기 수량(문자에 한함)
					$failCount1 = '-';    // 1차 발송 실패 수량
					$sucessCount2 = '-';  // 2차 발송 성공 수량
					$resultDelayCount2 = '';  //2차 발송 결과 대기 수량
					$failCount2 = '-';    // 2차 발송 실패 수량

					$statusClass1 = '';  // 상태 span class 1차
					$statusText1 = '';   // 상태 텍스트 1차
					$statusClass2 = '';  // 상태 span class 2차
					$statusText2 = '';   // 상태 텍스트 2차

					$kakaoTalkCount = 0;  // 알림톡(성공/실패), 친구톡(성공/실패), 친구톡이미지(성공/실패) 합계
					$messageCount = 0;    // 웹(A)LMS, 웹(A)SMS, 웹(A)MMS, 웹(B)LMS, 웹(B)SMS, 웹(B)MMS 각각 성공/실패 합계
					$reservedFlag = false;
					if ($r->mst_reserved_dt!="00000000000000") {

						$senderDate = $this->funn->format_date($r->mst_reserved_dt, '-', 14);

						if (strtotime($senderDate) > strtotime(date("Y-m-d H:i:s"))) {
							$reservedStatus = 'reserve';
							$statusClass1 = 'sending_stat reserve';
							$statusText1 = '발송예약';
							$statusClass2 = '';
							$statusText2 = '';
							$reservedFlag = true;
						}
					} else {
						$senderDate = $r->mst_datetime;
					}

					if ($r->spf_company) {
						$companyName = $r->spf_company;
						$friendName = '('.$r->spf_friend.')';
					} else {
						$companyName = $r->mem_username;
					}

					if ($r->mst_ft > 0 || $r->mst_err_ft > 0) {
						$sucessCount1 = number_format($r->mst_ft);
						$failCount1 = number_format($r->mst_err_ft);
					}
					if($r->mst_type1 == "at"){ //2021-01-05 2차 알림톡 추가
						if ($r->mst_at > 0 || $r->mst_err_at > 0) {
							$sucessCount1 = number_format($r->mst_at);
							$failCount1 = number_format($r->mst_err_at);
						}
					}
					if ($r->mst_ft_img > 0 || $r->mst_err_ft_img > 0) {
						$sucessCount1 = number_format($r->mst_ft_img);
						$failCount1 = number_format($r->mst_err_ft_img);
					}
            ?>
			<tr class="<?=$reservedStatus?>">
              <td><?=$r->mst_id?></td>
              <td><?=$senderDate?></td>
              <td class="td_name" onclick="go_view('<?=$r->mst_agreeid?>', '<?=$reservedStatus?>');" style="cursor: pointer;"><?=$companyName ."<br>". $friendName ?></td>
              <td class="td_msg tl" onclick="go_view('<?=$r->mst_agreeid?>', '<?=$reservedStatus?>');" style="cursor: pointer;">
                <div class="gn_tooltip">
                  <?=$summaryContent ?>
                  <span class="gn_tooltiptext">
                    <?=str_replace("\"", "\'",str_replace("\n", "<br />", $r->mst_content))?>
                  </span>
                </div>
              </td>
              <td>
                <button class="btn md" onclick="window.open('<?=$r->agi_imgpath?>', 'agi_imgpath', '');">자세히보기</button>
              </td>
              <td class="num_total"><?=number_format($r->mst_qty)?></td>
              <td>알림톡</td>
              <td>
                <div class="num_wrap" style="border:none; padding:0; margin:0;">
                  <span class="num_sucess"><?=$sucessCount1 ?><span class="stb"><?=$resultDelayCount1 ?></span></span>
                  <span class="num_fail"><?=$failCount1 ?></span>
                </div>
              </td>
              <td><?=number_format($r->view_cnt)?> 건</td>
              <td><?=number_format($r->agree_cnt)?> 건</td>
            </tr>
            <?
				}
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="page_cen"><?=$page_html?></div>
</div>
<script>
	//발송내역 상세화면 이동
	function go_view(mst_id, status) {
		if(status == "reserve"){
			alert("발송예약된 상태입니다.");
		}else{
			location.href = "/biz/sender/send/agree/view/" + mst_id;
		}
	}

	//검색
	function open_page(page) {
		var url = "?page="+ page;
		location.href = url;
	}
</script>
