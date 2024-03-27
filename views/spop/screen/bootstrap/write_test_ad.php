<?
	$step1_std = 2; //STEP1.할인&대표상품 기본 상품수
	$step2_std = 4; //STEP2.2단 이미지형 기본 상품수
	$step3_std = 5; //STEP3.1단 텍스트형 기본 상품수
	$step4_std = 6; //STEP4.3단 이미지형 기본 상품수
	$step5_std = 4; //STEP5.4단 이미지형 기본 상품수
	$title_img_imgpath = "/dhn/images/leaflets/title/type4/02.jpg"; //이미지형 타이틀 1번째 이미지
	$title_text_imgpath = "/dhn/images/leaflets/title/type4/02.jpg"; //텍스트형 타이틀 1번째 이미지
	$title_event_imgpath = "/uploads/design_title/test_images.png"; //행사이미지 타이틀
	$sample_imgpath = "/dhn/images/leaflets/sample_img.jpg"; //행사이미지 타이틀
	$sale_badge_imgpath = "/dhn/images/sale_badge_bg.jpg"; //할일율  뱃지 기본 이미지
	$goods_sample_path = "/uploads/sample/goods_sample.xlsx?v=". date("ymdHis"); //엑셀 샘플 다운로드 파일경로
	$goods_box_cnt = 0; //상품 박스 갯수
	$psd_id = ($org_id != "") ? $org_id : $screen_data->psd_id; //전단번호
	$psd_title = $screen_data->psd_title; //행사제목
	$psd_date = $screen_data->psd_date; //행사기간
	$psd_tem_id = $screen_data->psd_tem_id; //템플릿번호
	$psd_step1_yn = $screen_data->psd_step1_yn; //할인&대표상품 등록 사용여부
	$psd_viewyn = ($screen_data->psd_viewyn != "") ? $screen_data->psd_viewyn : "Y"; //스마트전단 샘플 뷰 (Y/N)
	$psd_ver_no = ($screen_data->psd_ver_no != "") ? $screen_data->psd_ver_no : 2; //버전번호
	$template_self_bgcolor = "#d9d9d9"; //템플릿 직접입력 배경색
	$mem_bigpos_yn = $this->member->item("mem_bigpos_yn"); //빅포스 사용여부
	$tit_text_info_first_i = ""; // 스마트 전단 메뉴명 초기값(이미지)
	$tit_text_info_first_t = ""; // 스마트 전단 메뉴명 초가값(텍스트)
	$pst_tit_text = ""; //전단 텍스트 정보
	$ex_tit_text_info = ""; // 기존 전단 수정시 텍스트정보 담을 그릇;
	if($psd_ver_no == "1"){ //버전1 & 테스트의 경우
		$psd_ver_no = 2;
	}


	//echo "psd_id : ". $psd_id .", add : ". $add ."<br>";
	$test_yn = "N"; //테스트여부
	if($psd_id == "" and $add == "_test"){ //테스트의 경우
		//$test_yn = "Y";
	}
	if($test_yn == "Y"){ //테스트의 경우
		$psd_title = "Test ". date("y.m.d H:i:s"); //행사제목
		$psd_date = "기간 ".date("n/j") ." ~ ". date("n/j", strtotime(date("Y-m-d") ." +7 day")); //행사기간
		$psd_tem_id = "78"; //템플릿번호
		//$psd_step1_yn = "N"; //할인&대표상품 등록 사용여부
		$psd_viewyn = "N"; //스마트전단 샘플 뷰 (Y/N)
	}

	$userAgent = $_SERVER['HTTP_USER_AGENT']; //user agent 확인
	$ieYn = $this->funn->fnIeYn($userAgent); //익스플로러 여부
	//$ieYn = "Y";
	//echo "userAgent : ". $userAgent ."<br>";
	//echo "ieYn : ". $ieYn ."<br>";

	//회원정보 > 스마트전단 주문하기 사용여부
	$mem_stmall_yn = $this->member->item("mem_stmall_yn"); //스마트전단 주문하기 사용여부
	//$mem_stmall_yn = "N"; //스마트전단 주문하기 사용여부
	//echo "mem_stmall_yn : ". $mem_stmall_yn ."<br>";
	if($screen_data->psd_id != ""){ //수정의 경우
		$psd_order_yn = $screen_data->psd_order_yn; //주문하기 사용여부
		$psd_order_sdt = ($screen_data->psd_order_sdt != "") ? date("Y.m.d", strtotime($screen_data->psd_order_sdt)) : ""; //주문하기 시작일자
		$psd_order_edt = ($screen_data->psd_order_edt != "") ? date("Y.m.d", strtotime($screen_data->psd_order_edt)) : ""; //주문하기 종료일자
        $psd_order_sh = (!empty((string)$screen_data->psd_order_st)) ? (string)substr($screen_data->psd_order_st, 0, 2) : "00"; //주문하기 시작시간
        $psd_order_eh = (!empty((string)$screen_data->psd_order_et)) ? (string)substr($screen_data->psd_order_et, 0, 2) : "00"; //주문하기 종료시간
        $psd_order_sm = (!empty((string)$screen_data->psd_order_st)) ? (string)substr($screen_data->psd_order_st, 2, 2) : "00"; //주문하기 시작분
        $psd_order_em = (!empty((string)$screen_data->psd_order_et)) ? (string)substr($screen_data->psd_order_et, 2, 2) : "00"; //주문하기 종료분
	}else{ //등록의 경우
		$psd_order_yn = $mem_stmall_yn; //주문하기 사용여부
        $psd_order_sh = "00"; //주문하기 시작시간
        $psd_order_eh = "00"; //주문하기 종료시간
        $psd_order_sm = "00"; //주문하기 시작시간
        $psd_order_em = "00"; //주문하기 종료시간
	}
	//$psd_order_yn = "N"; //주문하기 사용여부
	//echo "psd_order_yn : ". $psd_order_yn ."<br>";




	//장바구니 버튼 설정
	$button_add_cart = "";

	if($mem_stmall_yn == "Y"){
		if($psd_order_yn == "Y"){
			if($button_soldout=="N"){
				$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
			}else{
				$button_add_cart = "<button class='icon_add_cart'>장바구니</button>";
			}
		}else{
			$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
		}
	}
	//button_add('Y');

	function button_add($ynstr){
		if($mem_stmall_yn == "Y"){
			if($psd_order_yn == "Y"){
				if($ynstr=="N"){
					$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
				}else{
					$button_add_cart = "<button class='icon_add_cart'>장바구니</button>";
				}
			}else{
				$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
			}
		}
	}
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<link rel="stylesheet" type="text/css" href="/views/spop/screen/bootstrap/css/style<?=($screen_data->psd_font_LN=="N")? "_n" : "" ?>.css?v=<?=date("ymdHis")?>" title="csstypes" />
<!-- <script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script> -->

