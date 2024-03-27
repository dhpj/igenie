<?
	$goods_cnt = 1; //상품수
	$data_tit_img = "N"; //POP타이틀 이미지 여부
	$data_img_yn = "Y"; //이미지 사용여부
	$data_option_yn = "N"; //옵션명 사용여부
	$data_org_price_yn = "N"; //정상가 사용여부
	$data_pop_name = "POP제목"; //POP제목
	$base_goods_tit = ""; //기본값 POP제목
	$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
	$base_goods_option = ""; //기본값 옵션명
	$base_goods_org_price = ""; //기본값 정상가
	$base_goods_price = "9,900원"; //기본값 할인가
	if($type == "1_01"){
		$base_goods_tit = "전단상품"; //기본값 POP제목
	}else if($type == "1_02"){
		$goods_cnt = 2; //상품수
		$base_goods_tit = "타임세일"; //기본값 POP제목
	}else if($type == "1_03"){
		$goods_cnt = 3; //상품수
		$base_goods_tit = "오늘의핫딜"; //기본값 POP제목
	}else if($type == "1_04"){
		$goods_cnt = 4; //상품수
		$base_goods_tit = "슈퍼세일"; //기본값 POP제목
	}else if($type == "1_05"){
		$goods_cnt = 5; //상품수
		$base_goods_tit = "행사특가"; //기본값 POP제목
	}else if($type == "2_01"){
		$base_goods_tit = "슈/퍼/특/가"; //기본값 POP제목
	}else if($type == "2_02"){
		$goods_cnt = 2; //상품수
		$base_goods_tit = "이런 가격은 처음!"; //기본값 POP제목
	}else if($type == "2_03"){
		$goods_cnt = 3; //상품수
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$base_goods_tit = "특가세일"; //기본값 POP제목
	}else if($type == "2_04"){
		$goods_cnt = 4; //상품수
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$base_goods_tit = "수량한정세일"; //기본값 POP제목
	}else if($type == "3_01"){
		$data_img_yn = "N"; //이미지 사용여부
		$base_goods_tit = "행사상품"; //기본값 POP제목
		$base_goods_name = "딸기 한소쿠리(500g)~"; //기본값 상품명
		$base_goods_price = "9,900~19,900"; //기본값 할인가
	}else if($type == "3_02"){
		$data_img_yn = "N"; //이미지 사용여부
		$base_goods_tit = "오늘 딱 하루!"; //기본값 POP제목
		$base_goods_price = "9,900"; //기본값 할인가
	}else if($type == "3_03"){
		$data_img_yn = "N"; //이미지 사용여부
		$data_pop_name = "할인율"; //POP제목
		$base_goods_tit = "50"; //기본값 POP제목
		$base_goods_price = "9,900"; //기본값 할인가
	}else if($type == "3_04"){
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$data_img_yn = "N"; //이미지 사용여부
		$data_option_yn = "Y"; //옵션명 사용여부
		$data_org_price_yn = "Y"; //정상가 사용여부
		$base_goods_tit = "행사상품"; //기본값 POP제목
		$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
		$base_goods_org_price = "15,000"; //기본값 정상가
		$base_goods_price = "9,900"; //기본값 할인가
	}

	//스마트POP 기준정보
	$data_id = $data->ppd_id; //POP번호
	$data_md = "I"; //모드(I.추가, M.수정)
	if($data_id != ""){ //수정의 경우
		$data_md = "M"; //모드(I.추가, M.수정)
		$ppd_type = $data->ppd_type; //타입
		$ppd_title = $data->ppd_title; //POP제목
		$ppd_imgpath = $data->ppd_imgpath; //이미지경로
	}else{
		$ppd_title = $base_goods_tit; //POP제목
	}

	$goods_data = array();

	//스마트POP 상품정보 목록
	if(!empty($goods)){
		$ii = 1;
		foreach($goods as $r) {
			$goods_data[$ii]["imgpath"] = $r->ppg_imgpath; //이미지경로
			$goods_data[$ii]["name"] = $r->ppg_name; //상품명
			$goods_data[$ii]["option"] = $r->ppg_option; //옵션명
			$goods_data[$ii]["org_price"] = $r->ppg_org_price; //정상가
			$goods_data[$ii]["price"] = $r->ppg_price; //할인가
			//echo "goods_data[". $ii ."]['name'] : ". $goods_data[$ii]['name'] ."<br>";
			$ii++;
		}
	}

	//for($ii = 1; $ii <= $goods_cnt; $ii++){
		//echo "goods_data[". $ii ."]['name'] : ". $goods_data[$ii]['name'] ."<br>";
	//}

	/*
	$data_id = $data->ppd_id; //이미지경로
	$imgpath = $data->ppd_imgpath; //이미지경로
	$goods_name = $data->goods_name; //상품명
	$goods_option = $data->goods_option; //옵션명
	$goods_org_price = $data->goods_org_price; //정상가
	$goods_price = $data->goods_price; //할인가
	*/
?>
<input type="hidden" id="data_tit_img" value="<?=$data_tit_img?>">
<input type="hidden" id="data_img_yn" value="<?=$data_img_yn?>">
<input type="hidden" id="data_option_yn" value="<?=$data_option_yn?>">
<input type="hidden" id="data_org_price_yn" value="<?=$data_org_price_yn?>">
<input type="hidden" id="data_pop_name" value="<?=$data_pop_name?>">
<input type="hidden" id="data_goods_tit" value="<?=$ppd_title?>">
<style type="text/css">
	/* 프린트 배경색 인쇄 */
	h1, h2, h3, h4, h5, dl, dt, dd, ul, li, ol, th, td, p, blockquote, form, fieldset, legend, div,body { -webkit-print-color-adjust:exact; }

	/* 프린트 여백설정 */
	@page{
		margin-left: 0px;
		margin-right: 0px;
		margin-top: 0px;
		margin-bottom: 0px;
	}

	<? if(strpos("/".$_SERVER['REQUEST_URI'], "/pop/printer/type/1_") == true){ //type1의 경우 ?>
	/* 프린트 세로인쇄 */
	@media print{@page {size: portrait}}
	<? }else{ ?>
	/* 프린트 가로인쇄 */
	@media print{@page {size: landscape}}
	<? } ?>
</style>
<div class="wrap_pop">
  <div class="s_tit">
    스마트POP 만들기
  </div>
  <p class="step_tit">
    <span class="text_st1">STEP 2.</span>
		<span class="text_st2">
			아래 POP이미지를 클릭하시고 행사타이틀과 상품이미지, 상품명, 옵션, 가격 등을 수정하세요~!
		</span>
    <button class="btn_tr_back2" onclick="location.href='/pop/printer'">뒤로가기</button>
  </p>
