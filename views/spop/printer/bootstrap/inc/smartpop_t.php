<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_config.php'); ?>
<?
    //저장된 타이들 매칭부분 2021-10-13
    if(!empty($title_list)){
        foreach($title_list as $r) {
            if($r->type == $type){
                $base_goods_tit = $r->title;
            }
            if($type=='3_09'&&$r->type=='4_05'){ // 3_09타입은 4_05 저장 타이틀을 사용(예외처리)
                $base_goods_tit = $r->title;
            }
            if($type=='3_11'&&$r->type=='4_06'){ // 3_11타입은 4_06 저장 타이틀을 사용(예외처리)
                $base_goods_tit = $r->title;
            }
            if($type=='3_13'&&$r->type=='4_07'){ // 3_13타입은 4_07 저장 타이틀을 사용(예외처리)
                $base_goods_tit = $r->title;
            }
            if($type=='3_15'&&$r->type=='4_08'){ // 3_15타입은 4_08 저장 타이틀을 사용(예외처리)
                $base_goods_tit = $r->title;
            }
        }
    }

	$mylogotext = '';
	$logocss = '';
	if($type == "2_03"){
		$logocss = "mart_logo2";
	}else{
		$logocss = "mart_logo";
	}
	if(!empty($mylogo->ppl_useyn)){
		if($mylogo->ppl_useyn=='Y'){
			if(!empty($mylogo->ppl_imgpath)){
				$mylogotext = "<div class='".$logocss."'><img src='".$mylogo->ppl_imgpath."'></div>";
			}else{
				$mylogotext = "<div class='".$logocss."'><p>".$mylogo->ppl_name."</p></div>";
			}
		}
	}else{
		$mylogotext = "<div class='".$logocss."'><p>".$mem_name."</p></div>";
	}

	//스마트POP 기준정보
	$data_id = $data->ppd_id; //POP번호
	$data_md = "I"; //모드(I.추가, M.수정)
	if($data_id != ""){ //수정의 경우
		$data_md = "M"; //모드(I.추가, M.수정)
		$ppd_type = $data->ppd_type; //타입
		$ppd_title = $data->ppd_title; //POP제목
		$ppd_imgpath = $data->ppd_imgpath; //이미지경로
		$ppd_date = $data->ppd_date; //행사기간
	}else{
		$ppd_title = ""; //POP제목
	}

	$ppd_font_size = json_decode($data->ppd_font_size);

	$goods_data = array();
	$goods_data = array();

	//스마트POP 상품정보 목록
	if(!empty($goods)){
		$ii = 1;
		foreach($goods as $r) {
			$goods_data[$ii]["imgpath"] = $r->ppg_imgpath; //이미지경로
            if($option_type_yn=="Y"){
    			$goods_data[$ii]["name"] = $r->ppg_name; //상품명
    			$goods_data[$ii]["option"] = $r->ppg_option; //옵션명
            }else{
                $goods_data[$ii]["name"] = $r->ppg_name." ".$r->ppg_option; //상품명
    			$goods_data[$ii]["option"] = '';
            }
			$goods_data[$ii]["org_price"] = $r->ppg_org_price; //정상가
			$goods_data[$ii]["price"] = $r->ppg_price; //할인가
			$goods_data[$ii]["fontsize"] = json_decode($r->ppg_fontsize); //할인가
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
<input type="hidden" id="option_type_yn" value="<?=$option_type_yn?>">
<input type="hidden" id="data_org_price_yn" value="<?=$data_org_price_yn?>">
<input type="hidden" id="data_pop_name" value="<?=$data_pop_name?>">
<input type="hidden" id="data_goods_tit" value="<?=$ppd_title?>">
<input type="hidden" id="data_date_yn" value="<?=$data_date_yn?>">
<input type="hidden" id="data_goods_date" value="<?=$ppd_date?>">
<input type="hidden" id="data_poptit_yn" value="<?=$data_poptit_yn?>">
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

	<? if(strpos("/".$_SERVER['REQUEST_URI'], "/spop/printer/type/1_") == true){ //type1의 경우 ?>
	/* 프린트 세로인쇄 */
	@media print{@page {size: portrait}}
	<? }else{ ?>
	/* 프린트 가로인쇄 */
	@media print{@page {size: landscape}}
	<? } ?>

</style>
<script>
<? if($blankdata == "Y"){ ?>
    $(document).ready(function(){
        // $('.goods_baseprice').hide();
        // if($('#option_type_yn')=='Y'){
        //
        // }
        // $('.goods_option').hide();
    });
<? } ?>
</script>
<div class="wrap_pop">
  <div class="s_tit">
    스마트POP 만들기
  </div>
  <p class="step_tit">
    <span class="text_st1">STEP 3.</span>
		<span class="text_st2">
			아래 POP이미지를 클릭하시고 행사타이틀과 상품이미지, 상품명, 옵션, 가격 등을 수정하세요~!
		</span>
		<? if(empty($spop)){ ?>
    <button class="btn_tr_back2" onclick="window.history.back();">뒤로가기</button>
		<? } ?>
  </p>