<script type="text/javascript">
	var psd_id = '<?=$psd_id?>';
	var psd_cnt = '<?=$psd_cnt?>';
	var temp_psd_cnt = '<?=$temp_psd_cnt?>';
	var isChange = false;
    var isSample = false;
	var prebox = '';
	var isisall = false;
	//console.log(psd_id+" - "+psd_cnt+" - "+temp_psd_cnt+" - "+temp_psd_cnt+" - "+latest_date+" - "+temp_cnt);
	$(document).ready(function(){
		if(psd_id!=''){
            if(psd_cnt!=''){
                if(psd_cnt==0){
    				if(temp_psd_cnt>0){
    					$.ajax({
    						url: "/spop/screen/smart_temp_save",
    						type: "POST",
    						async: false,
    						data: {"psd_id":psd_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
    						success: function (json) {
    							alert(json.msg);
    							if(json.code==0){
                                    setTimeout(function() {
            							location.reload();
            						}, 500); //1초 지연
    							}
    							//history.go(0);
    							//$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
    						}
    					});
    				}
    			}
            }
		}

		$("input,select,textarea").change(function(){
			isChange = true;
		});

        $('.btn_excel_down').click(function(){
            isSample = true;
        });

		// var toDoWhenClosing = function() {
		// 	$.ajax({ type: "POST",
		// 					 url: "/spop/screen/outofpage",
		// 					 async: false,
		// 					 data: {"isChange":isChange, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
		// 					success: function (json) {
		// 						if(isChange){
		// 							if(confirm("수정된 내용이 있습니다. 저장하시겠습니까?")){
		// 							saved('');
		// 							//return false;
		// 							return;
		// 							}
		// 						}
		// 					}});
		//
		// };



			// window.addEventListener('beforeunload', saveChange);

	});

	// $(window).on("beforeunload", saveChange);
	// $(window).on("beforeunload",function(){
	// 	if(isChange){
	// 		return confirm("hi");
	// 	}
	// });
	// window.addEventListener('beforeunload', function(e) {
	//   if(isChange){
	// 		//toDoWhenClosing();
	// 		e.returnValue = '나가시렵니까?'
	// 		return '나가시렵니까?'
	//
	// 	}
	// });



	window.onbeforeunload = function(e) {
		if(isChange){
			// e.preventDefault();
			// setTimeout(saveChange, 0);
			// 명세에 따라 preventDefault는 호출해야하며, 기본 동작을 방지합니다.
			// event.preventDefault();
			// 대표적으로 Chrome에서는 returnValue 설정이 필요합니다.
            if(isSample==false){
                e.returnValue = '이 페이지를 벗어나면 작성된 내용은 저장되지 않습니다.';

    	           return "이 페이지를 벗어나면 작성된 내용은 저장되지 않습니다.";
            }else{
                isSample=false;
            }
			// return false;



		}
	};

	// $(window).on("beforeunload", function(){
	// 	// 데이터 변경이 있을경우
	// 	if(isChange){
	// 		// if (confirm("이 페이지를 벗어나면 작성된 내용은 저장되지 않습니다. 저장하시겠습니까?") == true){    //확인
	// 		// 			saved('');
	// 		// }else{   //취소
	// 		//
	// 		// }
	// 			return "이 페이지를 벗어나면 작성된 내용은 저장되지 않습니다.";
	// 	}
	// });
	// function saveChange(){
	// 	if(isChange){
	// 		if(confirm("수정된 내용이 있습니다. 저장하시겠습니까?")){
	// 		saved('');
	// 		//return false;
	// 		}
	// 	}
	// }

	// function preventClick(e){
	// 	e.preventDefault()
	// }


</script>
<div class="wrap_leaflets">
  <div class="s_tit">
    스마트전단 만들기
    <!--<div class="btn_list">
			<a href="/manual/leaflet"><span class="material-icons">video_call</span> 동영상 가이드 바로가기</a>
      <a href="javascript:list();"><span class="material-icons">art_track</span> 스마트전단 목록으로</a>
    </div>-->
  </div>
  <div class="write_leaflets">
    <div class="wl_lbox">
      <? //제목&템플릿선택&기간 [S] ------------------------------------------------------------------------------------------------------------------------------------ ?>
	  <span class="btn_imgtem">
        <button id="dh_myBtn" onClick="templet_open(1);"><span class="material-icons">photo_filter</span> 이미지 템플릿선택</button>
        <input type="hidden" id="data_id" value="<?=$psd_id?>"><?//전단번호?>
        <input type="hidden" id="tem_id" value="<?=$psd_tem_id?>"><?//템플릿번호?>
        <input type="hidden" id="chk_id" value=""><?//선택된ID?>
      </span>
      <div class="wl_tit">
        <input type="text" id="wl_tit" onKeyup="onKeyupData(this)" placeholder="행사제목을 입력해주세요." class="int" maxlength="35" value="<?=$psd_title?>">
      </div>
      <div class="wl_date">
        <input type="text" id="wl_date" onKeyup="onKeyupData(this)" placeholder="행사기간을 입력해주세요." class="int" maxlength="35" value="<?=$psd_date?>">
      </div>
      <div class="tit_box">
        <div class="tit">글꼴 크기</div>
        <div class="use_choice">
          <label class="uc_container"> 큰글씨
            <input type="radio" name="psd_font_LN" id="psd_font_LN_L" value="L" onClick="font_chk('L');"<? if($screen_data->psd_font_LN == "L"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
          <label class="uc_container"> 작은글씨
            <input type="radio" name="psd_font_LN" id="psd_font_LN_N" value="N" onClick="font_chk('N');"<? if($screen_data->psd_font_LN == "N"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
	  <? //제목&템플릿선택&기간 [E] ------------------------------------------------------------------------------------------------------------------------------------ ?>
      <div class="tit_box" style="display:<?=($mem_stmall_yn == "Y") ? "" : "none"?>">
        <div class="tit">스마트전단 주문하기</div>
        <div class="use_choice">
          <label class="uc_container"> 사용함
            <input type="radio" name="psd_order_yn" id="psd_order_yn_Y" value="Y" onClick="order_chk('');"<? if($psd_order_yn == "Y"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
          <label class="uc_container"> 사용안함
            <input type="radio" name="psd_order_yn" id="psd_order_yn_N" value="N" onClick="order_chk('none');"<? if($psd_order_yn != "Y"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
			<input type="hidden" id="hdn_psd_order_yn" value="<?=$psd_order_yn?>"/>
      <div id="psd_order_option" class="order_option" style="display:<?=($mem_stmall_yn == "Y" and $psd_order_yn == "Y") ? "" : "none"?>">
        <div class="input-group date fl" id="picker_order_sdt" style="width: 132px;cursor:pointer;">
          <input type="text" class="form-control" id="psd_order_sdt" style="cursor:pointer;" value="<?=$psd_order_sdt?>" onKeyPress="event.preventDefault();">
          <span class="input-group-addon">
              <span class="material-icons" style="font-size:18px;">date_range</span>
          </span>
        </div>
        <span class="under"> - </span>
        <div class="input-group date fl" id="picker_order_edt" style="width: 132px;cursor:pointer;">
          <input type="text" class="form-control" id="psd_order_edt" style="cursor:pointer;" value="<?=$psd_order_edt?>">
          <span class="input-group-addon">
              <span class="material-icons" style="font-size:18px;">date_range</span>
          </span>
        </div>
        <select class="mg_l20" id="psd_order_sh" onchange="change_time2(0)">
      <?
          for($i=0;$i<24;$i++){
      ?>
              <option value="<?=sprintf('%02d',$i)?>" <?=(string)$psd_order_sh == sprintf('%02d',$i) ? "selected" : "" ?>><?=sprintf('%02d',$i)?>시</option>
      <?
          }
      ?>
        </select>
        <select id="psd_order_sm" onchange="change_time2(0)">
      <?
          for($i=0;$i<6;$i++){
      ?>
              <option value="<?=sprintf('%02d',$i * 10)?>" <?=(string)$psd_order_sm == sprintf('%02d',$i * 10) ? "selected" : "" ?>><?=sprintf('%02d',$i * 10)?>분</option>
      <?
          }
      ?>
        </select>
        ~
        <select id="psd_order_eh" onchange="change_time2(1)">
        <?
            for($i=0;$i<24;$i++){
        ?>
                <option value="<?=sprintf('%02d',$i)?>" <?=(string)$psd_order_eh == sprintf('%02d',$i) ? "selected" : "" ?>><?=sprintf('%02d',$i)?>시</option>
        <?
            }
        ?>
        </select>
        <select id="psd_order_em" onchange="change_time2(1)">
        <?
            for($i=0;$i<6;$i++){
        ?>
                <option value="<?=sprintf('%02d',$i * 10)?>" <?=(string)$psd_order_em == sprintf('%02d',$i * 10) ? "selected" : "" ?>><?=sprintf('%02d',$i * 10)?>분</option>
        <?
            }
        ?>
        </select>
        <span class="text">
            <span>
                <font id="span_order_sdt"><?=($psd_order_sdt != "") ? $psd_order_sdt : date("Y").".00.00" . "<span id='psd_order_st_txt'></span>"?></font> ~ <font id="span_order_edt"><?=($psd_order_edt != "") ? $psd_order_edt : date("Y").".00.00" . "<span id='psd_order_et_txt'></span>"?></font>
                <font id="span_order_shm"><?=$psd_order_sh?>:<?=$psd_order_sm?></font> ~ <font id="span_order_ehm"><?=$psd_order_eh?>:<?=$psd_order_em?></font>
            </span> (설정된 기간동안 주문하기 기능이 활성화됩니다.)
        </span>
      </div>
	  <? //할인&대표상품 등록 [S] --------------------------------------------------------------------------------------------------------------------------------------- ?>
	  <div class="tit_box">
        <div class="tit">할인&대표상품 등록</div>
        <div class="use_choice">
        <label class="uc_container"> 사용함
          <input type="radio" name="step1_yn" id="step1_yn_Y" value="Y" onClick="goods_yn('step1', '');"<? if($psd_step1_yn != "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        <label class="uc_container"> 사용안함
          <input type="radio" name="step1_yn" id="step1_yn_N" value="N" onClick="goods_yn('step1', 'none');"<? if($psd_step1_yn == "N"){ ?> checked<? } ?>>
          <span class="checkmark"></span>
        </label>
        </div>
      </div>
      <div class="wl_main_goods" id="goods_list_step1" style="display:<? if($psd_step1_yn == "N"){ ?>none<? } ?>;">
        <div class="good_btn_box">
          <span class="btn_excel_down">
            <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
          </span>
          <span class="btn_excel_up">
            <a><label for="excelFile_step1" style="cursor:pointer;">상품 엑셀 업로드</label></a>
            <input type="file" id="excelFile_step1" onchange="excelExport(this, '1', 'step1')" style="display:none;">
          </span>
          <? if(!empty($psd_id)){ ?>
          <span class="btn_excel_down goods_down">
             <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
          </span>
        <? } ?>
					<button class="btn_badge_all" type="button" onclick="modal_all_badge_open('1', 'step1_')"><span class="material-icons">class</span> 행사뱃지 코너일괄적용</button>
          <? if($mem_bigpos_yn == "Y"){ ?>
		  <span class="btn_pos_view">
            <a onClick="pos_goods_open('1', 'step1');">포스 상품조회</a>
          </span>
		  <? } ?>
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
		  	if($psd_step1_yn != "N"){
		  		if(!empty($screen_step1)){
					foreach($screen_step1 as $r) {
						$badge_rate = "";
						if($r->psg_price != "" && $r->psg_dcprice != ""){
							$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
							$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
							$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
						}
		  ?>
          <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
              $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
              $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
              $findthumb = strripos($fileNameWithoutExt, '_thumb');
              //echo $fileNameWithoutExt."---".$file_ext;
              $plustext = "_thumb.";
              if($findthumb>0){
                  $plustext = ".";
              }
              $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
          }else{
              $imgnamepext = "";
          }
            ?>
		  <dl id="step1_<?=$ii?>" class="dl_step1">
		    <dt>
		  	  <div class="templet_img_in" onclick="showImg('step1_<?=$ii?>')">
		  		<div id="div_preview_step1_<?=$ii?>" class="templet_img_in2" style="background-image : url('<?=$imgnamepext?>');">
		  			<img id="img_preview_step1_<?=$ii?>" style="display:none;width:100%">
					<div id="div_badge_step1_<?=$ii?>" <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge"<? }else{ ?>class="design_badge"<? } ?>><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img id="img_badge_step1-<?=$seq?>_<?=$ii?>" src="<?=$r->badge_imgpath?>"><? } ?></div><?//행사뱃지 표시?>
		  		</div>
		  	  </div>
			  <button class="btn_good_badge" type="button" id="badgeBtn" onclick="modal_badge_open('1', 'step1_<?=$ii?>')">행사뱃지 선택</button>
		    </dt>
		    <dd>
		  	  <ul>
		  		<input type="hidden" id="goods_id_step1_<?=$ii?>" value="<?=$r->psg_id?>">
		  		<input type="hidden" id="goods_step_step1_<?=$ii?>" value="<?=$r->psg_step?>" style="width:60px;">
		  		<input type="hidden" id="goods_div_step1_<?=$ii?>" value="step1_<?=$ii?>" style="width:80px;">
		  		<input type="hidden" id="goods_seq_step1_<?=$ii?>" value="<?=$ii?>" style="width:42px;">
		  		<input type="hidden" id="goods_badge_step1_<?=$ii?>" value="<?=$r->psg_badge?>" style="width:42px;">
		  		<input type="hidden" id="badge_imgpath_step1_<?=$ii?>" value="<?=$r->badge_imgpath?>" style="text-align:right;">
		  		<input type="hidden" id="goods_imgpath_step1_<?=$ii?>" value="<?=$r->psg_imgpath?>" style="text-align:right;">

		  		<li><span>상품명</span><input type="text" id="goods_name_step1_<?=$ii?>" value="<?=$r->psg_name?>" onClick="chkGoodsData('step1_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step1_<?=$ii?>', '', this)" placeholder="상품명" class="int140"></li>
		  		<li><span>규&nbsp;&nbsp;&nbsp;격</span><input type="text" id="goods_option_step1_<?=$ii?>" value="<?=$r->psg_option?>" onClick="chkGoodsData('step1_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step1_<?=$ii?>', '', this)" placeholder="규격" class="int140"></li>
		  		<li><span>정상가</span><input type="text" id="goods_price_step1_<?=$ii?>" value="<?=$r->psg_price?>" onClick="chkGoodsData('step1_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step1_<?=$ii?>', '', this)" placeholder="" class="int140"></li>
		  		<li><span>할인가</span><input type="text" id="goods_dcprice_step1_<?=$ii?>" value="<?=$r->psg_dcprice?>" onClick="chkGoodsData('step1_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step1_<?=$ii?>', '', this)" placeholder="" class="int140"></li>
		  		<li><span>옵&nbsp;&nbsp;&nbsp;션</span><input type="text" id="goods_option2_step1_<?=$ii?>" value="<?=$r->psg_option2?>" onClick="chkGoodsData('step1_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step1_<?=$ii?>', '', this)" placeholder="옵션" class="int140"></li>
		  		<? // // 2021-06-01 수정 바코드 항목 추가 ?>
		  		<input type="hidden" id="goods_barcode_step1_<?=$ii?>" value="<?=$r->psg_code?>" style="text-align:right;">


		  	  </ul>
          <div class="soldout_box">
						<input type="hidden" id="goods_soldout_step1_<?=$ii?>" value="<?=$r->psg_stock?>">
						<label class="checkbox_container">
							<input type="checkbox" name="soldout_chk_step1" id="soldout_chk_step1_<?=$ii?>" onClick="chgGoodsData('step1_<?=$ii?>', '', this)" value="N" <?=($r->psg_stock == "N") ? " checked" : ""?> >
							<span class="checkmark"></span>
						</label>
          </div>
					<span class="soldout_text">품절</span>
		  	  <!-- <label class="checkbox_container pop_input">
		  	    <input type="checkbox" name="pop_chk" id="pop_chk<?=$ii?>" onClick="popChk(this);" value="step1_<?=$ii?>">
		  	    <span class="checkmark"></span>
		  	  </label> -->
		  	  <!-- <button id="myBtn" class="pop_modal_btn" onClick="popPrint('step1_<?=$ii?>')">POP 인쇄</button> -->
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
		  	} //if($psd_step1_yn != "N"){
		  	$step1_org = $ii;
		  	$step1_cnt = $ii;
		  ?>

		</section>
		<!--//상품추가시 looping-->
        <div class="btn_al_cen">
          <input type="hidden" id="step1_cnt" value="<?=$step1_cnt?>">
		  <button class="btn_good_add" type="button" onClick="goods_append('1', 'step1', 'new');">개별상품추가</button>
        </div>
      </div><!--//wl_main_goods-->
	  <? //할인&대표상품 등록 [E] ---------------------------------------------------------------------------------------------------------------------------------------?>
	  <div id="goods_copy_list">
        <? //행사이미지 복사대상 [S] ------------------------------------------------------------------------------------------------------------------------------------ ?>
		<div class="box_wrap" id="goods_list_step9-0" style="display:none;"><?//행사이미지 복사대상 ?>
          <div class="tit_box1">
            <div class="tit">행사이미지 <span id="goods_tit_step9-0">0</span></div>
            <a class="del_corner" id="btn_del_step9-0" onClick="goods_area_del('goods_box-0');">
              <i class="xi-close-circle"></i> 행사이미지 삭제
            </a>
            <input type="hidden" id="goods_id_step9-0_0" value="">
			<input type="hidden" id="goods_step_step9-0_0" value="9" style="width:30px;">
			<input type="hidden" id="goods_div_step9-0_0" value="step9-0_0" style="width:80px;">
			<input type="hidden" id="goods_seq_step9-0_0" value="0" style="width:40px;">
			<input type="hidden" id="goods_imgpath_step9-0_0" value="" style="text-align:right;">
			<input type="hidden" id="goods_name_step9-0_0" value="">
			<input type="hidden" id="goods_option_step9-0_0" value="">
			<input type="hidden" id="goods_price_step9-0_0" value="">
			<input type="hidden" id="goods_dcprice_step9-0_0" value="">
			<input type="hidden" id="goods_badge_step9-0_0" value="">
			<input type="hidden" id="badge_imgpath_step9-0_0" value="">
			<input type="hidden" id="goods_option2_step9-0_0" value="">
	  		<? // // 2021-06-01 수정 바코드 항목 추가 ?>
	  		<input type="hidden" id="goods_barcode_step9-0_0" value="">
          </div>
          <div class="smart_add_images">
            <img id="goods_img_step9-0" src="<?//=$title_event_imgpath?>">
          </div>
        </div>
        <? //행사이미지 복사대상 [E] ------------------------------------------------------------------------------------------------------------------------------------ ?>
		<? //2단 이미지형 복사대상 [S] -------------------------------------------------------------------------------------------------------------------------------------- ?>
        <div class="box_wrap" id="goods_list_step2-0" style="display:none;"><?//2단 이미지형 복사대상 ?>
  	      <div class="tit_box1">
            <div class="tit"><span id="goods_tit_tiers_step2-0">2단</span> 이미지형 상품등록 <span id="goods_tit_step2-0">0</span></div>
            <a class="del_corner" id="btn_del_step2-0" onClick="goods_area_del('goods_box-0');">
              <i class="xi-close-circle"></i> 이미지형 상품코너 삭제
            </a>
          </div>
          <div class="wl_img_goods" id="step2-0">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li><label class="uc_container"><input type="radio" name="tit_id_step2-0" id="tit_id_step2-0_0" value="0" onClick="areaSet('pre_tit_id_step2-0', '');"><span class="checkmark"></span><span class="w120">선택안함</span></label></li>
                <?
					$dti = 0;
					foreach($design_title_img_list as $r) { //타이틀 이미지
						if($r->tit_type_self != "S"){ //타이틀이미지 직접입력 체크
							$dti++;
							if($dti == 1) $title_img_imgpath = $r->tit_imgpath;
							if($dti == 1) $tit_text_info_first_i = $r->tit_text_info;
					?>
	    			<li><label class="uc_container"><input type="radio" name="tit_id_step2-0" id="tit_id_step2-0_<?=$dti?>" value="<?=$r->tit_id?>" onClick="areaSet('pre_tit_id_step2-0', '<img src=\'<?=$r->tit_imgpath?>\'>','<?=$r->tit_text_info?>');"<? if($dti == 1){ ?> checked<? } ?>><span class="checkmark"></span><img src="<?=$r->tit_imgpath?>"></label></li>
	    			<?
						}
					}
				?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <p class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~! 선택안함 또는 이미지 직접입력 선택시 스마트전단 메뉴로 등록될 타이틀명을 입력하셔야 합니다.</p>
              <ul class="tit_img_menu">
								<!-- 선택안함 입력값 따로 저장 -->
								<input type="hidden" id="h_tit_unchecked_step2-0" >
								<li><span><i class="xi-bars"></i> 스마트전단 메뉴명</span> <input type="text" id="t_tit_text_info_step2-0" placeholder="예시)야채코너" value="<?=$tit_text_info_first_i?>"></li>
								<li><span><i class="xi-camera-o"></i> 타이틀이미지 직접입력</span>
                   <label for="titimg_self_file_step2-0" class="templet_img_box" ><div id="div_preview_tit_step2-0"><img id="tit_img_preview_step2-0" style="display:block;width:100%"></div></label>
										 <input type="file" title="타이틀 이미지 파일" id="titimg_self_file_step2-0" onchange="titimg_self_img(this, 'step2-0');" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
									 <span><i class="xi-arrow-left"></i> 카메라 아이콘 클릭!</span>
									 <input type="hidden" id="tit_id_self_step2-0" value="">
									 <input type="hidden" id="tit_id_self_yn_step2-0" value="U">
								</li>
							</ul>
						</div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step2-0" style="cursor:pointer;">상품 엑셀 업로드</label></a>
				<input type="file" id="excelFile_step2-0" onChange="excelExport(this, '2', 'step2-0')" style="display:none;">
              </span>
              <? if(!empty($psd_id)){ ?>
              <span class="btn_excel_down goods_down">
                 <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
              </span>
            <? } ?>
              <button class="btn_badge_all" type="button" onclick="modal_all_badge_open('2', 'step2-0_')"><span class="material-icons">class</span> 행사뱃지 코너일괄적용</button>
              <? if($mem_bigpos_yn == "Y"){ ?>
		      <span class="btn_pos_view">
                <a onClick="pos_goods_open('2', 'step2-0');">포스 상품조회</a>
              </span>
			  <? } ?>

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
                  <button class="btn_good_badge" type="button" id="badgeBtn" onclick="modal_badge_open('2', 'step2-0_<?=$ii?>')">행사뱃지 선택</button>
                </dt>
                <dd>
                  <ul>
              	    <input type="hidden" id="goods_id_step2-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_step_step2-0_<?=$ii?>" value="2" style="width:30px;">
              	    <input type="hidden" id="goods_div_step2-0_<?=$ii?>" value="step2-0_<?=$ii?>" style="width:80px;">
              	    <input type="hidden" id="goods_seq_step2-0_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
              	    <input type="hidden" id="goods_badge_step2-0_<?=$ii?>" value="0" style="width:40px;">
              	    <input type="hidden" id="badge_imgpath_step2-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_imgpath_step2-0_<?=$ii?>" value="">
              	    <li><span>상품명</span><input type="text" id="goods_name_step2-0_<?=$ii?>" value="" onClick="chkGoodsData('step2-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step2-0_<?=$ii?>', '', this)" placeholder="상품명" class="int140"></li>
              	    <li><span>규&nbsp;&nbsp;&nbsp;격</span><input type="text" id="goods_option_step2-0_<?=$ii?>" value="" onClick="chkGoodsData('step2-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step2-0_<?=$ii?>', '', this)" placeholder="규격" class="int140"></li>
              	    <li><span>정상가</span><input type="text" id="goods_price_step2-0_<?=$ii?>" value="" onClick="chkGoodsData('step2-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step2-0_<?=$ii?>', '', this)" placeholder="4,000원" class="int140"></li>
              	    <li><span>할인가</span><input type="text" id="goods_dcprice_step2-0_<?=$ii?>" value="" onClick="chkGoodsData('step2-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step2-0_<?=$ii?>', '', this)" placeholder="3,000원" class="int140"></li>
              	    <li><span>옵&nbsp;&nbsp;&nbsp;션</span><input type="text" id="goods_option2_step2-0_<?=$ii?>" value="" onClick="chkGoodsData('step2-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step2-0_<?=$ii?>', '', this)" placeholder="옵션" class="int140"></li>
              	    <? // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환 ?>
              	    <input type="hidden" id="goods_barcode_step2-0_<?=$ii?>" value="">
                  </ul>
                  <!-- <label class="checkbox_container pop_input">
                    <input type="checkbox" name="pop_chk" id="pop_chk<?=$ii?>" onClick="popChk(this);" value="step2-0_<?=$ii?>">
                    <span class="checkmark"></span>
                  </label> -->
                  <!-- <button id="myBtn" class="pop_modal_btn" onClick="popPrint('step2-0_<?=$ii?>')">POP 인쇄</button> -->
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
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('2', 'step2-0', 'new');">개별상품추가</button>
            </div>
          </div><!--//wl_img_goods-->
        </div><!--//goods_list_step2-0-->
		<? //2단 이미지형 복사대상 [E] -------------------------------------------------------------------------------------------------------------------------------------- ?>
		<? //3단 이미지형 복사대상 [S] -------------------------------------------------------------------------------------------------------------------------------------- ?>
        <div class="box_wrap" id="goods_list_step4-0" style="display:none;"><?//3단 이미지형 복사대상 ?>
  	      <div class="tit_box1">
            <div class="tit"><span id="goods_tit_tiers_step4-0">3단</span> 이미지형 상품등록 <span id="goods_tit_step4-0">0</span></div>
            <a class="del_corner" id="btn_del_step4-0" onClick="goods_area_del('goods_box-0');">
              <i class="xi-close-circle"></i> 이미지형 상품코너 삭제
            </a>
          </div>
          <div class="wl_img_goods" id="step4-0">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li><label class="uc_container"><input type="radio" name="tit_id_step4-0" id="tit_id_step4-0_0" value="0" onClick="areaSet('pre_tit_id_step4-0', '');"><span class="checkmark"></span><span class="w120">선택안함</span></label></li>
                <?
					$dti = 0;
					foreach($design_title_img_list as $r) { //타이틀 이미지
						if($r->tit_type_self != "S"){ //타이틀이미지 직접입력 체크
							$dti++;
							if($dti == 1) $title_img_imgpath = $r->tit_imgpath;
							if($dti == 1) $tit_text_info_first_i = $r->tit_text_info;
							?>
							<li><label class="uc_container"><input type="radio" name="tit_id_step4-0" id="tit_id_step4-0_<?=$dti?>" value="<?=$r->tit_id?>" onClick="areaSet('pre_tit_id_step4-0', '<img src=\'<?=$r->tit_imgpath?>\'>' ,'<?=$r->tit_text_info?>');"<? if($dti == 1){ ?> checked<? } ?>><span class="checkmark"></span><img src="<?=$r->tit_imgpath?>"></label></li>
							<?
						}
					}
				?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~! 선택안함 또는 이미지 직접입력 선택시 스마트전단 메뉴로 등록될 타이틀명을 입력하셔야 합니다.</div>
							<ul class="tit_img_menu">
								<!-- 선택안함 입력값 따로 저장 -->
								<input type="hidden" id="h_tit_unchecked_step4-0" >
								<li><span><i class="xi-bars"></i> 스마트전단 메뉴명</span> <input type="text" id="t_tit_text_info_step4-0" placeholder="예시)야채코너" value="<?=$tit_text_info_first_i?>"></li>
								<li><span><i class="xi-camera-o"></i> 타이틀이미지 직접입력</span>
									 <label for="titimg_self_file_step4-0" class="templet_img_box" ><div id="div_preview_tit_step4-0"><img id="tit_img_preview_step4-0" style="display:block;width:100%"></div></label>
										 <input type="file" title="타이틀 이미지 파일" id="titimg_self_file_step4-0" onchange="titimg_self_img(this, 'step4-0');" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
									 <span><i class="xi-arrow-left"></i> 카메라 아이콘 클릭!</span>
									 <input type="hidden" id="tit_id_self_step4-0" value="">
									 <input type="hidden" id="tit_id_self_yn_step4-0" value="U">
								</li>
							</ul>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step4-0" style="cursor:pointer;">상품 엑셀 업로드</label></a>
				<input type="file" id="excelFile_step4-0" onChange="excelExport(this, '4', 'step4-0')" style="display:none;">
              </span>
              <? if(!empty($psd_id)){ ?>
              <span class="btn_excel_down goods_down">
                 <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
              </span>
            <? } ?>
              <? if($mem_bigpos_yn == "Y"){ ?>
		      <span class="btn_pos_view">
                <a onClick="pos_goods_open('4', 'step4-0');">포스 상품조회</a>
              </span>
			  <? } ?>

              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <!--상품추가시 looping-->
            <section id="area_goods_step4-0">
              <? for($ii = 0; $ii < $step4_std; $ii++){ ?>
              <dl id="step4-0_<?=$ii?>" class="dl_step4-0">
                <dt>
                  <div class="templet_img_in" onclick="showImg('step4-0_<?=$ii?>')">
                    <div id="div_preview_step4-0_<?=$ii?>" class="templet_img_in2" style="background-image : url('');">
                      <img id="img_preview_step4-0_<?=$ii?>" style="display:none;width:100%">
                    </div>
                  </div>
                  <button class="btn_good_badge" type="button" id="badgeBtn" onclick="modal_badge_open('4', 'step4-0_<?=$ii?>')" style="display:none;">행사뱃지 선택</button>
                </dt>
                <dd>
                  <ul>
              	    <input type="hidden" id="goods_id_step4-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_step_step4-0_<?=$ii?>" value="4" style="width:30px;">
              	    <input type="hidden" id="goods_div_step4-0_<?=$ii?>" value="step4-0_<?=$ii?>" style="width:80px;">
              	    <input type="hidden" id="goods_seq_step4-0_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
              	    <input type="hidden" id="goods_badge_step4-0_<?=$ii?>" value="0" style="width:40px;">
              	    <input type="hidden" id="badge_imgpath_step4-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_imgpath_step4-0_<?=$ii?>" value="">
              	    <li><span>상품명</span><input type="text" id="goods_name_step4-0_<?=$ii?>" value="" onClick="chkGoodsData('step4-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step4-0_<?=$ii?>', '', this)" placeholder="상품명" class="int140"></li>
              	    <li><span>규&nbsp;&nbsp;&nbsp;격</span><input type="text" id="goods_option_step4-0_<?=$ii?>" value="" onClick="chkGoodsData('step4-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step4-0_<?=$ii?>', '', this)" placeholder="규격" class="int140"></li>
              	    <li><span>정상가</span><input type="text" id="goods_price_step4-0_<?=$ii?>" value="" onClick="chkGoodsData('step4-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step4-0_<?=$ii?>', '', this)" placeholder="4,000원" class="int140"></li>
              	    <li><span>할인가</span><input type="text" id="goods_dcprice_step4-0_<?=$ii?>" value="" onClick="chkGoodsData('step4-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step4-0_<?=$ii?>', '', this)" placeholder="3,000원" class="int140"></li>
              	    <li style="display:none;"><span>옵&nbsp;&nbsp;&nbsp;션</span><input type="text" id="goods_option2_step4-0_<?=$ii?>" value="" onClick="chkGoodsData('step4-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step4-0_<?=$ii?>', '', this)" placeholder="옵션" class="int140"></li>
              	    <? // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환 ?>
              	    <input type="hidden" id="goods_barcode_step4-0_<?=$ii?>" value="">
                  </ul>
                  <!-- <label class="checkbox_container pop_input">
                    <input type="checkbox" name="pop_chk" id="pop_chk<?=$ii?>" onClick="popChk(this);" value="step4-0_<?=$ii?>">
                    <span class="checkmark"></span>
                  </label> -->
                  <!-- <button id="myBtn" class="pop_modal_btn" onClick="popPrint('step4-0_<?=$ii?>')">POP 인쇄</button> -->
                </dd>
                <span class="move_btn_group">
              	<a class="move_last_left" href="javascript:goods_move('first', 'step4-0', 'step4-0_<?=$ii?>');" title="처음으로이동"></a>
              	<a class="move_left" href="javascript:goods_move('left', 'step4-0', 'step4-0_<?=$ii?>');" title="왼쪽으로이동"></a>
              	<a class="move_right" href="javascript:goods_move('right', 'step4-0', 'step4-0_<?=$ii?>');" title="오른쪽으로이동"></a>
              	<a class="move_last_right" href="javascript:goods_move('last', 'step4-0', 'step4-0_<?=$ii?>');" title="마지막으로이동"></a>
              	<a class="move_del" href="javascript:area_del('step4-0',  '<?=$ii?>');" title="삭제"></a>
                </span>
              </dl>
              <? } ?>
  		    </section>
			<input type="hidden" id="step4-0_no" value="0" style="width:40px;">
            <input type="hidden" id="step4-0_cnt" value="<?=$step4_std?>" style="width:40px;">
            <!--//상품추가시 looping-->
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('4', 'step4-0', 'new');">개별상품추가</button>
            </div>
          </div><!--//wl_img_goods-->
        </div><!--//goods_list_step4-0-->
		<? //3단 이미지형 복사대상 [E] -------------------------------------------------------------------------------------------------------------------------------------- ?>
		<? //4단 이미지형 복사대상 [S] -------------------------------------------------------------------------------------------------------------------------------------- ?>
        <div class="box_wrap" id="goods_list_step5-0" style="display:none;"><?//4단 이미지형 복사대상 ?>
  	      <div class="tit_box1">
            <div class="tit"><span id="goods_tit_tiers_step5-0">4단</span> 이미지형 상품등록 <span id="goods_tit_step5-0">0</span></div>
            <a class="del_corner" id="btn_del_step5-0" onClick="goods_area_del('goods_box-0');">
              <i class="xi-close-circle"></i> 이미지형 상품코너 삭제
            </a>
          </div>
          <div class="wl_img_goods" id="step5-0">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li><label class="uc_container"><input type="radio" name="tit_id_step5-0" id="tit_id_step5-0_0" value="0" onClick="areaSet('pre_tit_id_step5-0', '');"><span class="checkmark"></span><span class="w120">선택안함</span></label></li>
                <?
					$dti = 0;
					foreach($design_title_img_list as $r) { //타이틀 이미지
						if($r->tit_type_self != "S"){ //타이틀이미지 직접입력 체크
							$dti++;
							if($dti == 1) $title_img_imgpath = $r->tit_imgpath;
							if($dti == 1) $tit_text_info_first_i = $r->tit_text_info;
							?>
	    			<li><label class="uc_container"><input type="radio" name="tit_id_step5-0" id="tit_id_step5-0_<?=$dti?>" value="<?=$r->tit_id?>" onClick="areaSet('pre_tit_id_step5-0', '<img src=\'<?=$r->tit_imgpath?>\'>' ,'<?=$r->tit_text_info?>');"<? if($dti == 1){ ?> checked<? } ?>><span class="checkmark"></span><img src="<?=$r->tit_imgpath?>"></label></li>
	    			<?
						}
					}
				?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~! 선택안함 또는 이미지 직접입력 선택시 스마트전단 메뉴로 등록될 타이틀명을 입력하셔야 합니다.</div>
							<ul class="tit_img_menu">
								<!-- 선택안함 입력값 따로 저장 -->
								<input type="hidden" id="h_tit_unchecked_step5-0" >
								<li><span><i class="xi-bars"></i> 스마트전단 메뉴명</span> <input type="text" id="t_tit_text_info_step5-0" placeholder="예시)야채코너" value="<?=$tit_text_info_first_i?>"></li>
								<li><span><i class="xi-camera-o"></i> 타이틀이미지 직접입력</span>
									 <label for="titimg_self_file_step5-0" class="templet_img_box" ><div id="div_preview_tit_step5-0"><img id="tit_img_preview_step5-0" style="display:block;width:100%"></div></label>
										 <input type="file" title="타이틀 이미지 파일" id="titimg_self_file_step5-0" onchange="titimg_self_img(this, 'step5-0');" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
									 <span><i class="xi-arrow-left"></i> 카메라 아이콘 클릭!</span>
									 <input type="hidden" id="tit_id_self_step5-0" value="">
									 <input type="hidden" id="tit_id_self_yn_step5-0" value="U">
								</li>
							</ul>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step5-0" style="cursor:pointer;">상품 엑셀 업로드</label></a>
				<input type="file" id="excelFile_step5-0" onChange="excelExport(this, '5', 'step5-0')" style="display:none;">
              </span>
              <? if(!empty($psd_id)){ ?>
              <span class="btn_excel_down goods_down">
                 <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
              </span>
            <? } ?>
              <? if($mem_bigpos_yn == "Y"){ ?>
		      <span class="btn_pos_view">
                <a onClick="pos_goods_open('5', 'step5-0');">포스 상품조회</a>
              </span>
			  <? } ?>

              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <!--상품추가시 looping-->
            <section id="area_goods_step5-0">
              <? for($ii = 0; $ii < $step5_std; $ii++){ ?>
              <dl id="step5-0_<?=$ii?>" class="dl_step5-0">
                <dt>
                  <div class="templet_img_in" onclick="showImg('step5-0_<?=$ii?>')">
                    <div id="div_preview_step5-0_<?=$ii?>" class="templet_img_in2" style="background-image : url('');">
                      <img id="img_preview_step5-0_<?=$ii?>" style="display:none;width:100%">
                    </div>
                  </div>
                  <button class="btn_good_badge" type="button" id="badgeBtn" onclick="modal_badge_open('5', 'step5-0_<?=$ii?>')" style="display:none;">행사뱃지 선택</button>
                </dt>
                <dd>
                  <ul>
              	    <input type="hidden" id="goods_id_step5-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_step_step5-0_<?=$ii?>" value="5" style="width:30px;">
              	    <input type="hidden" id="goods_div_step5-0_<?=$ii?>" value="step5-0_<?=$ii?>" style="width:80px;">
              	    <input type="hidden" id="goods_seq_step5-0_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
              	    <input type="hidden" id="goods_badge_step5-0_<?=$ii?>" value="0" style="width:40px;">
              	    <input type="hidden" id="badge_imgpath_step5-0_<?=$ii?>" value="">
              	    <input type="hidden" id="goods_imgpath_step5-0_<?=$ii?>" value="">
              	    <li><span>상품명</span><input type="text" id="goods_name_step5-0_<?=$ii?>" value="" onClick="chkGoodsData('step5-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step5-0_<?=$ii?>', '', this)" placeholder="상품명" class="int140"></li>
              	    <li><span>규&nbsp;&nbsp;&nbsp;격</span><input type="text" id="goods_option_step5-0_<?=$ii?>" value="" onClick="chkGoodsData('step5-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step5-0_<?=$ii?>', '', this)" placeholder="규격" class="int140"></li>
              	    <li><span>정상가</span><input type="text" id="goods_price_step5-0_<?=$ii?>" value="" onClick="chkGoodsData('step5-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step5-0_<?=$ii?>', '', this)" placeholder="4,000원" class="int140"></li>
              	    <li><span>할인가</span><input type="text" id="goods_dcprice_step5-0_<?=$ii?>" value="" onClick="chkGoodsData('step5-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step5-0_<?=$ii?>', '', this)" placeholder="3,000원" class="int140"></li>
              	    <li style="display:none;"><span>옵&nbsp;&nbsp;&nbsp;션</span><input type="text" id="goods_option2_step5-0_<?=$ii?>" value="" onClick="chkGoodsData('step5-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step5-0_<?=$ii?>', '', this)" placeholder="옵션" class="int140"></li>
              	    <? // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환 ?>
              	    <input type="hidden" id="goods_barcode_step5-0_<?=$ii?>" value="">
                  </ul>
                  <!-- <label class="checkbox_container pop_input">
                    <input type="checkbox" name="pop_chk" id="pop_chk<?=$ii?>" onClick="popChk(this);" value="step5-0_<?=$ii?>">
                    <span class="checkmark"></span>
                  </label> -->
                  <!-- <button id="myBtn" class="pop_modal_btn" onClick="popPrint('step5-0_<?=$ii?>')">POP 인쇄</button> -->
                </dd>
                <span class="move_btn_group">
              	<a class="move_last_left" href="javascript:goods_move('first', 'step5-0', 'step5-0_<?=$ii?>');" title="처음으로이동"></a>
              	<a class="move_left" href="javascript:goods_move('left', 'step5-0', 'step5-0_<?=$ii?>');" title="왼쪽으로이동"></a>
              	<a class="move_right" href="javascript:goods_move('right', 'step5-0', 'step5-0_<?=$ii?>');" title="오른쪽으로이동"></a>
              	<a class="move_last_right" href="javascript:goods_move('last', 'step5-0', 'step5-0_<?=$ii?>');" title="마지막으로이동"></a>
              	<a class="move_del" href="javascript:area_del('step5-0',  '<?=$ii?>');" title="삭제"></a>
                </span>
              </dl>
              <? } ?>
  		    </section>
			<input type="hidden" id="step5-0_no" value="0" style="width:40px;">
            <input type="hidden" id="step5-0_cnt" value="<?=$step5_std?>" style="width:40px;">
            <!--//상품추가시 looping-->
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('5', 'step5-0', 'new');">개별상품추가</button>
            </div>
          </div><!--//wl_img_goods-->
        </div><!--//goods_list_step5-0-->
		<? //4단 이미지형 복사대상 [E] -------------------------------------------------------------------------------------------------------------------------------------- ?>
		<? //텍스트형 복사대상 [S] -------------------------------------------------------------------------------------------------------------------------------------- ?>
        <div class="box_wrap" id="goods_list_step3-0" style="display:none;"><?//텍스트형 복사대상 ?>
  	      <div class="tit_box1">
            <div class="tit"><span id="goods_tit_tiers_step3-0">1단</span> 텍스트형 상품등록 <span id="goods_tit_step3-0">0</span></div>
						<a class="del_corner" id="btn_del_step3-0" onClick="goods_area_del('goods_box-0');">
              <i class="xi-close-circle"></i> 텍스트형 상품코너 삭제
            </a>
					</div>
  	      <div class="wl_txt_goods" id="step3-0">
            <!--타이틀 이미지가 들어갈 공간-->
            <div class="list_title_img">
              <ul>
                <li><label class="uc_container"><input type="radio" name="tit_id_step3-0" id="tit_id_step3-0_0" value="0" onClick="areaSet('pre_tit_id_step3-0', '');"><span class="checkmark"></span><span class="w120">선택안함</span></label></li>
                <?
  					$dti = 0;
  					foreach($design_title_text_list as $r) { //타이틀 이미지
							if($r->tit_type_self != "S"){ //타이틀이미지 직접입력 체크
	  						$dti++;
	  						if($dti == 1) $title_text_imgpath = $r->tit_imgpath;
								if($dti == 1) $tit_text_info_first_t = $r->tit_text_info;
	                ?>
	                <li><label class="uc_container"><input type="radio" name="tit_id_step3-0" id="tit_id_step3-0_<?=$dti?>" value="<?=$r->tit_id?>" onClick="areaSet('pre_tit_id_step3-0', '<img src=\'<?=$r->tit_imgpath?>\'>' ,'<?=$r->tit_text_info?>');"<? if($dti == 1){ ?> checked<? } ?>><span class="checkmark"></span><img src="<?=$r->tit_imgpath?>"></label></li>
	                <?
							}
  					}
                ?>
              </ul>
            </div>
            <!--//타이틀 이미지가 들어갈 공간-->
            <div class="tit_box2">
              <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~! 선택안함 또는 이미지 직접입력 선택시 스마트전단 메뉴로 등록될 타이틀명을 입력하셔야 합니다.</div>
							<ul class="tit_img_menu">
								<!-- 선택안함 입력값 따로 저장 -->
								<input type="hidden" id="h_tit_unchecked_step3-0" >
								<li><span><i class="xi-bars"></i> 스마트전단 메뉴명</span> <input type="text" id="t_tit_text_info_step3-0" placeholder="예시)야채코너" value="<?=$tit_text_info_first_t?>"></li>
								<li><span><i class="xi-camera-o"></i> 타이틀이미지 직접입력</span>
									 <label for="titimg_self_file_step3-0" class="templet_img_box" ><div id="div_preview_tit_step3-0"><img id="tit_img_preview_step3-0" style="display:block;width:100%"></div></label>
										 <input type="file" title="타이틀 이미지 파일" id="titimg_self_file_step3-0" onchange="titimg_self_img(this, 'step3-0');" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
									 <span><i class="xi-arrow-left"></i> 카메라 아이콘 클릭!</span>
									 <input type="hidden" id="tit_id_self_step3-0" value="">
									 <input type="hidden" id="tit_id_self_yn_step3-0" value="U">
								</li>
							</ul>
            </div>
            <div class="good_btn_box mg_t20">
              <span class="btn_excel_down">
                <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
              </span>
              <span class="btn_excel_up">
                <a><label for="excelFile_step3-0" style="cursor:pointer;">상품 엑셀 업로드</label></a>
                <input type="file" id="excelFile_step3-0" onChange="excelExport(this, '3', 'step3-0')" style="display:none;">
              </span>
              <? if(!empty($psd_id)){ ?>
              <span class="btn_excel_down goods_down">
                 <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
              </span>
            <? } ?>
              <? if($mem_bigpos_yn == "Y"){ ?>
		      <span class="btn_pos_view">
                <a onClick="pos_goods_open('3', 'step3-0');">포스 상품조회</a>
              </span>
			  <? } ?>

              <p>
                * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
              </p>
            </div>
            <ul class="txt_goods_list">
              <li>
                <span class="txt_name">상품명</span>
                <span class="txt_option">규격</span>
                <span class="txt_price1">정상가</span>
                <span class="txt_price2">할인가</span>
                <span class="txt_btn">삭제</span>
              </li>
              <!--상품추가시 looping-->
              <section id="area_goods_step3-0">
                <? for($ii = 0; $ii < $step3_std; $ii++){ ?>
                <li id="step3-0_<?=$ii?>" class="dl_step3-0">
                  <span class="txt_name"><input type="text" id="goods_name_step3-0_<?=$ii?>" value="" onClick="chkGoodsData('step3-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step3-0_<?=$ii?>', '', this)" placeholder="상품명" class="int100_l"></span>
                  <span class="txt_option"><input type="text" id="goods_option_step3-0_<?=$ii?>" value="" onClick="chkGoodsData('step3-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step3-0_<?=$ii?>', '', this)" placeholder="규격" class="int100_l"></span>
                  <span class="txt_price1"><input type="text" id="goods_price_step3-0_<?=$ii?>" value="" onClick="chkGoodsData('step3-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step3-0_<?=$ii?>', '', this)" placeholder="4,000원" class="int100_r"></span>
                  <span class="txt_price2"><input type="text" id="goods_dcprice_step3-0_<?=$ii?>" value="" onClick="chkGoodsData('step3-0_<?=$ii?>', '', this)" onKeyup="chgGoodsData('step3-0_<?=$ii?>', '', this)" placeholder="3,000원" class="int100_r"></span>
                  <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del('step3-0', '<?=$ii?>', '', this);" title="삭제"></a></span>
									<input type="hidden" id="goods_id_step3-0_<?=$ii?>" value="">
                  <input type="hidden" id="goods_step_step3-0_<?=$ii?>" value="3" style="width:30px;">
                  <input type="hidden" id="goods_div_step3-0_<?=$ii?>" value="step3-0_<?=$ii?>" style="width:80px;">
                  <input type="hidden" id="goods_seq_step3-0_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
              	  <input type="hidden" id="goods_badge_step3-0_<?=$ii?>" value="0" style="width:40px;">
				  <input type="hidden" id="badge_imgpath_step3-0_<?=$ii?>" value="">
              	  <input type="hidden" id="badge_imgpath_step3-0_<?=$ii?>" value="">
              	  <input type="hidden" id="goods_option2_step3-0_<?=$ii?>" value="">
          	      <? // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환 ?>
          	      <input type="hidden" id="goods_barcode_step3-0_<?=$ii?>" value="">
                </li>
                <? } //for($ii = 0; $ii < $step3_std; $ii++){ ?>
              </section>
              <input type="hidden" id="step3-0_no" value="0" style="width:40px;">
              <input type="hidden" id="step3-0_cnt" value="<?=$step3_std?>" style="width:40px;">
              <!--//상품추가시 looping-->
            </ul>
            <div class="btn_al_cen">
              <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('3', 'step3-0', 'new');">개별상품추가</button>
            </div>
          </div><!--//wl_txt_goods-->
        </div><!--//goods_list_step3-0-->
		<? //텍스트형 복사대상 [E] -------------------------------------------------------------------------------------------------------------------------------------- ?>
      </div><!--//goods_copy_list-->
	  <div id="box_area"><?//박스 영역?>
        <?
		  	//등록된 행사코너별 리스트
			$no = 0; //순번
			$ii = 0; //코너내 순번
		  	$seq = 0; //코너번호
			$chk_step_no = -1; //코너별 고유번호
		  	$box_seq = 0; //박스별 순번
		  	$step2_no = 0; //2단 이미지형 순번
		  	$step3_no = 0; //1단 텍스트형 순번
		  	$step4_no = 0; //3단 이미지형 순번
		  	$step5_no = 0; //4단 이미지형 순번
		  	$step2_cnt = 0; //2단 이미지형 등록수
		  	$step3_cnt = 0; //1단 텍스트형 등록수
		  	$step4_cnt = 0; //3단 이미지형 등록수
		  	$step5_cnt = 0; //4단 이미지형 등록수
		  	$step9_cnt = 0; //행사이미지 등록수
			$step_name = ""; //스텝
			if(!empty($screen_box)){
				$step_cnt = 0;
				$tit_tiers = "2단";
				foreach($screen_box as $r) {
					$no++;
					$psg_step = $r->psg_step; //스텝(1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형)
					if($psg_step == "2" or $psg_step == "4" or $psg_step == "5"){ //이미지형
						$badge_rate = "";
						if($r->psg_price != "" && $r->psg_dcprice != ""){
							$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
							$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
							if($goods_price != "" && $goods_dcprice){
								$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
							}
						}
						if($r->psg_step_no != $chk_step_no){
							$ii = 0;
							//$seq++; //박스내 순번
							$goods_box_cnt++; //상품 박스 갯수
							if($psg_step == "2"){ //2단 이미지형
								$step2_cnt++; //2단 이미지형 등록수 증가
								$tit_tiers = "2단";
								$seq = $step2_cnt; //박스내 순번
							}else if($psg_step == "4"){ //3단 이미지형
								$step4_cnt++; //3단 이미지형 등록수 증가
								$tit_tiers = "3단";
								$seq = $step4_cnt; //박스내 순번
							}else if($psg_step == "5"){ //4단 이미지형
								$step5_cnt++; //4단 이미지형 등록수 증가
								$tit_tiers = "4단";
								$seq = $step5_cnt; //박스내 순번
							}
							$step_cnt++; //이미지형 등록수 증가
        ?>
        <div id="goods_box-<?=$goods_box_cnt?>"><?//이미지형?>
          <input type="hidden" name="goods_box_id" id="goods_box-<?=$goods_box_cnt?>_name" value="goods_box-<?=$goods_box_cnt?>" style="width:120px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step" value="step<?=$psg_step?>" style="width:80px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step_id" value="step<?=$psg_step?>-<?=$seq?>" style="width:80px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step_no" value="<?=$seq?>" style="width:60px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_box_no" value="<?=$goods_box_cnt?>" style="width:60px;">
          <input type="hidden" id="goods_step<?=$psg_step?>-<?=$seq?>_cno" value="<?=$r->psg_step?>" style="width:60px;">
          <div class="box_wrap" id="goods_list_step<?=$psg_step?>-<?=$seq?>">
            <div class="tit_box1">
              <div class="tit">
								<span id="goods_tit_tiers_step<?=$psg_step?>-<?=$seq?>"><?=$tit_tiers?></span> 이미지형 상품등록 <span id="goods_tit_step<?=$psg_step?>-<?=$seq?>"><?=$seq?></span>
								<select id="changeStep<?=$psg_step?>-<?=$seq?>" onChange="changeStep('<?=$psg_step?>-<?=$seq?>','<?=$psg_step?>', '<?=$goods_box_cnt?>');">
	  				        <option value="2" <?=($r->psg_step == '2') ? " Selected" : ""?>>2단 이미지형</option>
	                  <option value="4" <?=($r->psg_step == '4') ? " Selected" : ""?>>3단 이미지형</option>
	                  <option value="5" <?=($r->psg_step == '5') ? " Selected" : ""?>>4단 이미지형</option>
	  			      </select>
							</div>
            <a class="del_corner" id="btn_del_step<?=$psg_step?>-<?=$seq?>" onClick="goods_area_del('goods_box-<?=$goods_box_cnt?>');">
                <i class="xi-close-circle"></i> 이미지형 상품코너 삭제
              </a>
              </div>
            <div class="wl_img_goods" id="step<?=$psg_step?>-<?=$seq?>">
              <!--타이틀 이미지가 들어갈 공간-->
              <div class="list_title_img">
                <ul>
                  <li>
                    <label class="uc_container">
                      <input type="radio" name="tit_id_step<?=$psg_step?>-<?=$seq?>" id="tit_id_step<?=$psg_step?>-<?=$seq?>_0" value="0" onClick="areaSet('pre_tit_id_step<?=$psg_step?>-<?=$seq?>', '');"<? if($r->psg_tit_id == "0"){ ?> checked<? } ?>>
                      <span class="checkmark"></span><span class="w120">선택안함</span>
                    </label>
                  </li>
                  <?
						$dti = 0;
						$tit_img_step_id = "";
						foreach($design_title_img_list as $dt) { //타이틀 이미지
							if($dt->tit_type_self != "S"){ //타이틀이미지 직접입력 체크
									$dti++;
									if($r->psg_tit_id == $dt->tit_id and $dti > 14){
										//$tit_img_step_id = "tit_img_step". $psg_step ."-". $seq ."_". $dti;
									}
						  ?>
		                  <li>
		                    <label class="uc_container">
		                      <input type="radio" name="tit_id_step<?=$psg_step?>-<?=$seq?>" id="tit_id_step<?=$psg_step?>-<?=$seq?>_<?=$dti?>" value="<?=$dt->tit_id?>" onClick="areaSet('pre_tit_id_step<?=$psg_step?>-<?=$seq?>', '<img src=\'<?=$dt->tit_imgpath?>\'>' ,'<?=$dt->tit_text_info?>');"<? if($r->psg_tit_id == $dt->tit_id){ $ex_tit_text_info = $dt->tit_text_info ?> checked<? } ?>>
		                      <span class="checkmark"></span><img id="tit_img_step<?=$psg_step?>-<?=$seq?>_<?=$dti?>" src="<?=$dt->tit_imgpath?>">
		                    </label>
		                  </li>
		    			  <?
							}
						} //foreach($design_title_img_list as $dt) { //타이틀 이미지
				  ?>
                </ul>
              </div>
			  <? if($tit_img_step_id != ""){ ?><script>$("#<?=$tit_img_step_id?>").attr("tabindex", -1).focus(); //미리보기 포커스</script><? } ?>
              <!--//타이틀 이미지가 들어갈 공간-->
              <div class="tit_box2">
                <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~! 선택안함 또는 이미지 직접입력 선택시 스마트전단 메뉴로 등록될 타이틀명을 입력하셔야 합니다.</div>
								<ul class="tit_img_menu">
									<!-- 기존에 만들어진 전단 수정시 텍스트 적용 -->
								<?if(empty($r->pst_tit_text_info) and !empty($r->psg_tit_id) and empty($r->tit_type_self_s) ){
									$pst_tit_text = $ex_tit_text_info;
									// echo $ex_tit_text_info;
								}else{
									$pst_tit_text = $r->pst_tit_text_info;
									// echo $pst_tit_text;
								}?>
									<!-- 선택안함 입력값 따로 저장 -->
									<input type="hidden" id="h_tit_unchecked_step<?=$psg_step?>-<?=$seq?>" value="<?=$pst_tit_text?>">
									<li><span><i class="xi-bars"></i> 스마트전단 메뉴명</span> <input type="text" id="t_tit_text_info_step<?=$psg_step?>-<?=$seq?>" placeholder="예시)야채코너" value="<?=$pst_tit_text?>"></li>
									<li><span><i class="xi-camera-o"></i> 타이틀이미지 직접입력</span>
										 <label for="titimg_self_file_step<?=$psg_step?>-<?=$seq?>" class="templet_img_box" ><div id="div_preview_tit_step<?=$psg_step?>-<?=$seq?>"><img id="tit_img_preview_step<?=$psg_step?>-<?=$seq?>" style="display:block;width:100%"></div></label>
											 <input type="file" title="타이틀 이미지 파일" id="titimg_self_file_step<?=$psg_step?>-<?=$seq?>" onchange="titimg_self_img(this, 'step<?=$psg_step?>-<?=$seq?>');" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
										 <span><i class="xi-arrow-left"></i> 카메라 아이콘 클릭!</span>
										 <input type="hidden" id="tit_id_self_step<?=$psg_step?>-<?=$seq?>" value="<?=$r->psg_tit_id?>">
										 <input type="hidden" id="tit_id_self_yn_step<?=$psg_step?>-<?=$seq?>" value="<?=$r->tit_type_self_s?>">
									</li>
								</ul>
              </div>
              <div class="good_btn_box mg_t20">
                <span class="btn_excel_down">
                  <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
                </span>
                <span class="btn_excel_up">
                  <a><label for="excelFile_step<?=$psg_step?>-<?=$seq?>" style="cursor:pointer;">상품 엑셀 업로드</label></a>
                  <input type="file" id="excelFile_step<?=$psg_step?>-<?=$seq?>" onChange="excelExport(this, '<?=$psg_step?>', 'step<?=$psg_step?>-<?=$seq?>')" style="display:none;">
                </span>
                <? if(!empty($psd_id)){ ?>
                <span class="btn_excel_down goods_down">
                   <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
                </span>
              <? } ?>
                <? if($psg_step < 3){ ?>
                    <button class="btn_badge_all badge_all<?=$goods_box_cnt?>" type="button" onclick="modal_all_badge_open('<?=$psg_step?>', 'step<?=$psg_step?>-<?=$seq?>_')"><span class="material-icons">class</span> 행사뱃지 코너일괄적용</button>
                <? }else{ ?>
                    <button style="display:none;" class="btn_badge_all badge_all<?=$goods_box_cnt?>" type="button" onclick="modal_all_badge_open('<?=$psg_step?>', 'step<?=$psg_step?>-<?=$seq?>_')"><span class="material-icons">class</span> 행사뱃지 코너일괄적용</button>
                <? } ?>
                <? if($mem_bigpos_yn == "Y"){ ?>
                <span class="btn_pos_view">
                  <a onClick="pos_goods_open('<?=$psg_step?>', 'step<?=$psg_step?>-<?=$seq?>');">포스 상품조회</a>
                </span>
                <? } ?>
                <p>
                  * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.+
                </p>
              </div>
              <!--상품추가시 looping-->
              <section id="area_goods_step<?=$psg_step?>-<?=$seq?>">
                <?
						} //if($r->psg_step_no != $chk_step_no){
			    ?>
                <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                    $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                    $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                    $findthumb = strripos($fileNameWithoutExt, '_thumb');
                    //echo $fileNameWithoutExt."---".$file_ext;
                    $plustext = "_thumb.";
                    if($findthumb>0){
                        $plustext = ".";
                    }
                    $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                }else{
                    $imgnamepext = "";
                }
                  ?>
                <dl id="step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" class="dl_step<?=$psg_step?>-<?=$seq?>">
                  <dt>
                    <div class="templet_img_in" onclick="showImg('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>')">
                      <div id="div_preview_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" class="templet_img_in2" style="background-image : url('<?=$imgnamepext?>');">
                        <img id="img_preview_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" style="display:none;width:100%">
                        <div id="div_badge_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" <? if($r->psg_badge == "0" or $psg_step != "2"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge"<? }else{ ?>class="design_badge"<? } ?>><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img id="img_badge_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" src="<?=$r->badge_imgpath?>"><? } ?></div>
                      </div>
                    </div>
					<button class="btn_good_badge badge<?=$goods_box_cnt?>" type="button" id="badgeBtn" onclick="modal_badge_open('<?=$psg_step?>', 'step<?=$psg_step?>-<?=$seq?>_<?=$ii?>')" style="display:<? if($psg_step != "2"){ ?>none<? } ?>;">행사뱃지 선택</button>
                  </dt>
                  <dd>
                    <ul>
                      <input type="hidden" id="goods_id_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_id?>">
                      <input type="hidden" id="goods_step_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_step?>" style="width:30px;">
                      <input type="hidden" id="goods_div_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" style="width:80px;">
                      <input type="hidden" id="goods_seq_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
                      <input type="hidden" id="goods_badge_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_badge?>" style="width:40px;">
                      <input type="hidden" id="badge_imgpath_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->badge_imgpath?>">
                      <input type="hidden" id="goods_imgpath_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_imgpath?>">
                      <li><span>상품명</span><input type="text" id="goods_name_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_name?>" onClick="chkGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this);" onKeyup="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this)" placeholder="상품명" class="int140"></li>
                      <li><span>규&nbsp;&nbsp;&nbsp;격</span><input type="text" id="goods_option_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option?>" onClick="chkGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this);" onKeyup="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this)" placeholder="규격" class="int140"></li>
                      <li><span>정상가</span><input type="text" id="goods_price_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_price?>" onClick="chkGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this);" onKeyup="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this)" placeholder="정상가" class="int140"></li>
                      <li><span>할인가</span><input type="text" id="goods_dcprice_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_dcprice?>" onClick="chkGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this);" onKeyup="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this)" placeholder="할인가" class="int140"></li>
                      <? if($psg_step == "2"){ //2단 이미지형 ?>
					  <li class="li_step<?=$goods_box_cnt?>"><span>옵&nbsp;&nbsp;&nbsp;션</span><input type="text" id="goods_option2_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option2?>" onClick="chkGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this);" onKeyup="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this)" placeholder="옵션" class="int140"></li>
					  <? }else{ ?>
                      <li class="li_step<?=$goods_box_cnt?>" style="display:none;"><span>옵&nbsp;&nbsp;&nbsp;션</span><input type="text" id="goods_option2_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option2?>" onClick="chkGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this);" onKeyup="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '' , this)" placeholder="옵션" class="int140"></li>
					  <!-- <input type="hidden" id="goods_option2_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option2?>"> -->
					  <? } ?>
					  <? // // 2021-06-01 수정 바코드 항목 추가 ?>
					  <input type="hidden" id="goods_barcode_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_code?>">
                    </ul>
										<div class="soldout_box">
											<label class="checkbox_container">
												<input type="hidden" id="goods_soldout_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_stock?>">
												<input type="checkbox" name="soldout_chk_step<?=$psg_step?>-<?=$seq?>" id="soldout_chk_step<?=$psg_step?>-<?=$seq?>_<?=$ii?>" onClick="chgGoodsData('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>', '', this)" value="N" <?=($r->psg_stock == "N") ? " checked" : ""?>>
												<span class="checkmark"></span>
											</label>
					          </div>
										<span class="soldout_text">품절</span>
                    <!-- <label class="checkbox_container pop_input">
                      <input type="checkbox" name="pop_chk" id="pop_chk<?=$ii?>" onClick="popChk(this);" value="step<?=$psg_step?>-<?=$seq?>_<?=$ii?>">
                      <span class="checkmark"></span>
                    </label> -->
                    <!-- <button id="myBtn" class="pop_modal_btn" onClick="popPrint('step<?=$psg_step?>-<?=$seq?>_<?=$ii?>')">POP 인쇄</button> -->
                  </dd>
                  <span class="move_btn_group">
                      <a class="move_last_left" href="javascript:goods_move('first', 'step<?=$psg_step?>-<?=$seq?>', 'step<?=$psg_step?>-<?=$seq?>_<?=$ii?>');" title="처음으로이동"></a>
                      <a class="move_left" href="javascript:goods_move('left', 'step<?=$psg_step?>-<?=$seq?>', 'step<?=$psg_step?>-<?=$seq?>_<?=$ii?>');" title="왼쪽으로이동"></a>
                      <a class="move_right" href="javascript:goods_move('right', 'step<?=$psg_step?>-<?=$seq?>', 'step<?=$psg_step?>-<?=$seq?>_<?=$ii?>');" title="오른쪽으로이동"></a>
                      <a class="move_last_right" href="javascript:goods_move('last', 'step<?=$psg_step?>-<?=$seq?>', 'step<?=$psg_step?>-<?=$seq?>_<?=$ii?>');" title="마지막으로이동"></a>
                      <a class="move_del" href="javascript:area_del('step<?=$psg_step?>-<?=$seq?>', '<?=$ii?>');" title="삭제"></a>
                  </span>
                </dl>
			    <?
						$ii++; //코너별 등록수
						if($ii >= $r->rownum){
			    ?>
              </section>
              <input type="hidden" id="step<?=$psg_step?>-<?=$seq?>_no" value="<?=$seq?>" style="width:40px;">
              <input type="hidden" id="step<?=$psg_step?>-<?=$seq?>_cnt" value="<?=$ii?>" style="width:40px;">
              <!--//상품추가시 looping-->
              <div class="btn_al_cen">
                <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('<?=$psg_step?>', 'step<?=$psg_step?>-<?=$seq?>', 'new');">개별상품추가</button>
              </div>
            </div><!--//wl_img_goods-->
          </div><!--//goods_list_step<?=$psg_step?>-<?=$seq?>-->
        </div><!--//goods_box-<?=$goods_box_cnt?>-->
        <?
						} //if($ii >= $r->rownum){
						$chk_step_no = $r->psg_step_no;
					}else if($psg_step == "3"){ //텍스트형
						if($r->psg_step_no != $chk_step_no){
							$ii = 0;
							//$seq++; //박스내 순번
							$goods_box_cnt++; //상품 박스 갯수
							$step3_cnt++; //텍스트형 등록수 증가
							$seq = $step3_cnt; //박스내 순번
							$tit_tiers = "1단";
        ?>
        <div id="goods_box-<?=$goods_box_cnt?>"><?//텍스트형?>
          <input type="hidden" name="goods_box_id" id="goods_box-<?=$goods_box_cnt?>_name" value="goods_box-<?=$goods_box_cnt?>" style="width:120px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step" value="step<?=$psg_step?>" style="width:80px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step_id" value="step<?=$psg_step?>-<?=$seq?>" style="width:80px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step_no" value="<?=$seq?>" style="width:60px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_box_no" value="<?=$goods_box_cnt?>" style="width:60px;">
          <div id="goods_list_step3-<?=$seq?>">
            <div class="tit_box1">
              <div class="tit"><span id="goods_tit_tiers_step<?=$psg_step?>-<?=$seq?>"><?=$tit_tiers?></span> 텍스트형 상품등록 <span id="goods_tit_step<?=$psg_step?>-<?=$seq?>"><?=$step3_cnt?></span></div>
              <a class="del_corner" id="btn_del_step3-<?=$seq?>" onClick="goods_area_del('goods_box-<?=$goods_box_cnt?>');">
                <i class="xi-close-circle"></i> 텍스트형 상품코너 삭제
              </a>
            </div>
            <div class="wl_txt_goods" id="step3-<?=$seq?>">
              <!--타이틀 이미지가 들어갈 공간-->
              <div class="list_title_img">
                <ul>
                  <li>
                    <label class="uc_container">
                      <input type="radio" name="tit_id_step3-<?=$seq?>" id="tit_id_step3-<?=$seq?>_0" value="0" onClick="areaSet('pre_tit_id_step3-<?=$seq?>', '');"<? if($r->psg_tit_id == "0"){ ?> checked<? } ?>>
                      <span class="checkmark"></span>
                      <span class="w120">선택안함</span>
                    </label>
                  </li>
                  <?
						$dti = 0;
						$tit_img_step_id = "";
						foreach($design_title_text_list as $dt) { //타이틀 이미지
							if($dt->tit_type_self != "S"){ //타이틀이미지 직접입력 체크
									$dti++;
									if($r->psg_tit_id == $dt->tit_id and $dti > 14){
										//$tit_img_step_id = "tit_id_step3-". $seq ."_". $dti;
									}
		                  ?>
		                  <li>
		                    <label class="uc_container">
		                      <input type="radio" name="tit_id_step3-<?=$seq?>" id="tit_id_step3-<?=$seq?>_<?=$dti?>" value="<?=$dt->tit_id?>" onClick="areaSet('pre_tit_id_step3-<?=$seq?>', '<img src=\'<?=$dt->tit_imgpath?>\'>' ,'<?=$dt->tit_text_info?>');"<? if($r->psg_tit_id == $dt->tit_id){ $ex_tit_text_info = $dt->tit_text_info ?> checked<? } ?>>
		                      <span class="checkmark"></span>
		                      <img src="<?=$dt->tit_imgpath?>">
		                    </label>
		                  </li>
		                  <?
								}
  						} //foreach($design_title_text_list as $dt) { //타이틀 이미지
                  ?>
                </ul>
              </div>
			  <? if($tit_img_step_id != ""){ ?><script>$("#<?=$tit_img_step_id?>").attr("tabindex", -1).focus(); //미리보기 포커스</script><? } ?>
              <!--//타이틀 이미지가 들어갈 공간-->
              <div class="tit_box2">
                <div class="tit">* 진열할 상품에 맞는 타이틀을 선택하세요~! 선택안함 또는 이미지 직접입력 선택시 스마트전단 메뉴로 등록될 타이틀명을 입력하셔야 합니다.</div>
								<ul class="tit_img_menu">
									<!-- 기존에 만들어진 전단 수정시 텍스트 적용 -->
								<?if(empty($r->pst_tit_text_info) and !empty($r->psg_tit_id) and empty($r->tit_type_self_s) ){
									$pst_tit_text = $ex_tit_text_info;
									// echo $ex_tit_text_info;
								}else{
									$pst_tit_text = $r->pst_tit_text_info;
									// echo $pst_tit_text;
								}?>
									<!-- 선택안함 입력값 따로 저장 -->
									<input type="hidden" id="h_tit_unchecked_step3-<?=$seq?>" value="<?=$pst_tit_text?>">
									<li><span><i class="xi-bars"></i> 스마트전단 메뉴명</span> <input type="text" id="t_tit_text_info_step3-<?=$seq?>" placeholder="예시)야채코너" value="<?=$pst_tit_text?>"></li>
									<li><span><i class="xi-camera-o"></i> 타이틀이미지 직접입력</span>
										 <label for="titimg_self_file_step3-<?=$seq?>" class="templet_img_box" ><div id="div_preview_tit_step3-<?=$seq?>"><img id="tit_img_preview_step3-<?=$seq?>" style="display:block;width:100%"></div></label>
											 <input type="file" title="타이틀 이미지 파일" id="titimg_self_file_step3-<?=$seq?>" onchange="titimg_self_img(this, 'step3-<?=$seq?>');" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
										 <span><i class="xi-arrow-left"></i> 카메라 아이콘 클릭!</span>
										 <input type="hidden" id="tit_id_self_step3-<?=$seq?>" value="<?=$r->psg_tit_id?>">
										 <input type="hidden" id="tit_id_self_yn_step3-<?=$seq?>" value="<?=$r->tit_type_self_s?>">
									</li>
								</ul>
              </div>
              <div class="good_btn_box mg_t20">
                <span class="btn_excel_down">
                  <a href="<?=$goods_sample_path?>">엑셀 샘플 다운로드</a>
                </span>
                <span class="btn_excel_up">
                  <a><label for="excelFile_step3-<?=$seq?>" style="cursor:pointer;">상품 엑셀 업로드</label></a>
                  <input type="file" id="excelFile_step3-<?=$seq?>" onChange="excelExport(this, '3', 'step3-<?=$seq?>')" style="display:none;">
                </span>
                <? if(!empty($psd_id)){ ?>
                <span class="btn_excel_down goods_down">
                   <a onclick="conner_download('<?=$psd_id?>','<?=$goods_box_cnt?>')" style="margin-left:5px;"><label style="cursor:pointer;">상품 엑셀 다운로드</label></a>
                </span>
              <? } ?>
                <p>
                  * 엑셀로 상품정보를 일괄업로드 하시려면 엑셀 샘플 다운로드 버튼을 클릭하셔서 작성 후 업로드 해주시면 됩니다.
                </p>
              </div>
              <ul class="txt_goods_list">
                <li>
                  <span class="txt_name">상품명</span>
                  <span class="txt_option">규&nbsp;&nbsp;&nbsp;격</span>
                  <span class="txt_price1">정상가</span>
                  <span class="txt_price2">할인가</span>
                  <span class="txt_btn">삭제</span>
									<span class="txt_soldout">품절</span>
                </li>
                <!--상품추가시 looping-->
                <section id="area_goods_step3-<?=$seq?>">
                  <?
						} //if($r->psg_step_no != $chk_step_no){
                  ?>
                  <li id="step3-<?=$seq?>_<?=$ii?>" class="dl_step3-<?=$seq?>">
                    <span class="txt_name"><input type="text" id="goods_name_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_name?>" onClick="chkGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this);" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this)" placeholder="" class="int100_l"></span>
                    <span class="txt_option"><input type="text" id="goods_option_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option?>" onClick="chkGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this);" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this)" placeholder="" class="int100_l"></span>
                    <span class="txt_price1"><input type="text" id="goods_price_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_price?>" onClick="chkGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this);" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this)" placeholder="" class="int100_r"></span>
                    <span class="txt_price2"><input type="text" id="goods_dcprice_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_dcprice?>" onClick="chkGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this);" onKeyup="chgGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this)" placeholder="" class="int100_r"></span>
                    <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del('step3-<?=$seq?>', '<?=$ii?>');" title="삭제"></a></span>
										<div class="soldout_box_t">
						          <label class="checkbox_container">
												<input type="hidden" id="goods_soldout_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_stock?>">
												<input type="checkbox" name="soldout_chk_step3-<?=$seq?>" id="soldout_chk_step3-<?=$seq?>_<?=$ii?>" onClick="chgGoodsData('step3-<?=$seq?>_<?=$ii?>', '', this)" value="N" <?=($r->psg_stock == "N") ? " checked" : ""?>>

							          <span class="checkmark"></span>
						          </label>
                    </div>
                    <input type="hidden" id="goods_id_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_id?>">
                    <input type="hidden" id="goods_step_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_step?>" style="width:30px;">
                    <input type="hidden" id="goods_div_step3-<?=$seq?>_<?=$ii?>" value="step3-<?=$seq?>_<?=$ii?>" style="width:80px;">
                    <input type="hidden" id="goods_seq_step3-<?=$seq?>_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
                    <input type="hidden" id="goods_badge_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_badge?>" style="width:40px;">
                    <input type="hidden" id="badge_imgpath_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->badge_imgpath?>">
                    <input type="hidden" id="goods_option2_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option2?>">
                    <? // // 2021-06-01 수정 바코드 항목 추가 ?>
                    <input type="hidden" id="goods_barcode_step3-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_code?>">
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
                <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('3', 'step3-<?=$seq?>', 'new');">개별상품추가</button>
              </div>
            </div><!--//wl_txt_goods-->
          </div><!--//goods_list_step3-<?=$seq?>-->
        </div><!--//goods_box-<?=$goods_box_cnt?>-->
        <?
						} //if($ii >= $r->rownum){
						$chk_step_no = $r->psg_step_no;
					}else if($psg_step == "9"){ //행사이미지
						$ii = 0;
						//$seq++; //박스내 순번
						$goods_box_cnt++; //상품 박스 갯수
						$step9_cnt++; //이미지형 등록수 증가
						$seq = $step9_cnt; //박스내 순번
        ?>
        <div id="goods_box-<?=$goods_box_cnt?>"><?//행사이미지?>
          <input type="hidden" name="goods_box_id" id="goods_box-<?=$goods_box_cnt?>_name" value="goods_box-<?=$goods_box_cnt?>" style="width:120px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step" value="step<?=$psg_step?>" style="width:80px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step_id" value="step<?=$psg_step?>-<?=$seq?>" style="width:80px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_step_no" value="<?=$seq?>" style="width:60px;">
          <input type="hidden" id="goods_box-<?=$goods_box_cnt?>_box_no" value="<?=$goods_box_cnt?>" style="width:60px;">
          <input type="hidden" id="goods_id_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_id?>">
          <input type="hidden" id="goods_step_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_step?>" style="width:30px;">
          <input type="hidden" id="goods_div_step9-<?=$seq?>_<?=$ii?>" value="step9-<?=$seq?>_<?=$ii?>" style="width:80px;">
          <input type="hidden" id="goods_seq_step9-<?=$seq?>_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
          <input type="hidden" id="goods_imgpath_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_imgpath?>" style="text-align:right;">
          <input type="hidden" id="goods_name_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_name?>">
          <input type="hidden" id="goods_option_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option?>">
          <input type="hidden" id="goods_price_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_price?>">
          <input type="hidden" id="goods_dcprice_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_dcprice?>">
          <input type="hidden" id="goods_option2_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_option2?>">
	  	  <? // // 2021-06-01 수정 바코드 항목 추가 ?>
	  	  <input type="hidden" id="goods_barcode_step9-<?=$seq?>_<?=$ii?>" value="<?=$r->psg_code?>">

          <div class="box_wrap" id="goods_list_step9-<?=$seq?>">
            <div class="tit_box1">
              <div class="tit">행사이미지 <span id="goods_tit_step9-<?=$seq?>"><?=$step9_cnt?></span></div>
							<a class="del_corner" id="btn_del_step9-0" onClick="goods_area_del('goods_box-<?=$goods_box_cnt?>');">
                <i class="xi-close-circle"></i> 행사이미지 삭제
              </a>
            </div>
            <div class="smart_add_images">
              <img id="goods_img_step9-0" src="<?=$r->psg_imgpath?>">
            </div>
          </div>
        </div><!--//goods_box-<?=$goods_box_cnt?>-->
        <?
					} //}else if($psg_step == "9"){
				} //foreach($screen_box as $r) {
			} //if(!empty($screen_box)){
        ?>
      </div><!--//box_area-->
	  <input type="hidden" id="step2_cnt" value="<?=$step2_cnt?>">
      <input type="hidden" id="step3_cnt" value="<?=$step3_cnt?>">
      <input type="hidden" id="step4_cnt" value="<?=$step4_cnt?>">
      <input type="hidden" id="step5_cnt" value="<?=$step5_cnt?>">
      <input type="hidden" id="step9_cnt" value="<?=$step9_cnt?>">
      <input type="hidden" id="goods_box_cnt" value="<?=$goods_box_cnt?>">
	  <input type="file" title="행사이미지 파일" id="event_file" onchange="imgEventChange(this);" class="upload-hidden" accept=".jpg, .jepg, .png, .gif" style="display:none;">
      <p class="smart_info_text">
        <i class="xi-plus-square-o"></i> 하단 버튼을 클릭하시면 더 많은 <span>행사코너를 추가</span>하실 수 있습니다.
      </p>
      <div id="smart_btn_group" class="smart_btn_group">
        <span><a href="javascript:goods_area_append('step2');"><i class="xi-library-image-o"></i> 2단 이미지형 행사코너 추가</a></span>
        <span><a href="javascript:goods_area_append('step4');"><i class="xi-library-image-o"></i> 3단 이미지형 행사코너 추가</a></span>
        <span><a href="javascript:goods_area_append('step5');"><i class="xi-library-image-o"></i> 4단 이미지형 행사코너 추가</a></span>
        <span><a href="javascript:goods_area_append('step3');"><i class="xi-library-books-o"></i> 1단 텍스트형 행사코너 추가</a></span>
        <!--<span><a href="javascript:goods_area_append('step9');"><i class="xi-image-o"></i> 행사이미지 직접추가</a></span>-->
        <label for="event_file" style="cursor:pointer;"><span><i class="xi-image-o"></i> 행사이미지 직접추가</span></label>
        <span><a href="javascript:modal_sort_open(1);"><i class="xi-code"></i> 전체 행사코너 순서변경</a></span>
      </div>
    </div><!--//wl_lbox-->
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// 스마트전단 미리보기 [S]
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
    <div class="wl_rbox">

      <div class="wl_r_preview_n">
        <div class="pre_box_wrap">
          <!--템플릿 이미지가 들어갈 공간-->
          <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('<?=($screen_data->tem_imgpath == "") ? "/dhn/images/leaflets/tem/tem01.jpg" : $screen_data->tem_imgpath?>') no-repeat top center;background-color:<?=($screen_data->tem_bgcolor == "") ? "#47b5e5" : $screen_data->tem_bgcolor?>;"></div>
          <!--//템플릿 이미지가 들어갈 공간-->
          <div class="pre_box1">
            <p id="pre_wl_tit" class="pre_tit"><?=($screen_data->psd_title == "") ? "행사제목을 입력해주세요." : $screen_data->psd_title?></p>
            <p id="pre_wl_date" class="pre_date"><?=($screen_data->psd_date == "") ? "행사기간을 입력해주세요." : $screen_data->psd_date?></p>
          </div>
          <p id="tem_self_img" class="img100" style="display:<?=($screen_data->tem_useyn == "S") ? "" : "none"?>;">
            <img src="<?=$screen_data->tem_imgpath?>">
          </p>
          <? //화면 미리보기 할인&대표상품 [S] ------------------------------------------------------------------------------------------------ ?>
          <div class="pre_box2<?=($screen_data->tem_useyn == "S") ? " mg_t0" : ""?>" id="pre_goods_list_step1" style="display:<?=($psd_step1_yn == "N") ? "none" : ""?>;">
            <!--상품추가시 looping-->
            <section id="pre_area_goods_step1">
		      <?
				$ii = 0;
				if($psd_step1_yn != "N"){
					foreach($screen_step1 as $r) {
						$badge_rate = "";
						if($r->psg_price != "" && $r->psg_dcprice != ""){
							$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
							$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
							$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
						}
						$psg_dcprice = $r->psg_dcprice; //STEP1 할인가
						if($psg_dcprice != ""){
							$psg_dcprice_last = mb_substr($r->psg_dcprice, -1, NULL, "UTF-8"); //할인가 마지막 단어
							if($psg_dcprice_last == "원"){ //할인가 마지막 단어가 원인 경우
								$psg_dcprice = mb_substr($r->psg_dcprice, 0 , -1, "UTF-8") . "<span class='sm_small'>원</span>"; //할인가 원에 class 주기
							}
						}
		      ?>
		      <dl id="pre_step1_<?=$ii?>">
								<? //장바구니 버튼 품절처리 - 2021.06.17
									if($r->psg_stock == "N"){
										if($mem_stmall_yn == "Y"){
											if($psd_order_yn == "Y"){
													$button_add_cart = "<button class='icon_add_cart' name='nostock' style='display:none;'>장바구니</button>";
											}
										}
									}else{
										if($mem_stmall_yn == "Y"){
											if($psd_order_yn == "Y"){
													$button_add_cart = "<button class='icon_add_cart' name='yesstock'>장바구니</button>";
											}
										}
									}
								?>
                                <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                                    $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                                    $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                                    $findthumb = strripos($fileNameWithoutExt, '_thumb');
                                    //echo $fileNameWithoutExt."---".$file_ext;
                                    $plustext = "_thumb.";
                                    if($findthumb>0){
                                        $plustext = ".";
                                    }
                                    $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                                }else{
                                    $imgnamepext = "";
                                }  ?>
                <?=$button_add_cart?>
                <dt>
				  <div id="pre_goods_badge_step1_<?=$ii?>" <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge"<? }else{ ?>class="design_badge"<? } ?>"><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img id="pre_badge_imgpath_step1_<?=$ii?>" src="<?=$r->badge_imgpath?>"><? } ?></div>
				  <div id="pre_div_preview_step1_<?=$ii?>" class="templet_img_in3<?=($r->psg_stock == "N") ? " soldout" : ""?>" style="background-image : url('<?=($r->psg_imgpath == "") ? $sample_imgpath : $imgnamepext?>');">
				    <img id="pre_img_preview_step1_<?=$ii?>" style="display:none;">
				  </div>
                </dt>
			    <dd>
                  <ul>
                    <li class="price1" id="pre_goods_price_step1_<?=$ii?>"><?=$r->psg_price?></li><?//STEP1 정상가?>
                    <li class="price2" id="pre_goods_dcprice_step1_<?=$ii?>"  <?=(strlen($r->psg_dcprice)>5)? (((50-((strlen($r->psg_dcprice)-5)*2)<40))? "style='font-size:40px;'" : "style='font-size:".(50-((strlen($r->psg_dcprice)-5)*2))."px'") : "" ?>><?=$psg_dcprice?></li><?//STEP1 할인가?>
                    <li><span class="pop_option" id="pre_goods_option2_step1_<?=$ii?>" style="display:<?=($r->psg_option2 != '') ? '' : 'none'?>;"><?=$r->psg_option2?></span></li><?//STEP1 옵션?>
                    <li class="name" <?=(strlen($r->psg_name.$r->psg_option)>30)? "style='font-size:20px'" : "" ?>>
					  <span id="pre_goods_name_step1_<?=$ii?>"><?=$r->psg_name?></span><?//STEP1 상품명?>
					  <span id="pre_goods_option_step1_<?=$ii?>"><?=$r->psg_option?></span><?//STEP1 규격?>
					  <span id="pre_goods_badge_cd_step1_<?=$ii?>" style="display:none;"><?=$r->psg_badge?></span><?//STEP1 뱃지?>
                      <div id="pre_goods_barcode_step1_<?=$ii?>" style="display:none;"><?=($r->psg_code == "") ? '' : $r->psg_code?></div><?//2021-06-01 바코드 위치변경시 필요?>
					</li>
				    <li id="pre_goods_imgpath_step1_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? $sample_imgpath : $r->psg_imgpath?></li><?//STEP1 이미지경로?>
                  </ul>
                </dd>
              </dl>
		      <?
						$ii++;
					} //foreach($screen_step1 as $r) {
				} //if($psd_step1_yn != "N"){
		      ?>
		    </section>
            <!--//상품추가시 looping-->
          </div><!--//pre_box2-->
          <? //화면 미리보기 할인&대표상품 [E] ------------------------------------------------------------------------------------------------ ?>
        </div><!--//pre_box_wrap-->
		<? //화면 미리보기 박스별 샘플 목록 [S] ------------------------------------------------------------------------------------------------ ?>
		<div class="pre_box_wrap" id="pre_box_sample">
			<? //화면 미리보기 2단 이미지형 [S] ------------------------------------------------------------------------------------------------ ?>
			<div class="pre_box3" id="pre_goods_list_step2-0" style="display:none;">
  			  <p class="pre_tit" id="pre_tit_id_step2-0">
			    <img src="<?=$title_img_imgpath?>"><?//이미지형 타이틀 이미지 ?>
  			  </p>
              <div class="pop_list_wrap" id="pre_area_goods_step2-0">
                <!--상품추가시 looping-->
                <? for($ii = 0; $ii < $step2_std; $ii++){ ?>
                 <div class="pop_list01" id="pre_step2-0_<?=$ii?>">
                  <div id="pre_goods_badge_step2-0_<?=$ii?>"style="display:none;"></div>
                  <div id="pre_div_preview_step2-0_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=$sample_imgpath?>');"><?//이미지형 상품 이미지 ?>
                    <img id="pre_img_preview_step2-0_<?=$ii?>" style="display:none;">
                  </div>
                  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step2-0_<?=$ii?>">4,000원</p><?//이미지형 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step2-0_<?=$ii?>">3,000원</p><?//이미지형 할인가?>
                  </div>
                  <div><span class="pop_option" id="pre_goods_option2_step2-0_<?=$ii?>" style="display:none;"></span></div><?//이미지형 옵션?>
                  <div class="pop_name">
                    <span id="pre_goods_name_step2-0_<?=$ii?>">상품명</span><?//이미지형 상품명?>
                    <span id="pre_goods_option_step2-0_<?=$ii?>">규격</span><?//이미지형 규격?>
					<span id="pre_goods_badge_cd_step2-0_<?=$ii?>" style="display:none;">0</span><?//행사뱃지번호?>
                    <div id="pre_goods_imgpath_step2-0_<?=$ii?>" style="display:none;"></div><?//이미지형 이미지경로?>
                    <div id="pre_goods_barcode_step2-0_<?=$ii?>" style="display:none;"></div><?//2021-06-01 바코드 위치변경시 필요?>
                  </div>
                </div>
                <? } ?>
                <!--상품추가시 looping-->
              </div><!--//pop_list_wrap-->
            </div>
			<? //화면 미리보기 2단 이미지형 [E] ------------------------------------------------------------------------------------------------ ?>
			<? //화면 미리보기 3단 이미지형 [S] ------------------------------------------------------------------------------------------------ ?>
			<div class="pre_box3" id="pre_goods_list_step4-0" style="display:none;">
  			  <p class="pre_tit" id="pre_tit_id_step4-0">
			    <img src="<?=$title_img_imgpath?>"><?//이미지형 타이틀 이미지 ?>
  			  </p>
              <div class="pop_list_wrap" id="pre_area_goods_step4-0">
                <!--상품추가시 looping-->
                <? for($ii = 0; $ii < $step4_std; $ii++){ ?>
                 <div class="pop_list03" id="pre_step4-0_<?=$ii?>">
                  <div id="pre_goods_badge_step4-0_<?=$ii?>"style="display:none;"></div>
                  <div id="pre_div_preview_step4-0_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=$sample_imgpath?>');"><?//이미지형 상품 이미지 ?>
                    <img id="pre_img_preview_step4-0_<?=$ii?>" style="display:none;">
                  </div>
                  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step4-0_<?=$ii?>">4,000원</p><?//이미지형 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step4-0_<?=$ii?>">3,000원</p><?//이미지형 할인가?>
                  </div>
                  <div><span class="pop_option" id="pre_goods_option2_step4-0_<?=$ii?>" style="display:none;"></span></div><?//이미지형 옵션?>
                  <div class="pop_name">
                    <span id="pre_goods_name_step4-0_<?=$ii?>">상품명</span><?//이미지형 상품명?>
                    <span id="pre_goods_option_step4-0_<?=$ii?>">규격</span><?//이미지형 규격?>
					<span id="pre_goods_badge_cd_step4-0_<?=$ii?>" style="display:none;">0</span><?//행사뱃지번호?>
                    <div id="pre_goods_imgpath_step4-0_<?=$ii?>" style="display:none;"></div><?//이미지형 이미지경로?>
                    <div id="pre_goods_barcode_step4-0_<?=$ii?>" style="display:none;"></div><?//2021-06-01 바코드 위치변경시 필요?>
                  </div>
                </div>
                <? } ?>
                <!--상품추가시 looping-->
              </div><!--//pop_list_wrap-->
            </div>
			<? //화면 미리보기 3단 이미지형 [E] ------------------------------------------------------------------------------------------------ ?>
			<? //화면 미리보기 4단 이미지형 [S] ------------------------------------------------------------------------------------------------ ?>
			<div class="pre_box3" id="pre_goods_list_step5-0" style="display:none;">
  			  <p class="pre_tit" id="pre_tit_id_step5-0">
			    <img src="<?=$title_img_imgpath?>"><?//이미지형 타이틀 이미지 ?>
  			  </p>
              <div class="pop_list_wrap" id="pre_area_goods_step5-0">
                <!--상품추가시 looping-->
                <? for($ii = 0; $ii < $step5_std; $ii++){ ?>
                 <div class="pop_list04" id="pre_step5-0_<?=$ii?>">
                  <div id="pre_goods_badge_step5-0_<?=$ii?>"style="display:none;"></div>
                  <div id="pre_div_preview_step5-0_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=$sample_imgpath?>');"><?//이미지형 상품 이미지 ?>
                    <img id="pre_img_preview_step5-0_<?=$ii?>" style="display:none;">
                  </div>
                  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step5-0_<?=$ii?>">4,000원</p><?//이미지형 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step5-0_<?=$ii?>">3,000원</p><?//이미지형 할인가?>
                  </div>
                  <div><span class="pop_option" id="pre_goods_option2_step5-0_<?=$ii?>" style="display:none;"></span></div><?//이미지형 옵션?>
                  <div class="pop_name">
                    <span id="pre_goods_name_step5-0_<?=$ii?>">상품명</span><?//이미지형 상품명?>
                    <span id="pre_goods_option_step5-0_<?=$ii?>">규격</span><?//이미지형 규격?>
					<span id="pre_goods_badge_cd_step5-0_<?=$ii?>" style="display:none;">0</span><?//행사뱃지번호?>
                    <div id="pre_goods_imgpath_step5-0_<?=$ii?>" style="display:none;"></div><?//이미지형 이미지경로?>
                    <div id="pre_goods_barcode_step5-0_<?=$ii?>" style="display:none;"></div><?//2021-06-01 바코드 위치변경시 필요?>
                  </div>
                </div>
                <? } ?>
                <!--상품추가시 looping-->
              </div><!--//pop_list_wrap-->
            </div>
			<? //화면 미리보기 4단 이미지형 [E] ------------------------------------------------------------------------------------------------ ?>
			<div class="pre_box4" id="pre_goods_list_step3-0" style="display:none;">
              <p class="pre_tit" id="pre_tit_id_step3-0">
                <img src="<?=$title_text_imgpath?>"><?//텍스트형 타이틀 이미지 ?>
              </p>
              <div class="pop_list_wrap" id="pre_area_goods_step3-0">
                <!--상품추가시 looping-->
                <? for($ii = 0; $ii < $step3_std; $ii++){ ?>
                <div class="pop_list02" id="pre_step3-0_<?=$ii?>">
                  <div class="name">
                    <span id="pre_goods_name_step3-0_<?=$ii?>">상품명</span><?//텍스트형 상품명?>
                    <span id="pre_goods_option_step3-0_<?=$ii?>">규격</span><?//텍스트형 규격?>
                  </div>
                  <span class="price1" id="pre_goods_price_step3-0_<?=$ii?>">4,000원</span><?//텍스트형 정상가?>
                  <span class="price2" id="pre_goods_dcprice_step3-0_<?=$ii?>">3,000원</span><?//텍스트형 할인가?>
                  <div id="pre_goods_barcode_step3-0_<?=$ii?>" style="display:none;"></div><?//2021-06-01 바코드 위치변경시 필요?>
                </div>
                <? } //for($ii = 0; $ii < $step3_std; $ii++){ ?>
                <!--상품추가시 looping-->
              </div>
            </div>
            <div class="pre_box4" id="pre_goods_list_step9-0" style="display:none;">
              <p class="pre_tit" id="pre_tit_id_step9-0">
                <img id="pre_goods_img_step9-0" src="<?//=$title_event_imgpath?>"><?//행사이미지 타이틀 이미지 ?>
              </p>
            </div>
			<? //화면 미리보기 박스별 샘플 목록 [E] ------------------------------------------------------------------------------------------------ ?>
		</div><!--//pre_box_sample-->
		<? //화면 미리보기 박스별 샘플 목록 [S] ------------------------------------------------------------------------------------------------ ?>
        <div class="pre_box_wrap" id="pre_box_area">
            <? //화면 미리보기 박스별 상품 목록 [S] ------------------------------------------------------------------------------------------------ ?>
			<?
				$no = 0; //순번
				$ii = 0; //코너내 순번
				$seq = 0; //코너번호
				$box_seq = 0; //박스번호
				$step2_seq = 0; //이미지형 코너번호
				$step2_seq = 0; //텍스트형 코너번호
				$step9_seq = 0; //행사이미지 코너번호
				$chk_step_no = -1; //코너별 고유번호
				if(!empty($screen_box)){
					foreach($screen_box as $r) {
						$no++;
						$psg_step = $r->psg_step; //스텝(1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형)
						if($psg_step == "2"){ //2단 이미지형
							$badge_rate = "";
							if($r->psg_price != "" && $r->psg_dcprice != ""){
								$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
								$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
								$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
							}
							if($r->psg_step_no != $chk_step_no){
								$ii = 0;
								$box_seq++; //박스번호
								$step2_seq++; //이미지형 코너번호
								$seq = $step2_seq; //코너번호
			?>
			<div class="pre_box3" id="pre_goods_box-<?=$box_seq?>">
			  <p class="pre_tit" id="pre_tit_id_step2-<?=$seq?>"><? if($r->tit_imgpath != ""){ ?><img src="<?=$r->tit_imgpath?>"><? } ?><?//이미지형 타이틀 이미지 ?></p>
              <div class="pop_list_wrap" id="pre_area_goods_step2-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
							} //if($r->psg_step_no != $chk_step_no){
                ?>
				<div class="pop_list01" id="pre_step2-<?=$seq?>_<?=$ii?>">
					<? //장바구니 버튼 품절처리 - 2021.06.17
						if($r->psg_stock == "N"){
							if($mem_stmall_yn == "Y"){
								if($psd_order_yn == "Y"){
										$button_add_cart = "<button class='icon_add_cart' name='nostock' style='display:none;'>장바구니</button>";
								}
							}
						}else{
							if($mem_stmall_yn == "Y"){
								if($psd_order_yn == "Y"){
										$button_add_cart = "<button class='icon_add_cart' name='yesstock'>장바구니</button>";
								}
							}
						}
					?>
                    <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                        $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                        $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                        $findthumb = strripos($fileNameWithoutExt, '_thumb');
                        //echo $fileNameWithoutExt."---".$file_ext;
                        $plustext = "_thumb.";
                        if($findthumb>0){
                            $plustext = ".";
                        }
                        $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                    }else{
                        $imgnamepext = "";
                    }  ?>
				  <?=$button_add_cart?>
				  <div id="pre_goods_badge_step2-<?=$seq?>_<?=$ii?>" <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge"<? }else{ ?>class="design_badge"<? } ?>"><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img id="pre_badge_imgpath_step2-<?=$seq?>_<?=$ii?>" src="<?=$r->badge_imgpath?>"><? } ?></div>
				  <div id="pre_div_preview_step2-<?=$seq?>_<?=$ii?>" class="templet_img_in3<?=($r->psg_stock == "N") ? " soldout" : ""?>" style="background-image : url('<?=($r->psg_imgpath == "") ? $sample_imgpath : $imgnamepext?>');">
				    <img id="pre_img_preview_step2-<?=$seq?>_<?=$ii?>" style="display:none;">
				  </div>
				  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></p><?//이미지형 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step2-<?=$seq?>_<?=$ii?>"  <?=(strlen($r->psg_dcprice)>5)? (((42-((strlen($r->psg_dcprice)-5)*2)<30))? "style='font-size:30px;'" : "style='font-size:".(42-((strlen($r->psg_dcprice)-5)*2))."px'") : "" ?>><?=$r->psg_dcprice?></p><?//이미지형 할인가?>
				  </div>
				  <div><span class="pop_option" id="pre_goods_option2_step2-<?=$seq?>_<?=$ii?>" style="display:<?=($r->psg_option2 != "") ? "" : "none"?>;"><?=$r->psg_option2?></span></div><?//이미지형 옵션?>
				  <div class="pop_name">
                    <span id="pre_goods_name_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//이미지형 상품명?>
                    <span id="pre_goods_option_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//이미지형 규격?>
					<span id="pre_goods_badge_cd_step2-<?=$seq?>_<?=$ii?>" style="display:none;"><?=$r->psg_badge?></span><?//행사뱃지번호?>
					<div id="pre_goods_imgpath_step2-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? $sample_imgpath : $r->psg_imgpath?></div><?//이미지형 이미지경로?>
					<div id="pre_goods_barcode_step2-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_code == "") ? '' : $r->psg_code?></div><?//2021-06-01 바코드?>
                  </div>
                </div>
                <?
							$ii++; //코너별 등록수
							if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
  			  </div><!--//pop_list_wrap-->
            </div>
			<?
							} //if($ii >= $r->rownum){
							$chk_step_no = $r->psg_step_no;
						}else if($psg_step == "4"){ //3단 이미지형
							$badge_rate = "";
							if($r->psg_price != "" && $r->psg_dcprice != ""){
								$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
								$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
								$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
							}
							if($r->psg_step_no != $chk_step_no){
								$ii = 0;
								$box_seq++; //박스번호
								$step4_seq++; //이미지형 코너번호
								$seq = $step4_seq; //코너번호
			?>
			<div class="pre_box3" id="pre_goods_box-<?=$box_seq?>">
			  <p class="pre_tit" id="pre_tit_id_step4-<?=$seq?>"><? if($r->tit_imgpath != ""){ ?><img src="<?=$r->tit_imgpath?>"><? } ?><?//이미지형 타이틀 이미지 ?></p>
              <div class="pop_list_wrap" id="pre_area_goods_step4-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
							} //if($r->psg_step_no != $chk_step_no){
                ?>
				<div class="pop_list03" id="pre_step4-<?=$seq?>_<?=$ii?>">
					<? //장바구니 버튼 품절처리 - 2021.06.17
						if($r->psg_stock == "N"){
							if($mem_stmall_yn == "Y"){
								if($psd_order_yn == "Y"){
										$button_add_cart = "<button class='icon_add_cart' name='nostock' style='display:none;'>장바구니</button>";
								}
							}
						}else{
							if($mem_stmall_yn == "Y"){
								if($psd_order_yn == "Y"){
										$button_add_cart = "<button class='icon_add_cart' name='yesstock'>장바구니</button>";
								}
							}
						}
					?>
                    <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                        $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                        $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                        $findthumb = strripos($fileNameWithoutExt, '_thumb');
                        //echo $fileNameWithoutExt."---".$file_ext;
                        $plustext = "_thumb.";
                        if($findthumb>0){
                            $plustext = ".";
                        }
                        $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                    }else{
                        $imgnamepext = "";
                    }  ?>
				  <?=$button_add_cart?>
				  <div id="pre_goods_badge_step4-<?=$seq?>_<?=$ii?>" <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge"<? }else{ ?>class="design_badge"<? } ?>"><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img id="pre_badge_imgpath_step4-<?=$seq?>_<?=$ii?>" src="<?=$r->badge_imgpath?>"><? } ?></div>
				  <div id="pre_div_preview_step4-<?=$seq?>_<?=$ii?>" class="templet_img_in3<?=($r->psg_stock == "N") ? " soldout" : ""?>" style="background-image : url('<?=($r->psg_imgpath == "") ? $sample_imgpath : $imgnamepext?>');">
				    <img id="pre_img_preview_step4-<?=$seq?>_<?=$ii?>" style="display:none;">
				  </div>
				  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step4-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></p><?//이미지형 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step4-<?=$seq?>_<?=$ii?>" <?=(strlen($r->psg_dcprice)>5)? (((32-((strlen($r->psg_dcprice)-5)*1.3)<22))? "style='font-size:22px;'" : "style='font-size:".(32-((strlen($r->psg_dcprice)-5)*1.3))."px'") : "" ?>><?=$r->psg_dcprice?></p><?//이미지형 할인가?>
				  </div>
				  <div><span class="pop_option" id="pre_goods_option2_step4-<?=$seq?>_<?=$ii?>" style="display:<?=($r->psg_option2 != "" && $r->psg_step < 3) ? "" : "none"?>;"><?=$r->psg_option2?></span></div><?//이미지형 옵션?>
				  <div class="pop_name">
                    <span id="pre_goods_name_step4-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//이미지형 상품명?>
                    <span id="pre_goods_option_step4-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//이미지형 규격?>
					<span id="pre_goods_badge_cd_step4-<?=$seq?>_<?=$ii?>" style="display:none;"><?=$r->psg_badge?></span><?//행사뱃지번호?>
					<div id="pre_goods_imgpath_step4-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? $sample_imgpath : $r->psg_imgpath?></div><?//이미지형 이미지경로?>
					<div id="pre_goods_barcode_step4-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_code == "") ? '' : $r->psg_code?></div><?//2021-06-01 바코드?>
                  </div>
                </div>
                <?
							$ii++; //코너별 등록수
							if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
  			  </div><!--//pop_list_wrap-->
            </div>
			<?
							} //if($ii >= $r->rownum){
							$chk_step_no = $r->psg_step_no;
						}else if($psg_step == "5"){ //4단 이미지형
							$badge_rate = "";
							if($r->psg_price != "" && $r->psg_dcprice != ""){
								$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
								$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
								if($goods_price != "" && $goods_dcprice){
									$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
								}
							}
							if($r->psg_step_no != $chk_step_no){
								$ii = 0;
								$box_seq++; //박스번호
								$step5_seq++; //이미지형 코너번호
								$seq = $step5_seq; //코너번호
			?>
			<div class="pre_box3" id="pre_goods_box-<?=$box_seq?>">
			  <p class="pre_tit" id="pre_tit_id_step5-<?=$seq?>"><? if($r->tit_imgpath != ""){ ?><img src="<?=$r->tit_imgpath?>"><? } ?><?//이미지형 타이틀 이미지 ?></p>
              <div class="pop_list_wrap" id="pre_area_goods_step5-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
							} //if($r->psg_step_no != $chk_step_no){
                ?>
				<div class="pop_list04" id="pre_step5-<?=$seq?>_<?=$ii?>">
					<? //장바구니 버튼 품절처리 - 2021.06.17
						if($r->psg_stock == "N"){
							if($mem_stmall_yn == "Y"){
								if($psd_order_yn == "Y"){
										$button_add_cart = "<button class='icon_add_cart' name='nostock' style='display:none;'>장바구니</button>";
								}
							}
						}else{
							if($mem_stmall_yn == "Y"){
								if($psd_order_yn == "Y"){
										$button_add_cart = "<button class='icon_add_cart' name='yesstock'>장바구니</button>";
								}
							}
						}
					?>
                    <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                        $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                        $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                        $findthumb = strripos($fileNameWithoutExt, '_thumb');
                        //echo $fileNameWithoutExt."---".$file_ext;
                        $plustext = "_thumb.";
                        if($findthumb>0){
                            $plustext = ".";
                        }
                        $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                    }else{
                        $imgnamepext = "";
                    }  ?>
				  <?=$button_add_cart?>
				  <div id="pre_goods_badge_step5-<?=$seq?>_<?=$ii?>" <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge"<? }else{ ?>class="design_badge"<? } ?>"><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img id="pre_badge_imgpath_step5-<?=$seq?>_<?=$ii?>" src="<?=$r->badge_imgpath?>"><? } ?></div>
				  <div id="pre_div_preview_step5-<?=$seq?>_<?=$ii?>" class="templet_img_in3<?=($r->psg_stock == "N") ? " soldout" : ""?>" style="background-image : url('<?=($r->psg_imgpath == "") ? $sample_imgpath : $imgnamepext?>');">
				    <img id="pre_img_preview_step5-<?=$seq?>_<?=$ii?>" style="display:none;">
				  </div>
				  <div class="pop_price">
                    <p class="price1" id="pre_goods_price_step5-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></p><?//이미지형 정상가?>
                    <p class="price2" id="pre_goods_dcprice_step5-<?=$seq?>_<?=$ii?>" <?=(strlen($r->psg_dcprice)>5)? (((26-((strlen($r->psg_dcprice)-5)*1.3)<17))? "style='font-size:17px;'" : "style='font-size:".(26-((strlen($r->psg_dcprice)-5)*1.3))."px'") : "" ?>><?=$r->psg_dcprice?></p><?//이미지형 할인가?>
				  </div>
				  <div><span class="pop_option" id="pre_goods_option2_step5-<?=$seq?>_<?=$ii?>" style="display:<?=($r->psg_option2 != "" && $r->psg_step < 3) ? "" : "none"?>;"><?=$r->psg_option2?></span></div><?//이미지형 옵션?>
				  <div class="pop_name">
                    <span id="pre_goods_name_step5-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//이미지형 상품명?>
                    <span id="pre_goods_option_step5-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//이미지형 규격?>
					<span id="pre_goods_badge_cd_step5-<?=$seq?>_<?=$ii?>" style="display:none;"><?=$r->psg_badge?></span><?//행사뱃지번호?>
					<div id="pre_goods_imgpath_step5-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? $sample_imgpath : $r->psg_imgpath?></div><?//이미지형 이미지경로?>
					<div id="pre_goods_barcode_step5-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_code == "") ? '' : $r->psg_code?></div><?//2021-06-01 바코드?>
                  </div>
                </div>
                <?
							$ii++; //코너별 등록수
							if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
  			  </div><!--//pop_list_wrap-->
            </div>
			<?
							} //if($ii >= $r->rownum){
							$chk_step_no = $r->psg_step_no;
						}else if($psg_step == "3"){ //텍스트형
							if($r->psg_step_no != $chk_step_no){
								$ii = 0;
								$box_seq++; //박스번호
								$step3_seq++; //텍스트형 코너번호
								$seq = $step3_seq;
			?>
			<div class="pre_box4" id="pre_goods_box-<?=$box_seq?>">
              <p class="pre_tit" id="pre_tit_id_step3-<?=$seq?>"><? if($r->tit_imgpath != ""){ ?><img src="<?=$r->tit_imgpath?>"><? } ?><?//텍스트형 타이틀 이미지 ?></p>
              <div class="pop_list_wrap" id="pre_area_goods_step3-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
							} //if($r->psg_step_no != $chk_step_no){
                ?>
                <div class="pop_list02 <?=($psd_order_yn=='Y')? " cartplus_t" : "cartplus" ?><?=($r->psg_stock == "N") ? " soldout" : ""?>" id="pre_step3-<?=$seq?>_<?=$ii?>">
                  <div class="name">
                    <span id="pre_goods_name_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//텍스트형 상품명?>
                    <span id="pre_goods_option_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//텍스트형 규격?>
                  </div>
                    <span class="price1" id="pre_goods_price_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></span><?//텍스트형 정상가?>
                  <span class="price2" id="pre_goods_dcprice_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_dcprice?></span><?//텍스트형 할인가?>
                  <div id="pre_goods_barcode_step3-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_code == "") ? '' : $r->psg_code?></div><?//2021-06-01 바코드?>
									<? //장바구니 버튼 품절처리 - 2021.06.17
										if($r->psg_stock == "N"){
											if($mem_stmall_yn == "Y"){
												if($psd_order_yn == "Y"){
														$button_add_cart = "<button class='icon_add_cart' name='nostock' style='display:none;'>장바구니</button>";
												}
											}
										}else{
											if($mem_stmall_yn == "Y"){
												if($psd_order_yn == "Y"){
														$button_add_cart = "<button class='icon_add_cart' name='yesstock'>장바구니</button>";
												}
											}
										}
									?>
                  <?=$button_add_cart?>
                </div>
                <?
							$ii++; //코너별 등록수
							if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
              </div>
		    </div>
			<?
							} //if($ii >= $r->rownum){
							$chk_step_no = $r->psg_step_no;
						}else if($psg_step == "9"){ //행사이미지
							$ii = 0;
							$box_seq++; //박스번호
							$step9_seq++; //행사이미지 코너번호
							$seq = $step9_seq;
			?>
			<div class="pre_box4" id="pre_goods_box-<?=$box_seq?>">
              <p class="pre_tit" id="pre_tit_id_step9-<?=$seq?>"><? if($r->psg_imgpath != ""){ ?><img src="<?=$r->psg_imgpath?>"><? } ?><?//행사이미지 타이틀 이미지 ?></p>
		    </div>
			<?
						} //}else if($psg_step == "9"){ //행사이미지
					} //foreach($screen_box as $r) {
				} //if(!empty($screen_box)){
			?>
			<? //화면 미리보기 박스별 상품 목록 [E] ------------------------------------------------------------------------------------------------ ?>
        </div><!--//pre_box_area-->
      </div><!--//wl_r_preview-->
    </div><!--//wl_rbox-->
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// 스마트전단 미리보기 [E]
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
    <div class="write_leaflets_btn">
      <button type="button" class="pop_btn_save" onClick="saved('');"><span class="material-icons">save</span> 저장하기</button>
      <button type="button" class="pop_btn_send" onClick="saved('lms');"><span class="material-icons">forward_to_inbox</span> 문자발송</button>
      <button type="button" class="pop_btn_send" onClick="saved('talk_adv');"><span class="material-icons">chat_bubble_outline</span> 알림톡발송</button>
      <button type="button" class="pop_btn_cancel" onclick="list();"><span class="material-icons">highlight_off</span> 목록으로</button>
     <!-- <a href="#dh_myBtn" class="btn_top" title="위로"><img src="/images/icon_arrow_wu.png" alt="위로"></a>
      <a href="#smart_btn_group" class="btn_bot" title="아래"><img src="/images/icon_arrow_wd.png" alt="아래"></a>-->
      <a onClick="$('#dh_myBtn').attr('tabindex', -1).focus();" class="btn_top" title="위로"><img src="/images/icon_arrow_wu.png" alt="위로"></a>
      <a onClick="$('#smart_btn_group').attr('tabindex', -1).focus();" class="btn_bot" title="아래"><img src="/images/icon_arrow_wd.png" alt="아래"></a>
    </div>
  </div><!--//write_leaflets-->
