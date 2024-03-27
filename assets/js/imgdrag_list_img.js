// call initialization file
//if (window.File && window.FileList && window.FileReader) {
//	alert("AAAAAAAAAAA");
//  var filedrag = $("#filedrag");
//
//  // is XHR2 available?
//  var xhr = new XMLHttpRequest();
//  if (xhr.upload) {
//    // file drop
//    filedrag.on("dragover", hover);
//    filedrag.on("dragleave", hover);
//    filedrag.on("drop", handler);
//  }
//}


//add_img_drag_event("filedrag");
//add_img_drag_event("filedrag01");
//add_img_drag_event("filedrag02");

// file drag hover
function hover(e) {
	e.stopPropagation();
	e.preventDefault();
	if (e.type == "dragover")
	$(e.target).addClass("hover");else

	$(e.target).removeClass("hover");
}

// file selection
function handler(e) {
	// cancel event and hover styling
	hover(e);

	var targetId = $(e.target).attr("id");
	// alert(targetId);
	// fetch FileList object
	var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
	
	if (files.length > 0) {
		showImage(files[0], targetId);
	} else {
		//showImageURL(e.originalEvent.dataTransfer.getData('URL'), targetId);
		//showImageURL(e.originalEvent.dataTransfer.getData('Text'), targetId);
		var imgSrcUrl = getImgSrc(e);

		
		
		if(imgSrcUrl.indexOf("http") >= 0) {
			if (imgSrcUrl.indexOf("http://design.kakaomarttalk.kr/") != 0) {
				alert("이미지가 타서버 URL로 연결되어 있으면 이미지를 출력할수 없습니다.");
				return;
			}
		}
		
		showImageURL(imgSrcUrl, targetId);
//		if (imgSrcUrl.indexOf("http") >= 0) {
//			if (imgSrcUrl.indexOf("http://dhncorp.co.kr/") < 0) {
//				toDataURL(imgSrcUrl, function(dataUrl) {
//					showImageURL(dataUrl, targetId);
//				});
//			} else {
//				showImageURL(imgSrcUrl, targetId);
//			}
//		} else {
//			showImageURL(imgSrcUrl, targetId);
//		}
	}

}

//file drag hover
function titel_hover(e) {
	e.stopPropagation();
	e.preventDefault();
	if (e.type == "dragover")
	$(e.target).addClass("hover");else

	$(e.target).removeClass("hover");
}
// file selection
function titel_handler(e) {
	// cancel event and hover styling
	hover(e);

	// fetch FileList object
	var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
  
	if (files.length > 0) {
		showTitleImage(files[0]);
	} else {
		//showTitleImageURL(e.originalEvent.dataTransfer.getData('URL'));
		//alert(getImgSrc(e));
		//showTitleImageURL(e.originalEvent.dataTransfer.getData('Text'));
		var imgSrcUrl = getImgSrc(e);

		if(imgSrcUrl.indexOf("http") >= 0) {
			if (imgSrcUrl.indexOf("http://design.kakaomarttalk.kr/") != 0) {
				alert("이미지가 타서버 URL로 연결되어 있으면 이미지를 출력할수 없습니다.");
				return;
			}
		}
		showTitleImageURL(imgSrcUrl);
		//showTitleImageURL(getImgSrc(e));
  }
}

function showImage(f, targetId) {
	
	var img = $('<img>');
	var reader = new FileReader();
	reader.onloadend = function () {
		img.attr('src', reader.result);
		img.attr('id', "img_" + targetId);

		targetId = "#" + targetId;
		//alert(targetId);
		
		var parent = $(targetId);
		var del_img = parent.children('img');
		//var del_img = $(targetId).children('img');
		del_img.remove();
		$(targetId).append(img);
    
		//$('#filedrag').css('background-image', 'url(' + reader.result + ')').addClass("selected");
	};
	reader.readAsDataURL(f);
}

function showImageURL(sourceUrl, targetId) {
	var img = $('<img>');
	
	img.attr('src', sourceUrl);
	img.attr('id', "img_" + targetId);
	alert(targetId);
	targetId = "#" + targetId;

	var parent = $(targetId).parent();
	var del_img = parent.children('img');
	//var del_img = $(targetId).children('img');
	del_img.remove();
	$(targetId).before(img);
}

function showTitleImage(f) {
	
	var img = $('<img>');
	var reader = new FileReader();
	reader.onloadend = function () {
	img.attr('src', reader.result);
		//img.attr('id', "img_01")
	    
		var del_img = $("#filedrag").children('img');
		del_img.remove();
		$("#filedrag").prepend(img);
	    
		//$('#filedrag').css('background-image', 'url(' + reader.result + ')').addClass("selected");
	};
	reader.readAsDataURL(f);
}

function showTitleImageURL(sourceUrl) {
	var img = $('<img>');
	img.attr('src', sourceUrl);

	var del_img = $("#filedrag").children('img');
	del_img.remove();
	
	$("#filedrag").prepend(img);
}

function getImgSrc(e) {
	var img = e.originalEvent.dataTransfer.getData("text/html");
	
    var div = document.createElement("div");
    div.innerHTML = img;

    var meta = div.querySelector("meta");
    if (meta) {
    	div.removeChild(meta);
    }
    
    var src = div.firstChild.src;
    
   	return src;
}

function toDataURL(url, callback) {
	var httpRequest = new XMLHttpRequest();
	httpRequest.onload = function() {
		var fileReader = new FileReader();
		fileReader.onloadend = function() {
			callback(fileReader.result);
		}
		fileReader.readAsDataURL(httpRequest.response);
	};
	httpRequest.open('GET', url);
	httpRequest.responseType = 'blob';
	httpRequest.send();
}

function add_img_drag_event(targetId) {
	if (window.File && window.FileList && window.FileReader) {
		//alert("AAAAAAAAAAA");
		var filedrag = $("#" + targetId);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {
		    // file drop
		    filedrag.on("dragover", hover);
		    filedrag.on("dragleave", hover);
		    filedrag.on("drop", handler);
		}
	}
}

function add_img_title_drag_event() {
	if (window.File && window.FileList && window.FileReader) {
		//alert("BBBBBBB");
		var filedrag = $("#filedrag");
		
		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {
		    // file drop
		    filedrag.on("dragover", titel_hover);
		    filedrag.on("dragleave", titel_hover);
		    filedrag.on("drop", titel_handler);
		}
	}
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
