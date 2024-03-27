<?
	// $mem_stmall_yn = $shop->mem_stmall_yn; //스마트전단 주문하기 사용여부
	// $psd_order_yn = $screen_data->psd_order_yn; //주문하기 사용여부
	// $psd_order_sdt = $screen_data->psd_order_sdt; //주문하기 시작일자
	// $psd_order_edt = $screen_data->psd_order_edt; //주문하기 종료일자
	// $stmall_yn = $this->funn->get_stmall_yn($mem_stmall_yn, $psd_order_yn, $psd_order_sdt, $psd_order_edt); //주문하기 사용여부
	//echo "stmall_yn : ". $stmall_yn ."<br>";
?>
<!-- 애니메이션 추가 -->
<script type="text/javascript" }
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" type="text/css" href="/views/_layout/bootstrap/css/CSS_Ani.css">
<script>
    // 스크롤 애니메이션
    new WOW().init();
</script>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
<?
    $cnt = 0;
    if(!empty($link->psd_url)){
        $cnt++;
    }
    if((!empty($link->html)&&!empty($link->url)||$link->mst_id==0&&!empty($link->html))&&$link->html != '<p><br></p>' && $link->html != '<p><span style="font-size: 18px;">﻿</span><br></p>'){
        $cnt++;
    }
    if(!empty($link->pcd_url)){
        $cnt++;
    }
    if(!empty($link->order_url)){
        $cnt++;
    }
