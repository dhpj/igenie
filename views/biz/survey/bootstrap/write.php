    <style type="text/css">
         
        textarea.form-control {min-height:30px; 
                               resize:none;
                               
                               }
        
          .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }

          .portlet {
            margin: 0 1em 1em 0;
            padding: 0.3em;
          }
          
          .portlet-header {
            padding: 0.2em 0.3em;
            margin-bottom: 0.5em;
            position: relative;
          }
          .portlet-header:hover {cursor:move}
          
          .portlet-toggle {
            position: absolute;
            top: 50%;
            right: 0;
            margin-top: -8px;
          }
          
          .portlet-content {
            padding: 0.4em;
          }
          
          .portlet-placeholder {
            border: 1px dotted black;
            background-color:#e8e8e8;
            margin: 0 1em 1em 0;
            height: 50px;
          }
          
          .answer_sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }

          .answer_portlet {
            margin: 0 1em 1em 0;
            padding: 0.3em;
          }
          
          .answer_portlet-header {
            padding: 0.2em 0.3em;
            margin-bottom: 0.5em;
            position: relative;
          }
          .answer_portlet-header:hover {cursor:move}
          
          .answer_portlet-toggle {
            position: absolute;
            top: 50%;
            right: 0;
            margin-top: -8px;
          }
          
          .answer_portlet-content {
            padding: 0.4em;
          }
          
          .answer_portlet-placeholder {
            border: 1px dotted black;
            background-color:#e8e8e8;
            margin: 0 1em 1em 0;
            height: 50px;
          }          

          .ui-selected { border-width:1px; border-left-width: 10px; color: white; }

          .none-selected {border-width:0px;}
          
          .head-selected { border-width:1px; 
                           border-left-width: 3px; 
                           color: #c5c2c2; 
                           border-left-color:#4d90fe; 
                           border-style:solid; 
                           box-shadow: 0 .5rem 1rem rgba(0,0,0,.75)!important;
                           position:relative;
                           z-index:1;
                           }

          .none-selected-udline {     border-top-width: 0px;
                                      border-left-width: 0px;
                                      border-right-width: 0px; 
                                      border-bottom-width:1px;
                                      border-bottom-color:#e0e0e0;
                                      border-radius: 0px;
                                      box-shadow:none
                                }
          .selected-udline {     border-top-width: 0px;
                                 border-left-width: 0px;
                                 border-right-width: 0px; 
                                 border-bottom-width:2px;
                                 border-bottom-color:blue;
                                 border-radius: 0px;
                                      box-shadow:none
                                }     
          .none-selected-udline:focus {border-bottom-width:3px;
                                       box-shadow:none}                           

          .title-selected-udline {   border-top-width: 0px;
                                      border-left-width: 0px;
                                      border-right-width: 0px; 
                                      border-bottom-width:1px;
                                      border-bottom-color:#e0e0e0;
                                      border-radius: 0px;
                                       box-shadow:none
                                }

          .title-selected-udline:focus {border-bottom-width:3px;
                                       box-shadow:none}  
                                       
          .answer-selected-udline {   border-top-width: 0px;
                                      border-left-width: 0px;
                                      border-right-width: 0px; 
                                      border-bottom-width:1px;
                                      border-bottom-color:#e0e0e0;
                                      border-radius: 0px;
                                      box-shadow:none
                                }

          .answer-selected-udline:focus {border-bottom-width:3px;
                                       box-shadow:none}  
          
          .answer-toolbar {
						border-width: 1px 0px 0px;
						border-style: solid;
						margin: 10px 0px 0px;
						padding: 10px 0px 0px;
					}
    </style>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    
          <script>
      var prevID = null;
      var privAnswerID = null;
      
      $( function() {
        $( ".sortable" ).sortable({
            connectWith: ".column",
            handle: ".portlet-header",
            cancel: ".portlet-toggle",
            placeholder: "portlet-placeholder ui-corner-all",
        });
        
        $( ".portlet" )
        	.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
        	.find( ".portlet-header" )
          	.addClass( "ui-widget-header ui-corner-all" )
          	.prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");
   
        $( ".portlet-toggle" ).on( "click", function() {
            var icon = $( this );
            icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
            icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
          });
      
      } );

      $( function() {
//           $( ".answer_sortable" ).sortable({
//               connectWith: ".column",
//               handle: ".answer_portlet-header",
//               cancel: ".answer_portlet-toggle",
//               placeholder: "answer_portlet-placeholder ui-corner-all"
//           });
          
//           $( ".answer_portlet" )
//           	.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
//           	.find( ".answer_portlet-header" )
//             	.addClass( "ui-widget-header ui-corner-all" )
//             	.prepend( "<span class='ui-icon ui-icon-minusthick answer_portlet-toggle'></span>");
     
//           $( ".answer_portlet-toggle" ).on( "click", function() {
//               var icon = $( this );
//               icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
//               icon.closest( ".answer_portlet" ).find( ".answer_portlet-content" ).toggle();
//             });
        update_sortable();
        } );

	  function update_sortable() {
		  $( ".answer_sortable" ).sortable({
              connectWith: ".column",
              handle: ".answer_portlet-header",
              cancel: ".answer_portlet-toggle",
              placeholder: "answer_portlet-placeholder ui-corner-all"
          });
          
          $( ".answer_portlet" )
          	.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
          	.find( ".answer_portlet-header" )
            	.addClass( "ui-widget-header ui-corner-all" )
            	.prepend( "<span class='ui-icon ui-icon-minusthick answer_portlet-toggle'></span>");
     
          $( ".answer_portlet-toggle" ).on( "click", function() {
              var icon = $( this );
              icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
              icon.closest( ".answer_portlet" ).find( ".answer_portlet-content" ).toggle();
            });
        
	  }
      
	  function on_focus(id) {

		$(id ).removeClass("none-selected");
		$(prevID ).addClass("none-selected");
			  
		$(prevID ).removeClass("head-selected");
		$(id ).addClass("head-selected");

		$(prevID ).css("z-index", "0");
		$(id ).css("z-index", "1");
		
		$(prevID).find(".qhead_hold").css("display", "none");
		$(id).find(".qhead_hold").css("display", "");

		$(prevID).find(".answer-toolbar").css("display", "none");
		$(id).find(".answer-toolbar").css("display", "");

		try {
    		if(privID != null && privID != id) {
    			$(privAnswerID).find("#answer_toolbar").css("display","none");
    		}
		} catch(e) {
		} finally {
		}
		
		prevID = id;
	  }
			  
		function on_answer_focus(input) {
			$(privAnswerID).find(".answer_move_toolbar").css("display","none");
			$(input).find(".answer_move_toolbar").css("display","");
			privAnswerID = input;
		}
 
  </script>
