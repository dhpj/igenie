<link rel="stylesheet" type="text/css" href="/views/dhnbiz/sender/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>
<link rel="stylesheet" type="text/css" href="/views/kakao/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>
<?php
// include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/login_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/modal1.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/modal2.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/modal3.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/modal4.php');

?>
<!-- 타이틀 영역 -->
<div class="send_menu">
	<ul>
        <li><a href="#" class="sm_on"><i class="xi-new-o"></i> 기본텍스트</a></li>
        <li><a href="#"><i class="xi-view-stream"></i> 와이드이미지</a></li>
        <li><a href="#"><i class="xi-view-list"></i> 와이드리스트</a></li>
        <li><a href="#"><i class="xi-view-carousel"></i> 캐러셀커머스</a></li>
        <li><a href="#"><i class="xi-view-column"></i> 캐러셀피드</a></li>
	</ul>
</div>

<!-- 컨텐츠 전체 영역 -->
<!-- 기본텍스트 -->
<div id="mArticle" class="kakao kakao_basic">
    <div class="form_section">
        <div class="inner_tit">
            <h3>기본텍스트</h3>
        </div>

        <div class="inner_content preview_info">
            <div id="send_template_content" class="">

                <!-- 광고 -->
                <div class="adFlag">
                    <input type="checkbox" id="chk_adFlag" <?=!empty($data->messageElement->adFlag) ? ($data->messageElement->adFlag ? 'checked' : '') : ''?>>
                    <label for="chk_adFlag">광고성 메시지</label>
                    <span tooltip="체크 시 광고성 메시지 가이드에 따라 프로필 이름 앞에 (광고)표시, 메시지 하단에 수신거부 및 수신 동의 철회 방법 안내가 표시됩니다." flow="down"><i class="xi-help-o"></i></span>
                </div>

                <!-- STEP 0 -->
                <?
                include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/creative_adglist.php');
                ?>

                <!-- STEP 1 -->
            	<div class="input_content_wrap">
            		<label class="input_tit" id="id_STEP1">
            			<p class="txt_st_eng">STEP 2.</p>
            			<p>소재영역</p>
            		</label>
            		<div class="input_content">

                        <div class="opt_select">
                            <button class="btn_gw link_selected" onclick='add_file();'>
                                <span class="ico_add ico_sel">파일 추가</span>
                            </button>
                            <div id='add_file_list' class="layer_opt guide_open" style='display:none'>
                                <div class="wrap_list">
                                    <ul class="list_option">
                                        <li onclick='open_modal("", 1)'><a class="link_option file_01"><span class="ico_add"></span>이미지 추가</a></li>
                                        <!-- <li onclick='open_modal("", 2)'><a class="link_option file_02"><span class="ico_add"></span>동영상 추가</a></li> -->
                                        <li onclick='open_modal("", 2)'><a class="link_option file_03"><span class="ico_add"></span>카카오TV 목록보기</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <button class="btn_gw">
                            <span onclick='open_modal("", 3)' class="ico_add">변수 항목 추가</span>
                            <span tooltip="메시지 발송 요청 시 소재에 설정한 변수 항목에 해당하는 이미지 또는 동영상 URL 정보를 포함하면, 사용자마다 개인화된 홍보 이미지 또는 홍보 동영상을 첨부하여 발송합니다." flow="down"><i class="xi-help-o"></i></span>
                        </button>
                        <!-- <button class="btn_gw btn_img">
                            <span class="ico_add">이미지 만들기</span>
                            <span tooltip="이미지 에디터를 통하여 만든 이미지 확인 및 수정은 `소재 라이브러리 > 이미지 에디터 탭` 에서 할 수 있습니다." flow="down"><i class="xi-help-o"></i></span>
                        </button> -->
            		</div>


                    <!-- 추가된 파일 들어갈 곳 -->
                    <dl id='m_img_box' class="input_content add_file_view" style='display:<?=!empty($data->messageElement->image) ? '' : 'none'?>;'>
                        <dt>
                            <?
                                $img_src1 = '';
                                $img_src2 = '';
                                if (!empty($data->messageElement->image->valueWithVariable)){
                                    $img_src1 = '/images/pm_01.jpg';
                                    $img_src2 = '/images/pm_02.jpg';
                                } else {
                                    if (!empty($data->messageElement->image->url)){
                                        $img_src1 = $data->messageElement->image->url;
                                        $img_src2 = $data->messageElement->image->url;
                                    }
                                }
                            ?>
                            <img id='m_img' src="<?=$img_src2?>" style="width: 100%">
                        </dt>
                        <dd>
                            <span id='m_name' class="add_file_name"><?=!empty($data->messageElement->image->url) ? $data->messageElement->image->fileName : ''?></span>
                            <span id='img_para_description' style='display:<?=!empty($data->messageElement->image->valueWithVariable) ? '' : 'none'?>;'>발송 요청 시 전달한 이미지 소재를 발송합니다.</span>
                            <span class="add_file_view" onclick='delete_img();'>×</span>
                        </dd>
                        <input id='select_img' type='hidden' data-path='' data-name='' data-type=''>
                        <input id='select_video' type='hidden' data-liid='' data-thumb='' data-name=''>
                    </dl>
            	</div>

                <!-- STEP 2 -->
            	<div class="input_content_wrap">
                	<label class="input_tit" id="id_STEP2">
                		<p class="txt_st_eng">STEP 3.</p>
                		<p>홍보문구 <em>*</em></p>
                	</label>
                	<div class="input_content">
                        <div class="msg_box">
                            <textarea class="input_msg" id="c_title" placeholder="홍보문구를 입력하세요." onkeyup="check_char(event, this, 1000);set_title_txt(this.value);" maxlength="400"><?=$data->messageElement->title?></textarea>
                            <span class="txt_byte" id="">
                                <span id="c_title" class='num'>0</span>/1000 자
                                <a class="bar_left btn_plus" onclick='open_modal("", 4)'></a>
                           </span>
                        </div>
                	</div>
                </div>

                <!-- STEP 3 버튼 1 -->
                <!-- <div class="input_content_wrap">
                    <label class="input_tit" id="id_STEP3">
            			<p class="txt_st_eng">STEP 3.</p>
            			<p>버튼 1</p>
            		</label>

                    <div class="input_content">
            			<div class="smart_btn_box">
                            <div id="field">

                                <div id="field-data-0" name="field-data">
                                    <div class="input_link_wrap">
                                        <div class="input_link_wrap">
                                            <div id="selectIn_0">
                                                <div style="display:;">
                                                    <input type="hidden" name="link_type_option0" id="link_type_option0" value="home">
                                                    <select style="" class="send_select" name="link_type" id="link_type0" onchange="chg_link_type(&quot;0&quot;);">
                                                        <option value="home" selected="">스마트홈</option>
                                                        <option value="self">직접입력</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button class="btn md fr" style="margin-left: -1px;" id="find_url" onclick="linkview_home(0);">★홈 바로가기★</button>
                                            <div style="overflow: hidden;">
                                                <input type="url" style="background:#f2f2f2;" id="btn_url" class="btn_loop_0" name="btn_url1" placeholder="스마트홈" value="http://dhnl.kr/buzvx" readonly="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="div_home_box" class="input_link_wrap">
                                    <div class="info_contents">
                                        <input type="checkbox" id="chk_a" name="chk_btn" onchange="smart_chk_change(this, &quot;&quot;);" value="psd" checked="">
                                        <label for="chk_a"> 스마트전단</label>&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" id="chk_b" name="chk_btn" onchange="editor_choice(this, &quot;cgcmy2irh0&quot;);" value="dhnl">
                                        <label for="chk_b"> 에디터</label>&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" id="chk_c" name="chk_btn" onchange="coupon_chk_change(this, &quot;&quot;);" value="pcd">
                                        <label for="chk_c"> 스마트쿠폰</label>&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" id="chk_d" name="chk_btn" onchange="order_chk_change(this);" value="ord">
                                        <label for="chk_d"> 직접입력</label>
                                    </div>
                                    <div id="div_smart">
                                        <span class="smart_home_btn">스마트전단</span>
                                        <button class="btn_add btn_myModal" onclick="smart_page(1);">전단불러오기</button>
                                        <input type="hidden" id="hdn_smart_url" value="">
                                        <button class="btn_homelink" onclick="linkview_btn(0);">
                                            <span class="material-icons">link</span> 행사보기
                                        </button>
                                    </div>
                                    <div id="div_editor" style="display:none;">
                                        <span class="smart_home_btn">에디터</span>
                                        <input type="hidden" id="hdn_editor_url" value="">
                                        <button class="btn_homelink" onclick="linkview_btn(1);">
                                             <span class="material-icons">link</span> 전단보기
                                        </button>
                                    </div>
                                    <div id="div_coupon" style="display:none;">
                                        <span class="smart_home_btn">스마트쿠폰</span>
                                        <button class="btn_add btn_myModal" onclick="coupon_page(1);">쿠폰불러오기</button>
                                        <input type="hidden" id="hdn_coupon_url" value="">
                                        <button class="btn_homelink" onclick="linkview_btn(2);">
                                            <span class="material-icons">link</span> 쿠폰보기
                                        </button>
                                    </div>
                                    <div id="div_order" style="display:none;">
                                        <span class="smart_home_btn">직접입력</span>
                                        <input type="text" id="hdn_order_url" value="01093111339">
                                        <button id="order_btn_review" class="btn_homelink" onclick="linkview_btn(3);" style="letter-spacing: -0.3px;">
                                            <span class="material-icons">link</span> 주문하기
                                        </button>
                                        <input type="text" id="hdn_order_btn" style="width: 103px; margin: 5px 0 0 274px;" value="" placeholder="버튼이름 입력" onkeyup="return view_btn_name();" maxlength="6">
                                    </div>
                                </div>
                            </div>
                        </div>
                	</div>
                </div> -->

                <!-- STEP 3 버튼 2 -->
                <div class="input_content_wrap">
                    <label class="input_tit" id="id_STEP3">
                        <p class="txt_st_eng">STEP 4.</p>
            			<p>버튼</p>
            		</label>

                    <div class="input_content">
            			<div class="smart_btn_box">
                            <?
                                $button_name1 = '버튼1';
                                $button_name2 = '버튼2';
                                if (!empty($data->messageElement->buttonAssetGroups[0])){
                                    $button_name1 = $data->messageElement->buttonAssetGroups[0]->title;
                                }
                                if (!empty($data->messageElement->buttonAssetGroups[1])){
                                    $button_name2 = $data->messageElement->buttonAssetGroups[1]->title;
                                }

                            ?>
                            <ul id="field3">
                                <li>
                                    <input type="checkbox" id="chk_btn1" name="chk_btn" onchange="change_btn(this, 1)" value="" <?=!empty($data->messageElement->buttonAssetGroups[0]) ? 'checked' : ''?>>
                                    <label for="chk_btn1"> 버튼1</label>
                                    <input type="text" id='txt_btn1' name="" value="<?=!empty($data->messageElement->buttonAssetGroups[0]) ? $data->messageElement->buttonAssetGroups[0]->title : ''?>" style='display:<?=!empty($data->messageElement->buttonAssetGroups[0]) ? '' : 'none'?>;' maxlength="8" placeholder='버튼1' onkeyup='set_button_txt(this.value, 0);'>
                                </li>
                                <li id='li_btn2' style='display:<?=!empty($data->messageElement->buttonAssetGroups[0]) ? '' : 'none'?>'>
                                    <input type="checkbox" id="chk_btn2" name="chk_btn" onchange="change_btn(this, 2)" value="" <?=!empty($data->messageElement->buttonAssetGroups[1]) ? 'checked' : ''?>>
                                    <label for="chk_btn2"> 버튼2</label>
                                    <input type="text" id='txt_btn2' name="" value="<?=!empty($data->messageElement->buttonAssetGroups[1]) ? $data->messageElement->buttonAssetGroups[1]->title : ''?>" style='display:<?=!empty($data->messageElement->buttonAssetGroups[0]) ? '' : 'none'?>;' maxlength="8" placeholder='버튼2' onkeyup='set_button_txt(this.value, 1);'>
                                </li>
                            </ul>
                        </div>

                	</div>
                </div>

                <!-- STEP 4 -->
            	<!-- <div class="input_content_wrap">
                	<label class="input_tit" id="id_STEP2">
                		<p class="txt_st_eng">STEP 4.</p>
                		<p>쿠폰 강조 버튼</p>
                	</label>
                	<div class="input_content">
                        <div class="opt_select select_coupon">
                            <button class="btn_gw link_selected">
                                <span class="ico_add ico_sel">쿠폰 추가</span>
                            </button>
                            <div class="layer_opt wrap_list" style='display:none'>
                                <ul class="list_option">
                                    <li><label class="link_option ico_add" for="couponBook_0">채널 쿠폰 추가<input id="couponBook_0"></label></li>
                                    <li><label class="link_option ico_add" for="couponBook_1">직접 쿠폰 추가<input id="couponBook_1"></label></li>
                                </ul>
                            </div>
                        </div>
                        <span class="box_checkinp">
                            <input type="checkbox" name="" id="couponBook" class="inp_check" checked="">
                            <label for="couponBook" class="lab_check"><span class="ico_comm ico_check"></span>미설정</label>
                        </span>

                    </div>
                    <div class="input_content coupon_selected">
                        <span class="coupon_selected_txt">선택된 쿠폰 <span class="coupon_selected_num">0</span>개</span>
                        <button type="button" class="btn_gw">전체 삭제</button>

                	</div>
                </div> -->



                <!-- STEP 4 직접 쿠폰 추가/ 쿠폰 URL -->
            	<!-- <div class="input_content_wrap">
                	<label class="input_tit" id="id_STEP2">
                		<p>쿠폰 URL <em>*</em></p>
                	</label>
                    <div class="input_content">
                        <div class="msg_box">
                            <textarea class="input_msg" id="" placeholder="http:// 또는 https:// 형식의 정상적인 랜딩 URL을 입력하세요." onkeyup="" maxlength="1000"></textarea>
                            <span class="txt_byte" id="">
                                <span id="type_num">218</span>/1,000 자
                                <a class="bar_left btn_plus"></a>
                           </span>
                        </div>
                	</div>
                    <div class="input_content add_link_pc">
                        <a href="#" class="ico_add">PC 링크 추가하기<span class="ico_comm ico_arr"></span></a>
                        <div class="msg_box">
                			<textarea class="input_msg" id="" placeholder="http:// 또는 https:// 형식의 정상적인 랜딩 URL을 입력하세요." maxlength="1000" onkeyup=""></textarea>
                			<span class="txt_byte" id="">
                                <span id="type_num">218</span>/1,000 자
                                <a class="bar_left btn_plus"></a>
                           </span>
                		</div>
                	</div>
                </div> -->

                <!-- STEP 4 직접 쿠폰 추가/ 쿠폰 유형 -->
            	<!-- <div class="input_content_wrap">

                    <label class="input_tit" id="id_STEP2">
                		<p>쿠폰 유형 <em>*</em></p>
                	</label>
                	<div class="input_content coupon_category">

                        <select style="" class="select_coupon_category" name="" id="" onchange=";">
                            <option value="" selected="">할인 금액</option>
                            <option value="">할인율</option>
                            <option value="">배송비 할인</option>
                            <option value="">무료증정</option>
                            <option value="">업그레이드</option>
                        </select>

                        <div class="msg_box">
                            <textarea class="input_msg length50" id="" placeholder="숫자를 입력하세요" onkeyup="" maxlength="50"></textarea><span class="input_msg_text"> %</span>
                			<span class="txt_byte" id="">
                                <span id="type_num">218</span>/1,000 자
                                <a class="bar_left btn_plus"></a>
                           </span>
                		</div>

                        <div class="msg_box">
                            <textarea class="input_msg" id="" placeholder="쿠폰 상세 설명을 입력하세요." onkeyup="" maxlength="1000"></textarea>
                            <span class="txt_byte" id="">
                                <span id="type_num">218</span>/1,000 자
                                <a class="bar_left btn_plus"></a>
                           </span>
                        </div>

                	</div>
                </div> -->

                <!-- 소재이름 -->
                <div class="material_name">
                    <label for="">소재 이름</label>
                    <input type="text" name="" id="c_name" onkeyup="check_char(event, this, 40)" placeholder="빈값일 시 자동 생성(예 : 개인화 메시지_도달_yyyyMMddhhmm)" value='<?=$data->name?>'>
                    <span class="txt_byte">
                        <span id="c_name" class='num'><?=!empty($data) ? count($data->name) : '0'?>0</span>/40 자
                    </span>
                </div>
                <input type='hidden' id='imageFile' data-path='' data-name='' data-type=''>
            </div>
        </div>

        <!-- 오른쪽 핸드폰 화면 -->
        <div class="mobile_preview">
			<div class="preview_circle"></div>
			<div class="preview_round"></div>
			<div class="preview_msg_wrap">
				<div class="preview_msg_window">
                    <div class="top_view">
                        <strong class="tit_view">대형네트웍스 관리자</strong>
                        <div class="txt_info">
                            <span class="txt_num">1522-7985</span>
                        </div>
                    </div>
					<div class="preview_box_profile">
						<span class="profile_thumb">
							<img src="/img/icon/icon_profile.jpg">
						</span>
						<span class="profile_text">(광고) 대형네트웍스 관리자</span>
					</div>
					<div class="preview_box_msg" id="text">
                        <img id='p_img' src="<?=$img_src1?>" style='display:<?=!empty($img_src1) ? '' : 'none'?>;'>
                        <div id='p_title' class="preview_msg_text"><?=!empty($data) ? str_replace(PHP_EOL, '<br>', $data->messageElement->title) : '홍보문구를 입력하세요.'?></div>
                        <div id='p_button_box' class="preview_msg_btn" style='display:<?=!empty($data->messageElement->buttonAssetGroups[0]) ? '' : 'none'?>'>
                            <p class="tem_btn"><?=$button_name1?></p>
                            <p class="tem_btn" style='display:<?=!empty($data->messageElement->buttonAssetGroups[1]) ? '' : 'none'?>'><?=$button_name2?></p>
                        </div>
                    </div>
                    <div class="txt_append">수신거부<span class="bar_append ico_comm"> | </span>홈<span class="arrow_append ico_comm"> &gt; </span>채널차단</div>
				</div>
			</div>
			<div class="preview_home"></div>
		</div><!-- mobile_preview END -->
        <div class="reform_view_guide">
            <ul class="reform_view_list">
                <li>광고성 메시지인 경우 프로필 이름 앞에 (광고)표시, 메시지 하단에 수신거부 및 수신 동의 철회 방법 안내가 표시됩니다.</li>
                <li>관련 법률을 준수하지 않은 개인정보를 포함하거나 수신자에게 불편함을 줄 수 있는 변수가 포함된 개인화 메시지를 발송할 경우 광고계정 및 기타 운영 제재가 발생할 수 있습니다.</li>
            </ul>
        </div>

    </div>
    <div class="btn_send_cen">
		<button class="btn lg btn_bk" onclick="set_personal_msg1('BASIC_TEXT_MESSAGE')"><?=!empty($data) ? '수정' : '저장'?></button>
	</div>
