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
<div id="smart_map" style="<?=($this->cbconfig->get_device_type() == "mobile") ? "width:100%;height:800px;" : "width:100%;height:1720px;"?>">
  <div class="map_dropup">
    <button class="dropbtn">반경선택</button>
    <div class="dropup-content">
      <a href="javascript:setMapCircle(100, 1);">100m</a>
      <a href="javascript:setMapCircle(200, 2);">200m</a>
      <a href="javascript:setMapCircle(500, 3);">500m</a>
      <a href="javascript:setMapCircle(1000, 4);">1Km</a>
      <a href="javascript:setMapCircle(2000, 5);">2Km</a>
      <a href="javascript:setMapCircle(5000, 6);">5Km</a>
      <a href="javascript:setMapCircle(10000, 7);">10Km</a>
    </div>
  </div>
  <button class="btn_mapadd_go" onclick="location.href='/mall/order/mapadd'"><i class="xi-maker"></i> 배달주소등록</button>
</div>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=config_item('kakao_map_appkey')?>&libraries=services"></script>
<script>
var mapNo = 0;
var mapCircle;
var mapContainer = document.getElementById('smart_map'), // 지도를 표시할 div
mapOption = {
	center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
	level: 5 // 지도의 확대 레벨
};
// 지도를 생성합니다
var map = new kakao.maps.Map(mapContainer, mapOption);

// 주소-좌표 변환 객체를 생성합니다
var geocoder = new kakao.maps.services.Geocoder();

