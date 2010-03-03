<div class="sidebarBox">
<div id="owner_block_submenu"><ul>
<?php
	if(isloggedin()){
		echo "<li><a href=\"{$vars['url']}pg/groups/member/{$_SESSION['user']->username}\">". elgg_echo('groups:yours') ."</a></li>";
		echo "<li><a href=\"{$vars['url']}pg/groups/invitations/{$_SESSION['user']->username}\">". elgg_echo('groups:invitations') ."</a></li>";
		echo "<li><a href=\"{$vars['url']}pg/groups/new/\">". elgg_echo('groups:new') ."</a></li>";
	}
?>
</ul></div></div>