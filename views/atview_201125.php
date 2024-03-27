<html>
    <head>
		<meta name="viewport" content="width=device-width, minimum-scale=0, user-scalable=yes, target-densitydpi=medium-dpi" />
		<meta http-equiv="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT">
		<meta http-equiv="Expires" content="-1">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
      	<script type="text/javascript" src="/js/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="http://ppurigo.co.kr/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="http://ppurigo.co.kr/assets/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="http://ppurigo.co.kr/css/adview.css">
    </head>
    <body>
    	<?
    	$tel_str = preg_replace("/[^0-9]/", "", $userinfo->mem_phone);
    	if (substr($tel_str,0,2) == '02') {
    	    $tel_str = preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel_str);
    	} else if (strlen($tel_str)=='8' && (substr($tel_str,0,2)=='15' || substr($tel_str,0,2)=='16' || substr($tel_str,0,2)=='18')) {
    	    $tel_str = preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $tel_str);
    	} else {
    	    $tel_str = preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel_str);
    	}
    	?>
		<div id="wrap">
			<div id="container" class="fixed-header">
				<div class="top_info">
					<div class="name"><?=$userinfo->mem_username?></div>
	    			<div class="num"><a href="tel:<?=$userinfo->mem_phone?>"><i class="material-icons">phone_in_talk</i><?=$tel_str?></a></div>

				</div>
		        <div  id="content" style="width:100%;">
		        	<?=$html->header_page ?>
		        </div>
		        <div  id="content" style="width:100%;">
		        	<?=$html->html ?>
		        </div>
		        <div  id="content" style="width:100%;">
		        	<?=$html->foot_page ?>
		        </div>
			</div>
        </div>
    </body>
</html> 
