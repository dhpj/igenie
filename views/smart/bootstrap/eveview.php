<!-- <link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" /> -->
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/eveview.css?v=<?=date("ymdHis")?>" />
<script src="/js/jquery.Keyframes.min.js"></script>


<?
    $emd_open_sdt = ($data->emd_open_sdt != "") ? date("Y.m.d", strtotime($data->emd_open_sdt)) : ""; //주문하기 시작일자
    $top_emd_open_edt = ($data->emd_open_edt != "") ? date("m.d", strtotime($data->emd_open_edt)) : ""; //주문하기 종료일자
    $bottom_emd_open_sdt = ($data->emd_open_sdt != "") ? date("Y년 m월 d일", strtotime($data->emd_open_sdt) ) : ""; //주문하기 시작일자
    $bottom_emd_open_edt = ($data->emd_open_edt != "") ? date("Y년 m월 d일", strtotime($data->emd_open_edt) ) : ""; //주문하기 종료일자
    $open_yn = "Y";
    if($this->input->get("code")!="preview"){

    $open_sdt = ($data->emd_open_sdt != "") ? date("Y-m-d", strtotime($data->emd_open_sdt)) : "";
    $open_edt = ($data->emd_open_sdt != "") ? date("Y-m-d", strtotime($data->emd_open_edt)) : "";

    $today = date("Y-m-d"); //오늘날짜
    $open_msg = "";
    //echo "psd_order_yn(2) : ". $psd_order_yn ."<br>";

        if($open_sdt > $today){
            $open_yn = "N";
            $open_msg = "이벤트<em>오픈예정</em>입니다.";
            //echo "시작전<br>";
        }else if($open_edt < $today){
            $open_yn = "N";

            $open_msg = "이미<em>종료된 이벤트</em>입니다.";
            //echo "판매중단<br>";
        }

    }
 ?>
<!-- <div class="wrap_smart"> -->
<div class="eveview_wrap roulette_wrap">
    <div class="eve_container roulette_<?=$data->emd_tem_type?>">
        <div class="eve_title">
            <h3><?=$shop->mem_username?></h3>
            <h2><?=$data->emd_title?></h2>
            <div class="eve_notice"><?=$data->emd_sub_info?></div>
            <div class="eve_date"><?=$emd_open_sdt." ~ ".$top_emd_open_edt?></div>
        </div>

        <div class="eve_main">
            <div class="smart_back">

                <div style="<?=($open_yn=="N")? "" : "display:none;"?>" class="eve_end"><?=$open_msg?></div>

                <div id="pre_pop" style="display:none;">
                    <img src="/images/eveview/pre_pop_01.png" alt="참여완료">
                    <? if($info_data->esd_emg_name != "꽝"){ ?>
                    <input type="button" id="reward_btn2" value="당첨경품보기" class="btn_detail" onclick="detail();">
                    <? } ?>
                </div>
                <div id="reward_pop" class="reward_pop" style="display:none;">
                    <input type="button" id="reward_btn" value="자세히보러가기" class="btn_detail" onclick="detail();">
                    <img src="/images/eveview/roulette_reward_pop_01.png" alt="wow"></div>
                <dl id="reward_div" class="reward" style="display:none;">
                    <dt id="rw_title" class="halfLine">리워드명</dt>
                    <dd id="rw_detail" class="text_stroke">상세설명</dd>
                    <dd id="reward_img"><img src="/uploads/maker_goods/7dc93b42de6c553598a067957cfdec45.jpg" alt="당첨상품"></dd>
                </dl>
                <? if($open_yn=="Y"){ ?>
                    <div id="start_btn" class="cover" onclick="actionStart();"><img src="/images/eveview/roulette_btn_go_01.png" alt="시작"></div>
                    <div id="stop_btn" class="cover" onclick="reward_view();" style="display:none;"><img src="/images/eveview/roulette_btn_stop_01.png" alt="멈춤"></div>
                    <!-- <div class="pin"></div> -->
                    <div class="w_pin"><img src="/images/eveview/roulette_pin_01.png" alt="pin"></div>
                    <div class="wheel"></div>
                <? } ?>


            </div>
        </div>
    </div>

    <div class="eve_notice2">
        <dl class="notice2_point">
            <dt>응모 기간</dt><dd><?=$bottom_emd_open_sdt?> ~ <?=$bottom_emd_open_edt?></dd>
            <dt>응모 조건</dt><dd>1인 1회 응모 가능</dd>
            <dt>대 상</dt><dd><?=$shop->mem_username?> 고객</dd>
        </dl>
        <hr class="notice2_hr" >
        <div class="notice2_detail">
            <h5>[이벤트 유의사항]</h5>
            <ul>
                <li>본 이벤트는 <span><?=$shop->mem_username?>고객 대상</span>으로 응모 가능합니다.</li>
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


    <div class="btn-box">
        <!-- <div class="txt">NUMBER <span>4~40</span></div> -->
        <input type="number" value="" class="num-box" placeholder="NUMBER" style="display:none;">
        <input type="button" value="SET" class="set-btn" onclick="setting();" style="display:none;">
        <input type="button" value="START" class="start-btn" onclick="actionStart();">
        <input type="button" value="STOP" class="stop-btn" onclick="reward_view();">
        <div class="btn-cover"></div>
    </div>

    <!-- <div class="arrow" style="display:none;">
        <div class="head"></div>
        <div class="shaft"></div>
    </div> -->
