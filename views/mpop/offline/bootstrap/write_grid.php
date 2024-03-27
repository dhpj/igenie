<script>
    var type_data = JSON.parse('<?=$type_data?>');
    var selected_type = '<?=$type?>';
    <?
        $td = json_decode($type_data);
        $td_t = $td->title;
        $dt = json_decode($data->pmd_data);
        $block_title_cnt = array_sum($td->block->title);
    ?>
    <?if(!empty($data->pmd_data)){?>
    var pmd_data = JSON.parse('<?=$data->pmd_data?>');
    <?}else{?>
    var pmd_data = '';
    <?}?>
    var max_option = <?=$max_option?>;
    var title_yn;
    var pageLibrary = 1; //페이지
	var totalLibrary = 0; //전체수
</script>

<div class="mpop_title_wrap">

    <input type="text" id="mpop_title" placeholder="전단이름을 입력해주세요." class="mpop_title" maxlength="35" value="<?=$data->pmd_title?>">
</div>

<!-- <div class="xlsx_drop_wrap">
    <span class="btn_excel_down">
        <a href="/uploads/sample/goods_sample.xlsx?v=231205112359">엑셀 샘플 다운로드</a>
    </span>
    <label for="excelFile"><div id="drop" class="xlsx_drop">여기에 엑셀 파일 드롭</div></label>
    <input type="file" id="excelFile" onchange="excelExport(this)" accept=".xls, .xlsx" style="display:none;"><?//엑셀 파일 드롭?>
</div> -->
<!-- 타입 선택하기 -->
<div class="selcet_mpop_type">
    <button class="btn_tr_back2" type="button" onclick="open_type_modal('<?=$type?>')">타입 선택하기</button>
</div>

<!-- 타이틀 박스 -->
<div id='title_main_box' style='display:<?=$td_t->useyn ? '' : 'none'?>'>

    <h4 class="title_using">
        <span class="txt_st_eng">STEP 1.</span>
        <label class="text_st2">타이틀 사용유무</label>
        <input type='radio' name='title_yn' id='title_yn_y' value='y' <?=$dt[0]->title->useyn == 'y' || empty($dt[0]->title->useyn) ? 'checked' : ''?>>
        <label for='title_yn_y'>사용</label>
        <input type='radio' name='title_yn' id='title_yn_n' value='n' <?=$dt[0]->title->useyn == 'n' ? 'checked' : ''?>>
        <label for='title_yn_n'>미사용</label>
    </h4>

    <!-- 타이틀 상세 -->
    <ul id='title_content_box' style='display:<?=$dt[0]->title->useyn == 'y' || empty($dt[0]->title->useyn) ? '' : 'none'?>'>
        <li id='title_name_box'>
            <div class="t_mart_name_wrap">
                <label>메인 타이틀</label>
                <input type='text' id='mart_name_main' value='<?=$dt[0]->title->mart_name->main?>' onkeyup='set_preview_text(this, "prev_mart_name_main")' placeholder='메인 타이틀'>
            </div>
            <div class="t_mart_name_wrap">
                <label>서브 타이틀</label>
                <input type='text' id='mart_name_sub' value='<?=$dt[0]->title->mart_name->sub?>' onkeyup='set_preview_text(this, "prev_mart_name_sub")' placeholder='서브 타이틀'>
            </div>
        </li>
    <?if ($td_t->option > 0){?>
        <li id='title_option'>
        <?for($a=0;$a<$td_t->option;$a++){?>
            <div class="t_option_wrap">
                <label>옵션<?=$a+1?></label>
                <input type='text' id='option_<?=$a?>' value='<?=$dt[0]->title->option[$a]?>' onkeyup='set_preview_text(this, "prev_option_<?=$a?>")' placeholder='옵션<?=$a+1?>'>
            </div>
        <?}?>
        </li>
    <?}?>
    <?if ($td_t->image > 0){?>
        <li id='title_image'>
        <?for($a=0;$a<$td_t->image;$a++){?>
            <div class="t_image_wrap">
                <label>이미지<?=$a+1?></label>

                <!-- 이미지 들어가는 부분 -->
                <div class="templet_img_wrap">
                    <label for="title_img_file_<?=$a?>" class="templet_img_in">
                        <img id="image_<?=$a?>" src='<?=str_replace('\\', '', $dt[0]->title->image[$a])?>' style='width:100%;display:<?=!empty($dt[0]->title->image[$a]) ? '' : 'none'?>'>
                    </label>
                    <input type="file" title="이미지 파일" id="title_img_file_<?=$a?>" onClick="init_colrow(<?=$a?>);" onChange="imgChange(this, 0);" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
                    <button class="templet_img_delete" type="button" onclick='$("#image_<?=$a?>").attr("src", "");$("#image_<?=$a?>").css("display", "none");$("#prev_image_<?=$a?>").css("display", "none");'>X</button>
                </div>

            </div>
        <?}?>
        </li>
    <?}?>
    </ul>
