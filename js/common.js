	window.onload = function(){
		/***** 사이드메뉴 slideToggle *****/
		$(document).ready(function() {
				$(".snb_menu h3").click(function() {
						var link = $(this);
						var closest_ul = link.closest("ul");
						var parallel_active_links = closest_ul.find(".active")
						var closest_li = link.closest("li");
						var link_status = closest_li.hasClass("active");
						var count = 0;

						closest_ul.find("ul").slideUp(200, function() {
								if (++count == closest_ul.find("ul").length)
										parallel_active_links.removeClass("active");
						});

						if (!link_status) {
								closest_li.children("ul").slideDown(200);
								closest_li.addClass("active");
						}
				})
		})

		/***** 체크박스 show/hide *****/
		$(function() {
		  $('.switch_content').hide();
		  $('.trigger').change(function() {
			var hiddenId = $(this).attr("data-trigger");
			if ($(this).is(':checked')) {
			  $("#" + hiddenId).show();
			} else {
			  $("#" + hiddenId).hide();
			}
		  });
		});

		/* 모바일 메뉴 펼침 */
		$( document ).ready(function() {
			$('.hamburger-menu').on('click', function() {
				$('.bar').toggleClass('animate');
				$('.backdrop').toggleClass('visible');
			var mobileNav = $('.mobile-nav');
			mobileNav.toggleClass('show');
			});
		});

		$(function () {
		  var Accordion = function (el, multiple) {
			this.el = el || {};
			this.multiple = multiple || false;

			// Variables privadas
			var links = this.el.find('.link');
			// Evento
			links.on('click', { el: this.el, multiple: this.multiple }, this.dropdown);
		  };

		  Accordion.prototype.dropdown = function (e) {
			var $el = e.data.el;
			$this = $(this),
			$next = $this.next();

			$next.slideToggle(300);
			$this.parent().toggleClass('open');

			if (!e.data.multiple) {
			  $el.find('.submenu').not($next).slideUp(300).parent().removeClass('open');
			};
		  };

		  var accordion = new Accordion($('#accordion'), false);
		});

	}

	//쿠키생성
	function set_cookie(cookieName, cookieValue, cookieExpire, cookieDomain, cookiePath, cookieSecure){
		//alert("cookieName : "+ cookieName +", cookieValue : "+ cookieValue +", cookieExpire : "+ cookieExpire +", cookieDomain : "+ cookieDomain);
		var cookieText=escape(cookieName)+'='+escape(cookieValue);
		//cookieText+=(cookieExpire ? '; EXPIRES='+cookieExpire.toGMTString() : '');
		cookieText+=(cookieExpire ? '; EXPIRES='+cookieExpire : '');
		//cookieText+=(cookiePath ? '; PATH='+cookiePath : '');
		cookieText+=(cookiePath ? '; PATH='+cookiePath : '; PATH=/');
		cookieText+=(cookieDomain ? '; DOMAIN='+cookieDomain : '');
		cookieText+=(cookieSecure ? '; SECURE' : '');
		//alert("cookieText : "+ cookieText);
		document.cookie=cookieText;
	}

    //스마트홈 미리보기
	function home_preview(url){
		window.open(url, 'home_preview', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
	}

	//스마트전단 미리보기
	function smart_preview(url){
		window.open(url, 'smart_preview', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
	}

	//스마트쿠폰 미리보기
	function coupon_preview(url, type){
		//type : 쿠폰타입(1.무료증정, 2.가격할인)
		var height = 689;
		if(type == "2") height = 564;
		window.open(url, 'coupon_preview', 'width=400, height='+ height +', location=no, resizable=no, scrollbars=no');
	}

	//에디터 미리보기
	function editor_preview(url){
		window.open(url, 'editor_preview', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
	}

	//모바일 미리보기
	function mobile_preview(url){
		window.open(url, 'mobile_preview', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
	}

	//영역 뷰(열기, 닫기)
	function div_view(id, dis){
		document.getElementById(id).style.display = dis;
	}

	//영역 뷰(열기, 닫기)
	function area_over(open_id, close_id){
		document.getElementById(open_id).style.display = "";
		document.getElementById(close_id).style.display = "none";
	}

	//특정위치로 스크롤 이동
	//window.scroll(0, getOffsetTop(document.getElementById("div_preview")));
	function getOffsetTop(el) {
		var top = 0;
		if (el.offsetParent) {
			do {
				top += el.offsetTop;
			} while (el = el.offsetParent);
			return [top];
		}
	}

	//로딩중 호출
	function jsLoading(md){
		//alert("jsLoading");
		//화면의 높이와 너비를 구합니다.
		var maskHeight = $(document).height();
		var maskWidth  = window.document.body.clientWidth;

		//화면에 출력할 마스크를 설정해줍니다.
		var mask = "";
		if(md == "SAVE"){
			mask += "<div id='id_loading_mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'>Loading...</div>";
		}else{
			mask += "<div id='id_loading_mask'  class=\"actionCon\" style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'>";
			mask += "<div class='actionType4_1'>";
			mask += "<div>";
			mask += "<div>";
			mask += "<div>";
			mask += "<div>";
			mask += "</div>";
			mask += "</div>";
			mask += "</div>";
			mask += "</div>";
			mask += "</div>";
			mask += "<div class='loading_text'>Loading ...</div>";
			mask += "</div>";
		}
		var loadingImg ='';
		//loadingImg +="<div id='div_loading_img' style='position:absolute; z-index:10000; color:#f00; left:0; top:0;'>77777777777";
		//loadingImg +=" <img src='/uploads/loading2.gif' style='position: relative; display: block; margin: 0px auto;'/>";
		//loadingImg +="</div>";

		//화면에 레이어 추가
		$('body')
			.append(mask)
			.append(loadingImg)

		//마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채웁니다.
		$('#id_loading_mask').css({
				'width' : maskWidth
				,'height': maskHeight
				,'opacity' :'0.8'
		});

		//마스크 표시
		$('#id_loading_mask').show();

		//로딩중 이미지 표시
		//$('#div_loading_img').show();
	}

	//로딩중 닫기
	function hideLoading(){
		$('#id_loading_mask').hide();
	}

	//ie 여부
	function jsIeyn(){
		var ieyn = "N";
		var agent = navigator.userAgent.toLowerCase(); //브라우저
		if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){ //ie 일때
			ieyn = "Y";
		}
		return ieyn;
	}

	//파일 사이즈 반환 (파일사이즈, 소수점)
	function jsFileSize(fileSize, decimal){
		var size = "";
		if(fileSize > (1024*1024*1024)){
			size = (fileSize / (1024*1024*1024)).toFixed(decimal) +"GB";
		}else if(fileSize > (1024*1024)){
			size = (fileSize / (1024*1024)).toFixed(decimal) +"MB";
		}else if(fileSize > 1024){
			size = (fileSize / 1024).toFixed(decimal) +"KB";
		}else{
			size = (fileSize / 0.1024) +"byte";
		}
		return size;
	}

	//파일 초기화
	function jsFileRemove(input){
		var ieyn = jsIeyn(); //ie 여부
		if (ieyn == "Y"){ //ie 일때
			$(input).replaceWith( $(input).clone(true) ); //파일 초기화
		}else{
			$(input).val(""); //파일 초기화
		}
	}

	//이미지 추가
	function jsImgChange(input, imgid, viewid, imgpath, maxSize, save_url, csrf_token, csrf_hash){
		//alert("imgid : "+ imgid +"\n"+"imgpath : "+ imgpath +"\n"+"input.value : "+ input.value); return;
		if(input.value.length > 0){
			var ext = input.value.slice(input.value.indexOf(".") + 1).toLowerCase();
			//alert("imgid : "+ imgid +"\n"+"ext : "+ ext +"\n"+"file : "+ input.value); return;
			if(ext != "gif" && ext != "jpg" && ext != "jpeg" && ext != "png"){
				jsFileRemove(input); //파일 초기화
				alert("이미지 파일만 업로드 가능합니다.\n(jpg, jpeg, gif, png)");
				return;
			}
			if (input.files && input.files[0]){
				var fileSize = input.files[0].size; //파일사이즈
				//alert("fileSize : "+ fileSize);
				if(maxSize < fileSize){
					jsFileRemove(input); //파일 초기화
					alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
					return;
				}
				jsImgRemove(imgid, viewid); //이미지 초기화
				jsImgUpload(input, imgid, viewid, imgpath, save_url, csrf_token, csrf_hash); //이미지 업로드
			}
		}
	}

	//이미지 초기화
	function jsImgRemove(imgid, viewid){
		$("#"+ imgid).attr("src", "");
		if(viewid != ""){
			$("#"+ viewid).attr("src", "");
		}
	}

	//이미지 업로드
	function jsImgUpload(input, imgid, viewid, imgpath, save_url, csrf_token, csrf_hash) {
		//alert("readURL(input, divid) > input.value : "+ input.value +", divid : "+ divid);
		if (input.files && input.files[0]){
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#"+ imgid).attr("src", e.target.result);
				if(viewid != ""){
					$("#"+ viewid).attr("src", e.target.result);
				}
			}
			reader.readAsDataURL(input.files[0]);

			//이미지 추가
			var formData = new FormData();
			formData.append("imgfile", input.files[0]); //이미지
			formData.append(csrf_token, csrf_hash);
			$.ajax({
				url: save_url,
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
					if(json.code == "0") { //성공
						jsFileRemove(input); //파일 초기화
						$("#"+ imgpath).val(json.imgpath); //이미지경로
					}else{
						jsFileRemove(input); //파일 초기화
						$("#"+ imgid).attr("src", "");
						if(viewid != ""){
							$("#"+ viewid).attr("src", "");
						}
						alert(json.msg);
						return;
					}
				}
			});
		}
	}

	//모달창 메시지
	function showSnackbar(msg, delay) {
		var x = document.getElementById("snackbar");
		x.innerHTML = msg;
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, delay);
	}

	//금액 콤마 찍기
	function comma(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	//숫자가 입력되면 3자리 마다 콤마 찍기
	function jsNumberFormat(obj) {
	  obj.value = rtnComma(rtnUncomma(obj.value));
	}

	// 콤마 찍기
	function rtnComma(str) {
	  str = String(str);
	  return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
	}

	// 콤마 풀기
	function rtnUncomma(str) {
	  str = String(str);
	  return str.replace(/[^\d]+/g, '');
	}

	//체크된값 반환 (라디오/체크박스)
	function value_check(name){
		var rtn = "";
		var check_count = document.getElementsByName(name).length;
		for (var i=0; i<check_count; i++) {
			if (document.getElementsByName(name)[i].checked == true) {
				rtn = document.getElementsByName(name)[i].value;
			}
		}
		return rtn;
	}

	//숫자 앞에 0 붙이기
	function numPad(n, width) {
		n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
	}

	//폰트 사이즈
	function jsFontSize(id){
		var classId = $("#"+ id);
		var currentSize = classId.css("fontSize"); //폰트사이즈를 알아낸다.
		var num = parseFloat(currentSize, 10); //parseFloat()은 숫자가 아니면 숫자가 아니라는 뜻의 NaN을 반환한다.
		return num;
	}

	//모달창 열기
	function modal_open(id){
		var modal = document.getElementById(id);
		modal.style.display = "block";
		var span = document.getElementById("close_"+ id);
		span.onclick = function() {
			modal_close(id); //모달창 닫기
            if(id == "myModalAll"){
                $('.btn_send').prop('disabled', false);
                console.log($('.btn_send').prop('disabled'));
            }
		}
		window.onclick = function(event) {
			if (event.target == modal) {
				modal_close(id); //모달창 닫기
                if(id == "myModalAll"){
                    $('.btn_send').prop('disabled', false);
                    console.log($('.btn_send').prop('disabled'));
                }
			}
		}
	}

	//모달창 닫기
	function modal_close(id){
		var modal = document.getElementById(id);
		modal.style.display = "none";
	}

	//숫자만 출력
	function rtn_number(str){
		var rtn = "";
		if(str != ""){
			rtn = str.replace(/[^0-9]/g,'');
		}
		return rtn;
	}

	//전화번호 입력시 자동 대시(하이픈, "-") 삽입
	function phone_chk(obj){
		//alert("$(obj).val() : "+ $(obj).val());
		$(obj).val( $(obj).val().replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-") );
	}

	//오늘 날짜 (yyyy-mm-dd) 형식으로 가져오기
	function getToday(){
		var d = new Date();
		var s = d.getFullYear() + '-' + numPad(d.getMonth() + 1, 2) + '-' + numPad(d.getDate(), 2);
		return s;
	}
