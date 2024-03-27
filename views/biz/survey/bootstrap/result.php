<!-- 타이틀 영역 -->
<link rel="stylesheet" type="text/css" href="/css/survey_style.css">
		<div class="tit_wrap">
			설문 결과
		</div>
			
   <!--  <form action="#" method="post" name="survey" id = "survey"> -->
        <div id="mArticle">
	        <div class="survey_section">
		        <div class="survey_content">
		        	<div class="header">
			        	<img src="/img/<?=$info->image?>">
		        		<div class="notice result">
		        			<ul>
			        			<li><label>설문조사 기간</label><?php echo $info->start.' ~ '.$info->end?> (5일간)</li>
		        				<li><label>전체응답수</label><?=$total_cnt?>개</li>
		        			</ul>
		        		</div>
		        	</div>
	            	<div class="contents">
	                		<? foreach($question as $q ) { ?>
	                		<fieldset class="answer" id="<?=$q->que_id?>" >
	                            <dl>
	                                <dt>
	                                    <span>Q <?=$q->seq?>. </span>
	                                    <span><?=$q->title?></span>
	                                    <p>응답 <?=$q->t_cnt?>개</p>
	                                </dt>
	                                <dd class="bar_wrap">
	                                <? foreach($answer[$q->que_id] as $a) { 
	                                    switch($a->type) {
	                                        case 1 :
	                                        case 2 :
	                                    ?>
	        		                    <div class="bar_row">
	        			                    <em><?=$a->title?></em>
	        			                    <p><?=$a->t_a_cnt?>명 (<?=round(($a->t_a_cnt/$q->t_cnt)*100)?> %)</p>
	        			                    <div class="data_wrap">
	        			                    	<div class="data" style="width: calc(<?=$a->t_a_cnt?> / <?=$q->t_cnt?> * 100%);"></div>
	        			                    </div>
	        		                    </div>
	                                    <?  break;
	                                        case 3 :
	                                            $i =0;
	                                            $j =0;
	                                            foreach($answer[$q->que_id]['tvalue'] as $t) {
	                                                if(!empty($t->tvalue)) {
	                                                    $i++;
	                                                    
	                                    ?>
	                                    
	                                            <p><?=$t->tvalue?></p>
	                                    
	                                    <?      
	                                                }
	                                    if($i >= 5) {
	                                        $j= 1;
	                                        break;
	                                    }
	                                            }
	                                            if($j == 1) {
	                                                echo "<div class='btn_more'>더 많은 설문은 다운로드로 확인 하세요.</div>";
	                                            }
	                                            break;
	                                    }
	                                    ?>
	                                <? } ?>
	                                </dd>
	                            </dl>
	                        </fieldset>
	                        <? } ?>
	                </div>
	                <div class="btn_group">
		                <button class="btn" onclick="download('<?=$info->short_url?>')">다운로드</button>
		                <button  class="btn">목록</button>
	                </div>
		        </div>
		        
	        </div>
        </div>
        <script>
		function download($svm_id)
		{
			var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/survey/download");

			var scrfField = document.createElement("input");
			scrfField.setAttribute("type", "hidden");
			scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(scrfField);
			var keyField = document.createElement("input");
			keyField.setAttribute("type", "hidden");
			keyField.setAttribute("name", "svm_id");
			keyField.setAttribute("value", $svm_id);
			form.appendChild(keyField);
			 
			form.submit();
		}
        </script>