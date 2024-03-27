<!-- <link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" /> -->
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/eveview.css?v=<?=date("ymdHis")?>" />
<script src="/js/jquery.Keyframes.min.js"></script>

<script src="/js/jquery-barcode.js"></script>
<script src="/js/jquery-barcode.min.js"></script>

<!-- <div class="wrap_smart"> -->
<div class="eveview_wrap roulette_wrap">
    <div class="eve_container roulette_<?=$data->emd_tem_type?>">
        <div class="eve_title">
            <h3><?=$shop->mem_username?></h3>
            <h2><?=$data->emd_title?></h2>
            <!-- <div class="eve_notice">부가설명 솰라솰라</div>
            <div class="eve_date">2023.06.23 ~ 06.30</div> -->
        </div>

        <div class="reward_wrap <?=(empty($rw_data->emg_barcode))? "no_barcode" : ""?>">

            <dl class="reward_item">
                <dt class="rw_title text_stroke"><img src="/images/eveview/img_heart_01.png" alt="하트"> <?=$rw_data->emg_name?> <img src="/images/eveview/img_heart_01.png" alt="하트"></dt>
                <dd class="rw_detail"> <?=$rw_data->emg_sub_name?> </dd>
                <dd class="rw_dDay"> 사용만료일 <?=date("Y.m.d", strtotime("+1 months", strtotime($data->emd_open_edt)))?></dd>
                <dd class="rw_img"><img src="<?=$imgurl?>" alt="당첨상품"></dd>
            </dl>
            <?
                if(!empty($rw_data->emg_barcode)&&!empty($rw_data->emg_barcode_type)){
                    ?>
                    <div class="barcode_box">
                        <div id="barcode_div"></div>
                    </div>
                    <?
                }
            ?>
            <? if($emsd_data->esd_state == '3'){ ?>
                <input type="button" id="confirm_btn" value="직원확인완료" class="btn_barcode"  style="background-color:#eee;color:#999;"  onclick="con_coupon();">

            <? }else{ ?>
                <input type="button" id="confirm_btn" value="직원확인" class="btn_barcode" onclick="con_coupon();">
            <? } ?>

        </div>
    </div>

    <div class="eve_notice2">
        <div class="notice2_detail">
            <h5>[ 이벤트 유의사항 ]</h5>
            <ul>
                <li>본 이벤트는 <span><?=$shop->mem_username?> 고객 대상</span>으로 응모 가능합니다.</li>
                <li>이벤트는 <span>1인 1회</span> (차수 별로 1회씩) 참여 가능합니다.</li>
                <li>이벤트 경품 발송을 위해 개인정보가 수집됩니다.</li>
                <li>부정한 방법(개인 정보 도용, 불법 프로그램 등)으로 이벤트에 참여한 것이 발견된 경우 당첨이 취소될 수 있습니다.</li>
                <li>경품 종류 별 당첨 확률은 상이합니다.</li>
                <li>응모 취소 및 재응모는 불가합니다.</li>
                <li>경품은 고객분이 입력한 핸드폰 번호로 발송 처리되며, 번호 오입력 시 재발송 되지 않습니다.</li>
                <li>본 이벤트는 선착순으로 진행되며, 조기에 종료되거나 경품이 변경될 수 있습니다.</li>
            </ul>

            <h5>[ 경품 안내 ]</h5>
            <ul>
                <li>일부 경품은 당첨 안내를 위해 개별 문자 및 유선 안내 예정입니다.</li>
                <li>경품 쿠폰은 유효기간 내에서만 이용이 가능합니다.</li>
                <li>실물 경품 중 5만원 초과 경품은 제세공과금 신고가 필수로 진행되며, 제세공과금 22%는 당첨자 고객 부담입니다.</li>
                <li>제세공과금 신고를 위한 개인정보를 확인하며 이에 동의하지 않거나 제세공과금 미입금 시 당첨은 취소됩니다.</li>
            </ul>
        </div>
    </div>


    <div class="btn-box">
        <!-- <div class="txt">NUMBER <span>4~40</span></div> -->
        <input type="number" value="" class="num-box" placeholder="NUMBER" style="display:none;">
        <input type="button" value="SET" class="set-btn" onclick="setting();" style="display:none;">
        <input type="button" value="START" class="start-btn" onclick="actionStart();">
        <input type="button" value="STOP" class="stop-btn" onclick="reward_view();">
        <div class="btn-cover"></div>
    </div>

</div>

<script>

$(document).ready(function(){
    <? if(!empty($rw_data->emg_barcode)&&!empty($rw_data->emg_barcode_type)){ ?>
    $("#barcode_div").barcode("<?=$rw_data->emg_barcode?>", "ean" + ("<?=$rw_data->emg_barcode_type?>"),{barWidth:2, barHeight:60, fontSize:20});
    <? } ?>
 });

 function con_coupon(){
     <? if(empty($this->input->get('code'))||$this->input->get('code')=="preview"){ ?>
         $("#confirm_btn").css("background-color","#eee;");
         $("#confirm_btn").css("color","#999;");
         $("#confirm_btn").val("직원확인 완료");
     <? }else if($emsd_data->esd_state < 3){ ?>
         $.ajax({
             url: "/smart/reward_confirm",
             type: "POST",
             async: false,
             data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                     // ,"emd_id":"<?=$data->emd_id?>"
                     ,"code":"<?=$this->input->get("code")?>"
             },
             success: function (json) {
                 // alert(json.msg);
                 if(json.code==0){
                     $("#confirm_btn").css("background-color","#eee;");
                     $("#confirm_btn").css("color","#999;");
                     $("#confirm_btn").val("직원확인 완료");
                 }else{
                     alert("확인을 완료하지 못했습니다.");
                 }
                 //history.go(0);
                 //$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
             }
         });
     <? } ?>


 }
</script>
