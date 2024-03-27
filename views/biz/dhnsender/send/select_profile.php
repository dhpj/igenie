<? if($rs->spf_key){ ?>
<div class="input_content_wrap">
	<label class="input_tit">발신아이디</label>
	<div class="input_content">
		<input type="hidden" id="pf_ynm" value="<?=$rs->spf_company?>"/>
		<input type="hidden" id="pf_yid" value="<?=$rs->spf_friend?>"/>
		<input type="hidden" id="sms_sender" value="<?=$rs->spf_sms_callback?>"/>
		<input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->spf_key?>">
		<input type="hidden" name="kind" id="kind" value="<?=$rs->spf_key_type?>">
		<!-- 20190107 이수환 추가 작업중인 아디의 링크버튼 명을 입력-->
		<input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name" value="<?=$rs->mem_linkbtn_name?>">
		<input type="file" name="image_file" id="image_file" accept="image/*" value="upload" style="display: none;" class="upload-hidden" accept="image/jpeg, image/png" onchange="img_size();">
		<select style="width: 400px;">
		<?
		foreach($profile as $row)  {
			if ($row->spf_key === $rs->spf_key) {
		?>
			<option value="<?=$row->spf_key ?>" selected="selected"><?=$row->spf_company ?></option>
		<?  } else { ?>
			<option value="<?=$row->spf_key ?>"><?=$row->spf_company ?></option>
		<?  }
		} ?>
		</select>
		<!-- <button class="btn md fr">계정관리</button> -->
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
			<div class="preview_option flex">
				<button class="btn_preview_option" onclick="msg_save('SVAE');">내용저장</button>
				<button class="btn_preview_option" onclick="open_msg_lms_list();">불러오기</button>
			</div>
		</div>
		<div class="preview_home"></div>
	</div><!-- mobile_preview END -->
</div><!-- input_content_wrap END -->

<div class="input_content_wrap">
	<label class="input_tit" id="id_STEP1">
			<p class="txt_st_eng">STEP 1.</p>
			<p>친구톡 이미지 선택</p>
	</label>
	<div class="input_content">
		<!-- <label class="file" title=""><input type="file" onchange="this.parentNode.setAttribute('title', this.value.replace(/^.*[\\/]/, ''))" /></label>  -->
		<button class="btn_add btn_myModal" onClick="image_list_open(1);">친구톡 이미지 관리</button>
		<p class="noted">* 친구톡 이미지를 등록, 삭제, 선택하실 수 있습니다.</p>
		<!-- The Modal -->
		<div id="image_list_modal" class="dh_modal">
		  <!-- Modal content -->
		  <div class="modal-content md_width970">
			  <span class="dh_close" onClick="image_list_close();">&times;</span>
			  <p class="modal-tit t_al_left">친구톡 이미지 관리</p>
				<button class="btn_friimg" type="button" onclick="javascript:click_bntimage()"><i class="material-icons">insert_photo</i> 이미지추가</button>
				<p class="modal_info">
					* 이미지 권장 사이즈 : <span>720px X 720px</span> / 지원 파일형식 및 크기 : <span>jpg, png</span> / 최대용량 : <span>500KB</span>
					<br  />* 이미지 제한 사이즈 : 가로 500px 미만, 가로:세로 비율이 2:1 미만, 3:4 초과시 업로드 불가
					<br  />* 이미지 편집이 어려우실 경우 <span>[알씨]를 설치</span>(<a href="https://www.altools.co.kr" target="_blank">https://www.altools.co.kr</a>)하거나 <span>이미지 무료편집사이트</span>(<a href="https://www.iloveimg.com/ko" target="_blank">https://www.iloveimg.com/ko</a>)를 이용해보세요~
				</p>
				<ul class="frided_imglist" id="image_list_content"><? //친구톡 이미지 리스트 ?>
				</ul>
				<div class="page_cen" id="image_list_pagination" style="margin-top:10px;"><? //친구톡 이미지 페이징 ?>
				</div>
			  </div>
		</div>
		<!--<button class="btn md fr" onclick="imageSelect()" id="img_select" style="margin-left: -1px;"><i class="icon-folder-open"></i>이미지 선택</button>-->
		<button class="btn md fr" onclick="image_list_open(1);" id="img_select" style="margin-left: -1px;"><i class="icon-folder-open"></i>이미지 선택</button>
		<button class="btn md fr" onclick="deleteImageSelect()" id="img_select_delete" style="margin-left:-1px; display: none"><i class="icon-folder-open"></i>이미지 제거</button>
		<div style="overflow: hidden;">
			<input type="hidden" name="img_url" id="img_url"/>
			<input type="text" name="img_link" id="img_link" onchange="scroll_prevent();" style="width: 100%;" placeholder="이미지 링크 주소를 입력해주세요." readonly>
		</div>
	</div>
</div><!-- input_content_wrap END -->
<div class="input_content_wrap">
	<label class="input_tit" id="id_STEP2">
			<p class="txt_st_eng">STEP 2.</p>
			<p>발송내용 입력</p>
	</label>
	<div class="input_content form_check">
		<div class="msg_box" style="margin-bottom:10px;">
			<?php // 친구톡 내용 헤드 수정 2019-07-25 ?>
			<!-- div class="txt_ad" id="cont_header"></div-->
			<div class="txt_ad" id="cont_header">
				<span id="span_ft_adv">(광고)</span>
				<input type="text" class="input_option" style="width: 320px;" id="ft_companyName" onkeyup="onPreviewText(); chkword_templi(); keyup_cont_header();" value="<?=$rs->spf_company?>">
			</div>
			<textarea class="input_msg" id="templi_cont" placeholder="메세지를 입력해주세요." onkeyup="onPreviewText();chkword_templi();"></textarea>
			<span class="txt_byte" id="templi_length"><span id="type_num">0</span>/1,000 자</span>
			<div class="txt_ad" id="cont_footer"></div>
		</div>
		<input type="checkbox" id="cont_homepagelink_select" onclick="insert_homepage_link(this);" style="cursor: default;"><label for="cont_homepagelink_select">주소 자동삽입</label>
		<button class="btn md btn_emoticon fr" style="margin-left:10px;">카카오 이모티콘</button>
		<button class="btn md fr btn_emoji_kakao fr">특수문자</button>
		<div class="clearfix"></div>
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
					<li onclick="insert_char('(콜)')"><img src="/images/kakaoimg/kakaoimg_082.png" title="콜" alt="콜"></li>
				</ul>
			</div>
		</div>
	</div>
</div><!-- input_content_wrap END -->
<div class="input_content_wrap">
	<label class="input_tit" id="id_STEP3">
			<p class="txt_st_eng">STEP 3.</p>
			<p>버튼내용 선택</p>
	</label>
	<div class="input_content">
		<button class="btn_add" onclick="add_info('')" id="btn_add">링크버튼 추가하기</button>
		<div id="pre_set" last-id="0" style="display: none;">
			<div class="link_info_wrap">
				<div class="input_link_wrap">
					<select style="width: 120px;" class="fl"  name="btn_type" id="btn_type0" onchange='modify_btn_type(0);link_name(this,0);'>
						<option value='WL'>웹링크</option>
						<option value='AL'>앱링크</option>
						<option value='BK'>봇키워드</option>
						<option value='MD'>메시지전달</option>
					</select>
					<div class="delete_box fr" ><button class="btn dark btn_minus" style="margin-left: -2px" onclick="delete_info(this)"></button></div>
					<div style="overflow: hidden;" id="btn_web_like_"><input type="text" name="btn_name2" id="btn_name2_" style="width:100%; margin-left: -1px;" placeholder="버튼명을 입력해주세요" onkeyup="link_name(this,0);scroll_prevent();"></div>
					<div style="overflow: hidden; display: none" id="btn_app_like_"><input type="text" name="btn_name3"  id="btn_name3_" style="width:100%; margin-left: -1px;" placeholder="버튼명을 입력해주세요" onkeyup="link_name(this,0);scroll_prevent();"></div>
					<div style="overflow: hidden; display: none" id="btn_bots_like_"><input type="text" name="btn_name4" id="btn_name4_" style="width:100%; margin-left: -1px;" placeholder="버튼명을 입력해주세요" onkeyup="link_name(this,0);scroll_prevent();"></div>
					<div style="overflow: hidden; display: none" id="btn_msg_like_"><input type="text" name="btn_name5" id="btn_name5_" style="width:100%; margin-left: -1px;" placeholder="버튼명을 입력해주세요" onkeyup="link_name(this,0);scroll_prevent();"></div>
				</div>
				<div class="input_link_wrap" id="web_like_">
					<button class="btn md fr" style="margin-left: -1px;" id="find_url" onclick="urlConfirm(this)">링크확인</button>
					<div style="overflow: hidden;">
					<input type="url" name="btn_url21" style="width:100%;" placeholder="링크주소를 입력해주세요." onkeyup= "">
					</div>
				</div>
				<div class="input_link_wrap" id="pc_web_like_" style="display: none">
					<button class="btn md fr" style="margin-left: -1px;" id="pc_find_url" onclick="urlConfirm(this)">링크확인</button>
					<div style="overflow: hidden;">
					<input type="url" name="btn_url22" style="width:100%;" placeholder="링크주소를 입력해주세요.">
					</div>
				</div>
				<div class="input_link_wrap" id="app_like_android_" style="display: none">
					<button class="btn md fr" style="margin-left: -1px;" id="android_find_url" onclick="urlConfirm(this)">링크확인</button>
					<div style="overflow: hidden;">
					<input type="url" name="btn_url31" style="width:100%;" placeholder="Android App 링크주소를 입력해주세요.">
					</div>
				</div>
				<div class="input_link_wrap" id="app_like_ios_" style="display: none">
					<button class="btn md fr" style="margin-left: -1px;" id="iso_find_url" onclick="urlConfirm(this)">링크확인</button>
					<div style="overflow: hidden;">
					<input type="url" name="btn_url32" style="width:100%;" placeholder="iOS App 링크주소를 입력해주세요.">
					</div>
				</div>
			</div>
			<div class="selection_content"></div>
		</div>
		<div id="field"></div>
	</div>
</div><!-- input_content_wrap END -->
<!-- 버튼 추가 스크립트 -->
<script type="text/javascript">
//카카오톡 이미지 모달창 오픈
function image_list_open(page){
	//카카오톡 이미지 리스트 조회
	$.ajax({
		url: "/dhnbiz/sender/send/friend/ajax_image_list",
		type: "POST",
		data: {"per_page" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
		success: function (json){
			var html = "";
			//alert("json.page_html : "+ json.page_html);
			$.each(json, function(key, value){
				var img_id = value.img_id; //이미지 일련번호
				var img_link = "http://<?=$_SERVER['HTTP_HOST']?>/pop/"+ value.img_filename; //이미지 파일명
				var img_url = value.img_url; //카카오 URL
				var del_url = encodeURI(value.img_filename +"|"+ img_id);
				if(img_id != undefined && img_url != undefined){
					html += "<li>";
					html += "	<p class=\"tem_img\" style=\"background-image: url('"+ img_url +"');cursor:pointer;\" onClick=\"image_choose('"+ img_url +"', '"+ img_link +"');\"></p>";
					html += "	<p class=\"tem_text\">";
					html += "	 <button type=\"button\" onClick=\"image_choose('"+ img_url +"', '"+ img_link +"');\"><i class=\"material-icons\">add</i>이미지선택</button>";
					html += "	 <button type=\"button\" onClick=\"delete_image('"+ del_url +"');\"><i class=\"material-icons\">remove</i>이미지삭제</button>";
					html += "	</p>";
					html += "</li>";
				}
			});
			$("#image_list_content").html(html);
			$("#image_list_pagination").html(json.page_html);
			//$("#image_list_pagination").html("<ul class=\"pagination\"><li class=\"active\"><a>1</a></li><li><a href=\"javascript:void(image_list_open('2'));\">2</a></li><li><a href=\"javascript:void(image_list_open('2'));\">&gt;</a></li></ul>");
		}
	});
	document.getElementById("image_list_modal").style.display = "block";
}

//카카오톡 이미지 모달창 > 이미지 선택
function image_choose(img_url, img_link){
	//alert("img_url : "+ img_url +"\n"+ "img_link : "+ img_link);
	setImageValue(img_url, img_link);
	document.getElementById("img_select").style.display = "none"; //이미지 선택 버튼
	image_list_close();
}

//카카오톡 이미지 모달창 닫기
function image_list_close(){
	document.getElementById("image_list_modal").style.display = "none";
}

//카카오톡 이미지 모달창 닫기
window.onclick = function(event) {
	if (event.target == document.getElementById("image_list_modal")) {
		document.getElementById("image_list_modal").style.display = "none";
	}
}

//이미지 업로드 버튼 클릭
function click_bntimage() {
	$("#image_file").trigger("click");
}

//카카오톡 이미지 추가시 체크
function img_size(){
	var img_file = $("#image_file")[0].files[0];
	var _URL = window.URL || window.webkitURL;
	var img = new Image();
	img.src = _URL.createObjectURL(img_file);
	img.onload = function() {
		var img_width = img.width;
		var img_height = img.height;
		var img_check = true;
		if (img_width/img_height < 0.75 && img_width/img_height > 2) { //이미지 사이즈 체크
			img_check = false;
		}
		upload(img_check);
	};
}

//파일 용량 및 확장자 확인 ->500KB제한
function upload(check) {
	var thumbext = document.getElementById('image_file').value;
	var file_data = new FormData();
	file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
	file_data.append("image_file", $("input[name=image_file]")[0].files[0]);
	file_data.append("img_wide", "N"); //이미지 구분(N.친구톡, W.와이드친구톡, C.쿠폰)
	thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();
	if (thumbext) {
		showSnackbar("처리중입니다. 잠시만 기다려 주세요.", 1500); //1.5초
		$.ajax({
			url: "/biz/sender/image_upload",
			type: "POST",
			data: file_data,
			processData: false,
			contentType: false,
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				showResult(json);
			},
			error: function (data, status, er) {
				//$(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
				//$("#myModal").modal('show');
				alert("처리중 오류가 발생했습니다.");return;
			}
		});
		function showResult(json) {
			//alert("code : "+ json['code']);
			if (json['code'] == 'success') {
				//window.location.href = '/biz/sender/image_w_list';
				image_list_open(1);
			} else {
				var message = json['message'];
				//console.log(message);
				//alert("message : "+ message);
				var messagekr = '';
				if (message == 'UnknownException') {
					messagekr = '관리자에게 문의하십시오';
				} else if (message == 'InvalidImageMaxLengthException') {
					messagekr = '이미지 용량을 초과하였습니다 (최대500KB)';
				} else if (message == 'InvalidImageSizeException') {
					messagekr = '가로 500px 미만 또는 가로*로 비율이 1:1.5 초과시 업로드 불가합니다';
				} else if (message == 'InvalidImageFormatException') {
					messagekr = '지원하지 않는 이미지 형식입니다 (jpg,png만 가능)';
				} else {
					if(message.indexOf("FailedToUploadImageException") != -1) {
						message = "발송할 수 없는 이미지 크기 입니다.\n가로:세로 비율은 2:1 이상 또는 3:4 이하여야 합니다.";
					}
					//messagekr = '관리자에게 문의하십시오.(' + message + ")";
					messagekr = message;
				}
				var text = '이미지 업로드에 실패하였습니다' + '\n\n' + messagekr;
				//$(".content").html(text.replace(/\n/g, "<br/>"));
				//$("#myModal").modal('show');
				//$(document).unbind("keyup").keyup(function (e) {
				//	var code = e.which;
				//	if (code == 13) {
				//		$(".btn-primary").click();
				//	}
				//});
				alert(text);return;
			}
		}
	}
}

//카카오톡 이미지 삭제
function delete_image(image) {
	if(!confirm("삭제 하시겠습니까?")){
		return;
	}
	var obj_image_name = [];
	obj_image_name.push(image);
	$.ajax({
		url: "/biz/sender/image_delete",
		type: "POST",
		data: {
			<?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
			image_name: JSON.stringify({image_name: obj_image_name}),
			count: 1
		},
		beforeSend: function () {
			$('#overlay').fadeIn();
		},
		complete: function () {
			$('#overlay').fadeOut();
		},
		success: function (json) {
			//showResult(json);
			showSnackbar("삭제 되었습니다.", 1500); //1.5초
			image_list_open(1);
		},
		error: function (data, status, er) {
			//$(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오.");
			//$("#myModal").modal('show');
			alert("처리중 오류가 발생했습니다.");return;
		}
	});
}

function add_info(x) {
	// 원본 찾아서 pre_set으로 저장.
	var pre_set = document.getElementById('pre_set');
	// last-id 속성에서 필드ID르 쓸값 찾고
	var fieldid = parseInt(pre_set.getAttribute('last-id'));
	if ( fieldid < 5) {	// 5개만 생성
		// 다음에 필드ID가 중복되지 않도록 1 증가.
		pre_set.setAttribute('last-id', fieldid + 1 );

		// 복사할 div 엘리먼트 생성
		var div = document.createElement('div');

		var pre_set_innerHTML = pre_set.innerHTML;
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_type0\"", ("\"btn_type" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("onchange=\"modify_btn_type(0);link_name(this,0);\"", "onchange=\"modify_btn_type(" + (fieldid + 1) + ");link_name(this," + (fieldid + 1) + ");\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_web_like_\"", ("\"btn_web_like_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name2_\"", ("\"btn_name2_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_app_like_\"", ("\"btn_app_like_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name3_\"", ("\"btn_name3_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_bots_like_\"", ("\"btn_bots_like_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name4_\"", ("\"btn_name4_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_msg_like_\"", ("\"btn_msg_like_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name5_\"", ("\"btn_name5_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"pc_web_like_\"", ("\"pc_web_like_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"web_like_\"", ("\"web_like_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"app_like_android_\"", ("\"app_like_android_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace("\"app_like_ios_\"", ("\"app_like_ios_" + (fieldid + 1)) + "\"");
		pre_set_innerHTML = pre_set_innerHTML.replace(/onkeyup=\"link_name\(this,0\);scroll_prevent\(\);\"/g, "onkeyup=\"link_name(this," + (fieldid + 1) + ");scroll_prevent();\"");

		// 내용 복사
		//div.innerHTML = pre_set.innerHTML;
		div.innerHTML = pre_set_innerHTML;
		// 복사된 엘리먼트의 id를 'field-data-XX'가 되도록 지정.
		div.id = 'field-data-' + fieldid;
		div.setAttribute('name', "field-data");
		// selection_content 영역에 내용 변경.
		var temp = div.getElementsByClassName('selection_content')[0];
		temp.innerText = x;

		// delete_box에 삭제할 fieldid 정보 건네기
		var deleteBox = div.getElementsByClassName('delete_box')[0];
		// target이라는 속성에 삭제할 div id 저장
		deleteBox.setAttribute('target',div.id);
		// #field에 복사한 div 추가.
		document.getElementById('field').appendChild(div);
		link_name(document.getElementById("btn_type" + (fieldid + 1)),(fieldid + 1));
	}
}

function delete_info(obj) {
	var pre_set = document.getElementById('pre_set');
	var fieldid = parseInt(pre_set.getAttribute('last-id'));

	// 삭제할 ID 정보 찾기
	var target = obj.parentNode.getAttribute('target');
	//alert(target);
	// 삭제할 element 찾기
	var field = document.getElementById(target);
	// filedId 검색 및 마지막 숫자만 분리
	var filedId = field.id;
	var fieldIdNo = parseInt(filedId.substring(filedId.lastIndexOf("-") + 1));


	// #field 에서 삭제할 element 제거하기
	document.getElementById('field').removeChild(field);
	$("#btn_preview_div" + (fieldIdNo + 1)).remove();

	for (var i = (fieldIdNo + 1); i < fieldid; i++) {
		var rowTargetId = "field-data-" + i;
		var rowTarget = document.getElementById(rowTargetId);

		modFieldIdNo = i - 1;
		rowTarget.id = "field-data-" + modFieldIdNo;

		//var preViewTargetId = "btn_preview_div" + (i + 1);
		var preViewTarget = document.getElementById("btn_preview_div" + (i + 1));
		preViewTarget.id = "btn_preview_div" + i;

		var btnRreViewTarget = document.getElementById("btn_preview" + (i + 1));
		btnRreViewTarget.id = "btn_preview" + i;


		// delete_box에 삭제할 fieldid 정보 건네기
		var deleteBox = rowTarget.getElementsByClassName('delete_box')[0];
		// target이라는 속성에 삭제할 div id 저장
		deleteBox.setAttribute('target',rowTarget.id);

		var rowTargetHtml = rowTarget.innerHTML;
		//alert(rowTargetHtml);
		rowTargetHtml = rowTargetHtml.replace("\"btn_type" + (i + 1) + "\"", ("\"btn_type" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_web_like_" + (i + 1) + "\"", ("\"btn_web_like_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_name2_" + (i + 1) + "\"", ("\"btn_name2_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_app_like_" + (i + 1) + "\"", ("\"btn_app_like_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_name3_" + (i + 1) + "\"", ("\"btn_name3_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_bots_like_" + (i + 1) + "\"", ("\"btn_bots_like_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_name4_" + (i + 1) + "\"", ("\"btn_name4_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_msg_like_" + (i + 1) + "\"", ("\"btn_msg_like_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"btn_name5_" + (i + 1) + "\"", ("\"btn_name5_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"pc_web_like_" + (i + 1) + "\"", ("\"pc_web_like_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"web_like_" + (i + 1) + "\"", ("\"web_like_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"app_like_android_" + (i + 1) + "\"", ("\"app_like_android_" + i) + "\"");
		rowTargetHtml = rowTargetHtml.replace("\"app_like_ios_" + (i + 1) + "\"", ("\"app_like_ios_" + i) + "\"");
		//alert(rowTargetHtml);
		rowTarget.innerHTML = rowTargetHtml;
	}

	fieldid -= 1;
	pre_set.setAttribute('last-id', fieldid);
}
</script>

<div class="input_content_wrap">
	<label class="input_tit">2차발송</label>
	<div class="input_content">
		<?
		if($sendtelauth > 0) { ?>
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_talk" class="trigger" id="lms_select"><i></i></label>
		<span class="noted">*친구톡 실패건을 알림톡 또는 문자(SMS/LMS)로 재발송합니다.</span>
		<div id="div_second_type" class="checks" style="display: none;">
			<input type="radio" name="second_type" value="at" id="second_type_at" onClick="chg_second_type();" checked><label for="second_type_at">알림톡발송</label>
			<input type="radio" name="second_type" value="ms" id="second_type_ms" onClick="chg_second_type();"><label for="second_type_ms" style="margin-left: -6px;">문자발송</label>
		</div>
		<?
		} else {
			if ($mem->mem_2nd_send=='NONE' || $mem->mem_2nd_send=='') {
		?>
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
		<span class="info_text">*2차 발신이 설정되지 않았습니다. 관리자에게 문의 하세요.</span>
		<?
			} else if ($sendtelauth == 0) { ?>
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
		<span class="info_text">*발신번호가 등록되지 않아 2차 발신은 할 수 없습니다.</span>
		<?
			} else { ?>
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
		<span class="info_text">*발신번호가 등록 및 2차 발신이 설정되지 않았습니다.</span>
		<?
			}
		} ?>
		<div class="switch_content" id="hidden_fields_sms" style="display: none;">
			<!-- 메시지 미리보기 -->
			<div class="sms_preview toggle">
				<div class="msg_type lms" id="preview_msg_type"></div>
				<div class="preview_circle"></div>
				<div class="preview_round"></div>
				<div class="preview_msg_wrap">
					<div class="preview_msg_window lms" id="lms_preview"></div>
				</div>
				<div class="preview_home"></div>
			</div><!-- mobile_preview END -->
			<div class="msg_box">
				<?php // 문자 내용 헤드, 풋 수정 2019-07-25 ?>
				<!-- div class="txt_ad" id="lms_header"></div-->
				<div class="txt_ad" id="lms_header">
					<span id="span_adv">(광고)</span>
					<input type="text" class="input_option" style="width: 320px;" id="companyName" onkeyup="onPreviewLms(); chkword_lms(); keyup_lms_header()" value="<?=$rs->spf_company?>">
				</div>
				<textarea class="input_msg" id="lms" name="lms" placeholder="'입력' 또는 '친구톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"></textarea>
				<span class="txt_byte"><span class="msg_type_txt lms" id="lms_limit_str">SMS</span><span id="lms_num">0</span>/<span id="lms_character_limit">80</span> bytes</span>
				<!-- div class="txt_ad" id="lms_footer"></div-->
				<div class="txt_ad" id="lms_footer">
					<p id="kakotalk_link_text"></p>
					<span id="span_unsubscribe">무료수신거부 : </span>
					<input type="text" class="input_option" id="unsubscribe_num" onkeyup="onPreviewLms(); chkword_lms();">
				</div>
				<input type="hidden" name="tit" id="tit">
			</div>
			<input type="checkbox" id="lms_kakaolink_select" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select">카카오 친구추가 주소 삽입</label>
			<button class="btn md fr" style="margin-left:10px;" onclick="getKakaoCont();">친구톡내용 가져오기</button>
			<button class="btn md fr btn_emoji">특수문자</button>

			<div class="toggle_layer_wrap toggle_layer_emoji">
				<h3>특수문자<i class="material-icons icon_close fr" id="icon_close">close</i></h3>
				<div class="layer_content emoticon_wrap clearfix">
					<ul>
						<li onclick="insert_char('☆', 'lms')">☆</li>
						<li onclick="insert_char('★', 'lms')">★</li>
						<li onclick="insert_char('○', 'lms')">○</li>
						<li onclick="insert_char('●', 'lms')">●</li>
						<li onclick="insert_char('◎', 'lms')">◎</li>
						<li onclick="insert_char('⊙', 'lms')">⊙</li>
						<li onclick="insert_char('◇', 'lms')">◇</li>
						<li onclick="insert_char('◆', 'lms')">◆</li>
						<li onclick="insert_char('□', 'lms')">□</li>
						<li onclick="insert_char('■', 'lms')">■</li>
						<li onclick="insert_char('▣', 'lms')">▣</li>
						<li onclick="insert_char('△', 'lms')">△</li>
						<li onclick="insert_char('▲', 'lms')">▲</li>
						<li onclick="insert_char('▽', 'lms')">▽</li>
						<li onclick="insert_char('▼', 'lms')">▼</li>
						<li onclick="insert_char('◁', 'lms')">◁</li>
						<li onclick="insert_char('◀', 'lms')">◀</li>
						<li onclick="insert_char('▷', 'lms')">▷</li>
						<li onclick="insert_char('▶', 'lms')">▶</li>
						<li onclick="insert_char('♡', 'lms')">♡</li>
						<li onclick="insert_char('♥', 'lms')">♥</li>
						<li onclick="insert_char('♧', 'lms')">♧</li>
						<li onclick="insert_char('♣', 'lms')">♣</li>
						<li onclick="insert_char('◐', 'lms')">◐</li>
						<li onclick="insert_char('◑', 'lms')">◑</li>
						<li onclick="insert_char('▦', 'lms')">▦</li>
						<li onclick="insert_char('☎', 'lms')">☎</li>
						<li onclick="insert_char('♪', 'lms')">♪</li>
						<li onclick="insert_char('♬', 'lms')">♬</li>
						<li onclick="insert_char('※', 'lms')">※</li>
						<li onclick="insert_char('＃', 'lms')">＃</li>
						<li onclick="insert_char('＠', 'lms')">＠</li>
						<li onclick="insert_char('＆', 'lms')">＆</li>
						<li onclick="insert_char('㉿', 'lms')">㉿</li>
						<li onclick="insert_char('™', 'lms')">™</li>
						<li onclick="insert_char('℡', 'lms')">℡</li>
						<li onclick="insert_char('Σ', 'lms')">Σ</li>
						<li onclick="insert_char('Δ', 'lms')">Δ</li>
						<li onclick="insert_char('♂', 'lms')">♂</li>
						<li onclick="insert_char('♀', 'lms')">♀</li>
					</ul>
				</div>
				<div class="layer_content emoticon_wrap_multi clearfix">
					<ul>
						<li onclick="insert_char('^0^', 'lms')">^0^</li>
						<li onclick="insert_char('☆(~.^)/', 'lms')">☆(~.^)/</li>
						<li onclick="insert_char('づ^0^)づ', 'lms')">づ^0^)づ</li>
						<li onclick="insert_char('(*^.^)♂', 'lms')">(*^.^)♂</li>
						<li onclick="insert_char('(o^^)o', 'lms')">(o^^)o</li>
						<li onclick="insert_char('o(^^o)', 'lms')">o(^^o)</li>
						<li onclick="insert_char('=◑.◐=', 'lms')">=◑.◐=</li>
						<li onclick="insert_char('_(≥∇≤)ノ', 'lms')">_(≥∇≤)ノ</li>
						<li onclick="insert_char('q⊙.⊙p', 'lms')">q⊙.⊙p</li>
						<li onclick="insert_char('^.^', 'lms')">^.^</li>
						<li onclick="insert_char('(^.^)V', 'lms')">(^.^)V</li>
						<li onclick="insert_char('*^^*', 'lms')">*^^*</li>
						<li onclick="insert_char('^o^~~♬', 'lms')">^o^~~♬</li>
						<li onclick="insert_char('^.~', 'lms')">^.~</li>
						<li onclick="insert_char('S(*^___^*)S', 'lms')">S(*^___^*)S</li>
						<li onclick="insert_char('^Δ^', 'lms')">^Δ^</li>
						<li onclick="insert_char('＼(*^▽^*)ノ', 'lms')">＼(*^▽^*)ノ</li>
						<li onclick="insert_char('^L^', 'lms')">^L^</li>
						<li onclick="insert_char('^ε^', 'lms')">^ε^</li>
						<li onclick="insert_char('^_^', 'lms')">^_^</li>
						<li onclick="insert_char('(ノ^O^)ノ', 'lms')">(ノ^O^)ノ</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="switch_content" id="hidden_fields_talk" style="display: none;">알림톡 템플릿 영역<?//알림톡 템플릿 영역?>
		</div>
	</div>
</div><!-- input_content_wrap END -->
<!-- <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> -->
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
<? } else { //if($rs->spf_key){ ?>
<div class="input_content_wrap">
	<label class="input_tit">발신프로필 선택</label>
	<div class="input_content" id="send_friend_content" >
	<div class="alert alert-danger" style="text-align:center;">
		<!--
		<p style="margin-bottom:10px;">등록된 발신프로필이 없습니다. 발신프로필 등록 후 사용가능합니다.</p>
		<button class="btn md plus" onclick="location.href='/dhnbiz/sendprofile/write'">발신프로필 등록하기</button>
		-->
		<p style="margin-bottom: 0px;">등록된 발신프로필이 없습니다. 관리자에게 문의 하세요.</p>
	</div>
</div>
<? } //if($rs->spf_key){ ?>
