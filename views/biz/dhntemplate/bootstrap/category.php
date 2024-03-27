<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu3.php');
?>
<!-- //3차 메뉴 -->
<!-- 타이틀 영역
<div class="tit_wrap">
	템플릿 관리
</div>
타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
    <div class="tab_st1">
      <button class="tablinks" onclick="openBox(event, 'Box1')" id="defaultOpen">카테고리 변경</button>
      <button class="tablinks" onclick="openBox(event, 'Box2')">카테고리 관리</button>
    </div>
		<div id="Box1" class="white_box tabcontent">
			<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
			<input type='hidden' name='confirm_flag' id='confirm_flag' value='N' />
			<input type='hidden' name='confirm_tpl_id' id='confirm_tpl_id' value='' />
			<input type='hidden' name='confirm_tpl_code' id='confirm_tpl_code' value='' />
			<input type='hidden' name='confirm_tpl_emphasizetype' id='confirm_tpl_emphasizetype' value='' />
			<input type='hidden' name='confirm_template_type' id='confirm_template_type' value='' />
			<input type='hidden' name='confirm_btn_cnt' id='confirm_btn_cnt' value='' />

			<table class="tpl_ver_form" width="100%" id="form1">
				<colgroup>
					<col width="200">
					<col width="*">
				</colgroup>
				<?
				if(!empty($profile)) {
				?>
				<tr>
					<th>프로필 업체<span class="required">*</span></th>
					<td style="text-align:left;">
						<select name="senderkey" id="senderkey">
							<?
							//var_dump($category);
							foreach($profile as $row ) {
								?>
								<option value="<?=$row->spf_key?>" <?=($senderkey ==$row->spf_key)?"selected":""?>><?=$row->spf_company?></option>
								<?
							}?>
						</select>
					</td>
				</tr>
				<?
				} else {
				?>
					<input type="hidden" id="mem_id" value="<?=$this->member->item('mem_id')?>" />
				<? } ?>
				<tr id="profile_key" >
					<th>템플릿 Code<span class="required">*</span></th>
					<td style="text-align:left;">
						<input type="text" name="tcode" id="tcode" placeholder="템플릿 Code를 입력해주세요." style="width:240px !important;">
            			<button id="tag_add_btn" class="btn_tagadd" onclick="template_confirm()">템플릿확인</button>
            			<span id="span_tpl_name"></span>
					</td>
				</tr>
        <tr>
          <th>템플릿 분류<span class="required">*</span></th>
          <td>
            <ul class="tem_cate_list" id="tpl_part">
            </ul>
          </td>
        </tr>
        <tr>
        	<th>템플릿 버튼 갯수</th>
          <td class="al_left">
				<span id="btn_cnt"></span>
          </td>
        </tr>
        <tr>
        	<th>템플릿 미리보기</th>
          <td>
    		<div class="talk_box">
    			<div class="talk_box_con" id="modal_templet_msg"></div>
    			<div class="talk_box_btn" id="modal_templet_btn"></div>
    		</div>
          </td>
        </tr>
		</table>
		<div class="btn_al_cen">
				<input type="button" class="btn_tagadd" style="cursor:pointer;" value="카테고리 변경하기" name="register" id="register" onClick="template_category()">
			</div>
		</div>
    <div id="Box2" class="tabcontent">
			<div class="inner_tit">
				<h3>알림톡 템플릿</h3> <span class="fc_red">* 드래그로 순서이동이 가능합니다!</span>
			</div>
		  <div class="white_box">
      <ul class="tag_write">
      <li>
        <span class="tit">카테고리 모음</span>
        <div class="text ui-sortable" id="div_img_list">
        <?
            if(!empty($list)){
                foreach($list as $r) {
        ?>
          <div class="tag_box ui-sortable-handle" id="<?=$r->tc_id?>">
            <input type="text" id="description_a<?=$r->tc_id?>" class="taginput" value="<?=$r->tc_description?>">
            <button class="tagbtn_insert" onclick="saved('<?=$r->tc_id?>', 'T');"><i class="material-icons">done</i></button>
            <button class="tagbtn_del" onclick="del('<?=$r->tc_id?>', 'T');"><i class="material-icons">clear</i></button>
          </div>

        <?
                }
            } else {
                echo "<span class='fc_red'>등록된 알림톡 템플릿 카테고리가 없습니다.</span>";
            }
        ?>
        </div>
      </li>
      <li>
        <span class="tit">카테고리 추가</span>
        <div class="text">
          <input type="text" id="description_a" maxlength="64">
          <button id="tag_add_btn" class="btn_tagadd" onclick="saved('', 'T');">추가하기</button>
        </div>
      </li>
    </ul>
		</div>
		<div class="inner_tit mg_t20">
			<h3>이미지 알림톡 템플릿</h3>
		</div>
		<div class="white_box">
		<ul class="tag_write">
		<li>
			<span class="tit">카테고리 모음</span>
			<div class="text ui-sortable" id="div_img_list2">
			<?
                if(!empty($list2)){
                    foreach($list2 as $r) {
            ?>
				<div class="tag_box ui-sortable-handle" id="<?=$r->tc_id?>">
					<input type="text" id="description_b<?=$r->tc_id?>" class="taginput" value="<?=$r->tc_description?>">
					<button class="tagbtn_insert" onclick="saved('<?=$r->tc_id?>', 'I');"><i class="material-icons">done</i></button>
					<button class="tagbtn_del" onclick="del('<?=$r->tc_id?>', 'I');"><i class="material-icons">clear</i></button>
				</div>
            <?
                    }
                } else {
                    echo "<span class='fc_red'>등록된 이미지 알림톡 템플릿 카테고리가 없습니다.</span>";
                }
			?>
			</div>
		</li>
		<li>
			<span class="tit">카테고리 추가</span>
			<div class="text">
				<input type="text" id="description_b" maxlength="64">
				<button id="tag_add_btn02" class="btn_tagadd" onclick="saved('', 'I');">추가하기</button>
			</div>
		</li>
	</ul>
	</div>
    </div>
	</div>
 </div>