?>
  <div class="fix_bot_btn fix_bot_btn smart_btn<?=$cnt?> shiny-btn1">
    <? if(!empty($link->psd_url)){ ?>
    <a class="btn_smartevent wow animate__animated animate__pulse" href="<?="/smart/view/".$link->psd_code."?home=".$key?>">행사보기</a>
    <? } ?>
     <? if((!empty($link->html)&&!empty($link->url)||$link->mst_id==0&&!empty($link->html))&&$link->html != '<p><br></p>' && $link->html != '<p><span style="font-size: 18px;">﻿</span><br></p>'){ ?>
    <a class="btn_smarteditor wow animate__animated animate__pulse" href="<?='/at/'.$link->short_url."?home=".$key?>">전단보기</a>
    <? } ?>
    <? if(!empty($link->pcd_url)){ ?>
    <a class="btn_smartcoupon wow animate__animated animate__pulse" href="<?="/smart/coupon/".$link->pcd_code."?home=".$key?>">쿠폰보기</a>
    <? } ?>
    <? if(!empty($link->order_url)){ ?>
    <a class="btn_smartcoupon wow animate__animated animate__pulse" href="<?=$link->order_url?>">주문하기</a>
    <? } ?>
  </div>

  <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
  <div class="smart_header">
    <span class="logo"><img src="/dhn/images/logo_v2.png" alt="<?=config_item('site_name')?>"></span>
    <!--<span class="header_txt"><?=config_item('site_full_name')?></span>-->

		<!-- <div class="smart_tmenu" onclick="openNav()">
			 &#9776;
		</div> -->
		<!-- <div class="smart_search" onclick="search_box_onoff();"><span class="material-icons">search</span></div> -->
    <div id="link_menu" class="icon_share"><span class="material-icons">share</span></div>
		<div id="mySidenav" class="sidenav">
			<span class="sidenav_txt"><span class="material-icons">store_mall_directory</span> 행사바로가기</span>

			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <? if(!empty($link->psd_url)){ ?>
            <a href="javascript:void(0)" onclick="location.href='<?="/smart/view/".$link->psd_code."?home=".$key?>';"><span class="material-icons">arrow_forward</span> 행사보기</a>
            <? } ?>
            <? if((!empty($link->html)&&!empty($link->url)||$link->mst_id==0&&!empty($link->html))&&$link->html != '<p><br></p>' && $link->html != '<p><span style="font-size: 18px;">﻿</span><br></p>'){ ?>
            <a href="javascript:void(0)" onclick="location.href='<?="/at/".$link->short_url."?home=".$key?>';"><span class="material-icons">arrow_forward</span> 전단보기</a>
            <? } ?>
            <? if(!empty($link->pcd_url)){ ?>
            <a href="javascript:void(0)" onclick="location.href='<?="/smart/coupon/".$link->pcd_code."?home=".$key?>';"><span class="material-icons">arrow_forward</span> 쿠폰보기</a>
            <? } ?>
            <? if(!empty($link->order_url)){ ?>
            <a href="javascript:void(0)" onclick="location.href='<?=$link->order_code?>';"><span class="material-icons">arrow_forward</span> 주문하기</a>
            <? } ?>
		</div>
	<script>
	function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  }
  function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  }
  var igenie_path = "<?=config_item('igenie_path')?>";
  </script>

  </div>
  <div class="smart_menu_s" style="display:none;">
    <span onclick="search_back();"><i class="material-icons">arrow_back</i></span>
    <span class="smart_title">검색</span>
  </div>
	<div class="smart_search_box" style="display:none;">
		<input type="search" value="" id="search_input" autocomplete="off" onkeypress="" onkeyup="" placeholder="검색어를 입력하세요">
		<button id="id_searchBtn" onclick="search_goods();">
				<i class="material-icons">search</i>
		</button>
	</div>
	<div class="smart_search_result" style="display:none;">
		<p class="smart_search_tit">
			<span id="search_keyword">"오뚜기"</span>에 대한 검색결과
		</p>
        <div id="search_item">
    		<dl>
    			<dt>
    				<img src="http://kakaobrandtalk.com/uploads/goods/78a834fe621dbcb2278f583e5ad31e51.jpg" />
    			</dt>
    			<dd>
    				<ul>
    					<li>오뚜기진라면 순한맛/매운맛</li>
    					<li>5,900원</li>
    					<li>5,900원</li>
    				</ul>
    			</dd>
    			<button class="icon_add_cart" onclick="addcart('2077565')">장바구니</button>
    		</dl>
        </div>
	</div>

    <div id="link_modal" class="modal">
    <div id="modal_contents" class="modal-content w80">
			<p class="share_tit">
				공유하기
			</p>
			<ul class="share_icon">
				<li>
					<a href="javascript:;" id="kakao-link-btn" onclick="close_pop();">
        	            <!-- 버튼이 생기는 부분, id는 맘대로 쓰시되 아래 js 코드도 동일하게 적용해주셔야 합니다. -->
        	            <!-- <img width="60" height="60" src="//developers.kakao.com/assets/img/about/logos/kakaolink/kakaolink_btn_medium.png" />--> <!-- 톡 이미지 부분이고, 전 kakaolink_btn_small.png로 불러왔습니다.   -->
        	  <p class="share_icon_img"><img src="/img/sp_spi_icons_kakao.png" alt=""/></p>
						<p class="share_icon_txt">
							카카오톡
						</p>
					</a>
				</li>
                <li><a href="javascript:toSNS('facebook','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="페이스북으로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_face.png"></p><p class="share_icon_txt">페이스북</p></a></li>
                <li><a href="javascript:toSNS('twitter','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="트위터로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_tw.png"></p><p class="share_icon_txt">트위터</p></a></li>
                <li><a href="javascript:toSNS('line','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="라인으로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_line.png"></p><p class="share_icon_txt">라인</p></a></li>
                <!-- <li><a href="javascript:toSNS('pholar','공유제목!','http://단축URL')" title="폴라로 가져가기"><img src="/img/pholar.jpg"></a></li> -->
                <!-- <li><a href="javascript:toSNS('pinterest','공유제목!','http://단축URL')" title="핀터레스트로 가져가기"><img src="/img/pinterest.jpg"></a></li> -->
                <li><a href="javascript:toSNS('band','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="밴드로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_band.png"></p><p class="share_icon_txt">밴드</p></a></li>
                <!-- <li><a href="javascript:toSNS('google','공유제목!','http://http://단축URL')" title="구글플러스로 가져가기"><img src="/img/google.jpg"></a></li> -->
                <!-- <li><a href="javascript:toSNS('blog','공유제목!','http://http://단축URL')" title="네이버블로그로 가져가기"><img src="/img/blog.jpg"></a></li> -->
				<li>
					<a id="clipboard_copy">
	                    <p class="share_icon_img"><img src="/img/icon_urlcopy_36.svg" alt=""/></p>
						<p class="share_icon_txt">
							URL복사
						</p>
	                </a>
				</li>
			</ul>
        <div class="share_close" onClick="close_pop();">
           <span class="pop_bt material-icons">close</span>
        </div>
    </div>
