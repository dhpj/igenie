<?if($rs->spf_key) {?>
										<input type="hidden" id="pf_ynm" value="<?=$rs->spf_company?>"/>
                						<input type="hidden" id="pf_yid" value="<?=$rs->spf_friend?>"/>
                        				<input type="hidden" id="sms_sender" value="<?=$rs->spf_sms_callback?>"/>
                                        <input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->spf_key?>">
                                        <input type="hidden" name="kind" id="kind" value="<?=$rs->spf_key_type?>">
                                        <!-- 20190107 이수환 추가 작업중인 아디의 링크버튼 명을 입력-->
                                        <input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name" value="<?=$rs->mem_linkbtn_name?>">
            							<input type="hidden" name="cc_tpl_code" value="_ft_">
            							<input type="hidden" name="cc_tpl_id" value="-1">
										<input type="hidden" name="cc_type" value="FT">								 

								<div class="input_content_wrap">
									<label class="input_tit">홍보이미지</label>
									<div class="input_content">
										<!-- <label class="file" title=""><input type="file" onchange="this.parentNode.setAttribute('title', this.value.replace(/^.*[\\/]/, ''))" /></label>  -->
										<button type="button" class="btn md" onclick="imageSelect()" id="img_select"><i class="icon-folder-open"></i>이미지 선택</button>
										<button type="button" class="btn md" onclick="deleteImageSelect()" id="img_select_delete" style="display: none"><i class="icon-folder-open"></i>이미지 제거</button> 
                						<div class="input-group col-xs-12 mt10">
                                            <input type="hidden" name="img_url" id="img_url"/>
                                            <input type="text" name="img_link" id="img_link"
                                                   onchange="scroll_prevent();"
                                                   class="form-control" style="cursor:default;"
                                                   placeholder="이미지 링크 주소를 입력해주세요." readonly>
                                        </div>									
									</div>
									<div class="mobile_preview">
										<div class="preview_circle"></div>
										<div class="preview_round"></div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window">
												<div class="preview_box_profile">
													<span class="profile_thumb">
														<img src="/img/icon/icon_profile.png">
													</span>
													<span class="profile_text"><?=$rs->spf_friend?></span>
												</div>
												<div class="preview_box_msg" id="text">
													
												</div>
												<div class="icon_share"></div>
											</div>
										</div>
										<div class="preview_home"></div>
									</div><!-- mobile_preview END -->										
								</div><!-- input_content_wrap END -->
								
								<div class="input_content_wrap">
									<label class="input_tit">친구톡 내용</label>
									<div class="input_content">
										<div class="msg_box">
											<?php // 친구톡 내용 헤드 수정 2019-07-25 ?>
											<!-- div class="txt_ad" id="cont_header"></div-->
											<div class="txt_ad" id="cont_header">
												<span id="span_ft_adv">(광고)</span>
												<input type="text" class="input_option" style="width: 320px;" id="ft_companyName" onkeyup="onPreviewText(); chkword_templi(); keyup_cont_header();" value="<?=$rs->spf_company?>">
											</div>
											<textarea class="input_msg" id="templi_cont" placeholder="메세지를 입력해주세요." onkeyup="onPreviewText();chkword_templi();"><?=$ftrs->tpl_contents?></textarea>
											<span class="txt_byte" id="templi_length"><span id="type_num">0</span>/1,000 자</span>
											<div class="txt_ad" id="cont_footer"></div>
										</div>
										<input type="checkbox" id="cont_homepagelink_select" onclick="insert_homepage_link(this);" style="cursor: default;"><label for="cont_homepagelink_select">홈페이지 주소 자동삽입</label>
										<div class="btn_group">
											<button type="button" class="btn md btn_emoticon">카카오 이모티콘</button>
											<button type="button" class="btn md fr btn_emoji_kakao">특수문자</button>
										</div>

										
										<div class="toggle_layer_wrap toggle_layer_emoji_kakao">
											<h3>특수문자<i class="material-icons icon_close fr" id="icon_close">close</i></h3>
											<div class="layer_content emoticon_wrap clearfix">
												<ul>
                									<li onclick="insert_char('☆')">☆</li>
                									<li onclick="insert_char('★')">★</li>
                									<li onclick="insert_char('○')">○</li>
                									<li onclick="insert_char('●')">●</li>
                									<li onclick="insert_char('◎')">◎</li>
                									<li onclick="insert_char('⊙')">⊙</li>
                									<li onclick="insert_char('◇')">◇</li>
                									<li onclick="insert_char('◆')">◆</li>
                									<li onclick="insert_char('□')">□</li>
                									<li onclick="insert_char('■')">■</li>
                									<li onclick="insert_char('▣')">▣</li>
                									<li onclick="insert_char('△')">△</li>
                									<li onclick="insert_char('▲')">▲</li>
                									<li onclick="insert_char('▽')">▽</li>
                									<li onclick="insert_char('▼')">▼</li>
                									<li onclick="insert_char('◁')">◁</li>
                									<li onclick="insert_char('◀')">◀</li>
                									<li onclick="insert_char('▷')">▷</li>
                									<li onclick="insert_char('▶')">▶</li>
                									<li onclick="insert_char('♡')">♡</li>
                									<li onclick="insert_char('♥')">♥</li>
                									<li onclick="insert_char('♧')">♧</li>
                									<li onclick="insert_char('♣')">♣</li>
                									<li onclick="insert_char('◐')">◐</li>
               										<li onclick="insert_char('◑')">◑</li>
                									<li onclick="insert_char('▦')">▦</li>
                									<li onclick="insert_char('☎')">☎</li>
                									<li onclick="insert_char('♪')">♪</li>
                									<li onclick="insert_char('♬')">♬</li>
                									<li onclick="insert_char('※')">※</li>
                									<li onclick="insert_char('＃')">＃</li>
                									<li onclick="insert_char('＠')">＠</li>
               										<li onclick="insert_char('㉿')">㉿</li>
                									<li onclick="insert_char('™')">™</li>
                									<li onclick="insert_char('℡')">℡</li>
                									<li onclick="insert_char('Σ')">Σ</li>
                									<li onclick="insert_char('Δ')">Δ</li>
                									<li onclick="insert_char('♂')">♂</li>
                									<li onclick="insert_char('♀')">♀</li>
												</ul>
											</div>
											<div class="layer_content emoticon_wrap_multi clearfix">
												<ul>
													<li onclick="insert_char('^0^')">^0^</li>
													<li onclick="insert_char('☆(~.^)/')">☆(~.^)/</li>
													<li onclick="insert_char('づ^0^)づ')">づ^0^)づ</li>
													<li onclick="insert_char('(*^.^)♂')">(*^.^)♂</li>
													<li onclick="insert_char('(o^^)o')">(o^^)o</li>
													<li onclick="insert_char('o(^^o)')">o(^^o)</li>
													<li onclick="insert_char('=◑.◐=')">=◑.◐=</li>
													<li onclick="insert_char('_(≥∇≤)ノ')">_(≥∇≤)ノ</li>
													<li onclick="insert_char('q⊙.⊙p')">q⊙.⊙p</li>
													<li onclick="insert_char('^.^')">^.^</li>
													<li onclick="insert_char('(^.^)V')">(^.^)V</li>
													<li onclick="insert_char('*^^*')">*^^*</li>
													<li onclick="insert_char('^o^~~♬')">^o^~~♬</li>
													<li onclick="insert_char('S(*^___^*)S')">S(*^___^*)S</li>
													<li onclick="insert_char('^Δ^')">^Δ^</li>
													<li onclick="insert_char('＼(*^▽^*)ノ')">＼(*^▽^*)ノ</li>
													<li onclick="insert_char('^L^')">^L^</li>
													<li onclick="insert_char('^ε^')">^ε^</li>
													<li onclick="insert_char('^_^)'">^_^</li>
													<li onclick="insert_char('(ノ^O^)ノ')">(ノ^O^)ノ</li>
												</ul>
											</div>											
										</div>

										<div class="toggle_layer_wrap toggle_layer_emoticon">
											<h3>카카오 이모티콘<i class="material-icons icon_close fr">close</i></h3>
											<div class="layer_content emoticon_wrap emoticon clearfix">
												<ul>
													<li onclick="insert_char('(미소)')"><img src="/images/kakaoimg/kakaoimg_001.png" title="미소" alt="미소"></li>
													<li onclick="insert_char('(윙크)')"><img src="/images/kakaoimg/kakaoimg_002.png" title="윙크" alt="윙크"></li>
													<li onclick="insert_char('(방긋)')"><img src="/images/kakaoimg/kakaoimg_003.png" title="방긋" alt="방긋"></li>
													<li onclick="insert_char('(반함)')"><img src="/images/kakaoimg/kakaoimg_004.png" title="반함" alt="반함"></li>
													<li onclick="insert_char('(눈물)')"><img src="/images/kakaoimg/kakaoimg_005.png" title="눈물" alt="눈물"></li>
													<li onclick="insert_char('(절규)')"><img src="/images/kakaoimg/kakaoimg_006.png" title="절규" alt="절규"></li>
													<li onclick="insert_char('(크크)')"><img src="/images/kakaoimg/kakaoimg_007.png" title="크크" alt="크크"></li>
													<li onclick="insert_char('(메롱)')"><img src="/images/kakaoimg/kakaoimg_008.png" title="메롱" alt="메롱"></li>
													<li onclick="insert_char('(잘자)')"><img src="/images/kakaoimg/kakaoimg_009.png" title="잘자" alt="잘자"></li>
													<li onclick="insert_char('(잘난척)')"><img src="/images/kakaoimg/kakaoimg_010.png" title="잘난척" alt="잘난척"></li>
					
													<li onclick="insert_char('(헤롱)')"><img src="/images/kakaoimg/kakaoimg_011.png" title="헤롱" alt="헤롱"></li>
													<li onclick="insert_char('(놀람)')"><img src="/images/kakaoimg/kakaoimg_012.png" title="놀람" alt="놀람"></li>
													<li onclick="insert_char('(아픔)')"><img src="/images/kakaoimg/kakaoimg_013.png" title="아픔" alt="아픔"></li>
													<li onclick="insert_char('(당황)')"><img src="/images/kakaoimg/kakaoimg_014.png" title="당황" alt="당황"></li>
													<li onclick="insert_char('(풍선껌)')"><img src="/images/kakaoimg/kakaoimg_015.png" title="풍선껌" alt="풍선껌"></li>
													<li onclick="insert_char('(버럭)')"><img src="/images/kakaoimg/kakaoimg_016.png" title="버럭" alt="버럭"></li>
													<li onclick="insert_char('(부끄)')"><img src="/images/kakaoimg/kakaoimg_017.png" title="부끄" alt="부끄"></li>
													<li onclick="insert_char('(궁금)')"><img src="/images/kakaoimg/kakaoimg_018.png" title="궁금" alt="궁금"></li>
													<li onclick="insert_char('(흡족)')"><img src="/images/kakaoimg/kakaoimg_019.png" title="흡족" alt="흡족"></li>
													<li onclick="insert_char('(깜찍)')"><img src="/images/kakaoimg/kakaoimg_020.png" title="깜찍" alt="깜찍"></li>
					
													<li onclick="insert_char('(으으)')"><img src="/images/kakaoimg/kakaoimg_021.png" title="으으" alt="으으"></li>
													<li onclick="insert_char('(민망)')"><img src="/images/kakaoimg/kakaoimg_022.png" title="민망" alt="민망"></li>
													<li onclick="insert_char('(곤란)')"><img src="/images/kakaoimg/kakaoimg_023.png" title="곤란" alt="곤란"></li>
													<li onclick="insert_char('(잠)')"><img src="/images/kakaoimg/kakaoimg_024.png" title="잠" alt="잠"></li>
													<li onclick="insert_char('(행복)')"><img src="/images/kakaoimg/kakaoimg_025.png" title="행복" alt="행복"></li>
													<li onclick="insert_char('(안도)')"><img src="/images/kakaoimg/kakaoimg_026.png" title="안도" alt="안도"></li>
													<li onclick="insert_char('(우웩)')"><img src="/images/kakaoimg/kakaoimg_027.png" title="우웩" alt="우웩"></li>
													<li onclick="insert_char('(외계인)')"><img src="/images/kakaoimg/kakaoimg_028.png" title="외계인" alt="외계인"></li>
													<li onclick="insert_char('(외계인녀)')"><img src="/images/kakaoimg/kakaoimg_029.png" title="외계인녀" alt="외계인녀"></li>
													<li onclick="insert_char('(공포)')"><img src="/images/kakaoimg/kakaoimg_030.png" title="공포" alt="공포"></li>
					
													<li onclick="insert_char('(근심)')"><img src="/images/kakaoimg/kakaoimg_031.png" title="근심" alt="근심"></li>
													<li onclick="insert_char('(악마)')"><img src="/images/kakaoimg/kakaoimg_032.png" title="악마" alt="악마"></li>
													<li onclick="insert_char('(썩소)')"><img src="/images/kakaoimg/kakaoimg_033.png" title="썩소" alt="썩소"></li>
													<li onclick="insert_char('(쳇)')"><img src="/images/kakaoimg/kakaoimg_034.png" title="쳇" alt="쳇"></li>
													<li onclick="insert_char('(야호)')"><img src="/images/kakaoimg/kakaoimg_035.png" title="야호" alt="야호"></li>
													<li onclick="insert_char('(좌절)')"><img src="/images/kakaoimg/kakaoimg_036.png" title="좌절" alt="좌절"></li>
													<li onclick="insert_char('(삐침)')"><img src="/images/kakaoimg/kakaoimg_037.png" title="삐침" alt="삐침"></li>
													<li onclick="insert_char('(하트)')"><img src="/images/kakaoimg/kakaoimg_038.png" title="하트" alt="하트"></li>
													<li onclick="insert_char('(실연)')"><img src="/images/kakaoimg/kakaoimg_039.png" title="실연" alt="실연"></li>
													<li onclick="insert_char('(별)')"><img src="/images/kakaoimg/kakaoimg_040.png" title="별" alt="별"></li>
					
													<li onclick="insert_char('(브이)')"><img src="/images/kakaoimg/kakaoimg_041.png" title="브이" alt="브이"></li>
													<li onclick="insert_char('(오케이)')"><img src="/images/kakaoimg/kakaoimg_042.png" title="오케이" alt="오케이"></li>
													<li onclick="insert_char('(최고)')"><img src="/images/kakaoimg/kakaoimg_043.png" title="최고" alt="최고"></li>
													<li onclick="insert_char('(최악)')"><img src="/images/kakaoimg/kakaoimg_044.png" title="최악" alt="최악"></li>
													<li onclick="insert_char('(그만)')"><img src="/images/kakaoimg/kakaoimg_045.png" title="그만" alt="그만"></li>
													<li onclick="insert_char('(땀)')"><img src="/images/kakaoimg/kakaoimg_046.png" title="땀" alt="땀"></li>
													<li onclick="insert_char('(알약)')"><img src="/images/kakaoimg/kakaoimg_047.png" title="알약" alt="알약"></li>
													<li onclick="insert_char('(밥)')"><img src="/images/kakaoimg/kakaoimg_048.png" title="밥" alt="밥"></li>
													<li onclick="insert_char('(커피)')"><img src="/images/kakaoimg/kakaoimg_049.png" title="커피" alt="커피"></li>
													<li onclick="insert_char('(맥주)')"><img src="/images/kakaoimg/kakaoimg_050.png" title="맥주" alt="맥주"></li>
					
													<li onclick="insert_char('(소주)')"><img src="/images/kakaoimg/kakaoimg_051.png" title="소주" alt="소주"></li>
													<li onclick="insert_char('(와인)')"><img src="/images/kakaoimg/kakaoimg_052.png" title="와인" alt="와인"></li>
													<li onclick="insert_char('(치킨)')"><img src="/images/kakaoimg/kakaoimg_053.png" title="치킨" alt="치킨"></li>
													<li onclick="insert_char('(축하)')"><img src="/images/kakaoimg/kakaoimg_054.png" title="축하" alt="축하"></li>
													<li onclick="insert_char('(음표)')"><img src="/images/kakaoimg/kakaoimg_055.png" title="음표" alt="음표"></li>
													<li onclick="insert_char('(선물)')"><img src="/images/kakaoimg/kakaoimg_056.png" title="선물" alt="선물"></li>
													<li onclick="insert_char('(케이크)')"><img src="/images/kakaoimg/kakaoimg_057.png" title="케이크" alt="케이크"></li>
													<li onclick="insert_char('(촛불)')"><img src="/images/kakaoimg/kakaoimg_058.png" title="촛불" alt="촛불"></li>
													<li onclick="insert_char('(컵케이크a)')"><img src="/images/kakaoimg/kakaoimg_059.png" title="컵케이크a" alt="컵케이크a"></li>
													<li onclick="insert_char('(컵케이크b)')"><img src="/images/kakaoimg/kakaoimg_060.png" title="컵케이크b" alt="컵케이크b"></li>
					
													<li onclick="insert_char('(해)')"><img src="/images/kakaoimg/kakaoimg_061.png" title="해" alt="해"></li>
													<li onclick="insert_char('(구름)')"><img src="/images/kakaoimg/kakaoimg_062.png" title="구름" alt="구름"></li>
													<li onclick="insert_char('(비)')"><img src="/images/kakaoimg/kakaoimg_063.png" title="비" alt="비"></li>
													<li onclick="insert_char('(눈)')"><img src="/images/kakaoimg/kakaoimg_064.png" title="눈" alt="눈"></li>
													<li onclick="insert_char('(똥)')"><img src="/images/kakaoimg/kakaoimg_065.png" title="똥" alt="똥"></li>
													<li onclick="insert_char('(근조)')"><img src="/images/kakaoimg/kakaoimg_066.png" title="근조" alt="근조"></li>
					
													<li onclick="insert_char('(딸기)')"><img src="/images/kakaoimg/kakaoimg_067.png" title="딸기" alt="딸기"></li>
													<li onclick="insert_char('(호박)')"><img src="/images/kakaoimg/kakaoimg_068.png" title="호박" alt="호박"></li>
													<li onclick="insert_char('(입술)')"><img src="/images/kakaoimg/kakaoimg_069.png" title="입술" alt="입술"></li>
													<li onclick="insert_char('(야옹)')"><img src="/images/kakaoimg/kakaoimg_070.png" title="야옹" alt="야옹"></li>
													<li onclick="insert_char('(돈)')"><img src="/images/kakaoimg/kakaoimg_071.png" title="돈" alt="돈"></li>
													<li onclick="insert_char('(담배)')"><img src="/images/kakaoimg/kakaoimg_072.png" title="담배" alt="담배"></li>
													<li onclick="insert_char('(축구)')"><img src="/images/kakaoimg/kakaoimg_073.png" title="축구" alt="축구"></li>
													<li onclick="insert_char('(야구)')"><img src="/images/kakaoimg/kakaoimg_074.png" title="야구" alt="야구"></li>
													<li onclick="insert_char('(농구)')"><img src="/images/kakaoimg/kakaoimg_075.png" title="농구" alt="농구"></li>
													<li onclick="insert_char('(당구)')"><img src="/images/kakaoimg/kakaoimg_076.png" title="당구" alt="당구"></li>
					
													<li onclick="insert_char('(골프)')"><img src="/images/kakaoimg/kakaoimg_077.png" title="골프" alt="골프"></li>
													<li onclick="insert_char('(카톡)')"><img src="/images/kakaoimg/kakaoimg_078.png" title="카톡" alt="카톡"></li>
													<li onclick="insert_char('(꽃)')"><img src="/images/kakaoimg/kakaoimg_079.png" title="꽃" alt="꽃"></li>
													<li onclick="insert_char('(총)')"><img src="/images/kakaoimg/kakaoimg_080.png" title="총" alt="총"></li>
													<li onclick="insert_char('(크리스마스)')"><img src="/images/kakaoimg/kakaoimg_081.png" title="크리스마스" alt="크리스마스"></li>
													<li onclick="insert_char('(콜)')"><img src="/images/kakaoimg/kakaoimg_082.png" title="콜" alt="콜"></li>												</ul>
											</div>
										</div>
									</div>
								</div><!-- input_content_wrap END -->
 
								<script >
									$(".btn_emoji").click(function () {
										$(".toggle_layer_emoji").slideToggle("fast");
									});

									$(".btn_emoji_kakao").click(function () {
										if ($(".toggle_layer_emoticon").css("display") == "block") {
											$(".toggle_layer_emoticon").slideToggle("fast");
										}
										$(".toggle_layer_emoji_kakao").slideToggle("fast");
									});
									
									$(".btn_emoticon").click(function () {
										if ($(".toggle_layer_emoji_kakao").css("display") == "block") {
											$(".toggle_layer_emoji_kakao").slideToggle("fast");
										}	
										$(".toggle_layer_emoticon").slideToggle("fast");
									});
									
									$(".icon_close").click(function () {
										$(this).parent().parent(".toggle_layer_wrap").slideToggle("fast")
									});
								</script>	
															
