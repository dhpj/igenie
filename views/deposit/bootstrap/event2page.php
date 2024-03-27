<!-- 타이틀 영역
<div class="tit_wrap">
	충전하기<?//php echo element('unique_id', $view); ?>
</div>
타이틀 영역 END -->
<!-- 3차 메뉴 -->
<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php'); //상단 서브 메뉴 ?>

<?
    if($eve_cnt>0||($this->member->item('mem_id')==3||$this->member->item('mem_id')==1260)){ //dhn + 대전연합
?>

        <!-- 소개이벤트 안내 -->
        <div id="mArticle" class="eve_230830">
            <div class="form_section">
                <div class="inner_tit mg_t30">
                    <h3>소개 이벤트 안내</h3>
                </div>
                <div class="service_box3">
                    <a href="http://pf.kakao.com/_agtSs/chat?from=qr" target="_blank"></a>
                    <img src="/images/eve_230830_2_bg.gif" alt="event_img">
                </div>
                <!-- <div class="charge_notice">
                    <h6>소개받은 업체 해지 및 환불 신청 시 현금 지원금은<br/> 소개 담당자 소속 매장에서 예치금 차감됩니다.</h6>
                    <dl class="call_num">
                        <dt>서비스문의</dt>
                        <dd>1522-7985</dd>
                    </dl>
                </div> -->
            </div>
        </div><!-- mArticle END -->

<?
    }
    else if($eve_cnt2>0||$this->member->item('mem_id')==962){ //지니
?>
        <script>
        	$(document).ready(function(){

        	});
        </script>


        <div id="mArticle">
        	<div class="form_section">


        	</div>
        </div>
        <!-- mArticle END -->
<?
    }
?>