</div>
	<div class="smart_box">
  <div class="smart_top" style="margin-top: 55px; background: url(<? if($shop->mem_shop_img != ""){ ?><?=config_item('igenie_path').$shop->mem_shop_img?><? }else{ ?>/dhn/images/mi_pick.jpg<? } ?>)no-repeat; background-size: cover;">
    <!-- <a class="icon_call" href="tel:<?=$member->mem_shop_phone?>"><img src="/images/icon_call.png"/></a> -->
		<!-- <p class="mart_name"><?=$member->mem_username?></p><?//매장 이름 ?> -->
        <a class="btn_smartcall" href="tel:<?=$shop->mem_shop_phone?>"><span class="material-icons">phone_in_talk</span></a>
  </div>
  <div class="mart_homemenu">
      <ul>
          <li><a class="tablinks" onclick="openMenu(event, 'menu01')" id="defaultOpen">매장안내</a></li>
          <li><a class="tablinks" onclick="openMenu(event, 'menu02')">공지사항</a></li>
          <li><a class="tablinks" onclick="openMenu(event, 'menu03')">이용안내</a></li>
          <li><a class="tablinks" onclick="openMenu(event, 'menu04')">오시는길</a></li>
      </ul>
  </div>
  <div id="menu01" class="shome_content">
      <p class="mart_logo" style="font-size:<?=mb_strlen($shop->mem_username, 'UTF-8') > 10 ? '20px' : '30px'?>"><?=$shop->mem_username?></p><?//매장 이름 ?>
      <p class="btn_kakao_ch">
         <a href="http://pf.kakao.com/<?=$puid?>" target="_blank">카카오톡 채널추가</a>
      </p>
      <div class="notice_box" <?=(!empty($shop->mem_notice))? "" : "style='display:none;'" ?>>
          <span class="material-icons">campaign</span> <?=$shop->mem_notice?>
      </div>
      <div class="smart_info">
        <?=nl2br($shop->mem_shop_intro)?>
      </div><?//매장 소개글 ?>

      <div class="smart_bot" style="margin-bottom:20px;">
    	  <ul>
            <!-- <li>
                <span class="material-icons">send</span> kakao : <span class="kakao_add">https://pf.kakao.com/_gxcCyC</span><a class="btn_kakao_ch2" href="" target="_blank">채널추가</a>
            </li> -->
    		<li>
    		 <span class="material-icons">storefront</span> 운영시간 : <?=$shop->mem_shop_time?>
    		</li>
    		<li>
    		  <span class="material-icons">coffee</span> 휴무일 : <?=$shop->mem_shop_holiday?>
    		</li>
    		<li>
    		  <span class="material-icons">headset</span> 전화번호 : <a class="font_num" href="tel:<?=$shop->mem_shop_phone?>"><?=$shop->mem_shop_phone?></a>
    		</li>
    		<li>
    		  <span class="material-icons">place</span> 주소 : <?=$shop->mem_shop_addr?>
    		</li>
    	  </ul>
      </div><!--//smart_bot-->

</div>

