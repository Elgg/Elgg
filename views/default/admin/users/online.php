<?php

$users_online = get_online_users();
	
?>
<div class="admin_settings members-list users_online">
	<h3><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h3>
	<?php echo $users_online; ?>
</div>
