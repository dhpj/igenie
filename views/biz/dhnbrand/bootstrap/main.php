<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu14.php');
?>
<!-- //3차 메뉴 -->
<!-- 컨텐츠 전체 영역 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
            <h3>브랜드 목록 (전체 <b style="color: red" id="total_rows"><?=number_format($total_rows)?></b>개)</h3>
            <button class="btn_tr" onclick="get_brand_list()">브랜드 모두 가져오기</button>
		</div>
<?
   $uri= $_SERVER['REQUEST_URI'];
  ?>
   <div class="box-search">
        <select class="" name="sfield" id="sfield">
             <option value="1"  <?=($this->input->get("sfield")=='1')? ' selected' : '' ?>>업체명</option>
             <option value="2"  <?=($this->input->get("sfield")=='2')? ' selected' : '' ?>>브랜드ID</option>
        </select>
          <input type="text" class="" name="skeyword" id="skeyword"  value="<?=$skeyword?>"  placeholder="검색어를 입력하세요" onkeypress="if(event.keyCode==13){ open_page(1); }">
          <button class="btn md yellow" id="search_btn" name="search_submit" type="button" onclick="open_page(1);">검색</button>
          <a href="/dhnbiz/brand" class="btn md" style="<?=(empty($skeyword))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>
    </div>
	<div class="white_box mg_t10">

		<div class="table_top">
		<!-- <p class="notice">템플릿 등록후 꼭! 검수요청 버튼을 클릭하셔야 정상적으로 요청이 완료됩니다.</p> -->
		</div>
		<div class="table_list">
			<table>
				<colgroup>
                    <col width="200px"><?//등록일?>
                    <col width="180px"><?//브랜드ID?>
                    <col width="180px"><?//발신번호?>
                    <col width="180px"><?//소속?>
					<col width="*"><?//업체명?>

					<!-- <col width="260px"><?//브랜드키?> -->

					<!-- <col width="85px"><?//상태?> -->


                    <col width="200px"><?//등록버튼?>
				</colgroup>
				<thead>
				<tr>
                    <th>등록일</th>
                    <th>브랜드ID</th>
                    <th>대표발신번호</th>
                    <th>소속</th>
					<th>업체명</th>

					<!-- <th>브랜드키</th> -->

					<!-- <th>상태</th> -->


					<th>관리</th>
				</tr>
				</thead>
				<tbody>
						<!-- 템플릿이슈 수정 시작 -->
						<?$num = 0;
						foreach($list as $r) { $num++;?>
						<tr>
							<!-- 템플릿이슈 수정 끝 -->
                            <td><?=$r->brd_date?></td>
                            <td style="word-break:break-all !important;">
								<?=$r->brd_brand?><?//템플릿코드?>
							</td>
                            <td><?=(!empty($r->brd_sms_callback))? $this->funn->format_phone($r->brd_sms_callback,"-") : "번호 미지정"?></td>
                            <td><?=$r->adminname?></td>
							<td style="cursor:pointer;" >
								<?=$r->brd_company?><?//업체명?>
							</td>

							<!-- <td style="cursor:pointer;text-align:left;" onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
								<?=$r->brd_brand_key?><?//템플릿명?>
							</td> -->

                            <!-- <td><?=$r->brd_status?></td> -->


                            <td>
                                <button class="btn md yellow" onclick="location.href='/dhnbiz/brand/tel?comp=<?=$r->brd_id?>'">발신번호</button>
                                <button class="btn md yellow" onclick="location.href='/dhnbiz/brand/template?comp=<?=$r->brd_id?>'">템플릿</button>
                            </td>
						</tr>

						<?}?>
				</tbody>
			</table>
            <div class="tc"><?=$page_html?></div>
		</div>

	</div>
</div>


<script type="text/javascript">

    //브랜드리스트 가져오기
	function get_brand_list(){
        jsLoading();
		$.ajax({
			url: "/dhnbiz/brand/get_brand_list",
			type: "POST",
			data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>',
				   // 'senderKey':senderKey,
				   // 'tcode': tcode
               },
			beforeSend:function(){
				$('#overlay').fadeIn();
			},
			complete:function(){
				$('#overlay').fadeOut();
			},
			success: function (json) {
                hideLoading();
                showSnackbar(json.msg, 2000);
                if(json.code=='1'){
                        setTimeout(function() {
    					location.href = "/dhnbiz/brand";
    				}, 2000); //1초 지연
                }
			},
			error: function () {
                hideLoading();
                showSnackbar("처리되지 않았습니다.", 1500);
				// alert("처리되지 않았습니다.");
				return;
			}
		});
	}

    // //챗봇리스트 가져오기
	// function chatbot_list(brandid, divid){
    //     jsLoading();
	// 	$.ajax({
	// 		url: "/dhnbiz/brand/chatbot_list",
	// 		type: "POST",
	// 		data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>',
	// 			   // 'senderKey':senderKey,
	// 			   'brandid': brandid
    //            },
	// 		beforeSend:function(){
	// 			$('#overlay').fadeIn();
	// 		},
	// 		complete:function(){
	// 			$('#overlay').fadeOut();
	// 		},
	// 		success: function (json) {
    //             hideLoading();
    //             var html = '';
    //             if(json.code=='0'){
    //                 showSnackbar(json.msg, 2000);
    //                 html = "<td colspan='5'>등록된 발신번호가 없습니다</td>";
    //             }else{
    //                 html = "<td colspan='5'>";
    //                 $.each(json, function(index, item) { // 데이터 =item
    //
    //                     html = html + "<div><span>"+item.brt_tel_no+"</span><span>"+item.brt_use+"</span></div>";
    // 				});
    //                 html = html + "</td>";
    //             }
    //
    //             $("#show_"+divid).html(html);
    //             $("#show_"+divid).show();
    //
    //             // if(json.code=='1'){
    //                 //     setTimeout(function() {
    // 				// 	location.href = "/dhnbiz/brand";
    // 				// }, 2000); //1초 지연
    //             // }
	// 		},
	// 		error: function () {
    //             hideLoading();
    //             showSnackbar("처리되지 않았습니다.", 1500);
	// 			// alert("처리되지 않았습니다.");
	// 			return;
	// 		}
	// 	});
	// }

    function open_page(page){
        var sfield = $("#sfield").val(); //검색타입
        var skeyword = $("#skeyword").val(); //검색내용

        var pram = "";
        if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;

        location.href = "?page="+ page + pram;
    }

</script>