</div><!-- mArticle END -->


<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/kakao/bootstrap/sender.php');
?>

<script type="text/javascript">
    var imgOrVideoFlag = '';

    $(document).on('keydown', function(e){
        if (e.keyCode == 27){
            for(var i=1;i<5;i++){
                if($('#modal' + i).css('display') == 'block'){
                    $('#modal' + i).css('display', 'none');
                }
            }
        }
    })

    function add_file(){
        var afl = $('#add_file_list').css('display');
        if (afl == 'block'){
            $('#add_file_list').css('display', 'none');
        } else {
            $('#add_file_list').css('display', 'block');
        }
    }

    function change_btn(t, seq){
        if(t.checked){
            $('#txt_btn' + seq).css('display', '');
            if (seq === 1){
                $('#li_btn2').css('display', '');
                $('#p_button_box').css('display', '');
                $('#p_button_box').find('p').eq(0).css('display', '');
                $('#p_button_box').find('p').eq(1).css('display', 'none');
            } else if (seq === 2){
                $('#p_button_box').find('p').eq(1).css('display', '');
            }
        } else {
            $('#txt_btn' + seq).css('display', 'none');
            $('#txt_btn' + seq).val('');
            if (seq === 1){
                $('#li_btn2').css('display', 'none');
                $('#p_button_box').css('display', 'none');
                if ($('#chk_btn2').prop('checked') == true){
                    $('#chk_btn2').trigger('click');
                }
            } else if (seq === 2){
                $('#p_button_box').find('p').eq(1).css('display', 'none');
            }
        }
    }

    function check_char(e, t, max){
        $(t).val($(t).val().substring(0, max));
        $(t).parent().find('.num').html($(t).val().length);
    }

    function open_modal(dp, seq){
        $('#modal' + seq).css('display', dp);
        if (dp == '' && seq !== 3 && seq !== 4){
            $('#file_name').val('');
            add_file();
        }
        $('.tab').eq(1).trigger('click');
        if(seq === 1) {
            get_creative_img(1)
        } else if (seq === 2){
            $('#file_name2').val('');
            if (chid == ''){
                $.ajax({
        			url: "/kakao/get_kakaotv_chs",
        			type: "POST",
        			data: {
                        "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                      , token : Kakao.Auth.getAccessToken()
                    },
        			success: function (json) {
                        if (json.data.code === undefined){
                            chid = json.data.list[0].id;
                            get_kakaotv_ch_movs(1);
                        } else {
                            alert(json.data.msg);
                        }
        			}
        		});
            } else {
                get_kakaotv_ch_movs(1);
            }
        } else if (seq === 3){
            $('#add_file_list').css('display', 'none');
            $('#p_para').html('${image_url1}');
            $('#m3_sel1').val(1);
            $('#m3_sel2').val(1);
        } else if (seq === 4){

        }
    }

    function delete_img(){
        $('#m_img_box').css('display', 'none');
        $('#select_img').data('path', '');
        $('#select_img').data('name', '');
        $('#select_img').data('type', '');
        $('#select_video').data('liid', '');
        $('#select_video').data('thumb', '');
        $('#select_video').data('name', '');
        $('#p_img').css('display', 'none');
        $('#p_img').attr('src', '');
        imgOrVideoFlag = '';
    }

    function set_button_txt(txt, seq){
        $('#p_button_box').find('p').eq(seq).html(txt);
    }

    function set_title_txt(txt){
        $('#p_title').html(txt.replaceAll('\n', '<br>'));
    }


</script>
