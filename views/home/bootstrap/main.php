<? $parent_id = $this->funn->getParent($this->member->item('mem_id'));
// if(($parent_id=="3"&&$this->member->item('mem_level') == 1)||$this->member->item("mem_id")=="3"||$this->member->item("mem_id")=="1260"||$this->member->item("mem_id")=="962"||$parent_id=="1260"||$parent_id=="962"){
if(($parent_id=="3"&&$this->member->item('mem_level') == 1)||$this->member->item("mem_id")=="3"||$this->member->item("mem_id")=="1260"||$this->member->item("mem_id")=="1308"||$parent_id=="1260"||$parent_id=="1308"||$this->member->item("mem_id")=="1294"||$parent_id=="1294"||$this->member->item("mem_id")=="911"||$parent_id=="911"){
    ?>

    <? //일반팝업 시작 ?>
    <!--popup-->
    <!-- <style>
/*popup*/
.popStyle0{width:500px; height:680px; background-color: #a5dcef; border:none; padding:0; color:#404040; position:absolute; top:70px; left:calc(50% - 350px); z-index:10000;}
.pop_imgbox{width:100%; position: relative; display: inline-block;}
.popStyle0 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
.popStyle0 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
.popStyle0 form a{float:right; margin-right:20px; }
/* #demo{position: absolute; bottom:50px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
#demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
.cd_seconds{color:#fff000 !important;}
</style>
<div id="divpop0" class="popStyle0">
  <div class="pop_imgbox">
      <a href="http://igenie.co.kr/post/38" target="_blank">
      <img src="../images/pop_20230330.jpg" alt=""/>
      </a>
  </div>
        <form name="notice_form0">
        <input type="checkbox" name="chkbox0" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
        <a href="javascript:closeWin0();"><strong>X 닫기</strong></a>
        </form>



</div>
<script language="Javascript">
function setCookie0( name, value, expiredays ) {
    var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }
function closeWin0() {
    if ( document.notice_form0.chkbox0.checked ){
        setCookie0( "maindiv0", "done" , 1 );
    }
    document.all['divpop0'].style.visibility = "hidden";
}
</script>

<script language="Javascript">
cookiedata = document.cookie;
if ( cookiedata.indexOf("maindiv0=done") < 0 ){
    document.all['divpop0'].style.visibility = "visible";
    }
    else {
        document.all['divpop'].style.visibility = "hidden";
}
</script> -->
    <!-- <style>
    /*popup*/
    .popStyle0{width:959px; height:765px; background-color: #a5dcef; border:none; padding:0; color:#404040; position:absolute; top:70px; right:0; z-index:10000;}
    .pop_imgbox{width:100%; position: relative; display: inline-block;}
    .popStyle0 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
    .popStyle0 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
    .popStyle0 form a{float:right; margin-right:20px; }
#demo{position: absolute; bottom:50px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
    #demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;}
    .cd_seconds{color:#fff000 !important;}
    </style> -->
    <!-- <div id="divpop2" class="popStyle0">
      <div class="pop_imgbox">

          <img src="../images/pop_20220315.jpg" alt=""/>

      </div>
            <form name="notice_form0">
            <input type="checkbox" name="chkbox0" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
            <a href="javascript:closeWin0();"><strong>X 닫기</strong></a>
            </form>

            <p id="demo"></p>
            <script>
    // Set the date we're counting down to
    var countDownDate = new Date("apr 14, 2022 11:59:59").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();

      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);


      document.getElementById("demo").innerHTML = "<span class='cd_day'>" + days + "</span>" + "일" + "<span class='cd_hours'>" + hours + "</span>" + "시간"
       + "<span class='cd_minutes'>" + minutes + "</span>" + "분" + "<span class='cd_seconds'>" + seconds + "</span>" + "초";

      // If the count down is over, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "이벤트종료";
      }
    }, 1000);
    </script>

    </div> -->
    <!-- <script language="Javascript">
    function setCookie0( name, value, expiredays ) {
        var todayDate = new Date();
            todayDate.setDate( todayDate.getDate() + expiredays );
            document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }
    function closeWin0() {
        if ( document.notice_form0.chkbox0.checked ){
            setCookie0( "maindiv2", "done" , 1 );
        }
        document.all['divpop2'].style.visibility = "hidden";
    }
    </script>

    <script language="Javascript">
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("maindiv2=done") < 0 ){
        document.all['divpop2'].style.visibility = "visible";
        }
        else {
            document.all['divpop2'].style.visibility = "hidden";
    }
    </script> -->


    <? //일반팝업 종료 ?>
