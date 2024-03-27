<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu14.php');
?>
<!-- //3차 메뉴 -->
<!-- 컨텐츠 전체 영역 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
            <h3>발신번호 목록 (전체 <b style="color: red" id="total_rows"><?=number_format($total_rows)?></b>개)</h3>
            <!-- <button class="btn_tr" onclick="get_brand_list()">브랜드 모두 가져오기</button> -->
		</div>
<?
   $uri= $_SERVER['REQUEST_URI'];
  ?>
   <div class="box-search">
       <input type="hidden" id="hdn_brand" value="<?=$brandid?>" />
       <select class="" name="comp" id="comp" onchange="open_page('1');">
            <option value="ALL"  <?=($this->input->get("comp")=='ALL')? ' selected' : '' ?>>--모든업체--</option>
            <?
            foreach($company as $r) { ?>
                <option value="<?=$r->brd_id?>"  <?=($this->input->get("comp")==$r->brd_id)? ' selected' : '' ?>><?=$r->brd_company."[".$r->brd_brand."]"?></option>
            <?}?>
       </select>
        <select class="" name="sfield" id="sfield">
             <option value="1"  <?=($this->input->get("sfield")=='1')? ' selected' : '' ?>>발신번호</option>
             <option value="2"  <?=($this->input->get("sfield")=='2')? ' selected' : '' ?>>업체명</option>
             <option value="3"  <?=($this->input->get("sfield")=='3')? ' selected' : '' ?>>브랜드ID</option>
        </select>
          <input type="text" class="" name="skeyword" id="skeyword"  value="<?=$skeyword?>"  placeholder="검색어를 입력하세요" onkeypress="if(event.keyCode==13){ open_page(1); }">
          <button class="btn md yellow" id="search_btn" name="search_submit" type="button" onclick="open_page(1);">검색</button>
          <a href="/dhnbiz/brand" class="btn md" style="<?=(empty($skeyword))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>
          <button class="btn md yellow" id="get_btn" style="<?=(empty($comp)||$comp=="ALL")? 'display:none;' : '' ?>" name="get_submit" type="button" onclick="get_chatbot();">발신번호 가져오기</button>
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

					<col width="85px"><?//사용?>


                    <col width="200px"><?//등록버튼?>
				</colgroup>
				<thead>
				<tr>
                    <th>등록일</th>
                    <th>브랜드ID</th>
                    <th>발신번호</th>
                    <th>소속</th>
					<th>업체명</th>

					<!-- <th>브랜드키</th> -->

					<th>사용선택</th>


					<th>관리</th>
				</tr>
				</thead>
				<tbody>
						<!-- 템플릿이슈 수정 시작 -->
						<?$num = 0;
						foreach($list as $r) { $num++;?>
						<tr>
							<!-- 템플릿이슈 수정 끝 -->
                            <td><?=$r->brt_date?></td>
                            <td style="word-break:break-all !important;">
								<?=$r->brt_brand?><?//템플릿코드?>
							</td>
                            <td><?=$this->funn->format_phone($r->brt_tel_no,"-")?></td>
                            <td><?=$r->adminname?></td>
							<td style="cursor:pointer;" >
								<?=$r->brt_company?><?//업체명?>
							</td>


                            <td><?=($r->brt_use=='Y')? '사용' : '미사용' ?></td>


                            <td><button class="btn md yellow" <?=($r->brt_use=='Y')? "style='display:none;'" : "" ?> onclick="set_chatbot('<?=$r->brt_brand?>', '<?=$r->brt_id?>')">사용하기</button></td>
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
	function set_chatbot(brand, bid){
        jsLoading();
		$.ajax({
			url: "/dhnbiz/brand/set_chatbot",
			type: "POST",
			data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>',
				   // 'senderKey':senderKey,
				   'brand': brand,
                   'bid': bid
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
    					location.reload();
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

    //챗봇리스트 가져오기
	function get_chatbot(){
        var brandid = $("#hdn_brand").val();

        jsLoading();
		$.ajax({
			url: "/dhnbiz/brand/get_chatbot",
			type: "POST",
			data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>',
				   // 'senderKey':senderKey,
				   'brandid': brandid
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
    					location.href = "/dhnbiz/brand/tel?comp=<?=$this->input->get("comp")?>";
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

    function open_page(page){
        var sfield = $("#sfield").val(); //검색타입
        var skeyword = $("#skeyword").val(); //검색내용
        var comp = $("#comp").val();

        var pram = "";
        if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;
        if(comp != "") pram += "&comp="+ comp;

        location.href = "?page="+ page + pram;
    }

</script>
