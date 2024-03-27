<?
$userAgent = $_SERVER["HTTP_USER_AGENT"]; //웹사이트에 접속한 사용자의 정보
$ie_yn = $this->funn->fnIeYn($userAgent);
//echo "userAgent : ". $userAgent ."<br>";
//echo "ie_yn : ". $ie_yn ."<br>";
?>
<? if($ie_yn == "Y"){ ?>
<div class="api_none">
  <p>
    <i class="xi-error-o"></i> 익스플로러는 kakao 지도 API 정책에 따라 기능을 지원하지 않습니다. 크롬 사용을 권장드립니다.<br  /><br  />
    <a href="https://www.google.com/chrome/" target="_blank">[ 구글 크롬 설치하러가기 ]</a>
  </p>
</div>
<? }else{ //if($ie_yn == "Y"){ ?>
<div class="wrap_order">
  <div class="s_tit">
    배달분포 주소추가
  </div>
  <div class="order_content">
    <div class="white_box">
      <input type="tel" id="tel" placeholder="전화번호 입력" value="" onkeypress="if(event.keyCode==13){ search(); }">
      <button class="btn md color" onclick="search();">조회</button>
    </div>
    <div id="search_top" class="s_tit2" style="display:none;">
      검색결과 (총 <span id="search_num" class="total_num">0</span> 건)
    </div>
    <ul id="search_result" class="search_result" style="display:none;"><?//검색결과 영역?>
    </ul>
    <div class="s_tit2">
      추가고객목록 (총 <span id="total_num" class="total_num"><?=number_format($total_rows)?></span> 건)
      <div class="s_tit2_fr">
        <span>지도표시기간</span>
        <select id="day" onchange="chgDay(this.value);">
          <option value="1">1일</option>
          <option value="2"<?=($this->member->item('mem_mapadd_day') == "2") ? " selected" : ""?>>2일</option>
          <option value="3"<?=($this->member->item('mem_mapadd_day') == "3") ? " selected" : ""?>>3일</option>
        </select>
      </div>
    </div>
    <ul id="order_maplist" class="order_maplist">
      <?
		if(!empty($data_list)){
			foreach($data_list as $r){
      ?>
      <li id="insli<?=$r->ma_id?>">
        <div class="map_info">
          <p class="phnum"><?=trim($r->ab_name ." ". $this->funn->format_phone($r->ab_tel, "-"))?></p>
          <p class="map_address"><?=$r->ab_addr?></p>
        </div>
        <span class="btn_map">
          <button class="btn_map_del" onclick="del('<?=$r->ma_id?>');"><i class="xi-minus-circle-o"></i> 지도삭제</button>
        </span>
      </li>
      <?
			}
		}else{
      ?>
      <li>
		<div>추가된 고객이 없습니다.</div>
      </li>
      <?
		}
      ?>
    </ul>
    <p class="btn_al_cen">
      <button class="btn_map_go" onclick="location.href='/mall/order/map'"><i class="xi-maker"></i> 배달분포 지도 바로가기</button>
    </p>
  </div>
</div>
<script>
	//검색
	function search(){
		var tel = $("#tel").val().trim();
		if(tel == ""){
			alert("전화번호를 입력 후 조회 버튼을 클릭하세요.");
			$("#tel").focus();
			return;
		}
		$.ajax({
			url: "mapadd_search",
			type: "POST",
			data: {"tel" : tel.replace("-", "").replace(".", ""), "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$("#search_top").show();
				$("#search_result").show();
				var no = 0;
				var html = "";
				$.each(json, function(key, value){
					var ab_tel = value.ab_tel.replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-"); //전화번호
					var ab_addr = value.ab_addr; //주소
					if(ab_addr == "") ab_addr = "등록된 주소가 없습니다.";
					html += "<li id='schli"+ no +"'>";
					html += "  <div class=\"map_info\">";
					html += "    <p class=\"phnum\">"+ trim(value.ab_name +" "+ ab_tel) +"</p>";
					html += "    <p class=\"map_address\">"+ ab_addr +"</p>";
					html += "  </div>";
					if(value.ab_addr != ""){
						html += "  <span class=\"btn_map\">";
						html += "    <button class=\"btn_map_add\" onclick=\"add('"+ no +"', '"+ value.ab_tel +"')\"><i class=\"xi-plus-circle-o\"></i> 지도등록</button>";
						html += "  </span>";
					}
					html += "</li>";
					no++;
				});
				//alert("no : "+ no);
				if(no == 0){
					html += "<li>";
					html += "  <div>검색된 고객이 없습니다.</div>";
					html += "</li>";
				}else{
					$("#search_num").html(no);
				}
				$("#search_result").html(html);
			}
		});
	}

	//지도등록
	function add(no, tel){
		$.ajax({
			url: "mapadd_add",
			type: "POST",
			data: {"tel" : tel, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				var ii = 0;
				var html = "";
				showSnackbar("등록 되었습니다.", 1500);
				$("#schli"+ no).hide();
				var search_num = $("#search_num").html();
				if(search_num == 1){
					var htm = "";
					htm += "<li>";
					htm += "  <div>등록 가능한 고객이 없습니다.</div>";
					htm += "</li>";
					$("#search_result").html(htm);
				}
				$("#search_num").html(search_num-1);
				$.each(json, function(key, value){
					var ab_tel = value.ab_tel.replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-"); //전화번호
					var ab_addr = value.ab_addr; //주소
					if(ab_addr == "") ab_addr = "등록된 주소가 없습니다.";
					html += "<li id='insli"+ value.ma_id +"'>";
					html += "  <div class=\"map_info\">";
					html += "    <p class=\"phnum\">"+ trim(value.ab_name +" "+ ab_tel) +"</p>";
					html += "    <p class=\"map_address\">"+ ab_addr +"</p>";
					html += "  </div>";
					if(value.ab_addr != ""){
						html += "  <span class=\"btn_map\">";
						html += "    <button class=\"btn_map_del\" onclick=\"del('"+ value.ma_id +"')\"><i class=\"xi-plus-circle-o\"></i> 지도삭제</button>";
						html += "  </span>";
					}
					html += "</li>";
					ii++;
				});
				//alert("ii : "+ ii);
				if(ii == 0){
					html += "<li>";
					html += "  <div>추가된 고객이 없습니다.</div>";
					html += "</li>";
				}else{
					$("#total_num").html(ii);
				}
				$("#order_maplist").html(html);
			}
		});
	}

	//지도삭제
	function del(id){
		$.ajax({
			url: "mapadd_del",
			type: "POST",
			data: {"id" : id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(json.code == "0"){
					$("#insli"+ id).hide();
					var total_num = $("#total_num").html();
					$("#total_num").html(total_num-1);
					showSnackbar("삭제 되었습니다.", 1500);
				}else{
					alert("처리중 오류가 발생하였습니다.");
				}
			}
		});
	}

	//지도표시기간 변경
	function chgDay(day){
		//alert("day : "+ day); return;
		$.ajax({
			url: "mapadd_day",
			type: "POST",
			data: {"day" : day, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(json.code == "0"){
					showSnackbar("지도 표시 기간이 변경되었습니다.", 1500);
					setTimeout(function() {
						location.reload();
					}, 1000); //1초 지연
				}else{
					alert("처리중 오류가 발생하였습니다.");
				}
			}
		});
	}
</script>
<? } //if($ie_yn == "Y"){ ?>