<? } ?>
<?if(($parent_id=="962"&&$this->member->item('mem_level') == 1)||$this->member->item("mem_id")=="962"){?>
    <? //일반팝업 시작 ?>
    <!--popup-->
    <!-- <style>
    /*popup*/
    .popStyle0{width:700px; height:808px; background-color: #a5dcef; border:none; padding:0; color:#404040; position:absolute; top:25px; left:calc(50% - 260px); z-index:10000;}
    .pop_imgbox{width:100%; position: relative; display: inline-block;}
    .popStyle0 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
    .popStyle0 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
    .popStyle0 form a{float:right; margin-right:20px; }
/* #demo{position: absolute; bottom:50px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
    #demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
    .cd_seconds{color:#fff000 !important;}
    </style>
    <div id="divpop0" class="popStyle0">
      <div class="pop_imgbox">

          <img src="../images/pop_20221202_02.jpg" alt=""/>

      </div>
            <form name="notice_form0">
            <input type="checkbox" name="chkbox0" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
            <a href="javascript:closeWin0();"><strong>X 닫기</strong></a>
            </form>



    </div>
    <script language="Javascript">
    function setCookie0( name, value, expiredays ) {
        var todayDate = new Date();
            todayDate.setDate( todayDate.getDate() + expiredays );
            document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }
    function closeWin0() {
        if ( document.notice_form0.chkbox0.checked ){
            setCookie0( "maindiv0", "done" , 1 );
        }
        document.all['divpop0'].style.visibility = "hidden";
    }
    </script>

    <script language="Javascript">
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("maindiv0=done") < 0 ){
        document.all['divpop0'].style.visibility = "visible";
        }
        else {
            document.all['divpop0'].style.visibility = "hidden";
    }
    </script> -->

    <? //일반팝업 종료 ?>
<? }else{?>
    <? //일반팝업 시작 ?>
    <!--popup-->
    <!-- <style>
    /*popup*/
    .popStyle0{width:700px; height:808px; background-color: #a5dcef; border:none; padding:0; color:#404040; position:absolute; top:25px; left:calc(50% - 260px); z-index:10000;}
    .pop_imgbox{width:100%; position: relative; display: inline-block;}
    .popStyle0 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
    .popStyle0 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
    .popStyle0 form a{float:right; margin-right:20px; }
    /* #demo{position: absolute; bottom:50px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
    #demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
    .cd_seconds{color:#fff000 !important;}
    </style>
    <div id="divpop0" class="popStyle0">
      <div class="pop_imgbox">

          <img src="../images/pop_20221202_01.jpg" alt=""/>

      </div>
            <form name="notice_form0">
            <input type="checkbox" name="chkbox0" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
            <a href="javascript:closeWin0();"><strong>X 닫기</strong></a>
            </form>



    </div>
    <script language="Javascript">
    function setCookie0( name, value, expiredays ) {
        var todayDate = new Date();
            todayDate.setDate( todayDate.getDate() + expiredays );
            document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }
    function closeWin0() {
        if ( document.notice_form0.chkbox0.checked ){
            setCookie0( "maindiv0", "done" , 1 );
        }
        document.all['divpop0'].style.visibility = "hidden";
    }
    </script>

    <script language="Javascript">
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("maindiv0=done") < 0 ){
        document.all['divpop0'].style.visibility = "visible";
        }
        else {
            document.all['divpop0'].style.visibility = "hidden";
    }
    </script> -->

    <? //일반팝업 종료 ?>
<? } ?>

<? if (!empty($unread_announces)) { ?>
  <link href="/views/biz/manager/announce/bootstrap/css/style.css" rel="stylesheet">
  <link href="/views/biz/manager/announce/bootstrap/css/modal.css" rel="stylesheet">
  <div id='an_modal' class="an_wrap">
    <? foreach ($unread_announces as $i => $an) { ?>
      <div class="modal_white_box" style="z-index: <?= 1000000 - $i ?>;" data-an-id="<?= $an->an_id ?>">
        <h1>
          <?= $an->an_title ?>
        </h1>
        <main>
          <?= $an->an_content ?>
        </main>
        <div class="an_modal_btn_container">
          <label class="an_confirm_checkbox_container">
            <span class="checkbox_container">
              <input class="an_confirm_checkbox" type="checkbox" oninput="an_confirm_toggled(event)">
              <span class="checkmark"></span>
            </span>
            내용을 확인했습니다.
          </label>
          <button class="an_close_btn an_btn" onclick='close_an_popup(event)' disabled>닫기</button>
        </div>
      </div>
    <? } ?>
  </div>
  <script>
    $("#an_modal").prependTo(document.body)

    var an_popup_count = <?= count($unread_announces) ?>;

    function an_confirm_toggled(evt) {
      var $confirmCheckbox = $(evt.currentTarget)
      var $closeBtn = $confirmCheckbox.parent().parent().parent().find(".an_close_btn")
      $closeBtn.attr('disabled', !$confirmCheckbox.is(":checked"))
    }

    function close_an_popup(evt) {
      var box = $(evt.currentTarget).parent().parent()
      var id = box[0].dataset.anId
      box.hide()
      an_popup_count--
      $.ajax({
        url: "/biz/manager/announce/read_announce",
        type: "POST",
        data: {
          "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
          id: id,
        },
      })
      if (an_popup_count === 0) $(".an_wrap").hide()
    }
  </script>
<? } ?>

<?
$eve0_flag="N";
if(!empty(config_item('eve0_member'))){
    foreach(config_item('eve0_member') as $r){
        if($r==$this->member->item("mem_id")||($this->member->item("mem_level")==1&&$r==$parent_id)){
            $eve0_flag="Y";
        }
    }
    $eve0_Reg_time = date("Y-m-d H:i:s", strtotime($this->member->item("mem_register_datetime"))); // 멤버 등록 시간 제한
    $eve0_Stan_time = date("Y-m-d H:i:s", strtotime('2023-01-09 00:00:00')); // 기준 시간
    if($eve0_Reg_time > $eve0_Stan_time){
        $eve0_flag="N";
    }
}
?>

<? if($parent_id!="895"&&$this->member->item("mem_id")!="895"&&$parent_id!="2312"&&$this->member->item("mem_id")!="2312"){ //일반팝업 빅포스제외?>
<!--popup-->
<!-- <style>
/*popup*/
.popStyle000{width:500px; height:718px; background-color: #a5dcef; border:none; padding:0; color:#404040; position:absolute; top:70px; left:calc(50% - 280px); z-index:10000;}
.pop_imgbox{width:100%; position: relative; display: inline-block;}
.popStyle000 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
.popStyle000 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
.popStyle000 form a{float:right; margin-right:20px; }
/* #demo{position: absolute; bottom:50px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
#demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
.cd_seconds{color:#fff000 !important;}
</style>
<div id="divpop000" class="popStyle000">
  <div class="pop_imgbox">
      <a href="http://igenie.co.kr/card/main" target="_blank">
          <img src="../images/pop_20230126.jpg" alt=""/>
      </a>
  </div>
        <form name="notice_form000">
        <input type="checkbox" name="chkbox000" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
        <a href="javascript:closeWin000();"><strong>X 닫기</strong></a>
        </form>




</div>
<script language="Javascript">
function setCookie000( name, value, expiredays ) {
    var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }
function closeWin000() {
    if ( document.notice_form000.chkbox000.checked ){
        setCookie000( "maindiv000", "done" , 1 );
    }
    document.all['divpop000'].style.visibility = "hidden";
}
</script>

<script language="Javascript">
cookiedata = document.cookie;
if ( cookiedata.indexOf("maindiv0=done") < 0 ){
    document.all['divpop000'].style.visibility = "visible";
    }
    else {
        document.all['divpop000'].style.visibility = "hidden";
}
</script> -->
<? } ?>
<? if($eve0_flag=="Y"){?>
    <? //신팝업 시작 ?>
    <!--popup-->
    <!-- <style>
    /*popup*/
    .popStyle000{width:500px; height:719px; background-color: #a5dcef; border:none; padding:0; color:#404040; position:absolute; top:25px; left:calc(50% - 800px); z-index:10000;}
    .pop_imgbox{width:100%; position: relative; display: inline-block;}
    .popStyle000 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
    .popStyle000 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
    .popStyle000 form a{float:right; margin-right:20px; }
/* #demo{position: absolute; bottom:50px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
    #demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
    .cd_seconds{color:#fff000 !important;}
    </style>
    <div id="divpop000" class="popStyle000">
      <div class="pop_imgbox">
          <a href="http://pf.kakao.com/_agtSs/98161668" target="_blank">
              <img src="../images/pop_20230106.jpg" alt=""/>
          </a>
      </div>
            <form name="notice_form000">
            <input type="checkbox" name="chkbox000" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
            <a href="javascript:closeWin000();"><strong>X 닫기</strong></a>
            </form>



    </div>
    <script language="Javascript">
    function setCookie000( name, value, expiredays ) {
        var todayDate = new Date();
            todayDate.setDate( todayDate.getDate() + expiredays );
            document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }
    function closeWin000() {
        if ( document.notice_form000.chkbox000.checked ){
            setCookie000( "maindiv000", "done" , 1 );
        }
        document.all['divpop000'].style.visibility = "hidden";
    }
    </script>

    <script language="Javascript">
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("maindiv0=done") < 0 ){
        document.all['divpop000'].style.visibility = "visible";
        }
        else {
            document.all['divpop000'].style.visibility = "hidden";
    }
    </script> -->


    <? //신팝업 종료 ?>
<? } ?>


<?
$eve1_flag="N";
if(!empty(config_item('eve1_member'))){
    foreach(config_item('eve1_member') as $r){
        if($r==$this->member->item("mem_id")){
            $eve1_flag="Y";
        }
    }
}

$eve2_flag="N";
if(!empty(config_item('eve2_member'))){
    foreach(config_item('eve2_member') as $r){
        if($r==$this->member->item("mem_id")){
            $eve2_flag="Y";
        }
    }
}

 if($eve_cnt>0||$eve1_flag=="Y"){
?>
<? //일반팝업 시작 ?>
<!--popup-->
<!-- <style>
/*popup*/
.popStyle0{width:500px; height:655px; background-color: #ff8424; border:none; padding:0; color:#404040; position:absolute; top:25px; left:calc(50% - 250px); z-index:10000;}
.pop_imgbox{width:100%; position: relative; display: inline-block;}
.popStyle0 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
.popStyle0 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
.popStyle0 form a{float:right; margin-right:20px; }
/* #demo{position: absolute; bottom:120px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
#demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
.cd_seconds{color:#fff000 !important;}
</style>
<div id="divpop0" class="popStyle0">
  <div class="pop_imgbox">

      <img src="../images/pop_20220208.jpg" alt=""/>

  </div>
        <form name="notice_form0">
        <input type="checkbox" name="chkbox0" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
        <a href="javascript:closeWin0();"><strong>X 닫기</strong></a>
        </form>


</div>
<script language="Javascript">
function setCookie0( name, value, expiredays ) {
    var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }
function closeWin0() {
    if ( document.notice_form0.chkbox0.checked ){
        setCookie0( "maindiv0", "done" , 1 );
    }
    document.all['divpop0'].style.visibility = "hidden";
}
</script>

<script language="Javascript">
cookiedata = document.cookie;
if ( cookiedata.indexOf("maindiv0=done") < 0 ){
    document.all['divpop0'].style.visibility = "visible";
    }
    else {
        document.all['divpop'].style.visibility = "hidden";
}
</script> -->

<? //일반팝업 종료 ?>
<? $nowTime = date("Y-m-d H:i:s"); // 현재 시간
// $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 시작 시간
$startTime = date("Y-m-d H:i:s", strtotime('2023-11-29 00:00:00')); // 시작 시간
$expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
$eventTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 이벤트 시작 시간

// $in_ip = $_SERVER['REMOTE_ADDR'];
// if(($nowTime < $expTime && $nowTime >= $startTime)||$in_ip=="61.75.230.209"){

    if($nowTime < $expTime && $nowTime >= $startTime){
    ?>

<style>
/*popup*/
.popStyle1{width:500px; height:738px; background-color: #fff; border:none; padding:0; color:#404040; position:absolute; top:25px; left:40px; z-index:10000;}
.pop_imgbox{width:100%; position: relative; display: inline-block;}
.pop_imgbox .comming_soon{position: absolute; width:100%; display: inline-block;}
.pop_imgbox .comming_soon_text{position: absolute; left:150px; bottom:63px; width:200px; line-height: 2; font-size:20px; color:#fff; font-weight: 600; background-color: #58026f; text-align: center; display: inline-block; border-radius: 30px;}
.popStyle1 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
.popStyle1 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
.popStyle1 form a{float:right; margin-right:20px; }
#demo{position: absolute; top:250px; left:8%; right:0; font-size:25px; color:#282828; width:86%;font-weight: bold; text-align: center; font-family: 'Spoqa Han Sans';padding: 10px 0;background: #ffff45bd; border-radius: 15px;}
#demo span{font-size:40px; margin:0 5px 0 10px; background-color: #333; color:#fff; padding:0 10px; border-radius:3px;}
.cd_seconds{color:#ff3574 !important;}
</style>
<div id="divpop" class="popStyle1">
    <div class="pop_imgbox">
        <!-- <div class="event_link_box">
        <a href="/deposit/eventpage" class="event_link1"></a>
        <a href="/deposit/event2page" class="event_link2"></a>
        </div> -->
        <div id="soonid" class="comming_soon" style="display:;">
            <div class="comming_soon_text">COMMING SOON</div>
            <img src="../images/event/eve_231116.jpg" alt="고객감사이벤트"/>
        </div>
        <a href="/deposit/eventpage"><img src="../images/event/eve_231116.jpg" alt="고객감사이벤트"/></a>
    </div>
        <form name="notice_form">
        <input type="checkbox" name="chkbox" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
        <a href="javascript:closeWin();"><strong>X 닫기</strong></a>
        </form>

        <p id="demo"></p>

        <script>
// Set the date we're counting down to
var countDownDate = new Date("dec 31, 2023 23:59:59").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

<? if($nowTime >= $eventTime){ ?>
    $("#soonid").hide();
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = "<span class='cd_day'>" + days + "</span>" + "일" + "<span class='cd_hours'>" + hours + "</span>" + "시간"
   + "<span class='cd_minutes'>" + minutes + "</span>" + "분" + "<span class='cd_seconds'>" + seconds + "</span>" + "초";
<? }else{ ?>
    $("#soonid").show();
    document.getElementById("demo").innerHTML = "<p style='font-size:28px;'> COMING SOON! </p>";
<? } ?>
  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "<p style='font-size:28px;'> 이벤트종료  </p>";
  }
}, 1000);
</script>



</div>

<script language="Javascript">
function setCookie( name, value, expiredays ) {
    var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }
function closeWin() {
    if ( document.notice_form.chkbox.checked ){
        setCookie( "maindiv", "done" , 1 );
    }
    document.all['divpop'].style.visibility = "hidden";
}
</script>

<script language="Javascript">
cookiedata = document.cookie;
if ( cookiedata.indexOf("maindiv=done") < 0 ){
    document.all['divpop'].style.visibility = "visible";
    }
    else {
        document.all['divpop'].style.visibility = "hidden";
}
</script>
    <!--popup-->
    <!-- <style>
    /*popup*/
    .popStyle1{width:420px; height:718px; background-color: #ff8424; border:none; padding:0; color:#404040; position:absolute; top:70px; left:calc(50% - 800px); z-index:10000;}
.pop_imgbox{width:100%; position: relative; display: inline-block;}
.popStyle1 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
.popStyle1 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
.popStyle1 form a{float:right; margin-right:20px; }
#demo{position: absolute; bottom:100px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
#demo span{font-size:25px; margin:0 5px 0 10px; background-color: #333; color:#fff; padding:0 10px; border-radius:3px;}
.cd_seconds{color:#ff3574 !important;}
    </style>
    <div id="divpop" class="popStyle1">
      <div class="pop_imgbox">
        <a href="../deposit/redbank">
          <img src="../images/pop_20230524.jpg" alt=""/>
        </a>
      </div>
            <form name="notice_form">
            <input type="checkbox" name="chkbox" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
            <a href="javascript:closeWin();"><strong>X 닫기</strong></a>
            </form>

            <p id="demo"></p>

            <script>
    // Set the date we're counting down to
    var countDownDate = new Date("jun 30, 2023 23:59:59").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();

      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      <? if($nowTime >= $eventTime){ ?>
   // Output the result in an element with id="demo"
   document.getElementById("demo").innerHTML = "<span class='cd_day'>" + days + "</span>" + "일" + "<span class='cd_hours'>" + hours + "</span>" + "시간"
    + "<span class='cd_minutes'>" + minutes + "</span>" + "분" + "<span class='cd_seconds'>" + seconds + "</span>" + "초";
 <? }else{ ?>
     document.getElementById("demo").innerHTML = "<p style='font-size:28px;'>COMING SOON!</p>";
 <? } ?>
      // If the count down is over, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "<p style='font-size:28px;'>이벤트종료</p>";
      }
    }, 1000);
    </script>
    </div>
    <script language="Javascript">
    function setCookie( name, value, expiredays ) {
        var todayDate = new Date();
            todayDate.setDate( todayDate.getDate() + expiredays );
            document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }
    function closeWin() {
        if ( document.notice_form.chkbox.checked ){
            setCookie( "maindiv", "done" , 1 );
        }
        document.all['divpop'].style.visibility = "hidden";
    }
    </script>

    <script language="Javascript">
    cookiedata = document.cookie;
    if ( cookiedata.indexOf("maindiv=done") < 0 ){
        document.all['divpop'].style.visibility = "visible";
        }
        else {
            document.all['divpop'].style.visibility = "hidden";
    }
    </script> -->
<? }else{ ?>

        <? $nowTime0 = date("Y-m-d H:i:s"); // 현재 시간
        $startTime0 = date("Y-m-d H:i:s", strtotime('2022-01-01 00:00:00')); // 시작 시간
        $expTime0 = date("Y-m-d H:i:s", strtotime('2022-01-06 00:00:00')); // 종료 시간
        if($nowTime0 < $expTime0 && $nowTime0 >= $startTime0){ ?>
        <!--popup-->
        <style>
        /*popup*/
        .popStyle1{width:500px; height:758px; background-color: #ff8424; border:none; padding:0; color:#404040; position:absolute; top:25px; left:calc(50% - 250px); z-index:10000;}
        .pop_imgbox{width:100%; position: relative; display: inline-block;}
        .popStyle1 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
        .popStyle1 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
        .popStyle1 form a{float:right; margin-right:20px; }
        /* #demo{position: absolute; bottom:120px; left:0; right:0; font-size:20px; color:#fff; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
        #demo span{font-size:30px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;} */
        .cd_seconds{color:#fff000 !important;}
        </style>
        <div id="divpop" class="popStyle1">
          <div class="pop_imgbox">

              <img src="../images/pop_event_end.jpg" alt=""/>

          </div>
                <form name="notice_form">
                <input type="checkbox" name="chkbox" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
                <a href="javascript:closeWin();"><strong>X 닫기</strong></a>
                </form>

                <!-- <p id="demo"></p> -->

        </div>
        <script language="Javascript">
        function setCookie( name, value, expiredays ) {
            var todayDate = new Date();
                todayDate.setDate( todayDate.getDate() + expiredays );
                document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
            }
        function closeWin() {
            if ( document.notice_form.chkbox.checked ){
                setCookie( "maindiv", "done" , 1 );
            }
            document.all['divpop'].style.visibility = "hidden";
        }
        </script>

        <script language="Javascript">
        cookiedata = document.cookie;
        if ( cookiedata.indexOf("maindiv=done") < 0 ){
            document.all['divpop'].style.visibility = "visible";
            }
            else {
                document.all['divpop'].style.visibility = "hidden";
        }
        </script>
    <? } } ?>
<? }else if($eve_cnt2>0||$eve2_flag=="Y"){ ?>
    <? $nowTime = date("Y-m-d H:i:s"); // 현재 시간
    $startTime = date("Y-m-d H:i:s", strtotime('2022-07-27 00:00:00')); // 시작 시간
    // $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 시작 시간
    $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime2'))); // 종료 시간
    $eventTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 이벤트 시작 시간
    if($nowTime < $expTime && $nowTime >= $startTime){ ?>

<!--popup-->
<style>
/*popup*/
.popStyle1{width:500px; height:720px; background-color: #ff8424; border:none; padding:0; color:#404040; position:absolute; top:70px; left:calc(50% - 800px); z-index:10000;}
.pop_imgbox{width:100%; position: relative; display: inline-block;}
.popStyle1 form{background-color: #404040; color:#fff; position:absolute; bottom:0; padding:10px 0; width:100%;}
.popStyle1 form input{margin:0 5px 0 20px; vertical-align: middle;position: relative;}
.popStyle1 form a{float:right; margin-right:20px; }
#demo{position: absolute; bottom:185px; left:0; right:0; font-size:20px; color:#404040; width:100%; text-align: center; font-family: 'Spoqa Han Sans';}
#demo span{font-size:25px; margin:0 5px 0 10px; background-color: #000; color:#fff; padding:0 10px; border-radius:3px;}
.cd_seconds{color:#fff000 !important;}
</style>
<div id="divpop" class="popStyle1">
  <div class="pop_imgbox">
    <a href="../deposit/eventpage">
      <img src="../images/pop_20220805_tg.jpg" alt=""/>
    </a>
  </div>
        <form name="notice_form">
        <input type="checkbox" name="chkbox" value="checkbox" style="width:15px; height:15px; padding:0;"> 오늘 하루 이 창을 열지 않음
        <a href="javascript:closeWin();"><strong>X 닫기</strong></a>
        </form>

        <p id="demo"></p>

        <script>
// Set the date we're counting down to
var countDownDate = new Date("aug 31, 2022 23:59:59").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Output the result in an element with id="demo"
  <? if($nowTime >= $eventTime){ ?>
// Output the result in an element with id="demo"
document.getElementById("demo").innerHTML = "<span class='cd_day'>" + days + "</span>" + "일" + "<span class='cd_hours'>" + hours + "</span>" + "시간"
+ "<span class='cd_minutes'>" + minutes + "</span>" + "분" + "<span class='cd_seconds'>" + seconds + "</span>" + "초";
<? }else{ ?>
 document.getElementById("demo").innerHTML = "COMING SOON!";
<? } ?>
  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "이벤트종료";
  }
}, 1000);
</script>
</div>
<script language="Javascript">
function setCookie( name, value, expiredays ) {
    var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }
function closeWin() {
    if ( document.notice_form.chkbox.checked ){
        setCookie( "maindiv", "done" , 1 );
    }
    document.all['divpop'].style.visibility = "hidden";
}
</script>

<script language="Javascript">
cookiedata = document.cookie;
if ( cookiedata.indexOf("maindiv=done") < 0 ){
    document.all['divpop'].style.visibility = "visible";
    }
    else {
        document.all['divpop'].style.visibility = "hidden";
}
</script>
<? } ?>
<? } ?>
<div class="main_top">
	<!--div class="select_bar fr">
		<ul>
			<li class="selected">A.I 스마트설정</li>
			<li class="">커스텀설정</li>
			<li class="">수동설정</li>
		</ul>
	</div-->
	<div class="notice_bar">
		<span class="title">알립니다.</span>
		<span class="notice_text"><? if(!empty($notice->post_id)){ ?><a href="/post/<?=$notice->post_id?>"><?=$notice->post_date?>. <?=$notice->post_title?></a><? } ?></span>
	</div>
  <!-- <div class="smart_mart_img" style="width:350px;margin:0 auto 30px;position: absolute;top:390px;left: 50%;transform: translate(-50%, -50%);z-index:100" onclick="$('.smart_mart_img').hide();">
    <img src="./images/smart_mart_img.png"  style="width:350px;cursor: pointer;"/>
  </div> -->
</div>
<div class="main_contents">
	<div class="mobile_btn">
		<a href="/mall/order/mapadd"><span class="material-icons">fmd_good</span> 배달분포 주소추가</a>
		<span class="btn_add_home_mo" onclick="HomeButtonAdd('지니','cm_id=bookmark');"><i class="material-icons">star</i> 홈화면에 바로가기 추가</span>
		<div id="myModal_HomeButton" class="pf_modal">
		<!-- Modal content -->
		<div class="pf_modal-content">
			<div class="pf_modal-header">
			<span class="pf_modal_close">×</span>
			<h2>아이폰, 아이패드 홈버튼 추가 안내</h2>
			</div>
			<div class="pf_modal-body">
			 <p>
				 1. 애플 사용자의 경우 사파리 브라우저에서 하단 중앙버튼을 클릭
			 </p>
			 <p>
				<img src="/images/app_info1.jpg" alt="">
			 </p>
			 <p class="mg_t20">
				 2. 버튼클릭 후 홈화면에 추가 메뉴를 클릭
			 </p>
			 <p>
				<img src="/images/app_info2.jpg" alt="">
			 </p>
			 <p class="mg_t20">
				 3. 추가 버튼 클릭 - 완료!
			 </p>
			 <p>
				<img src="/images/app_info3.jpg" alt="">
			 </p>
			</div>
		</div>
	</div>
	<script type="text/Javascript">
	/* ==============================================================================
	# 홈버튼 탭 추가
	* 사용방법 : onclick='HomeButtonAdd("플친몰","cm_id=bookmark")'
	============================================================================== */
	function HomeButtonAdd(title,code){
		var HomeButtonTitle = title;
		var LogAnalysisCode = code;
		//alert("HomeButtonTitle : "+ HomeButtonTitle +", LogAnalysisCode : "+ LogAnalysisCode); return;

		var HomeButtonTitle = encodeURI(HomeButtonTitle);
		var HomePageUri = document.domain;
		var WebRootPathUri = "http://"+document.domain;
		var HomePageUri = "http://"+document.domain;
		var iconurl="http://"+document.domain+"/favicon/android-icon-192x192.png";
		//alert("iconurl : "+ iconurl);
		//alert("HomeButtonTitle : "+ HomeButtonTitle +"\n"+"HomePageUri : "+ HomePageUri +"\n"+"WebRootPathUri : "+ WebRootPathUri +"\n"+"HomePageUri : "+ HomePageUri +"\n"+"iconurl : "+ iconurl); return;

		var HomeButtonIconUri = WebRootPathUri+$('link[rel="apple-touch-icon-precomposed"]').attr("href");
		var customUrlScheme= "intent://addshortcut?url="+HomePageUri+"%3F"+LogAnalysisCode+"&icon="+iconurl+"&title="+ HomeButtonTitle+"&oq="+HomeButtonTitle+"&serviceCode=nstore&version=7#Intent;scheme=naversearchapp;action=android.intent.action.VIEW;category=android.intent.category.BROWSABLE;package=com.nhn.android.search;end";
		//alert("HomeButtonIconUri : "+ HomeButtonIconUri +"\n"+"customUrlScheme : "+ customUrlScheme); return;

		var UserAgent = navigator.userAgent.toLowerCase();
		var BlockDevice1 = UserAgent.indexOf("iphone");
		var BlockDevice2 = UserAgent.indexOf("ipad");
		var BlockDevice = BlockDevice1 + BlockDevice2;
		//alert("UserAgent : "+ UserAgent +"\n"+"BlockDevice1 : "+ BlockDevice1 +"\n"+"BlockDevice2 : "+ BlockDevice2 +"\n"+"BlockDevice : "+ BlockDevice); return;

		if(BlockDevice == -2){
			//alert(title+'을(를) 홈화면에 추가합니다.\n\n네이버앱이 없는 고객 네이버앱 설치페이지로 이동됩니다.');
			window.open(customUrlScheme);
		}else{
			//alert("아이폰, 아이패드 계열은 직접 홈버튼 추가를 사용하셔야합니다.");
			HomeButtonAdd_iphone();
		}
	}
	</script>
	<script>
	// Get the modal
	var modal_HomeButton = document.getElementById("myModal_HomeButton");

	// Get the <span> element that closes the modal
	var span_HomeButton = document.getElementsByClassName("pf_modal_close")[0];

	// When the user clicks on <span> (x), close the modal
	span_HomeButton.onclick = function() {
		modal_HomeButton.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal_HomeButton) {
			modal_HomeButton.style.display = "none";
		}
	}

	//아이폰, 아이패드 계열은 직접 홈버튼 추가 모달 호출
	function HomeButtonAdd_iphone(){
		//alert("HomeButtonAdd_iphone"); return;
		modal_HomeButton.style.display = "block";
	}
	</script>
	</div>
	<div class="main_card_list row3">
		<div class="card line_color1">
			<h3 class="title">나의 고객</h3>
			<p class="value"><?=number_format($mycnt->cus_cnt)?>명</p>
		</div>
		<div class="card line_color2">
			<h3 class="title">스마트 전단 행사</h3>
			<p class="value"><?=number_format($mycnt->psd_cnt)?>건</p>
		</div>
		<div class="card line_color3">
			<h3>오늘의 발송현황</h3>
			<p class="value"><?=number_format($mycnt->mst_qty)?>건</p>
		</div>
	</div>
    <div class="dh_modal_pop" id="voucher_modal" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
            <div class="modal-content">
	            <span class="dh_close" data-dismiss="modal">×</span>
                <p class="modal-tit">바우처 사용 알림</p>
                <div class="content" id="modal_user_msg_list">
                    <p class="modal_voucher">
                        최근 1개월간 발송이력이 없습니다.</br>
                        바우처 혜택을 받으시려면 기간 내에 예치금을 소진하셔야 합니다.
                    </p>
                </div>
            </div>
    </div>
    <script>
        $(document).ready(function (){
    <?
        if (!empty($voucher) && $v_coin["coin"] >= 10000){
    ?>
            $("#voucher_modal").modal({backdrop: 'static'});
    <?
        }
    ?>
        });
    </script>
	<? if($pos_yn == "Y"){ //포스연동 사용의 경우 ?>
	<div class="main_card_list row2">
		<div class="card">
			<div class="card_option">
				<ul>
					<!--<li class="<?=($chart['date_type'] == "DAY") ? "on" : ""?>">일</li>-->
					<li class="<?=($chart['date_type'] == "WEEK") ? "on" : ""?>" id="BTN_SALES_WEEK" onclick="daily_data('SALES', 'WEEK');" style="cursor: pointer;!important;">주</li>
					<li class="<?=($chart['date_type'] == "MONTH") ? "on" : ""?>" id="BTN_SALES_MONTH" onclick="daily_data('SALES', 'MONTH');" style="cursor: pointer;!important;">월</li>
				</ul>
			</div>
			<h3>매출현황</h3>
			<div class="card_row">
				<div class=""><h4>오늘&nbsp;매출</h4><p class="chart_value" id="TODAY_SALES_TOT"><?=number_format($chart['TODAY_SALES_TOT'])?>원</p></div>
				<div class=""><h4>어제&nbsp;매출</h4><p class="chart_value" id="YESTERDAY_SALES_TOT"><?=number_format($chart['YESTERDAY_SALES_TOT'])?>원</p></div>
			</div>
			<div class="card_row">
				<div class=""><h4 id="SALES_TYPE1"><?=($chart['date_type'] == "MONTH") ? "이번달" : "이번주"?>&nbsp;매출</h4><p class="chart_value" id="SALES_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_SALES_TOT'] : $chart['WEEK_SALES_TOT'])?>원</p></div>
				<div class=""><h4 id="SALES_TYPE2"><?=($chart['date_type'] == "MONTH") ? "지난달" : "지난주"?>&nbsp;매출</h4><p class="chart_value" id="PREV_SALES_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_PREV_SALES_TOT'] : $chart['WEEK_PREV_SALES_TOT'])?>원</p></div>
			</div>
			<div class="chart_wrap" id="graph_sales"></div>
		</div>
		<div class="card">
			<div class="card_title">
				<div class="card_option">
					<ul>
						<!--<li class="<?=($chart['date_type'] == "DAY") ? "on" : ""?>">일</li>-->
						<li class="<?=($chart['date_type'] == "WEEK") ? "on" : ""?>" id="BTN_VISIT_WEEK" onclick="daily_data('VISIT', 'WEEK');" style="cursor: pointer">주</li>
						<li class="<?=($chart['date_type'] == "MONTH") ? "on" : ""?>" id="BTN_VISIT_MONTH" onclick="daily_data('VISIT', 'MONTH');" style="cursor: pointer">월</li>
					</ul>
				</div>
				<h3>매장방문고객</h3>
			</div>
			<div class="card_row">
				<div class=""><h4>오늘&nbsp;방문자</h4><p class="chart_value" id="TODAY_VISIT_TOT"><?=number_format($chart['TODAY_VISIT_TOT'])?>명</p></div>
				<div class=""><h4>어제&nbsp;방문자</h4><p class="chart_value" id="YESTERDAY_VISIT_TOT"><?=number_format($chart['YESTERDAY_VISIT_TOT'])?>명</p></div>
			</div>
			<div class="card_row">
				<div class=""><h4 id="VISIT_TYPE1"><?=($chart['date_type'] == "MONTH") ? "이번달" : "이번주"?>&nbsp;방문자</h4><p class="chart_value" id="VISIT_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_VISIT_TOT'] : $chart['WEEK_VISIT_TOT'])?>명</p></div>
				<div class=""><h4 id="VISIT_TYPE2"><?=($chart['date_type'] == "MONTH") ? "지난달" : "지난주"?>&nbsp;방문자</h4><p class="chart_value" id="PREV_VISIT_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_PREV_VISIT_TOT'] : $chart['WEEK_PREV_VISIT_TOT'])?>명</p></div>
			</div>
			<div class="chart_wrap" id="graph_visit"></div>
		</div>
	</div>
	<!-- 메인 차트 관련 -->
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script>
		Highcharts.setOptions({
			lang: {
				thousandsSep: ','
			}
		});
		//매출현황 차트
		Highcharts.chart('graph_sales', {
			title: {
				text: '<?=($chart['date_type'] == "MONTH") ? $chart['month_title'] : $chart['week_title']?>'
			},
			legend: {
				layout: 'horizontal',
				align: 'center',
				verticalAlign: 'bottom'
			},

			xAxis: {
				categories: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_SALES_CAT'] : $chart['WEEK_SALES_CAT']?> ]
			},
			yAxis: {
				title: {
					text: '주문건수'
				}
			},
			series: [{
				showInLegend: false,
				name: '매출액',
				data: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_SALES_AMT'] : $chart['WEEK_SALES_AMT']?> ]
			}]
		});

		//매장방문고객 차트
		Highcharts.chart('graph_visit', {
			title: {
				text: '<?=($chart['date_type'] == "MONTH") ? $chart['month_title'] : $chart['week_title']?>'
			},
			legend: {
				layout: 'horizontal',
				align: 'center',
				verticalAlign: 'bottom'
			},

			xAxis: {
				categories: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_VISIT_CAT'] : $chart['WEEK_VISIT_CAT']?> ]
			},
			yAxis: {
				title: {
					text: '방문자수'
				}
			},
			series: [{
				showInLegend: false,
				name: '방문자수',
				data: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_VISIT_CNT'] : $chart['WEEK_VISIT_CNT']?> ]
			}]
		});

		//일별통계 실시간 조회
		function daily_data(data_type, date_type){
			//alert("data_type : "+ data_type +", date_type : "+ date_type);
			if(data_type == "SALES"){ //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
				var chart1 = $('#graph_sales').highcharts(); //매출현황 차트
				var SALES_CAT = "<?=$chart['WEEK_SALES_CAT']?>"; //주별 카테고리
				var SALES_AMT = "<?=$chart['WEEK_SALES_AMT']?>"; //주별 데이타
				var SALES_TOT = "<?=$chart['WEEK_SALES_TOT']?>"; //이번주 매출
				var PREV_SALES_TOT = "<?=$chart['WEEK_PREV_SALES_TOT']?>"; //지난주 매출
				var TITLE = "<?=$chart['week_title']?>"; //주별 타이틀
				if(date_type == "MONTH"){
					SALES_CAT = "<?=$chart['MONTH_SALES_CAT']?>"; //월별 카테고리
					SALES_AMT = "<?=$chart['MONTH_SALES_AMT']?>"; //월별 데이타
					SALES_TOT = "<?=$chart['MONTH_SALES_TOT']?>"; //지난달 매출
					PREV_SALES_TOT = "<?=$chart['MONTH_PREV_SALES_TOT']?>"; //지난달 매출
					TITLE = "<?=$chart['month_title']?>"; //월별 타이틀
				}
				//alert("SALES_CAT : "+ SALES_CAT +"\n"+ "SALES_AMT : "+ SALES_AMT); return;
				var data1 = SALES_AMT.split(", "); //매출 데이타
				var vdata = [];
				for (var i in data1) {
					vdata.push(Number(data1[i]));
				}
				var xdata = SALES_CAT.split(", "); //x축
				chart1.series[0].update({
					data: vdata
				});
				chart1.xAxis[0].update({
					categories: xdata
				});
				chart1.setTitle({text: TITLE}); //타이틀
				chart1.redraw();
				$("#SALES_TOT").html(numberWithCommas(SALES_TOT) +"원"); //이번주/달 매출
				$("#PREV_SALES_TOT").html(numberWithCommas(PREV_SALES_TOT) +"원"); //지난주/달 매출
				$("#BTN_SALES_WEEK").removeClass("on");
				$("#BTN_SALES_MONTH").removeClass("on");
				if (date_type == "WEEK") {
					$("#BTN_SALES_WEEK").addClass("on");
					$("#SALES_TYPE1").html("이번주 매출");
					$("#SALES_TYPE2").html("지난주 매출");
				} else if (date_type == "MONTH") {
					$("#BTN_SALES_MONTH").addClass("on");
					$("#SALES_TYPE1").html("이번달 매출");
					$("#SALES_TYPE2").html("지난달 매출");
				}
			}else if(data_type == "VISIT"){ //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
				var chart1 = $('#graph_visit').highcharts(); //방문현황 차트
				var VISIT_CAT = "<?=$chart['WEEK_VISIT_CAT']?>"; //주별 카테고리
				var VISIT_CNT = "<?=$chart['WEEK_VISIT_CNT']?>"; //주별 데이타
				var VISIT_TOT = "<?=$chart['WEEK_VISIT_TOT']?>"; //이번주 매출
				var PREV_VISIT_TOT = "<?=$chart['WEEK_PREV_VISIT_TOT']?>"; //지난주 매출
				var TITLE = "<?=$chart['week_title']?>"; //주별 타이틀
				if(date_type == "MONTH"){
					VISIT_CAT = "<?=$chart['MONTH_VISIT_CAT']?>"; //월별 카테고리
					VISIT_CNT = "<?=$chart['MONTH_VISIT_CNT']?>"; //월별 데이타
					VISIT_TOT = "<?=$chart['MONTH_VISIT_TOT']?>"; //지난달 매출
					PREV_VISIT_TOT = "<?=$chart['MONTH_PREV_VISIT_TOT']?>"; //지난달 매출
					TITLE = "<?=$chart['month_title']?>"; //월별 타이틀
				}
				//alert("VISIT_CAT : "+ VISIT_CAT +"\n"+ "VISIT_CNT : "+ VISIT_CNT); return;
				var data1 = VISIT_CNT.split(", "); //방문 데이타
				var vdata = [];
				for (var i in data1) {
					vdata.push(Number(data1[i]));
				}
				var xdata = VISIT_CAT.split(", "); //x축
				chart1.series[0].update({
					data: vdata
				});
				chart1.xAxis[0].update({
					categories: xdata
				});
				chart1.setTitle({text: TITLE}); //타이틀
				chart1.redraw();
				$("#VISIT_TOT").html(numberWithCommas(VISIT_TOT) +"명"); //이번주 방문자
				$("#PREV_VISIT_TOT").html(numberWithCommas(PREV_VISIT_TOT) +"명"); //지난주 방문자
				$("#BTN_VISIT_WEEK").removeClass("on");
				$("#BTN_VISIT_MONTH").removeClass("on");
				if (date_type == "WEEK") {
					$("#BTN_VISIT_WEEK").addClass("on");
					$("#VISIT_TYPE1").html("이번주 방문자");
					$("#VISIT_TYPE2").html("지난주 방문자");
				} else if (date_type == "MONTH") {
					$("#BTN_VISIT_MONTH").addClass("on");
					$("#VISIT_TYPE1").html("이번달 방문자");
					$("#VISIT_TYPE2").html("지난달 방문자");
				}
			}
		}

		if("<?=$chart['msg']?>" == "error"){
			showSnackbar("포스연동 프로그램이 실행되지 않았습니다.", 3000);
		}

		//금액 콤마 찍기
		function numberWithCommas(x) {
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	</script>
	<? }else{ //if($pos_yn == "Y"){ //포스연동 사용의 경우 ?>
	<div class="pos_none">
		<!-- <div class="apply_wrap">
            <h3>
                <span>신청문의</span>
                <span class="apply_tel">1522-7985</span>
            </h3>

            <div class="apply_address">
                <h4>(주)대형네트웍스</h4>
                <p>
                    창원본사 : 경상남도 창원시 의창구 팔용로 48번길54, 302~304호
                    서울지점 : 서울특별시 중랑구 신내동 821-2 신내에스케이브이 1센터 1220호
                    대전지점 : 대전광역시 유성구 반석로 7 애니빌프라자 206호
                    대구지점 : 대구광역시 수성구 알파시티1로 160 SW융합테크비즈센터 513호
                    광주지점 : 광주광역시 서구 상무중앙로 7 상무타워5층 르호봇광주비즈니스센터 533-1호
                    부산지점 : 부산광역시 사상구 모라로 22 부산벤처타워 615호
                </p>
                <p>
                    자회사 : (주)지니
                    대표문의 : 1661-1849
                    서울특별시 중랑구 신내역로 3길 40-36, 5층 506호(신내동)
                </h4>
            </div>
		</div> -->
		<p>
		AI 스마트 매장관리시스템 '지니'를 이용하시는 고객님,<br />
			매장정보를 보다 편리하게 관리하시려면 포스사에 지니를 소개해주세요~<br />
        <? if($gyn != "962"&&$this->member->item("mem_id") != "962"){ ?>
			지니 X (주)대형네트웍스 : <span>1522-7985</span>
        <? }else{ ?>
            주식회사 지니 : <span>1661-1849</span>
        <? } ?>
		</p>
	</div>
	<? } //if($pos_yn == "Y"){ //포스연동 사용의 경우 ?>
</div>
<?php
if (!empty($this->session->userdata('login_stack'))){
    if(in_array(962, $this->session->userdata('login_stack'))){
   ?>
<div id="hc_modal" class="happycall_wrap" style="">
    <div class="modal_white_box">
        <div class="modal_textarea">
            <span id="patner_name" class="modal_list_name" style="display: none;"></span>
            <label>작성자</label>
            <input id="writer" type="text">
        </div>
        <textarea id="contents"></textarea>
        <button onclick="save_history()">등록</button>

        <div class="modal_list_box">
            <table>
                <colgroup>
                    <col width="100px">
                    <col width="*">
                    <col width="100px">
                    <col width="90px">
                    <col width="90px">
                </colgroup>
                <thead>
                    <tr>
                    <th>작성자</th>
                    <th>내용</th>
                    <th>작성일</th>
                    <th>고정</th>
                    <th>삭제</th>
                    </tr>
                </thead>
                <tbody id="list_box">
                </tbody>
            </table>
        </div>
        <button class="modal_close" onclick="close_modal()"><i class="xi-close"></i></button>
    </div>
</div>

<script>
    var mid = '<?php echo $this->member->item('mem_id')?>';
    var page_flag;
    var category = [];

    <?foreach(config_item('hc_category') as $key => $a){?>
        category[<?=$key?>] = '<?=$a?>';
    <?}?>

	$(document).ready(function(){
		get_history(mid);
		})
    function open_modal(flag){
        if (flag){
            $('#patner_name').css('display', 'none');
            $('#partner_list').css('display', '');
        } else {
            $('#patner_name').css('display', '');
            $('#partner_list').css('display', 'none');
            get_history(mid);
        }
        page_flag = flag;
        $('#hc_modal').css('display', '');
    }

    function close_modal(){
        $('#hc_modal').css('display', 'none');
    }

    function save_history(){
        if ($('#writer').val().trim() == ''){
            alert('작성자를 입력해주세요.');
            $('#writer').focus()
            return;
        }

        if ($('#contents').val().trim() == ''){
            alert('내용을 입력해주세요.');
            $('#contents').focus()
            return;
        }
        ins_history();
    }

    function ins_history(){
        if(confirm('등록하시겠습니까?')){
            $.ajax({
                url: "/biz/manager/happycall/ins_history",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                  , mid : mid
                  , category : '8'
                  , writer : $('#writer').val()
                  , contents : $('#contents').val()
                },
                success: function (json) {
                	get_history(mid);
                }
            });
        }
    }

    function set_history(list){
        var html = '';
        $('#list_box').html('');
        if (list != undefined){
            if (list.length > 0){
                $.each(list, function(idx, val){
                    html += '<tr style=\'' + (val.hc_fix == 'Y' ? 'background-color:rgb(244,244,244)' : '') + '\'>';
                    html += '<td>' + val.hc_writer + '</td>';
                    html += '<td>' + val.hc_contents.replaceAll('\n', '<br>') + '</td>';
                    html += '<td>' + val.hc_create_datetime + '</td>';
                    html += '<td><button onclick="update_fix(' + val.hc_id + ', \'' + (val.hc_fix == 'Y' ? 'N' : 'Y') + '\')">' + (val.hc_fix == 'Y' ? '해제' : '고정') + '</button></td>';
                    html += '<td><button onclick="del_history_from_modal(' + val.hc_id + ')">삭제</button></td>';
                    html += '</tr>';
                });
            } else {
                html += '<tr><td colspan=\'4\'>히스토리가 없습니다.</td></tr>'
            }
        } else {
            html += '<tr><td colspan=\'4\'>히스토리가 없습니다.</td></tr>'
        }
        $('#list_box').append(html);
    }

    function update_fix(id, flag){
        $.ajax({
            url: "/biz/manager/happycall/update_fix",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , id : id
              , flag : flag
            },
            success: function (json) {
                get_history(mid);
            }
        });
    }

    function get_history(mid){
        $.ajax({
            url: "/biz/manager/happycall/get_history",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , mid : mid
              , flag : 1
            },
            success: function (json) {
                set_history(json.list);
            }
        });
    }

    function del_history_from_modal(id){
        if (confirm('삭제하시겠습니까?')){
            $.ajax({
                url: "/biz/manager/happycall/del_history",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                  , id : id
                },
                success: function (json) {
                    get_history(mid);
                }
            });
        }
    }
</script>
<?php }}?>
