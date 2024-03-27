<script>
  var id_arr =[];
  var img_path_arr =[];
  var name_arr =[];
  var option_arr =[];
  var price_arr =[];
  var org_price_arr =[];
  var step_arr =[];
  var sample_imgpath = '/dhn/images/leaflets/sample_img.jpg';
  var style = '<?=$style?>';
  var spop = '<?=$spop?>';

</script>

<div class="wrap_pop">
  <div class="s_tit">
    스마트POP 만들기
  </div>
  <p class="step_tit w880">
    <span class="text_st2"><span class="material-icons">contact_support</span> 스마트POP로 제작할 상품을 선택하세요~!</span>
    <button class="all_ch" onclick="allchk()"><span class="material-icons">done</span> <span id="chkall_text">전체코너선택</span></button>
  </p>
  <div class="smartpop_wrap">
  <div class="smartpop_l">
      <?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_config.php'); ?>
  <?


  $sample_imgpath = "/dhn/images/leaflets/sample_img.jpg"; //행사이미지 타이틀

    if(!empty($screen_step1)){

  ?>
  <div class="smartpop_list">
		<div class="smartpop_list_t">
			<span class="tit">할인&대표상품</span>
			<button class="all_ch" id="chkall_0" onclick="chkSection('0')"><span class="material-icons">done</span> <span id="chkall_text_0" class="chk_text">전체선택</span></button>
		</div>
    <?
    $ii=0;
    foreach($screen_step1 as $r) {
      $badge_rate = "";
      if($r->psg_price != "" && $r->psg_dcprice != ""){
        $goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
        $goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
        $badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
      }
    ?>
		<dl>
			<dt>
        <input type="hidden" id="hdn_<?=$r->psg_id?>" value="0"/>
        <input type="hidden" id="goods_org_price<?=$r->psg_id?>" value="<?=$r->psg_price?>"/>
        <input type="hidden" id="goods_path_<?=$r->psg_id?>" value="<?=$r->psg_imgpath?>"/>
        <?
        if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
            $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
            $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
            $findthumb = strripos($fileNameWithoutExt, '_thumb');
            //echo $fileNameWithoutExt."---".$file_ext;
            $plustext = "_thumb.";
            if($findthumb>0){
                $plustext = ".";
            }
            $imgnamepext = config_item('igenie_path').$fileNameWithoutExt.$plustext.$file_ext;
        }else{
            $imgnamepext = "";
        }

        ?>

				<img  id="goods<?=$r->psg_id?>" src="<?=($r->psg_imgpath == "") ? $sample_imgpath : $imgnamepext?>" alt="">
        <label class="check_con" for="chk<?=$r->psg_id?>">
          <input type="checkbox" name="chkIds0" id="chk<?=$r->psg_id?>" onclick="popClick('<?=$r->psg_id?>')" value="<?=$r->psg_id?>" class="chk">
          <span class="checkmark cc_pointer"></span>
        </label>
			</dt>
			<dd>
				<ul>
					<li><span class="material-icons">chevron_right</span><span id="goods_name<?=$r->psg_id?>"><?=$r->psg_name?></span></li>
					<li><span class="material-icons">chevron_right</span><span id="goods_option<?=$r->psg_id?>"><?=$r->psg_option?></span></li>
                    <li><span class="material-icons">chevron_right</span><span id="goods_org_price<?=$r->psg_id?>" style="text-decoration:line-through;"><?=$r->psg_price?></span></li>
					<li><span class="material-icons">chevron_right</span><span id="goods_price<?=$r->psg_id?>"><?=$r->psg_dcprice?></span></li>
				</ul>
			</dd>
		</dl>
    <?
    $ii++;
    }
    ?>
	</div>
  <? } ?>
  <?

  $no = 0; //순번
  $ii = 0; //코너내 순번
  $seq = 0; //코너번호
  $chk_step_no = -1; //코너별 고유번호
  if(!empty($screen_box)){
    //$step_cnt = 0;
    foreach($screen_box as $r) {
      $no++;
      $psg_step = $r->psg_step;

      if($r->psg_step_no != $chk_step_no){
        $ii = 0;
        $chk_step_no = $r->psg_step_no;
        //$step_cnt++; //등록수 증가\


  ?>
  <div class="smartpop_list">
		<div class="smartpop_list_t">
			<span class="tit"><?=(!empty($r->tit_text_info))? $r->tit_text_info : '코너명 미지정' ?></span>
			<button id="chkall_<?=$chk_step_no?>" class="all_ch" onclick="chkSection('<?=$chk_step_no?>')"><span class="material-icons">done</span> <span id="chkall_text_<?=$chk_step_no?>" class="chk_text">전체선택</span></button>
		</div>
  <? } ?>
		<dl>
			<dt>
                <input type="hidden" id="goods_org_price<?=$r->psg_id?>" value="<?=$r->psg_price?>"/>
                <input type="hidden" id="hdn_<?=$r->psg_id?>" value="<?=$chk_step_no?>"/>
                <input type="hidden" id="goods_path_<?=$r->psg_id?>" value="<?=$r->psg_imgpath?>"/>
                <?
                if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                    $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                    $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                    $findthumb = strripos($fileNameWithoutExt, '_thumb');
                    //echo $fileNameWithoutExt."---".$file_ext;
                    $plustext = "_thumb.";
                    if($findthumb>0){
                        $plustext = ".";
                    }
                    $imgnamepext = config_item('igenie_path').$fileNameWithoutExt.$plustext.$file_ext;
                }else{
                    $imgnamepext = "";
                }

                ?>
				<img id="goods<?=$r->psg_id?>" src="<?=($r->psg_imgpath == "") ? $sample_imgpath : $imgnamepext?>" alt="">
        <label class="check_con" for="chk<?=$r->psg_id?>">
          <input type="checkbox" name="chkIds<?=$chk_step_no?>" id="chk<?=$r->psg_id?>"  onclick="javascript:popClick('<?=$r->psg_id?>');" value="<?=$r->psg_id?>" class="chk">
          <span class="checkmark cc_pointer"></span>
        </label>
			</dt>
			<dd>
				<ul>
					<li><span class="material-icons">chevron_right</span><span id="goods_name<?=$r->psg_id?>"><?=$r->psg_name?></span></li>
					<li><span class="material-icons">chevron_right</span><span id="goods_option<?=$r->psg_id?>"><?=$r->psg_option?></span></li>
                    <li><span class="material-icons">chevron_right</span><span id="goods_org_price<?=$r->psg_id?>" style="text-decoration:line-through;"><?=$r->psg_price?></span></li>
					<li><span class="material-icons">chevron_right</span><span id="goods_price<?=$r->psg_id?>"><?=$r->psg_dcprice?></span></li>
				</ul>
			</dd>
		</dl>
		<?
    $ii++;
    if($ii == $r->rownum){ ?>
	</div>
  <? }
    }
  } ?>

  </div>
  <div class="smartpop_r" id="preview_pop">
    <div class="smartpop_r_info" id="preview_defalut">
    <span class="material-icons">contact_support</span>  POP복사를 클릭하시면 해당 POP가 한장 더 복사됩니다.
    </div>
    <div id="poplist">
      <div class="pop_list" id="poppage">
        <ul id="popitem">
          <? for($i=0;$i<$goods_num_cnt;$i++){ ?>
          <li>
            <span class="material-icons">chevron_right</span>
            <div class="goods_infobox">
              <span>상품명</span>
              <span>옵션</span>
              <span>할인가</span>
            </div>
            <span class="material-icons btn_popdel">close</span>
          </li>
        <? } ?>
        </ul>
        <div class="pop_btn_box">
          <button class="" onclick=""><span class="material-icons">perm_media</span> POP복사</button>
          <button class="" onclick=""><span class="material-icons">cancel</span> POP삭제</button>
        </div>
      </div>
    </div>
  </div>
  <div class="write_pop_btn">
      <button type="button" class="pop_btn_save" onclick="printer('<?=$type?>')"><span class="material-icons">assignment_turned_in</span> POP 만들기</button>
      <button type="button" class="pop_btn_cancel" onclick="truncatePop();"><span class="material-icons">delete_sweep</span> POP 비우기</button>
      <a onclick="$('.s_tit').attr('tabindex', -1).focus();" class="btn_top" title="위로"><img src="/images/icon_arrow_wu.png" alt="위로"></a>
      <a onclick="$('footer').attr('tabindex', -1).focus();" class="btn_bot" title="아래"><img src="/images/icon_arrow_wd.png" alt="아래"></a>
  </div>
