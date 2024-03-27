<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<!-- <link rel="stylesheet" href="/css/tui-grid.css" />
<script src="/js/tui-grid.js"></script> -->
<!-- 본문 영역 -->
<div class="tit_wrap">지니2->지니1 계정이전</div>


<div class="salesman_box">
  <div class="w100">
      <input type="text" id="uid" value="" placeholder="아이디로 확인하세요">
      <button id="member_search" onclick="member_search()"><i class="material-icons">done</i></button><br/><br/>
      <select id="uppartner" style="display:none;">
        <option value="" selected="">소속선택</option>
            <?
            if(!empty($uppartner)){
                foreach ($uppartner as $r) {
                    echo '<option value="'.$r->mem_id.'" >'.$r->mem_username.'</option>';
                    }
                }
            ?>
      </select>
      <button id="member_move" onclick="member_move()" style="display:none;">파트너 이전하기</button>
      <br/>
      <div id ="photo"  style="display:none;">
      <!-- <div>mem_photo : <span id="photo1span"></span></div>
      <div>mem_contract_filepath : <span id="photo2span"></span></div>
      <div>mem_shop_img : <span id="photo3span"></span>
      </div> -->
      <div style="font-size:30px;">mem_photo : <span id="photo1span"></span></div>
      <div style="font-size:30px;">mem_shop_img : <span id="photo3span"></span>
      </div>
      <div style="color:#f00; font-size:30px;">mem_contract_filepath : <span id="photo2span"></span></div>
  </div>
  </div>
</div>

<script>
    var uuid = "";

    function member_search() {
        var uid = $("#uid").val();
        if(uid != ""){
            $.ajax({
                url: "/biz/partner_move/partner_search",
                type: "POST",
                data: {
                    <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                    "uid": uid
                },
                success: function(json) {

                    uuid = uid;

                    if(json.code=="0"){
                        $("#member_move").show();
                        $("#uppartner").show();
                    }else{
                        $("#member_move").hide();
                        $("#uppartner").hide();
                    }
                    $("#photo").hide();
                    $("#photo1span").text('');
                    $("#photo2span").text('');
                    $("#photo3span").text('');
                    showSnackbar(json.msg, 1500);
                }
            });
        }

    }


    function member_move() {
        var uid = $("#uid").val();
        var uppartner = $("#uppartner").val();

        if(uppartner==""){
            showSnackbar("소속을 선택해주세요", 1500);
            $("#uppartner").focus();
            return false;
        }

        if(uid != ""&&uuid == uid){
            $.ajax({
                url: "/biz/partner_move/partner_move",
                type: "POST",
                data: {
                    <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                    "uid": uid,
                    "uppartner": uppartner
                },
                success: function(json) {

                    $("#member_move").hide();
                    $("#uppartner").hide();

                    $("#photo").show();
                    $("#photo1span").text(json.mem_photo);
                    $("#photo2span").text(json.mem_contract_filepath);
                    $("#photo3span").text(json.mem_shop_img);

                    showSnackbar(json.msg, 1500);
                    // if(json.code=="0"){
                    //
                    //     // setTimeout(function() {
					// 	// 	location.href("./biz/partner_move");
					// 	// }, 1500); //1초 지연
                    // }

                }
            });
        }

    }



</script>