</div><!--//wrap_leaflets-->
<!-- 이미지 템플릿선택 Modal -->
<div id="dh_myModal1" class="dh_modal">
  <div class="modal-content">
	<span id="dh_close" class="dh_close">&times;</span>
	<p class="modal-tit">이미지 템플릿선택</p>
	<!--<span class="btn_smart"><a href="javascript:template_self_append();">템플릿 직접입력</a></span>-->
	<label for="template_self_file" style="cursor:pointer;"><span class="btn_smart">템플릿 직접입력</span></label>
	<input type="file" title="행사이미지 파일" id="template_self_file" onchange="template_self_img(this);" accept=".jpg, .jepg, .png, .gif" style="display:none;">
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
			<button onclick="showLibrary('goods');" class="goods_pos" style="margin-left:10px;cursor:pointer;">최근상품</button>
			<button onclick="showLibrary('keep');" class="goods_my" style="margin-left:10px;cursor:pointer;">이미지보관함</button>
			<ul>
				<li>1. 내사진 : <span>내 PC에 저장된 이미지</span>를 등록합니다.</li>
				<li>2. 이미지선택 : <span>지니 라이브러리에 있는 이미지</span>를 선택합니다.</li>
				<li>3. 최근상품 : <span>최근 등록한 상품정보</span>를 불러옵니다.</li>
				<li>4. 이미지보관함 : <span>이미지보관함에 등록한 상품이미지</span>를 선택합니다.</li>
			</ul>
		</div>
	</div>
