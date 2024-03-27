<?//php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?
	$year = date('Y'); //년도
	$month = date('n'); //월
	$day = date('j'); //일

	//요일
	$weekString = array("일", "월", "화", "수", "목", "금", "토");

	//오늘 일자별 요일 세팅
	$strToday = date("Y년 n월 j일") ."(". $weekString[date('w')] .")";
	//echo "strToday : ". $strToday ."<br>";

	//주간 일자별 요일 세팅
	$strWeek = "";
	for($i = 6; $i >=0; $i--){
		//echo "i : ". $i ."<br>";
		$date = date("Y-m-d", strtotime(date('Y-m-d') ." -". $i ." day"));
		$day = date('j', strtotime($date));
		$week = $weekString[date('w', strtotime($date))];
		$rtnDay = $day ."(". $week .")";
		//echo "date : ". $date .", day : ". $day .", week : ". $week .", rtnDay : ". $rtnDay ."<br>";
		if($strWeek != ""){
			$strWeek .= ",". $rtnDay;
		}else{
			$strWeek = $rtnDay;
		}
	}
	//echo "strWeek : ". $strWeek ."<br>";

	//월간 일자별 요일 세팅
	$strMonth = "";
	$eno = date("t", mktime( 0, 0, 0, $month, $day, $year ));
	//echo "eno : ". $eno ."<br>";
	for($i = 1; $i <=$eno; $i++){
		$date = date("Y-m-d", mktime( 0, 0, 0, $month, $i, $year ));
		$day = date('j', strtotime($date));
		$week = $weekString[date('w', strtotime($date))];
		$rtnDay = $day ."(". $week .")";
		//echo "date : ". $date .", day : ". $day .", week : ". $week .", rtnDay : ". $rtnDay ."<br>";
		if($strMonth != ""){
			$strMonth .= ",". $rtnDay;
		}else{
			$strMonth = $rtnDay;
		}
	}
	//echo "strMonth : ". $strMonth ."<br>";

?>
<!-- 타이틀 영역
<div class="tit_wrap tc">
	<span class="main_date">
	<script>
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10) {
			dd='0'+dd
		}
		if(mm<10) {
			mm='0'+mm
		}
		today = yyyy+'년 ' +mm+'월 ' +dd+'일 ';
		document.write(today);
	</script>
	</span>
</div> 타이틀 영역 END -->

<div id="dh_myModal" class="dh_modal">
	<div class="modal-content">
		<span id="dh_close" class="dh_close">&times;</span>
		<p class="modal-tit"><span id="id_modal_title">이미지 라이브러리</span> (<span id="id_modal_count">0</span>건)</p>
		<div class="search_input" id="id_searchDiv">
			<select id="id_searchName" style="display:none;margin-left:5px;">
				<option value="1">업체명</option>
				<option value="2">계정</option>
			</select>
			<input type="search" id="id_searchMember" placeholder="검색어를 입력하세요." style="width:320px;" onKeypress="if(event.keyCode==13){ searchReMember(itype); }">
			<button id="id_searchBtn" onclick="searchReMember(itype);">
				<i class="material-icons">search</i>
			</button>
            <button id="id_tomenuBtn" onclick="toMenu(itype);" style="display:none;">
				<i class="material-icons">arow back</i>
			</button>
		</div>
        <table id="member_append_list" class="member_append_list"<? if($ieYn == "Y"){ ?> style="height:440px;"<? } ?>><?//리스트 영역?>

        </table>

		<div id="member_page" class="page_cen" style="margin-top:10px;"></div><?//라이브러리 페이징 영역?>

	</div>
</div>
<script>
var ieYn = "<?=$ieYn?>"; //익스플로러 여부
var itype = "";
var pageMember = 1; //페이지
var totalMember = 0; //전체수

var modal = document.getElementById("dh_myModal");
function showMember(type){
    $("#id_searchName").val("name"); //검색이름
    $("#id_searchDiv").show(); //검색영역 열기
    $("#id_searchName").show(); //검색타입 열기
    $("#id_searchName").val("1");
    $("#id_searchMember").val('');
    $("#id_tomenuBtn").hide();
    var searchnm = $("#id_searchName").val(); //검색이름
    var searchstr = $("#id_searchMember").val(); //검색내용
    itype = type;
    var span = document.getElementById("dh_close");
    modal.style.display = "block";

    if(type == "w1"){
        $("#id_modal_title").html("전체고객업체");
    }else if(type == "w2"){
        $("#id_modal_title").html("계정해지업체");
    }else if(type == "w3"){
        $("#id_modal_title").html("휴면업체");
    }else if(type == "m1"){
        $("#id_modal_title").html("총계약업체");
    }else if(type == "m2"){
        $("#id_modal_title").html("미발송업체");
    }else if(type == "m3"){
        $("#id_modal_title").html("당월신규계약업체");
    }else if(type == "m4"){
        $("#id_modal_title").html("당월신규계약업체(미발송)");
    }else if(type == "m5"){
        $("#id_modal_title").html("당월신규계약업체(발송)");
    }else if (type == "v1"){
        $("#id_modal_title").html("바우처 미발송 업체");
    }else if (type == "v2"){
        $("#id_modal_title").html("바우처 업체");
    }

    //alert("searchstr : "+ searchstr); return;
    $("#id_searchMember").val(searchstr); //검색내용
    removeMember(); //리스트 초기화
    searchMember(type); //리스트 조회
    span.onclick = function() {
        removeMember(); //리스트 초기화

        modal.style.display = "none"; //라이브러리 모달창 닫기
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            removeMember(); //리스트 초기화
            modal.style.display = "none"; //라이브러리 모달창 닫기
        }
    }
}

