<div class="send_menu">
	<ul>
        <? if($this->member->item('mem_send_smart_yn')=="Y"){ ?>
            <li><a href="/dhnbiz/sender/send/talk_img_adv_v2_2"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/talk_img_adv") == true) ? " class='sm_on'" : ""?>><i class="xi-new-o"></i> 이미지 알림톡</a></li>
    		<li><a href="/dhnbiz/sender/send/talk_adv_v2_2"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/talk_adv") == true) ? " class='sm_on'" : ""?>><i class="xi-comment-o"></i> 텍스트 알림톡</a></li>
        <? }else{?>
            <?// if($this->member->item('mem_level') >= 100){ //최고관리자 이상 권한만 보임 ?>
                <li><a href="/dhnbiz/sender/send/talk_img_adv_v4"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/talk_img_adv") == true) ? " class='sm_on'" : ""?>><i class="xi-new-o"></i> 이미지 알림톡</a></li>
        		<li><a href="/dhnbiz/sender/send/talk_adv_v4"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/talk_adv") == true) ? " class='sm_on'" : ""?>><i class="xi-comment-o"></i> 텍스트 알림톡</a></li>
            <?// }else{ ?>

            <?// } ?>

        <? } ?>

		<li><a href="/dhnbiz/sender/send/friend_v3"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/friend") == true && strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/friend_wide") == false) ? " class='sm_on'" : ""?>><i class="xi-message-o"></i> 친구톡</a></li>
		<li><a href="/dhnbiz/sender/send/lms"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/lms") == true) ? " class='sm_on'" : ""?>><i class="xi-mail-o"></i> 문자메시지</a></li>
        <? if($this->member->item('mem_rcs_yn')=="Y"){ //최고관리자 권한만 보임 ?>
            <li><a href="/dhnbiz/sender/send/rcs_v1"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/rcs") == true) ? " class='sm_on'" : ""?>><i class="xi-speech-o"></i> RCS메시지</a></li>
        <? } ?>
        <? if($this->member->item('mem_id')=="2" || $this->member->item('mem_id')=="3"){ //최고관리자 권한만 보임 ?>
            <li><a href="/dhnbiz/sender/send/personal_v1"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/personal_v1") == true) ? " class='sm_on'" : ""?>><i class="xi-speech-o"></i> 개인화메시지</a></li>
        <? } ?>
		<!-- <li><a href="/biz/coupon"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/coupon") == true OR strpos("/".$_SERVER['REQUEST_URI'], "/biz/sender/send/coupon") == true) ? " class='sm_on'" : ""?>><i class="xi-star-o"></i> 타겟쿠폰(알림톡)</a></li>
		<li><a href="/biz/sender/send/agree"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/sender/send/agree") == true) ? " class='sm_on'" : ""?>><i class="xi-lock-o"></i> 개인정보동의(알림톡)</a></li> -->
		<!--<li><a href="/dhnbiz/sender/history"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/history") == true) ? " class='sm_on'" : ""?>><i class="xi-list-dot"></i> 발신목록</a></li>
		<li><a href="/dhnbiz/sender/send/friend_wide"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/sender/send/friend_wide") == true) ? " class='sm_on'" : ""?>><i class="xi-message"></i>친구톡 와이드형</a></li>-->
	</ul>
</div>