</div>
<!-- 라이브러리 Modal -->
<div id="dh_myModal3" class="dh_modal">
	<div class="modal-content">
		<span id="dh_close3" class="dh_close">&times;</span>
		<p class="modal-tit"><span id="id_modal_title">이미지 라이브러리</span> (<span id="id_modal_count">0</span>건)</p>
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
        <? $ieYn="Y" ?>
		<ul id="library_append_list" class="library_append_list"<? if($ieYn == "Y"){ ?> style="height:440px;"<? } ?>><?//라이브러리 리스트 영역?>
		</ul>
		<? if($ieYn == "Y"){ ?>
		<div id="library_page" class="page_cen" style="margin-top:10px;"></div><?//라이브러리 페이징 영역?>
		<? } ?>
	</div>
</div>
<!-- 포스 상품조회 Modal -->
<div id="pos_goods_modal" class="dh_modal">
	<div class="modal-content">
		<span id="pos_goods_close" class="dh_close">&times;</span>
		<p class="modal-tit"><span id="id_pos_title">포스 상품조회</span> (<span id="id_pos_count">0</span>건)</p>
		<div class="search_input2">
			<input type="hidden" id="pos_stepNo">
			<input type="hidden" id="pos_id">
			<select id="pos_sort" style="margin-left:5px;" onchange="pos_goods_search();">
				<option value="id">등록순</option>
				<option value="name">상품명순</option>
			</select>
			<select id="pos_sc" style="margin-left:5px;">
				<option value="name">상품명</option>
				<option value="code">상품코드</option>
			</select>
			<input type="search" id="pos_sv" placeholder="검색어를 입력하세요." style="width:320px;">
			<button onclick="pos_goods_search();" class="btn_search_s">
				<i class="material-icons">search</i>
			</button>
			<button class="btn_pos_goods" onclick="pos_goods_send();"><i class="material-icons">save_alt</i> 선택상품 불러오기</button>
		</div>
		<ul id="pos_goods_list" class="library_append_list"><?//포스 상품조회 리스트 영역?>
		</ul>
	</div>
</div>
<!-- 행사코너 순서변경 Modal -->
<div id="modal_sort" class="dh_modal">
	<div class="modal-content">
		<span id="close_sort" class="dh_close">&times;</span>
		<p class="modal-tit">행사코너 순서변경</p>
		<ul id="modal_sort_list" class="corner_move_list"><?//행사코너 순서변경 리스트 영역?>
		</ul>
		<p class="btn_al_cen">
		  <button class="btn_st3" type="button" onclick="modal_sort_ok();"><i class="xi-check"></i> 적용하기</button>
		</p>
	</div>
</div>
<!-- 행사뱃지 선택 모달 -->
<div id="modal_badge" class="dh_modal">
  <div class="modal-content">
    <span id="close_badge" class="dh_close">×</span>
    <p class="modal-tit">행사뱃지 선택</p>
    <ul class="badge_list" id="modal_badge_list"><?//행사뱃지 리스트 영역?>
    </ul>
  </div>
</div>
<!-- POP인쇄 Modal -->
<!-- <div id="popModal" class="dh_modal">
  <div class="modal-content500">
    <span id="popClose" class="dh_close">&times;</span>
    <p class="modal-tit">POP 인쇄하기</p>
    <input type="hidden" id="pop_id" value="">
    <ul id="modal_pop1" class="modal-list"><?//1단 인쇄?>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_01.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_01');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_05.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_05');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_01.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_01');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_02.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_02');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_02.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_02');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_06.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_06');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_03.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_03');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_03.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_03');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_08.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_08');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_04.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_04');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_04.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_04');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_07.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_07');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_05.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_05');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
    </ul>
    <ul id="modal_pop2" class="modal-list" style="display:none;"><?//2단 인쇄?>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_02.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_02');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_02.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_02');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_06.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_06');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
    </ul>
    <ul id="modal_pop3" class="modal-list" style="display:none;"><?//3단 인쇄?>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_03.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_03');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_03.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_03');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_08.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_08');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
    </ul>
    <ul id="modal_pop4" class="modal-list" style="display:none;"><?//4단 인쇄?>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type2_04.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('2_04');" style="cursor:pointer;">가로형 POP 인쇄하기</a></p>
      </li>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_04.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_04');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
			<li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_07.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_07');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
    </ul>
    <ul id="modal_pop5" class="modal-list" style="display:none;"><?//5단 인쇄?>
      <li>
        <p class="pop_img"><img src="/dhn/images/leaflets/pop/pop_type1_05.jpg" alt=""></p>
        <p class="pop_btn"><a onClick="printer('1_05');" style="cursor:pointer;">세로형 POP 인쇄하기</a></p>
      </li>
    </ul>
  </div>
