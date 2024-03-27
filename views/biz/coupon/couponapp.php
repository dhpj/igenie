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
         body {
	         margin: 0;
	         padding: 0;
         }
         #img_message {
         width: 100%;
         margin: 0;
         }
         #img_message img {
         max-width: 100%;
         max-height: 100%;
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
         background-position:center center;
         }
         #contents {
		 	text-align: center;
         }
         #txt_btn {
	         display: block;
	         width: 240px;
	         height: 38px;
	         line-height: 38px;
	         border-radius: 3px;
	         font-size: 14px;
	         background-color: #ffd900;
	         text-align: center;
	         margin: 0 auto;
         }
         #barcodeTarget,
         #canvasTarget{
         	margin-bottom: 5px;
         }
         #pre_title {
	         border-bottom: 1px dashed #dedede;
	         padding: 10px;
	         line-height: 1;
         }
         #pre_title h3 {
	         font-size: 2.5rem;
			 margin: 0;
	         padding: 0;
         }
         #pre_title p {
	         font-size: 1.2rem;
	         margin: 0;
	         padding: 0;
         }
         .friend_add {
		     display: block;
	         width: 240px;
	         height: 38px;
	         margin: 10px auto;
	         line-height: 38px;
	         border: none;
	         border-radius: 3px;
	         font-size: 14px;
		     background:#ffd900;
		     color: #000;
		     text-align: center;
         }
         .friend_add i {
	         vertical-align: middle;
	         font-size: 20px;
	         margin-right: 5px;
	         margin-top: -3px;
         }
      </style>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
             barWidth: 2,
             barHeight: 50,
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
        	 <? 
        			 if(!empty($rs->cc_user_barcode) && !empty($cc_no)) { ?>             
             generateBarcode(1,"<?=$rs->cc_user_barcode?>");
             <? } ?>
         });
         
      </script>
   </head>
   <body>
      <div id="img_message" >
         <img name="image" src="<?=$rs->cc_img_link?>">
      </div>
<? 
if(!empty($rs->cc_user_barcode) && !empty($cc_no)) { ?>
      <div id="user_barcode" >
         <center>
            <div id="userbarcode" class="barcodeTarget" style="margin-top:15px"></div>
         </center>
      </div>
<? } ?>      
      <div id="contents">
         <div id="pre_title">
            <h3><?=$rs->cc_title?></h3>
            <p>사용기간 : <?=$rs->cc_end_date?> 까지</p>
         </div>
         <button class="friend_add" style="cursor:pointer;" onclick="location.href='<?='http://plus-talk.kakao.com/plus/home/'.$rs->spf_friend ?>'">친구 추가</button>
<? if(empty($cc_no)) { ?>
                   <span id="txt_btn">꽝! 다음기회에...</span> 
<?
            } else {
?>
                   <span id="txt_btn" style="cursor:pointer;" onclick="javascript:approval();">직원확인</span>
                   <p><?=$rs->cc_memo?></p>
                 
                  <script type="text/javascript">
                    function approval() {
                        $.ajax({
				       	  url: "/couponview/approval",
					      type: "POST",
						  data:{ '<?=$this->security->get_csrf_token_name()?>': "<?=$this->security->get_csrf_hash()?>",
                                 idx : "<?=$idx?>", 
                                 phn : "<?=$phn?>",
                                 appr: "YES"},
					      success: function (json) {
						     msg = json["result"];
                             alert(msg);
                             location.reload();
                          }
                       });
                    }
                </script>      
            
<?            } ?>
      </div>
   </body>
</html> 