<div id="menu02" class="shome_content" style="min-height:350px;">
  <? if($shop->mem_shop_notice == '' || $shop->mem_shop_notice == '<p><br></p>' || $shop->mem_shop_notice == '<p><span style="font-size: 18px;">﻿</span><br></p>'){
      ?>
      <span class="notice_no">현재 등록된 공지사항이 없습니다.</span>
      <?
    }else{
        echo nl2br($shop->mem_shop_notice);

    }
  ?>
</div>

<div id="menu03" class="shome_content">

</div>

<div id="menu04" class="shome_content">
    <div class="smart_map" id="smart_map" style="width:100%;height:250px">
    </div><!-- 카카오맵 - 지도 -->
</div>
<div style="margin-bottom:60px;">
<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
<div style="height:60px;"></div>
</div>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=config_item('kakao_map_appkey')?>&libraries=services"></script>
<script>
function openMenu(evt, menuName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("shome_content");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(menuName).style.display = "block";
  evt.currentTarget.className += " active";

  if(menuName == "menu03"){
      var info_html = "<img src='/images/info_01.jpg' alt='이용안내'>"+"<img src='/images/info_02.jpg' alt='이용안내'>"+"<img src='/images/info_03.jpg' alt='이용안내'>";
      $("#menu03").html(info_html);
  }
  if(menuName == "menu04"){
      //카카오맵 - 지도
  	 var mapContainer = document.getElementById('smart_map'), // 지도를 표시할 div
  	 mapOption = {
  	 	center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
  	 	level: 3 // 지도의 확대 레벨
  	 };

  	// 지도를 생성합니다
  	 var map = new kakao.maps.Map(mapContainer, mapOption);

  	// 주소-좌표 변환 객체를 생성합니다
  	 var geocoder = new kakao.maps.services.Geocoder();

  	// 주소로 좌표를 검색합니다
  	 geocoder.addressSearch('<?=$shop->mem_shop_addr?>', function(result, status) {
  	 	// 정상적으로 검색이 완료됐으면
  	 	 if (status === kakao.maps.services.Status.OK) {
  	 		var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
  	 		// 결과값으로 받은 위치를 마커로 표시합니다
  	 		var marker = new kakao.maps.Marker({
  	 			map: map,
  	 			position: coords
  	 		});
  	 		// 인포윈도우로 장소에 대한 설명을 표시합니다
  	 		var infowindow = new kakao.maps.InfoWindow({
  	 			content: '<div style="width:200px;text-align:center;padding:3px 0; font-size:14px;"><?=$shop->mem_username?></div>'
  	 		});
  	 		infowindow.open(map, marker);
  	 		// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
  	 		map.setCenter(coords);
  	 	}
  	 });
  }
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

  <!-- <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div> -->
	</div><!--//smart_box-->
</div><!--//wrap_smart-->

<script>


	//장바구니 추가
	// function addcart(goodsid){
	// 	//alert("addcart(goodsid) > goodsid : "+ goodsid); return;
	// 	var qty = 1; //주문갯수
	// 	$.ajax({
	// 		url: "/smart/addcart",
	// 		type: "POST",
	// 		data: {"shop_mem_id" : "<?=$shop->mem_id?>", "psdid" : "<?=$screen_data->psd_id?>", "goodsid" : goodsid, "qty" : qty, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	// 		success: function (json){
	// 			if(json.code == "0"){
	// 				$("#cart_count").html(json.cartcnt); //주문건수 표기
	// 				showSnackbar("장바구니에 추가되었습니다.", 1000);
	// 			} else {
	// 				showSnackbar("장바구니에 담지 못했습니다.<br>"+ json.message, 2000);
	// 			}
	// 		}
	// 	});
	// }

	//장바구니 이동
	// function cart(){
	// 	var url = "/smart/cart?code=<?=$code?>";
	// 	var ScreenShotYn = "<?=$ScreenShotYn?>";
	// 	if(ScreenShotYn != "") url += "&ScreenShotYn="+ ScreenShotYn;
	// 	location.href = url;
	// }

	//바로가기 클릭이벤트
	function tit_text_info_click(num){
		if(num=="t"){
			var target =	document.getElementById("pre_templet_bg");
		}else{
			var target =	document.getElementById("pre_box_n_"+num);
		}
		//바로가기위치 상대좌표 위치
		var clientRect = target.getBoundingClientRect();
		var relativeTop = clientRect.top;
		//바로가기위치 절대좌표 위치
		var scrolledTopLength = window.pageYOffset;
		var absoluteTop = scrolledTopLength + relativeTop;
		//바로가기위치 고정바 제외
		var menuRelativeTop = absoluteTop - 55;
		window.scrollTo(0,menuRelativeTop);
		closeNav();
	}

    function search_box_onoff(){

        if($(".smart_menu_s").is(':visible') == false){
            $(".smart_menu_s").show();
            $(".smart_search_box").show();
            if($("#search_input").val()!=""){
                $(".smart_search_result").show();
                $(".smart_box").hide();
            }
            $(".smart_top").css("margin-top", "161px");
        }else{
            $(".smart_menu_s").hide();
            $(".smart_search_box").hide();
            $(".smart_search_result").hide();
            $(".smart_top").css("margin-top", "");
            $(".smart_box").show();
        }

    }

    function search_goods(){
		//alert("addcart(goodsid) > goodsid : "+ goodsid); return;
		var word = $("#search_input").val(); //검색어
        word = word.trim();
        if(word==""){
            showSnackbar("검색어를 입력하세요.", 1000);
            // alert("검색어를 입력하세요");
            $("#search_input").focus();
            return;
        }
        var html ="";
		$.ajax({
			url: "/smart/search_goods",
			type: "POST",
			data: {"word" : word, "psdid" : "<?=$screen_data->psd_id?>", "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json){
				if(json.code == "0"){
					// $("#cart_count").html(json.cartcnt); //주문건수 표기
					// showSnackbar(".", 1000);
                    $.each(json.search, function(index, item) { // 데이터 =item
                        html += "<dl>";
                        html += "<dt><img src='"
                        if(item.psg_imgpath){
                            var file_ext = item.psg_imgpath.substring(item.psg_imgpath.lastIndexOf('.'));
                            var file_name = item.psg_imgpath.substring(0, item.psg_imgpath.lastIndexOf('.'));

                            var temp_psg_imgpath = "";
							if (item.psg_imgpath.lastIndexOf('_thumb') > 0) {
								temp_psg_imgpath = item.psg_imgpath;
							} else {
								temp_psg_imgpath = file_name + '_thumb' + file_ext;
							}

                            // html += item.psg_imgpath;
                            html += igenie_path + temp_psg_imgpath;
                        }else{
                            html += "/dhn/images/leaflets/sample_img.jpg";
                        }
                        html += "' /></dt>";
                        html += "<dd><ul>";
                        html += "<li>"+item.psg_name+ " " + item.psg_option + "</li>";
                        html += "<li>"+item.psg_price+"</li>";
                        html += "<li>"+item.psg_dcprice+"</li>";
                        html += "</ul></dd>";
                        <? if($cart_yn == "Y"&&$r->psg_stock != "N"){ //스마트전단 주문하기 사용의 경우 ?>
                        html += "<button class='icon_add_cart' onclick='addcart(\""+item.psg_id+"\")'>장바구니</button>";
                        <? } ?>
                        html += "</dl>";
    					 // index가 끝날때까지
    					// $("#demo").append(item.name + " ");
    					// $("#demo").append(item.age + " ");
    					// $("#demo").append(item.address + " ");
    					// $("#demo").append(item.phone + "<br>");
    				});
                    if(html){
                        $("#search_item").html(html);
                    }else{
                        $("#search_item").html("검색결과가 없습니다.");
                    }
                    $("#search_keyword").text("\""+word+"\"");
                    $(".smart_search_result").show();
                    $(".smart_box").hide();

				} else {
					showSnackbar("검색 결과가 없습니다.", 2000);
				}
			}
		});
	}

    function search_back(){
        $(".smart_menu_s").hide();
        $(".smart_search_box").hide();
        $(".smart_search_result").hide();
        $(".smart_top").css("margin-top", "");
        $("#search_item").html("");
        $("#search_input").val("");
        $(".smart_box").show();
    }



    // $(document).on("click", function(e){
    //     if (e.target.id != "modal_contents" && e.target.id != "link_menu"){
    //         $('#link_modal').hide();
    //     }
    // });

    function copyText() {
      const temp = document.createElement('textarea');

      document.body.appendChild(temp);

      temp.value = '<?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>';
      temp.select();

      document.execCommand('copy');
      document.body.removeChild(temp);

      showSnackbar("클립보드에 복사되었습니다.", 1500);
      $('#link_modal').hide();
    }



    $( document ).ready(function() {
        const button = document.querySelector('#clipboard_copy');

        button.addEventListener('click', copyText);
        $('#link_menu').on('click', function() {
            $('#link_modal').show();
        });
    });
    function close_pop() {
        $('#link_modal').hide();
    };

     Kakao.init('<?=config_item('kakao_map_appkey')?>');
     // // 카카오링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
     Kakao.Link.createDefaultButton({
       container: '#kakao-link-btn',  // 컨테이너는 아까 위에 버튼이 쓰여진 부분 id
       objectType: 'feed',
       content: {  // 여기부터 실제 내용이 들어갑니다.
         title: '<?=$screen_data->psd_title?>', // 본문 제목
         description: '<?=$shop->mem_username?> 스마트전단',  // 본문 바로 아래 들어가는 영역?
         imageUrl: '<? if($shop->mem_shop_img != ""){ ?><?=config_item("base_url")?><?=$shop->mem_shop_img?><? }else{ ?>/dhn/images/mi_pick.jpg<? } ?>', // 이미지
         link: {
           mobileWebUrl: '<?=current_url()?>',
           webUrl: '<?=current_url()?>'
         }
       },
       buttons: [
         {
           title: '바로가기',
           link: {
             mobileWebUrl: '<?=current_url()?>',
             webUrl: '<?=current_url()?>'
           }
         },
       ]
     });


     function toSNS(sns, strTitle, strURL) {
         var snsArray = new Array();
         var strMsg = strTitle + " " + strURL;
         // var image = "이미지경로";

         snsArray['twitter'] = "http://twitter.com/home?status=" + encodeURIComponent(strTitle) + ' ' + encodeURIComponent(strURL);
         snsArray['facebook'] = "http://www.facebook.com/share.php?u=" + encodeURIComponent(strURL);
         // snsArray['pinterest'] = "http://www.pinterest.com/pin/create/button/?url=" + encodeURIComponent(strURL) + "&media=" + image + "&description=" + encodeURIComponent(strTitle);
         snsArray['band'] = "http://band.us/plugin/share?body=" + encodeURIComponent(strTitle) + "  " + encodeURIComponent(strURL) + "&route=" + encodeURIComponent(strURL);
         // snsArray['blog'] = "http://blog.naver.com/openapi/share?url=" + encodeURIComponent(strURL) + "&title=" + encodeURIComponent(strTitle);
         snsArray['line'] = "http://line.me/R/msg/text/?" + encodeURIComponent(strTitle) + " " + encodeURIComponent(strURL);
         // snsArray['pholar'] = "http://www.pholar.co/spi/rephol?url=" + encodeURIComponent(strURL) + "&title=" + encodeURIComponent(strTitle);
         // snsArray['google'] = "https://plus.google.com/share?url=" + encodeURIComponent(strURL) + "&t=" + encodeURIComponent(strTitle);
         window.open(snsArray[sns]);
     }

</script><? //$_SESSION['user_cart'] = ""; ?>

<link rel="stylesheet" href="/views/smart/bootstrap/css/effect-js.min.css" />
<script src="/views/smart/bootstrap/css/effect-js.min.js"></script>
