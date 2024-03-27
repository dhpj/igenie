<?php include 'common/header.php'; ?>
		<div class="contents">
			<div class="cate_title">
				장바구니
			</div>
			<div class="list_wrap cart">
				<div class="card_wrap cart">
					<div class="thum"><img src="img/products/farm/001.jpg"></div>
					<div class="info">
						<p class="name">상품명<span class="option">100g/국내산</span></p>
						<p class="price">5,000원</p>
					</div>
					<div class="amount">1</div>
					<div class="btn_del"><i class="material-icons">clear</i></div>
				</div>
				<div class="card_wrap cart">
					<div class="thum"><img src="img/products/meat/003.jpg"></div>
					<div class="info">
						<p class="name">상품명<span class="option">100g/국내산</span></p>
						<p class="price">5,000원</p>
					</div>
					<div class="amount">1</div>
					<div class="btn_del"><i class="material-icons">clear</i></div>
				</div>
				<div class="card_wrap cart">
					<div class="thum"><img src="img/products/farm/001.jpg"></div>
					<div class="info">
						<p class="name">상품명<span class="option">100g/국내산</span></p>
						<p class="price">5,000원</p>
					</div>
					<div class="amount">1</div>
					<div class="btn_del"><i class="material-icons">clear</i></div>
				</div>
			</div>
		</div>
		<div class="order_wrap">
			<div class="order_info">
				<div class="price">50,000원</div>
				<div class="amount">전체주문(5개)</div>
			</div>
			<div class="btn_group">
				<button class="btn md del">전체 비우기</button>
				<button class="btn md order"><a href="#ex1" rel="modal:open">주문하기</a></button>
			</div>
		</div>
		<?php include 'common/nav.php'; ?>
	</div><!-- mall_wrap END-->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/nav.js" id="rendered-js"></script>
	
	<!-- Remember to include jQuery :) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<!-- Modal HTML embedded directly into document -->
<div id="ex1" class="modal">
  <p>주문자 정보(전화번호, 주소, 이름, 원하는 배송시간등)</p>
  <p>결재방법 선택 : 현금결제/카드결제</p>
  <p>메모</p>
</div>

</body>
</html>