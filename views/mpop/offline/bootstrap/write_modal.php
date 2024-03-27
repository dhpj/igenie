<div id="modal1" class="dh_modal" style='display:none'>
	<div class="modal-content2_1">
		<span id="dh_close2" class="dh_close" onclick='$("#modal1").css("display", "none")'>&times;</span>
		<div class="img_choice">
			<input type="hidden" id="library_type" value="">
			<label for="img_file" class="img_mypick">내사진</label>
			<input type="file" title="이미지 파일" id="img_file" onChange="imgChange(this, 1);" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
			<button onclick="showLibrary('img', 1);" class="img_library" style="margin-left:10px;cursor:pointer;">이미지선택</button>
			<!-- <button onclick="showLibrary('goods');" class="goods_pos" style="margin-left:10px;cursor:pointer;">최근상품</button>
			<button onclick="showLibrary('keep');" class="goods_my" style="margin-left:10px;cursor:pointer;">이미지보관함</button> -->
			<ul>
				<li>1. 내사진 : <span>내 PC에 저장된 이미지</span>를 등록합니다.</li>
				<li>2. 이미지선택 : <span>지니 라이브러리에 있는 이미지</span>를 선택합니다.</li>
				<!-- <li>3. 최근상품 : <span>최근 등록한 상품정보</span>를 불러옵니다.</li> -->
				<!-- <li>4. 이미지보관함 : <span>이미지보관함에 등록한 상품이미지</span>를 선택합니다.</li> -->
			</ul>
		</div>
	</div>
</div>


<div id="modal2" class="dh_modal" style='display:none'>
    <div class="modal-content">
		<span id="dh_close2" class="dh_close" onclick='$("#modal2").css("display", "none")'>&times;</span>
		<!-- <p class="modal-tit"><span id="id_modal_title">이미지 라이브러리</span> (<span id="id_modal_count">0</span>건)</p> -->

        <div id="modal2_img_box">
            <h5 class="modal-tit">
                선택한 이미지
                <span><em>0개</em> / 총 <?=$goods_cnt?>개</span>
                <button onClick='set_images()'>적용하기</button>
                <button onClick='reset_image()'>초기화</button>
            </h5>
            <div id="selected_img"></div>
        </div>

		<div class="search_input" id="id_searchDiv">
			<select id="searchCate1" style="display:none;" onChange="$('#searchCate2').val('');search_category2('');searchImgLibrary();">
				<option value="">- 대분류 -</option>
			<? foreach($category_list as $r) { ?>
				<option value="<?=$r->id?>"<?=($searchCate1 == $r->id) ? " Selected" : ""?>><?=$r->description?></option>
			<? } ?>
			</select>
			<select id="searchCate2" style="display:none;width:220px;margin-left:5px;" onChange="searchImgLibrary();">
				<option value="">- 소분류 -</option>
			</select>
			<select id="id_searchName" style="display:none;margin-left:5px;">
				<option value="name">상품명</option>
				<option value="code">상품코드</option>
			</select>
			<input type="search" id="id_searchLibrary" placeholder="검색어를 입력하세요." style="width:320px;">
			<button id="id_searchBtn" onclick="searchImgLibrary();">
				<i class="material-icons">search</i>
			</button>
		</div>
		<ul id="library_append_list" class="library_append_list"><?//라이브러리 리스트 영역?>
		</ul>
	</div>
</div>


<div id="modal3" class="dh_modal" style='display:none'>
    <div class="modal-content">
        <span id="close_badge" class="dh_close" onclick='$("#modal3").css("display", "none")'>×</span>
        <p class="modal-tit">행사뱃지 선택</p>
        <ul class="badge_list" id="modal_badge_list"><?//행사뱃지 리스트 영역?>
        </ul>
    </div>
</div>


<div id="modal4" class="dh_modal" style='display:none'>
    <div class="modal-content">
        <span id="close_badge" class="dh_close" onclick='$("#modal4").css("display", "none")'>×</span>
        <p class="modal-tit"><span id="id_modal_title">목록으로 돌아가시겠습니까? </span></p>
        <p class="modal_button_wrap">
            <button id="aaaa" class="btn md c_gray" onclick="location.href = '/mpop/offline'">예</button>
            <button id="aaaa" class="btn md" onclick="modal4.style.display = 'none'">아니오</button>
        </p>
    </div>
</div>


<div id="modal5" class="dh_modal" style='display:none'>
    <div class="modal-content">
        <span id="close_badge" class="dh_close" onclick='$("#modal5").css("display", "none")'>×</span>
        <p class="modal-tit"><span id="id_modal_title">타입 선택하기</span></p>
        <div class="mpop_typebox_wrap">
            <div id='type_list' class="mpop_typebox">
            </div>
        </div>
    </div>
</div>
