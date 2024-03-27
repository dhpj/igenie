<!--<div class="pos_none">
	<p>
	쇼핑몰 서비스는 프리미엄 요금제부터 사용가능합니다.<br />
  장바구니 상품, 집앞으로 바로배송! 플친몰은 판매자와 구매자 모두를 위한 플랫폼입니다.<br />
  대표문의 : <span>1522-7985</span>
	</p>
</div>-->

<div class="pf_none">
<div class="slideshow-container">

<div class="mySlides">
  <div class="numbertext">1 / 4</div>
  <img src="/images/pf_slide02.jpg" style="width:100%">
  <div class="text">Caption Text</div>
</div>

<div class="mySlides">
  <div class="numbertext">2 / 4</div>
  <img src="/images/pf_slide01.jpg" style="width:100%">
  <div class="text">Caption Text</div>
</div>

<div class="mySlides">
  <div class="numbertext">3 / 4</div>
  <img src="/images/pf_slide04.jpg" style="width:100%">
  <div class="text">Caption Text</div>
</div>

<div class="mySlides">
  <div class="numbertext">4 / 4</div>
  <img src="/images/pf_slide03.jpg" style="width:100%">
  <div class="text">Caption Text</div>
</div>

<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>
<br />
<div style="text-align:center; width:1000px;">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
  <span class="dot" onclick="currentSlide(4)"></span>
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}
</script>
<div class="pf_btn_cen">
	<a href="" onClick="mobile_preview('http://dhmart.pfmall.co.kr');">플친몰 체험하기</a>
	<a href="/uploads/genie_pfmall.pdf" target="_blank">플친몰 제안서보기</a>
</div>
  <p class="pf_info">서비스 업그레이드 문의 : <span>1522-7985</span></p>
</div>