function toMenu(type){
    $("#id_searchName").val("1");
    $("#id_searchMember").val('');
    removeMember(); //리스트 초기화
    searchMember(type); //리스트 조회
    $("#id_tomenuBtn").hide();
}

function removeMember(){
    pageMember = 1; //페이지
    totalMember = 0; //전체수

    $("#member_append_list").html(""); //초기화
}

function searchReMember(type){
    removeMember(); //리스트 초기화
    searchMember(type); //리스트 조회
    $("#id_tomenuBtn").show();
}

function searchMember(type){
    var searchnm = $("#id_searchName").val(); //검색이름
    var searchstr = $("#id_searchMember").val(); //검색내용
    var perpage = 10;
    // if(ieYn == "Y") perpage = 10; //익스플로러의 경우
    //alert("pageMember : "+ pageMember +"\n"+ ", searchCate1 : "+ searchCate1 +"\n"+ ", searchCate2 : "+ searchCate2 +"\n"+ ", searchstr : "+ searchstr +"\n"+ ", library_type : "+ library_type); return;
    //url: "/imglibrary/search_library",
    $.ajax({
        url: "/biz/stats/search_member",
        type: "POST",
        data: {
            "type" : type
          , "perpage" : perpage
          , "page" : pageMember
          , "searchnm" : searchnm
          , "searchstr" : searchstr
          ,  "page_yn" : "Y"
          , "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
        },
        success: function (json) {
            // pageMember = json.page;
            totalMember = json.total;
            //alert(json.html);
            // console.log(json.html);
            $("#member_append_list").append(json.html); //이미지 리스트
            $("#id_modal_count").html(comma(json.total)); //이미지 건수
            // if(ieYn == "Y"){ //익스플로러의 경우
            $("#member_page").html(json.page_html); //페이징
            // }
        }
    });
}

//라이브러리 페이지 조회
function searchMemberPage(page){
    // removeImgLibrary(); //리스트 초기화
    pageMember = page;
    $("#member_append_list").html(""); //초기화
    searchMember(itype);
    // var searchnm = $("#id_searchName").val(); //검색이름
    // var searchstr = $("#id_searchMember").val(); //검색내용
    // var perpage = 10;
    //
    // $.ajax({
    //     url: "/biz/stats/search_member",
    //     type: "POST",
    //     data: {"type" : type, "perpage" : perpage, "page" : page, "searchnm" : searchnm, "searchstr" : searchstr, "page_yn" : ieYn, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
    //     success: function (json) {
    //         //alert(json.html);
    //         $("#member_append_list").html(json.html); //이미지 리스트
    //         $("#id_modal_count").html(comma(json.total)); //이미지 건수
    //         $("#member_page").html(json.page_html); //페이징
    //     }
    // });
}


</script>

