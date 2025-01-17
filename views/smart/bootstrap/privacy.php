<?
	$home_url = "/smart/view/". $code; //스마트전단 홈
	if($ScreenShotYn != "") $home_url .= "?ScreenShotYn=". $ScreenShotYn;
?>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <div class="smart_header">
    <span class="logo"><a href="<?=$home_url?>"><img src="/dhn/images/logo_v2.png" alt="<?=config_item('site_name')?>"></a></span>
    <span class="header_txt"><?=config_item('site_full_name')?></span>
  </div>
  <div class="smart_menu">
    <a href="javascript:history.back()"><i class="material-icons">arrow_back</i></a>
    <span class="smart_title">개인정보처리방침</span>
  </div>
  <div class="policy_wrap">
<p class="policy_box"><?=$shop->mem_username?>(은)는 개인정보 보호법 제30조에 따라 정보주체(고객)의 개인정보를 보호하고 이와 관련한 고충을 신속하고 원활하게 처리할 수 있도록 하기 위하여 다음과 같이 개인정보 처리지침을 수립․공개합니다.</p>

<h3>1. 개인정보의 처리목적</h3>
<h4><?=$shop->mem_username?>은(는) 다음의 목적을 위하여 개인정보를 처리하고 있으며, 다음의 목적 이외의 용도로는 이용하지 않습니다.</h4>
<p>- 고객에 대한 서비스 제공에 따른 상품 주문 내역, 주문자 정보, 결제 금액, 또는 서비스의 공급․배송 등</p>

<h3>2. 개인정보의 처리 및 보유기간</h3>
<p>① <?=$shop->mem_username?>은(는) 정보주체로부터 개인정보를 수집할 때 동의받은 개인정보 보유․이용기간 또는 법령에 따른 개인정보 보유․이용기간 내에서 개인정보를 처리․보유합니다.</p>
<p>② 구체적인 개인정보 처리 및 보유 기간은 다음과 같습니다.<br />
    - 고객 가입 및 관리 : 서비스 이용 또는 상품 주문 및 결제 고객의 개인정보를 법령에 따라 관리한다.<br />
    - 전자상거래에서의 계약․청약철회, 대금결제, 재화 등 공급기록 : 5년</p>

<h3>3. 개인정보 제3자 제공 동의</h3>
<h4>설문조사(제3자 의뢰 설문 조사 포함), 재화/서비스 홍보 및 권유, 이용자의 관심, 기호, 성향의 추정을 통한 맞춤형 콘텐츠 추천 및 마케팅 활용, 인구 통계학적 분석, 서비스 분석 및 통계에 따른 맟춤 서비스 제공, 광고 게재, 리타겟팅 위한 동의입니다.</h4>
<p>당사는 본 쇼핑몰에 등록된 개인정보를 수집/이용하여 각종 서비스/상품 및 타사 서비스와 결합된 상품에 대하여 홍보, 가입권유, 프로모션, 이벤트 등 목적으로 고객센터에 그 취급을 위탁하고 설문조사를 위하여 전문기관에 그 취급을 위탁하여 본인에게 정보/광고를 각종 통신방식(전화, SMS, LMS, MMS, WAP, Push, 이메일, 우편, 어플안내, 팝업 등)으로 전송함에 동의합니다.</p>

<h3>4. 개인정보처리의 위탁</h3>
<p>① <?=$shop->mem_username?>은(는) 원활한 개인정보 업무처리를 위하여 다음과 같이 개인정보 처리업무를 외부에 위탁하고 있습니다.<br />
      - 위탁받는 자 (수탁자) : (주)대형네트웍스, 나이스페이<br />
      - 위탁하는 업무의 내용 : 구매 및 요금 결제, 알림톡 및 문자메세지 발송, 구매자 데이터 분석</p>
<p>② <?=$shop->mem_username?>은(는) 위탁계약 체결시 개인정보 보호법 제25조에 따라 위탁업무 수행목적 외 개인정보 처리금지, 수탁자에 대한 관리․감독, 책임에 관한 사항을 문서에 명시하고, 수탁자가 개인정보를 안전하게 처리하는지를 감독하고 있습니다.</p>