<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					설문 신규 작성
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>설문 신규 작성</h3>
								<button type="button" class="btn btn-secondary btn-sm" onclick="submit()">저장</button>
    							<button type="button" class="btn btn-secondary btn-sm">미리보기</button>
						</div>
						
						<div class="inner_content">
                            <div  >
                            	<div >
                            		<input type="hidden" name="survey_id" id="surveyid" value="-1">
                          			<textarea class="form-control title-selected-udline" id="survey_title" aria-describedby="inputGroup-sizing-lg" placeholder="설문제목" style="font-size: 30px;margin-top: 5px; margin-bottom: 5px; height: 43px;" wrap="virtual"></textarea>
                           			<textarea class="form-control title-selected-udline" id="survey_description" aria-describedby="inputGroup-sizing-sm" placeholder="설문 설명" style="margin-top: 0px; margin-bottom: 5px; height: 35px;"></textarea>
                            
                            		<div class="row" style="margin-left: 0px;margin-bottom: 10px; vertical-align: middle;">
                            			<div class="custom-file col-sm-8"  >
                            				<input type="file" class="custom-file-input" id="customFile" name="title_img" accept="image/gif, image/jpeg, image/png" style="display:none">
                            				<label class="custom-file-label btn" for="customFile">이미지 선택</label>
                            			</div>
                            			<div class="col-sm-4">
                            				<select class="custom-select" id="img_position">
                            					<option value="1">제목 위</option>
                            					<option selected value="2">제목 아래</option>
                            					<option value="3">설명 아래</option>
                            				</select>
                            			</div> 
                            		</div>
                            
                            		<ul class="sortable " >
                            		
                            			<div class="none-selected text-center qhead" onclick="on_focus(this)">
                            			    <div class="portlet-header qhead_hold" id="qhead_1_hold" style="display:none">:::</div>
                            				<div class="input-group" style="width:98%; padding:5px">
                            					<textarea class="form-control none-selected-udline qhead_title" name="description" aria-describedby="inputGroup-sizing-sm" style="font-size: 18px; color:black">제목없는 질문</textarea>
                            				</div>            
                            			
                            				<div class="card-body none-selected answer_sortable qbody" style="width:98%" >
                            					<div class="input-group answer" style="margin-bottom:3px" data-id="0" onclick="on_answer_focus(this);">
                            					    <span class="input-group-addon text-center" style="margin-right:5px; border:none;background-color:transparent; width:30px" >
                        								<div class="answer_portlet-header answer_move_toolbar" style="display:none" id="answer_toolbar">:::</div>
                        							</span>
                        							<span class="input-group-addon" style="margin-right:5px; width:30px;border:none;background-color:transparent">
                        								<input type="radio" class="">
                        							</span>
                            						<input type="text" class="answer-selected-udline form-control answer_text" aria-label="Text input with radio button" value="답변1">
                            						<span class="input-group-btn">
                            							<button class="btn btn-outline-secondary btn-sm" style="border:none" type="button"  onclick="Answer_remove(this)">
                            								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            							</button>
                            						</span>
                            					</div>				                
                            				</div>
                            					<div class="input-group" style="margin-bottom:3px" data-id="0" >
                                                    <span class="input-group-addon text-center" style="margin-right:5px; border:none;background-color:transparent; width:30px" >
                        							</span>
                        							<span class="input-group-addon" style="margin-right:5px; width:30px;border:none;background-color:transparent">
                        								<input type="radio" class="" disabled>
                        							</span>
                        							<span class="input-group-addon" style="margin-right:5px; width:30px;border:none;background-color:transparent">
                        								<button class="btn btn-outline-secondary btn-sm" style="border:none" type="button" onclick="Answer_add(this)">
                            								<span class="btn glyphicon glyphicon-plus" aria-hidden="true"> 추가</span>
                            							</button>
                        							</span>
                            					</div>		                            				
                            				<div class="text-right row answer-toolbar" id="qhead_1_toolbar" style="display:none;">
                            					<div class="col-sm-7"></div>
                            					<div class="col-sm-1">
                                					  <button class="btn btn-outline-secondary btn-lg" type="button" onclick="Question_remove(this)">
                                								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                					 </button>
                            					</div>
                            					<div class="col-sm-1"></div>
                            					<div class="col-sm-2">
                                                      <input class="form-check-input" type="checkbox" id="gridCheck">
                                                      <label class="form-check-label" for="gridCheck">필수</label>
                                                </div>
                            				</div>
                            			</div>
                            			<div style="border-top-width: 0px;border-left-width: 0px;border-right-width: 0px;border-bottom-width:1px;border-bottom-color:#e0e0e0;border-bottom-style: dashed;border-radius: 0px;box-shadow:none"></div>
                        			</ul>
                            		<br>
                            		<br>    		
                            		<button type="button" class="btn btn-secondary btn-sm" onclick="Question_add()">질문추가</button>
                            	</div>
                            </div>
                        
                          

      
    </div>
    </div>
    </div>
    
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
    <script>
        $(".custom-file-input").on("change", function() {
          var fileName = $(this).val().split("\\").pop();
          $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $("textarea.form-control").on('keydown keyup', function () {
        	  $(this).height(1).height( $(this).prop('scrollHeight') );	
        	});


		function Answer_add(idx) {
			//alert($(idx).parents("div.qhead").find(".qbody").attr('id'));
			$(idx).parents("div.qhead").find(".qbody").append("<div class='input-group answer' style='margin-bottom:3px' data-id='0' onclick='on_answer_focus(this);' > \
					                      <span class='input-group-addon text-center' style='margin-right:5px; border:none;background-color:transparent; width:30px' > \
					                         <div class='answer_portlet-header answer_move_toolbar' style='display:none; ' id='answer_toolbar'>:::</div> \
					                      </span> \
					                      <span class='input-group-addon' style='margin-right:5px; width:30px;border:none;background-color:transparent'> \
					                         <input type='radio' class=''> \
					                      </span> \
					                      <input type='text' class='form-control answer-selected-udline answer_text' aria-label='Text input with radio button' value='제목없는 답변'> \
					                      <span class='input-group-btn'> \
					                         <button class='btn btn-outline-secondary btn-sm' style='border:none' type='button'  onclick='Answer_remove(this)'> \
					                            <span class='glyphicon glyphicon-trash' aria-hidden='true'></span> \
					                         </button> \
					                      </span> \
					                   </div>");
		}			

		function Answer_remove(idx) {
			$(idx).parents("div .answer").remove();
		}			

		function Question_add() {
			
    		$("ul.sortable").append("<div class='none-selected text-center qhead' onclick='on_focus(this)' id='qhead_1'> \
    		   		<div class='portlet-header qhead_hold' id='qhead_1_hold' style='display:none'>:::</div> \
        			<div class='input-group' style='width:98%; padding:5px'> \
        				<textarea class='form-control none-selected-udline qhead_title' name='description' aria-describedby='inputGroup-sizing-sm' style='font-size: 18px; color:black'>제목없는 질문</textarea> \
        			</div> \
        			<div class='card-body none-selected answer_sortable qbody' style='width:98%' id='qbody_1'> \
        				<div class='input-group answer' style='margin-bottom:3px' data-id='0' onclick='on_answer_focus(this);'> \
        				    <span class='input-group-addon text-center' style='margin-right:5px; border:none;background-color:transparent; width:30px' > \
        						<div class='answer_portlet-header answer_move_toolbar' style='display:none' id='answer_toolbar'>:::</div> \
        					</span> \
        					<span class='input-group-addon' style='margin-right:5px; width:30px;border:none;background-color:transparent'> \
        						<input type='radio' class=''> \
        					</span> \
        					<input type='text' class='answer-selected-udline form-control answer_text' aria-label='Text input with radio button' value='제목없는 답변'> \
        					<span class='input-group-btn'> \
        						<button class='btn btn-outline-secondary btn-sm' style='border:none' type='button'  onclick='Answer_remove(this)'> \
        							<span class='glyphicon glyphicon-trash' aria-hidden='true'></span> \
        						</button> \
        					</span> \
        				</div> \
        			</div> \
    				<div class='input-group' style='margin-bottom:3px' data-id='0' > \
                        <span class='input-group-addon text-center' style='margin-right:5px; border:none;background-color:transparent; width:30px' > \
    					</span> \
    					<span class='input-group-addon' style='margin-right:5px; width:30px;border:none;background-color:transparent'> \
    						<input type='radio' class='' disabled> \
    					</span> \
    					<span class='input-group-addon' style='margin-right:5px; width:30px;border:none;background-color:transparent'> \
    						<button class='btn btn-outline-secondary btn-sm' style='border:none' type='button' onclick='Answer_add(this)'> \
    							<span class='btn glyphicon glyphicon-plus' aria-hidden='true'> 추가</span> \
    						</button> \
    					</span> \
    				</div> \
        			<div class='text-right row answer-toolbar' id='qhead_1_toolbar' style='display:none;'> \
        				<div class='col-sm-7'></div> \
        				<div class='col-sm-1'> \
        					  <button class='btn btn-outline-secondary btn-lg' type='button'  onclick='Question_remove(this)'> \
        								<span class='glyphicon glyphicon-trash' aria-hidden='true'></span> \
        					 </button> \
        				</div> \
        				<div class='col-sm-1'></div> \
        				<div class='col-sm-2'> \
                              <input class='form-check-input' type='checkbox' id='gridCheck'> \
                              <label class='form-check-label' for='gridCheck'>필수</label> \
                        </div> \
        			</div> \
    			</div> \
        		<div style='border-top-width: 0px;border-left-width: 0px;border-right-width: 0px;border-bottom-width:1px;border-bottom-color:#e0e0e0;border-bottom-style: dashed;border-radius: 0px;box-shadow:none'></div>");

    		update_sortable();
		}

		function Question_remove(idx) {
			$(idx).parents("div.qhead").remove();
		}

		function submit() {
	        var surveydata = new FormData();
	        surveydata.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
	        surveydata.append("surveyid", $("#surveyid").val());
	        surveydata.append("title", $("#survey_title").val());
	        surveydata.append("description", $("#survey_description").val());
	        surveydata.append("image", $("#customFile")[0].files[0]);
	        surveydata.append("img_position", $("#img_position").val());

	        var q = 1;
	        var a = 1;
	        var survey_obj = new Object();

	        var question_a = new Array();
		    
			$(".qhead").each(function(index) {
				a = 1;
			    var question_obj = new Object();
			    $(this).find(".qhead_title").each(function(index) {
					    //console.log(" q = " + q + " : " +  $(this).val());
					    question_obj.title = $(this).val();
					    question_obj.seq = q; 
			    });

			    var answer_a = new Array();
			    
			    $(this).find(".answer_text").each(function(index) {
				    //console.log(" q = " + q + " / A = " + a + " : " +  $(this).val());

				    var answer_obj = new Object();
					answer_obj.title = $(this).val();
					answer_obj.seq = a;
					answer_obj.type = "1";				    

				    answer_a.push(answer_obj);
				    a = a + 1;
			    });

			    question_obj.answer = answer_a;

			    question_a.push(question_obj);
			    
			    q = q + 1;
			}); 

			survey_obj.survey = question_a;

			//console.log(JSON.stringify(survey_obj));
			
			surveydata.append("survey_json",JSON.stringify(survey_obj));
			$.ajax({
				url: "/biz/survey/save",
                processData: false,
                contentType: false,
				type: "POST",
				data:surveydata,
				success: function (json) {
					var survey_id =  json["surver_id"];
					$("#surveyid").val(survey_id);
					console.log("Survey_id : " + survey_id);		
				},
                error: function (data, status, er) {
                    alert("ERROR" + status+ er);
                }				
						
			});		
		}
	</script>
  </body>
</html>