<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu11.php');
?>
<!-- //3차 메뉴 -->
<!-- 본문 영역 -->
<div id="mArticle">
	<div class="main_panel_container">
		<div class="main_panel_wrap col">
			<div class="main_panel">
				<div class="panel_header">
					<ion-icon name="pie-chart"></ion-icon>발송 요약 정보
					<div class="panel_tool">
						<ul>
							<li class="on" id="btn_mst_cnt_D" onclick="get_mst_cnt('D');">일</li>
							<li class="" id="btn_mst_cnt_W" onclick="get_mst_cnt('W');">주</li>
							<li class="" id="btn_mst_cnt_M" onclick="get_mst_cnt('M');">월</li>
						</ul>
					</div>
				</div>
				<div class="panel_body flex">
					<div class="info_wrap">
						<h4><em>전체 발송수</em></h4>
						<div class="send_total">
							<span id="id_send_total"><?=number_format($mst_data["send_total"])?></span>
						</div>
						<div class="send_rate">
							<div class="rate_info">
								<dl>
									<dt>성공</dt>
									<dd class="suc"><span id="id_succ"><?=number_format($mst_data["succ"])?></span></dd>
								</dl>
								<dl>
									<dt>실패</dt>
									<dd class="fail"><span id="id_fail"><?=number_format($mst_data["fail"])?></span></dd>
								</dl>
								<dl>
									<dt>예약</dt>
									<dd class="res"><span id="id_reserved_qty"><?=number_format($mst_data["reserved_qty"])?></span></dd>
								</dl>
							</div>
							<div class="rate_chart" id="id_rate_chart">
								<div class="stat-circle-chart" data-progress="<?=$mst_data["rate"]?>">
									<svg class="scc-svg" viewBox="0 0 130 130" xmlns="http://www.w3.org/2000/svg">
										<circle class="scc-svg-back" cx="65" cy="65" r="60" fill="none" fill-rule="evenodd" />
										<circle class="scc-svg-forth" cx="65" cy="65" r="60" fill="none" fill-rule="evenodd" />
									</svg>
									<div class="scc-percents">
										<span class="scc-p-digits">0</span>
										<span class="scc-p-currency">%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="chart_wrap">
						<div id="graph_mst_cnt" style="height: 300px;"><? //발송건수 그래프 ?></div>
					</div>
				</div>
				<div class="panel_bottom">
					<div class="main_send_type alim">
						<div class="info">
							<label>알림톡 발송수</label>
							<p><span id="id_at_qty"><?=number_format($mst_data["at_qty"])?></span></p>
						</div>
						<div class="detail_wrap">
							<ul class="detail">
								<li>
									<span class="info_title">성공</span>
									<span class="data_area success"><span id="id_at_succ"><?=number_format($mst_data["at_succ"])?></span></span>
								</li>
								<li>
									<span class="info_title">실패</span>
									<span class="data_area fail"><span id="id_at_fail"><?=number_format($mst_data["at_fail"])?></span></span>
								</li>
							</ul>
							<div class="chart">
								<span id="id_at_rate"><?=$mst_data["at_rate"]?></span><? //알림톡 성공율 ?>
							</div>
						</div>
					</div>
					<div class="main_send_type friend">
						<div class="info">
							<label>친구톡 발송수</label>
							<p><span id="id_ft_qty"><?=number_format($mst_data["ft_qty"])?></span></p>
						</div>
						<div class="detail_wrap">
							<ul class="detail">
								<li>
									<span class="info_title">성공</span>
									<span class="data_area success"><span id="id_ft_succ"><?=number_format($mst_data["ft_succ"])?></span></span>
								</li>
								<li>
									<span class="info_title">실패</span>
									<span class="data_area fail"><span id="id_ft_fail"><?=number_format($mst_data["ft_fail"])?></span></span>
								</li>
							</ul>
							<div class="chart">
								<span id="id_ft_rate"><?=$mst_data["ft_rate"]?></span><? //친구톡 성공율 ?>
							</div>
						</div>
					</div>
					<div class="main_send_type lms">
						<div class="info">
							<label>문자 발송수</label>
							<p><span id="id_tx_qty"><?=number_format($mst_data["tx_qty"])?></span></p>
						</div>
						<div class="detail_wrap">
							<ul class="detail">
								<li>
									<span class="info_title">성공</span>
									<span class="data_area success"><span id="id_tx_succ"><?=number_format($mst_data["tx_succ"])?></span></span>
								</li>
								<li>
									<span class="info_title">실패</span>
									<span class="data_area fail"><span id="id_tx_fail"><?=number_format($mst_data["tx_fail"])?></span></span>
								</li>
							</ul>
							<div class="chart">
								<span id="id_tx_rate"><?=$mst_data["tx_rate"]?></span><? //문자 성공율 ?>
							</div>
						</div>
					</div>
                    <div class="main_send_type rcs" <?=($this->member->item("mem_level")>100)? "" : "style='display:none;'" ?>>
						<div class="info">
							<label>RCS 발송수</label>
							<p><span id="id_rc_qty"><?=number_format($mst_data["rc_qty"])?></span></p>
						</div>
						<div class="detail_wrap">
							<ul class="detail">
								<li>
									<span class="info_title">성공</span>
									<span class="data_area success"><span id="id_rc_succ"><?=number_format($mst_data["rc_succ"])?></span></span>
								</li>
								<li>
									<span class="info_title">실패</span>
									<span class="data_area fail"><span id="id_rc_fail"><?=number_format($mst_data["rc_fail"])?></span></span>
								</li>
							</ul>
							<div class="chart">
								<span id="id_rc_rate"><?=$mst_data["rc_rate"]?></span><? //RCS 성공율 ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 관리자 계정만 표시 -->
		<? if($this->member->item('mem_id')=='3' || $this->member->item('mem_id')=='2'|| $this->member->item('mem_id')=='962') { //관리자 계정만 표시 ?>
		<div class="main_panel_wrap col75">
			<div class="main_panel">
				<div class="panel_header">
					<h3><em class="marker">최근 3개월간</em> 발송 추이</h3>
					<!-- <div class="panel_tool">
						<ul>
							<li class="on" id="btn_mst_cnt_3MACC" onclick="get_mst_3m_cnt('3MACC');">누적 비교</li> -->
							<!-- <li class="" id="btn_mst_cnt_3M" onclick="get_mst_3m_cnt('3M');">일별 비교</li> -->
						<!-- </ul>
					</div> -->
				</div>
				<div class="panel_body">
					<div class="chart_wrap" id="graph_mst_cnt2" style="height:360px;"><? //발송건수 그래프 ?></div>
				</div>
			</div>
		</div>
		<div class="main_panel_wrap col25">
			<div class="main_panel flex-center">
				<!-- <div class="info_txt">
					<h3>전체 고객 업체</h3>
					<p class="value" onclick="showMember('w1')"><?=number_format($compony_cnt->mem_cnt)?></p>
				</div>
				<div class="info_txt">
					<h3>계정 해지 업체</h3>
					<p class="value" onclick="showMember('w2')"><?=number_format($compony_cnt->mem_cont_cnacel_cnt)?></p>
				</div>
				<div class="info_txt">
					<h3>휴면 업체</h3>
					<p class="value" onclick="showMember('w3')"><?=number_format($compony_cnt->mem_dormant_cnt)?></p>
				</div> -->
                <div class="info_txt">
                    <h3>전체 고객 업체</h3>
                    <p class="value" style="cursor:pointer;" onclick="showMember('w1')"><?=number_format($compony_cnt->mem_cnt)?></p>
					<div class="info_txt_s">
                        <h3>계정 해지 업체</h3>
    					<p class="value" style="cursor:pointer;" onclick="showMember('w2')"><?=number_format($compony_cnt->mem_cont_cnacel_cnt)?></p>
            	    </div>
    				<div class="info_txt_s">
                        <h3>휴면 업체</h3>
    					<p class="value" style="cursor:pointer;" onclick="showMember('w3')"><?=number_format($compony_cnt->mem_dormant_cnt)?></p>
                	</div>
				</div>
                <div class="info_txt">
                    <h3>바우처 사업 업체</h3>
                    <p class="value" style="cursor:pointer;" onclick="showMember('v2')"><?=number_format($v_all_cnt)?></p>
					<div class="info_txt_s">
                        <h3>바우처 미접속 업체</h3>
    					<p class="value" style="cursor:pointer;" onclick="showMember('v3')"><?=number_format($v_20day_cnt)?></p>
            	    </div>
    				<div class="info_txt_s">
                        <h3>바우처 미발송 업체</h3>
    					<p class="value" style="cursor:pointer;" onclick="showMember('v1')"><?=number_format($v_not_send_cnt)?></p>
                	</div>
				</div>
			</div>
		</div>
<? }
if($this->member->item('mem_id')=='3' || $this->member->item('mem_id')=='2') { //관리자 계정만 표시 ?>
        <div class="main_panel_wrap col75">
			<div class="main_panel">
				<div class="panel_header">
					<h3><em class="marker">최근 3개월간</em> 계약 추이</h3>
					<!-- <div class="panel_tool">
						<ul>
							<li class="on" id="btn_mst_cnt_3MACC2" onclick="get_mst_3m_cnt2('3MACC2');">누적 비교</li> -->
							<!-- <li class="" id="btn_mst_cnt_3M" onclick="get_mst_3m_cnt('3M');">일별 비교</li> -->
						<!-- </ul>
					</div> -->
				</div>
				<div class="panel_body">
					<div class="chart_wrap" id="graph_mst_cnt3" style="height:360px;"><? //발송건수 그래프 ?></div>
				</div>
			</div>
		</div>
		<div class="main_panel_wrap col25">
			<div class="main_panel flex-center">
				<div class="info_txt">
					<h3>총 계약 업체</h3>
					<p class="value" style="cursor:pointer;" onclick="showMember('m1')"><?=number_format($reg_cnt->mem_cnt)?></p>
				</div>
				<div class="info_txt">
					<h3>미발송 업체</h3>
					<p class="value" style="cursor:pointer;" onclick="showMember('m2')"><?=number_format($reg_cnt->mem_cont_cnacel_cnt)?></p>
				</div>
				<div class="info_txt">
					<h3>당월 신규계약업체</h3>
					<p class="value" style="cursor:pointer;" onclick="showMember('m3')"><?=number_format($reg_cnt->mem_new_cnt)?></p>
					<div class="info_txt_s">
		<h3>미발송</h3>
		<p class="value" style="cursor:pointer;" onclick="showMember('m4')"><?=number_format($reg_cnt->mem_new_not_cnt)?></p>
	</div>
					<div class="info_txt_s">
		<h3>발송</h3>
		<p class="value" style="cursor:pointer;" onclick="showMember('m5')"><?=number_format($reg_cnt->mem_new_cnt - $reg_cnt->mem_new_not_cnt)?></p>
	</div>
				</div>
			</div>
		</div>
		<!--div class="main_panel_wrap">
			<div class="main_panel">
				<div class="panel_header">
					<h3><em class="marker">신규등록</em> 업체</h3>
					<div class="chart_tool">
						<ul>
							<li class="" >옵션2</li>
							<li class="on" >옵션1</li>
							<li class="" >옵션2</li>
							<li class="" >옵션2</li>
						</ul>
					</div>
				</div>
				<div class="panel_body">
					<ul class="rank_list">
						<li>
							<div class="data"><em class="rank_num">1</em>하나로마트 창원 1호점</div><?//업체명?>
							<div class="value" title="">2일전</div><?//계약 만료일?>
						</li>
						<li>
							<div class="data"><em class="rank_num">2</em>하나로마트 창원 2호점</div><?//업체명?>
							<div class="value" title="">5일전</div><?//계약 만료일?>
						</li>
						<li>
							<div class="data"><em class="rank_num">3</em>하나로마트 창원 3호점</div><?//업체명?>
							<div class="value" title="">1주일전</div><?//계약 만료일?>
						</li>
						<li>
							<div class="data"><em class="rank_num">4</em>하나로마트 창원 4호점</div><?//업체명?>
							<div class="value" title="">2주일전</div><?//계약 만료일?>
						</li>
						<li>
							<div class="data"><em class="rank_num">5</em>하나로마트 창원 5호점</div><?//업체명?>
							<div class="value" title="">1개월전</div><?//계약 만료일?>
						</li>
					</ul>
				</div>
			</div>
		</div-->
		<? } //if($this->member->item('mem_id')=='3' || $this->member->item('mem_id')=='2') { //관리자 계정만 표시 ?>
		<!-- 관리자 계정 표시 끝 -->
	</div>
