<?php

$users_online = get_online_users();
	
?>
<div class="elgg-module elgg-inline-module">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h3>
	</div>
	<div class="elgg-body">
		<?php echo $users_online; ?>
	</div>
</div>
