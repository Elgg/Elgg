<?php
// newest users
$users = elgg_list_entities(array(
	'type' => 'user',
	'subtype'=> null,
	'full_view' => FALSE
));

?>

<div class="admin_settings members-list users_online">
	<h3><?php echo elgg_echo('admin:users:newest'); ?></h3>
	<?php echo $users; ?>
</div>
