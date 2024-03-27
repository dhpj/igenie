<?
	$sample_img = "/dhn/images/leaflets/sample_img.jpg"; //샘플이미지 경로
?>
<div class="wrap_leaflets">
    <div class="s_tit">
        스마트쿠폰 만들기1
        <div class="btn_list">
            <a href="/spop/coupon<?=($add !="") ? "?add=". $add : ""?>">스마트쿠폰 목록으로</a>
        </div>
    </div>
    <input type="hidden" id="data_id" value="<?=$data[0]->pcd_id?>">
    <input type="hidden" id="coupon_seq" value="<?=empty($data) ? "1" : count($data)?>">
    <input type="hidden" id="img_seq" value="">
    <input type="hidden" id="img_type" value="">
    <div class="write_leaflets">
        <div class="wl_lbox">
            <div id="coupon_append_div">
            <?
            if (empty($data)){
            ?>
                <div id="coupon_0" class="coupon">
                    <input type="hidden" id="pcd_type_0" value="<?=($data->pcd_type !="") ? $data->pcd_type : "1"?>">
                    <div class="tit_box">
                        <ul>
                            <li id="li_type_0_1" class='type_tab <?=$data->pcd_type == "1" || empty($data) ? ' tm_on' : ''?>' onclick='coupon_type(this, 1, 0)'><a><span>TYPE 1.</span> 무료증정 쿠폰</a></li>
                            <li id="li_type_0_2" class='type_tab <?=$data->pcd_type == "2" ? ' tm_on' : ''?>' onclick='coupon_type(this, 2, 0)'><a><span>TYPE 2.</span> 가격할인 쿠폰</a></li>
                            <li id="li_type_0_3" class='type_tab <?=$data->pcd_type == "3" ? ' tm_on' : ''?>' onclick='coupon_type(this, 3, 0)'><a><span>TYPE 3.</span> 타입3</a></li>
                        </ul>
                    </div>
                    <div class="wl_main_goods" id="coupon_type1_0" style="display:<? if($data->pcd_type == "2"){ ?>none<? } ?>"><?//무료증정 쿠폰 입력란?>
                        <dl class="dl_step1">
                            <dt>
                                <input type="hidden" id="pcd_imgpath_0" value="<?=$data->pcd_imgpath?>" style="width:600px;">
                                <div class="templet_img_in" onclick="showImg('', 0)">
                                    <div id="div_preview">
                                        <img id="img_preview_0" src="<?=$data->pcd_imgpath?>">
                                    </div>
                                </div>
                            </dt>
                            <dd>
                                <ul>
                                    <li>
                                        <span class="tit">행사기간</span>
        		                        <input type="text" id="pcd_date_type1_0" value="<?=$data->pcd_date?>" onkeyup="chgData('pcd_date_type1_0')" placeholder="2020.11.11. 단하루">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_date_type1_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_date_type1_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">쿠폰제목</span>
        				                <input type="text" id="pcd_title_type1_0" value="<?=$data->pcd_title?>" onkeyup="chgData('pcd_title_type1_0')" placeholder="카톡친구 무료증정">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus" onClick="size_change('pcd_title_type1_0', 'minus');">-</span>
                                            <span class="counter_plus" onClick="size_change('pcd_title_type1_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">상품정보</span>
        				                <input type="text" id="pcd_option1_type1_0" value="<?=$data->pcd_option1?>" onkeyup="chgData('pcd_option1_type1_0')" placeholder="크리넥스 안심 키친타올">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus" onClick="size_change('pcd_option1_type1_0', 'minus');">-</span>
                                            <span class="counter_plus" onClick="size_change('pcd_option1_type1_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                    <span class="tit">쿠폰옵션</span>
        			                    <input type="text" id="pcd_option2_type1_0" value="<?=$data->pcd_option2?>" onkeyup="chgData('pcd_option2_type1_0')" placeholder="선착순 100개 한정수량">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus" onClick="size_change('pcd_option2_type1_0', 'minus');">-</span>
                                            <span class="counter_plus" onClick="size_change('pcd_option2_type1_0', 'plus');">+</span>
                                        </div>
                                    </li>
        														<li>
                                        <span class="tit">바코드</span>
                                        <div class="barcode">
                                            <input type="hidden" id="apply_barcode_type1_0" value="0">
        									<select id="select_barcode_length_type1_0" onchange="changeBarcodeSelect(this, 'type1', 0)">
                                                <option value="13" selected="">13자리</option>
                                                <option value="8">8자리</option>
                                            </select>
        									<input type="text" id="barcode_txt_type1_0" value="" onkeyup="" maxlength=13 style="width:120px !important;">
                                            <button type="button" class="btn_bar_ok" onclick="apply_barcode('type1', 0)"> 적용</button>
        									<!-- <label for="barcode_img_type1_0" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label> -->
        									<input type="file" title="바코드이미지 파일" id="barcode_img_type1_0" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">
        									<button type="button" class="btn_bar_no" onclick="delete_barcode('type1', 0)"> 바코드 삭제하기</button>
        								</div>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
        				<button type="button" class="smart_coupon_del" onclick="delete_type(0)"><span class="material-icons">clear</span></button>
                    </div>
                    <div class="wl_main_goods" id="coupon_type2_0" style="display:<? if($data->pcd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 입력란?>
                        <ul>
                            <li>
                                <span class="tit">행사기간</span>
        		                <input type="text" id="pcd_date_type2_0" value="<?=$data->pcd_date?>" onkeyup="chgData('pcd_date_type2_0')" placeholder="2020.11.11. 단하루">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_date_type2_0', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_date_type2_0', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">쿠폰제목</span>
        	                    <input type="text" id="pcd_title_type2_0" value="<?=$data->pcd_title?>" onkeyup="chgData('pcd_title_type2_0')" placeholder=" 카톡친구 무료증정">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_title_type2_0', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_title_type2_0', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">쿠폰옵션1</span>
        		                <input type="text" id="pcd_option1_type2_0" value="<?=$data->pcd_option1?>" onkeyup="chgData('pcd_option1_type2_0')" placeholder="3만원이상 구매시(1인1매)">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_option1_type2_0', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_option1_type2_0', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">할인금액</span>
                                <input type="text" id="pcd_price_type2_0" value="<?=$data->pcd_price?>" onkeyup="chgData('pcd_price_type2_0')" placeholder="3,000원">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_price_type2_0', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_price_type2_0', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">쿠폰옵션2</span>
        	                    <input type="text" id="pcd_option2_type2_0" value="<?=$data->pcd_option2?>" onkeyup="chgData('pcd_option2_type2_0')" placeholder="선착순 100명 현장할인" >
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_option2_type2_0', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_option2_type2_0', 'plus');">+</span>
                                </div>
                            </li>
        					<li>
                                <span class="tit">바코드</span>
                                <input type="hidden" id="apply_barcode_type2_0" value="0">
                                <div class="barcode">
        		                    <select id="select_barcode_length_type2_0" onchange="changeBarcodeSelect(this, 'type2', 0)">
                                        <option value="13" selected="">13자리</option>
                                        <option value="8">8자리</option>
                                    </select>
        							<input type="text" id="barcode_txt_type2_0" value="" onkeyup="" maxlength=13 style="width:120px !important;">
                                    <button type="button" class="btn_bar_ok" onclick="apply_barcode('type2', 0)"> 적용</button>
        							<label for="barcode_img_type2_0" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>
        							<input type="file" title="바코드이미지 파일" id="barcode_img_type2_0" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">
        							<button type="button" class="btn_bar_no" onclick="delete_barcode('type2', 0)"> 바코드 삭제하기</button>
        						</div>
                            </li>
                        </ul>
                        <button type="button" class="smart_coupon_del" onclick="delete_type(0)"><span class="material-icons">clear</span></button>
                    </div>
                    <div class="wl_main_goods" id="coupon_type3_0" style='display:none;'>
                    	<dl class="dl_step1">
                            <dt>
                                <input type="hidden" id="pcd_imgpath3_0" value="<?=$data->pcd_imgpath?>" style="width:600px;">
                                <div class="templet_img_in" onclick="showImg(3, 0)">
                                    <div id="div_preview">
                                        <img id="img_preview3_0" src="<?=$data->pcd_imgpath?>">
                                    </div>
                                </div>
                            </dt>
                    		<dd>
                        		<ul>
                        			<li>
                        			<span class="tit">메시지</span>
                        			<input id='pcd_message_type3_0' type="text" onkeyup="chgData('pcd_message_type3_0')" placeholder='<?=$this->member->item('mem_username')?>'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                        				<span class="counter_minus button" onClick="size_change('pcd_message_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_message_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">쿠폰제목</span>
                        			<input id='pcd_title_type3_0' type="text" onkeyup="chgData('pcd_title_type3_0')" placeholder='할인쿠폰'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_title_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_title_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">쿠폰사용기간</span>
                        			<input id='pcd_date_type3_0' type="text" onkeyup="chgData('pcd_date_type3_0')" placeholder='쿠폰사용기간 23.08.07(월)~08.08(화)'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_date_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_date_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">상품정보</span>
                        			<input id='pcd_option1_type3_0' type="text" onkeyup="chgData('pcd_option1_type3_0')" placeholder='신라면'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_option1_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_option1_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                                    <li>
                        			<span class="tit">쿠폰옵션</span>
                        			<input id='pcd_option2_type3_0' type="text" onkeyup="chgData('pcd_option2_type3_0')" placeholder='5만원이상 구매시'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_option2_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_option2_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">규격</span>
                        			<input id='pcd_stan_type3_0' type="text" onkeyup="chgData('pcd_stan_type3_0')" placeholder='5입'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_stan_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_stan_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">정상가</span>
                        			<input id='pcd_price_type3_0' type="text" onkeyup="chgData('pcd_price_type3_0')" placeholder='4,990원'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_price_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_price_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">판매가</span>
                        			<input id='pcd_dcprice_type3_0' type="text" onkeyup="chgData('pcd_dcprice_type3_0')" placeholder='3,990원'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_dcprice_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_dcprice_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">하단정보</span>
                        			<input id='pcd_ucomment_type3_0' type="text" onkeyup="chgData('pcd_ucomment_type3_0')" placeholder='쿠폰이 발행되었습니다!'>
                        			<div class="counter">
                        				<span class="text">글자크기</span>
                                        <span class="counter_minus button" onClick="size_change('pcd_ucomment_type3_0', 'minus');">-</span>
                        				<span class="counter_plus button" onClick="size_change('pcd_ucomment_type3_0', 'plus');">+</span>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">배경색</span>
                        			<div class="counter_color" data-seq='0'>
                        				<?include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/coupon/bootstrap/color.php');?>
                        			</div>
                        			</li>
                        			<li>
                        			<span class="tit">바코드</span>
                        			<div class="barcode">
                        				<input type="hidden">
                        				<select id="select_barcode_length_type3_0" onchange="changeBarcodeSelect(this, 'type3', 0)">
                        					<option value="13" selected="">13자리</option>
                        					<option value="8">8자리</option>
                        				</select>
                        				<input type="text" id="barcode_txt_type3_0" maxlength=13 style="width:120px !important;">
                        				<button type="button" class="btn_bar_ok" onclick="apply_barcode('type3', 0)"> 적용</button>
                        				<label for="barcode_img_type3_0" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>
                        				<input type="file" title="바코드이미지 파일" id="barcode_img_type3_0" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">
                        				<button type="button" class="btn_bar_no" onclick="delete_barcode('type3', 0)"> 바코드 삭제하기</button>
                        			</div>
                        			</li>
                        		</ul>
                    		</dd>
                    	</dl>
                    	<button type="button" class="smart_coupon_del"><span class="material-icons">clear</span></button>
                    </div>
                </div>
            <?
            } else {
                $seq = 0;
                foreach($data as $a){
            ?>
                <div id="coupon_<?=$seq?>" class="coupon">
                    <input type="hidden" id="pcd_type_<?=$seq?>" value="<?=($a->pcdd_type !="") ? $a->pcdd_type : "1"?>">
                    <div class="tit_box">
                        <ul>
                            <li id="li_type_<?=$seq?>_1" class='type_tab <?=$a->pcdd_type == "1" ? ' tm_on' : ''?>' onclick='coupon_type(this, 1, <?=$seq?>)'><a><span>TYPE 1.</span> 무료증정 쿠폰</a></li>
                            <li id="li_type_<?=$seq?>_2" class='type_tab <?=$a->pcdd_type == "2" ? ' tm_on' : ''?>' onclick='coupon_type(this, 2, <?=$seq?>)'><a><span>TYPE 2.</span> 가격할인 쿠폰</a></li>
                            <li id="li_type_<?=$seq?>_2" class='type_tab <?=$a->pcdd_type == "3" ? ' tm_on' : ''?>' onclick='coupon_type(this, 3, <?=$seq?>)'><a><span>TYPE 3.</span> 타입3</a></li>
                        </ul>
                    </div>
                    <div class="wl_main_goods" id="coupon_type1_<?=$seq?>" style="display:<? if($a->pcdd_type != "1"){ ?>none<? } ?>"><?//무료증정 쿠폰 입력란?>
                        <dl class="dl_step1">
                            <dt>
                                <input type="hidden" id="pcd_imgpath_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? $a->pcdd_imgpath : ''?>" style="width:600px;">
                                <div class="templet_img_in" onclick="showImg('', <?=$seq?>)">
                                    <div id="div_preview">
                                        <img id="img_preview_<?=$seq?>" src="<?=$a->pcdd_type == '1' ? $a->pcdd_imgpath : ''?>">
                                    </div>
                                </div>
                            </dt>
                            <dd>
                                <ul>
                                    <li>
                                        <span class="tit">행사기간</span>
        		                        <input type="text" id="pcd_date_type1_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? $a->pcdd_date : ''?>" onkeyup="chgData('pcd_date_type1_<?=$seq?>')" placeholder="2020.11.11. 단하루">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_date_type1_<?=$seq?>', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_date_type1_<?=$seq?>', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">쿠폰제목</span>
        				                <input type="text" id="pcd_title_type1_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? $a->pcdd_title : ''?>" onkeyup="chgData('pcd_title_type1_<?=$seq?>')" placeholder="카톡친구 무료증정">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus" onClick="size_change('pcd_title_type1_<?=$seq?>', 'minus');">-</span>
                                            <span class="counter_plus" onClick="size_change('pcd_title_type1_<?=$seq?>', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">상품정보</span>
        				                <input type="text" id="pcd_option1_type1_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? $a->pcdd_option1 : ''?>" onkeyup="chgData('pcd_option1_type1_<?=$seq?>')" placeholder="크리넥스 안심 키친타올">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus" onClick="size_change('pcd_option1_type1_<?=$seq?>', 'minus');">-</span>
                                            <span class="counter_plus" onClick="size_change('pcd_option1_type1_<?=$seq?>', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                    <span class="tit">쿠폰옵션</span>
        			                    <input type="text" id="pcd_option2_type1_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? $a->pcdd_option2 : ''?>" onkeyup="chgData('pcd_option2_type1_<?=$seq?>')" placeholder="선착순 100개 한정수량">
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus" onClick="size_change('pcd_option2_type1_<?=$seq?>', 'minus');">-</span>
                                            <span class="counter_plus" onClick="size_change('pcd_option2_type1_<?=$seq?>', 'plus');">+</span>
                                        </div>
                                    </li>
        							<li>
                                        <span class="tit">바코드</span>
                                        <div class="barcode">
                                            <input type="hidden" id="apply_barcode_type1_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? ($a->barcode_txt_length_flag != 0 ? "1" : "0") : '0'?>">
        									<select id="select_barcode_length_type1_<?=$seq?>" onchange="changeBarcodeSelect(this, 'type1', <?=$seq?>)">
                                                <option value="13" <?=$a->pcdd_type == '1' ? ($a->barcode_txt_length_flag == 13 ? "selected" : "") : 'selected'?>>13자리</option>
                                                <option value="8" <?=$a->pcdd_type == '1' ? ($a->barcode_txt_length_flag == 8 ? "selected" : "") : ''?>>8자리</option>
                                            </select>
        									<input type="text" id="barcode_txt_type1_<?=$seq?>" value="<?=$a->pcdd_type == '1' ? $a->barcode_txt : ''?>" onkeyup="" maxlength=<?=$a->pcdd_type == '1' ? ($a->barcode_txt_length_flag == 8 ? 8 : 13) : 13?> style="width:120px !important;">
                                            <button type="button" class="btn_bar_ok" onclick="apply_barcode('type1', <?=$seq?>)"> 적용</button>
        									<label for="barcode_img_type1_<?=$seq?>" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>
        									<input type="file" title="바코드이미지 파일" id="barcode_img_type1_<?=$seq?>" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">
        									<button type="button" class="btn_bar_no" onclick="delete_barcode('type1', <?=$seq?>)"> 바코드 삭제하기</button>
        								</div>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
        				<button type="button" class="smart_coupon_del" onclick="delete_type(<?=$seq?>)"><span class="material-icons">clear</span></button>
                    </div>
                    <div class="wl_main_goods" id="coupon_type2_<?=$seq?>" style="display:<? if($a->pcdd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 입력란?>
                        <ul>
                            <li>
                                <span class="tit">행사기간</span>
        		                <input type="text" id="pcd_date_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? $a->pcdd_date : ''?>" onkeyup="chgData('pcd_date_type2_<?=$seq?>')" placeholder="2020.11.11. 단하루">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_date_type2_<?=$seq?>', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_date_type2_<?=$seq?>', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">쿠폰제목</span>
        	                    <input type="text" id="pcd_title_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? $a->pcdd_title : ''?>" onkeyup="chgData('pcd_title_type2_<?=$seq?>')" placeholder=" 카톡친구 무료증정">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_title_type2_<?=$seq?>', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_title_type2_<?=$seq?>', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">쿠폰옵션1</span>
        		                <input type="text" id="pcd_option1_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? $a->pcdd_option1 : ''?>" onkeyup="chgData('pcd_option1_type2_<?=$seq?>')" placeholder="3만원이상 구매시(1인1매)">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_option1_type2_<?=$seq?>', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_option1_type2_<?=$seq?>', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">할인금액</span>
                                <input type="text" id="pcd_price_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? $a->pcdd_price : ''?>" onkeyup="chgData('pcd_price_type2_<?=$seq?>')" placeholder="3,000원">
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_price_type2_<?=$seq?>', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_price_type2_<?=$seq?>', 'plus');">+</span>
                                </div>
                            </li>
                            <li>
                                <span class="tit">쿠폰옵션2</span>
        	                    <input type="text" id="pcd_option2_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? $a->pcdd_option2 : ''?>" onkeyup="chgData('pcd_option2_type2_<?=$seq?>')" placeholder="선착순 100명 현장할인" >
                                <div class="counter">
                                    <span class="text">글자크기</span>
                                    <span class="counter_minus" onClick="size_change('pcd_option2_type2_<?=$seq?>', 'minus');">-</span>
                                    <span class="counter_plus" onClick="size_change('pcd_option2_type2_<?=$seq?>', 'plus');">+</span>
                                </div>
                            </li>
        					<li>
                                <span class="tit">바코드</span>
                                <input type="hidden" id="apply_barcode_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? ($a->barcode_txt_length_flag != 0 ? "1" : "0") : '0'?>">
                                <div class="barcode">
        		                    <select id="select_barcode_length_type2_<?=$seq?>" onchange="changeBarcodeSelect(this, 'type2', <?=$seq?>)">
                                        <option value="13" <?=$a->pcdd_type == '2' ? ($a->barcode_txt_length_flag == 13 ? "selected" : "") : 'selected'?>>13자리</option>
                                        <option value="8" <?=$a->pcdd_type == '2' ? ($a->barcode_txt_length_flag == 8 ? "selected" : "") : ''?>>8자리</option>
                                    </select>
        							<input type="text" id="barcode_txt_type2_<?=$seq?>" value="<?=$a->pcdd_type == '2' ? $a->barcode_txt : ''?>" onkeyup="" maxlength=<?=$a->pcdd_type == '2' ? ($a->barcode_txt_length_flag == 8 ? 8 : 13) : 13?> style="width:120px !important;">
                                    <button type="button" class="btn_bar_ok" onclick="apply_barcode('type2', <?=$seq?>)"> 적용</button>
        							<label for="barcode_img_type2_<?=$seq?>" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>
        							<input type="file" title="바코드이미지 파일" id="barcode_img_type2_<?=$seq?>" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">
        							<button type="button" class="btn_bar_no" onclick="delete_barcode('type2', <?=$seq?>)"> 바코드 삭제하기</button>
        						</div>
                            </li>
                        </ul>
                        <button type="button" class="smart_coupon_del" onclick="delete_type(<?=$seq?>)"><span class="material-icons">clear</span></button>
                    </div>
                    <div class="wl_main_goods" id="coupon_type3_<?=$seq?>" style="display:<? if($a->pcdd_type != "3"){ ?>none<? } ?>">
                        <dl class="dl_step1">
                            <dt>
                                <input type="hidden" id="pcd_imgpath3_<?=$seq?>" value="<?=$a->pcdd_type == '3' ? $a->pcdd_imgpath : ''?>" style="width:600px;">
                                <div class="templet_img_in" onclick="showImg(3, <?=$seq?>)">
                                    <div id="div_preview">
                                        <img id="img_preview3_<?=$seq?>" src="<?=$a->pcdd_type == '3' ? $a->pcdd_imgpath : ''?>">
                                    </div>
                                </div>
                            </dt>
                            <dd>
                                <ul>
                                    <li>
                                        <span class="tit">메시지</span>
                                        <input id='pcd_message_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_message : ''?>' onkeyup="chgData('pcd_message_type3_<?=$seq?>')" placeholder='<?=$this->member->item('mem_username')?>'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_message_type3_<?=$seq?>', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_message_type3_<?=$seq?>', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">쿠폰제목</span>
                                        <input id='pcd_title_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_title : ''?>' onkeyup="chgData('pcd_title_type3_0')" placeholder='할인쿠폰'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_title_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_title_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">쿠폰사용기간</span>
                                        <input id='pcd_date_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_date : ''?>' onkeyup="chgData('pcd_date_type3_0')" placeholder='쿠폰사용기간 23.08.07(월)~08.08(화)'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_date_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_date_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">상품정보</span>
                                        <input id='pcd_option1_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_option1 : ''?>' onkeyup="chgData('pcd_option1_type3_0')" placeholder='신라면'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_option1_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_option1_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">쿠폰옵션</span>
                                        <input id='pcd_option2_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_option2 : ''?>' onkeyup="chgData('pcd_option2_type3_0')" placeholder='5만원이상 구매시'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_option2_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_option2_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">규격</span>
                                        <input id='pcd_stan_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_stan : ''?>' onkeyup="chgData('pcd_stan_type3_0')" placeholder='5입'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_stan_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_stan_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">정상가</span>
                                        <input id='pcd_price_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_price : ''?>' onkeyup="chgData('pcd_price_type3_0')" placeholder='4,990원'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_price_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_price_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">판매가</span>
                                        <input id='pcd_dcprice_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_dcprice : ''?>' onkeyup="chgData('pcd_dcprice_type3_0')" placeholder='3.990원'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_dcprice_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_dcprice_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">하단정보</span>
                                        <input id='pcd_ucomment_type3_<?=$seq?>' type="text" value='<?=$a->pcdd_type == '3' ? $a->pcdd_ucomment : ''?>' onkeyup="chgData('pcd_ucomment_type3_0')" placeholder='쿠폰이 발행되었습니다!'>
                                        <div class="counter">
                                            <span class="text">글자크기</span>
                                            <span class="counter_minus button" onClick="size_change('pcd_ucomment_type3_0', 'minus');">-</span>
                                            <span class="counter_plus button" onClick="size_change('pcd_ucomment_type3_0', 'plus');">+</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">배경색</span>
                                        <div class="counter_color" data-seq='<?=$seq?>'>
                                            <div class="color_chip color01" style="background-color:#d8282f"></div>
                                            <div class="color_chip color02" style="background-color:#9d1000"></div>
                                            <div class="color_chip color03" style="background-color:#ae0009"></div>
                                            <div class="color_chip color04" style="background-color:#f63756"></div>
                                            <div class="color_chip color05" style="background-color:#ff7800"></div>
                                            <div class="color_chip color06" style="background-color:#ff1b41"></div>
                                            <div class="color_chip color07" style="background-color:#f85333"></div>
                                            <div class="color_chip color08" style="background-color:#ff9400"></div>
                                            <div class="color_chip color10" style="background-color:#e9ab00"></div>
                                            <div class="color_chip color11" style="background-color:#ffa400"></div>
                                            <div class="color_chip color13" style="background-color:#d08e01"></div>
                                            <div class="color_chip color14" style="background-color:#a39b87"></div>
                                            <div class="color_chip color15" style="background-color:#71675d"></div>
                                            <div class="color_chip color17" style="background-color:#59301a"></div>
                                            <div class="color_chip color18" style="background-color:#299e00"></div>
                                            <div class="color_chip color19" style="background-color:#008b6e"></div>
                                            <div class="color_chip color20" style="background-color:#005e3c"></div>
                                            <div class="color_chip color21" style="background-color:#a9d44f"></div>
                                            <div class="color_chip color23" style="background-color:#409834"></div>
                                            <div class="color_chip color24" style="background-color:#87a395"></div>
                                            <div class="color_chip color25" style="background-color:#8794a3"></div>
                                            <div class="color_chip color26" style="background-color:#00acaa"></div>
                                            <div class="color_chip color27" style="background-color:#0050c7"></div>
                                            <div class="color_chip color28" style="background-color:#00aacf"></div>
                                            <div class="color_chip color29" style="background-color:#01508d"></div>
                                            <div class="color_chip color30" style="background-color:#0138ed"></div>
                                            <div class="color_chip color31" style="background-color:#132062"></div>
                                            <div class="color_chip color32" style="background-color:#c138c1"></div>
                                            <div class="color_chip color33" style="background-color:#9032d2"></div>
                                            <div class="color_chip color34" style="background-color:#410098"></div>
                                            <div class="color_chip color39" style="background-color:#6299fe"></div>
                                            <div class="color_chip color37" style="background-color:#363636"></div>
                                            <div class="color_chip color38" style="background-color:#000000"></div>
                                            <div class="color_chip color40" style="background:linear-gradient( to top, #ffa400, #f63756 );"></div>
                                            <div class="color_chip color41" style="background:linear-gradient( to top, #01508d, #00acaa );"></div>
                                            <div class="color_chip color42" style="background:linear-gradient( to top, #00aacf, #c138c1 );"></div>
                                            <div class="color_chip color43" style="background:linear-gradient( to top, #3B2667, #BC78EC );"></div>
                                            <div class="color_chip color44" style="background:linear-gradient( to top, #360940, #F05F57 );"></div>
                                            <div class="color_chip color45" style="background:linear-gradient( to top, #623AA2, #F97794 );"></div>
                                            <div class="color_chip color46" style="background:linear-gradient( to top, #ffa400, #FD6E6A );"></div>
                                            <div class="color_chip color47" style="background:linear-gradient( to top, #513162, #c138c1 );"></div>
                                            <div class="color_chip color48" style="background:linear-gradient( to top, #BB4E75, #FF9D6C );"></div>
                                            <div class="color_chip color49" style="background:linear-gradient( to top, #123597, #00aacf );"></div>
                                            <div class="color_chip color50" style="background:linear-gradient( to top, #ffa400, #c138c1 );"></div>
                                            <div class="color_chip color51" style="background-color:#F97794"></div>
                                            <div class="color_chip color52" style="background-color:#FD6E6A "></div>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="tit">바코드</span>
                                        <input type="hidden" id="apply_barcode_type3_<?=$seq?>" value="<?=$a->pcdd_type == '3' ? ($a->barcode_txt_length_flag != 0 ? "1" : "0") : '0'?>">
                                        <div class="barcode">
                                            <select id="select_barcode_length_type3_<?=$seq?>" onchange="changeBarcodeSelect(this, 'type3', <?=$seq?>)">
                                                <option value="13" <?=$a->pcdd_type == '3' ? ($a->barcode_txt_length_flag == 13 ? "selected" : "") : 'selected'?>>13자리</option>
                                                <option value="8" <?=$a->pcdd_type == '3' ? ($a->barcode_txt_length_flag == 8 ? "selected" : "") : ''?>>8자리</option>
                                            </select>
                                            <input type="text" id="barcode_txt_type3_<?=$seq?>" value="<?=$a->pcdd_type == '3' ? $a->barcode_txt : ''?>" onkeyup="" maxlength=<?=$a->pcdd_type == '3' ? ($a->barcode_txt_length_flag == 8 ? 8 : 13) : 13?> style="width:120px !important;">
                                            <button type="button" class="btn_bar_ok" onclick="apply_barcode('type3', <?=$seq?>)"> 적용</button>
                                            <label for="barcode_img_type3_<?=$seq?>" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>
                                            <input type="file" title="바코드이미지 파일" id="barcode_img_type3_<?=$seq?>" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">
                                            <button type="button" class="btn_bar_no" onclick="delete_barcode('type3', <?=$seq?>)"> 바코드 삭제하기</button>
                                        </div>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <button type="button" class="smart_coupon_del" onclick="delete_type(<?=$seq?>)"><span class="material-icons">clear</span></button>
                    </div>
                </div>
            <?
                    $seq++;
                }
            }
            ?>
            </div>
            <div class="btn_coupon_add">
                <a href="#" onclick="addCoupon();return false;"><i class="xi-coupon"></i> 스마트쿠폰 추가하기</a>
            </div>
        </div><!--//wl_lbox-->
        <div class="wl_rbox">
            <!-- <p class="wl_rbox_tit">
                스마트쿠폰 미리보기
            </p> -->
            <div class="smart_coupon_preview">
                <div id="coupon_img_append_div">
                <?
                if (empty($data)){
                ?>
                    <div id="coupon_img_0">
                        <div class="wl_r_preview" id="pre_coupon_type1_0"  style="display:<? if($data->pcd_type == "2"){ ?>none<? } ?>"><?//무료증정 쿠폰 미리보기?>
                            <div class="pre_box_wrap1">
                                <!--템플릿 이미지가 들어갈 공간-->
                                <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg1.jpg') no-repeat top center;background-color:#47b5e5"></div>
                                <!--//템플릿 이미지가 들어갈 공간-->
                                <div class="pre_box1">
                                    <p id="pre_pcd_date_type1_0" class="pre_date speech"<? if($data->pcd_date_size != "" and $data->pcd_date_size != "0"){ ?> style="font-size:<?=$data->pcd_date_size?>px;"<? } ?>><?=($data->pcd_date == "") ? "2020.11.11. 단하루" : $data->pcd_date?></p>
                                    <p id="pre_pcd_title_type1_0" class="pre_tit"<? if($data->pcd_title_size != "" and $data->pcd_title_size != "0"){ ?> style="font-size:<?=$data->pcd_title_size?>px;"<? } ?>><?=($data->pcd_title == "") ? "카톡친구 무료증정" : $data->pcd_title?></p>
                                </div>
                                <div class="pre_box2" id="pre_goods_list_step1">
                                    <div id="pre_div_preview_0" class="templet_img_in3" style="background-image : url('<?=($data->pcd_imgpath == "") ? $sample_img : $data->pcd_imgpath?>');">
                                        <img id="pre_img_preview_0" style="display:none;">
                                    </div>
            					    <div class="barcode_box">
            						    <div id="barcode_type1_0"></div>
                                        <img id="barcode_pre_img_type1_0" src=""></img>
            					    </div>
                                    <p id="pre_pcd_option1_type1_0" class="pre_goodsinfo"<? if($data->pcd_option1_size != "" and $data->pcd_option1_size != "0"){ ?> style="font-size:<?=$data->pcd_option1_size?>px;"<? } ?>><?=($data->pcd_option1 == "") ? "크리넥스 안심 키친타올" : $data->pcd_option1?></p>
                                    <p id="pre_pcd_option2_type1_0" class="pre_couoption"<? if($data->pcd_option2_size != "" and $data->pcd_option2_size != "0"){ ?> style="font-size:<?=$data->pcd_option2_size?>px;"<? } ?>><?=($data->pcd_option2 == "") ? "선착순 100개 한정수량" : $data->pcd_option2?></p>
                                </div><!--//pre_box2-->
                            </div><!--//pre_box_wrap-->
                        </div><!--//wl_r_preview-->
                        <div class="wl_r_preview" id="pre_coupon_type2_0" style="display:<? if($data->pcd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 미리보기?>
                            <div class="pre_box_wrap2">
                                <!--템플릿 이미지가 들어갈 공간-->
                                <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg2.jpg') no-repeat top center;background-color:#47b5e5"></div>
                                <!--//템플릿 이미지가 들어갈 공간-->
                                <div class="pre_box1">
                                    <p id="pre_pcd_date_type2_0" class="pre_date"<? if($data->pcd_date_size != "" and $data->pcd_date_size != "0"){ ?> style="font-size:<?=$data->pcd_date_size?>px;"<? } ?>><?=($data->pcd_date == "") ? "2020.11.11. 단하루" : $data->pcd_date?></p>
                                    <p id="pre_pcd_title_type2_0" class="pre_tit"<? if($data->pcd_title_size != "" and $data->pcd_title_size != "0"){ ?> style="font-size:<?=$data->pcd_title_size?>px;"<? } ?>><?=($data->pcd_title == "") ? "카톡친구 무료증정" : $data->pcd_title?></p>
                                </div>
                                <div class="pre_box2" id="pre_goods_list_step1">
                                    <p id="pre_pcd_option1_type2_0" class="pre_couoption1"<? if($data->pcd_option1_size != "" and $data->pcd_option1_size != "0"){ ?> style="font-size:<?=$data->pcd_option1_size?>px;"<? } ?>><?=($data->pcd_option1 == "") ? "3만원이상 구매시(1인1매)" : $data->pcd_option1?></p>
                                    <p id="pre_pcd_price_type2_0" class="pre_saleprice"<? if($data->pcd_price_size != "" and $data->pcd_price_size != "0"){ ?> style="font-size:<?=$data->pcd_price_size?>px;"<? } ?>><?=($data->pcd_price == "") ? "3,000원" : $data->pcd_price?></p>
                					<div class="barcode_box2">
                						<div id="barcode_type2_0"></div>
                                        <img id="barcode_pre_img_type2_0" src=""></img>
                					</div>
                					<p id="pre_pcd_option2_type2_0" class="pre_couoption2"<? if($data->pcd_option2_size != "" and $data->pcd_option2_size != "0"){ ?> style="font-size:<?=$data->pcd_option2_size?>px;"<? } ?>><?=($data->pcd_option2 == "") ? "선착순 100명 현장할인" : $data->pcd_option2?></p>
                                </div><!--//pre_box2-->
                            </div><!--//pre_box_wrap-->
                        </div><!--//wl_r_preview-->
                        <div id='pre_coupon_type3_0' class="maker_box_c" style="background:rgb(169, 212, 79);display:none;">
                        	<p class="coupon_txt1" id='pre_pcd_message_type3_0'>
                        		<?=$this->member->item('mem_username')?>
                        	</p>
                        	<p class="coupon_txt2" id='pre_pcd_title_type3_0'>
                        		할인쿠폰
                        	</p>
                        	<div class="coupon_goods_box">
                        		<div class="cp_goods_top" id='pre_pcd_date_type3_0'>
                        			 쿠폰사용기간 23.08.07(월)~08.08(화)
                        		</div>
                                <div class="cp_goods_img_box">
                                	<div id="pre_div_preview3_0" style="background : url('/dhn/images/leaflets/sample_img.jpg');">
                                		<img src="" />
                                	</div>
                                </div>
                        		<div class="cp_goods_info">
                        			<div class="cp_gi_op" id='pre_pcd_option2_type3_0'>
                        				5만원이상 구매시
                        			</div>
                        			<div class="cp_gi_na" id='pre_pcd_option1_type3_0'>
                        				신라면
                        			</div>
                        			<div class="cp_gi_op2" id='pre_pcd_stan_type3_0'>
                        				5입
                        			</div>
                        			<div class="cp_gi_dp" id='pre_pcd_dcprice_type3_0'>
                        				3,990원
                        			</div>
                        			<div class="bprice">
                        				<div class="cp_gi_fp" id='pre_pcd_price_type3_0'>
                        					 4,990원
                        				</div>
                        			</div>
                        		</div>
                        		<div class="cp_goods_date" id='pre_pcd_ucomment_type3_0'>
                        			쿠폰이 발행되었습니다!
                        		</div>
                                <div id='mother_barcode_box_type3_0' class="cp_barcode_img" style="padding-left:40px;display:none;">
                        			<div class="" style="padding:0px;overflow:auto; width:345px;">
                                        <div class="barcode_box3">
                                            <div id="barcode_type3_0"></div>
                                            <img id="barcode_pre_img_type3_0" src=""></img>
                                        </div>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    </div>
                <?
                } else {
                    $seq = 0;
                    foreach($data as $a){
                ?>
                    <div id="coupon_img_<?=$seq?>">
                        <div class="wl_r_preview" id="pre_coupon_type1_<?=$seq?>"  style="display:<? if($a->pcdd_type != "1"){ ?>none<? } ?>"><?//무료증정 쿠폰 미리보기?>
                            <div class="pre_box_wrap1 <?=(!empty($a->barcode_txt)||!empty($a->barcode_img_path))? " coupon_wrap" : ""?>">
                                <!--템플릿 이미지가 들어갈 공간-->
                                <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg<?=(!empty($a->barcode_txt)||!empty($a->barcode_img_path))? "3" : "1"?>.jpg') no-repeat top center;background-color:#47b5e5"></div>
                                <!--//템플릿 이미지가 들어갈 공간-->
                                <div class="pre_box1">
                                    <p id="pre_pcd_date_type1_<?=$seq?>" class="pre_date speech"<? if($a->pcdd_date_size != "" and $a->pcdd_date_size != "0"){ ?> style="font-size:<?=$a->pcdd_date_size?>px;"<? } ?>><?=($a->pcdd_date == "") ? "2020.11.11. 단하루" : $a->pcdd_date?></p>
                                    <p id="pre_pcd_title_type1_<?=$seq?>" class="pre_tit"<? if($a->pcdd_title_size != "" and $a->pcdd_title_size != "0"){ ?> style="font-size:<?=$a->pcdd_title_size?>px;"<? } ?>><?=($a->pcdd_title == "") ? "카톡친구 무료증정" : $a->pcdd_title?></p>
                                </div>
                                <div class="pre_box2" id="pre_goods_list_step1">
                                    <div id="pre_div_preview_<?=$seq?>" class="templet_img_in3" style="background-image : url('<?=($a->pcdd_imgpath == "") ? $sample_img : $a->pcdd_imgpath?>');">
                                        <img id="pre_img_preview_<?=$seq?>" style="display:none;">
                                    </div>
                                    <div class="barcode_box">
                                        <div id="barcode_type1_<?=$seq?>"></div>
                                        <img id="barcode_pre_img_type1_<?=$seq?>" src="<?=empty($a->barcode_img_path) ? "" : $a->barcode_img_path ?>"></img>
                                    </div>
                                    <p id="pre_pcd_option1_type1_<?=$seq?>" class="pre_goodsinfo"<? if($a->pcdd_option1_size != "" and $a->pcdd_option1_size != "0"){ ?> style="font-size:<?=$a->pcdd_option1_size?>px;"<? } ?>><?=($a->pcdd_option1 == "") ? "크리넥스 안심 키친타올" : $a->pcdd_option1?></p>
                                    <p id="pre_pcd_option2_type1_<?=$seq?>" class="pre_couoption"<? if($a->pcdd_option2_size != "" and $a->pcdd_option2_size != "0"){ ?> style="font-size:<?=$a->pcdd_option2_size?>px;"<? } ?>><?=($a->pcdd_option2 == "") ? "선착순 100개 한정수량" : $a->pcdd_option2?></p>
                                </div><!--//pre_box2-->
                            </div><!--//pre_box_wrap-->
                        </div><!--//wl_r_preview-->
                        <div class="wl_r_preview" id="pre_coupon_type2_<?=$seq?>" style="display:<? if($a->pcdd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 미리보기?>
                            <div class="pre_box_wrap2">
                                <!--템플릿 이미지가 들어갈 공간-->
                                <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg2.jpg') no-repeat top center;background-color:#47b5e5"></div>
                                <!--//템플릿 이미지가 들어갈 공간-->
                                <div class="pre_box1">
                                    <p id="pre_pcd_date_type2_<?=$seq?>" class="pre_date"<? if($a->pcdd_date_size != "" and $a->pcdd_date_size != "0"){ ?> style="font-size:<?=$a->pcdd_date_size?>px;"<? } ?>><?=($a->pcdd_date == "") ? "2020.11.11. 단하루" : $a->pcdd_date?></p>
                                    <p id="pre_pcd_title_type2_<?=$seq?>" class="pre_tit"<? if($a->pcdd_title_size != "" and $a->pcdd_title_size != "0"){ ?> style="font-size:<?=$a->pcdd_title_size?>px;"<? } ?>><?=($a->pcdd_title == "") ? "카톡친구 무료증정" : $a->pcdd_title?></p>
                                </div>
                                <div class="pre_box2" id="pre_goods_list_step1">
                                    <p id="pre_pcd_option1_type2_<?=$seq?>" class="pre_couoption1"<? if($a->pcdd_option1_size != "" and $a->pcdd_option1_size != "0"){ ?> style="font-size:<?=$a->pcdd_option1_size?>px;"<? } ?>><?=($a->pcdd_option1 == "") ? "3만원이상 구매시(1인1매)" : $a->pcdd_option1?></p>
                                    <p id="pre_pcd_price_type2_<?=$seq?>" class="pre_saleprice"<? if($a->pcdd_price_size != "" and $a->pcdd_price_size != "0"){ ?> style="font-size:<?=$a->pcdd_price_size?>px;"<? } ?>><?=($a->pcdd_price == "") ? "3,000원" : $a->pcdd_price?></p>
                                    <div class="barcode_box2">
                                        <div id="barcode_type2_<?=$seq?>"></div>
                                        <img id="barcode_pre_img_type2_<?=$seq?>" src="<?=empty($a->barcode_img_path) ? "" : $a->barcode_img_path ?>"></img>
                                    </div>
                                    <p id="pre_pcd_option2_type2_<?=$seq?>" class="pre_couoption2<?=$a->barcode_txt_length_flag != 0 ? " exist_barcode" : "" ?>"<? if($a->pcdd_option2_size != "" and $a->pcdd_option2_size != "0"){ ?> style="font-size:<?=$a->pcdd_option2_size?>px;"<? } ?>><?=($a->pcdd_option2 == "") ? "선착순 100명 현장할인" : $a->pcdd_option2?></p>
                                </div><!--//pre_box2-->
                            </div><!--//pre_box_wrap-->
                        </div><!--//wl_r_preview-->
                        <div class="maker_box_c" id="pre_coupon_type3_<?=$seq?>" style="background:<?=$a->pcdd_type == '3' ? $a->pcdd_backcolor : 'rgb(169, 212, 79)'?>;display:<? if($a->pcdd_type != "3"){ ?>none<? } ?>">
                        	<p class="coupon_txt1" id='pre_pcd_message_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_message_size . 'px' : '20px'?>">
                                <?=$a->pcdd_type == '3' ? $a->pcdd_message : $this->member->item('mem_username')?>
                        	</p>
                        	<p class="coupon_txt2" id='pre_pcd_title_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_title_size . 'px' : '70px'?>">
                                <?=$a->pcdd_type == '3' ? $a->pcdd_title : '할인쿠폰'?>
                        	</p>
                        	<div class="coupon_goods_box">
                        		<div class="cp_goods_top" id='pre_pcd_date_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_date_size . 'px' : '18px'?>">
                        			 <?=$a->pcdd_type == '3' ? $a->pcdd_date : '쿠폰사용기간 23.08.07(월)~08.08(화)'?>
                        		</div>
                                <div class="cp_goods_img_box">
                                	<div id="pre_div_preview3_<?=$seq?>" style="background : url('<?=$a->pcdd_type == '3' ? $a->pcdd_imgpath : '/dhn/images/leaflets/sample_img.jpg'?>');">
                                		<img src="" />
                                	</div>
                                </div>
                        		<div class="cp_goods_info">
                        			<div class="cp_gi_op" id='pre_pcd_option2_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_option2_size . 'px' : '20px'?>">
                        				<?=$a->pcdd_type == '3' ? $a->pcdd_option2 : '5만원이상 구매시'?>
                        			</div>
                        			<div class="cp_gi_na" id='pre_pcd_option1_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_option1_size . 'px' : '40px'?>">
                        				<?=$a->pcdd_type == '3' ? $a->pcdd_option1 : '신라면'?>
                        			</div>
                        			<div class="cp_gi_op2" id='pre_pcd_stan_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_stan_size . 'px' : '20px'?>">
                        				<?=$a->pcdd_type == '3' ? $a->pcdd_stan : '5입'?>
                        			</div>
                        			<div class="cp_gi_dp" id='pre_pcd_dcprice_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_dcprice_size . 'px' : '40px'?>">
                        				<?=$a->pcdd_type == '3' ? $a->pcdd_dcprice : '3,990원'?>
                        			</div>
                        			<div class="bprice">
                        				<div class="cp_gi_fp" id='pre_pcd_price_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_price_size . 'px' : '25px'?>">
                        					 <?=$a->pcdd_type == '3' ? $a->pcdd_price : '4,990원'?>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="cp_goods_date" id='pre_pcd_ucomment_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_ucomment_size . 'px' : '20px'?>">
                        			<?=$a->pcdd_type == '3' ? $a->pcdd_ucomment : '쿠폰이 발행되었습니다!'?>
                        		</div>
                                <div id='mother_barcode_box_type3_<?=$seq?>' class="cp_barcode_img" style="padding-left:40px;display:<?=$a->barcode_txt_length_flag != '0' ? '' : 'none'?>;">
                        			<div class="" style="padding:0px;overflow:auto; width:345px;">
                                        <div class="barcode_box3">
                                            <div id="barcode_type3_<?=$seq?>"></div>
                                            <img id="barcode_pre_img_type3_<?=$seq?>" src="<?=$a->pcdd_type == '3' ? (empty($a->barcode_img_path) ? "" : $a->barcode_img_path) : ''?>"></img>
                                        </div>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    </div>
                <?
                        $seq++;
                    }
                }
                ?>
                </div>
            </div>
        </div><!--//wl_rbox-->
        <div class="write_leaflets_btn">
          <button type="button" class="pop_btn_save" onClick="saved('');"><span class="material-icons">save</span> 저장하기</button>
          <button type="button" class="pop_btn_send" onClick="saved('lms');"><span class="material-icons">forward_to_inbox</span> 문자발송</button>
          <button type="button" class="pop_btn_send" onClick="saved('talk_adv');"><span class="material-icons">chat_bubble_outline</span> 알림톡발송</button>
          <button type="button" class="pop_btn_cancel" onclick="location.href='/spop/coupon'"><span class="material-icons">highlight_off</span> 취소하기</button>
        </div>
    </div><!--//write_leaflets-->