</div> -->
<script>
    console.log("<?=$screen_data->psd_order_et?>");
    function change_time(flag){
        if (!flag){
            // $("#psd_order_st_txt").html($("#psd_order_sdt").val() + " " + $("#psd_order_sh").val() + ":" + $("#psd_order_sm").val());
            $("#span_order_sdt").html($("#psd_order_sdt").val());
        } else {
            // $("#psd_order_et_txt").html($("#psd_order_edt").val() + " " + $("#psd_order_eh").val() + ":" + $("#psd_order_em").val());
            $("#span_order_edt").html($("#psd_order_edt").val());
        }
    }
    function change_time2(flag){
        if (!flag){
            // $("#psd_order_st_txt").html($("#psd_order_sdt").val() + " " + $("#psd_order_sh").val() + ":" + $("#psd_order_sm").val());
            $("#span_order_shm").html($("#psd_order_sh").val() + ":" + $("#psd_order_sm").val());
        } else {
            // $("#psd_order_et_txt").html($("#psd_order_edt").val() + " " + $("#psd_order_eh").val() + ":" + $("#psd_order_em").val());
            $("#span_order_ehm").html($("#psd_order_eh").val() + ":" + $("#psd_order_em").val());
        }
    }
	//POP인쇄 클릭시
	// function popChk(obj){
	// 	//alert("pop_chk click");
	// 	var pop_chk_cnt = $("input:checkbox[name='pop_chk']:checked").length; //POP인쇄 체크된 갯수
	// 	//alert("pop_chk_cnt : "+ pop_chk_cnt);
	// 	// if(pop_chk_cnt > 4){
	// 	// 	//var id = $(obj).val();
	// 	// 	//alert("id : "+ id); return;
	// 	// 	$(obj).prop("checked", false);
	// 	// 	alert("POP 인쇄 상품은 4개까지만 선택하세요.");
	// 	// 	return false;
	// 	// }else{
	// 	// 	return true;
	// 	// }
	// }

	//POP인쇄 모달창 열기
	// function popPrint(id){
	// 	var modal = document.getElementById("popModal");
	// 	var span = document.getElementById("popClose");
	//
	// 	$("#pop_id").val(id); //상품 ID
	//
	// 	var err = 0;
	// 	var pop_chk_cnt = $("input:checkbox[name='pop_chk']:checked").length; //POP인쇄 체크된 갯수
	// 	var pop_chk_cnt_sw = pop_chk_cnt;
	// 	if(pop_chk_cnt == 0){ //체그된 갯수가 없을 경우
	// 		var imgpath = $("#pre_goods_imgpath_"+ id).html(); //상품이미지
	// 		var name = $("#goods_name_"+ id).val(); //상품명
	// 		var price = $("#goods_dcprice_"+ id).val(); //할인가
	// 		if(imgpath == ""){
	// 			alert("상품 이미지를 추가해 주세요.");
	//
	// 			showImg(id);
	// 			return;
	// 		}
	// 		if(name == ""){
	// 			alert("상품명을 입력해 주세요.");
	// 			$("#goods_name_"+ id).focus();
	// 			return;
	// 		}
	// 		if(price == ""){
	// 			alert("할인가를 입력해 주세요.");
	// 			$("#goods_dcprice_"+ id).focus();
	// 			return;
	// 		}
	// 		pop_chk_cnt_sw = 1;
	// 	}else if(pop_chk_cnt >= 1){
	// 		$("input[name='pop_chk']:checked").each(function(){
	// 			var id = $(this).val();
	// 			var imgpath = $("#pre_goods_imgpath_"+ id).html(); //상품이미지
	// 			var name = $("#goods_name_"+ id).val(); //상품명
	// 			var price = $("#goods_dcprice_"+ id).val(); //할인가
	// 			if(err == 0 && imgpath == ""){
	// 				alert("상품 이미지를 추가해 주세요.");
	//
	// 				showImg(id);
	// 				err++;
	// 			}
	// 			if(err == 0 && name == ""){
	// 				alert("상품명을 입력해 주세요.");
	// 				$("#goods_name_"+ id).focus();
	// 				err++;
	// 			}
	// 			if(err == 0 && price == ""){
	// 				alert("할인가를 입력해 주세요.");
	// 				$("#goods_dcprice_"+ id).focus();
	// 				err++;
	// 			}
	// 		});
	// 	}
	// 	if(err > 0) return;
	//
	// 	modal.style.display = "block"; //모달창 열기
	//
	// 	$("#modal_pop1").hide(); //1단 인쇄 닫기
	// 	$("#modal_pop2").hide(); //2단 인쇄 닫기
	// 	$("#modal_pop3").hide(); //3단 인쇄 닫기
	// 	$("#modal_pop4").hide(); //4단 인쇄 닫기
	// 	$("#modal_pop5").hide(); //5단 인쇄 닫기
	// 	$("#modal_pop1").show();
	// 	// switch(pop_chk_cnt_sw) {
	// 	// case 1:
	// 	// 	$("#modal_pop1").show(); //1단 인쇄 열기
	// 	// 	break;
	// 	// case 2:
	// 	// 	$("#modal_pop2").show(); //2단 인쇄 열기
	// 	// 	break;
	// 	// case 3:
	// 	// 	$("#modal_pop3").show(); //3단 인쇄 열기
	// 	// 	break;
	// 	// case 4:
	// 	// 	$("#modal_pop4").show(); //4단 인쇄 열기
	// 	// 	break;
	// 	// case 5:
	// 	// 	$("#modal_pop5").show(); //5단 인쇄 열기
	// 	// 	break;
	// 	// }
	//
	// 	span.onclick = function() {
	// 		modal.style.display = "none";
	// 	}
	// 	window.onclick = function(event) {
	// 		if (event.target == modal) {
	// 			modal.style.display = "none";
	// 		}
	// 	}
	// }

	//POP인쇄 새창 열기
	// function printer(type){
	// 	var url = "/spop/printer/type/"+ type;
	//
	// 	var pop_chk_cnt = $("input:checkbox[name='pop_chk']:checked").length; //POP인쇄 체크된 갯수
	//
	// 	var imgpath = ""; //상품이미지
	// 	var name = ""; //상품명
	// 	var price = ""; //할인가
	// 	if(pop_chk_cnt == 0){ //체그된 갯수가 없을 경우
	// 		var id = $("#pop_id").val();
	// 		imgpath = $("#pre_goods_imgpath_"+ id).html(); //상품이미지
	// 		name = $("#goods_name_"+ id).val(); //상품명
	// 		price = $("#goods_dcprice_"+ id).val(); //할인가
	//
	// 	}else{
	// 		$("input[name='pop_chk']:checked").each(function(){
	// 			var id = $(this).val();
	// 			if(imgpath != ""){
	// 				imgpath += "§§"+ $("#pre_goods_imgpath_"+ id).html(); //상품이미지
	// 				name += "§§"+ $("#goods_name_"+ id).val(); //상품명
	// 				price += "§§"+ $("#goods_dcprice_"+ id).val(); //할인가
	// 			}else{
	// 				imgpath = $("#pre_goods_imgpath_"+ id).html(); //상품이미지
	// 				name = $("#goods_name_"+ id).val(); //상품명
	// 				price = $("#goods_dcprice_"+ id).val(); //할인가
	// 			}
	//
	// 		});
	// 	}
	//
	// 	var pop_title = "printer";
  //       window.open("", pop_title);
	//
	// 	var form = document.createElement("form");
	// 	document.body.appendChild(form);
	// 	form.setAttribute("method", "POST");
	// 	form.setAttribute("action", url);
	// 	form.setAttribute("target", pop_title);
	//
	// 	var field = document.createElement("input");
	// 	field.setAttribute("type", "hidden");
	// 	field.setAttribute("name", "tmp_imgpath");
	// 	field.setAttribute("value", imgpath); //이미지경로
	// 	form.appendChild(field);
	//
	// 	var field = document.createElement("input");
	// 	field.setAttribute("type", "hidden");
	// 	field.setAttribute("name", "tmp_name");
	// 	field.setAttribute("value", name); //상품명
	// 	form.appendChild(field);
	//
	// 	var field = document.createElement("input");
	// 	field.setAttribute("type", "hidden");
	// 	field.setAttribute("name", "tmp_price");
	// 	field.setAttribute("value", price); //할인가
	// 	form.appendChild(field);
	//
	// 	field = document.createElement("input");
	// 	field.setAttribute("type", "hidden");
	// 	field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
	// 	field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
	// 	form.appendChild(field);
	//
	// 	form.submit();
	// 	$("input[name=pop_chk]").prop('checked', false); //POP인쇄 선택해제 처리
	// 	document.getElementById("popModal").style.display = "none"; //POP인쇄 모달창 닫기
	// }

	var request = new XMLHttpRequest();
	var set_goods_imgpath_id = "";
	var add = "<?=$add?>";
	if(add != "") add = "&add="+ add;
	var ieYn = "<?=$ieYn?>"; //익스플로러 여부

	// 이미지 템플릿 modal
	var modal1 = document.getElementById("dh_myModal1");

	//이미지 템플릿선택 모달창 열기
	function templet_open(page){
		// Get the <span> element that closes the modal
		var span = document.getElementById("dh_close");

		var tag_id = $(":input:radio[name=tag_list]:checked").val(); //선택된 태그번호
		if(tag_id == undefined || tag_id == "all") tag_id = "";


		//템플릿 태그 리스트 조회
		$.ajax({
			url: "/spop/screen/ajax_templet_tag",
			type: "POST",
			data: {"tag_id":tag_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
			}
		});

		templet_list(tag_id, page); //템플릿 리스트 조회

		// Open the modal
		modal1.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal1.style.display = "none";
			$("input:radio[name=tag_list]:radio[value='all']").prop('checked', true); //태그 전체 선택하기
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal1) {
				modal1.style.display = "none";
				$("input:radio[name=tag_list]:radio[value='all']").prop('checked', true); //태그 전체 선택하기
			}
		}
	}

	//이미지 템플릿선택 모달창 열기
	function templet_page(page){
		var tag_id = $(":input:radio[name=tag_list]:checked").val(); //선택된 태그번호
		if(tag_id == undefined || tag_id == "all") tag_id = "";
		//alert("tag_id : "+ tag_id);
		templet_list(tag_id, page); //템플릿 리스트 조회
	}

	//템플릿 리스트 조회
	function templet_list(tag_id, page){
		//alert("템플릿 리스트 조회");
		$.ajax({
			url: "/spop/screen/ajax_templet",
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
	function templet_choice(id, bgcolor, imgpath, type){
		//alert("id : "+ id  +"\n"+"bgcolor : "+ bgcolor +"\n"+"imgpath : "+ imgpath +"\n"+"type : "+ type);
		var obj = document.getElementById("pre_templet_bg"); //템플릿 선택의 경우 배경이미지 표시영역
		obj.style.background = bgcolor; //템플릿 배경색
		obj.style.backgroundImage = "url('"+ imgpath +"')"; //템플릿 선택의 경우 배경이미지 표시영역
		$("#pre_wl_tit").attr("tabindex", -1).focus(); //전단제목 포커스
		//obj.style.backgroundSize = "200px";
		if(type == "self"){ //템플릿 직접입력의 경우
			$("#tem_self_img").show(); //직접입력 이미지 영역
			$("#tem_self_img").html("<img src='"+ imgpath +"'>"); //직접입력 이미지 영역
			$("#pre_goods_list_step1").addClass("mg_t0"); //할인&대표상품 영역 클래스 추가
		}else{ //템플릿 선택의 경우
			$("#tem_self_img").hide(); //직접입력 이미지 영역
			$("#tem_self_img").html(""); //직접입력 이미지 영역
			$("#pre_goods_list_step1").removeClass("mg_t0"); //할인&대표상품 영역 클래스 삭제
		}
		$("#tem_id").val(id); //템플릿번호
		modal1.style.display = "none"; //이미지 템플릿선택 모달창 닫기
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

	//행사코너 순서변경 모달창 열기
	function modal_sort_open(page){
		var cnt = $("input[name=goods_box_id]").length; //행사코너 등록수
		if(cnt < 2){
			alert("행사코너가 2개 이상부터 순서변경 가능합니다.");
			return;
		}
		var no = 0; //순번
		var html = "";
		$("input[name=goods_box_id]").each(function(index, item){ //상품코너별 조회
			no++;
			var goods_box_id = $(item).val(); //박스 ID
			var goods_box_step = $("#"+ goods_box_id +"_step").val(); //스텝
			var goods_box_step_id = $("#"+ goods_box_id +"_step_id").val(); //스텝ID
			var goods_box_step_no = $("#"+ goods_box_id +"_step_no").val(); //스텝순번
			var goods_box_img = $("#pre_tit_id_"+ goods_box_step_id).html(); //타이틀 이미지
			var goods_box_tit = ""; //타이틀
			if(goods_box_step == "step9"){
				goods_box_tit = "행사이미지 "+ goods_box_step_no;
			}else{
				if(goods_box_step == "step2"){
					goods_box_tit = "2단 이미지형 상품등록 "+ goods_box_step_no;
				}else if(goods_box_step == "step4"){
					goods_box_tit = "3단 이미지형 상품등록 "+ goods_box_step_no;
				}else if(goods_box_step == "step5"){
					goods_box_tit = "4단 이미지형 상품등록 "+ goods_box_step_no;
				}else if(goods_box_step == "step3"){
					goods_box_tit = "1단 텍스트형 상품등록 "+ goods_box_step_no;
				}
			}
			// alert("item : "+ $(item).val());
			html += "<li id='modal_sort_li_"+ goods_box_id +"'>";
			if(goods_box_img == "" && goods_box_step != "step9"){
				html += "  <p class=\"move_img no_title\">타이틀이미지 사용안함</p>";
			}else{
				html += "  <p class=\"move_img\">"+ goods_box_img +"</p>";
			}
			html += "  <p class=\"move_tit\">"+ goods_box_tit +"</p>";
			html += "  <input type='hidden' name='modal_sort_box_id' id='modal_sort_box_id_"+ no +"' value='"+ goods_box_id +"' style='width:110px;'>";
			html += "  <p class=\"move_btn\">";
			html += "    <span class=\"move_btn_group\">";
			html += "      <a class=\"move_last_left\" href=\"javascript:modal_sort_move('first', '"+ goods_box_id +"');\" title=\"처음으로이동\"></a>";
			html += "      <a class=\"move_left\" href=\"javascript:modal_sort_move('left', '"+ goods_box_id +"');\" title=\"왼쪽으로이동\"></a>";
			html += "      <a class=\"move_right\" href=\"javascript:modal_sort_move('right', '"+ goods_box_id +"');\" title=\"오른쪽으로이동\"></a>";
			html += "      <a class=\"move_last_right\" href=\"javascript:modal_sort_move('last', '"+ goods_box_id +"');\" title=\"마지막으로이동\"></a>";
			html += "    </span>";
			html += "  </p>";
			html += "</li>";
		});
		$("#modal_sort_list").html(html);

		// Open the modal
		var modal = document.getElementById("modal_sort");
		modal.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		var span = document.getElementById("close_sort");
		span.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	}

	//행사코너 순서변경 모달창 열기 => 순서변경
	function modal_sort_move(type, id){
		//var seq = Number($("#goods_seq_"+ id).val()); //정렬번호
		var cnt = $("input[name=goods_box_id]").length; //행사코너 등록수
		//alert("cnt : "+ cnt);
		//alert("type : "+ type +", id : "+ id); return;
		if(cnt > 1){
			var html = "";
			if(type == "first" || type == "last"){ //first.처음으로, last.마지막으로
				if(type == "first"){ //first.처음으로
					html += "<li id='modal_sort_li_"+ id +"'>"+ $("#modal_sort_li_"+ id).html() +"</li>";
				}
				var no = 0;
				$("input[name=modal_sort_box_id]").each(function(index, item){ //상품코너별 조회
					no++;
					var modal_sort_box_id = $(item).val(); //상품코너별 ID
					//alert("id : "+ id +", modal_sort_box_id : "+ modal_sort_box_id);
					if(id != modal_sort_box_id){
						html += "<li id='modal_sort_li_"+ modal_sort_box_id +"'>"+ $("#modal_sort_li_"+ modal_sort_box_id).html() +"</li>";
					}
				});
				if(type == "last"){ //last.마지막으로
					html += "<li id='modal_sort_li_"+ id +"'>"+ $("#modal_sort_li_"+ id).html() +"</li>";
				}
				$("#modal_sort_list").html(html);
			}else{
				//$(".item.one").insertAfter(".item.three"); //첫 번째 항목이 세 번째 항목 뒤로 이동
				var no = 0;
				var chk = -1;
				var arr = new Array();
				$("input[name=modal_sort_box_id]").each(function(index, item){ //상품코너별 조회
					var modal_sort_box_id = $(item).val(); //상품코너별 ID
					if(id == modal_sort_box_id) chk = no;
					arr[no] = modal_sort_box_id;
					no++;
				});
				if(chk > -1){
					//alert("chk : "+ chk +", arr[chk-1] : "+ arr[chk-1] +", arr[chk] : "+ arr[chk]);
					if(type == "left" && chk > 0){
						$("#modal_sort_li_"+ arr[chk-1]).insertAfter("#modal_sort_li_"+ arr[chk]);
					}else if(type == "right" && chk < (cnt-1)){
						$("#modal_sort_li_"+ arr[chk]).insertAfter("#modal_sort_li_"+ arr[chk+1]);
					}
				}
			}
		}
	}

	//행사코너 순서변경 모달창 열기 => 적용하기
	function modal_sort_ok(){
		saved("temp");
		$("#modal_sort").hide();
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
	if(ieYn == "N"){ //익스플로러가 아닌 경우
		$("#library_append_list").scroll(function(){
			//alert("library_append_list"); return;
			var dh = $("#library_append_list")[0].scrollHeight;
			var dch = $("#library_append_list")[0].clientHeight;
			var dct = $("#library_append_list").scrollTop();
			//alert("스크롤 : " + dh + "=" + dch +  " + " + dct);
			//alert("스크롤 : " + dh + "=" + dch +  " + " + dct); return;
			if(dh == (dch+dct)) {
				var rowcnt = $(".img_select").length;
				//alert("totalLibrary : " + totalLibrary + " / rowcnt : " + rowcnt); return;
				if(rowcnt < totalLibrary) {
					searchLibrary();
				}
			}
		});
	}

	//라이브러리 조회
	function searchLibrary(){
		var searchCate1 = $("#searchCate1").val(); //대분류
		var searchCate2 = $("#searchCate2").val(); //소분류
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		var perpage = 15;
		if(ieYn == "Y") perpage = 10; //익스플로러의 경우
		//alert("pageLibrary : "+ pageLibrary +"\n"+ ", searchCate1 : "+ searchCate1 +"\n"+ ", searchCate2 : "+ searchCate2 +"\n"+ ", searchstr : "+ searchstr +"\n"+ ", library_type : "+ library_type); return;
		//url: "/imglibrary/search_library",
		$.ajax({
			url: "/spop/screen/search_library",
			type: "POST",
			data: {"perpage" : perpage, "page" : pageLibrary, "searchCate1" : searchCate1, "searchCate2" : searchCate2, "searchnm" : searchnm, "searchstr" : searchstr, "library_type" : library_type,  "page_yn" : ieYn, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				pageLibrary = json.page;
				totalLibrary = json.total;
				//alert(json.html);
				$("#library_append_list").append(json.html); //이미지 리스트
				$("#id_modal_count").html(comma(json.total)); //이미지 건수
				if(ieYn == "Y"){ //익스플로러의 경우
					$("#library_page").html(json.page_html); //페이징
				}
			}
		});
	}

	//라이브러리 페이지 조회
	function searchLibraryPage(page){
		var searchCate1 = $("#searchCate1").val(); //대분류
		var searchCate2 = $("#searchCate2").val(); //소분류
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		var perpage = 10;
		//alert("pageLibrary : "+ pageLibrary +"\n"+ ", searchCate1 : "+ searchCate1 +"\n"+ ", searchCate2 : "+ searchCate2 +"\n"+ ", searchstr : "+ searchstr +"\n"+ ", library_type : "+ library_type); return;
		//url: "/imglibrary/search_library",
		$.ajax({
			url: "/spop/screen/search_library",
			type: "POST",
			data: {"perpage" : perpage, "page" : page, "searchCate1" : searchCate1, "searchCate2" : searchCate2, "searchnm" : searchnm, "searchstr" : searchstr, "library_type" : library_type,  "page_yn" : ieYn, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				//alert(json.html);
				$("#library_append_list").html(json.html); //이미지 리스트
				$("#id_modal_count").html(comma(json.total)); //이미지 건수
				$("#library_page").html(json.page_html); //페이징
			}
		});
	}

	//라이브러리 모달창 열기
	var modal3 = document.getElementById("dh_myModal3");
	function showLibrary(type){
		$("#id_searchName").val("name"); //검색이름
		//$("#searchCate1").val(''); //대분류 초기화
		//$("#searchCate2").val(''); //소분류 초기화
		if(type == "img"){ //이미지 라이브러리
			$("#id_searchDiv").show(); //검색영역 열기
			$("#searchCate1").show(); //대분류 열기
			$("#searchCate2").show(); //소분류 열기
			$("#id_searchName").show(); //검색타입 열기
			$("#id_searchLibrary").attr("placeholder", "검색어를 입력하세요."); //검색내용 placeholder 변경
		}else if(type == "keep"){ //이미지보관함
			$("#id_searchDiv").show(); //검색영역 닫기
			$("#searchCate1").hide(); //대분류 닫기
			$("#searchCate2").hide(); //소분류 닫기
			$("#id_searchName").hide(); //검색타입 닫기
			$("#id_searchLibrary").attr("placeholder", "이미지명 입력하세요."); //검색내용 placeholder 변경
		}else{ //상품 라이브러리
			$("#id_searchDiv").show(); //검색영역 열기
			$("#searchCate1").hide(); //대분류 닫기
			$("#searchCate2").hide(); //소분류 닫기
			$("#id_searchName").hide(); //검색타입 닫기
			$("#id_searchLibrary").attr("placeholder", "상품명 입력하세요."); //검색내용 placeholder 변경
		}
		var span = document.getElementById("dh_close3");
		modal3.style.display = "block";
		$("#library_type").val(type); //라이브러리 타입
		if(type == "goods"){
			$("#id_modal_title").html("최근상품 리스트");
		}else if(type == "keep"){
			$("#id_modal_title").html("이미지보관함");
		}else{
			$("#id_modal_title").html("이미지 라이브러리");
		}
		var goods_step_id = $("#goods_step_id").val(); //상품 div ID
		var goods_name = $("#goods_name_"+ goods_step_id).val(); //상품명
		var goods_option = $("#goods_option_"+ goods_step_id).val(); //규격
		//alert("goods_step_id : "+ goods_step_id +", goods_name : "+ goods_name +", goods_option : "+ goods_option); return;
		//규격검색 사용유무체크
		var option_use = "<?=$this->member->item("mem_is_stan"); ?>";
		var searchstr ='';
		if(option_use == 'N'){
			// alert(option_use);
			searchstr =	trim(goods_name);
		}else{
			searchstr = trim(goods_name +" "+ goods_option);
		}
		 if(type == "keep") searchstr = ""; //검색내용
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
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
			remove_img(id);
            var url = imgpath;
            var fileLength = url.length;
            var lastDot = url.lastIndexOf('.');
            var fileUrlName = url.substring(0, lastDot);
            var fileExt = url.substring(lastDot+1, fileLength);
            var fileurl = '';
            if(url.indexOf('_thumb')>0){
                fileurl = url;
            }else{
                fileurl = fileUrlName+'_thumb.'+fileExt;
            }
			$("#div_preview"+ id).css({"background":"url(" + fileurl + ")"}); //상품 이미지 배경 URL
			$("#pre_div_preview"+ id).css({"background":"url(" + fileurl + ")"}); //미리보기 상품 이미지 배경 URL
			$("#goods_imgpath"+ id).val(imgpath); //사진 경로
			$("#pre_goods_imgpath"+ id).html(fileurl); //미리보기 사진 경로
			$("#pre"+ id).attr("tabindex", -1).focus(); //미리보기 포커스
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

    function getExt(url){


        var fileLength = url.length;
        var lastDot = url.lastIndexOf('.');
        var fileUrlName = url.substring(0, lastDot-1);
        var fileExt = url.substring(lastDot+1, fileLength);
        var fileurl = url;
        if(url.indexOf('_thumb')>0){
            fileurl = fileUrlName+'_thumb.'+fileExt;
        }
        return fileurl;
    }

	//상품 라이브러리 선택 클릭시
	function set_goods_library(imgpath, name, price, dcprice){
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", imgpath : "+ imgpath); return;
		if(imgpath != ""){
			remove_img(id);
            var thumb_imgpath = getExt(imgpath)
			$("#div_preview"+ id).css({"background":"url(" + thumb_imgpath + ")"}); //상품 이미지 배경 URL
			$("#pre_div_preview"+ id).css({"background":"url(" + thumb_imgpath + ")"}); //미리보기 상품 이미지 배경 URL
			$("#goods_imgpath"+ id).val(imgpath); //사진 경로
			$("#goods_name"+ id).val(name); //상품명
			$("#goods_price"+ id).val(price); //정상가
			// $("#goods_dcprice"+ id).val(dcprice); //할인가
			$("#pre_goods_imgpath"+ id).html(thumb_imgpath); //미리보기 사진 경로
			$("#pre_goods_name"+ id).html(name); //미리보기 상품명
			$("#pre_goods_price"+ id).html(price); //미리보기 정상가
			// $("#pre_goods_dcprice"+ id).html(dcprice); //미리보기 할인가
			$("#pre"+ id).attr("tabindex", -1).focus(); //미리보기 포커스
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

	//행사뱃지 선택 모달창 열기
	var modal_badge = document.getElementById("modal_badge");
	function modal_badge_open(step, id){
		var badge_no = $("#goods_badge_"+ id).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
		//goods_badge_step1_1
		//alert("step : "+ step +"\n"+ "id : "+ id +"\n"+ "badge_no : "+ badge_no);
		modal_badge.style.display = "block";
		var checked = "";
		var html = "";
		if(badge_no == "0"){ //사용안함 뱃지
			checked = " checked";
		}else{
			checked = "";
		}
		html += "<li onclick=\"badge_choice('"+ step +"', '"+ id +"', '0', '');\" style=\"cursor: pointer;\">";
		html += "  <p class=\"badge_img\"><img src=\"/dhn/images/sale_badge_no.jpg\"></p>";
		html += "  <div class=\"badge_text\">";
		html += "    <div class=\"checks\">";
		html += "      <input type=\"radio\" name=\"rad_badge_"+ id +"\" value=\"0\" id=\"badge_imgpath_0\""+ checked +">";
		html += "      <label for=\"badge_imgpath_0\">사용안함</label>";
		html += "    </div>";
		html += "  </div>";
		html += "</li>";
		if(badge_no == "1"){ //할인율 뱃지
			checked = " checked";
		}else{
			checked = "";
		}
		html += "<li onclick=\"badge_choice('"+ step +"', '"+ id +"', '1', '');\" style=\"cursor: pointer;\">";
		html += "  <p class=\"badge_img\"><img src=\"/dhn/images/sale_badge_num.jpg\"></p>";
		html += "  <div class=\"badge_text\">";
		html += "    <div class=\"checks\">";
		html += "      <input type=\"radio\" name=\"rad_badge_"+ id +"\" value=\"1\" id=\"badge_imgpath_1\""+ checked +">";
		html += "      <label for=\"badge_imgpath_1\">할인율</label>";
		html += "    </div>";
		html += "  </div>";
		html += "</li>";

		//뱃지 리스트 조회
		$.ajax({
			url: "/spop/screen/ajax_badge_list",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					var tit_id = value.tit_id; //뱃지번호
					var tit_imgpath = value.tit_imgpath; //뱃지경로
					if(tit_id == badge_no){
						checked = " checked";
					}else{
						checked = "";
					}
					//alert("key : "+ key +", tit_id : "+ tit_id +", tit_imgpath : "+ tit_imgpath);
					html += "<li onclick=\"badge_choice('"+ step +"', '"+ id +"', '"+ tit_id +"', '"+ tit_imgpath +"');\" style=\"cursor: pointer;\">";
					html += "  <p class=\"badge_img\"><img src=\""+ tit_imgpath +"\"></p>";
					html += "  <div class=\"badge_text\">";
					html += "    <div class=\"checks\">";
					html += "      <input type=\"radio\" name=\"rad_badge_"+ id +"\" value=\""+ tit_id +"\" id=\"badge_imgpath_"+ tit_id +"\""+ checked +">";
					html += "      <label for=\"badge_imgpath_"+ tit_id +"\">뱃지선택</label>";
					html += "    </div>";
					html += "  </div>";
					html += "</li>";
				});
				$("#modal_badge_list").html(html);
			}
		});

		var close_badge = document.getElementById("close_badge");
		close_badge.onclick = function() {
			modal_badge.style.display = "none"; //모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == modal_badge) {
				modal_badge.style.display = "none"; //모달창 닫기
			}
		}
	}

	//행사뱃지 선택
	function badge_choice(step, id, tit_id, tit_imgpath){
		var divId = "div_badge_"+ id; //이미지 뱃지 표기 영역
		var preId = "pre_goods_badge_"+ id; //미리보기 뱃지 표기 영역
		//alert("step : "+ step +"\n"+ "id : "+ id +"\n"+ "tit_id : "+ tit_id +"\n"+ "preId : "+ preId +"\n"+ "tit_imgpath : "+ tit_imgpath);
		if(tit_id == "") tit_id = "0";
		//$("#"+ preId).html("<img id='pre_badge_imgpath_"+ id +"' src='"+ tit_imgpath +"'>"); //행사뱃지 이미지
		if(tit_id == "0"){
			$("#"+ divId).hide(); //행사뱃지 배경이미지
			$("#"+ divId).removeClass("sale_badge"); //할인뱃지 클래스 삭제
			$("#"+ divId).removeClass("design_badge"); //행시뱃지 클래스 삭제
			$("#"+ preId).hide(); //행사뱃지 배경이미지
			$("#"+ preId).removeClass("sale_badge"); //할인뱃지 클래스 삭제
			$("#"+ preId).removeClass("design_badge"); //행시뱃지 클래스 삭제
		}else{
			$("#"+ divId).show(); //행사뱃지 배경이미지
			$("#"+ preId).show(); //행사뱃지 배경이미지
			if(tit_id == "1"){ //할인율 뱃지의 경우
				$("#"+ divId).addClass("sale_badge"); //할인뱃지 클래스 추가
				$("#"+ divId).removeClass("design_badge"); //행시뱃지 클래스 삭제
				$("#"+ preId).addClass("sale_badge"); //할인뱃지 클래스 추가
				$("#"+ preId).removeClass("design_badge"); //행시뱃지 클래스 삭제
			}else{
				$("#"+ divId).removeClass("sale_badge"); //할인뱃지 클래스 추가
				$("#"+ divId).addClass("design_badge"); //행시뱃지 클래스 삭제
				$("#"+ divId).html("<img id='pre_badge_imgpath_"+ id +"' src='"+ tit_imgpath +"'>"); //행사뱃지 이미지
				$("#"+ preId).removeClass("sale_badge"); //할인뱃지 클래스 추가
				$("#"+ preId).addClass("design_badge"); //행시뱃지 클래스 삭제
				$("#"+ preId).html("<img id='pre_badge_imgpath_"+ id +"' src='"+ tit_imgpath +"'>"); //행사뱃지 이미지
			}
		}
		$("#goods_badge_"+ id).val(tit_id); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
		$("#badge_imgpath_"+ id).val(tit_imgpath); //행사뱃지 경로
		//$("#pre_badge_imgpath_"+ id).attr("src", tit_imgpath);
		chkBadge(step, id, tit_id); //뱃지 체크
		$("#"+ preId).attr("tabindex", -1).focus(); //미리보기 포커스
		modal_badge.style.display = "none"; //모달창 닫기
	}

    function modal_all_badge_open(step, id){
		var badge_no = $("#goods_badge_"+ id + "0").val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
		//goods_badge_step1_1
		//alert("step : "+ step +"\n"+ "id : "+ id +"\n"+ "badge_no : "+ badge_no);
		modal_badge.style.display = "block";
		var checked = "";
		var html = "";
		if(badge_no == "0"){ //사용안함 뱃지
			checked = " checked";
		}else{
			checked = "";
		}
		html += "<li onclick=\"badge_all_choice('"+ step +"', '"+ id +"', '0', '');\" style=\"cursor: pointer;\">";
		html += "  <p class=\"badge_img\"><img src=\"/dhn/images/sale_badge_no.jpg\"></p>";
		html += "  <div class=\"badge_text\">";
		html += "    <div class=\"checks\">";
		html += "      <input type=\"radio\" name=\"rad_badge_"+ id +"\" value=\"0\" id=\"badge_imgpath_0\""+ checked +">";
		html += "      <label for=\"badge_imgpath_0\">사용안함</label>";
		html += "    </div>";
		html += "  </div>";
		html += "</li>";
		if(badge_no == "1"){ //할인율 뱃지
			checked = " checked";
		}else{
			checked = "";
		}
		html += "<li onclick=\"badge_all_choice('"+ step +"', '"+ id +"', '1', '');\" style=\"cursor: pointer;\">";
		html += "  <p class=\"badge_img\"><img src=\"/dhn/images/sale_badge_num.jpg\"></p>";
		html += "  <div class=\"badge_text\">";
		html += "    <div class=\"checks\">";
		html += "      <input type=\"radio\" name=\"rad_badge_"+ id +"\" value=\"1\" id=\"badge_imgpath_1\""+ checked +">";
		html += "      <label for=\"badge_imgpath_1\">할인율</label>";
		html += "    </div>";
		html += "  </div>";
		html += "</li>";

		//뱃지 리스트 조회
		$.ajax({
			url: "/spop/screen/ajax_badge_list",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					var tit_id = value.tit_id; //뱃지번호
					var tit_imgpath = value.tit_imgpath; //뱃지경로
					if(tit_id == badge_no){
						checked = " checked";
					}else{
						checked = "";
					}
					//alert("key : "+ key +", tit_id : "+ tit_id +", tit_imgpath : "+ tit_imgpath);
					html += "<li onclick=\"badge_all_choice('"+ step +"', '"+ id +"', '"+ tit_id +"', '"+ tit_imgpath +"');\" style=\"cursor: pointer;\">";
					html += "  <p class=\"badge_img\"><img src=\""+ tit_imgpath +"\"></p>";
					html += "  <div class=\"badge_text\">";
					html += "    <div class=\"checks\">";
					html += "      <input type=\"radio\" name=\"rad_badge_"+ id +"\" value=\""+ tit_id +"\" id=\"badge_imgpath_"+ tit_id +"\""+ checked +">";
					html += "      <label for=\"badge_imgpath_"+ tit_id +"\">뱃지선택</label>";
					html += "    </div>";
					html += "  </div>";
					html += "</li>";
				});
				$("#modal_badge_list").html(html);
			}
		});

		var close_badge = document.getElementById("close_badge");
		close_badge.onclick = function() {
			modal_badge.style.display = "none"; //모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == modal_badge) {
				modal_badge.style.display = "none"; //모달창 닫기
			}
		}
	}

	//행사뱃지 선택
	function badge_all_choice(step, id, tit_id, tit_imgpath){
		var divId = "div_badge_"+ id; //이미지 뱃지 표기 영역
		var preId = "pre_goods_badge_"+ id; //미리보기 뱃지 표기 영역
		//alert("step : "+ step +"\n"+ "id : "+ id +"\n"+ "tit_id : "+ tit_id +"\n"+ "preId : "+ preId +"\n"+ "tit_imgpath : "+ tit_imgpath);
		if(tit_id == "") tit_id = "0";

		//$("#"+ preId).html("<img id='pre_badge_imgpath_"+ id +"' src='"+ tit_imgpath +"'>"); //행사뱃지 이미지
        var cnt = $("#"+id+"cnt").val();
        // console.log(cnt);
        for(var ii=0;ii<cnt;ii++){
            if(tit_id == "0"){
    			$("#"+ divId+ii).hide(); //행사뱃지 배경이미지
    			$("#"+ divId+ii).removeClass("sale_badge"); //할인뱃지 클래스 삭제
    			$("#"+ divId+ii).removeClass("design_badge"); //행시뱃지 클래스 삭제
    			$("#"+ preId+ii).hide(); //행사뱃지 배경이미지
    			$("#"+ preId+ii).removeClass("sale_badge"); //할인뱃지 클래스 삭제
    			$("#"+ preId+ii).removeClass("design_badge"); //행시뱃지 클래스 삭제
    		}else{
    			$("#"+ divId+ii).show(); //행사뱃지 배경이미지
    			$("#"+ preId+ii).show(); //행사뱃지 배경이미지
    			if(tit_id == "1"){ //할인율 뱃지의 경우
    				$("#"+ divId+ii).addClass("sale_badge"); //할인뱃지 클래스 추가
    				$("#"+ divId+ii).removeClass("design_badge"); //행시뱃지 클래스 삭제
    				$("#"+ preId+ii).addClass("sale_badge"); //할인뱃지 클래스 추가
    				$("#"+ preId+ii).removeClass("design_badge"); //행시뱃지 클래스 삭제
    			}else{
    				$("#"+ divId+ii).removeClass("sale_badge"); //할인뱃지 클래스 추가
    				$("#"+ divId+ii).addClass("design_badge"); //행시뱃지 클래스 삭제
    				$("#"+ divId+ii).html("<img id='pre_badge_imgpath_"+ id + ii +"' src='"+ tit_imgpath +"'>"); //행사뱃지 이미지
    				$("#"+ preId+ii).removeClass("sale_badge"); //할인뱃지 클래스 추가
    				$("#"+ preId+ii).addClass("design_badge"); //행시뱃지 클래스 삭제
    				$("#"+ preId+ii).html("<img id='pre_badge_imgpath_"+ id + ii +"' src='"+ tit_imgpath +"'>"); //행사뱃지 이미지
    			}
    		}
    		$("#goods_badge_"+ id+ii).val(tit_id); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
    		$("#badge_imgpath_"+ id+ii).val(tit_imgpath); //행사뱃지 경로
    		//$("#pre_badge_imgpath_"+ id).attr("src", tit_imgpath);
    		chkBadge(step, id+ii, tit_id); //뱃지 체크
        }

		// $("#"+ preId).attr("tabindex", -1).focus(); //미리보기 포커스
		modal_badge.style.display = "none"; //모달창 닫기
	}

	//데이타 변경시
	function onKeyupData(obj) {
		var name = $(obj).attr('id');
		var vlaue = $("#"+ name).val();
		//alert("name : "+ name +"\n"+"vlaue : "+ vlaue);
		$("#pre_"+ name).html(vlaue); //미리보기 영역 표시값 변경
	}

	//상품 데이타 클릭시
	function chkGoodsData(id, fl, obj){
		var preId = "pre_"+ id;
		var chk_id = $("#chk_id").val(); //선택된ID
		//alert("chk_id : "+ chk_id +"\n"+ "preId : "+ preId);
		if(id != chk_id && id != "N"){
			$("#"+ preId).attr("tabindex", -1).focus(); //미리보기 포커스
			$(obj).focus(); //해당 항목 포커스
			$("#chk_id").val(id); //선택된ID
		}else{
		}
	}

	//상품 데이타 변경시
	function chgGoodsData(id, fl, obj) {
		var stepId = id.substring(0, 5); //스텝 ID
		//alert("stepId : "+ stepId +"\n"+ "id : "+ id);
		var preId = "pre_"+ id;
		//alert("preId : "+ preId);

		//var chk_id = $("#chk_id").val(); //선택된ID
		//if(id != chk_id){
		//	$("#"+ preId).attr("tabindex", -1).focus(); //미리보기 포커스
		//	if(obj != "N"){
		//		$(obj).focus(); //해당 항목 포커스
		//	}
		//	$("#chk_id").val(id); //선택된ID
		//}
		var hdn_psd_order_yn = $('#hdn_psd_order_yn').val();

		//품절처리 반영 2021-06-16 수정
		if($("input:checkbox[id='soldout_chk_"+id+"']").is(":checked") == true){
			var chk_value = $("#soldout_chk_"+id).val(); //선택된ID
			//var order_ch = value_check("order_ch");



	    if(chk_value){
	      $('input[name=goods_soldout_'+id+']').attr('value',chk_value);
				if(id.includes("step3-1")){
					if($('#pre_'+id).hasClass("soldout")==false){
							$('#pre_'+id).addClass("soldout");
					}
				}else{
					if($('#pre_div_preview_'+id).hasClass("soldout")==false){
							$('#pre_div_preview_'+id).addClass("soldout");
					}
				}
				$('#pre_'+id).find('.icon_add_cart').attr("name","nostock");//개별 장바구니버튼 name 속성 변//
				$('#pre_'+id).find('.icon_add_cart').hide();//개별 장바구니버튼 숨김
				$("input:hidden[id='goods_soldout_"+id+"']").val('N');
	    }
		}else{
			 $('input[name=goods_soldout_'+id+']').attr('value','Y');
			 if(id.includes("step3-1")){
				 if($('#pre_'+id).hasClass("soldout")==true){
						 $('#pre_'+id).removeClass("soldout");
				 }
			 }else{
				 if($('#pre_div_preview_'+id).hasClass("soldout")==true){
				 			$('#pre_div_preview_'+id).removeClass("soldout");
			 		}
			 }
			 $('#pre_'+id).find('.icon_add_cart').attr("name","yesstock");//개별 장바구니버튼 name 속성 변경

			 if(hdn_psd_order_yn=='Y'){
				 $('#pre_'+id).find('.icon_add_cart').show(); //개별장바구니버튼 보이기
			 }

				$("input:hidden[id='goods_soldout_"+id+"']").val('Y');
		}


		var goods_name = $("#goods_name_"+ id).val(); //상품명
		var goods_option = $("#goods_option_"+ id).val(); //규격
		var goods_price = $("#goods_price_"+ id).val(); //정상가
		var goods_dcprice = $("#goods_dcprice_"+ id).val(); //할인가
		var goods_option2 = $("#goods_option2_"+ id).val(); //옵션
		if(goods_name != "") goods_name = goods_name.trim(); //상품명
		if(goods_option != "") goods_option = goods_option.trim(); //규격
		//alert("goods_option2 : "+ goods_option2);
		if(goods_option2 != ""){
			goods_option2 = goods_option2.trim(); //옵션
			$("#pre_goods_option2_"+ id).show(); //옵션 미리보기 열기
		}else{
			$("#pre_goods_option2_"+ id).hide(); //옵션 미리보기 닫기
		}
		//if(goods_price != "") goods_price = goods_price.trim(); //정상가
		//if(goods_dcprice != "") goods_dcprice = goods_dcprice.trim(); //할인가
		if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
			goods_price = goods_price.trim();
			var rtn_price = goods_price;
			var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
			if(temp_price !=""){
				rtn_price = rtn_price.replace(/,/g,'');
				var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
				rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
				if(fl == "excel") $("#goods_price_"+ id).val(rtn_price); //엑셀 업로드의 경우
			}
			goods_price = rtn_price;
		}
		if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
			goods_dcprice = goods_dcprice.trim();
			var rtn_price = goods_dcprice;
			var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
			if(temp_price !=""){
				rtn_price = rtn_price.replace(/,/g,'');
				var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
				rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
				if(fl == "excel") $("#goods_dcprice_"+ id).val(rtn_price); //엑셀 업로드의 경우
			}
            if(stepId == "step1"){
                if(Number(goods_dcprice.length)>5){
                    var sizesize = 50 - ((goods_dcprice.length - 5) * 2);
                    // $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
                    if(sizesize>=40){
                        $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
                    }else{
                        $("#pre_goods_dcprice_"+id).css("font-size", "40px");
                    }
                }else{
                    if(temp_price == ""){
                        $("#pre_goods_dcprice_"+id).css("font-size", "40px");
                    }else{
                        $("#pre_goods_dcprice_"+id).css("font-size", "50px");
                    }
                }


            }
            if(stepId == "step2"){
                if(Number(goods_dcprice.length)>5){
                        var sizesize = 42 - ((goods_dcprice.length - 5) * 2);
                        if(sizesize>30){
                            $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
                        }else{
                            $("#pre_goods_dcprice_"+id).css("font-size", "30px");
                        }
                }else{
                    $("#pre_goods_dcprice_"+id).css("font-size", "42px");
                }
            }

            if(stepId == "step4"){
                if(Number(goods_dcprice.length)>5){
                        var sizesize = 32 - ((goods_dcprice.length - 5) * 2.2);
                        if(sizesize>22){
                            $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
                        }else{
                            $("#pre_goods_dcprice_"+id).css("font-size", "20px");
                        }
                }else{
                    $("#pre_goods_dcprice_"+id).css("font-size", "32px");
                }
            }
            if(stepId == "step5"){
                if(Number(goods_dcprice.length)>5){
                        var sizesize = 26 - ((goods_dcprice.length - 5) * 2.2);
                        if(sizesize>17){
                            $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
                        }else{
                            $("#pre_goods_dcprice_"+id).css("font-size", "17px");
                        }
                }else{
                    $("#pre_goods_dcprice_"+id).css("font-size", "26px");
                }
            }
			if(rtn_price != ""){
				var psg_dcprice_last = rtn_price.substr(rtn_price.length-1, 1); //할인가 마지막 단어
				if(psg_dcprice_last == "원"){ //할인가 마지막 단어가 원인 경우
					rtn_price = rtn_price.substr(0 , rtn_price.length-1) + "<span class='sm_small'>원</span>"; //할인가 원에 class 주기
				}
			}
			goods_dcprice = rtn_price;
		}
		$("#pre_goods_name_"+ id).html(goods_name); //상품명 미리보기 영역 표시값 변경
		$("#pre_goods_option_"+ id).html(goods_option); //규격 미리보기 영역 표시값 변경
		$("#pre_goods_price_"+ id).html(goods_price); //정상가 미리보기 영역 표시값 변경
		$("#pre_goods_dcprice_"+ id).html(goods_dcprice); //할인가 미리보기 영역 표시값 변경
		$("#pre_goods_option2_"+ id).html(goods_option2); //옵션 미리보기 영역 표시값 변경
		//if(goods_option2 != "") alert("goods_option2 : "+ goods_option2); return;
		//if(goods_option2 != "") alert("pre_goods_option2 : "+ $("#pre_goods_option2_"+ id).html()); return;
		//alert("stepId : "+ stepId); return;
		//alert("option2 : pre_goods_option2_"+ id);
		if(stepId == "step1" || stepId == "step2"){ //step1.할인&대표상품, step2.2단 이미지형
			var goods_badge = $("#goods_badge_"+ id).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
            if(stepId == "step1"){
                if((Number(goods_name.length)+Number(goods_option.length))>12){
                    $("#pre_goods_name_"+id).parent('.name').css("font-size", "20px");
                }else{
                    $("#pre_goods_name_"+id).parent('.name').css("font-size", "25px");
                }
            }
			//alert("stepId : "+ stepId +", goods_badge : "+ goods_badge); return;
			if(goods_badge == "1"){
				goods_discount("1", id); //할인율 계산
			}
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
	function imgChange(input){
		var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
		//alert("id : "+ id +", input.value : "+ input.value); return;
		if(input.value.length > 0) {
			//alert("input.value : "+ input.value);
			if (input.files && input.files[0]) {
				remove_img(id);
				var fileSize = input.files[0].size; //파일사이즈
				var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
				//alert("fileSize : "+ fileSize +", maxSize : "+ maxSize);
				if(maxSize < fileSize){
					jsFileRemove(input); //파일 초기화
					alert("이미지 파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재 파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
					return;
				}
				//alert("첨부파일 사이즈 체크 완료"); return;
				readURL(input, "div_preview"+ id, id);
				modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			}
		}
	}

	//템플릿 직접입력 클릭시
	function template_self_img(input){
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
						$("#"+ imgid).attr("src", "");
						$("#pre_"+ imgid).attr("src", "");
						alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;

					var formData = new FormData();
					formData.append("imgfile", input.files[0]); //이미지
					formData.append("uppath", "<?=$template_self_path?>"); //업로드경로
					formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
					$.ajax({
						url: "/spop/printer/imgfile_save",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function (json) {
							//alert("json.code : "+ json.code);
							if(json.code == "0" && json.imgpath != "") { //성공
								jsFileRemove(input); //파일 초기화
								var imgpath = json.imgpath; //템플릿 이미지 경로
								// alert(json.imgpath);
								$.ajax({
									url: "/spop/screen/template_self_save",
									type: "POST",
									data: {
										  "tag_id" : "0" //템플릿 태그번호
										, "imgpath" : imgpath //템플릿 이미지 경로
										, "bgcolor" : "<?=$template_self_bgcolor?>" //템플릿 배경색상
										, "useyn" : "S" //템플릿 사용여부(Y.사용, N.사용안함, S.직접입력)
										, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
									},
									success: function (json) {
										if(json.code == "0" && json.tem_id != "") { //성공
											//alert("json.tem_id : "+ json.tem_id +"\n"+ "bgcolor : <?=$template_self_bgcolor?>\n"+ "imgpath : "+ imgpath);
											templet_choice(json.tem_id, "<?=$template_self_bgcolor?>", imgpath, "self"); //템플릿선택
										}
									}
								});
								return;
							}else{
								jsFileRemove(input); //파일 초기화
								alert("처리중 오류가 발생하였습니다.");
								return;
							}
						}
					});
				}
			}
		}
	}
	//행사이미지 직접추가 클릭시
	function imgEventChange(input){
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
						$("#"+ imgid).attr("src", "");
						$("#pre_"+ imgid).attr("src", "");
						alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;

					goods_area_append('step9'); //행사이미지 직접추가 영역
					var step9_cnt = $("#step9_cnt").val(); //행사이미지 등록수
					var goods_box_cnt = $("#goods_box_cnt").val(); //전체 코너 등록수
					var imgid = "goods_img_step9-"+ step9_cnt;
					var imgpath_id = "_step9-"+ step9_cnt;
					//$("#goods_box-"+ goods_box_cnt).hide(); //해당 코너 임시 숨김
					//$("#pre_goods_box-"+ goods_box_cnt).hide(); //해당 미리보기 코너 임시 숨김
					//alert("step9_cnt : "+ step9_cnt +", goods_box_cnt : "+ goods_box_cnt);
					//alert("imgid : "+ imgid +", id : "+ id); return;

					var reader = new FileReader();
					reader.onload = function(e) {
						$("#"+ imgid).attr("src", e.target.result);
						$("#pre_"+ imgid).attr("src", e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
					set_goods_imgpath_id = "goods_imgpath"+ imgpath_id +"_0";
					// alert("set_goods_imgpath_id : "+ set_goods_imgpath_id);

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
							if(json.code == "0") { //성공
								jsFileRemove(input); //파일 초기화
                                console.log(json.imgpath);
                                console.log(set_goods_imgpath_id);
								$("#"+ set_goods_imgpath_id).val(json.imgpath);
								$("#pre_"+ set_goods_imgpath_id).html(json.imgpath);
								$("#goods_box-"+ goods_box_cnt).show(); //해당 코너 열기
								$("#pre_goods_box-"+ goods_box_cnt).show(); //해당 미리보기 코너 열기
								window.scroll(0, getOffsetTop(document.getElementById("goods_box-"+ goods_box_cnt))); //신규 박스 상단으로 이동
							}else{
								jsFileRemove(input); //파일 초기화
								$("#"+ set_goods_imgpath_id).val("");
								$("#pre_"+ set_goods_imgpath_id).html("");
								goods_area_del("goods_box-"+ goods_box_cnt); //해당 코너 삭제
								$("#step9_cnt").val(Number(step9_cnt)-1);
								$("#goods_box_cnt").val(Number(goods_box_cnt)-1);
								alert(json.msg);
								return;
							}
						}
					});
				}
			}
		}
	}

	//이미지 경로 세팅
	function readURL(input, divid, id){
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
				$("#pre_"+ divid).attr("tabindex", -1).focus(); //미리보기 포커스
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
			request.open("POST", "/spop/screen/imgfile_save");
			request.send(formData);
		}
	}

	//리턴값 처리
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

	//저장하기
	function saved(flag){
		var code = "1";
		var no = 0;
		var data_id = $("#data_id").val(); //전단번호
		var tem_id = $("#tem_id").val(); //템플릿번호
		var psd_title = $("#wl_tit").val().trim(); //행사제목
		var psd_date = $("#wl_date").val().trim(); //행사기간
		//alert("data_id : "+ data_id +"\n"+"tem_id : "+ tem_id +"\n"+"psd_title : "+ psd_title +"\n"+"psd_date : "+ psd_date); return;
		if(psd_title == "" && flag != "temp"){
			alert("행사제목을 입력하세요.");
			$("#wl_tit").focus();
			return;
		}
		if(tem_id == "" && flag != "temp"){
			alert("이미지 템플릿을 선택하세요.");
			templet_open(1); //이미지 템플릿선택 모달창 열기
			return;
		}
		if(psd_date == "" && flag != "temp"){
			alert("행사기간을 입력하세요.");
			$("#wl_date").focus();
			return;
		}
		var psd_order_yn = $(":input:radio[name=psd_order_yn]:checked").val(); //주문하기 사용여부
        var psd_font_LN = $(":input:radio[name=psd_font_LN]:checked").val(); //글꼴 크기
		var psd_order_sdt = $("#psd_order_sdt").val(); //주문하기 시작일자
		var psd_order_edt = $("#psd_order_edt").val(); //주문하기 종료일자
        var psd_order_sh = $("#psd_order_sh").val(); //주문하기 시작일자
		var psd_order_eh = $("#psd_order_eh").val(); //주문하기 종료일자
        var psd_order_sm = $("#psd_order_sm").val(); //주문하기 시작일자
		var psd_order_em = $("#psd_order_em").val(); //주문하기 종료일자
		if(psd_order_yn == "Y" && flag != "temp"){
			if(psd_order_sdt == ""){
				alert("스마트전단 주문하기 시작 일자를 입력하세요.");
				$("#psd_order_sdt").focus();
				return;
			}
			psd_order_sdt = psd_order_sdt.replace(/[^0-9]/g,'');
			if(psd_order_sdt.length != 8){
				alert("스마트전단 주문하기 시작 일자가 올바른 형식이 아닙니다.");
				$("#psd_order_sdt").focus();
				return;
			}
			//psd_order_sdt = psd_order_sdt.substring(0, 4) +"-"+ psd_order_sdt.substring(4, 6) +"-"+ psd_order_sdt.substring(6, 8);
			//alert("psd_order_sdt : "+ psd_order_sdt); return;
			if(psd_order_edt == ""){
				alert("스마트전단 주문하기 종료 일자를 입력하세요.");
				$("#psd_order_edt").focus();
				return;
			}
			psd_order_edt = psd_order_edt.replace(/[^0-9]/g,'');
			if(psd_order_edt.length != 8){
				alert("스마트전단 주문하기 종료 일자가 올바른 형식이 아닙니다.");
				$("#psd_order_edt").focus();
				return;
			}
			if(psd_order_sdt > psd_order_edt){
				alert("스마트전단 주문하기 종료 일자가를 확인해 주세요.");
				$("#psd_order_edt").focus();
				return;
			}
            if ((psd_order_sh + psd_order_sm) == (psd_order_eh + psd_order_em)){
                alert("스마트전단 주문하기 시간이 동일합니다.");
				$("#psd_order_sh").focus();
				return;
            }

            if (Number(psd_order_sh + psd_order_sm) > Number(psd_order_eh + psd_order_em)){
                alert("스마트전단 주문하기 시작시간이 종료시간 보다 더 늦습니다.");
				$("#psd_order_sh").focus();
				return;
            }
		}
		//STEP1 ---------------------------------------------------------------------------------------------------
		var step1_yn = "N";
		var step1_yn_Y = $("#step1_yn_Y").is(":checked"); //할인&대표상품 등록 사용여부
		if(step1_yn_Y){ //STEP1 사용함
			no = 0;
			var step1_cnt = $("#step1_cnt").val(); //할인&대표상품 등록 등록 상품수
			//alert("step1_cnt : "+ step1_cnt); return;
			if(step1_cnt > 0) step1_yn = "Y";
			for(var i=0; i < step1_cnt; i++){
				var goods_id = $("#goods_id_step1_"+ i).val(); //상품번호
				var goods_step = $("#goods_step_step1_"+ i).val(); //스텝
				var goods_imgpath = $("#goods_imgpath_step1_"+ i).val(); //이미지경로
				var goods_name = $("#goods_name_step1_"+ i).val().trim(); //상품명
				var goods_option = $("#goods_option_step1_"+ i).val().trim(); //규격
				var goods_price = $("#goods_price_step1_"+ i).val().trim(); //정상가
				var goods_dcprice = $("#goods_dcprice_step1_"+ i).val().trim(); //할인가
				var goods_badge = $("#goods_badge_step1_"+ i).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				var goods_barcode = $("#goods_barcode_step1_"+ i).val().trim(); // 바코드
				//alert("goods_badge : "+ goods_badge); return;
				if($("#goods_name_step1_"+ i).length > 0){ //해당 라인이 존재 할 경우
					no++;
					<? //if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
					//if(goods_imgpath == "" && flag != "temp"){
					//	alert("[할인&대표상품 등록 "+ no +"번째]\n상품 이미지를 추가하세요.");
					//	//$("#img_file_step1_"+ i).click();
					//	showImg("step1_"+ i);
					//	return;
					//}
					<? //} ?>
					if(goods_name == "" && flag != "temp"){
						alert("[할인&대표상품 등록 "+ no +"번째]\n상품명을 입력하세요.");
						$("#goods_name_step1_"+ i).focus();
						return;
					}
					if(goods_dcprice == "" && flag != "temp"){
						alert("[할인&대표상품 등록 "+ no +"번째]\n할인가를 입력하세요.");
						$("#goods_dcprice_step1_"+ i).focus();
						return;
					}
				}
			}
		}
		//alert("[할인&대표상품 등록] OK"); return;
		var step2_yn = "N"; //이미지형 상품 사용여부
		var step3_yn = "N"; //텍스트형 상품 사용여부
		var psd_type = "S"; //타입(S.전단, C.쿠폰, T.임시저장)
		var org_id = "";
		if(flag == "temp"){
			data_id = "";
			org_id = data_id;
			psd_type = "T"; //타입(S.전단, C.쿠폰, T.임시저장)
		}
		var str_add = "<?=add?>";
		if(str_add == "_test") psd_viewyn = "N";
		var goods_box_cnt = Number($("#goods_box_cnt").val()); //상품 박스 갯수
		//alert("goods_box_cnt : "+ goods_box_cnt); return;
		//for(var j=1; j <= goods_box_cnt; j++){ //박스 갯수만큼
		var err = 0;
		var j = 0;
		$("input[name=goods_box_id]").each(function(index, item){ //상품코너별 조회
			no = 0;
			var box_id = $(item).val(); //코너별 ID
			// alert("box_id["+ index +"] : "+ box_id);
			var box_no = $("#"+ box_id +"_box_no").val();
			var step = $("#"+ box_id +"_step").val();
			var stepId = $("#"+ box_id +"_step_id").val();
			var stepNo = $("#"+ box_id +"_step_no").val();
			var stepNm = "이미지형";
			if(step == "step3"){
				stepNm = "1단 텍스트형";
			}else if(step == "step2"){
				stepNm = "2단 이미지형";
			}else if(step == "step4"){
				stepNm = "3단 이미지형";
			}else if(step == "step5"){
				stepNm = "4단 이미지형";
			}
			//alert("box_id : "+ box_id +", box_no : "+ box_no +"\n"+"step : "+ step +", stepId : "+ stepId +", stepNo : "+ stepNo); return;
			var line = Number($("#"+ stepId+"_cnt").val()); //라인별 상품수
			var tit_id ="";
			// alert("tit_id_self_"+stepId +" : "+ $("#tit_id_self_"+stepId).val() +"tit_id_self_yn_"+stepId +" : "+ $("#tit_id_self_yn_"+stepId).val()); return;
			if($("#tit_id_self_yn_"+stepId).val() =="S"){
				tit_id = $("#tit_id_self_"+ stepId).val();
			}else{
				tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
			}

			// alert("stepId : "+ stepId +"line : "+ line +", tit_id : "+ tit_id); return;
			if(step != "step9"){ //행사이미지 제외
				for(var i=0; i < line; i++){
					var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
					var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
					var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
					var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
					var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //규격
					var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
					var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
					var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
					// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
					var goods_barcode = $("#goods_barcode_"+ stepId +"_"+ i).val().trim(); // 바코드
					if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
						no++;
						var strNo = stepNm +" 상품등록 "+ stepNo +"번 "+ no;
						<? //if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
						//if(goods_imgpath == "" && step == "step2" && flag != "temp"){
						//	alert("["+ strNo +"번째]\n상품 이미지를 추가하세요.");
						//	showImg(stepId +"_"+ i);
						//	return;
						//}
						<? //} ?>
						if(goods_name == "" && flag != "temp"){
							err++;
							alert("["+ strNo +"번째]\n상품명을 입력하세요.");
							$("#goods_name_"+ stepId +"_"+ i).focus();
							return false;
						}
						if(goods_dcprice == "" && flag != "temp"){
							err++;
							alert("["+ strNo +"번째]\n할인가를 입력하세요.");
							$("#goods_dcprice_"+ stepId +"_"+ i).focus();
							return false;
						}
						if(step == "step2") step2_yn = "Y";
						if(step == "step3") step3_yn = "Y";
					}
				}
			}
		});
		if(err > 0) return;
		//alert("OK"); return;
		if(data_id != "" && "mod" == "<?=$md?>" && flag == ""){ //수정의 경우
			if(!confirm("수정 하시겠습니까?")){
				return;
			}
		}
		//스마트전단 데이타 저장
		//jsLoading(); //로딩중 호출
		$.ajax({
			url: "/spop/screen/screen_data_save",
			type: "POST",
            async : false,
			data: {
				  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
				, "flag" : flag
				, "data_id" : data_id //전단번호
				, "tem_id" : tem_id //템플릿번호
				, "psd_title" : psd_title //행사제목
				, "psd_date" : psd_date //행사기간
				, "step1_yn" : step1_yn //할인&대표상품 등록 사용여부
				, "step2_yn" : step2_yn //사용안함
				, "step3_yn" : step3_yn //사용안함
				, "psd_viewyn" : "<?=$psd_viewyn?>" //스마트전단 샘플 뷰 (Y/N)
				, "psd_type" : psd_type //타입(S.전단, C.쿠폰, T.임시저장)
				, "psd_ver_no" : "<?=$psd_ver_no?>" //버전번호
				, "psd_order_yn" : psd_order_yn //주문하기 사용여부 2021-03-05
				, "psd_order_sdt" : psd_order_sdt //주문하기 시작일자 2021-03-05
				, "psd_order_edt" : psd_order_edt //주문하기 종료일자 2021-03-05
                , "psd_order_sh" : psd_order_sh //주문하기 시작시간 07
				, "psd_order_eh" : psd_order_eh //주문하기 종료시간 19
                , "psd_order_sm" : psd_order_sm //주문하기 시작분 30
				, "psd_order_em" : psd_order_em //주문하기 종료분 50
                , "psd_order_st" : $("#psd_order_st").val()
                , "psd_order_et" : $("#psd_order_et").val()
                , "psd_font_LN" : psd_font_LN //글꼴크기 2023-02-01
			},
			success: function (json) {
				//alert("json.id : "+ json.id +", json.psd_code : "+ json.psd_code +", json.flag : "+ json.flag);
				if(json.code == "0") { //성공
					goods_del(json.id, flag); //스마트전단 상품 삭제 (초기화)
					// goods_save(json.id, flag); //스마트전단 상품 저장
					$("#data_id").val(json.id); //전단번호
					if(json.flag == "lms"){ //문자메시지 발송
						location.href = "/dhnbiz/sender/send/lms?psd_code="+ json.psd_code; //문자메시지 발송 페이지
					}else if(json.flag == "talk_adv"){ //알림톡 발송
						location.href = "/dhnbiz/sender/send/talk_adv_v3?psd_code="+ json.psd_code; //알림톡 발송 페이지
					}else if(json.flag == "temp"){ //임시저장
						setTimeout(function() {
							location.replace("/spop/screen/write?psd_id=<?=$psd_id?>&md=<?=$md?>&temp_id="+ json.id + add +""); //#smart_btn_group
						}, 50); //0.005초 지연
					}else{
						showSnackbar("저장 되었습니다.", 1500);
						setTimeout(function() {
							list(); //목록 페이지
						}, 1000); //1초 지연
					}
				}
			}
		});
		//alert("OK"); return;
	}

	//스마트전단 상품 저장
	function goods_save(data_id, flag){
		var cnt = 0;
		var ui = new Array();
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 배열생성 > STEP1
		//--------------------------------------------------------------------------------------------------------------
		var step1_yn_Y = $("#step1_yn_Y").is(":checked");
		if(step1_yn_Y){ //STEP1 사용함
			var step1_cnt = Number($("#step1_cnt").val()); //할인&대표상품 등록 등록 상품수
			//alert("step1_cnt : "+ step1_cnt);
			for(var i=0; i < step1_cnt; i++){
				var goods_id = $("#goods_id_step1_"+ i).val(); //상품번호
				var goods_step = $("#goods_step_step1_"+ i).val(); //스텝
				var goods_imgpath = $("#goods_imgpath_step1_"+ i).val(); //이미지경로
				var goods_name = $("#goods_name_step1_"+ i).val().trim(); //상품명
				var goods_option = $("#goods_option_step1_"+ i).val().trim(); //규격
				var goods_price = $("#goods_price_step1_"+ i).val().trim(); //정상가
				var goods_dcprice = $("#goods_dcprice_step1_"+ i).val().trim(); //할인가
				var goods_seq = $("#goods_seq_step1_"+ i).val(); //정렬순서
				var goods_badge = $("#goods_badge_step1_"+ i).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
				var goods_option2 = $("#goods_option2_step1_"+ i).val().trim(); //옵션
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				var goods_barcode = $("#goods_barcode_step1_"+ i).val().trim(); // 바코드
        var goods_soldout = $("#goods_soldout_step1_"+ i).val(); //품절여부
				if(goods_soldout=="" || goods_soldout == null){
					goods_soldout="Y";
				}

				if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
					goods_price = goods_price.trim();
					var rtn_price = goods_price;
					var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
					if(temp_price !=""){
						rtn_price = rtn_price.replace(/,/g,'');
						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
					}
					goods_price = rtn_price;
				}
				if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
					goods_dcprice = goods_dcprice.trim();
					var rtn_price = goods_dcprice;
					var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
					if(temp_price !=""){
						rtn_price = rtn_price.replace(/,/g,'');
						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
					}
					goods_dcprice = rtn_price.replace(/<span class="sm_small">원<\/span>/gi, '원'); //원 작은글씨 본원 처리
				}
				if(goods_badge == "1" && (goods_price == "" || goods_dcprice == "")){
					goods_badge = "0";
				}
				//<span class="sm_small">원</span>
				//alert("goods_badge : "+ goods_badge); return;
				if($("#goods_name_step1_"+ i).length > 0){ //해당 라인이 존재 할 경우
					var pushData = {
						  "data_id" : data_id //전단번호
						, "goods_id" : goods_id //상품번호
						, "tit_id" : 0 //타이틀번호
						, "goods_step" : goods_step //스텝
						, "goods_step_no" : 0 //스텝순번
						, "goods_imgpath" : goods_imgpath //이미지경로
						, "goods_name" : goods_name //상품명
						, "goods_option" : goods_option //규격
						, "goods_price" : goods_price //정상가
						, "goods_dcprice" : goods_dcprice //할인가
						, "goods_seq" : goods_seq //할인가
						, "goods_badge" : goods_badge //할인뱃지
						, "goods_option2" : goods_option2 //옵션
						, "goods_barcode" : goods_barcode //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
            , "goods_stock" : goods_soldout //품절여부 - 2021-06-17 추가
					};
					ui.push(pushData);
					cnt++;
					//alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
				}
			}
		}
		//if( cnt > 0 ) alert("STEP1 cnt : "+ cnt +", ui : "+ JSON.stringify(ui));
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 배열생성 > 박스 갯수만큼
		//--------------------------------------------------------------------------------------------------------------
		var goods_box_id = "goods_box_id";
		if(flag == "temp") goods_box_id = "modal_sort_box_id";
		$("input[name="+ goods_box_id +"]").each(function(index, item){ //상품코너별 조회
			var box_id = $(item).val(); //코너별 ID
			//var box_no = $("#"+ box_id +"_box_no").val();
			var box_no = index+1; //코너별 순번
			var step = $("#"+ box_id +"_step").val(); //스텝
			var stepId = $("#"+ box_id +"_step_id").val();
			var stepNo = $("#"+ box_id +"_step_no").val();
			//alert("box_id : "+ box_id +", box_no : "+ box_no +"\n"+"step : "+ step +", stepId : "+ stepId +", stepNo : "+ stepNo); return;
			var line = Number($("#"+ stepId+"_cnt").val()); //라인별 상품수
			var tit_id ="";
			if($("#tit_id_self_yn_"+stepId).val() =="S"){
				tit_id = $("#tit_id_self_"+ stepId).val();
			}else{
				tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
			}
			if(step == "step9"){
				line = 1;
				tit_id = 0;
			}
			// alert("line : "+ line +", tit_id : "+ tit_id);
			for(var i=0; i < line; i++){
				var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
				var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
				var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
				var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
				var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //규격
				var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
				var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
				var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
				var goods_badge = $("#goods_badge_"+ stepId +"_"+ i).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
				var goods_option2 = $("#goods_option2_"+ stepId +"_"+ i).val().trim(); //옵션
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				var goods_barcode = $("#goods_barcode_"+ stepId +"_"+ i).val().trim(); // 바코드
        var goods_soldout = $("#goods_soldout_"+ stepId +"_"+ i).val(); //품절여부 - 2021-06-17 추가
				var tit_text_info = $("#t_tit_text_info_"+ stepId).val(); // 바로가기 텍스트 정보 2021-08-12 추가
                // alert("goods_step : "+ goods_step);
				if(goods_soldout=="" || goods_soldout == null){
					goods_soldout="Y";
				}

				if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
					goods_price = goods_price.trim();
					var rtn_price = goods_price;
					var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
					if(temp_price !=""){
						rtn_price = rtn_price.replace(/,/g,'');
						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
					}
					goods_price = rtn_price;
				}
				if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
					goods_dcprice = goods_dcprice.trim();
					var rtn_price = goods_dcprice;
					var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
					if(temp_price !=""){
						rtn_price = rtn_price.replace(/,/g,'');
						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
					}
					goods_dcprice = rtn_price;
				}
				if(goods_badge == "") goods_badge = "0";
				if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
					var pushData = {
						  "data_id" : data_id //전단번호
						, "goods_id" : goods_id //상품번호
						, "tit_id" : tit_id //타이틀번호
						, "goods_step" : goods_step //스텝
						, "goods_step_no" : box_no //스텝순번
						, "goods_imgpath" : goods_imgpath //이미지경로
						, "goods_name" : goods_name //상품명
						, "goods_option" : goods_option //규격
						, "goods_price" : goods_price //정상가
						, "goods_dcprice" : goods_dcprice //할인가
						, "goods_badge" : goods_badge //할인뱃지
						, "goods_seq" : goods_seq //정렬순서
						, "box_no" : box_no //코너별 순번
						, "goods_option2" : goods_option2 //옵션
						, "goods_barcode" : goods_barcode //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
            , "goods_stock" : goods_soldout //품절여부 2021-06-17 추가
						, "tit_text_info" : tit_text_info // 바로가기 텍스트 정보

					};
					ui.push(pushData);
					cnt++;
					//if(cnt == 1) alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
				}
			}
		});
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 저장
		//--------------------------------------------------------------------------------------------------------------

		// alert("cnt : "+ cnt );

        // console.log("cnt : "+ cnt );

		if( cnt > 0 ) {
			var uiStr = JSON.stringify(ui);
			//alert("cnt : "+ cnt +", uiStr : "+ uiStr); return;
			//alert("순서변경 적용중입니다.");
			//showSnackbar("순서변경 적용중입니다.", 2000);
			var formData = new FormData();
			formData.append("updata", uiStr);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/spop/screen/screen_goods_arr_save",
				type: "POST",
				data: formData,
				async: false,
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
		isChange = false;
	}

	//목록
	function list(){
		location.href = "/spop/screen"+ add.replace(/&/gi, '?'); //스마트전단 목록 페이지
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
					goods_append('1', 'step1', 'goods_yn'); //STEP1 할인상품 등록 영역 추가
				}
			}else if(stepId == "step2" && step_cnt == 0){ //STEP2 등록된 상품이 없는 경우
				goods_area_copy('goods_list_step2', 'step2'); //STEP2. 이미지형 상품등록 영역 복사
			}else if(stepId == "step3" && step_cnt == 0){ //STEP3 등록된 상품이 없는 경우
				goods_area_copy('goods_list_step3', 'step3'); //STEP3. 텍스트형 상품등록 영역 복사
			}
		}
	}

	//스마트전단 상품 삭제 (초기화)
	function goods_del(data_id, flag){
		$.ajax({
			url: "/spop/screen/screen_goods_del",
			type: "POST",
            async : false,
			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
                goods_save(data_id, flag);
            }
		});
	}

	//뱃지 체크
	function chkBadge(stepNo, id, data){
		//alert("stepNo : "+ stepNo +", id : "+ id +", data : "+ data);
		var dis = "";
		if(data == "0"){ //뱃지 사용안함
			dis = "none";
		}else if(data == "1"){ //할인율 뱃지
			goods_discount(stepNo, id); //할인율 계산
		}
		$("#pre_goods_badge_cd_"+ id).html(data);
		$("#pre_goods_badge_"+ id).css("display", dis);
	}

	//할인율 계산
	function goods_discount(stepNo, id){
		//alert("stepNo : "+ stepNo +", id : "+ id);
		//$("#pre_goods_badge_"+ id).html("");
		var price = $("#goods_price_"+ id).val(); //정상가
		//alert("price : "+ price);
		if(price != "") price = price.replace(/[^0-9]/g,''); //정상가 숫자만 추출
		var dcprice = $("#goods_dcprice_"+ id).val(); //할인가 숫자만 추출
		if(dcprice != "") dcprice = dcprice.replace(/[^0-9]/g,''); //할인가 숫자만 추출
		var rate = "";
		//alert(" id : "+ id +", price : "+ price +", dcprice : "+ dcprice);
		if(price != "" && dcprice != ""){ //정상가 할인가 모두 있는 경우
			rate = Math.round( 100 - ( (dcprice / price) * 100) ); //할인율
			rate = rate +"<span>%</span>"; //할인율
			//alert(" id : "+ id +", price : "+ price +", dcprice : "+ dcprice +", rate : "+ rate);
		}
		$("#div_badge_"+ id).html(rate); //상품이미지 할율율 표시
		$("#pre_goods_badge_"+ id).html(rate); //미리보기 할인율 표시
	}

	//상품 영역 추가
	function goods_append(step, id, flag){
		//step : 1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형
		var no = $("#"+ id +"_cnt").val(); //상품 등록 상품수
		$("#"+ id +"_cnt").val(Number(no)+1); //상품 등록 상품수
		//alert("step : "+ step +", id : "+ id +", "+ id +"_cnt : "+ no);
		var psd_order_yn = $(":input:radio[name=psd_order_yn]:checked").val(); //스마트전단 주문하기 여부
		//alert("step : "+ step +", id : "+ id +", psd_order_yn : "+ psd_order_yn);
		var stmall_html = "";
		if(psd_order_yn == "Y"){ //주문하기 사용의 경우
			stmall_html = "<button class='icon_add_cart'>장바구니</button>";
		}else{
			stmall_html = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
		}
		var html = 	'';
		if(step == "3"){ //3.1단 텍스트형
			html += '<li id="'+ id +'_'+ no +'" class="dl_step'+ step +'">';
			html += '  <span class="txt_name"><input type="text" id="goods_name_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="상품명" class="int100_l"></span>';
			html += '  <span class="txt_option"><input type="text" id="goods_option_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="규격" class="int100_l"></span>';
			html += '  <span class="txt_price1"><input type="text" id="goods_price_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="4,000원" class="int100_r"></span>';
			html += '  <span class="txt_price2"><input type="text" id="goods_dcprice_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="3,000원" class="int100_r"></span>';
			html += '  <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del(\''+ id +'\', \''+ no +'\');" title="삭제"></a></span>';
			html += '  <input type="hidden" id="goods_id_'+ id +'_'+ no +'" value="">';
			html += '  <input type="hidden" id="goods_step_'+ id +'_'+ no +'" value="3" style="width:30px;">';
			html += '  <input type="hidden" id="goods_div_'+ id +'_'+ no +'" value="'+ id +'_'+ no +'" style="width:80px;">';
			html += '  <input type="hidden" id="goods_seq_'+ id +'_'+ no +'" value="' + no + '" style="width:40px;">';
			html += '  <input type="hidden" id="goods_option_'+ id +'_'+ no +'" value="">';
			html += '  <input type="hidden" id="goods_option2_'+ id +'_'+ no +'" value="">';
			// 2021-06-01 goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			html += '  <input type="hidden" id="goods_barcode_'+ id +'_'+ no +'" value="">';
			html += '</li>';
		}else{ //1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형
			var goods_price = "4,000원"; //정상가
			var goods_dcprice = "3,000원"; //할인가
			var badge_nm = "행사뱃지 추가"; //행사뱃지 명칭
			var badge_no = "0"; //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
			if(step == "1"){ //할인&대표상품
				goods_price = "4,000"; //정상가
				goods_dcprice = "3,000"; //할인가
				badge_nm = "행사뱃지 선택"; //행사뱃지 명칭
				badge_no = "1"; //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
			}
			html += '<dl id="'+ id +'_'+ no +'" class="dl_step'+ step +'">';
			html += '    <dt>';
			html += '      <div class="templet_img_in" onclick="showImg(\''+ id +'_'+ no +'\')">';
			html += '        <div id="div_preview_'+ id +'_'+ no +'">';
			html += '          <img id="img_preview_'+ id +'_'+ no +'" style="display:none;width:100%">';
			if(step == "1"){ //할인&대표상품
				html += '          <div id="div_badge_'+ id +'_'+ no +'" class="sale_badge" style="display:;">25<span>%</span></div>';
			}else{
				html += '          <div id="div_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
			}
			html += '        </div>';
			html += '      </div>';
			if(step == "1" || step == "2"){ //1.할인&대표상품, 2.2단 이미지형
				html += '      <button class="btn_good_badge" type="button" id="badgeBtn"onclick="modal_badge_open(\''+ step +'\', \''+ id +'_'+ no +'\')">'+ badge_nm +'</button>'; //행사뱃지 선택
			}
			html += '    </dt>';
			html += '    <dd>';
			html += '      <ul>';
			html += '          <input type="hidden" id="goods_id_'+ id +'_'+ no +'">';
			html += '          <input type="hidden" id="goods_step_'+ id +'_'+ no +'" value="'+ step +'" style="width:60px;">';
			html += '          <input type="hidden" id="goods_div_'+ id +'_'+ no +'" value="'+ id +'_'+ no +'" style="width:80px;">';
			html += '          <input type="hidden" id="goods_seq_'+ id +'_'+ no +'" value="'+ no +'" style="width:42px;">';
			html += '          <input type="hidden" id="goods_imgpath_'+ id +'_'+ no +'">';
			html += '          <input type="hidden" id="goods_badge_'+ id +'_'+ no +'" value="'+ badge_no +'">';
			html += '          <input type="hidden" id="badge_imgpath_'+ id +'_'+ no +'" value="">';
			html += '          <li><span>상품명</span> <input type="text" id="goods_name_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="상품명" class="int140"></li>';
			html += '          <li><span>규&nbsp;&nbsp;&nbsp;격</span> <input type="text" id="goods_option_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder=" 규격" class="int140"></li>';
			html += '          <li><span>정상가</span> <input type="text" id="goods_price_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="'+ goods_price +'" class="int140"></li>';
			html += '          <li><span>할인가</span> <input type="text" id="goods_dcprice_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="'+ goods_dcprice +'" class="int140"></li>';
			if(step == "1" || step == "2"){ //1.할인&대표상품, 2.2단 이미지형
				html += '          <li><span>옵&nbsp;&nbsp;&nbsp;션</span> <input type="text" id="goods_option2_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션" class="int140"></li>';
			}else{
                html += '          <li style="display:none;"><span>옵&nbsp;&nbsp;&nbsp;션</span> <input type="text" id="goods_option2_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션" class="int140"></li>';
				//html += '          <input type="hidden" id="goods_option2_'+ id +'_'+ no +'" value="">';
			}
			// 2021-06-01 goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			html += '  			   <input type="hidden" id="goods_barcode_'+ id +'_'+ no +'" value="">';
			html += '      </ul>';
			// html += '      <label class="checkbox_container pop_input">';
			// html += '        <input type="checkbox" name="pop_chk" onClick="popChk(this);" id="pop_chk'+ no +'" value="'+ id +'_'+ no +'">';
			// html += '        <span class="checkmark"></span>';
			// html += '      </label>';
			// html += '      <button id="myBtn" class="pop_modal_btn" onClick="popPrint(\''+ id +'_'+ no +'\')">POP 인쇄</button>';
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
		if(step == "2"){ //2단 이미지형
			html += '<div class="pop_list01" id="pre_'+ id +'_'+ no +'">';
			html += stmall_html;
			html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
			html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
			html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
			html += '  </div>';
			html += '  <div class="pop_price">';
			html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000원</p>'; //정상가
			html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000원</p>'; //할인가
			html += '  </div>';
			html += '  <div><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
			html += '  <div class="pop_name">';
			html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
			html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
			html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
			html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
			// 2021-06-01 미리보기 바코드 항목 추가
			html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
			html += '  </div>';
			html += '</div>';
		}else if(step == "4"){ //3단 이미지형
			html += '<div class="pop_list03" id="pre_'+ id +'_'+ no +'">';
			html += stmall_html;
			html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
			html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
			html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
			html += '  </div>';
			html += '  <div class="pop_price">';
			html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000원</p>'; //정상가
			html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000원</p>'; //할인가
			html += '  </div>';
			html += '  <div><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
			html += '  <div class="pop_name">';
			html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
			html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
			html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
			html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
			// 2021-06-01 미리보기 바코드 항목 추가
			html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
			html += '  </div>';
			html += '</div>';
		}else if(step == "5"){ //4단 이미지형
			html += '<div class="pop_list04" id="pre_'+ id +'_'+ no +'">';
			html += stmall_html;
			html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
			html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
			html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
			html += '  </div>';
			html += '  <div class="pop_price">';
			html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000원</p>'; //정상가
			html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000원</p>'; //할인가
			html += '  </div>';
			html += '  <div><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
			html += '  <div class="pop_name">';
			html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
			html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
			html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
			html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
			// 2021-06-01 미리보기 바코드 항목 추가
			html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
			html += '  </div>';
			html += '</div>';
		}else if(step == "3"){ //1단 텍스트형
			if(psd_order_yn == "Y"){ //주문하기 사용의 경우
				html += '<div class="pop_list02 cartplus_t" id="pre_'+ id +'_'+ no +'">';
			}else{
				html += '<div class="pop_list02 cartplus" id="pre_'+ id +'_'+ no +'">';
			}
			html += '  <div class="name">';
			html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
			html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
			// 2021-06-01 미리보기 바코드 항목 추가
			html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너

			html += '  </div>';
			html += '  <span class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000원</span>'; //정상가
			html += '  <span class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000원</span>'; //할인가
			html += stmall_html;
			html += '  <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
			html += '  <span id="pre_goods_option2_'+ id +'_'+ no +'" style="display:none;"></div>'; //옵션
			html += '</div>';
		}else{ //할인&대표상품
			html += '<dl id="pre_'+ id +'_'+ no +'">';
			html += stmall_html;
			html += '  <dt>';
			html += '    <div id="pre_goods_badge_'+ id +'_'+ no +'" class="sale_badge" style="display:;">25<span>%</span></div>';
			html += '    <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
			html += '      <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
			html += '    </div>';
			html += '  </dt>';
			html += '  <dd>';
			html += '    <ul>';
			html += '        <li id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000</li>'; //정상가
			html += '        <li id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000</li>'; //할인가
			html += '        <li><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></li>'; //옵션
			html += '        <li class="name">';
			html += '          <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
			html += '          <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
			html += '          <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">1</span>'; //행사뱃지
			html += '          <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
			// 2021-06-01 미리보기 바코드 항목 추가
			html += '    	   <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
			html += '        </li>';
			html += '    </ul>';
			html += '  </dd>';
			html += '</dl>';
		}
		$('#pre_area_goods_'+ id).append(html);
		if(flag == "new"){
			$("#pre_"+ id +'_'+ no).attr("tabindex", -1).focus(); //미리보기 포커스
		}
	}

    function changeStep(id, step, goods_cnt){
        var ori_box_no = $("#goods_step"+id+"_cno").val();
        var box_no = $("#changeStep"+id).val();
        var ori = Number(ori_box_no)-1;
        var no_new = Number(box_no)-1;
        var cnt = $("#step"+id+"_cnt").val();
        var step_no = Number(step)-1;
        console.log(id+ " " +ori_box_no + " " + box_no + " " +ori + " " +no_new+ " " +cnt+ " " +step_no+ " " +step);
        if(ori==1){
            badge_all_choice(step, "step"+id+"_", '0', '');
        }
        for(var i=0; i<cnt; i++){
            $("#pre_step"+id+"_"+i).removeClass("pop_list0"+ori);
            $("#pre_step"+id+"_"+i).addClass("pop_list0"+no_new);
            $("#goods_step_step"+ id +"_"+ i).val(box_no);
            if(box_no<3){
                if($("#pre_goods_option2_step"+ id +"_"+ i).text()!=""){
                    $("#pre_goods_option2_step"+ id +"_"+ i).show();
                }
            }else{
                $("#pre_goods_option2_step"+ id +"_"+ i).hide();
            }
        }
        $("#goods_step"+id+"_cno").val(box_no);
        $("#goods_box-"+goods_cnt+"_step").val("step"+box_no);
        // $("#goods_box-"+goods_cnt+"_step_id").val("step"+box_no+"_"+step_no);
        $("#goods_box-"+goods_cnt+"_box_no").val(box_no);
        $("#pre_goods_box-"+goods_cnt).attr("tabindex", -1).focus();
        //2022-02-22 신규추가 윤재박
        $("#goods_box-"+goods_cnt).find("#add_goods").attr("onClick", "goods_append('"+box_no+"','step"+id+"','new');");
        if(box_no<3){
            $(".badge"+goods_cnt).show();
            $(".badge_all"+goods_cnt).show();
            $(".li_step"+goods_cnt).show();
        }else{
            $(".badge"+goods_cnt).hide();
            $(".badge_all"+goods_cnt).hide();
            $(".li_step"+goods_cnt).hide();
        }

    }

	//상품 삭제
	function area_del(step, no){
		//alert("step : "+ step +", no : "+ no); return;
		var id = step +"_"+ no;
		var stepNo = step.substring(4, 5); //스텝번호
		var cnt = Number($("#"+ step +"_cnt").val()); //상품수
		var nxno = Number(no)+1; //다음 상품번호
		var stepId = id.substring(0, 5); //스텝 ID
		$("#"+ step +"_cnt").val(cnt-1); //상품수
		var hdn_psd_order_yn = $('#hdn_psd_order_yn').val();
		//alert("step : "+ step +", no : "+ no +", id : "+ id +", cnt : "+ cnt +", nxno : "+ nxno +", stepId : "+ stepId); return;
		if(cnt > nxno){ //중간에 삭제된 경우
			for(var i=nxno; i<cnt; i++){
				var id1 = step +"_"+ i; //이전 ID
				var id2 = step +"_"+ (i-1); //새로운 ID
				//alert("id1 : "+ id1 +", id2 : "+ id2);
				var goods_name = $("#goods_name_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name); return;
				var goods_option = $("#goods_option_"+ id1).val();
				var goods_option2 = $("#goods_option2_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option); return;
				var goods_price = $("#goods_price_"+ id1).val();
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option +", goods_price : "+ goods_price); return;
				var goods_dcprice = $("#goods_dcprice_"+ id1).val();

				var soldout_chk = $("#soldout_chk_"+ id1).is(":checked");

				var goods_badge = $("#goods_badge_"+ id1).val();
				var badge_imgpath = $("#badge_imgpath_"+ id1).val();
				var pop_chk = $('#'+id1).find('#pop_chk'+i).is(":checked");
				//console.log('id1 - '+ id1 + ' : id2 - '+ id2 +': pop_chk' +pop_chk);
				if(pop_chk==true){
					$("#"+id2).find('#pop_chk'+(i-1)).prop("checked", true);
				}else{
					$("#"+id2).find('#pop_chk'+(i-1)).prop("checked", false);
				}


				$("#goods_badge_"+ id2).val(goods_badge); //행사뱃지
				$("#badge_imgpath_"+ id2).val(badge_imgpath); //행사뱃지

				//alert("id1 : "+ id1 +", goods_badge1 : "+ goods_badge1 +", id2 : "+ id2 +", goods_badge2 : "+ goods_badge2);
				if(goods_badge == "1"){ //할인율 표기함
					badge_choice(step, id2, goods_badge, ""); //뱃지선택
					goods_discount(stepNo, id2); //할인율 계산
				}else{
					if(goods_badge == "") goods_badge = "0";
					if(goods_badge != "0"){
						badge_choice(step, id2, goods_badge, badge_imgpath); //뱃지선택
					}else{
						badge_choice(step, id2, goods_badge, ''); //뱃지선택
					}
				}

				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option +", goods_price : "+ goods_price +", goods_dcprice : "+ goods_dcprice); return;
				var goods_seq = (i-1);
				//alert("id1 : "+ id1 +", goods_name : "+ goods_name +", goods_option : "+ goods_option +", goods_price : "+ goods_price +", goods_dcprice : "+ goods_dcprice +", goods_seq : "+ goods_seq); return;
				$("#goods_name_"+ id2).val(goods_name); //상품명
				$("#goods_option_"+ id2).val(goods_option); //규격
				$("#goods_price_"+ id2).val(goods_price); //정상가
				$("#goods_dcprice_"+ id2).val(goods_dcprice); //할인가
				$("#goods_option2_"+ id2).val(goods_option2); //옵션
				$("#goods_seq_"+ id2).val(goods_seq); //정렬순서
				$("#pre_goods_name_"+ id2).html(goods_name); //미리보기 상품명
				$("#pre_goods_option_"+ id2).html(goods_option); //미리보기 규격
				$("#pre_goods_price_"+ id2).html(goods_price); //미리보기 정상가
				$("#pre_goods_dcprice_"+ id2).html(goods_dcprice); //미리보기 할인가
				//$("#pre_goods_option2_"+ id2).html(goods_option2); //미리보기 옵션
				$("#pre_goods_option2_"+ id2).html(goods_option2); //옵션 미리보기 영역 표시값 변경
				if(goods_option2!=''){
						$("#pre_goods_option2_"+ id2).show();
				}else{
					$("#pre_goods_option2_"+ id2).hide();
				}
				if(soldout_chk==true){
					if($("#pre_div_preview_"+ id2).hasClass("soldout")){
					}else {
						$("#pre_div_preview_"+ id2).addClass("soldout");
					}
                    if ($("#pre_div_preview_"+ id2).length == 0){
                        if($("#pre_"+ id2).hasClass("soldout")){
    					}else {
    						$("#pre_"+ id2).addClass("soldout");
    					}
                    }
					$("#soldout_chk_"+id2).prop("checked", true);
                    $("#goods_soldout_"+id2).val("N");
					$("#pre_"+id2).children(".icon_add_cart").hide();

				}else{
					if($("#pre_div_preview_"+ id2).hasClass("soldout")){
						$("#pre_div_preview_"+ id2).removeClass("soldout");
					}
                    if ($("#pre_div_preview_"+ id2).length == 0){
                        if($("#pre_"+ id2).hasClass("soldout")){
    						$("#pre_"+ id2).removeClass("soldout");
    					}
                    }
					$("#soldout_chk_"+id2).prop("checked", false);
                    $("#goods_soldout_"+id2).val("Y");
					if(hdn_psd_order_yn=='Y'){
						$("#pre_"+id2).children(".icon_add_cart").show();
					}
				}
				if(stepId == "step1" || stepId == "step2" || stepId == "step4" || stepId == "step5"){ //이미지형
					remove_img("_"+ id2);
					var goods_imgpath = $("#goods_imgpath_"+ id1).val();
					$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath + ")"}); //사진
					$("#goods_imgpath_"+ id2).val(goods_imgpath); //이미지경로
                    if (goods_imgpath == ""){
                        goods_imgpath = "/dhn/images/leaflets/sample_img.jpg";
                    }
					$("#pre_div_preview_"+ id2).css({"background":"url(" + goods_imgpath + ")"}); //미리보기 사진
					$("#pre_goods_imgpath_"+ id2).html(goods_imgpath); //미리보기 이미지경로
					//$("#goods_badge_"+ id2).val(goods_badge_cd); //뱃지번호
					//$("#badge_imgpath_"+ id2).val(goods_badge_img); //뱃지이미지
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
		var stepNo = step.substring(4, 5); //스텝번호
		//alert("type : "+ type +", step : "+ step +", id : "+ id +", stepNo : "+ stepNo); return;
		var i = 0;
		var seq = Number($("#goods_seq_"+ id).val()); //정렬번호
		var cnt = Number($("#"+ step +"_cnt").val()); //전체수
		//alert("seq : "+ seq +", cnt : "+ cnt); return;
		var no = 0;
		var no1 = 0;
		var no2 = 0;
		var id1 = "";
		var id2 = "";
		var hdn_psd_order_yn = $('#hdn_psd_order_yn').val();
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
			var badge_imgpath1 = "";
			var goods_option21 = $("#goods_option2_"+ id1).val();
			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			var goods_barcode1 = $("#goods_barcode_"+ id1).val();		//바코드(상품코드)
			var soldout_chk1 = $("#soldout_chk_"+ id1).is(":checked");
			var pop_chk1 = $('#'+id1).find('#pop_chk'+no1).is(":checked");
			//console.log('id1 - '+ id1 + ' : id2 - '+ id2 +': pop_chk' +pop_chk);


			var goods_name2 = $("#goods_name_"+ id2).val();
			var goods_option2 = $("#goods_option_"+ id2).val();
			var goods_price2 = $("#goods_price_"+ id2).val();
			var goods_dcprice2 = $("#goods_dcprice_"+ id2).val();
			var goods_imgpath2 = $("#goods_imgpath_"+ id2).val();
			var goods_seq2 = $("#goods_seq_"+ id2).val();
			var goods_badge2 = "";
			var badge_imgpath2 = "";
			var goods_option22 = $("#goods_option2_"+ id2).val();
			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			var goods_barcode2 = $("#goods_barcode_"+ id2).val();	//바코드(상품코드)
			var soldout_chk2 = $("#soldout_chk_"+ id2).is(":checked");
			var pop_chk2 = $('#'+id2).find('#pop_chk'+no2).is(":checked");
			//console.log('id1 - '+ id1 + ' : id2 - '+ id2 +': pop_chk' +pop_chk);

			//alert("goods_name1 : "+ goods_name1 +", goods_seq1 : "+ goods_seq1 +"\n"+"goods_name2 : "+ goods_name2 +", goods_seq2 : "+ goods_seq2 +"\n"+"goods_badge1 : "+ goods_badge1 +", goods_badge2 : "+ goods_badge2); return;
			remove_img("_"+ id1);
			$("#div_preview_"+ id1).css({"background":"url(" + goods_imgpath2 + ")"});
			$("#goods_name_"+ id1).val(goods_name2); //상품명
			$("#goods_option_"+ id1).val(goods_option2); //규격
			$("#goods_price_"+ id1).val(goods_price2); //정상가
			$("#goods_dcprice_"+ id1).val(goods_dcprice2); //할인가
			$("#goods_imgpath_"+ id1).val(goods_imgpath2); //이미지경로
			$("#goods_option2_"+ id1).val(goods_option22); //옵션
			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			$("#goods_barcode_"+ id1).val(goods_barcode2);	//바코드(상품코드)
			if(soldout_chk2==true){
				$("#soldout_chk_"+id1).prop("checked", true);
                $("#goods_soldout_"+id1).val("N");
				if($("#pre_div_preview_"+ id1).hasClass("soldout")){

				}else {
					$("#pre_div_preview_"+ id1).addClass("soldout");
				}
				$("#pre_"+id1).children(".icon_add_cart").hide();
			}else{
				$("#soldout_chk_"+id1).prop("checked", false);
                $("#goods_soldout_"+id1).val("Y");
				if($("#pre_div_preview_"+ id1).hasClass("soldout")){
					$("#pre_div_preview_"+ id1).removeClass("soldout");
				}
				$("#pre_"+id1).children(".icon_add_cart").show();
			}
			if(pop_chk2==true){
				$("#"+id1).find('#pop_chk'+no1).prop("checked", true);
			}else{
				$("#"+id1).find('#pop_chk'+no1).prop("checked", false);
			}

			//$("#goods_seq_"+ id1).val(goods_seq1); //정렬순서
			var pre_goods_imgpath2 = goods_imgpath2;
			if(pre_goods_imgpath2 == "" || pre_goods_imgpath2 == null) pre_goods_imgpath2 = "<?=$sample_imgpath?>";
			$("#pre_div_preview_"+ id1).css({"background":"url(" + pre_goods_imgpath2 + ")"});
			$("#pre_goods_name_"+ id1).html(goods_name2); //미리보기 상품명
			$("#pre_goods_option_"+ id1).html(goods_option2); //미리보기 규격
			$("#pre_goods_price_"+ id1).html(goods_price2); //미리보기 정상가
			if(goods_option22 != ""){
				$("#pre_goods_option2_"+ id1).show(); //옵션 미리보기 닫기
				$("#pre_goods_option2_"+ id1).html(goods_option22); //옵션 미리보기 영역
			}else{
				$("#pre_goods_option2_"+ id1).hide(); //옵션 미리보기 열기
				$("#pre_goods_option2_"+ id1).html(""); //옵션 미리보기 영역
			}

			var pre_goods_dcprice2 = goods_dcprice2;
			if(stepNo == "1"){
				//alert("pre_goods_dcprice2 : "+ pre_goods_dcprice2);
				pre_goods_dcprice2 = pre_goods_dcprice2.replace(/원/gi, '<span class="sm_small">원</span>'); //원 작은글씨 처리
			}
			$("#pre_goods_dcprice_"+ id1).html(pre_goods_dcprice2); //미리보기 할인가
			$("#pre_goods_imgpath_"+ id1).html(pre_goods_imgpath2); //미리보기 이미지경로
			remove_img("_"+ id2);
			$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath1 + ")"});
			$("#goods_name_"+ id2).val(goods_name1); //상품명
			$("#goods_option_"+ id2).val(goods_option1); //규격
			$("#goods_price_"+ id2).val(goods_price1); //정상가
			$("#goods_dcprice_"+ id2).val(goods_dcprice1); //할인가
			$("#goods_imgpath_"+ id2).val(goods_imgpath1); //이미지경로
			$("#goods_option2_"+ id2).val(goods_option21); //옵션
			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			$("#goods_barcode_"+ id2).val(goods_barcode1);	//바코드(상품코드)
			//$("#goods_seq_"+ id2).val(goods_seq2); //정렬순서
			var pre_goods_imgpath1 = goods_imgpath1;
			if(pre_goods_imgpath1 == "" || pre_goods_imgpath1 == null) pre_goods_imgpath1 = "<?=$sample_imgpath?>";
			$("#pre_div_preview_"+ id2).css({"background":"url(" + pre_goods_imgpath1 + ")"});
			$("#pre_goods_name_"+ id2).html(goods_name1); //미리보기 상품명
			$("#pre_goods_option_"+ id2).html(goods_option1); //미리보기 규격
			$("#pre_goods_price_"+ id2).html(goods_price1); //미리보기 정상가
			if(goods_option21 != ""){
				$("#pre_goods_option2_"+ id2).show(); //옵션 미리보기 닫기
				$("#pre_goods_option2_"+ id2).html(goods_option21); //옵션 미리보기 영역
			}else{
				$("#pre_goods_option2_"+ id2).hide(); //옵션 미리보기 열기
				$("#pre_goods_option2_"+ id2).html(""); //옵션 미리보기 영역
			}
			if(soldout_chk1==true){
				$("#soldout_chk_"+id2).prop("checked", true);
				if($("#pre_div_preview_"+ id2).hasClass("soldout")){
				}else {
					$("#pre_div_preview_"+ id2).addClass("soldout");
				}
                $("#goods_soldout_"+id2).val("N");
				$("#pre_"+id2).children(".icon_add_cart").hide();

			}else{
				$("#soldout_chk_"+id2).prop("checked", false);
				if($("#pre_div_preview_"+ id2).hasClass("soldout")){
					$("#pre_div_preview_"+ id2).removeClass("soldout");
				}
                $("#goods_soldout_"+id2).val("Y");
				if(hdn_psd_order_yn=='Y'){
						$("#pre_"+id2).children(".icon_add_cart").show();
				}
			}
			if(pop_chk1==true){
				$("#"+id2).find('#pop_chk'+no2).prop("checked", true);
			}else{
				$("#"+id2).find('#pop_chk'+no2).prop("checked", false);
			}
			var pre_goods_dcprice1 = goods_dcprice1;
			if(stepNo == "1"){
				//alert("pre_goods_dcprice1 : "+ pre_goods_dcprice1);
				pre_goods_dcprice1 = pre_goods_dcprice1.replace(/원/gi, '<span class="sm_small">원</span>'); //원 작은글씨 처리
			}
			$("#pre_goods_dcprice_"+ id2).html(pre_goods_dcprice1); //미리보기 할인가
			$("#pre_goods_imgpath_"+ id2).html(pre_goods_imgpath1); //미리보기 이미지경로
			//alert("d1 : "+ d1 +", d2 : "+ d2);
			if(stepNo == "1" || stepNo == "2"){
				goods_badge1 = $("#goods_badge_"+ id1).val();
				badge_imgpath1 = $("#badge_imgpath_"+ id1).val();
				goods_badge2 = $("#goods_badge_"+ id2).val();
				badge_imgpath2 = $("#badge_imgpath_"+ id2).val();
				$("#goods_badge_"+ id1).val(goods_badge2); //행사뱃지
				$("#badge_imgpath_"+ id1).val(badge_imgpath2); //행사뱃지
				$("#goods_badge_"+ id2).val(goods_badge1); //행사뱃지
				$("#badge_imgpath_"+ id2).val(badge_imgpath1); //행사뱃지
				//alert("id1 : "+ id1 +", goods_badge1 : "+ goods_badge1 +", id2 : "+ id2 +", goods_badge2 : "+ goods_badge2);
				if(goods_badge2 == "1"){ //할인율 표기함
					badge_choice(step, id1, goods_badge2, ""); //뱃지선택
					goods_discount(stepNo, id1); //할인율 계산
				}else{
					if(goods_badge2 == "") goods_badge2 = "0";
					if(goods_badge2 != "0"){
						badge_choice(step, id1, goods_badge2, badge_imgpath2); //뱃지선택
					}else{
						badge_choice(step, id1, goods_badge2, ''); //뱃지선택
					}
				}
				if(goods_badge1 == "1"){ //할인율 표기함
					badge_choice(step, id2, goods_badge1, ''); //뱃지선택
					goods_discount(stepNo, id2); //할인율 계산
				}else{
					if(goods_badge1 == "") goods_badge1 = "0";
					if(goods_badge1 != "0"){
						badge_choice(step, id2, goods_badge1, badge_imgpath1); //뱃지선택
					}else{
						badge_choice(step, id2, goods_badge1, '');
					}
				}
			}
		}else if(type == "first" || type == "last"){ //처음으로, 끝으로 클릭
			var goods_name_temp = "";
			var goods_option_temp = "";
			var goods_price_temp = "";
			var goods_dcprice_temp = "";
			var goods_imgpath_temp = "";
			var goods_badge_cd_temp = "";
			var goods_badge_img_temp = "";
			var goods_option2_temp = "";
			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			var goods_barcode_temp = "";
			// 2021-06-01  지역변수 선언 - 이수환
			var soldout_chk_temp = "";
			var pop_chk_temp ="";
			var goods_name = "";
			var goods_option = "";
			var goods_price = "";
			var goods_dcprice = "";
			var goods_imgpath = "";
			var goods_option2 = "";
			var goods_barcode = "";
			var soldout_chk = "";
			var pop_chk = "";
			var pop_arr = [];

			if(type == "first"){ //처음으로
				no1 = 0;
				no2 = Number(seq);
				goods_name_temp = $("#pre_goods_name_"+ step +"_"+ no2).html();
				goods_option_temp = $("#pre_goods_option_"+ step +"_"+ no2).html();
				goods_price_temp = $("#pre_goods_price_"+ step +"_"+ no2).html();
				goods_dcprice_temp = $("#pre_goods_dcprice_"+ step +"_"+ no2).html();
				goods_imgpath_temp = $("#pre_goods_imgpath_"+ step +"_"+ no2).html();
				goods_option2_temp = $("#pre_goods_option2_"+ step +"_"+ no2).html();
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				goods_barcode_temp = $("#pre_goods_barcode_"+ step +"_"+ no2).html();
				//soldout_chk_temp = $("#soldout_chk_"+ step +"_"+ no2).is(":checked");
				soldout_chk_temp = $("#pre_div_preview_"+ step +"_"+ no2).hasClass("soldout");
				pop_chk_temp = $('#'+step +"_"+ no2).find('#pop_chk'+no2).is(":checked");
					console.log(step +"_"+ no2 + ':'+ soldout_chk_temp);
				if(stepNo == "1"){
					goods_dcprice_temp = goods_dcprice_temp.replace(/<span class="sm_small">원<\/span>/gi, '원'); //원 작은글씨 처리
				}
				if(stepNo == "1" || stepNo == "2"){
					goods_badge_cd_temp = $("#pre_goods_badge_cd_"+ step +"_"+ no2).html();
					if(goods_badge_cd_temp !="" && goods_badge_cd_temp != "0" && goods_badge_cd_temp != "1"){ //뱃지 이미지 사용의 경우
						goods_badge_img_temp = $("#pre_badge_imgpath_"+ step +"_"+ no2).attr("src");
						//alert("goods_badge_img_temp : "+ goods_badge_img_temp);
					}
				}
			}else if(type == "last"){ //끝으로
				no1 = Number(seq);
				no2 = Number(cnt)-1;
				goods_name_temp = $("#pre_goods_name_"+ step +"_"+ no1).html();
				goods_option_temp = $("#pre_goods_option_"+ step +"_"+ no1).html();
				goods_price_temp = $("#pre_goods_price_"+ step +"_"+ no1).html();
				goods_dcprice_temp = $("#pre_goods_dcprice_"+ step +"_"+ no1).html();
				goods_imgpath_temp = $("#pre_goods_imgpath_"+ step +"_"+ no1).html();
				goods_option2_temp = $("#pre_goods_option2_"+ step +"_"+ no1).html();
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				goods_barcode_temp = $("#pre_goods_barcode_"+ step +"_"+ no1).html();
				soldout_chk_temp = $("#pre_div_preview_"+ step +"_"+ no1).hasClass("soldout");
				pop_chk_temp = $('#'+step +"_"+ no1).find('#pop_chk'+no1).is(":checked");
				//soldout_chk_temp = $("#soldout_chk_"+ step +"_"+ no1).is(":checked");
				if(stepNo == "1"){
					goods_dcprice_temp = goods_dcprice_temp.replace(/<span class="sm_small">원<\/span>/gi, '원'); //원 작은글씨 처리
				}
				if(stepNo == "1" || stepNo == "2"){
					goods_badge_cd_temp = $("#pre_goods_badge_cd_"+ step +"_"+ no1).html();
					if(goods_badge_cd_temp !="" && goods_badge_cd_temp != "0" && goods_badge_cd_temp != "1"){ //뱃지 이미지 사용의 경우
						goods_badge_img_temp = $("#pre_badge_imgpath_"+ step +"_"+ no1).attr("src");
						//alert("goods_badge_img_temp : "+ goods_badge_img_temp);
					}
				}
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
				var goods_badge_cd = "";
				var goods_badge_img = "";
				if( (type == "first" && ii == no1) || (type == "last" && ii == no2) ){ //(처음으로 && 선택 상품) || (끝으로 && 선택 상품)
					goods_name = goods_name_temp;
					goods_option = goods_option_temp;
					goods_price = goods_price_temp;
					goods_dcprice = goods_dcprice_temp;
					goods_imgpath = goods_imgpath_temp;
					goods_option2 = goods_option2_temp;
					// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
					goods_barcode = goods_barcode_temp;
					soldout_chk = soldout_chk_temp;
					pop_chk = pop_chk_temp;
					//console.log('soldout_chk_temp ' + soldout_chk_temp);
						console.log(ii + '  ii - soldout_chk ' + soldout_chk +': soldout_chk_temp '+ soldout_chk_temp);
					if(stepNo == "1"){
						goods_dcprice = goods_dcprice.replace(/<span class="sm_small">원<\/span>/gi, '원'); //원 작은글씨 처리
					}
					if(stepNo == "1" || stepNo == "2"){
						goods_badge_cd = goods_badge_cd_temp;
						goods_badge_img = goods_badge_img_temp;
					}
					//alert("goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_imgpath : "+ goods_imgpath); return;
				}else{
					//alert("id1 : "+ id1 +", id2 : "+ id2);
					goods_name = $("#pre_goods_name_"+ id1).html();
					goods_option = $("#pre_goods_option_"+ id1).html();
					goods_price = $("#pre_goods_price_"+ id1).html();
					goods_dcprice = $("#pre_goods_dcprice_"+ id1).html();
					goods_imgpath = $("#pre_goods_imgpath_"+ id1).html();
					goods_option2 = $("#pre_goods_option2_"+ id1).html();
					// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
					goods_barcode = $("#pre_goods_barcode_"+ id1).html();
					soldout_chk = $("#pre_div_preview_"+ id1).hasClass("soldout");
					pop_chk = $('#'+id1).find('#pop_chk'+(ii-1)).is(":checked");
					/*if($("#pre_div_preview_"+ id1).hasClass("soldout")){
					}else{

					}*/
					//soldout_chk = $("#soldout_chk_"+ id1).is(":checked");
					//console.log(id1 + '  id1 ' + soldout_chk +':'+id2+ ' id2 '+ $("#soldout_chk_"+ id2).is(":checked"));
					console.log(ii + '  ii - soldout_chk -'+id1+ '-' + $("#soldout_chk_"+ id1).is(":checked") +': id2 '+ id2 + ' - ' +$("#soldout_chk_"+ id2).is(":checked"));
					if(stepNo == "1"){
						goods_dcprice = goods_dcprice.replace(/<span class="sm_small">원<\/span>/gi, '원'); //원 작은글씨 처리
					}
					if(stepNo == "1" || stepNo == "2"){
						goods_badge_cd = $("#pre_goods_badge_cd_"+ id1).html();
						if(goods_badge_cd !="" && goods_badge_cd != "0" && goods_badge_cd != "1"){ //뱃지 이미지 사용의 경우
							goods_badge_img = $("#pre_badge_imgpath_"+ id1).attr("src");
							//alert("goods_badge_img : "+ goods_badge_img);
						}
					}
				}
				//alert("["+ ii +"] id1 : "+ id2 +", id2 : "+ id2 +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_imgpath : "+ goods_imgpath +"\n"+"goods_badge_cd : "+ goods_badge_cd);
				$("#goods_name_"+ id2).val(goods_name); //상품명
				$("#goods_option_"+ id2).val(goods_option); //규격
				$("#goods_price_"+ id2).val(goods_price); //정상가
				$("#goods_dcprice_"+ id2).val(goods_dcprice); //할인가
				<? // 2021-06-02 처음, 끝으로 이동할때 입력부 노이미지 안나타나게 처리?>
				$("#goods_imgpath_"+ id2).val(goods_imgpath.replace("<?=$sample_imgpath?>", "")); //이미지경로
				//$("#goods_imgpath_"+ id2).val(goods_imgpath); //이미지경로

				$("#goods_option2_"+ id2).val(goods_option2); //옵션
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				$("#goods_barcode_"+ id2).val(goods_barcode); //바코드(상품코드)

				<? // 2021-06-02 처음, 끝으로 이동할때 입력부 노이미지 안나타나게 처리?>
				$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath.replace("<?=$sample_imgpath?>", "") + ")"}); //이미지배경URL
				console.log(id1 + '  soldout_chk ' + soldout_chk +':'+id2+ ' id2 '+ $("#soldout_chk_"+ id2).is(":checked"));
				//$("#div_preview_"+ id2).css({"background":"url(" + goods_imgpath + ")"}); //이미지배경URL
				//if(ii>=0){
					$("#soldout_chk_"+id2).prop("checked", soldout_chk);
					pop_arr.push(soldout_chk);
				//}
				//if(soldout_chk==true){
				/*	if($("#pre_div_preview_"+ id2).hasClass("soldout")){
					}else {
						$("#pre_div_preview_"+ id2).addClass("soldout");
					}*/
				//	$("#soldout_chk_"+id2).prop("checked", true);

			//	}else{
				/*	if($("#pre_div_preview_"+ id2).hasClass("soldout")){
						$("#pre_div_preview_"+ id2).removeClass("soldout");
					}*/
				//	$("#soldout_chk_"+id2).prop("checked", false);
			//	}
				if(stepNo == "1" || stepNo == "2"){
					$("#goods_badge_"+ id2).val(goods_badge_cd); //뱃지번호
					$("#badge_imgpath_"+ id2).val(goods_badge_img); //뱃지이미지
				}
				//console.log(id2 + '  id2 ' + soldout_chk);
			}
			for(var ii=no1; ii<=no2; ii++){
				var gid = step +"_"+ ii;
				var goods_dcprice = $("#goods_dcprice_"+ gid).val();
				var goods_option2 = $("#goods_option2_"+ gid).val();
				var soldout_chk = $("#soldout_chk_"+ gid).is(":checked");
				var pop_chk = pop_arr[ii];
				//alert("["+ ii +"] goods_option2 : "+ goods_option2);
				//console.log(gid + '  soldout_chk ' + soldout_chk);
				$('#'+gid).find('#pop_chk'+ii).prop("checked",pop_chk);
				if(stepNo == "1"){
					goods_dcprice = goods_dcprice.replace(/원/gi, '<span class="sm_small">원</span>'); //원 작은글씨 처리
				}
				$("#pre_goods_name_"+ gid).html($("#goods_name_"+ gid).val()); //상품명
				$("#pre_goods_option_"+ gid).html($("#goods_option_"+ gid).val()); //규격
				$("#pre_goods_price_"+ gid).html($("#goods_price_"+ gid).val()); //정상가
				$("#pre_goods_dcprice_"+ gid).html(goods_dcprice); //할인가
				<? // 2021-06-02 처음, 끝으로 이동할때 입력부 노이미지 안나타나게 처리?>
				var tempimagelink = $("#goods_imgpath_"+ gid).val();
				if(tempimagelink == "" || tempimagelink == null) tempimagelink = "<?=$sample_imgpath?>";
				$("#pre_goods_imgpath_"+ gid).html(tempimagelink); //이미지경로
				//$("#pre_goods_imgpath_"+ gid).html($("#goods_imgpath_"+ gid).val()); //이미지경로

				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				$("#pre_goods_barcode_"+ gid).html($("#goods_barcode_"+ gid).val()); //바코드(상품코드)

				if(soldout_chk==true){
                    $("#goods_soldout_"+gid).val("N");
					if($("#pre_div_preview_"+ gid).hasClass("soldout")){
					}else {
						$("#pre_div_preview_"+ gid).addClass("soldout");
					}
					//$("#soldout_chk_"+gid).prop("checked", true);
					$("#pre_"+id2).children(".icon_add_cart").hide();
				}else{
                    $("#goods_soldout_"+gid).val("Y");
					if($("#pre_div_preview_"+ gid).hasClass("soldout")){
						$("#pre_div_preview_"+ gid).removeClass("soldout");
					}
				//	$("#soldout_chk_"+gid).prop("checked", false);
					if(hdn_psd_order_yn=='Y'){
						$("#pre_"+id2).children(".icon_add_cart").show();
					}
				}

				if(goods_option2 != ""){
					$("#pre_goods_option2_"+ gid).show(); //옵션 미리보기 닫기
					$("#pre_goods_option2_"+ gid).html(goods_option2); //옵션 미리보기 영역
				}else{
					$("#pre_goods_option2_"+ gid).hide(); //옵션 미리보기 열기
					$("#pre_goods_option2_"+ gid).html(""); //옵션 미리보기 영역
				}
				<? // 2021-06-02 변경 ?>
				//$("#pre_div_preview_"+ gid).css({"background":"url(" + $("#goods_imgpath_"+ gid).val() + ")"}); //이미지배경URL
				$("#pre_div_preview_"+ gid).css({"background":"url(" + tempimagelink + ")"}); //이미지배경URL

				if(stepNo == "1" || stepNo == "2"){
					var goods_badge = $("#goods_badge_"+ gid).val();
					//alert("["+ ii +"] goods_badge : "+ goods_badge);
					if(goods_badge == "1"){ //할인율 표기함
						badge_choice(step, gid, goods_badge, ''); //뱃지선택
						goods_discount(stepNo, gid); //할인율 계산
					}else{
						if(goods_badge == "") goods_badge = "0";
						if(goods_badge != "0"){
							var badge_imgpath = $("#badge_imgpath_"+ gid).val();
							badge_choice(step, gid, goods_badge, badge_imgpath);
						}else{
							badge_choice(step, gid, goods_badge, '');
						}
					}
				}
			}
		}
	}

	//상품 영역 복사
	function goods_area_copy(area, step){
		var step_cnt = Number($("#"+ step +"_cnt").val()); //기존 라인수
		var no = step_cnt + 1;
		//alert("area : "+ area +", step : "+ step +", step_cnt : "+ step_cnt +", no : "+ no);
		//if(step == "step3") alert("area : "+ area +", step : "+ step +", step_cnt : "+ step_cnt +", no : "+ no); return;
		var regex = new RegExp(step +'-0', 'gi'); //변경값
		var copy = copy = $("#"+ area +"-0").html(); //입력란 복사 대상 영역
		//if(step == "step3") alert("area : "+ area +", step : "+ step +", copy : "+ copy); return;
		//alert("area : "+ area +"\n"+ "step : "+ step +"\n"+ "copy : "+ copy); return;

		var goods_box_cnt = Number($("#goods_box_cnt").val()); //상품 박스 갯수
		var next_box_no = goods_box_cnt+1; //다음 상품 박스 갯수
		//alert("next_box_no : "+ next_box_no);
		var stepId = step +'-'+ no;
		var newId = "goods_box-"+ next_box_no;
		var regex2 = new RegExp('goods_box-0', 'gi'); //변경값
		//alert("newId : "+ newId);
		var html = '';
		if(step == "step9"){
			html += '<div id="'+ newId +'" style="display:none;">\n';
		}else{
			html += '<div id="'+ newId +'">\n';
		}
		html += '  <input type="hidden" name="goods_box_id" id="'+ newId +'_name" value="'+ newId +'" style="width:120px;">';
		html += '  <input type="hidden" id="'+ newId +'_step" value="'+ step +'" style="width:80px;">';
		html += '  <input type="hidden" id="'+ newId +'_step_id" value="'+ stepId +'" style="width:80px;">';
		html += '  <input type="hidden" id="'+ newId +'_step_no" value="'+ no +'" style="width:60px;">';
		html += '  <input type="hidden" id="'+ newId +'_box_no" value="'+ next_box_no +'" style="width:60px;">';
		html += '  '+ copy.replace(regex, stepId).replace(regex2, newId);
		html += '</div>\n'; //입력란 붙여넣기 HTML
		// console.log("html : "+ html); return;
		//if(step == "step3") alert("html : "+ html); return;
		$("#box_area").append(html); //입력란 붙여넣기 영역
		$("#"+ step +"-"+ no +"_no").val(no); //라인번호
		//alert(step +"-"+ no +"_no"); return;

		//미리보기 영역
		var pre_copy = $("#pre_"+ area +"-0").html(); //미리보기 복사 대상 영역
		var pre_class = "";
		if(step == "step2" || step == "step4" || step == "step5"){ //이미지형
			pre_class = ' class="pre_box3"'
		}else if(step == "step3"){ //텍스트형
			pre_class = ' class="pre_box4"'
		}
		if(step == "step9"){
			html = '<div'+ pre_class +' id="pre_'+ newId +'" style="display:none;">\n'+ pre_copy.replace(regex, stepId) +'</div>\n'; //입력란 붙여넣기 HTML
		}else{
			html = '<div'+ pre_class +' id="pre_'+ newId +'">\n'+ pre_copy.replace(regex, stepId) +'</div>\n'; //입력란 붙여넣기 HTML
			//html = '<div'+ pre_class +' id="pre_'+ newId +'">\n'+ pre_copy +'</div>\n'; //입력란 붙여넣기 HTML
		}
		//alert("html : "+ html); return;
		$("#pre_box_area").append(html); //입력란 붙여넣기 영역
		$("#"+ step +"_cnt").val(no); //라인수 증가
		if(step_cnt > 0){ //등록된 상품영역이 있는 경우
			$("#btn_add_"+ step +"-"+ (step_cnt)).css("display", "none"); //이전 상품영역 추가 버튼 비활성화
		}
		$("#goods_tit_"+ stepId).html(no); //박스 코너별 타이틀 번호
		$("#goods_box_cnt").val(next_box_no); //상품 박스 번호
		$("#pre_"+ newId).attr("tabindex", -1).focus(); //미리보기 포커스
		//alert("newId : "+ newId); return;//pre_goods_box-9


		if(step != "step9"){
            <? if(!empty($psd_id)){?>
            $("#"+newId).find(".goods_down").attr("style", "display:none");
            <? } ?>
			window.scroll(0, getOffsetTop(document.getElementById(newId))); //신규 박스 상단으로 이동
		}
	}


	//타이틀 직접입력 클릭시
	function titimg_self_img(input, step){
		// alert("step 5번째 : " + step.charAt(4) ); return;
		var step_4 = step.charAt(4);
		var tit_type_self ="S";
		if(step_4 == 3){
			tit_type = "T";
		}else{
			tit_type = "I";
		}
		$("input:radio[name='tit_id_"+step+"']").prop('checked', false);
		$("#t_tit_text_info_"+step).val($("#h_tit_unchecked_"+step).val());
		$("#t_tit_text_info_"+step).keyup(function(){
			$("#h_tit_unchecked_"+step).val($(this).val());
		});


		// alert("tit_type : " +tit_type ); return;
		// // var input_place = input;
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
						$("#"+ imgid).attr("src", "");
						$("#pre_"+ imgid).attr("src", "");
						alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;

					// var step_cnt = Number($("#"+ step +"_cnt").val()); //기존 라인수
					// var no = step_cnt + 1;
					// var stepId = step +'-'+ step_cnt;
					// alert("step : " + step); return;
					var formData = new FormData();
					formData.append("imgfile", input.files[0]); //이미지
					formData.append("uppath", "<?=$titimg_self_path?>"); //업로드경로
					formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
					$.ajax({
						url: "/spop/printer/imgfile_save",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function (json) {
							//alert("json.code : "+ json.code);
							if(json.code == "0" && json.imgpath != "") { //성공
								jsFileRemove(input); //파일 초기화
								var imgpath = json.imgpath; //템플릿 이미지 경로
								// alert(json.imgpath);
								$.ajax({
									url: "/spop/screen/title_self_save",
									type: "POST",
									data: {
										 "imgpath" : imgpath //템플릿 이미지 경로
										, "tit_type" : tit_type // 타이틀 타입지정
										, "tit_type_self" : tit_type_self //타이틀 타이틀 직접입력
										, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
									},
									success: function (json) {
										if(json.code == "0" && json.tit_id != "" && json.tit_imgpath) { //성공
											var rs_tit_img = "<img src=\'"+json.tit_imgpath+"\'>"
											//저장할때 self 저장유무 체크
											$("#tit_id_self_yn_"+ step).val("S");
											//직접입력의 경우 tit_id 따로 저장
											$("#tit_id_self_"+ step).val(json.tit_id);
											$("#tit_img_preview_"+ step).attr("src", json.tit_imgpath);
											// alert("rs_tit_img : " + rs_tit_img);
											// $("#goods_imgpath_"+ step).val(json.tit_imgpath); //이미지 경로
											$("#pre_tit_id_"+ step).html(rs_tit_img); //입력에 값 넣기
											$("#pre_tit_id_"+ step).attr("tabindex", -1).focus(); //미리보기 포커스
											$("#t_tit_text_info_"+ step).focus(); //바로가기 입력창 포커스
											// stepId ="";
										}
									}
								});
								return;
							}else{
								jsFileRemove(input); //파일 초기화
								alert("처리중 오류가 발생하였습니다.");
								return;
							}
						}
					});
				}
			}
		}
	}

	//상품 영역 삭제
	function goods_area_del(area){
		$("#"+ area).remove(); //라인 삭제
		$("#pre_"+ area).remove(); //미리보기 라인 삭제
	}

	//영역에 값 넣기
	function areaSet(id, html, text){
		var s_id = id.substring(11);
		// alert("id : "+ id +" html : " + html + " text : " +text);
		$("#"+ id).html(html); //입력에 값 넣기
		//선택안함 선택시
		if(html ==''){
			//선택안함시
			$("#t_tit_text_info_"+s_id).val($("#h_tit_unchecked_"+s_id).val());
			$("#t_tit_text_info_"+s_id).keyup(function(){
				$("#h_tit_unchecked_"+s_id).val($(this).val());
			});
            // 직접입력 타이틀 시 선택안함 오류 수정 2022-06-08 윤재박
            if($('input[name=tit_id_'+s_id+']:checked').val()=='0'){
                $("#tit_id_self_yn_"+s_id).val('U');
            }
			//타이틀직접입력 이미지 초기화
			$("#tit_img_preview_"+s_id).attr("src", "");

			$("#t_tit_text_info_"+ s_id).focus(); //바로가기 입력창 포커스
			return;
		}
		$("#"+ id).attr("tabindex", -1).focus(); //미리보기 포커스
		//타이틀직접입력 이미지 초기화
		$("#tit_img_preview_"+s_id).attr("src", "");
		$("#tit_id_self_yn_"+ s_id).val("U");
		$("#t_tit_text_info_"+s_id).val(text);
		// alert($("#h_tit_unchecked_"+s_id).val());
	}



	//상품 엑셀 업로드
	function excelExport(input, step, id){
		var file = document.getElementById("excelFile_"+ id).value;
		//alert("file : "+ file); return;
		//alert("step : "+ step +", id : "+ id); return;
		var ext = file.slice(file.indexOf(".") + 1).toLowerCase();
		//alert("file : "+ file +", ext : "+ ext); return;
		if (ext == "xls" || ext == "xlsx") {
            jsLoading();
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
				url: "/spop/screen/excel_upload_ok",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
					//alert("excel_upload_ok");
					var no = 0;
					$.each(json, function(key, value){
						var goods_code = value.code; // 상품코드 - 이수환
						var goods_name = value.name; //상품명
						var goods_option = value.option; //규격
						var goods_price = value.price; //정상가
						var goods_dcprice = value.dcprice; //할인가
						var goods_option2 = value.option2; //옵션
						var goods_imgpath = value.imgpath; //이미지 경로
						var goods_seq = value.seq; //정렬순서
						var rtn_data = value.rtn_data; //리턴값 : 스텝No§§스텝ID
						var arr_data = rtn_data.split("§§");
						var rtn_sno = arr_data[0]; //스텝No
						var rtn_sid = arr_data[1]; //스텝ID
						//alert("["+ no +"] "+ goods_name +", "+ goods_option +", "+ goods_price +", "+ goods_dcprice +", "+ rtn_data);
						if(goods_name != undefined && goods_name != "" && goods_name != null){ //상품명이 있는 경우
							var flag = "excel";
							if(no == 0) flag = "new";
							goods_append(rtn_sno, rtn_sid, flag); //상품정보 입력란 추가
							var stepId = rtn_sid +"_"+ no;
							$("#goods_barcode_"+ stepId).val(goods_code); //상품코드 - 이수환
							$("#goods_name_"+ stepId).val(goods_name); //상품명
							$("#goods_option_"+ stepId).val(goods_option); //규격
							$("#goods_price_"+ stepId).val(goods_price); //정상가
							$("#goods_dcprice_"+ stepId).val(goods_dcprice); //할인가
							$("#goods_seq_"+ stepId).val(no); //정렬순서
							if(goods_name == "") goods_name = "상품명"; //미리보기 상품명
							if(goods_option == "") goods_option = ""; //미리보기 규격
							if(goods_price == "") goods_price = ""; //미리보기 정상가
							if(goods_dcprice == "") goods_dcprice = "0원"; //미리보기 할인가
							$("#pre_goods_name_"+ stepId).html(goods_name); //미리보기 상품명
							$("#pre_goods_option_"+ stepId).html(goods_option); //미리보기 규격
							$("#pre_goods_price_"+ stepId).html(goods_price); //미리보기 정상가
							$("#pre_goods_dcprice_"+ stepId).html(goods_dcprice); //미리보기 할인가
							chgGoodsData(stepId, "excel", "N");
							if(rtn_sno != "3" && goods_name != "" && goods_name != "상품명"){ //이미지형 상품등록
								remove_img("_"+ stepId); //배경 이미지 초기화
								var img_path = value.img_path; //이미지 경로
								$("#goods_imgpath_"+ stepId).val(img_path); //이미지 경로
								$("#div_preview_"+ stepId).css({"background":"url(" + img_path + ")"});
								if(img_path == "" || img_path == null) img_path = "<?=$sample_imgpath?>";
								//alert("img_path : "+ img_path);
								$("#pre_goods_imgpath_"+ stepId).html(img_path); //미리보기 이미지 경로
								$("#pre_div_preview_"+ stepId).css({"background":"url(" + img_path + ")"});
								if(rtn_sno == "1" || rtn_sno == "2"){ //1.할인&대표상품, 2.2단 이미지형
									if(goods_option2 != ""){ //옵션
										$("#goods_option2_"+ stepId).val(goods_option2); //옵션
										$("#pre_goods_option2_"+ stepId).show(); //미리보기 옵션
										$("#pre_goods_option2_"+ stepId).html(goods_option2); //미리보기 옵션
									}
									//console.log("rtn_sno : "+ rtn_sno +", goods_price : "+ goods_price +", goods_dcprice : "+ goods_dcprice);
									if(rtn_sno == "1" && goods_price != "" && goods_dcprice != ""){ //정상가가 있는 경우
										//goods_discount("1", stepId); //할인율 계산
										//console.log("rtn_sno : "+ rtn_sno +", chkBadge : 1");
										chkBadge(rtn_sno, stepId, "1"); //미리보기 할인율 표기안함 선택
									}else{
										//$("#goods_badge_"+ stepId).val("0"); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
										//console.log("rtn_sno : "+ rtn_sno +", chkBadge : 0");
										chkBadge(rtn_sno, stepId, "0"); //미리보기 할인율 표기안함 선택
										if(rtn_sno == "1") badge_choice('1', stepId, '0', ''); //뱃지 사용안함처리
									}
								}
							}
							no++;
						}
					});
                    hideLoading();
				}
			});
			//alert("OK");
			var agent = navigator.userAgent.toLowerCase(); //브라우저
			//alert("agent : "+ agent);
			if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){ //ie 일때
				//alert("ie 일때");
				$("#excelFile_"+ id).replaceWith( $("#excelFile_"+ id).clone(true) ); //파일 초기화
			}else{
				$("#excelFile_"+ id).val(""); //파일 초기화
			}
		} else {
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
		}
	}

    function conner_download(pid, bno) {

    	// var type = $('#date_type').val();
    	// var start_date = $('#startDate').val();
    	// var end_date = $('#endDate').val();
        if(isChange==true){
            showSnackbar("수정된 이력이 있습니다.<br/>저장 후 다운로드를 진행하여 주십시오.", 2000);
        }else{
            var form = document.createElement("form");
        	document.body.appendChild(form);
        	form.setAttribute("method", "post");
        	form.setAttribute("action", "/spop/screen/conner_download");

        	var scrfField = document.createElement("input");
        	scrfField.setAttribute("type", "hidden");
        	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        	form.appendChild(scrfField);

        	var kindField = document.createElement("input");
        	kindField.setAttribute("type", "hidden");
        	kindField.setAttribute("name", "psd_id");
        	kindField.setAttribute("value", pid);
        	form.appendChild(kindField);

        	var resultField = document.createElement("input");
        	resultField.setAttribute("type", "hidden");
        	resultField.setAttribute("name", "box_no");
        	resultField.setAttribute("value", bno);
        	form.appendChild(resultField);

        	form.submit();
        }

    }

	//상품코너 추가
	function goods_area_append(stepNo){
		goods_area_copy('goods_list_'+ stepNo, stepNo);
	}

	$(window).ready(function() {
		search_category2("<?=$searchCate2?>");
	});

	//소분류 조회
	function search_category2(category2){
		var category1 = $("#searchCate1").val(); //대분류
		$("#searchCate2").empty(); //소분류 초기화
		var option = $("<option value=''>- 소분류 -</option>");
		$("#searchCate2").append(option);
		//alert("category1 : "+ category1 +", category2 : "+ category2);
		//alert("category1 : "+ category1); return;
		//소분류 조회
		$.ajax({
			url: "/mng/design/search_category2",
			type: "POST",
			data: {"category1" : category1, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					//if(key == 0) alert("["+ (key+1) +"] value.id : "+ value.id +", value.description : "+ value.description);
					if(category2 != "" && category2 == value.id){
						option = $("<option value='"+ value.id +"' Selected>"+ value.description +"</option>");
					}else{
						option = $("<option value='"+ value.id +"'>"+ value.description +"</option>");
					}
					$("#searchCate2").append(option);
				});
			}
		});
	}

	<? if($mem_bigpos_yn == "Y"){ ?>
	//포스 상품조회 페이지
	var pagePosGoods = 1; //페이지
	var totalPosGoods = 0; //전체수

	//포스 상품조회 검색내용 엔터키
	$("#pos_sv").keydown(function(key) {
		if (key.keyCode == 13) {
			pos_goods_search(); //포스 상품조회
		}
	});

	//포스 상품조회 초기화
	function pos_goods_remove(){
		pagePosGoods = 1; //페이지
		totalPosGoods = 0; //전체수
		$("#pos_goods_list").html(""); //초기화
	}

	//포스 상품조회 검색시
	function pos_goods_search(){
		pos_goods_remove(); //포스 상품조회 초기화
		ajax_pos_goods(); //ajax 포스 상품조회
	}

	//포스 상품조회 스크롤시
	$("#pos_goods_list").scroll(function(){
		//alert("pos_goods_list"); return;
		var dh = $("#pos_goods_list")[0].scrollHeight;
		var dch = $("#pos_goods_list")[0].clientHeight;
		var dct = $("#pos_goods_list").scrollTop();
		//alert("스크롤 : " + dh + "=" + dch +  " + " + dct); return;
		if(dh == (dch+dct)) {
			var rowcnt = $(".img_select").length;
			//alert("totalPosGoods : " + totalPosGoods + " / rowcnt : " + rowcnt);
			if(rowcnt < totalPosGoods) {
				ajax_pos_goods(); //ajax 포스 상품조회
			}
		}
	});

	//포스 상품조회 모달 오픈
	var pos_goods_modal = document.getElementById("pos_goods_modal");
	function pos_goods_open(stepNo, id){
		$("#pos_stepNo").val(stepNo); //스텝번호
		$("#pos_id").val(id); //ID
		$("#pos_sc").val("name"); //검색구분 초기화
		$("#pos_sv").val(""); //검색내용 초기화
		pos_goods_search(); //포스 상품조회
		pos_goods_modal.style.display = "block"; //포스 상품조회 모달 오픈
		var span = document.getElementById("pos_goods_close");
		span.onclick = function() {
			pos_goods_modal.style.display = "none"; //포스 상품조회 모달 닫기
		}
		window.onclick = function(event) {
			if (event.target == pos_goods_modal) {
				pos_goods_modal.style.display = "none"; //포스 상품조회 모달 닫기
			}
		}
	}

	//ajax 포스 상품조회
	function ajax_pos_goods(){
		var pos_sc = $("#pos_sc").val(); //검색구분
		var pos_sv = $("#pos_sv").val(); //검색내용
		var pos_sort = $("#pos_sort").val(); //정렬
		//alert("pagePosGoods : "+ pagePosGoods);
		//alert("stepNo : "+ stepNo); return;
		$.ajax({
			url: "/spop/screen/ajax_pos_goods",
			type: "POST",
			data: {"perpage" : "15", "page" : pagePosGoods, "pos_sc" : pos_sc, "pos_sv" : pos_sv, "pos_sort" : pos_sort, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				pagePosGoods = json.page;
				totalPosGoods = json.total;
				//alert(json.html);
				$("#id_pos_count").html(totalPosGoods); //포스 상품 전체건수
				$("#pos_goods_list").append(json.html); //포스 상품 리스트
			}
		});
	}

	//포스 상품조회 적용하기
	function pos_goods_send(){
		var no = 0;
		var chk = 0;
		var stepNo = $("#pos_stepNo").val(); //스텝번호
		var id = $("#pos_id").val(); //ID
		var step = id; //스텝
		var step_cnt = parseInt($("#"+ id +"_cnt").val()); //박스별 상품수
		//alert("step : "+ step +", step_cnt : "+ step_cnt +", (step_cnt-1) : "+ (step_cnt-1));
		for(var i = (step_cnt-1); i >= 0; i--){ //상품명이 비어있는 것 조회
			var goods_name = $("#goods_name_"+ step +"_"+ i).val(); //상품명
			no = i;
			if(goods_name != ""){
				no = i+1;
				break;
			}
		}
		//alert("step : "+ step +", step_cnt : "+ step_cnt +", no : "+ no); return;
		$('input:checkbox[name="pos_goods_chk"]').each(function(){
			if(this.checked) {
				//alert("["+ no +"] this.value : "+ this.value);
				var id = this.value;
				var goods_name = $("#pos_goods_name"+ id).html(); //상품명
				var goods_option = $("#pos_goods_option"+ id).html(); //규격
				var goods_price = $("#pos_goods_price"+ id).html(); //정상가
				var goods_dcprice = $("#pos_goods_dcprice"+ id).html(); //할인가
				var goods_option2 = ""; //옵션
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				var goods_barcode = $("#pos_goods_barcode"+ id).html();	// 바코드(상품코드)
				var goods_imgpath = $("#pos_goods_imgpath"+ id).html(); //이미지 경로
				var goods_seq = no; //정렬순서
				var flag = "excel";
				if(no == 0) flag = "new";
				var stepId = step +"_"+ no;
				if($("#goods_name_"+ stepId).length > 0){
					//console.log("goods_name_step1_"+ no +" : 해당 객체 존재함");
				}else{
					//console.log("goods_name_step1_"+ no +" : 해당 객체 존재안함");
					goods_append(stepNo, step, flag); //상품정보 입력란 추가
				}
				$("#goods_name_"+ stepId).val(goods_name); //상품명
				$("#goods_option_"+ stepId).val(goods_option); //규격
				$("#goods_price_"+ stepId).val(goods_price); //정상가
				$("#goods_dcprice_"+ stepId).val(goods_dcprice); //할인가
				$("#goods_seq_"+ stepId).val(no); //정렬순서
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				$("#goods_barcode_" + stepId).val(goods_barcode) // 바코드(상품코드)
				if(goods_name == "") goods_name = "상품명"; //미리보기 상품명
				if(goods_option == "") goods_option = ""; //미리보기 규격
				if(goods_price == "") goods_price = ""; //미리보기 정상가
				if(goods_dcprice == "") goods_dcprice = "0원"; //미리보기 할인가
				$("#pre_goods_name_"+ stepId).html(goods_name); //미리보기 상품명
				$("#pre_goods_option_"+ stepId).html(goods_option); //미리보기 규격
				$("#pre_goods_price_"+ stepId).html(goods_price); //미리보기 정상가
				$("#pre_goods_dcprice_"+ stepId).html(goods_dcprice); //미리보기 할인가
				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
				$("#pre_goods_barcode_" + stepId).html(goods_barcode) // 바코드(상품코드)

				if(stepNo == "1") chgGoodsData(stepId, "excel", "N");
				if(stepNo != "3" && goods_name != "" && goods_name != "상품명"){ //이미지형 상품등록
					remove_img("_"+ stepId); //배경 이미지 초기화
					var img_path = goods_imgpath; //이미지 경로
					$("#goods_imgpath_"+ stepId).val(img_path); //이미지 경로
					$("#div_preview_"+ stepId).css({"background":"url(" + img_path + ")"});
					if(img_path == "" || img_path == null) img_path = "<?=$sample_imgpath?>";
					//alert("img_path : "+ img_path);
					$("#pre_goods_imgpath_"+ stepId).html(img_path); //미리보기 이미지 경로
					$("#pre_div_preview_"+ stepId).css({"background":"url(" + img_path + ")"});
					if(stepNo == "1" || stepNo == "2"){ //1.할인&대표상품, 2.2단 이미지형
						if(goods_option2 != ""){ //옵션
							$("#goods_option2_"+ stepId).val(goods_option2); //옵션
							$("#pre_goods_option2_"+ stepId).show(); //미리보기 옵션
							$("#pre_goods_option2_"+ stepId).html(goods_option2); //미리보기 옵션
						}
						if(stepNo == "1" && goods_price != ""){ //정상가가 있는 경우
							//goods_discount("1", stepId); //할인율 계산
							chkBadge(stepNo, stepId, "1"); //미리보기 할인율 표기안함 선택
						}else{
							//$("#goods_badge_"+ stepId).val("0"); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
							chkBadge(stepNo, stepId, "0"); //미리보기 할인율 표기안함 선택
						}
					}
				}
				no++;
				chk++;
				//alert("["+ no +"] name : "+ name);
			}
		});
		if(chk > 0){
			pos_goods_modal.style.display = "none"; //포스 상품조회 모달 닫기
		}else{
			alert("상품을 선택해 주세요.");
			return;
		}
	}
	<? } ?>

	//주문하기 사용여부 클릴시
	function order_chk(dis){
		div_view('psd_order_option', dis); //날짜 입력 영역
		//alert("dis : "+ dis);
		if(dis == "none"){ //사용안함의 경우
			$(".icon_add_cart").hide(); //장바구니 이미지 Class 제거
			$(".pop_list02").removeClass("cartplus_t"); //텍스트형 행사코너 장바구니 Class 영역 제거
			$(".pop_list02").addClass("cartplus"); //텍스트형 행사코너 장바구니 Class 영역 추가
			$('#hdn_psd_order_yn').val('N');
		}else{ //사용함의 경우
			$("button[name='yesstock']").show();//재고유무에 따라 버튼 선택적으로 보여주기
			//$(".icon_add_cart").show(); //장바구니 이미지 Class 추가
			$(".pop_list02").removeClass("cartplus"); //텍스트형 행사코너 장바구니 Class 영역 제거
			$(".pop_list02").addClass("cartplus_t"); //텍스트형 행사코너 장바구니 Class 영역 추가
			$('#hdn_psd_order_yn').val('Y');
		}
	}

    //폰트 크기 변경
    function font_chk(flag){
        if(flag=="N"){
            $("link[title='csstypes']").attr("href","/views/spop/screen/bootstrap/css/style_n.css?v=<?=date("ymdHis")?>");
        }else{
            $("link[title='csstypes']").attr("href","/views/spop/screen/bootstrap/css/style.css?v=<?=date("ymdHis")?>");
        }

    }

	//주문하기 시작일자 클리시
	$("#picker_order_sdt").datepicker({
		format: "yyyy.mm.dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});
	$("#psd_order_sdt").change(function(){
		var order_dt = $("#psd_order_sdt").val().replace(/[^0-9]/g,'');
		//alert("psd_order_sdt : "+ psd_order_sdt);
		var rtn_sdt = order_dt.substring(0, 4) +"."+ order_dt.substring(4, 6) +"."+ order_dt.substring(6, 8);
		$("#span_order_sdt").html(rtn_sdt);
	});

	//주문하기 시작일자 클리시
	$("#picker_order_edt").datepicker({
		format: "yyyy.mm.dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});
	$("#psd_order_edt").change(function(){
		var order_dt = $("#psd_order_edt").val().replace(/[^0-9]/g,'');
		//alert("psd_order_sdt : "+ psd_order_sdt);
		var rtn_sdt = order_dt.substring(0, 4) +"."+ order_dt.substring(4, 6) +"."+ order_dt.substring(6, 8);
		$("#span_order_edt").html(rtn_sdt);
	});
</script>
<?
	if($psd_id == ""){ //신규등록의 경우
		echo "<script>\n";
		if($psd_step1_yn != "N"){ //신규등록 && STEP1 사용함의 경우
			if($step1_org < $step1_std){ //할인&대표상품 등록 등록수가 기본 갯수보다 작은 경우
				for($jj = $step1_org; $jj < $step1_std; $jj++){
					echo "  goods_append('1', 'step1', 'base'); //할인&대표상품 등록 영역 추가\n";
				}
			}
		}
		if($screen_data->psd_step2_yn != "N"){ //STEP2 사용함의 경우
			if($step2_cnt < 1){ //STEP2. 등록건이 없는 경우
				//echo "  goods_area_copy('goods_list_step2', 'step2'); //STEP2. 이미지형 상품등록 영역 복사\n";
			}
		}
		if($screen_data->psd_step3_yn != "N"){ //STEP3 사용함의 경우
			if($step3_cnt < 1){ //STEP3. 등록건이 없는 경우
				//echo "  goods_area_copy('goods_list_step3', 'step3'); //STEP3. 텍스트형 상품등록 영역 복사\n";
			}
		}
		echo "</script>\n";
	}else{
		echo "<script>$('#dh_myBtn').attr('tabindex', -1).focus(); //미리보기 포커스</script>";
	}

	if($test_yn == "Y"){ //테스트의 경우
		echo "<script>\n";
		//echo "  goods_area_append('step2'); //STEP2. 이미지형 상품코너 추가\n";
		//echo "  goods_area_append('step3'); //STEP2. 텍스트형 상품코너 추가\n";
		//echo "  goods_area_append('step2'); //STEP2. 이미지형 상품코너 추가\n";
		//echo "  goods_area_append('step3'); //STEP2. 텍스트형 상품코너 추가\n";
		//echo "  $(window).scrollTop(0); //스크롤 TOP 이동\n";
		echo "</script>\n";
	}
?>
