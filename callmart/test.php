<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>오늘은 뭐할꺼야?</title>
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
    <!--<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>-->
    <script src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
    <link rel="stylesheet" href="css/themes/my-custom-theme.css" />

     <!-- Photo Swipe 자바스크립트 설정 추가 -->
 	<script src="photoswipe/lib/klass.min.js"></script>   
	<script src="photoswipe/code.photoswipe.jquery-3.0.4.min.js"></script>
    <script src="photoswipe/create_ps_instance.js"></script>
    
    <link type="text/css" href="css/menu.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <link rel="stylesheet" href="css/hshs.css" />
    <link media="all" />

    <link href="photoswipe/photoswipe.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="js/klass.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="code.photoswipe.jquery-2.1.6.min.js"></script>

	<!-- JQuery Mobile을 사용하기 위해 반드시 필요한 태그-->
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0rc2/jquery.mobile-1.0rc2.min.css" />
    <script src="http://code.jquery.com/mobile/1.0rc2/jquery.mobile-1.0rc2.min.js"></script>
	<!-- JQuery-UI-Map을 사용하기 위해 반드시 필요한 태그-->
	<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
    <script src="web/jquery.fn.gmap.js" type="text/javascript"></script>
    <script src="ui/jquery.ui.map.extensions.js" type="text/javascript"></script>
    <script type="text/javascript">
	window.addEventListener("load", function(){
		setTimeout(loaded, 100);
	}, false);

	function loaded(){
		window.scrollTo(0, 1); // �� �κ��� �ٽ�!
	}
	</script>
	<script type="text/javascript">
		(function(window, $, PhotoSwipe){
		
			$(document).ready(function(){
				
				var options = {};
				$("#Gallery a").photoSwipe(options);
			
			});
			
		}(window, window.jQuery, window.Code.PhotoSwipe));
		
	</script>
  
</head>

