<!DOCTYPE html>
<!-- saved from url=(0036) -->
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>대형네트웍스</title>
<link rel="stylesheet" href="/js/owlcarousel/owl.carousel.min.css" />
<link rel="stylesheet" href="/js/owlcarousel/owl.theme.default.min.css" />
<link rel="stylesheet" href="/bizM/css/style.css" />
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/js/jquery.min.js"></script>
<script src="/js/owlcarousel/owl.carousel.min.js"></script>
</head>
<body>
<div id="wrap">
	<div id="container"><link rel="stylesheet" href="/bizM/css/style.css" />
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
		<!-- 본문 시작 -->
			

<div class="board">
   <h3>전화상담 예약 글쓰기</h3>
	<div class="wrap">
    <form action="/write/request_tel" class="form-horizontal" name="fwrite" id="fwrite" onsubmit="return submitContents(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="csrf_test_name" value="64c35308643e6a0a5659e173466280a6" />                                                  
		  <input type="hidden" name="mode" value="" />
        <input type="hidden" name="post_id"    value="" />
		  <input type="hidden" name="post_title" id="post_title" value="" />
		  <input type="hidden" name="post_content" id="post_content" value="" />
        <div class="form-horizontal box-table">
				<style type="text/css">
				.row-group {}
				.col-group { clear:none !important;width:50%;float:left; }
				.pd7 { padding:7px; }
				textarea{width:100% !important;min-height:200px}
				</style>
				<div class="row row-group">
               <div class="form-group col-group">
                  <label for="post_nickname" class="col-sm-4 control-label">이름</label>
                  <div class="col-sm-8 pd7">
                     <input type="text" class="form-control px150" name="post_nickname" id="post_nickname" value="" />
                  </div>
               </div>
				<div class="row row-group">
					<div class="form-group col-group">
						<label class="col-sm-4 control-label" for="ct_hp">연락처</label>
						<div class="col-sm-8 pd7"><input type="text" id="ct_hp" name="ct_hp" class="form-control input validphone" value="" required /></div>
					</div>
				</div>
            <div class="border_button text-center mt20">
                <button type="button" class="btn btn-default btn-sm btn-history-back">취소</button>
                <button type="submit" class="btn btn-success btn-sm">예약하기</button>
            </div>
        </div>
    </form>	</div>
</div>

<script type="text/javascript">
// 글자수 제한
var char_min = parseInt(0); // 최소
var char_max = parseInt(0); // 최대


function submitContents(f) {
	$('#post_title').val($('#post_nickname').val() + " (" + $('#ct_hp').val() + ") 님의 전화상담 예약");
	$('#post_content').val($('#post_title').val());
}
</script>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fwrite').validate({
        rules: {
            , post_nickname: {required :true, minlength:2, maxlength:20}
            , ct_hp: {required :true, minlength:9, maxlength:12}
        }
    });
});

window.onbeforeunload = function () { auto_tempsave(cb_board); } //]]>
</script>
		<!-- 본문 끝 -->
	</div>
</div>
</body>
</html>