<!-- </div> -->
</div>
<script>




// var urlurl = [];
//
// var nameArr = [];
// var sub_nameArr = [];
// var idArr = [];
// var typeArr = [];
        $(document).ready(function(){
            <? if($open_yn == "Y"){ ?>
            <? if(!empty($info_data)&&$info_data->esd_state>1){ ?>
                $("#pre_pop").show();
                $("#start_btn").hide();
                $(".smart_back > .wheel").hide();
                $(".smart_back > .w_pin").hide();
            <? }else{ ?>

                setting();
            <? } ?>
            <? } ?>

         });
        //룰렛 셋팅
        function setting() {

            var urlurl = [<?=$urlurlArr?>];

            var name = [<?=$nameArr?>];
            var subname = [<?=$subnameArr?>];

            // var urlurl = ["/uploads/maker_goods/8f9e3256f512625818a39dc0b84233ef.jpg"
            //               ,"/uploads/maker_goods/2022/07/63d7c1be75f1f5d30f48ffa1c8e55b4d.jpg"
            //               ,"/uploads/maker_goods/9616bae0a91cdc25962403192af907a1.jpg"
            //               ,"/uploads/maker_goods/7dc93b42de6c553598a067957cfdec45.jpg"
            //               ,"/uploads/maker_goods/2021/10/7eb0093c14dccb9cebe7591321bea7f5.jpg"
            //               ,"/uploads/maker_goods/d2ad79e2251e8165dfff4e65ee086231.jpg"
            //              ];
            //
            // var name = ["골드키위"
            //             ,"고당도 수박"
            //             ,"샤인머스켓"
            //             ,"채끝등심"
            //             ,"육개장컵라면"
            //             ,"오감자"
            //             ];
            // var subname = ["500g"
            //                ,"1통"
            //                ,"1봉"
            //                ,"100g"
            //                ,"6입"
            //                ,"1봉"
            //               ];



            // var num = $(".num-box").val();

            var num = name.length;
            var deg = 360 / num;

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

            //룰렛 갯수 한도 지정
            if (num < 4 || num > 40) {
                $(".num-box").stop().animate({
                    marginLeft: "-2px"
                }, 50).animate({
                    marginLeft: "2px"
                }, 50).animate({
                    marginLeft: "-2px"
                }, 50).animate({
                    marginLeft: "2px"
                }, 50).animate({
                    marginLeft: "0px"
                }, 25, caution()).focus();
                return false;
            }
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
            //받아온 num값에 따라 룰렛 선과 숫자를 뿌림
            for (var i = 0; i < num; i++) {
                $(".smart_back > .wheel").append("<div></div>");
                $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ")").css("transform", "rotate(" + (deg * i) + "deg)").append("<div id='reward_"+i+"' class='w_reward'></div>");
                // $(".smart_back > .wheel > div:nth-child(" + (i + 1) + ") > div").attr( "id", "reward_"+i);

                var html = "";
                html += "<div style='width:"+eve_width + "px"+";' class='reward_goods'>";
                html += "<div class='text_stroke'>";
                html += "<span style='font-size:"+eve_fontsize+"px;font-weight:bold;'>"+name[i]+"</span><br>";
                html += "<span>"+subname[i]+"</span>";
                html += "</div>";
                html += "<img src=\""+urlurl[i]+"\" style=\""+eve_imgstyle+"\">";
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
            $(".smart_back > .action").css("animation-duration", "0.4s").css("animation-play-state", "running");
        }
        //룰렛 정지
        function actionStop(num) {

            //set함수일경우에만 rand에 0을 받아 룰렛을 즉시멈추고 이외에는 넘겨받은 rand값에 따라 룰렛 정지 속도가 변경됨
            if (num == 0) {
                $(".btn-cover").hide();
                $(".smart_back > .action").css("animation-play-state", "paused");
            } else {

                // actionStart();
                // $(".smart_back > .wheel").addClass("action");
                // $(".smart_back > .action").css("animation-duration", "0.3s").css("animation-play-state", "running");
                // $(".smart_back > .action").stop().css("animation-duration", num * 10 + "s").delay(3000 - num * 300).queue(function () {
                //     $(".smart_back > .action").css("animation-play-state", "paused").dequeue();
                // });
                // $(".smart_back > .action").stop().css("animation-duration", "1s").delay(1000).queue(function () {
                //     $(".smart_back > .action").css("animation-play-state", "paused").dequeue();
                // });
                setting();
                var mxnum = Number(num + 359);
                $.keyframe.define([{
                    name: 'action25',
                       '0%':   {transform: 'rotate('+mxnum+'deg)'},
                       '100%': {transform: 'rotate('+num+'deg)'}
                }]);



                // animation.appendRule('0% {transform: rotate('+num+'deg);}');
                // animation.appendRule('100% {transform: rotate('+mxnum+'deg);}');
                // $(".smart_back > .wheel").addClass("action25");
                $('.smart_back > .wheel').css({
                      transform: 'rotate('+num+'deg)'
                    })
                // $("#start_btn").hide();
                // $("#stop_btn").show();
                // $(".smart_back > .action25").css("animation-iteration-count", "3");
                // $(".smart_back > .action25").css("animation-duration", "1s").css("animation-play-state", "running");
                $(".smart_back > .action").stop().css("animation-duration", "1s").delay(2000).queue(function () {
                    $(".smart_back > .action").css("animation-play-state", "paused").dequeue();
                });
                $(".smart_back > .wheel").addClass("action25");
                $(".smart_back > .action25").css("animation-duration", "1s").css("animation-play-state", "running");
                $(".smart_back > .action25").stop().css("animation-duration", "1s").delay(2000).queue(function () {
                    $(".smart_back > .action25").css("animation-play-state", "paused").dequeue();
                });
                // $('.smart_back > .wheel').css({
                //       transform: 'rotate('+150+'deg)'
                //     })
                // animation-name: action;
                // animation-duration: 0.3s;
                // animation-timing-function: linear;
                // animation-iteration-count: infinite;

                // $(".smart_back > .action").stop().css("animation-duration", num + "s").delay(2000).queue(function () {
                //     $(".smart_back > .action").css("animation-play-state", "paused").dequeue();
                //     if($('.smart_back > .wheel').css("transform", 'rotate(150deg)')){
                //         $(".smart_back > .action").css("animation-play-state", "paused");
                //     }
                // });
                // setTimeout(function() {
                //     setting();
                //     $('.smart_back > .wheel').css({
                //       transform: 'rotate('+150+'deg)'
                //     })
                //
                // }, 1500); //1초 지연

                // $(".btn-cover").hide()
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

        function reward_view(){
            var emd_id = $("#emd_id").val();
            // actionStop(Math.floor(Math.random() * 2) + 1);
            // actionStop(0.8);
            // $("#stop_btn").attr('onClick', "");
            // setTimeout(function() {
            //     $(".btn-cover").hide()
            //     $("#stop_btn").hide();
            //     $("#reward_div").show();
            //     $("#reward_pop").show();
            //     $(".smart_back > .wheel").hide();
            //     $(".smart_back > .w_pin").hide();
            //
            //     console.log("여기");
			// }, 2000); //1초 지연


            $.ajax({
				url: "/smart/reward_pick",
				type: "POST",
				async: false,
				data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
                        ,"emd_id":"<?=$data->emd_id?>"
                        ,"code":"<?=$this->input->get("code")?>"
                },
				success: function (json) {
					// alert(json.msg);
					if(json.code==0){
                        // setTimeout(function() {
						// 	location.reload();
						// }, 500); //1초 지연
                        // $("#reward_div").show();
                        if(json.imgurl!=""){
                            $("#reward_img").show();
                            $("#reward_img").children("img").attr("src", json.imgurl);
                        }else{
                            $("#reward_img").show();
                            $("#reward_img").children("img").attr("src", "/images/eveview/img_gift_01.png");
                        }
                        $("#rw_title").text(json.title);
                        $("#rw_detail").text(json.detail);

                        actionStop(json.ranpick);
                        $("#stop_btn").attr('onClick', "");
                        setTimeout(function() {
                            // $(".btn-cover").hide()
                            $("#stop_btn").hide();
                            if(json.type!="K"){
                                if(json.type=="P"||json.type=="D"){
                                    $("#reward_img").children("img").attr("src", "/images/eveview/img_point_01.png");
                                }
                                $("#reward_pop").show();

                            }else {
                                $("#reward_img").show();
                                $("#reward_img").children("img").attr("src", "/images/eveview/img_bomb_01.png");
                                $("#rw_title").text(json.title);
                                $("#rw_detail").text(json.detail);
                            }
                            $("#reward_div").show();
                            $(".smart_back > .wheel").hide();
                            $(".smart_back > .w_pin").hide();
                            if(json.eid!=""){
                                $("#reward_btn").attr("onClick","detail('"+json.eid+"')");
                            }


                            // console.log("여기");
            			}, 2000); //1초 지연

					}else{
                        alert("데이터를 가져오지 못했습니다.");
                    }
					//history.go(0);
					//$("#id_tag_list").html(json.html_tag); //템플릿 태그 리스트
				}
			});
        }

        function detail(code){
            var url = "";
            <? if($this->input->get("code")=="preview"){ ?>
                url = "/smart/evereward/"+ code+"?emd_code=<?=$code?>&code=<?=$this->input->get("code")?>";
            <? }else{ ?>
                url = "/smart/evereward/?code=<?=$this->input->get("code")?>";
            <? } ?>
            location.href = url;
        }

</script>
