$("#btn_save").click(function(e) {
	//var div = $("#screenshot_wrap");
	//var div = $(".dhn_body");
	//div = div[0];

	//사진 요소 숨기기
	$('.goods_img, .goods_img2').css({'display':'none'});

	html2canvas(document.querySelector("#screenshot_wrap"), {scale: 1}).then(function (canvas) {
	//html2canvas(document.querySelector("#screenshot_wrap"), {windowWidth: document.querySelector("#screenshot_wrap").scrollWidth, windowHeight: document.querySelector("#screenshot_wrap").scrollHeight, scale: 1}).then(function (canvas) {
		//document.body.appendChild(canvas);
		canvas.toBlob(function(blob) {
			var download_name = $("#mak_content_title").val();
			if(download_name.length > 1){
				saveAs(blob, download_name+".png");
			} else {
				saveAs(blob, "제목_없음.png");
			}

			return $('.goods_img, .goods_img2').css({'display':'block'}); //저장후 활성화

		});
	});


});