// 주소로 좌표를 검색합니다
<?
	if(!empty($addr_list)){
		foreach($addr_list as $r){
			$flag = $r->flag; //구분(0.주소추가, 1.주문)
			if($flag == "0"){ //주소추가
				$info = trim($r->receiver ." ". $this->funn->format_phone($r->phnno, "-")); //이름 전화번호
			}else{ //주문
				$info = $r->receiver ." (". $r->id .") ". $this->funn->get_order_status($r->status); //이름 (주문번호) 진행상태
			}
?>
geocoder.addressSearch('<?=trim($r->addr)?>', function(result, status) {
    // 정상적으로 검색이 완료됐으면
     if (status === kakao.maps.services.Status.OK) {
        mapNo++;
		//var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
        // 결과값으로 받은 위치를 마커로 표시합니다
        //var marker = new kakao.maps.Marker({
        //    map: map,
        //    position: coords
        //});
        // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
        //map.setCenter(coords);
		//if(mapNo <= 2) alert("["+ mapNo +"] y : "+ result[0].y +", x : "+ result[0].x);

		//마커 표시 [S] -------------------------------------------------------------------------------------------
		// 지도마커 이미지
		var imageSrc = '/images/map_icon<?=($flag == "0") ? "_c" : ""?>.png', // 마커이미지의 주소입니다
		imageSize = new kakao.maps.Size(38, 60), // 마커이미지의 크기입니다
		imageOption = {offset: new kakao.maps.Point(19, 55)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

		// 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
		var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
		markerPosition = new kakao.maps.LatLng(result[0].y, result[0].x); // 마커가 표시될 위치입니다

		// 마커를 생성합니다
		var marker = new kakao.maps.Marker({
			position: markerPosition,
			image: markerImage // 마커이미지 설정
		});

		// 인포윈도우로 장소에 대한 설명을 표시합니다
		var infowindow = new kakao.maps.InfoWindow({
			content: '<div style="width:185px;text-align:center;padding:3px 0; font-size:14px;"><?=$info?></div>' //최소 width 150px
		});
		infowindow.open(map, marker);

		// 마커가 지도 위에 표시되도록 설정합니다
		marker.setMap(map);
		//마커 표시 [E] -------------------------------------------------------------------------------------------
    }
});
<?
		}
	}
?>

geocoder.addressSearch('<?=$this->member->item("mem_shop_addr")?>', function(result, status) {
	// 정상적으로 검색이 완료됐으면
	 if (status === kakao.maps.services.Status.OK) {
		var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
		// 결과값으로 받은 위치를 마커로 표시합니다
		//var marker = new kakao.maps.Marker({
		//	map: map,
		//	position: coords
		//});
		map.setCenter(coords);
	}
});

// 지도에 표시할 원을 생성합니다
function setMapCircle(radius, level){
	//지도 레벨 세팅
	map.setLevel(level);

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch('<?=$this->member->item("mem_shop_addr")?>', function(result, status) {
		// 정상적으로 검색이 완료됐으면
		 if (status === kakao.maps.services.Status.OK) {
			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
			// 결과값으로 받은 위치를 마커로 표시합니다
			//var marker = new kakao.maps.Marker({
			//	map: map,
			//	position: coords
			//});
			// 인포윈도우로 장소에 대한 설명을 표시합니다
			//var infowindow = new kakao.maps.InfoWindow({
			//	content: '<div style="width:200px;text-align:center;padding:3px 0; font-size:14px;"><?=$this->member->item("mem_username")?></div>'
			//});
			//infowindow.open(map, marker);
			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			//map.setCenter(coords);

			//마커 표시 [S] -------------------------------------------------------------------------------------------
			// 지도마커 이미지
			var imageSrc = '/images/map_icon_m.png', // 마커이미지의 주소입니다
			imageSize = new kakao.maps.Size(38, 60), // 마커이미지의 크기입니다
			imageOption = {offset: new kakao.maps.Point(19, 55)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

			// 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
			var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
			markerPosition = new kakao.maps.LatLng(result[0].y, result[0].x); // 마커가 표시될 위치입니다

			// 마커를 생성합니다
			var marker = new kakao.maps.Marker({
				position: markerPosition,
				image: markerImage // 마커이미지 설정
			});

			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div style="width:200px;text-align:center;padding:3px 0; font-size:14px;"><?=$this->member->item("mem_username")?></div>' //최소 width 150px
			});
			infowindow.open(map, marker);

			// 마커가 지도 위에 표시되도록 설정합니다
			marker.setMap(map);
			//마커 표시 [E] -------------------------------------------------------------------------------------------

			//원 표시 [S] ---------------------------------------------------------------------------------------------
			if (mapCircle) { // 최초 실행시에는 mapCircle이 없을테니 예외처리를 해줍니다.
				mapCircle.setMap(null);
			}
			mapCircle = new kakao.maps.Circle({
				center : coords,  // 원의 중심좌표 입니다
				radius: radius, // 미터 단위의 원의 반지름입니다
				strokeWeight: 5, // 선의 두께입니다
				strokeColor: '#ebae34', // 선의 색깔입니다
				strokeOpacity: 1, // 선의 불투명도 입니다 1에서 0 사이의 값이며 0에 가까울수록 투명합니다
				strokeStyle: 'dashed', // 선의 스타일 입니다 (dashed.점선, solid.직선)
				fillColor: 'yellow', // 채우기 색깔입니다
				fillOpacity: 0.2  // 채우기 불투명도 입니다
			});

			// 지도에 원을 표시합니다
			mapCircle.setMap(map);
			//원 표시 [E] ---------------------------------------------------------------------------------------------
		}

	});
}
setMapCircle(1000, 4); //m단위 거리 원 그리기 (1KM)
//setMapCircle(2000, 5); //m단위 거리 원 그리기 (2KM)

//위도, 경도 활용한 좌표간의 거리 구하기
function jsDistance(lat1, lng1, lat2, lng2){
	function deg2rad(deg) {
		return deg * (Math.PI/180)
	}
	var R = 6371; // Radius of the earth in km
	var dLat = deg2rad(lat2-lat1);  // deg2rad below
	var dLon = deg2rad(lng2-lng1);
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var k = R * c; //Km 단위 거리
	var m = Math.round(k * 1000, 0);  //m 단위 거리 (소수점 1자리에서 반올림)
	//alert("m : "+ m);
	return m;
}
//jsDistance('35.2585279761481', '128.610310882808', '35.2414187313228', '128.633146572352');
</script>
<? } //if($ie_yn == "Y"){ ?>
