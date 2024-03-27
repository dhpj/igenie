<?include $_SERVER["DOCUMENT_ROOT"]."/views/homepage/top.php";?>
<link rel="stylesheet" href="/bizM/css/style.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/views/board/bootstrap/css/style.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/main.css" />
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/js/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<style type="text/css">
	a.btn-success,button.btn-success,a.btn-primary,button.btn-primary,button.btn-primary i{color:#fff}
	btn-primary{background-color:#efefef}
	.board h3{display:none}
	.board h3:after{}
	.board .row{width:1170px;margin:0 auto}
	#fboardlist{width:1170px;margin:0 auto}
	.border_button{width:1170px;margin:0 auto 50px}
	.pull-right{}

</style>

		<!-- 본문 시작 -->
			<div id="faq" class="faq">
				<h1>이용절차<em>카카오톡 비즈메세지 공식 딜러사 <strong>DHN</strong></em></h1>
				<div class="faq1">
					<div class="wrap titwrap">
						<div class="tit">이용절차</div>
						<p>이용절차 안내 및 Q&A<br />
						<strong>카카오톡 비즈메시지 이용안내</strong></p>
					</div>
				</div>
				<h2>이용절차</h2>
				<div class="faq2">
					<div class="wrap">
						<div class="faq2_01">
							<h3><strong>1. 플러스친구 회원가입</strong></h3>
							<p class="faqTx01"><strong>카카오톡 플러스친구 회원가입하기!</strong></p>
							<p class="faqTx02">카카오 알림톡을 보내기 위해서 ‘플러스친구’ 가입은 필수입니다.<br />
							가입 전이라면 해당페이지 (https://center-pf.kakao.com/login)에서<br />
							플러스 친구를 가입해주세요.</p>
						</div>
						<div class="faq2_02">
							<div class="faq2_02_wrap">
								<a href="#"><p class="faqTx03"><strong>바로가기</strong></p>
								<p class="faqTx04">click<img src="/images/faq_dot_y.png" alt="" /></p></a>
							</div>
						</div>
					</div>
				</div>
				<div class="faq3">
					<div class="wrap">
						<div class="faq2_01">
							<h3><strong>2. DHN 회원가입</strong></h3>
							<p class="faqTx01"><strong>DHN 회원가입하기!</strong></p>
							<p class="faqTx02">DHN 회원에 가입하세요.<br />
							다양한 혜택과 함께 알림톡을 편하게 보내실 수 있습니다.</p>
						</div>
						<div class="faq2_02">
							<div class="faq2_03_wrap">
								<a href="#"><p class="faqTx03"><strong>바로가기</strong></p>
								<p class="faqTx04">click<img src="/images/faq_dot_w.png" alt="" /></p></a>
							</div>
						</div>
					</div>
				</div>
				<div class="faq2">
					<div class="wrap">
						<div class="faq2_01">
							<h3><strong>3. 템플릿 선택/만들기</strong></h3>
							<p class="faqTx01"><strong>템플릿 선택/만들기!</strong></p>
							<p class="faqTx02">문구 작성이 어려우시다구요?<br />
							업종별 다양한 템플릿이 준비되어 있습니다.</p>
						</div>
						<div class="faq2_02">
							<div class="faq2_02_wrap">
								<a href="#"><p class="faqTx03"><strong>바로가기</strong></p>
								<p class="faqTx04">click<img src="/images/faq_dot_y.png" alt="" /></p></a>
							</div>
						</div>
					</div>
				</div>
				<div class="faq3">
					<div class="wrap">
						<div class="faq2_01">
							<h3><strong>4. 알림톡 보내기</strong></h3>
							<p class="faqTx01"><strong>카카오톡 알림톡 보내기!</strong></p>
							<p class="faqTx02">알림톡 발송으로 고객관리를 더욱 효율적으로 하실 수 있습니다.</p>
						</div>
						<div class="faq2_02">
							<div class="faq2_03_wrap">
								<a href="#"><p class="faqTx03"><strong>바로가기</strong></p>
								<p class="faqTx04">click<img src="/images/faq_dot_w.png" alt="" /></p></a>
							</div>
						</div>
					</div>
				</div>
				<h2>FAQ</h2>
			</div>




			<?php if (isset($yield))echo $yield; ?>
		<!-- 본문 끝 -->
		<?include $_SERVER["DOCUMENT_ROOT"]."/views/homepage/bottom.php";?>