<script>
    //탭박스 추가
    function openBox(evt, BoxName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(BoxName).style.display = "block";
        evt.currentTarget.className += " active";
     }

	function template_confirm() {
		var temp_code = $('#tcode').val();
		// alert(temp_code);
		$.ajax({
			url: "/dhnbiz/template/template_confirm",
			type: "POST",
			data: {"tpl_code" : temp_code, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(json.code == 200) {
					var tpl_type_text = "";
					var tpl_btn_cnt_text = "";
					$("#confirm_tpl_id").val(json.tpl_id);
					$("#confirm_tpl_code").val(json.tpl_code);
					$("#confirm_tpl_emphasizetype").val(json.tpl_emphasizetype);
					$("#confirm_template_type").val(json.template_type);
					$("#confirm_btn_cnt").val(json.btn_cnt);

					// alert("json.tpl_id : " + json.tpl_id + "\njson.tpl_code : " + json.tpl_code + "\njson.tpl_emphasizetype : " + json.tpl_emphasizetype + "\njson.template_type : " + json.template_type + "\n");

					if (json.template_type == "I") {
						tpl_type_text = "[이미지형]"
					}
					$("#span_tpl_name").text(json.tpl_name + " " + tpl_type_text);

					var data = json.category_list;

					// alert(data.length);

					// alert(json.category_ids);
					var selected_category = (json.category_ids).split(",");
					// alert(selected_category[0]);
					// alert(selected_category.length);
					var chkor = 0;
					var li_html = "";
					for(var i = 0; i < data.length; i++) {
						chkor = 0;
						for(var j = 0; j < selected_category.length; j++) {
							if (selected_category[j] == data[i].tc_id) {
								chkor = 1
								break;
							}
						}

						li_html += "<li> ";
						li_html += "<label class=\"radio_con\">	" + data[i].tc_description + "<input type=\"checkbox\" name=\"category_id\" id=\"category_id"+ data[i].tc_id +"\" ";
						if (chkor == 1) {
							li_html += "checked ";
						}
						li_html += "value=\"" + data[i].tc_id + "\"> ";
						li_html += "<span class=\"checkmark\"></span>";
						li_html += "</label>";
						li_html += "</li>";
					}

					$("#tpl_part").html(li_html);

					if(json.btn_cnt > 0) {
						tpl_btn_cnt_text = json.btn_cnt + " 개";
					} else {
						tpl_btn_cnt_text = "없음";
					}

					$("#btn_cnt").text(tpl_btn_cnt_text);
					$("#confirm_flag").val('Y');
					view_templet(json.tpl_preview);
				} else {
					//showSnackbar(json.message, 1500);
					alert(json.message);
				}
			},
			error: function (data, status, er) {
                alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
            }
		});

	}

	//알림톡버튼 데이타 미리보기
	function view_templet(json){
		//alert("tpl_id : "+ tpl_id +", tpl_premium_yn : "+ tpl_premium_yn +"\n"+"btn_name : "+ btn_name);
// 		var tpl_contents = $("#tpl_contents_"+ tpl_id).val(); //템플릿 내용
// 		var tpl_image_url = $("#tpl_image_url_"+ tpl_id).val();
// 		var tpl_extra = $("#tpl_extra_"+ tpl_id).val();
// 		if (tpl_image_url) {
// 			tpl_image_url = "<img src='"+tpl_image_url+"' style='width:100%' /><br>";
// 		}
// 		if (tpl_extra) {
// 			tpl_extra = "<br><br>"+tpl_extra;
// 		}

		var tpl_contents = json[0].tpl_contents.replace(/(\n|\r\n)/g, '<br>');	// 템플릿 내용
		var tpl_image_url = json[0].tpl_imageurl;
		//var tpl_extra = json[0].tpl_extra.replace(/(\n|\r\n)/g, '<br>');
		var tpl_extra = json[0].tpl_extra;
		if (tpl_extra) {
			tpl_extra = tpl_extra.replace(/(\n|\r\n)/g, '<br>');
		}
		var tpl_button = json[0].tpl_button;
		var btn = tpl_button.replace(/&quot;/gi, "\"");
		var btn_content = JSON.parse(btn);
		var btn_name = "";
		for(var i=0;i<btn_content.length;i++){
			btn_name += "  <p>"+ btn_content[i]["name"] +"</p>";
			btn_cnt++;
		}

		if (json[0].tpl_imageurl && json[0].tpl_imageurl != 'NONE') {
			tpl_image_url = "<img src='"+tpl_image_url+"' style='width:100%' /><br>";
		} else {
			tpl_image_url = "";
		}

		if (json[0].tpl_extra && json[0].tpl_extra != 'NONE') {
			tpl_extra = "<br><br>"+tpl_extra;
		} else {
			tpl_extra = "";
		}

		tpl_contents = tpl_contents.replace(/#{변수내용}/gi, '행사 정보');
		tpl_contents = tpl_contents.replace(/#{회원정보 등록일}/gi, '행사 정보');
		tpl_contents = tpl_contents.replace(/#{업체명}/gi, '업체명');
		tpl_contents = tpl_contents.replace(/#{업체전화번호}/gi, '전화번호');
		tpl_contents = tpl_contents.replace(/#{전화번호}/gi, '전화번호');
		tpl_contents = tpl_contents.replace(/#{날짜}/gi, '행사 날짜');

		$("#modal_templet_msg").html(tpl_image_url + tpl_contents + tpl_extra); //템플릿 내용
		$("#modal_templet_btn").html(btn_name); //템플릿 버튼


	}


	function template_category() {
		if($("#tcode").val().trim() != "") {	// 템플릿코드에 값을 입력하고 "카테고리 변경하기"버튼 클릭시 함수 종료
			if($("#confirm_flag").val() == "Y") {

				if ($("#confirm_tpl_code").val() == $("#tcode").val()) {
					var category_ids = [];	// 카테고리 체크배열
					var category_ids_str = "";	// 카테고리 id 모음 문자열 - 구분자 "," (tc_id)
					var confirm_tpl_id = $("#confirm_tpl_id").val();	// 템플릿 ID(tpl_id)
					var confirm_template_type = $("#confirm_template_type").val(); // 템플릿 타입("T" : 텍스트형, "I" : 이미지형)
					var confirm_btn_cnt = $("#confirm_btn_cnt").val();	// 템플릿 버턴 갯수

					$(":input:checkbox[name=category_id]:checked").each(function(e) {
						category_ids.push($(this).val());
					});

					category_ids_str = category_ids.join(",");
					//alert(category_ids_str);
					if(category_ids_str == "" || category_ids_str == undefined){
						alert("템플릿 분류를 선택해 주세요.");
						return;
					}

	        		$.ajax({
	        			url: "/dhnbiz/template/template_category_save",
	        			type: "POST",
	        			data:{"tpl_id":confirm_tpl_id, "tpl_case_type":confirm_template_type, "tpl_btn_cnt":confirm_btn_cnt, "category_ids[]" : category_ids, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
	        			success:function(json) {
	        				//console.log('완료');
	        				showSnackbar("카테고리 변경하기가 처리되었습니다.", 1500);

	         				setTimeout(function() {
	     						$("#confirm_flag").val("N");
	     						$("#confirm_tpl_id").val("");
	     						$("#confirm_tpl_code").val("");
	     						$("#confirm_tpl_emphasizetype").val("");
	     						$("#confirm_template_type").val("");
	     						$("#confirm_btn_cnt").val("");
	     						$("#tcode").val("");
	     						$("#span_tpl_name").text("");
	    	     				$("#tpl_part").html("");
	     						$("#btn_cnt").text("");
	     		    			$("#modal_templet_msg").html("");
	     		    			$("#modal_templet_btn").html("");
	     					}, 1000); //1초 지연
	        			},
	        			error: function (data, status, er) {
	                        alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
	                    }
	        		});
				} else {
					alert("템플릿 확인한 템플릿 코드와 입력한 템플릿 코드가 틀립니다.\n템플릿확인 후 사용하세요.");
				}
			} else {
				alert("템플릿확인 후 사용하세요.");
			}
		}
	}

    function category_refresh(type) {
		$.ajax({
			url: "/dhnbiz/template/category_list",
			type: "POST",
			data: {"type" : type, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				if(json.code == 200) {
    				//alert(json.category_list);
    				var category_list_html = "";
    				var data = json.category_list;
    				for(var i = 0; i < json.list_count; i++) {
    					//alert(i);
    					//alert(data[i].tc_id);
    					//alert(data[i].tc_description);
    					category_list_html += "<div class=\"tag_box ui-sortable-handle\" id=\"" + data[i].tc_id + "\">";
    					if (type == "I") {
    						category_list_html += "<input type=\"text\" id=\"description_b" + data[i].tc_id + "\" class=\"taginput\" value=\"" + data[i].tc_description + "\">";
       					} else {
    						category_list_html += "<input type=\"text\" id=\"description_a" + data[i].tc_id + "\" class=\"taginput\" value=\"" + data[i].tc_description + "\">";
    					}
    					category_list_html += "<button class=\"tagbtn_insert\" onclick=\"saved('" + data[i].tc_id + "', '" + type + "');\"><i class=\"material-icons\">done</i></button>";
    					category_list_html += "<button class=\"tagbtn_del\" onclick=\"del('" + data[i].tc_id + "', '" + type + "');\"><i class=\"material-icons\">clear</i></button>";
    					category_list_html += "</div>";
    				}

    				if (type == "I") {
    					$("#div_img_list2").html(category_list_html);
    				} else {
    					$("#div_img_list").html(category_list_html);
    				}
				}
			},
			error: function (data, status, er) {
                alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
            }
		});
    }



	//저장
	function saved(data_id, type){
		//alert("data_id : "+ data_id); return;
		var mag = "추가";
		var description = "";
		if(type=="I"){
			description = $("#description_b"+ data_id).val().trim(); //이미지 알림톡 템플릿 카테고리
		}else{
			description = $("#description_a"+ data_id).val().trim(); //알림톡 템플릿 카테고리
		}
		if(description == ""){
			alert("태그명을 입력하세요.");
			if (type=="I") {
				$("#description_b"+ data_id).focus();
			} else {
				$("#description_a"+ data_id).focus();
			}
			return;
		}
		//alert("OK"); return;
		if(data_id != ""){ //수정의 경우
			mag = "수정";
			if(!confirm("수정 하시겠습니까?")){ return; }
		}
		//alert("data_id : "+ data_id +"\n"+ "description : "+ description); return;
		$.ajax({
			url: "/dhnbiz/template/category_save",
			type: "POST",
			data: {"data_id" : data_id, "description" : description, "type" : type, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				showSnackbar("정상적으로 "+ mag +" 되었습니다.", 1500);
				category_refresh(type);
				if (type=="I") {
					$("#description_b").val('');
				} else {
					$("#description_a").val('');
				}

// 				setTimeout(function() {
// 					location.reload();
// 				}, 1000); //1초 지연
			},
			error: function (data, status, er) {
                alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
            }
		});
	}

	//삭제
	function del(id, type){
		if(!confirm("삭제 하시겠습니까?")){ return; }
		$.ajax({
			url: "/dhnbiz/template/category_del",
			type: "POST",
			data: {"id" : id, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				showSnackbar("정상적으로 삭제 되었습니다.", 1500);
				category_refresh(type);
// 				setTimeout(function() {
// 					location.reload();
// 				}, 1000); //1초 지연
			},
			error: function (data, status, er) {
                alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
            }
		});
	}



    $(document).ready(function() {
        document.getElementById("defaultOpen").click();

        //이미지 마우스 이동
        $("#div_img_list").sortable({
        	stop: function(event, ui) {
        		var seq = [];
        		var i = 0;
        		$("#div_img_list > .tag_box").each(function(t) {
        			seq.push($(this).attr("id"));
        		});
        		//console.log(seq);
        		//alert("seq : "+ seq); return;
        		$.ajax({
        			url: "/dhnbiz/template/category_sort",
        			type: "POST",
        			data:{"seq[]" : seq, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
        			success:function(json) {
        				//console.log('완료');
        				showSnackbar("정렬 순서가 변경되었습니다.", 1500);
        			},
        			error: function (data, status, er) {
                        alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
                    }
        		});
        	}
        });
        $("#div_img_list").disableSelection();

        //이미지 마우스 이동
        $("#div_img_list2").sortable({
        	stop: function(event, ui) {
        		var seq = [];
        		var i = 0;
        		$("#div_img_list2 > .tag_box").each(function(t) {
        			seq.push($(this).attr("id"));
        		});
        		//console.log(seq);
        		//alert("seq : "+ seq); return;
        		$.ajax({
        			url: "/dhnbiz/template/category_sort",
        			type: "POST",
        			data:{"seq[]" : seq, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
        			success:function(json) {
        				//console.log('완료');
        				showSnackbar("정렬 순서가 변경되었습니다.", 1500);
        			},
        			error: function (data, status, er) {
                        alert("처리중 오류가 발생했습니다.\n관리자에게 문의하십시오.");
                    }
        		});
        	}
        });
        $("#div_img_list2").disableSelection();

    });




 </script>
