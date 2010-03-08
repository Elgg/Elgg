<div class="submenu page_navigation"><ul>
<?php
	if(isloggedin()){
		echo "<li><a href=\"{$vars['url']}pg/groups/member/{$_SESSION['user']->username}\">". elgg_echo('groups:yours') ."</a></li>";
		echo "<li><a href=\"{$vars['url']}pg/groups/invitations/{$_SESSION['user']->username}\">". elgg_echo('groups:invitations') ."</a></li>";
	}
?>
</ul></div>