</div><!--//wrap_leaflets-->
<!-- 이미지 선택 Modal -->
<div id="dh_myModal2" class="dh_modal">
    <div class="modal-content2_1">
        <span id="dh_close2" class="dh_close">&times;</span>
        <div class="img_choice">
            <label for="img_file" class="img_mypick">내사진</label>
            <input type="file" title="이미지 파일" id="img_file" onChange="imgChange(this);" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
            <button onclick="showLibrary('img');" class="img_library" style="margin-left:10px;cursor:pointer;">이미지선택</button>
            <ul>
                <li>1. 내사진 :  <span>내 PC에 저장된 이미지</span>를 등록합니다.</li>
                <li>2. 이미지선택 : <span>지니 라이브러리에 있는 이미지</span>를 선택합니다.</li>
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
<!-- 스샷 -->
<script src="/assets/js/FileSaver.min.js"></script>
<script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
<script src="/assets/js/canvas-toBlob.js"></script>
<script src="/js/jquery-barcode.js"></script>
<script src="/js/jquery-barcode.min.js"></script>

<script>

    $(document).on("click", function(e){
        if (e.target.tagName == "INPUT" && e.target.type == "text"){
            var split_id = e.target.id.split("_");
            var target;
            if (/barcode/g.test(e.target.id)){
                target = $("#"+e.target.id).closest("div").parent().closest("div").parent().attr("id");
            }else{
                target = $("#"+e.target.id).closest("div").parent().attr("id");
            }

            var index = $('.coupon').index(document.getElementById(target));
            $('.smart_coupon_preview').animate( { scrollTop : document.querySelector('#coupon_img_' + split_id[3]).offsetTop }, 500 );
        }
    }).on('click', '.color_chip', function(){
        var seq = $(this).parent().data('seq');
        $('#pre_coupon_type3_' + seq).attr('style', $(this).attr('style'));
    });

    $(document).ready(function(){
        if ("<?=count($data)?>" > 0){
            <?
            $seq = 0;
            foreach($data as $a){
            ?>
                $("#barcode_<?=$a->pcdd_type == '1' ? "type1" : ($a->pcdd_type == '2' ? "type2" : 'type3') ?>_<?=$seq?>").barcode(String($("#barcode_txt_<?=$a->pcdd_type == '1' ? "type1" : ($a->pcdd_type == '2' ? "type2" : 'type3') ?>_<?=$seq?>").val()), "ean<?=$a->barcode_txt_length_flag?>",{barWidth:2, barHeight:60, fontSize:20});
            <?
                $seq++;
            }
            ?>
        }
    });

    function addCoupon(){
        var type = ""
        var seq = $("#coupon_seq").val();
        $("#coupon_seq").val(Number($("#coupon_seq").val()) + 1);
        var html = "";
        html = html + '<div id="coupon_' + seq + '" class="coupon">';
        html = html + '    <input type="hidden" id="pcd_type_' + seq + '" value="1">';
        html = html + '    <div class="tit_box">';
        html = html + '        <ul>';
        html = html + '            <li id="li_type_' + seq + '_1" class="tm_on" onclick="coupon_type(this, 1, ' + seq + ');"><a><span>TYPE 1.</span> 무료증정 쿠폰</a></li>';
        html = html + '            <li id="li_type_' + seq + '_2" class="" onclick="coupon_type(this, 2, ' + seq + ');"><a><span>TYPE 2.</span> 가격할인 쿠폰</a></li>';
        html = html + '            <li id="li_type_' + seq + '_3" class="" onclick="coupon_type(this, 3, ' + seq + ');"><a><span>TYPE 3.</span> 타입3</a></li>';
        html = html + '        </ul>';
        html = html + '    </div>';
        html = html + '    <div class="wl_main_goods" id="coupon_type1_' + seq + '">';
        html = html + '        <dl class="dl_step1">';
        html = html + '            <dt>';
        html = html + '                <input type="hidden" id="pcd_imgpath_' + seq + '" value="" style="width:600px;">';
        html = html + '                <div class="templet_img_in" onclick="showImg(\'\', ' + seq + ')">';
        html = html + '                    <div id="div_preview">';
        html = html + '                        <img id="img_preview_' + seq + '" src="">';
        html = html + '                    </div>';
        html = html + '                </div>';
        html = html + '            </dt>';
        html = html + '            <dd>';
        html = html + '                <ul>';
        html = html + '                   <li>';
        html = html + '                       <span class="tit">행사기간</span>';
        html = html + '                       <input type="text" id="pcd_date_type1_' + seq + '" value="" onkeyup="chgData(\'pcd_date_type1_' + seq + '\')" placeholder="2020.11.11. 단하루">';
        html = html + '                       <div class="counter">';
        html = html + '                           <span class="text">글자크기</span>';
        html = html + '                           <span class="counter_minus button" onClick="size_change(\'pcd_date_type1_' + seq + '\', \'minus\');">-</span>';
        html = html + '                           <span class="counter_plus button" onClick="size_change(\'pcd_date_type1_' + seq + '\', \'plus\');">+</span>';
        html = html + '                       </div>';
        html = html + '                   </li>';
        html = html + '                   <li>';
        html = html + '                       <span class="tit">쿠폰제목</span>';
        html = html + '                       <input type="text" id="pcd_title_type1_' + seq + '" value="" onkeyup="chgData(\'pcd_title_type1_' + seq + '\')" placeholder="카톡친구 무료증정">';
        html = html + '                       <div class="counter">';
        html = html + '                           <span class="text">글자크기</span>';
        html = html + '                           <span class="counter_minus button" onClick="size_change(\'pcd_title_type1_' + seq + '\', \'minus\');">-</span>';
        html = html + '                           <span class="counter_plus button" onClick="size_change(\'pcd_title_type1_' + seq + '\', \'plus\');">+</span>';
        html = html + '                       </div>';
        html = html + '                   </li>';
        html = html + '                   <li>';
        html = html + '                       <span class="tit">상품정보</span>';
        html = html + '                       <input type="text" id="pcd_option1_type1_' + seq + '" value="" onkeyup="chgData(\'pcd_option1_type1_' + seq + '\')" placeholder="크리넥스 안심 키친타올">';
        html = html + '                       <div class="counter">';
        html = html + '                           <span class="text">글자크기</span>';
        html = html + '                           <span class="counter_minus button" onClick="size_change(\'pcd_option1_type1_' + seq + '\', \'minus\');">-</span>';
        html = html + '                           <span class="counter_plus button" onClick="size_change(\'pcd_option1_type1_' + seq + '\', \'plus\');">+</span>';
        html = html + '                       </div>';
        html = html + '                   </li>';
        html = html + '                   <li>';
        html = html + '                       <span class="tit">쿠폰옵션</span>';
        html = html + '                       <input type="text" id="pcd_option2_type1_' + seq + '" value="" onkeyup="chgData(\'pcd_option2_type1_' + seq + '\')" placeholder="선착순 100개 한정수량">';
        html = html + '                       <div class="counter">';
        html = html + '                           <span class="text">글자크기</span>';
        html = html + '                           <span class="counter_minus button" onClick="size_change(\'pcd_option2_type1_' + seq + '\', \'minus\');">-</span>';
        html = html + '                           <span class="counter_plus button" onClick="size_change(\'pcd_option2_type1_' + seq + '\', \'plus\');">+</span>';
        html = html + '                       </div>';
        html = html + '                   </li>';
        html = html + '                   <li>';
        html = html + '                       <span class="tit">바코드</span>';
        html = html + '                       <div class="barcode">';
        html = html + '                           <input type="hidden" id="apply_barcode_type1_' + seq + '" value="0">';
        html = html + '                           <select id="select_barcode_length_type1_' + seq + '" onchange="changeBarcodeSelect(this, \'type1\', ' + seq + ')">';
        html = html + '                               <option value="13" selected="">13자리</option>';
        html = html + '                               <option value="8">8자리</option>';
        html = html + '                           </select>';
        html = html + '                           <input type="text" id="barcode_txt_type1_' + seq + '" value="" onkeyup="" maxlength=13 style="width:120px !important;">';
        html = html + '                           <button type="button" class="btn_bar_ok" onclick="apply_barcode(\'type1\', ' + seq + ')"> 적용</button>';
        html = html + '                           <label for="barcode_img_type1_' + seq + '" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>';
        html = html + '                           <input type="file" title="바코드이미지 파일" id="barcode_img_type1_' + seq + '" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">';
        html = html + '                           <button type="button" class="btn_bar_no" onclick="delete_barcode(\'type1\', ' + seq + ')"> 바코드 삭제하기</button>';
        html = html + '                       </div>';
        html = html + '                   </li>';
        html = html + '                </ul>';
        html = html + '            </dd>';
        html = html + '        </dl>';
        html = html + '        <button type="button" class="smart_coupon_del" onclick="delete_type(' + seq + ');"><span class="material-icons">clear</span></button>';
        html = html + '    </div>';
        html = html + '    <div class="wl_main_goods" id="coupon_type2_' + seq + '" style="display:none;">';
        html = html + '        <ul>';
        html = html + '           <li>';
        html = html + '               <span class="tit">행사기간</span>';
        html = html + '               <input type="text" id="pcd_date_type2_' + seq + '" value="" onkeyup="chgData(\'pcd_date_type2_' + seq + '\')" placeholder="2020.11.11. 단하루">';
        html = html + '               <div class="counter">';
        html = html + '                   <span class="text">글자크기</span>';
        html = html + '                   <span class="counter_minus button" onClick="size_change(\'pcd_date_type2_' + seq + '\', \'minus\');">-</span>';
        html = html + '                   <span class="counter_plus button" onClick="size_change(\'pcd_date_type2_' + seq + '\', \'plus\');">+</span>';
        html = html + '               </div>';
        html = html + '           </li>';
        html = html + '           <li>';
        html = html + '               <span class="tit">쿠폰제목</span>';
        html = html + '               <input type="text" id="pcd_title_type2_' + seq + '" value="" onkeyup="chgData(\'pcd_title_type2_' + seq + '\')" placeholder="카톡친구 무료증정">';
        html = html + '               <div class="counter">';
        html = html + '                   <span class="text">글자크기</span>';
        html = html + '                   <span class="counter_minus button" onClick="size_change(\'pcd_title_type2_' + seq + '\', \'minus\');">-</span>';
        html = html + '                   <span class="counter_plus button" onClick="size_change(\'pcd_title_type2_' + seq + '\', \'plus\');">+</span>';
        html = html + '               </div>';
        html = html + '           </li>';
        html = html + '           <li>';
        html = html + '               <span class="tit">쿠폰옵션1</span>';
        html = html + '               <input type="text" id="pcd_option1_type2_' + seq + '" value="" onkeyup="chgData(\'pcd_option1_type2_' + seq + '\')" placeholder="3만원이상 구매시(1인1매)">';
        html = html + '               <div class="counter">';
        html = html + '                   <span class="text">글자크기</span>';
        html = html + '                   <span class="counter_minus button" onClick="size_change(\'pcd_option1_type2_' + seq + '\', \'minus\');">-</span>';
        html = html + '                   <span class="counter_plus button" onClick="size_change(\'pcd_option1_type2_' + seq + '\', \'plus\');">+</span>';
        html = html + '               </div>';
        html = html + '           </li>';
        html = html + '           <li>';
        html = html + '               <span class="tit">할인금액</span>';
        html = html + '               <input type="text" id="pcd_price_type2_' + seq + '" value="" onkeyup="chgData(\'pcd_price_type2_' + seq + '\')" placeholder="3,000원">';
        html = html + '               <div class="counter">';
        html = html + '                   <span class="text">글자크기</span>';
        html = html + '                   <span class="counter_minus button" onClick="size_change(\'pcd_price_type2_' + seq + '\', \'minus\');">-</span>';
        html = html + '                   <span class="counter_plus button" onClick="size_change(\'pcd_price_type2_' + seq + '\', \'plus\');">+</span>';
        html = html + '               </div>';
        html = html + '           </li>';
        html = html + '           <li>';
        html = html + '               <span class="tit">쿠폰옵션2</span>';
        html = html + '               <input type="text" id="pcd_option2_type2_' + seq + '" value="" onkeyup="chgData(\'pcd_option2_type2_' + seq + '\')" placeholder="선착순 100명 현장할인">';
        html = html + '               <div class="counter">';
        html = html + '                   <span class="text">글자크기</span>';
        html = html + '                   <span class="counter_minus button" onClick="size_change(\'pcd_option2_type2_' + seq + '\', \'minus\');">-</span>';
        html = html + '                   <span class="counter_plus button" onClick="size_change(\'pcd_option2_type2_' + seq + '\', \'plus\');">+</span>';
        html = html + '               </div>';
        html = html + '           </li>';
        html = html + '           <li>';
        html = html + '               <span class="tit">바코드</span>';
        html = html + '               <div class="barcode">';
        html = html + '                   <input type="hidden" id="apply_barcode_type2_' + seq + '" value="0">';
        html = html + '                   <select id="select_barcode_length_type2_' + seq + '" onchange="changeBarcodeSelect(this, \'type2\', ' + seq + ')">';
        html = html + '                       <option value="13" selected="">13자리</option>';
        html = html + '                       <option value="8">8자리</option>';
        html = html + '                   </select>';
        html = html + '                   <input type="text" id="barcode_txt_type2_' + seq + '" value="" onkeyup="" maxlength=13 style="width:120px !important;">';
        html = html + '                   <button type="button" class="btn_bar_ok" onclick="apply_barcode(\'type2\', ' + seq + ')"> 적용</button>';
        html = html + '                   <label for="barcode_img_type2_' + seq + '" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>';
        html = html + '                   <input type="file" title="바코드이미지 파일" id="barcode_img_type2_' + seq + '" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">';
        html = html + '                   <button type="button" class="btn_bar_no" onclick="delete_barcode(\'type2\', ' + seq + ')"> 바코드 삭제하기</button>';
        html = html + '               </div>';
        html = html + '           </li>';
        html = html + '        </ul>';
        html = html + '        <button type="button" class="smart_coupon_del" onclick="delete_type(' + seq + ')"><span class="material-icons">clear</span></button>';
        html = html + '    </div>';
        html = html + '    <div class="wl_main_goods" id="coupon_type3_' + seq + '" style="display:none;">';
        html = html + '        <dl class="dl_step1">';
        html = html + '            <dt>';
        html = html + '                <input type="hidden" id="pcd_imgpath3_' + seq + '" value="" style="width:600px;">';
        html = html + '                <div class="templet_img_in" onclick="showImg(3, ' + seq + ')">';
        html = html + '                    <div id="div_preview">';
        html = html + '                        <img id="img_preview3_' + seq + '" src="">';
        html = html + '                    </div>';
        html = html + '                </div>';
        html = html + '            </dt>';
        html = html + '            <dd>';
        html = html + '                <ul>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">메시지</span>';
        html = html + '                    <input id="pcd_message_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_message_type3_' + seq + '\')" placeholder="<?=$this->member->item('mem_username')?>">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_message_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_message_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">쿠폰제목</span>';
        html = html + '                    <input id="pcd_title_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_title_type3_' + seq + '\')" placeholder="할인쿠폰">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_title_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_title_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">쿠폰사용기간</span>';
        html = html + '                    <input id="pcd_date_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_date_type3_' + seq + '\')" placeholder="쿠폰사용기간 23.08.07(월)~08.08(화)">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_date_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_date_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">상품정보</span>';
        html = html + '                    <input id="pcd_option1_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_option1_type3_' + seq + '\')" placeholder="신라면">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_option1_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_option1_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">쿠폰옵션</span>';
        html = html + '                    <input id="pcd_option2_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_option2_type3_' + seq + '\')" placeholder="5만원이상 구매시">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_option2_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_option2_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">규격</span>';
        html = html + '                    <input id="pcd_stan_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_stan_type3_' + seq + '\')" placeholder="5입">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_stan_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_stan_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">정상가</span>';
        html = html + '                    <input id="pcd_price_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_price_type3_' + seq + '\')" placeholder="4,990원">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_price_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_price_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">판매가</span>';
        html = html + '                    <input id="pcd_dcprice_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_dcprice_type3_' + seq + '\')" placeholder="3,990원">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_dcprice_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_dcprice_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">하단정보</span>';
        html = html + '                    <input id="pcd_ucomment_type3_' + seq + '" type="text" onkeyup="chgData(\'pcd_ucomment_type3_' + seq + '\')" placeholder="쿠폰이 발행되었습니다!">';
        html = html + '                    <div class="counter">';
        html = html + '                        <span class="text">글자크기</span>';
        html = html + '                        <span class="counter_minus button" onClick="size_change(\'pcd_ucomment_type3_' + seq + '\', \'minus\');">-</span>';
        html = html + '                        <span class="counter_plus button" onClick="size_change(\'pcd_ucomment_type3_' + seq + '\', \'plus\');">+</span>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">배경색</span>';
        html = html + '                    <div class="counter_color" data-seq="' + seq + '">';
        html = html + '                        <div class="color_chip color01" style="background-color:#d8282f"></div>';
        html = html + '                        <div class="color_chip color02" style="background-color:#9d1000"></div>';
        html = html + '                        <div class="color_chip color03" style="background-color:#ae0009"></div>';
        html = html + '                        <div class="color_chip color04" style="background-color:#f63756"></div>';
        html = html + '                        <div class="color_chip color05" style="background-color:#ff7800"></div>';
        html = html + '                        <div class="color_chip color06" style="background-color:#ff1b41"></div>';
        html = html + '                        <div class="color_chip color07" style="background-color:#f85333"></div>';
        html = html + '                        <div class="color_chip color08" style="background-color:#ff9400"></div>';
        html = html + '                        <div class="color_chip color10" style="background-color:#e9ab00"></div>';
        html = html + '                        <div class="color_chip color11" style="background-color:#ffa400"></div>';
        html = html + '                        <div class="color_chip color13" style="background-color:#d08e01"></div>';
        html = html + '                        <div class="color_chip color14" style="background-color:#a39b87"></div>';
        html = html + '                        <div class="color_chip color15" style="background-color:#71675d"></div>';
        html = html + '                        <div class="color_chip color17" style="background-color:#59301a"></div>';
        html = html + '                        <div class="color_chip color18" style="background-color:#299e00"></div>';
        html = html + '                        <div class="color_chip color19" style="background-color:#008b6e"></div>';
        html = html + '                        <div class="color_chip color20" style="background-color:#005e3c"></div>';
        html = html + '                        <div class="color_chip color21" style="background-color:#a9d44f"></div>';
        html = html + '                        <div class="color_chip color23" style="background-color:#409834"></div>';
        html = html + '                        <div class="color_chip color24" style="background-color:#87a395"></div>';
        html = html + '                        <div class="color_chip color25" style="background-color:#8794a3"></div>';
        html = html + '                        <div class="color_chip color26" style="background-color:#00acaa"></div>';
        html = html + '                        <div class="color_chip color27" style="background-color:#0050c7"></div>';
        html = html + '                        <div class="color_chip color28" style="background-color:#00aacf"></div>';
        html = html + '                        <div class="color_chip color29" style="background-color:#01508d"></div>';
        html = html + '                        <div class="color_chip color30" style="background-color:#0138ed"></div>';
        html = html + '                        <div class="color_chip color31" style="background-color:#132062"></div>';
        html = html + '                        <div class="color_chip color32" style="background-color:#c138c1"></div>';
        html = html + '                        <div class="color_chip color33" style="background-color:#9032d2"></div>';
        html = html + '                        <div class="color_chip color34" style="background-color:#410098"></div>';
        html = html + '                        <div class="color_chip color39" style="background-color:#6299fe"></div>';
        html = html + '                        <div class="color_chip color37" style="background-color:#363636"></div>';
        html = html + '                        <div class="color_chip color38" style="background-color:#000000"></div>';
        html = html + '                        <div class="color_chip color40" style="background:linear-gradient( to top, #ffa400, #f63756 );"></div>';
        html = html + '                        <div class="color_chip color41" style="background:linear-gradient( to top, #01508d, #00acaa );"></div>';
        html = html + '                        <div class="color_chip color42" style="background:linear-gradient( to top, #00aacf, #c138c1 );"></div>';
        html = html + '                        <div class="color_chip color43" style="background:linear-gradient( to top, #3B2667, #BC78EC );"></div>';
        html = html + '                        <div class="color_chip color44" style="background:linear-gradient( to top, #360940, #F05F57 );"></div>';
        html = html + '                        <div class="color_chip color45" style="background:linear-gradient( to top, #623AA2, #F97794 );"></div>';
        html = html + '                        <div class="color_chip color46" style="background:linear-gradient( to top, #ffa400, #FD6E6A );"></div>';
        html = html + '                        <div class="color_chip color47" style="background:linear-gradient( to top, #513162, #c138c1 );"></div>';
        html = html + '                        <div class="color_chip color48" style="background:linear-gradient( to top, #BB4E75, #FF9D6C );"></div>';
        html = html + '                        <div class="color_chip color49" style="background:linear-gradient( to top, #123597, #00aacf );"></div>';
        html = html + '                        <div class="color_chip color50" style="background:linear-gradient( to top, #ffa400, #c138c1 );"></div>';
        html = html + '                        <div class="color_chip color51" style="background-color:#F97794"></div>';
        html = html + '                        <div class="color_chip color52" style="background-color:#FD6E6A "></div>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                    <li>';
        html = html + '                    <span class="tit">바코드</span>';
        html = html + '                    <div class="barcode">';
        html = html + '                        <input type="hidden">';
        html = html + '                        <select id="select_barcode_length_type3_' + seq + '" onchange="changeBarcodeSelect(this, \'type3\', ' + seq + ')">';
        html = html + '                            <option value="13" selected="">13자리</option>';
        html = html + '                            <option value="8">8자리</option>';
        html = html + '                        </select>';
        html = html + '                        <input type="text" id="barcode_txt_type3_' + seq + '" maxlength=13 style="width:120px !important;">';
        html = html + '                        <button type="button" class="btn_bar_ok" onclick="apply_barcode(\'type3\', ' + seq + ')"> 적용</button>';
        html = html + '                        <label for="barcode_img_type3_' + seq + '" style="cursor:pointer;"><span class="btn_barcode">이미지 직접입력</span></label>';
        html = html + '                        <input type="file" title="바코드이미지 파일" id="barcode_img_type3_' + seq + '" class="barcode_img" accept=".jpg, .jepg, .png, .gif" style="display:none;">';
        html = html + '                        <button type="button" class="btn_bar_no" onclick="delete_barcode(\'type3\', ' + seq + ')"> 바코드 삭제하기</button>';
        html = html + '                    </div>';
        html = html + '                    </li>';
        html = html + '                </ul>';
        html = html + '            </dd>';
        html = html + '        </dl>';
        html = html + '        <button type="button" class="smart_coupon_del" onclick="delete_type(' + seq + ')"><span class="material-icons">clear</span></button>';
        html = html + '    </div>';
        html = html + '</div>';
        $("#coupon_append_div").append(html);

        html = "";
        html = html + '<div id="coupon_img_' + seq + '">';
        html = html + '    <div class="wl_r_preview" id="pre_coupon_type1_' + seq + '">';
        html = html + '        <div class="pre_box_wrap1">';
        html = html + '            <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url(\'/images/smart_cou_bg1.jpg\') no-repeat top center;background-color:#47b5e5"></div>';
        html = html + '            <div class="pre_box1">';
        html = html + '                <p id="pre_pcd_date_type1_' + seq + '" class="pre_date speech">2020.11.11. 단하루</p>';
        html = html + '                <p id="pre_pcd_title_type1_' + seq + '" class="pre_tit">카톡친구 무료증정</p>';
        html = html + '            </div>';
        html = html + '            <div class="pre_box2" id="pre_goods_list_step1">';
        html = html + '                <div id="pre_div_preview_' + seq + '" class="templet_img_in3" style="background-image : url(<?=$sample_img?>);">';
        html = html + '                    <img id="pre_img_preview_' + seq + '" style="display:none;">';
        html = html + '                </div>';
        html = html + '                <div class="barcode_box">';
        html = html + '                    <div id="barcode_type1_' + seq + '"></div>';
        html = html + '                    <img id="barcode_pre_img_type1_' + seq + '" src=""></img>';
        html = html + '                </div>';
        html = html + '                <p id="pre_pcd_option1_type1_' + seq + '" class="pre_goodsinfo">크리넥스 안심 키친타올</p>';
        html = html + '                <p id="pre_pcd_option2_type1_' + seq + '" class="pre_couoption">선착순 100개 한정수량</p>';
        html = html + '            </div>';
        html = html + '        </div>';
        html = html + '    </div>';
        html = html + '    <div class="wl_r_preview" id="pre_coupon_type2_' + seq + '" style="display:none;">';
        html = html + '        <div class="pre_box_wrap2">';
        html = html + '            <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url(\'/images/smart_cou_bg2.jpg\') no-repeat top center;background-color:#47b5e5"></div>';
        html = html + '            <div class="pre_box1">';
        html = html + '                <p id="pre_pcd_date_type2_' + seq + '" class="pre_date">2020.11.11. 단하루</p>';
        html = html + '                <p id="pre_pcd_title_type2_' + seq + '" class="pre_tit">카톡친구 무료증정</p>';
        html = html + '            </div>';
        html = html + '            <div class="pre_box2" id="pre_goods_list_step1">';
        html = html + '                <p id="pre_pcd_option1_type2_' + seq + '" class="pre_couoption1">3만원이상 구매시(1인1매)</p>';
        html = html + '                <p id="pre_pcd_price_type2_' + seq + '" class="pre_saleprice">3,000원</p>';
        html = html + '                <div class="barcode_box2">';
        html = html + '                    <div id="barcode_type2_' + seq + '"></div>';
        html = html + '                    <img id="barcode_pre_img_type2_' + seq + '" src=""></img>';
        html = html + '                </div>';
        html = html + '                <p id="pre_pcd_option2_type2_' + seq + '" class="pre_couoption2">선착순 100명 현장할인</p>';
        html = html + '            </div>';
        html = html + '        </div>';
        html = html + '    </div>';
        html = html + '    <div id="pre_coupon_type3_' + seq + '" class="maker_box_c" style="background:rgb(169, 212, 79);display:none;">';
        html = html + '        <p class="coupon_txt1" id="pre_pcd_message_type3_' + seq + '">';
        html = html + '            <?=$this->member->item('mem_username')?>';
        html = html + '        </p>';
        html = html + '        <p class="coupon_txt2" id="pre_pcd_title_type3_' + seq + '">';
        html = html + '            할인쿠폰';
        html = html + '        </p>';
        html = html + '        <div class="coupon_goods_box">';
        html = html + '            <div class="cp_goods_top" id="pre_pcd_date_type3_' + seq + '">';
        html = html + '                 쿠폰사용기간 23.08.07(월)~08.08(화)';
        html = html + '            </div>';
        html = html + '            <div class="cp_goods_img_box">';
        html = html + '                <div id="pre_div_preview3_' + seq + '" style="background : url(/dhn/images/leaflets/sample_img.jpg);">';
        html = html + '                   <img src=""/>';
        html = html + '                </div>';
        html = html + '            </div>';
        html = html + '            <div class="cp_goods_info">';
        html = html + '                <div class="cp_gi_op" id="pre_pcd_option2_type3_' + seq + '">';
        html = html + '                    5만원이상 구매시';
        html = html + '                </div>';
        html = html + '                <div class="cp_gi_na" id="pre_pcd_option1_type3_' + seq + '">';
        html = html + '                    신라면';
        html = html + '                </div>';
        html = html + '                <div class="cp_gi_op2" id="pre_pcd_stan_type3_' + seq + '">';
        html = html + '                    5입';
        html = html + '                </div>';
        html = html + '                <div class="cp_gi_dp" id="pre_pcd_dcprice_type3_' + seq + '">';
        html = html + '                    2,980원';
        html = html + '                </div>';
        html = html + '                <div class="bprice">';
        html = html + '                    <div class="cp_gi_fp" id="pre_pcd_price_type3_' + seq + '">';
        html = html + '                         39,900원';
        html = html + '                    </div>';
        html = html + '                </div>';
        html = html + '            </div>';
        html = html + '            <div class="cp_goods_date" id="pre_pcd_ucomment_type3_' + seq + '">';
        html = html + '                쿠폰이 발행되었습니다!';
        html = html + '            </div>';
        html = html + '            <div id="mother_barcode_box_type3_' + seq + '" class="cp_barcode_img" style="padding-left:40px;display:none;">';
        html = html + '                <div class="" style="padding:0px;overflow:auto; width:345px;">';
        html = html + '                    <div class="barcode_box3">';
        html = html + '                        <div id="barcode_type3_' + seq + '"></div>';
        html = html + '                        <img id="barcode_pre_img_type3_' + seq + '" src=""></img>';
        html = html + '                    </div>';
        html = html + '                </div>';
        html = html + '            </div>';
        html = html + '        </div>';
        html = html + '    </div>';
        html = html + '</div>';
        $("#coupon_img_append_div").append(html);

    }

    function delete_type(seq){
        if ($("#coupon_seq").val() == "1"){
            alert("삭제 불가능합니다.");
            return;
        }
        $("#coupon_" + seq).remove();
        $("#coupon_img_" + seq).remove();
    }

    function apply_barcode(coupon_type, seq){
        $("#barcode_pre_img_" + coupon_type + "_" + seq).attr('src', "");
        $("#barcode_" + coupon_type + "_" + seq).barcode(String($("#barcode_txt_" + coupon_type + "_" + seq).val()), "ean" + ($("#select_barcode_length_" + coupon_type + "_" + seq).val() == "13" ? "13" : "8"),{barWidth:2, barHeight:60, fontSize:20});
        $("#apply_barcode_" + coupon_type + "_" + seq).val(1);
        if (coupon_type == "type1"){
            $("#pre_coupon_"+coupon_type+"_" + seq).find(".wl_r_preview_bg").css("background", "url('/images/smart_cou_bg3.jpg') no-repeat top center");
            $("#pre_coupon_"+coupon_type+"_" + seq).find(".pre_box_wrap1").addClass("coupon_wrap");
        } else if (coupon_type == "type2") {
            $("#pre_pcd_option2_type2_" + seq).addClass("exist_barcode");
        } else if (coupon_type == "type3"){
            $('#mother_barcode_box_type3_' + seq).css('display', '');
        }
    }

    function delete_barcode(coupon_type, seq){
        $("#barcode_txt_" + coupon_type + "_" + seq).val("");
        $("#barcode_" + coupon_type + "_" + seq).html("");
        $("#barcode_pre_img_" + coupon_type + "_" + seq).attr('src', "");
        $("#apply_barcode_" + coupon_type + "_" + seq).val(0);
        if (coupon_type == "type1"){
            $("#pre_coupon_"+coupon_type+"_" + seq).find(".wl_r_preview_bg").css("background", "url('/images/smart_cou_bg1.jpg') no-repeat top center");
            $("#pre_coupon_"+coupon_type+"_" + seq).find(".pre_box_wrap1").removeClass("coupon_wrap");
        } else if (coupon_type == "type2") {
            $("#pre_pcd_option2_type2_" + seq).removeClass("exist_barcode");
        } else if (coupon_type == "type3"){
            $('#mother_barcode_box_type3_' + seq).css('display', 'none');
        }
    }

    $(document).on("change", ".barcode_img", function(){
        var id_array = $(this).attr("id").split("_");
        if(this.value.length > 0) {
            $("#barcode_" + id_array[2] + "_" + id_array[3]).html("");
            $("#barcode_pre_img_" + id_array[2] + "_" + id_array[3]).attr('src', "");
            if ($("#pcd_type_" + id_array[3]).val() == "2"){
                $("#pre_pcd_option2_type2_" + id_array[3]).addClass("exist_barcode");
            }
            readURL(this, id_array[2], id_array[3]);
        }
    });

    function readURL(input, coupon_type, seq) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#barcode_pre_img_" + coupon_type + "_" + seq).attr('src', e.target.result);
                $("#apply_barcode_" + coupon_type + "_" + seq).val(1);
                if (coupon_type == 'type3'){
                    $('#mother_barcode_box_type3_' + seq).css('display', '');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
	}

    function changeBarcodeSelect(t, coupon_type, seq){
        if ($("#select_barcode_length_" + coupon_type + "_" + seq + " option:selected").val() == 13){
            $("#barcode_txt_" + coupon_type + "_" + seq).prop("maxlength", 13);
        }else if ($("#select_barcode_length_" + coupon_type + "_" + seq + " option:selected").val() == 8){
            $("#barcode_txt_" + coupon_type + "_" + seq).prop("maxlength", 8);
            if ($("#barcode_txt_" + coupon_type + "_" + seq).val().length > 8){
                $("#barcode_txt_" + coupon_type + "_" + seq).val($("#barcode_txt_" + coupon_type + "_" + seq).val().substring(0,8));
            }
        }
    }

	//쿠폰 타입 선택
	function coupon_type(t, no, seq){
		$("#pcd_type_" + seq).val(no);
        $(t).parent().children('.tm_on').removeClass('tm_on');
        $(t).addClass('tm_on');
		if(no == 1){ //TYPE 1. 무료증정 쿠폰 입력란 오픈
            $('#coupon_type1_' + seq).css('display', '');
            $('#coupon_type2_' + seq).css('display', 'none');
            $('#coupon_type3_' + seq).css('display', 'none');
            $('#pre_coupon_type1_' + seq).css('display', '');
            $('#pre_coupon_type2_' + seq).css('display', 'none');
            $('#pre_coupon_type3_' + seq).css('display', 'none');
		}else if(no == 2){ //TYPE 2. 가격할인 쿠폰 입력란 오픈
            $('#coupon_type1_' + seq).css('display', 'none');
            $('#coupon_type2_' + seq).css('display', '');
            $('#coupon_type3_' + seq).css('display', 'none');
            $('#pre_coupon_type1_' + seq).css('display', 'none');
            $('#pre_coupon_type2_' + seq).css('display', '');
            $('#pre_coupon_type3_' + seq).css('display', 'none');
		}else if(no == 3){ //TYPE 3. 타입 3 오픈
            $('#coupon_type1_' + seq).css('display', 'none');
            $('#coupon_type2_' + seq).css('display', 'none');
            $('#coupon_type3_' + seq).css('display', '');
            $('#pre_coupon_type1_' + seq).css('display', 'none');
            $('#pre_coupon_type2_' + seq).css('display', 'none');
            $('#pre_coupon_type3_' + seq).css('display', '');
		}
	}

	//폰트 사이즈 변경
	function size_change(id, type){
		var classId = $("#pre_"+ id);
		var currentSize = classId.css("fontSize"); //폰트사이즈를 알아낸다.
		var num = parseFloat(currentSize, 10); //parseFloat()은 숫자가 아니면 숫자가 아니라는 뜻의 NaN을 반환한다.
		var unit = currentSize.slice(-2); //끝에서부터 두자리의 문자를 가져온다.
		//alert("classId : "+ classId +"\n"+ "currentSize : "+ currentSize +"\n"+ "num : "+ num +"\n"+ "unit : "+ unit);
        var t = id.split('_')[2];

        if (t == 'type1' || t == 'type2'){
            if(type == "plus"){
    			num *= 1.10;
    			if(/pcd_date/g.test(id) && num > 45){ //행사기간 : 기본30-최대35/최소25
    				num = 45;
    			}else if(/pcd_title/g.test(id) && num > 55){ //쿠폰제목 : 기본40-최대45/최소35
    				num = 55;
    			}else if(/pcd_option1/g.test(id) && num > 30){ //TYPE1 상품정보, TYPE2 쿠폰옵션2 : 기본25-최대30/최소20
    				num = 30;
    			}else if(/pcd_option2/g.test(id) && num > 35){ //TYPE1 쿠폰옵션 : 기본20-최대25/최소15
    				num = 35;
    			}else if(/pcd_option1_type2/g.test(id) && num > 28){ //TYPE2 쿠폰옵션1 : 기본23-최대28/최소18
    				num = 28;
    			}else if(/pcd_price/g.test(id) && num > 80){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 80;
    			}
    		} else if(type == "minus"){
    			num /= 1.10;
    			if(/pcd_date/g.test(id) && num < 25){ //행사기간 : 기본30-최대35/최소25
    				num = 25;
    			}else if(/pcd_title/g.test(id) && num < 35){ //쿠폰제목 : 기본40-최대45/최소35
    				num = 35;
    			}else if(/pcd_option1/g.test(id) && num < 20){ //TYPE1 상품정보, TYPE2 쿠폰옵션2 : 기본25-최대30/최소20
    				num = 20;
    			}else if(/pcd_option2/g.test(id) && num < 15){ //TYPE1 쿠폰옵션 : 기본20-최대25/최소15
    				num = 15;
    			}else if(/pcd_option1_type2/g.test(id) && num < 18){ //TYPE2 쿠폰옵션1 : 기본23-최대28/최소18
    				num = 18;
    			}else if(/pcd_price/g.test(id) && num < 60){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 60;
    			}
    		}
        } else if (t == 'type3'){
            if(type == "plus"){
    			num *= 1.10;
    			if(/pcd_date/g.test(id) && num > 25){ //행사기간 : 기본30-최대35/최소25
    				num = 25;
    			}else if(/pcd_title/g.test(id) && num > 100){ //쿠폰제목 : 기본40-최대45/최소35
    				num = 100;
    			}else if(/pcd_option1/g.test(id) && num > 65){ //TYPE1 상품정보, TYPE2 쿠폰옵션2 : 기본25-최대30/최소20
    				num = 65;
    			}else if(/pcd_option2/g.test(id) && num > 35){ //TYPE1 쿠폰옵션 : 기본20-최대25/최소15
    				num = 35;
    			}else if(/pcd_option1_type2/g.test(id) && num > 28){ //TYPE2 쿠폰옵션1 : 기본23-최대28/최소18
    				num = 28;
    			}else if(/pcd_price/g.test(id) && num > 80){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 80;
    			}else if(/pcd_dcprice/g.test(id) && num > 50){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 50;
    			}else if(/pcd_message/g.test(id) && num > 25){
                    num = 25;
                }else if(/pcd_ucomment/g.test(id) && num > 35){
                    num = 35;
                }
    		} else if(type == "minus"){
    			num /= 1.10;
    			if(/pcd_date/g.test(id) && num < 15){ //행사기간 : 기본30-최대35/최소25
    				num = 15;
    			}else if(/pcd_title/g.test(id) && num < 35){ //쿠폰제목 : 기본40-최대45/최소35
    				num = 35;
    			}else if(/pcd_option1/g.test(id) && num < 20){ //TYPE1 상품정보, TYPE2 쿠폰옵션2 : 기본25-최대30/최소20
    				num = 20;
    			}else if(/pcd_option2/g.test(id) && num < 15){ //TYPE1 쿠폰옵션 : 기본20-최대25/최소15
    				num = 15;
    			}else if(/pcd_option1_type2/g.test(id) && num < 18){ //TYPE2 쿠폰옵션1 : 기본23-최대28/최소18
    				num = 18;
    			}else if(/pcd_price/g.test(id) && num < 20){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 20;
    			}else if(/pcd_dcprice/g.test(id) && num < 20){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 20;
    			}else if(/pcd_message/g.test(id) && num < 15){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 15;
    			}else if(/pcd_ucomment/g.test(id) && num < 15){ //TYPE2 할인금액 : 기본70-최대80/최소60
    				num = 15;
    			}
    		}
        }


		// alert("id : "+ id +"\n"+ "num : "+ num);
		classId.css("fontSize", num + unit);
	}

	//이미지 추가 모달창 열기
	var modal2 = document.getElementById("dh_myModal2");
	function showImg(type, imgid){
		//alert("imgid : "+ imgid); return;
		// $("#library_imgid").val(imgid); //이미지경로 ID
        $("#img_seq").val(imgid);
        $("#img_type").val(type);
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

	//이미지 선택 클릭시
	function imgChange(input){
		//alert("input.value : "+ input.value); return;
		if(input.value.length > 0) {
			//alert("input.value : "+ input.value); return;
  			var ext = input.value.split('.').pop().toLowerCase(); //파일 확장자
			//alert("ext : "+ ext); return;
  			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				alert("해당 파일은 이미지 파일이 아닙니다.\n(이미지 파일 : jpg, jpeg, png, gif)");
				return;
			}else{
				if (input.files && input.files[0]){
					var fileSize = input.files[0].size; //파일사이즈
					var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
					//alert("fileSize : "+ fileSize +", maxSize : "+ maxSize);
					if(maxSize < fileSize){
						jsFileRemove(input); //파일 초기화
						alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;
					modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
					var reader = new FileReader();
					reader.onload = function(e) {
                        var seq = $("#img_seq").val();
                        var type = $('#img_type').val();
						$("#img_preview" + type + "_" + seq).attr("src", e.target.result);
						$("#pre_div_preview" + type + "_" + seq).css({"background":"url(" + e.target.result + ")"}); //미리보기 이미지 배경 URL
					}
					reader.readAsDataURL(input.files[0]);

					var formData = new FormData();
					formData.append("imgfile", input.files[0]); //이미지
					formData.append("uppath", "<?=$upload_path?>"); //업로드경로
					formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
					$.ajax({
						url: "/spop/printer/imgfile_save",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function (json) {
                            var seq = $("#img_seq").val();
                            var type = $('#img_type').val();
							if(json.code == "0") { //성공
								jsFileRemove(input); //파일 초기화
								$("#img_preview" + type + "_" + seq).attr("src", json.imgpath); //이미지 URL
								$("#pre_div_preview" + type + "_" + seq).css({"background":"url(" + json.imgpath + ")"}); //미리보기 이미지 배경 URL
								$("#pcd_imgpath" + type + "_" + seq).val(json.imgpath); //사진 경로
							}else{ //실패
								jsFileRemove(input); //파일 초기화
								$("#img_preview" + type + "_" + seq).attr("src", ""); //이미지 URL
								$("#pre_div_preview" + type + "_" + seq).css({"background":"url(<?=$sample_img?>)"}); //미리보기 이미지 배경 URL
								$("#pcd_imgpath" + type + "_" + seq).val(""); //사진 경로
								alert(json.msg);
								return;
							}
						}
					});
				}
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
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		//alert("pageLibrary : "+ pageLibrary +", searchstr : "+ searchstr); return;
		//url: "/imglibrary/search_library",
		$.ajax({
			url: "/spop/screen/search_library",
			type: "POST",
			data: {"perpage" : "15", "page" : pageLibrary, "searchnm" : searchnm, "searchstr" : searchstr, "library_type" : library_type, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				pageLibrary = json.page;
				totalLibrary = json.total;
				//alert("pageLibrary : "+ pageLibrary +"\n"+ "totalLibrary : "+ totalLibrary +"\n"+ "json.html : "+ json.html); return;
				$("#library_append_list").append(json.html);
			}
		});
	}

	//라이브러리 모달창 열기
	var modal3 = document.getElementById("dh_myModal3");
	function showLibrary(type){
		$("#id_searchName").val("name"); //검색이름
		if(type == "img"){
			$("#id_searchName").show();
		}else{
			$("#id_searchName").hide();
		}
		var span = document.getElementById("dh_close3");
		modal3.style.display = "block";
		$("#library_type").val(type); //라이브러리 타입
		if(type == "goods"){
			$("#id_modal_title").html("상품");
		}else{
			$("#id_modal_title").html("이미지");
		}
		var goods_name = ""; //상품명
		var goods_option = ""; //옵션명
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
				modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
				modal3.style.display = "none"; //라이브러리 모달창 닫기
			}
		}
	}

	//이미지 라이브러리 선택 클릭시
	function set_img_library(imgpath){
		var id = ""; //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
            var seq = $("#img_seq").val();
            var type = $("#img_type").val();
			$("#img_preview" + type + "_" + seq).attr("src", imgpath); //이미지 URL
			$("#pre_div_preview" + type + "_" + seq).css({"background":"url(" + imgpath + ")"}); //미리보기 이미지 배경 URL
			$("#pcd_imgpath" + type + "_" + seq).val(imgpath); //사진 경로
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

	//데이타 변경시
	function chgData(id) {
		var data = $("#"+ id).val();
		if(data.trim() != "") {
            data = data.trim();
        } else {

        }
		$("#pre_"+ id).html(data); //미리보기 영역 표시값 변경
	}

	//저장하기
	function saved(flag){
		// var data_id = $("#data_id").val(); //쿠폰번호
		// var pcd_type = $("#pcd_type").val(); //쿠폰타입(1.무료증정, 2.가격할인)
		// var pcd_imgpath = ""; //이미지경로
		// var pcd_date = $("#pcd_date_"+ pcd_type).val().trim(); //행사기간
		// var pcd_title = $("#pcd_title_"+ pcd_type).val().trim(); //쿠폰제목
		// var pcd_option1 = $("#pcd_option1_"+ pcd_type).val().trim(); //상품정보/쿠폰옵션1
		// var pcd_option2 = $("#pcd_option2_"+ pcd_type).val().trim(); //쿠폰옵션/쿠폰옵션2
		// var pcd_price = ""; //할인금액
		// var pcd_date_size = jsFontSize("pre_pcd_date_"+ pcd_type); //행사기간 폰트크기
		// var pcd_title_size = jsFontSize("pre_pcd_title_"+ pcd_type); //쿠폰제목 폰트크기
		// var pcd_option1_size = jsFontSize("pre_pcd_option1_"+ pcd_type); //상품정보/쿠폰옵션1 폰트크기
		// var pcd_option2_size = jsFontSize("pre_pcd_option2_"+ pcd_type); //쿠폰옵션/쿠폰옵션2 폰트크기
		// var pcd_price_size = ""; //할인금액 폰트크기

        var coupon_seq = Number($("#coupon_seq").val());
        var formData = new FormData();
        for (var i = 0; i < coupon_seq; i++){
            if ($("#coupon_" + i).length > 0){
                var pcdd_type = $("#pcd_type_" + i).val();
                var pcdd_date = "";
                var pcdd_title = "";
                var pcdd_option1 = "";
                var pcdd_option2 = "";
                var pcdd_price = "";
                var pcdd_date_size = "";
                var pcdd_title_size = "";
                var pcdd_option1_size = "";
                var pcdd_option2_size = "";
                var pcdd_price_size = "";
                var pcdd_imgpath = "";
                var pcdd_barcode_length_flag = "";
                var pcdd_barcode_txt = "";
                var pcdd_barcode_img_path = "";
                var pcdd_message = "";
                var pcdd_message_size = "";
                var pcdd_stan = "";
                var pcdd_stan_size = "";
                var pcdd_dcprice = "";
                var pcdd_dcprice_size = "";
                var pcdd_ucomment = "";
                var pcdd_ucomment_size = "";
                var pcdd_backcolor = "";
                if(pcdd_type == "1"){
            		pcdd_date = $("#pcd_date_type1_" + i).val().trim(); //행사기간
            		pcdd_title = $("#pcd_title_type1_" + i).val().trim(); //쿠폰제목
            		pcdd_option1 = $("#pcd_option1_type1_" + i).val().trim(); //상품정보/쿠폰옵션1
            		pcdd_option2 = $("#pcd_option2_type1_" + i).val().trim(); //쿠폰옵션/쿠폰옵션2
                    pcdd_date_size = jsFontSize("pre_pcd_date_type1_"+ i); //행사기간 폰트크기
                    pcdd_title_size = jsFontSize("pre_pcd_title_type1_"+ i); //쿠폰제목 폰트크기
                    pcdd_option1_size = jsFontSize("pre_pcd_option1_type1_"+ i); //상품정보/쿠폰옵션1 폰트크기
                    pcdd_option2_size = jsFontSize("pre_pcd_option2_type1_"+ i); //쿠폰옵션/쿠폰옵션2 폰트크기
                    pcdd_imgpath = $("#pcd_imgpath_" + i).val(); //이미지경로
                    if ($("#apply_barcode_type1_" + i).val() == "1"){
                        pcdd_barcode_length_flag = $("#select_barcode_length_type1_" + i).val(); //바코드 길이
                        pcdd_barcode_txt = $("#barcode_txt_type1_" + i).val(); //바코드 텍스트
                        pcdd_barcode_img_path = $("#barcode_pre_img_type1_" + i).attr("src"); //바코드 이미지 경로
                    }

                    if(pcdd_imgpath == ""){
                        alert("쿠폰 이미지를 추가하세요.");
                        showImg('', i);
                        return false;
                    }
                    if(pcdd_date == ""){
                        alert("행사기간을 입력하세요.");
                        $("#pcd_date_type1_" + i).focus();
                        return false;
                    }
                    if(pcdd_title == ""){
                        alert("쿠폰제목을 입력하세요.");
                        $("#pcd_title_type1_" + i).focus();
                        return false;
                    }
                    if(pcdd_option1 == ""){
                        alert("상품정보를 입력하세요.");
                        $("#pcd_option1_type1_" + i).focus();
                        return false;
                    }
                    if(pcdd_option2 == ""){
                        alert("쿠폰옵션을 입력하세요.");
                        $("#pcd_option2_type1_" + i).focus();
                        return false;
                    }
                }else if (pcdd_type == "2"){
                    pcdd_date = $("#pcd_date_type2_" + i).val().trim(); //행사기간
            		pcdd_title = $("#pcd_title_type2_" + i).val().trim(); //쿠폰제목
            		pcdd_option1 = $("#pcd_option1_type2_" + i).val().trim(); //상품정보/쿠폰옵션1
            		pcdd_option2 = $("#pcd_option2_type2_" + i).val().trim(); //쿠폰옵션/쿠폰옵션2
                    pcdd_price = $("#pcd_price_type2_" + i).val().trim(); //가격
                    pcdd_date_size = jsFontSize("pre_pcd_date_type2_"+ i); //행사기간 폰트크기
                    pcdd_title_size = jsFontSize("pre_pcd_title_type2_"+ i); //쿠폰제목 폰트크기
                    pcdd_option1_size = jsFontSize("pre_pcd_option1_type2_"+ i); //상품정보/쿠폰옵션1 폰트크기
                    pcdd_option2_size = jsFontSize("pre_pcd_option2_type2_"+ i); //쿠폰옵션/쿠폰옵션2 폰트크기
                    pcdd_price_size = jsFontSize("pre_pcd_price_type2_"+ i); //쿠폰옵션/쿠폰옵션2 폰트크기
                    if ($("#apply_barcode_type2_" + i).val() == "1"){
                        pcdd_barcode_length_flag = $("#select_barcode_length_type2_" + i).val(); //바코드 길이
                        pcdd_barcode_txt = $("#barcode_txt_type2_" + i).val(); //바코드 텍스트
                        pcdd_barcode_img_path = $("#barcode_pre_img_type2_" + i).attr("src"); //바코드 이미지 경로
                    }

                    if(pcdd_date == ""){
                        alert("행사기간을 입력하세요.");
                        $("#pcd_date_type2_" + i).focus();
                        return false;
                    }
                    if(pcdd_title == ""){
                        alert("쿠폰제목을 입력하세요.");
                        $("#pcd_title_type2_" + i).focus();
                        return false;
                    }
                    if(pcdd_option1 == ""){
                        alert("쿠폰옵션1을 입력하세요.");
                        $("#pcd_option1_type2_" + i).focus();
                        return false;
                    }
                    if(pcdd_price == ""){
                        alert("할인금액을 입력하세요.");
                        $("#pcd_price_type2_" + i).focus();
                        return false;
                    }
                    if(pcdd_option2 == ""){
                        alert("쿠폰옵션2를 입력하세요.");
                        $("#pcd_option2_type2_" + i).focus();
                        return false;
                    }
                } else if (pcdd_type == "3"){
                    pcdd_date = $("#pcd_date_type3_" + i).val().trim(); //행사기간
            		pcdd_title = $("#pcd_title_type3_" + i).val().trim(); //쿠폰제목
            		pcdd_option1 = $("#pcd_option1_type3_" + i).val().trim(); //상품정보/쿠폰옵션1
            		pcdd_option2 = $("#pcd_option2_type3_" + i).val().trim(); //쿠폰옵션/쿠폰옵션2
                    pcdd_price = $("#pcd_price_type3_" + i).val().trim(); //가격
                    pcdd_date_size = jsFontSize("pre_pcd_date_type3_"+ i); //행사기간 폰트크기
                    pcdd_title_size = jsFontSize("pre_pcd_title_type3_"+ i); //쿠폰제목 폰트크기
                    pcdd_option1_size = jsFontSize("pre_pcd_option1_type3_"+ i); //상품정보/쿠폰옵션1 폰트크기
                    pcdd_option2_size = jsFontSize("pre_pcd_option2_type3_"+ i); //쿠폰옵션/쿠폰옵션2 폰트크기
                    pcdd_price_size = jsFontSize("pre_pcd_price_type3_"+ i); //쿠폰옵션/쿠폰옵션2 폰트크기
                    pcdd_imgpath = $("#pcd_imgpath3_" + i).val(); //이미지경로
                    if ($("#apply_barcode_type3_" + i).val() == "1"){
                        pcdd_barcode_length_flag = $("#select_barcode_length_type3_" + i).val(); //바코드 길이
                        pcdd_barcode_txt = $("#barcode_txt_type3_" + i).val(); //바코드 텍스트
                        pcdd_barcode_img_path = $("#barcode_pre_img_type3_" + i).attr("src"); //바코드 이미지 경로
                    }
                    pcdd_message = $("#pcd_message_type3_" + i).val().trim(); //행사기간;
                    pcdd_message_size = jsFontSize("pre_pcd_message_type3_"+ i);
                    pcdd_stan = $("#pcd_stan_type3_" + i).val().trim();
                    pcdd_stan_size = jsFontSize("pre_pcd_stan_type3_"+ i);
                    pcdd_dcprice = $("#pcd_dcprice_type3_" + i).val().trim();
                    pcdd_dcprice_size = jsFontSize("pre_pcd_dcprice_type3_"+ i);
                    pcdd_ucomment = $("#pcd_ucomment_type3_" + i).val().trim();
                    pcdd_ucomment_size = jsFontSize("pre_pcd_ucomment_type3_"+ i);
                    pcdd_backcolor = $('#pre_coupon_type3_' + i).css('background-color');

                    if(pcdd_imgpath == ""){
                        alert("쿠폰 이미지를 추가하세요.");
                        showImg(3, i);
                        return false;
                    }
                    if(pcdd_message == ""){
                        alert("메세지를 입력하세요.");
                        $("#pcd_message_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_title == ""){
                        alert("쿠폰제목을 입력하세요.");
                        $("#pcd_title_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_date == ""){
                        alert("쿠폰사용기간을 입력하세요.");
                        $("#pcd_date_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_option1 == ""){
                        alert("상품정보를 입력하세요.");
                        $("#pcd_option1_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_price == ""){
                        alert("쿠폰옵션을 입력하세요.");
                        $("#pcd_price_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_stan == ""){
                        alert("규격을 입력하세요.");
                        $("#pcd_stan_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_price == ""){
                        alert("정상가를 입력하세요.");
                        $("#pcd_price_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_dcprice == ""){
                        alert("판매가을 입력하세요.");
                        $("#pcd_dcprice_type3_" + i).focus();
                        return false;
                    }
                    if(pcdd_ucomment == ""){
                        alert("하단정보를 입력하세요.");
                        $("#pcd_ucomment_type3_" + i).focus();
                        return false;
                    }
                }
                var dataAraay = [pcdd_type, pcdd_date, pcdd_title, pcdd_option1, pcdd_option2, pcdd_price, pcdd_date_size, pcdd_title_size, pcdd_option1_size, pcdd_option2_size, pcdd_price_size, pcdd_imgpath, pcdd_barcode_length_flag, pcdd_barcode_txt, pcdd_barcode_img_path, pcdd_message, pcdd_message_size, pcdd_stan, pcdd_stan_size, pcdd_dcprice, pcdd_dcprice_size, pcdd_ucomment, pcdd_ucomment_size, pcdd_backcolor];
                formData.append('pcdd_arr[]', JSON.stringify(dataAraay));
            }
        }
		var target = $("#coupon_img_append_div");
		var t = target[0];
		html2canvas(t, {scale:3, scrollX: 0, scrollY:0, allowTaint: true, useCORS: true}).then(function(canvas) {
			var imgfile = canvas.toDataURL("image/png");
            var data_id = $("#data_id").val(); //쿠폰번호
			formData.append("data_id", data_id); //일련번호
			formData.append("imgfile", imgfile); //HTML to Image
            formData.append("flag", flag); //HTML to Image
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/spop/coupon/coupon_save",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
					//alert("json.id : "+ json.id +", json.pcd_code : "+ json.pcd_code +", json.flag : "+ json.flag);
					if(json.code == "0") { //성공
						$("#data_id").val(json.id); //쿠폰번호
						if(json.flag == "lms"){ //문자메시지 발송
							location.href = "/dhnbiz/sender/send/lms?pcd_code="+ json.pcd_code +"&pcd_type="+ pcd_type; //문자메시지 발송 페이지
						}else if(json.flag == "talk_adv"){ //알림톡 발송
							location.href = "/dhnbiz/sender/send/talk_img_adv_v4?pcd_code="+ json.pcd_code +"&pcd_type="+ pcd_type; //알림톡 발송 페이지
						}else{
							showSnackbar("저장 되었습니다.", 1500);
							setTimeout(function() {
								list(); //목록 페이지
							}, 1000); //1초 지연
						}
					}else{ //실패
						alert(json.msg);
					}
				}
			});
			//alert("OK1"); return;
		});
	}
	//alert("OK2"); return;

	//목록
	function list(){
		location.href = "/spop/coupon<?=($add !="") ? "?add=". $add : ""?>"; //목록 페이지
	}


</script>
