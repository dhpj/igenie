<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<title>설문조사</title>
	<link rel="stylesheet" type="text/css" href="/css/survey_style.css">
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
<?if($report == 'N') { ?>
    <form action="#" method="post" name="survey" id ="survey">
        <div id="wrap" class="survey_section">
        	<div class="header">
        		<img src="/img/<?=$info->image?>">
        	</div>
        	<div class="contents">
	        	<div class="notice">
        			<ul>
	        			<li><label>설문조사 기간</label><?php echo $info->start.$start_w.' ~ '.$info->end.$end_w?> (5일간)</li>
        				<li><label>참여방법</label><?php echo $info->description?></li>
        			</ul>
        		</div>
        		<div class="question_wrap">
            		<? foreach($question as $q ) { ?>
            		<fieldset class="question" id="<?=$q->que_id?>" >
                        <dl>
                            <dt>
                                <span>Q <?=$q->seq?>. </span>
                                <span><?=$q->title?></span>
                            </dt>
                            <dd>
                            <? foreach($answer[$q->que_id] as $a) { 
                                switch($a->type) {
                                    case 1 :
                                ?>
                                <input type="radio" name="q<?=$info->svm_id."_".$q->que_id."_".$a->type?>" id="q<?=$q->que_id.$a->seq?>" value="<?=$a->seq?>">
                                <label for="q<?=$q->que_id.$a->seq?>"><?=$a->title?></label>
                                <?  break;
                                    case 2 :
                                ?>
                                <input type="checkbox" name="q<?=$info->svm_id."_".$q->que_id."_".$a->type?>" id="q<?=$q->que_id.$a->seq?>" value="<?=$a->seq?>">
                                <label for="q<?=$q->que_id.$a->seq?>"><?=$a->title?></label>
                                <?  break;
                                    case 3 :
                                ?>
                                <textarea id="q<?=$q->que_id.$a->seq?>" name="q<?=$info->svm_id."_".$q->que_id."_".$a->type?>" placeholder="ex) 상품, 가격, 서비스, 시설등"></textarea>
                                <?  break;
                                }
                                ?>
                            <? } ?>
                            </dd>
                        </dl>
                    </fieldset>
                    <? } ?>
                    
            	</div>
    	        <div class="btn_group">
    	        	<? if($enabled == 'N') { ?>
    		    		<p class="desc">설문 진행 기간이 아닙니다.</p>
    	        	<? } else { ?>
    		    		<input type="btn" onclick="check();" value="설문참여하고 쿠폰받기" class="btn btn-custom" name="register" id="register">
    	        	<? } ?>
    	    	</div>

        </div>
    </form>
    
    
    <form action="http://www.bizalimtalk.kr/survey/report" method="post" name="survey_report" id = "survey_report">
        <input type="hidden" value="" name="result" id="result">
        <input type="hidden" value="<?=$info->short_url?>" name="svm_id" id="svm_id">
    </form>
	    	<? } else { ?>
        <div id="wrap" class="survey_section">
        	<div class="header">
        		<img src="/img/<?=$info->image?>">
        	</div>
        	<div class="contents">
	        	<div class="notice">
        			<ul>
	        			<li><label>설문조사 기간</label><?php echo $info->start.' ~ '.$info->end?></li>
        				<li><label>참여방법</label><?php echo $info->description?></li>
        			</ul>
        		</div>	    	
    	    	<div class="msg">
    	    		<p class="title">설문에 참여해주셔서 감사합니다.</p>
    	    		<p class="desc">항상 최선을 다하는 김해축산농협하나로마트가 되겠습니다.</p>
    	    		<button class="btn" onclick="go_home();">무료쿠폰 받기</button>
    	    	</div>
    	    </div>
    	 </div>
	    	<? } ?>
	    	    
	<footer class="survey">
		@DHN all rights reserved.
	</footer>
        <script type="text/javascript">
		<?if($report == 'N') { ?>
            <? foreach($question as $q) { ?>
                var q<?=$q->que_id?> = "";
                $('input[name^=q<?=$info->svm_id."_".$q->que_id?>]').on('change', function() {
                	q<?=$q->que_id?> = $(this).val();
               	});
     
            <?} ?>
             
            function check() {

                <? foreach($question as $q) { 
                    if($q->type == '1') {
                    ?>
                 	if(q<?=$q->que_id?>.length == 0) {
                     	alert("<?=$q->seq?>번 문항을 반드시 선택 하세요." );
                     	return;
                 	}
                <?} 
                }?>

                var result = {};
                $("#survey input[name^='q']").each(function (index, item) { 
                    if( $("#" + item.id + ":checked").val() != undefined ) {
                    	var aaa = index + " : " + item.name + " = " + item.value + " / " + item.type + " / " + $("#" + item.id + ":checked").val();
                    	result[item.name] = $("#" + item.id + ":checked").val();
                    }
                    $("#" + item.id).attr("disabled",true);
            	});

                $("#survey textarea[name^='q']").each(function (index, item) { 
                    	result[item.name] = item.value;
            	});

            	$("#result").val(JSON.stringify(result));
            	//console.log(JSON.stringify(result));
            	$("#survey_report").submit();
        	}
       <? } else { ?>
		function go_home() {
			window.location.replace("<?=$info->go_url?>");
		}
       <? } ?>
       
        </script>
</body>
</html>