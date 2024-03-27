<div class="send_menu">
	<ul>
		<li><a href="/manual/send"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/manual/send") == true) ? " class='sm_on'" : ""?>><i class="xi-comment-o"></i> 알림톡 발송</a></li>
		<li><a href="/manual/friend"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/manual/friend") == true) ? " class='sm_on'" : ""?>><i class="xi-message-o"></i> 친구톡 발송</a></li>
		<li><a href="/manual/lms"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/manual/lms") == true) ? " class='sm_on'" : ""?>><i class="xi-mail-o"></i> 문자메시지 발송</a></li>
	</ul>
</div>
