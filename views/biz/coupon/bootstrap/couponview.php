<html>
   <head>
      <meta name="viewport" content="width=device-width, user-scalable=no">
      <style>
         * {font-size: 12px;
         line-height: 1.5;
         font-weight: 400;
         font-family: "Noto Sans", sans-serif;
         color: rgb(51,51,51);
         }    
         #img_message {
         width: 278px;
         height: 278px;
         margin: 0;
         border: 1px solid rgb(179,204,220);
         border-bottom: 1px solid rgb(216,216,216);
         border-radius: 4px 4px 0 0;
         background-color: rgb(238,238,238);
         }
         #coupon_barcode {
         overflow: hidden;
         height: 10px;
         padding: 5 1px;
         background: url(/images/coupon/bg_view_barcode.png) no-repeat 0 100%;
         -webkit-background-size: 280px 72px;
         background-size: 280px 72px;
         }
         #user_barcode {
         overflow: hidden;
         height: 82px;
         padding: 5 1px;
         background: url(/images/coupon/bg_view_userbarcode.png) no-repeat 0 100%;
         -webkit-background-size: 280px 92px;
         background-size: 280px 92px;
         }
         #contents {
         overflow: hidden;
         min-height: 130px;
         border: 1px solid rgb(179,204,220);
         border-top: 0 none;
         border-radius: 0 0 4px 4px;
         background-color: rgb(255,255,255);
         }
         #txt_btn {
         display: block;
         width: 258px;
         height: 36px;
         margin: 4px auto 0;
         border-radius: 2px;
         font-size: 14px;
         line-height: 36px;
         background-color: rgb(255,223,44);
         text-align: center;
         }
         #barcodeTarget,
         #canvasTarget{
         margin-bottom: 5px;
         }        
      </style>
      <script type="text/javascript" src="/js/jquery.min.js"></script>
      <script type="text/javascript" src="/js/jquery-barcode.js"></script>
      <script type="text/javascript">
         function generateBarcode(t, bar_value){
           var value = bar_value;
           var btype = "code128";
           var renderer = "css";
               
         var quietZone = false;
               
           var settings = {
             output:renderer,
             bgColor: "#FFFFFF",
             color: "#000000",
             barWidth: 1,
             barHeight: 35,
             moduleSize: 3,
             posX: 10,
             posY: 10,
             addQuietZone: 1
           };
           
           if (t==1) {    
               $("#userbarcode").html("").show().barcode(value, btype, settings);
           }
         
           if (t==2) {    
               $("#barcodeTarget").html("").show().barcode(value, btype, settings);
           }
         }
             
         
         $(function(){ 
             generateBarcode(1,"<?=$rs->cc_user_barcode?>");
         });
         
      </script>
   </head>
   <body style="position: absolute;top: 0;left: 0;left: 50%;margin-left:-139px;background-color: rgb(199,224,228);">
      <div id="img_message" >
         <img name="image" src="http://sjk1030.cafe24.com/pop/2018/10/bdd360219cf177179b9e587ac76b12b2.png" style="width:100%; margin-bottom:5px; height:278px;">
      </div>
<? 
if(!empty($rs->cc_user_barcode)) { ?>
      <div id="user_barcode" style="width:278px">
         <center>
            <div id="userbarcode" class="barcodeTarget" style="margin-top:15px"></div>
         </center>
      </div>
<? } ?>      
      <div id="contents" style="width:278px">
         <div id="pre_title" style="padding-bottom: 10px;">
            <strong style="margin-top: 1px;display: block; padding: 2px 9px; border: 1px solid transparent; font-weight: normal; font-size: 16px; color: rgb(136,136,136);">100%쿠폰12</strong>
            <li style="padding-bottom: 10px;display: block;padding: 0 10px;color: rgb(46,172,188);font-size: 12px;">사용기간 : <?=$rs->cc_end_date?> 까지</li>
         </div>
<? if(empty($check1->coupon_no)) { ?>
                   <span id="txt_btn">꽝! 다음기회에...</span>';
<?
            } else {
?>
                   <span id="txt_btn">직원확인 완료</span>
                   <pre style="margin:10px">'.$rs->cc_memo.'</pre>';
<?            } ?>
      </div>
   </body>
</html> 