<h3>5. 정보주체와 법정대리인의 권리․의무 및 행사방법</h3>
<h4>정보주체는 <?=$shop->mem_username?>에 대해 언제든지 다음 각 호의 개인정보 보호 관련 권리를 행사할 수 있습니다.</h4>
<p>1. 개인정보 열람요구<br />
    2. 개인정보에 오류 등이 있을 경우 정정 요구<br />
    3. 삭제요구<br />
    4. 처리정지 요구</p>

<h3>6. 처리하는 개인정보 항목</h3>
<h4><?=$shop->mem_username?>은(는) 다음의 개인정보 항목을 처리하고 있습니다. </h4>
<p>- 성명, 주소, 전화번호, 휴대전화번호, 신용카드번호, 은행계좌번호 등 결제정보</p>

<h3>7. 개인정보의 파기</h3>
<p>① <?=$shop->mem_username?>은(는) 개인정보 보유기간의 경과, 처리목적 달성 등 개인정보가 불필요하게 되었을 때에는 지체없이 해당 개인정보를 파기합니다.</p>
<p>② <?=$shop->mem_username?>은(는) 다음의 방법으로 개인정보를 파기합니다.<br />
      - 전자적 파일 : 파일 삭제 및 디스크 등 저장매체 포맷<br />
      - 수기(手記) 문서 : 분쇄하거나 소각</p>

<h3>8. 개인정보의 안전성 확보조치</h3>
<h4><?=$shop->mem_username?>은(는) 개인정보의 안전성 확보를 위해 다음과 같은 조치를 취하고 있습니다.</h4>
<p>- 관리적 조치 : 내부 관리 계획 수립, 시행, 직원, 종업원 등에 대한 정기적 교육</p>
<p>- 기술적 조치 : 개인정보처리시스템(또는 개인정보가 저장된 컴퓨터)의 비밀번호 설정 등 접근권한 관리, 백신소프트웨어 등 보안프로그램 설치, 개인정보가 저장된 파일의 암호화</p>
<p>- 물리적 조치 : 개인정보가 저장․보관된 장소의 시건, 출입통제 등</p>

<h3>9. 개인정보 자동 수집 장치의 설치∙운영 및 거부에 관한 사항</h3>
<p>① <?=$shop->mem_username?>은(는) 이용자에게 개별적인 맞춤서비스를 제공하기 위해 이용정보를 저장하고 수시로 불러오는 ‘쿠기(cookie)’를 사용합니다.</p>
<p>② 쿠키는 웹사이트를 운영하는데 이용되는 서버(http)가 이용자의 컴퓨터 브라우저에게 보내는 소량의 정보이며 이용자들의 PC 컴퓨터내의 하드디스크에 저장되기도 합니다.<br />
      가. 쿠키의 사용목적: 이용자가 방문한 각 서비스와 웹 사이트들에 대한 방문 및 이용형태, 인기 검색어, 보안접속 여부, 등을 파악하여 이용자에게 최적화된 정보 제공을 위해 사용됩니다.<br />
      나. 쿠키의 설치∙운영 및 거부 : 웹브라우저 상단의 도구>인터넷 옵션>개인정보 메뉴의 옵션 설정을 통해 쿠키 저장을 거부 할 수 있습니다.<br />
      다. 쿠키 저장을 거부할 경우 맞춤형 서비스 이용에 어려움이 발생할 수 있습니다.</p>

<h3>10. 개인정보 보호책임자</h3>
<h4><?=$shop->mem_username?>은(는) 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제를 처리하기 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.</h4>
    <p style="margin-top:10px;">▶ <?=$shop->mem_username?> 개인정보 보호책임자<br />
    - 성명 : <?=$shop->mem_nickname?><br />
    - 직책 : 개인정보 보호책임자<br />
    - 연락처 : <?=$this->funn->format_phone($shop->mem_phone, "-")?><?=($shop->mem_email != "") ? ", ". $shop->mem_email : ""?></p>
    <p style="margin-top: 10px; opacity:0.4;">[지니] 담당자<br />
    - 성명 : 송종근<br />
    <!-- - 직책 : 부장<br /> -->
    - 연락처 : 1522-7985</p>

<h3>11. 개인정보 처리방침 변경</h3>

<h4>이 개인정보 처리방침은 2023. 02. 01.부터 적용됩니다.</h4>

</div>
  <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div>
</div>
