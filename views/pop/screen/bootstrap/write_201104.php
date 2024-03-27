<?
	$step1_std = 2; //STEP1 기본 상품수
	$step2_std = 4; //STEP2 기본 상품수
	$step3_std = 5; //STEP3 기본 상품수
	$title_img_imgpath = "/dhn/images/leaflets/title/type4/02.jpg"; //이미지형 타이틀 1번째 이미지
	$title_text_imgpath = "/dhn/images/leaflets/title/type4/02.jpg"; //텍스트형 타이틀 1번째 이미지
	$goods_sample_path = "/uploads/sample/goods_sample_201103.xlsx"; //엑셀 샘플 다운로드 파일경로
?>
<div class="wrap_leaflets">
  <div class="s_tit">
    스마트전단 만들기<span class="btn_guide"><a href="/manual/leaflet"><span class="material-icons">pageview</span> 동영상 가이드 바로가기</a></span>
    <div class="btn_list">
      <a href="/pop/screen">스마트전단 목록으로</a>
    </div>
  </div>
  <div class="write_leaflets">
    <div class="wl_lbox">
      <span class="btn_imgtem">
        <button id="dh_myBtn" class="btn_myModal" onClick="templet_page(1);">이미지 탬플릿선택</button>
        <input type="hidden" id="data_id" value="<?=$screen_data->psd_id?>"><?//전단번호?>
        <input type="hidden" id="tem_id" value="<?=$screen_data->psd_tem_id?>"><?//템플릿번호?>
      </span>
      <div class="wl_tit">
        <input type="text" id="wl_tit" onKeyup="onKeyupData(this)" placeholder="행사제목을 입력해주세요." class="int" maxlength="35" value="<?=$screen_data->psd_title?>">
      </div>
      <div class="wl_date">
        <input type="text" id="wl_date" onKeyup="onKeyupData(this)" placeholder="행사기간을 입력해주세요." class="int" maxlength="35" value="<?=$screen_data->psd_date?>">
      </div>
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// STEP1
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
	  <div class="tit_box">
        <div class="tit"><span>STEP1.</span> 쿠폰&할인상품 등록</div>
        <div class="use_choice">
        <label class="uc_container"> 사용함
          <input type="radio" name="step1_yn" id="step1_yn_Y" value="Y" onClick="goods_yn('step1', '');"<? if($screen_data->psd_step1_yn != "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        <label class="uc_container"> 사용안함
          <input type="radio" name="step1_yn" id="step1_yn_N" value="N" onClick="goods_yn('step1', 'none');"<? if($screen_data->psd_step1_yn == "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        </div>
      </div>
      <div class="wl_main_goods" id="goods_list_step1" style="display:<? if($screen_data->psd_step1_yn == "N"){ ?>none<? } ?>;">
        <div class="good_btn_box">
          <span class="btn_excel_down">
            <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
          </span>
          <span class="btn_excel_up">
            <a><label for="excelFile_step1" style="cursor:pointer;">상품 엑셀 업로드</label></a>
            <input type="file" id="excelFile_step1" onchange="excelExport(this, '1', 'step1')" style="display:none;">
          </span>
          <p>
            * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
          </p>
        </div>
        <!--상품추가시 looping-->
        <input type="hidden" id="goods_seq_step1" value="" style="width:80px;">
		<section id="area_goods_step1">
		  <?
		  	$ii = 0;
		  	$step1_cnt = 0;
		  	if($screen_data->psd_step1_yn != "N"){
		  		if(!empty($screen_step1)){
					foreach($screen_step1 as $r) {
		  ?>
		  <dl id="step1_<?=$ii?>" class="dl_step1">
		    <dt>
		  	  <!--
		  	  <label for="img_file_step1_<?=$ii?>" class="templet_img_in"><div id="div_preview_step1_<?=$ii?>" class="templet_img_in2" style="background-image : url('<?=$r->psg_imgpath?>');"><img id="img_preview_step1_<?=$ii?>" style="display:none;width:100%"></div></label>
		  	  <input type="file" title="이미지 파일" id="img_file_step1_<?=$ii?>" onChange="imgChange(this, '_step1_<?=$ii?>');" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
		  	  -->
		  	  <div class="templet_img_in" onclick="showImg('step1_<?=$ii?>')">
		  		<div id="div_preview_step1_<?=$ii?>" class="templet_img_in2" style="background-image : url('<?=$r->psg_imgpath?>');">
		  			<img id="img_preview_step1_<?=$ii?>" style="display:none;width:100%">
		  		</div>
		  	  </div>
		    </dt>
		    <dd>
		  	  <ul>
		  		<input type="hidden" id="goods_id_step1_<?=$ii?>" value="<?=$r->psg_id?>">
		  		<input type="hidden" id="goods_step_step1_<?=$ii?>" value="<?=$r->psg_step?>" style="width:60px;">
		  		<input type="hidden" id="goods_div_step1_<?=$ii?>" value="step1_<?=$ii?>" style="width:80px;">
		  		<input type="hidden" id="goods_seq_step1_<?=$ii?>" value="<?=$ii?>" style="width:42px;">
		  		<input type="hidden" id="goods_imgpath_step1_<?=$ii?>" value="<?=$r->psg_imgpath?>" style="text-align:right;">
		  		<li><span>상품명</span><input type="text" id="goods_name_step1_<?=$ii?>" value="<?=$r->psg_name?>" onKeyup="chgGoodsData('step1_<?=$ii?>')" placeholder="" class="int140"></li>
		  		<li><span>옵션명</span><input type="text" id="goods_option_step1_<?=$ii?>" value="<?=$r->psg_option?>" onKeyup="chgGoodsData('step1_<?=$ii?>')" placeholder="5입" class="int140"></li>
		  		<li><span>정상가</span><input type="text" id="goods_price_step1_<?=$ii?>" value="<?=$r->psg_price?>" onKeyup="chgGoodsData('step1_<?=$ii?>')" placeholder="" class="int140"></li>
		  		<li><span>할인가</span><input type="text" id="goods_dcprice_step1_<?=$ii?>" value="<?=$r->psg_dcprice?>" onKeyup="chgGoodsData('step1_<?=$ii?>')" placeholder="" class="int140"></li>
		  		<li><span>할인율</span>
		  		  <div class="checks" style="display:inline-block; margin-top:3px;">
		  		    <input type="radio" name="goods_badge_step1_<?=$ii?>" value="1" id="goods_badge_on_step1_<?=$ii?>" onClick="chkBadge('1', 'step1_<?=$ii?>', '1');"<? if($r->psg_badge != "0"){ ?> checked<? } ?>>
		  		    <label for="goods_badge_on_step1_<?=$ii?>">표기함</label>
		  		    <input type="radio" name="goods_badge_step1_<?=$ii?>" value="0" id="goods_badge_off_step1_<?=$ii?>" onClick="chkBadge('1', 'step1_<?=$ii?>', '0');"<? if($r->psg_badge == "0"){ ?> checked<? } ?>>
		  		    <label for="goods_badge_off_step1_<?=$ii?>">표기안함</label>
		  		  </div>
		  		</li>
		  	  </ul>
		    </dd>
		    <span class="move_btn_group">
		  	  <a class="move_last_left" href="javascript:goods_move('first', 'step1', 'step1_<?=$ii?>');" title="처음으로이동"></a>
		  	  <a class="move_left" href="javascript:goods_move('left', 'step1', 'step1_<?=$ii?>');" title="왼쪽으로이동"></a>
		  	  <a class="move_right" href="javascript:goods_move('right', 'step1', 'step1_<?=$ii?>');" title="오른쪽으로이동"></a>
		  	  <a class="move_last_right" href="javascript:goods_move('last', 'step1', 'step1_<?=$ii?>');" title="마지막으로이동"></a>
		  	  <a class="move_del" href="javascript:area_del('step1', '<?=$ii?>');" title="삭제"></a>
		    </span>
		  </dl>
		  <?
		  				$ii++;
					} //foreach($screen_step1 as $r) {
		  		} //if(!empty($screen_step1)){
		  	} //if($screen_data->psd_step1_yn != "N"){
		  	$step1_org = $ii;
		  	$step1_cnt = $ii;
		  ?>
		</section>
		<!--//상품추가시 looping-->
        <div class="btn_al_cen">
          <input type="hidden" id="step1_cnt" value="<?=$step1_cnt?>">
          <!--<button type="button" onClick="saved();" style="width:80px;margin-right:100px;">저장하기</button>-->
		  <button class="btn_good_add" type="button" onClick="goods_append('1', 'step1');">개별상품추가</button>
        </div>

      </div><!--//wl_main_goods-->
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// STEP2
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
      <div class="tit_box1">
        <div class="tit"><span>STEP2.</span> 이미지형 상품등록</div>
        <div class="use_choice">
        <label class="uc_container"> 사용함
          <input type="radio" name="step2_yn" id="step2_yn_Y" value="Y" onClick="goods_yn('step2', '');"<? if($screen_data->psd_step2_yn != "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        <label class="uc_container"> 사용안함
          <input type="radio" name="step2_yn" id="step2_yn_N" value="N" onClick="goods_yn('step2', 'none');"<? if($screen_data->psd_step2_yn == "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        </div>
      </div>
  	  <div id="goods_list_step2" style="display:<? if($screen_data->psd_step2_yn == "N"){ ?>none<? } ?>;">
  	    <div id="goods_list_step2-0" style="display:none;"><?//STEP2 복사대상 ?>
          <div class="wl_img_goods" id="step2-0">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li><label class="uc_container"><input type="radio" name="tit_id_step2-0" id="tit_id_step2-0_0" value="0" onClick="areaSet('pre_tit_id_step2-0', '');"><span class="checkmark"></span><span class="w120">선택안함</span></label></li>
                <?
					$dti = 0;
					foreach($design_title_img_list as $r) { //타이틀 이미지
						$dti++;
						if($dti == 1) $title_img_imgpath = $r->tit_imgpath;
				?>
    			<li><label class="uc_container"><input type="radio" name="tit_id_step2-0" id="tit_id_step2-0_<?=$dti?>" value="<?=$r->tit_id?>" onClick="areaSet('pre_tit_id_step2-0', '<img src=\'<?=$r->tit_imgpath?>\'>');"<? if($dti == 1){ ?> checked<? } ?>><span class="checkmark"></span><img src="<?=$r->tit_imgpath?>"></label></li>
    			<?
					}
				?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~!</div>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step2-0" style="cursor:pointer;">상품 엑셀 업로드</label></a>
				<input type="file" id="excelFile_step2-0" onChange="excelExport(this, '2', 'step2-0')" style="display:none;">
              </span>
              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <!--상품추가시 looping-->
            <section id="area_goods_step2-0">
              <? for($ii = 0; $ii < $step2_std; $ii++){ ?>
              <dl id="step2-0_<?=$ii?>" class="dl_step2-0">
                <dt>
                  <div class="templet_img_in" onclick="showImg('step2-0_<?=$ii?>')">
                    <div id="div_preview_step2-0_<?=$ii?>" class="templet_img_in2" style="background-image : url('');">
                      <img id="img_preview_step2-0_<?=$ii?>" style="display:none;width:100%">
                    </div>
                   </div>
                </dt>
                <dd>
                  <ul>
              	    <input type="hidden" id="goods_id_step2-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_step_step2-0_<?=$ii?>" value="2" style="width:30px;">
              	    <input type="hidden" id="goods_div_step2-0_<?=$ii?>" value="step2-0_<?=$ii?>" style="width:80px;">
              	    <input type="hidden" id="goods_seq_step2-0_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
              	    <input type="hidden" id="goods_imgpath_step2-0_<?=$ii?>" value="" style="text-align:right;">
              	    <li><span>상품명</span><input type="text" id="goods_name_step2-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step2-0_<?=$ii?>')" placeholder="신라면 5입" class="int140"></li>
              	    <li><span>옵션명</span><input type="text" id="goods_option_step2-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step2-0_<?=$ii?>')" placeholder="" class="int140"></li>
              	    <li><span>정상가</span><input type="text" id="goods_price_step2-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step2-0_<?=$ii?>')" placeholder="4,000원" class="int140"></li>
              	    <li><span>할인가</span><input type="text" id="goods_dcprice_step2-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step2-0_<?=$ii?>')" placeholder="3,000원" class="int140"></li>
                  </ul>
                </dd>
                <span class="move_btn_group">
              	<a class="move_last_left" href="javascript:goods_move('first', 'step2-0', 'step2-0_<?=$ii?>');" title="처음으로이동"></a>
              	<a class="move_left" href="javascript:goods_move('left', 'step2-0', 'step2-0_<?=$ii?>');" title="왼쪽으로이동"></a>
              	<a class="move_right" href="javascript:goods_move('right', 'step2-0', 'step2-0_<?=$ii?>');" title="오른쪽으로이동"></a>
              	<a class="move_last_right" href="javascript:goods_move('last', 'step2-0', 'step2-0_<?=$ii?>');" title="마지막으로이동"></a>
              	<a class="move_del" href="javascript:area_del('step2-0',  '<?=$ii?>');" title="삭제"></a>
                </span>
              </dl>
              <? } ?>
  		    </section>
			<input type="hidden" id="step2-0_no" value="0" style="width:40px;">
            <input type="hidden" id="step2-0_cnt" value="<?=$step2_std?>" style="width:40px;">
            <!--//상품추가시 looping-->
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('2', 'step2-0');">개별상품추가</button>
              <!--<button class="btn_good_add" type="button" onClick="saved();" style="margin-left:3px;">저장하기</button>-->
            </div>
          </div><!--//wl_img_goods-->
          <div class="add_corner">
            <a class="" id="btn_add_step2-0" onClick="goods_area_copy('goods_list_step2', 'step2');">
              <i class="xi-plus-circle-o"></i> 이미지형 상품코너 추가
            </a>
            <a class="" id="btn_del_step2-0" onClick="goods_area_del('goods_list_', 'step2', 'step2-0');">
              <i class="xi-minus-circle-o"></i> 이미지형 상품코너 삭제
            </a>
          </div><!--//코너 추가/삭제-->
        </div><!--//goods_list_step2-0-->
		<?
		  	$no = 0; //순번
			$ii = 0; //코너내 순번
		  	$seq = 0; //코너번호
			$chk_step_no = -1; //코너별 고유번호
		  	$step2_cnt = 0; //STEP2 등록수
		  	if($screen_data->psd_step2_yn != "N"){
		  		if(!empty($screen_step2)){
					foreach($screen_step2 as $r) {
						$no++;
						if($r->psg_step_no != $chk_step_no){
							$ii = 0;
							$seq++;
		?>
		<div id="goods_list_step2-<?=$seq?>">
          <div class="wl_img_goods" id="step2-<?=$seq?>">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li>
                  <label class="uc_container">
                    <input type="radio" name="tit_id_step2-<?=$seq?>" id="tit_id_step2-<?=$seq?>_0" value="0" onClick="areaSet('pre_tit_id_step2-<?=$seq?>', '');"<? if($r->psg_tit_id == "0"){ ?> checked<? } ?>>
                    <span class="checkmark"></span><span class="w120">선택안함</span>
                  </label>
				</li>
                <?
					$dti = 0;
					foreach($design_title_img_list as $dt) { //타이틀 이미지
						$dti++;
				?>
				<li>
				  <label class="uc_container">
				    <input type="radio" name="tit_id_step2-<?=$seq?>" id="tit_id_step2-<?=$seq?>_<?=$dti?>" value="<?=$dt->tit_id?>" onClick="areaSet('pre_tit_id_step2-<?=$seq?>', '<img src=\'<?=$dt->tit_imgpath?>\'>');"<? if($r->psg_tit_id == $dt->tit_id){ ?> checked<? } ?>>
				    <span class="checkmark"></span><img src="<?=$dt->tit_imgpath?>">
				  </label>
				</li>
    			<?
					}
				?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~!</div>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step2-<?=$seq?>" style="cursor:pointer;">상품 엑셀 업로드</label></a>
				<input type="file" id="excelFile_step2-<?=$seq?>" onChange="excelExport(this, '2', 'step2-<?=$seq?>')" style="display:none;">
              </span>
              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <!--상품추가시 looping-->
            <section id="area_goods_step2-<?=$seq?>">
              <?
						} //if($r->psg_step_no != $chk_step_no){
			  ?>
			  <dl id="step2-<?=$seq?>_<?=$ii?>" class="dl_step2-<?=$seq?>">
                <dt>
                  <div class="templet_img_in" onclick="showImg('step2-<?=$seq?>_<?=$ii?>')">
                    <div id="div_preview_step2-<?=$seq?>_<?=$ii?>" class="templet_img_in2" style="background-image : url('<?=$r->psg_imgpath?>');">
                      <img id="img_preview_step2-<?=$seq?>_<?=$ii?>" style="display:none;width:100%">
                    </div>
                   </div>
                </dt>
                <dd>
              	<ul>
              	  <input type="hidden" id="goods_id_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_id?>">
              	  <input type="hidden" id="goods_step_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_step?>" style="width:30px;">
              	  <input type="hidden" id="goods_div_step2-<?=$seq?>_<?=$ii?>" value="step2-<?=$seq?>_<?=$ii?>" style="width:80px;">
              	  <input type="hidden" id="goods_seq_step2-<?=$seq?>_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
              	  <input type="hidden" id="goods_imgpath_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_imgpath?>" style="text-align:right;">
              	  <li><span>상품명</span><input type="text" id="goods_name_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_name?>" onKeyup="chgGoodsData('step2-<?=$seq?>_<?=$ii?>')" placeholder="" class="int140"></li>
              	  <li><span>옵션명</span><input type="text" id="goods_option_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option?>" onKeyup="chgGoodsData('step2-<?=$seq?>_<?=$ii?>')" placeholder="" class="int140"></li>
              	  <li><span>정상가</span><input type="text" id="goods_price_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_price?>" onKeyup="chgGoodsData('step2-<?=$seq?>_<?=$ii?>')" placeholder="" class="int140"></li>
              	  <li><span>할인가</span><input type="text" id="goods_dcprice_step2-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_dcprice?>" onKeyup="chgGoodsData('step2-<?=$seq?>_<?=$ii?>')" placeholder="" class="int140"></li>
              	</ul>
                </dd>
                <span class="move_btn_group">
              	<a class="move_last_left" href="javascript:goods_move('first', 'step2-<?=$seq?>', 'step2-<?=$seq?>_<?=$ii?>');" title="처음으로이동"></a>
              	<a class="move_left" href="javascript:goods_move('left', 'step2-<?=$seq?>', 'step2-<?=$seq?>_<?=$ii?>');" title="왼쪽으로이동"></a>
              	<a class="move_right" href="javascript:goods_move('right', 'step2-<?=$seq?>', 'step2-<?=$seq?>_<?=$ii?>');" title="오른쪽으로이동"></a>
              	<a class="move_last_right" href="javascript:goods_move('last', 'step2-<?=$seq?>', 'step2-<?=$seq?>_<?=$ii?>');" title="마지막으로이동"></a>
              	<a class="move_del" href="javascript:area_del('step2-<?=$seq?>', '<?=$ii?>');" title="삭제"></a>
                </span>
              </dl>
			  <?
						$ii++; //코너별 등록수
						if($ii >= $r->rownum){
			  ?>
  		    </section>
			<input type="hidden" id="step2-<?=$seq?>_no" value="<?=$seq?>" style="width:40px;">
            <input type="hidden" id="step2-<?=$seq?>_cnt" value="<?=$ii?>" style="width:40px;">
            <!--//상품추가시 looping-->
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('2', 'step2-<?=$seq?>');">개별상품추가</button>
              <!--<button class="btn_good_add" type="button" onClick="saved();">저장하기</button>-->
            </div>
          </div><!--//wl_img_goods-->
          <div class="add_corner">
            <a class="" id="btn_add_step2-<?=$seq?>"<? if($seq != $r->gronum){ ?> style="display:none;"<? } ?> onClick="goods_area_copy('goods_list_step2', 'step2');">
              <i class="xi-plus-circle-o"></i> 이미지형 상품코너 추가
            </a>
            <a class="" id="btn_del_step2-<?=$seq?>" onClick="goods_area_del('goods_list_', 'step2', 'step2-<?=$seq?>');">
              <i class="xi-minus-circle-o"></i> 이미지형 상품코너 삭제
            </a>
          </div><!--//코너 추가/삭제-->
        </div><!--//goods_list_step2-<?=$seq?>-->
		<?
						} //if($ii >= $r->rownum){
						$chk_step_no = $r->psg_step_no;
					} //foreach($screen_step2 as $r) {
				} //if(!empty($screen_step2)){
			} //if($screen_data->psd_step2_yn != "N"){
			$step2_cnt = $seq; //STEP2 등록수
		?>
      </div><!--//goods_list_step2-->
	  <input type="hidden" id="step2_cnt" value="<?=$step2_cnt?>">
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// STEP3
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
      <div class="tit_box1">
        <div class="tit"><span>STEP3.</span> 텍스트형 상품등록</div>
        <div class="use_choice">
        <label class="uc_container"> 사용함
		  <input type="radio" name="step3_yn" id="step3_yn_Y" value="Y" onClick="goods_yn('step3', '');"<? if($screen_data->psd_step3_yn != "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        <label class="uc_container"> 사용안함
		  <input type="radio" name="step3_yn" id="step3_yn_N" value="N" onClick="goods_yn('step3', 'none');"<? if($screen_data->psd_step3_yn == "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        </div>
      </div>
      <div id="goods_list_step3" style="display:<? if($screen_data->psd_step3_yn == "N"){ ?>none<? } ?>;">
  	    <div id="goods_list_step3-0" style="display:none;"><?//STEP3 복사대상 ?>
  	      <div class="wl_txt_goods" id="step3-0">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li><label class="uc_container"><input type="radio" name="tit_id_step3-0" id="tit_id_step3-0_0" value="0" onClick="areaSet('pre_tit_id_step3-0', '');"><span class="checkmark"></span><span class="w120">선택안함</span></label></li>
                <?
  					$dti = 0;
  					foreach($design_title_text_list as $r) { //타이틀 이미지
  						$dti++;
  						if($dti == 1) $title_text_imgpath = $r->tit_imgpath;
                ?>
                <li><label class="uc_container"><input type="radio" name="tit_id_step3-0" id="tit_id_step3-0_<?=$dti?>" value="<?=$r->tit_id?>" onClick="areaSet('pre_tit_id_step3-0', '<img src=\'<?=$r->tit_imgpath?>\'>');"<? if($dti == 1){ ?> checked<? } ?>><span class="checkmark"></span><img src="<?=$r->tit_imgpath?>"></label></li>
                <?
  					}
                ?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~!</div>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step3-0" style="cursor:pointer;">상품 엑셀 업로드</label></a>
                <input type="file" id="excelFile_step3-0" onChange="excelExport(this, '3', 'step3-0')" style="display:none;">
              </span>
              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <ul class="txt_goods_list">
              <li>
                <span class="txt_name">상품명</span>
                <span class="txt_option">옵션명</span>
                <span class="txt_price1">정상가</span>
                <span class="txt_price2">할인가</span>
                <span class="txt_btn">삭제</span>
              </li>
              <!--상품추가시 looping-->
              <section id="area_goods_step3-0">
                <? for($ii = 0; $ii < $step3_std; $ii++){ ?>
                <li id="step3-0_<?=$ii?>" class="dl_step3-0">
                  <span class="txt_name"><input type="text" id="goods_name_step3-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step3-0_<?=$ii?>')" placeholder="신라면" class="int100_l"></span>
                  <span class="txt_option"><input type="text" id="goods_option_step3-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step3-0_<?=$ii?>')" placeholder="5입" class="int100_r"></span>
                  <span class="txt_price1"><input type="text" id="goods_price_step3-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step3-0_<?=$ii?>')" placeholder="4,000원" class="int100_r"></span>
                  <span class="txt_price2"><input type="text" id="goods_dcprice_step3-0_<?=$ii?>" value="" onKeyup="chgGoodsData('step3-0_<?=$ii?>')" placeholder="3,000원" class="int100_r"></span>
                  <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del('step3-0', '<?=$ii?>');" title="삭제"></a></span>
                  <input type="hidden" id="goods_id_step3-0_<?=$ii?>" value="">
                  <input type="hidden" id="goods_step_step3-0_<?=$ii?>" value="3" style="width:30px;">
                  <input type="hidden" id="goods_div_step3-0_<?=$ii?>" value="step3-0_<?=$ii?>" style="width:80px;">
                  <input type="hidden" id="goods_seq_step3-0_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
                </li>
                <? } //for($ii = 0; $ii < $step3_std; $ii++){ ?>
              </section>
              <input type="hidden" id="step3-0_no" value="0" style="width:40px;">
              <input type="hidden" id="step3-0_cnt" value="<?=$step3_std?>" style="width:40px;">
              <!--//상품추가시 looping-->
            </ul>
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('3', 'step3-0');">개별상품추가</button>
              <!--<button class="btn_good_add" type="button" onClick="saved();">저장하기</button>-->
            </div>
          </div><!--//wl_txt_goods-->
          <div class="add_corner">
            <a class="" id="btn_add_step3-0" onClick="goods_area_copy('goods_list_step3', 'step3');">
             <i class="xi-plus-circle-o"></i> 텍스트형 상품코너 추가
            </a>
            <a class="" id="btn_del_step3-0" onClick="goods_area_del('goods_list_', 'step3', 'step3-0');">
              <i class="xi-minus-circle-o"></i> 텍스트형 상품코너 삭제
            </a>
          </div><!--//코너 추가/삭제-->
        </div><!--//goods_list_step3-0-->
		<?
		  	$no = 0; //순번
			$ii = 0; //코너내 순번
		  	$seq = 0; //코너번호
			$chk_step_no = -1; //코너별 고유번호
		  	$step3_cnt = 0; //STEP3 등록수
		  	if($screen_data->psd_step3_yn != "N"){
		  		if(!empty($screen_step3)){
					foreach($screen_step3 as $r) {
						$no++;
						if($r->psg_step_no != $chk_step_no){
							$ii = 0;
							$seq++;
		?>
        <div id="goods_list_step3-<?=$seq?>">
  	      <div class="wl_txt_goods" id="step3-<?=$seq?>">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li>
                  <label class="uc_container">
                    <input type="radio" name="tit_id_step3-<?=$seq?>" id="tit_id_step3-<?=$seq?>_0" value="0" onClick="areaSet('pre_tit_id_step3-<?=$seq?>', '');">
                    <span class="checkmark"></span>
                    <span class="w120">선택안함</span>
                  </label>
                </li>
                <?
  					$dti = 0;
  					foreach($design_title_text_list as $dt) { //타이틀 이미지
  						$dti++;
                ?>
                <li>
                  <label class="uc_container">
                    <input type="radio" name="tit_id_step3-<?=$seq?>" id="tit_id_step3-<?=$seq?>_<?=$dti?>" value="<?=$dt->tit_id?>" onClick="areaSet('pre_tit_id_step3-<?=$seq?>', '<img src=\'<?=$dt->tit_imgpath?>\'>');"<? if($r->psg_tit_id == $dt->tit_id){ ?> checked<? } ?>>
                    <span class="checkmark"></span>
                    <img src="<?=$dt->tit_imgpath?>">
                  </label>
				</li>
                <?
  					}
                ?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~!</div>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step3-<?=$seq?>" style="cursor:pointer;">상품 엑셀 업로드</label></a>
                <input type="file" id="excelFile_step3-<?=$seq?>" onChange="excelExport(this, '3', 'step3-<?=$seq?>')" style="display:none;">
              </span>
              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <ul class="txt_goods_list">
              <li>
                <span class="txt_name">상품명</span>
                <span class="txt_option">옵션명</span>
                <span class="txt_price1">정상가</span>
                <span class="txt_price2">할인가</span>
                <span class="txt_btn">삭제</span>
              </li>
              <!--상품추가시 looping-->
              <section id="area_goods_step3-<?=$seq?>">
                <?
						} //if($r->psg_step_no != $chk_step_no){
                ?>
                <li id="step3-<?=$seq?>_<?=$ii?>" class="dl_step3-<?=$seq?>">
                  <span class="txt_name"><input type="text" id="goods_name_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_name?>" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>')" placeholder="" class="int100_l"></span>
				  <span class="txt_option"><input type="text" id="goods_option_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option?>" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>')" placeholder="" class="int100_l"></span>
                  <span class="txt_price1"><input type="text" id="goods_price_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_price?>" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>')" placeholder="" class="int100_r"></span>
                  <span class="txt_price2"><input type="text" id="goods_dcprice_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_dcprice?>" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>')" placeholder="" class="int100_r"></span>
                  <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del('step3-<?=$seq?>', '<?=$ii?>');" title="삭제"></a></span>
                  <input type="hidden" id="goods_id_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_id?>">
                  <input type="hidden" id="goods_step_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_step?>" style="width:30px;">
                  <input type="hidden" id="goods_div_step3-<?=$seq?>_<?=$ii?>" value="step3-<?=$seq?>_<?=$ii?>" style="width:80px;">
                  <input type="hidden" id="goods_seq_step3-<?=$seq?>_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
                </li>
                <?
						$ii++; //코너별 등록수
						if($ii >= $r->rownum){
                ?>
              </section>
              <input type="hidden" id="step3-<?=$seq?>_no" value="<?=$seq?>" style="width:40px;">
              <input type="hidden" id="step3-<?=$seq?>_cnt" value="<?=$ii?>" style="width:40px;">
              <!--//상품추가시 looping-->
            </ul>
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('3', 'step3-<?=$seq?>');">개별상품추가</button>
              <!--<button class="btn_good_add" type="button" onClick="saved();">저장하기</button>-->
            </div>
          </div><!--//wl_txt_goods-->
          <div class="add_corner">
            <a class="" id="btn_add_step3-<?=$seq?>"<? if($seq != $r->gronum){ ?> style="display:none;"<? } ?> onClick="goods_area_copy('goods_list_step3', 'step3');">
             <i class="xi-plus-circle-o"></i> 텍스트형 상품코너 추가
            </a>
            <a class="" id="btn_del_step3-<?=$seq?>" onClick="goods_area_del('goods_list_', 'step3', 'step3-<?=$seq?>');">
             <i class="xi-minus-circle-o"></i> 텍스트형 상품코너 삭제
            </a>
          </div><!--//코너 추가/삭제-->
        </div><!--//goods_list_step3-<?=$seq?>-->
		<?
						} //if($ii >= $r->rownum){
						$chk_step_no = $r->psg_step_no;
					} //foreach($screen_step3 as $r) {
				} //if(!empty($screen_step3)){
			} //if($screen_data->psd_step3_yn != "N"){
			$step3_cnt = $seq; //STEP3 등록수
		?>
      </div><!--//goods_list_step3-->
      <input type="hidden" id="step3_cnt" value="<?=$step3_cnt?>">
    </div><!--//wl_lbox-->
    <div class="wl_rbox">
      <p class="wl_rbox_tit">
        스마트전단 미리보기
      </p>
      <div class="wl_r_preview" id="smart_capture">
        <div class="pre_box_wrap">
          <!--템플릿 이미지가 들어갈 공간-->
          <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('<?=($screen_data->tem_imgpath == "") ? "/dhn/images/leaflets/tem/tem01.jpg" : $screen_data->tem_imgpath?>') no-repeat top center;background-color:<?=($screen_data->tem_bgcolor == "") ? "#47b5e5" : $screen_data->tem_bgcolor?>;"></div>
          <!--//템플릿 이미지가 들어갈 공간-->
          <div class="pre_box1">
            <p id="pre_wl_tit" class="pre_tit"><?=($screen_data->psd_title == "") ? "행사제목을 입력해주세요." : $screen_data->psd_title?></p>
            <p id="pre_wl_date" class="pre_date"><?=($screen_data->psd_date == "") ? "행사기간을 입력해주세요." : $screen_data->psd_date?></p>
          </div>
<?
	//화면 미리보기 STEP1 [S] ------------------------------------------------------------------------------------------------
?>
          <div class="pre_box2" id="pre_goods_list_step1">
            <!--상품추가시 looping-->
            <section id="pre_area_goods_step1">
		      <?
				$ii = 0;
				if($screen_data->psd_step1_yn != "N"){
					foreach($screen_step1 as $r) {
						$badge_rate = "";
						if($r->psg_price != "" && $r->psg_dcprice != ""){
							$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
							$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
							$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
						}
		      ?>
		      <dl id="pre_step1_<?=$ii?>">
                <dt>
				  <div id="pre_goods_badge_step1_<?=$ii?>" class="sale_badge" style="display:<? if($r->psg_badge == "0"){ ?>none<? } ?>;"><?=$badge_rate?><span>%</span></div>
				  <div id="pre_div_preview_step1_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=$r->psg_imgpath?>');">
				    <img id="pre_img_preview_step1_<?=$ii?>" style="display:none;">
				  </div>
                </dt>
			    <dd>
                  <ul>
                    <li class="price1" id="pre_goods_price_step1_<?=$ii?>"><?=$r->psg_price?></li><?//STEP1 정상가?>
                    <li class="price2" id="pre_goods_dcprice_step1_<?=$ii?>"><?=$r->psg_dcprice?></li><?//STEP1 할인가?>
                    <li class="name">
					  <span id="pre_goods_name_step1_<?=$ii?>"><?=$r->psg_name?></span><?//STEP1 상품명?>
					  <span id="pre_goods_option_step1_<?=$ii?>"><?=$r->psg_option?></span><?//STEP1 옵션명?>
					  <span id="pre_goods_badge_cd_step1_<?=$ii?>" style="display:none;"><?=$r->psg_badge?></span><?//STEP1 뱃지?>
					</li>
				    <li id="pre_goods_imgpath_step1_<?=$ii?>" style="display:none;"><?=$r->psg_imgpath?></li><?//STEP1 이미지경로?>
                  </ul>
                </dd>
              </dl>
		      <?
						$ii++;
					} //foreach($screen_step1 as $r) {
				} //if($screen_data->psd_step1_yn != "N"){
		      ?>
		    </section>
            <!--//상품추가시 looping-->
          </div><!--//pre_box2-->
<?
	//화면 미리보기 STEP1 [E] ------------------------------------------------------------------------------------------------
?>
        </div><!--//pre_box_wrap-->
        <div class="pre_box_wrap">
<?
	//화면 미리보기 STEP2 [S] ------------------------------------------------------------------------------------------------
?>
		  <div class="pre_box3" id="pre_goods_list_step2">
            <section id="pre_goods_list_step2-0" style="display:none;">
  			  <p class="pre_tit" id="pre_tit_id_step2-0">
			    <img src="<?=$title_img_imgpath?>"><?//STEP2 타이틀 이미지 ?>
  			  </p>
              <div class="pop_list_wrap" id="pre_area_goods_step2-0">
                <!--상품추가시 looping-->
                <? for($ii = 0; $ii < $step2_std; $ii++){ ?>
				<div class="pop_list01" id="pre_step2-0_<?=$ii?>">
                  <div id="pre_div_preview_step2-0_<?=$ii?>" class="templet_img_in3" style="background-image : url('/dhn/images/leaflets/sample_img.jpg');"><?//STEP2 상품 이미지 ?>
                    <img id="pre_img_preview_step2-0_<?=$ii?>" style="display:none;">
                  </div>
                  <div class="pop_name">
                    <span id="pre_goods_name_step2-0_<?=$ii?>">신라면</span><?//STEP2 상품명?>
                    <span id="pre_goods_option_step2-0_<?=$ii?>">5입</span><?//STEP2 옵션명?>
					<div id="pre_goods_imgpath_step2-0_<?=$ii?>" style="display:none;"></div><?//STEP2 이미지경로?>
                  </div>
                  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step2-0_<?=$ii?>">4,000원</p><?//STEP2 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step2-0_<?=$ii?>">3,000원</p><?//STEP2 할인가?>
                  </div>
                </div>
                <? } ?>
                <!--상품추가시 looping-->
  			  </div><!--//pop_list_wrap-->
            </section>
			<?
				$no = 0; //순번
				$ii = 0; //코너내 순번
				$seq = 0; //코너번호
				$chk_step_no = -1; //코너별 고유번호
				if($screen_data->psd_step2_yn != "N"){
					if(!empty($screen_step2)){
						foreach($screen_step2 as $r) {
							$no++;
							if($r->psg_step_no != $chk_step_no){
								$ii = 0;
								$seq++;
			?>
			<section id="pre_goods_list_step2-<?=$seq?>">
			  <p class="pre_tit" id="pre_tit_id_step2-<?=$seq?>">
			    <? if($r->tit_imgpath != ""){ ?><img src="<?=$r->tit_imgpath?>"><? } ?><?//STEP2 타이틀 이미지 ?>
  			  </p>
              <div class="pop_list_wrap" id="pre_area_goods_step2-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
							} //if($r->psg_step_no != $chk_step_no){
                ?>
				<div class="pop_list01" id="pre_step2-<?=$seq?>_<?=$ii?>">
				  <div id="pre_div_preview_step2-<?=$seq?>_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=$r->psg_imgpath?>');">
				    <img id="pre_img_preview_step2-<?=$seq?>_<?=$ii?>" style="display:none;">
				  </div>
					<div class="pop_price">
						<p class="price1" id="pre_goods_price_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></p><?//STEP2 정상가?>
						<p class="price2" id="pre_goods_dcprice_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_dcprice?></p><?//STEP2 할인가?>
					</div>
				  <div class="pop_name">
                    <span id="pre_goods_name_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//STEP2 상품명?>
                    <span id="pre_goods_option_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//STEP2 옵션명?>
					<div id="pre_goods_imgpath_step2-<?=$seq?>_<?=$ii?>" style="display:none;"><?=$r->psg_imgpath?></div><?//STEP2 이미지경로?>
                  </div>

                </div>
                <?
							$ii++; //코너별 등록수
							if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
  			  </div><!--//pop_list_wrap-->
            </section>
			<?
							} //if($ii >= $r->rownum){
							$chk_step_no = $r->psg_step_no;
						} //foreach($screen_step2 as $r) {
					} //if(!empty($screen_step2)){
				} //if($screen_data->psd_step2_yn != "N"){
			?>
		  </div><!--//pre_box3-->
<?
	//화면 미리보기 STEP2 [E] ------------------------------------------------------------------------------------------------
	//화면 미리보기 STEP3 [S] ------------------------------------------------------------------------------------------------
?>
          <div class="pre_box4" id="pre_goods_list_step3">
            <section id="pre_goods_list_step3-0" style="display:none;">
              <p class="pre_tit" id="pre_tit_id_step3-0">
                <img src="<?=$title_text_imgpath?>"><?//STEP3 타이틀 이미지 ?>
              </p>
              <div class="pop_list_wrap" id="pre_area_goods_step3-0">
                <!--상품추가시 looping-->
                <? for($ii = 0; $ii < $step3_std; $ii++){ ?>
                <div class="pop_list02" id="pre_step3-0_<?=$ii?>">
                  <div class="name">
                    <span id="pre_goods_name_step3-0_<?=$ii?>">신라면</span><?//STEP3 상품명?>
                    <span id="pre_goods_option_step3-0_<?=$ii?>"> 5입</span><?//STEP3 옵션명?>
                  </div>
				  <span class="price1" id="pre_goods_price_step3-0_<?=$ii?>">4,000원</span><?//STEP3 정상가?>
                  <span class="price2" id="pre_goods_dcprice_step3-0_<?=$ii?>">3,000원</span><?//STEP3 할인가?>
                </div>
                <? } //for($ii = 0; $ii < $step3_std; $ii++){ ?>
                <!--상품추가시 looping-->
              </div>
		    </section>
			<?
				$no = 0; //순번
				$ii = 0; //코너내 순번
				$seq = 0; //코너번호
				$chk_step_no = -1; //코너별 고유번호
				if($screen_data->psd_step3_yn != "N"){
					if(!empty($screen_step3)){
						foreach($screen_step3 as $r) {
							$no++;
							if($r->psg_step_no != $chk_step_no){
								$ii = 0;
								$seq++;
			?>
            <section id="pre_goods_list_step3-<?=$seq?>">
              <p class="pre_tit" id="pre_tit_id_step3-<?=$seq?>">
                <? if($r->tit_imgpath != ""){ ?><img src="<?=$r->tit_imgpath?>"><? } ?><?//STEP3 타이틀 이미지 ?>
              </p>
              <div class="pop_list_wrap" id="pre_area_goods_step3-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
							} //if($r->psg_step_no != $chk_step_no){
                ?>
                <div class="pop_list02" id="pre_step3-<?=$seq?>_<?=$ii?>">
                  <div class="name">
				    <span id="pre_goods_name_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//STEP3 상품명?>
                    <span id="pre_goods_option_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//STEP3 옵션명?>
                  </div>
				  <span class="price1" id="pre_goods_price_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></span><?//STEP3 정상가?>
                  <span class="price2" id="pre_goods_dcprice_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_dcprice?></span><?//STEP3 할인가?>
                </div>
                <?
							$ii++; //코너별 등록수
							if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
              </div>
		    </section>
			<?
							} //if($ii >= $r->rownum){
							$chk_step_no = $r->psg_step_no;
						} //foreach($screen_step3 as $r) {
					} //if(!empty($screen_step3)){
				} //if($screen_data->psd_step3_yn != "N"){
			?>
		  </div><!--//pre_box4-->
<?
	//화면 미리보기 STEP3 [E] ------------------------------------------------------------------------------------------------
?>
        </div><!--//pre_box_wrap-->
      </div><!--//wl_r_preview-->
    </div><!--//wl_rbox-->
    <div class="write_leaflets_btn">
      <button type="button" class="pop_btn_save" onClick="saved('');"><span class="material-icons">save</span> 저장하기</button>
      <button type="button" class="pop_btn_send" onClick="saved('lms');"><span class="material-icons">forward_to_inbox</span> 문자발송</button>
      <button type="button" class="pop_btn_send" onClick="saved('talk_adv');"><span class="material-icons">chat_bubble_outline</span> 알림톡발송</button>
      <button type="button" class="pop_btn_cancel" onclick="location.href='/pop/screen'"><span class="material-icons">highlight_off</span> 취소하기</button>
    </div>
  </div><!--//write_leaflets-->
</div><!--//wrap_leaflets-->
<!-- 이미지 탬플릿선택 Modal -->
<div id="dh_myModal" class="dh_modal">
  <div class="modal-content">
	<span id="dh_close" class="dh_close">&times;</span>
	<p class="modal-tit">이미지 탬플릿선택</p>
	<div class="radio-field">
		<form method="POST" name="tag_list" onsubmit="return false;">
			<ul id="id_tag_list"><?//템플릿 태그 리스트 영역?>
			</ul>
		</form>
	</div>
	<ul id="id_templet_list"><?//템플릿 리스트 영역?>
	</ul>
	<div class="page_cen" id="id_pagination">
	</div><!--//pagination-->
  </div>
</div>
<!-- 상품 이미지 선택 Modal -->
<div id="dh_myModal2" class="dh_modal">
	<div class="modal-content2_1">
		<span id="dh_close2" class="dh_close">&times;</span>
		<div class="img_choice">
			<input type="hidden" id="goods_step_id" value="">
			<input type="hidden" id="library_type" value="">
			<label for="img_file" class="img_mypick">내사진</label>
			<input type="file" title="이미지 파일" id="img_file" onChange="imgChange(this);" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
			<button onclick="showLibrary('img');" class="img_library" style="margin-left:10px;cursor:pointer;">이미지선택</button>
			<button onclick="showLibrary('goods');" class="goods_pos" style="margin-left:10px;cursor:pointer;">상품선택</button>
			<ul>
				<li>1. 내사진 :  <span>내 PC에 저장된 이미지</span>를 등록합니다.</li>
				<li>2. 이미지선택 : <span>지니 라이브러리에 있는 이미지</span>를 선택합니다.</li>
				<li>3. 상품선택 : <span>지니에 등록된 상품 정보</span>를 불러옵니다.</li>
			</ul>
		</div>
	</div>
</div>
<!-- 라이브러리 Modal -->
<div id="dh_myModal3" class="dh_modal">
	<div class="modal-content">
		<span id="dh_close3" class="dh_close">&times;</span>
		<p class="modal-tit"><span id="id_modal_title">이미지</span> 라이브러리</p>
		<div class="search_input">
			<input type="search" placeholder="검색어를 입력하세요." id="id_searchLibrary">
			<button onclick="searchImgLibrary();">
				<i class="material-icons">search</i>
			</button>
		</div>
		<ul id="library_append_list" class="library_append_list"><?//라이브러리 리스트 영역?>
		</ul>
	</div>
</div>
<script>
	var request = new XMLHttpRequest();

	// Get the modal
	var modal = document.getElementById("dh_myModal");

	//이미지 템플릿선택 모달창 열기
	function templet_page(page){
		// Get the <span> element that closes the modal
		var span = document.getElementById("dh_close");

		//템플릿 태그 리스트 조회
		$.ajax({
			url: "/pop/screen/ajax_templet_tag",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
			}
		});

		var tag_id = $(":input:radio[name=tag_list]:checked").val(); //선택된 태그번호
		if(tag_id == undefined || tag_id == "all") tag_id = "";
		//alert("tag_id : "+ tag_id);

		templet_list(tag_id, page); //템플릿 리스트 조회

		// Open the modal
		modal.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
			$("input:radio[name=tag_list]:radio[value='all']").prop('checked', true); //태그 전체 선택하기
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
				$("input:radio[name=tag_list]:radio[value='all']").prop('checked', true); //태그 전체 선택하기
			}
		}
	}

	//템플릿 리스트 조회
	function templet_list(tag_id, page){
		//alert("템플릿 리스트 조회");
		$.ajax({
			url: "/pop/screen/ajax_templet",
			type: "POST",
			data: {"tag_id":tag_id, "page":page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$("#id_templet_list").html(json.html); //템플릿 리스트
				$("#id_pagination").html(json.page_html); //템플릿 페이징
			}
		});
	}

	//이미지 템플릿선택 모달창 열기 > 태그선택
	function tag_choice(tag_id, page){
		$("input:radio[name=tag_list]:radio[value='"+ tag_id +"']").prop('checked', true); //태그 선택하기
		templet_list(tag_id, page); //템플릿 리스트 조회
	}

	//이미지 템플릿선택 모달창 열기 > 템플릿선택
	function templet_choice(id, bgcolor, imgpath){
		//alert("id : "+ id  +"\n"+"bgcolor : "+ bgcolor +"\n"+"imgpath : "+ imgpath);
		var obj = document.getElementById("pre_templet_bg"); //템플릿 이미지가 들어갈 공간
		obj.style.background = bgcolor;
		obj.style.backgroundImage = "url('"+ imgpath +"')";
		//obj.style.backgroundSize = "200px";
		$("#tem_id").val(id); //템플릿번호
		modal.style.display = "none"; //모달창 닫기
	}

	//상품 이미지 추가 모달창 열기
	var modal2 = document.getElementById("dh_myModal2");
	function showImg(rowid){
		//alert("rowid : "+ rowid); return;
		$("#goods_step_id").val(rowid); //선택된 STEP ID
		var span = document.getElementById("dh_close2");
		modal2.style.display = "block";
		span.onclick = function() {
			modal2.style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == modal2) {
				modal2.style.display = "none";
			}
		}
	}

	//라이브러리 페이지
	var pageLibrary = 1; //페이지
	var totalLibrary = 0; //전체수

	//라이브러리 검색내용 엔터키
	$("#id_searchLibrary").keydown(function(key) {
		if (key.keyCode == 13) {
			searchImgLibrary(); //라이브러리 검색
		}
	});

	//라이브러리 초기화
	function removeImgLibrary(){
		pageLibrary = 1; //페이지
		totalLibrary = 0; //전체수
		$("#library_append_list").html(""); //초기화
	}

	//라이브러리 검색시
	function searchImgLibrary(){
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
	}

	//라이브러리 스크롤시
	$("#library_append_list").scroll(function(){
		//alert("library_append_list"); return;
		var dh = $("#library_append_list")[0].scrollHeight;
		var dch = $("#library_append_list")[0].clientHeight;
		var dct = $("#library_append_list").scrollTop();
		//alert("스크롤 : " + dh + "=" + dch +  " + " + dct); return;
		if(dh == (dch+dct)) {
			var rowcnt = $(".img_select").length;
			//alert("totalLibrary : " + totalLibrary + " / rowcnt : " + rowcnt); return;
			if(rowcnt < totalLibrary) {
				searchLibrary();
			}
		}
	});

	//라이브러리 조회
	function searchLibrary(){
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		//alert("pageLibrary : "+ pageLibrary +", searchstr : "+ searchstr); return;
		//url: "/imglibrary/search_library",
		$.ajax({
			url: "/pop/screen/search_library",
			type: "POST",
			data: {"perpage" : "15", "page" : pageLibrary, "searchstr" : searchstr, "library_type" : library_type, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				pageLibrary = json.page;
				totalLibrary = json.total;
				$("#library_append_list").append(json.html);
			}
		});
	}

	//라이브러리 모달창 열기
	var modal3 = document.getElementById("dh_myModal3");
	function showLibrary(type){
		var span = document.getElementById("dh_close3");
		modal3.style.display = "block";
		$("#library_type").val(type); //라이브러리 타입
		if(type == "goods"){
			$("#id_modal_title").html("상품");
		}else{
			$("#id_modal_title").html("이미지");
		}
		var goods_step_id = $("#goods_step_id").val(); //상품 div ID
		var goods_name = $("#goods_name_"+ goods_step_id).val(); //상품명
		var goods_option = $("#goods_option_"+ goods_step_id).val(); //옵션명
		//alert("goods_step_id : "+ goods_step_id +", goods_name : "+ goods_name +", goods_option : "+ goods_option); return;
		var searchstr = trim(goods_name +" "+ goods_option);
		//alert("searchstr : "+ searchstr); return;
		$("#id_searchLibrary").val(searchstr); //검색내용
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
		span.onclick = function() {
			removeImgLibrary(); //라이브러리 초기화
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == modal3) {
				removeImgLibrary(); //라이브러리 초기화
				modal2.style.display = "none";
				modal3.style.display = "none";
			}
		}
	}

	//이미지 라이브러리 선택 클릭시
	function set_img_library(imgpath){
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
			remove_img(id);
			$("#div_preview"+ id).css({"background":"url(" + imgpath + ")"}); //상품 이미지 배경 URL
			$("#pre_div_preview"+ id).css({"background":"url(" + imgpath + ")"}); //미리보기 상품 이미지 배경 URL
			$("#goods_imgpath"+ id).val(imgpath); //사진 경로
			$("#pre_goods_imgpath"+ id).html(imgpath); //미리보기 사진 경로
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

	//상품 라이브러리 선택 클릭시
	function set_goods_library(imgpath, name, price, dcprice){
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
			remove_img(id);
			$("#div_preview"+ id).css({"background":"url(" + imgpath + ")"}); //상품 이미지 배경 URL
			$("#pre_div_preview"+ id).css({"background":"url(" + imgpath + ")"}); //미리보기 상품 이미지 배경 URL
			$("#goods_imgpath"+ id).val(imgpath); //사진 경로
			$("#goods_name"+ id).val(name); //상품명
			$("#goods_price"+ id).val(price); //정상가
			$("#goods_dcprice"+ id).val(dcprice); //할인가
			$("#pre_goods_imgpath"+ id).html(imgpath); //미리보기 사진 경로
			$("#pre_goods_name"+ id).html(name); //미리보기 상품명
			$("#pre_goods_price"+ id).html(price); //미리보기 정상가
			$("#pre_goods_dcprice"+ id).html(dcprice); //미리보기 할인가
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

	//데이타 변경시
	function onKeyupData(obj) {
		var name = $(obj).attr('id');
		var vlaue = $("#"+ name).val();
		//alert("name : "+ name +"\n"+"vlaue : "+ vlaue);
		$("#pre_"+ name).html(vlaue); //미리보기 영역 표시값 변경
	}

	//상품 데이타 변경시
	function chgGoodsData(id) {
		var stepId = id.substring(0, 5); //스텝 ID
		var goods_name = $("#goods_name_"+ id).val(); //상품명
		var goods_option = $("#goods_option_"+ id).val(); //옵션명
		var goods_price = $("#goods_price_"+ id).val(); //정상가
		var goods_dcprice = $("#goods_dcprice_"+ id).val(); //할인가
		if(goods_name != "") goods_name = goods_name.trim(); //상품명
		if(goods_option != "") goods_option = goods_option.trim(); //옵션명
		if(goods_price != "") goods_price = goods_price.trim(); //정상가
		if(goods_dcprice != "") goods_dcprice = goods_dcprice.trim(); //할인가
		$("#pre_goods_name_"+ id).html(goods_name); //상품명 미리보기 영역 표시값 변경
		$("#pre_goods_option_"+ id).html(goods_option); //옵션명 미리보기 영역 표시값 변경
		$("#pre_goods_price_"+ id).html(goods_price); //정상가 미리보기 영역 표시값 변경
		$("#pre_goods_dcprice_"+ id).html(goods_dcprice); //할인가 미리보기 영역 표시값 변경
		//alert("stepId : "+ stepId); return;
		if(stepId == "step1"){ //STEP1
			var goods_badge = $(":input:radio[name=goods_badge_"+ id +"]:checked").val(); //할인뱃지
			//alert("stepId : "+ stepId +", goods_badge : "+ goods_badge); return;
			goods_discount("1", id); //할인율 계산
		}
	}

	//배경 이미지 초기화
	function remove_img(rowid){
		$("#img_preview"+ rowid).attr("src", "");
		$("#img_preview"+ rowid).css("display", "none");
		$("#div_preview"+ rowid).css({"background":"url('')"});
		$("#div_preview"+ rowid).addClass("templet_img_in2");
		$("#pre_img_preview"+ rowid).attr("src", "");
		$("#pre_img_preview"+ rowid).css("display", "none");
		$("#pre_div_preview"+ rowid).css({"background":"url('')"});
		$("#pre_div_preview"+ rowid).addClass("templet_img_in3");
	}

	//이미지 선택 클릭시
	function imgChange(obj){
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", obj.value : "+ obj.value); return;
		if(obj.value.length > 0) {
			//alert("obj.value : "+ obj.value);
			if (obj.files && obj.files[0]) {
				remove_img(id);
				readURL(obj, "div_preview"+ id, id);
				modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			}
		}
	}

	//이미지 경로 세팅
	var set_goods_imgpath_id = "";
	function readURL(input, divid, id) {
		//alert("readURL(input, divid) > input.value : "+ input.value +", divid : "+ divid +", id : "+ id); return;
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#"+ divid).css({"background":"url(" + e.target.result + ")"});
				//$("#"+ divid).css({"background-size":"contain"});
				//$("#"+ divid).css({"background-repeat":"repeat-x"});
				$("#pre_"+ divid).css({"background":"url(" + e.target.result + ")"});
				//$("#pre_"+ divid).css({"background-size":"contain"});
				//$("#pre_"+ divid).css({"background-repeat":"repeat-x"});
				//$("#pre_img_file"+ id).val(input.value);
			}
			reader.readAsDataURL(input.files[0]);
			set_goods_imgpath_id = "goods_imgpath"+ id;
			//alert("set_goods_imgpath_id : "+ set_goods_imgpath_id);

			//이미지 추가
			request = new XMLHttpRequest();
			var formData = new FormData();
			formData.append("imgfile", input.files[0]); //이미지
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			request.onreadystatechange = imgCallback;
			request.open("POST", "/pop/screen/imgfile_save");
			request.send(formData);
		}
	}

	function imgCallback(){
		//console.log(request.responseText);
		if(request.readyState  == 4) {
			var obj = JSON.parse(request.responseText);
			if(obj.code == "0") { //성공
				$("#"+ set_goods_imgpath_id).val(obj.imgpath);
				$("#pre_"+ set_goods_imgpath_id).html(obj.imgpath);
			} else { //오류
			}
		}
	}

	//스마트전단 데이타 저장
	function saved(flag){
		var code = "1";
		var data_id = $("#data_id").val(); //전단번호
		var tem_id = $("#tem_id").val(); //템플릿번호
		var psd_title = $("#wl_tit").val().trim(); //행사제목
		var psd_date = $("#wl_date").val().trim(); //행사기간
		//alert("data_id : "+ data_id +"\n"+"tem_id : "+ tem_id +"\n"+"psd_title : "+ psd_title +"\n"+"psd_date : "+ psd_date); return;
		if(psd_title == ""){
			alert("행사제목을 입력하세요.");
			$("#wl_tit").focus();
			return;
		}
		if(tem_id == ""){
			alert("이미지 템플릿을 선택하세요.");
			templet_page(1); //이미지 템플릿선택 모달창 열기
			return;
		}
		if(psd_date == ""){
			alert("행사기간을 입력하세요.");
			$("#wl_date").focus();
			return;
		}
		var no = 0;
		//STEP1 ---------------------------------------------------------------------------------------------------
		var step1_yn = "N";
		var step1_yn_Y = $("#step1_yn_Y").is(":checked");
		if(step1_yn_Y){ //STEP1 사용함
			no = 0;
			var step1_cnt = $("#step1_cnt").val(); //STEP1. 할인상품 등록 상품수
			//alert("step1_cnt : "+ step1_cnt); return;
			if(step1_cnt > 0) step1_yn = "Y";
			for(var i=0; i < step1_cnt; i++){
				var goods_id = $("#goods_id_step1_"+ i).val(); //상품번호
				var goods_step = $("#goods_step_step1_"+ i).val(); //스텝
				var goods_imgpath = $("#goods_imgpath_step1_"+ i).val(); //이미지경로
				var goods_name = $("#goods_name_step1_"+ i).val().trim(); //상품명
				var goods_option = $("#goods_option_step1_"+ i).val().trim(); //옵션명
				var goods_price = $("#goods_price_step1_"+ i).val().trim(); //정상가
				var goods_dcprice = $("#goods_dcprice_step1_"+ i).val().trim(); //할인가
				var goods_badge = $(":input:radio[name=goods_badge_step1_"+ i +"]:checked").val(); //할인뱃지
				//alert("goods_badge : "+ goods_badge); return;
				if($("#goods_name_step1_"+ i).length > 0){ //해당 라인이 존재 할 경우
					no++;
					<? if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
					if(goods_imgpath == ""){
						alert("[STEP1. 할인상품 "+ no +" 번째]\n상품 이미지를 추가하세요.");
						//$("#img_file_step1_"+ i).click();
						showImg("step1_"+ i);
						return;
					}
					<? } ?>
					if(goods_name == ""){
						alert("[STEP1. 할인상품 "+ no +" 번째]\n상품명을 입력하세요.");
						$("#goods_name_step1_"+ i).focus();
						return;
					}
					if(goods_dcprice == ""){
						alert("[STEP1. 할인상품 "+ no +" 번째]\n할인가를 입력하세요.");
						$("#goods_dcprice_step1_"+ i).focus();
						return;
					}
				}
			}
		}
		//STEP2 ---------------------------------------------------------------------------------------------------
		var step2_yn = "N";
		var step2_yn_Y = $("#step2_yn_Y").is(":checked");
		//alert("step2_yn_Y : "+ step2_yn_Y); return;
		if(step2_yn_Y){ //STEP2 사용함
			var step2_cnt = Number($("#step2_cnt").val()); //STEP2 라인수
			//alert("step2_cnt : "+ step2_cnt); return;
			if(step2_cnt > 0) step2_yn = "Y";
			for(var j=1; j <= step2_cnt; j++){
				//alert("j : "+ j); return;
				no = 0;
				var stepId = "step2-"+ j;
				var line = Number($("#"+ stepId+"_cnt").val()); //라인별 상품수
				var tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
				//alert("line : "+ line +", tit_id : "+ tit_id); return;
				for(var i=0; i < line; i++){
					var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
					var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
					var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
					var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
					var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //옵션명
					var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
					var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
					var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
					if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
						no++;
						var strNo = no;
						if(step2_cnt > 1) strNo = "코너"+ j +" "+ no;
						<? if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
						if(goods_imgpath == ""){
							alert("[STEP2. 이미지형 상품등록 "+ strNo +"번째]\n상품 이미지를 추가하세요.");
							showImg(stepId +"_"+ i);
							return;
						}
						<? } ?>
						if(goods_name == ""){
							alert("[STEP2. 이미지형 상품등록 "+ strNo +"번째]\n상품명을 입력하세요.");
							$("#goods_name_"+ stepId +"_"+ i).focus();
							return;
						}
						if(goods_dcprice == ""){
							alert("[STEP2. 이미지형 상품등록 "+ strNo +"번째]\n할인가를 입력하세요.");
							$("#goods_dcprice_"+ stepId +"_"+ i).focus();
							return;
						}
					}
				}
			}
		}
		//alert("STEP2 OK"); return;
		//STEP3 ---------------------------------------------------------------------------------------------------
		var step3_yn = "N";
		var step3_yn_Y = $("#step3_yn_Y").is(":checked");
		//alert("step3_yn_Y : "+ step3_yn_Y); return;
		if(step3_yn_Y){ //STEP3 사용함
			step3_yn = "Y";
			var step3_cnt = Number($("#step3_cnt").val()); //STEP3 라인수
			//alert("step3_cnt : "+ step3_cnt);
			for(var j=1; j <= step3_cnt; j++){
				//alert("j : "+ j); return;
				no = 0;
				var stepId = "step3-"+ j;
				var line = Number($("#"+ stepId+"_cnt").val()); //라인별 상품수
				var tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP3 타이틀번호
				//alert("line : "+ line +", tit_id : "+ tit_id); return;
				for(var i=0; i < line; i++){
					var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
					var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
					var goods_imgpath = ""; //$("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
					var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
					var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //옵션명
					var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
					var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
					var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
					if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
						no++;
						var strNo = no;
						if(step3_cnt > 1) strNo = "코너"+ j +" "+ no;
						//if(goods_imgpath == ""){
						//	alert("[STEP3. 텍스트형 상품등록 "+ strNo +"번째]\n상품 이미지를 추가하세요.");
						//	showImg(stepId +"_"+ i);
						//	return;
						//}
						if(goods_name == ""){
							alert("[STEP3. 텍스트형 상품등록 "+ strNo +"번째]\n상품명을 입력하세요.");
							$("#goods_name_"+ stepId +"_"+ i).focus();
							return;
						}
						if(goods_dcprice == ""){
							alert("[STEP3. 텍스트형 상품등록 "+ strNo +"번째]\n할인가를 입력하세요.");
							$("#goods_dcprice_"+ stepId +"_"+ i).focus();
							return;
						}
					}
				}
			}
		}
		//alert("STEP3 OK"); return;
		//alert("OK"); return;
		if(data_id != "" && flag == ""){ //수정의 경우
			if(!confirm("수정 하시겠습니까?")){
				return;
			}
		}
		//스마트전단 데이타 저장
		$.ajax({
			url: "/pop/screen/screen_data_save",
			type: "POST",
			data: {"flag" : flag, "data_id" : data_id, "tem_id" : tem_id, "psd_title" : psd_title, "psd_date" : psd_date, "step1_yn" : step1_yn, "step2_yn" : step2_yn, "step3_yn" : step3_yn, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				//alert("json.id : "+ json.id +", json.psd_code : "+ json.psd_code +", json.flag : "+ json.flag);
				if(json.code == "0") { //성공
					goods_del(json.id); //스마트전단 상품 삭제 (초기화)
					goods_save(json.id); //스마트전단 상품 저장
					$("#data_id").val(json.id); //전단번호
					if(json.flag == "lms"){ //문자메시지 발송
						location.href = "/dhnbiz/sender/send/lms?psd_code="+ json.psd_code; //문자메시지 발송 페이지
					}else if(json.flag == "talk_adv"){ //알림톡 발송
						location.href = "/dhnbiz/sender/send/talk_adv?psd_code="+ json.psd_code; //알림톡 발송 페이지
					}else{
						showSnackbar("저장 되었습니다.", 1500);
						setTimeout(function() {
								location.href = "/pop/screen"; //스마트전단 목록 페이지
						}, 1000); //1초 지연
					}
				}
			}
		});
		//alert("OK"); return;
	}

	//스마트전단 상품 저장
	function goods_save(data_id){
		var cnt = 0;
		var ui = new Array();
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 배열생성 > STEP1
		//--------------------------------------------------------------------------------------------------------------
		var step1_yn_Y = $("#step1_yn_Y").is(":checked");
		if(step1_yn_Y){ //STEP1 사용함
			var step1_cnt = Number($("#step1_cnt").val()); //STEP1. 할인상품 등록 상품수
			//alert("step1_cnt : "+ step1_cnt);
			for(var i=0; i < step1_cnt; i++){
				var goods_id = $("#goods_id_step1_"+ i).val(); //상품번호
				var goods_step = $("#goods_step_step1_"+ i).val(); //스텝
				var goods_imgpath = $("#goods_imgpath_step1_"+ i).val(); //이미지경로
				var goods_name = $("#goods_name_step1_"+ i).val().trim(); //상품명
				var goods_option = $("#goods_option_step1_"+ i).val().trim(); //옵션명
				var goods_price = $("#goods_price_step1_"+ i).val().trim(); //정상가
				var goods_dcprice = $("#goods_dcprice_step1_"+ i).val().trim(); //할인가
				var goods_seq = $("#goods_seq_step1_"+ i).val(); //정렬순서
				var goods_badge = $(":input:radio[name=goods_badge_step1_"+ i +"]:checked").val(); //할인뱃지
				//alert("goods_badge : "+ goods_badge); return;
				if($("#goods_name_step1_"+ i).length > 0){ //해당 라인이 존재 할 경우
					var pushData = {
						"data_id" : data_id, //전단번호
						"goods_id" : goods_id, //상품번호
						"tit_id" : 0, //타이틀번호
						"goods_step" : goods_step, //스텝
						"goods_step_no" : 0, //스텝순번
						"goods_imgpath" : goods_imgpath, //이미지경로
						"goods_name" : goods_name, //상품명
						"goods_option" : goods_option, //옵션명
						"goods_price" : goods_price, //정상가
						"goods_dcprice" : goods_dcprice, //할인가
						"goods_seq" : goods_seq, //할인가
						"goods_badge" : goods_badge, //할인뱃지
					};
					ui.push(pushData);
					cnt++;
					//alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
				}
			}
		}
		//if( cnt > 0 ) alert("STEP1 cnt : "+ cnt +", ui : "+ JSON.stringify(ui));
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 배열생성 > STEP2
		//--------------------------------------------------------------------------------------------------------------
		var step2_yn_Y = $("#step2_yn_Y").is(":checked");
		//alert("step2_yn_Y : "+ step2_yn_Y);
		if(step2_yn_Y){ //STEP2 사용함
			var step2_cnt = Number($("#step2_cnt").val()); //STEP2 라인수
			//alert("step2_cnt : "+ step2_cnt);
			for(var j=1; j <= step2_cnt; j++){
				var stepId = "step2-"+ j;
				var line = Number($("#"+ stepId+"_cnt").val()); //라인번호
				var tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
				//alert("stepId : "+ stepId +", line : "+ line +", tit_id : "+ tit_id); return;
				for(var i=0; i < line; i++){
					var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
					var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
					var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
					var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
					var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //옵션명
					var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
					var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
					var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
					if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
						var pushData = {
							"data_id" : data_id, //전단번호
							"goods_id" : goods_id, //상품번호
							"tit_id" : tit_id, //타이틀번호
							"goods_step" : goods_step, //스텝
							"goods_step_no" : j, //스텝순번
							"goods_imgpath" : goods_imgpath, //이미지경로
							"goods_name" : goods_name, //상품명
							"goods_option" : goods_option, //옵션명
							"goods_price" : goods_price, //정상가
							"goods_dcprice" : goods_dcprice, //할인가
							"goods_seq" : goods_seq, //할인가
						};
						ui.push(pushData);
						cnt++;
						//alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
					}
				}
			}
		}
		//if( cnt > 0 ) alert("STEP2 cnt : "+ cnt +", ui : "+ JSON.stringify(ui));
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 배열생성 > STEP3
		//--------------------------------------------------------------------------------------------------------------
		var step3_yn_Y = $("#step3_yn_Y").is(":checked");
		if(step3_yn_Y){ //STEP3 사용함
			var step3_cnt = Number($("#step3_cnt").val()); //STEP3 라인수
			//alert("step3_cnt : "+ step3_cnt);
			for(var j=1; j <= step3_cnt; j++){
				var stepId = "step3-"+ j;
				var line = Number($("#"+ stepId+"_cnt").val()); //라인번호
				var tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
				//alert("line : "+ line +", tit_id : "+ tit_id); return;
				for(var i=0; i < line; i++){
					var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
					var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
					var goods_imgpath = ""; //$("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
					var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
					var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //옵션명
					var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
					var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
					var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
					if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
						var pushData = {
							"data_id" : data_id, //전단번호
							"goods_id" : goods_id, //상품번호
							"tit_id" : tit_id, //타이틀번호
							"goods_step" : goods_step, //스텝
							"goods_step_no" : j, //스텝순번
							"goods_imgpath" : goods_imgpath, //이미지경로
							"goods_name" : goods_name, //상품명
							"goods_option" : goods_option, //옵션명
							"goods_price" : goods_price, //정상가
							"goods_dcprice" : goods_dcprice, //할인가
							"goods_seq" : goods_seq, //할인가
						};
						ui.push(pushData);
						cnt++;
						//alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
					}
				}
			}
		}
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 저장
		//--------------------------------------------------------------------------------------------------------------
		if( cnt > 0 ) {
			var uiStr = JSON.stringify(ui);
			//alert("cnt : "+ cnt +", uiStr : "+ uiStr); return;
			//jsLoading(); //로딩중 호출
			var formData = new FormData();
			formData.append("updata", uiStr);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/pop/screen/screen_goods_arr_save",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					//$('#overlay').fadeIn();
				},
				complete: function () {
					//$('#overlay').fadeOut();
				},
				success: function (json) {
				}
			});
		}
	}

	//스마트전단 상품 사용여부
	function goods_yn(stepId, dis){
		//alert("stepId : "+ stepId +", dis : "+ dis);
		$("#goods_list_"+ stepId).css("display", dis);
		$("#pre_goods_list_"+ stepId).css("display", dis);
		if(dis != "none"){ //사용함의 경우
			var step_cnt = $("#"+ stepId +"_cnt").val(); //STEP별 등록 상품수
			//alert("stepId : "+ stepId +", dis : "+ dis +", step_cnt : "+ step_cnt);
			if(stepId == "step1" && step_cnt == 0){ //STEP1 등록된 상품이 없는 경우
				for(i = 0; i < <?=$step1_std?>; i++){
					goods_append('1', 'step1'); //STEP1 할인상품 등록 영역 추가
				}
			}else if(stepId == "step2" && step_cnt == 0){ //STEP2 등록된 상품이 없는 경우
				goods_area_copy('goods_list_step2', 'step2'); //STEP2. 이미지형 상품등록 영역 복사
			}else if(stepId == "step3" && step_cnt == 0){ //STEP3 등록된 상품이 없는 경우
				goods_area_copy('goods_list_step3', 'step3'); //STEP3. 텍스트형 상품등록 영역 복사
			}
		}
	}

	//스마트전단 상품 삭제 (초기화)
	function goods_del(data_id){
		$.ajax({
			url: "/pop/screen/screen_goods_del",
			type: "POST",
			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {}
		});
	}

	//할인율 체크
	function chkBadge(stepNo, id, data){
		//alert("stepNo : "+ stepNo +", id : "+ id +", data : "+ data);
		var dis = "";
		if(data == "0"){
			dis = "none";
		}else{
			goods_discount(stepNo, id); //할인율 계산
		}
		$("#pre_goods_badge_cd_"+ id).html(data);
		$("#pre_goods_badge_"+ id).css("display", dis);
	}

	//할인율 계산
	function goods_discount(stepNo, id){
		//alert("stepNo : "+ stepNo +", id : "+ id);
		var price = $("#goods_price_"+ id).val().replace(/[^0-9]/g,''); //정상가 숫자만 추출
		var dcprice = $("#goods_dcprice_"+ id).val().replace(/[^0-9]/g,''); //할인가 숫자만 추출
		var rate = "";
		//alert(" id : "+ id +", price : "+ price +", dcprice : "+ dcprice);
		if(price != "" && dcprice != ""){ //정상가 할인가 모두 있는 경우
			rate = Math.round( 100 - ( (dcprice / price) * 100) ); //할인율
			//alert(" id : "+ id +", price : "+ price +", dcprice : "+ dcprice +", rate : "+ rate);
		}
		$("#pre_goods_badge_"+ id).html(rate +"<span>%</span>");
	}

	//상품 영역 추가
	function goods_append(step, id){
		var no = $("#"+ id +"_cnt").val(); //상품 등록 상품수
		$("#"+ id +"_cnt").val(Number(no)+1); //상품 등록 상품수
		//alert(id +"_cnt : "+ no);
		var html = 	'';
		if(step == "3"){ //STEP3
			html += '<li id="'+ id +'_'+ no +'" class="dl_step'+ step +'">';
			html += '  <span class="txt_name"><input type="text" id="goods_name_'+ id +'_'+ no +'" value="" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="신라면" class="int100_l"></span>';
			html += '  <span class="txt_option"><input type="text" id="goods_option_'+ id +'_'+ no +'" value="" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="5입" class="int100_r"></span>';
			html += '  <span class="txt_price1"><input type="text" id="goods_price_'+ id +'_'+ no +'" value="" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="4,000원" class="int100_r"></span>';
			html += '  <span class="txt_price2"><input type="text" id="goods_dcprice_'+ id +'_'+ no +'" value="" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="3,000원" class="int100_r"></span>';
			html += '  <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del(\''+ id +'\', \''+ no +'\');" title="삭제"></a></span>';
			html += '  <input type="hidden" id="goods_id_'+ id +'_'+ no +'" value="">';
			html += '  <input type="hidden" id="goods_step_'+ id +'_'+ no +'" value="3" style="width:30px;">';
			html += '  <input type="hidden" id="goods_div_'+ id +'_'+ no +'" value="'+ id +'_'+ no +'" style="width:80px;">';
			html += '  <input type="hidden" id="goods_seq_'+ id +'_'+ no +'" value="<?=$ii?>" style="width:40px;">';
			html += '  <input type="hidden" id="goods_option_'+ id +'_'+ no +'" value="">';
			html += '</li>';
		}else{ //STEP1~2
			var goods_price = "4,000원"; //정상가
			var goods_dcprice = "3,000원"; //할인가
			if(step == "1"){ //STEP1
				goods_price = "4,000"; //정상가
				goods_dcprice = "3,000"; //할인가
			}
			html += '<dl id="'+ id +'_'+ no +'" class="dl_step'+ step +'">';
			html += '    <dt>';
			html += '      <div class="templet_img_in" onclick="showImg(\''+ id +'_'+ no +'\')">';
			html += '        <div id="div_preview_'+ id +'_'+ no +'">';
			html += '          <img id="img_preview_'+ id +'_'+ no +'" style="display:none;width:100%">';
			html += '        </div>';
			html += '      </div>';
			html += '    </dt>';
			html += '    <dd>';
			html += '      <ul>';
			html += '          <input type="hidden" id="goods_id_'+ id +'_'+ no +'"></li>';
			html += '          <input type="hidden" id="goods_step_'+ id +'_'+ no +'" value="'+ step +'" style="width:60px;"></li>';
			html += '          <input type="hidden" id="goods_div_'+ id +'_'+ no +'" value="'+ id +'_'+ no +'" style="width:80px;"></li>';
			html += '          <input type="hidden" id="goods_seq_'+ id +'_'+ no +'" value="'+ no +'" style="width:42px;"></li>';
			html += '          <input type="hidden" id="goods_imgpath_'+ id +'_'+ no +'"></li>';
			html += '          <li><span>상품명</span> <input type="text" id="goods_name_'+ id +'_'+ no +'" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="신라면" class="int140"></li>';
			html += '          <li><span>옵션명</span> <input type="text" id="goods_option_'+ id +'_'+ no +'" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder=" 5입" class="int140"></li>';
			html += '          <li><span>정상가</span> <input type="text" id="goods_price_'+ id +'_'+ no +'" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="'+ goods_price +'" class="int140"></li>';
			html += '          <li><span>할인가</span> <input type="text" id="goods_dcprice_'+ id +'_'+ no +'" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\')" placeholder="'+ goods_dcprice +'" class="int140"></li>';
			if(step == "1"){ //STEP1
				html += '          <li><span>할인율</span>';
				html += '            <div class="checks" style="display:inline-block; margin-top:3px;">';
				html += '              <input type="radio" name="goods_badge_'+ id +'_'+ no +'" value="1" id="goods_badge_on_'+ id +'_'+ no +'" onClick="chkBadge(\''+ step +'\', \''+ id +'_'+ no +'\', \'1\');" checked>';
				html += '              <label for="goods_badge_on_'+ id +'_'+ no +'">표기함</label>';
				html += '              <input type="radio" name="goods_badge_'+ id +'_'+ no +'" value="0" id="goods_badge_off_'+ id +'_'+ no +'" onClick="chkBadge(\''+ step +'\', \''+ id +'_'+ no +'\', \'0\');">';
				html += '              <label for="goods_badge_off_'+ id +'_'+ no +'">표기안함</label>';
				html += '            </div>';
				html += '          </li>';
			}
			html += '      </ul>';
			html += '    </dd>';
			html += '    <span class="move_btn_group">';
			html += '       <a class="move_last_left" href="javascript:goods_move(\'first\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="처음으로이동"></a>';
			html += '       <a class="move_left" href="javascript:goods_move(\'left\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="왼쪽으로이동"></a>';
			html += '       <a class="move_right" href="javascript:goods_move(\'right\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="오른쪽으로이동"></a>';
			html += '       <a class="move_last_right" href="javascript:goods_move(\'last\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="마지막으로이동"></a>';
			html += '       <a class="move_del" href="javascript:area_del(\''+ id +'\', \''+ no +'\');" title="삭제"></a>';
			html += '    </span>';
			html += '</dl>';
		}
		$('#area_goods_'+ id).append(html);

		//미리보기 영역
		var html = 	'';
		if(step == "2"){ //STEP2
			html += '<div class="pop_list01" id="pre_'+ id +'_'+ no +'">';
			html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'/dhn/images/leaflets/sample_img.jpg\');">';
			html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //STEP2 상품 이미지
			html += '  </div>';
			html += '  <div class="pop_price">';
			html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000원</p>'; //STEP1 정상가
			html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000원</p>'; //STEP1 할인가
			html += '    </ul>';
			html += '  </div>';
			html += '  <div class="pop_name">';
			html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">농심 신라면 120g 5입 1+1</span>'; //STEP2 상품명
			html += '    <span id="pre_goods_option_'+ id +'_'+ no +'"></span>'; //STEP2 옵션명
			html += '    <div id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></div>'; //STEP2 이미지경로
			html += '  </div>';
			html += '</div>';
		}else if(step == "3"){ //STEP3
			html += '<div class="pop_list02" id="pre_'+ id +'_'+ no +'">';
			html += '  <div class="name">';
			html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">농심 신라면 120g 5입 1+1</span>'; //STEP3 상품명
			html += '    <span id="pre_goods_option_'+ id +'_'+ no +'"></span>'; //STEP3 옵션명
			html += '  </div>';
			html += '  <span class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000원</span>'; //STEP3 정상가
			html += '  <span class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000원</span>'; //STEP3 할인가
			html += '</div>';
		}else{ //STEP1
			html += '<dl id="pre_'+ id +'_'+ no +'">';
			html += '  <dt>';
			html += '    <div id="pre_goods_badge_'+ id +'_'+ no +'" class="sale_badge" style="display:;">25<span>%</span></div>';
			html += '    <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'/dhn/images/leaflets/sample_img.jpg\');">';
			html += '      <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //STEP1 상품 이미지
			html += '    </div>';
			html += '  </dt>';
			html += '  <dd>';
			html += '    <ul>';
			html += '        <li id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000</li>'; //STEP1 정상가
			html += '        <li id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000</li>'; //STEP1 할인가
			html += '        <li class="name">';
			html += '          <span id="pre_goods_name_'+ id +'_'+ no +'">농심 신라면 120g 5입 1+1</span>'; //STEP1 상품명
			html += '          <span id="pre_goods_option_'+ id +'_'+ no +'"></span>'; //STEP1 옵션명
			html += '          <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">1</span>'; //STEP1 뱃지
			html += '          <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //STEP1 이미지경로
			html += '        </li>';
			html += '    </ul>';
			html += '  </dd>';
			html += '</dl>';
		}
		$('#pre_area_goods_'+ id).append(html);
	}

	//상품 삭제
	function area_del(step, no){
		//alert("step : "+ step +", no : "+ no); return;
		var id = step +"_"+ no;
		var cnt = Number($("#"+ step +"_cnt").val()); //상품수
		var nxno = Number(no)+1; //다음 상품번호
		var stepId = id.substring(0, 5); //스텝 ID
		$("#"+ step +"_cnt").val(cnt-1); //상품수
		//alert("step : "+ step +", no : "+ no +", id : "+ id +", cnt : "+ cnt +", nxno : "+ nxno +", stepId : "+ stepId); return;
		if(cnt > nxno){ //중간에 삭제된 경우
			for(var i=nxno; i<cnt; i++){
				var id1 = step +"_"+ i; //이전 ID
				var id2 = step +"_"+ (i-1); //새로운 ID
				//alert("id1 : "+ id1 +", id2 : "+ id2);
				var goods_name = $("#goods_name_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name); return;
				var goods_option = $("#goods_option_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option); return;
				var goods_price = $("#goods_price_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option +", goods_price : "+ goods_price); return;
				var goods_dcprice = $("#goods_dcprice_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option +", goods_price : "+ goods_price +", goods_dcprice : "+ goods_dcprice); return;
				var goods_seq = (i-1);
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option +", goods_price : "+ goods_price +", goods_dcprice : "+ goods_dcprice +", goods_seq : "+ goods_seq); return;

				$("#goods_name_"+ id2).val(goods_name); //상품명
				$("#goods_option_"+ id2).val(goods_option); //옵션명
				$("#goods_price_"+ id2).val(goods_price); //정상가
				$("#goods_dcprice_"+ id2).val(goods_dcprice); //할인가
				$("#goods_seq_"+ id2).val(goods_seq); //정렬순서
				$("#pre_goods_name_"+ id2).html(goods_name); //미리보기 상품명
				$("#pre_goods_option_"+ id2).html(goods_option); //미리보기 옵션명
				$("#pre_goods_price_"+ id2).html(goods_price); //미리보기 정상가
				$("#pre_goods_dcprice_"+ id2).html(goods_dcprice); //미리보기 할인가

				if(stepId == "step1" || stepId == "step2"){ //STEP1~2
					remove_img("_"+ id2);
					var goods_imgpath = $("#goods_imgpath_"+ id1).val();
					$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath + ")"}); //사진
					$("#goods_imgpath_"+ id2).val(goods_imgpath); //이미지경로
					$("#pre_div_preview_"+ id2).css({"background":"url(" + goods_imgpath + ")"}); //미리보기 사진
					$("#pre_goods_imgpath_"+ id2).html(goods_imgpath); //미리보기 이미지경로
				}
			}
		}
		var delId = step +"_"+ (cnt-1);
		//alert("delId : "+ delId);
		$("#"+ delId).remove();
		$("#pre_"+ delId).remove();
		//alert("stepId : "+ stepId +", cnt : "+ cnt);
		//if(cnt == "1") $("input:radio[name='"+ stepId +"_yn']:radio[value='N']").prop('checked', true); //모두 삭제시 사용안함 선택하기
		if(stepId == "step1" && cnt == "1"){ //STEP1
			goods_yn(stepId, "none"); //모두 삭제시 사용안함 처리
			$("input:radio[name='"+ stepId +"_yn']:radio[value='N']").prop('checked', true); //모두 삭제시 사용안함 선택하기
		}
		//alert("cnt : "+ $("#"+ step +"_cnt").val());
	}

	//상품이동
	function goods_move(type, step, id){
		//alert("type : "+ type +", step : "+ step +", id : "+ id); return;
		var i = 0;
		var seq = Number($("#goods_seq_"+ id).val()); //정렬번호
		var cnt = Number($("#"+ step +"_cnt").val()); //전체수
		//alert("seq : "+ seq +", cnt : "+ cnt); return;
		var no = 0;
		var no1 = 0;
		var no2 = 0;
		var id1 = "";
		var id2 = "";
		//alert("type : "+ type +", step : "+ step +", id : "+ id +", no : "+ no); return;
		if((type == "left" && seq > 0) || (type == "right" && (seq+1) < cnt)){ //왼쪽, 오른쪽 클릭
			if(type == "left"){
				no = -1;
			}else if(type == "right"){
				no = 1;
			}
			no1 = Number(seq);
			no2 = Number(seq) + no;
			id1 = step +"_"+ no1;
			id2 = step +"_"+ no2;
			//alert("type : "+ type +", no : "+ no +", no1 : "+ no1 +", no2 : "+ no2 +", id1 : "+ id1 +", id2 : "+ id2); return;

			var goods_name1 = $("#goods_name_"+ id1).val();
			var goods_option1 = $("#goods_option_"+ id1).val();
			var goods_price1 = $("#goods_price_"+ id1).val();
			var goods_dcprice1 = $("#goods_dcprice_"+ id1).val();
			var goods_imgpath1 = $("#goods_imgpath_"+ id1).val();
			var goods_seq1 = $("#goods_seq_"+ id1).val();
			var goods_badge1 = "";
			var goods_name2 = $("#goods_name_"+ id2).val();
			var goods_option2 = $("#goods_option_"+ id2).val();
			var goods_price2 = $("#goods_price_"+ id2).val();
			var goods_dcprice2 = $("#goods_dcprice_"+ id2).val();
			var goods_imgpath2 = $("#goods_imgpath_"+ id2).val();
			var goods_seq2 = $("#goods_seq_"+ id2).val();
			var goods_badge2 = "";
			//alert("goods_name1 : "+ goods_name1 +", goods_seq1 : "+ goods_seq1 +"\n"+"goods_name2 : "+ goods_name2 +", goods_seq2 : "+ goods_seq2 +"\n"+"goods_badge1 : "+ goods_badge1 +", goods_badge2 : "+ goods_badge2); return;

			remove_img("_"+ id1);
			$("#div_preview_"+ id1).css({"background":"url(" + goods_imgpath2 + ")"});
			$("#goods_name_"+ id1).val(goods_name2); //상품명
			$("#goods_option_"+ id1).val(goods_option2); //옵션명
			$("#goods_price_"+ id1).val(goods_price2); //정상가
			$("#goods_dcprice_"+ id1).val(goods_dcprice2); //할인가
			$("#goods_imgpath_"+ id1).val(goods_imgpath2); //이미지경로
			//$("#goods_seq_"+ id1).val(goods_seq1); //정렬순서
			$("#pre_div_preview_"+ id1).css({"background":"url(" + goods_imgpath2 + ")"});
			$("#pre_goods_name_"+ id1).html(goods_name2); //미리보기 상품명
			$("#pre_goods_option_"+ id1).html(goods_option2); //미리보기 옵션명
			$("#pre_goods_price_"+ id1).html(goods_price2); //미리보기 정상가
			$("#pre_goods_dcprice_"+ id1).html(goods_dcprice2); //미리보기 할인가
			$("#pre_goods_imgpath_"+ id1).html(goods_imgpath2); //미리보기 이미지경로
			remove_img("_"+ id2);
			$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath1 + ")"});
			$("#goods_name_"+ id2).val(goods_name1); //상품명
			$("#goods_option_"+ id2).val(goods_option1); //옵션명
			$("#goods_price_"+ id2).val(goods_price1); //정상가
			$("#goods_dcprice_"+ id2).val(goods_dcprice1); //할인가
			$("#goods_imgpath_"+ id2).val(goods_imgpath1); //이미지경로
			//$("#goods_seq_"+ id2).val(goods_seq2); //정렬순서
			$("#pre_div_preview_"+ id2).css({"background":"url(" + goods_imgpath1 + ")"});
			$("#pre_goods_name_"+ id2).html(goods_name1); //미리보기 상품명
			$("#pre_goods_option_"+ id2).html(goods_option1); //미리보기 옵션명
			$("#pre_goods_price_"+ id2).html(goods_price1); //미리보기 정상가
			$("#pre_goods_dcprice_"+ id2).html(goods_dcprice1); //미리보기 할인가
			$("#pre_goods_imgpath_"+ id2).html(goods_imgpath1); //미리보기 이미지경로
			//alert("d1 : "+ d1 +", d2 : "+ d2);
			if(step == "step1"){ //STEP1
				goods_badge1 = $(":input:radio[name=goods_badge_"+ id1 +"]:checked").val();
				goods_badge2 = $(":input:radio[name=goods_badge_"+ id2 +"]:checked").val();
				$("input:radio[name='goods_badge_"+ id1 +"']:radio[value='"+ goods_badge2 +"']").prop('checked', true); //할인율 표기
				$("input:radio[name='goods_badge_"+ id2 +"']:radio[value='"+ goods_badge1 +"']").prop('checked', true); //할인율 표기
				//alert("id1 : "+ id1 +", goods_badge1 : "+ goods_badge1 +", id2 : "+ id2 +", goods_badge2 : "+ goods_badge2);
				if(goods_badge2 != "0"){ //할인율 표기함
					goods_discount("1", id1); //할인율 계산
					chkBadge("1", id1, goods_badge2); //미리보기 할인율 표기함
				}else{
					chkBadge("1", id1, "0"); //미리보기 할인율 표기안함 선택
				}
				if(goods_badge1 != "0"){ //할인율 표기함
					goods_discount("1", id2); //할인율 계산
					chkBadge("1", id2, goods_badge1); //미리보기 할인율 표기함
				}else{
					chkBadge("1", id2, "0"); //미리보기 할인율 표기안함 선택
				}
			}
		}else if(type == "first" || type == "last"){ //처음으로, 끝으로 클릭
			var goods_name_temp = "";
			var goods_option_temp = "";
			var goods_price_temp = "";
			var goods_dcprice_temp = "";
			var goods_imgpath_temp = "";
			var goods_badge_cd_temp = "";
			if(type == "first"){ //처음으로
				no1 = 0;
				no2 = Number(seq);
				goods_name_temp = $("#pre_goods_name_"+ step +"_"+ no2).html();
				goods_option_temp = $("#pre_goods_option_"+ step +"_"+ no2).html();
				goods_price_temp = $("#pre_goods_price_"+ step +"_"+ no2).html();
				goods_dcprice_temp = $("#pre_goods_dcprice_"+ step +"_"+ no2).html();
				goods_imgpath_temp = $("#pre_goods_imgpath_"+ step +"_"+ no2).html();
				if(step == "step1") goods_badge_cd_temp = $("#pre_goods_badge_cd_"+ step +"_"+ no2).html();
			}else if(type == "last"){ //끝으로
				no1 = Number(seq);
				no2 = Number(cnt)-1;
				goods_name_temp = $("#pre_goods_name_"+ step +"_"+ no1).html();
				goods_option_temp = $("#pre_goods_option_"+ step +"_"+ no1).html();
				goods_price_temp = $("#pre_goods_price_"+ step +"_"+ no1).html();
				goods_dcprice_temp = $("#pre_goods_dcprice_"+ step +"_"+ no1).html();
				goods_imgpath_temp = $("#pre_goods_imgpath_"+ step +"_"+ no1).html();
				//if(step == "step1") goods_badge_cd_temp = $(":input:radio[name=goods_badge_cd_"+ step +"_"+ no1 +"]:checked").val(); //STEP1
				if(step == "step1") goods_badge_cd_temp = $("#pre_goods_badge_cd_"+ step +"_"+ no1).html();
			}
			//alert("type : "+ type +", no : "+ no +", no1 : "+ no1 +", no2 : "+ no2 +"\n"+"goods_name_temp : "+ goods_name_temp +", goods_badge_cd_temp : "+ goods_badge_cd_temp); return;
			for(var ii=no1; ii<=no2; ii++){
				if(type == "first"){ //처음으로
					id1 = step +"_"+ (ii-1);
					id2 = step +"_"+ ii;
				}else if(type == "last"){ //끝으로
					id1 = step +"_"+ (ii+1);
					id2 = step +"_"+ ii;
				}
				//alert("type : "+ type +", id1 : "+ id1 +", id2 : "+ id2);
				goods_badge_cd = "";
				if( (type == "first" && ii == no1) || (type == "last" && ii == no2) ){ //(처음으로 && 선택 상품) || (끝으로 && 선택 상품)
					goods_name = goods_name_temp;
					goods_option = goods_option_temp;
					goods_price = goods_price_temp;
					goods_dcprice = goods_dcprice_temp;
					goods_imgpath = goods_imgpath_temp;
					if(step == "step1") goods_badge_cd = goods_badge_cd_temp; //STEP1
					//alert("goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_imgpath : "+ goods_imgpath); return;
				}else{
					//alert("id1 : "+ id1 +", id2 : "+ id2);
					goods_name = $("#pre_goods_name_"+ id1).html();
					goods_option = $("#pre_goods_option_"+ id1).html();
					goods_price = $("#pre_goods_price_"+ id1).html();
					goods_dcprice = $("#pre_goods_dcprice_"+ id1).html();
					goods_imgpath = $("#pre_goods_imgpath_"+ id1).html();
					if(step == "step1") goods_badge_cd = $("#pre_goods_badge_cd_"+ id1).html();
				}
				//alert("["+ ii +"] id1 : "+ id2 +", id2 : "+ id2 +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_imgpath : "+ goods_imgpath +"\n"+"goods_badge_cd : "+ goods_badge_cd);
				$("#goods_name_"+ id2).val(goods_name); //상품명
				$("#goods_option_"+ id2).val(goods_option); //옵션명
				$("#goods_price_"+ id2).val(goods_price); //정상가
				$("#goods_dcprice_"+ id2).val(goods_dcprice); //할인가
				$("#goods_imgpath_"+ id2).val(goods_imgpath); //이미지경로
				$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath + ")"}); //이미지배경URL
				if(step == "step1") $("input:radio[name='goods_badge_"+ id2 +"']:radio[value='"+ goods_badge_cd +"']").prop('checked', true); //할인율 표기
			}
			for(var ii=no1; ii<=no2; ii++){
				//alert("["+ ii +"] goods_name : "+ $("#goods_name_"+ step +"_"+ ii).val());
				$("#pre_goods_name_"+ step +"_"+ ii).html($("#goods_name_"+ step +"_"+ ii).val()); //상품명
				$("#pre_goods_option_"+ step +"_"+ ii).html($("#goods_option_"+ step +"_"+ ii).val()); //옵션명
				$("#pre_goods_price_"+ step +"_"+ ii).html($("#goods_price_"+ step +"_"+ ii).val()); //정상가
				$("#pre_goods_dcprice_"+ step +"_"+ ii).html($("#goods_dcprice_"+ step +"_"+ ii).val()); //할인가
				$("#pre_goods_imgpath_"+ step +"_"+ ii).html($("#goods_imgpath_"+ step +"_"+ ii).val()); //이미지경로
				$("#pre_div_preview_"+ step +"_"+ ii).css({"background":"url(" + $("#goods_imgpath_"+ step +"_"+ ii).val() + ")"}); //이미지배경URL
				if(step == "step1"){ //STEP1
					var goods_badge = $(":input:radio[name=goods_badge_"+ step +"_"+ ii +"]:checked").val();
					if(goods_badge != "0"){ //할인율 표기함
						goods_discount("1", step +"_"+ ii); //할인율 계산
						chkBadge("1", step +"_"+ ii, goods_badge); //미리보기 할인율 표기함
					}else{
						chkBadge("1", step +"_"+ ii, "0"); //미리보기 할인율 표기안함 선택
					}
				}
			}
		}
	}

	//STEP1. 할인상품 위치이동
	/*$("#area_goods_step1").sortable({
		//var id = $(this).attr("id");
		//alert("id : "+ id);
		stop: function(event, ui) {
			var html = [];
			var i = 0;
			var id = "";
			$("#area_goods_step1 > dl.dl_step1").each(function(t) {
				id = $(this).attr("id");
				$("#goods_seq_"+ id).val(i);
				html[i] = "<dl id=\"pre_"+ id +"\">"+ $("#pre_"+ id).html() +"</dl>";
				//alert("["+ i +"] : "+ i);
				i++;
			});
			$("#pre_area_goods_step1").html("");
			var i = 0;
			$("#area_goods_step1 > dl.dl_step1").each(function(t) {
				//alert("["+ i +"] : "+ i);
				//alert("html["+ i +"] : "+ html);
				$("#pre_area_goods_step1").append(html[i]);
				i++;
			});
		}
	});*/

	//상품 영역 복사
	function goods_area_copy(area, step){
		var step_cnt = Number($("#"+ step +"_cnt").val()); //기존 라인수
		var no = step_cnt + 1;
		//alert("area : "+ area +", step : "+ step +", step_cnt : "+ step_cnt +", no : "+ no);
		//if(step == "step3") alert("area : "+ area +", step : "+ step +", step_cnt : "+ step_cnt +", no : "+ no); return;
		var regex = new RegExp(step +'-0', 'gi'); //변경값
		var copy = $("#"+ area +"-0").html(); //입력란 복사 대상 영역
		//if(step == "step3") alert("area : "+ area +", step : "+ step +", copy : "+ copy); return;
		var html = '<div id="'+ area +'-'+ no +'">\n'+ copy.replace(regex, step +'-'+ no) +'</div>\n'; //입력란 븉여넣기 HTML
		//alert("html : "+ html); return;
		//if(step == "step3") alert("html : "+ html); return;
		$("#"+ area).append(html); //입력란 븉여넣기 영역
		$("#"+ step +"-"+ no +"_no").val(no); //라인번호

		//미리보기 영역
		var pre_copy = $("#pre_"+ area +"-0").html(); //미리보기 복사 대상 영역
		html = '<div id="pre_'+ area +'-'+ no +'">\n'+ pre_copy.replace(regex, step +'-'+ no) +'</div>\n'; //입력란 븉여넣기 HTML
		//alert("html : "+ html); return;
		$("#pre_"+ area).append(html); //입력란 븉여넣기 영역
		$("#"+ step +"_cnt").val(no); //라인수 증가
		if(step_cnt > 0){ //등록된 상품영역이 있는 경우
			$("#btn_add_"+ step +"-"+ (step_cnt)).css("display", "none"); //이전 상품영역 추가 버튼 비활성화
		}
	}

	//상품 영역 삭제
	function goods_area_del(area, step, stepId){
		var cnt = Number($("#"+ step +"_cnt").val()); //라인수
		var no = Number($("#"+ stepId +"_no").val()); //라인번호
		var nxno = no+1; //다음 라인번호
		//alert("area : "+ area +", step : "+ step +", stepId : "+ stepId +"\n"+"cnt : "+ cnt +", no : "+ no +", nxno : "+ nxno); return;
		if(cnt == no){ //마지막 라인 삭제의 경우
			if(no > 1){ //라인수가 1개 이상인 경우
				var btn = "btn_add_"+ step +"-"+ (no-1);
				//alert("btn : "+ btn);
				$("#"+ btn).css("display", ""); //상품코너 추가 버튼 활성화
			}
		}else{ //마지막 라인이 아닌 경우
			//alert("cnt : "+ cnt +", nxno : "+ nxno);
			for(var i=nxno; i<=cnt; i++){
				var id1 = step +"-"+ i; //이전 ID
				var id2 = step +"-"+ (i-1); //새로운 ID
				//alert("id1 : "+ id1 +", id2 : "+ id2);
				var org1 = $("#"+ area + id1).html();
				//alert("org1 : "+ org1);
				var regex1 = new RegExp(id1, 'gi');
				org1 = org1.replace(regex1, id2);
				//alert("rep org1 : "+ org1);
				$("#"+ area +id2).html(org1);
				var pre_org1 = $("#pre_"+ area + id1).html();
				pre_org1 = pre_org1.replace(regex1, id2);
				$("#pre_"+area + id2).html(pre_org1);
				$("#"+ id2 +"_no").val(i-1);
			}
		}
		$("#"+ area + step +"-"+ cnt).remove(); //마지막 라인 삭제
		$("#pre_"+ area + step +"-"+ cnt).remove(); //미리보기 마지막 라인 삭제
		$("#"+ step +"_cnt").val(cnt-1); //라인수 감소
		if(cnt == "1"){ //모두 삭제의 경우
			goods_yn(step, "none"); //모두 삭제시 사용안함 처리
			$("input:radio[name='"+ step +"_yn']:radio[value='N']").prop('checked', true); //모두 삭제시 사용안함 선택하기
		}
	}

	//영역에 값 넣기
	function areaSet(id, html){
		//alert("id : "+ id +", html : "+ html); return;
		$("#"+ id).html(html); //입력에 값 넣기
	}

	//상품 엑셀 업로드
	function excelExport(input, step, id){
		var file = document.getElementById("excelFile_"+ id).value;
		//alert("file : "+ file); return;
		var ext = file.slice(file.indexOf(".") + 1).toLowerCase();
		//alert("file : "+ file +", ext : "+ ext); return;
		if (ext == "xls" || ext == "xlsx") {
			var gno = $("#"+ id +"_cnt").val(); //기존 상품수
			//alert("handleExcelDataHtml > 기존 상품수 gno : "+ gno +", step : "+ step +", id : "+ id);
			for(var i = 0; i < gno; i++){ //기존 상품 삭제
				area_del(id, i); //기존 상품 초기화
			}
			if(step == "1") { //STEP1의 경우
				$("#goods_list_"+ id).css("display", "");
				$("#pre_goods_list_"+ id).css("display", "");
				$("input:radio[name='"+ id +"_yn']:radio[value='Y']").prop('checked', true); //사용함 선택하기
			}
			//alert("기존 상품 초기화 완료"); return;
			//var file_data = $("input[name=excelFile_"+ id +"]")[0].files[0];
			var file_data = input.files[0];
			var rtn_data = step +"§§"+ id; //리턴값 : 스텝No§§스텝ID
			//alert("file_data : "+ file_data); return;
			var formData = new FormData();
			formData.append("file", file_data);
			formData.append("rtn_data", rtn_data);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/pop/screen/excel_upload_ok",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
					//alert("excel_upload_ok");
					var no = 0;
					$.each(json, function(key, value){
						var goods_name = value.name; //상품명
						var goods_option = value.option; //옵션명
						var goods_price = value.price; //정상가
						var goods_dcprice = value.dcprice; //할인가
						var goods_imgpath = value.imgpath; //이미지 경로
						var goods_seq = value.seq; //정렬순서
						var rtn_data = value.rtn_data; //리턴값 : 스텝No§§스텝ID
						var arr_data = rtn_data.split("§§");
						var rtn_sno = arr_data[0]; //스텝No
						var rtn_sid = arr_data[1]; //스텝ID
						//alert("["+ no +"] "+ goods_name +", "+ goods_option +", "+ goods_price +", "+ goods_dcprice +", "+ rtn_data);
						goods_append(rtn_sno, rtn_sid); //상품정보 입력란 추가
						var stepId = rtn_sid +"_"+ no;
						$("#goods_name_"+ stepId).val(goods_name); //상품명
						$("#goods_option_"+ stepId).val(goods_option); //옵션명
						$("#goods_price_"+ stepId).val(goods_price); //정상가
						$("#goods_dcprice_"+ stepId).val(goods_dcprice); //할인가
						$("#goods_seq_"+ stepId).val(no); //정렬순서
						if(goods_name == "") goods_name = "상품명"; //미리보기 상품명
						if(goods_option == "") goods_option = ""; //미리보기 옵션명
						if(goods_price == "") goods_price = ""; //미리보기 정상가
						if(goods_dcprice == "") goods_dcprice = "0원"; //미리보기 할인가
						$("#pre_goods_name_"+ stepId).html(goods_name); //미리보기 상품명
						$("#pre_goods_option_"+ stepId).html(goods_option); //미리보기 옵션명
						$("#pre_goods_price_"+ stepId).html(goods_price); //미리보기 정상가
						$("#pre_goods_dcprice_"+ stepId).html(goods_dcprice); //미리보기 할인가
						if(rtn_sno != "3" && goods_name != "" && goods_name != "상품명"){ //STEP1~2. 이미지형 상품등록
							remove_img("_"+ stepId); //배경 이미지 초기화
							var img_path = value.img_path; //이미지 경로
							$("#goods_imgpath_"+ stepId).val(img_path); //이미지 경로
							$("#div_preview_"+ stepId).css({"background":"url(" + img_path + ")"});
							$("#pre_goods_imgpath_"+ stepId).html(img_path); //미리보기 이미지 경로
							$("#pre_div_preview_"+ stepId).css({"background":"url(" + img_path + ")"});
							if(rtn_sno == "1"){ //STEP1
								if(goods_price != ""){ //정상가가 있는 경우
									goods_discount("1", stepId); //할인율 계산
								}else{
									$("input:radio[name='goods_badge_"+ stepId +"']:radio[value='0']").prop('checked', true); //할인율 표기안함 선택하기
									chkBadge("1", stepId, "0"); //미리보기 할인율 표기안함 선택
								}
							}
						}
						no++;
					});
				}
			});
		} else {
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
		}
	}