<div class="input_content_wrap">
  <table class="table table-bordered table-highlight-head table-checkable mt10" id="alimtalk_table" >
		<tbody>
		     <tr>
				  <th style="vertical-align:middle">쿠폰명<span class="required">*</span></th>
					<td value="" colspan="4" style="text-align: left;"><input class="form-control input-width-large required" id="id_cc_title" name="cc_title" size = "60" value="<?=$ftrs->cc_title?>" <? echo ($ftrs->cc_status=='P')?"readonly":""; ?>>  </td>
			 </tr>
		     <tr>
				  <th style="vertical-align:middle">시작일<span class="required">*</span></th>
					<td  colspan = "2"  style="text-align: left;">
					  <input class="form-control input-width-middle required" id="id_cc_start_date" name="cc_start_date" value="<?=$ftrs->cc_start_date?>" <? echo ($ftrs->cc_status=='P')?"readonly":""; ?>>  </td>
			 
				  <th style="vertical-align:middle">종료일<span class="required">*</span></th>
					<td   style="text-align: left;">
					  <input class="form-control input-width-middle required" id="id_cc_end_date" name="cc_end_date" value="<?=$ftrs->cc_end_date?>" <? echo ($ftrs->cc_status=='P')?"readonly":""; ?>>  </td>
			 </tr>
		     <tr>
				  <th style="vertical-align:middle">쿠폰 번호 생성<span class="required">*</span></th>
					<td  colspan="4" style="line-height:2.3;text-align: left;" >
						<div class="checks">
						<? if($rs->cc_status!='P') { ?>
						<input type="radio" id="auto" name="id_coupon_create" style="display:none" onclick="javascript:$('#id_upload_div').css('display', 'none'); $('#id_input_div').css('display', '')" value="A" <? echo ($ftrs->cc_method=='A' || empty($ftrs->cc_method)) ? 'checked':''; ?> ><label for="auto" style="display:none">자동 생성</label>
						<input type="radio" id="upload" name="id_coupon_create" style="display:none" onclick="javascript:$('#id_input_div').css('display', 'none'); $('#id_upload_div').css('display', '')" value="M" <? echo ($ftrs->cc_method=='M') ? 'checked':''; ?>><label for="upload" style="display:none">직접 올리기</label>
						<? } else { ?>
						    <input type="hidden" name="id_coupon_create" value="<?=$ftrs->cc_method?>">
						<?}?>
						</div>
						<div id="id_input_div" style="<? echo ($rs->cc_method=='A'|| empty($ftrs->cc_method)) ? '':'display:none;'; ?>">
							<input class="form-control input-width-small required " id="id_cc_coupon_qty" name="cc_coupon_qty" style="text-align:right" type="number" value="<?=$ftrs->cc_coupon_qty?>" max="10000" <? echo ($ftrs->cc_status=='P')?"readonly":""; ?> ><span> 개 </span>
						</div>
						<div id="id_upload_div" style="<? echo ($ftrs->cc_method=='M') ? '':'display:none;'; ?>">
                            <div class="widget-content">
                                <div class="row">
                                    <div class="col-xs-11 file">
                                        <input type="file" name="userfile" id="userfile" multiple="multiple" style="cursor: default; padding: 20px 20px 20px 20px !important;">
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $('#userfile').filestyle({
                                        input: true,
                                        buttonName: '',
                                        iconName: 'icon-folder-open',
                                        buttonText: '파일업로드'
                                    });
                                </script>
                                <style>
                                    .file input{
                                        cursor: default !important;
                                    }
                                    .file button{
                                        position: absolute;
                                        z-index: -1;
                                    }
                                </style>
                                <a herf="#" >업로드파일 양식 다운로드</a>
                                
                            </div>
						</div>  
					</td>
			 </tr>
		     <tr>
				  <th style="vertical-align:middle">당첨률<span class="required">*</span></th>
					<td  colspan = "4"  style="text-align: left;">
						<div class="checks">
					<? if($ftrs->cc_status!='P') { ?>
						<input type="radio" id="rate_01" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="100" <? echo ($ftrs->cc_rate == '100') ? "checked" : ""; ?> <? echo ($ftrs->cc_status=='P')?"disabled":""; ?>><label for="rate_01">100%(선착순)</label>
						<input type="radio" id="rate_02" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="75" <? echo ($ftrs->cc_rate == '75') ? "checked" : ""; ?> <? echo ($ftrs->cc_status=='P')?"disabled":""; ?>><label for="rate_02">75%</label>
						<input type="radio" id="rate_03" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="50" <? echo ($ftrs->cc_rate == '50') ? "checked" : ""; ?> <? echo ($ftrs->cc_status=='P')?"disabled":""; ?>><label for="rate_03">50%</label>
					  	<input type="radio" id="rate_04" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="25" <? echo ($ftrs->cc_rate == '25') ? "checked" : ""; ?> <? echo ($ftrs->cc_status=='P')?"disabled":""; ?>><label for="rate_04">25%</label>
					  	<input type="radio" id="rate_05" name="cc_rate" onclick="javascript:$('#rate_id').css('display', '')" value="0" <? echo ($ftrs->cc_rate == '0') ? "checked" : ""; ?> <? echo ($ftrs->cc_status=='P')?"disabled":""; ?>><label for="rate_05">직접입력 </label>
						</div>
					  	<div id="rate_id" style="<? echo ($ftrs->cc_rate == '0') ? "" : "display: none"; ?>">
						  	<input type="number" max="100" min="1" class="form-control input-width-small " id="rate" name="cc_rate_txt"  value="<? if($ftrs->cc_rate_txt<1 || $ftrs->cc_rate_txt>100) { echo 1; } else {echo $ftrs->cc_rate_txt;} ?>" <? echo ($ftrs->cc_status=='P')?"readonly":""; ?>>
					  	</div>  
					 <? } else {?>
					     <input type="number" max="100" min="1" class="form-control input-width-small " id="rate" name="cc_rate"  value="<?=$ftrs->cc_rate ?>" style="<? echo ($ftrs->cc_rate == '0') ? "display: none":""; ?>" readonly>
					     <input type="number" max="100" min="1" class="form-control input-width-small " id="rate_txt" name="cc_rate_txt"  value="<?=$ftrs->cc_rate_txt ?>" style="<? echo ($ftrs->cc_rate == '0') ? "" : "display: none"; ?>" readonly>
					 <? } ?>
					</td>
			 </tr>
		     <tr>
				  <th style="vertical-align:middle">당첨 페이지<BR> 이미지<span class="required">*</span></th>
				  
					<td value="" colspan = "4"  style="text-align: left;">
					<? if($ftrs->cc_status!='P') { ?>
							<button class="btn btn-sm" type="button"  onclick="imageSelect()" id="img_select">
							  <i class="icon-folder-open"></i>이미지 선택
							</button> 
							<? } ?>
							<input name="img_link" id="img_link" value="<?=$ftrs->cc_img_link?>"
							       class="form-control required" style="cursor:default;"
										  size="50" 
										  placeholder="이미지 링크 주소를 입력해주세요." readonly></td>
			 </tr>	
		     <tr>
				  <th style="vertical-align:middle">사용자<br>발급번호</th>
					<td value="" colspan="4" style="text-align: left;"><input class="form-control input-width-large required" id="id_cc_userbarcode" name="cc_user_barcode" size = "30" value="<?=$ftrs->cc_user_barcode?>" <? echo ($ftrs->cc_status=='P')?"readonly":""; ?>>  </td>
			 </tr>
		     <tr>
				  <th style="vertical-align:middle">당첨 페이지<BR> 텍스트</th>
				  
					<td value="" colspan = "4" style="text-align: left;" >
					<textarea name="cc_memo" id="id_cc_memo" cols="20" rows="10" class="form-control autosize" placeholder="당첨 페이지 텍스트를 입력 하세요" style="cursor:default;"
								value=""><?=$ftrs->cc_memo?></textarea>
					</td>
			 </tr>				 		 
		</tbody>
  </table>	
	</div>
  <script type="text/javascript">
	$(document).ready(function() {
 
		$("#id_cc_start_date, #id_cc_end_date").datepicker({
			format:'yyyy-mm-dd',
			todayBtn:"linked",
			language:"kr",
			autoclose:true,
			todayHighlight:true
		});		
	});
 
</script>
<?} else {?>
    <div class="content_wrap">
        <div class="col-xs-8">
            <div class="widget box mt20">
                <div class="widget-header">
                    <h4>발신프로필 선택</h4>
                </div>
                <div class="widget-content" id="send_friend_content" >
                    
    			<div class="alert alert-danger align-center">선택된 프로필이 없습니다. 하단 버튼을 눌러 선택해주세요.</div>




        <div class="align-center mt10">
            <button class="btn btn-sm btn-custom" type="button"
                    onclick="btnSelectProfile()" id="modalBtn">
                <i class="icon-ok"></i>프로필 선택
            </button>
        </div>

    </div>
<?}?>