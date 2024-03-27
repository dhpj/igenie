<html>
    <head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    	<script type="text/javascript" src="/js/jquery.min.js"></script>
    </head>
    <body>
        <div  id="short_url_content" style="width:100%; max-width:720px; text-align:center; margin: 0 auto;">
        </div>
        <? if(!empty($url) ) { ?>
    <script type="text/javascript">
    	$(document).ready(function() {
    		$('#short_url_content').html('').load("<?=$url?>");
    	});
    </script>    
    <? } ?>
    </body>
</html> 
