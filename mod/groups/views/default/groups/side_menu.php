<div class="user_groups_link">
<?php
	if(isloggedin()){
		echo "<p><a href=\"{$vars['url']}pg/groups/member/{$_SESSION['user']->username}\">". elgg_echo('groups:yours') ."</a></p>";
		echo "<p><a href=\"{$vars['url']}pg/groups/new/\">". elgg_echo('groups:new') ."</a></p>";
	}
?>
</div>