</script>
<?
	if($screen_data->psd_id == ""){ //신규등록의 경우
		echo "<script>\n";
		if($screen_data->psd_step1_yn != "N"){ //신규등록 && STEP1 사용함의 경우
			if($step1_org < $step1_std){ //STEP1. 할인상품 등록수가 기본 갯수보다 작은 경우
				for($jj = $step1_org; $jj < $step1_std; $jj++){
					echo "  goods_append('1', 'step1'); //STEP1. 할인상품 영역 추가\n";
				}
			}
		}
		if($screen_data->psd_step2_yn != "N"){ //STEP2 사용함의 경우
			if($step2_cnt < 1){ //STEP2. 등록건이 없는 경우
				echo "  goods_area_copy('goods_list_step2', 'step2'); //STEP2. 이미지형 상품등록 영역 복사\n";
			}
		}
		if($screen_data->psd_step3_yn != "N"){ //STEP3 사용함의 경우
			if($step3_cnt < 1){ //STEP3. 등록건이 없는 경우
				echo "  goods_area_copy('goods_list_step3', 'step3'); //STEP3. 텍스트형 상품등록 영역 복사\n";
			}
		}
		echo "</script>\n";
	}
?>
<div id="snackbar"></div><?//모달창 div?>
<script>
	//모달창 메시지
	function showSnackbar(msg, delay) {
		var x = document.getElementById("snackbar");
		x.innerHTML = msg;
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, delay);
	}
</script>
