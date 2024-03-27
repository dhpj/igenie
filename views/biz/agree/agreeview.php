<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<style>
	body{padding: 0; margin: 0;}
	img{width:100%; padding: 0; margin: 0;}
	button{padding:10px 20px; background-color: rgb(255,223,44); font-size: 20px; border:none; border-radius: 5px; margin:20px 0;}
	p{background-color: #f1f1f1; padding:10px 0; margin:20px 0;}
</style>
</head>
<body>
<? if($idx !== "" and $phn != ""){ ?>
	<img src="<?=$rs->agi_imgpath?>">
	<? if($data->agd_state != "2"){ //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) ?>
	<button onclick="approval('2');">동의하기</button>
	<script type="text/javascript">
		//동의하기
		function approval(st){
			$.ajax({
				url: "/agreeview/approval",
				type: "POST",
				data:{
					  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
					, idx : "<?=$idx?>" //개인정보동의번호
					, phn : "<?=$phn?>" //연락처
					, state : st //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
				},
				success: function (json) {
					msg = json["result"];
					alert(msg);
					location.reload();
				}
			});
		}
	</script>
	<? }else if($data->agd_state == "2"){ //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) ?>
	<p>
		동의하였습니다.
	</p>
	<? } ?>
<? } ?>
</body>
</html>
