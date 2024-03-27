
// All in one step HTML->FILE
//$("#btn_save").click(function(e) {
//  html2canvas($("#screenshot_wrap"), {
//    onrendered: function(canvas) {
//      canvas.toBlob(function(blob) {
//        saveAs(blob, "001.png");
//      });
//    }
//  });
//});

//$("#btn_save").click(function(e) {
//
//  html2canvas($("#screenshot_wrap")[0], {
//	  useCORS: true,
//    onrendered: function(canvas) {
//      canvas.toBlob(function(blob) {
//    	  saveAs(blob, "001.png");
//      });
//    }
//  });
//});

//$("#btn_save").click(function(e) {
//	var div = $("#screenshot_wrap");
//	div = div[0];
//	//alert($(div).html());
//	html2canvas(div, {
//		useCORS: false,
//		onrendered: function(canvas) {
//			alert("Aaaaa");
//			canvas.toBlob(function(blob) {
//				alert("bbbbb");
//				saveAs(blob, "001.png");
//			});
//		},
//		letterRendering: false
//	});
//});

$("#btn_save").click(function(e) {
	//var div = $("#screenshot_wrap");
	//var div = $(".dhn_body");
	//div = div[0];
	//alert("TEST\n" + $(div).html());
	html2canvas(document.querySelector("#screenshot_wrap"), {scale: 1}).then(function (canvas) {
		//document.body.appendChild(canvas);
		canvas.toBlob(function(blob) {
			saveAs(blob, "001.png");
		});
	});

});