</div><!-- mArticle END -->
<!-- 메인 차트 관련 -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
	Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });

	//일별통계
    Highcharts.chart('graph_mst_cnt', {
	  chart: {
	    type: 'column'
	  },
        title: {
            text: '<?=$strToday?>'
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom'
        },
	    stackLabels: {
	      enabled: true
	      },
        xAxis: {
            categories: [ <?=$mst_graph["seq"]?> ]
        },
        yAxis: {
            title: {
                text: '발송갯수'
            }
        },
		  plotOptions: {
		    column: {
		      stacking: 'normal',
		      dataLabels: {
		        enabled: false
		      }
		    }
		  },
        series: [{
            showInLegend: false,
            name: '실패갯수',
            color:'#ccc',
            data: [ <?=$mst_graph["failcnt"]?> ]
        },{
            showInLegend: false,
            name: '성공갯수',
            color:'#11b3ff',
            data: [ <?=$mst_graph["cnt"]?> ]
        }]
    });

	//최근 3개월간 발송 추이
	Highcharts.chart('graph_mst_cnt2', {
	    chart: {
	        type: 'areaspline'
	    },
        title: {
            text: ''
        },
        legend: {
			align: 'center',
			verticalAlign: 'bottom',
			borderWidth: 0
        },
		tooltip: {
	        shared: true,
	        valueSuffix: ' units'
		},
        xAxis: {
	        tickWidth: 0,
	        gridLineWidth: 1,
            categories: [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ]
        },
        yAxis: {
            title: {
                text: '성공갯수'
            }
        },
		  plotOptions: {
		    areaspline: {
				fillOpacity: 0.2,
				lineWidth: 0.5,
				marker: {
					enabled: false
				}
		    }
		},
        series: [{
            showInLegend: true,
            name: '',
            color:'#666',
            data: []
        }, {
            showInLegend: true,
            name: '',
            color:'',
            data: []
        }, {
            showInLegend: true,
            name: '',
            color:'#8E44AD',
            data: []
        }]
    });

    //최근 3개월간 발송 추이
	Highcharts.chart('graph_mst_cnt3', {
	    chart: {
	        type: 'areaspline'
	    },
        title: {
            text: ''
        },
        legend: {
			align: 'center',
			verticalAlign: 'bottom',
			borderWidth: 0
        },
		tooltip: {
	        shared: true,
	        valueSuffix: ' units'
		},
        xAxis: {
	        tickWidth: 0,
	        gridLineWidth: 1,
            categories: [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ]
        },
        yAxis: {
            title: {
                text: '계약건수'
            }
        },
		  plotOptions: {
		    areaspline: {
				fillOpacity: 0.2,
				lineWidth: 0.5,
				marker: {
					enabled: false
				}
		    }
		},
        series: [{
            showInLegend: true,
            name: '',
            color:'#666',
            data: []
        }, {
            showInLegend: true,
            name: '',
            color:'',
            data: []
        }, {
            showInLegend: true,
            name: '',
            color:'#8E44AD',
            data: []
        }]
    });

	//숫자 콤마 찍기
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	//날짜 yyyy년 MM월 dd 포맷으로 반환
	function getFormatDate(date){
		var year = date.getFullYear();              //yyyy
		var month = (1 + date.getMonth());          //M
		month = month >= 10 ? month : '0' + month;  //month 두자리로 저장
		var day = date.getDate();                   //d
		day = day >= 10 ? day : '0' + day;          //day 두자리로 저장
		return  year + '년 ' + month + '월 ' + day +'일';
	}

    //발송건수 실시간 조회
	function get_mst_cnt(t) {
		$.ajax({
            url: "/biz/stats/ajax_mst_cnt",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"type": t
            },
            success: function(json) {
				//alert("t : "+ t);
				var x = json['x']; //그래프 기간
				var v = json['v']; //데이타
				var f_v = json['f_v'];
				var syy = json['syy']; //시작 년도
				var smm = json['smm']; //시작 월
				var sdd = json['sdd']; //시작 일
				var eyy = json['eyy']; //종료 년도
				var emm = json['emm']; //종료 월
				var edd = json['edd']; //종료 일
				var period = "";
				if(t == "D"){ //일
					period = "<?=$strToday?>";
				} else if(t == "W"){ //주
					period = syy +"년 "+ smm +"월 "+ sdd +"일" +" ~ "+ eyy +"년 "+ emm +"월 "+ edd +"일";
					//period = (startDate) +" ~ "+ (endDate);
					x = "<?=$strWeek?>";
				} else if(t == "M"){ //월
					period = syy +"년 "+ smm +"월";
					x = "<?=$strMonth?>";
				}
				document.getElementById("id_send_total").innerHTML = numberWithCommas(json['send_total']); //전체 발송수
				document.getElementById("id_reserved_qty").innerHTML = numberWithCommas(json['reserved_qty']); //전체 예약
				document.getElementById("id_succ").innerHTML = numberWithCommas(json['succ']); //전체 성공
				document.getElementById("id_fail").innerHTML = numberWithCommas(json['fail']); //전체 실패
				document.getElementById("id_rate_chart").innerHTML = '	<div class="stat-circle-chart" data-progress="'+ numberWithCommas(json['rate']) +'"><svg class="scc-svg" viewBox="0 0 130 130" xmlns="http://www.w3.org/2000/svg"><circle class="scc-svg-back" cx="65" cy="65" r="60" fill="none" fill-rule="evenodd" /><circle class="scc-svg-forth" cx="65" cy="65" r="60" fill="none" fill-rule="evenodd" />	</svg><div class="scc-percents"><span class="scc-p-digits">0</span><span class="scc-p-currency">%</span></div></div>';
				document.getElementById("id_at_qty").innerHTML = numberWithCommas(json['at_qty']); //알림톡 발송수
				document.getElementById("id_at_succ").innerHTML = numberWithCommas(json['at_succ']); //알림톡 성공
				document.getElementById("id_at_fail").innerHTML = numberWithCommas(json['at_fail']); //알림톡 실패
				document.getElementById("id_at_rate").innerHTML = numberWithCommas(json['at_rate']); //알림톡 성공율
				document.getElementById("id_ft_qty").innerHTML = numberWithCommas(json['ft_qty']); //친구톡 발송수
				document.getElementById("id_ft_succ").innerHTML = numberWithCommas(json['ft_succ']); //친구톡 성공
				document.getElementById("id_ft_fail").innerHTML = numberWithCommas(json['ft_fail']); //친구톡 실패
				document.getElementById("id_ft_rate").innerHTML = numberWithCommas(json['ft_rate']); //친구톡 성공율
				document.getElementById("id_tx_qty").innerHTML = numberWithCommas(json['tx_qty']); //문자 발송수
				document.getElementById("id_tx_succ").innerHTML = numberWithCommas(json['tx_succ']); //문자 성공
				document.getElementById("id_tx_fail").innerHTML = numberWithCommas(json['tx_fail']); //문자 실패
				document.getElementById("id_tx_rate").innerHTML = numberWithCommas(json['tx_rate']); //문자 성공율

                document.getElementById("id_rc_qty").innerHTML = numberWithCommas(json['rc_qty']); //RCS 발송수
				document.getElementById("id_rc_succ").innerHTML = numberWithCommas(json['rc_succ']); //RCS 성공
				document.getElementById("id_rc_fail").innerHTML = numberWithCommas(json['rc_fail']); //RCS 실패
				document.getElementById("id_rc_rate").innerHTML = numberWithCommas(json['rc_rate']); //RCS 성공율
				//alert("x : "+ x +"\n"+"v : "+ v +"\n"+"period : "+ {text:period});
				//alert("json.v : "+ json.v);
				var chart1 = $('#graph_mst_cnt').highcharts();
                var jtemp = v.split(",");
                var jtemp_f = f_v.split(",");
                var vdata = [];
                var vdata_f = []
                for (var i in jtemp) {
                    vdata.push(Number(jtemp[i]));
                }
                for (var j in jtemp_f) {
                	vdata_f.push(Number(jtemp_f[j]));
                }
                var xdata = x.split(",");
                chart1.series[0].update({
                    data: vdata_f
                });
                chart1.series[1].update({
                    data: vdata
                });
                chart1.xAxis[0].update({
                    categories: xdata
                });
                chart1.setTitle({text: period});
                chart1.redraw();

				call_stat_circle_chart(); //도넛차트 구동
            }
        });
		//alert("get_mst_cnt(t) : "+ t);
        $("#btn_mst_cnt_D").removeClass("on");
        $("#btn_mst_cnt_W").removeClass("on");
        $("#btn_mst_cnt_M").removeClass("on");
		$("#btn_mst_cnt_"+ t).addClass("on");
    }

	//최근 3개월간 발송 추이 > 일별비교
	function get_mst_3m_cnt(t) {
		$.ajax({
            url: "/biz/stats/ajax_mst_3m_cnt",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"type": t
            },
            success: function(json) {
				//alert("t : "+ t);
				var x = json['x']; //그래프 기간
				var v1 = json['v1']; //데이타
				var v2 = json['v2']; //데이타
				var v3 = json['v3']; //데이타
				var n1 = json['n1']; //데이타
				var n2 = json['n2']; //데이타
				var n3 = json['n3']; //데이타
				var syy = json['syy']; //시작 년도
				var smm = json['smm']; //시작 월
				var sdd = json['sdd']; //시작 일
				var eyy = json['eyy']; //종료 년도
				var emm = json['emm']; //종료 월
				var edd = json['edd']; //종료 일
				var period = "";
				if(t == "3M"){ //월 일변 수량
					period = syy +"년 "+ smm +"월 ~ " + eyy +"년 "+ emm +"월" ;
					x = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31";
				} else if (t == "3MACC") {
					period = syy +"년 "+ smm +"월 ~ " + eyy +"년 "+ emm +"월 누적" ;
					x = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31";
				}

				/*
				document.getElementById("id_send_total").innerHTML = numberWithCommas(json['send_total']); //전체 발송수
				document.getElementById("id_reserved_qty").innerHTML = numberWithCommas(json['reserved_qty']); //전체 예약
				document.getElementById("id_succ").innerHTML = numberWithCommas(json['succ']); //전체 성공
				document.getElementById("id_fail").innerHTML = numberWithCommas(json['fail']); //전체 실패
				document.getElementById("id_rate_chart").innerHTML = '	<div class="stat-circle-chart" data-progress="'+ numberWithCommas(json['rate']) +'"><svg class="scc-svg" viewBox="0 0 130 130" xmlns="http://www.w3.org/2000/svg"><circle class="scc-svg-back" cx="65" cy="65" r="60" fill="none" fill-rule="evenodd" /><circle class="scc-svg-forth" cx="65" cy="65" r="60" fill="none" fill-rule="evenodd" />	</svg><div class="scc-percents"><span class="scc-p-digits">0</span><span class="scc-p-currency">%</span></div></div>';
				document.getElementById("id_at_qty").innerHTML = numberWithCommas(json['at_qty']); //알림톡 발송수
				document.getElementById("id_at_succ").innerHTML = numberWithCommas(json['at_succ']); //알림톡 성공
				document.getElementById("id_at_fail").innerHTML = numberWithCommas(json['at_fail']); //알림톡 실패
				document.getElementById("id_at_rate").innerHTML = numberWithCommas(json['at_rate']); //알림톡 성공율
				document.getElementById("id_ft_qty").innerHTML = numberWithCommas(json['ft_qty']); //친구톡 발송수
				document.getElementById("id_ft_succ").innerHTML = numberWithCommas(json['ft_succ']); //친구톡 성공
				document.getElementById("id_ft_fail").innerHTML = numberWithCommas(json['ft_fail']); //친구톡 실패
				document.getElementById("id_ft_rate").innerHTML = numberWithCommas(json['ft_rate']); //친구톡 성공율
				document.getElementById("id_tx_qty").innerHTML = numberWithCommas(json['tx_qty']); //문자 발송수
				document.getElementById("id_tx_succ").innerHTML = numberWithCommas(json['tx_succ']); //문자 성공
				document.getElementById("id_tx_fail").innerHTML = numberWithCommas(json['tx_fail']); //문자 실패
				document.getElementById("id_tx_rate").innerHTML = numberWithCommas(json['tx_rate']); //문자 성공율

				//alert("x : "+ x +"\n"+"v : "+ v +"\n"+"period : "+ {text:period});
				//alert("json.v : "+ json.v);
				var chart1 = $('#graph_mst_cnt').highcharts();
                var jtemp = v.split(",");
                var vdata = [];
                for (var i in jtemp) {
                    vdata.push(Number(jtemp[i]));
                }
                var xdata = x.split(",");
                chart1.series[0].update({
                    data: vdata
                });
                chart1.xAxis[0].update({
                    categories: xdata
                });
                chart1.setTitle({text: period});
                chart1.redraw();

				call_stat_circle_chart(); //도넛차트 구동
				*/
                //var xdata = x.split(",");
                var vdata1 = [];
                var vdata2 = [];
                var vdata3 = [];

				var jtemp1 = v1.split(",");
                for (var i in jtemp1) {
                    if (jtemp1 != 'null') {
                    	vdata1.push(Number(jtemp1[i]));
                    } else {
                    	vdata1.push(null);
                    }
                }

				var jtemp2 = v2.split(",");
                for (var i in jtemp2) {
                    if (jtemp2 != 'null') {
                    	vdata2.push(Number(jtemp2[i]));
                    } else {
                    	vdata2.push(null);
                    }
                }

				var jtemp3 = v3.split(",");
                for (var i in jtemp3) {
                    if (jtemp3 != 'null') {
                    	vdata3.push(Number(jtemp3[i]));
                    } else {
                    	vdata3.push(null);
                    }
                }

				var chart1 = $('#graph_mst_cnt2').highcharts();
                chart1.series[0].update({
                    name: n1,
                    data: vdata1
                });
                chart1.series[1].update({
                    name: n2,
                    data: vdata2
                });
                chart1.series[2].update({
                    name: n3,
                    data: vdata3
                });
                chart1.setTitle({text: period});
                chart1.redraw();
           		//call_stat_circle_chart(); //도넛차트 구동
            }
        });
		//alert("get_mst_cnt(t) : "+ t);
        $("#btn_mst_cnt_3M").removeClass("on");
        $("#btn_mst_cnt_3MACC").removeClass("on");
		$("#btn_mst_cnt_"+ t).addClass("on");
    }

    //최근 3개월간 발송 추이 > 일별비교
	function get_mst_3m_cnt2(t) {
		$.ajax({
            url: "/biz/stats/ajax_mst_3m_cnt2",
            type: "POST",
            data: {
                <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"type": t
            },
            success: function(json) {
				//alert("t : "+ t);
				var x = json['x']; //그래프 기간
				var v1 = json['v1']; //데이타
				var v2 = json['v2']; //데이타
				var v3 = json['v3']; //데이타
				var n1 = json['n1']; //데이타
				var n2 = json['n2']; //데이타
				var n3 = json['n3']; //데이타
				var syy = json['syy']; //시작 년도
				var smm = json['smm']; //시작 월
				var sdd = json['sdd']; //시작 일
				var eyy = json['eyy']; //종료 년도
				var emm = json['emm']; //종료 월
				var edd = json['edd']; //종료 일
				var period = "";
				if(t == "3M"){ //월 일변 수량
					period = syy +"년 "+ smm +"월 ~ " + eyy +"년 "+ emm +"월" ;
					x = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31";
				} else if (t == "3MACC2") {
					period = syy +"년 "+ smm +"월 ~ " + eyy +"년 "+ emm +"월 누적" ;
					x = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31";
				}


                var vdata1 = [];
                var vdata2 = [];
                var vdata3 = [];

				var jtemp1 = v1.split(",");
                for (var i in jtemp1) {
                    if (jtemp1 != 'null') {
                    	vdata1.push(Number(jtemp1[i]));
                    } else {
                    	vdata1.push(null);
                    }
                }

				var jtemp2 = v2.split(",");
                for (var i in jtemp2) {
                    if (jtemp2 != 'null') {
                    	vdata2.push(Number(jtemp2[i]));
                    } else {
                    	vdata2.push(null);
                    }
                }

				var jtemp3 = v3.split(",");
                for (var i in jtemp3) {
                    if (jtemp3 != 'null') {
                    	vdata3.push(Number(jtemp3[i]));
                    } else {
                    	vdata3.push(null);
                    }
                }

				var chart1 = $('#graph_mst_cnt3').highcharts();
                chart1.series[0].update({
                    name: n1,
                    data: vdata1
                });
                chart1.series[1].update({
                    name: n2,
                    data: vdata2
                });
                chart1.series[2].update({
                    name: n3,
                    data: vdata3
                });
                chart1.setTitle({text: period});
                chart1.redraw();
           		//call_stat_circle_chart(); //도넛차트 구동
            }
        });
		//alert("get_mst_cnt(t) : "+ t);
        $("#btn_mst_cnt_3M").removeClass("on");
        $("#btn_mst_cnt_3MACC2").removeClass("on");
		$("#btn_mst_cnt_"+ t).addClass("on");
    }