</div>

<div class="grid_detail_wrap" style='display:<?=$block_title_cnt != '0' ? '' : 'none'?>'>
    <h4 class="title_block">
        <span class="txt_st_eng">STEP 2.</span>
        <span class="text_st2">블록 타이틀을 입력하세요.</span>
    </h4>
    <ul id='block_title_box' class="grid_detail_top">
<?foreach($td->block->title as $key => $a){?>
        <li style='display:<?=$a ? '' : 'none'?>'><label><?=$key+1?>번째 블록 타이틀</label><input type='text' id='block_title_<?=$key?>' value='<?=$dt[0]->block[$key]->title?>' onkeyup='$("#prev_block_<?=$key?>").find("span").html(this.value)'></li>
<?}?>
    </ul>
</div >

<div class="grid_detail_wrap">
    <div class="excelFile_wrap">
        <div class="drop_excelFile">
            <label for="excelFile"><div id="drop" class="xlsx_drop">이곳에 엑셀 파일을 드롭해 주세요.</div></label>
            <input type="file" id="excelFile" onchange="excelExport(this)" accept=".xls, .xlsx" style="display:none;"><?//엑셀 파일 드롭?>
        </div>
        <span>전단을 작성할 파일을 선택하세요.</span>
        <select id="sel_pos">
            <option value="">- 선택 -</option>
			<option value="together">투게더(특매상품)</option>
		</select>
    </div>

    <h4 class="title_upload">
        <span class="txt_st_eng">STEP <?=$block_title_cnt != '0' ? '3' : '2'?>.</span>
        <span class="text_st2">상품 정보를 입력/ 수정하세요.</span>
        <button class="btn md" type="button" onclick="showLibrary('img', 2);">상품이미지 일괄등록</button>
        <span class="text_st3"><em>■</em> 칸은 입력불가 합니다.</span>
    </h4>
    <div id='grid_detail' class='<?=$type?>_t'></div>
</div>

<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/mpop/offline/bootstrap/write_modal.php');?>