</div>
  <!-- <div class="btn_al_cen mg_t50 w880">
    <button class="btn_st3" onclick=""><span class="material-icons">assignment_turned_in</span> POP 만들기</button>
  </div> -->
</div>
<script type="text/javascript">
    function addpage(index, num){
      // var goods_num_cnt = "<?=$goods_num_cnt?>";
      //var fristNum = Number(index) + 1 - Number(goods_num_cnt);
      //var add_arr = [];
      for(i=0;i<num;i++){
        if(index < id_arr.length){
          var id = id_arr[index];
          var imgpath = img_path_arr[index];
          var name = name_arr[index];
          var option = option_arr[index];
          var price = price_arr[index];
          var org_price = org_price_arr[index];
          var step = step_arr[index];
          var slicenum = Number(index)+Number(num);
          id_arr.splice(slicenum, 0, id);
          img_path_arr.splice(slicenum, 0, imgpath);
          name_arr.splice(slicenum, 0, name);
          option_arr.splice(slicenum, 0, option);
          price_arr.splice(slicenum, 0, price);
          org_price_arr.splice(slicenum, 0, org_price);
          step_arr.splice(slicenum, 0, step);
          index++;
        }
      }
      refreshData();
    }

    function delpage(index, num){
      // var goods_num_cnt = "<?=$goods_num_cnt?>";
      var fristNum = index;
      //var add_arr = [];

      for(i=0;i<num;i++){
        if(index < id_arr.length){
          var count = 0;
          for(var j=0; j < id_arr.length; j++) {
            if(id_arr[j] === id_arr[index])  {
              count++;
            }
          }
          var step = step_arr[index];
          if(count==1){
            $('#chk'+id_arr[index]).attr("checked", false);
            if($('#chkall_text').html()=='전체코너해제'){
              $('#chkall_text').html('전체코너선택');
            }
            if($('#chkall_text_'+step).html()=='전체해제'){
              $('#chkall_text_'+step).html('전체선택');
            }
          }
          index++;
        }
      }
      id_arr.splice(fristNum, num);
      img_path_arr.splice(fristNum, num);
      name_arr.splice(fristNum, num);
      option_arr.splice(fristNum, num);
      price_arr.splice(fristNum, num);
      org_price_arr.splice(fristNum, num);
      step_arr.splice(fristNum, num);
      refreshData();
    }

    function delitem(index){
      var count = 0;
      for(var j=0; j < id_arr.length; j++) {
        if(id_arr[j] === id_arr[index])  {
          count++;
        }
      }
      var step = step_arr[index];
      if(count==1){
        $('#chk'+id_arr[index]).attr("checked", false);
        if($('#chkall_text').html()=='전체코너해제'){
          $('#chkall_text').html('전체코너선택');
        }
        if($('#chkall_text_'+step).html()=='전체해제'){
          $('#chkall_text_'+step).html('전체선택');
        }
      }
      id_arr.splice(index, 1);
      img_path_arr.splice(index, 1);
      name_arr.splice(index, 1);
      option_arr.splice(index, 1);
      price_arr.splice(index, 1);
      org_price_arr.splice(index, 1);
      step_arr.splice(index, 1);
      refreshData();
    }

    function allchk(){
        // 전체 갯수
        var wholenum = $(".chk").length;
        // 선택된 갯수
        var selectnum = $(".chk:checked").length;
        if(Number(selectnum) < Number(wholenum)){
            $(".chk").each(function() {
              if($(this).is(":checked") == false) {
                var id = $(this).val();
                var step = $("#hdn_"+ id).val(); //코너번호
                var imgpath = "";
                if($("#goods"+ id).attr("src")!=sample_imgpath){
                  imgpath = $("#goods_path_"+ id).val(); //상품이미지
                }
                var name = $("#goods_name"+ id).html(); //상품명
                var option = $("#goods_option"+ id).html(); //옵션
                var price = $("#goods_price"+ id).html(); //할인가
                var org_price = $("#goods_org_price"+ id).val(); //정상가
                id_arr.push(id);
                img_path_arr.push(imgpath);
                name_arr.push(name);
                option_arr.push(option);
                price_arr.push(price);
                org_price_arr.push(org_price);
                step_arr.push(step);
                this.checked = true;
              }

            });
            refreshData();
            $('#chkall_text').html('전체코너해제');
            $('.chk_text').html('전체해제');

        }else{
            $(".chk").each(function() {
              if($(this).is(":checked") == true) {
                var id = $(this).val();
                var fromIndex = id_arr.indexOf(id);
                while(fromIndex != -1)  {
                  id_arr.splice(fromIndex, 1);
                  img_path_arr.splice(fromIndex, 1);
                  name_arr.splice(fromIndex, 1);
                  option_arr.splice(fromIndex, 1);
                  price_arr.splice(fromIndex, 1);
                  org_price_arr.splice(fromIndex, 1);
                  step_arr.splice(fromIndex, 1);
                  fromIndex = id_arr.indexOf(id, fromIndex);
                }
                this.checked = false;
              }

            });
            refreshData();
            $('#chkall_text').html('전체코너선택');
            $('.chk_text').html('전체선택');

        }

    }

    function chkSection(step){
        // 전체 갯수
        var wholenum = $("input:checkbox[name=chkIds"+step+"]").length;
        // 선택된 갯수
        var selectnum = $("input:checkbox[name=chkIds"+step+"]:checked").length;
        if(Number(selectnum) < Number(wholenum)){
            $("input:checkbox[name=chkIds"+step+"]").each(function() {
              if($(this).is(":checked") == false) {
                var id = $(this).val();
                var step = $("#hdn_"+ id).val(); //코너번호
                var imgpath = "";
                if($("#goods"+ id).attr("src")!=sample_imgpath){
                  imgpath = $("#goods_path_"+ id).val(); //상품이미지
                }
                var name = $("#goods_name"+ id).html(); //상품명
                var option = $("#goods_option"+ id).html(); //옵션
                var price = $("#goods_price"+ id).html(); //할인가
                var org_price = $("#goods_org_price"+ id).val(); //할인가
                id_arr.push(id);
                img_path_arr.push(imgpath);
                name_arr.push(name);
                option_arr.push(option);
                price_arr.push(price);
                org_price_arr.push(org_price);
                step_arr.push(step);
                this.checked = true;
              }

            });
            refreshData();
            $('#chkall_text_'+step).html('전체해제');

        }else{
            $("input:checkbox[name=chkIds"+step+"]").each(function() {
              if($(this).is(":checked") == true) {
                var id = $(this).val();

                var fromIndex = id_arr.indexOf(id);
                while(fromIndex != -1)  {
                  id_arr.splice(fromIndex, 1);
                  img_path_arr.splice(fromIndex, 1);
                  name_arr.splice(fromIndex, 1);
                  option_arr.splice(fromIndex, 1);
                  price_arr.splice(fromIndex, 1);
                  org_price_arr.splice(fromIndex, 1);
                  step_arr.splice(fromIndex, 1);
                  fromIndex = id_arr.indexOf(id, fromIndex);
                }
                this.checked = false;
              }

            });
            refreshData();
            $('#chkall_text_'+step).html('전체선택');
            $('#chkall_text').html('전체코너선택');
        }

    }

    function popClick(id){

      var chk_yn = $('#chk'+id).is(":checked");
      var imgpath = "";
      if($("#goods"+ id).attr("src")!=sample_imgpath){
        imgpath = $("#goods_path_"+ id).val(); //상품이미지
      }
      var name = $("#goods_name"+ id).html(); //상품명
      var option = $("#goods_option"+ id).html(); //상품명
      var price = $("#goods_price"+ id).html(); //할인가
      var org_price = $("#goods_org_price"+ id).val(); //정상가
      var step = $("#hdn_"+ id).val(); //코너번호
      console.log(id + "-" + chk_yn + "-" + imgpath + "-" + name  + "-" + option  + "-" + price);

      // 전체 갯수
      var wholenum = $("input:checkbox[name=chkIds"+step+"]").length;
      // 선택된 갯수
      var selectnum = $("input:checkbox[name=chkIds"+step+"]:checked").length;

      if(chk_yn){
        id_arr.push(id);
        img_path_arr.push(imgpath);
        name_arr.push(name);
        option_arr.push(option);
        price_arr.push(price);
        org_price_arr.push(org_price);
        step_arr.push(step);
        if(Number(selectnum) == Number(wholenum)){
          $('#chkall_text_'+step).html('전체해제');
        }
      }else{
        var in_arr = [];
        var fromIndex = id_arr.indexOf(id);
        while(fromIndex != -1)  {
          in_arr.push(fromIndex);
          fromIndex = id_arr.indexOf(id, fromIndex+1);
        }
        if(in_arr.length > 0){
          var fromIndex = id_arr.indexOf(id);
          while(fromIndex != -1)  {
            id_arr.splice(fromIndex, 1);
            img_path_arr.splice(fromIndex, 1);
            name_arr.splice(fromIndex, 1);
            option_arr.splice(fromIndex, 1);
            price_arr.splice(fromIndex, 1);
            org_price_arr.splice(fromIndex, 1);
            step_arr.splice(fromIndex, 1);
            fromIndex = id_arr.indexOf(id, fromIndex);
          }
        }
        if($('#chkall_text_'+step).html()=='전체해제'){
          $('#chkall_text_'+step).html('전체선택');
        }
        $('#chkall_text').html('전체코너선택');

      }
      refreshData();
    }


    function refreshData(){
      var cnt = '<?=$goods_num_cnt?>';
      $("#poplist").html('');
      var popwrite = '';
      var firstindex = 0;
      var num = 0;
      for(i=0;i<id_arr.length;i++){
        j=i+1;
        if(j % cnt == 1||cnt == 1){
            popwrite = popwrite + "<div class='pop_list' id='poppage"+i+"'><ul id='popitem'>";
            firstindex = i;
            num = 0;
        }
        num++;
        // var pricewon = price_arr[i].indexOf("원");
        // var won = '';
        // if(pricewon==-1){
        //   won = '원';
        // }
        popwrite = popwrite + "<li><span class='material-icons'>chevron_right</span><div class='goods_infobox'><span>" + name_arr[i] + " </span><span>" + option_arr[i] + " </span><span style='text-decoration:line-through;'>" + org_price_arr[i] + " </span><span>" + price_arr[i] + " </span></div><span class='material-icons btn_popdel' onClick='delitem(\""+i+"\")'>close</span></li>";
        if(j % cnt == 0 || j == id_arr.length ){

          popwrite = popwrite + "</ul>";
          if(num==cnt){
            popwrite = popwrite + "<div class='pop_btn_box'><button class='' onclick='addpage(\""+firstindex+"\",\""+num+"\")'><span class='material-icons'>perm_media</span> POP복사</button><button class='' onclick='delpage(\""+firstindex+"\",\""+num+"\")'><span class='material-icons'>cancel</span> POP삭제</button></div>";
          }
          popwrite = popwrite + "</div>";
        }
      }
      $("#poplist").html(popwrite);

    }

    function truncatePop(){
      id_arr =[];
      img_path_arr =[];
      name_arr =[];
      option_arr =[];
      price_arr =[];
      org_price_arr =[];
      step_arr =[];

      refreshData();
      $('#chkall_text').html('전체코너선택');
      $('.chk_text').html('전체선택');
      $('.chk').attr("checked", false);

    }


    //POP인쇄 데이터 전달
    function printer(type){
      // console.log(style + " - " + spop);
      var url = "/spop/printer/type/"+ type + "?style="+style+"&spop="+spop;
      //alert("url : "+ url); return;
      if(id_arr.length==0){
        alert("pop인쇄할 상품을 추가해주세요.");
        $(".chk").attr("checked", false);
        return;
      }

      //alert("pop_chk_cnt : "+ pop_chk_cnt); return;
      var imgpath = ""; //상품이미지
      var name = ""; //상품명
      var price = ""; //할인가
      var org_price = ""; //정상가
      var option = ""; //옵션명

      var plusoption = '';
      for(i=0;i<id_arr.length;i++){
        if(i==0){
          imgpath = img_path_arr[i];
          // plusoption = '';
          // if(option_arr[i]!=''){
          //   plusoption = " " + option_arr[i];
          // }
          name = name_arr[i];
          option = option_arr[i];
          price = price_arr[i];
          org_price = org_price_arr[i];
        }else{
          imgpath += "§§"+ img_path_arr[i];
          // plusoption = '';
          // if(option_arr[i]!=''){
          //   plusoption = " " + option_arr[i];
          // }

          name += "§§"+ name_arr[i];
          option += "§§"+ option_arr[i];
          price += "§§"+ price_arr[i];
          org_price += "§§"+ org_price_arr[i];
        }
      }
      console.log(imgpath);
      console.log(name);
      console.log(price);
      //return;
      // if(pop_chk_cnt == 0){ //체그된 갯수가 없을 경우
      //   var id = $("#pop_id").val();
      //   imgpath = $("#pre_goods_imgpath_"+ id).html(); //상품이미지
      //   name = $("#goods_name_"+ id).val(); //상품명
      //   price = $("#goods_dcprice_"+ id).val(); //할인가
      //   //console.log("id : "+ id +", name : "+ name +", price : "+ price +", imgpath : "+ imgpath);
      // }else{
      //   $("input[name='pop_chk']:checked").each(function(){
      //     var id = $(this).val();
      //     if(imgpath != ""){
      //       imgpath += "§§"+ $("#pre_goods_imgpath_"+ id).html(); //상품이미지
      //       name += "§§"+ $("#goods_name_"+ id).val(); //상품명
      //       price += "§§"+ $("#goods_dcprice_"+ id).val(); //할인가
      //     }else{
      //       imgpath = $("#pre_goods_imgpath_"+ id).html(); //상품이미지
      //       name = $("#goods_name_"+ id).val(); //상품명
      //       price = $("#goods_dcprice_"+ id).val(); //할인가
      //     }
      //     //console.log("id : "+ id +", name : "+ name +", price : "+ price +", imgpath : "+ imgpath);
      //   });
      // }
      //console.log("name : "+ name +", price : "+ price +", imgpath : "+ imgpath);
      //alert("printer(type) OK"); return;
      //alert("arr_data : "+ JSON.stringify(arr_data)); return;

      var pop_title = "printer";
          window.open("", pop_title);

      var form = document.createElement("form");
      document.body.appendChild(form);
      form.setAttribute("method", "POST");
      form.setAttribute("action", url);
      form.setAttribute("target", pop_title);

      var field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "tmp_imgpath");
      field.setAttribute("value", imgpath); //이미지경로
      form.appendChild(field);

      var field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "tmp_name");
      field.setAttribute("value", name); //상품명
      form.appendChild(field);

      var field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "tmp_option");
      field.setAttribute("value", option); //옵션명
      form.appendChild(field);

      var field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "tmp_org_price");
      field.setAttribute("value", org_price); //정상가
      form.appendChild(field);

      var field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "tmp_price");
      field.setAttribute("value", price); //할인가
      form.appendChild(field);

      field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
      field.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
      form.appendChild(field);

      form.submit();

    }
</script>