</script>
<!-- 도넛차트 구동  -->
<script>
//도넛차트 구동
function call_stat_circle_chart() {
	var $charts = $('.stat-circle-chart');
	var duration = 1500;
	var fps = 50;
	var timerLimit = Math.ceil(duration / fps);
	var playString = 'transition: stroke-dashoffset '+duration+'ms ease-in-out; -webkit-transition: stroke-dashoffset '+duration+'ms ease-in-out;-moz-transition: stroke-dashoffset '+duration+'ms ease-in-out;';

	$charts.each(function(i,thisChart){
		var $thisChart = $(thisChart);
	  var $thisChartCounter = $thisChart.find('.scc-p-digits');
	  var thisChartInterval;
	  var chartProgress = $thisChart.attr('data-progress');
	  if (!chartProgress) { return true; }
	  $thisChart.find('.scc-svg-forth').attr('style',playString+'stroke-dashoffset:'+(376 - Math.ceil(chartProgress*3.749))+';');
	  var t = 0, counter = 0, step = chartProgress/timerLimit;
	  thisChartInterval = setInterval(function(){
	    if (counter >= timerLimit) {
	    	clearInterval(thisChartInterval);
	      $thisChartCounter.html(chartProgress);
	      return false;
	    }
	    $thisChartCounter.html(Math.round(t));
	  	t = t + step; counter++;
	  }, fps);
	});
}
call_stat_circle_chart(); //도넛차트 구동
get_mst_3m_cnt('3MACC'); //최근 3개월간 발송 추이 > 누적비교
get_mst_3m_cnt2('3MACC2'); //최근 3개월간 발송 추이 > 누적비교
</script>