<script>
    var modal1 = document.getElementById('modal1');
    var modal2 = document.getElementById('modal2');
    var modal3 = document.getElementById('modal3');
    var modal4 = document.getElementById('modal4');
    var modal5 = document.getElementById('modal5');
    var sel_img_flag = 0;

    function set_preview_text(t, target){
        $("#" + target).html(t.value);
    }

    function load_body(id, type){
        $("#body").html("").load(
            "/mpop/offline/write_body",
            {
                <?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"
              , id : id
              , type : type
            },
            function() {
            }
        );
    }

    function get_type_list(){
        $.ajax({
			url: "/mpop/offline/get_type_list",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
            },
			success: function (json) {
                $('#type_list').html(json.html);
			}
		});
    }

    function change_type(tp){
        load_grid(<?=$id?>, tp);
    }

    $(document).ready(function (){
        load_body(<?=$id?>, '<?=$type?>');
    }).on('change', '[name=title_yn]', function(){
        if ($(this).val() == 'y'){
            $('#title_content_box').css('display', '');
            $('#prev_title').css('display', '');
        } else {
            $('#title_content_box').css('display', 'none');
            $('#prev_title').css('display', 'none');
        }
    }).on('keydown', function(e){
        if (e.keyCode === 27){
            if ($('#modal2').css("display") == "block"){
                $('#modal2').css("display", "none");
            } else if ($('#modal1').css("display") == "block"){
                $('#modal1').css("display", "none");
            }

            if($('#modal3').css("display") == "block"){
                $('#modal3').css("display", "none");
            }
        }
    });

    $(document).ready(function (){
        <?if($this->member->item('mem_id') == '2'){?>
        $("#body_test").html("").load(
            "/mpop/offline/write_body_test",
            {
                <?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"
              , id : <?=$id?>
            },
            function() {}
        );
        <?}?>
    }).on('change', '[name=title_yn]', function(){
        if ($(this).val() == 'y'){
            $('#title_content_box').css('display', '');
        } else {
            $('#title_content_box').css('display', 'none');
        }
    });

    const container = document.querySelector('#grid_detail');
    var hot;

    var data = <?=$grid_data?>;
    function imageRenderer(instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        function_name = col === 0 ? 'open_img_modal' : 'open_badge_modal';
        target_name = col === 0 ? 'goods_img' : 'goods_badge';
        td.innerHTML = "<div class='templet_img_wrap'><div onClick='" + function_name + "(this, " + row + ", " + col + ")'>" + (value != "" ? "<img src='" + value + "'>" : '') + "</div><button class='templet_img_delete' type='button' onclick='del_grid_image(this, " + row + ", " + col + ", \"" + target_name + "\")'>X</button></div>";
        $(td).find('div img').css('width', '90').css('height', '90');
    }

    var row_h = <?=$row_h?>;
    var col_h = <?=$col_h?>;
    var row_h_flag = 0;
    hot = new Handsontable(container, {
        data : data,
        rowHeaders: row_h,
        colHeaders: col_h,
        height: 'auto',
        manualRowResize: false,
        minSpareRows: 0,
        minSpareCols: 0,
        rowHeaderWidth: 90,
        manualRowMove: true,
        maxRows: <?=$row_h?>.length,
        maxCols: <?=$col_h?>.length,
        licenseKey: 'non-commercial-and-evaluation', // for non-commercial use only
        cells: function(row, col){
            var cp = {}
            if (row_h[row] != ''){
                row_h_flag = Number(row_h[row].replace(/[^0-9]/g, ""));
            }
            if(col === 0 || ("<?=$col_h?>".indexOf('뱃지') != -1 && col === <?=$col_h?>.length - 1)){
                cp.renderer = imageRenderer;
                cp.editor = false;
                cp.readOnly = true;
            }
            if (!type_data.block.goods_price[row_h_flag-1] && col === 2){
                data[row][col] = '';
                cp.renderer = function(instance, td, row, col, prop, value, cellProperties){
                    Handsontable.renderers.TextRenderer.apply(this, arguments);
                    td.style.backgroundColor = 'rgb(102,102,102)';
                }
                cp.readOnly = true;
            }
            return cp
        },
        afterChange : function(changes, source){
            $.each(changes, function(idx, val){
                if(val[2] !== val[3]){
                    val[3] = val[3].replaceAll(/(?:\r\n|\r|\n)/g, '');
                    data[val[0]][val[1]] = val[3].replaceAll(/(?:\r\n|\r|\n)/g, '');
                    if (col_h[val[1]] == '상품 이름'){
                        $('#goods_' + val[0]).find('.goods_name span').html(val[3]);
                    } else if (col_h[val[1]] == '상품 가격'){
                        $('#goods_' + val[0]).find('.goods_price span').html(val[3]);
                    } else if (col_h[val[1]] == '상품 할인가격'){
                        $('#goods_' + val[0]).find('.goods_dcprice span').html(val[3]);
                    } else if (col_h[val[1]].indexOf('옵션') != -1){
                        $('#goods_' + val[0]).find('.goods_option_' + (Number(col_h[val[1]].substring(2)) - 1) + ' span').html(val[3]);
                    }
                }
            });
        },
        afterInit : function(){

        },
        afterRowMove: function(startRow, endRow){
            data = hot.getData();
            set_preview(startRow, endRow);
        }
    });

    function set_preview(sr, er){
        var start = 0;
        var end = 0;
        if (sr[0] < er){
            start = sr[0];
            end = er + sr.length;
        } else {
            start = er;
            end = sr.reverse()[0] + 1;
        }
        for(var i=start;i<end;i++){
            $('#goods_' + i).find('.goods_img img').attr('src', (data[i][0] != '' ? data[i][0] : no_image));
            $('#goods_' + i).find('.goods_name span').html(data[i][1]);
            $('#goods_' + i).find('.goods_price span').html(data[i][2]);
            $('#goods_' + i).find('.goods_dcprice span').html(data[i][3]);
            for(var j=4;j<data[i].length;j++){
                if(col_h[j].indexOf('옵션') != -1){
                    $('#goods_' + i).find('.goods_option_' + (Number(col_h[j].substring(2)) - 1) + ' span').html(data[i][j]);
                } else if (col_h[j] == '뱃지 이미지'){
                    if (data[i][j] == ''){
                        $('#goods_' + i).find('.goods_badge').css('display', 'none');
                    } else {
                        $('#goods_' + i).find('.goods_badge').css('display', '');
                        $('#goods_' + i).find('.goods_badge img').attr('src', data[i][j]);
                    }
                }
            }
        }
    }

    var sel_img;
    var sel_row;
    var sel_col;

    function init_colrow(seq){
        sel_img = seq;
        sel_row = undefined;
        sel_col = undefined;
    }

    function open_img_modal(t, row, col){
        sel_img = t;
        sel_row = row;
        sel_col = col;
        modal1.style.display = 'block';
    }

    function open_badge_modal(t, row, col){
        sel_img = t;
        sel_row = row;
        sel_col = col;
        modal3.style.display = 'block';
        //뱃지 리스트 조회
        var html = '';
        html += "<li onclick=\"badge_choice('"+ t +"', '"+ sel_row + "', '" + sel_col + "', '0', '');\" style=\"cursor: pointer;\">";
		html += "  <p class=\"badge_img\"><img src=\"/dhn/images/sale_badge_no.jpg\"></p>";
		html += "  <div class=\"badge_text\">";
		html += "    <div class=\"checks\">";
		html += "      <input type=\"radio\" name=\"rad_badge\" value=\"0\" id=\"badge_imgpath_0\">";
		html += "      <label for=\"badge_imgpath_0\">사용안함</label>";
		html += "    </div>";
		html += "  </div>";
		html += "</li>";
		$.ajax({
			url: "/spop/screen_v2/ajax_badge_list",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					var tit_id = value.tit_id; //뱃지번호
					var tit_imgpath = value.tit_imgpath; //뱃지경로
					html += "<li onclick=\"badge_choice('"+ t +"', '"+ sel_row + "', '" + sel_col + "', '" + tit_id + "', '" + tit_imgpath +"');\" style=\"cursor: pointer;\">";
					html += "  <p class=\"badge_img\"><img src=\""+ tit_imgpath +"\"></p>";
					html += "  <div class=\"badge_text\">";
					html += "    <div class=\"checks\">";
					html += "      <input type=\"radio\" name=\"rad_badge\" value=\""+ tit_id +"\" id=\"badge_imgpath_"+ tit_id +"\">";
					html += "      <label for=\"badge_imgpath_"+ tit_id +"\">뱃지선택</label>";
					html += "    </div>";
					html += "  </div>";
					html += "</li>";
				});
				$("#modal_badge_list").html(html);
			}
		});
    }

    function open_type_modal(tp){
        get_type_list();
        modal5.style.display = 'block';
    }

    //행사뱃지 선택
	function badge_choice(step, row, col, tit_id, tit_imgpath){
        if (tit_imgpath == ''){
            del_grid_image(sel_img, sel_row, sel_col, "goods_badge");
        } else {
            set_grid_image(sel_img, sel_row, sel_col, tit_imgpath, "goods_badge");
            $('#goods_' + sel_row).find('.goods_badge').css('display', '');
        }
		modal3.style.display = "none"; //모달창 닫기
	}

    function set_grid_image(t, row, col, path, target){
        data[row][col] = path;
        $('#goods_' + row).find('.' + target + ' img').attr('src', path);
        hot.render();
    }

    function del_grid_image(t, row, col, target){
        data[row][col] = "";
        if (target == 'goods_img'){
            $('#goods_' + row).find('.' + target + ' img').attr('src', no_image);
        } else {
            $('#goods_' + row).find('.' + target).css('display', 'none');
        }
        hot.render();
    }

    function showLibrary(type, flag){
		$("#id_searchName").val("name"); //검색이름
		if(type == "img"){ //이미지 라이브러리
            sel_img_flag = flag;
            if (flag === 1){
                $('#modal2_img_box').css('display', 'none');
            } else if (flag === 2){
                $('#modal2_img_box').css('display', '');
            }
			$("#id_searchDiv").show(); //검색영역 열기
			$("#searchCate1").show(); //대분류 열기
			$("#searchCate2").show(); //소분류 열기
			$("#id_searchName").show(); //검색타입 열기
			$("#id_searchLibrary").attr("placeholder", "검색어를 입력하세요."); //검색내용 placeholder 변경
		}else if(type == "keep"){ //이미지보관함
			$("#id_searchDiv").show(); //검색영역 닫기
			$("#searchCate1").hide(); //대분류 닫기
			$("#searchCate2").hide(); //소분류 닫기
			$("#id_searchName").hide(); //검색타입 닫기
			$("#id_searchLibrary").attr("placeholder", "이미지명 입력하세요."); //검색내용 placeholder 변경
		}else{ //상품 라이브러리
			$("#id_searchDiv").show(); //검색영역 열기
			$("#searchCate1").hide(); //대분류 닫기
			$("#searchCate2").hide(); //소분류 닫기
			$("#id_searchName").hide(); //검색타입 닫기
			$("#id_searchLibrary").attr("placeholder", "상품명 입력하세요."); //검색내용 placeholder 변경
		}
		var span = document.getElementById("dh_close2");
		modal2.style.display = "block";
		$("#library_type").val(type); //라이브러리 타입
		if(type == "goods"){
			$("#id_modal_title").html("최근상품 리스트");
		}else if(type == "keep"){
			$("#id_modal_title").html("이미지보관함");
		}else{
			$("#id_modal_title").html("이미지 라이브러리");
		}

		//alert("goods_step_id : "+ goods_step_id +", goods_name : "+ goods_name +", goods_option : "+ goods_option); return;
		//규격검색 사용유무체크
		var searchstr ='';
		$("#id_searchLibrary").val(searchstr); //검색내용
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
		span.onclick = function() {
			removeImgLibrary(); //라이브러리 초기화
			modal1.style.display = "none"; //상품 이미지 추가 모달창 닫기
			modal2.style.display = "none"; //라이브러리 모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == modal2) {
				removeImgLibrary(); //라이브러리 초기화
				modal1.style.display = "none"; //상품 이미지 추가 모달창 닫기
				modal2.style.display = "none"; //라이브러리 모달창 닫기
			}
		}
	}

    //라이브러리 초기화
	function removeImgLibrary(){
		pageLibrary = 1; //페이지
		totalLibrary = 0; //전체수
		$("#library_append_list").html(""); //초기화
	}

    //라이브러리 검색시
	function searchImgLibrary(){
		removeImgLibrary(); //라이브러리 초기화
		searchLibrary(); //라이브러리 조회
	}

    $("#id_searchLibrary").keydown(function(key) {
		if (key.keyCode == 13) {
			searchImgLibrary(); //라이브러리 검색
		}
	});

    $("#library_append_list").scroll(function(){
        var dh = $("#library_append_list")[0].scrollHeight;
        var dch = $("#library_append_list")[0].clientHeight;
        var dct = $("#library_append_list").scrollTop();
        //alert("스크롤 : " + dh + "=" + dch +  " + " + dct);
        //alert("스크롤 : " + dh + "=" + dch +  " + " + dct); return;
        if(dh == (dch+dct)) {
            var rowcnt = $(".img_select").length;
            //alert("totalLibrary : " + totalLibrary + " / rowcnt : " + rowcnt); return;
            if(rowcnt < totalLibrary) {
                searchLibrary();
            }
        }
    });

    //라이브러리 조회
	function searchLibrary(){
		var searchCate1 = $("#searchCate1").val(); //대분류
		var searchCate2 = $("#searchCate2").val(); //소분류
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		var perpage = 28;
		$.ajax({
			url: "/mpop/offline/search_library",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
                "perpage" : perpage,
                "page" : pageLibrary,
                "searchCate1" : searchCate1,
                "searchCate2" : searchCate2,
                "searchnm" : searchnm,
                "searchstr" : searchstr,
                "library_type" : library_type,
                "sel_img_flag" : sel_img_flag,
                "page_yn" : 'N',
            },
			success: function (json) {
				pageLibrary = json.page;
				totalLibrary = json.total;
				$("#library_append_list").append(json.html); //이미지 리스트
				$("#id_modal_count").html(comma(json.total)); //이미지 건수
			}
		});
	}

    //라이브러리 페이지 조회
	function searchLibraryPage(page, flag){
		var searchCate1 = $("#searchCate1").val(); //대분류
		var searchCate2 = $("#searchCate2").val(); //소분류
		var searchnm = $("#id_searchName").val(); //검색이름
		var searchstr = $("#id_searchLibrary").val(); //검색내용
		var library_type = $("#library_type").val(); //라이브러리 타입
		var perpage = 10;
		$.ajax({
			url: "/mpop/offline/search_library",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , "perpage" : perpage
              , "page" : page
              , "searchCate1" : searchCate1
              , "searchCate2" : searchCate2
              , "searchnm" : searchnm
              , "searchstr" : searchstr
              , "library_type" : library_type
              , "sel_img_flag" : sel_img_flag
              ,  "page_yn" : 'N'
            },
            success: function (json) {
                $("#library_append_list").html(json.html); //이미지 리스트
                $("#id_modal_count").html(comma(json.total)); //이미지 건수
                $("#library_page").html(json.page_html); //페이징
            }
        });
    }

    //이미지 라이브러리 선택 클릭시
	function set_img_library(imgpath){
		if(imgpath != ""){
            var url = imgpath;
            var fileLength = url.length;
            var lastDot = url.lastIndexOf('.');
            var fileUrlName = url.substring(0, lastDot);
            var fileExt = url.substring(lastDot+1, fileLength);
            var fileurl = '';
            if(url.indexOf('_thumb')>0){
                fileurl = url;
            }else{
                fileurl = fileUrlName+'_thumb.'+fileExt;
            }
            set_grid_image(sel_img, sel_row, sel_col, fileurl, "goods_img");
			modal1.style.display = "none"; //상품 이미지 추가 모달창 닫기
			$("#library_append_list").html(""); //라이브러리 모달창 초기화
			modal2.style.display = "none"; //라이브러리 모달창 닫기
		}
	}

    //이미지 선택 클릭시
	function imgChange(input, flag){
		if(input.value.length > 0) {
			if (input.files && input.files[0]) {
				readURL(input, sel_row, flag);
				modal1.style.display = "none"; //상품 이미지 추가 모달창 닫기
			}
		}
	}

    //이미지 경로 세팅
	function readURL(input, row, flag){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
			}
			reader.readAsDataURL(input.files[0]);

			//이미지 추가
			request = new XMLHttpRequest();
			var formData = new FormData();
			formData.append("imgfile", input.files[0]); //이미지
            formData.append("flag", flag);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			request.onreadystatechange = imgCallback;
			request.open("POST", "/mpop/offline/imgfile_save");
			request.send(formData);
		}
	}

    function imgCallback(){
		if(request.readyState  == 4) {
			var obj = JSON.parse(request.responseText);
			if(obj.code == "0") { //성공
				if (sel_row === undefined && sel_col === undefined){
                    $('#image_' + sel_img).css('display', '');
                    $('#prev_image_' + sel_img).css('display', '');
                    $('#image_' + sel_img).attr('src', obj.imgpath);
                    $('#prev_image_' + sel_img + ' img').attr('src', obj.imgpath);
                } else {
                    $('#img_file').val('');
                    set_grid_image(sel_img, sel_row, sel_col, obj.imgpath, "goods_img");
                }
			} else { //오류
			}
		}
	}

    $("#drop").on("dragenter", function(e){
        e.preventDefault();
        e.stopPropagation();
    }).on("dragover", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).css("background-color", "rgb(221, 236, 202)"); //연한 연두색
    }).on("dragleave", function(e){
        e.preventDefault();
        e.stopPropagation();
        //$(this).css("background-color", "#FFF");
    }).on("drop", function(e){
        e.preventDefault();
        $(this).css("background-color", "");
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 1) {
            alert("엑셀 파일 하나만 드래그하세요.");
            return;
        }
        if($('#sel_pos').val() == ''){
            alert('포스를 선택해주세요.');
            return;
        }
        var file_name = files[0].name; //1번째 파일
        var file_size = files[0].size ; //1번째 파일
        excel_upload(files[0]);
    });

    function excelExport(input){
        if($('#sel_pos').val() == ''){
            alert('포스를 선택해주세요.');
            $('#excelFile').val('');
            return;
        }
        excel_upload(input.files[0]);
    }

	//엑셀 업로드
	function excel_upload(file){
		var file_name = file.name;
		var ext = file_name.slice(file_name.indexOf(".") + 1).toLowerCase();

		if(ext == "xls" || ext == "xlsx"){
			var file_data = file;
			var formData = new FormData();
			formData.append("file", file_data);
            formData.append("pos", $('#sel_pos').val());
            formData.append("goods_cnt", <?=$goods_cnt?>);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			$.ajax({
				url: "/mpop/offline/excel_upload",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (json) {
                    $('#excelFile').val('');
                    var last_seq = -1;
                    console.log(data);
                    $.each(json, function(idx, val){
                        data[idx][1] = val[1];
                        if (type_data.block.goods_price[0] === 1){
                            data[idx][2] = val[2];
                        }
                        data[idx][3] = val[3];
                        last_seq++;
                    });
                    set_preview([0], last_seq);
                    hot.render();
				}
			});
			var agent = navigator.userAgent.toLowerCase(); //브라우저
			if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ){ //ie 일때
				$("#excelFile").replaceWith( $("#excelFile").clone(true) ); //파일 초기화
			}else{
				$("#excelFile").val(""); //파일 초기화
			}
		} else {
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
		}
	}

    function set_img_library2(path){
        if ($('#selected_img').find('img').length == <?=$goods_cnt?>){
            alert('최대 등록 개수 입니다.');
            return;
        }
        var html = "";
        html += '<p onclick="$(this).remove();reset_image_seq();image_counter();"><span>' + ($('#selected_img').find('img').length + 1) + '</span><img src="' + path + '"></p>';
        $('#selected_img').append(html);
        image_counter();
    }

    function reset_image_seq(){
        $.each($('#selected_img').find('span'), function(idx, val){
            $(this).html(idx + 1);
        });
    }

    function reset_image(){
        $('#selected_img').html('');
        image_counter();
    }

    function set_images(){
        $.each($('#selected_img').find('img'), function(idx, val){
            var src = $(this).attr('src');
            data[idx][0] = src;
            $('#goods_' + idx).find('.goods_img img').attr('src', src);
        });
        modal2.style.display = 'none';
        hot.loadData(data);
        image_counter();
    }

    function image_counter(){
        $('#modal2_img_box').find('em').html($('#selected_img').find('img').length + '개');
    }

    // function disable_row(sel_row, img_flag){
    //     hot.updateSettings({
    //         cells: function (row, col) {
    //             var cp = {};
    //
    //             if(col === 0 || ("<?=$col_h?>".indexOf('뱃지') != -1 && col === <?=$col_h?>.length - 1)){
    //                 cp.renderer = imageRenderer;
    //                 cp.editor = false;
    //             }
    //
    //             if (img_flag){
    //                 if (col !== 0 && row == sel_row) {
    //                     data[row][col] = '';
    //                     cp.readOnly = true;
    //                 }
    //             } else {
    //                 if (col !== 0 && row == sel_row) {
    //                     cp.readOnly = false;
    //                 }
    //             }
    //
    //             return cp;
    //         }
    //     });
    // }
</script>
