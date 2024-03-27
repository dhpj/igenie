<div class="form_section">
    <div class="inner_tit">
        <h3>커스텀API<span id="div_title"><span></h3>
    </div>
    <div class="white_box">
        <div class="search_box" style="margin:0;">
            <span class="search_tit">업체명</span>
            <input type="text" id="coName" name="coName" placeholder="업체명 입력" value="" onKeypress="if(event.keyCode==13){ send_api(); }">
            <span class="search_tit">발신프로필</span>
            <input type="text" id="profile" name="profile" placeholder="발신프로필 입력" value="" onKeypress="if(event.keyCode==13){ send_api(); }">
            <span class="search_tit">수신전화번호</span>
            <input type="text" id="sendNum" name="sendNum" placeholder="전화번호 입력" value="" onKeypress="if(event.keyCode==13){ send_api(); }">
            <input type="button" class="btn md color" style="cursor:pointer;" id="check" value="보내기" onclick="send_api();"/>

        </div>
    </div>

</div>


<script>
 function send_api(){
     var coName = $("#coName").val();
     var profile = $("#profile").val();
     var sendNum = $("#sendNum").val();

     if(coName==''){
         alert('업체명을 입력하세요');
         return;
     }

     if(profile==''){
         alert('발신프로필을 입력하세요');
         return;
     }

     if(sendNum==''){
         alert('수신전화번호를 입력하세요');
         return;
     }

     console.log("coName : "+ coName + " / profile : " + profile + " / sendNum : " + sendNum);


     $.ajax({
         url: "/customapi/sendat_custom",
         type: "POST",
         data: {"coName" : coName, "profile" : profile, "sendNum" : sendNum, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
         success: function (json){
             if(json.code == "0"){
                 $("#cart_count").html(json.cartcnt); //주문건수 표기
                 showSnackbar("발송하였습니다.", 1500);
             } else {
                 showSnackbar("발송에 오류가 있습니다."+ json.msg, 2000);
             }
         }
     });


 }
</script>