<body>
	<!-- intro 페이지-->
    <div data-role="page" id="main">	
        
      <div class="user" data-role="content">  
            <img id="h" src="images/main_text.jpg" />
            <div align="center">  
              <a href="#login" style="text-decoration:none" data-transition="slide">
              <img src="images/enter.jpg" /><img id="logo" src="images/intro.jpg" />
              </a>
         
            </div>
      </div>
                       
    </div>
      <!-- intro 페이지 끝 -->
     
    
      <!-- home 화면 -->
    <div data-role="page" id="login">
        <div data-role="header" data-position="fixed" data-theme="b">
		    <div data-role="navbar" align="center">
               <ul>
                 <li><a href="#login" data-icon="home" data-transition="slide"><span>home</span></a></li> 
       	      	 <li><a href="#play" data-icon="arrow-d" data-transition="slide"><span>모하지?</span></a> </li>     
      	  	     <li><a href="#eat" data-icon="arrow-d" data-transition="slide"><span>모먹지?</span></a></li>
                 <li><a href="#main" data-icon="back" data-transition="slide"><span>나가기</span></a></li>
               </ul>
   	        </div> 
        </div>
    
        <div data-role="content">
          	<img id="h" src="images/main_text.jpg" align="middle" />
            <img id="home_img" src="images/main_love.png" align="middle" />
            <img id="home_bot" src="images/main_botton.png" align="middle">
        </div>           
    
    </div>
   
    <!-- home 화면 끝-->
   
   
   
     <!-- 모하지? 화면 -->
    <div data-role="page" id="play">
        <div data-role="header" data-position="fixed" data-theme="b">
		    <div data-role="navbar" align="center">
               <ul>
                 <li><a href="#login" data-icon="home" data-transition="slide"><span>home</span></a></li> 
       	      	 <li><a href="#play" data-icon="arrow-d" data-transition="slide"><span>모하지?</span></a> </li>     
      	  	     <li><a href="#eat" data-icon="arrow-d" data-transition="slide"><span>모먹지?</span></a></li>
                 <li><a href="#main" data-icon="back" data-transition="slide"><span>나가기</span></a></li>
               </ul>
   	        </div> 
        </div>
    
        <div data-role="content" data-fullscreen="true">
    	    <div>
               <img id="h" src="images/mo_main.jpg" align="middle"/>
            </div>
            <div class="list_wrap">
            <ul id="Gallery" class="gallery">
                   <li><a href=""#login" data-icon="home" data-transition="slide"><span>home</span></a></li> 
       	           <li><a href="#play" data-icon="arrow-d" data-transition="slide"><span>모하지?</span></a> </li>     
      	           <li><a href="#eat" data-icon="arrow-d" data-transition="slide"><span>모먹지?</span></a></li>
                   <li><a href="#main" data-icon="back" data-transition="slide"><span>나가기</span></a></li>
                </ul>
   	        </div> 
         </div>
    
         <div data-role="content" data-fullscreen="true">
    	     <div>
                <img id="h" src="eat_img/eat_main.jpg" align="middle"/>
             </div>
             <div class="list_wrap">
          		  <ul id="Gallery" class="gallery">
            		    <li><a href="eat_img/1347819814157.jpeg" rel="external"><img src="eat_img/1347819814157.jpeg" /></a></li>
            		    <li><a href="eat_img/1347819893198.jpeg" rel="external"><img src="eat_img/1347819893198.jpeg" /></a></li>
           			    <li><a href="eat_img/1347831519199.jpeg" rel="external"><img src="eat_img/1347831519199.jpeg" /></a></li>
              	        <li><a href="eat_img/1347831827199.jpeg" rel="external"><img src="eat_img/1347831827199.jpeg" /></a></li>
                        <li><a href="eat_img/1349094761852.jpeg" rel="external"><img src="eat_img/1349094761852.jpeg" /></a></li>
                        <li><a href="eat_img/1349107482153.jpeg" rel="external"><img src="eat_img/1349107482153.jpeg" /></a></li>
                        <li><a href="eat_img/1349098387373.jpeg" rel="external"><img src="eat_img/1349098387373.jpeg" /></a></li>
                        <li><a href="eat_img/1349107508153.jpeg" rel="external"><img src="eat_img/1349107508153.jpeg" /></a></li>
                        <li><a href="eat_img/1349107766153.jpeg" rel="external"><img src="eat_img/1349107766153.jpeg" /></a></li>
                        <li><a href="eat_img/1349107877153.jpeg" rel="external"><img src="eat_img/1349107877153.jpeg" /></a></li>
                        <li><a href="eat_img/1349141567102.jpeg" rel="external"><img src="eat_img/1349141567102.jpeg" /></a></li>
                        <li><a href="eat_img/1349533835727.jpeg" rel="external"><img src="eat_img/1349533835727.jpeg" /></a></li>
               
                 </ul>
          </div></div>
     </div>
   
   <!-- 모먹지? 화면 끝-->


   <!-- 모먹지? 화면 -->
    <div data-role="page" id="eat">
        <div data-role="header" data-position="fixed" data-theme="b">
	     	<div data-role="navbar" align="center">
                <ul>
                   <li><a href="#login" data-icon="home" data-transition="slide"><span>home</span></a></li> 
       	           <li><a href="#play" data-icon="arrow-d" data-transition="slide"><span>모하지?</span></a> </li>     
      	           <li><a href="#eat" data-icon="arrow-d" data-transition="slide"><span>모먹지?</span></a></li>
                   <li><a href="#main" data-icon="back" data-transition="slide"><span>나가기</span></a></li>
                </ul>
   	        </div> 
         </div>
    
         <div data-role="content" data-fullscreen="true">
    	     <div>
                <img id="h" src="eat_img/eat_main.jpg" align="middle"/>
             </div>
             <div class="list_wrap">
          		  <ul id="Gallery" class="gallery">
            		    <li><a href="eat_img/1347819814157.jpeg" rel="external"><img src="eat_img/1347819814157.jpeg" /></a></li>
            		    <li><a href="eat_img/1347819893198.jpeg" rel="external"><img src="eat_img/1347819893198.jpeg" /></a></li>
           			    <li><a href="eat_img/1347831519199.jpeg" rel="external"><img src="eat_img/1347831519199.jpeg" /></a></li>
              	        <li><a href="eat_img/1347831827199.jpeg" rel="external"><img src="eat_img/1347831827199.jpeg" /></a></li>
                        <li><a href="eat_img/1349094761852.jpeg" rel="external"><img src="eat_img/1349094761852.jpeg" /></a></li>
                        <li><a href="eat_img/1349107482153.jpeg" rel="external"><img src="eat_img/1349107482153.jpeg" /></a></li>
                        <li><a href="eat_img/1349098387373.jpeg" rel="external"><img src="eat_img/1349098387373.jpeg" /></a></li>
                        <li><a href="eat_img/1349107508153.jpeg" rel="external"><img src="eat_img/1349107508153.jpeg" /></a></li>
                        <li><a href="eat_img/1349107766153.jpeg" rel="external"><img src="eat_img/1349107766153.jpeg" /></a></li>
                        <li><a href="eat_img/1349107877153.jpeg" rel="external"><img src="eat_img/1349107877153.jpeg" /></a></li>
                        <li><a href="eat_img/1349141567102.jpeg" rel="external"><img src="eat_img/1349141567102.jpeg" /></a></li>
                        <li><a href="eat_img/1349533835727.jpeg" rel="external"><img src="eat_img/1349533835727.jpeg" /></a></li>
               
                 </ul>
          </div></div>
     </div>
   
   <!-- 모먹지? 화면 끝-->
</body>
</html>