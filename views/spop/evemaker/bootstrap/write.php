<?
    $reward_std = 4;

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
	$emd_id = ($org_id != "") ? $org_id : $evemaker_data->emd_id; //전단번호
	$emd_title = $evemaker_data->emd_title; //행사제목
	$emd_sub_info = $evemaker_data->emd_sub_info; //행사기간
	$emd_sub_info2 = $evemaker_data->emd_sub_info2; //템플릿번호
	// $emd_step1_yn = $evemaker_data->emd_step1_yn; //할인&대표상품 등록 사용여부
	// $emd_viewyn = ($evemaker_data->emd_viewyn != "") ? $evemaker_data->emd_viewyn : "Y"; //스마트전단 샘플 뷰 (Y/N)
	// $emd_ver_no = ($evemaker_data->emd_ver_no != "") ? $evemaker_data->emd_ver_no : 2; //버전번호
	// $template_self_bgcolor = "#d9d9d9"; //템플릿 직접입력 배경색
	// $mem_bigpos_yn = $this->member->item("mem_bigpos_yn"); //빅포스 사용여부
	// $tit_text_info_first_i = ""; // 스마트 전단 메뉴명 초기값(이미지)
	// $tit_text_info_first_t = ""; // 스마트 전단 메뉴명 초가값(텍스트)
	// $pst_tit_text = ""; //전단 텍스트 정보
	// $ex_tit_text_info = ""; // 기존 전단 수정시 텍스트정보 담을 그릇;
	// if($emd_ver_no == "1"){ //버전1 & 테스트의 경우
	// 	$emd_ver_no = 2;
	// }


	//echo "emd_id : ". $emd_id .", add : ". $add ."<br>";
	// $test_yn = "N"; //테스트여부
	// if($emd_id == "" and $add == "_test"){ //테스트의 경우
	// 	//$test_yn = "Y";
	// }
	// if($test_yn == "Y"){ //테스트의 경우
	// 	$emd_title = "Test ". date("y.m.d H:i:s"); //행사제목
	// 	$emd_date = "기간 ".date("n/j") ." ~ ". date("n/j", strtotime(date("Y-m-d") ." +7 day")); //행사기간
	// 	$emd_tem_id = "78"; //템플릿번호
	// 	//$emd_step1_yn = "N"; //할인&대표상품 등록 사용여부
	// 	$emd_viewyn = "N"; //스마트전단 샘플 뷰 (Y/N)
	// }

	$userAgent = $_SERVER['HTTP_USER_AGENT']; //user agent 확인
	// $ieYn = $this->funn->fnIeYn($userAgent); //익스플로러 여부
	$ieYn = "Y";
	//echo "userAgent : ". $userAgent ."<br>";
	//echo "ieYn : ". $ieYn ."<br>";

	//회원정보 > 스마트전단 주문하기 사용여부
	$mem_stmall_yn = $this->member->item("mem_stmall_yn"); //스마트전단 주문하기 사용여부
	//$mem_stmall_yn = "N"; //스마트전단 주문하기 사용여부
	//echo "mem_stmall_yn : ". $mem_stmall_yn ."<br>";
	if($evemaker_data->emd_id != ""){ //수정의 경우
		// $emd_order_yn = $evemaker_data->emd_order_yn; //주문하기 사용여부
		$emd_open_sdt = ($evemaker_data->emd_open_sdt != "") ? date("Y.m.d", strtotime($evemaker_data->emd_open_sdt)) : ""; //주문하기 시작일자
		$emd_open_edt = ($evemaker_data->emd_open_edt != "") ? date("Y.m.d", strtotime($evemaker_data->emd_open_edt)) : ""; //주문하기 종료일자
        $top_emd_open_edt = ($evemaker_data->emd_open_edt != "") ? date("m.d", strtotime($evemaker_data->emd_open_edt)) : ""; //주문하기 종료일자
        $bottom_emd_open_sdt = ($evemaker_data->emd_open_sdt != "") ? date("Y년 m월 d일", strtotime($evemaker_data->emd_open_sdt) ) : ""; //주문하기 시작일자
		$bottom_emd_open_edt = ($evemaker_data->emd_open_edt != "") ? date("Y년 m월 d일", strtotime($evemaker_data->emd_open_edt) ) : ""; //주문하기 종료일자

        // $emd_order_sh = (!empty((string)$evemaker_data->emd_order_st)) ? (string)substr($evemaker_data->emd_order_st, 0, 2) : "00"; //주문하기 시작시간
        // $emd_order_eh = (!empty((string)$evemaker_data->emd_order_et)) ? (string)substr($evemaker_data->emd_order_et, 0, 2) : "00"; //주문하기 종료시간
        // $emd_order_sm = (!empty((string)$evemaker_data->emd_order_st)) ? (string)substr($evemaker_data->emd_order_st, 2, 2) : "00"; //주문하기 시작분
        // $emd_order_em = (!empty((string)$evemaker_data->emd_order_et)) ? (string)substr($evemaker_data->emd_order_et, 2, 2) : "00"; //주문하기 종료분
	}else{ //등록의 경우
		// $emd_order_yn = $mem_stmall_yn; //주문하기 사용여부
        // $emd_order_sh = "00"; //주문하기 시작시간
        // $emd_order_eh = "00"; //주문하기 종료시간
        // $emd_order_sm = "00"; //주문하기 시작시간
        // $emd_order_em = "00"; //주문하기 종료시간
	}
	//$emd_order_yn = "N"; //주문하기 사용여부
	//echo "emd_order_yn : ". $emd_order_yn ."<br>";




	//장바구니 버튼 설정
	// $button_add_cart = "";
    //
	// if($mem_stmall_yn == "Y"){
	// 	if($emd_order_yn == "Y"){
	// 		if($button_soldout=="N"){
	// 			$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
	// 		}else{
	// 			$button_add_cart = "<button class='icon_add_cart'>장바구니</button>";
	// 		}
	// 	}else{
	// 		$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
	// 	}
	// }
	//button_add('Y');

	// function button_add($ynstr){
	// 	if($mem_stmall_yn == "Y"){
	// 		if($emd_order_yn == "Y"){
	// 			if($ynstr=="N"){
	// 				$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
	// 			}else{
	// 				$button_add_cart = "<button class='icon_add_cart'>장바구니</button>";
	// 			}
	// 		}else{
	// 			$button_add_cart = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
	// 		}
	// 	}
	// }
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<link rel="stylesheet" type="text/css" href="/views/spop/evemaker/bootstrap/css/common.css?v=<?=date("ymdHis")?>"/>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/eveview.css?v=<?=date("ymdHis")?>"/>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style_<?=(!empty($evemaker_data->emd_tem_type))? $evemaker_data->emd_tem_type : "A" ?><?=($evemaker_data->emd_font_LN=="N")? "_n" : "" ?>.css?v=<?=date("ymdHis")?>" title="csstypes" />
<!-- <script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script> -->
<script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
<script id="rendered-js" src="/assets/js/canvas-toBlob.js"></script>
<script src="/js/jquery-barcode.js"></script>
<script src="/js/jquery-barcode.min.js"></script>
<script type="text/javascript">
	var emd_id = '<?=$emd_id?>';
	var emd_cnt = '<?=$emd_cnt?>';
    var tem_type = '<?=(!empty($evemaker_data->emd_tem_type))? $evemaker_data->emd_tem_type : "A" ?>';
	// var temp_emd_cnt = '<?=$temp_emd_cnt?>';
    // var emd_v = '<?=$emd_v?>';
	var isChange = false;


    <? if(!empty($bottom_emd_open_sdt)){ ?>
        var sdate = "<?=$emd_open_sdt?>";
        var edate = "<?=$top_emd_open_edt?>";
        var hansdate = "<?=$bottom_emd_open_sdt?>";
        var hanedate = "<?=$bottom_emd_open_edt?>";

        // console.log("<?=$evemaker_data->emd_open_edt?> / <?=$bottom_emd_open_edt?>");
    <? }else{ ?>
        var sdate = "<?=date('Y')?>.00.00";
        var edate = "00.00";
        var hansdate = "<?=date('Y')?>년 00월 00일";
        var hanedate = "<?=date('Y')?>년 00월 00일";
    <? } ?>


    var urlurl = [];

    var nameArr = [];
    var sub_nameArr = [];
    var idArr = [];
    var typeArr = [];
  // var isSample = false;
	// var prebox = '';
	// var isisall = false;
    // if(emd_id!=''){
        // jsLoading();
    // }
	//console.log(emd_id+" - "+emd_cnt+" - "+temp_emd_cnt+" - "+temp_emd_cnt+" - "+latest_date+" - "+temp_cnt);
	$(document).ready(function(){
        // hideLoading();
		// if(emd_id!=''){
        //     if(emd_cnt!=''){
        //         if(emd_cnt==0){
    	// 			if(temp_emd_cnt>0&&emd_v=='A'){
    	// 				$.ajax({
    	// 					url: "/spop/evemaker/smart_temp_save",
    	// 					type: "POST",
    	// 					async: false,
    	// 					data: {"emd_id":emd_id, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
    	// 					success: function (json) {
    	// 						alert(json.msg);
    	// 						if(json.code==0){
        //                             setTimeout(function() {
        //     							location.reload();
        //     						}, 500); //1초 지연
    	// 						}
    	// 						//history.go(0);
    	// 						//$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
    	// 					}
    	// 				});
    	// 			}
    	// 		}
        //     }
		// }

		$("input,select,textarea").change(function(){
			isChange = true;
		});

        // $('.btn_excel_down').click(function(){
        //     isSample = true;
        // });

        // var conner1st = "<?=$evemaker_box[0]->psg_box_no?>";
        // console.log(conner1st);
        // modal_sort_list(conner1st);

		// var toDoWhenClosing = function() {
		// 	$.ajax({ type: "POST",
		// 					 url: "/spop/evemaker/outofpage",
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
            // if(isSample==false){
                e.returnValue = '이 페이지를 벗어나면 작성된 내용은 저장되지 않습니다.';

    	           return "이 페이지를 벗어나면 작성된 내용은 저장되지 않습니다.";
            // }else{
            //     isSample=false;
            // }

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

<div id="mArticle" class="write_eveview">
<div class="wrap_leaflets">
<!-- 타이틀 영역 -->
<div class="s_tit">
 	스마트이벤트 만들기
</div>
<!-- 타이틀 영역 END -->
  <div class="write_leaflets">
    <div class="wl_lbox">
      <? //제목&템플릿선택&기간 [S] ------------------------------------------------------------------------------------------------------------------------------------ ?>
	  <span class="btn_imgtem">
        <!-- <button id="dh_myBtn" onClick="templet_open(1);"><span class="material-icons">photo_filter</span> 이미지 템플릿선택</button> -->
        <input type="hidden" id="data_id" value="<?=$emd_id?>"><?//전단번호?>
        <!-- <input type="hidden" id="tem_id" value="<?=$emd_tem_id?>"><?//템플릿번호?> -->
        <input type="hidden" id="chk_id" value=""><?//선택된ID?>
      </span>
      <!-- <div class="tit_box">
        <div class="tit">기본 정보</div>
      </div> -->
      <div class="wl_tit">
        <input type="text" id="wl_tit" onKeyup="onKeyupData(this)" placeholder="이벤트명을 입력해주세요." class="int" maxlength="35" value="<?=$emd_title?>">
      </div>
      <div class="wl_sub_info">
        <input type="text" id="wl_sub_info" onKeyup="onKeyupData(this)" placeholder="이벤트설명을 입력해주세요." class="int" maxlength="35" value="<?=$emd_sub_info?>">
      </div>

      <div class="tit_box choice_type">
        <div class="tit">템플릿유형</div>
        <div class="use_choice">
          <label class="uc_container"> 타입A
            <input type="radio" name="emd_tem_type" id="emd_tem_type_A" value="A" onClick="tem_chk('A');"<? if($evemaker_data->emd_tem_type == "A"||$evemaker_data->emd_tem_type == ""){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
          <label class="uc_container"> 타입B
            <input type="radio" name="emd_tem_type" id="emd_tem_type_B" value="B" onClick="tem_chk('B');"<? if($evemaker_data->emd_tem_type == "B"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
          <!-- <label class="uc_container"> 타입C
            <input type="radio" name="emd_tem_type" id="emd_tem_type_C" value="C" onClick="tem_chk('C');"<? if($evemaker_data->emd_tem_type == "C"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label> -->
        </div>
      </div>

	  <? //제목&템플릿선택&기간 [E] ------------------------------------------------------------------------------------------------------------------------------------ ?>
      <div class="tit_box">
        <div class="tit">이벤트 기간</div>
        <!-- <div class="use_choice">
          <label class="uc_container"> 사용함
            <input type="radio" name="emd_order_yn" id="emd_order_yn_Y" value="Y" onClick="order_chk('');"<? if($emd_order_yn == "Y"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
          <label class="uc_container"> 사용안함
            <input type="radio" name="emd_order_yn" id="emd_order_yn_N" value="N" onClick="order_chk('none');"<? if($emd_order_yn != "Y"){ ?> checked<? } ?>>
            <span class="checkmark"></span>
          </label>
        </div> -->
      </div>
			<input type="hidden" id="hdn_emd_open_yn" value="<?=$emd_open_yn?>"/>
      <div id="emd_open_option" class="order_option">
        <div class="input-group date fl" id="picker_open_sdt" style="width: 132px;cursor:pointer;">
          <input type="text" class="form-control" id="emd_open_sdt" style="cursor:pointer;" value="<?=$emd_open_sdt?>" onKeyPress="event.preventDefault();">
          <span class="input-group-addon">
              <span class="material-icons" style="font-size:18px;">date_range</span>
          </span>

        </div>
        <span class="under"> - </span>
        <div class="input-group date fl" id="picker_open_edt" style="width: 132px;cursor:pointer;">
          <input type="text" class="form-control" id="emd_open_edt" style="cursor:pointer;" value="<?=$emd_open_edt?>">
          <span class="input-group-addon">
              <span class="material-icons" style="font-size:18px;">date_range</span>
          </span>

        </div>
        <!-- <select class="mg_l20" id="emd_order_sh" onchange="change_time2(0)">
      <?
          for($i=0;$i<24;$i++){
      ?>
              <option value="<?=sprintf('%02d',$i)?>" <?=(string)$emd_order_sh == sprintf('%02d',$i) ? "selected" : "" ?>><?=sprintf('%02d',$i)?>시</option>
      <?
          }
      ?>
        </select>
        <select id="emd_order_sm" onchange="change_time2(0)">
      <?
          for($i=0;$i<6;$i++){
      ?>
              <option value="<?=sprintf('%02d',$i * 10)?>" <?=(string)$emd_order_sm == sprintf('%02d',$i * 10) ? "selected" : "" ?>><?=sprintf('%02d',$i * 10)?>분</option>
      <?
          }
      ?>
        </select>
        ~
        <select id="emd_order_eh" onchange="change_time2(1)">
        <?
            for($i=0;$i<24;$i++){
        ?>
                <option value="<?=sprintf('%02d',$i)?>" <?=(string)$emd_order_eh == sprintf('%02d',$i) ? "selected" : "" ?>><?=sprintf('%02d',$i)?>시</option>
        <?
            }
        ?>
        </select>
        <select id="emd_order_em" onchange="change_time2(1)">
        <?
            for($i=0;$i<6;$i++){
        ?>
                <option value="<?=sprintf('%02d',$i * 10)?>" <?=(string)$emd_order_em == sprintf('%02d',$i * 10) ? "selected" : "" ?>><?=sprintf('%02d',$i * 10)?>분</option>
        <?
            }
        ?>
        </select> -->
        <!-- <div class='chk_alltime'>
            <label class="checkbox_container">
                <input type="checkbox" name="emd_order_alltime" id="emd_order_alltime" <?=$evemaker_data->emd_order_alltime == 'Y' ? 'checked' : ''?>>
                <span class="checkmark"></span>
            </label>
        </div> -->
        <!-- <span class="chk_alltime_txt">종일</span> -->
        <span class="text">
            <span>
                <font id="span_open_sdt"><?=($emd_open_sdt != "") ? $emd_open_sdt : date("Y").".00.00"?></font> ~ <font id="span_open_edt"><?=($emd_open_edt != "") ? $emd_open_edt : date("Y").".00.00"?></font>
                <!-- <font id="span_order_shm"><?=$emd_order_sh?>:<?=$emd_order_sm?></font> ~ <font id="span_order_ehm"><?=$emd_order_eh?>:<?=$emd_order_em?></font> -->
            </span> (설정된 기간동안 이벤트가 활성화됩니다.)
        </span>
      </div>
      <div class="tit_box">
        <div class="tit">상품 목록</div>
      </div>
	  <div id="box_area"><?//박스 영역?>
        <?
		  	//등록된 행사코너별 리스트
			$no = 0; //순번
			$ii = 0; //코너내 순번
		  	$seq = 0; //코너번호

			$step_name = ""; //스텝




        ?>
        <div id="goods_box-0" class="gb_box"><?//이미지형?>


          <div class="box_wrap" id="goods_list_step0">

            <div class="wl_img_goods" id="step0">

              <!--상품추가시 looping-->
              <section id="area_goods_step0">
                <?
            if(!empty($evemaker_box)){
                $eve_cnt = count($evemaker_box);
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > evemaker_box : ".$eve_cnt);
                foreach($evemaker_box as $r) {
                    $no++;

			    ?>
                <? if(!empty($r->emg_imgpath)&&$r->emg_imgpath != ""){
                    $file_ext = substr(strrchr($r->emg_imgpath, "."), 1);
                    $fileNameWithoutExt = substr($r->emg_imgpath, 0, strrpos($r->emg_imgpath, "."));
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
                <dl id="step0_<?=$ii?>" class="dl_step0">
                  <dt>
                    <div class="templet_img_in" onclick="showImg('<?=$ii?>')">
                      <div id="div_preview_<?=$ii?>" class="templet_img_in2" style="background-image : url('<?=$imgnamepext?>');">
                        <img id="img_preview_<?=$ii?>" style="display:none;width:100%">
                      </div>
                    </div>
                  </dt>
                  <dd>
                      <input type="hidden" id="goods_id_<?=$ii?>" value="<?=$r->emg_id?>">
                      <input type="hidden" id="goods_seq_<?=$ii?>" value="<?=$ii?>" style="width:40px;">
                      <input type="hidden" id="goods_imgpath_<?=$ii?>" value="<?=$r->emg_imgpath?>">

                      <!-- <div class="use_choice"> -->
                        <label class="uc_container"> 상품
                          <input type="radio" name="emd_type_<?=$ii?>" id="emd_type_<?=$ii?>_N" value="N" onClick="type_chk('<?=$ii?>','N');"<? if($r->emg_type == "N"||$r->emg_type == ""){ ?> checked<? } ?>>
                          <span class="checkmark"></span>
                        </label>
                        <label class="uc_container"> 꽝
                          <input type="radio" name="emd_type_<?=$ii?>" id="emd_type_<?=$ii?>_K" value="K" onClick="type_chk('<?=$ii?>','K');"<? if($r->emg_type == "K"){ ?> checked<? } ?>>
                          <span class="checkmark"></span>
                        </label>
                        <label class="uc_container"> 포인트
                          <input type="radio" name="emd_type_<?=$ii?>" id="emd_type_<?=$ii?>_P" value="P" onClick="type_chk('<?=$ii?>','P');"<? if($r->emg_type == "P"){ ?> checked<? } ?>>
                          <span class="checkmark"></span>
                        </label>
                        <!-- <label class="uc_container"> 예치금
                          <input type="radio" name="emd_type_<?=$ii?>" id="emd_type_<?=$ii?>_D" value="D" onClick="type_chk('<?=$ii?>','D');"<? if($r->emg_type == "D"){ ?> checked<? } ?>>
                          <span class="checkmark"></span>
                        </label> -->
                      <!-- </div> -->

                    <ul>

                      <li id="div_name_<?=$ii?>" <?=($r->emg_type=="K")? "style='display:none;'" : ""?>><span>상품명</span><input type="text" id="goods_name_<?=$ii?>" value="<?=$r->emg_name?>" onKeyup="chgNameData('<?=$ii?>', this)" placeholder="상품명" class="int140"></li>
                      <li id="div_info_<?=$ii?>" <?=($r->emg_type=="K")? "style='display:none;'" : ""?>><span>설&nbsp;&nbsp;&nbsp;명</span><input type="text" id="goods_info_<?=$ii?>" value="<?=$r->emg_sub_name?>" onKeyup="chgSubNameData('<?=$ii?>', this)" placeholder="설명" class="int140"></li>
                      <li id="div_cnt_<?=$ii?>" <?=($r->emg_type=="K")? "style='display:none;'" : ""?>><span>수&nbsp;&nbsp;&nbsp;량</span><input type="text" id="goods_cnt_<?=$ii?>" value="<?=$r->emg_cnt?>" onKeyup="chgCntData('<?=$ii?>', this)" placeholder="수량" class="int140"></li>
                      <li id="div_deposit_<?=$ii?>" style="display:none;"><span>예치금</span><input type="text" id="goods_deposit_<?=$ii?>" value="<?=$r->emg_deposit?>" placeholder="행사가" class="int140"></li>
                      <li id="div_barcode_<?=$ii?>" <?=($r->emg_type!="N")? "style='display:none;'" : ""?>>
                          <span class="tit">바코드</span>
                          <div class="barcode">
                              <input type="hidden" id="apply_barcode_<?=$ii?>" value="<?=$ii?>">
                              <select id="select_barcode_length_<?=$ii?>" onchange="changeBarcodeSelect(this, <?=$ii?>)">
                                  <option value="13" <?=($r->emg_barcode_type=="13")? "selected" : ""?>>13자리</option>
                                  <option value="8" <?=($r->emg_barcode_type=="8")? "selected" : ""?>>8자리</option>
                              </select>
                              <input type="text" id="barcode_txt_<?=$ii?>" value="<?=$r->emg_barcode?>" onkeyup="" maxlength=13 style="width: calc(100% - 236px) !important;">
                              <button type="button" class="btn_bar_ok" onclick="showBarcode(<?=$ii?>)"> 미리보기</button>
                          </div>
                      </li>
                    </ul>

					<!-- <div class="noselect_sel">
						<input type="hidden" id="goods_noselect_<?=$ii?>" name="hdn_goods_noselect" value="<?=$r->psg_no_select?>">
						<label class="checkbox_container">
							<input type="checkbox" name="noselect_chk" id="noselect_chk_<?=$ii?>" onClick="chgGoodsData('<?=$ii?>', '', this)" value="Y" <?=($r->psg_no_select == "Y") ? " checked" : ""?> >
							<span class="checkmark"></span>
						</label>
					</div>
					<span class="noselect_sel_text">선택X</span>

					<div class="vip_box">
						<label class="checkbox_container">
							<input type="hidden" id="goods_vip_<?=$ii?>" value="<?=$r->emg_vip?>">
							<input type="checkbox" name="vip_chk" id="vip_chk_<?=$ii?>" onClick="chgGoodsData('<?=$ii?>', '', this)" value="V" <?=($r->emg_vip == "V") ? " checked" : ""?>>
							<span class="checkmark"></span>
						</label>
			        </div>
					<span class="vip_text">VIP</span> -->
                    <!-- <label class="checkbox_container pop_input">
                      <input type="checkbox" name="pop_chk" id="pop_chk<?=$ii?>" onClick="popChk(this);" value="step0_<?=$ii?>">
                      <span class="checkmark"></span>
                    </label> -->
                    <!-- <button id="myBtn" class="pop_modal_btn" onClick="popPrint('step0_<?=$ii?>')">POP 인쇄</button> -->
                  </dd>
                  <ddd style="position: absolute;font-size: 20px;top: -3px;right: 11px;color: #d1d1d1;">
                      당첨  <span style="font-size:40px;" id="pick_rate_<?=$ii?>">15</span>%
                  </ddd>
                  <span class="move_btn_group">
                      <a class="move_last_left" href="javascript:goods_move('first', '<?=$ii?>');" title="처음으로이동"></a>
                      <a class="move_left" href="javascript:goods_move('left', '<?=$ii?>');" title="이전으로이동"></a>
                      <a class="move_right" href="javascript:goods_move('right', '<?=$ii?>');" title="다음으로이동"></a>
                      <a class="move_last_right" href="javascript:goods_move('last', '<?=$ii?>');" title="마지막으로이동"></a>
                      <a class="move_del" href="javascript:area_del('<?=$ii?>');" title="삭제"></a>
                  </span>
                </dl>
			    <?
						$ii++; //코너별 등록수
                        log_message("ERROR", $_SERVER['REQUEST_URI']." > ii : ".$ii ." > evemaker_box : ".$eve_cnt);
						if($ii >= $eve_cnt ){
			    ?>
              </section>
              <!-- <input type="hidden" id="step0_no" value="<?=$seq?>" style="width:40px;"> -->
              <input type="hidden" id="step0_cnt" value="<?=$ii?>" style="width:40px;">
              <input type="hidden" id="step0_mx" value="<?=$ii?>" style="width:40px;">
              <!--//상품추가시 looping-->
              <div class="btn_al_cen">
                <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('new');">상품추가</button>
                <!-- <button class="btn_good_add" style="margin-left:10px;background:#333;" type="button" id="save_goods" onClick="goods_conner_save('<?=$emd_id?>', 'goods_box-0');">상품코너저장</button> -->
              </div>
              <?
      						} //if($ii >= $r->rownum){
      						// $chk_step_no = $r->psg_step_no;

      				} //foreach($evemaker_box as $r) {
      			}else{ ?>
                    </section>
                    <input type="hidden" id="step0_cnt" value="<?=$ii?>" style="width:40px;">
                    <input type="hidden" id="step0_mx" value="<?=$ii?>" style="width:40px;">
                    <!--//상품추가시 looping-->
                    <div class="btn_al_cen">
                      <button class="btn_good_add" type="button" id="add_goods" onClick="goods_append('new');">상품추가</button>
                      <!-- <button class="btn_good_add" style="margin-left:10px;background:#333;" type="button" id="save_goods" onClick="goods_conner_save('<?=$emd_id?>', 'goods_box-0');">상품코너저장</button> -->
                    </div>
            <?    } //if(!empty($evemaker_box)){
              ?>
            </div><!--//wl_img_goods-->
          </div><!--//goods_list_step0-->
        </div><!--//goods_box-0-->

      </div><!--//box_area-->
      <!-- <div class="tit_box">
        <div class="tit">하단 내용</div>
      </div>
      <div class="wl_date">
        <input type="text" id="wl_info2" onKeyup="onKeyupData(this)" placeholder="하단 이벤트설명을 입력해주세요." class="int" maxlength="35" value="<?=$emd_sub_info2?>">
      </div> -->
      <input type="hidden" id="goods_box_cnt" value="0">

      <div id="footer_pd" style="padding:30px;"></div>
    </div><!--//wl_lbox-->
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// 스마트전단 미리보기 [S]
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
    <div class="wl_rbox">
        <div class="eveview_wrap roulette_wrap">
            <div class="eve_container roulette_<?=(!empty($evemaker_data->emd_tem_type))? $evemaker_data->emd_tem_type : "A" ?>">
                <div class="eve_title">
                    <h3><?=$this->member->item("mem_username")?></h3>
                    <h2 id="pre_wl_tit"><?=(!empty($evemaker_data->emd_title))? $evemaker_data->emd_title : "새이벤트" ?></h2>
                    <div id="pre_wl_sub_info" class="eve_notice"><?=(!empty($evemaker_data->emd_sub_info))? $evemaker_data->emd_sub_info : "이벤트설명" ?></div>
                    <div id="pre_date" class="eve_date"><?=(!empty($emd_open_sdt))? $emd_open_sdt." ~ ".$top_emd_open_edt :  date("Y").".00.00 ~ 00.00"//date("Y.m.d")." ~ ".date("m.d", strtotime(date("Y-m-d") ." +7 day")) ?></div>
                </div>
                <div class="eve_main">
                    <div class="smart_back">
                        <div id="reward_pop" class="reward_pop" style="display:none;"><img src="/images/eveview/roulette_reward_pop_01.png" alt="wow"></div>
                        <dl id="reward_div" class="reward" style="display:none;">
                            <dt id="rw_title" class="halfLine">상품명</dt>
                            <dd id="rw_detail" class="text_stroke">상세설명</dd>
                            <dd id="reward_img"><img src="/uploads/maker_goods/7dc93b42de6c553598a067957cfdec45.jpg" alt="당첨상품"></dd>
                            <dd><input type="button" value="자세히보러가기" class="btn_detail"></dd>
                        </dl>
                        <div id="start_btn" class="cover" ><img src="/images/eveview/roulette_btn_go_01.png" alt="시작"></div>
                        <div id="stop_btn" class="cover" style="display:none;"><img src="/images/eveview/roulette_btn_stop_01.png" alt="멈춤"></div>
                        <!-- <div class="pin"></div> -->
                        <div class="w_pin"><img src="/images/eveview/roulette_pin_01.png" alt="pin"></div>
                        <div class="wheel"></div>

                    </div>
                </div>
            </div>

            <div class="eve_notice2">
                <dl class="notice2_point">
                    <dt>응모 기간</dt><dd id="bottomdate"><?=(!empty($bottom_emd_open_sdt))? $bottom_emd_open_sdt." ~ ".$bottom_emd_open_edt :  date("Y")."년 00월 00일 ~  ".date("Y")."년 00월 00일"//date("Y년 m월 d일")." ~ ".date("Y년 m월 d일", strtotime(date("Y-m-d") ." +7 day")) ?></dd>
                    <dt>응모 조건</dt><dd>1인 1회 응모 가능</dd>
                    <dt>대 상</dt><dd><?=$this->member->item("mem_username")?> 고객</dd>
                </dl>
                <hr class="notice2_hr" >
                <div class="notice2_detail">
                    <h5>[이벤트 유의사항]</h5>
                    <ul>
                        <li>본 이벤트는 <span><?=$this->member->item("mem_username")?> 고객 대상</span>으로 응모 가능합니다.</li>
                        <li>이벤트는 <span>1인 1회</span> (차수 별로 1회씩) 참여 가능합니다.</li>
                        <li>이벤트 경품 발송을 위해 개인정보가 수집됩니다.</li>
                        <li>부정한 방법(개인 정보 도용, 불법 프로그램 등)으로 이벤트에 참여한 것이 발견된 경우 당첨이 취소될 수 있습니다.</li>
                        <li>경품 종류 별 당첨 확률은 상이합니다.</li>
                        <li>응모 취소 및 재응모는 불가합니다.</li>
                        <li>경품은 고객분이 입력한 핸드폰 번호로 발송 처리되며, 번호 오입력 시 재발송 되지 않습니다.</li>
                        <li>본 이벤트는 선착순으로 진행되며, 조기에 종료되거나 경품이 변경될 수 있습니다.</li>
                    </ul>

                    <h5>[경품 안내]</h5>
                    <ul>
                        <li>일부 경품은 당첨 안내를 위해 개별 문자 및 유선 안내 예정입니다.</li>
                        <li>경품 쿠폰은 유효기간 내에서만 이용이 가능합니다.</li>
                        <li>실물 경품 중 5만원 초과 경품은 제세공과금 신고가 필수로 진행되며, 제세공과금 22%는 당첨자 고객 부담입니다.</li>
                        <li>제세공과금 신고를 위한 개인정보를 확인하며 이에 동의하지 않거나 제세공과금 미입금 시 당첨은 취소됩니다.</li>
                    </ul>
                </div>
            </div>
        </div>

      </div><!--//wl_r_preview-->
    </div><!--//wl_rbox-->
<?
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	// 스마트전단 미리보기 [E]
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
    <div class="write_leaflets_btn">
      <button type="button" class="pop_btn_save" onClick="saved('');"><span class="material-icons">save</span> 저장하기</button>
      <!-- <button type="button" class="pop_btn_send" onClick="saved('talk_adv');"><span class="material-icons">chat_bubble_outline</span> 알림톡발송</button> -->
      <button type="button" class="pop_btn_cancel" onclick="list();"><span class="material-icons">highlight_off</span> 목록으로</button>
	  <a onclick="$('#wl_tit').attr('tabindex', -1).focus();" class="btn_top" title="위로">
		  <span class="material-symbols-outlined">expand_less</span></a>
	  <!-- <a onclick="$('#smart_btn_group').attr('tabindex', -1).focus();" class="btn_add_write" title="추가">
		  <span class="material-symbols-outlined">add</span></a> -->
	  <a onclick="$('#footer_pd').attr('tabindex', -1).focus();" class="btn_bot" title="아래">
		  <span class="material-symbols-outlined">expand_more</span></a>
    </div>
  </div><!--//write_leaflets-->
  <div id="dh_myModal" class="dh_modal">
  	<div class="modal-content2_1">
  		<span id="dh_close" class="dh_close">&times;</span>
          <div class="barcode_box">
              <div id="barcode_div"></div>
          </div>
  	</div>
  </div>
</div><!--//wrap_leaflets-->

<!-- 상품 이미지 선택 Modal -->


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
			<!-- <button onclick="showLibrary('goods');" class="goods_pos" style="margin-left:10px;cursor:pointer;">최근상품</button> -->
			<!-- <button onclick="showLibrary('keep');" class="goods_my" style="margin-left:10px;cursor:pointer;">이미지보관함</button> -->
			<ul>
				<li>1. 내사진 : <span>내 PC에 저장된 이미지</span>를 등록합니다.</li>
				<li>2. 이미지선택 : <span>지니 라이브러리에 있는 이미지</span>를 선택합니다.</li>
				<!-- <li>3. 최근상품 : <span>최근 등록한 상품정보</span>를 불러옵니다.</li>
				<li>4. 이미지보관함 : <span>이미지보관함에 등록한 상품이미지</span>를 선택합니다.</li> -->

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
		<ul id="library_append_list" class="library_append_list"<? if($ieYn == "Y"){ ?> style="height:440px;"<? } ?>><?//라이브러리 리스트 영역?>
		</ul>
		<? if($ieYn == "Y"){ ?>
		<div id="library_page" class="page_cen" style="margin-top:10px;"></div><?//라이브러리 페이징 영역?>
		<? } ?>
	</div>
</div>

<!-- </div> -->
<script>
    // function change_time(flag){
    //     if (!flag){
    //         // $("#emd_order_st_txt").html($("#emd_order_sdt").val() + " " + $("#emd_order_sh").val() + ":" + $("#emd_order_sm").val());
    //         $("#span_order_sdt").html($("#emd_order_sdt").val());
    //     } else {
    //         // $("#emd_order_et_txt").html($("#emd_order_edt").val() + " " + $("#emd_order_eh").val() + ":" + $("#emd_order_em").val());
    //         $("#span_order_edt").html($("#emd_order_edt").val());
    //     }
    // }
    // function change_time2(flag){
    //     if (!flag){
    //         // $("#emd_order_st_txt").html($("#emd_order_sdt").val() + " " + $("#emd_order_sh").val() + ":" + $("#emd_order_sm").val());
    //         $("#span_order_shm").html($("#emd_order_sh").val() + ":" + $("#emd_order_sm").val());
    //     } else {
    //         // $("#emd_order_et_txt").html($("#emd_order_edt").val() + " " + $("#emd_order_eh").val() + ":" + $("#emd_order_em").val());
    //         $("#span_order_ehm").html($("#emd_order_eh").val() + ":" + $("#emd_order_em").val());
    //     }
    // }


	var request = new XMLHttpRequest();
	var set_goods_imgpath_id = "";
    var myid = "";
	var add = "<?=$add?>";
	if(add != "") add = "&add="+ add;
	var ieYn = "<?=$ieYn?>"; //익스플로러 여부


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


    var modal = document.getElementById("dh_myModal");
	function showBarcode(id){
		//alert("rowid : "+ rowid); return;
		// $("#goods_step_id").val(rowid); //선택된 STEP ID
        var bartxt = $("#barcode_txt_" + id).val();
        if(bartxt != ""){
            $("#barcode_div").barcode(String($("#barcode_txt_" + id).val()), "ean" + ($("#select_barcode_length_" + id).val() == "13" ? "13" : "8"),{barWidth:2, barHeight:60, fontSize:20});
    		var span = document.getElementById("dh_close");
    		modal.style.display = "block";
    		span.onclick = function() {
    			modal.style.display = "none";
    		}
    		window.onclick = function(event) {
    			if (event.target == modal) {
    				modal.style.display = "none";
    			}
    		}
        }else{
            showSnackbar("바코드숫자를 먼저 입력하세요", 1500);
        }

	}

    function changeBarcodeSelect(obj, id){
        var selectval = $(obj).val();
        $("#barcode_txt_" + id).attr("maxlength", selectval);
    }

    function type_chk(id, type){
        var goods_name = $("#goods_name_"+id).val();
        var goods_info = $("#goods_info_"+id).val();
        var goods_imgpath = $("#goods_imgpath_"+id).val();


        if(type == "N"){
            $("#div_name_"+id).show();
            $("#div_info_"+id).show();
            $("#div_cnt_"+id).show();
            $("#div_deposit_"+id).hide();
            $("#eve_name_"+id).text(goods_name);
            $("#eve_subname_"+id).text(goods_info);
            if(goods_imgpath==""){
                goods_imgpath = "/images/eveview/img_gift_01.png";

            }
            $("#div_preview_"+id).css("background-image", "url('"+goods_imgpath+"')");
            $("#eve_img_"+id).show();
            $("#eve_img_"+id).attr("src", goods_imgpath);
            $("#div_barcode_"+id).show();
        }else if(type == "K"){
            $("#div_name_"+id).hide();
            $("#div_info_"+id).hide();
            $("#div_cnt_"+id).hide();
            $("#div_deposit_"+id).hide();
            $("#eve_name_"+id).text("꽝");
            $("#eve_subname_"+id).text("다음기회에");
            $("#eve_img_"+id).show();
            goods_imgpath = "/images/eveview/img_bomb_01.png";
            $("#div_preview_"+id).css("background-image", "url('"+goods_imgpath+"')");
            $("#eve_img_"+id).attr("src", goods_imgpath);
            $("#div_barcode_"+id).hide();
        }else if(type=="P"){
            $("#div_name_"+id).show();
            $("#div_info_"+id).show();
            $("#div_cnt_"+id).show();
            $("#div_deposit_"+id).hide();
            $("#eve_name_"+id).text(goods_name);
            $("#eve_subname_"+id).text(goods_info);
            goods_imgpath = "/images/eveview/img_point_01.png";
            $("#div_preview_"+id).css("background-image", "url('"+goods_imgpath+"')");
            $("#eve_img_"+id).show();
            $("#eve_img_"+id).attr("src", goods_imgpath);
            $("#div_barcode_"+id).hide();
        }else if(type=="D"){
            $("#div_name_"+id).show();
            $("#div_info_"+id).show();
            $("#div_cnt_"+id).show();
            $("#div_deposit_"+id).show();
            $("#eve_name_"+id).text(goods_name);
            $("#eve_subname_"+id).text(goods_info);
            goods_imgpath = "/images/eveview/img_point_01.png";
            $("#div_preview_"+id).css("background-image", "url('"+goods_imgpath+"')");
            $("#eve_img_"+id).show();
            $("#eve_img_"+id).attr("src", goods_imgpath);
            $("#div_barcode_"+id).hide();
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
			url: "/spop/evemaker/search_library",
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
			url: "/spop/evemaker/search_library",
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
		// if(option_use == 'N'){
		// 	// alert(option_use);
		// 	searchstr =	trim(goods_name);
		// }else{
		// 	searchstr = trim(goods_name +" "+ goods_option);
		// }
        searchstr =	trim(goods_name);
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
            console.log(imgpath+ " /// " +fileurl);
			$("#div_preview"+ id).css({"background":"url(" + fileurl + ")"}); //상품 이미지 배경 URL
			$("#eve_img"+ id).attr("src", fileurl); //미리보기 상품 이미지 배경 URL
            $("#eve_img"+ id).show();
			$("#goods_imgpath"+ id).val(imgpath); //사진 경로
			// $("#pre_goods_imgpath"+ id).html(fileurl); //미리보기 사진 경로
			// $("#pre"+ id).attr("tabindex", -1).focus(); //미리보기 포커스
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
            var thumb_imgpath = getExt(imgpath);
			$("#div_preview"+ id).css({"background":"url(" + thumb_imgpath + ")"}); //상품 이미지 배경 URL
			$("#pre_div_preview"+ id).css({"background":"url(" + thumb_imgpath + ")"}); //미리보기 상품 이미지 배경 URL
			$("#goods_imgpath"+ id).val(imgpath); //사진 경로
            if($("#goods_name"+ id).val()==""){
                $("#goods_name"+ id).val(name); //상품명
            }

			$("#goods_price"+ id).val(price); //정상가
			// $("#goods_dcprice"+ id).val(dcprice); //할인가
			$("#pre_goods_imgpath"+ id).html(thumb_imgpath); //미리보기 사진 경로
            if($("#goods_name"+ id).val()==""){
    			$("#pre_goods_name"+ id).html(name); //미리보기 상품명
            }
			$("#pre_goods_price"+ id).html(price); //미리보기 정상가
			// $("#pre_goods_dcprice"+ id).html(dcprice); //미리보기 할인가
			$("#pre"+ id).attr("tabindex", -1).focus(); //미리보기 포커스
			modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal3.style.display = "none"; //라이브러리 모달창 닫기
		}
	}



	//데이타 변경시
	function onKeyupData(obj) {
		var name = $(obj).attr('id');
		var value = $("#"+ name).val();
        if(value==""){
            if(name=="wl_tit"){
                value = "새이벤트";
            }else if(name=="wl_sub_info"){
                value = "이벤트설명";
            }
        }
		//alert("name : "+ name +"\n"+"vlaue : "+ vlaue);
		$("#pre_"+ name).html(value); //미리보기 영역 표시값 변경
	}

    //코너 클릭시 포커스 이동
	function chkBoxData(stepid, id, step){

            if(step =="step9"){
                $("#pre_goods_box-"+ id).attr("tabindex", -1).focus(); //미리보기 포커스
                $("#goods_list_"+ stepid).attr("tabindex", -1).focus(); //미리보기 포커스
            }else{
                $("#pre_"+ stepid+"_0").attr("tabindex", -1).focus(); //미리보기 포커스
                $("#"+ stepid+"_0").attr("tabindex", -1).focus();
            }
            $(".move_list_typeA").children("li").removeClass("selection");
            $("#modal_sort_li_goods_box-"+id).addClass("selection");
			// $("#pre_"+ stepid+"_0").attr("tabindex", -1).focus(); //미리보기 포커스 pre_goods_box-2

            // $("#"+ stepid+"_0").attr("tabindex", -1).focus(); //미리보기 포커스
            // $("#"+ stepid+"_0").attr("tabindex", -1).focus(); //미리보기 포커스
			// $("#goods_box-"+ id).focus(); //해당 항목 포커스 goods_list_
			// $("#chk_id").val(id); //선택된ID

	}

	//상품 데이타 클릭시
	function chkGoodsData(id, fl, obj){
		// var preId = "pre_"+ id;
		// var chk_id = $("#chk_id").val(); //선택된ID
		// //alert("chk_id : "+ chk_id +"\n"+ "preId : "+ preId);
		// if(id != chk_id && id != "N"){
		// 	$("#"+ preId).attr("tabindex", -1).focus(); //미리보기 포커스
		// 	$(obj).focus(); //해당 항목 포커스
		// 	$("#chk_id").val(id); //선택된ID
		// }else{
		// }
	}

    function chgNameData(id,obj) {
        $("#eve_name_"+id).text($(obj).val());

    }

    function chgSubNameData(id,obj) {
        $("#eve_subname_"+id).text($(obj).val());
    }

    function chgCntData(id,obj) {
        // var chgcnt = Number($(obj).val());
        // var dl_type = $(":input:radio[name=emd_type_"+id+"]:checked").val();
        var k_rate = 0;

        if(ktype_cnt>0){
            k_rate = 25;
            if(wtype_cnt=="4"){
                k_rate = 30;
            }
        }whole_cnt = 0;

        $(".dl_step0").each(function(index, item){ //상품코너별 조회

            var id = $(item).prop('id');
            var splitArr = id.split("_");
            var dl_id = splitArr[1]; //스텝 ID
            // console.log(dl_id);
            var dl_type = $(":input:radio[name=emd_type_"+dl_id+"]:checked").val();


            var dl_cnt = $("#goods_cnt_"+dl_id).val();



            if(dl_type == "K"){

            }else{
                whole_cnt = Number(whole_cnt) + Number(dl_cnt);
            }

        });

        $(".dl_step0").each(function(index, item){ //상품코너별 조회

            var id = $(item).prop('id');
            var splitArr = id.split("_");
            var dl_id = splitArr[1]; //스텝 ID
            // console.log(dl_id);
            var dl_type = $(":input:radio[name=emd_type_"+dl_id+"]:checked").val();


            var dl_cnt = $("#goods_cnt_"+dl_id).val();

            var ratenum = 0;
            if(dl_type == "K"){
                ratenum = k_rate;
            }else if(dl_cnt>0){
                // ratenum=Math.round((dl_cnt / whole_cnt)*100);
                ratenum=(dl_cnt / whole_cnt)*100;
                var rateremain = dl_cnt % whole_cnt;
                if(rateremain>0){
                    ratenum=ratenum.toFixed(1);
                }
            }

            $("#pick_rate_"+dl_id).text(ratenum);

        });
    }

	//상품 데이타 변경시
	function chgGoodsData(id, fl, obj) {
		// // var stepId = id.substring(0, 5); //스텝 ID
        // var stepId = "step"+$("#goods_step_"+id).val();
		// //alert("stepId : "+ stepId +"\n"+ "id : "+ id);
		// var preId = "pre_"+ id;
		// //alert("preId : "+ preId);
        //
		// //var chk_id = $("#chk_id").val(); //선택된ID
		// //if(id != chk_id){
		// //	$("#"+ preId).attr("tabindex", -1).focus(); //미리보기 포커스
		// //	if(obj != "N"){
		// //		$(obj).focus(); //해당 항목 포커스
		// //	}
		// //	$("#chk_id").val(id); //선택된ID
		// //}
		// var hdn_emd_order_yn = $('#hdn_emd_order_yn').val();
        //
		// //품절처리 반영 2021-06-16 수정
		// if($("input:checkbox[id='soldout_chk_"+id+"']").is(":checked") == true){
		// 	var chk_value = $("#soldout_chk_"+id).val(); //선택된ID
		// 	//var order_ch = value_check("order_ch");
        //
    	//     if(chk_value){
    	//         $('input[name=goods_soldout_'+id+']').attr('value',chk_value);
		// 		if(id.includes("step3-")){
		// 			if($('#pre_'+id).hasClass("soldout")==false){
		// 					$('#pre_'+id).addClass("soldout");
		// 			}
		// 		}else{
		// 			// if($('#pre_div_preview_'+id).hasClass("soldout")==false){
		// 			// 		$('#pre_div_preview_'+id).addClass("soldout");
		// 			// }
        //             $('#pre_'+id).children(".soldout").show();
		// 		}
		// 		$('#pre_'+id).find('.icon_add_cart').attr("name","nostock");//개별 장바구니버튼 name 속성 변//
		// 		$('#pre_'+id).find('.icon_add_cart').hide();//개별 장바구니버튼 숨김
		// 		$("input:hidden[id='goods_soldout_"+id+"']").val('N');
    	//     }
		// }else{
		// 	 $('input[name=goods_soldout_'+id+']').attr('value','Y');
		// 	 if(id.includes("step3-")){
		// 		 if($('#pre_'+id).hasClass("soldout")==true){
		// 				 $('#pre_'+id).removeClass("soldout");
		// 		 }
		// 	 }else{
        //
        //             $('#pre_'+id).children(".soldout").hide();
		// 	 }
		// 	 $('#pre_'+id).find('.icon_add_cart').attr("name","yesstock");//개별 장바구니버튼 name 속성 변경
        //
		// 	 if(hdn_emd_order_yn=='Y'){
		// 		 $('#pre_'+id).find('.icon_add_cart').show(); //개별장바구니버튼 보이기
		// 	 }
        //
		// 		$("input:hidden[id='goods_soldout_"+id+"']").val('Y');
		// }
        //
        // if($("input:checkbox[id='noorder_chk_"+id+"']").is(":checked") == true){
		// 	var chk_value = $("#noorder_chk_"+id).val(); //선택된ID
		// 	//var order_ch = value_check("order_ch");
        //
    	//     if(chk_value){
    	//         $('input[name=goods_noorder_'+id+']').attr('value',chk_value);
        //
		// 		$("input:hidden[id='goods_noorder_"+id+"']").val('Y');
    	//     }
		// }else{
		// 	 $('input[name=goods_noorder_'+id+']').attr('value','N');
        //
        //
		// 	 if(hdn_emd_order_yn=='Y'){
		// 		 $('#pre_'+id).find('.icon_add_cart').show(); //개별장바구니버튼 보이기
		// 	 }
        //
		// 		$("input:hidden[id='goods_noorder_"+id+"']").val('N');
		// }
        //
        //
		// var goods_name = $("#goods_name_"+ id).val(); //상품명
		// var goods_option = $("#goods_option_"+ id).val(); //규격
		// var goods_price = $("#goods_price_"+ id).val(); //정상가
        // var goods_evprice = $("#goods_evprice_"+ id).val(); //정상가
		// var goods_dcprice = $("#goods_dcprice_"+ id).val(); //할인가
		// var goods_option2 = $("#goods_option2_"+ id).val(); //옵션
        // var goods_option3 = $("#goods_option3_"+ id).val(); //옵션
		// if(goods_name != "") goods_name = goods_name.trim(); //상품명
		// if(goods_option != "") goods_option = goods_option.trim(); //규격
        //
        // // $("#goods_dcprice_"+ id).val(str_price(goods_dcprice));
		// //alert("goods_option2 : "+ goods_option2);
        // if(stepId != "step5"){
        //     if(goods_option2 != ""){
    	// 		goods_option2 = goods_option2.trim(); //옵션
    	// 		$("#pre_goods_option2_"+ id).show(); //옵션 미리보기 열기
    	// 	}else{
    	// 		$("#pre_goods_option2_"+ id).hide(); //옵션 미리보기 닫기
    	// 	}
        //
        //     if(goods_option3 != ""){
    	// 		goods_option3 = goods_option3.trim(); //옵션
    	// 		$("#pre_goods_option3_"+ id).show(); //옵션 미리보기 열기
    	// 	}else{
    	// 		$("#pre_goods_option3_"+ id).hide(); //옵션 미리보기 닫기
    	// 	}
        //
        // }
        //
        // if(goods_price != ""){
        //     $("#pre_goods_price_"+ id).show(); //옵션 미리보기 열기
        // }else{
        //     $("#pre_goods_price_"+ id).hide(); //옵션 미리보기 닫기
        // }
        //
        // if(goods_evprice != ""){
        //     $("#pre_goods_evprice_"+ id).show(); //옵션 미리보기 열기
        // }else{
        //     $("#pre_goods_evprice_"+ id).hide(); //옵션 미리보기 닫기
        // }
        //
		// //if(goods_price != "") goods_price = goods_price.trim(); //정상가
		// //if(goods_dcprice != "") goods_dcprice = goods_dcprice.trim(); //할인가
		// if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
		// 	goods_price = goods_price.trim();
		// 	var rtn_price = goods_price;
		// 	var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
		// 	if(temp_price !=""){
		// 		rtn_price = rtn_price.replace(/,/g,'');
		// 		var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
		// 		rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
		// 		if(fl == "excel") $("#goods_price_"+ id).val(rtn_price); //엑셀 업로드의 경우
		// 	}
        //
        //     // if($("input:checkbox[id='noorder_chk_"+id+"']").is(":checked") == false){
        //          if(temp_price !=""){
        //              goods_price = str_price(goods_price)+"원";
        //          }else{
        //              goods_price = goods_price;
        //          }
        //      // }else{
        //      //    goods_price = rtn_price;
        //      // }
        //
        //
		// 	// goods_price = rtn_price;
		// }
        // if(goods_evprice != ""){ //정상가 (숫자만 콤마 처리)
		// 	goods_evprice = goods_evprice.trim();
		// 	var rtn_price = goods_evprice;
		// 	var temp_price = goods_evprice.replace(/[^0-9]/g,''); //숫자만 추출
		// 	if(temp_price !=""){
		// 		rtn_price = rtn_price.replace(/,/g,'');
		// 		var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
		// 		rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
		// 		if(fl == "excel") $("#goods_evprice_"+ id).val(rtn_price); //엑셀 업로드의 경우
		// 	}
        //     // if(rtn_price != ""){
		// 	// 	var psg_evprice_last = rtn_price.substr(rtn_price.length-1, 1); //할인가 마지막 단어
		// 	// 	if(psg_evprice_last == "원"){ //할인가 마지막 단어가 원인 경우
		// 	// 		rtn_price = rtn_price.substr(0 , rtn_price.length-1) + "<span class='sm_small'>원</span>"; //할인가 원에 class 주기
		// 	// 	}else{
        //     //         if(temp_price !=""){
        //     //             rtn_price = rtn_price + "<span class='sm_small'>원</span>";
        //     //         }
        //     //     }
		// 	// }
		// 	// goods_evprice = rtn_price;
        //     if(temp_price !=""){
        //         goods_evprice = str_price(goods_evprice) + "<span class='sm_small'>원</span>";
        //     }else{
        //         goods_evprice = goods_evprice;
        //     }
		// }
		// if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
		// 	goods_dcprice = goods_dcprice.trim();
		// 	var rtn_price = goods_dcprice;
		// 	var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
		// 	if(temp_price !=""){
		// 		rtn_price = rtn_price.replace(/,/g,'');
		// 		var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
		// 		rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
		// 		if(fl == "excel") $("#goods_dcprice_"+ id).val(rtn_price); //엑셀 업로드의 경우
		// 	}
        //     if(stepId == "step1"){
        //         if(Number(goods_dcprice.length)>5){
        //             // if(stepId == "step1"){
        //                 var sizesize = 50 - ((goods_dcprice.length - 5) * 2);
        //                 if(sizesize>40){
        //                     $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
        //                 }else{
        //                     $("#pre_goods_dcprice_"+id).css("font-size", "40px");
        //                 }
        //
        //             // }else if(stepId == "step2"){
        //             //
        //             // }
        //         }else{
        //             // if(stepId == "step1"){
        //             if(temp_price == ""){
        //                 $("#pre_goods_dcprice_"+id).css("font-size", "40px");
        //             }else{
        //                 $("#pre_goods_dcprice_"+id).css("font-size", "50px");
        //             }
        //
        //             // }else if(stepId == "step2"){
        //
        //             // }
        //         }
        //
        //
        //     }
        //
        //     if(stepId == "step2"){
        //         if(Number(goods_dcprice.length)>5){
        //                 var sizesize = 42 - ((goods_dcprice.length - 5) * 2);
        //                 if(sizesize>30){
        //                     $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
        //                 }else{
        //                     $("#pre_goods_dcprice_"+id).css("font-size", "30px");
        //                 }
        //         }else{
        //             $("#pre_goods_dcprice_"+id).css("font-size", "42px");
        //         }
        //     }
        //
        //     if(stepId == "step4"){
        //         if(Number(goods_dcprice.length)>5){
        //                 var sizesize = 32 - ((goods_dcprice.length - 5) * 2.2);
        //                 if(sizesize>22){
        //                     $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
        //                 }else{
        //                     $("#pre_goods_dcprice_"+id).css("font-size", "20px");
        //                 }
        //         }else{
        //             $("#pre_goods_dcprice_"+id).css("font-size", "32px");
        //         }
        //     }
        //     if(stepId == "step5"){
        //         if(Number(goods_dcprice.length)>5){
        //                 var sizesize = 26 - ((goods_dcprice.length - 5) * 2.2);
        //                 if(sizesize>17){
        //                     $("#pre_goods_dcprice_"+id).css("font-size", sizesize+"px");
        //                 }else{
        //                     $("#pre_goods_dcprice_"+id).css("font-size", "17px");
        //                 }
        //         }else{
        //             $("#pre_goods_dcprice_"+id).css("font-size", "26px");
        //         }
        //     }
		// 	// if(rtn_price != ""){
		// 	// 	var psg_dcprice_last = rtn_price.substr(rtn_price.length-1, 1); //할인가 마지막 단어
		// 	// 	if(psg_dcprice_last == "원"){ //할인가 마지막 단어가 원인 경우
		// 	// 		rtn_price = rtn_price.substr(0 , rtn_price.length-1) + "<span class='sm_small'>원</span>"; //할인가 원에 class 주기
		// 	// 	}else{
        //     //         if(temp_price !=""){
        //     //             rtn_price = rtn_price + "<span class='sm_small'>원</span>";
        //     //         }
        //     //     }
		// 	// }
		// 	// goods_dcprice = rtn_price;
        //     if($("input:checkbox[id='noorder_chk_"+id+"']").is(":checked") == false){
        //         if(temp_price !=""){
        //             goods_dcprice = str_price(goods_dcprice) + "<span class='sm_small'>원</span>";
        //         }else{
        //             goods_dcprice = goods_dcprice;
        //         }
        //      }else{
        //         goods_dcprice = rtn_price;
        //      }
        //
		// }
		// $("#pre_goods_name_"+ id).html(goods_name); //상품명 미리보기 영역 표시값 변경
		// $("#pre_goods_option_"+ id).html(goods_option); //규격 미리보기 영역 표시값 변경
		// $("#pre_goods_price_"+ id).html(goods_price); //정상가 미리보기 영역 표시값 변경
        // $("#pre_goods_evprice_"+ id).html(goods_evprice); //정상가 미리보기 영역 표시값 변경
		// $("#pre_goods_dcprice_"+ id).html(goods_dcprice); //할인가 미리보기 영역 표시값 변경
		// $("#pre_goods_option2_"+ id).html(goods_option2); //옵션 미리보기 영역 표시값 변경
        // $("#pre_goods_option3_"+ id).html(goods_option3); //옵션 미리보기 영역 표시값 변경
		// //if(goods_option2 != "") alert("goods_option2 : "+ goods_option2); return;
		// //if(goods_option2 != "") alert("pre_goods_option2 : "+ $("#pre_goods_option2_"+ id).html()); return;
		// //alert("stepId : "+ stepId); return;
		// // //alert("option2 : pre_goods_option2_"+ id);
        // // var test_text = "생가득 통새우볶음12";
        // // console.log("길이 : "+test_text.length);
        //
		// if(stepId == "step1" || stepId == "step2"){ //step1.할인&대표상품, step2.2단 이미지형
		// 	var goods_badge = $("#goods_badge_"+ id).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
        //     if(stepId == "step1"){
        //         if((Number(goods_name.length)+Number(goods_option.length))>12){
        //             $("#pre_goods_name_"+id).parent('.name').css("font-size", "18px");
        //         }else{
        //             $("#pre_goods_name_"+id).parent('.name').css("font-size", "22px");
        //         }
        //     }
        //
        //
		// 	//alert("stepId : "+ stepId +", goods_badge : "+ goods_badge); return;
		// 	if(goods_badge == "1"){
		// 		goods_discount("1", id); //할인율 계산
		// 	}
		// }
	}

    //문자 + 금액 처리
	function str_price(data){
		// console.log("data : " + data + " gb : " + gb + " no : " + no );
		if(data != ""){
			if(isNaN(data)){ //문자가 포함되어 있는
				data = data.trim();
				var last_data = data.charAt(data.length-1);
				// console.log("gb : "+ gb +", no : "+ no +", last_data : "+ last_data);
				// console.log(data);
				//마지막 문자 "원" 제거
				if(last_data == "원") data = data.slice(0,-1);
				data = data.trim();
				//특수문자(,)처리
				data = data.replaceAll(",","");
				// console.log("gb : "+ gb +", no : "+ no +", 최종 data : "+ data);
				if(isNaN(data)){ //문자가 포함되어 있는 경우
					data = data.trim();
					// console.log("data : *"+ data +"*");
					// console.log("gb : "+ gb +", no : "+ no +", data : *"+ data +"*");
					var array_data = data.split(" "); //뛰어쓰기 배열처리
					if(array_data.length > 1){
						if(array_data.length == 2){
							data = "<span class='sm_small'>"+array_data[0]+"</span>"+comma(array_data[1]);
						}else{
							str = '';
							for(var j = 0; j < array_data.length - 1; j++){
								str += array_data[j] + " ";
							}

						     data = "<span class='sm_small'>"+str.trim()+"</span>" + comma(array_data[array_data.length - 1]);


						}
					}else{
						//문자인 경우

						data = array_data[0];

						//숫자인 경우
						// $('#goods_'+ gb +'B'+ no).html(comma(data));
						// $('#goods_'+ gb +'C'+ no).show();
					}
				}else{ //숫자인 경우
                    data = comma(data);
				}
			}else{ //숫자인 경우
				data = comma(data);
			}

		}
		return data;
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


	//이미지 경로 세팅
	function readURL(input, divid, id){
		//alert("readURL(input, divid) > input.value : "+ input.value +", divid : "+ divid +", id : "+ id); return;
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#"+ divid).css({"background":"url(" + e.target.result + ")"});

                // $("#eve_img_"+id).attr("src", e.target.result);
				//$("#"+ divid).css({"background-size":"contain"});
				//$("#"+ divid).css({"background-repeat":"repeat-x"});
				// $("#pre_"+ divid).css({"background":"url(" + e.target.result + ")"});
				//$("#pre_"+ divid).css({"background-size":"contain"});
				//$("#pre_"+ divid).css({"background-repeat":"repeat-x"});
				//$("#pre_img_file"+ id).val(input.value);
				// $("#pre_"+ divid).attr("tabindex", -1).focus(); //미리보기 포커스
			}
			reader.readAsDataURL(input.files[0]);
			set_goods_imgpath_id = "goods_imgpath"+ id;
            myid = id;
			//alert("set_goods_imgpath_id : "+ set_goods_imgpath_id);

			//이미지 추가
			request = new XMLHttpRequest();
			var formData = new FormData();
			formData.append("imgfile", input.files[0]); //이미지
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			request.onreadystatechange = imgCallback;
			request.open("POST", "/spop/evemaker/imgfile_save");
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
                // var id = set_goods_imgpath_id.substring(id.length-1, id.length);
                $("#eve_img"+myid).prop("src", obj.imgpath);
                // console.log(myid + obj.imgpath);
				// $("#pre_"+ set_goods_imgpath_id).html(obj.imgpath);
			} else { //오류
			}
		}
	}

	//저장하기
	// function saved(flag){
	// 	var code = "1";
	// 	var no = 0;
	// 	var data_id = $("#data_id").val(); //전단번호
	// 	var tem_id = $("#tem_id").val(); //템플릿번호
	// 	var emd_title = $("#wl_tit").val().trim(); //행사제목
	// 	var emd_date = $("#wl_date").val().trim(); //행사기간
	// 	//alert("data_id : "+ data_id +"\n"+"tem_id : "+ tem_id +"\n"+"emd_title : "+ emd_title +"\n"+"emd_date : "+ emd_date); return;
	// 	if(emd_title == "" && flag != "temp"){
	// 		alert("행사제목을 입력하세요.");
	// 		$("#wl_tit").focus();
	// 		return;
	// 	}
	// 	if(tem_id == "" && flag != "temp"){
	// 		alert("이미지 템플릿을 선택하세요.");
	// 		templet_open(1); //이미지 템플릿선택 모달창 열기
	// 		return;
	// 	}
	// 	if(emd_date == "" && flag != "temp"){
	// 		alert("행사기간을 입력하세요.");
	// 		$("#wl_date").focus();
	// 		return;
	// 	}
	// 	var emd_order_yn = $(":input:radio[name=emd_order_yn]:checked").val(); //주문하기 사용여부
    //     var emd_font_LN = $(":input:radio[name=emd_font_LN]:checked").val(); //주문하기 사용여부
    //     var emd_tem_type = $(":input:radio[name=emd_tem_type]:checked").val();
	// 	var emd_order_sdt = $("#emd_order_sdt").val(); //주문하기 시작일자
	// 	var emd_order_edt = $("#emd_order_edt").val(); //주문하기 종료일자
    //     var emd_order_sh = $("#emd_order_sh").val(); //주문하기 시작일자
	// 	var emd_order_eh = $("#emd_order_eh").val(); //주문하기 종료일자
    //     var emd_order_sm = $("#emd_order_sm").val(); //주문하기 시작일자
	// 	var emd_order_em = $("#emd_order_em").val(); //주문하기 종료일자
	// 	if(emd_order_yn == "Y" && flag != "temp"){
	// 		if(emd_order_sdt == ""){
	// 			alert("스마트전단 주문하기 시작 일자를 입력하세요.");
	// 			$("#emd_order_sdt").focus();
	// 			return;
	// 		}
	// 		emd_order_sdt = emd_order_sdt.replace(/[^0-9]/g,'');
	// 		if(emd_order_sdt.length != 8){
	// 			alert("스마트전단 주문하기 시작 일자가 올바른 형식이 아닙니다.");
	// 			$("#emd_order_sdt").focus();
	// 			return;
	// 		}
	// 		//emd_order_sdt = emd_order_sdt.substring(0, 4) +"-"+ emd_order_sdt.substring(4, 6) +"-"+ emd_order_sdt.substring(6, 8);
	// 		//alert("emd_order_sdt : "+ emd_order_sdt); return;
	// 		if(emd_order_edt == ""){
	// 			alert("스마트전단 주문하기 종료 일자를 입력하세요.");
	// 			$("#emd_order_edt").focus();
	// 			return;
	// 		}
	// 		emd_order_edt = emd_order_edt.replace(/[^0-9]/g,'');
	// 		if(emd_order_edt.length != 8){
	// 			alert("스마트전단 주문하기 종료 일자가 올바른 형식이 아닙니다.");
	// 			$("#emd_order_edt").focus();
	// 			return;
	// 		}
	// 		if(emd_order_sdt > emd_order_edt){
	// 			alert("스마트전단 주문하기 종료 일자가를 확인해 주세요.");
	// 			$("#emd_order_edt").focus();
	// 			return;
	// 		}
    //         if ((emd_order_sh + emd_order_sm) == (emd_order_eh + emd_order_em) && !$('#emd_order_alltime').is(':checked')){
    //             alert("스마트전단 주문하기 시간이 동일합니다.");
	// 			$("#emd_order_sh").focus();
	// 			return;
    //         }
    //
    //         if (Number(emd_order_sh + emd_order_sm) > Number(emd_order_eh + emd_order_em)){
    //             alert("스마트전단 주문하기 시작시간이 종료시간 보다 더 늦습니다.");
	// 			$("#emd_order_sh").focus();
	// 			return;
    //         }
	// 	}
    //
	// 	//STEP1 ---------------------------------------------------------------------------------------------------
	// 	var step1_yn = "N";
	// 	var step1_yn_Y = $("#step1_yn_Y").is(":checked"); //할인&대표상품 등록 사용여부
	// 	if(step1_yn_Y){ //STEP1 사용함
	// 		no = 0;
	// 		var step1_cnt = $("#step1_cnt").val(); //할인&대표상품 등록 등록 상품수
	// 		// alert("step1_cnt : "+ step1_cnt); return;
	// 		if(step1_cnt > 0) step1_yn = "Y";
	// 		// for(var i=0; i < step1_cnt; i++){
    //         $('#area_goods_step1').children('dl').each(function(index, item){ //상품코너별 조회
    //             var gid = $(item).prop('id');
	// 			// var goods_id = $("#goods_id_step1_"+ i).val(); //상품번호
	// 			// var goods_step = $("#goods_step_step1_"+ i).val(); //스텝
	// 			// var goods_imgpath = $("#goods_imgpath_step1_"+ i).val(); //이미지경로
	// 			// var goods_name = $("#goods_name_step1_"+ i).val().trim(); //상품명
	// 			// var goods_option = $("#goods_option_step1_"+ i).val().trim(); //규격
	// 			// var goods_price = $("#goods_price_step1_"+ i).val().trim(); //정상가
	// 			// var goods_dcprice = $("#goods_dcprice_step1_"+ i).val().trim(); //할인가
	// 			// var goods_badge = $("#goods_badge_step1_"+ i).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
	// 			// // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
	// 			// var goods_barcode = $("#goods_barcode_step1_"+ i).val().trim(); // 바코드
    //
    //             var goods_id = $("#goods_id_"+ gid).val(); //상품번호
    //             var goods_step = $("#goods_step_"+ gid).val(); //스텝
    //             var goods_imgpath = $("#goods_imgpath_"+ gid).val(); //이미지경로
    //             var goods_name = $("#goods_name_"+ gid).val().trim(); //상품명
    //             var goods_option = $("#goods_option_"+ gid).val().trim(); //규격
    //             var goods_price = $("#goods_price_"+ gid).val().trim(); //정상가
    //             var goods_evprice = $("#goods_evprice_"+ gid).val().trim(); //정상가
    //             var goods_dcprice = $("#goods_dcprice_"+ gid).val().trim(); //할인가
    //             // var goods_seq = $("#goods_seq_"+ gid).val(); //정렬순서
    //             var goods_badge = $("#goods_badge_"+ gid).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
    //             var goods_option2 = $("#goods_option2_"+ gid).val().trim(); //옵션
    //             var goods_option3 = $("#goods_option3_"+ gid).val().trim(); //옵션
    //             // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    //             var goods_barcode = $("#goods_barcode_"+ gid).val().trim(); // 바코드
    //             // var goods_soldout = $("#goods_soldout_"+ gid).val(); //품절여부
    //
    //
	// 			//alert("goods_badge : "+ goods_badge); return;
	// 			if($("#goods_name_"+ gid).length > 0){ //해당 라인이 존재 할 경우
	// 				no++;
	// 				<? //if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
	// 				//if(goods_imgpath == "" && flag != "temp"){
	// 				//	alert("[할인&대표상품 등록 "+ no +"번째]\n상품 이미지를 추가하세요.");
	// 				//	//$("#img_file_step1_"+ i).click();
	// 				//	showImg("step1_"+ i);
	// 				//	return;
	// 				//}
	// 				<? //} ?>
	// 				if(goods_name == "" && flag != "temp"){
	// 					alert("[할인&대표상품 등록 "+ no +"번째]\n상품명을 입력하세요.");
	// 					$("#goods_name_"+ gid).focus();
	// 					return;
	// 				}
	// 				if(goods_dcprice == "" && flag != "temp"){
	// 					alert("[할인&대표상품 등록 "+ no +"번째]\n할인가를 입력하세요.");
	// 					$("#goods_dcprice_"+ gid).focus();
	// 					return;
	// 				}
	// 			}
	// 		});
	// 	}
	// 	//alert("[할인&대표상품 등록] OK"); return;
	// 	var step2_yn = "N"; //이미지형 상품 사용여부
	// 	var step3_yn = "N"; //텍스트형 상품 사용여부
	// 	var emd_type = "S"; //타입(S.전단, C.쿠폰, T.임시저장)
	// 	var org_id = "";
	// 	if(flag == "temp"){
	// 		data_id = "";
	// 		org_id = data_id;
	// 		emd_type = "T"; //타입(S.전단, C.쿠폰, T.임시저장)
	// 	}
	// 	var str_add = "<?=add?>";
	// 	if(str_add == "_test") emd_viewyn = "N";
	// 	var goods_box_cnt = Number($("#goods_box_cnt").val()); //상품 박스 갯수
	// 	//alert("goods_box_cnt : "+ goods_box_cnt); return;
	// 	//for(var j=1; j <= goods_box_cnt; j++){ //박스 갯수만큼
	// 	var err = 0;
	// 	var j = 0;
	// 	$("input[name=goods_box_id]").each(function(index, item){ //상품코너별 조회
	// 		no = 0;
	// 		var box_id = $(item).val(); //코너별 ID
	// 		// alert("box_id["+ index +"] : "+ box_id);
	// 		var box_no = $("#"+ box_id +"_box_no").val();
	// 		var step = $("#"+ box_id +"_step").val();
	// 		var stepId = $("#"+ box_id +"_step_id").val();
	// 		var stepNo = $("#"+ box_id +"_step_no").val();
	// 		var stepNm = "이미지형";
	// 		if(step == "step3"){
	// 			stepNm = "1단 텍스트형";
	// 		}else if(step == "step1"){
	// 			stepNm = "1단 이미지형";
	// 		}else if(step == "step2"){
	// 			stepNm = "2단 이미지형";
	// 		}else if(step == "step4"){
	// 			stepNm = "3단 이미지형";
	// 		}else if(step == "step5"){
	// 			stepNm = "4단 이미지형";
	// 		}
	// 		//alert("box_id : "+ box_id +", box_no : "+ box_no +"\n"+"step : "+ step +", stepId : "+ stepId +", stepNo : "+ stepNo); return;
	// 		var line = Number($("#"+ stepId+"_cnt").val()); //라인별 상품수
	// 		var tit_id ="";
	// 		// alert("tit_id_self_"+stepId +" : "+ $("#tit_id_self_"+stepId).val() +"tit_id_self_yn_"+stepId +" : "+ $("#tit_id_self_yn_"+stepId).val()); return;
	// 		if($("#tit_id_self_yn_"+stepId).val() =="S"){
	// 			tit_id = $("#tit_id_self_"+ stepId).val();
	// 		}else{
	// 			tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
	// 		}
    //
	// 		// alert("stepId : "+ stepId +"line : "+ line +", tit_id : "+ tit_id); return;
	// 		if(step != "step9"){ //행사이미지 제외
	// 			// for(var i=0; i < line; i++){
    //             $('#area_goods_'+stepId).children('dl').each(function(index, item){ //상품코너별 조회
    // 			// for(var i=0; i < line; i++){
    //                 var gid = $(item).prop('id');
    // 				var goods_id = $("#goods_id_"+ gid).val(); //상품번호
    // 				var goods_step = $("#goods_step_"+ gid).val(); //스텝
    // 				var goods_imgpath = $("#goods_imgpath_"+ gid).val(); //이미지경로
    // 				var goods_name = $("#goods_name_"+ gid).val().trim(); //상품명
    // 				var goods_option = $("#goods_option_"+ gid).val().trim(); //규격
    // 				var goods_price = $("#goods_price_"+ gid).val().trim(); //정상가
    //                 var goods_evprice = $("#goods_evprice_"+ gid).val().trim(); //정상가
    // 				var goods_dcprice = $("#goods_dcprice_"+ gid).val().trim(); //할인가
    // 				var goods_seq = $("#goods_seq_"+ gid).val(); //정렬순서
    //                 var goods_option2 = $("#goods_option2_"+ gid).val().trim(); //규격
    //                 var goods_option3 = $("#goods_option3_"+ gid).val().trim(); //규격
    // 				// var goods_badge = $("#goods_badge_"+ gid).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
    //
    // 				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    // 				var goods_barcode = $("#goods_barcode_"+ gid).val().trim(); // 바코드
    //
	// 				// var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
	// 				// var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
	// 				// var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
	// 				// var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
	// 				// var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //규격
	// 				// var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
	// 				// var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
	// 				// var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
	// 				// // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
	// 				// var goods_barcode = $("#goods_barcode_"+ stepId +"_"+ i).val().trim(); // 바코드
	// 				if($("#goods_name_"+ gid).length > 0){ //해당 라인이 존재 할 경우
	// 					no++;
	// 					var strNo = stepNm +" 상품등록 "+ stepNo +"번 "+ no;
	// 					<? //if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
	// 					//if(goods_imgpath == "" && step == "step2" && flag != "temp"){
	// 					//	alert("["+ strNo +"번째]\n상품 이미지를 추가하세요.");
	// 					//	showImg(stepId +"_"+ i);
	// 					//	return;
	// 					//}
	// 					<? //} ?>
	// 					if(goods_name == "" && flag != "temp"){
	// 						err++;
	// 						alert("["+ strNo +"번째]\n상품명을 입력하세요.");
	// 						$("#goods_name_"+ gid).focus();
	// 						return false;
	// 					}
	// 					if(goods_dcprice == "" && flag != "temp"){
	// 						err++;
	// 						alert("["+ strNo +"번째]\n할인가를 입력하세요.");
	// 						$("#goods_dcprice_"+ gid).focus();
	// 						return false;
	// 					}
	// 					if(step == "step2") step2_yn = "Y";
	// 					if(step == "step3") step3_yn = "Y";
	// 				}
	// 			});
	// 		}
	// 	});
	// 	if(err > 0) return;
	// 	//alert("OK"); return;
	// 	if(data_id != "" && "mod" == "<?=$md?>" && flag == ""){ //수정의 경우
	// 		if(!confirm("수정 하시겠습니까?")){
	// 			return;
	// 		}
	// 	}
    //
    //     var emd_order_alltime = $('#emd_order_alltime').is(':checked') ? 'Y' : 'N';
    //
	// 	//스마트전단 데이타 저장
	// 	// jsLoading(); //로딩중 호출
	// 	$.ajax({
	// 		url: "/spop/evemaker/evemaker_data_save",
	// 		type: "POST",
    //         async : false,
	// 		data: {
	// 			  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
	// 			, "flag" : flag
	// 			, "data_id" : data_id //전단번호
	// 			, "tem_id" : tem_id //템플릿번호
	// 			, "emd_title" : emd_title //행사제목
	// 			, "emd_date" : emd_date //행사기간
	// 			, "step1_yn" : step1_yn //할인&대표상품 등록 사용여부
	// 			, "step2_yn" : step2_yn //사용안함
	// 			, "step3_yn" : step3_yn //사용안함
	// 			, "emd_viewyn" : "<?=$emd_viewyn?>" //스마트전단 샘플 뷰 (Y/N)
	// 			, "emd_type" : emd_type //타입(S.전단, C.쿠폰, T.임시저장)
	// 			, "emd_ver_no" : "<?=$emd_ver_no?>" //버전번호
	// 			, "emd_order_yn" : emd_order_yn //주문하기 사용여부 2021-03-05
	// 			, "emd_order_sdt" : emd_order_sdt //주문하기 시작일자 2021-03-05
	// 			, "emd_order_edt" : emd_order_edt //주문하기 종료일자 2021-03-05
    //             , "emd_order_sh" : emd_order_sh //주문하기 시작시간 07
	// 			, "emd_order_eh" : emd_order_eh //주문하기 종료시간 19
    //             , "emd_order_sm" : emd_order_sm //주문하기 시작분 30
	// 			, "emd_order_em" : emd_order_em //주문하기 종료분 50
    //             , "emd_order_st" : $("#emd_order_st").val()
    //             , "emd_order_et" : $("#emd_order_et").val()
    //             , "emd_font_LN" : emd_font_LN //글꼴크기 2023-02-01
    //             , "emd_tem_type" : emd_tem_type
    //             , 'emd_order_alltime' : emd_order_alltime
	// 		},
	// 		success: function (json) {
	// 			//alert("json.id : "+ json.id +", json.emd_code : "+ json.emd_code +", json.flag : "+ json.flag);
	// 			if(json.code == "0") { //성공
	// 				// goods_del(json.id, flag); //스마트전단 상품 삭제 (초기화)
	// 				goods_save(json.id, flag); //스마트전단 상품 저장
	// 				$("#data_id").val(json.id); //전단번호
	// 				if(json.flag == "lms"){ //문자메시지 발송
	// 					location.href = "/dhnbiz/sender/send/lms?emd_code="+ json.emd_code; //문자메시지 발송 페이지
	// 				}else if(json.flag == "talk_adv"){ //알림톡 발송
	// 					location.href = "/dhnbiz/sender/send/talk_adv?emd_code="+ json.emd_code; //알림톡 발송 페이지
	// 				}else if(json.flag == "temp"){ //임시저장
	// 					setTimeout(function() {
	// 						location.replace("/spop/evemaker/write?emd_id=<?=$emd_id?>&md=<?=$md?>&temp_id="+ json.id + add +""); //#smart_btn_group
	// 					}, 50); //0.005초 지연
	// 				}else{
	// 					// showSnackbar("저장 되었습니다.", 1500);
    //             		if(!confirm("저장 되었습니다. 목록으로 이동하시겠습니까?")){
    //                         // hideLoading();
    //             			return;
    //             		}else{
    //                         setTimeout(function() {
    //                             list(); //목록 페이지
    //                         }, 1000); //1초 지연
    //                     }
    //
	// 					// setTimeout(function() {
	// 					// 	list(); //목록 페이지
	// 					// }, 1000); //1초 지연
	// 				}
	// 			}
	// 		}
	// 	});
	// 	//alert("OK"); return;
	// }


    //저장하기
	function saved(flag){
		var code = "1";
		var no = 0;
		var data_id = $("#data_id").val(); //전단번호

		var emd_title = $("#wl_tit").val().trim(); //행사제목
		var emd_sub_info = $("#wl_sub_info").val().trim(); //행사기간
		//alert("data_id : "+ data_id +"\n"+"tem_id : "+ tem_id +"\n"+"emd_title : "+ emd_title +"\n"+"emd_date : "+ emd_date); return;
		if(emd_title == ""){
			alert("이벤트제목 입력하세요.");
			$("#wl_tit").focus();
			return;
		}

		// if(emd_date == "" && flag != "temp"){
		// 	alert("행사기간을 입력하세요.");
		// 	$("#wl_date").focus();
		// 	return;
		// }
		// var emd_order_yn = $(":input:radio[name=emd_order_yn]:checked").val(); //주문하기 사용여부
        // var emd_font_LN = $(":input:radio[name=emd_font_LN]:checked").val(); //주문하기 사용여부
        // var emd_tem_type = $(":input:radio[name=emd_tem_type]:checked").val();
		var emd_open_sdt = $("#emd_open_sdt").val(); //주문하기 시작일자
		var emd_open_edt = $("#emd_open_edt").val(); //주문하기 종료일자
        // var emd_order_sh = $("#emd_order_sh").val(); //주문하기 시작일자
		// var emd_order_eh = $("#emd_order_eh").val(); //주문하기 종료일자
        // var emd_order_sm = $("#emd_order_sm").val(); //주문하기 시작일자
		// var emd_order_em = $("#emd_order_em").val(); //주문하기 종료일자
		// if(emd_order_yn == "Y" && flag != "temp"){
		if(emd_open_sdt == ""){
			alert("이벤트 기간 시작 일자를 입력하세요.");
			$("#emd_order_sdt").focus();
			return;
		}
        // console.log(emd_open_sdt);
		emd_open_sdt = emd_open_sdt.replace(/[^0-9]/g,'');
		if(emd_open_sdt.length != 8){
			alert("이벤트 기간 시작 일자가 올바른 형식이 아닙니다.");
			$("#emd_open_sdt").focus();
			return;
		}
		//emd_order_sdt = emd_order_sdt.substring(0, 4) +"-"+ emd_order_sdt.substring(4, 6) +"-"+ emd_order_sdt.substring(6, 8);
		//alert("emd_order_sdt : "+ emd_order_sdt); return;
		if(emd_open_edt == ""){
			alert("이벤트 기간 종료 일자를 입력하세요.");
			$("#emd_open_edt").focus();
			return;
		}
		emd_open_edt = emd_open_edt.replace(/[^0-9]/g,'');
		if(emd_open_edt.length != 8){
			alert("이벤트 기간 종료 일자가 올바른 형식이 아닙니다.");
			$("#emd_open_edt").focus();
			return;
		}
		if(emd_open_sdt > emd_open_edt){
			alert("이벤트 기간 종료 일자가를 확인해 주세요.");
			$("#emd_open_edt").focus();
			return;
		}

		// }


		//alert("[할인&대표상품 등록] OK"); return;



        var emd_type = $('input[name="emd_tem_type"]:checked').val(); //STEP2 타이틀번호
        if(emd_type == ""){
            emd_type = "A";
        }


		// var goods_box_cnt = Number($("#goods_box_cnt").val()); //상품 박스 갯수
		//alert("goods_box_cnt : "+ goods_box_cnt); return;
		//for(var j=1; j <= goods_box_cnt; j++){ //박스 갯수만큼
		var err = 0;
		var j = 0;
		// $("input[name=goods_box_id]").each(function(index, item){ //상품코너별 조회
			no = 0;

			//alert("box_id : "+ box_id +", box_no : "+ box_no +"\n"+"step : "+ step +", stepId : "+ stepId +", stepNo : "+ stepNo); return;
			var line = Number($("#step0_cnt").val()); //라인별 상품수




			// alert("stepId : "+ stepId +"line : "+ line +", tit_id : "+ tit_id); return;
			// if(step != "step9"){ //행사이미지 제외
				// for(var i=0; i < line; i++){
                $('#area_goods_step0').children('dl').each(function(index, item){ //상품코너별 조회
    			// for(var i=0; i < line; i++){

                    var id = $(item).prop('id');
                    var splitArr = id.split("_");
                    var dl_id = splitArr[1];//id.substring(id.length-1, id.length); //스텝 ID
                    // console.log(dl_id);
                    var dl_type = $(":input:radio[name=emd_type_"+dl_id+"]:checked").val();

    				var goods_id = $("#goods_id_"+ dl_id).val(); //상품번호
    				// var goods_step = $("#goods_step_"+ dl_id).val(); //스텝
    				var goods_imgpath = $("#goods_imgpath_"+ dl_id).val(); //이미지경로
    				var goods_name = $("#goods_name_"+ dl_id).val().trim(); //상품명
    				var goods_info = $("#goods_info_"+ dl_id).val().trim(); //규격
    				var goods_cnt = $("#goods_cnt_"+ dl_id).val().trim(); //정상가
                    var goods_deposit = $("#goods_deposit_"+ dl_id).val().trim(); //정상가

    				// var goods_badge = $("#goods_badge_"+ gid).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)

    				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    				// var goods_barcode = $("#goods_barcode_"+ dl_id).val().trim(); // 바코드

					// var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
					// var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
					// var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
					// var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
					// var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //규격
					// var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
					// var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
					// var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
					// // 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
					// var goods_barcode = $("#goods_barcode_"+ stepId +"_"+ i).val().trim(); // 바코드
					if($("#goods_name_"+ dl_id).length > 0){ //해당 라인이 존재 할 경우
						no++;

						<? //if($this->member->item('mem_level') < 100){ //최고관리자 예외처리 ?>
						//if(goods_imgpath == "" && step == "step2" && flag != "temp"){
						//	alert("["+ strNo +"번째]\n상품 이미지를 추가하세요.");
						//	showImg(stepId +"_"+ i);
						//	return;
						//}
						<? //} ?>
						if(goods_name == ""){
							err++;
							alert("["+ no +"번째]\n상품명을 입력하세요.");
							$("#goods_name_"+ dl_id).focus();
							return false;
						}
						// if(goods_dcprice == "" && flag != "temp"){
						// 	err++;
						// 	alert("["+ strNo +"번째]\n할인가를 입력하세요.");
						// 	$("#goods_dcprice_"+ gid).focus();
						// 	return false;
						// }

					}
				});

		// });
		if(err > 0) return;
		//alert("OK"); return;
		if(data_id != "" && "mod" == "<?=$md?>" && flag == ""){ //수정의 경우
			if(!confirm("수정 하시겠습니까?")){
				return;
			}
		}

        // var emd_order_alltime = $('#emd_order_alltime').is(':checked') ? 'Y' : 'N';

		//스마트전단 데이타 저장
		// jsLoading(); //로딩중 호출
		$.ajax({
			url: "/spop/evemaker/evemaker_data_save",
			type: "POST",
            async : false,
			data: {
				  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
				, "data_id" : data_id //전단번호
				, "emd_title" : emd_title //행사제목
				, "emd_sub_info" : emd_sub_info //행사기간
				, "emd_open_sdt" : emd_open_sdt //주문하기 시작일자 2021-03-05
				, "emd_open_edt" : emd_open_edt //주문하기 종료일자 2021-03-05
                , "emd_tem_type" : tem_type
			},
			success: function (json) {
				//alert("json.id : "+ json.id +", json.emd_code : "+ json.emd_code +", json.flag : "+ json.flag);
				if(json.code == "0") { //성공
					// goods_del(json.id, flag); //스마트전단 상품 삭제 (초기화)
					goods_save(json.id); //스마트전단 상품 저장
					$("#data_id").val(json.id); //전단번호

						// showSnackbar("저장 되었습니다.", 1500);
                		if(!confirm("저장 되었습니다. 목록으로 이동하시겠습니까?")){
                            // hideLoading();
                			return;
                		}else{
                            setTimeout(function() {
                                list(); //목록 페이지
                            }, 1000); //1초 지연
                        }

						// setTimeout(function() {
						// 	list(); //목록 페이지
						// }, 1000); //1초 지연

				}
			}
		});
		//alert("OK"); return;
	}

    function goods_save(data_id){
		var cnt = 0;
		var ui = new Array();
		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 배열생성 > STEP1
		//--------------------------------------------------------------------------------------------------------------


            var i = 0;







                // return false;
                $('#area_goods_step0').children('dl').each(function(index, item){ //상품코너별 조회
    			// for(var i=0; i < line; i++){
                    var id = $(item).prop('id');
                    var splitArr = id.split("_");
                    var dl_id = splitArr[1];
                    // var dl_id = id.substring(id.length-1, id.length); //스텝 ID


                    // console.log(dl_id);
                    var dl_type = $(":input:radio[name=emd_type_"+dl_id+"]:checked").val();

                    var goods_id = $("#goods_id_"+ dl_id).val(); //상품번호
                    // var goods_step = $("#goods_step_"+ dl_id).val(); //스텝
                    var goods_imgpath = $("#goods_imgpath_"+ dl_id).val(); //이미지경로
                    var goods_name = $("#goods_name_"+ dl_id).val().trim(); //상품명
                    var goods_info = $("#goods_info_"+ dl_id).val().trim(); //규격
                    var goods_cnt = $("#goods_cnt_"+ dl_id).val().trim(); //정상가
                    var goods_deposit = $("#goods_deposit_"+ dl_id).val().trim(); //정상가
                    var goods_barcode = "";
                    var goods_barcode_type = "";

                    if(dl_type=="K"){
                        goods_name = "꽝"; //상품명
                        goods_info = "다음기회에";
                    }else if(dl_type=="N"){
                        goods_barcode = $("#barcode_txt_"+dl_id).val().trim();
                        goods_barcode_type = $("#select_barcode_length_"+dl_id).val().trim();
                    }


    				if($("#goods_name_"+ dl_id).length > 0){ //해당 라인이 존재 할 경우
    					var pushData = {
    						  "data_id" : data_id //전단번호
    						, "goods_id" : goods_id //상품번호
                            , "goods_type" : dl_type //상품번호
    						, "goods_imgpath" : goods_imgpath //이미지경로
    						, "goods_name" : goods_name //상품명
    						, "goods_info" : goods_info //규격
    						, "goods_cnt" : goods_cnt //정상가
                            , "goods_deposit" : goods_deposit //정상가
                            , "goods_barcode" : goods_barcode //정상가
                            , "goods_barcode_type" : goods_barcode_type //정상가
    					};
    					ui.push(pushData);
    					cnt++;
                        i++;
    					//if(cnt == 1) alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
    				}

    			});



		//--------------------------------------------------------------------------------------------------------------
		// 스마트전단 상품 저장
		//--------------------------------------------------------------------------------------------------------------

		// alert("cnt : "+ cnt );
        console.log( JSON.stringify(ui));
        // return false;
        // console.log("cnt : "+ cnt );

		if( cnt > 0 ) {
            var uiStr = JSON.stringify(ui);
            var emd_all_cnt = "<?=$emd_goods_cnt?>";
			//alert("cnt : "+ cnt +", uiStr : "+ uiStr); return;
			//alert("순서변경 적용중입니다.");
			//showSnackbar("순서변경 적용중입니다.", 2000);
			var formData = new FormData();
			formData.append("updata", uiStr);
            formData.append("emd_all_cnt", emd_all_cnt);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/spop/evemaker/evemaker_goods_arr_save",
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
                    if(json.code == "0") { //성공

    					/*최초 저장하는 경우 : 생성된 ID를 Update해준다-2021.04.27*/
    					// $("#data_id").val(json.id);
                        html2canvas(document.querySelector(".eve_container"), {scale:1, scrollX: 0,
                    		scrollY: -window.scrollY}).then(function(canvas){

                            var imageData = canvas.toDataURL("image/jpeg");
                            var formData = new FormData();
                            formData.append('img_file', imageData);
                            formData.append('id', data_id);
                            formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");

                            $.ajax({
                                    url : 'thumb_upload',
                                    type : "POST",
                                    dataType : "json",
                                    data : formData,
                                    processData : false,
                                    contentType : false,
                                    success : function(data) {
                                        showSnackbar("저장 되었습니다.", 1500);
                                        reload_banner();
                                    },

                                    error : function(request, status, error) {
                                        console.log(request, status, error);
                                    }

                                });

                		});
    				}

				}
			});
		}
		isChange = false;
	}



    //스마트전단 상품 저장
	// function goods_save(data_id, flag){
	// 	var cnt = 0;
	// 	var ui = new Array();
    //     var pui = new Array();
    //     // var tui = new Array();
	// 	//--------------------------------------------------------------------------------------------------------------
	// 	// 스마트전단 상품 배열생성 > STEP1
	// 	//--------------------------------------------------------------------------------------------------------------
	// 	var step1_yn_Y = $("#step1_yn_Y").is(":checked");
	// 	if(step1_yn_Y){ //STEP1 사용함
	// 		var step1_cnt = Number($("#step1_cnt").val()); //할인&대표상품 등록 등록 상품수
	// 		// alert("step1_cnt : "+ step1_cnt);
    //
    //         $('#area_goods_step1').children('dl').each(function(index, item){ //상품코너별 조회
    //
	// 		// for(var i=0; i < step1_cnt; i++){
    //             var gid = $(item).prop('id');
	// 			var goods_id = $("#goods_id_"+ gid).val(); //상품번호
	// 			var goods_step = $("#goods_step_"+ gid).val(); //스텝
	// 			var goods_imgpath = $("#goods_imgpath_"+ gid).val(); //이미지경로
	// 			var goods_name = $("#goods_name_"+ gid).val().trim(); //상품명
	// 			var goods_option = $("#goods_option_"+ gid).val().trim(); //규격
	// 			var goods_price = $("#goods_price_"+ gid).val().trim(); //정상가
    //             var goods_evprice = $("#goods_evprice_"+ gid).val().trim(); //정상가
	// 			var goods_dcprice = $("#goods_dcprice_"+ gid).val().trim(); //할인가
	// 			var goods_seq = $("#goods_seq_"+ gid).val(); //정렬순서
	// 			var goods_badge = $("#goods_badge_"+ gid).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
	// 			var goods_option2 = $("#goods_option2_"+ gid).val().trim(); //옵션
    //             var goods_option3 = $("#goods_option3_"+ gid).val().trim(); //옵션
	// 			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
	// 			var goods_barcode = $("#goods_barcode_"+ gid).val().trim(); // 바코드
    //             var goods_soldout = $("#goods_soldout_"+ gid).val(); //품절여부
    //             var goods_noorder = $("#goods_noorder_"+ gid).val(); //품절여부
    //             // alert(goods_name + "  /  " + goods_soldout); //품절여부
	// 			if(goods_soldout=="" || goods_soldout == null){
	// 				goods_soldout="Y";
	// 			}
    //
    //             if(goods_noorder=="" || goods_noorder == null){
	// 				goods_noorder="N";
	// 			}
    //             var gbadgeImgPath = "";
    //             if($("#div_badge_"+gid).hasClass("design_badge") == true){
    //                 gbadgeImgPath = $("#div_badge_"+gid).children("img").attr("src");
    //             }
    //
	// 			if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
	// 				goods_price = goods_price.trim();
	// 				var rtn_price = goods_price;
	// 				var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
	// 				if(temp_price !=""){
	// 					rtn_price = rtn_price.replace(/,/g,'');
	// 					var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
	// 					rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
	// 				}
	// 				goods_price = rtn_price;
	// 			}
    //             if(goods_evprice != ""){ //정상가 (숫자만 콤마 처리)
	// 				goods_evprice = goods_evprice.trim();
	// 				var rtn_price = goods_evprice;
	// 				var temp_price = goods_evprice.replace(/[^0-9]/g,''); //숫자만 추출
	// 				if(temp_price !=""){
	// 					rtn_price = rtn_price.replace(/,/g,'');
	// 					var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
	// 					rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
	// 				}
	// 				goods_evprice = rtn_price;
	// 			}
	// 			if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
	// 				goods_dcprice = goods_dcprice.trim();
	// 				var rtn_price = goods_dcprice;
	// 				var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
	// 				if(temp_price !=""){
	// 					rtn_price = rtn_price.replace(/,/g,'');
	// 					var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
	// 					rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
	// 				}
	// 				goods_dcprice = rtn_price.replace(/<span class="sm_small">원<\/span>/gi, '원'); //원 작은글씨 본원 처리
	// 			}
	// 			if(goods_badge == "1" && (goods_price == "" || goods_dcprice == "")){
	// 				goods_badge = "0";
	// 			}
	// 			//<span class="sm_small">원</span>
	// 			//alert("goods_badge : "+ goods_badge); return;
    //
	// 			if($("#goods_name_"+ gid).length > 0){ //해당 라인이 존재 할 경우
	// 				var pushData = {
	// 					  "data_id" : data_id //전단번호
	// 					, "goods_id" : goods_id //상품번호
	// 					, "tit_id" : 0 //타이틀번호
	// 					, "goods_step" : goods_step //스텝
	// 					, "goods_step_no" : 0 //스텝순번
	// 					, "goods_imgpath" : goods_imgpath //이미지경로
	// 					, "goods_name" : goods_name //상품명
	// 					, "goods_option" : goods_option //규격
	// 					, "goods_price" : goods_price //정상가
    //                     , "goods_evprice" : goods_evprice //정상가
	// 					, "goods_dcprice" : goods_dcprice //할인가
	// 					, "goods_seq" : goods_seq //할인가
	// 					, "goods_badge" : goods_badge //할인뱃지
	// 					, "goods_option2" : goods_option2 //옵션
    //                     , "goods_option3" : goods_option3 //옵션
	// 					, "goods_barcode" : goods_barcode //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    //                     , "goods_stock" : goods_soldout //품절여부 - 2021-06-17 추가
    //                     , "goods_noorder" : goods_noorder //주문불가 - 2023-04-06 추가
    //                     , "badge_imgpath" : gbadgeImgPath // 뱃지이미지
	// 				};
	// 				pui.push(pushData);
	// 				cnt++;
	// 				//alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
	// 			}
	// 		// }
    //         });
	// 	}
	// 	//if( cnt > 0 ) alert("STEP1 cnt : "+ cnt +", ui : "+ JSON.stringify(ui));
	// 	//--------------------------------------------------------------------------------------------------------------
	// 	// 스마트전단 상품 배열생성 > 박스 갯수만큼
	// 	//--------------------------------------------------------------------------------------------------------------
    //
	// 	var goods_box_id = "goods_box_id";
	// 	if(flag == "temp") goods_box_id = "modal_sort_box_id";
	// 	$("input[name="+ goods_box_id +"]").each(function(index, item){ //상품코너별 조회
    //
	// 		var box_id = $(item).val(); //코너별 ID
	// 		//var box_no = $("#"+ box_id +"_box_no").val();
	// 		var box_no = index+1; //코너별 순번
	// 		var step = $("#"+ box_id +"_step").val(); //스텝
	// 		var stepId = $("#"+ box_id +"_step_id").val();
	// 		var stepNo = $("#"+ box_id +"_step_no").val();
    //         // var stepCnt = $("#"+stepId+"_cnt").val();
    //         var titImgPath = $("#pre_tit_id_"+stepId).children("img").attr("src");
	// 		//alert("box_id : "+ box_id +", box_no : "+ box_no +"\n"+"step : "+ step +", stepId : "+ stepId +", stepNo : "+ stepNo); return;
	// 		var line = Number($("#"+ stepId+"_cnt").val()); //라인별 상품수
	// 		var tit_id ="";
    //         var tit_self = "";
	// 		if($("#tit_id_self_yn_"+stepId).val() =="S"){
	// 			tit_id = $("#tit_id_self_"+ stepId).val();
    //             tit_self = "S";
	// 		}else{
	// 			tit_id = $('input[name="tit_id_'+ stepId +'"]:checked').val(); //STEP2 타이틀번호
    //             tit_self = "Y";
	// 		}
	// 		if(step == "step9"){
	// 			line = 1;
	// 			tit_id = 0;
	// 		}
    //
    //
    //
    //         var i = 0;
    //
    //       // if($("#goods_name_"+ stepId +"_"+ i).length > 0){ //해당 라인이 존재 할 경우
    //       //     var pushData = {
    //       //           "data_id" : data_id //전단번호
    //       //         , "tit_step" : goods_step //스텝
    //       //         , "tit_step_no" : box_no //스텝순번
    //       //         , "tit_seq" : goods_seq //정렬순서
    //       //         , "box_no" : box_no //코너별 순번
    //       //         , "tit_text_info" : tit_text_info // 바로가기 텍스트 정보
    //       //
    //       //     };
    //       //     tui.push(pushData);
    //       //     cnt++;
    //       //     //if(cnt == 1) alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
    //       // }
    //
    //
	// 		// alert("line : "+ line +", tit_id : "+ tit_id);
    //         if(step == "step9"){
    //
    // 			// for(var i=0; i < line; i++){
    //
    //             var goods_id = $("#goods_id_"+ stepId +"_"+ i).val(); //상품번호
	// 			var goods_step = $("#goods_step_"+ stepId +"_"+ i).val(); //스텝
	// 			var goods_imgpath = $("#goods_imgpath_"+ stepId +"_"+ i).val(); //이미지경로
	// 			var goods_name = $("#goods_name_"+ stepId +"_"+ i).val().trim(); //상품명
	// 			var goods_option = $("#goods_option_"+ stepId +"_"+ i).val().trim(); //규격
	// 			var goods_price = $("#goods_price_"+ stepId +"_"+ i).val().trim(); //정상가
    //             var goods_evprice = $("#goods_evprice_"+ stepId +"_"+ i).val().trim(); //정상가
	// 			var goods_dcprice = $("#goods_dcprice_"+ stepId +"_"+ i).val().trim(); //할인가
	// 			var goods_seq = $("#goods_seq_"+ stepId +"_"+ i).val(); //정렬순서
	// 			var goods_badge = $("#goods_badge_"+ stepId +"_"+ i).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
	// 			var goods_option2 = $("#goods_option2_"+ stepId +"_"+ i).val().trim(); //옵션
    //             var goods_option3 = $("#goods_option3_"+ stepId +"_"+ i).val().trim(); //옵션
	// 			// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
	// 			var goods_barcode = $("#goods_barcode_"+ stepId +"_"+ i).val().trim(); // 바코드
    //             var goods_soldout = $("#goods_soldout_"+ stepId +"_"+ i).val(); //품절여부 - 2021-06-17 추가
    //             var goods_noorder = $("#goods_noorder_"+ stepId +"_"+ i).val();
    //
	// 			var tit_text_info = $("#t_tit_text_info_"+ stepId).val(); // 바로가기 텍스트 정보 2021-08-12 추가
    //
    //         //         var gid = $(item).prop('id');
    // 		// 		var goods_id = $("#goods_id_"+ gid).val(); //상품번호
    // 		// 		var goods_step = $("#goods_step_"+ gid).val(); //스텝
    // 		// 		var goods_imgpath = $("#goods_imgpath_"+ gid).val(); //이미지경로
    // 		// 		var goods_name = $("#goods_name_"+ gid).val().trim(); //상품명
    // 		// 		var goods_option = $("#goods_option_"+ gid).val().trim(); //규격
    // 		// 		var goods_price = $("#goods_price_"+ gid).val().trim(); //정상가
    // 		// 		var goods_dcprice = $("#goods_dcprice_"+ gid).val().trim(); //할인가
    // 		// 		var goods_seq = $("#goods_seq_"+ gid).val(); //정렬순서
    // 		// 		var goods_badge = $("#goods_badge_"+ gid).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
    // 		// 		var goods_option2 = $("#goods_option2_"+ gid).val().trim(); //옵션
    // 		// 		// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    // 		// 		var goods_barcode = $("#goods_barcode_"+ gid).val().trim(); // 바코드
    //         // var goods_soldout = $("#goods_soldout_"+ gid).val(); //품절여부 - 2021-06-17 추가
    // 		// 		var tit_text_info = $("#t_tit_text_info_"+ stepId).val(); // 바로가기 텍스트 정보 2021-08-12 추가
    //
    //                 var badgeImgPath = "";
    //                 // if($("#div_badge_"+gid).hasClass("design_badge") == true){
    //                 //     badgeImgPath = $("#div_badge_"+gid).children("img").attr("src");
    //                 // }
    //                 // alert("goods_step : "+ goods_step);
    // 				if(goods_soldout=="" || goods_soldout == null){
    // 					goods_soldout="Y";
    // 				}
    //
    //                 if(goods_noorder=="" || goods_noorder == null){
    // 					goods_noorder="N";
    // 				}
    //
    // 				if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
    // 					goods_price = goods_price.trim();
    // 					var rtn_price = goods_price;
    // 					var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
    // 					if(temp_price !=""){
    // 						rtn_price = rtn_price.replace(/,/g,'');
    // 						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
    // 						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
    // 					}
    // 					goods_price = rtn_price;
    // 				}
    //                 if(goods_evprice != ""){ //정상가 (숫자만 콤마 처리)
    // 					goods_evprice = goods_evprice.trim();
    // 					var rtn_price = goods_evprice;
    // 					var temp_price = goods_evprice.replace(/[^0-9]/g,''); //숫자만 추출
    // 					if(temp_price !=""){
    // 						rtn_price = rtn_price.replace(/,/g,'');
    // 						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
    // 						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
    // 					}
    // 					goods_evprice = rtn_price;
    // 				}
    // 				if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
    // 					goods_dcprice = goods_dcprice.trim();
    // 					var rtn_price = goods_dcprice;
    // 					var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
    // 					if(temp_price !=""){
    // 						rtn_price = rtn_price.replace(/,/g,'');
    // 						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
    // 						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
    // 					}
    // 					goods_dcprice = rtn_price;
    // 				}
    //
    //                 if(i>0&&step != "step9"){
    //                     titImgPath="";
    //                 }
    // 				if(goods_badge == "") goods_badge = "0";
    //
    // 				// if($("#goods_name_"+ gid).length > 0){ //해당 라인이 존재 할 경우
    // 					var pushData = {
    // 						  "data_id" : data_id //전단번호
    // 						, "goods_id" : goods_id //상품번호
    // 						, "tit_id" : tit_id //타이틀번호
    // 						, "goods_step" : goods_step //스텝
    // 						, "goods_step_no" : box_no //스텝순번
    // 						, "goods_imgpath" : goods_imgpath //이미지경로
    // 						, "goods_name" : goods_name //상품명
    // 						, "goods_option" : goods_option //규격
    // 						, "goods_price" : goods_price //정상가
    //                         , "goods_evprice" : goods_evprice //정상가
    // 						, "goods_dcprice" : goods_dcprice //할인가
    // 						, "goods_badge" : goods_badge //할인뱃지
    // 						, "goods_seq" : goods_seq //정렬순서
    // 						, "box_no" : box_no //코너별 순번
    // 						, "goods_option2" : goods_option2 //옵션
    //                         , "goods_option3" : goods_option3 //옵션
    // 						, "goods_barcode" : goods_barcode //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    //             , "goods_stock" : goods_soldout //품절여부 2021-06-17 추가
    //             , "goods_noorder" : goods_noorder //주문불가 - 2023-04-06 추가
    // 						, "tit_text_info" : tit_text_info // 바로가기 텍스트 정보
    //                         , "badge_imgpath" : badgeImgPath // 뱃지이미지
    //                         , "tit_imgpath" : titImgPath //타이틀 이미지
    //                         , "tit_self" : tit_self //타이틀 이미지
    //                         , "rownum" : line //코너상품수
    //
    // 					};
    // 					ui.push(pushData);
    // 					cnt++;
    //                     // i++;
    // 					//if(cnt == 1) alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
    // 				// }
    //
    //
    //         }else{
    //             var chil_tag = "dl";
    //             if(step == "step3"){
    //                 chil_tag = "li";
    //             }
    //             var tit_type = $("#tit_id_custom_yn_"+ stepId).val().trim();
    //             var bgcolor = $("#t_tit_bgcolor_"+ stepId).val().trim();
    //             var txcolor = $("#t_tit_txcolor_"+ stepId).val().trim();
    //             var tit_align = $("#tit_id_custom_align_"+ stepId).val().trim();
    //
    //
    //
    //
    //             // return false;
    //             $('#area_goods_'+stepId).children(chil_tag).each(function(index, item){ //상품코너별 조회
    // 			// for(var i=0; i < line; i++){
    //
    //                 var gid = $(item).prop('id');
    // 				var goods_id = $("#goods_id_"+ gid).val(); //상품번호
    // 				var goods_step = $("#goods_step_"+ gid).val(); //스텝
    // 				var goods_imgpath = $("#goods_imgpath_"+ gid).val(); //이미지경로
    // 				var goods_name = $("#goods_name_"+ gid).val().trim(); //상품명
    // 				var goods_option = $("#goods_option_"+ gid).val().trim(); //규격
    // 				var goods_price = $("#goods_price_"+ gid).val().trim(); //정상가
    //                 var goods_evprice = $("#goods_evprice_"+ gid).val().trim(); //정상가
    // 				var goods_dcprice = $("#goods_dcprice_"+ gid).val().trim(); //할인가
    // 				var goods_seq = $("#goods_seq_"+ gid).val(); //정렬순서
    // 				var goods_badge = $("#goods_badge_"+ gid).val(); //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
    // 				var goods_option2 = $("#goods_option2_"+ gid).val().trim(); //옵션
    //                 var goods_option3 = $("#goods_option3_"+ gid).val().trim(); //옵션
    // 				// 2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    // 				var goods_barcode = $("#goods_barcode_"+ gid).val().trim(); // 바코드
    //         var goods_soldout = $("#goods_soldout_"+ gid).val(); //품절여부 - 2021-06-17 추가
    //         var goods_noorder = $("#goods_noorder_"+ gid).val();
    //
    // 				var tit_text_info = $("#t_tit_text_info_"+ stepId).val(); // 바로가기 텍스트 정보 2021-08-12 추가
    //
    //                 var sub_title = $("#t_sub_txt_"+stepId).val().trim();
    //                 var sub_title_size = "";
    //                 var sub_title_color = "";
    //                 if(sub_title!=""){
    //                     sub_title_size = $("#sub_title_text_"+stepId).css("fontSize").trim();
    //                     sub_title_color = $("#t_sub_txcolor_"+stepId).val().trim();
    //                 }
    //                 console.log("1 - " + sub_title + " / " + sub_title_size + " / " + sub_title_color + " / i : "+ i);
    //
    //                 var badgeImgPath = "";
    //                 if($("#div_badge_"+gid).hasClass("design_badge") == true){
    //                     badgeImgPath = $("#div_badge_"+gid).children("img").attr("src");
    //                 }
    //                 // alert("goods_step : "+ goods_step);
    // 				if(goods_soldout=="" || goods_soldout == null){
    // 					goods_soldout="Y";
    // 				}
    //
    //                 if(goods_noorder=="" || goods_noorder == null){
    // 					goods_noorder="N";
    // 				}
    //
    // 				if(goods_price != ""){ //정상가 (숫자만 콤마 처리)
    // 					goods_price = goods_price.trim();
    // 					var rtn_price = goods_price;
    // 					var temp_price = goods_price.replace(/[^0-9]/g,''); //숫자만 추출
    // 					if(temp_price !=""){
    // 						rtn_price = rtn_price.replace(/,/g,'');
    // 						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
    // 						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
    // 					}
    // 					goods_price = rtn_price;
    // 				}
    //                 if(goods_evprice != ""){ //정상가 (숫자만 콤마 처리)
    // 					goods_evprice = goods_evprice.trim();
    // 					var rtn_price = goods_evprice;
    // 					var temp_price = goods_evprice.replace(/[^0-9]/g,''); //숫자만 추출
    // 					if(temp_price !=""){
    // 						rtn_price = rtn_price.replace(/,/g,'');
    // 						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
    // 						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
    // 					}
    // 					goods_evprice = rtn_price;
    // 				}
    // 				if(goods_dcprice != ""){ //할인가 (숫자만 콤마 처리)
    // 					goods_dcprice = goods_dcprice.trim();
    // 					var rtn_price = goods_dcprice;
    // 					var temp_price = goods_dcprice.replace(/[^0-9]/g,''); //숫자만 추출
    // 					if(temp_price !=""){
    // 						rtn_price = rtn_price.replace(/,/g,'');
    // 						var coma_price = temp_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //금액 콤마 찍기
    // 						rtn_price = rtn_price.replace(temp_price, coma_price); //최종 금액
    // 					}
    // 					goods_dcprice = rtn_price;
    // 				}
    //
    //                 if(i>0&&step != "step9"){
    //                     titImgPath="";
    //                     tit_type="";
    //                     bgcolor="";
    //                     txcolor="";
    //                     tit_align="";
    //                     sub_title = "";
    //                     sub_title_size = "";
    //                     sub_title_color = "";
    //                 }
    // 				if(goods_badge == "") goods_badge = "0";
    //                 console.log("2 - " + sub_title + " / " + sub_title_size + " / " + sub_title_color + " / i : "+ i);
    // 				if($("#goods_name_"+ gid).length > 0){ //해당 라인이 존재 할 경우
    // 					var pushData = {
    // 						  "data_id" : data_id //전단번호
    // 						, "goods_id" : goods_id //상품번호
    // 						, "tit_id" : tit_id //타이틀번호
    // 						, "goods_step" : goods_step //스텝
    // 						, "goods_step_no" : box_no //스텝순번
    // 						, "goods_imgpath" : goods_imgpath //이미지경로
    // 						, "goods_name" : goods_name //상품명
    // 						, "goods_option" : goods_option //규격
    // 						, "goods_price" : goods_price //정상가
    //                         , "goods_evprice" : goods_evprice //정상가
    // 						, "goods_dcprice" : goods_dcprice //할인가
    // 						, "goods_badge" : goods_badge //할인뱃지
    // 						, "goods_seq" : goods_seq //정렬순서
    // 						, "box_no" : box_no //코너별 순번
    // 						, "goods_option2" : goods_option2 //옵션
    //                         , "goods_option3" : goods_option3 //옵션
    // 						, "goods_barcode" : goods_barcode //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    //             , "goods_stock" : goods_soldout //품절여부 2021-06-17 추가
    //             , "goods_noorder" : goods_noorder //주문불가 - 2023-04-06 추가
    // 						, "tit_text_info" : tit_text_info // 바로가기 텍스트 정보
    //                         , "badge_imgpath" : badgeImgPath // 뱃지이미지
    //                         , "tit_imgpath" : titImgPath //타이틀 이미지
    //                         , "tit_self" : tit_self //타이틀 이미지
    //                         , "tit_type" : tit_type
    //                         , "bgcolor" : bgcolor
    //                         , "txcolor" : txcolor
    //                         , "tit_align" : tit_align
    //                         , "sub_title" : sub_title
    //                         , "sub_title_size" : sub_title_size
    //                         , "sub_title_color" : sub_title_color
    //                         , "rownum" : line //코너상품수
    //
    // 					};
    // 					ui.push(pushData);
    // 					cnt++;
    //                     i++;
    // 					//if(cnt == 1) alert("["+ i +"] data_id : "+ data_id +"\n"+"goods_id : "+ goods_id +"\n"+"tit_id : "+ tit_id +"\n"+"goods_step : "+ goods_step +"\n"+"goods_name : "+ goods_name +"\n"+"goods_option : "+ goods_option +"\n"+"goods_price : "+ goods_price +"\n"+"goods_dcprice : "+ goods_dcprice +"\n"+"goods_seq : "+ goods_seq);
    // 				}
    //
    // 			});
    //         }
    //
	// 	});
	// 	//--------------------------------------------------------------------------------------------------------------
	// 	// 스마트전단 상품 저장
	// 	//--------------------------------------------------------------------------------------------------------------
    //
	// 	// alert("cnt : "+ cnt );
    //     console.log( JSON.stringify(ui));
    //     // return false;
    //     // console.log("cnt : "+ cnt );
    //
	// 	if( cnt > 0 ) {
	// 		var uiStr = JSON.stringify(pui);
    //         var uiStr2 = JSON.stringify(ui);
    //         var emd_all_cnt = "<?=$emd_all_cnt?>";
	// 		//alert("cnt : "+ cnt +", uiStr : "+ uiStr); return;
	// 		//alert("순서변경 적용중입니다.");
	// 		//showSnackbar("순서변경 적용중입니다.", 2000);
	// 		var formData = new FormData();
	// 		formData.append("updata", uiStr);
    //         formData.append("updata2", uiStr2);
    //         formData.append("emd_all_cnt", emd_all_cnt);
	// 		formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
	// 		$.ajax({
	// 			url: "/spop/evemaker/evemaker_goods_arr_save",
	// 			type: "POST",
	// 			data: formData,
	// 			async: false,
	// 			processData: false,
	// 			contentType: false,
	// 			beforeSend: function () {
	// 				//$('#overlay').fadeIn();
	// 			},
	// 			complete: function () {
	// 				//$('#overlay').fadeOut();
	// 			},
	// 			success: function (json) {
    //
	// 			}
	// 		});
	// 	}
	// 	isChange = false;
	// }

	//목록
	function list(){
		location.href = "/spop/evemaker"+ add.replace(/&/gi, '?'); //스마트전단 목록 페이지
	}

	//스마트전단 상품 사용여부
	// function goods_yn(stepId, dis){
	// 	//alert("stepId : "+ stepId +", dis : "+ dis);
	// 	$("#goods_list_"+ stepId).css("display", dis);
	// 	$("#pre_goods_list_"+ stepId).css("display", dis);
	// 	if(dis != "none"){ //사용함의 경우
	// 		var step_cnt = $("#"+ stepId +"_cnt").val(); //STEP별 등록 상품수
	// 		//alert("stepId : "+ stepId +", dis : "+ dis +", step_cnt : "+ step_cnt);
	// 		if(stepId == "step1" && step_cnt == 0){ //STEP1 등록된 상품이 없는 경우
	// 			for(i = 0; i < <?=$step1_std?>; i++){
	// 				goods_append('1', 'step1', 'goods_yn', 'Y'); //STEP1 할인상품 등록 영역 추가
	// 			}
	// 		}else if(stepId == "step2" && step_cnt == 0){ //STEP2 등록된 상품이 없는 경우
	// 			goods_area_copy('goods_list_step2', 'step2'); //STEP2. 이미지형 상품등록 영역 복사
	// 		}else if(stepId == "step3" && step_cnt == 0){ //STEP3 등록된 상품이 없는 경우
	// 			goods_area_copy('goods_list_step3', 'step3'); //STEP3. 텍스트형 상품등록 영역 복사
	// 		}
	// 	}
	// }

	//스마트전단 상품 삭제 (초기화)
	function goods_del(data_id, flag){
		$.ajax({
			url: "/spop/evemaker/evemaker_goods_del",
			type: "POST",
            async : false,
			data: {"data_id" : data_id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
                goods_save(data_id, flag);
            }
		});
	}

    function goods_append(flag){
        var html = 	'';
        var imgurl = "";
        var ii = $("#step0_cnt").val();
        var mxnum = $("#step0_mx").val();

        var iis = ii;

        if(iis<8){
            // if(idArr.length>0){
            //     var mxnum = Math.max.apply(null, idArr);
            //     ii=mxnum++;
            //     console.log(idArr+" / "+ mxnum + " / " + ii);
            // }
            if(ii!=mxnum&&mxnum>0){
                ii = mxnum;
            }


            var iii = Number(ii)+1;


            html += "             <dl id=\"step0_"+ ii +"\" class=\"dl_step0\">";
            html += "                  <dt>";
            html += "                    <div class=\"templet_img_in\" onclick=\"showImg('"+ ii +"')\">";
            html += "                      <div id=\"div_preview_"+ ii +"\" class=\"templet_img_in2\" style=\"background-image : url('"+imgurl+"');\">";
            html += "                        <img id=\"img_preview_"+ ii +"\" style=\"display:none;width:100%\">";
            html += "                      </div>";
            html += "                    </div>";
            html += "                  </dt>";
            html += "                  <dd>";
            html += "                      <input type=\"hidden\" id=\"goods_id_"+ ii +"\" value=\"\">";
            html += "                      <input type=\"hidden\" id=\"goods_seq_"+ ii +"\" value=\""+ ii +"\" style=\"width:40px;\">";
            html += "                      <input type=\"hidden\" id=\"goods_imgpath_"+ ii +"\" value=\"\">";
            // html += "                      <li>";
            // html += "                          <div class=\"use_choice\">";
            html += "                            <label class=\"uc_container\"> 상품";
            html += "                              <input type=\"radio\" name=\"emd_type_"+ ii +"\" id=\"emd_type_"+ ii +"_N\" value=\"N\" onClick=\"type_chk('"+ ii +"','N');\" checked>";
            html += "                              <span class=\"checkmark\"></span>";
            html += "                            </label>";
            html += "                            <label class=\"uc_container\"> 꽝";
            html += "                              <input type=\"radio\" name=\"emd_type_"+ ii +"\" id=\"emd_type_"+ ii +"_K\" value=\"K\" onClick=\"type_chk('"+ ii +"','K');\">";
            html += "                              <span class=\"checkmark\"></span>";
            html += "                            </label>";
            html += "                            <label class=\"uc_container\"> 포인트";
            html += "                              <input type=\"radio\" name=\"emd_type_"+ ii +"\" id=\"emd_type_"+ ii +"_P\" value=\"P\" onClick=\"type_chk('"+ ii +"','P');\">";
            html += "                              <span class=\"checkmark\"></span>";
            html += "                            </label>";
            // html += "                            <label class=\"uc_container\"> 예치금";
            // html += "                              <input type=\"radio\" name=\"emd_type_"+ ii +"\" id=\"emd_type_"+ ii +"_D\" value=\"D\" onClick=\"type_chk('"+ ii +"','D');\">";
            // html += "                              <span class=\"checkmark\"></span>";
            // html += "                            </label>";
            // html += "                          </div>";
            // html += "                      </li>";
            html += "                    <ul>";

            html += "                      <li id=\"div_name_"+ii+"\"><span>상품명</span><input type=\"text\" id=\"goods_name_"+ ii +"\" value=\"상품"+iii+"\" onKeyup=\"chgNameData('"+ ii +"', this)\" placeholder=\"상품명\" class=\"int140\"></li>";
            html += "                      <li id=\"div_info_"+ii+"\"><span>설&nbsp;&nbsp;&nbsp;명</span><input type=\"text\" id=\"goods_info_"+ ii +"\" value=\"부가글"+iii+"\" onKeyup=\"chgSubNameData('"+ ii +"', this)\" placeholder=\"설명\" class=\"int140\"></li>";
            html += "                      <li id=\"div_cnt_"+ii+"\"><span>수&nbsp;&nbsp;&nbsp;량</span><input type=\"text\" id=\"goods_cnt_"+ ii +"\" value=\"10\" onKeyup=\"chgCntData('"+ ii +"', this)\" placeholder=\"수량\" class=\"int140\"></li>";
            html += "                      <li id=\"div_deposit_"+ii+"\" style=\"display:none;\"><span>예치금</span><input type=\"text\" id=\"goods_deposit_"+ ii +"\" value=\"0\" placeholder=\"행사가\" class=\"int140\"></li>";
            html += "					 <li id=\"div_barcode_"+ ii +"\">";
            html += "                          <span class=\"tit\">바코드</span>";
            html += "                          <div class=\"barcode\">";
            html += "                              <input type=\"hidden\" id=\"apply_barcode_"+ ii +"\" value=\""+ ii +"\">";
            html += "                              <select id=\"select_barcode_length_"+ ii +"\" onchange=\"changeBarcodeSelect(this, "+ ii +")\">";
            html += "                                  <option value=\"13\" selected=\"\">13자리</option>";
            html += "                                  <option value=\"8\">8자리</option>";
            html += "                              </select>";
            html += "                              <input type=\"text\" id=\"barcode_txt_"+ ii +"\" value=\"\" onkeyup=\"\" maxlength=13 style=\"width: calc(100% - 236px) !important;\">";
            html += "                              <button type=\"button\" class=\"btn_bar_ok\" onclick=\"showBarcode("+ ii +")\"> 미리보기</button>";
            html += "                          </div>";
            html += "                      </li>";
            html += "                    </ul>";

            // html += "					<div class=\"noselect_sel\">";
            // html += "						<input type=\"hidden\" id=\"goods_noselect_"+ ii +"\" name=\"hdn_goods_noselect\" value=\"N\">";
            // html += "						<label class=\"checkbox_container\">";
            // html += "							<input type=\"checkbox\" name=\"noselect_chk\" id=\"noselect_chk_"+ ii +"\" onClick=\"chgGoodsData('"+ ii +"', '', this)\" value=\"Y\" >";
            // html += "							<span class=\"checkmark\"></span>";
            // html += "						</label>";
            // html += "					</div>";
            // html += "					<span class=\"noselect_sel_text\">선택X</span>";
            //
            // html += "					<div class=\"vip_box\">";
            // html += "						<label class=\"checkbox_container\">";
            // html += "							<input type=\"hidden\" id=\"goods_vip_"+ ii +"\" value=\"N\">";
            // html += "							<input type=\"checkbox\" name=\"vip_chk\" id=\"vip_chk_"+ ii +"\" onClick=\"chgGoodsData('"+ ii +"', '', this)\" value=\"V\" >";
            // html += "							<span class=\"checkmark\"></span>";
            // html += "						</label>";
            // html += "			        </div>";
            // html += "					<span class=\"vip_text\">VIP</span>";

            html += "                  </dd>";
            html += "<ddd style=\"position: absolute;font-size: 20px;top: -3px;right: 11px;color: #d1d1d1;\">";
            html +=     "당첨  <span style=\"font-size:40px;\" id=\"pick_rate_"+ii+"\">15</span>%";
            html +="</ddd>";
            html += "                  <span class=\"move_btn_group\">";
            html += "                      <a class=\"move_last_left\" href=\"javascript:goods_move('first', '"+ ii +"');\" title=\"처음으로이동\"></a>";
            html += "                      <a class=\"move_left\" href=\"javascript:goods_move('left', '"+ ii +"');\" title=\"이전으로이동\"></a>";
            html += "                      <a class=\"move_right\" href=\"javascript:goods_move('right', '"+ ii +"');\" title=\"다음으로이동\"></a>";
            html += "                      <a class=\"move_last_right\" href=\"javascript:goods_move('last', '"+ ii +"');\" title=\"마지막으로이동\"></a>";
            html += "                      <a class=\"move_del\" href=\"javascript:area_del('"+ ii +"');\" title=\"삭제\"></a>";
            html += "                  </span>";
            html += "                </dl>";


            $("#area_goods_step0").append(html);
            iis++;
            ii++;
            $("#step0_cnt").val(iis);
            $("#step0_mx").val(ii);
            set_event();
        }else{
            showSnackbar("최대 8개의 상품까지 입력가능합니다.", 1500);
        }


    }

    function setting() {
        // var num = $(".num-box").val();

        var num = nameArr.length;
        var deg = 360 / num;
        //룰렛 갯수 한도 지정
        // if (num < 4 || num > 40) {
        //     $(".num-box").stop().animate({
        //         marginLeft: "-2px"
        //     }, 50).animate({
        //         marginLeft: "2px"
        //     }, 50).animate({
        //         marginLeft: "-2px"
        //     }, 50).animate({
        //         marginLeft: "2px"
        //     }, 50).animate({
        //         marginLeft: "0px"
        //     }, 25, caution()).focus();
        //     return false;
        // }
        //룰렛 정지
        actionStop(0);

        // $(".arrow").hide();
        // $(".smart_back > .cover").fadeOut(500);
        $(".smart_back > .wheel").removeClass("action");
        $(".smart_back > .wheel > div").remove();

        var minusnum = 0;
        if(num>4){
            minusnum = (num - 4) * 10 ;
        }

        var eve_top = 47;
        var eve_left = -9;
        var eve_width = 135;
        var eve_fontsize = 22;
        var eve_imgstyle = "width: 95px; height: 95px; left: 20px; bottom: 0; clip-path: polygon(0 0, 100% 0, 100% 70%, 70% 100%, 30% 100%, 0 70%)";

        if(num == 5){
            eve_top = 37;
            eve_left = -13;
            eve_width = 125;
            eve_fontsize = 20;
            eve_imgstyle = "width: 90px; height: 90px; left: 18px; bottom: 6px; clip-path: polygon(0 0, 100% 0, 100% 60%, 70% 100%, 30% 100%, 0 60%);";
        }else if(num == 6){
            eve_top = 33;
            eve_left = -21;
            eve_width = 125;
            eve_fontsize = 18;
            eve_imgstyle = "width: 85px; height: 85px; left: 20px; bottom: 12px; clip-path: polygon(0 0, 100% 0, 100% 50%, 71% 100%, 29% 100%, 0 50%);";
        }else if(num == 7){
            eve_top = 30;
            eve_left = -17;
            eve_width = 105;
            eve_fontsize = 16;
            eve_imgstyle = "width: 70px; height: 70px; left: 18px; bottom: 19px; clip-path: polygon(0 0, 100% 0, 100% 50%, 76% 100%, 24% 100%, 0 50%);";
        }else if(num == 8){
            eve_top = 30;
            eve_left = -19;
            eve_width = 100;
            eve_fontsize = 16;
            eve_imgstyle = "width: 66px; height: 66px; left: 17px; bottom: 25px; clip-path: polygon(0 0, 100% 0, 100% 42%, 76% 100%, 24% 100%, 0 42%);";
        }

        //받아온 num값에 따라 룰렛 선과 숫자를 뿌림
        for (var i = 0; i < num; i++) {
            $(".smart_back > .wheel").append("<div></div>");
            $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ")").css("transform", "rotate(" + (deg * i) + "deg)").append("<div id='reward_"+i+"' class='w_reward'></div>");
            // $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ") > div").attr( "id", "reward_"+i);

            var html = "";
            html += "<div style='width:"+ eve_width + "px"+";' class='reward_goods'>";
            html += "<div class='text_stroke'>";
            html += "<span id=\"eve_name_"+idArr[i]+"\" style='font-size:"+eve_fontsize+"px;font-weight:bold;'>"+nameArr[i]+"</span><br>";
            html += "<span id=\"eve_subname_"+idArr[i]+"\">"+sub_nameArr[i]+"</span>";
            html += "</div>";
            var imgurllink = urlurl[i];
            if(imgurllink==""){
                if(typeArr[i]=="N"){
                    imgurllink = "/images/eveview/img_gift_01.png";
                }
            }
            if(typeArr[i]=="D"||typeArr[i]=="P"){
                imgurllink = "/images/eveview/img_point_01.png";
            }else if(typeArr[i]=="K"){
                imgurllink = "/images/eveview/img_bomb_01.png";
            }
            html += "<img id=\"eve_img_"+idArr[i]+"\" src=\""+imgurllink+"\" style=\""+eve_imgstyle+"\">";
            html += "</div>";
            $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ") > div").html(html);

            //4 -> top 140   -> +60
            //자리수에 따라 간격조정
            if (i < 9) {
                // $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ") > div").css("left", (500 / num - 4) + "px");
            } else {
                // $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ") > div").css("left", (500 / num - 8) + "px");
            }
        }
        //숫자가 커지면 간격이 좁아지므로 숫자를 바깥으로 조정

        $(".smart_back > .wheel > div > div").css("top", eve_top + "px").css("left", eve_left + "px").css("transform", "rotate(" + (deg / 2) + "deg)");
    }
    //룰렛 돌리기
    function actionStart() {
        $(".smart_back > .wheel").addClass("action");
        $("#start_btn").hide();
        $("#stop_btn").show();
        $(".smart_back > .action").css("animation-duration", "0.3s").css("animation-play-state", "running");
    }
    //룰렛 정지
    function actionStop(num) {

        //set함수일경우에만 rand에 0을 받아 룰렛을 즉시멈추고 이외에는 넘겨받은 rand값에 따라 룰렛 정지 속도가 변경됨
        if (num == 0) {
            $(".btn-cover").hide();
            $(".smart_back > .action").css("animation-play-state", "paused");
        } else {

            setting();
            var mxnum = Number(num + 360);
            $.keyframe.define([{
                name: 'action25',
                   '0%':   {transform: 'rotate('+mxnum+'deg)'},
                   '100%': {transform: 'rotate('+num+'deg)'}
            }]);



            // animation.appendRule('0% {transform: rotate('+num+'deg);}');
            // animation.appendRule('100% {transform: rotate('+mxnum+'deg);}');
            $(".smart_back > .wheel").addClass("action25");
            $('.smart_back > .wheel').css({
                  transform: 'rotate('+num+'deg)'
                })

            $(".smart_back > .action25").css("animation-duration", "0.8s").css("animation-play-state", "running");
            $(".smart_back > .action25").stop().css("animation-duration", "0.8s").delay(2000).queue(function () {
                $(".smart_back > .action25").css("animation-play-state", "paused").dequeue();
            });

        }
    }
    //숫자 깜빡이기
    function caution() {
        $(".txt > span").delay(250).animate({
            opacity: "0"
        }, 50).animate({
            opacity: "1"
        }, 50).animate({
            opacity: "0"
        }, 50).animate({
            opacity: "1"
        }, 50).animate({
            opacity: "0"
        }, 50).animate({
            opacity: "1"
        }, 50);
    }

    var whole_cnt = 0;
    var wtype_cnt = 0;
    var ktype_cnt = 0;

    function set_event(){
        urlurl=[];
        nameArr=[];
        sub_nameArr=[];
        idArr=[];
        typeArr = [];
        whole_cnt = 0;

        $(".dl_step0").each(function(index, item){ //상품코너별 조회

            var id = $(item).prop('id');
            var splitArr = id.split("_");
            var dl_id = splitArr[1]; //스텝 ID
            // console.log(dl_id);
            var dl_type = $(":input:radio[name=emd_type_"+dl_id+"]:checked").val();

            var dl_name = $("#goods_name_"+dl_id).val();
            var dl_sub_name = $("#goods_info_"+dl_id).val();
            var dl_imgurl = $("#goods_imgpath_"+dl_id).val();
            var dl_cnt = $("#goods_cnt_"+dl_id).val();
            if(dl_type=="N"&&dl_imgurl==""){
                 $("#div_preview_"+dl_id).css("background-image", "url('/images/eveview/img_gift_01.png')");
            }else if(dl_type=="K"){
                 $("#div_preview_"+dl_id).css("background-image", "url('/images/eveview/img_bomb_01.png')");
            }else if(dl_type=="D"||dl_type=="P"){
                 $("#div_preview_"+dl_id).css("background-image", "url('/images/eveview/img_point_01.png')");
            }

            console.log("id : "+id+" / dl_id : "+ dl_id + " / dl_type : "+dl_type+ " / dl_name : "+dl_name + " / dl_imgurl : "+dl_imgurl);


            if(dl_type == "K"){
                dl_name = "꽝";
                dl_sub_name = "다음기회에";
            }else{
                whole_cnt = Number(whole_cnt) + Number(dl_cnt);
            }
            idArr.push(dl_id);
            urlurl.push(dl_imgurl);
            nameArr.push(dl_name);
            sub_nameArr.push(dl_sub_name);
            typeArr.push(dl_type);

             //상품코너별 ID
            // console.log(sort_dl_id);
            // if(id == sort_dl_id) chk = no;
            // arr[no] = sort_dl_id;
            // no++;
        });

        setting();
        wtype_cnt = typeArr.length;

        ktype_cnt = typeArr.filter(element => 'K' === element).length;

        var k_rate = 0;

        if(ktype_cnt>0){
            k_rate = 25;
            if(wtype_cnt=="4"){
                k_rate = 30;
            }
        }


        if(k_rate>0){
            whole_cnt = Number(whole_cnt) + Number(k_rate);
        }

        if(whole_cnt>0){
            $(".dl_step0").each(function(index, item){ //상품코너별 조회

                var id = $(item).prop('id');
                var splitArr = id.split("_");
                var dl_id = splitArr[1]; //스텝 ID
                // console.log(dl_id);
                var dl_type = $(":input:radio[name=emd_type_"+dl_id+"]:checked").val();


                var dl_cnt = $("#goods_cnt_"+dl_id).val();

                var ratenum = 0;
                if(dl_type == "K"){
                    ratenum = k_rate;
                }else if(dl_cnt>0){
                    // ratenum=Math.round((dl_cnt / whole_cnt)*100);
                    ratenum=(dl_cnt / whole_cnt)*100;
                    var rateremain = dl_cnt % whole_cnt;
                    if(rateremain>0){
                        ratenum=ratenum.toFixed(1);
                    }
                }

                $("#pick_rate_"+dl_id).text(ratenum);

            });

        }
    }





	//상품 영역 추가
	// function goods_append(step, id, flag, first="N"){
	// 	//step : 1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형
	// 	var no = $("#"+ id +"_cnt").val(); //상품 등록 상품수
	// 	$("#"+ id +"_cnt").val(Number(no)+1); //상품 등록 상품수
	// 	// alert("step : "+ step +", id : "+ id +", "+ id +"_cnt : "+ no);
	// 	var emd_order_yn = $(":input:radio[name=emd_order_yn]:checked").val(); //스마트전단 주문하기 여부
	// 	//alert("step : "+ step +", id : "+ id +", emd_order_yn : "+ emd_order_yn);
	// 	var stmall_html = "";
	// 	if(emd_order_yn == "Y"){ //주문하기 사용의 경우
	// 		stmall_html = "<button class='icon_add_cart'>장바구니</button>";
	// 	}else{
	// 		stmall_html = "<button class='icon_add_cart' style='display:none;'>장바구니</button>";
	// 	}
	// 	var html = 	'';
	// 	if(step == "3"){ //3.1단 텍스트형
	// 		html += '<li id="'+ id +'_'+ no +'" class="dl_step'+ step +'">';
	// 		html += '  <span class="txt_name"><input type="text" id="goods_name_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="상품명" class="int100_l"></span>';
	// 		html += '  <span class="txt_option"><input type="text" id="goods_option_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="규격" class="int100_l"></span>';
	// 		html += '  <span class="txt_price1"><input type="text" id="goods_price_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="4,000" class="int100_r"></span>';
    //         html += '  <span class="txt_price3"><input type="text" id="goods_evprice_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="3,500" class="int100_r"></span>';
	// 		html += '  <span class="txt_price2"><input type="text" id="goods_dcprice_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="3,000" class="int100_r"></span>';
    //         html += '  <span class="txt_option2"><input type="text" id="goods_option2_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션1" class="int100_l"></span>';
	// 		html += '  <span class="txt_option3"><input type="text" id="goods_option3_'+ id +'_'+ no +'" value="" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션2" class="int100_l"></span>';
    //
	// 		// html += '  <span class="txt_btn"><a class="move_del2" style="cursor:pointer;" onClick="area_del(\''+ id +'\', \''+ no +'\');" title="삭제"></a></span>';
	// 		html += '  <input type="hidden" id="goods_id_'+ id +'_'+ no +'" value="">';
	// 		html += '  <input type="hidden" id="goods_step_'+ id +'_'+ no +'" value="3" style="width:30px;">';
	// 		html += '  <input type="hidden" id="goods_div_'+ id +'_'+ no +'" value="'+ id +'_'+ no +'" style="width:80px;">';
	// 		html += '  <input type="hidden" id="goods_seq_'+ id +'_'+ no +'" value="'+ no +'" style="width:40px;">';
	// 		html += '  <input type="hidden" id="goods_option_'+ id +'_'+ no +'" value="">';
	// 		html += '  <input type="hidden" id="goods_option2_'+ id +'_'+ no +'" value="">';
    //         html += '  <input type="hidden" id="goods_option3_'+ id +'_'+ no +'" value="">';
	// 		// 2021-06-01 goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
	// 		html += '  <input type="hidden" id="goods_barcode_'+ id +'_'+ no +'" value="">';
	// 		html += '</li>';
	// 	}else{ //1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형
	// 		var goods_price = "4,000"; //정상가
	// 		var goods_dcprice = "3,000"; //할인가
	// 		var badge_nm = "행사뱃지 추가"; //행사뱃지 명칭
	// 		var badge_no = "0"; //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
	// 		if(step == "1"&&first=="Y"){ //할인&대표상품
	// 			goods_price = "4,000"; //정상가
	// 			goods_dcprice = "3,000"; //할인가
	// 			badge_nm = "행사뱃지 선택"; //행사뱃지 명칭
	// 			badge_no = "1"; //행사뱃지(0.표기안함, 1.할인율, x.뱃지번호)
	// 		}
	// 		html += '<dl id="'+ id +'_'+ no +'" class="dl_step'+ step +'">';
	// 		html += '    <dt>';
	// 		html += '      <div class="templet_img_in" onclick="showImg(\''+ id +'_'+ no +'\')">';
	// 		html += '        <div id="div_preview_'+ id +'_'+ no +'">';
	// 		html += '          <img id="img_preview_'+ id +'_'+ no +'" style="display:none;width:100%">';
	// 		if(step == "1"&&first=="Y"){ //할인&대표상품
	// 			html += '          <div id="div_badge_'+ id +'_'+ no +'" class="sale_badge" style="display:;">25<span>%</span></div>';
	// 		}else{
	// 			html += '          <div id="div_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
	// 		}
	// 		html += '        </div>';
	// 		html += '      </div>';
	// 		if(step == "1" || step == "2"){ //1.할인&대표상품, 2.2단 이미지형
	// 			html += '      <button class="btn_good_badge" type="button" id="badgeBtn"onclick="modal_badge_open(\''+ step +'\', \''+ id +'_'+ no +'\')">'+ badge_nm +'</button>'; //행사뱃지 선택
	// 		}
	// 		html += '    </dt>';
	// 		html += '    <dd>';
	// 		html += '      <ul>';
	// 		html += '          <input type="hidden" id="goods_id_'+ id +'_'+ no +'">';
	// 		html += '          <input type="hidden" id="goods_step_'+ id +'_'+ no +'" value="'+ step +'" style="width:60px;">';
	// 		html += '          <input type="hidden" id="goods_div_'+ id +'_'+ no +'" value="'+ id +'_'+ no +'" style="width:80px;">';
	// 		html += '          <input type="hidden" id="goods_seq_'+ id +'_'+ no +'" value="'+ no +'" style="width:42px;">';
	// 		html += '          <input type="hidden" id="goods_imgpath_'+ id +'_'+ no +'">';
	// 		html += '          <input type="hidden" id="goods_badge_'+ id +'_'+ no +'" value="'+ badge_no +'">';
	// 		html += '          <input type="hidden" id="badge_imgpath_'+ id +'_'+ no +'" value="">';
	// 		html += '          <li><span>상품명</span> <input type="text" id="goods_name_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="상품명" class="int140"></li>';
	// 		html += '          <li><span>규&nbsp;&nbsp;&nbsp;격</span> <input type="text" id="goods_option_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder=" 규격" class="int140"></li>';
	// 		html += '          <li><span>정상가</span> <input type="text" id="goods_price_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="'+ goods_price +'" class="int140"></li>';
    //         html += '          <li><span>행사가</span> <input type="text" id="goods_evprice_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="'+ goods_price +'" class="int140"></li>';
	// 		html += '          <li><span>할인가</span> <input type="text" id="goods_dcprice_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="'+ goods_dcprice +'" class="int140"></li>';
	// 		if(step != "5"){ //1.할인&대표상품, 2.2단 이미지형
	// 			html += '          <li><span>옵션1</span> <input type="text" id="goods_option2_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션1" class="int140"></li>';
    //             html += '          <li><span>옵션2</span> <input type="text" id="goods_option3_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션2" class="int140"></li>';
	// 		}else{
    //             html += '          <li style="display:none;"><span>옵션1</span> <input type="text" id="goods_option2_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션1" class="int140"></li>';
    //             html += '          <li style="display:none;"><span>옵션2</span> <input type="text" id="goods_option3_'+ id +'_'+ no +'" onClick="chkGoodsData(\''+ id +'_'+ no +'\', \'\', this)" onKeyup="chgGoodsData(\''+ id +'_'+ no +'\', \'\', this)" placeholder="옵션2" class="int140"></li>';
	// 			//html += '          <input type="hidden" id="goods_option2_'+ id +'_'+ no +'" value="">';
	// 		}
	// 		// 2021-06-01 goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
	// 		html += '  			   <input type="hidden" id="goods_barcode_'+ id +'_'+ no +'" value="">';
	// 		html += '      </ul>';
	// 		// html += '      <label class="checkbox_container pop_input">';
	// 		// html += '        <input type="checkbox" name="pop_chk" onClick="popChk(this);" id="pop_chk'+ no +'" value="'+ id +'_'+ no +'">';
	// 		// html += '        <span class="checkmark"></span>';
	// 		// html += '      </label>';
	// 		// html += '      <button id="myBtn" class="pop_modal_btn" onClick="popPrint(\''+ id +'_'+ no +'\')">POP 인쇄</button>';
	// 		html += '    </dd>';
	// 		html += '    <span class="move_btn_group">';
	// 		html += '       <a class="move_last_left" href="javascript:goods_move(\'first\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="처음으로이동"></a>';
	// 		html += '       <a class="move_left" href="javascript:goods_move(\'left\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="왼쪽으로이동"></a>';
	// 		html += '       <a class="move_right" href="javascript:goods_move(\'right\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="오른쪽으로이동"></a>';
	// 		html += '       <a class="move_last_right" href="javascript:goods_move(\'last\', \''+ id +'\', \''+ id +'_'+ no +'\');" title="마지막으로이동"></a>';
	// 		html += '       <a class="move_del" href="javascript:area_del(\''+ id +'\', \''+ no +'\');" title="삭제"></a>';
	// 		html += '    </span>';
	// 		html += '</dl>';
	// 	}
	// 	$('#area_goods_'+ id).append(html);
    //
	// 	//미리보기 영역
	// 	var html = 	'';
    //     if(step == "1"&&first=="N"){ //2단 이미지형
	// 		html += '<div class="pop_list00" id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		html += stmall_html;
	// 		html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
	// 		html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
	// 		html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap1"><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
	// 		html += '  <div class="pop_name">';
	// 		html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
	// 		html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
	// 		html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
	// 		html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
	// 		// 2021-06-01 미리보기 바코드 항목 추가
	// 		html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap2"><span id="pre_goods_option3_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_price">';
	// 		html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000</p>'; //정상가
    //         html += '    <p class="price3" id="pre_goods_evprice_'+ id +'_'+ no +'">3,500</p>'; //정상가
	// 		html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000</p>'; //할인가
	// 		html += '  </div>';
	// 		html += '</div>';
	// 	}else if(step == "2"){ //2단 이미지형
	// 		html += '<div class="pop_list01" id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		html += stmall_html;
	// 		html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
	// 		html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
	// 		html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap1"><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_name">';
	// 		html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
	// 		html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
	// 		html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
	// 		html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
	// 		// 2021-06-01 미리보기 바코드 항목 추가
	// 		html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
	// 		html += '  </div>';
    //
    //         html += '  <div class="pop_option_wrap2"><span id="pre_goods_option3_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //
    //         html += '  <div class="pop_price">';
	// 		html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000</p>'; //정상가
    //         html += '    <p class="price3" id="pre_goods_evprice_'+ id +'_'+ no +'">3,500</p>'; //정상가
	// 		html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000</p>'; //할인가
	// 		html += '  </div>';
	// 		html += '</div>';
	// 	}else if(step == "4"){ //3단 이미지형
	// 		html += '<div class="pop_list03" id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		html += stmall_html;
	// 		html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
	// 		html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
	// 		html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap1"><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_name">';
	// 		html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
	// 		html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
	// 		html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
	// 		html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
	// 		// 2021-06-01 미리보기 바코드 항목 추가
	// 		html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap2"><span id="pre_goods_option3_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_price">';
	// 		html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000</p>'; //정상가
    //         html += '    <p class="price3" id="pre_goods_evprice_'+ id +'_'+ no +'">3,500</p>'; //정상가
	// 		html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000</p>'; //할인가
	// 		html += '  </div>';
    //
	// 		html += '</div>';
	// 	}else if(step == "5"){ //4단 이미지형
	// 		html += '<div class="pop_list04" id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		html += stmall_html;
	// 		html += '  <div id="pre_goods_badge_'+ id +'_'+ no +'" style="display:none;"></div>';
	// 		html += '  <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
	// 		html += '    <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap1"><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_name">';
	// 		html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
	// 		html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
	// 		html += '    <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
	// 		html += '    <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
	// 		// 2021-06-01 미리보기 바코드 항목 추가
	// 		html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap2"><span id="pre_goods_option3_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_price">';
	// 		html += '    <p class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000</p>'; //정상가
    //         html += '    <p class="price3" id="pre_goods_evprice_'+ id +'_'+ no +'">3,500</p>'; //정상가
	// 		html += '    <p class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000</p>'; //할인가
	// 		html += '  </div>';
	// 		html += '</div>';
	// 	}else if(step == "3"){ //1단 텍스트형
	// 		if(emd_order_yn == "Y"){ //주문하기 사용의 경우
	// 			html += '<div class="pop_list02 cartplus_t" id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		}else{
	// 			html += '<div class="pop_list02 cartplus" id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		}
	// 		html += '  <div class="name">';
	// 		html += '    <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
	// 		html += '    <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
	// 		// 2021-06-01 미리보기 바코드 항목 추가
	// 		html += '    <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
    //
	// 		html += '  </div>';
    //         html += '  <div class="pop_option_wrap1"><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
    //         html += '  <div class="pop_option_wrap2"><span id="pre_goods_option3_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></div>'; //옵션
	// 		html += '  <span class="price1" id="pre_goods_price_'+ id +'_'+ no +'">4,000</span>'; //정상가
    //         html += '  <span class="price3" id="pre_goods_evprice_'+ id +'_'+ no +'">4,000</span>'; //정상가
	// 		html += '  <span class="price2" id="pre_goods_dcprice_'+ id +'_'+ no +'">3,000</span>'; //할인가
	// 		html += stmall_html;
	// 		html += '  <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">0</span>'; //행사뱃지
	// 		html += '  <span id="pre_goods_option2_'+ id +'_'+ no +'" style="display:none;"></div>'; //옵션
	// 		html += '</div>';
	// 	}else{ //할인&대표상품
	// 		html += '<dl id="pre_'+ id +'_'+ no +'" onclick=\"$(\'#'+id +'_'+ no+'\').attr(\'tabindex\', -1).focus();\">';
	// 		html += stmall_html;
	// 		html += '  <dt>';
	// 		html += '    <div id="pre_goods_badge_'+ id +'_'+ no +'" class="sale_badge" style="display:;">25<span>%</span></div>';
	// 		html += '    <div id="pre_div_preview_'+ id +'_'+ no +'" class="templet_img_in3" style="background-image : url(\'<?=$sample_imgpath?>\');">';
	// 		html += '      <img id="pre_img_preview_'+ id +'_'+ no +'" style="display:none;">'; //상품 이미지
	// 		html += '    </div>';
	// 		html += '  </dt>';
	// 		html += '  <dd>';
	// 		html += '    <ul>';
    //         html += '        <li class="pop_option_wrap1"><span id="pre_goods_option2_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></li>'; //옵션
    //         html += '        <li class="name">';
	// 		html += '          <span id="pre_goods_name_'+ id +'_'+ no +'">상품명</span>'; //상품명
	// 		html += '          <span id="pre_goods_option_'+ id +'_'+ no +'">규격</span>'; //규격
	// 		html += '          <span id="pre_goods_badge_cd_'+ id +'_'+ no +'" style="display:none;">1</span>'; //행사뱃지
	// 		html += '          <span id="pre_goods_imgpath_'+ id +'_'+ no +'" style="display:none;"></span>'; //이미지경로
	// 		// 2021-06-01 미리보기 바코드 항목 추가
	// 		html += '    	   <div id="pre_goods_barcode_'+ id +'_'+ no +'" style="display:none;"></div>'; //바코드 상품코너
	// 		html += '        </li>';
	// 		html += '        <li id="pre_goods_price_'+ id +'_'+ no +'" class="price1">4,000</li>'; //정상가
    //         html += '        <li id="pre_goods_evprice_'+ id +'_'+ no +'" class="price3">3,500</li>'; //정상가
    //         html += '        <li class="pop_option_wrap2"><span id="pre_goods_option3_'+ id +'_'+ no +'" class="pop_option" style="display:none;"></span></li>'; //옵션
	// 		html += '        <li id="pre_goods_dcprice_'+ id +'_'+ no +'" class="price2">3,000</li>'; //할인가
    //
    //
    //
	// 		html += '    </ul>';
	// 		html += '  </dd>';
	// 		html += '</dl>';
	// 	}
	// 	$('#pre_area_goods_'+ id).append(html);
    //     if(step == "2"){ //2단 이미지형
    //         var tem_type = $(":input:radio[name=emd_tem_type]:checked").val();
    //         if(tem_type=="E"){
    //             chg_tem_type_E(tem_type);
    //         }
    //     }
	// 	if(flag == "new"){
	// 		$("#pre_"+ id +'_'+ no).attr("tabindex", -1).focus(); //미리보기 포커스
	// 	}
	// }

    //상품 삭제
	function area_del(no){
		//alert("step : "+ step +", no : "+ no); return;
		var id = "step0_"+ no;

		var cnt = Number($("#step0_cnt").val()); //상품수

        if(cnt>4){
            $("#step0_cnt").val(cnt-1); //상품수
            $('#'+id).remove();
            set_event();
        }else{
            showSnackbar("최소 4개 이상의 상품을 입력하셔야합니다.", 1500);
        }


		//alert("step : "+ step +", no : "+ no +", id : "+ id +", cnt : "+ cnt +", nxno : "+ nxno +", stepId : "+ stepId); return;


        // $('#pre_'+id).remove();
		//alert("stepId : "+ stepId +", cnt : "+ cnt);
		//if(cnt == "1") $("input:radio[name='"+ stepId +"_yn']:radio[value='N']").prop('checked', true); //모두 삭제시 사용안함 선택하기


	}



	//상품 삭제
	// function area_del(step, no, first="N"){
	// 	//alert("step : "+ step +", no : "+ no); return;
	// 	var id = step +"_"+ no;
	// 	var stepNo = step.substring(4, 5); //스텝번호
	// 	var cnt = Number($("#"+ step +"_cnt").val()); //상품수
	// 	var nxno = Number(no)+1; //다음 상품번호
	// 	var stepId = id.substring(0, 5); //스텝 ID
	// 	$("#"+ step +"_cnt").val(cnt-1); //상품수
	// 	var hdn_emd_order_yn = $('#hdn_emd_order_yn').val();
	// 	//alert("step : "+ step +", no : "+ no +", id : "+ id +", cnt : "+ cnt +", nxno : "+ nxno +", stepId : "+ stepId); return;
    //
    //     $('#'+id).remove();
    //     $('#pre_'+id).remove();
	// 	//alert("stepId : "+ stepId +", cnt : "+ cnt);
	// 	//if(cnt == "1") $("input:radio[name='"+ stepId +"_yn']:radio[value='N']").prop('checked', true); //모두 삭제시 사용안함 선택하기
	// 	if(stepId == "step1" && cnt == "1"&&first=="Y"){ //STEP1
	// 		goods_yn(stepId, "none"); //모두 삭제시 사용안함 처리
	// 		$("input:radio[name='"+ stepId +"_yn']:radio[value='N']").prop('checked', true); //모두 삭제시 사용안함 선택하기
	// 	}
    //     if(first!="Y"){
    //         var cno_num = $("#goods_"+step+"_cno").val();
    //         // if(cno_num == "2"){ //2단 이미지형
    //             var tem_type = $(":input:radio[name=emd_tem_type]:checked").val();
    //             if(tem_type=="E"){
    //                 chg_tem_type_E(tem_type, step, cno_num);
    //             }
    //         // }
    // 		//alert("cnt : "+ $("#"+ step +"_cnt").val());
    //     }
    //
	// }

    //상품이동
	function goods_move(type, id){

		//alert("type : "+ type +", step : "+ step +", id : "+ id +", stepNo : "+ stepNo); return;
		var i = 0;

		var cnt = Number($("#step0_cnt").val()); //전체수
		//alert("seq : "+ seq +", cnt : "+ cnt); return;
        id = "step0_"+id;
		//alert("type : "+ type +", step : "+ step +", id : "+ id +", no : "+ no); return;
        if(type == "first" || type == "last"){ //first.처음으로, last.마지막으로
            if(type == "first"){ //first.처음으로
                $("#"+ id).prependTo("#area_goods_step0");
                // $("#"+id).prependTo("#box_area");
                // $("#pre_"+id).prependTo("#pre_area_goods_"+step);

            }
            if(type == "last"){ //last.마지막으로
                // html += "<li id='modal_sort_li_"+ id +"'>"+ $("#modal_sort_li_"+ id).html() +"</li>";
                $("#"+id).appendTo("#area_goods_step0");
                // $("#"+id).appendTo("#box_area");
                // $("#pre_"+id).appendTo("#pre_area_goods_"+step);
            }
        }else{
            var no = 0;
            var chk = -1;
            var arr = new Array();


            $("#area_goods_step0").children('dl').each(function(index, item){ //상품코너별 조회
                var sort_dl_id = $(item).prop('id'); //상품코너별 ID
                console.log(sort_dl_id);
                if(id == sort_dl_id) chk = no;
                arr[no] = sort_dl_id;
                no++;
            });
            if(chk > -1){
                //alert("chk : "+ chk +", arr[chk-1] : "+ arr[chk-1] +", arr[chk] : "+ arr[chk]);
                if(type == "left" && chk > 0){
                    // $("#modal_sort_li_"+ arr[chk-1]).insertAfter("#modal_sort_li_"+ arr[chk]);
                    $("#"+ arr[chk-1]).insertAfter("#"+ arr[chk]);
                    // $("#pre_"+ arr[chk-1]).insertAfter("#pre_"+ arr[chk]);
                }else if(type == "right" && chk < (cnt-1)){
                    // $("#modal_sort_li_"+ arr[chk]).insertAfter("#modal_sort_li_"+ arr[chk+1]);
                    $("#"+ arr[chk]).insertAfter("#"+ arr[chk+1]);
                    // $("#pre_"+ arr[chk]).insertAfter("#pre_"+ arr[chk+1]);
                }
            }
        }

        set_event();

	}

	//상품이동
	// function goods_move(type, step, id){
	// 	var stepNo = step.substring(4, 5); //스텝번호
	// 	//alert("type : "+ type +", step : "+ step +", id : "+ id +", stepNo : "+ stepNo); return;
	// 	var i = 0;
	// 	var seq = Number($("#goods_seq_"+ id).val()); //정렬번호
	// 	var cnt = Number($("#"+ step +"_cnt").val()); //전체수
	// 	//alert("seq : "+ seq +", cnt : "+ cnt); return;
	// 	var no = 0;
	// 	var no1 = 0;
	// 	var no2 = 0;
	// 	var id1 = "";
	// 	var id2 = "";
	// 	var hdn_emd_order_yn = $('#hdn_emd_order_yn').val();
	// 	//alert("type : "+ type +", step : "+ step +", id : "+ id +", no : "+ no); return;
    //     if(type == "first" || type == "last"){ //first.처음으로, last.마지막으로
    //         if(type == "first"){ //first.처음으로
    //             $("#"+ id).prependTo("#area_goods_"+step);
    //             // $("#"+id).prependTo("#box_area");
    //             // $("#pre_"+id).prependTo("#pre_area_goods_"+step);
    //
    //         }
    //         if(type == "last"){ //last.마지막으로
    //             // html += "<li id='modal_sort_li_"+ id +"'>"+ $("#modal_sort_li_"+ id).html() +"</li>";
    //             $("#"+id).appendTo("#area_goods_"+step);
    //             // $("#"+id).appendTo("#box_area");
    //             // $("#pre_"+id).appendTo("#pre_area_goods_"+step);
    //         }
    //     }else{
    //         var no = 0;
    //         var chk = -1;
    //         var arr = new Array();
    //         var eachtag = "dl";
    //
    //         $("#area_goods_"+step).children(eachtag).each(function(index, item){ //상품코너별 조회
    //             var sort_dl_id = $(item).prop('id'); //상품코너별 ID
    //             if(id == sort_dl_id) chk = no;
    //             arr[no] = sort_dl_id;
    //             no++;
    //         });
    //         if(chk > -1){
    //             //alert("chk : "+ chk +", arr[chk-1] : "+ arr[chk-1] +", arr[chk] : "+ arr[chk]);
    //             if(type == "left" && chk > 0){
    //                 // $("#modal_sort_li_"+ arr[chk-1]).insertAfter("#modal_sort_li_"+ arr[chk]);
    //                 $("#"+ arr[chk-1]).insertAfter("#"+ arr[chk]);
    //                 // $("#pre_"+ arr[chk-1]).insertAfter("#pre_"+ arr[chk]);
    //             }else if(type == "right" && chk < (cnt-1)){
    //                 // $("#modal_sort_li_"+ arr[chk]).insertAfter("#modal_sort_li_"+ arr[chk+1]);
    //                 $("#"+ arr[chk]).insertAfter("#"+ arr[chk+1]);
    //                 // $("#pre_"+ arr[chk]).insertAfter("#pre_"+ arr[chk+1]);
    //             }
    //         }
    //     }
    //
	// }



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


	// //주문하기 사용여부 클릴시
	// function order_chk(dis){
	// 	div_view('emd_order_option', dis); //날짜 입력 영역
	// 	//alert("dis : "+ dis);
	// 	if(dis == "none"){ //사용안함의 경우
	// 		$(".icon_add_cart").hide(); //장바구니 이미지 Class 제거
	// 		$(".pop_list02").removeClass("cartplus_t"); //텍스트형 행사코너 장바구니 Class 영역 제거
	// 		$(".pop_list02").addClass("cartplus"); //텍스트형 행사코너 장바구니 Class 영역 추가
	// 		$('#hdn_emd_order_yn').val('N');
	// 	}else{ //사용함의 경우
	// 		$("button[name='yesstock']").show();//재고유무에 따라 버튼 선택적으로 보여주기
	// 		//$(".icon_add_cart").show(); //장바구니 이미지 Class 추가
	// 		$(".pop_list02").removeClass("cartplus"); //텍스트형 행사코너 장바구니 Class 영역 제거
	// 		$(".pop_list02").addClass("cartplus_t"); //텍스트형 행사코너 장바구니 Class 영역 추가
	// 		$('#hdn_emd_order_yn').val('Y');
	// 	}
	// }

    //

    function tem_chk(flag){
        if(flag==""){
            showSnackbar("잘못된 정보가 입력되었습니다.", 1500);
            // showSnackbar("저장 되었습니다.", 1500);
            return;
        }else{
            $(".eve_container").removeClass("roulette_"+tem_type);
            // $("#tem_type").val(flag);
            tem_type = flag;
        }

        $(".eve_container").addClass("roulette_"+flag);

    }






	//주문하기 시작일자 클리시
	$("#picker_open_sdt").datepicker({
		format: "yyyy.mm.dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		// startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});
	$("#emd_open_sdt").change(function(){
		var open_dt = $("#emd_open_sdt").val().replace(/[^0-9]/g,'');
		//alert("emd_order_sdt : "+ emd_order_sdt);
		var rtn_sdt = open_dt.substring(0, 4) +"."+ open_dt.substring(4, 6) +"."+ open_dt.substring(6, 8);
		$("#span_open_sdt").html(rtn_sdt);
        sdate = rtn_sdt;
        hansdate = open_dt.substring(0, 4) +"년 "+ open_dt.substring(4, 6) +"월 "+ open_dt.substring(6, 8)+"일";
        pre_date_info();
	});

    function pre_date_info(){

        $("#pre_date").text(sdate+" ~ "+edate);
        $("#bottomdate").text(hansdate+" ~ "+hanedate);
    }

	//주문하기 시작일자 클리시
	$("#picker_open_edt").datepicker({
		format: "yyyy.mm.dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});
	$("#emd_open_edt").change(function(){
		var open_dt = $("#emd_open_edt").val().replace(/[^0-9]/g,'');
		//alert("emd_order_sdt : "+ emd_order_sdt);
		var rtn_sdt = open_dt.substring(0, 4) +"."+ open_dt.substring(4, 6) +"."+ open_dt.substring(6, 8);
		$("#span_open_edt").html(rtn_sdt);
        edate = open_dt.substring(4, 6) +"."+ open_dt.substring(6, 8);
        hanedate = open_dt.substring(0, 4) +"년 "+ open_dt.substring(4, 6) +"월 "+ open_dt.substring(6, 8)+"일";
        pre_date_info();
	});
</script>
<?
	if($emd_id == ""){ //신규등록의 경우
		echo "<script>\n";
		// if($emd_step1_yn != "N"){ //신규등록 && STEP1 사용함의 경우
			if($step1_org < $reward_std){ //할인&대표상품 등록 등록수가 기본 갯수보다 작은 경우
				for($jj = $step1_org; $jj < $reward_std; $jj++){
					echo "  goods_append('new'); ";
				}
			}
            if(!empty($this->input->get('goods'))){
                echo "  $('#goods_name_0').val('농협 5천원권'); ";
                echo "  $('#goods_info_0').val('".$this->input->get('goods')."'); ";
                echo "  $('#goods_imgpath_0').val('/images/eveview/G00001690972.jpg'); ";
                echo "  $('#div_preview_0').css(\"background-image\", \"url('/images/eveview/G00001690972.jpg')\");";
                // echo "  $('#div_preview_0').css('background-image: url(\'/images/eveview/G00001690972.jpg\')'); ";
                // echo "  console.log('".$this->input->get('goods')."'); ";
            }
            echo "set_event();";
        // if(!empty($this->input->get('goods'))){
        // echo "  $('#div_preview_0').css(\"background-image\", \"url('/images/eveview/G00001690972.jpg')\");";
        // }

		// }

		echo "</script>\n";
	}else{
        echo "<script>\n";
        echo "set_event();";
        echo "</script>\n";
		// echo "<script>$('#dh_myBtn').attr('tabindex', -1).focus(); //미리보기 포커스</script>";
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
